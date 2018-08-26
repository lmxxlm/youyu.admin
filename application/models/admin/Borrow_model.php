<?php
	class Borrow_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getLoanList($where,$page=0,$page_size=0){
			$this->db->select('a.id,d.realname,a.name,a.success_time,a.end_time,a.repayment_time,a.time_limit_day,account,apr,a.account_yes, a.repayment_account,a.repayment_yesaccount,a.real_state,a.new_hand,a.borrow_type, b.m as money,b.rw as reward,b.coupon_money,b.performance,c.ins as interest');
			$this->db->from('borrow a');
			$this->db->join('(select borrow_id,sum(money) as m,sum(reward) as rw, SUM(coupon_money) AS coupon_money,SUM(performance) as performance from `dw_borrow_tender` WHERE status = 1 group by borrow_id) b','a.id = b.borrow_id');
			$this->db->join('(select borrow_id,sum(interest) as ins from `dw_borrow_repayment` group by borrow_id) c','a.id = c.borrow_id');
			$this->db->join('user d','a.user_id = d.user_id');
			$where and $this->db->where($where);
			$page and $this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('a.id desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getLoanCount($where){
			$this->db->select('count(1) count,sum(account) account,sum(performance) performance,sum(b.rw) reward,sum(c.ins) interest,sum(coupon_money) coupon_money');
			$this->db->from('borrow a');
			$this->db->join('(select borrow_id,sum(money) as m,sum(reward) as rw, SUM(coupon_money) AS coupon_money,SUM(performance) as performance from `dw_borrow_tender` WHERE status = 1 group by borrow_id) b','a.id = b.borrow_id');
			$this->db->join('(select borrow_id,sum(interest) as ins from `dw_borrow_repayment` group by borrow_id) c','a.id = c.borrow_id');
			$this->db->join('user d','a.user_id = d.user_id');
			$where and $this->db->where($where);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function getBorrowList($where,$like,$page = 0,$page_size = 0){
			$this->db->select('a.id,a.name,a.account,a.account_yes,a.apr,a.start_time,a.end_time,a.success_time,a.time_limit_day,a.borrow_type,a.new_hand,a.real_state,b.username,b.realname');
			$this->db->from('borrow a');
			$this->db->join('user b','a.user_id = b.user_id','left');
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$page and $this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('a.id desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getBorrowCount($where,$like){
			$this->db->select('count(1) count,sum(account) account');
			$this->db->from('borrow a');
			$this->db->join('user b','a.user_id = b.user_id');
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function add($data){
			$query = $this->db->insert('borrow',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function update($id,$data){
			$this->db->where('id',$id);
			$query = $this->db->update('borrow',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getBorrowInfo($id){
			$this->db->select('b.username,b.realname,a.id,a.name,a.borrow_type,a.new_hand,a.style,a.account,a.account_yes,a.use,a.real_state,a.original_rate,a.discount_rate,a.lowest_account,a.most_account,a.tender_user,a.invite_user,a.contract_no,a.pawn,a.litpic,a.start_time,a.end_time,a.time_limit_day,a.name,a.sign,a.content,a.borrow_type,a.new_hand,a.addtime,a.addip');
			$this->db->from('borrow a');
			$this->db->join('user b','a.user_id = b.user_id');
			$this->db->where('id',$id);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function borrowCheck($id,$data){
			$this->db->where('id',$id);
			$query = $this->db->update('borrow',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function borrowDel($id){
			$this->db->where('id',$id);
			$query = $this->db->delete('borrow');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getRepayList($where,$like,$page = 0,$page_size = 0){
			$this->db->select('a.id,a.name,a.start_time,a.end_time,a.success_time,a.account,a.status,a.style,
			a.time_limit_day,c.interest,c.capital,
			a.apr,b.user_id,b.realname,b.username,c.repayment_time,
			c.status as st2,c.repayment_account,c.id as repay_id,a.real_state');
			$this->db->from('borrow a');
			$this->db->join('user b','a.user_id = b.user_id');
			$this->db->join('borrow_repayment c','a.id = c.borrow_id');
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$page and $this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('c.repayment_time asc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

	}
?>