<?php
class model_stage extends CI_Model {


		public function get_bigstage_image($id) {

			if($GLOBALS['akt_wehr_details']['wehrID']==0) {	
				$sql = 'SELECT * FROM ffwbs_stages WHERE moduleID="'.$id.'" AND wehrID="'.$GLOBALS['akt_wehr_details']['wehrID'].'" AND online="1" ORDER BY sort ASC';
			} else {
				$sql = 'SELECT * FROM ffwbs_stages WHERE moduleID="'.$id.'" AND wehrID="'.$GLOBALS['akt_wehr_details']['wehrID'].'" OR wehrID="0" AND freeuse="1" AND online="1" ORDER BY sort ASC';
			}
			$query = $this->db->query($sql);
			
			if($query->num_rows() > 0) {
				$stage_content = $query->result_array();
			} else {
				$stage_array = array(
					"image" => "",
					"headline" => "",
					"subline" => "",
					"link" => "",
					"color" => "black",
				);
				$stage_content = array($stage_array);
			}

			return $stage_content;

		}

		public function get_smallstage_image($id) {

			$sql = 'SELECT * FROM ffwbs_stages WHERE moduleID="'.$id.'" AND wehrID="'.$GLOBALS['akt_wehr_details']['wehrID'].'" AND online="1" ORDER BY sort ASC';
			$query = $this->db->query($sql);
			
			if($query->num_rows() > 0) {
				$stage_content = $query->result_array();
			} else {

				$sql = 'SELECT * FROM ffwbs_stages WHERE moduleID="'.$id.'" AND wehrID="0" AND online="1" ORDER BY sort ASC';
				$query = $this->db->query($sql);
	
				if($query->num_rows() > 0) {
					$stage_content = $query->result_array();
				} else {
					$stage_array = array(
						"image" => "",
						"headline" => "",
						"subline" => "",
						"link" => "",
						"color" => "black",
					);
					$stage_content = array($stage_array);
				}
			}

			return $stage_content;

		}


}