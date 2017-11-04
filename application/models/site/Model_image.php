<?php
class model_image extends CI_Model {

		public function get_imagegallery($data) {

			$test=preg_match_all("#\{img::(.*?)}#si", $data, $treffer, PREG_SET_ORDER);
			$content = array();
			
			for($i=0; $i<count($treffer); $i++) {
				$replace_array = array("{", "}");
				$treffer[$i][0]=str_replace($replace_array, "", $treffer[$i][0]);
				$content_items=explode("::", $treffer[$i][0]);
				
				$sql='SELECT * FROM ffwbs_images WHERE imageID="'.$content_items[1].'"';
				$query = $this->db->query($sql);
				$image_data = $query->row_array();
				
				$content[$i] = $image_data;
			}
			
			return $content;

		}
		
}