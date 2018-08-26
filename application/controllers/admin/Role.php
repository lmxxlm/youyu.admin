<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends Common {

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
		$this->load->model('admin/user_type_model');
	}

	public function getAllRole(){
		$response = $this->user_type_model->allUserTypeList();
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function roleList(){
		$search = $this->input->post('search');
		$page = $this->input->post('page')?$this->input->post('page'):2;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$response = [];
		$res = $this->user_type_model->userTypePageList($search,$page,$page_size);
		$count = $this->user_type_model->userTypeListCount($search);
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$res,
				'count'		=>	$count
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function roleAdd(){
		$data = $this->input->post(array('name','status','summary','remark','rule'));
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('name','角色','required|is_unique[user_type.name]');
		$this->form_validation->set_rules('status','状态','required|in_list[0,1]');
		$this->form_validation->set_rules('summary','简要说明','required');
		$this->form_validation->set_rules('remark','备注','required');
		$this->form_validation->set_message('required','{field}不能为空');
		$this->form_validation->set_message('is_unique','{field}有冲突');
		$this->form_validation->set_message('in_list','{field}不在指定范围内');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$data['purview'] = $data['rule'];
		unset($data['rule']);
		$data['type'] = 1;
		$data['addtime'] = time();
		$data['addip'] = $this->input->ip_address();
		if($this->user_type_model->userTypeAdd($data)){
			$response['errcode'] = 0;
			$response['msg'] = '添加成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '添加失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function roleEdit(){
		$id = $this->input->post('id');
		$res = $this->user_type_model->getUserTypeById($id);
		$response = [];
		if($res){
			$response['errcode'] = 0;
			$response['data'] = $res;
			$response['data']['rule'] = $response['data']['rule']?explode(',',$response['data']['rule']):[];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'此角色不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function roleUpdate(){
		$id = $this->input->post('id');
		$data = $this->input->post(array('name','status','summary','remark','rule'));
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('name','角色','required|callback_check_user_type_name['.$id.']');
		$this->form_validation->set_rules('status','状态','required|in_list[0,1]');
		$this->form_validation->set_rules('summary','简要说明','required');
		$this->form_validation->set_rules('remark','备注','required');
		$this->form_validation->set_message('required','{field}不能为空');
		$this->form_validation->set_message('in_list','{field}不在指定范围内');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}

		$data['purview'] = implode(',',$data['rule']);
		unset($data['rule']);
		if($this->user_type_model->userTypeUpdate($id,$data)){
			$response['errcode'] = 0;
			$response['msg'] = '更新成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '更新失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function roleDel(){
		$id = $this->input->post('id');
		if($this->user_type_model->checkTypeId($id)){
			if($this->user_type_model->userTypeDel($id)){
				$response = [
					'errcode'	=>	0,
					'msg'		=>	'删除成功'
				];
			}else{
				$response = [
					'errcode'	=>	1,
					'msg'		=>	'删除失败'
				];
			}
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'此角色不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function check_user_type_name($name,$type_id){
		$this->form_validation->set_message('check_user_type_name', '{field}有冲突');
		return !$this->user_type_model->checkUserTypeName($name,$type_id);
	}
}
