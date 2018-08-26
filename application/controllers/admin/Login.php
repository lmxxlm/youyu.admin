<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
	}
	public function login()
	{	
		$request = $this->input->post();
		$response = [];
		$this->form_validation->set_data($request);
		$this->form_validation->set_rules('username', '用户名', 'required',[
			'required'	=>	'%s不能为空'
		]);
		$this->form_validation->set_rules('password', '密码', 'required',[
			'required'	=>	'%s不能为空'
		]);
		$this->form_validation->set_rules('captcha', '验证码', 'callback_captcha_check');
		if($this->form_validation->run() == false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			$response['captcha'] = $this->captcha();
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$this->load->model('admin/user_model');
		$data = [
			'username'	=>	$request['username'],
			// 'password'	=>	login_pwd_handel($request['password']),
			'type'		=>	1
		];
		$res = $this->user_model->getUserInfoByUserName($data);
		
		if(!$res){	
			$response['errcode'] = 1;
			$response['error'] = [
				'username'	=> '用户名不存在'
			];
		}elseif($res['password'] != login_pwd_handel($request['password'])){
			$response['errcode'] = 1;
			$response['error'] = [
				'password'	=> '密码错误'
			];
		}elseif($res['islock']){
			$response['errcode'] = 1;
			$response['error'] = [
				'username'	=> '账户已被锁定'
			];
		}elseif(!$res['status']){
			$response['errcode'] = 1;
			$response['error'] = [
				'username'	=> '账户已被禁用'
			];
		}else{				// 登录成功
			$response = [
				'errcode'	=>	0,
				'data'		=>	[
					'username'	=>	$res['realname']
				]
			];
			//	保存信息到session
			$_SESSION['user_info']["rule"]=$res['purview'];
			$_SESSION['user_info']["user_id"]=$res['user_id'];
			$_SESSION['user_info']["logintype"]='admin';
			$_SESSION['user_info']['last_login_time'] = time();
			$_SESSION['user_info']['last_login_ip'] = $this->input->ip_address();
			$_SESSION['user_info']['real_status'] = $res['real_status'];
			$_SESSION['user_info']['phone'] =$res['phone'];
			$_SESSION['user_info']['real_name'] = $res['realname']?$res['realname']:$res['username'];
			$_SESSION['user_info']['type_id'] = $res['type_id'];
			//	生成token保存到数据库和cookie
			$this->load->model('admin/user_token_admin_model');
			$token = token();
			$res = $this->user_token_admin_model->update_login($res['user_id'],$token);
			set_cookie('token',$token,LIFE_CYCLE);
		}
		// 返回验证码
		// if($response['errcode']) $response['captcha'] = $this->captcha();
		die(json_encode($response,JSON_UNESCAPED_UNICODE));
	}

	public function captcha_check($captcha){
		if(strtolower($captcha) != strtolower($_SESSION['login_captcha'])){
			$this->form_validation->set_message('captcha_check', '{field}不正确');
            return false;
		}else{
			return true;
		}
	}
	public function captcha(){
		$this->load->helper('captcha');
		$config = array(
			'word'      	=> '',
			'img_path'  	=> './public/captcha/',
			'img_url'   	=> $this->config->item('base_url').'/public/captcha/',
			'font_path' 	=> './path/to/fonts/texb.ttf',
			'img_width' 	=> 80,
			'img_height'    => 30,
			'expiration'    => 7200,
			'word_length'   => 4,
			'font_size' 	=> 20,
			'img_id'    	=> 'Imageid',
			'pool'      	=> '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
		
			// White background and border, black text and red grid
			'colors'    => array(
				'background' => array(255, 255, 255),
				'border' => array(255, 255, 255),
				'text' => array(0, 0, 0),
				'grid' => array(255, 40, 40)
			)
		);
		$result = create_captcha($config);
		$_SESSION['login_captcha'] = $result['word'];
		return $result['url'];
	}

	public function verify(){
		$response = [
			'url'	=>	$this->captcha()
		];
		echo json_encode($response);
	}
}
