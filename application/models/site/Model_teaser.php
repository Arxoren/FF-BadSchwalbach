<?php
class model_teaser extends CI_Model {


		public function get_teaser_list($content) {

			$test=preg_match_all("#\[(.*?)]#si", $content, $treffer, PREG_SET_ORDER);
			$content = array();

			for($i=0; $i<count($treffer); $i++) {
				$replace_array = array("[", "]");
				$treffer[$i][0]=str_replace($replace_array, "", $treffer[$i][0]);
				$content_items=explode("|", $treffer[$i][0]);
				
				$query = $this->db->query('SELECT * FROM ffwbs_images WHERE imageID="'.$content_items[0].'"');
				$img_array = $query->row_array();

				$content[$i]["image"]=$img_array["folder"].$img_array["name"].'.'.$img_array["format"];
				$content[$i]["intro"]=$content_items[1];
				$content[$i]["text"]=$content_items[2];
				$content[$i]["link"]=$content_items[3];
			}

			return $content;

		}


}