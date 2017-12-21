<?php
class model_termine extends CI_Model {

	
	public function get_termine() {

		if($GLOBALS['location']=='all') {	
			$sql='SELECT * FROM ffwbs_termine WHERE date_anfang>="'.basic_get_date().' '.basic_get_time().'" AND online="1" ORDER BY date_anfang ASC';
		} else {
			$sql='SELECT * FROM ffwbs_termine WHERE (wehrID="'.$GLOBALS['location'].'" OR wehrID="0") AND date_anfang>="'.basic_get_date().' '.basic_get_time().'" AND online="1" ORDER BY date_anfang ASC';
		}
		$query = $this->db->query($sql);
		$termine = $query->result_array();	

		for($i=0; $i<count($termine); $i++) {
			
			$datetime = $termine[$i]['date_anfang'];

			$termine[$i]['datum_von'] = basic_get_ger_datetime($termine[$i]['date_anfang'], 'dateonly', 2);
			$termine[$i]['zeit_von'] = basic_get_ger_datetime($termine[$i]['date_anfang'], 'time', 2);

			$termine[$i]['datum_bis'] = basic_get_ger_datetime($termine[$i]['date_ende'], 'dateonly', 2);
			$termine[$i]['zeit_bis'] = basic_get_ger_datetime($termine[$i]['date_ende'], 'time', 2);
			
			$termine[$i]['tag'] = basic_get_datedetail($datetime, 'tag');
			$termine[$i]['wochentag'] = basic_get_datedetail($datetime, 'wochentag');
			$termine[$i]['monat'] = basic_get_datedetail($datetime, 'monat');
			$termine[$i]['jahr'] = basic_get_datedetail($datetime, 'jahr');

			if($termine[$i]['wehrID']!=0) {
				$termine[$i]['feuerwehr'] = 'FF '.basicffw_get_vereindetails_singlevar($termine[$i]['wehrID'], 'ort');
			} else {
				$termine[$i]['feuerwehr'] = 'Allgemein';
			}

		}
		
		return $termine;

	}


	/*
	|--------------------------------------------------------------------------
	| Startpage News abrufen (Limit auf 3 Items gesetzt)
	|--------------------------------------------------------------------------
	*/
	public function get_startpagetermine() {
	
		if($GLOBALS['location']=='all') {	
			$query = $this->db->query('SELECT * FROM ffwbs_termine WHERE date_anfang>="'.basic_get_date().' '.basic_get_time().'" AND online="1" ORDER BY date_anfang ASC LIMIT 3');
		} else {
			$query = $this->db->query('SELECT * FROM ffwbs_termine WHERE wehrID="'.$GLOBALS['location'].'" OR wehrID="0" AND date_anfang>="'.basic_get_date().' '.basic_get_time().'" AND online="1" ORDER BY date_anfang ASC LIMIT 3');
		}

		if($query->num_rows() > 0) {
			$termine = $query->result_array();	
	
			// Datum parsen
			for($i=0; $i<count($termine); $i++) {
				$termine[$i]['date_anfang']=basic_get_ger_datetime($termine[$i]['date_anfang'], 'datetime', 2);
			}
		
		} else {
			$termine = "NO_TERMIN";
		}
			
		return $termine;

	}


	/*
	|--------------------------------------------------------------------------
	| Menüpunkte ermitteln (Limit 6)
	|--------------------------------------------------------------------------
	*/	
	public function get_menuitems($i) {
		$ch = curl_init();

		if($GLOBALS['location']=="all") {
			$query = $this->db->query('SELECT * FROM ffwbs_termine WHERE date_anfang>="'.basic_get_date().' '.basic_get_time().'" AND online="1" ORDER BY date_anfang DESC LIMIT 6');
		} else {
			$query = $this->db->query('SELECT * FROM ffwbs_termine WHERE date_anfang>="'.basic_get_date().' '.basic_get_time().'" AND online="1" AND wehrID="'.$GLOBALS['location'].'" OR wehrID="0" ORDER BY date_anfang DESC LIMIT 6');
		}
		$auto_items = $query->result_array();

		for($i=0; $i<count($auto_items); $i++) {
			$auto_items[$i]['name'] = '<h3>'.basic_get_ger_datetime($auto_items[$i]['date_anfang'], 'datetime', 2).'</h3>'.$auto_items[$i]['headline'];
			$auto_items[$i]['modulepath'] = '';
		}

		return $auto_items;
	}

}