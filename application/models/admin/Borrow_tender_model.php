<?php
	class Borrow_tender_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getInviteUserTender($user_id,$time){
			if($time){
				$this->db->where('a.addtime >= ',$time);
			}
			$this->db->from('user a');
			$this->db->join('borrow_tender b','a.user_id = b.user_id');
			$this->db->where('a.invite_userid',$user_id);
			$query = $this->db->count_all_results();
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getRewardUseCount($user_id){
			$this->db->select_sum('reward','xutou');
			$this->db->where('user_id',$user_id);
			$query = $this->db->get('borrow_tender');
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function getInvestList($where,$like,$page,$page_size){
			$this->db->select('a.id,b.user_id,b.username,b.realname,b.invite_userid,b.app_marketing,a.money,a.interest,a.addtime,a.addip,a.reward,a.first_reward,a.max_reward,a.last_reward,a.coupon,a.tender_resource,c.name,TIMESTAMPDIFF(day,FROM_UNIXTIME(c.success_time,"%Y%m%d"),FROM_UNIXTIME(c.end_time,"%Y%m%d")) cyc_time,c.end_time,c.apr');
			$this->db->from('borrow_tender a');
			$this->db->join('user b','a.user_id = b.user_id');
			$this->db->join('borrow c','a.borrow_id = c.id');
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('a.addtime desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getInvestCount($where,$like){
			$this->db->select('count(1) count,sum(a.money) moneys,sum(interest) interests,sum(reward) rewards');
			$this->db->from('borrow_tender a');
			$this->db->join('user b','a.user_id = b.user_id');
			$this->db->join('borrow c','a.borrow_id = c.id');
			$where and $this->db->where($where);
			$like and $this->db->like($like);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function moneyUse($where){
			$this->db->select("FROM_UNIXTIME(addtime, '%Y-%m-%d') as dt,sum(money) as moneys,sum(reward) as rewards,sum(performance) performance,count(1) pv");
			$this->db->from('borrow_tender a');
			$where = $this->db->where($where);
			$this->db->order_by("FROM_UNIXTIME(addtime, '%Y-%m-%d') desc");
			$this->db->group_by("FROM_UNIXTIME(addtime, '%Y-%m-%d') desc");
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function moneyRepay($where){
			$this->db->select("FROM_UNIXTIME(repayment_time, '%Y-%m-%d') as dt,sum(repayment_account) repayments,sum(interest) interests,count(1) hpv");
			$this->db->from('borrow_repayment a');
			$where = $this->db->where($where);
			$this->db->order_by("FROM_UNIXTIME(repayment_time, '%Y-%m-%d') desc");
			$this->db->group_by("FROM_UNIXTIME(repayment_time, '%Y-%m-%d') desc");
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getBorrowTender($id){
			$this->db->where('borrow_id',$id);
			$query = $this->db->get('borrow_tender');
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

	}
?>