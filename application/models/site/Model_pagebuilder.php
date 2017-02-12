<?php
class model_pagebuilder extends CI_Model {

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
		
		// EDITABLE TAG   !!! ACHTUNG !!!
		// Wird nur für den Adminbereich benötigt, daher wird er hier Frontend
		// auf <LEER> gesetzt, so dass Inhalte nicht bearbeitet werden können
		// --------------------------------------------------------------------
		$GLOBALS['editable_tag'] = "";
		
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

	}


	/*
	|--------------------------------------------------------------------------
	| Pages und die zugehörigen Module aus der Datenbank laden 
	|--------------------------------------------------------------------------
	*/
	public function get_page() {

		if($this->uri->rsegment(3)) {
			$query = $this->db->query('SELECT * FROM ffwbs_pages WHERE page_name="'.$this->uri->rsegment(3).'" LIMIT 1');
		} else {
			$query = $this->db->query('SELECT * FROM ffwbs_pages WHERE startpage="1" LIMIT 1');
		}
       	if ($this->db->affected_rows()!=0) {	
			return $query->row_array();	
		} else {
			return "404";	
		}
	
	}

	public function get_content_modules($pageID, $subpage) {
		
		$sql_query ='SELECT * FROM ffwbs_page_modules WHERE pageID="'.$pageID.'" AND online="1" AND subpage_module="'.$subpage.'" ORDER BY sort ASC';
		$query = $this->db->query($sql_query);
		$content_modules = $query->result_array();

		$this->get_headerassets($content_modules);
		
		return $content_modules;
	}

	public function get_content($content) {

		$test=preg_match_all("#\[(.*?)]#si", $content, $treffer, PREG_SET_ORDER);
		$content = array();

		for($i=0; $i<count($treffer); $i++) {
			$replace_array = array("[", "]");
			$treffer[$i][0]=str_replace($replace_array, "", $treffer[$i][0]);
			$content_items=explode("::", $treffer[$i][0]);
			$content[$content_items[0]]=$content_items[1];
		}

		return $content;
	}


	/*
	|--------------------------------------------------------------------------
	| HEADER ASSETS LADEN
	|--------------------------------------------------------------------------
	| Zusatzliche Script Assets werden geladen um bestimmte Funktioen zu 
	| gewährleisten die nicht auf allen Seiten relevatnt sind.
	| 
	| Dazu wird der "model_type" eines jeden Content-Modules überprüft ob eines 
	| der Module ein spezielles Asset-Libary benötigt.
	| 
	| "charts" -> Scripte für Graphen und Diagramme laden  
	|--------------------------------------------------------------------------
	*/
	public function get_headerassets($content_modules) {
		
		if(array_search("charts", array_column($content_modules, 'model_type')) !="") {
			$GLOBALS["header_assets"] = "load_charts";
		} else {
			$GLOBALS["header_assets"] = "";
		}

	}


	/*
	|--------------------------------------------------------------------------
	| Navigationsgruppen ermitteln 
	|--------------------------------------------------------------------------
	*/
	public function get_navigation_groups() {
		$sql_query ='SELECT * FROM ffwbs_navigation_groups';
		$query = $this->db->query($sql_query);
		return $query->result_array();
	}

	/*
	|--------------------------------------------------------------------------
	| Erste Ebene der Navigation nach Gruppierung ermitteln
	|--------------------------------------------------------------------------
	| $ navgroup => Gibt die Kategorie a welche Punkte geladen werden sollen (z.B. Main-Navigation, ...)
	| 
	| ERGEBNIS: 'ARRAY'
	| Das Array wird sortiert nach Reiehenfolge der Ebenen zurückgegeben 
	|--------------------------------------------------------------------------
	*/
	public function get_menue($navgroup) {
			
		$menue_array = array();
		if($GLOBALS['location']=="all") { 
			$navWehrID=0; 
		} else {
			$navWehrID=$GLOBALS['location'];
		}

		//$sqlstr = 'SELECT * FROM ffwbs_navigation WHERE nav_group="'.$navgroup.'" AND language="'.$GLOBALS['language'].'" AND online="1" AND subcategory="0" ORDER BY sort ASC';

		$sqlstr = 'SELECT m.* FROM ffwbs_navigation m INNER JOIN ffwbs_navigation_zuordnung z ON m.navID=z.navID AND z.wehrID="'.$navWehrID.'" WHERE m.nav_group="'.$navgroup.'" AND m.language="'.$GLOBALS['language'].'" AND m.online="1" AND m.subcategory="0" ORDER BY sort ASC';
		$query = $this->db->query($sqlstr);
		$menue = $query->result_array();

		$menue_array = $this->get_submenue($menue, 0, $menue_array, $navWehrID);

		/*
		foreach($menue_array as $test) {
			echo $test['level'].' // '.$test['subcategory'].' ->> '.$test['label'].' >> '.$test['navID'].'<br>';
		}
		*/

		return $menue_array;
	}

	/*
	|--------------------------------------------------------------------------
	| Rekursives ermitteln der Unterpunkte
	|--------------------------------------------------------------------------
	*/
	private function get_submenue($items, $var, $menue_array, $navWehrID) {

		$var++;

		for($i=0; $i<count($items); $i++) {
				
			$items[$i]['level'] = $var;
			if($items[$i]['pagesID']!=0) {	
				$items[$i]['path'] = $this->get_path($items[$i]['pagesID']);
			}
			$menue_array[] = $items[$i];

			// Automatische Menüpunkte aus einer DB laden (z.B. Fahrzeuge oder News)
			if($items[$i]['auto_subcategories']!="") {

				if($items[$i]['auto_subcategories']=="_blank") {

				} else {
					$CI =& get_instance();
					$CI->load->model('site/model_'.$items[$i]['auto_subcategories'].'');
					$model = 'model_'.$items[$i]['auto_subcategories'];
					$auto_items = $CI->$model->get_menuitems($i);				

					foreach($auto_items as $menueitem) {
						$autoitems[$i]['level'] = $var+1;
						$autoitems[$i]['label'] = $menueitem['name'];
						$autoitems[$i]['path'] = $items[$i]['path'].$menueitem['modulepath'];
						$autoitems[$i]['auto_subcategories'] = '';

						$menue_array[] = $autoitems[$i]; 
					}
				}
			} else {
				$items[$i]['path'] = $this->get_path($items[$i]['path']);
			}

			//$sqlstr = 'SELECT * FROM ffwbs_navigation WHERE language="'.$GLOBALS['language'].'" AND online="1" AND subcategory="'.$items[$i]['navID'].'"';
			$sqlstr = 'SELECT m.* FROM ffwbs_navigation m INNER JOIN ffwbs_navigation_zuordnung z ON m.navID=z.navID AND z.wehrID="'.$navWehrID.'" WHERE m.language="'.$GLOBALS['language'].'" AND m.online="1" AND m.subcategory="'.$items[$i]['navID'].'" ORDER BY sort ASC';
			$query = $this->db->query($sqlstr);
			$menue = $query->result_array();

			if($query->num_rows()!=0) {
				$menue_array = $this->get_submenue($menue, $var, $menue_array, $navWehrID);
			}
		}
		return $menue_array;
	}

	private function get_path($id) {

		$sqlstr = 'SELECT * FROM ffwbs_pages WHERE pagesID="'.$id.'"';
		$query = $this->db->query($sqlstr);
		$navdetails = $query->row_array();

		return $navdetails['path'];
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


	/*
	|--------------------------------------------------------------------------
	| Open Graph Data laden
	|--------------------------------------------------------------------------
	| Um das Link-Sharing zu optimieren werden Open-Graph Meta-Tags im Header verwendet.
	| Der Inhalt dieser Tags wird hier erzeugt.
	|--------------------------------------------------------------------------
	*/
	public function get_opengraphdata($content_modules, $meta_data, $check_subpage) {
		
		//echo "<br>------------------------------------------<br>";
		//echo print_r($meta_data);
		//echo "<br>------------------------------------------<br><br>";

		$opengraph = array (
			'site' => $GLOBALS["project_domain"],
			'url' => current_url(),
			'title' => str_replace("_", " ", $meta_data["page_name"]),
			'type' => 'article',
			'description' => $GLOBALS["seo_page_desc"],
			'image' => base_url().'frontend/images/og_images/'.$GLOBALS["og_standard_image"]
		);

		if($meta_data["model"]=="news" && $check_subpage==1) {

			$n = ($this->uri->total_segments())-2;

			$query = $this->db->query('SELECT * FROM ffwbs_news WHERE newsID="'.$this->uri->rsegment($n).'"');
			$news_array = $query->row_array();

			$opengraph['image'] = base_url().'frontend/images_cms/news/news_'.$news_array['newsID'].'_big.jpg';
			$opengraph['title'] = $news_array['headline'];
			$opengraph['description'] = $news_array['text'];
		}

		if($meta_data["model"]=="cms") {

			//$opengraph['description'] = $news_array['text'];

		}

		//	Feuerwehr spezifische Funktionen
		// --------------------------------------------------------------

		if($this->uri->segment(2)!="allewehren") {
			$opengraph['image'] = base_url().'frontend/images/og_images/'.'og_feuerwehr-'.$this->uri->segment(2).'.jpg';
		}

		if($meta_data["model"]=="einsatz" && $check_subpage==1) {

			$query = $this->db->query('SELECT * FROM ffwbs_einsatz WHERE einsatzID="'.$this->uri->rsegment(5).'"');
			$einsatzarray = $query->row_array();

			switch($einsatzarray['type']) {
				case "Fehlalarm": $img = base_url().'frontend/images/og_images/og_einsatz_fehlalarm.jpg'; break;
				case "Hilfeleistung": $img = base_url().'frontend/images/og_images/og_einsatz_hilfe.jpg'; break;
				case "Gefahrenguteinsatz": $img = base_url().'frontend/images/og_images/og_einsatz_gefahr.jpg'; break;
				case "Brandeinsatz": $img = base_url().'frontend/images/og_images/og_einsatz_brand.jpg'; break;
			}

			$opengraph['image'] = $img;
			$opengraph['title'] = $einsatzarray['title'];
			$opengraph['description'] = $einsatzarray['text_short'];
		}

		if($meta_data["model"]=="fahrzeuge" && $check_subpage==1) {

			$query = $this->db->query('SELECT * FROM ffwbs_fahrzeuge WHERE fahrzeugID="'.$this->uri->rsegment(4).'"');
			$fahrzeugarray = $query->row_array();

			$opengraph['image'] = base_url().'frontend/images_cms/fahrzeuge/stages/'.str_replace(" ", "", str_replace("/", "", $fahrzeugarray['shortname'])).'_'.$fahrzeugarray['fahrzeugID'].'.png';
			$opengraph['title'] = $fahrzeugarray['shortname'].' - '.$fahrzeugarray['name'];
			$opengraph['description'] = $fahrzeugarray['description'];
		}

		return $opengraph;
	}

}

?>