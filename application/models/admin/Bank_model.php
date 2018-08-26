<?php
	class Bank_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getAllBank(){
			$this->db->select('bank_id,bank_name,bank_code');
			$query = $this->db->get('bank');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

	}
?>