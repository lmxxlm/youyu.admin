<?php
	class Account_cash_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getWithdrawList($where,$like,$page,$page_size){
			$this->db->select('a.*,b.realname,b.username');
			$this->db->from('account_cash a');
			$this->db->join('user b','a.user_id = b.user_id');
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('a.id desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getWithdrawCount($where,$like){
			$this->db->select('count(1) count,count(distinct(a.user_id)) num,sum(a.total) totals');
			$this->db->from('account_cash a');
			$this->db->join('user b','a.user_id = b.user_id');
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function getCashInfo($id){
			$this->db->select('a.*,b.username,b.realname,c.province_name,c.city_name');
			$this->db->from('account_cash a');
			$this->db->join('user b','a.user_id = b.user_id');
			$this->db->join('account_bank c','a.bank_id = c.id','left');
			$this->db->where('a.id',$id);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function cashCheck($where,$data){
			$this->db->where($where);
			$query = $this->db->update('account_cash',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;			
		}
	}
?>