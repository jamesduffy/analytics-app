<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timekeeper_model extends CI_Model {
		
	private $data = array();

	/*
	 |-------------------------------
	 | SAVE TO DATABASE
	 |-------------------------------
	 | This function will be called by the system
	 | post_system hook to save the data to the database
	 |
	 */
	public function save() {

		$this->db->insert_batch('timekeeper', $this->data);

	}

	/*
	 |-------------------------------
	 | 
	 |-------------------------------
	 | 
	 |
	 */
	public function new_record($name, $elapsed_time) {
		$created_at = Date('Y-m-d H:i:s');

		$this->data[] = array(
				'name' => $name,
				'created_at' => $created_at,
				'elapsed_time' => $elapsed_time
			);

		return TRUE;
	} 

}