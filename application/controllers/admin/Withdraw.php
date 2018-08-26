<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Withdraw extends Common {

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

	public function withdrawList(){
		$page = $this->input->post('page')?:1;
		$page_size = $this->input->post('size')?:PAGE_SIZE;
		$username = $this->input->post('username');
		$realname = $this->input->post('realname');
		$withdraw_no = $this->input->post('withdraw_no');
		$type = $this->input->post('type');
		$money = $this->input->post('money');
		$add_time_begin = $this->input->post('add_time_begin');
		$add_time_end = $this->input->post('add_time_end');
		$verify_time_begin = $this->input->post('verify_time_begin');
		$verify_time_end = $this->input->post('verify_time_end');
		$status = $this->input->post('status');
		$sina_st = $this->input->post('sina_st');
		
		$where = $like = [];
		$username and $like['b.username'] = $username;
		$realname and $like['b.realname'] = $realname;
		$withdraw_no and $where['a.withdraw_no'] = $withdraw_no;
		if($money && $type){
			if(in_array($type,['<=','=','>='])){
				$where['a.total '.$type] = $money;
			}
		}
		$add_time_begin and $where['a.addtime >='] = strtotime($add_time_begin);
		$add_time_end and $where['a.addtime <='] = strtotime($add_time_end.' 23:59:59');

		$verify_time_begin and $where['a.verify_time >= '] = strtotime($verify_time_begin);
		$verify_time_end and $where['a.verify_time <= '] = strtotime($verify_time_end.' 23:59:59');
		is_numeric($status) and $where['a.status'] = $status;
		is_numeric($sina_st) and $where['a.sina_st'] = $sina_st;

		$this->load->model('admin/Account_cash_model');
		$this->load->helper('bank');

		$res1 = $this->Account_cash_model->getWithdrawList($where,$like,$page,$page_size);
		$res2 = $this->Account_cash_model->getWithdrawCount($where,$like);
		foreach($res1 as &$v){
			$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
			$v['verify_time'] = $v['verify_time']?date('Y-m-d H:i:s',$v['verify_time']):'';
			$v['bank'] = bank($v['bank']);
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$res1,
				'count'		=>	$res2['count'],
				'sum'		=>	[
					'totals'		=>	$res2['totals'],
					'num'			=>	$res2['num']
				]
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function cashLook(){
		$id = $this->input->post('id');
		$this->load->model('admin/Account_cash_model');
		$this->load->helper('bank');
		if($res = $this->Account_cash_model->getCashInfo($id)){
			$res['bank'] = bank($res['bank']);
			$res['addtime'] = $res['addtime']?date('Y-m-d',$res['addtime']):'';
			$response = [
				'errcode'	=>	0,
				'data'		=>	$res
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'数据不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function cashCheck(){
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$verify_remark = $this->input->post('verify_remark');

		$this->load->model('admin/Account_cash_model');
		if($res = $this->Account_cash_model->getCashInfo($id)){
			if($res['status']){
				$response = [
					'errcode'	=>	1,
					'msg'		=>	'此提现审核已被审核过'
				];
			}else{
				$where['id'] = $id;
				$data['status'] = $status;
				$data['verify_remark'] = $verify_remark;
				$data['verify_time'] = time();
				$data['verify_userid'] = $_SESSION['user_info']['user_id'];
				if($this->Account_cash_model->cashCheck($where,$data)){
					$response = [
						'errcode'	=>	0,
						'msg'		=>	'审核完成'
					];
				}else{
					$response = [
						'errcode'	=>	1,
						'msg'		=>	'审核异常'
					];
				}
			}
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'数据不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

}
