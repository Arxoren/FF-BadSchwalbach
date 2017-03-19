<?php
class model_adminpagebuilder extends CI_Model {

	/*
	|--------------------------------------------------------------------------
	| Globale Variablen und Settings aus der Datenbank laden
	|--------------------------------------------------------------------------
	*/
	public function set_globals() {
		$query = $this->db->query('SELECT * FROM ffwbs_globals');
		$gloabals_array = $query->result_array();

		foreach($gloabals_array as $globals) {
			$GLOBALS[$globals['name']]=$globals['value'];
		}
		
		// SET ADMIN
		// --------------------------------------------------------------------
		if($this->uri->segment(1)=="admin") { 
			$GLOBALS['editable_tag']=' contenteditable="true"'; 
		} else { 
			$GLOBALS['editable_tag']=''; 
		}


		// SET LOCATION
		// Ermittelt die ID der in der URL gefundenen Location Angabe
		// --------------------------------------------------------------------
		if($this->uri->segment(2)!="" && $this->uri->segment(2)!="allewehren") {
			$query = $this->db->query('SELECT * FROM ffwbs_wehren WHERE pfad="'.$this->uri->segment(2).'" LIMIT 1');
			$wehrID = $query->row_array();
			$location_link = $this->uri->segment(2);
			$GLOBALS['location_link'] = $wehrID['ort'];
			$GLOBALS['akt_wehr_details'] = $wehrID;
		} else {
			$wehrID['wehrID'] ='all';
			$location_link = 'allewehren';
			$GLOBALS['location_link'] = 'Alle Feuerwehren';
			$GLOBALS['akt_wehr_details']['wehrID'] = 0;
		}
		$GLOBALS['location']=$wehrID['wehrID'];

		// SET LANGUAGE
		// --------------------------------------------------------------------
		if($this->uri->segment(1)!="") {
			$language = $this->uri->segment(1);
		} else {
			$language = $GLOBALS['lang_default'];
		}	
		$GLOBALS['language'] = $language;
		
		// Speichern der aktuellen Sprache/Feuerwehr Einstellung
		// --------------------------------------------------------------------
		$GLOBALS['varpath'] = $GLOBALS['language'].'/'.$location_link;

		// Speichern des aktuellen links (- Sprache/Feuerwehr)
		// --------------------------------------------------------------------
		$segmentarray = $this->uri->segment_array();
		$GLOBALS['aktpath'] = "";
		for($i=3; $i<=count($segmentarray); $i++) {	
			$GLOBALS['aktpath'] = $GLOBALS['aktpath'].'/'.$segmentarray[$i];
		}

		// Message Variable initialisieren
		// Wenn eine Messega ausgegeben werden muss dann wird diee befüllt
		// --------------------------------------------------------------------
		$GLOBALS['globalmessage'] = "";
		
	}


	/*
	|--------------------------------------------------------------------------
	| Pages und die zugehörigen Module aus der Datenbank laden 
	|--------------------------------------------------------------------------
	*/
	public function get_page() {

			$query = $this->db->query('SELECT * FROM ffwbs_admin_function WHERE var="'.$_GET["op"].'" LIMIT 1');
			
	       	if ($this->db->affected_rows()!=0) {	
				return $query->row_array();	
			} else {
				return "404";	
			}
	
	}
	

	public function get_navigation() {
			
		$mainnavi = array("content", "media", "module", "config");
		$navi = array();

		if($_SESSION["secondnavilist"]=="") {
			$_SESSION["secondnavilist"]="content";
		}

		foreach($mainnavi as $kat) {
			$query = $this->db->query('SELECT * FROM ffwbs_admin_menue WHERE main="'.$kat.'" ORDER BY sort ASC');
	       	if ($this->db->affected_rows()!=0) {	
				$navi[$kat] = $query->result_array();
			} else {
				$navi[$kat] = "nonavi";	
			}
		}
		return $navi;

	}

	/*
	|--------------------------------------------------------------------------
	| Alle filterbaren Feuerwehren
	|--------------------------------------------------------------------------
	| Alle Inhalte sind theoretisch filterbar nach den einzelnen Feuerwehren (z.B. Einstäze und Fahrzeuge)
	| Statischer Content der sich nicht ändert (z.B Tipps) ignorieren den Filter
	|--------------------------------------------------------------------------
	*/
	public function get_feuerwehren() {
			
		$menue_array = array();

		$sqlstr = 'SELECT * FROM ffwbs_wehren WHERE online="1" ORDER BY sort ASC';
		$query = $this->db->query($sqlstr);
		$wehren = $query->result_array();

		return $wehren;
	}

}

?>