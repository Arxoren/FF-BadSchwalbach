<?php
class model_table extends CI_Model {

		
		public function get_table($moduleID) {
			
			$query = $this->db->query('SELECT * FROM ffwbs_tables WHERE moduleID="'.$moduleID.'" ORDER BY sort ASC');
			return $query->result_array();
		
		}


		public function get_table_verein($content) {
			
			//$query = $this->db->query('SELECT * FROM ffwbs_vereine WHERE name="'.$content.'" LIMIT 1');
			//$daten = $query->row_array();	
		
		}

}

?>