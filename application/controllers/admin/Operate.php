<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operate extends Common {

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

	public function sms(){
		$page = $this->input->post('page')?:1;
		$page_size = $this->input->post('size')?:PAGE_SIZE;
		$mobile = $this->input->post('mobile');
		$is_type = $this->input->post('is_type');
		$trackid = $this->input->post('trackid');

		$where = $like = [];

		$mobile and $where['mobile'] = $mobile;
		$is_type and $where['is_type'] = $is_type;
		$trackid and $like['trackid'] = $trackid;

		$this->load->model('admin/User_sms_code_model');
		$res1 = $this->User_sms_code_model->getSmsCodeList($where,$like,$page,$page_size);
		$res2 = $this->User_sms_code_model->getSmsCodeCount($where,$like);
		foreach($res1 as &$v){
			$v['ctime'] = $v['ctime']?date('Y-m-d H:i:s',$v['ctime']):'';
			$v['is_last'] = $v['is_last']?'未过期':'已过期';
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$res1,
				'count'		=>	$res2
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}
	
}
