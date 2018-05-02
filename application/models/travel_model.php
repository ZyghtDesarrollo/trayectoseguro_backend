<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Zyght_Model.php');
require_once(APPPATH . 'models/travellog_model.php');
require_once(APPPATH . 'models/answer_model.php');
require_once(APPPATH . 'models/phoneusagelogs_model.php');

class Travel_model extends Zyght_Model {
	public function __construct(){
		parent::__construct();

		$this->table = 'Travel';
		$this->id = 'id';

		$this->load->model('travellog_model');
		$this->load->model('answer_model');
		$this->load->model('phoneusagelogs_model');
	}

	public function create($user, $answers, $travel_logs, $max_speed, 
			$average_speed, $distance, $duration, $speed_violation,$phone_usage_logs) {
		$this->db->trans_start();

		$this->db->insert($this->table, array(
			'user_id' => $user->id,
			'date' => date('Y-m-d H:i:s'),
			'max_speed' => $max_speed,
			'average_speed' => $average_speed,
			'distance' => $distance,
			'duration' => $duration,
			'speed_violation' => $speed_violation
		));

		$travel_id = $this->db->insert_id();

		foreach ($answers as $answer) {
			
			$this->answer_model->create($travel_id, $answer->question_id, $answer->value);
		}

		foreach ($travel_logs as $log) {
			$this->travellog_model->create($travel_id, $log->latitude, $log->longitude, $log->date, $log->speed);
		}
		foreach ($phone_usage_logs as $log) {
			$this->phoneusagelogs_model->create($travel_id, $log->latitude, $log->longitude, $log->date);
		}
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			return FALSE;
		}

		return $travel_id;
	}
	
	
	public function get_travel_by_id($travel_id){
		$this->db->select('t.*, c.speed_limit');
		$this->db->from($this->table.' AS t');
		$this->db->join('AppUser AS au', 'au.id = t.user_id');
		$this->db->join('Company AS c', 'c.id = au.company_id');
		$this->db->where('t.id', $travel_id);
		$query = $this->db->get();
		
		return ($query->num_rows() > 0) ? $query->row() : array();
	}

	public function get_all_with_user($company_id = '', $user_id = '') {
		$this->db->select('t.*, u.username as appuser');
		$this->db->from($this->table ." as t");
		$this->db->join("Appuser as u", "u.id = t.user_id");

		if ($company_id != '') {
			$this->db->where('u.company_id', $company_id);
		}

		if ($user_id != '') {
			$this->db->where('u.id', $user_id);
		}
		$this->db->order_by("t.id", "desc");
		$query = $this->db->get();

		return ($query->num_rows() > 0) ? $query->result() : array();
	}


	public function get_ranking_user($company_id = '', $user_id = '') {
		$this->db->select('username, 100 -(sum(speed_violation)/ count(t.id)) as ranking');
		$this->db->from($this->table ." as t");
		$this->db->join("Appuser as u", "u.id = t.user_id");

		if ($company_id != '') {
			$this->db->where('u.company_id', $company_id);
		}

		if ($user_id != '') {
			$this->db->where('u.id', $user_id);
		}
		$this->db->group_by('username'); 
		$this->db->order_by("ranking", "desc");
		$query = $this->db->get();

		return ($query->num_rows() > 0) ? $query->result() : array();
	}
}
