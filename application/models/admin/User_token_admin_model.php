<?php
	class User_token_admin_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
		}

		public function update_login($user_id,$token){
			$this->db->select('id');
			$this->db->from('user_token_admin');
			$this->db->where('user_id',$user_id);
			$this->db->limit(1);
			$query = $this->db->get();
			$res = $query->row_array();
			$result = [];
			$time = time();
			if($res){
				$where_update['id'] = $res['id'];
				$data = [
					'token'			=>	$token,
					'handle_time'	=>	$time,
					'expire_time'	=>	$time+LIFE_CYCLE
				];
				return $this->db->where($where_update)->update('user_token_admin',$data);
			}else{
				$data = [
					'token'			=>	$token,
					'user_id'		=>	$user_id,
					'login_time'	=>	$time,
					'handle_time'	=>	$time,
					'expire_time'	=>	$time+LIFE_CYCLE
				];
				$this->db->set($data);
				return $this->db->insert('user_token_admin');
			}
		}

		public function get_token_info($token){
			$this->db->select('*');
			$this->db->where('token',$token);
			$query = $this->db->get('user_token_admin');
			$res = $query->row_array();
			return $res;
		}

		public function update_token($id,$token,$time){
			$where['id'] = $id;
			$data['token'] = $token;
			$data['handle_time'] = $time;
			$data['expire_time'] = $time+LIFE_CYCLE;
			return $this->db->where($where)->update('user_token_admin',$data);
		}

		public function update_handle($id,$time){
			$where['id'] = $id;
			$data['handle_time'] = $time;
			return $this->db->where($where)->update('user_token_admin',$data);
		}

		// public function check_login($token){
		// 	$this->db->select('id');
		// 	$this->db->where('token',$token);
		// 	$query = $this->db->get('user_token_admin');
		// 	$res = $query->row_array();
		// 	$token = token();
		// 	$time = time();
		// 	$expire = 7200;
		// 	if($res && $res['expire_time'] >= $time){
		// 		$where_update['id'] = $res['id'];
		// 		$data = [
		// 			'token'			=>	$token,
		// 			'expire_time'	=>	$time+$expire
		// 		];
		// 		$this->db->where($where_update)->update('user_token_admin',$data);
		// 		$result = [
		// 			'token'		=>	$token,
		// 			'expire'	=>	$expire
		// 		];
		// 		return $result;
		// 	}
		// 	return false;
		// }

	}
?>