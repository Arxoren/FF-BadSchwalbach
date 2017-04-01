<?php
class model_files extends CI_Model {


		public function get_downloadfiles($data) {

			if($data!="") {
				$test=preg_match_all("#\[file::(.*?)]#si", $data, $treffer, PREG_SET_ORDER);
				$content = array();
				$i = 0;

				foreach($treffer as $file) {
					$replace_array = array("[", "]");
					$file[0]=str_replace($replace_array, "", $file[0]);
					$content_items=explode("::", $file[0]);

					$query = $this->db->query('SELECT * FROM ffwbs_files WHERE fileID="'.$content_items[1].'" LIMIT 1');
					$downloadlist[$i] = $query->row_array();
					$i++;
				}
			} else {
				$downloadlist = '';
			}

			return $downloadlist;

		}


}