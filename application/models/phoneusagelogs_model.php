<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Zyght_Model.php');

class Phoneusagelogs_model extends Zyght_Model {
	public function __construct(){
		parent::__construct();

		$this->table = 'Phoneusagelog';
		$this->id = 'id';
    }

    public function create($travel_id, $latitude, $longitude, $date) {
		$this->db->insert($this->table, array(
			'travel_id' => $travel_id,
			'latitude' => $latitude,
			'longitude' => $longitude,
			'date' => $date
		));

		$id = $this->db->insert_id();

		return ($id > 0) ? $id : FALSE;
	}
    public function get_by_travel_id($id) {
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('travel_id', $id);
		$this->db->order_by('id');
		$query = $this->db->get();

		return ($query->num_rows() > 0) ? $query->result() : array();
    }
    public function get_count_usage_phone_by_trave_id($id) {
		$this->db->select('count(id) as usage_phone');
		$this->db->from($this->table);
		$this->db->where('travel_id', $id);
		
		$query = $this->db->get();

		return ($query->num_rows() > 0) ? $query->row() : array();
    }
    

}
