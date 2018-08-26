<?php
	class User_coupon_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getConponList($where,$whereStr,$like,$page,$page_size){
			$this->db->select('a.user_id,a.username,b.invite_userid,b.addtime regtime,a.coupon,a.coupon_name,a.money_limit,a.borrow_days,a.is_use,a.timeout,a.addtime,a.usedtime,a.tender_id,a.borrow_id,a.borrow_limit,c.money tender_money,c.coupon_money coupon_capital,c.performance,c.borrow_days borrow_dayss,d.borrow_type,d.new_hand');
			$this->db->from('user_coupon a');
			$this->db->join('user b','a.user_id = b.user_id','left');
			$this->db->join('borrow_tender c','a.tender_id = c.id','left');
			$this->db->join('borrow d','a.borrow_id = d.id','left');
			$where and $this->db->where($where);
			$whereStr and $this->db->where($whereStr);
			$like and $this->db->like($like);
			$this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('a.id desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getConponCount($where,$whereStr,$like){
			$this->db->select('count(1) count,count(distinct a.user_id) count_users,sum(c.money) tender_moneys,sum(c.performance) performances,sum(c.coupon_money) coupon_capitals');
			$this->db->from('user_coupon a');
			$this->db->join('user b','a.user_id = b.user_id','left');
			$this->db->join('borrow_tender c','a.tender_id = c.id','left');
			$this->db->join('borrow d','a.borrow_id = d.id','left');
			$where and $this->db->where($where);
			$whereStr and $this->db->where($whereStr);
			$like and $this->db->like($like);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function getCouponType(){
			$this->db->select('coupon_name');
			$this->db->distinct('coupon_name');
			$query = $this->db->get('user_coupon');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function add($data){
			$query = $this->db->insert('user_coupon',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function addBatch($data){
			$query = $this->db->insert_batch('user_coupon',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}
	}
?>
