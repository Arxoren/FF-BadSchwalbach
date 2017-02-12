<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pagebuilder extends CI_Controller {

	public $location;
	public $segments = array();

	public function __construct() {
		
		parent::__construct();
		$this->load->model('site/model_pagebuilder');
		$this->load->helper('basic');
		$this->load->helper('feuerwehr');

		/*
		|-------------------------------------------
		| GLOBALS und SETTINGS initialisieren:
		|-------------------------------------------
		*/
		$this->model_pagebuilder->set_globals();

	}
	
	public function index() {
		
		if($GLOBALS['page_status']=="ONLINE") {
			/*
			|-------------------------------------------
			| META 				=> Daten wie Page Title etc.
			| NAVIGATION_GROUPS => Alle Menüepunkte
			| FEUERWEHREN 		=> Liste aller filterbaren Feuerwehren
			| MENUE 			=> Alle Menüepunkte
			| CONTENT 			=> Contentmodule der jeweiligen Page
			|-------------------------------------------
			*/
			$data['meta'] = $this->model_pagebuilder->get_page();
			$data['navigation_groups'] = $this->model_pagebuilder->get_navigation_groups();
			// Special Feuerwehr Functionen laden
			$data['feuerwehren'] = $this->model_pagebuilder->get_feuerwehren();
			
			foreach ($data['navigation_groups'] as $nav_group) {
				$data['menue'][$nav_group['name']] = $this->model_pagebuilder->get_menue($nav_group['name']);
			}
			
			if($data['meta']!="404") {
			
				// Check ob "Subpage" oder "Mainpage" bei dynamischen Seiten geladen werden soll
				if($data['meta']["expected_var"]!=0) {
					if($data['meta']["expected_var"]==($this->uri->total_rsegments()-3)) {
						$check_subpage = 1;
					} else {
						$check_subpage = 0;
					}
				} else {
					$check_subpage = 0;
				}
				
				// Module laden
				$data['content'] = $this->model_pagebuilder->get_content_modules($data['meta']['pagesID'], $check_subpage);

				// ----- Page Building ----- //
				// Open Graph Data laden
				$data['opengraph_data'] = $this->model_pagebuilder->get_opengraphdata($data['content'], $data['meta'], $check_subpage);
				// Header laden
				$this->load->view('site/meta_elements/documenthead', $data);
				$this->load->view('site/meta_elements/'.$data['meta']['header'].'_header', $data);
				$GLOBALS['expected_var'] = $data['meta']['expected_var'];

				// Content
				$i = 0;		

				foreach($data['content'] as $row) {	
					//echo "<br>R-> ".$i." -> ".$row["module_data"]." -> ".$row["model_type"]." -> ".$row['page_moduleID']."<br>";
					
					$data['moduleID'] = $row["page_moduleID"];

					if($row["model_type"]=="editorial") {	
						
						// Editorial Modules befüllen
						$data['modulecontent'] = $this->model_pagebuilder->get_content($row["module_data"]);
						$this->load->view('site/page_'.$row["layout"].'', $data);
			
					} else {
						
						if($row["layout"]!="") {	
				
							// Funktions Module befüllen
							//-------------------------------------------------------------------------------------
							$data_quellen = explode(",", $row["model_func"]);
													
							for($i=0; $i<count($data_quellen); $i++) {
								
								$this->load->model('site/model_'.$row["model_type"].'');
								
								$getmodel = "model_".$row["model_type"];
								$getmethode = "get_".$data_quellen[$i];
								
								switch($row["model_type"]) {
									case "table": $data[$data_quellen[$i]] = $this->$getmodel->$getmethode($row["page_moduleID"]); breaK;
									case "stage": $data[$data_quellen[$i]] = $this->$getmodel->$getmethode($row["page_moduleID"]); breaK;
									default: $data[$data_quellen[$i]] = $this->$getmodel->$getmethode($row["module_data"]);
								}
									
							}

							$this->load->view('site/page_'.$row['layout'], $data);	

						} else {
							
							// Page-Module als Content laden (z.B. News)
							//-------------------------------------------------------------------------------------
							$getmodel = "model_".$row["model_type"];
							$getmethode = "get_".$row["model_func"];

							$this->load->model('site/model_'.$row["model_type"].'');
							$content_module_list = $this->$getmodel->$getmethode();

							foreach($content_module_list['module'] as $module) {
								// Editorial Modules befüllen
								
								$data['moduleID'] = $module["page_moduleID"];

								if($module["model_type"]=="editorial") {	
									$data['modulecontent'] = $this->model_pagebuilder->get_content($module["module_data"]);
									$this->load->view('site/page_'.$module["layout"].'', $data);

								} else {
									
									$data_quellen = explode(",", $module["model_func"]);
														
									for($i=0; $i<count($data_quellen); $i++) {
										
										$this->load->model('site/model_'.$module["model_type"].'');
										
										$getmodel = "model_".$module["model_type"];
										$getmethode = "get_".$data_quellen[$i];
										
										switch($module["model_type"]) {
											//case "table": $data[$data_quellen[$i]] = $this->$getmodel->$getmethode($module["page_moduleID"]); breaK;
											//case "stage": $data[$data_quellen[$i]] = $this->$getmodel->$getmethode($module["page_moduleID"]); breaK;
											default: $data[$data_quellen[$i]] = $this->$getmodel->$getmethode($module["module_data"]);
										}
											
									}

									$this->load->view('site/page_'.$module['layout'], $data);	
								}
							}

						}

					}
					$i++;
				}
				
				// Footer
				$this->load->view('site/meta_elements/'.$data['meta']['header'].'_footer', $data);
				$this->load->view('site/meta_elements/documentend', $data);

			
			} else {
				show_404($page = '', $log_error = TRUE);	
			}

		} else {
			$message = "Wir überarbeiten gerade unsere Seite und sind in wenigen Momenten wieder für Sie da.";
			$status_code = "503";
			show_error($message, $status_code, $heading = 'Wir sind gerade Offline');
		}
	}

}
