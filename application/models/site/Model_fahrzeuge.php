<?php
class model_fahrzeuge extends CI_Model {

		public function get_fahrzeugdetails() {
	
			$ch = curl_init();
			$carID =$this->uri->rsegment(4);

			$query_string = 'SELECT * FROM ffwbs_fahrzeuge WHERE fahrzeugID="'.$carID.'"';
			$query = $this->db->query($query_string);
			$fahrzeugarray = $query->row_array();
			
			if($query->num_rows() > 0) {
				// Ausrüstung laden
				$query_string = 'SELECT * FROM ffwbs_fahrzeuge_ausruestung WHERE fahrzeugID="'.$fahrzeugarray['fahrzeugID'].'"';
				$query = $this->db->query($query_string);
				$toolvarDB = $query->result_array();

				$alltools = array();
				foreach ($toolvarDB as $toolvar) {
					$tool = array();
					$array = explode("=", $toolvar['content']);

					$tool['headline'] = $toolvar['headline'];
					$tool['subline'] = $toolvar['subline'];

					$tool['type']=$array[0];
					if($tool['type']=='list') {
						$tool['value']=explode(';', $array[1]);
					} else {
						$tool['value']=$array[1];
					}
					$alltools[] = $tool;
				}
				$fahrzeugarray['tools'] = $alltools;

				// Bilder Galerie laden
	            $this->load->helper('file');
		        $folder = './frontend/images_cms/fahrzeuge/galerie/'.$fahrzeugarray['fahrzeugID'];
	           	$fahrzeugarray['gallery'] = get_filenames($folder);
	        } else {
	        	$fahrzeugarray = "404";
	        }

			return $fahrzeugarray;
			
		}

		public function get_fahrzeugdetails_byID($id) {
	
			$query_string = 'SELECT * FROM ffwbs_fahrzeuge WHERE fahrzeugID="'.$id.'"';
			$query = $this->db->query($query_string);

			return $query->row_array();
			
		}

		public function get_fahrzeugliste() {
	
			if($GLOBALS['location']=="all") {
				$query_string = 'SELECT f.* FROM ffwbs_fahrzeuge f JOIN ffwbs_wehren w ON f.wehrID = w.wehrID AND f.online="1" ORDER BY w.sort ASC, f.sort ASC';
				$query = $this->db->query($query_string);			
			} else {
				$query_string = 'SELECT * FROM ffwbs_fahrzeuge WHERE wehrID="'.$GLOBALS['location'].'" AND online="1" ORDER BY sort';
				$query = $this->db->query($query_string);	
			}
			return $query->result_array();
			
		}


	/*
	|--------------------------------------------------------------------------
	| Menüpunkte ermitteln (Alle Fahrzeugnamen)
	|--------------------------------------------------------------------------
	*/	
	public function get_menuitems($i) {
		$ch = curl_init();
		
		if($GLOBALS['location']=="all") {
			$query = $this->db->query('SELECT * FROM ffwbs_fahrzeuge WHERE language="'.$GLOBALS['language'].'" AND online="1" ORDER BY sort ASC');
		} else {
			$query = $this->db->query('SELECT * FROM ffwbs_fahrzeuge WHERE wehrID="'.$GLOBALS['location'].'" AND language="'.$GLOBALS['language'].'" AND online="1" ORDER BY sort ASC');
		}
		$auto_items = $query->result_array();

		for($i=0; $i<count($auto_items); $i++) {
			$auto_items[$i]['name'] = '<h3>'.$auto_items[$i]['shortname'].'</h3><h2>'.$auto_items[$i]['name'].'</h2>';
			$auto_items[$i]['modulepath'] = '/'.$auto_items[$i]['fahrzeugID'].'/'.curl_escape($ch, str_replace("/", "_", $auto_items[$i]['shortname']));
		}

		return $auto_items;

	}

}