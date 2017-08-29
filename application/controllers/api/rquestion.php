<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/API_Controller.php');

class Rquestion extends API_Controller {
	private $resource;

	function __construct() {
		parent::__construct();

		$this->resource = 'Rquestion';

		$this->load->model('question_model');
	}

	public function add_post() {
		$result = $this->question_model->create(
				$this->post('name')
				);
		
		if ($result === FALSE) {
			$this->response_error(404);
		}
		
		$this->response_ok($result);
	}
	
	public function list_get() {
		$result = $this->question_model->get_all();

		if ($result === FALSE) {
			$this->response_error(404);
		}

		$this->response_ok($result);
	}
	
	public function list_actives_get() {
		$result = $this->question_model->get_actives();
		
		if ($result === FALSE) {
			$this->response_error(404);
		}
		
		$this->response_ok($result);
	}
	
	public function edit_post() {
		$result = $this->question_model->update(
				$this->post('id'),
				$this->post('name')
				);
		
		if ($result === FALSE) {
			$this->response_error(404);
		}
		
		$this->response_ok($result);
	}
	
	public function activate_post() {
		$result = $this->question_model->activate($this->post('id'), 1);
		
		if ($result === FALSE) {
			$this->response_error(404);
		}
		
		$this->response_ok($result);
	}
	
	public function deactivate_post() {
		$result = $this->question_model->activate($this->post('id'), 0);
		
		if ($result === FALSE) {
			$this->response_error(404);
		}
		
		$this->response_ok($result);
	}
	
}
