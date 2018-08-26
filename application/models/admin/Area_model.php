<?php
	class Area_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getAllArea(){
			$query = $this->db->get('area');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

	}
?>