<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends Common {

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
		$this->load->model('admin/system_model');
	}

	public function params(){
		$search = $this->input->post('search');
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$res = $this->system_model->getSystemParams($search,$page,$page_size);
		$count = $this->system_model->getSystemParamsCount($search);
		// $orderby = array_column($res,'style');
		// array_multisort($orderby,SORT_NUMERIC,SORT_ASC,$res);
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'count'		=>	$count,
				'params'	=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function add(){
		$data = $this->input->post(array('name', 'nid','value','type','style','status'));
		// $data = [
		// 	'name'	=>	'网站名称112',
		// 	'nid'	=>	'ssssfsdsss2',
		// 	'value'	=>	'ddd',
		// 	'type'	=>	'0',
		// 	'style'	=>	'1',
		// 	'status'=>	'0'
		// ];
		$response = [];
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('name', '系统参数名称', 'required|is_unique[system.name]');
		$this->form_validation->set_rules('nid', 'nid', 'required|is_unique[system.nid]');
		$this->form_validation->set_rules('value', 'value', 'required');
		$this->form_validation->set_rules('type', '输入类型', 'required|in_list[0,1,2,3]');
		$this->form_validation->set_rules('style', '参数分组', 'required|in_list[1,2,3,4]');
		$this->form_validation->set_rules('status', '状态', 'required|in_list[0,1]');
		$this->form_validation->set_message('required', '{field}不能为空.');
		$this->form_validation->set_message('is_unique', '{field}不能重复.');
		$this->form_validation->set_message('in_list', '{field}不在指定范围内.');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		if($this->system_model->add($data)){
			$response['errcode'] = 0;
			$response['msg'] = '添加成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '添加失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function edit(){
		$id = 1;
		$res = $this->system_model->getSystemParamsById($id);
		if($res){
			$response = [
				'errcode'	=>	0,
				'data'		=>	$res
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'请求的数据为空'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function update(){
		$id = $this->input->post('id');
		$data = $this->input->post(array('name', 'nid','value','type','style','status'));
		// $id = 62;
		// $data = [
		// 	'name'	=>	'网站名称1112',
		// 	'nid'	=>	'ssssfsd1112',
		// 	'value'	=>	'ddd',
		// 	'type'	=>	'0',
		// 	'style'	=>	'1',
		// 	'status'=>	'0'
		// ];
		$response = [];
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('name', '系统参数名称', 'required|callback_check_name['.$id.']');
		$this->form_validation->set_rules('nid', 'nid', 'required|callback_check_nid['.$id.']');
		$this->form_validation->set_rules('value', 'value', 'required');
		$this->form_validation->set_rules('type', '输入类型', 'required|in_list[0,1,2,3]');
		$this->form_validation->set_rules('style', '参数分组', 'required|in_list[1,2,3,4]');
		$this->form_validation->set_rules('status', '状态', 'required|in_list[0,1]');
		$this->form_validation->set_message('required', '{field}不能为空.');
		$this->form_validation->set_message('in_list', '{field}不在指定范围内.');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		if($this->system_model->update($id,$data)){
			$response['errcode'] = 0;
			$response['msg'] = '更新成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '更新失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function del(){
		$id = $this->input->post('id');
		// $id = 81;
		$res = $this->system_model->getSystemParamsById($id);
		$response = [];
		if($res){
			if($res['status']){
				if($this->system_model->del($id)){
					$response = [
						'errcode'	=>	0,
						'msg'		=>	'系统参数删除成功'
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
					'msg'		=>	'此系统参数无法被删除'
				];
			}
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'此系统参数不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function check_name($name,$id){
		$this->form_validation->set_message('check_name', '{field}有冲突');
		return !$this->system_model->checkName($name,$id);
	}

	public function check_nid($nid,$id){
		$this->form_validation->set_message('check_nid', '{field}有冲突');
		return !$this->system_model->checkNid($nid,$id);
	}

}
