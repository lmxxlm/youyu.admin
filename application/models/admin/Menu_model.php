<?php
	class Menu_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getAllMenu(){
			$this->db->select('*');
			$this->db->where('state = 1');
			$query = $this->db->get('menu');
			$res = $query->result_array();
			return $res;
		}

	}
?>