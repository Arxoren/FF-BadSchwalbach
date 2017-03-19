<?php
class model_table extends CI_Model {

		
		public function get_table($module_data) {
			
			/*
			$query = $this->db->query('SELECT * FROM ffwbs_tables WHERE moduleID="'.$moduleID.'" ORDER BY sort ASC');
			return $query->result_array();
			*/
			
			$tabelID_array = explode(",", $module_data);
			$i = 0;

			foreach($tabelID_array as $tableID) {
				$query = $this->db->query('SELECT * FROM ffwbs_tables WHERE tableID="'.$tableID.'"');
				$tablecontent[$i] = $query->row_array();
				$i++;
			}
			return $tablecontent;

		}


		public function get_table_verein($content) {
			
			//$query = $this->db->query('SELECT * FROM ffwbs_vereine WHERE name="'.$content.'" LIMIT 1');
			//$daten = $query->row_array();	
		
		}

}

?>