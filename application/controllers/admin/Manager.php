<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends Common {

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
		$this->load->model('admin/user_model');
		$this->load->model('admin/user_type_model');
	}

	public function lists(){
		$search = $this->input->post('search');
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$res = $this->user_model->managerList($search,$page,$page_size);
		foreach($res as &$v){
			$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
			$v['status'] = $v['status']?'启用':'禁用';
			$v['lasttime'] = $v['lasttime']?date('Y-m-d H:i:s',$v['lasttime']):'';
		}
		$count = $this->user_model->managerListCount($search);
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'count'		=>	$count,
				'consts'	=>	$res
			]
		];
		die(json_encode($response,JSON_UNESCAPED_UNICODE));
	}

	public function managerAdd(){
		$data = $this->input->post(array('username','password','realname','type_id','status','email'));
		// $data = [
		// 	'username'	=>	'admin11',
		// 	'password'	=>	'123456',
		// 	'realname'	=>	'测试号',
		// 	'type_id'	=>	'1',
		// 	'status'	=>	'0',
		// 	'email'		=>	'yjj19941226@qq.com'
		// ];
		$response = [];
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('username', '用户名', 'required|is_unique[user.username]');
		$this->form_validation->set_rules('password','密码','trim|required|min_length[6]|max_length[16]');
		$this->form_validation->set_rules('realname','真实姓名','required');
		$this->form_validation->set_rules('type_id','用户类型','required|callback_check_type_id');
		$this->form_validation->set_rules('status','用户状态','required|in_list[0,1]');
		$this->form_validation->set_rules('email','邮箱','valid_email');
		$this->form_validation->set_message('required', '{field}不能为空.');
		$this->form_validation->set_message('is_unique', '{field}有冲突.');
		$this->form_validation->set_message('in_list', '{field}不在指定范围内.');
		$this->form_validation->set_message('valid_email', '{field}格式不正确.');
		$this->form_validation->set_message('min_length', '{field}长度应在6-16位之间.');
		$this->form_validation->set_message('max_length', '{field}长度应在6-16位之间.');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$data['password'] = login_pwd_handel($data['password']);
		$data['addtime'] = time();
		$data['addip'] = $this->input->ip_address();
		if($this->user_model->userInfoAdd($data)){
			$response = [
				'errcode'	=>	0,
				'msg'		=>	'新增成功'
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'未知错误'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function managerEdit(){
		$user_id = $this->input->post('id');
		$response = [];
		$res = $this->user_model->getUserInfoById($user_id,1);
		if($res){
			$user_info = [
				'user_id'	=>	$res['user_id'],
				'username'	=>	$res['username'],
				'realname'	=>	$res['realname'],
				'type_id'	=>	$res['type_id'],
				'status'	=>	$res['status'],
				'email'		=>	$res['email']
			];
			$response = [
				'errcode'	=>	0,
				'data'		=>	[
					'userInfo'	=>	$user_info
				]
			];
		}else{
			$response = [
				'errcode'	=>	0,
				'msg'		=>	'用户不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function managerUpdate(){
		$id = $this->input->post('id');
		$data = $this->input->post(array('username','password','realname','type_id','status','email'));
		$response = [];
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('username', '用户名', 'required|callback_check_username['.$id.']');
		$this->form_validation->set_rules('password','密码','trim|min_length[6]|max_length[16]');
		$this->form_validation->set_rules('realname','真实姓名','required');
		$this->form_validation->set_rules('type_id','用户类型','required|callback_check_type_id');
		$this->form_validation->set_rules('status','用户状态','required|in_list[0,1]');
		$this->form_validation->set_rules('email','邮箱','valid_email');
		$this->form_validation->set_message('required', '{field}不能为空.');
		$this->form_validation->set_message('in_list', '{field}不在指定范围内.');
		$this->form_validation->set_message('valid_email', '{field}格式不正确.');
		$this->form_validation->set_message('min_length', '{field}长度应在6-16位之间.');
		$this->form_validation->set_message('max_length', '{field}长度应在6-16位之间.');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		if($this->user_model->getUserInfoById($id,1)){
			if(trim($data['password'])){
				$data['password'] = login_pwd_handel($data['password']);
			}else{
				unset($data['password']);
			}
			$data['uptime'] = time();
			$data['upip'] = $this->input->ip_address();
			if($this->user_model->userInfoUpdate($id,$data)){
				$response = [
					'errcode'	=>	0,
					'msg'		=>	'修改成功'
				];
			}else{
				$response = [
					'errcode'	=>	1,
					'msg'		=>	'未知错误'
				];
			}
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'用户不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function check_username($username,$id){
		$this->form_validation->set_message('check_username', '{field}有冲突');
		return !$this->user_model->checkUsername($username,$id);
	}

	public function check_type_id($type_id){
		$this->form_validation->set_message('check_type_id', '{field}不在用户类型范围内');
		return $this->user_type_model->checkTypeId($type_id)?true:false;
	}
}
