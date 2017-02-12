<?php
class model_mannschaft extends CI_Model {

	
	public function get_einsatzabteilung() {


		if($GLOBALS['location']=="all") {
			$query = $this->db->query('SELECT * FROM ffwbs_mannschaft WHERE online="1" AND wehrID!="0" ORDER BY wehrID ASC, position DESC, nachname ASC, vorname ASC');
		} else {
			$query = $this->db->query('SELECT * FROM ffwbs_mannschaft WHERE online="1" AND wehrID="'.$GLOBALS['location'].'" ORDER BY position DESC, nachname ASC, vorname ASC');
		}
		$member = $query->result_array();
	 	$heute = new DateTime(date('Y-m-d'));

		for($i=0; $i<count($member); $i++) {
			if($member[$i]['gebday']!='0000-00-00') {
				$geburtstag = new DateTime($member[$i]['gebday']);
				$differenz = $geburtstag->diff($heute);
			 
				$member[$i]['alter'] = $differenz->format('%y')." Jahre";
			} else {
				$member[$i]['alter'] = "";
			}
			
			if($member[$i]['beruf']!="") {
		    	if($member[$i]['alter']!="") {  
		          $member[$i]['beruf'] = ", ".$member[$i]['beruf'];
		        }
		    }

		    if($member[$i]['alter']=="" && $member[$i]['beruf']=="") {
		    	$member[$i]['alter'] = "&nbsp;";
		    }

		}

		return $member;
	}


}