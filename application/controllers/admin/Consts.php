<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consts extends Common {

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
		$this->load->model('admin/linkage_type_model');
		$this->load->model('admin/linkage_model');
	}

	public function constsType(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$search = $this->input->post('search');
		$res = $this->linkage_type_model->getSystemConstsType($search,$page,$page_size);
		$count = $this->linkage_type_model->getSystemConstsTypeCount($search);
		// $orderby = array_column($res,'style');
		// array_multisort($orderby,SORT_NUMERIC,SORT_ASC,$res);
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'count'		=>	$count,
				'consts'	=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function typeAdd(){
		$data = $this->input->post(array('name','nid','order'));
		// $data = [
		// 	'name'	=>	'婚姻状况111',
		// 	'nid'	=>	'sdfd1',
		// 	'order'	=>	10
		// ];
		$response = [];
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('name', '系统参数类型', 'required|is_unique[linkage_type.name]');
		$this->form_validation->set_rules('nid', 'nid', 'required|is_unique[linkage_type.nid]');
		$this->form_validation->set_rules('order', 'order', 'required|integer');
		$this->form_validation->set_message('required', '{field}不能为空.');
		$this->form_validation->set_message('integer', '{field}应该为整数.');
		$this->form_validation->set_message('is_unique', '{field}不能重复.');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$data['addtime'] = time();
		$data['addip'] = $this->input->ip_address();
		if($this->linkage_type_model->add($data)){
			$response['errcode'] = 0;
			$response['msg'] = '添加成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '添加失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function typeEdit(){
		$id = $this->input->post('id');
		$res = $this->linkage_type_model->getSystemConstTypeById($id);
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

	public function typeUpdate(){
		$id = $this->input->post('id');
		$data = $this->input->post(array('name','nid','order'));
		// $id = 30;
		// $data = [
		// 	'name'	=>	'婚姻状况1',
		// 	'nid'	=>	'user_company_industry1',
		// 	'order'	=>	'10'
		// ];
		$response = [];
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('name', '系统参数类型', 'required|callback_check_name['.$id.']');
		$this->form_validation->set_rules('nid', 'nid', 'required|callback_check_nid['.$id.']');
		$this->form_validation->set_rules('order', 'order', 'required|integer');
		$this->form_validation->set_message('required', '{field}不能为空.');
		$this->form_validation->set_message('integer', '{field}应该为整数.');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		if($this->linkage_type_model->update($id,$data)){
			$response['errcode'] = 0;
			$response['msg'] = '更新成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '更新失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function check_name($name,$id){
		$this->form_validation->set_message('check_name', '{field}有冲突');
		return !$this->linkage_type_model->check_name($name,$id);
	}

	public function check_nid($nid,$id){
		$this->form_validation->set_message('check_nid', '{field}有冲突');
		return !$this->linkage_type_model->check_nid($nid,$id);
	}

	public function constsList(){
		$id = $this->input->post('id');
		$search = $this->input->post('search');
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$res = $this->linkage_model->getConstsByTypeId($id,$search,$page,$page_size);
		$count = $this->linkage_model->getConstsCountByTypeId($id,$search);
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'count'		=>	$count,
				'consts'	=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function constAdd(){
		$data = $this->input->post();
		// $data = [
		// 	'status'	=>	'1',
		// 	'order'		=>	'10',
		// 	'type_id'	=>	'13',
		// 	'pid'		=>	'0',
		// 	'name'		=>	'测试测试测试',
		// 	'value'		=>	'测试测试测试'
		// ];
		$response = [];
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('status', '系统常量状态', 'required|in_list[0,1]');
		$this->form_validation->set_rules('order', 'order', 'required|integer');
		$this->form_validation->set_rules('type_id', '系统常量类型', 'required|integer');
		$this->form_validation->set_rules('pid', '所属联动', 'required|integer');
		$this->form_validation->set_rules('name', '联动名称', 'required|callback_check_const_name_add['.$data['type_id'].']');
		$this->form_validation->set_rules('value', '联动值', 'required|callback_check_const_value_add['.$data['type_id'].']');
		$this->form_validation->set_message('required', '{field}不能为空.');
		$this->form_validation->set_message('integer', '{field}应该为整数.');
		$this->form_validation->set_message('in_list', '{field}不在指定范围内.');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$data['addtime'] = time();
		$data['addip'] = $this->input->ip_address();
		if($this->linkage_model->addConst($data)){
			$response['errcode'] = 0;
			$response['msg'] = '添加成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '添加失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function constEdit(){
		$id = $this->input->post('id');
		$res = $this->linkage_model->getConstById($id);
		$response = [];
		if($res){
			$response = [
				'errcode'	=>	0,
				'data'		=>	$res
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'此常量不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function constUpdate(){
		$id = $this->input->post('id');
		$data = $this->input->post(array('status','order','type_id','pid','name','value'));
		// $id = 834;
		// $data = [
		// 	'status'	=>	'1',
		// 	'order'		=>	'10',
		// 	'type_id'	=>	'13',
		// 	'pid'		=>	'0',
		// 	'name'		=>	'创意与策划11',
		// 	'value'		=>	'创意与策划11'
		// ];
		$response = [];
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('status', '系统常量状态', 'required|in_list[0,1]');
		$this->form_validation->set_rules('order', 'order', 'required|integer');
		$this->form_validation->set_rules('type_id', '系统常量类型', 'required|integer');
		$this->form_validation->set_rules('pid', '所属联动', 'required|integer');
		$this->form_validation->set_rules('name', '联动名称', 'required|callback_check_const_name_update['.$id.','.$data['type_id'].']');
		$this->form_validation->set_rules('value', '联动值', 'required|callback_check_const_value_update['.$id.','.$data['type_id'].']');
		$this->form_validation->set_message('required', '{field}不能为空.');
		$this->form_validation->set_message('integer', '{field}应该为整数.');
		$this->form_validation->set_message('in_list', '{field}不在指定范围内.');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		if($this->linkage_model->updateConst($id,$data)){
			$response['errcode'] = 0;
			$response['msg'] = '更新成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '更新失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function constDel(){
		$id = $this->input->post('id');
		$res = $this->linkage_model->getConstById($id);
		$response = [];
		if($res){
			if($this->linkage_model->delConst($id)){
				$response = [
					'errcode'	=>	0,
					'msg'		=>	'系统常量删除成功'
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
				'msg'		=>	'此系统常量不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function check_const_name_add($name,$type_id){
		$this->form_validation->set_message('check_const_name_add', '{field}在当前系统常量类型下有冲突');
		return !$this->linkage_model->check_const_name_add($name,$type_id);
	}

	public function check_const_value_add($value,$type_id){
		$this->form_validation->set_message('check_const_value_add', '{field}在当前系统常量类型下有冲突');
		return !$this->linkage_model->check_const_value_add($value,$type_id);
	}

	public function check_const_name_update($name,$params){
		$params = explode(',',$params);
		if(count($params) == 2){
			$id = $params[0];
			$type_id = $params[1];
			$this->form_validation->set_message('check_const_name_update', '{field}在当前系统常量类型下有冲突');
			return !$this->linkage_model->check_const_name_update($name,$id,$type_id);
		}else{
			$this->form_validation->set_message('check_const_name_update', '参数不正确');
			return false;
		}
	}

	public function check_const_value_update($name,$params){
		$params = explode(',',$params);
		if(count($params) == 2){
			$id = $params[0];
			$type_id = $params[1];
			$this->form_validation->set_message('check_const_value_update', '{field}在当前系统常量类型下有冲突');
			return !$this->linkage_model->check_const_value_update($name,$id,$type_id);
		}else{
			$this->form_validation->set_message('check_const_value_update', '参数不正确');
			return false;
		}
	}
}
?>