<?php
class model_files extends CI_Model {


		public function get_downloadfiles($id) {

			$id_array = explode(":", $id);
			$downloadlist = array();
			$i = 0;
			
			foreach ($id_array as $id) {
				$query = $this->db->query('SELECT * FROM ffwbs_files WHERE fileID="'.$id.'" LIMIT 1');
				$downloadlist[$i] = $query->row_array();
				$i++;
			}

			return $downloadlist;

		}


}