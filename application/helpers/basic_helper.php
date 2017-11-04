<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


		/*
		|--------------------------------------------------------------------------
		| Aktuelle Daten berechnen
		|--------------------------------------------------------------------------
		|
		| Aktuelles Datun und Zeit geben lassen.
		|
		*/
		function basic_get_date() {
			date_default_timezone_set("Europe/Berlin");
			$heute = date("Y")."-".date("m")."-".date("d");
			return("$heute");
		}
	
		function basic_get_time()	{
			date_default_timezone_set("Europe/Berlin");
			$zeit = date("G:i:s");
			return("$zeit");
		}

		function basic_get_year() {
			date_default_timezone_set("Europe/Berlin");
			$heute = date("Y");
			return("$heute");
		}

		/*
		|--------------------------------------------------------------------------
		| EIN DEUTSCHES DATUMSFORMAT PARSEN
		|--------------------------------------------------------------------------
		|
		| Formatiert ein englisches Datum in ein deutsches Datum
		| Einstellungen:
		|
		| $date => [string] Das zu parsende Datum
		|
		| $format
		|	datetime 	= Datum und Uhrzeit
		|	dateonly 	= Nur Datm parsen
		|	time 		= Nur Zeit zurück geben
		|
		| $TimeFormart:  
		|	1 = Stunden
		|	2 = Stunden:Minuten
		|	3 = Stunden:Minuten:Sekunden
		|
		*/
		function basic_get_ger_datetime($date, $format, $format_time) {
			$particle = explode(" ", $date);

			// Datum berechnen
			$date_particle = explode("-", $particle[0]);
			$date="".$date_particle[2].".".$date_particle[1].".".$date_particle[0]."";

			// TIME-String berechnen:
			$time_particle = explode(":", $particle[1]);
			$time = "";
			for($i=0; $i<$format_time; $i++) {
				if($time == "") {
					$time = $time_particle[$i];
				} else {
					$time = $time.':'.$time_particle[$i];
				}
			}

			// Wenn gewünscht TIME-String berechnen:
			switch($format) {
				case 'datetime': 
					$new_date= $date." - ".$time."h";
					break;
				case 'time':
					$new_date= $time; 
					break;
				case 'dateonly': 
					$new_date= $date; 
					break;
				default: 
					$new_date= 'unknown format request';
			}
			
			return($new_date);
		}



		function basic_get_engl_date($date) {
			if(substr($date, 2, 1)==".") {	
				$var=explode(".", $date); 
				$date=$var[2]."-".$var[1]."-".$var[0];
			} else {
				$date="error";
			}
			return($date);
		}
		
		function basic_get_engl_datetime($date) {
			if(substr($date, 2, 1)==".") {	
				$var=explode(" ", $date); 
					$var2=explode(".", $var[0]); 
					$date=$var2[2]."-".$var2[1]."-".$var2[0]." ".$var[1];
			} else {
				$date="error";
			}
			return($date);
		}

		function basic_get_datedetail($date, $op) {
			date_default_timezone_set("Europe/Berlin");
			$datetime = new DateTime($date);
       		
			if($op=='monat') {
		       		switch($datetime->format('m')) {
		       			case "01": $var = 'Januar'; 	break;
		       			case "02": $var = 'Ferbruar';	break;
		       			case "03": $var = 'M&auml;rz';	break;
		       			case "04": $var = 'April';		break;
		       			case "05": $var = 'Mai';		break;
		       			case "06": $var = 'Juni';		break;
		       			case "07": $var = 'Juli';		break;
		       			case "08": $var = 'August';		break;
		       			case "09": $var = 'September';	break;
		       			case "10": $var = 'Oktober';	break;
		       			case "11": $var = 'November';	break;
		       			case "12": $var = 'Dezember';	break;
		       		}

			}
			if($op=='wochentag') {
	       		switch($datetime->format('D')) {
	       			case "Mon": $var = 'Montag'; 		break;
	       			case "Tue": $var = 'Dienstag';		break;
	       			case "Wed": $var = 'Mittwoch';		break;
	       			case "Thu": $var = 'Donnerstag';	break;
	       			case "Fri": $var = 'Freitag';		break;
	       			case "Sat": $var = 'Samstag';		break;
	       			case "Sun": $var = 'Sonntag';		break;
	       		}
	       	}
			if($op=='tag') {
				$var = $datetime->format('d');
			}
			if($op=='jahr') {
				$var = $datetime->format('Y');
			}

       		return($var);
		}

		function basic_get_fileicon($format) {
		    switch($format) { 
                case "jpg": $file_icon = 'icon_images.svg'; break;
                case "mp4": $file_icon = 'icon_video.svg'; break;
                case "webm": $file_icon = 'icon_video.svg'; break;
                case "pdf": $file_icon = 'icon_files_pdf.svg'; break;
                case "txt": $file_icon = 'icon_files_txt.svg'; break;
                case "zip": $file_icon = 'icon_files_zip.svg'; break;
                case "doc": $file_icon = 'icon_files_doc.svg'; break;
                case "docx": $file_icon = 'icon_files_doc.svg'; break;
                case "ppt": $file_icon = 'icon_files_ppt.svg'; break;
                case "pptx": $file_icon = 'icon_files_ppt.svg'; break;
                default: $file_icon = 'icon_files_blank.svg'; break;
            }
            return $file_icon;
        }

		/*
		|--------------------------------------------------------------------------
		| Bread-Crump-Path Helper
		|--------------------------------------------------------------------------
		|
		| Ermittelt auf Basis der URL den Pfad mit der jeweiligen
		| Link und gibt ihn zurück.
		|
		| $url_segment => [array] Alle URL-Segmente in einem Array auf Basis von URI-Helper
		| $basis_url => [string] Die Basis URL vor dem CMS Pfad
		|
		*/
		function basic_get_breadcrumppath($url_segment, $internalurl_segment, $basis_url) {
			$url= $basis_url.$url_segment[1].'/'.$url_segment[2];
			$path = '<a href="'.$url.'">Start</a>&nbsp;&nbsp;/&nbsp;&nbsp;';
			$ch = curl_init();
			$writing_url_before = '';

			$ci=& get_instance();
			$ci->load->database(); 

			$sqlstr_page = 'SELECT * FROM ffwbs_pages WHERE page_name="'.$internalurl_segment[3].'"';
			$query_page = $ci->db->query($sqlstr_page);
			$page = $query_page->row_array();

			
			//print_r($url_segment);

			// Wenn eine 2. Variable erwartet wird (e.g. News) die erste davon nicht anzeigen
			/*
			if($GLOBALS['expected_var']==2) {
				// Wenn das vorletzte gefiltert werden soll Stelle merken
				$x=count($url_segment)-1;
			} else {
				// Wenn alle angezeigt werden soll $i auf "0" setzen da $i erst mit 3 beginnt
				$x=0;
			}
			*/

			for($i=3; $i<=count($url_segment); $i++) {

				// Der Link wird erzeugt in dem er pro Durchlauf aufgebaut wird
				$url=$url.'/'.$url_segment[$i];

				// aus der URL wieder den namen parsen ' ' und '/' ersetzen 	
				$writing_url = ucfirst(curl_unescape($ch, $url_segment[$i])); 

				$sqlstr = 'SELECT * FROM ffwbs_navigation WHERE label="'.$url_segment[$i].'"';
				$query = $ci->db->query($sqlstr);
				$menue = $query->row_array();

				$link = base_url().$GLOBALS['varpath'].'/'.$menue['path'];

				if($i==count($url_segment)) {
					// ACTIVE Status für das letzte Segment
					$path = $path.'<span class="active">'.$writing_url.'</span>';
				} else {
					// Normale Segmente
					// Segmente die nicht in der Nacvigation auftauchen nicht anzeigen
					if($page['expected_var_default']==0) {
						if($query->num_rows() > 0) {
							$path = $path.'<a href="'.$link.'/">'.$writing_url.'</a>&nbsp;&nbsp;/&nbsp;&nbsp;';
						}
					} else {
						$link = $url;
						$path = $path.'<a href="'.$link.'/">'.$writing_url.'</a>&nbsp;&nbsp;/&nbsp;&nbsp;';
					}
				}
			}
			return $path;
	    }


	    function basic_convert_to_url($string) {
	    	return str_replace(" ", "_", str_replace("/", "-", str_replace(".", "", basic_clear_string($string))));
	    }


    	function basic_get_imageIdbyName($imagename) {
			
			$ci=& get_instance();
			$ci->load->database(); 

			$sqlstr = 'SELECT * FROM ffwbs_images WHERE name="'.$imagename.'"';
			$query = $ci->db->query($sqlstr);
			$image = $query->row_array();

			return($image);

		}

		function basic_clear_string($str) {

			$search = array("ä", "ö", "ü", "ß", "Ä", "Ö", "Ü", "&", "é", "á", "ó");
			$replace = array("ae", "oe", "ue", "ss", "Ae", "Oe", "Üe", "", "e", "a", "o");
			$str = str_replace($search, $replace, $str);
			return $str;

		}
		function strip_editor_tags($content) {
			
			//--- Editor Formate umwandeln
			$content = str_replace('<strong>', '[b]', str_replace('</strong>', '[/b]', $content));
			$content = str_replace('<i>', '[i]', str_replace('</i>', '[/i]', $content));
			$content = str_replace('<u>', '[u]', str_replace('</i>', '[/u]', $content));

			$test=preg_match_all('#\<a href=\"(.*?)\" target=\"(.*?)\"\>(.*?)\<\/a\>#si', $content, $treffer, PREG_SET_ORDER);
			$i = 0;
			foreach($treffer as $string) {
				$textvar = '[url='.$string[1].'='.$string[2].']'.$string[3].'[/url]';
				$content=str_replace($string[0], $textvar, $content);
			}

			//--- Autoformate durch editable Tags entfernen
			$content = str_replace("<li>", "|", str_replace("<div>", "[br]", str_replace("<p>", "[br]", str_replace("<br>", "[br]", $content))));
			$content = str_replace("[br]", "<br/>", strip_tags($content));
			$content = str_replace('"', '&quot;', $content);
			
			return $content;
		}

		function text_format_parsing($content) {
			
			/*
			|-------------------------------------------------------
			| ### Pseudo Code für Text Formatierungen
			|-------------------------------------------------------
			|	LINK => [url=adressstring=target=title]*txt[/url]
			|-------------------------------------------------------
			*/

			//if($GLOBALS['editable_tag']=="") {
				// Links Finden und ersetzen
				//---------------------------
				$test=preg_match_all("#\[url=(.*?)=(.*?)\](.*?)\[\/url\]#si", $content, $treffer, PREG_SET_ORDER);
				$i = 0;

				foreach($treffer as $string) {
					if(substr($string[1], 0, 7)!="http://") {
						$link = "http://".$string[1];
					} else {
						$link = $string[1];
					}
					$textvar = '<a href="'.$link.'" class="textlink" target="'.$string[2].'">'.$string[3].'</a>';
					$content=str_replace($string[0], $textvar, $content);
				}
				//---------------------------
				$content = str_replace('[b]', '<strong>', str_replace('[/b]', '</strong>', $content));
				$content = str_replace('[i]', '<i>', str_replace('[/i]', '</i>', $content));
				$content = str_replace('[u]', '<u>', str_replace('[/i]', '</u>', $content));
			//}
			return $content;
		} 

    	function basic_get_pagepath($internalurl_segment) {
			
			$ci=& get_instance();
			$ci->load->database(); 

			$sqlstr_page = 'SELECT * FROM ffwbs_pages WHERE page_name="'.$internalurl_segment[3].'"';
			$query_page = $ci->db->query($sqlstr_page);
			$page = $query_page->row_array();

			return $page['path'];

		}


		/*
		|--------------------------------------------------------------------------
		| Admin Log Schreiber
		|--------------------------------------------------------------------------
		|
		| Schreibt den eintrag aus einer Admin-Funktion in den LOG
		|   msg   =>  Der Eintrag für den Log (die Aktion)
		|   func  =>  Die betroffene Funktion
		|
		*/

		function basic_writelog($msg, $func, $level) {
			
			$ci=& get_instance();
			$ci->load->database();
			
			date_default_timezone_set("Europe/Berlin");
			$datum = date("Y")."-".date("m")."-".date("d")." ".date("G:i:s");

		    $data_adminuser = array(
			   'userID' => ''.$_SESSION["userID"].'' ,
			   'action' => ''.$msg.'' ,
			   'function' => ''.$func.'' ,
			   'datum' => ''.$datum.'' ,
			   'level' => ''.$level.''
			);
			$ci->db->insert('admin_log_action', $data_adminuser); 

		}

		/*
		|--------------------------------------------------------------------------
		| Admin Log Schreiber
		|--------------------------------------------------------------------------
		|
		| Schreibt den eintrag aus einer Admin-Funktion in den LOG
		|   msg   =>  Der Eintrag für den Log (die Aktion)
		|   func  =>  Die betroffene Funktion
		|
		*/

		function basic_get_moduleID($name) {
			
			$ci=& get_instance();
			$ci->load->database(); 

			$sqlstr_page = 'SELECT * FROM ffwbs_contentmodules WHERE name="'.$name.'"';
			$query_page = $ci->db->query($sqlstr_page);
			$contentmodule = $query_page->row_array();

			return $contentmodule['contentmoduleID'];

		}