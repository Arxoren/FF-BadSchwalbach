<?php
class model_einsatz extends CI_Model {

	public function get_einsatzliste() {
		date_default_timezone_set("Europe/Berlin");
			
        if($this->uri->rsegment(4)=="") {
            $act_year = basic_get_year();
        } else {
            $act_year = $this->uri->rsegment(4);
        }

		if($GLOBALS['location']=="all") {
			$query = $this->db->query('SELECT * FROM ffwbs_einsatz WHERE YEAR(date_start)='.$act_year.' AND online="1" ORDER BY date_start DESC');
		} else {
			$query = $this->db->query('SELECT e.* FROM ffwbs_einsatz e INNER JOIN ffwbs_einsatz_zuordnung z ON e.einsatzID=z.einsatzID AND z.wehrID="'.$GLOBALS['location'].'" WHERE YEAR(e.date_start)='.$act_year.' AND e.online="1" ORDER BY date_start DESC');
		}
		$einsatzarray = $query->result_array();

		$einsatzstats = array(
			"fehlalarm" => 0,
			"hilfeleistung" => 0,
			"gefahrengut" => 0,
			"brandeinsatz" => 0,
			"ueberoertlich" => 0,
			"alle" => count($einsatzarray)
		);

		// Statistik zählen
		for($i=0; $i<count($einsatzarray); $i++) {
			$einsatzarray[$i]['icon']= basicffw_get_alarmtype($einsatzarray[$i]['type']);
			$einsatzarray[$i]['number']= count($einsatzarray)-$i;

			switch($einsatzarray[$i]['type']) {
				case "Fehlalarm": $einsatzstats['fehlalarm']++; break;
				case "Hilfeleistung": $einsatzstats['hilfeleistung']++; break;
				case "Gefahrenguteinsatz": $einsatzstats['gefahrengut']++; break;
				case "Brandeinsatz": $einsatzstats['brandeinsatz']++; break;
			}
			if($einsatzarray[$i]['ueberoertlich']==1) {
				$einsatzstats['ueberoertlich']++; 
			}

		}
		
		$einstazdata['einsatz']=$einsatzarray;
		$einstazdata['stats']=$einsatzstats;
		$einstazdata['detaillink']=basic_get_pagepath($this->uri->rsegment_array());

		return $einstazdata;

	}

	public function get_einsatz() {

		$query = $this->db->query('SELECT * FROM ffwbs_einsatz WHERE einsatzID="'.$this->uri->rsegment(5).'"');
		$einsatzarray = $query->row_array();
		
		// Einsatznummer (laufendes Jahr) ermitteln
		$einsatzarray['number']= $this->get_einsatznummer($einsatzarray['einsatzID']);

		// Passendes Einsatzart Icon ermitteln
		$einsatzarray['icon']= basicffw_get_alarmtype($einsatzarray['type']);

		// Externe Einsatzkräfte in ein Array wandeln
		$einsatzarray['einsatzkraefte'] = explode(":", $einsatzarray['einsatzkraefte']);

		// Image Gallery Array erstellen
		if($einsatzarray['gallery']!="") {	
			$einsatzarray['gallery'] = explode(":", $einsatzarray['gallery']);
		}

		// Fahrzeuge ermitteln
		if($einsatzarray['fahrzeuge']!="") {	
			$einsatzarray['fahrzeuge'] = explode(":", $einsatzarray['fahrzeuge']);
	   		$einsatzarray['fahrzeuge_anzahl'] = count($einsatzarray['fahrzeuge']);
	   		$this->load->model('site/model_fahrzeuge', 'fahrzeug');

	   		for($i=0; $i<count($einsatzarray['fahrzeuge']); $i++) {
				$einsatzarray['fahrzeuge'][$i] = $this->fahrzeug->get_fahrzeugdetails_byID($einsatzarray['fahrzeuge'][$i]);
			}
		} else {
	   		$einsatzarray['fahrzeuge'] = "";
	   		$einsatzarray['fahrzeuge_anzahl'] = 0;
	   	}

		// Wehren ermitteln
		$query_alarmwehren = $this->db->query('SELECT w.ort FROM ffwbs_wehren w INNER JOIN ffwbs_einsatz_zuordnung z ON z.wehrID=w.wehrID AND z.einsatzID="'.$einsatzarray['einsatzID'].'" ORDER BY w.sort ASC');
		$einsatzarray['wehren'] = $query_alarmwehren->result_array();
		$einsatzarray['alamiertewheren'] = "";

		for($i=0; $i<count($einsatzarray['wehren']); $i++) {
			if($einsatzarray['alamiertewheren']=="") {	
				$einsatzarray['alamiertewheren'] = $einsatzarray['alamiertewheren']."FFW ".$einsatzarray['wehren'][$i]['ort'];
			} else {
				$einsatzarray['alamiertewheren'] = $einsatzarray['alamiertewheren'].", FFW ".$einsatzarray['wehren'][$i]['ort'];
			}
		}

		// Einsatzdauer berechnen
		date_default_timezone_set("Europe/Berlin");
		$date_start = new DateTime($einsatzarray['date_start']);
		$date_end = new DateTime($einsatzarray['date_ende']);
		$interval = $date_start->diff($date_end);
		$einsatzarray['dauer_min'] = $interval->format('%i');
		$einsatzarray['dauer_h'] = $interval->format('%H:%I');
			
		$day = $interval->format('%d');
		$hour = $interval->format('%h');
		$min = $interval->format('%i');
		$einsatzarray['dauer_m'] = $min+($hour*60)+(($day*24)*60);

		if($einsatzarray['ort']=="") {
			$einsatzarray['ort'] = "keine Angaben";
		}

		return $einsatzarray;

	}

	/*
	|--------------------------------------------------------------------------
	| Einstazliste für diue Startseite ermitteln (Limit 4)
	|--------------------------------------------------------------------------
	*/
	function get_startpageeinsatz() {
			
		if($GLOBALS['location']=="all") {
			$query = $this->db->query('SELECT * FROM ffwbs_einsatz WHERE online="1" ORDER BY date_start DESC LIMIT 4');
		} else {
			$query = $this->db->query('SELECT e.* FROM ffwbs_einsatz e INNER JOIN ffwbs_einsatz_zuordnung z ON e.einsatzID=z.einsatzID AND z.wehrID="'.$GLOBALS['location'].'" WHERE online="1" ORDER BY date_start DESC LIMIT 4');
		}			
		$einsatzarray = $query->result_array();
			
		for($i=0; $i<count($einsatzarray); $i++) {
			$einsatzarray[$i]['icon']= basicffw_get_alarmtype($einsatzarray[$i]['type']);
			$einsatzarray[$i]['number']= $this->get_einsatznummer($einsatzarray[$i]['einsatzID']);
			$einsatzarray[$i]['modulepath'] = '/'.basic_get_datedetail($einsatzarray[$i]['date_start'], 'jahr').'/'.$einsatzarray[$i]['einsatzID'];
		}

		return $einsatzarray;

	}

	/*
	|--------------------------------------------------------------------------
	| Menüpunkte ermitteln (Einsatzheadlines)
	|--------------------------------------------------------------------------
	*/	
	public function get_menuitems($i) {
		$ch = curl_init();

		if($GLOBALS['location']=="all") {
			$query = $this->db->query('SELECT * FROM ffwbs_einsatz WHERE online="1" ORDER BY date_start DESC LIMIT 8');
		} else {
			$query = $this->db->query('SELECT e.* FROM ffwbs_einsatz e INNER JOIN ffwbs_einsatz_zuordnung z ON e.einsatzID=z.einsatzID AND z.wehrID="'.$GLOBALS['location'].'" WHERE online="1" ORDER BY date_start DESC LIMIT 8');
		}
		$auto_items = $query->result_array();

		for($i=0; $i<count($auto_items); $i++) {
			$auto_items[$i]['name'] = $auto_items[$i]['title'];
			$auto_items[$i]['modulepath'] = '/'.basic_get_datedetail($auto_items[$i]['date_start'], 'jahr').'/'.$auto_items[$i]['einsatzID'];
		}

		return $auto_items;

	}

	/*
	|--------------------------------------------------------------------------
	| Einsatz Nummerierung ermitteln
	|--------------------------------------------------------------------------
	| Ermitttelt die Einsatznummer des jeweiligen Jahres
	|--------------------------------------------------------------------------
	*/	

	private function get_einsatznummer($einsatzID) {

		if($GLOBALS['location']=="all") {
			$query = $this->db->query('SELECT * FROM ffwbs_einsatz WHERE YEAR(date_start)=YEAR(CURDATE()) AND online="1" ORDER BY date_start DESC');
		} else {
			$query = $this->db->query('SELECT e.* FROM ffwbs_einsatz e INNER JOIN ffwbs_einsatz_zuordnung z ON e.einsatzID=z.einsatzID AND z.wehrID="'.$GLOBALS['location'].'" WHERE YEAR(e.date_start)=YEAR(CURDATE()) AND e.online="1" ORDER BY date_start DESC');
		}
		$items = $query->result_array();
		$number = count($items);

		for($i=0; $i<count($items); $i++) {
			$number = count($items)-$i;
			if($items[$i]['einsatzID']==$einsatzID) {
				break;
			}
		}

		return($number);
	}


}