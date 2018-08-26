<?php
	class Rule_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getRules(){
			$this->db->select('rule_id,name,rule,parent_id');
			$query = $this->db->get('rule');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

	}
?>