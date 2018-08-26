<?php
	class User_sms_code_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getSmsCodeList($where,$like,$page,$page_size){
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$page and $this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('id desc');
			$query = $this->db->get('user_sms_code');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getSmsCodeCount($where,$like){
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$query = $this->db->count_all_results('user_sms_code');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}
	}
?>
