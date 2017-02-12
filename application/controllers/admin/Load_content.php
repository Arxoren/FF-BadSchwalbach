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
	
	public function get_view_ajax() {
		echo "hallo;"
		$response = $this->load->view('site/page_text',$data,TRUE);
   		echo $response;
	}	
}
