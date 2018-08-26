<?php
	class Account_log_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getAccountLog($where,$page,$page_size){
			$this->db->where($where);
			$this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('id desc');
			$query = $this->db->get('account_log');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getAllAccountLog($where){
			$this->db->where($where);
			$query = $this->db->get('account_log');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

	}
?>