<?php
class model_einsatz extends CI_Model {

	/*
	|--------------------------------------------------------------------------
	| einsatzliste laden
	|--------------------------------------------------------------------------
	*/
	public function einsatz_liste() {
		
		if(!isset($_GET["order"])) { 
			$var['order'] = "DESC";
		} else {
			if($_GET["order"]=="DESC") {
				$var['order']="ASC";
			} else {
				$var['order']="DESC";
			}
		}
		
		if(!isset($_GET["sort"])) {
			$var['sort'] = "date_start";
		} else {
			$var['sort'] = $_GET["sort"];
		}

		if(!isset($_POST["year"])) {
			$act_year = basic_get_year();
		} else {
			$act_year = $_POST["year"];
		}

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

			$sql = 'SELECT * FROM ffwbs_einsatz WHERE YEAR(date_start)='.$act_year.' '.$filter.' ORDER BY '.$var['sort'].' '.$var['order'].'';
		} else {
			
			$var['aktfilter_wehren'] = $_POST["filter_wehren"];
			$var['aktfilter_type'] = $_POST["filter_type"];
			
			if($_POST["filter_type"]=="alle") {	
				$filter = "";
			} else {	
				$filter = 'AND e.type="'.$_POST["filter_type"].'"';
			}

			$sql = 'SELECT * FROM ffwbs_einsatz e INNER JOIN ffwbs_einsatz_zuordnung z ON z.einsatzID=e.einsatzID WHERE YEAR(e.date_start)="'.$act_year.'" AND z.wehrID="'.$_POST["filter_wehren"].'" '.$filter.' ORDER BY '.$var["sort"].' '.$var["order"].'';

		}

		$query = $this->db->query($sql);
		$var['einsaetze'] = $query->result_array();

		for($x=0; $x<count($var['einsaetze']); $x++) {
			// Wehren ermitteln
			$query_alarmwehren = $this->db->query('SELECT w.ort FROM ffwbs_wehren w INNER JOIN ffwbs_einsatz_zuordnung z ON z.wehrID=w.wehrID AND z.einsatzID="'.$var['einsaetze'][$x]['einsatzID'].'" ORDER BY w.sort ASC');
			$wehren = $query_alarmwehren->result_array();
			$var['einsaetze'][$x]['alamiertewheren'] = "";

			foreach($wehren as $wehr) {
				if($var['einsaetze'][$x]['alamiertewheren']=="") {	
					$var['einsaetze'][$x]['alamiertewheren'] = $var['einsaetze'][$x]['alamiertewheren']." ".$wehr['ort'];
				} else {
					$var['einsaetze'][$x]['alamiertewheren'] = $var['einsaetze'][$x]['alamiertewheren'].", ".$wehr['ort'];
				}
			}
		}

		$query = $this->db->query('SELECT * FROM ffwbs_wehren ORDER BY sort ASC');
		$var['filter_wehren'] = $query->result_array();

		$var['filter_einsatz_type'] = array('brandeinsatz', 'hilfeleistung', 'gefahrengut', 'fehlalarm');

		$var['page_headline'] = "Einsätze";
		$var['page_btn_addnew'] = "Einen neuen Einsatz anlegen";
		return $var;
		
	}

	public function editor() {
		
		$var['page_headline'] = "Einsätze bearbeiten";
		$var['page_btn_addnew'] = "Speichern";
		$var['feuerwehren'] = basicffw_get_wehrlist();
		$var['fahrzeuge'] = basicffw_get_fahrzeuglist();
		
		if(isset($_GET["einsatzID"]) && $_GET["einsatzID"]!="") {
			$query = $this->db->query('SELECT * FROM ffwbs_einsatz WHERE einsatzID="'.$_GET["einsatzID"].'" LIMIT 1');
			$var['einsaetze'] = $query->row_array();

			$query_alarmwehren = $this->db->query('SELECT wehrID FROM ffwbs_einsatz_zuordnung WHERE einsatzID="'.$_GET["einsatzID"].'"');
			$aktwehren = $query_alarmwehren->result_array();
			$aktwehren_array = array();

			foreach($aktwehren as $wehr) {	
				array_push($aktwehren_array, $wehr['wehrID']);	
			}		

			$var['einsaetze']['wehren']=$aktwehren_array;
		
			// Mini Gallery erzeugen
			if($var['einsaetze']['gallery']!="") {

				$var['einsaetze']['gallery']=explode(":", $var['einsaetze']['gallery']);
				for($i=0; $i<count($var['einsaetze']['gallery']); $i++) {
					$var_img_name = explode(".", $var['einsaetze']['gallery'][$i]);
					$var['einsaetze']['gallery_imgID'][$i] = basic_get_imageIdbyName($var_img_name[0]);
				}
			}
		
		} else {
			$var['einsaetze']  = array(
			   'einsatzID' => '',
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
	public function einsatz_save() {

		if(!isset($_POST["ueberoertlich"])) { $_POST["ueberoertlich"]=0; }
		if(!empty($_POST["cars"])) { $cars=implode(":", $_POST["cars"]); } else { $cars=""; }

		// Einsatzdauer berechnen wenn Minuten angegeben wurden
		if($_POST['einsatzdauer']!="") {
			date_default_timezone_set("Europe/Berlin");
			$time = new DateTime($_POST["einsatzstart_date"].' '.$_POST["einsatzstart_time"]);
			$time->add(new DateInterval('PT' . $_POST['einsatzdauer'] . 'M'));

			$time_ende = $time->format('Y-m-d H:i:s');
		} else {
			$time_ende = $_POST["einsatzende_date"].' '.$_POST["einsatzende_time"];
		}

		$data_einsatz = array(
		   'date_start' => ''.$_POST["einsatzstart_date"].' '.$_POST["einsatzstart_time"].'' ,
		   'date_ende' => ''.$time_ende.'' ,
		   'stichwort' => ''.$_POST["stichwort"].'' ,
		   'title' => ''.$_POST["title"].'' ,
		   'text_short' => ''.$_POST["text_intro"].'' ,
		   'text_long' => ''.$_POST["text_long"].'' ,
		   'type' => ''.$_POST["type"].'' ,
		   'ort' => ''.$_POST["ort"].'' ,
		   'fahrzeuge' => ''.$cars.'' ,
		   'ueberoertlich' => ''.$_POST["ueberoertlich"].'' ,
		   'einsatzkraefte' => ''.str_replace(", ", ":", str_replace(";", ",", $_POST["einsatzkraefte"])).'' ,
		   'eigenekraefte' => ''.$_POST["eigenekraefte"].'' ,
		   'online' => '1'
		);
		
		if($_POST["editID"]=="") {
			/*
			|--------------------------------------------------------------------------
			|  Neuen Einsatz speichern
			|--------------------------------------------------------------------------
			*/
			$this->db->insert('einsatz', $data_einsatz); 
			$newest_einsatzID = $this->db->insert_id();

			if($_FILES["media_file"]["tmp_name"][0]!="") {
					
				$CI =& get_instance();
				$CI->load->model('admin/Model_media');
				$images = "";

				for($i=0; $i<count($_FILES["media_file"]["tmp_name"]); $i++) {
				    
				    // Bildname und Nummerierung ermitteln "id###_x" 
				    $filename =  explode(".", $_FILES["media_file"]["name"][$i]);
				    $imagename = 'id'.$newest_einsatzID.'_'.$i;

				    // Media Fuktion laden zum Bilder speichern
				    $file_msg = $CI->Model_media->write_image($i, $imagename);
				    $file_msg = explode(':', $file_msg);

				    if($file_msg[0]!="error") {
					    if($images=="") {	
					    	$images = $imagename.'.'.$filename[1];
					    } else {
					    	$images = $images.':'.$imagename.'.'.$filename[1];
						}
					}
				}
				
				// Bilder in die Einsatztabelle schreiben
				$images_einsatz_data = array(
					'gallery' => $images
				);
				$this->db->where('einsatzID', $newest_einsatzID);
				$this->db->update('einsatz', $images_einsatz_data);
			}

			foreach ($_POST["wehren"] as $wehren) {
				$data_zuordnung = array(
					'einsatzID' => $newest_einsatzID,
					'wehrID' => $wehren
				);
				$this->db->insert('einsatz_zuordnung', $data_zuordnung);
			}
			
			$log_action = 'hat einen neuen Einstaz "#'.$newest_einsatzID.' |'.$_POST["title"].'" erstellt.';
			basic_writelog($log_action,'einstaz - save', 2);

			$msg = "success:Der Einsatz wurde gespeichert.";

		} else {	
			/*
			|--------------------------------------------------------------------------
			|  Vorhandenen Einsatz bearbeiten
			|--------------------------------------------------------------------------
			*/
			$this->db->where('einsatzID', $_POST["editID"]);
			$this->db->update('einsatz', $data_einsatz);

			// Bilder abspeichern
			if($_FILES["media_file"]["tmp_name"][0]!="") {
					
				$CI =& get_instance();
				$CI->load->model('admin/Model_media');
				$images = "";

				// Test: Wie viele Bilder sind bereits im Beitrag enthalten?
				$query = $this->db->query('SELECT * FROM ffwbs_einsatz WHERE einsatzID="'.$_POST["editID"].'" LIMIT 1');
				$image_check = $query->row_array();
				if($image_check['gallery']!="") {	
					// letzte ziffer ermiteln um den nächsten Bildnamen zu identifizieren
					$imagenamevar = explode(":", $image_check['gallery']);
					$imagenamevarsingle = explode(".", end($imagenamevar));
					$imagenamevarnum = explode("_", $imagenamevarsingle[0]);
					$image_number = intval($imagenamevarnum[1])+1;
					$images = $image_check['gallery'];
				} else {
					$image_number = 0;
				}

				for($i=0; $i<count($_FILES["media_file"]["tmp_name"]); $i++) {
				    
				    if($_FILES["media_file"]["tmp_name"][$i]!="") {
					    // Bildname und Nummerierung ermitteln "id###_x" 
					    $filename =  explode(".", $_FILES["media_file"]["name"][$i]);
					    $imagename = 'id'.$_POST["editID"].'_'.$image_number;
				   		
				   		// Media Fuktion laden zum Bilder speichern
					    $file_msg = $CI->Model_media->write_image($i, $imagename);
					    $file_msg = explode(':', $file_msg);

					    if($file_msg[0]!="error") {
						    if($images=="") {	
						    	$images = $imagename.'.'.$filename[1];
						    } else {
						    	$images = $images.':'.$imagename.'.'.$filename[1];
							}
						}
					}
					$image_number++;
				}
				
				// Bilder in die Einsatztabelle schreiben
				$images_einsatz_data = array(
					'gallery' => $images
				);
				$this->db->where('einsatzID', $_POST["editID"]);
				$this->db->update('einsatz', $images_einsatz_data);
			}

			// Fehlende Zuordnungen hinzufügen
			foreach ($_POST["wehren"] as $wehren) {
				
				$zuordnung_query=$this->db->query("SELECT * FROM ffwbs_einsatz_zuordnung WHERE wehrID='".$wehren."' AND einsatzID='".$_POST["editID"]."'");
				$edit_zuordnung = $zuordnung_query->result_array();

				if(empty($edit_zuordnung)) {
					$data_zuordnung = array(
						'einsatzID' => $_POST["editID"],
						'wehrID' => $wehren
					);
					$this->db->insert('einsatz_zuordnung', $data_zuordnung);
				} 
			}

			// Nicht mehr benötigte Zuordnungen löschen
			$zuordnung_query=$this->db->query("SELECT * FROM ffwbs_einsatz_zuordnung WHERE einsatzID='".$_POST["editID"]."'");
			$edit_zuordnung = $zuordnung_query->result_array();
			foreach($edit_zuordnung as $z) {
				$notdelete=0;
				for($i=0; $i<count($_POST["wehren"]); $i++) {	
					if($_POST["wehren"][$i]==$z["wehrID"]) {
						$notdelete=1;
						break;
					}
				}
				if($notdelete==0) {
					$query = $this->db->query('DELETE FROM ffwbs_einsatz_zuordnung WHERE einsatzzuordnungID="'.$z["einsatzzuordnungID"].'"');
				}
			}
			
			$log_action = 'hat den Einstaz "#'.$_POST["editID"].' |'.$_POST["title"].'" bearbeitet.';
			basic_writelog($log_action,'einstaz - save', 2);

			$msg = "success:Der Einsatz wurde bearbeitet.";

		}

		$GLOBALS['globalmessage'] = $msg;

		$var = $this->einsatz_liste();
		return $var;
	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz nicht im Frontend anzeigen
	|--------------------------------------------------------------------------
	|	field_name => Der "name" des Formularfeldes
	|	expected_value => Der erwartete Inhalt / Test
	|		- '' 		=> Auf Inhalt geprüft
	|		- 'exists' 	=> Auf Existenz überprüft
	|	level => gibt das level zurück wo die Message im FE mit jQuery platziert wird  
	|		- group 	=> Gibt nur eine Message am ende eine Grupppe aus
	|		- array 	=> Gibt eine Message für einen array Block aus
	|		- 0 		=> Gibt die Msg direkt unter dem Feld aus
	|		- *ziffer	=> Gibt die Message entsprechend unter dem *parent aus 
	|--------------------------------------------------------------------------
	*/
	public function checkform() {

		$check_list [] = array(
		   'field_name' => 'title' ,
		   'expected_value' => '' ,
		   'errormsg' => 'Es muss ein Titel eingegeben werden',
		   'level' => '0'
		);
		$check_list [] = array(
		   'field_name' => 'stichwort' ,
		   'expected_value' => '' ,
		   'errormsg' => 'Es muss ein Stichwort eingegeben werden',
		   'level' => '0'
		);
		$check_list [] = array(
		   'field_name' => 'text_intro' ,
		   'expected_value' => '' ,
		   'errormsg' => 'Es muss ein Intro eingegeben werden',
		   'level' => '0'
		);		
		$check_list [] = array(
		   'field_name' => 'type' ,
		   'expected_value' => 'exists' ,
		   'errormsg' => 'Es muss eine Einsatzart angegeben werden',
		   'level' => 'group'
		);
		$check_list [] = array(
		   'field_name' => 'wehren' ,
		   'expected_value' => 'exists' ,
		   'errormsg' => 'Es muss mindestens eine Wehr angegeben werden',
		   'level' => 'array'
		);
		$check_list [] = array(
		   'field_name' => 'cars' ,
		   'expected_value' => 'exists' ,
		   'errormsg' => 'Es muss mindestens ein Fahrzeug angegeben werden',
		   'level' => 'array'
		);
		$check_list [] = array(
		   'field_name' => 'einsatzstart_date' ,
		   'expected_value' => '' ,
		   'errormsg' => 'Es muss ein Startdatum eingegeben werden',
		   'level' => '2'
		);
		$check_list [] = array(
		   'field_name' => 'einsatzstart_time' ,
		   'expected_value' => '' ,
		   'errormsg' => 'Es muss ein Startzeit eingegeben werden',
		   'level' => '2'
		);
		$check_list [] = array(
		   'field_name' => 'eigenekraefte' ,
		   'expected_value' => '' ,
		   'errormsg' => 'Es muss die Anzahl der eigenen Kräfte eingegeben werden',
		   'level' => '0'
		);

		$returnmsg = ":yes";
		
		foreach ($check_list as $check) {
			if($check['expected_value']=='') {
				if($_POST[$check['field_name']]=="") {
					$returnmsg=$returnmsg.":".$check['field_name']."|".$check['errormsg']."|".$check['level'];
				}
			} else {
				if(!isset($_POST[$check['field_name']])) {
					$returnmsg=$returnmsg.":".$check['field_name']."|".$check['errormsg']."|".$check['level'];
				}
			}
		}

		// Special Logiv Check
		if($_POST["einsatzdauer"]=="") {
			if($_POST["einsatzende_date"]=="") {
				$returnmsg=$returnmsg.":einsatzende_date|Bitte geben Sie ein Enddatum oder die Einsatzdauer ein|2";
			}
			if($_POST["einsatzende_time"]=="") {
				$returnmsg=$returnmsg.":einsatzende_time|Bitte geben Sie eine Endzeit oder die Einsatzdauer ein|2";
			}
			
			if($_POST["einsatzende_date"]!="") {
				if($_POST["einsatzende_time"]!="") {
					$date_start = new DateTime($_POST["einsatzstart_date"]." ".$_POST["einsatzstart_time"]);
					$date_end = new DateTime($_POST["einsatzende_date"]." ".$_POST["einsatzende_time"]);
					$tstamp_s = $date_start->getTimestamp();
					$tstamp_e = $date_end->getTimestamp();
					if($tstamp_s>=$tstamp_e) {
						$returnmsg=$returnmsg.":einsatzende_time|Ihr Endzeitpunkt liegt hinter dem Startzeitpunkt|2";
					}
				}
			}
		}		

		return substr($returnmsg,1,strlen($returnmsg)-1);
	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz nicht im Frontend anzeigen
	|--------------------------------------------------------------------------
	*/
	public function einsatz_publish() {

		$this->db->simple_query('UPDATE ffwbs_einsatz SET online="'.$_GET["state"].'" WHERE einsatzID="'.$_GET["id"].'"');

		if($_GET["state"]=1) {	
			$log_action = 'hat den Einstaz "#'.$_GET["id"].'" ONLINE geschaltet.';
		} else {
			$log_action = 'hat den Einstaz "#'.$_GET["id"].'" OFFLINE geschaltet';
		}
		basic_writelog($log_action,'einstaz - publish', 2);

	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz löschen
	|--------------------------------------------------------------------------
	*/
	public function einsatz_delete() {

		$query = $this->db->query('SELECT * FROM ffwbs_einsatz WHERE einsatzID="'.$_GET["id"].'" LIMIT 1');
		$galleryvar = $query->row_array();
		
		// Eventuelle Einsatzbilder löschen
		if($galleryvar['gallery']!="") {
			$imagelist = explode(":", $galleryvar["gallery"]);
			
			foreach($imagelist as $image) {
				$imagename = explode(".", $image);
				$imgdedata = basic_get_imageIdbyName($imagename[0]);

				if($imgdedata['imageID']!="") {
					$filedir = "./frontend/images_cms/".$imgdedata["folder"].$imgdedata["name"].".".$imgdedata["format"];

					if(unlink ($filedir)) {
						$query = $this->db->query('DELETE FROM ffwbs_images WHERE imageID="'.$imgdedata["imageID"].'"');
					}
				}
			}
		}

		$query = $this->db->query('DELETE FROM ffwbs_einsatz WHERE einsatzID="'.$_GET["id"].'"');
		$query = $this->db->query('DELETE FROM ffwbs_einsatz_zuordnung WHERE einsatzID="'.$_GET["id"].'"');

		$log_action = 'hat den Einstaz "#'.$_GET["id"].'" gelöscht.';
		basic_writelog($log_action,'einstaz - delete', 2);

		$GLOBALS['globalmessage'] = "success:Einsatz wurde gelöscht";

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