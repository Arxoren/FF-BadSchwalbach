<?php
class model_adminnews extends CI_Model {

	/*
	|--------------------------------------------------------------------------
	| einsatzliste laden
	|--------------------------------------------------------------------------
	*/
	public function news_liste() {
		
		if(!isset($_POST["filter_wehren"]) && isset($_GET["sort"])) {
			if($_GET["sort"]!=0) {	
				$_POST["filter_wehren"] = $_GET["sort"];
			}
		}

		if(!isset($_POST["filter_wehren"]) || $_POST["filter_wehren"]=="alle") {
			$sql = 'SELECT * FROM ffwbs_news ORDER BY date DESC';
			$var['aktfilter_wehren'] = 0;
		} else {
			$sql = 'SELECT * FROM ffwbs_news WHERE wehrID="'.$_POST["filter_wehren"].'" ORDER BY date DESC';
			$var['aktfilter_wehren'] = $_POST["filter_wehren"];
		}

		$query = $this->db->query($sql);
		$var['news'] = $query->result_array();

		//--- Wehren Filter befüllen
		$query = $this->db->query('SELECT * FROM ffwbs_wehren ORDER BY sort ASC');
		$var['filter_wehren'] = $query->result_array();

		//--- Page Headline festlegen
		$var['page_headline'] = "Neuigkeiten bearbeiten";
		$var['page_btn_addnew'] = "Eine neue News anlegen";
		
		return $var;
	}

	public function editor() {
		
		if(isset($_POST["newsID"])) {
			$_GET["newsID"]=$_POST["newsID"];
		} 

		if($_GET["newsID"]=="newnews") {
			$_GET["newsID"] = $this->news_add_new_page();
		}

		$query = $this->db->query('SELECT * FROM ffwbs_news WHERE newsID="'.$_GET["newsID"].'"');
		$pages['newsdata'] = $query->row_array();

		$query = $this->db->query('SELECT * FROM ffwbs_news_modules WHERE newsID="'.$_GET["newsID"].'" ORDER BY sort ASC');
		$pages['pagemodules'] = $query->result_array();		

		return $pages;

	}

	// Seite speichern
	// --------------------------------------------------------------------------

	public function news_add_new_page() {

		$data_new_news = array(
		   'date' => ''.basic_get_date().' '.basic_get_time().'' ,
		   'headline' => 'Eine neue News' ,
		   'text' => 'Hier kommt die Einleitung hin' ,
		   'link' => '' ,
		   'wehrID' => '0' ,
		   'online' => '0' ,
		   'archive' => '0'
		);
		$this->db->insert('news', $data_new_news);
		$newest_newsID = $this->db->insert_id();

		
		$data_new_module = array(
		   'newsID' => ''.$newest_newsID.'' ,
		   'model_type' => 'editorial' ,
		   'model_func' => '' ,
		   'layout' => 'text' ,
		   'module_data' => '[text::Ein neues Modul.]' ,
		   'subpage_module' => '0' ,
		   'sort' => '0'
		);
		$this->db->insert('news_modules', $data_new_module);

		$log_action = 'hat eine neue News angelegt.';
		basic_writelog($log_action,'newseditor - add new news', 2);

		return $newest_newsID;

	}

	public function get_news_details() {
		
		if(!isset($_GET["newsID"])) {
			$_GET["newsID"] = $_POST["id"];
		}

		$query = $this->db->query('SELECT * FROM ffwbs_news WHERE newsID="'.$_GET["newsID"].'"');
		$news_array = $query->row_array();
		
		if($news_array['wehrID']==0) {
		    $news_array['category'] = "Allgemein";
		} else {    
            $news_array['category'] = "FFW ".basicffw_get_vereindetails_singlevar($news_array['wehrID'], 'ort');
        }

  		$news_array['wehren'] = basicffw_get_wehrlist();

		return $news_array;
	}

	/*
	|--------------------------------------------------------------------------
	| Einsatz speichern
	|--------------------------------------------------------------------------
	*/
	public function news_save() {

		//echo "<p style='color: #FFF;'>Reihe: ".$_POST["module_reihe"]."</p>";

		$modul_array = explode(":", $_POST["module_reihe"]);

		// --- META-Daten Speichern
		$data_metadata = array(
		   'headline' => ''.strip_editor_tags($_POST["news_headline"]).'',
		   'text' => ''.strip_editor_tags($_POST["news_shorttext"]).'',
		   'date' => ''.$_POST["news_datetime"].'',
		   'wehrID' => ''.$_POST["news_wehrID"].''
		);
		$this->db->where('newsID', $_POST["newsID"]);
		$this->db->update('news', $data_metadata);

		// --- Module Löschen die nicht mehr gebraucht werden
		$query = $this->db->query('SELECT page_moduleID FROM ffwbs_news_modules WHERE newsID="'.$_POST["newsID"].'"');
		$old_module = $query->result_array();
		$old_module_array = array();

		foreach($old_module as $pageid) {
			array_push($old_module_array , $pageid['page_moduleID']);
		}
		$modules_delete = array_diff($old_module_array, $modul_array);
		
		foreach($modules_delete as $delete_id) {
			if(substr($delete_id, 0, 1)!=0) {
				$this->news_module_delete($delete_id);
			}
		}

		// --- Reihenfolge updaten
		$i = 0;

		foreach($modul_array as $modulID) {
			
			// --- Modul-Parameter laden 
			$query = $this->db->query('SELECT * FROM ffwbs_contentmodules WHERE contentmoduleID="'.$_POST["moduleType_".$modulID].'"');
			$sontentmodule_data = $query->row_array();
			$stringname = explode("::", $sontentmodule_data['content_parameter']);

			// --- EDITORIAL INHALTE UPDATEN
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
			$sql = 'UPDATE ffwbs_news_modules SET sort="'.$i.'" WHERE page_moduleID="'.$modulID.'"';
			$this->db->simple_query($sql);
			$i++;
			
			if(substr($modulID, 0, 1)!=0) {

				if($sontentmodule_data["model"]=="editorial") {
					$sql = 'UPDATE ffwbs_news_modules SET module_data="'.$content_string.'" WHERE page_moduleID="'.$modulID.'"';
					$this->db->simple_query($sql);
				} else {
					// -- Only for test Nur "imgGALLERY" update
					if($sontentmodule_data["model"]=="image") {
						$sql = 'UPDATE ffwbs_news_modules SET module_data="'.$_POST["content_".$modulID].'" WHERE page_moduleID="'.$modulID.'"';
						$this->db->simple_query($sql);									
					}
					if($sontentmodule_data["model"]=="table") {
						$CI =& get_instance();
			        	$CI->load->model('admin/model_pageeditor');
			       		$CI->model_pageeditor->page_savetable($_POST["content_".$modulID], $modulID, 'news_modules');
					}
					if($sontentmodule_data["model"]=="video") {
						$sql = 'UPDATE ffwbs_news_modules SET module_data="'.$_POST["content_".$modulID].'" WHERE page_moduleID="'.$modulID.'"';
						$this->db->simple_query($sql);									
					}
				}

			} else {
				if($sontentmodule_data["model"]!="table") {
					$data_new_module = array(
					   'newsID' => ''.$_POST["newsID"].'' ,
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
					$this->db->insert('news_modules', $data_new_module);
				} else {
					$data_new_module = array(
					   'newsID' => ''.$_POST["newsID"].'' ,
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
					$this->db->insert('news_modules', $data_new_module);
					$newest_tabeID = $this->db->insert_id();
					
					$CI =& get_instance();
			        $CI->load->model('admin/model_pageeditor');
			        $CI->model_pageeditor->page_savetable($_POST["content_".$modulID], $newest_tabeID, 'news_modules');
				}
				
				$i++;
			}

		}

		$log_action = 'hat die News "'.$_POST["newsID"].' | '.$_POST["news_headline"].'" bearbeitet.';
		basic_writelog($log_action,'pageeditor - module delete', 2);

		$GLOBALS['globalmessage'] = "success:Änderungen wurden gespeichert";

	}

	public function news_module_delete($delete_id) {

		$query = $this->db->query('SELECT * FROM ffwbs_news_modules WHERE page_moduleID="'.$delete_id.'"');
		$pagemoduldata = $query->row_array();
		$query = $this->db->query('SELECT * FROM ffwbs_news WHERE newsID="'.$pagemoduldata['newsID'].'"');
		$pagedata = $query->row_array();

		$query = $this->db->query('DELETE FROM ffwbs_news_modules WHERE page_moduleID="'.$delete_id.'"');
	
		$log_action = 'hat das News-Modul "#'.$pagemoduldata['page_moduleID'].'_'.$pagemoduldata['layout'].'" in der News: "'.$pagedata['newsID'].' | '.$pagedata['headline'].'" gelöscht.';
		basic_writelog($log_action,'pageeditor - module delete', 2);
	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz nicht im Frontend anzeigen
	|--------------------------------------------------------------------------
	*/
	public function news_publish() {

		$this->db->simple_query('UPDATE ffwbs_news SET online="'.$_GET["state"].'" WHERE newsID="'.$_GET["id"].'"');
		
		if($_GET["state"]==1) {
			$log_action = 'hat die News "#'.$_GET["id"].'" ONLINE geschaltet.';
		} else {	
			$log_action = 'hat die News "#'.$_GET["id"].'" OFFLINE geschaltet.';
		}
		basic_writelog($log_action,'news - publish', 2);
	}
	public function news_archive() {

		$this->db->simple_query('UPDATE ffwbs_news SET archive="'.$_GET["state"].'" WHERE newsID="'.$_GET["id"].'"');
		
		if($_GET["state"]==1) {
			$log_action = 'hat die News "#'.$_GET["id"].'" ins ARCHIV verschoben.';
		} else {	
			$log_action = 'hat die News "#'.$_GET["id"].'" aus dem ARCHIV geholt.';
		}
		basic_writelog($log_action,'news - archive', 2);
	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz löschen
	|--------------------------------------------------------------------------
	*/
	public function news_delete() {

		$query = $this->db->query('SELECT * FROM ffwbs_news WHERE newsID="'.$_GET["id"].'" LIMIT 1');
		$news = $query->row_array();
		
		// Newsbild löschen
		$filedir = "./frontend/images_cms/news/news_".$_GET["id"]."_big.jpg";
		if(file_exists($filedir)) {
			if(unlink($filedir)) {
				$control = "SUCCSESS";
			}
		}

		$query = $this->db->query('DELETE FROM ffwbs_news_modules WHERE newsID="'.$_GET["id"].'"');
		$query = $this->db->query('DELETE FROM ffwbs_news WHERE newsID="'.$_GET["id"].'"');

		$log_action = 'hat das Mannschaftsmitglied "#'.$_GET["id"].' | '.$news["headline"].'" gelöscht.';
		basic_writelog($log_action,'news - delete', 2);

		$GLOBALS['globalmessage'] = "success:Die News '#".$_GET["id"]."' wurde gelöscht";

	}
	
}

?>