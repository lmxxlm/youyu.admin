<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Active extends Common {

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

	public function ActiveList(){
		$page = $this->input->post('page')?:1;
		$page_size = $this->input->post('size')?:PAGE_SIZE;
		$title = $this->input->post('title');
		$time_start = $this->input->post('time_start');
		$time_end = $this->input->post('time_end');
		$type_id = $this->input->post('type_id');

		$where = $like = [];
		$title and $like['title'] = $title;
		$time_start and $where['sendtime >='] = strtotime($time_start);
		$time_end and $where['sendtime <='] = strtotime($time_end.' 23:59:59');
		$type_id and $where['type_id'] = $type_id;

		$this->load->model('admin/Inform_web_message_group_queue_model');
		$res1 = $this->Inform_web_message_group_queue_model->getActiveList($where,$like,$page,$page_size);
		$res2 = $this->Inform_web_message_group_queue_model->getActiveCount($where,$like);
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$res1,
				'count'		=>	$res2
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function ActiveAdd(){
		$data = $this->input->post(array('type_id','title','content'));
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('type_id','公告类型','required|in_list[5001,5002]');
		$this->form_validation->set_rules('title','公告标题','required');
		$this->form_validation->set_rules('content','公告内容','required');
		$this->form_validation->set_message('required','{field}不能为空');
		$this->form_validation->set_message('in_list','{field}不在指点范围内');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$time = time();
		$data['batch_name'] = '第一批次';
		$data['sendtime'] = $time;
		$data['message_status'] = 1;
		$this->load->model('admin/Inform_web_message_group_queue_model');
		$this->load->model('admin/Inform_web_message_group_model');
		$this->load->model('admin/user_model');

		$this->db->trans_start();
		$queue_id = $this->Inform_web_message_group_queue_model->activeAdd($data);
		$where = [
			'a.type_id' =>	2
		];
		$users = $this->user_model->customerList($where);
		$batchData = [];
		foreach($users as $user){
			$batchData[] = array(
				'queue_id'	=>	$queue_id,
				'user_id'	=>	$user['user_id'],
				'sendtime'	=>	$time,
				'send_state'=>	4,
				'addtime'	=>	$time
			);
		}
		$res = $this->Inform_web_message_group_model->batchAdd($batchData);
		$this->db->trans_complete();
		if($res){
			$response = [
				'errcode'	=>	0,
				'msg'		=>	'公告发布成功'
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'公告发布失败'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}
}
