<?php
class model_verein extends CI_Model {


		public function get_vereinsintro($id) {

			$query = $this->db->query('SELECT * FROM ffwbs_wehren WHERE wehrID="'.$id.'" LIMIT 1');
			return $query->row_array();

		}


}