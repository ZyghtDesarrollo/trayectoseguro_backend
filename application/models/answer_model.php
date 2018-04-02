<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Zyght_Model.php');

class Answer_model extends Zyght_Model {
	public function __construct(){
		parent::__construct();

		$this->table = 'Answer';
		$this->id = 'id';
	}

	public function create($travel_id, $question_id, $value) {
		$this->db->insert($this->table, array(
			'travel_id' => $travel_id,
			'question_id' => $question_id,
			'value' => $value
		));

		$id = $this->db->insert_id();

		return ($id > 0) ? $id : FALSE;
	}


	function get_answer_by_travel_id($id) {
		$this->db->select('title, value');
		$this->db->from($this->table);
		$this->db->join('Question AS q', 'q.id = question_id');
		$this->db->where('travel_id', $id);

		$query = $this->db->get();
		

		return ($query->num_rows() > 0) ? $query->result() : array();
	}
	
}
