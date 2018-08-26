<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/user_token_admin_model');
		// $this->check_login();
	}

	public function validate_token($token){
		if($token){
			if($res = $this->user_token_admin_model->get_token_info($token)){
				$time = time();
				if($res['expire_time'] < $time){	// token过期
					if($res['handle_time'] + LIFE_CYCLE > $time){	// 更新token及生存周期
						$new_token = token();
						set_cookie('token',$new_token,LIFE_CYCLE);
						!isset($_SESSION['user_info'])?$this->update_session():'';
						return $this->user_token_admin_model->update_token($res['id'],$new_token,$time);
					}
					return false;
				}else{	// token未过期  更新handle_time
					$this->user_token_admin_model->update_handle($res['id'],$time);
					!isset($_SESSION['user_info'])?$this->update_session():'';
					return true;
				}
			}
		}
		return false;
	}

	public function update_session($token){
		$user_id = $this->user_token_admin_model->getUserIdByToken($token);
		$res = $this->user_token_admin_model->getUserInfoById($user_id,1);
		$_SESSION['user_info']["rule"]=$res['purview'];
		$_SESSION['user_info']["user_id"]=$res['user_id'];
		$_SESSION['user_info']["logintype"]='admin';
		$_SESSION['user_info']['last_login_time'] = time();
		$_SESSION['user_info']['last_login_ip'] = $this->input->ip_address();
		$_SESSION['user_info']['real_status'] = $res['real_status'];
		$_SESSION['user_info']['phone'] =$res['phone'];
		$_SESSION['user_info']['real_name'] = $res['realname']?$res['realname']:$res['username'];
		$_SESSION['user_info']['type_id'] = $res['type_id'];
	}

	public function check_login(){
		// $token = $this->input->method(TRUE) == 'POST'?$this->input->post['token']:get_cookie('token');
		$token = $this->input->server('HTTP_TOKEN');
		if(!$this->validate_token($token)){
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'请先登录'
			];
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
	}
	
	public function check_rule(){
		
	}
}
