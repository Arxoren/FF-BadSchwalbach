<?php
class model_dashboard extends CI_Model {

	/*
	|--------------------------------------------------------------------------
	| Globale Variablen und Settings aus der Datenbank laden
	|--------------------------------------------------------------------------
	*/
	public function get_dashboard() {
		
		if($GLOBALS['page_status']=="ONLINE") {
			$var['pagestatus'] = '<span class="admin_tag_online">LIVE</span>';
		} else {
			$var['pagestatus'] = '<span class="admin_tag_offline">OFFLINE</span>';
		}

		//--- Quicklinks erzeugen (NOCH STATISCH!!!)
		$quicknav_array = array("3", "1", "5", "6"); // mit db einstellungen verknüpfen
		$quicknavigation = array();

		foreach($quicknav_array as $quicknavi) {	
			$query = $this->db->query('SELECT * FROM ffwbs_admin_menue WHERE adminMenueID="'.$quicknavi.'"');
			array_push($quicknavigation, $query->row_array());
		}
		$var['quicknavi'] = $quicknavigation;

		//--- Versionslog abrufen
		$query = $this->db->query('SELECT * FROM ffwbs_admin_log_version WHERE version="'.$GLOBALS['software_version'].'" ORDER BY version DESC, type DESC');
		$var['version_log'] = $query->result_array();

		//--- Versionslog abrufen
		$query = $this->db->query('SELECT * FROM ffwbs_admin_log_action WHERE level="2" ORDER BY datum DESC LIMIT 10');
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

		$var['headline'] = 'Dashboard';
		return $var;
		
	}


	/*
	|--------------------------------------------------------------------------
	| Function Dashboards
	|--------------------------------------------------------------------------
	*/
	public function get_function_dashboard() {
		
		$query = $this->db->query('SELECT * FROM ffwbs_admin_menue WHERE main="'.$_GET["sort"].'" ORDER BY sort ASC');
		$var['dashboard_functionlist'] = $query->result_array();

		switch($_GET['sort']) {
			case "content": $var['headline'] = "Content-Verwaltung"; break;
			case "media": $var['headline'] = "Media-Verwaltung"; break;
			case "module": $var['headline'] = "Modul-Verwaltung"; break;
			case "config": $var['headline'] = "Konfiguration"; break;
		}
		
		return $var;
		
	}

}

?>