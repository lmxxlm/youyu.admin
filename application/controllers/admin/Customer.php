<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends Common {

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
		$this->load->model('admin/User_model');
	}

	public function lists(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$type = $this->input->post('type');
		$search = $this->input->post('search');
		$start_time = $this->input->post('start_time');
		$end_time = $this->input->post('end_time');
		$st = $this->input->post('st');
		$bank = $this->input->post('bank');
		$where = [];
		$where_str = 'a.type_id in (2,17)';
		$type and $where['a.'.$type] = $search;
		// if($register_time){
		// 	$start_register_time = substr($register_time,0,10);
		// 	$end_register_time = substr($register_time,11);
		// 	$where['a.addtime >='] = strtotime($start_register_time);
		// 	$where['a.addtime <='] = strtotime($end_register_time.' 23:59:59');
		// }
		$start_time and $where['a.addtime >='] = strtotime($start_time);
		$end_time and $where['a.addtime <='] = strtotime($end_time.' 23:59:59');
		if($st){
			if($st == 1) $where['a.real_status'] = 1;
			if($st == 2) $where['a.real_status'] = 0;
		}
		if($bank){
			if($bank == 1) $where['c.bank_status'] = 1;
			if($bank == 2) $where['c.bank_status'] = 'is null';
		}
		$res = $this->User_model->customerList($where,$where_str,$page,$page_size);
		$count = $this->User_model->customerListCount($where,$where_str);
		foreach($res as &$v){
			$v['sex'] = $v['sex'] == 1?'男':($v['sex'] == 2?'女':'');
			$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
			$v['is_company'] = $v['is_company']?'企业':'个人';
			$v['card_type'] = $this->config->item('system_http').'://'.$this->config->item('system_domain').'/event/regChannel/'.$v['card_type'];
			$v['bank_status'] = $v['bank_status']?'已绑卡':'未绑卡';
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'count'		=> $count,
				'res'		=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function export(){
		$type = $this->input->post('type');
		$search = $this->input->post('search');
		$start_time = $this->input->post('start_time');
		$end_time = $this->input->post('end_time');
		$st = $this->input->post('st');
		$bank = $this->input->post('bank');
		$where = [];
		$where_str = 'a.type_id in (2,17)';
		$type !== '' and $where['a.'.$type] = $search;
		// if($register_time){
		// 	$start_register_time = substr($register_time,0,10);
		// 	$end_register_time = substr($register_time,11);
		// 	$where['a.addtime >='] = strtotime($start_register_time);
		// 	$where['a.addtime <='] = strtotime($end_register_time.' 23:59:59');
		// }
		$start_time !== '' and $where['a.addtime >='] = strtotime($start_time);
		$end_time !== '' and $where['a.addtime <='] = strtotime($end_time.' 23:59:59');
		if($st){
			if($st == 1) $where['a.reael_status'] = 1;
			if($st == 2) $where['a.real_status'] = 0;
		}
		if($bank){
			if($bank == 1) $where['c.bank_status'] = 1;
			if($bank == 2) $where['c.bank_status'] = 'is null';
		}
		$res = $this->User_model->customerSearchList($where,$where_str);
		foreach($res as &$v){
			$v['sex'] = $v['sex'] == 1?'男':($v['sex'] == 2?'女':'');
			$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
			$v['is_company'] = $v['is_company']?'企业':'个人';
			// $v['card_type'] = $this->config->item('system_http').'://'.$this->config->item('system_domain').'/event/regChannel/'.$v['card_type'];
			$v['bank_status'] = $v['bank_status']?'已绑卡':'未绑卡';
		}
		$newContent = [];
		// $data = "ID,用户名,真实姓名,性别,邮箱,手机,身份证,添加时间,类型,上线用户ID,上线用户名称,推广标识,是否绑卡\n";
		// foreach($res as $n){
		// 	$content = array_values($n);
		// 	$newContent[] = implode(",",$content);
		// }
		// $data .= implode("\n",$newContent);
		// $this->load->helper('download');
		// force_download('customer_lists.csv',$data);
		$response = [
			'errcode'		=>	0,
			'data'			=>	[
				'headKey'	=>	['user_id','username','realname','sex','email','phone','card_id','addtime','is_company','invite_userid','invite_real','app_marketing','bank_status'],
				'headVal'	=>	['ID','用户名','真实姓名','性别','邮箱','手机','身份证','添加时间','类型','上线用户ID','上线用户名称','推广标识','是否绑卡'],
				'data'	=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function edit(){
		$id = $this->input->post('id');
		$response = [];
		if($res = $this->User_model->getUserInfoById($id,2)){
			$response = [
				'errcode'	=>	0,
				'data'		=>	$res
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'用户不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function update(){
		$id = $this->input->post('id');
		$data = $this->input->post(array('username','realname','card_id'));
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('username','用户名','required');
		$this->form_validation->set_rules('realname','真实姓名');
		$this->form_validation->set_rules('card_id','身份证号','callback_check_card_id');
		$this->form_validation->set_message('required','{field}不能为空');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		if($res = $this->User_model->userInfoUpdate($id,$data)){
			$response['errcode'] = 0;
			$response['msg'] = '更新成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '更新失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function del(){
		$ids = $this->input->post('ids');
		if($res = $this->User_model->userDel($ids)){
			$response['errcode'] = 0;
			$response['msg'] = '删除成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '删除失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function getInfoByUsername(){
		$username = $this->input->post('username');
		$where = [];
		$where['type_id'] = 2;
		$where['username'] = $username;
		$res = $this->User_model->checkInUsername($where);
		if($res){
			$response = [
				'errcode'	=>	0,
				'data'		=>	$res
			];
		}else{
			$response = [
				'errcode'	=>	0,
				'msg'		=>	'用户不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function check_card_id($card_id){
		$this->form_validation->set_message('check_card_id','{field}不正确');
		return is_idcard($card_id);
	}

	public function accountCount(){
		$id = $this->input->post('id');
		$this->load->model('admin/Account_sina_model');
		$this->load->model('admin/Borrow_tender_model');
		$response = $this->Account_sina_model->getAccountCount($id);
		$xutou = $this->Borrow_tender_model->getRewardUseCount($id);
		if($response){
			$response['xutou'] = $xutou['xutou'];
		}else{
			$response = [];
            $response['user_id'] = $id;
            $response['xutou'] = $xutou['xutou'];
            $response['id'] = false;
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function accountRecord(){
		$id = $this->input->post('id');
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$money = $this->input->post('money');
		$type = $this->input->post('type');
		$start_time = $this->input->post('start_time');
		$end_time = $this->input->post('end_time');
		$where['user_id'] = $id;
		$money and $where['money'] = $money;
		$type and $where['type'] = $type;
		$start_time and $where['addtime >= '] = strtotime($start_time);
		$end_time and $where['addtime <= '] = strtotime($end_time.' 23:59:59');
		$this->load->model('admin/Account_log_model');
		$res = $this->Account_log_model->getAccountLog($where,$page,$page_size);
		$count = [
			'moneys'	=>	0,
			'funds'	=>	0
		];
		foreach($res as &$v){
			$v['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
			$v['remark'] = strip_tags($v['remark']);
			$count['moneys'] += $v['money'];
			$count['funds'] += $v['fund'];
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$res,
				'count'		=>	$count
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function accountLogType(){
		$response = [
			ACCOUNT_LOG_TYPE_RECHARGE => '充值',
			ACCOUNT_LOG_TYPE_BONUS => '平台奖励',
			ACCOUNT_LOG_TYPE_CASH_APPLY => '提现',
			ACCOUNT_LOG_TYPE_CASH_SUCCESS => '提现成功',
			ACCOUNT_LOG_TYPE_CASH_FAILURE => '提现失败',
			ACCOUNT_LOG_TYPE_CASH_CANCEL => '取消提现',
			ACCOUNT_LOG_TYPE_CASH_FROST => '提现冻结',
			ACCOUNT_LOG_TYPE_TENDER => '投资',
			ACCOUNT_LOG_TYPE_TENDER_RECEIVE => '借款人收款',
			ACCOUNT_LOG_TYPE_REPAYMENT => '借款人还款',
			ACCOUNT_LOG_TYPE_REPAYMENT_RECEIVE => '投资人收款',
			ACCOUNT_LOG_TYPE_BORROW_SUCCESS => '借款入帐',
			ACCOUNT_LOG_TYPE_REWARD_USE => '使用红包',
			ACCOUNT_LOG_TYPE_INVEST_FALSE => '投资失败资金',
			ACCOUNT_LOG_TYPE_LATE_REPAYMENT => '逾期利息',
			ACCOUNT_LOG_TYPE_LATE_COLLECTION => '逾期收益',
			ACCOUNT_LOG_TYPE_RECHARGE_FEE => '提现手续费'
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function checkVip(){
		$id = $this->input->post('id');
		$response = [];
		$userInfo = $this->User_model->getUserInfoById($id,2);
		$moneyResult = $this->checkMoney($id,10000);
		$numResult = $this->checkInviteUser($id,5);
		$response['errCode'] = 0;
		$response['data']['userInfo'] = [
			'realname'	=>	$userInfo['realname'],
			'phone'		=>	$userInfo['phone']
		];
		if($moneyResult['vip'] === true || $numResult['vip'] === true){
			$response['data']['result'] = 'VIP';
		}else{
			$response['data']['result'] = '条件不足';
		}
		$response['data']['accountInfo'] = $moneyResult['accountInfo'];
		$response['data']['num'] = $numResult['num'];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function checkMoney($id,$money){
		$this->load->model('admin/Account_sina_model');
		$result = [];
		if($res = $this->Account_sina_model->getAccountCount($id)){
			if(($res['use_money'] + $res['wait_interest'] + $res['wait_capital']) >= $money){
				$result = [
					'vip'	=>	true,
					'accountInfo'	=>	[
						'use_money'		=>	$res['use_money'],
						'collection'	=>	$res['collection']
					]
				];
			}else{
				$result = [
					'vip'	=>	false,
					'accountInfo'	=>	[
						'use_money'		=>	$res['use_money'],
						'collection'	=>	$res['collection']
					]
				];
			}
		}
		return $result;
	}

	public function checkInviteUser($id,$num){
		$this->load->model('admin/Borrow_tender_model');
		$time = time() - 31 * 24 * 60 * 60;
		$res = $this->Borrow_tender_model->getInviteUserTender($id,$time);
		if($res >= $num){
			$result = [
				'vip'	=>	true,
				'num'	=>	$res
			];
		}else{
			$result = [
				'vip'	=>	false,
				'num'	=>	$res
			];
		}
		return $result;
	}

	public function company(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$username = $this->input->post('username');
		$realname = $this->input->post('realname');
		$start_time = $this->input->post('start_time');
		$end_time = $this->input->post('end_time');
		$real_status = $this->input->post('real_status');
		$response = [];
		$where = [
			'a.is_company'	=>	1
		];
		$like = [];
		if($username) $like['a.username'] = $username;
		if($realname) $like['a.realname'] = $realname;
		if($start_time) $where['a.addtime >='] = strtotime($start_time);
		if($end_time) $where['a.addtime <='] = strtotime($end_time);
		if($real_status) $where['a.real_status'] = $real_status;
		$res = $this->User_model->getCompanyListInfo($where,$like,$page,$page_size);
		$count = $this->User_model->getCompanyListCount($where,$like);
		if($res){
			foreach($res as &$v){
				$v['is_company'] = $v['is_company']?'企业':'个人';
				$v['status'] = $v['status']?'开通':'隐藏';
				$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
			}
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'count'		=>	$count,
				'lists'		=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function companyEdit(){
		$id = $this->input->post('id');
		$id = 151;
		if($res = $this->User_model->getCompanyInfo($id)){
			$res['litpic'] = $this->config->item('system_domain').'/'.$res['litpic'];
			$response['errcode'] = 0;
			$response['data'] = $res;
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '此企业不存在';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function companyUpdate(){
		$id = $this->input->post('id');
		$data1 = $this->input->post(array('realname','company_workyear','company_worktime1','company_worktime2','private_employee','linkman1','relation1','tel1','company_reamrk','company_name','private_commerceid','province','city','area','company_address','others','real_status'));
		$this->form_validation->set_data($data1);
		$this->form_validation->set_rules('realname','企业名称','required');
		$this->form_validation->set_rules('linkman1','企业联系人','required');
		$this->form_validation->set_rules('relation1','联系人职位','required');
		$this->form_validation->set_rules('company_name','法人代表','required');
		$this->form_validation->set_rules('private_commerceid','税号','required');
		$this->form_validation->set_rules('province','省份','integer');
		$this->form_validation->set_rules('city','城市','integer');
		$this->form_validation->set_rules('area','区/县','integer');
		$this->form_validation->set_rules('real_status','审核结果','in_list[1,2]');
		$this->form_validation->set_message('required','{field}不能为空！');
		$this->form_validation->set_message('integer','{field}不正确');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$data2['real_status'] = $data1['real_status'];
		$data2['realname'] = $data1['realname'];
		unset($data1['real_status']);
		unset($data1['realname']);
		$this->load->model('admin/Userinfo_model');
		$res1 = $this->Userinfo_model->companyUpdate($id,$data1);
		$res2 = $this->User_model->userInfoUpdate($id,$data2);
		if($res1 && $res2){
			$response['errcode'] = 0;
			$response['msg'] = '更新成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '更新失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function downLists(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$type = $this->input->post('type');
		$search = $this->input->post('search');
		$start_time = $this->input->post('start_time');
		$end_time = $this->input->post('end_time');
		$st = $this->input->post('st');
		$bank = $this->input->post('bank');
		$where = [];
		$like = [];
		$like_or = [];
		$where_str = 'a.type_id in (2,17)';
		if($search){
			switch($type){
				case 'username':
					$like['a.username'] = $search;
				break;
				case 'realname':
					$like['a.username'] = $search;
				break;
				case 'invite_username':
					$like['b.username'] = $search;
				break;
				case 'invite_realname':
					$like['b.realname'] = $search;
				break;
				default:
					$like['a.username'] = $search;
					$like_or['a.realname'] = $search;
					$like_or['b.username'] = $search;
					$like_or['b.realname'] = $search;
			}
		}
		$start_time !== '' and $where['a.addtime >='] = strtotime($start_time);
		$end_time !== '' and $where['a.addtime <='] = strtotime($end_time.' 23:59:59');
		if($st){
			if($st == 1) $where['a.real_status'] = 1;
			if($st == 2) $where['a.real_status'] = 0;
		}
		if($bank){
			if($bank == 1) $where['c.bank_status'] = 1;
			if($bank == 2) $where['c.bank_status'] = 'is null';
		}
		$res = $this->User_model->customerDownList($where,$where_str,$like,$like_or,$page,$page_size);
		$count = $this->User_model->customerDownListCount($where,$where_str,$like,$like_or);
		foreach($res as &$v){
			$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
			$v['bank_status'] = $v['bank_status']?'已绑卡':'未绑卡';
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'count'		=> $count,
				'res'		=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function bankLists(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$user_id = $this->input->post('user_id');
		$bank_status = $this->input->post('bank_status');
		$bank_code = $this->input->post('bank_code');
		$bank_account = $this->input->post('bank_account');
		$username = $this->input->post('username');
		$realname = $this->input->post('realname');

		$where = $like = [];
		$user_id and $where['b.user_id'] = $user_id;
		$bank_status and $where['a.bank_status'] = $bank_status;
		$bank_code and $where['a.bank'] = $bank_code;
		$bank_account and $like['a.account'] = $bank_account;
		$username and $where['b.username'] = $username;
		$realname and $where['b.realname'] = $realname;

		$this->load->model('admin/Account_bank_model');
		$res = $this->Account_bank_model->getList($where,$like,$page,$page_size);
		$count = $this->Account_bank_model->getListCount($where,$like);
		foreach($res as &$v){
			$v['province'] = $v['province_name']?$v['province_name']:$v['province'];
			$v['city'] = $v['city_name']?$v['city_name']:$v['city'];
			unset($v['province_name']);
			unset($v['city_name']);
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'count'		=>	$count,
				'res'		=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function bankEdit(){
		$id = $this->input->post('id');
		$id = 10;
		$this->load->model('admin/Account_bank_model');

		if($res = $this->Account_bank_model->getBankInfo($id)){
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

	public function bankUpdate(){
		$id = $this->input->post('id');
		$data = $this->input->post(array('account'));
		$this->load->model('admin/Account_bank_model');
		if($this->Account_bank_model->getBankInfo($id)){
			if($this->Account_bank_model->bankUpdate($id,$data)){
				$response = [
					'errcode'	=>	0,
					'msg'		=>	'更新成功'
				];
			}else{
				$response = [
					'errcode'	=>	1,
					'msg'		=>	'更新失败'
				];
			}
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'数据不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function bankDel(){
		$id = $this->input->post('id');
		$this->load->model('admin/Account_bank_model');
		if($this->Account_bank_model->getBankInfo($id)){
			if($this->Account_bank_model->bankDel($id)){
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
				'msg'		=>	'数据不存在'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function getRecordCount(){
		$user_id = $this->input->post('id');
		$where['user_id'] = $user_id;
		$this->load->model('admin/Account_log_model');
		$res = $this->Account_log_model->getAllAccountLog($where);
		$maps = [
			'recharge' => '充值',
            'cash_success' => '提现',
            'tender' => '投资',
            'repayment_receive' => '收益',
            'repayment' => '还款',
			'tender_receive' => '借款',
			'other' => '其他'
		];
		$data = [];
		$max = [
			'max'	=>	0,
			'cur'	=>	0
		];
		if($res){
			foreach($res as $v){
				$month = date('Y-m',$v['addtime']);
				if(array_key_exists($v['type'],$maps)){
					$data[$month][$v['type']] = isset($data[$month][$v['type']])?$data[$month][$v['type']] + $v['money']:$v['money'];
				}else{
					$data[$month]['other'] = isset($data[$month]['other'])?$data[$month]['other'] + $v['money']:$v['money'];
				}
				if($v['type'] == 'recharge'){
					$max['cur'] += $v['money'];
				}elseif($v['type'] == 'cash_success'){
					$max['cur'] -= $v['money'];
				}
				if($max['cur'] > $max['max']){
					$max['max'] = $max['cur'];
				}
				foreach($maps as $k=>$v){
					if(!isset($data[$month][$k])) $data[$month][$k] = 0;
				}
			}
		}
		krsort($data);
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'monthData'	=>	$data,
				'max'		=>	$max
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}
}
