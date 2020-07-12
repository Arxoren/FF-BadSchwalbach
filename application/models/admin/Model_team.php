<?php
class model_team extends CI_Model {

	/*
	|--------------------------------------------------------------------------
	| einsatzliste laden
	|--------------------------------------------------------------------------
	*/
	public function team_liste() {
		
		if(!isset($_POST["filter_wehren"]) && isset($_GET["sort"])) {
			if($_GET["sort"]!=0) {	
				$_POST["filter_wehren"] = $_GET["sort"];
			}
		}

		if(!isset($_POST["filter_wehren"]) || $_POST["filter_wehren"]=="alle") {
			$sql = 'SELECT * FROM ffwbs_mannschaft ORDER BY nachname, vorname ASC';
			$var['aktfilter_wehren'] = 0;
		} else {
			$sql = 'SELECT * FROM ffwbs_mannschaft WHERE wehrID="'.$_POST["filter_wehren"].'" ORDER BY nachname, vorname ASC';
			$var['aktfilter_wehren'] = $_POST["filter_wehren"];
		}

		$query = $this->db->query($sql);
		$var['member'] = $query->result_array();
		$var['member_count'] = $query->num_rows();

		//--- Wehrnamen, Rang, Position hineinweben
		for($x=0; $x<count($var['member']); $x++) {
			$wehrname = basicffw_get_vereindetails($var['member'][$x]['wehrID']);
			$var['member'][$x]['wehr_name'] = $wehrname['ort'];
			$var['member'][$x]['rang_details'] = basicffw_get_rang($var['member'][$x]['rang'], $var['member'][$x]['geschlecht']);
			$var['member'][$x]['position_name'] = basicffw_get_position($var['member'][$x]['position']);
		}

		//--- Wehren Filter befüllen
		$query = $this->db->query('SELECT * FROM ffwbs_wehren ORDER BY sort ASC');
		$var['filter_wehren'] = $query->result_array();

		//--- Page Headline festlegen
		$var['page_headline'] = "Mannschaft";
		$var['page_btn_addnew'] = "Ein neues Mitglied anlegen";
		
		return $var;
	}

	public function editor() {
		
		$var['page_headline'] = "Einsätze bearbeiten";
		$var['page_btn_addnew'] = "Speichern";
		$var['feuerwehren'] = basicffw_get_wehrlist();
        $var['position'] = explode(":", $GLOBALS['team_ffposition']);
        $var['rang_m'] = explode(":", $GLOBALS['team_ffrang_m']);
        $var['rang_w'] = explode(":", $GLOBALS['team_ffrang_w']);
		
		if(isset($_GET["memberID"]) && $_GET["memberID"]!="") {
			$query = $this->db->query('SELECT * FROM ffwbs_mannschaft WHERE memberID="'.$_GET["memberID"].'" LIMIT 1');
			$var['member'] = $query->row_array();


		} else {
			$var['member']  = array(
			   'memberID' => '',
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
	public function team_save() {

		$data_team = array(
		   'vorname' => ''.$_POST["vorname"].'' ,
		   'nachname' => ''.$_POST["nachname"].'' ,
		   'geschlecht' => ''.$_POST["geschlecht"].'' ,
		   'gebday' => ''.$_POST["gebday"].'' ,
		   'beruf' => ''.$_POST["beruf"].'' ,
		   'rang' => ''.$_POST["rang"].'' ,
		   'position' => ''.$_POST["position"].'' ,
		   'wehrID' => ''.$_POST["wehrID"].'' ,
		);
			
		if($_POST["editID"]=="") {
			/*
			|--------------------------------------------------------------------------
			|  Neuen Eintrag speichern
			|--------------------------------------------------------------------------
			*/
			$this->db->insert('mannschaft', $data_team); 
			$newest_memberID = $this->db->insert_id();

			for($i=0; $i<count($_FILES["media_file"]["tmp_name"]); $i++) {
				if($_FILES["media_file"]["tmp_name"][$i]!="") {
						
					$CI =& get_instance();
					$CI->load->model('admin/Model_media');
					$images = "";

					$filename =  explode(".", $_FILES["media_file"]["name"][$i]);
					$imagename = "team_".$filename[0].$newest_memberID;
					$imageDB = "team_".$filename[0].$newest_memberID.".".$filename[1];;

				    // Media Fuktion laden zum Bilder speichern
				    $file_msg = $CI->Model_media->write_image($i, $imagename);
				    $file_msg = explode(':', $file_msg);

				    if($file_msg[0]!="error") {
						// Bilder in die Einsatztabelle schreiben
						$images_data = array(
							'bild' => $imageDB
						);
						$this->db->where('memberID', $newest_memberID);
						$this->db->update('mannschaft', $images_data);
					}
				}
			}

			$log_action = 'hat ein neues Mannschaftsmitglied "#'.$newest_memberID.' | '.$_POST["vorname"].' '.$_POST["nachname"].'" hinzugefügt.';
			basic_writelog($log_action,'mannschaft - save', 2);

			$msg = "success:Der Einsatz wurde gespeichert.";

		} else {	
			/*
			|--------------------------------------------------------------------------
			|  Vorhandenen Eintrag bearbeiten
			|--------------------------------------------------------------------------
			*/
			$this->db->where('memberID', $_POST["editID"]);
			$this->db->update('mannschaft', $data_team);

			// Bilder abspeichern
			if($_FILES["media_file"]["tmp_name"][0]!="") {
						
				$CI =& get_instance();
				$CI->load->model('admin/Model_media');
				$images = "";

				// Test: Wie viele Bilder sind bereits im Beitrag enthalten?
				$query = $this->db->query('SELECT * FROM ffwbs_mannschaft WHERE memberID="'.$_POST["editID"].'" LIMIT 1');
				$image_check = $query->row_array();
				if($image_check['bild']!="") {	
					$filedir = "./frontend/images_cms/mannschaft/".$image_check["bild"];
					@unlink ($filedir);
				}

				for($i=0; $i<count($_FILES["media_file"]["tmp_name"]); $i++) {
					if($_FILES["media_file"]["tmp_name"][$i]!="") {
					    $filename =  explode(".", $_FILES["media_file"]["name"][$i]);
					    $imagename = "team_".$filename[0].$_POST["editID"];
					    $imageDB = "team_".$filename[0].$_POST["editID"].".".$filename[1];
						   		
				   		// Media Fuktion laden zum Bilder speichern
					    $file_msg = $CI->Model_media->write_image($i, $imagename);

					    $file_msg = explode(':', $file_msg);
					    if($file_msg[0]!="error") {
							// Bild in der DB abspeichern
							$images_data = array(
								'bild' => $imageDB
							);
							$this->db->where('memberID', $_POST["editID"]);
							$this->db->update('mannschaft', $images_data);
						}
					}
				}
			}

			$log_action = 'hat das Mannschaftsmitglied "#'.$_POST["editID"].' | '.$_POST["vorname"].' '.$_POST["nachname"].'" bearbeitet.';
			basic_writelog($log_action,'mannschaft - save', 2);

			$msg = "success:Der Teameintrag wurde bearbeitet.";
			$GLOBALS['globalmessage'] = $msg;

		}

		$GLOBALS['globalmessage'] = $msg;

		$var = $this->team_liste();
		return $var;
	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz nicht im Frontend anzeigen
	|--------------------------------------------------------------------------
	*/
	public function team_publish() {

		$this->db->simple_query('UPDATE ffwbs_mannschaft SET online="'.$_GET["state"].'" WHERE memberID="'.$_GET["id"].'"');
		
		if($_GET["state"]==1) {
			$log_action = 'hat das Mannschaftsmitglied "#'.$_GET["id"].'" ONLINE geschaltet.';
		} else {	
			$log_action = 'hat das Mannschaftsmitglied "#'.$_GET["id"].'" OFFLINE geschaltet.';
		}
		basic_writelog($log_action,'mannschaft - publish', 2);
	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz löschen
	|--------------------------------------------------------------------------
	*/
	public function team_delete() {

		$query = $this->db->query('SELECT * FROM ffwbs_mannschaft WHERE memberID="'.$_GET["id"].'" LIMIT 1');
		$team = $query->row_array();
		
		// Eventuelle Memeberbilder löschen
		if($team['bild']!="") {
			$filedir = "./frontend/images_cms/mannschaft/".$team["bild"];
			if(unlink ($filedir)) {
				$control = "SUCCSESS";
				$picture = explode(".", $team["bild"]);
				$sql= 'DELETE FROM ffwbs_images WHERE folder="mannschaft/" AND name="'.$picture[0].'"';
				$query = $this->db->query($sql);
			}
		}

		$query = $this->db->query('DELETE FROM ffwbs_mannschaft WHERE memberID="'.$_GET["id"].'"');

		$log_action = 'hat das Mannschaftsmitglied "#'.$_GET["id"].' | '.$team["vorname"].' '.$team["nachname"].'" gelöscht.';
		basic_writelog($log_action,'mannschaft - delete', 2);

		$GLOBALS['globalmessage'] = "success:Einsatz wurde gelöscht";

	}
	
	// Bild löschen
	//--------------------------------------------------------------------------
	public function team_image_delete() {

		$query = $this->db->query('SELECT * FROM ffwbs_mannschaft WHERE memberID="'.$_GET["memberID"].'"');
		$team = $query->row_array();
			
		if($team['bild']!="") {
			
			$filedir='./frontend/images_cms/mannschaft/'.$team['bild'];

			if(!unlink ($filedir)) {
				$msg = "error:Datei konnte nicht gelöscht werden";
			} else {
				// Bilder in der Einsatztabelle updaten
				$images_team_data = array(
					'bild' => ""
				);
				$this->db->where('memberID', $_GET["memberID"]);
				$this->db->update('mannschaft', $images_team_data);

				$msg = "success:Datei wurde gelöscht";
			}

		}

		$log_action = 'hat das Bild des Mannschaftsmitgliedes "#'.$_GET["memberID"].' | '.$team["vorname"].' '.$team["nachname"].'" gelöscht.';
		basic_writelog($log_action,'mannschaft - image delete', 2);

		$GLOBALS['globalmessage'] = $msg;
	}

}

?>