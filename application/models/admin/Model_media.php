<?php
class model_media extends CI_Model {


	/*
	|--------------------------------------------------------------------------
	| Die Ordner / Dateien Liste wird geladen
	|--------------------------------------------------------------------------
	*/
	public function media_folder_list() {

		if(isset($_GET["path"])) {

			// --- Wenn ein Unterorder geöffnet wurde
			// working VARS
			$path = str_replace(",", "/", $_GET["path"]);
			$path_segments = explode(",", $_GET["path"]);
			$var['media_path'] = implode("/", $path_segments);

			// Ausgabe VARS					
			$var['headline'] = ucfirst(str_replace("/", "", end($path_segments))); // --- Letztes Segment = Aktueller Ordner
			array_unshift($path_segments, "".$_GET["type"]."_cms");
			array_pop($path_segments);
			$var['path'] = implode("/", $path_segments);

			if($_GET["type"]=="images") {
				// -- IMAGES aus der DB laden
				$var['images'] = $this->media_get_images($path);
			} else {
				// -- FILES aus der DB laden
				$var['files'] = $this->media_get_files($path);
			}
		

		} else {
		
			// --- Wenn die Startansicht (./images_cms/) angezeugt wird
			$path = "";
			switch($_GET["type"]) {
				case "images": 
					$var['headline'] = "Bilder Ordner"; 
					$var['path'] = ""; 
					break;
				case "files": 
					$var['headline'] = "Dateien Ordner"; 
					$var['path'] = ""; 
					break;
			}

		}

		$var['protection'] = explode(":", $GLOBALS['upload_protectedfolder']);

		// --- Ordner liste abrufen
		$var['folder'] = $this->media_get_folders($_GET["type"], $path);
		return $var;
	}


	/*
	|--------------------------------------------------------------------------
	| Die Ordner-Liste wird geladen 
	| (Anzeige nur die bearbeitbaren in der Medialiste nicht alle Ordner)
	|--------------------------------------------------------------------------
	*/
	function media_get_folders($type, $path) {
	
		$folder="./frontend/".$type."_cms/".$path."";

		$filemap = directory_map($folder, 1);
		return $filemap;

	}

	/*
	|--------------------------------------------------------------------------
	| Die Bilder mit Metadaten werden geladen
	|--------------------------------------------------------------------------
	*/
	function media_get_images($folder_path) {
	
		$folder = $folder_path."/";
		$sql = 'SELECT * FROM ffwbs_images WHERE folder="'.str_replace("//", "", $folder).'" ORDER BY date DESC, name ASC';
		$query = $this->db->query($sql);
		return $query->result_array();

	}
	
	/*
	|--------------------------------------------------------------------------
	| Die Bilder mit Metadaten werden geladen
	|--------------------------------------------------------------------------
	*/
	function media_get_files($folder_path) {
	
		$folder = $folder_path."/";
		$sql = 'SELECT * FROM ffwbs_files WHERE folder="'.str_replace("//", "", $folder).'" ORDER BY date DESC, name ASC';

		$query = $this->db->query($sql);
		return $query->result_array();

	}


	/*
	|--------------------------------------------------------------------------
	| Image Upload
	|--------------------------------------------------------------------------
	*/
	public function media_upload() {
	
		if($_POST["media_type"]=="image") {
			if($_FILES["media_file"]["tmp_name"][0]!="") {
				for($i=0; $i<count($_FILES["media_file"]["tmp_name"]); $i++) {

					if(isset($_POST["img_name"])) {	
						$msg = $this->write_image($i, $_POST["img_name"]);
					} else {
						$msg = $this->write_image($i, "var_originalname");
					}

					if($GLOBALS['globalmessage']=="") {
						$GLOBALS['globalmessage'] = $msg;
					} else {
						$GLOBALS['globalmessage'] = $GLOBALS['globalmessage']."|".$msg;
					}

					$log_action = 'hat ein neues Bild "'.$_FILES["media_file"]["name"][$i].'" in den Ordner "'.$_POST["folder"].'" hochgeladen.';
					basic_writelog($log_action,'media - upload', 2);
				}
			}
		} else {
			if($_FILES["media_file"]["tmp_name"][0]!="") {
				for($i=0; $i<count($_FILES["media_file"]["tmp_name"]); $i++) {

					$msg = $this->write_image($i, "var_originalname");

					if($GLOBALS['globalmessage']=="") {
						$GLOBALS['globalmessage'] = $msg;
					} else {
						$GLOBALS['globalmessage'] = $GLOBALS['globalmessage']."|".$msg;
					}
				
					$log_action = 'hat eine neue Datei "'.$_FILES["media_file"]["name"][$i].'" in den Ordner "'.$_POST["folder"].'" hochgeladen.';
					basic_writelog($log_action,'media - upload', 2);
				}
			}
		}
	}


	/*
	|--------------------------------------------------------------------------
	| Write an uploadet Image
	|--------------------------------------------------------------------------
	| $i 			= Mehrfach Image Upload Array Referenz
	| $imagename 	= a) Name der eingesetzt werden soll (neuer Name)
	|				  b) Wenn "var_originalname" dann wird der file_name benutzt
	|--------------------------------------------------------------------------
	*/
	public function write_image($i, $newimagename) {

		if($_FILES["media_file"]["tmp_name"][$i]!="") {
			$newfilename = $_FILES["media_file"]["tmp_name"][$i];
			$filedir = "./frontend/".$_POST["folder"]."/";
			
			//--- Datei Test
			if ($_POST["media_type"] == "image") {
				$finfo = getimagesize($_FILES["media_file"]["tmp_name"][$i]);
				if($finfo["mime"] == "image/jpeg" || $finfo["mime"] == "image/gif" || $finfo["mime"] == "image/png" || $finfo["mime"] == "image/svg") {
				    $uploadOk = 1;
				} else {
				    $error = 'error:Sorry, Diese Datei ist kein Bild.';
				    $uploadOk = 0;				
				}
				//--- Doppelter Name checken
				/*
				if (file_exists($filedir.$_FILES["media_file"]["name"][$i])) {
				    $msg = 'error:Sorry, Ein Bild mit dem Namen "'.$filedir.$_FILES["media_file"]["name"][$i].'" existiert bereits und kann nicht überschrieben werden.';
				    $uploadOk = 0;
				}
				*/
				//--- Dateigröße begrenzen
				if ($_FILES["media_file"]["size"][$i] > 500000) {
				    $msg = 'error:Sorry, Das Bild "'.$_FILES["media_file"]["name"][$i].'" ist zu groß.';
				    $uploadOk = 0;
				}

			} elseif($_POST["media_type"] == "file") {
				if($_FILES['media_file']['type'][$i] == "image/jpeg" || $_FILES['media_file']['type'][$i] == "video/webm" || $_FILES['media_file']['type'][$i] == "video/mp4" || $_FILES['media_file']['type'][$i] == "text/plain" || $_FILES['media_file']['type'][$i] == "application/msexcel" || $_FILES['media_file']['type'][$i] == "application/mspowerpoint" || $_FILES['media_file']['type'][$i] == "application/pdf" || $_FILES['media_file']['type'][$i] == "application/zip" || $_FILES['media_file']['type'][$i] == "application/msword") {
				    $uploadOk = 1;
				} else {
				    $error = 'error:Sorry, Diese Datei ist nicht erlaubt.';
				    $uploadOk = 0;				
				}
				//--- Doppelter Name checken
				/*
				if (file_exists($filedir.$_FILES["media_file"]["name"][$i])) {
				    $msg = 'error:Sorry, Eine Datei mit dem Namen "'.$_FILES["media_file"]["name"][$i].'" existiert bereits und kann nicht überschrieben werden.';
				    $uploadOk = 0;
				}
				*/
				//--- Dateigröße begrenzen
				if ($_FILES["media_file"]["size"][$i] > 80000000) {
				    $msg = 'error:Sorry, Die Datei "'.$_FILES["media_file"]["name"][$i].'" ist zu groß.';
				    $uploadOk = 0;
				}

			}


			if($uploadOk == 1) {
				
				$filename =  explode(".", $_FILES["media_file"]["name"][$i]);
				$filename[0] = basic_clear_string(str_replace(" ", "_", $filename[0]));
				
				if($newimagename == "var_originalname") {	
					$newimagename = $filename[0];
					$imagename = $filename[0].'.'.$filename[1];
				} else {
					$imagename = $newimagename.'.'.$filename["1"];
				}

				//echo 'Path: '.$filedir.$imagename.'<br>TEMP: '.$_FILES["media_file"]["tmp_name"][$i].'<br>'.$_POST["media_type"].'<br>';

				if (!move_uploaded_file($_FILES["media_file"]["tmp_name"][$i], $filedir.$imagename)) {
				    $msg = 'error:Sorry, das Bild "'.$_FILES["media_file"]["name"][$i].'" konnte nicht geschrieben werden';
				} else {

					if(isset($_POST["alt_text"])) {
						$alt_text = $_POST["alt_text"];
					} else {
						$alt_text = "";
					}

				    if($_POST["media_type"] == "image") {
					    $msg = 'success: +++ '.$newimagename.' +++ Perfekt, das Bild "'.$_FILES["media_file"]["name"][$i].'" wurde hoch geladen!';
						$data_file = array(
						   'name' => ''.$newimagename.'' ,
						   'format' => ''.$filename["1"].'' ,
						   'folder' => ''.str_replace("images_cms/", "", $_POST["folder"]).'/' ,
						   'alt' => ''.$alt_text.'' ,
						   'date' => ''.basic_get_date().' '.basic_get_time().'',
						);
						$this->db->insert('images', $data_file); 
					}
					if($_POST["media_type"] == "file") {

					    $filesize = str_replace(".", "", $_FILES["media_file"]["size"][$i]);
					    $size = intval($filesize/1000);
					    $filesize_einheit = "KB";
					    
					    if($size>999) {
						    $size =  number_format($size/1000, 2, '.', '');
						    $filesize_einheit = "MB";
						}

					    $msg = 'success:Perfekt, die Datei "'.$_FILES["media_file"]["name"][$i].'" wurde hoch geladen!';
						$data_file = array(
						   'filename' => ''.$newimagename.'.'.$filename["1"] ,
						   'format' => ''.$filename["1"].'' ,
						   'size' => ''.$size.' '.$filesize_einheit,
						   'folder' => ''.str_replace("files_cms/", "", $_POST["folder"]).'/' ,
						   'name' => ''.$_POST["displayname"].'' ,
						   'description' => ''.$_POST["description"].'' ,
						   'date' => ''.basic_get_date().' '.basic_get_time().'',
						);
						$this->db->insert('files', $data_file); 
					}
				}
			}
		}
		return($msg);
	}

	public function get_last_added_image() {

		$sql = 'SELECT * FROM ffwbs_images WHERE folder="'.str_replace('images_cms/', '', $_POST['folder']).'/" ORDER BY imageID DESC';
		$query = $this->db->query($sql);
		$imagedata = $query->row_array();

		return(implode(":", $imagedata));

	}

	/*
	|--------------------------------------------------------------------------
	| File DELETE
	|--------------------------------------------------------------------------
	*/
	public function media_delete() {

		if($_GET['type']=="images") {
			$sql = 'SELECT * FROM ffwbs_images WHERE imageID="'.$_GET["fileID"].'"';
			$query = $this->db->query($sql);
			
			if($query->num_rows()==1) {
				$file = $query->row_array();
				$filedir = "./frontend/images_cms/".$file["folder"].$file["name"].".".$file["format"];
				$sql_del='DELETE FROM ffwbs_images WHERE imageID="'.$_GET["fileID"].'"';
				$log_action = 'hat das Bild "'.$file["folder"].$file["name"].'.'.$file["format"].'" gelöscht.';
			} else {
				$filedir = "nofile";
			}
		}
		if($_GET['type']=="files") {
			$sql = 'SELECT * FROM ffwbs_files WHERE fileID="'.$_GET["fileID"].'"';
			$query = $this->db->query($sql);
			
			if($query->num_rows()==1) {
				$file = $query->row_array();
				$filedir = "./frontend/files_cms/".$file["folder"].$file["filename"];
			} else {
				$filedir = "nofile";
			}
			$sql_del='DELETE FROM ffwbs_files WHERE fileID="'.$_GET["fileID"].'"';
			$log_action = 'hat die Datei "'.$file["folder"].$file["filename"].'" gelöscht.';
		}

		if($filedir!="nofile") {	
			if(!unlink ($filedir)) {
				$msg = "error:Datei konnte nicht gelöscht werden";
			} else {
				$query = $this->db->query($sql_del);
				$msg = "success:Datei wurde gelöscht";
				basic_writelog($log_action,'media - upload', 2);
			}
			$GLOBALS['globalmessage'] = $msg;
		}

		//$var = $this->media_folder_list();
		//return $var;

	}

	/*
	|--------------------------------------------------------------------------
	| Ordnerstruktur auslesen
	|--------------------------------------------------------------------------
	*/
	public function media_get_folderstructure($mediatype, $path) {

		$folder="./frontend/".$mediatype."_cms/".$path."";
		$filemap['main'] = directory_map($folder, 1);
		$folder_list = array();

		for($i=0; $i<count($filemap['main']); $i++) {
			if(is_dir("./frontend/".$mediatype."_cms/".$path."/".$filemap['main'][$i])) {
				
				$filemap['main'][$i] = str_replace('/', '', str_replace('\\', '', $filemap['main'][$i]));
				$folder_list = $this->media_get_scandir($mediatype, $filemap['main'][$i], '', $folder_list);
			}
		}

		// --- Ordner liste abrufen
		$var['folder'] = $folder_list;
		print_r($var);

		return $var;
	}

	public function media_get_scandir($mediatype, $path, $level, $folder_list) {

		$folder="./frontend/".$mediatype."_cms/".$level.$path."";
		$folder_list[] = $level.$path;
		
		$filemap = directory_map($folder, 1);
		$level = $path.'/';
		$x=0;

		for($i=0; $i<count($filemap); $i++) {
			if(is_dir("./frontend/".$mediatype."_cms/".$path."/".$filemap[$i])) {	
				$var[$path][$x] = str_replace('/', '', str_replace('\\', '', $filemap[$i]));
				$folder_list = $this->media_get_scandir($mediatype, $var[$path][$x], $level, $folder_list);
			}
		}

		// --- Ordner liste abrufen
		return  $folder_list;
	}


}

?>