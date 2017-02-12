<?php
class model_pagenavigation extends CI_Model {

	/*
	|--------------------------------------------------------------------------
	| einsatzliste laden
	|--------------------------------------------------------------------------
	*/
	public function pagenavigation_liste() {
		
		if(!isset($_POST['wehrID'])) {
			if(!isset($_GET['wehrID'])) {
				$menue_array['akt_wehr'] = 0;
			} else {
				$menue_array['akt_wehr'] = $_GET['wehrID'];
			}
		} else {
			$menue_array['akt_wehr'] = $_POST['wehrID'];
		}


		$sqlstr = 'SELECT * FROM ffwbs_navigation_groups ORDER BY navigationgroupID ASC';
		$query = $this->db->query($sqlstr);
		$menue_array['navgroup'] = $query->result_array();
		$menue_arrayitems = '';

		foreach($menue_array['navgroup'] as $group) {		
		
			//$sqlstr = 'SELECT m.* FROM ffwbs_navigation m INNER JOIN ffwbs_navigation_zuordnung z ON m.navID=z.navID AND z.wehrID="'.$navWehrID.'" WHERE m.nav_group="'.$navgroup.'" AND m.language="'.$GLOBALS['language'].'" AND m.online="1" AND m.subcategory="0" ORDER BY sort ASC';
			$sqlstr = 'SELECT m.* FROM ffwbs_navigation m INNER JOIN ffwbs_navigation_zuordnung z ON m.navID=z.navID AND z.wehrID="'.$menue_array['akt_wehr'].'" WHERE m.nav_group="'.$group['name'].'" AND m.subcategory="0" ORDER BY sort ASC';
			$query = $this->db->query($sqlstr);
			$menue = $query->result_array();

			$menue_arrayitems = $this->get_submenue($menue, 0, $menue_arrayitems, $menue_array['akt_wehr']); 
		}
		$menue_array['menueitems']=$menue_arrayitems;

		$query = $this->db->query('SELECT * FROM ffwbs_wehren ORDER BY sort ASC');
		$menue_array['filter_wehren'] = $query->result_array();

		return $menue_array;

	}

	private function get_submenue($items, $var, $menue_array, $navWehrID) {

		$var++;

		for($i=0; $i<count($items); $i++) {
				
			$items[$i]['level'] = $var;
			$menue_array[] = $items[$i];

			// Automatische Menüpunkte aus einer DB laden (z.B. Fahrzeuge oder News)
			if($items[$i]['auto_subcategories']!="") {

				if($items[$i]['auto_subcategories']=="_blank") {

				} else {
					/*
					$CI =& get_instance();
					$CI->load->model('site/model_'.$items[$i]['auto_subcategories'].'');
					$model = 'model_'.$items[$i]['auto_subcategories'];
					$auto_items = $CI->$model->get_menuitems($i);				

					foreach($auto_items as $menueitem) {
					*/
						$autoitems[$i]['level'] = $var+1;
						$autoitems[$i]['label'] = 'AUTO: '.$items[$i]['auto_subcategories'];
						$autoitems[$i]['subcategory'] = $items[$i]['subcategory'];
						$autoitems[$i]['navID'] = 'x';
						$autoitems[$i]['sort'] = 0;
						$autoitems[$i]['nav_group'] = $items[$i]['nav_group'];
						$autoitems[$i]['online'] = $items[$i]['online'];

						$menue_array[] = $autoitems[$i]; 
					/*
					}
					*/
					//$menue_array[] = 'AUTO::'.$items[$i]['auto_subcategories'];
				}
			}

			//$sqlstr = 'SELECT * FROM ffwbs_navigation WHERE language="'.$GLOBALS['language'].'" AND online="1" AND subcategory="'.$items[$i]['navID'].'"';
			$sqlstr = 'SELECT m.* FROM ffwbs_navigation m INNER JOIN ffwbs_navigation_zuordnung z ON m.navID=z.navID AND z.wehrID="'.$navWehrID.'" WHERE m.subcategory="'.$items[$i]['navID'].'" ORDER BY sort ASC';
			$query = $this->db->query($sqlstr);
			$menue = $query->result_array();
			if($query->num_rows()!=0) {
				$menue_array = $this->get_submenue($menue, $var, $menue_array, $navWehrID);
			}
		}
		return $menue_array;
	}


	public function pagenavigation_editor() {
		
		$var['akt_wehr'] = $_GET['wehrID'];
		$var['structure'] = '';

		if($_GET['id']!="new") {
			$sqlstr = 'SELECT * FROM ffwbs_navigation WHERE navID="'.$_GET['id'].'"';
			$query = $this->db->query($sqlstr);
			$var['navdetails'] = $query->row_array();
			$var['page_headline']='Navigationspunkt bearbeiten';

			$sqlstr = 'SELECT * FROM ffwbs_navigation_zuordnung WHERE navID="'.$_GET['id'].'"';
			$query = $this->db->query($sqlstr);
			$var['navzuordnung'] = $query->result_array();
		} else {
			$var['navdetails']['navID'] = "";
			$var['page_headline']='Neuen Navigationspunkt erstellen';
		}

		$sqlstr = 'SELECT * FROM ffwbs_wehren WHERE online="1" ORDER BY sort ASC';
		$query = $this->db->query($sqlstr);
		$var['wehren'] = $query->result_array();

		$sqlstr = 'SELECT * FROM ffwbs_navigation_groups ORDER BY navigationgroupID ASC';
		$query = $this->db->query($sqlstr);
		$var['navgroups'] = $query->result_array();

		foreach($var['navgroups'] as $group) {		
		
			//$sqlstr = 'SELECT m.* FROM ffwbs_navigation m INNER JOIN ffwbs_navigation_zuordnung z ON m.navID=z.navID AND z.wehrID="'.$navWehrID.'" WHERE m.nav_group="'.$navgroup.'" AND m.language="'.$GLOBALS['language'].'" AND m.online="1" AND m.subcategory="0" ORDER BY sort ASC';
			$sqlstr = 'SELECT m.* FROM ffwbs_navigation m INNER JOIN ffwbs_navigation_zuordnung z ON m.navID=z.navID AND z.wehrID="'.$var['akt_wehr'].'" WHERE m.nav_group="'.$group['name'].'" AND m.subcategory="0" ORDER BY sort ASC';
			$query = $this->db->query($sqlstr);
			$menue = $query->result_array();

			$var['structure'] = $this->get_submenue($menue, 0, $var['structure'], $var['akt_wehr']); 
		} 

		$sqlstr = 'SELECT * FROM ffwbs_pages ORDER BY page_name ASC';
		$query = $this->db->query($sqlstr);
		$var['pages'] = $query->result_array();

		return $var;
	}


	/*
	|--------------------------------------------------------------------------
	| Einsatz speichern
	|--------------------------------------------------------------------------
	*/
	public function pagenavigation_save() {

		$lang = 'de';
		$auto_subcategory = '';
		$path = '';
		
		if(isset($_POST['urlpath']) && $_POST['urlpath']==1) {
			$path=$_POST['url'];
			$auto_subcategory = '_blank';
			$_POST["ziel"] = '';
		}

		// ----
		if($_POST['submenue']=='navgroup') {
			$query = $this->db->query('SELECT * FROM ffwbs_navigation WHERE nav_group="'.$_POST["navgroup"].'" AND subcategory="0"');
			$sort = $query->num_rows();
			$nav_group = $_POST["navgroup"];
		} else {
			$query = $this->db->query('SELECT * FROM ffwbs_navigation WHERE subcategory="'.$_POST['subcategory'].'"');
			$sort = $query->num_rows();

			$query = $this->db->query('SELECT * FROM ffwbs_navigation WHERE navID="'.$_POST['subcategory'].'"');
			$item = $query->row_array();
			$nav_group = $item['nav_group'];
		}

		$data_navigation = array(
		   'label' => ''.$_POST["title"].'' ,
		   'auto_subcategories' => ''.$auto_subcategory.'' ,
		   'pagesID' => ''.$_POST["ziel"].'' ,
		   'path' => ''.$path.'' ,
		   'nav_group' => ''.$nav_group.'' ,
		   'sort' => ''.$sort.'' ,
		   'subcategory' => ''.$_POST["subcategory"].'' ,
		   'language' => $lang,
		   'online' => $_POST["online"]
		);
		
		if($_POST['editID']=="") {
			$this->db->insert('navigation', $data_navigation);
			$newest_navID = $this->db->insert_id();

			// --- Zuordnung der Wehren eintragen
			foreach($_POST['wehren'] as $wehr) {
				$data_navigation_zuordnung = array(
				   'navID' => ''.$newest_navID.'' ,
				   'wehrID' => ''.$wehr.''
				);
				$this->db->insert('navigation_zuordnung', $data_navigation_zuordnung);
			}
			

		} else {
			$this->db->where('navID', $_POST["editID"]);
			$this->db->update('navigation', $data_navigation);

			// --- Nicht mehr benötigte Zuordnungen löschen
			$query_z = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE navID="'.$_POST["editID"].'"');
			$test_z = $query_z->result_array();
			
			foreach($test_z as $zuordnung) {

				if(!in_array($zuordnung["wehrID"], $test_z)) {
					$query = $this->db->query('DELETE FROM ffwbs_navigation_zuordnung WHERE navzuordnungID="'.$zuordnung["navzuordnungID"].'"');
				}
			}	

			// --- Neue Zuordnung der Wehren eintragen
			foreach($_POST['wehren'] as $wehr) {
				
				$query_z = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE navID="'.$_POST["editID"].'" AND wehrID="'.$wehr.'"');
				$test_z = $query_z->num_rows();

				if($test_z==0) {
					$data_navigation_zuordnung = array(
					   'navID' => ''.$_POST["editID"].'' ,
					   'wehrID' => ''.$wehr.''
					);
					$this->db->insert('navigation_zuordnung', $data_navigation_zuordnung);
				}
			}			
		}
		echo "SAVE!";

		//$GLOBALS['globalmessage'] = $msg;

		$var = $this->pagenavigation_liste();
		return $var;
	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz nicht im Frontend anzeigen
	|--------------------------------------------------------------------------
	*/
	public function pagenavigation_publish() {

		$this->db->simple_query('UPDATE ffwbs_navigation SET online="'.$_GET["state"].'" WHERE navID="'.$_GET["id"].'"');

	}


	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz nicht im Frontend anzeigen
	|--------------------------------------------------------------------------
	*/
	public function pagenavigation_pos() {

		$query = $this->db->query('SELECT * FROM ffwbs_navigation WHERE navID="'.$_GET["id"].'"');
		$item = $query->row_array();
		$msg= '';

		// Neue Position des geklickten Elementes bestimmen
		$newpos_B = $item['sort'];
		if($_GET['direction']=="up") {
			if($item['sort']>0) {
				$newpos_A = $item['sort']-1;
				$wohin = "nach oben";
			} else {
				$newpos_A = 0;
				$msg='error:Der Menüpunkt ist schon an der ersten Position';
			}
		}
		if($_GET['direction']=="down") {
			$newpos_A = $item['sort']+1;
			$wohin = "nach unten";
		}		

		$query = $this->db->query('SELECT * FROM ffwbs_navigation WHERE nav_group="'.$item['nav_group'].'" AND subcategory="'.$item['subcategory'].'" ORDER BY sort ASC');
		$allitems = $query->result_array();
		$num_items = $query->num_rows();
		$pos = 0;

		if($newpos_A>$num_items) {
			$msg='error:Der Menüpunkt ist schon an der letzten Position';
		}

		if($msg=="") {
			foreach($allitems as $navitem) {
				if($navitem['sort']==$newpos_A) {
					$sql = 'UPDATE ffwbs_navigation SET sort="'.$newpos_B.'" WHERE navID="'.$navitem["navID"].'"';
				} else {
					if($navitem['navID']==$_GET["id"]) {
						$sql = 'UPDATE ffwbs_navigation SET sort="'.$newpos_A.'" WHERE navID="'.$navitem["navID"].'"';
					} else {
						$sql = 'UPDATE ffwbs_navigation SET sort="'.$pos.'" WHERE navID="'.$navitem["navID"].'"';
					}
				}
				$this->db->simple_query($sql);
				$pos++;
			}
			$msg='success:Der Menüpunkt "'.$item['label'].'" wurde '.$wohin.' verschoben';
		}

		$GLOBALS['globalmessage'] = $msg;
	}


	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz löschen
	|--------------------------------------------------------------------------
	*/
	public function pagenavigation_delete() {

		$query = $this->db->query('SELECT * FROM ffwbs_navigation WHERE navID="'.$_GET["id"].'"');
		$item = $query->row_array();

		if(isset($_GET['func'])) {
			$query = $this->db->query('DELETE FROM ffwbs_navigation WHERE navID="'.$_GET["id"].'"');
			$query = $this->db->query('DELETE FROM ffwbs_navigation_zuordnung WHERE navID="'.$_GET["id"].'"');

			$query = $this->db->query('SELECT * FROM ffwbs_navigation WHERE subcategory="'.$item['subcategory'].'" ORDER BY sort ASC');
			$newsort = $query->result_array();
			$pos = 0;
			foreach($newsort as $sortitem) {
				$this->db->simple_query('UPDATE ffwbs_navigation SET sort="'.$pos.'" WHERE navID="'.$sortitem["navID"].'"');
				$pos++;
			}

			$GLOBALS['globalmessage'] = 'success:Menüpunkt "'.$item['label'].'" wurde komplett gelöscht';
		} else {
			$query = $this->db->query('DELETE FROM ffwbs_navigation_zuordnung WHERE navID="'.$_GET["id"].'" AND wehrID="'.$_GET["wehrID"].'"');
			$var = basicffw_get_vereindetails($_GET["wehrID"]);
			$GLOBALS['globalmessage'] = 'success:Menüpunkt "'.$item['label'].'" wurde für die Wehr "'.$var['wehr_name'].'" entfernt.';			
		}

	}
	

}

?>