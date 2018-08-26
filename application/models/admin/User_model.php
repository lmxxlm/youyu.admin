<?php
	class User_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function managerList($search,$page,$page_size){
			if($search){
				$this->db->like('a.username',$search);
				$this->db->like('a.realname',$search);
			}
			$this->db->select('a.user_id,a.username,a.realname,a.addtime,a.status,b.name role,a.logintime,a.lasttime,a.lastip');
			$this->db->from('user a');
			$this->db->where('a.status = 1');
			$this->db->where('b.type = 1');
			$this->db->join('user_type b','a.type_id = b.type_id');
			$this->db->order_by('a.addtime asc');
			$this->db->limit($page_size,($page-1)*$page_size);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}
		
		public function managerListCount($search){
			if($search){
				$this->db->like('a.username',$search);
				$this->db->like('a.realname',$search);
			}
			$this->db->from('user a');
			$this->db->where('a.status = 1');
			$this->db->where('b.type = 1');
			$this->db->join('user_type b','a.type_id = b.type_id');
			$query = $this->db->count_all_results();
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function customerList($where = [],$where_str = '',$page = 0,$page_size = 0){
			$this->db->select('a.user_id,a.username,a.realname,a.sex,a.email,a.phone,a.card_id,a.addtime,a.is_company,a.invite_userid,a.app_marketing,a.card_type,b.realname as invite_real,c.bank_status');
			$this->db->from('user a');
			$this->db->join('user b','a.invite_userid = b.user_id','left');
			$this->db->join('(select user_id,bank_status from `dw_account_bank` where `bank_status` = 1 group by user_id) as c','a.user_id = c.user_id','left');
			$where and $this->db->where($where);
			$where_str and $this->db->where($where_str);
			$this->db->order_by('a.user_id','desc');
			$page and $this->db->limit($page_size,($page-1)*$page_size);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function customerSearchList($where,$where_str){
			$this->db->select('a.user_id,a.username,a.realname,a.sex,a.email,a.phone,a.card_id,a.addtime,a.is_company,a.invite_userid,a.app_marketing,b.realname as invite_real,c.bank_status');
			$this->db->from('user a');
			$this->db->join('user b','a.invite_userid = b.user_id','left');
			$this->db->join('(select user_id,bank_status from `dw_account_bank` where `bank_status` = 1 group by user_id) as c','a.user_id = c.user_id','left');
			$this->db->where($where);
			$this->db->where($where_str);
			$this->db->order_by('a.user_id','desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function customerListCount($where,$where_str){
			$this->db->from('user a');
			
			$this->db->join('(select user_id,bank_status from `dw_account_bank` where `bank_status` = 1 group by user_id) as c','a.user_id = c.user_id','left');
			$this->db->where($where);
			$this->db->where($where_str);
			$query = $this->db->count_all_results();
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getUserInfoByUserName($data){
			$where['a.username'] = $data['username'];
			isset($data['type']) and $where['b.type'] = $data['type'];
			$this->db->select('a.*,b.purview,b.type,b.name as typename');
			$this->db->from('user a');
			$this->db->join('user_type b','a.type_id=b.type_id');
			$this->db->where($where);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function getUserIdByToken($token){
			$this->db->select('user_id');
			$this->db->from('user_token_admin');
			$this->db->where('token',$token);
			$query = $this->db->get();
			$result = $query->row_array();
			log_message('info','sql:'.$this->db->last_query());
			return $result['user_id'];
		}
		
		public function getUserInfoById($id,$type){
			$where['a.user_id'] = $id;
			if($type == 1){
				$where['b.type'] = 1;
				$this->db->select('a.*,b.purview,b.type,b.name as typename');
			}elseif($type == 2){
				$where['b.type'] = 2;
				$this->db->select('a.username,a.realname,a.phone,a.card_id,a.invite_userid');
			}
			// $this->db->select('a.user_id,a.type_id,a.username,a.realname,a.status,a.email,a.phone,b.purview,b.type,b.name as typename');
			$this->db->from('user a');
			$this->db->join('user_type b','a.type_id=b.type_id');
			$this->db->where($where);
			$query = $this->db->get();
			$result = $query->row_array();
			log_message('info','sql:'.$this->db->last_query());
			return $result;
		}

		public function checkUsername($username,$id){
			$this->db->where('user_id !=',$id);
			$this->db->where('username',$username);
			$query = $this->db->count_all_results('user');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function userInfoAdd($data){
			$query = $this->db->insert('user',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function userInfoUpdate($id,$data){
			$this->db->where('user_id',$id);
			$query = $this->db->update('user',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function userDel($ids){
			$this->db->where_in('user_id',$ids);
			$query = $this->db->delete('user');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getCompanyListInfo($where,$like,$page,$page_size){
			$this->db->select('a.user_id,a.username,a.realname,a.type_id,a.trackid,a.is_company,a.status,a.addtime,b.linkman1,b.relation1,b.tel1,b.company_name,c.name type_name');
			$this->db->where($where);
			$this->db->like($like);
			$this->db->from('user a');
			$this->db->join('userinfo b','a.user_id = b.user_id','left');
			$this->db->join('user_type c','a.type_id = c.type_id','left');
			$this->db->limit($page_size,($page-1)*$page_size);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getCompanyListCount($where,$like){
			$this->db->where($where);
			$this->db->like($like);
			$this->db->from('user a');
			$this->db->join('userinfo b','a.user_id = b.user_id','left');
			$this->db->join('user_type c','a.type_id = c.type_id','left');
			$query = $this->db->count_all_results();
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getCompanyInfo($id){
			$this->db->select('a.user_id,a.username,a.real_status,a.realname,b.litpic,b.ability,b.company_workyear,b.company_worktime1,b.company_worktime2,b.private_employee,b.linkman1,b.relation1,b.tel1,b.company_reamrk,b.company_name,b.private_commerceid,b.province,b.city,b.area,b.company_address,b.others');
			$this->db->where('a.user_id',$id);
			$this->db->from('user a');
			$this->db->join('userinfo b','a.user_id = b.user_id','left');
			$this->db->limit(1);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function customerDownList($where,$where_str,$like,$like_or,$page,$page_size){
			$this->db->select('a.user_id,a.username,a.realname,a.addtime,a.invite_userid,a.trackid,b.username as invite_name,b.realname as invite_real,c.bank_status');
			$this->db->from('user a');
			$this->db->join('user b','a.invite_userid = b.user_id','left');
			$this->db->join('(select user_id,bank_status from `dw_account_bank` where `bank_status` = 1 group by user_id) as c','a.user_id = c.user_id','left');
			$this->db->where($where);
			$this->db->where($where_str);
			if($like && $like_or){
				$this->db->group_start();
				$this->db->like($like);
				$this->db->or_like($like_or);
				$this->db->group_end();
			}
			$this->db->order_by('a.user_id','desc');
			$this->db->limit($page_size,($page-1)*$page_size);
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function customerDownListCount($where,$where_str,$like,$like_or){
			$this->db->from('user a');
			$this->db->join('user b','a.invite_userid = b.user_id','left');
			$this->db->join('(select user_id,bank_status from `dw_account_bank` where `bank_status` = 1 group by user_id) as c','a.user_id = c.user_id','left');
			$this->db->where($where);
			$this->db->where($where_str);
			if($like && $like_or){
				$this->db->group_start();
				$this->db->like($like);
				$this->db->or_like($like_or);
				$this->db->group_end();
			}
			$query = $this->db->count_all_results();
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function checkInUsername($where){
			$this->db->select('user_id,username,realname');
			$this->db->where($where);
			$query = $this->db->get('user');
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}
	}
?>