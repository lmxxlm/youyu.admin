<?php
	class Account_bank_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getList($where,$like,$page,$page_size){
			$this->db->select('a.id,a.bank,a.account,a.bank_status,a.province,a.city,a.province_name,a.city_name,a.pay_status,a.addtime,a.addip,b.user_id,b.username,b.realname,c.name province,d.name city');
			$this->db->from('account_bank a');
			$this->db->join('user b','a.user_id = b.user_id','inner');
			$this->db->join('area c','a.province = c.id','left');
			$this->db->join('area d','a.city = d.id','left');
			$this->db->where($where);
			$this->db->like($like);
			$this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('a.id desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getListCount($where,$like){
			$this->db->select('a.bank,a.account,a.bank_status,a.province,a.city,a.province_name,a.city_name,a.pay_status,a.addtime,a.addip,b.user_id,b.username,b.realname,c.name province,d.name city');
			$this->db->from('account_bank a');
			$this->db->join('user b','a.user_id = b.user_id','inner');
			$this->db->join('area c','a.province = c.id','left');
			$this->db->join('area d','a.city = d.id','left');
			$this->db->where($where);
			$this->db->like($like);
			$query = $this->db->count_all_results();
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getBankInfo($id){
			$this->db->select('id,account');
			$this->db->where('id',$id);
			$query = $this->db->get('account_bank');
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function bankUpdate($id,$data){
			$this->db->where('id',$id);
			$query = $this->db->update('account_bank',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function bankDel($id){
			$this->db->where('id',$id);
			$query = $this->db->delete('account_bank');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}
	}
?>
