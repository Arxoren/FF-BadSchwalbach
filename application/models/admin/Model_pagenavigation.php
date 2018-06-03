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
			
			$sqlstr = 'SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$menue_array['akt_wehr'].'" AND nav_group="'.$group['name'].'" AND subcategory="0" ORDER BY sort ASC';
			
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

			$sqlstr = 'SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$navWehrID.'" AND subcategory="'.$items[$i]['navID'].'" ORDER BY sort ASC';
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
			$sqlstr = 'SELECT * FROM ffwbs_navigation_zuordnung WHERE navID="'.$_GET['id'].'"';
			$query = $this->db->query($sqlstr);
			$var['navzuordnung'] = $query->row_array();
			$var['page_headline']='Navigationspunkt bearbeiten';
		} else {
			$var['navzuordnung']['navID'] = "";
			$var['page_headline']='Neuen Navigationspunkt erstellen';
		}

		$sqlstr = 'SELECT * FROM ffwbs_module_list WHERE installed="1" AND auto_navcategory!="" ORDER BY modulname ASC';
		$query = $this->db->query($sqlstr);
		$var['autosubcat'] = $query->result_array();

		$sqlstr = 'SELECT * FROM ffwbs_wehren WHERE online="1" ORDER BY sort ASC';
		$query = $this->db->query($sqlstr);
		$var['wehren'] = $query->result_array();

		$sqlstr = 'SELECT * FROM ffwbs_navigation_groups ORDER BY navigationgroupID ASC';
		$query = $this->db->query($sqlstr);
		$var['navgroups'] = $query->result_array();

		foreach($var['navgroups'] as $group) {		
		
			//$sqlstr = 'SELECT m.* FROM ffwbs_navigation m INNER JOIN ffwbs_navigation_zuordnung z ON m.navID=z.navID AND z.wehrID="'.$navWehrID.'" WHERE m.nav_group="'.$navgroup.'" AND m.language="'.$GLOBALS['language'].'" AND m.online="1" AND m.subcategory="0" ORDER BY sort ASC';
			$sqlstr = 'SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$var['akt_wehr'].'" AND nav_group="'.$group['name'].'" AND subcategory="0" ORDER BY sort ASC';
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
		$path = '';
		
		if(isset($_POST['urlpath']) && $_POST['urlpath']==1) {
			$path=$_POST['url'];
			$auto_subcategory = '_blank';
			$_POST["ziel"] = '';
		}
		if(isset($_POST['autosubcat']) && $_POST['autosubcat']!="") {
			$auto_subcategory = $_POST['autosubcat_value'];
		} else {
			$auto_subcategory = '';
		}

		foreach($_POST["wehren"] as $wehrID) {
			// ----
			if($_POST['editID']!="") {
				// Alte Werte zum Vergleich ziehen
				$query = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE navID="'.$_POST["editID"].'"');
				echo 'SELECT * FROM ffwbs_navigation_zuordnung WHERE navID="'.$_POST["editID"].'"<br>';
				$old = $query->row_array();
				$sort = $old['sort'];
				$nav_group = $old['nav_group'];
				echo 'A: '.$sort.'<br>';
				
				// Sortierung nur ändern wenn sich die Subcategory ändert
				if($_POST['submenue']=='navgroup') {
					if($_POST["navgroup"]!=$old['nav_group']) {	
						$query = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$wehrID.'" AND nav_group="'.$_POST["navgroup"].'" AND subcategory="'.$_POST['subcategory'].'"');
						$sort = $query->num_rows();
						$nav_group = $this->pagenavigation_getNavGroup($query);
						echo "maincategory >>><br>";
					} 
				} elseif($_POST["submenue"]=='subcategory') {
					if($_POST["subcategory"]!=$old['subcategory']) {
						$query = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$wehrID.'" AND subcategory="'.$_POST['subcategory'].'"');
						$sort = $query->num_rows();
						$nav_group = $this->pagenavigation_getNavGroup($query);
						echo "subcategory >>><br>";
					}
				}

				echo 'B: '.$sort.'<br>';
			} else {
				if($_POST['submenue']=='navgroup') {	
					$nav_group = $_POST["navgroup"];
					$query = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$wehrID.'" AND nav_group="'.$_POST["navgroup"].'" AND subcategory="'.$_POST['subcategory'].'"');
				} elseif($_POST["submenue"]=='subcategory') {
					$query = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$wehrID.'" AND subcategory="'.$_POST['subcategory'].'"');
					$nav_group = $this->pagenavigation_getNavGroup($query);
				}
				$sort = $query->num_rows();
			}

			$data_navigation = array(
			   'label' => ''.$_POST["title"].'' ,
			   'wehrID' => ''.$wehrID.'',
			   'auto_subcategories' => ''.$auto_subcategory.'' ,
			   'pagesID' => ''.$_POST["ziel"].'' ,
			   'path' => ''.$path.'' ,
			   'nav_group' => ''.$nav_group.'' ,
			   'sort' => ''.$sort.'' ,
			   'subcategory' => ''.$_POST["subcategory"].'' ,
			   'language' => $lang,
			   'online' => $_POST["online"]
			);
			
			print_r($data_navigation);

			if($_POST['editID']=="") {
				$this->db->insert('navigation_zuordnung', $data_navigation);
				$newest_navID = $this->db->insert_id();
			} else {
				$this->db->where('navID', $_POST["editID"]);
				$this->db->update('navigation_zuordnung', $data_navigation);			
			}
		}
		
		echo "SAVE!";

		//$GLOBALS['globalmessage'] = $msg;

		$var = $this->pagenavigation_liste();
		return $var;
	}

	public function pagenavigation_getNavGroup($query) {
		$new = $query->row_array();
		return $new["nav_group"]; 
	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz nicht im Frontend anzeigen
	|--------------------------------------------------------------------------
	*/
	public function pagenavigation_publish() {

		$this->db->simple_query('UPDATE ffwbs_navigation_zuordnung SET online="'.$_GET["state"].'" WHERE navID="'.$_GET["id"].'"');

	}


	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz nicht im Frontend anzeigen
	|--------------------------------------------------------------------------
	*/
	public function pagenavigation_pos() {

		$query = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE navID="'.$_GET["id"].'" AND wehrID="'.$_GET["wehrID"].'"');
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

		$query = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE nav_group="'.$item['nav_group'].'" AND wehrID="'.$_GET["wehrID"].'" AND subcategory="'.$item['subcategory'].'" ORDER BY sort ASC');
		$allitems = $query->result_array();
		$num_items = $query->num_rows();
		$pos = 0;

		if($newpos_A==$num_items) {
			$msg='error:Der Menüpunkt ist schon an der letzten Position';
		}

		if($msg=="") {
			foreach($allitems as $navitem) {
				if($navitem['sort']==$newpos_A) {
					$sql = 'UPDATE ffwbs_navigation_zuordnung SET sort="'.$newpos_B.'" WHERE navID="'.$navitem["navID"].'"';
				} else {
					if($navitem['navID']==$_GET["id"]) {
						$sql = 'UPDATE ffwbs_navigation_zuordnung SET sort="'.$newpos_A.'" WHERE navID="'.$navitem["navID"].'"';
					} else {
						$sql = 'UPDATE ffwbs_navigation_zuordnung SET sort="'.$pos.'" WHERE navID="'.$navitem["navID"].'"';
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
	| Einen Navigationspunkt löschen
	|--------------------------------------------------------------------------
	*/
	public function pagenavigation_delete() {

		$query = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE navID="'.$_GET["id"].'"');
		$item = $query->row_array();

		// Reiehnfolge Updaten
		$query = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$item['wehrID'].'" AND nav_group="'.$item['nav_group'].'" AND subcategory="'.$item['subcategory'].'" AND sort>"'.$item['sort'].'" ORDER BY sort ASC');
		$allitems = $query->result_array();
		foreach($allitems as $navitem) {
			$sql = 'UPDATE ffwbs_navigation_zuordnung SET sort="'.($navitem["sort"]-1).'" WHERE navID="'.$navitem["navID"].'"';
			$this->db->simple_query($sql);
		}

		// ------ ALLE UNTERPUNKTE LÖSCHEN
		$query = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$_GET["wehrID"].'" AND subcategory="'.$item['navID'].'"');
		$delstructure = $query->result_array();
		foreach($delstructure as $structure) {
			$this->pagenavigation_delete_structure($structure['navID'], $_GET["wehrID"]);
			$this->db->query('DELETE FROM ffwbs_navigation_zuordnung WHERE navID="'.$structure["navID"].'" AND wehrID="'.$_GET["wehrID"].'"');	
		}


		$query = $this->db->query('DELETE FROM ffwbs_navigation_zuordnung WHERE navID="'.$_GET["id"].'" AND wehrID="'.$_GET["wehrID"].'"');
		$var = basicffw_get_vereindetails($_GET["wehrID"]);

		$GLOBALS['globalmessage'] = 'success:Menüpunkt "'.$item['label'].'" wurde für die Wehr "'.$var['wehr_name'].'" entfernt.';	

	}

	private function pagenavigation_delete_structure($subcat, $wehrID) {

		$query = $this->db->query('SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$_GET["wehrID"].'" AND subcategory="'.$subcat.'"');
		$delstructure = $query->result_array();

		foreach($delstructure as $structure) {
			$this->pagenavigation_delete_structure($structure['navID'], $wehrID);
			$this->db->query('DELETE FROM ffwbs_navigation_zuordnung WHERE navID="'.$structure["navID"].'" AND wehrID="'.$_GET["wehrID"].'"');	
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Menüpunkte kopieren
	|--------------------------------------------------------------------------
	*/
	public function copy() {
		$var["page_headline"] = "Menü oder Menüpunkte kopieren";

		$sqlstr = 'SELECT * FROM ffwbs_wehren WHERE online="1" ORDER BY sort ASC';
		$query = $this->db->query($sqlstr);
		$var['wehren'] = $query->result_array();

		return $var;	
	}

	public function copysave() {
		
		// --- Löschen des aktuellen Menüs der jeweiligen Wehr
		$query = $this->db->query('DELETE FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$_POST["wehrID"].'"');

		$sqlstr = 'SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$_POST["copyby_wehrID"].'" AND subcategory="0" ORDER BY sort ASC';
		$query = $this->db->query($sqlstr);
		$orginal['nav'] = $query->result_array();

		foreach($orginal['nav'] as $navitem) {	
			$data_navigation = array(
			   'label' => ''.$navitem["label"].'' ,
			   'wehrID' => ''.$_POST["wehrID"].'',
			   'auto_subcategories' => ''.$navitem["auto_subcategories"].'' ,
			   'pagesID' => ''.$navitem["pagesID"].'' ,
			   'path' => ''.$navitem["path"].'' ,
			   'nav_group' => ''.$navitem["nav_group"].'' ,
			   'sort' => ''.$navitem["sort"].'' ,
			   'subcategory' => ''.$navitem["subcategory"].'' ,
			   'language' => $navitem["language"],
			   'online' => $navitem["online"]
			);
			$this->db->insert('navigation_zuordnung', $data_navigation);
			$newest_navID = $this->db->insert_id();

			$this->copy_save_structre($newest_navID, $navitem["navID"], $_POST["copyby_wehrID"], $_POST["wehrID"]);
		}

		$var = $this->pagenavigation_liste();
		return $var;
	}

	public function copy_save_structre($new_navID, $navID, $org_wehrID, $new_wehrID) {

		$sqlstr = 'SELECT * FROM ffwbs_navigation_zuordnung WHERE wehrID="'.$org_wehrID.'" AND subcategory="'.$navID.'" ORDER BY sort ASC';
		$query = $this->db->query($sqlstr);
		$orginal['nav'] = $query->result_array();	

		foreach($orginal['nav'] as $navitem) {	
			$data_navigation = array(
			   'label' => ''.$navitem["label"].'' ,
			   'wehrID' => ''.$new_wehrID.'',
			   'auto_subcategories' => ''.$navitem["auto_subcategories"].'' ,
			   'pagesID' => ''.$navitem["pagesID"].'' ,
			   'path' => ''.$navitem["path"].'' ,
			   'nav_group' => ''.$navitem["nav_group"].'' ,
			   'sort' => ''.$navitem["sort"].'' ,
			   'subcategory' => ''.$new_navID.'' ,
			   'language' => $navitem["language"],
			   'online' => $navitem["online"]
			);

			$this->db->insert('navigation_zuordnung', $data_navigation);
			$newest_navID = $this->db->insert_id();			
			
			$this->copy_save_structre($newest_navID, $navitem["navID"], $org_wehrID, $new_wehrID);
		}

	}

}

?>