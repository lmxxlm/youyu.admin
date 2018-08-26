<?php
	class Inform_web_message_group_queue_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getActiveList($where,$like,$page,$page_size){
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$page and $this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('id desc');
			$query = $this->db->get('inform_web_message_group_queue');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getActiveCount($where,$like){
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$query = $this->db->count_all_results('inform_web_message_group_queue');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function activeAdd($data){
			$query = $this->db->insert('inform_web_message_group_queue',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $this->db->insert_id();
		}

	}
?>