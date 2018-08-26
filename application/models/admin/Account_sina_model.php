<?php
	class Account_sina_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getAccountList($where,$like,$page=0,$page_size=0){
			$this->db->select('a.id,b.username,b.realname,a.use_money,a.no_use_money,a.wait_capital,a.wait_interest,a.fund,a.taste_money,a.xutou');
			$this->db->from('account_sina a');
			$this->db->join('user b','a.user_id  = b.user_id');
			$this->db->where($where);
			$this->db->like($like);
			$page and $this->db->limit($page_size,($page-1)*$page_size);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getAccountListCount($where,$like){
			$this->db->select('count(1) count,sum(a.use_money) use_money,sum(a.no_use_money) no_use_money,sum(a.wait_capital) wait_capital,sum(a.wait_interest) wait_interest');
			$this->db->from('account_sina a');
			$this->db->join('user b','a.user_id  = b.user_id','inner');
			$this->db->where($where);
			$this->db->like($like);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function getAccountCount($user_id){
			$this->db->where('user_id',$user_id);
			$query = $this->db->get('account_sina');
			log_message('info','sql:'.$this->db->last_query());
			if($query->row_array()){
				return $query->row_array();
			}
			$data = array();
			$data['user_id'] = $user_id;
			$data['total'] = 0.0;
			$data['use_money'] = 0.0;
			$data['taste_money'] = 0.0;
			$data['no_use_money'] = 0.0;
			$data['collection'] = 0.0;
			$data['fund'] = 0.0;
			$this->db->insert('account_sina',$data);
			return self::getAccountCount($user_id);
		}

		public function getProjectCount($field,$time_begin,$time_end){
			$sql = "select $field,sum(investment_users) as investment_users,sum(investment_money) as investment_money,
				sum(verified_users) as verified_users,sum(registered_users) as registered_users,
				sum(repayment_interest) as repayment_interest,sum(repayment_money) as repayment_money,
				sum(new_borrowings) as new_borrowings,sum(repayment_users) as repayment_users,
				sum(recharge_users) as recharge_users,sum(recharge_money) as recharge_money,
				sum(cash_users) as cash_users,sum(cash_money) as cash_money,
				sum(coupon_money) as coupon_money,sum(reward_money) as reward_money,sum(cash_coupon) as cash_coupon,
				sum(channel_new_investors) as channel_new_investors,sum(new_investors) as new_investors,
				min(stat_date) as min_date , max(stat_date) as max_date,sum(performance) as performance 
				from tj_repayment_analyzed 
				where `stat_date` >= '$time_begin' and `stat_date` <= '$time_end'
				group by s_date order by stat_date asc ";
			$query = $this->db->query($sql);
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function addStatDate($time_begin,$time_end){
			$sql = "select count(1) count from `tj_repayment_analyzed` where `stat_date` >= FROM_UNIXTIME($time_begin,'%Y-%m-%d') and `stat_date` <= FROM_UNIXTIME($time_end,'%Y-%m-%d')";
			$query = $this->db->query($sql);
			$res = $query->row_array();
			if($res['count'] == date('t',$time_begin)){
				return;
			}
			for($i = $time_begin;$i <= $time_end;$i += 86400){
				$sql = "select count(1) count from `tj_repayment_analyzed` where `stat_date` = FROM_UNIXTIME($i,'%Y-%m-%d')";
				$query = $this->db->query($sql);
				$res = $query->row_array();
				if(!$res['count']){
					$sql = "insert into `tj_repayment_analyzed` (`stat_date`) values (FROM_UNIXTIME($i,'%Y-%m-%d'))";
					$this->db->query($sql);
				}
			}

		}

		public function freshProjectCount($time_begin,$time_end){
			// 日期, 投资人数,投资金额,业绩
			$sql1 = " UPDATE tj_repayment_analyzed t
					INNER JOIN (
					SELECT
					FROM_UNIXTIME(addtime, '%Y-%m-%d') AS days,
					COUNT(DISTINCT(user_id)) AS tzrs,
					SUM(money) AS tzje,
					SUM(coupon_money)  AS sum_coupon_money,
					SUM(performance) AS performance
					FROM
					`dw_borrow_tender`
					WHERE `status` = 1  AND addtime >= $time_begin and addtime <= $time_end 
					GROUP BY days) s ON t.stat_date = s.days 
					SET t.investment_users = s.tzrs,
					t.investment_money = s.tzje,
					t.coupon_money = s.sum_coupon_money,
					t.performance = s.performance ";

/** 日期,实名认证数**/
			$sql2 = " UPDATE tj_repayment_analyzed t
				INNER JOIN (
				SELECT
				FROM_UNIXTIME(addtime, '%Y-%m-%d') AS days,
				COUNT(1) AS smrzs
				FROM
				dw_user
				WHERE
				type_id = 2
				AND real_status =1
				AND  addtime >= $time_begin and  addtime <= $time_end 
				GROUP BY
				days
				) s ON s.days = t.stat_date
				SET t.verified_users = s.smrzs ";

//日期,注册用户数
		$sql3 = " UPDATE tj_repayment_analyzed t
				INNER JOIN (
				SELECT
				COUNT(1) AS zcyhs,
				FROM_UNIXTIME(addtime, '%Y-%m-%d') AS days
				FROM
				dw_user
				WHERE
				type_id = 2
				AND  addtime >= $time_begin and  addtime <= $time_end 
				GROUP BY
				days
				) s ON s.days = t.stat_date
				SET t.registered_users = s.zcyhs ";

/** 日期, 待还款总额,待还款利息**/
		$sql4 = " UPDATE tj_repayment_analyzed t
			INNER JOIN (
				SELECT
					FROM_UNIXTIME(`repay_time`, '%Y-%m-%d') AS repayment_time,
					SUM(`repay_account`) AS dhkze,
					SUM(interest) AS dhlx,
					COUNT(DISTINCT(r.user_id)) AS peoples
				FROM
					`dw_borrow_collection` r 
					where repay_time >= $time_begin and  repay_time <= $time_end 
				GROUP BY
					repayment_time
			) s ON s.repayment_time = t.stat_date
			SET t.repayment_money = s.dhkze,
			t.repayment_interest = s.dhlx,
			t.repayment_users = s.peoples;";

/**日期， 新增借款金额**/
		$sql5 = "UPDATE tj_repayment_analyzed t
			INNER JOIN (
			SELECT
			SUM(account) AS xzjkje,
			FROM_UNIXTIME(success_time, '%Y-%m-%d') AS stime
			FROM
			dw_borrow 
			WHERE
			real_state IN (2,3,5,6,7)
			AND  success_time >= $time_begin and  success_time <= $time_end 
			GROUP BY
			stime
			) s ON s.stime = t.stat_date
			SET t.new_borrowings = s.xzjkje ";

/**日期 ， 提现人数， 提现金额**/
		$sql6 = "UPDATE tj_repayment_analyzed t
			INNER JOIN (
				SELECT
					FROM_UNIXTIME(c.`addtime`, '%Y-%m-%d') AS success_time,
					SUM(c.`total`) AS cash_money,
					COUNT(DISTINCT(c.`user_id`)) AS cash_users
				FROM
					dw_account_cash c,
					dw_user u
				WHERE
					c.`status` = 1
				AND c.user_id = u.user_id
				AND u.type_id = 2
				AND c.addtime >= $time_begin and  c.addtime <= $time_end 
				GROUP BY
					success_time
			) s ON s.success_time = t.stat_date
			SET t.cash_money = s.cash_money,
			t.cash_users = s.cash_users ";

//日期 ，充值人数，充值金额
		$sql7 = "	UPDATE tj_repayment_analyzed t
			INNER JOIN (
				SELECT
					FROM_UNIXTIME(r.`addtime`, '%Y-%m-%d') AS success_time,
					SUM(r.`money`) AS recharge_money,
					COUNT(DISTINCT(r.`user_id`)) AS recharge_users
				FROM
					dw_account_recharge r,
					dw_user u
				WHERE
					r.`status` = 1
				AND r.user_id = u.user_id
				AND u.type_id = 2
				AND r.type=2
				AND r.addtime >= $time_begin and   r.addtime <= $time_end 
				GROUP BY
					success_time
			) s ON s.success_time = t.stat_date
			SET t.recharge_money = s.recharge_money,
			t.recharge_users = s.recharge_users ";

		//  新增投资人数
		$sql8 = " UPDATE tj_repayment_analyzed t
			INNER JOIN (
				select t1.addtime days,COUNT(1) peoples  FROM (	
				  select t.user_id,FROM_UNIXTIME(t.addtime,'%Y-%m-%d ') addtime FROM (
					SELECT user_id,addtime FROM dw_borrow_tender WHERE user_id <>0 GROUP BY user_id ORDER BY addtime ASC
				  ) t  WHERE t.addtime>=$time_begin AND t.addtime<= $time_end 
				) t1 GROUP BY t1.addtime 
			) a ON t.`stat_date` = a.days
			SET t.`new_investors` = a.peoples  ";

//渠道新增投资人数
		$sql9 = "UPDATE tj_repayment_analyzed t
			INNER JOIN (
				SELECT
					FROM_UNIXTIME(d.first_addtime, '%Y-%m-%d') AS days,
					COUNT(1) AS peoples
				FROM
					dw_user_first_end_tender d,
					`dw_user` u
				WHERE
					d.`user_id` = u.`user_id`
				AND u.`trackid` <> ''
				AND d.first_addtime >= $time_begin and   d.first_addtime <= $time_end 
				GROUP BY days
			) a ON t.`stat_date` = a.days
			SET t.`channel_new_investors` = a.peoples ";

		//日期,红包,现金券
		$sql10 = "UPDATE `tj_repayment_analyzed` t
			INNER JOIN (
			SELECT
			FROM_UNIXTIME(usetime, '%Y-%m-%d') AS days,
			SUM(CASE WHEN use_together = 1 THEN money ELSE 0 END) AS reward_money,
			SUM(CASE WHEN use_together = 2 THEN money ELSE 0 END) AS cash_coupon
			FROM `dw_user_reward` 
			WHERE is_use = 1 and usetime >= $time_begin and usetime <= $time_end 
			GROUP BY days) s ON t.stat_date = s.days 
			SET t.reward_money = s.reward_money,
			t.cash_coupon = s.cash_coupon ";

		log_message('info','sql1:'.$sql1."||".'sql2:'.$sql2."||".'sql3:'.$sql3."||".'sql4:'.$sql4."||".'sql5:'.$sql5."||".'sql6:'.$sql6."||".'sql7:'.$sql7."||".'sql8:'.$sql8."||".'sql9:'.$sql9."||".'sql10:'.$sql10);
		return $this->db->query($sql1) && $this->db->query($sql2) && $this->db->query($sql3) && $this->db->query($sql4)
				&& $this->db->query($sql5) && $this->db->query($sql6) && $this->db->query($sql7) && $this->db->query($sql8)
				&& $this->db->query($sql9) && $this->db->query($sql10);
		}

		public function report_recharge_type_day($begin, $end, $typeid)
		{			
			$select="al.user_id,FROM_UNIXTIME(al.addtime, '%Y-%m-%d') as dt,sum(al.money) moneys,count(1) pv";
			$where="al.`status`=1 AND al.type in ({$typeid}) and al.addtime >= {$begin} and al.addtime <= {$end} ";//
			$order="ORDER BY FROM_UNIXTIME(al.addtime, '%Y-%m-%d') desc ";
			$group="GROUP BY FROM_UNIXTIME(al.addtime, '%Y-%m-%d')";
			$sql="select SELECT 
					FROM `dw_account_log` al LEFT JOIN dw_user u ON u.user_id=al.user_id 
					WHERE  $where GROUP ORDER";

			$query = $this->db->query(str_replace(array('SELECT', 'GROUP', 'ORDER'), array($select, $group, $order), $sql));
			return $query->result();
		}

		//借款人充值
		public function report_recharge_realtype_day($begin, $end, $typeid)
		{
			$select="al.user_id,FROM_UNIXTIME(al.addtime, '%Y-%m-%d') as dt,sum(al.money) moneys,count(1) pv";
			$where="al.`status`=1 AND al.real_type ={$typeid} and al.addtime >= {$begin} and al.addtime <= {$end}";//
			$order="ORDER BY FROM_UNIXTIME(al.addtime, '%Y-%m-%d') desc ";
			$group="GROUP BY FROM_UNIXTIME(al.addtime, '%Y-%m-%d')";
			$sql="select SELECT 
					FROM `dw_account_log` al LEFT JOIN dw_user u ON u.user_id=al.user_id 
					WHERE  $where GROUP ORDER";

			$query = $this->db->query(str_replace(array('SELECT', 'GROUP', 'ORDER'), array($select, $group, $order), $sql));
			return $query->result();
		}
		
		//充值日志
		public function report_rechargelog_day($begin, $end, $typeid)
		{
			$_where = "a.sina_time >= $begin and a.sina_time <= $end and a.type='recharge' and u.type_id = $typeid";
			$_select = "FROM_UNIXTIME(a.sina_time, '%Y-%m-%d') as dt,sum(a.money) as moneys,count(1) as pv";
			$_order = "order by FROM_UNIXTIME(sina_time, '%Y-%m-%d') desc";
			$_group = "group by FROM_UNIXTIME(sina_time, '%Y-%m-%d')";
			$sql = "select SELECT
						from `dw_account_log` a LEFT JOIN dw_user u on a.user_id = u.user_id
						where $_where GROUP ORDER";
			$query = $this->db->query(str_replace(array('SELECT', 'GROUP', 'ORDER'), array($_select, $_group, $_order), $sql));
			return $query->result();
		}

		public function report_cash_day($begin, $end, $typeid)
		{
			$_where = "c.addtime >= $begin and c.addtime <= $end and c.`status` = 1 and u.type_id = $typeid";
			$_select = "FROM_UNIXTIME(c.addtime, '%Y-%m-%d') as dt,sum(total) as moneys,count(1) as pv";
			$_order = "order by FROM_UNIXTIME(c.addtime, '%Y-%m-%d') desc";
			$_group = "group by FROM_UNIXTIME(c.addtime, '%Y-%m-%d')";
			$sql = "select SELECT
						from dw_account_cash c LEFT JOIN dw_user u on c.user_id = u.user_id
						where $_where GROUP ORDER";
			$query = $this->db->query(str_replace(array('SELECT', 'GROUP', 'ORDER'), array($_select, $_group, $_order), $sql));
			return $query->result();
		}

		

	}
?>
