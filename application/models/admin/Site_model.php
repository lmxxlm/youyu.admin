<?php
	class Site_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getSiteList($where,$like,$page,$page_size){
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$page and $this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('site_id desc');
			$query = $this->db->get('site');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getSiteCount($where,$like){
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$query = $this->db->count_all_results('site');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function siteAdd($data){
			$query = $this->db->insert('site',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function siteUpdate($id,$data){
			$this->db->where('site_id',$id);
			$query = $this->db->update('site',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getSiteInfo($id){
			$this->db->where('site_id',$id);
			$query = $this->db->get('site');
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}
	}
?>