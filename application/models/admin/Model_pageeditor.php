<?php
class model_pageeditor extends CI_Model {


	/*
	|--------------------------------------------------------------------------
	| einloggen = Verifiziert den User und loggt ihn ein
	|--------------------------------------------------------------------------
	*/
	public function showlist() {

		$query = $this->db->query('SELECT * FROM ffwbs_pages WHERE subpage="0"');
		$pages = $query->result_array();
		$page_array = array();

		foreach($pages as $page) {
			$page['level'] = 0;
			array_push($page_array, $page);
			
			if($page['expected_var']!=0) {
				//$page_array = $this->get_autopages($page, $page_array);
			}
			
			$page_array = $this->get_subpage($page["pagesID"], $page_array, 0);
		}

		$pagedata['pages'] = $page_array;
		$pagedata['headline'] = "Website-Struktur";
		return $pagedata;

	}

	function get_subpage($id, $page_array, $level) {

		$level++;
		$query = $this->db->query('SELECT * FROM ffwbs_pages WHERE subpage="'.$id.'"');
		
		if ($this->db->affected_rows()!=0) {	
			$pages = $query->result_array();
			
			foreach($pages as $page) {	
				$page['level'] = $level;
				array_push($page_array, $page);
				$page_array = $this->get_subpage($page["pagesID"], $page_array, $level);

			}
		}
		
		return $page_array;

	}

	function get_autopages($pagedata, $page_array) {
		
		$CI =& get_instance();
		$model = 'model_'.$pagedata['model'];
		$CI->load->model('site/'.$model.'');
		$auto_items = $CI->$model->get_menuitems(0);	

		foreach($auto_items as $menueitem) {

			$autoitems['pagesID'] = 3; 
			$autoitems['page_name'] = $menueitem['name'];
			$autoitems['pagetype'] = 'autopage'; 
			$autoitems['path'] = $pagedata['path'];
			$autoitems['header'] = $pagedata['header']; 
			$autoitems['footer'] = $pagedata['footer'];
			$autoitems['model'] = $pagedata['model']; 
			$autoitems['expected_var'] = 1; 
			$autoitems['subpage'] = $pagedata['pagesID']; 
			$autoitems['protected'] = 0; 
			$autoitems['startpage'] = 0; 
			$autoitems['online'] = $menueitem['online']; 
			$autoitems['level'] = $pagedata['level']+1;

			array_push($page_array, $autoitems); 
		}

		return $page_array;
	}


	/*
	|--------------------------------------------------------------------------
	| Liste installierter Layout Module ermitteln
	|--------------------------------------------------------------------------
	*/
	public function page_get_modulelist() {

		$query = $this->db->query('SELECT * FROM ffwbs_contentmodules WHERE installed="1" AND editable="1"');
		$modulelist = $query->result_array();

		return $modulelist;

	}


	/*
	|--------------------------------------------------------------------------
	| Ermittelt die Modulparameter zum laden des gewünschten Modules in der Editor-Ansicht
	|--------------------------------------------------------------------------
	*/
	public function page_get_newmoduldata() {

		$query = $this->db->query('SELECT * FROM ffwbs_contentmodules WHERE contentmoduleID="'.$_POST["moduleID"].'"');
		$row = $query->row_array();

		$row['moduleID']=$_POST["itemnumber"];
		$row['pagemoduleID']=0;
		$row['moduleLayout']=$row['layout'];

		return $row;

	}


	/*
	|--------------------------------------------------------------------------
	| Seite speichern
	|--------------------------------------------------------------------------
	*/
	public function page_layoutsave() {

		//echo "<p style='color: #FFF;'>Reihe: ".$_POST["module_reihe"]."</p>";

		$modul_array = explode(":", $_POST["module_reihe"]);
		
		//--- Pfad ermitteln
		if($_POST["meta_parentpage"]!=0) {	
			$path = $this->get_page_name($_POST["meta_parentpage"]).'/'.basic_clear_string(str_replace(" ", "_", $_POST["page_name"]));
		} else {
			$path = basic_clear_string(str_replace(" ", "_", $_POST["page_name"]));
		}

		// --- META-Daten Speichern
		$data_metadata = array(
		   'page_name' => ''.str_replace(" ", "_", $_POST["page_name"]).'',
		   'page_description' => ''.$_POST["meta_description"].'',
		   'path' => $path,
		   'page_keywords' => ''.$_POST["meta_keywords"].'',
		   'subpage' => ''.$_POST["meta_parentpage"].''
		);
		$this->db->where('pagesID', $_POST["pagesID"]);
		$this->db->update('pages', $data_metadata);

		// --- Module Löschen die nicht mehr gebraucht werden
		$query = $this->db->query('SELECT page_moduleID FROM ffwbs_page_modules WHERE pageID="'.$_POST["pagesID"].'" AND sort > 0');
		$old_module = $query->result_array();
		$old_module_array = array();

		foreach($old_module as $pageid) {
			array_push($old_module_array , $pageid['page_moduleID']);
		}
		$modules_delete = array_diff($old_module_array, $modul_array);
		
		foreach($modules_delete as $delete_id) {
			if(substr($delete_id, 0, 1)!=0) {
				$query = $this->db->query('SELECT * FROM ffwbs_page_modules WHERE page_moduleID="'.$delete_id.'"');
				$tablecheck = $query->row_array();
				if($tablecheck['layout']=="table") {
					$query = $this->db->query('DELETE FROM ffwbs_tables WHERE moduleID="'.$delete_id.'"');
				}
				$this->page_module_delete($delete_id);
			}
		}

		// --- Reihenfolge updaten
		$i = 1;

		foreach($modul_array as $modulID) {
			
			// --- Modul-Parameter laden 
			$query = $this->db->query('SELECT * FROM ffwbs_contentmodules WHERE contentmoduleID="'.$_POST["moduleType_".$modulID].'"');
			$sontentmodule_data = $query->row_array();
			$stringname = explode("::", $sontentmodule_data['content_parameter']);

			// --- EDITORIAL INHALTE PARSEN
			if($sontentmodule_data["model"]=="editorial") {

				$content_array = explode("::", $_POST["content_".$modulID]);
				$content_string = "";
				$z = 0;
				foreach($content_array as $content) {
					
					//--- Inhalte bereinigen und Zeilenumbrüche beibehalten
					$content = strip_editor_tags($content);

					$content_string = $content_string.'['.$stringname[$z].'::'.$content.']';
					$z++;
				}
			} else {
				$content_string = $_POST["content_".$modulID];
			}
			
			// --- Reihenfolge updaten
			$sql = 'UPDATE ffwbs_page_modules SET sort="'.$i.'" WHERE page_moduleID="'.$modulID.'"';
				
			$this->db->simple_query($sql);
			$i++;

			
			if(substr($modulID, 0, 1)!=0) {

				if($sontentmodule_data["model"]=="editorial") {
					$sql = 'UPDATE ffwbs_page_modules SET module_data="'.$content_string.'" WHERE page_moduleID="'.$modulID.'"';
					//echo '<br>----<br>';
					$this->db->simple_query($sql);
				} else {
					// -- Only for test Nur "imgGALLERY" update
					if($sontentmodule_data["model"]=="image") {
						$sql = 'UPDATE ffwbs_page_modules SET module_data="'.$_POST["content_".$modulID].'" WHERE page_moduleID="'.$modulID.'"';
						$this->db->simple_query($sql);									
					}
					if($sontentmodule_data["model"]=="table") {
						$this->page_savetable($_POST["content_".$modulID], $modulID);
					}
				}

			} else {
				if($sontentmodule_data["model"]!="table") {
					$data_new_module = array(
					   'pageID' => ''.$_POST["pagesID"].'' ,
					   'contentmoduleID' => ''.$sontentmodule_data['contentmoduleID'].'' ,
					   'model_type' => ''.$sontentmodule_data['model'].'' ,
					   'model_func' => ''.$sontentmodule_data["function"].'' ,
					   'layout' => ''.$sontentmodule_data["layout"].'' ,
					   'name' => ''.$_POST["name_".$modulID].'' ,
					   'module_data' => ''.$content_string.'' ,
					   'subpage_module' => '0' ,
					   'sort' => ''.$i.'' ,
					   'online' => '1'
					);
					$this->db->insert('page_modules', $data_new_module);
				} else {
					$data_new_module = array(
					   'pageID' => ''.$_POST["pagesID"].'' ,
					   'contentmoduleID' => ''.$sontentmodule_data['contentmoduleID'].'' ,
					   'model_type' => ''.$sontentmodule_data['model'].'' ,
					   'model_func' => ''.$sontentmodule_data["function"].'' ,
					   'layout' => ''.$sontentmodule_data["layout"].'' ,
					   'name' => '' ,
					   'module_data' => '' ,
					   'subpage_module' => '0' ,
					   'sort' => ''.$i.'' ,
					   'online' => '1'
					);
					$this->db->insert('page_modules', $data_new_module);
					$newest_tabeID = $this->db->insert_id();
					
					$this->page_savetable($_POST["content_".$modulID], $newest_tabeID);
				}
				
				$i++;
			}

		}

		$log_action = 'hat die Seite "'.$_POST["pagesID"].' | '.$_POST["page_name"].'" bearbeitet.';
		basic_writelog($log_action,'pageeditor - module delete', 2);

		$GLOBALS['globalmessage'] = "success:Änderungen wurden gespeichert";

	}

	function page_savetable($content, $moduleID) {
		$content_array = explode("|", $content);

		$query = $this->db->query('SELECT * FROM ffwbs_tables WHERE moduleID="'.$moduleID.'" ORDER BY SORT ASC');
		$dbcelldata = $query->result_array();
		$dbcellcount = $query->num_rows();	
		$x=0;

		foreach($content_array as $content) {
				
			$content_data = explode("::", $content);

			if(count($content_data)==3) {
				$icon = $content_data[2];
			} else {
				$icon = "";
			}

			if($x<$dbcellcount) {
				$data_cellupdate = array(
				   'label' => ''.$content_data[0].'' ,
				   'value' => ''.$content_data[1].'',
				   'icon' => ''.$icon.''
				);
				$this->db->where('tableID', $dbcelldata[$x]['tableID']);
				$this->db->update('tables', $data_cellupdate);
			} else {
				$data_cellupdate = array(
				   'moduleID' => ''.$moduleID.'' ,
				   'sort' => ''.$x.'' ,
				   'label' => ''.$content_data[0].'' ,
				   'value' => ''.$content_data[1].'' ,
				   'icon' => ''.$icon.''
				);
				$this->db->insert('tables', $data_cellupdate);
			}
			//print_r($data_cellupdate);
			//echo'<br>';
			$x++;
		}	

		if($dbcellcount > count($content_array) ) {
			$query = $this->db->query('DELETE FROM ffwbs_tables WHERE moduleID="'.$moduleID.'" AND sort>="'.(count($content_array)).'"');
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Seitenpfad ermitteln
	|--------------------------------------------------------------------------
	*/

	public function get_page_name($page_id) {

		$query = $this->db->query('SELECT * FROM ffwbs_pages WHERE pagesID="'.$page_id.'"');
		$pathdata = $query->row_array();

		if($pathdata['subpage']!=0) {
			$path = $this->get_page_name($pathdata['subpage']).'/'.basic_clear_string(strtolower(str_replace(" ", "_", $pathdata['page_name'])));
		} else {
			$path = basic_clear_string(strtolower(str_replace(" ", "_", $pathdata['page_name'])));
		}

		return $path;

	}


	/*
	|--------------------------------------------------------------------------
	| Ein Seitenmodul löschen (AJAX Request)
	|--------------------------------------------------------------------------
	*/
	public function page_module_delete($delete_id) {

		$query = $this->db->query('SELECT * FROM ffwbs_page_modules WHERE page_moduleID="'.$delete_id.'"');
		$pagemoduldata = $query->row_array();
		$query = $this->db->query('SELECT * FROM ffwbs_pages WHERE pagesID="'.$pagemoduldata['pageID'].'"');
		$pagedata = $query->row_array();

		$query = $this->db->query('DELETE FROM ffwbs_page_modules WHERE page_moduleID="'.$delete_id.'"');
	
		$log_action = 'hat das Pagemodul "#'.$pagemoduldata['page_moduleID'].'_'.$pagemoduldata['layout'].'" auf der Seite "'.$pagedata['pagesID'].' | '.$pagedata['page_name'].'" gelöscht.';
		basic_writelog($log_action,'pageeditor - module delete', 2);
	}

	/*
	|--------------------------------------------------------------------------
	| Eine Seite nicht im Frontend anzeigen
	|--------------------------------------------------------------------------
	*/
	public function page_publish() {

		$this->db->simple_query('UPDATE ffwbs_pages SET online="'.$_GET["state"].'" WHERE pagesID="'.$_GET["id"].'"');
		
		$log_action = 'hat die Seite "#'.$_GET["id"].'" online gestellt.';
		basic_writelog($log_action,'pageeditor - page publish', 2);
	}

	/*
	|--------------------------------------------------------------------------
	| Eine Seite auf locked setzen
	|--------------------------------------------------------------------------
	*/
	public function page_lock() {

		$query = $this->db->query('UPDATE ffwbs_pages SET protected="'.$_GET["state"].'" WHERE pagesID="'.$_GET["id"].'"');
		
		$log_action = 'hat die Seite "#'.$_GET["id"].'" gesperrt.';
		basic_writelog($log_action,'pageeditor - page lock', 2);
	}

	/*
	|--------------------------------------------------------------------------
	| Eine Seite löschen
	|--------------------------------------------------------------------------
	*/
	public function page_delete() {

		$query = $this->db->query('SELECT * FROM ffwbs_pages WHERE pagesID="'.$_GET["id"].'"');
		$pagedata = $query->row_array();

		$query = $this->db->query('DELETE FROM ffwbs_page_modules WHERE pageID="'.$_GET["id"].'"');
		$query = $this->db->query('DELETE FROM ffwbs_pages WHERE pagesID="'.$_GET["id"].'"');

		$log_action = 'hat die Seite "#'.$_GET["id"].' | '.$pagedata["page_name"].'" gelöscht.';
		basic_writelog($log_action,'pageeditor - page delete', 2);

		$GLOBALS['globalmessage'] = 'success:Die Seite "'.$pagedata['page_name'].'" wurde gelöscht';

	}

	/*
	|--------------------------------------------------------------------------
	| Page-Editor Ansicht laden
	|--------------------------------------------------------------------------
	*/
	public function editor() {

		if(isset($_POST["pagesID"])) {
			$_GET["id"]=$_POST["pagesID"];
		} 

		if($_GET["id"]=="newpage") {
			$_GET["id"] = $this->page_add_new_page();
		}

		if(isset($_GET['subpage'])) {
			$subpage=$_GET['subpage'];
		} else {
			$subpage = 0;
		}

		$query = $this->db->query('SELECT * FROM ffwbs_pages ORDER BY page_name ASC');
		$pages['pagelist'] = $query->result_array();

		$query = $this->db->query('SELECT * FROM ffwbs_pages WHERE pagesID="'.$_GET["id"].'"');
		$pages['metadata'] = $query->row_array();

		$query = $this->db->query('SELECT * FROM ffwbs_page_modules WHERE pageID="'.$_GET["id"].'" AND subpage_module="'.$subpage.'" ORDER BY sort ASC');
		$pages['pagemodules'] = $query->result_array();		

		return $pages;

	}


	// Seite speichern
	// --------------------------------------------------------------------------

	public function page_add_new_page() {

		$data_new_page = array(
		   'page_name' => 'Neue Seite' ,
		   'page_description' => '' ,
		   'page_keywords' => '' ,
		   'pagetype' => 'page' ,
		   'path' => '' ,
		   'header' => 'default' ,
		   'footer' => 'default' ,
		   'model' => 'cms',
		   'expected_var' => '0',
		   'subpage' => '0',
		   'protected' => '0',
		   'startpage' => '0',
		   'online' => '0'
		);
		$this->db->insert('pages', $data_new_page);
		$newest_pageID = $this->db->insert_id();

		
		$data_new_module = array(
		   'pageID' => ''.$newest_pageID.'' ,
		   'model_type' => 'stage' ,
		   'model_func' => 'smallstage_image' ,
		   'layout' => 'stage_small' ,
		   'module_data' => '' ,
		   'subpage_module' => '0' ,
		   'sort' => '0' ,
		   'online' => '1'
		);
		$this->db->insert('page_modules', $data_new_module);

		$data_new_module = array(
		   'pageID' => ''.$newest_pageID.'' ,
		   'contentmoduleID' => '3' ,
		   'model_type' => 'editorial' ,
		   'model_func' => '' ,
		   'layout' => 'text' ,
		   'module_data' => '[text::Fügen Sie neue Module ein.]' ,
		   'subpage_module' => '0' ,
		   'sort' => '0' ,
		   'online' => '1'
		);
		$this->db->insert('page_modules', $data_new_module);

		$log_action = 'hat eine neue Seite angelegt.';
		basic_writelog($log_action,'pageeditor - add new page', 2);

		return $newest_pageID;

	}

	// Nachladen der Spezial Formulare pro Modul (AJAX-CALL)
	// --------------------------------------------------------------------------

	public function page_getspecial_form() {

		$query = $this->db->query('SELECT * FROM ffwbs_contentmodules WHERE contentmoduleID="'.$_POST["contentmoduleID"].'"');
		return $query->row_array();

	}
	public function page_getspecial_form_infodata() {

		switch($_POST["modul_type"]) {
			case "news_edit": $dbtable = "news"; break;
			default: $dbtable = "page";
		}
		if(substr($_POST["moduleID"], 0, 1)!=0) {	
			$sql = 'SELECT * FROM ffwbs_'.$dbtable.'_modules WHERE page_moduleID="'.$_POST["moduleID"].'"';
			$query = $this->db->query($sql);
			$modul_data = $query->row_array();
		} else {
			$modul_data = array(
			   'page_moduleID' => '' ,
			   'pageID' => '' ,
			   'contentmoduleID' => '' ,
			   'model_type' => '' ,
			   'model_func' => '' ,
			   'layout' => '' ,
			   'name' => '' ,
			   'module_data' => '' ,
			   'subpage_module' => '0' ,
			   'sort' => '0' ,
			   'online' => '0'
			);
		}
		return $modul_data;

	}


	
	// Modulsettings speichern
	// --------------------------------------------------------------------------

	public function page_save_module_settings() {
		if(substr($_POST["moduleID"], 0, 1)!=0) {
			$sql = 'UPDATE ffwbs_page_modules SET name="'.$_POST["name"].'" WHERE page_moduleID="'.$_POST["moduleID"].'"';
			$this->db->simple_query($sql);	
			$msg = "success:Moduleinstellunge gespeichert";
		} else {
			$msg="";
			$folder = "frontend/images_cms/gallerie/".$_POST["name"];
			if(!is_dir($folder)) {
				if (!mkdir($folder, 0777, true)) {
				    die('error:Erstellung der Verzeichnisse schlug fehl...');
				}
			}
		}

		return $msg;
	}

	
	// Modulsettings "ICON LISTE ABRUFEN"
	// --------------------------------------------------------------------------

	public function page_get_iconlist() {
		
		$folder = './frontend/images/icons';
		$iconlist = directory_map($folder, 1);

		return $iconlist;
	}

}

?>