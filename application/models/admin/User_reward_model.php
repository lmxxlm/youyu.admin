<?php
	class User_reward_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function rewardList($where,$like,$page,$page_size){
			$this->db->select('a.user_id,a.username,a.reward_no,b.invite_userid,a.money,a.reward_name,a.money_limit,a.borrow_days,a.weixin_id,a.use_together,a.addtime,a.timeout,usetime,a.is_use');
			$this->db->from('user_reward a');
			$this->db->join('user b','a.user_id = b.user_id','left');
			$this->db->where($where);
			$this->db->like($like);
			$this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('a.id desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function rewardListSumCount($where,$like){
			$this->db->select('count(1) count,count(distinct(a.user_id)) countUsers,IFNULL(sum(a.money),0) countMoney');
			$this->db->from('user_reward a');
			$this->db->where($where);
			$this->db->like($like);
			// $this->db->group_by('a.user_id');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function rewardListExport($where,$like){
			$this->db->select('a.user_id,a.username,a.reward_no,b.invite_userid,a.money,a.reward_name,a.money_limit,a.borrow_days,a.weixin_id,a.use_together,a.addtime,a.timeout,usetime,a.is_use');
			$this->db->from('user_reward a');
			$this->db->join('user b','a.user_id = b.user_id','left');
			$this->db->where($where);
			$this->db->like($like);
			$this->db->order_by('a.id desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function rewardAllForCount(){
			$this->db->select('money,is_use,timeout,reward_style');
			$query = $this->db->get('user_reward');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getRewardType(){
			$this->db->select('reward_name');
			$this->db->distinct('reward_name');
			$query = $this->db->get('user_reward');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function rewardUseList($where,$like,$page,$page_size){
			$this->db->select('a.*,b.realname,b.username,b.addtime regtime,c.borrow_type,c.new_hand,d.money tmoney,d.performance');
			$this->db->from('user_reward a');
			$this->db->join('user b','a.user_id = b.user_id','left');
			$this->db->join('borrow c','a.uborrow_id = c.id','left');
			$this->db->join('borrow_tender d','a.utender_id = d.id','left');
			$this->db->where($where);
			$this->db->where($like);
			$this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('a.id desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function rewardUseListCountSum($where,$like){
			$this->db->select('count(1) count,count(distinct(a.user_id)) countUsers,sum(a.money) countMoney,sum(d.money) countTmoney,sum(d.performance) CountPerformance');
			$this->db->from('user_reward a');
			$this->db->join('user b','a.user_id = b.user_id','left');
			$this->db->join('borrow c','a.uborrow_id = c.id','left');
			$this->db->join('borrow_tender d','a.utender_id = d.id','left');
			$this->db->where($where);
			$this->db->where($like);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function rewardUseListExport($where,$like){
			$this->db->select('a.user_id,a.username,b.addtime regtime,a.reward_no,a.money,a.reward_name,a.weixin_id,a.use_together,a.addtime,a.usetime,a.uborrow_id,a.utender_id,d.money tmoney,d.performance');
			$this->db->from('user_reward a');
			$this->db->join('user b','a.user_id = b.user_id','left');
			$this->db->join('borrow c','a.uborrow_id = c.id','left');
			$this->db->join('borrow_tender d','a.utender_id = d.id','left');
			$this->db->where($where);
			$this->db->where($like);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function add($data){
			$query = $this->db->insert('user_reward',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function addBatch($data){
			$query = $this->db->insert_batch('user_reward',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function checkRewardNo($reward_no){
			$this->db->where('reward_no',$reward_no);
			$query = $this->db->count_all_results('user_reward');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function rewardReport($time_begin,$time_end){
			$sql = "select FROM_UNIXTIME(c.addtime, '%Y-%m-%d') as riqi,  sum(CASE c.is_use WHEN 1 THEN c.money ELSE 0 END) as used_reward,sum(c.money) as total, sum(CASE is_use WHEN 0 THEN money ELSE 0 END) as unused_reward,(select sum(money) from dw_user_reward where FROM_UNIXTIME(c.addtime, '%Y-%m-%d') > FROM_UNIXTIME(timeout, '%Y-%m-%d') and is_use = 0 and reward_name <>'合并红包券') as timeout_reward
			from dw_user_reward c where addtime >= '{$time_begin}' and addtime <= '{$time_end}'
			 and c.reward_name <>'合并红包券' group by FROM_UNIXTIME(c.addtime, '%Y-%m-%d')";
			$query=$this->db->query($sql);
			log_message('info','sql:'.$sql);
      return $query->result_array();
		}

		public function rewardReportCount($time_begin,$time_end){
			$sql = "select sum(CASE c.is_use WHEN 1 THEN c.money ELSE 0 END) as used_reward,sum(c.money) as total, sum(CASE is_use WHEN 0 THEN money ELSE 0 END) as unused_reward,(select sum(money) from dw_user_reward where FROM_UNIXTIME(c.addtime, '%Y-%m-%d') > FROM_UNIXTIME(timeout, '%Y-%m-%d') and is_use = 0 and reward_name <>'合并红包券') as timeout_reward
			from dw_user_reward c where addtime >= '{$time_begin}' and addtime <= '{$time_end}'
			 and c.reward_name <>'合并红包券'";
			$query=$this->db->query($sql);
			log_message('info','sql:'.$sql);
      return $query->row_array();
		}
	}
?>
