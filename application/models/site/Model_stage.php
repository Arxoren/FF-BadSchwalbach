<?php
class model_stage extends CI_Model {


		public function get_bigstage_image($id) {

			$sql = 'SELECT * FROM ffwbs_zuordnung_var WHERE wehrID="'.$GLOBALS['akt_wehr_details']['wehrID'].'"';
			$query = $this->db->query($sql);
			$stages = $query->row_array();
			$stage_id = explode(":", $stages["value"]);
			$stage_content = array();

			foreach($stage_id as $id) {
				$sql_stage = 'SELECT * FROM ffwbs_stages WHERE stageID="'.$id.'"';
				$query_stage = $this->db->query($sql_stage);
				$var_stage = $query_stage->row_array();
				array_push($stage_content, $var_stage);
			}

			if(count($stage_content)==0) {
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