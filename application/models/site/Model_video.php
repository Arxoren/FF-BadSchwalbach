<?php
class model_video extends CI_Model {


	public function get_video($data) {

		/*
		|--------------------------------------------------------------------
		|	Video Datei laden oder Videodienst einbetten
		|--------------------------------------------------------------------
		|	DB_Var_Structure => "TYPE"::"CODE" or "filename"
		|   -------------------------------------------------------
		|	type:file 		=> Videodatei unter "files_cms/video/"
		|	type:youtube	=> Video von youtube einbinden
		|	type:vimeo		=> Video von vimeo einbinden
		|--------------------------------------------------------------------
		*/

		if($data!="") {
			$array = explode("::", str_replace("[", "", str_replace("]", "", $data)));

			if($array[1]=="file") {
				$filedetails = explode(".", $array[2]);
				$video = array(
					"type" => $array[1],
					"file" => $array[2],
					"name" => $filedetails[0],
					"fileend" => $filedetails[1]
				);
			} else {
				$video = array(
					"type" => $array[1],
					"file" => $array[2]
				);
			}
		} else {
			$video = array(
				"type" => "editmodus"
			);
		}

		return $video;
	}


}