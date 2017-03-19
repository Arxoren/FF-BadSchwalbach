<?php
class model_termine extends CI_Model {

	/*
	|--------------------------------------------------------------------------
	| einsatzliste laden
	|--------------------------------------------------------------------------
	*/
	public function termine_liste() {
		
		if(!isset($_POST["filter_wehren"]) || $_POST["filter_wehren"]=="alle") {
			
			if(isset($_POST["filter_type"])) {
				if($_POST["filter_type"]=="alle") {	
					$filter = "";
				} else {	
					$filter = 'AND type="'.$_POST["filter_type"].'"';
				}
			} else {
				$filter = "";
			}

			$sql = 'SELECT * FROM ffwbs_termine ORDER BY date_anfang DESC';
		} else {
			
			$var['aktfilter_wehren'] = $_POST["filter_wehren"];
			
			$sql = 'SELECT * FROM ffwbs_termine WHERE wehrID="'.$_POST["filter_wehren"].'" ORDER BY date_anfang DESC';

		}

		$query = $this->db->query($sql);
		$var['termine'] = $query->result_array();

		$query = $this->db->query('SELECT * FROM ffwbs_wehren ORDER BY sort ASC');
		$var['filter_wehren'] = $query->result_array();

		$var['page_headline'] = "Termine";
		$var['page_btn_addnew'] = "Einen neuen Termin anlegen";
		return $var;
		
	}

	public function editor() {
		
		$var['page_headline'] = "Termin bearbeiten";
		$var['page_btn_addnew'] = "Speichern";
		$var['feuerwehren'] = basicffw_get_wehrlist();
		
		if(isset($_GET["termineID"]) && $_GET["termineID"]!="") {
			$query = $this->db->query('SELECT * FROM ffwbs_termine WHERE termineID="'.$_GET["termineID"].'" LIMIT 1');
			$var['termine'] = $query->row_array();

		} else {
			$var['termine']  = array(
			   'termineID' => '',
			   'wehren' => ''
			);
		}

		return $var;

	}


	/*
	|--------------------------------------------------------------------------
	| Einsatz speichern
	|--------------------------------------------------------------------------
	*/
	public function termine_save() {

		$data_termine = array(
		   'date_anfang' => ''.$_POST["terminstart_date"].' '.$_POST["terminstart_time"].'' ,
		   'date_ende' => ''.$_POST["terminende_date"].' '.$_POST["terminende_time"].'' ,
		   'headline' => ''.strip_editor_tags($_POST["headline"]).'' ,
		   'text' => ''.strip_editor_tags($_POST["text_long"]).'' ,
		   'ort' => ''.strip_editor_tags($_POST["ort"]).'' ,
		   'wehrID' => ''.$_POST["wehrID"].'' ,
		   'online' => '1'
		);
		
		if($_POST["editID"]=="") {
			/*
			|--------------------------------------------------------------------------
			|  Neuen Einsatz speichern
			|--------------------------------------------------------------------------
			*/
			$this->db->insert('termine', $data_termine); 
			$newest_termineID = $this->db->insert_id();

			$log_action = 'hat einen neuen Termin "#'.$newest_termineID.' |'.$_POST["headline"].'" erstellt.';
			basic_writelog($log_action,'termin - save', 2);

			$msg = "success:Der Termin wurde neu angelegt.";

		} else {	
			/*
			|--------------------------------------------------------------------------
			|  Vorhandenen Einsatz bearbeiten
			|--------------------------------------------------------------------------
			*/
			$this->db->where('termineID', $_POST["editID"]);
			$this->db->update('termine', $data_termine);

			$log_action = 'hat den Termine "#'.$_POST["editID"].' |'.$_POST["headline"].'" bearbeitet.';
			basic_writelog($log_action,'termin - save', 2);

			$msg = "success:Die Änderungen wurden geseichert.";

		}

		$GLOBALS['globalmessage'] = $msg;

		$var = $this->termine_liste();
		return $var;
	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz nicht im Frontend anzeigen
	|--------------------------------------------------------------------------
	*/
	public function termine_publish() {

		$this->db->simple_query('UPDATE ffwbs_termine SET online="'.$_GET["state"].'" WHERE termineID="'.$_GET["id"].'"');

		if($_GET["state"]=1) {	
			$log_action = 'hat den Termin "#'.$_GET["id"].'" ONLINE geschaltet.';
		} else {
			$log_action = 'hat den Termin "#'.$_GET["id"].'" OFFLINE geschaltet';
		}
		basic_writelog($log_action,'termine - publish', 2);

	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz löschen
	|--------------------------------------------------------------------------
	*/
	public function termine_delete() {

		$query = $this->db->query('DELETE FROM ffwbs_termine WHERE termineID="'.$_GET["id"].'"');

		$log_action = 'hat den Termin "#'.$_GET["id"].'" gelöscht.';
		basic_writelog($log_action,'termine - delete', 2);

		$GLOBALS['globalmessage'] = "success:Termin wurde gelöscht";

	}
	
	// Bild löschen
	//--------------------------------------------------------------------------
	public function einsatz_image_MiniGal_delete() {

		$sql = 'SELECT * FROM ffwbs_images WHERE imageID="'.$_GET["fileID"].'"';
		$query = $this->db->query($sql);
			
		if($query->num_rows()==1) {
			$file = $query->row_array();
			$filedir = "./frontend/images_cms/".$file["folder"].$file["name"].".".$file["format"];
			$imagename = $file["name"].".".$file["format"];
			$images = "";
		} else {
			$filedir = "nofile";
		}
				
		if($filedir!="nofile") {	
			if(!unlink ($filedir)) {
				$msg = "error:Datei konnte nicht gelöscht werden";
			} else {
				$query = $this->db->query('DELETE FROM ffwbs_images WHERE imageID="'.$_GET["fileID"].'"');
				
				// Einsatz ermitteln und Gallery neu generieren
				$sql = 'SELECT * FROM ffwbs_einsatz WHERE einsatzID="'.$_GET["einsatzID"].'"';
				$einsatz_query = $this->db->query($sql);
				$einsatz = $einsatz_query->row_array();

				$imagelist = explode(":", $einsatz['gallery']);
				foreach($imagelist as $img) {	
					if($imagename != $img) {
						if($images=="") {	
							$images = $img;
						} else {
							$images = $images.':'.$img;
						}
					}
				}

				// Bilder in der Einsatztabelle updaten
				$images_einsatz_data = array(
					'gallery' => $images
				);
				$this->db->where('einsatzID', $_GET["einsatzID"]);
				$this->db->update('einsatz', $images_einsatz_data);

				$msg = "success:Datei wurde gelöscht";
			}
			$GLOBALS['globalmessage'] = $msg;
		}
	}

}

?>