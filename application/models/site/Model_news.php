<?php
class model_news extends CI_Model {

	
	public function get_newsliste() {

		if($GLOBALS['location']=="all") {
			$sql = 'SELECT * FROM ffwbs_news WHERE online="1" ORDER BY date DESC';
		} else {
			$sql = 'SELECT * FROM ffwbs_news WHERE online="1" AND (wehrID="'.$GLOBALS['location'].'" OR wehrID="0") ORDER BY date DESC';
		}
		$query = $this->db->query($sql);
		$news = $query->result_array();	

		$news = $this->category_einweben($news);

		return $news;
	}

	public function get_news_details() {
	
		$n = ($this->uri->total_segments())-2;

		$query = $this->db->query('SELECT * FROM ffwbs_news WHERE newsID="'.$this->uri->rsegment($n).'"');
		$news_array = $query->row_array();

		if($news_array['wehrID']==0) {
		    $news_array['category'] = "Allgemein";
		} else {    
            $news_array['category'] = "FFW ".basicffw_get_vereindetails_singlevar($news_array['wehrID'], 'ort');
        }

        if($news_array['online']!=0) {
			$query = $this->db->query('SELECT * FROM ffwbs_news_modules WHERE online="1" AND newsID="'.$this->uri->rsegment($n).'" ORDER BY sort ASC');
			$news_array['module'] = $query->result_array();
		} else {
			$news_array = 404;
		}
		return $news_array;
			
	}

	/*
	|--------------------------------------------------------------------------
	| Startpage News abrufen (Limit auf 8 News gesetzt)
	|--------------------------------------------------------------------------
	*/
	public function get_startpagenews() {
	
		if($GLOBALS['location']=="all") {
			$query = $this->db->query('SELECT * FROM ffwbs_news WHERE online="1" ORDER BY date DESC LIMIT 9');
		} else {
			$query = $this->db->query('SELECT * FROM ffwbs_news WHERE online="1" AND (wehrID="'.$GLOBALS['location'].'" OR wehrID="0") ORDER BY date DESC LIMIT 9');
		}			

		$news_array = $query->result_array();

		// Wehrname einwegeb in das Array
		$news_array = $this->category_einweben($news_array);

		return $news_array;
			
	}


	/*
	|--------------------------------------------------------------------------
	| Kategorie einweben
	|--------------------------------------------------------------------------
	|
	| Der Wehrname wird ermittelt und als ['category']-array-item in den newsdatensatz eingewoben
	|
	*/
	function category_einweben($news_array) {
		$i=0;

		foreach($news_array as $news) {
		    if($news['wehrID']==0) {
		    	$news_array[$i]['category'] = "Allgemein";
		    } else {    
                $news_array[$i]['category'] = "FF ".basicffw_get_vereindetails_singlevar($news['wehrID'], 'ort');
            }
            $i++;
   		}

   		return $news_array;
	}


	/*
	|--------------------------------------------------------------------------
	| Die letzten drei News ermitteln (für die Teaser_row)
	|--------------------------------------------------------------------------
	*/	
	public function get_last_news() {
		$ch = curl_init();
		
		if($GLOBALS['location']=="all") {
			$query = $this->db->query('SELECT * FROM ffwbs_news WHERE online="1" ORDER BY date DESC LIMIT 3');
		} else {
			$query = $this->db->query('SELECT * FROM ffwbs_news WHERE online="1" AND wehrID="'.$GLOBALS['location'].'" OR wehrID="0" ORDER BY date DESC LIMIT 3');
		}
		$news_items = $query->result_array();

		for($i=0; $i<count($news_items); $i++) {
			
			$news_items[$i]['image']=base_url().'frontend/images_cms/news/news_'.$news_items[$i]['newsID'].'_big.jpg';
			$news_items[$i]['link']=base_url().$GLOBALS['varpath'].'/aktuelles/news/'.$news_items[$i]['newsID'].'/'.curl_escape($ch, $news_items[$i]["headline"]);

			$news_items[$i]['content']='
          	<h2>'.$news_items[$i]['headline'].'</h2>
			<h3>'.basic_get_ger_datetime($news_items[$i]['date'], 'datetime', 2).'</h3>
          	';
		}

		return $news_items;
	}


	/*
	|--------------------------------------------------------------------------
	| Menüpunkte ermitteln (Limit 8)
	|--------------------------------------------------------------------------
	*/	
	public function get_menuitems($i) {
		$ch = curl_init();

		if($GLOBALS['location']=="all") {
			$query = $this->db->query('SELECT * FROM ffwbs_news WHERE online="1" ORDER BY date DESC LIMIT 8');
		} else {
			$query = $this->db->query('SELECT * FROM ffwbs_news WHERE online="1" AND wehrID="'.$GLOBALS['location'].'" OR wehrID="0" ORDER BY date DESC LIMIT 8');
		}
		$auto_items = $query->result_array();

		for($i=0; $i<count($auto_items); $i++) {
			$auto_items[$i]['name'] = $auto_items[$i]['headline'];
			$auto_items[$i]['modulepath'] = '/'.$auto_items[$i]['newsID'].'/'.curl_escape($ch, $auto_items[$i]['headline']);
		}

		return $auto_items;
	}

}