<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pagebuilder extends CI_Controller {

	public $location;
	public $segments = array();

	public function __construct() {
		parent::__construct();
		session_start();

		$this->load->model('admin/model_adminpagebuilder');
		$this->load->model('site/model_pagebuilder');
		$this->load->model('admin/model_login');
		$this->load->helper('basic');
		$this->load->helper('feuerwehr');
		$this->load->helper('directory');
		
		/*
		|-------------------------------------------
		| GLOBALS und SETTINGS initialisieren:
		|-------------------------------------------
		*/
		$this->model_adminpagebuilder->set_globals();
		$GLOBALS['language']='de';

	}
	
	public function index() {
		
		$data = array();
		$data['feuerwehren'] = $this->model_pagebuilder->get_feuerwehren(); // Special Feuerwehr Function!

		/*
		|-------------------------------------------
		| Formular OP und Target abfangen
		|-------------------------------------------
		*/
		if(isset($_POST["op"])) {
			$_GET["op"]=$_POST["op"];
		} else {
			if(!isset($_GET["op"])) {
				$_GET["op"]='dashboard';
			} 
		}
		if(isset($_POST["target"])) {
			$_GET["target"]=$_POST["target"];
		}

		/*
		|-------------------------------------------
		| Login und Logout abfangen
		|-------------------------------------------
		*/
		if($_GET["op"]=='login') {
			$data['loginerrormsg'] = $this->model_login->login();
		}
		if($_GET["op"]=='logout') {
			$this->model_login->logout();
		}
		if($_GET["op"]=='confirmation') {
			$this->load->model('admin/model_adminuser');
			$data['loginerrormsg'] = $this->model_adminuser->email_confirmation();
		}
		if($_GET["op"]=='resetpassword') {
			$this->load->model('admin/model_adminuser');
			$data['loginerrormsg'] = $this->model_adminuser->resetpassword();
		}

		/*
		|-------------------------------------------
		| Login und Logout abfangen
		|-------------------------------------------
		*/
		if($_GET["op"]=="function_dashboard" || $_GET["op"]=="dashboard") {
			if(isset($_GET['sort'])) {	
				$_SESSION["secondnavilist"] = $_GET['sort'];
			} else {
				$_SESSION["secondnavilist"] = "";
			}
		}


		/*
		|-------------------------------------------
		| actual_func => Gibt Seitendaten wieder
		|-------------------------------------------
		| [function] => Gibt den Funktionsnamen zurück
		| [model] => Gibt an welches Model geladen werden soll
		| [view] => Gibt an welche View geladen werden soll
		|-------------------------------------------
		*/
		$data['actual_func'] = $this->model_adminpagebuilder->get_page();

		if(isset($_SESSION["userID"]) && $_GET["op"]!="logout" && $_GET["op"]!="confirmation" && $_GET["op"]!="resetpassword_form"  && $_GET["op"]!="resetpassword") {
			
			if($data['actual_func']!="404") {
				
				if($data['actual_func']['view']=="") {
					
					// Model laden und ausführen
					$model = 'model_'.$data['actual_func']['model'];
					$func = $data['actual_func']['function'];
					$this->load->model('admin/'.$model);
					$this->$model->$func();

					// target zuweisen und Meta-Daten neu laden
					$_GET['op']=$_GET['target'];
					$data['actual_func'] = $this->model_adminpagebuilder->get_page();
				}

				// ----- Page Building ----- //
				// Document-Head
				$this->load->view('admin/meta/documenthead', $data);
				// MESSAGES
				if($GLOBALS["globalmessage"]!="") {	
					$this->load->view('admin/meta/globalmessages', $data);
				}
				// Header
				$data['navigation'] = $this->model_adminpagebuilder->get_navigation();
				$this->load->view('admin/meta/header', $data);

				// Content laden
				if($data['actual_func']['model']!="") {	
					$model = 'model_'.$data['actual_func']['model'];
					$func = $data['actual_func']['function'];
					$this->load->model('admin/'.$model);
					$data['content'] = $this->$model->$func();
				}
				
				if($_GET['op']!="pages_edit" && $_GET['op']!="news_edit") {
					if($_SESSION["logincount"]>0) {
						// Layout laden
						$this->load->view('admin/'.$data['actual_func']['view'], $data);	
						// Footer
						$this->load->view('admin/meta/footer', $data);
					} else {
						// Passwort Ändernzwang beim ersten einloggen
						$this->load->model('admin/Model_adminuser');
						$data['content'] = $this->Model_adminuser->usersettings();
						$this->load->view('admin/adminuser_usersettings', $data);
						$this->load->view('admin/meta/footer', $data);
					}
				} else {
					$this->editor_loadmodules($data);
					$this->load->view('admin/meta/footer_editor', $data);
				}
	
			} else {
				show_404($page = '', $log_error = TRUE);	
			}
		} else {

			if($_GET["op"]=="resetpassword_form" || $_GET["op"]=="resetpassword") {
				$this->load->view('admin/resetpassword', $data);
			} else {
				$this->load->view('admin/login', $data);
			}

		}
	}


	/*
	|-------------------------------------------
	| VIEW-Modules des Editors laden
	|-------------------------------------------
	| Da die Module mit dem Frontend verknüpft werden müssen sie gesondert geladen werden.
	|-------------------------------------------
	*/
	private function editor_loadmodules($data) {

		$this->load->view('admin/'.$data['actual_func']['view'], $data);
		
		$data['meta'] = $this->model_pagebuilder->get_page();
		$data['navigation_groups'] = $this->model_pagebuilder->get_navigation_groups();
		foreach ($data['navigation_groups'] as $nav_group) {
			$data['menue'][$nav_group['name']] = $this->model_pagebuilder->get_menue($nav_group['name']);
		}

		$this->load->view('site/meta_elements/default_header', $data);

		if($_GET['op']=="news_edit") {
			$this->editor_loadnewsmodules();
		}

		foreach($data['content']['pagemodules'] as $module) {
			
			$data['moduleID'] = $module['page_moduleID'];
			$data['pagemoduleID'] = $module['model_type'];
			$data['moduleLayout'] = $module['layout'];
			$data['contentmoduleID'] = $module['contentmoduleID'];
			$data['module_data'] = $module['module_data'];
			$data['module_name'] = $module['name'];

			if($module['model_type']=="editorial") {	
				
				// Editorial Modules befüllen
				$data['modulecontent'] = $this->model_pagebuilder->get_content($module['module_data']);

			} else {
				
				// Funktions Module befüllen
				$data_quellen = explode(",", $module["model_func"]);
						
				for($i=0; $i<count($data_quellen); $i++) {
							
					$this->load->model('site/model_'.$module["model_type"].'');
					
					$getmodel = "model_".$module["model_type"];
					$getmethode = "get_".$data_quellen[$i];
					
					switch($module["model_type"]) {
						case "table": $data[$data_quellen[$i]] = $this->$getmodel->$getmethode($module["module_data"]); breaK;
						case "stage": $data[$data_quellen[$i]] = $this->$getmodel->$getmethode($module["page_moduleID"]); breaK;
						default: $data[$data_quellen[$i]] = $this->$getmodel->$getmethode($module["module_data"]);
					}
				
				}

			}
			
			// Stage aus der Sortable Area ausnehmen
			if($module['layout']!="stage_big" && $module['layout']!="stage_small") {
				$this->load->view('admin/pageedit_editbox', $data);	
				$this->load->view('site/page_'.$module['layout'], $data);	
				$this->load->view('admin/pageedit_editbox_close', $data);
			} else {
				$this->load->view('admin/pageedit_editbox_static', $data);	
				$this->load->view('site/page_'.$module['layout'], $data);	
			}
		}

		$this->load->view('site/meta_elements/default_footer', $data);
		

	} 

	/*
	|-------------------------------------------
	| Modules des NEWS-Editors laden
	|-------------------------------------------
	| Da die Module mit dem Frontend verknüpft werden müssen sie gesondert geladen werden.
	|-------------------------------------------
	*/
	private function editor_loadnewsmodules() {
		
		$this->load->model('admin/Model_adminnews');
		$ndata['news_details'] = $this->Model_adminnews->get_news_details();
		$ndata[''] = basic_get_moduleID('news_details');

		$this->load->view('site/page_only_breadcrump.php', $ndata);
		$this->load->view('admin/pageedit_editboxnews', $ndata);
		$this->load->view('site/page_news_detail_head', $ndata);
		$this->load->view('admin/pageedit_editbox_close', $ndata);
	} 



	/*
	|-------------------------------------------
	| AJAX Module Laden (Settings im Editor View)
	|-------------------------------------------
	*/
	public function get_modulelist_ajax() {

		$this->load->model('admin/Model_pageeditor');
		$modulelist['modulelist'] = $this->Model_pageeditor->page_get_modulelist();

		$response = $this->load->view('admin/pageedit_inline_modulelist', $modulelist, TRUE);
		$this->output->set_output($response);

	}

	public function get_view_ajax() {
		
		/*
		$_POST['text'] = "THE NEW TEXT MODULE<br>dumdidum<br>Loremipsum<br>dolor";
		$_POST['moduleID'] = 1;
		$_POST['itemnumber'] = 01; 
		*/

		$this->load->model('admin/Model_pageeditor');
		$data = $this->Model_pageeditor->page_get_newmoduldata();

		$data['module_data'] = $data['initialcontent'];
		$data['pagemoduleID'] = $data['model'];
		$data['module_name'] = "";

		if($data['model']=="editorial") {	
			// Editorial Modules befüllen
			$this->load->model('admin/model_pagebuilder');
			$data['modulecontent'] = $this->model_pagebuilder->get_content($data['initialcontent']);

		}

		$response = $this->load->view('admin/pageedit_editbox', $data, TRUE);
		$response .= $this->load->view('site/page_'.$data['layout'], $data,TRUE);
		$response .= $this->load->view('admin/pageedit_editbox_close', $data, TRUE);

		$this->output->set_output($response); 
	}

	public function get_moduleform_ajax() {
		
		/*
		$_POST["contentmoduleID"]=8;
		$_POST["moduleID"]="newssetails";
		*/

		$this->load->model('admin/Model_pageeditor');
		$module = $this->Model_pageeditor->page_getspecial_form();
		$mdata = $this->Model_pageeditor->page_getspecial_form_infodata(); 

		$model = 'site/Model_'.$module["model"];
		$function = 'get_'.$module["function"];
		$adminfunction = 'admin/m_'.$module["function"];

		if($module['attachment']=="images") {	
			switch($mdata['model_func']) {
				case 'imagegallery': $path = 'gallerie/'.$mdata['name']; break;
				case 'smallstage_image': $path = 'stages'; break;
				default: $path = $mdata['name'];
			}

			$_GET["path"] = $path;
			$_GET["type"] = $module['attachment'];

			$this->load->model('admin/Model_media');
			$mdata['filelist'] = $this->Model_media->media_get_folderstructure($module['attachment'], 'gallerie');
			$mdata['imagelist'] = $this->Model_media->media_get_images($_GET["path"]);
		}
		if($module['attachment']=="files") {	

			$mdata['model_func'].'<br>';
			if($mdata['model_func']=='video') {
				$path = 'video';
			} else {
				if($mdata['name']!="") {	
					$path = $mdata['name'];
				} else {
					$path = '';
				}
			}
			$_GET["path"] = $path;
			$_GET["type"] = $module['attachment'];
			
			$this->load->model('admin/Model_media');
			$mdata['filelist'] = $this->Model_media->media_get_folderstructure($module['attachment'], $path);

			if($_GET["path"]=="") {
				$_GET["path"] = $mdata['filelist']['folder'][0];
			}
			$mdata['files'] = $this->Model_media->media_get_files($_GET["path"]);
		}

		$this->load->model('site/model_'.$module["model"].'');
		$getmodel = "model_".$module["model"];
		$getmethode = "get_".$module["function"];
					
		if($module["model"] == "table") {
			$mdata[$module["function"]] = $this->$getmodel->$getmethode($_POST["moduleID"]);
		} else {
			switch($module["model"]) {	
				case "image" :  $content_data = $_POST['content']; break;
				case "files" :  $content_data = $_POST['content']; break;
				default :  $content_data = $mdata['module_data'];
			}
			$mdata[$module["function"]] = $this->$getmodel->$getmethode($content_data);
		}

		$response = $this->load->view($adminfunction, $mdata, TRUE);
		$this->output->set_output($response); 

	}

	public function get_adminmoduleform_ajax() {
				
		/*
		$_POST["contentmoduleID"]=10;
		$_POST["moduleID"]="downloadfiles";
		*/

		$this->load->model('admin/Model_pageeditor');
		$module = $this->Model_pageeditor->page_getspecial_form();
		$mdata = $this->Model_pageeditor->page_getspecial_form_infodata(); 

		$model = 'admin/Model_'.$_POST["moduleID"];
		$function = 'get_'.$module["function"];
		$adminfunction = 'admin/m_'.$module["function"];

		if($module['attachment']=="images") {	
			if($mdata['model_func']=='imagegallery') {
				$path = 'gallerie/'.$mdata['name'];
			} else {
				$path = $mdata['name'];
			}

			$_GET["path"] = $path;
			$_GET["type"] = $module['attachment'];

			$this->load->model('admin/Model_media');
			$mdata['filelist'] = $this->Model_media->media_get_folderstructure($module['attachment'], 'gallerie');
			$mdata['files'] = $this->Model_media->media_get_images($_GET["path"]);
		}
		
		$this->load->model('admin/model_'.$_POST["moduleID"].'');
		$getmodel = "model_".$_POST["moduleID"];
		$getmethode = "get_".$module["function"];
					
		if($module["model"] == "table") {
			$mdata[$module["function"]] = $this->$getmodel->$getmethode($_POST["moduleID"]);
		} else {
			$mdata[$module["function"]] = $this->$getmodel->$getmethode($mdata['module_data']);
		}

		$response = $this->load->view($adminfunction, $mdata, TRUE);
		$this->output->set_output($response); 

	}

public function get_admineditorfunctions_ajax() {
				
		/*
		$_POST["contentmoduleID"]=10;
		$_POST["moduleID"]="downloadfiles";
		*/
		echo $adminfunction = 'admin/m_editor_'.$_POST["formtype"];
		$mdata = array();

		$response = $this->load->view($adminfunction, $mdata, TRUE);
		$this->output->set_output($response); 

	}

	public function get_folderlist_ajax() {

		/*
		$_GET["path"] = 'gallery/test0r';
		$_GET["type"] = 'images';
		*/

		$_GET["path"] = $_POST["folder"];
		$_GET["type"] = $_POST["media_type"];
		
		$this->load->model('admin/Model_media');
		if($_GET["type"] == 'image') {
			$mdata['imagelist'] = $this->Model_media->media_get_images($_GET["path"]);
		} else {
			$mdata['files'] = $this->Model_media->media_get_files($_GET["path"]);
		}
		
		$response = $this->load->view('admin/m_folderlist', $mdata, TRUE);
		
		//echo $response;
		$this->output->set_output($response);
	}

	public function get_imageupload_ajax() {

		/*
		$_GET["path"] = 'gallery/test0r';
		$_GET["type"] = 'images';
		*/

		$_GET["path"] = $_POST["folder"];
		$_GET["type"] = $_POST["media_type"];
		
		$this->load->model('admin/Model_media');
		$mdata['imagelist'] = $this->Model_media->media_get_images($_GET["path"]);
		
		$response = $this->load->view('admin/m_imageupload', $mdata, TRUE);
		
		//echo $response;
		$this->output->set_output($response);
	}

	public function checkform_ajax() {

		$this->load->model('admin/Model_einsatz');
		$response = $this->Model_einsatz->checkform();
		
		//echo $response;
		$this->output->set_output($response);
	}

	public function imageupload_ajax() {

		//$_POST['folder']='images_cms/gallerie/langenseifen_wache';
		//echo $_POST['img_name'];

		$this->load->model('admin/Model_media');
		$this->Model_media->media_upload();

		$var = $this->Model_media->get_last_added_image();

		$response = $GLOBALS['globalmessage'].':'.$var;
		$this->output->set_output($response); 

	}
	public function save_module_settings() {

		$this->load->model('admin/Model_pageeditor');
		$response = $this->Model_pageeditor->page_save_module_settings();

		$this->output->set_output($response);
	}
	public function get_icons() {

		$this->load->model('admin/Model_pageeditor');
		$mdata['iconlist'] = $this->Model_pageeditor->page_get_iconlist();


		$response = $this->load->view('admin/m_iconlist', $mdata, TRUE);
		$this->output->set_output($response);
	}
	public function get_teaseredit() {

		$path = 'teaser';
		$_GET["path"] = $path;
		$_GET["type"] = 'images';
	
		$this->load->model('admin/Model_media');
		$mdata['imagelist'] = $this->Model_media->media_get_images($_GET["path"]);

		$response = $this->load->view('admin/m_teaser_list', $mdata, TRUE);
		$this->output->set_output($response);
	}

}
