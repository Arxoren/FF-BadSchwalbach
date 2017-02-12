<?php
class model_startpage extends CI_Model {

		public function get_news() {
	
			$this->load->model('site/model_news', 'news');
			return $this->news->get_startpagenews();
			
		}

		public function get_appointments() {
	
			$this->load->model('site/model_termine', 'termine');
			return $this->termine->get_startpagetermine();
			
		}

		public function get_einsatz() {

			$this->load->model('site/model_einsatz', 'einsatz');
			return $this->einsatz->get_startpageeinsatz();

		}

		public function get_unwetter() {

			$this->load->model('site/model_unwetter', 'unwetter');
			return $this->unwetter->get_unwetter();

		}

}