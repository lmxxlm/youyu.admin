<?php
	class Account_recharge_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getRechargeList($where,$like,$page,$page_size){
			$this->db->select('a.id,a.trade_no,b.username,b.realname,a.pay_type,c.bank_name,a.money,a.fee,a.remark,a.addtime,a.verify_time,a.status');
			$this->db->from('account_recharge a');
			$this->db->join('user b','a.user_id = b.user_id');
			$this->db->join('bank c','a.bank_code = c.bank_code');
			$this->db->where($where);
			$this->db->like($like);
			$this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('id desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getRechargeCount($where,$like){
			$this->db->select('count(1) count,sum(money) sum');
			$this->db->from('account_recharge a');
			$this->db->join('user b','a.user_id = b.user_id','left');
			$this->db->join('bank c','a.bank_code = c.bank_code','left');
			$this->db->where($where);
			$this->db->like($like);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function rechargeAdd($data){
			$query = $this->db->insert('account_recharge',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function rechargeExport($where,$like){
			$this->db->select('a.id,a.trade_no,b.username,b.realname,a.pay_type,c.bank_name,a.money,a.fee,a.remark,a.addtime,a.verify_time,a.status');
			$this->db->from('account_recharge a');
			$this->db->join('user b','a.user_id = b.user_id','left');
			$this->db->join('bank c','a.bank_code = c.bank_code','left');
			$this->db->where($where);
			$this->db->like($like);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

	}
?>