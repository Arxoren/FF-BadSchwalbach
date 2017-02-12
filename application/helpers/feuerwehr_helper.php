<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 



	/*
	|--------------------------------------------------------------------------
	| Alarm-Icon Klasse ermitteln
	|--------------------------------------------------------------------------
	|
	| Gibt die Klasse für das Alarm-Icon zurück
	|
	| $type => [string] Gibt den type des Einsatzes an die Funktion
	|
	*/
	function basicffw_get_alarmtype($type) {
		switch($type) {
			case "Brandeinsatz": $alarmicon='firealarm'; break;
			case "Fehlalarm": $alarmicon='falsealarm'; break;
			case "Hilfeleistung": $alarmicon='helpalarm'; break;
			case "Gefahrenguteinsatz": $alarmicon='hazardouse'; break;
			default: $alarmicon=''; break;;
		}
		return $alarmicon;
	}


	/*
	|--------------------------------------------------------------------------
	| Vereinsdetails
	|--------------------------------------------------------------------------
	|
	| Gibt die Vereinsdetails zurück
	|
	*/
	function basicffw_get_vereindetails($vereinID) {
		$CI =& get_instance();
		
		if($vereinID!=0) {
			$query_string = 'SELECT * FROM ffwbs_wehren WHERE wehrID="'.$vereinID.'" LIMIT 1';
			$query = $CI->db->query($query_string);
			$var = $query->row_array();
		} else {
			$var['wehrID'] = 0;
			$var['wehr_name'] = 'Alle Wehren';
		}	
		
		return $var; 
	}	
	function basicffw_get_vereindetails_singlevar($vereinID, $var) {
		$CI =& get_instance();
		
		$query_string = 'SELECT * FROM ffwbs_wehren WHERE wehrID="'.$vereinID.'" LIMIT 1';
		$query = $CI->db->query($query_string);	
		$verein = $query->row_array();
		
		return $verein[$var]; 
	}		
	function basicffw_get_wehrlist() {
		$CI =& get_instance();
		
		$query_string = 'SELECT ort, wehrID  FROM ffwbs_wehren WHERE online="1" ORDER BY sort ASC';
		$query = $CI->db->query($query_string);	
		return $query->result_array(); 
	}


	/*
	|--------------------------------------------------------------------------
	| Position und Titel ermitteln
	|--------------------------------------------------------------------------
	*/
	function basicffw_get_position($type) {
		switch($type) {
			case "1": $title=', Gruppenf&uuml;hrer'; break;
			case "2": $title=', Zugf&uuml;hrer'; break;
			case "3": $title=', stellv. Wehrf&uuml;hrer'; break;
			case "4": $title=', Wehrf&uuml;hrer'; break;
			case "5": $title=', stellv. Stadtbrandinspektor'; break;
			case "6": $title=', Stadtbrandinspektor'; break;
			default: $title=''; break;
		}
		return $title;
	}
	function basicffw_get_rang($rang, $geschlecht) {
		$rang_array = array();
		
		if($geschlecht=='m'){
			switch($rang) {
				case "1": $rang_array['name']='Feuerwehrmann'; $rang_array['bild']='feuerwehrmann'; break;
				case "2": $rang_array['name']='Oberfeuerwehrmann'; $rang_array['bild']='oberfeuerwehrmann'; break;
				case "3": $rang_array['name']='Hauptfeuerwehrmann'; $rang_array['bild']='hauptfeuerwehrmann'; break;
				case "4": $rang_array['name']='Löschmeister'; $rang_array['bild']='loeschmeister'; break;
				case "5": $rang_array['name']='Oberlöschmeister'; $rang_array['bild']='oberloeschmeister'; break;
				case "6": $rang_array['name']='Hauptlöschmeister'; $rang_array['bild']='hauptloeschmeister'; break;
				case "7": $rang_array['name']='Brandmeister'; $rang_array['bild']='brandmeister'; break;
				case "8": $rang_array['name']='Oberbrandmeister'; $rang_array['bild']='oberbrandmeister'; break;
				case "9": $rang_array['name']='Hauptbrandmeister'; $rang_array['bild']='hauptbrandmeister'; break;
				default: $rang_array['name']='Feuerwehrmann-Anwärter'; $rang_array['bild']='anwaerter'; break;
			}
		} else {
			switch($rang) {
				case "1": $rang_array['name']='Feuerwehrfrau'; $rang_array['bild']='feuerwehrmann'; break;
				case "2": $rang_array['name']='Oberfeuerwehrfrau'; $rang_array['bild']='oberfeuerwehrmann'; break;
				case "3": $rang_array['name']='Hauptfeuerwehrfrau'; $rang_array['bild']='hauptfeuerwehrmann'; break;
				case "4": $rang_array['name']='Löschmeisterin'; $rang_array['bild']='loeschmeister'; break;
				case "5": $rang_array['name']='Oberlöschmeisterin'; $rang_array['bild']='oberloeschmeister'; break;
				case "6": $rang_array['name']='Hauptlöschmeisterin'; $rang_array['bild']='hauptloeschmeister'; break;
				case "7": $rang_array['name']='Brandmeisterin'; $rang_array['bild']='brandmeister'; break;
				case "8": $rang_array['name']='Oberbrandmeisterin'; $rang_array['bild']='oberbrandmeister'; break;
				case "9": $rang_array['name']='Hauptbrandmeisterin'; $rang_array['bild']='hauptbrandmeister'; break;
				default: $rang_array['name']='Feuerwehrfrau-Anwärterin'; $rang_array['bild']='anwaerter'; break;
			}		}

		return $rang_array;
	}


	/*
	|--------------------------------------------------------------------------
	| Fahrzeuge ermitteln
	|--------------------------------------------------------------------------
	*/
	function basicffw_get_fahrzeuglist() {
		$CI =& get_instance();
		
		$query_string = 'SELECT f.* FROM ffwbs_fahrzeuge f JOIN ffwbs_wehren w ON f.wehrID = w.wehrID AND f.online="1" ORDER BY w.sort ASC';
		$query = $CI->db->query($query_string);	
		return $query->result_array(); 
	}
