<?php
class model_settings extends CI_Model {


	public function settings_overview() {

		$query = $this->db->query('SELECT * FROM ffwbs_globals WHERE gruppe IS NOT NULL ORDER BY gruppe ASC, name ASC');
		$var['settings'] = $query->result_array();

		if($GLOBALS['page_status']=="ONLINE") {
			$var['pagestatus'] = '<span class="admin_tag_online">LIVE</span>';
			$var['pagestatus_btntext'] = 'Wartungsmodus aktivieren';
		} else {
			$var['pagestatus'] = '<span class="admin_tag_offline">OFFLINE</span>';
			$var['pagestatus_btntext'] = 'Wartungsmodus deaktivieren';
		}

		$var['headline'] = 'Settings';
		return $var;
	}

	public function editor() {

		$query = $this->db->query('SELECT * FROM ffwbs_globals WHERE gruppe="'.$_GET['group'].'" ORDER BY name ASC');
		$var['settings'] = $query->result_array();

		$var['headline'] = 'Einstellungen "'.$_GET['group'].'"';
		return $var;
	}

	public function settings_save() {

		$query = $this->db->query('SELECT * FROM ffwbs_globals WHERE gruppe="'.$_POST['group'].'" ORDER BY name ASC');
		$setting_array = $query->result_array();

		$data_settings = array();

		foreach($setting_array as $settings) {
			$data_settings['value']=''.$_POST["newval_".$settings["name"]].'';
			$this->db->where('name', $settings["name"]);
			$this->db->update('globals', $data_settings);
		}

		$log_action = 'hat die Globalen Variablen der Gruppe "'.$_POST['group'].'" bearbeitet.';
		basic_writelog($log_action,'settings - save', 2);
		
		$GLOBALS['globalmessage'] = 'success:Die Einstellungen "'.$_POST['group'].'" wurden gespeichert';
	}

	/*
	|--------------------------------------------------------------------------
	| Globalen Wartungsmodus umswitchen
	|--------------------------------------------------------------------------
	*/
	public function settings_change_pagestatus() {

		if($GLOBALS["page_status"]=="OFFLINE") {
			$GLOBALS["page_status"] = $status = 'ONLINE';
			$msg = 'success:Der Wartungsmodus wurde ausgeschaltet';
		} else {
			$GLOBALS["page_status"] = $status = 'OFFLINE';
			$msg = 'success:Der Wartungsmodus wurde eingeschaltet';
		}
		$this->db->simple_query("UPDATE ffwbs_globals SET value='".$status."' WHERE name='page_status'");
	
		$log_action = 'hat den Wartungsmodus: "'.$status.'" eingeschaltet.';
		basic_writelog($log_action,'settings - pagestatus', 2);
		
		$GLOBALS['globalmessage'] = $msg;

	}


	/*
	|--------------------------------------------------------------------------
	| ADMIN-LOG anzeigen
	|--------------------------------------------------------------------------
	*/
	public function settings_adminlog() {

		//--- Versionslog abrufen
		$query = $this->db->query('SELECT * FROM ffwbs_admin_log_action ORDER BY datum DESC LIMIT 100');
		$var['log'] = $query->result_array();

		$query = $this->db->query('SELECT * FROM ffwbs_admin_user');
		$adminuser = $query->result_array();
		foreach($adminuser as $user) {
			$adminuser_name[$user['userID']] = $user['vorname']; 
		}

		for($i=0; $i<count($var['log']); $i++) {
			if(isset($adminuser_name[$var['log'][$i]['userID']])) {
				$var['log'][$i]['userID'] = $adminuser_name[$var['log'][$i]['userID']];
			} else {
				$var['log'][$i]['userID'] = "*";
			}
		}
		return $var;
	}

}

?>