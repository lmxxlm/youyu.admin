<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Borrow extends Common {

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
	protected $real_states;

	public function __construct(){
		parent::__construct();
		$this->real_states = [
			0	=>	'等待审核',
			1	=>	'审核失败',
			2	=>	'投资中',
			3	=>	'已满标',
			4	=>	'已流标',
			5	=>	'还款中',
			6	=>	'已还款',
			7	=>	'满标待审',
		];
	}

	public function borrowList(){
		$page = $this->input->post('page')?:1;
		$page_size = $this->input->post('size')?:PAGE_SIZE;
		$username = $this->input->post('username');
		$name = $this->input->post('name');
		$real_state = $this->input->post('real_state');
		$money_begin = $this->input->post('money_begin');
		$money_end = $this->input->post('money_end');
		$time_limit_begin = $this->input->post('time_limit_begin');
		$time_limit_end = $this->input->post('time_limit_end');
		$apr = $this->input->post('apr');

		$where = $like = [];

		$username and $like['b.username'] = $username;
		$name and $like['a.name'] = $name;
		$real_state and $where['a.real_state'] = $real_state;
		$money_begin and $where['a.account >='] = $money_begin;
		$money_end and $where['a.account <='] = $money_end;
		$time_limit_begin and $where['a.time_limit_day >='] = $time_limit_begin;
		$time_limit_end and $where['a.time_limit_day >='] = $time_limit_end;
		$apr and $where['a.apr'] = $apr;

		$this->load->model('admin/Borrow_model');
		$this->load->model('admin/Linkage_model');
		$res1 = $this->Borrow_model->getBorrowList($where,$like,$page,$page_size);
		$res2 = $this->Borrow_model->getBorrowCount($where,$like);
		$res3 = $this->Linkage_model->getConstsByNid('borrow_use');
		$borrow_types = [];
		foreach($res3 as $m){
			$borrow_types[$m['id']] = $m['name'];
		}
		foreach($res1 as &$v){
			$v['start_time'] = $v['start_time']?date('Y-m-d',$v['start_time']):'';
			$v['end_time'] = $v['end_time']?date('Y-m-d',$v['end_time']):'';
			$v['success_time'] = $v['success_time']?date('Y-m-d H:i:s',$v['success_time']):'';
			$v['borrow_type'] = isset($borrow_types[$v['borrow_type']])?$borrow_types[$v['borrow_type']]:'';
			$v['real_state_name'] = isset($this->real_states[$v['real_state']])?$this->real_states[$v['real_state']]:'';
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$res1,
				'count'		=>	$res2['count'],
				'sum'		=>	[
					'account'	=>	$res2['account']
				]
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function borrowListExport(){
		$username = $this->input->post('username');
		$name = $this->input->post('name');
		$real_state = $this->input->post('real_state');
		$money_begin = $this->input->post('money_begin');
		$money_end = $this->input->post('money_end');
		$time_limit_begin = $this->input->post('time_limit_begin');
		$time_limit_end = $this->input->post('time_limit_end');
		$apr = $this->input->post('apr');

		$where = $like = [];

		$username and $like['b.username'] = $username;
		$name and $like['a.name'] = $name;
		$real_state and $where['a.real_state'] = $real_state;
		$money_begin and $where['a.account >='] = $money_begin;
		$money_end and $where['a.account <='] = $money_end;
		$time_limit_begin and $where['a.time_limit_day >='] = $time_limit_begin;
		$time_limit_end and $where['a.time_limit_day >='] = $time_limit_end;
		$apr and $where['a.apr'] = $apr;

		$this->load->model('admin/Borrow_model');
		$this->load->model('admin/Linkage_model');
		$res1 = $this->Borrow_model->getBorrowList($where,$like);
		$res2 = $this->Linkage_model->getConstsByNid('borrow_use');
		$borrow_types = [];
		foreach($res2 as $m){
			$borrow_types[$m['id']] = $m['name'];
		}

		foreach($res1 as &$v){
			$v['start_time'] = $v['start_time']?date('Y-m-d',$v['start_time']):'';
			$v['end_time'] = $v['end_time']?date('Y-m-d',$v['end_time']):'';
			$v['success_time'] = $v['success_time']?date('Y-m-d H:i:s',$v['success_time']):'';
			$v['contract_time'] = $v['start_time'].'-'.$v['end_time'];
			$v['borrow_type'] = isset($borrow_types[$v['borrow_type']])?$borrow_types[$v['borrow_type']]:'';
			$v['real_state_name'] = isset($this->real_states[$v['real_state']])?$this->real_states[$v['real_state']]:'';
		}
		$response = [
			'errcode'		=>	0,
			'data'			=>	[
				'headKey'	=>	['id','username','realname','name','account','account_yes','apr','contract_time','success_time','time_limit_day','borrow_type','real_status_name'],
				'headVal'	=>	['ID','用户名','真实姓名','标题','借款金额','已投金额','利率','合同时间','开标时间','借款期限','标类型','状态'],
				'data'	=>	$res1
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function borrowAdd(){
		$data = $this->input->post(array('username','borrow_type','new_hand','style','account','use','original_rate','discount_rate','lowest_account','most_account','tender_user','invite_user','contract_no','pawn','start_time','time_limit_day','name','sign','content'));
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('username','用户名','required');
		// $this->form_validation->set_rules('realname','真实姓名','required');
		// $this->form_validation->set_rules('user_id','用户id','required');
		$this->form_validation->set_rules('borrow_type','借款类型','required|callback_check_borrow_type');
		$this->form_validation->set_rules('new_hand','标类型','required|in_list[0,1,2]');
		$this->form_validation->set_rules('style','还款方式','required|in_list[1,2]');
		$this->form_validation->set_rules('account','借款总额','required|numeric');
		// $this->form_validation->set_rules('use','借款用途','required');
		$this->form_validation->set_rules('original_rate','原始利率','required|numeric');
		$this->form_validation->set_rules('discount_rate','贴息利率','numeric');
		$this->form_validation->set_rules('lowest_account','最低投标金额','required|numeric');
		$this->form_validation->set_rules('most_account','最多投标总额','numeric');
		// $this->form_validation->set_rules('tender_user','专投手机号','required');
		// $this->form_validation->set_rules('invite_user','专投渠道号','required');
		// $this->form_validation->set_rules('contract_no','原合同编号','required');
		// $this->form_validation->set_rules('pawn','债劵抵(质)押信息','required');
		// $this->form_validation->set_rules('litpic','缩略图','required');
		$this->form_validation->set_rules('start_time','标投放开始时间','callback_check_time[Y-m-d]');
		$this->form_validation->set_rules('time_limit_day','标期','required|integer|greater_than[0]');
		$this->form_validation->set_rules('name','借款标题','required');
		// $this->form_validation->set_rules('sign','标记','required');
		// $this->form_validation->set_rules('content','内容描述','required');

		$this->form_validation->set_message('required','{field}不能为空');
		$this->form_validation->set_message('in_list','{field}不在指定范围{param}内');
		$this->form_validation->set_message('numeric','{field}不为数字');
		$this->form_validation->set_message('integer','{field}不为整数');
		$this->form_validation->set_message('greater_than','{field}不能小于{param}');

		$response = [];
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$litpics = [];
		if(is_array($_FILES['files']['error'])){
			$relative_path = $this->config->item('system_path').$this->config->item('system_img_path').'/';
			$path = 'upload/image/'.date('Y/m/d').'/';
			if(!is_dir($relative_path.$path)){
				mkdir($relative_path.$path,0777,true);
			}
			foreach($_FILES['files']['name'] as $k => $name){
				$fileInfo = pathinfo($name);
				$basename = $fileInfo['basename'];
				$extension = $fileInfo['extension'];
				if(!in_array($extension,['jpg','jpeg','png','bmp'])){
					$response = [
						'errcode'	=>	1,
						'msg'		=>	'图片格式不正确'
					];
					die(json_encode($response,JSON_UNESCAPED_UNICODE));
				}
				$filename = 'br'.date("His").get_rand().'.'.$extension;
				move_uploaded_file($_FILES['files']['tmp_name'][$k],$relative_path.$path.$filename);
				$litpics[] = $path.$filename;
			}
		}
		$data['litpic'] = implode(',',$litpics).',';
		$data['addtime'] = time();
		$data['addip'] = $this->input->ip_address();
		$data['original_rate'] = $data['original_rate']?:0.0;
		$data['discount_rate'] = $data['discount_rate']?:0.0;
		$data['lowest_account'] = $data['lowest_account']?:0.0;
		$data['most_account'] = $data['most_account']?:0.0;
		$data['apr'] = $data['original_rate'] + $data['discount_rate'];
		$data['status'] = 0;
		$data['real_state'] = 0;
		$data['order'] = 0;
		$data['start_time'] = strtotime($data['start_time']);
		$data['end_time'] = $data['start_time'] + ($data['time_limit_day'] * 86400) - 1;
		$data['isday'] = 1;
		$data['vouch_id'] = 0;
		$data['tender_user'] = str_replace('；',';',$data['tender_user']);
		$data['invite_user'] = str_replace('；',';',$data['invite_user']);
		$this->load->model('admin/User_model');
		$where['username'] = $data['username'];
		if($res = $this->User_model->getUserInfoByUserName($where)){
			unset($data['username']);
			$data['user_id'] = $data['repayment_user'] = $res['user_id'];
			$this->load->model('admin/Borrow_model');
			if($res = $this->Borrow_model->add($data)){
				$response = [
					'errcode'	=>	0,
					'msg'		=>	'发标成功'
				];
			}else{
				$response = [
					'errcode'	=>	1,
					'msg'		=>	'发标失败'
				];
			}
		}else{
			$response = [
				'errcode'	=>	1,
				'error'		=>	[
					'username'	=>	'用户不存在'
				]
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function borrowEdit(){
		$id = $this->input->post('id');
		$this->load->model('admin/Borrow_model');
		if($res1 = $this->Borrow_model->getBorrowInfo($id)){
			$this->load->helper('borrow');
			$this->load->model('admin/Linkage_model');
			$res2 = $this->Linkage_model->getConstsByNid('borrow_use');
			$borrow_types = [];
			foreach($res2 as $v){
				$borrow_types[$v['id']] = $v['name'];
			}
			$res1['borrow_type_name'] = isset($borrow_types[$res1['borrow_type']])?$borrow_types[$res1['borrow_type']]:'';
			$res3 = $this->Linkage_model->getConstsByNid('borrow_style');
			$borrow_styles = [];
			foreach($res3 as $v){
				$borrow_styles[$v['value']] = $v['name'];
			}
			$res1['style_name'] = isset($borrow_styles[$res1['style']])?$borrow_styles[$res1['style']]:'';
			$res1['real_state_name'] = isset($this->real_states[$res1['real_state']])?$this->real_states[$res1['real_state']]:'';
			$res1['contract_time'] = date('Y-m-d',$res1['start_time']).'-'.date('Y-m-d',$res1['end_time']);
			$res1['days'] = time_limit_day(time(),$res1['end_time']);
			$res1['addtime'] = date('Y-m-d H:i:s',$res1['addtime']);
			$res1['start_time'] = date('Y-m-d',$res1['start_time']);
			$res1['end_time'] = date('Y-m-d',$res1['end_time']);
			$res1['litpics'] = [];
			$litpics = explode(',',substr($res1['litpic'],0,strlen($res1['litpic'])-1));
			if(!empty($res1['litpic'])){
				foreach($litpics as $v){
					$res1['litpics'][] = $this->config->item('system_http').'://'.$this->config->item('system_domain').'/'.$v;
				}
			}
			$response = [
				'errcode'	=>	0,
				'data'		=>	$res1
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'未找到'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function borrowUpdate(){
		$data = $this->input->post(array('username','borrow_type','new_hand','style','account','use','original_rate','discount_rate','lowest_account','most_account','tender_user','invite_user','contract_no','pawn','start_time','time_limit_day','name','sign','content'));
		$id = $this->input->post('id');
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('username','用户名','required');
		// $this->form_validation->set_rules('realname','真实姓名','required');
		// $this->form_validation->set_rules('user_id','用户id','required');
		$this->form_validation->set_rules('borrow_type','借款类型','required|callback_check_borrow_type');
		$this->form_validation->set_rules('new_hand','标类型','required|in_list[0,1,2]');
		$this->form_validation->set_rules('style','还款方式','required|in_list[1,2]');
		$this->form_validation->set_rules('account','借款总额','required|numeric');
		// $this->form_validation->set_rules('use','借款用途','required');
		$this->form_validation->set_rules('original_rate','原始利率','required|numeric');
		$this->form_validation->set_rules('discount_rate','贴息利率','numeric');
		$this->form_validation->set_rules('lowest_account','最低投标金额','required|numeric');
		$this->form_validation->set_rules('most_account','最多投标总额','numeric');
		// $this->form_validation->set_rules('tender_user','专投手机号','required');
		// $this->form_validation->set_rules('invite_user','专投渠道号','required');
		// $this->form_validation->set_rules('contract_no','原合同编号','required');
		// $this->form_validation->set_rules('pawn','债劵抵(质)押信息','required');
		// $this->form_validation->set_rules('litpic','缩略图','required');
		$this->form_validation->set_rules('start_time','标投放开始时间','callback_check_time[Y-m-d]');
		$this->form_validation->set_rules('time_limit_day','标期','required|integer|greater_than[0]');
		$this->form_validation->set_rules('name','借款标题','required');
		// $this->form_validation->set_rules('sign','标记','required');
		// $this->form_validation->set_rules('content','内容描述','required');

		$this->form_validation->set_message('required','{field}不能为空');
		$this->form_validation->set_message('in_list','{field}不在指定范围{param}内');
		$this->form_validation->set_message('numeric','{field}不为数字');
		$this->form_validation->set_message('integer','{field}不为整数');
		$this->form_validation->set_message('greater_than','{field}不能小于{param}');
		$response = [];
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$this->load->model('admin/Borrow_model');
		if(!$res = $this->Borrow_model->getBorrowInfo($id)){
			$response['errcode'] = 1;
			$response['msg'] = '此记录不存在';
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$litpics = [];
		if(!empty($_FILES) && is_array($_FILES['files']['error'])){
			$num = count($_FILES['files']['error']);
			$relative_path = $this->config->item('system_path').$this->config->item('system_img_path').'/';
			$path = 'upload/image/'.date('Y/m/d').'/';
			if(!is_dir($relative_path.$path)){
				mkdir($relative_path.$path,0777,true);
			}
			foreach($_FILES['files']['name'] as $k => $name){
				$fileInfo = pathinfo($name);
				$basename = $fileInfo['basename'];
				$extension = $fileInfo['extension'];
				if(!in_array($extension,['jpg','jpeg','png','bmp'])){
					$response = [
						'errcode'	=>	1,
						'msg'		=>	'图片格式不正确'
					];
					die(json_encode($response,JSON_UNESCAPED_UNICODE));
				}
				$filename = 'br'.date("His").get_rand().'.'.$extension;
				move_uploaded_file($_FILES['files']['tmp_name'][$k],$relative_path.$path.$filename);
				$litpics[] = $path.$filename;
			}
		}
		$data['litpic'] = $res['litpic'].implode(',',$litpics).',';
		$data['addtime'] = time();
		$data['addip'] = $this->input->ip_address();
		$data['original_rate'] = $data['original_rate']?:0.0;
		$data['discount_rate'] = $data['discount_rate']?:0.0;
		$data['lowest_account'] = $data['lowest_account']?:0.0;
		$data['most_account'] = $data['most_account']?:0.0;
		$data['apr'] = $data['original_rate'] + $data['discount_rate'];
		$data['status'] = 0;
		$data['real_state'] = 0;
		$data['order'] = 0;
		$data['start_time'] = strtotime($data['start_time']);
		$data['end_time'] = $data['start_time'] + ($data['time_limit_day'] * 86400) - 1;
		$data['isday'] = 1;
		$data['vouch_id'] = 0;
		$data['tender_user'] = str_replace('；',';',$data['tender_user']);
		$data['invite_user'] = str_replace('；',';',$data['invite_user']);
		$this->load->model('admin/User_model');
		$where['username'] = $data['username'];
		if($res = $this->User_model->getUserInfoByUserName($where)){
			unset($data['username']);
			$data['user_id'] = $data['repayment_user'] = $res['user_id'];
			if($res = $this->Borrow_model->update($id,$data)){
				$response = [
					'errcode'	=>	0,
					'msg'		=>	'修改成功'
				];
			}else{
				$response = [
					'errcode'	=>	1,
					'msg'		=>	'修改失败'
				];
			}
		}else{
			$response = [
				'errcode'	=>	1,
				'error'		=>	[
					'username'	=>	'用户不存在'
				]
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function borrowPicDel(){
		$id = $this->input->post('id');
		$index = $this->input->post('index');
		$this->load->model('admin/Borrow_model');
		if(!$res = $this->Borrow_model->getBorrowInfo($id)){
			$response['errcode'] = 1;
			$response['msg'] = '此记录不存在';
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$litpics = explode(',',substr($res['litpic'],0,strlen($res['litpic'])-1));
		unset($litpics[$index]);
		$data['litpic'] = implode(',',$litpics).(empty($litpics)?'':',');
		if($res = $this->Borrow_model->update($id,$data)){
			$response = [
				'errcode'	=>	0,
				'msg'		=>	'删除图片成功'
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'删除图片失败'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function borrowCheck(){
		$data = $this->input->post(array('real_state','recomend_flg','success_time','verify_remark'));
		$id = $this->input->post('id');
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('real_state','审核结果','required|in_list[1,2]');
		$this->form_validation->set_rules('recomend_flg','推荐标','required|in_list[0,1]');
		$this->form_validation->set_rules('success_time','发布时间','required|callback_check_time[Y-m-d H:i]');
		$this->form_validation->set_message('required','{field}不能为空');
		$this->form_validation->set_message('in_list','{field}不在指定范围{param}内');
		$response = [];
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$this->load->model('admin/Borrow_model');
		if($res = $this->Borrow_model->getBorrowInfo($id)){
			if($res['real_state'] != BORROW_STATE_CHECK_WAIT){
				$response = [
					'errcode'	=>	1,
					'msg'		=>	'该标已审核'
				];
				die(json_encode($response,JSON_UNESCAPED_UNICODE));
			}
			$data['verify_time'] = '`start_time`';
			$data['verify_user'] = 1;
			// $data['verify_user'] = $_SESSION['user_info']['user_id'];
			if($data['real_state'] == BORROW_STATE_BIDDING){
				$msg = '审核通过';
				$data['success_time'] = strtotime($data['success_time']);
			}elseif($data['real_state'] == BORROW_STATE_CHECK_FAILURE){
				unset($data['success_time']);
				$msg = '审核未通过';
			}
			if($this->Borrow_model->borrowCheck($id,$data)){
				$response = [
					'errcode'	=>	0,
					'msg'		=>	$msg
				];
			}
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);

	}

	public function borrowDel(){
		$id = $this->input->post('id');
		$this->load->model('admin/Borrow_model');
		$this->load->model('admin/Borrow_tender_model');
		if($res = $this->Borrow_tender_model->getBorrowTender($id)){
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'此标已有用户投资，不能删除'
			];
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$this->Borrow_model->borrowDel($id);
		$response = [
			'errcode'	=>	0,
			'msg'		=>	'删除成功'
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function check_borrow_type($borrow_type){
		$this->load->model('admin/Linkage_model');
		$this->form_validation->set_message('check_borrow_type', '{field}不正确');
		if($res = $this->Linkage_model->getConstsByNid('borrow_use')){
			foreach($res as $v){
				if($borrow_type == $v['id']){
					return true;
				}
			}
		}
		return false;
	}

	public function check_time($start_time,$str){
		$this->form_validation->set_message('check_time', '{field}格式不正确');
		if($start_time == date($str,strtotime($start_time))){
			return true;
		}
		return false;
	}

	public function repayList(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$username = $this->input->post('username');
		$name = $this->input->post('name');
		$status = $this->input->post('status');

		$where = $like = [];
		$username and $like['b.username'] = $username;
		$name and $like['a.name'] = $name;
		is_numeric($status) and $where['c.status'] = $status;
		$this->load->model('admin/Borrow_model');
		$res = $this->Borrow_model->getRepayList($where,$like,$page,$page_size);
		$this->load->helper('borrow');
		foreach($res as &$v){
			$v['repayment_time'] = $v['repayment_time']?date('Y-m-d',$v['repayment_time']):'';
			$v['start_time'] = $v['start_time']?date('Y-m-d',$v['start_time']):'';
			$v['end_time'] = $v['end_time']?date('Y-m-d',$v['end_time']):'';
			$v['success_time'] = $v['success_time']?date('Y-m-d',$v['success_time']):'';
			$v['style_name'] = repayment_style($v['style']);
			$v['repay_status'] = $v['st2']?'已还款':'未还款';
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	$res
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function repay(){
		$borrow_id = $this->input->post('id');
		$data = array("borrow_id" => $borrow_id);

		$this->load->library("api/borrow_api");
        $data = array("borrow_id" => $borrow_id);
        $result = $this->borrow_api->borrow_repay_single($data);
		$code=$this->borrow_api->getcode();
        $msg=$this->borrow_api->getmsg();
        if($code==310054){
            $tips['code']=$code;
            $tips['msg']=$msg;
        }elseif($code==310055){
            $tips['code']=$code;
            $tips['msg']=$msg;
        }elseif($code==310056){
            $tips['code']=$code;
            $tips['msg']=$msg;
        }elseif($code==310017){
            $tips['code']=$code;
            $tips['msg']=$msg;
        }else{
            $tips['code']=$code;
            $tips['msg']=$msg;
		}
		die(json_encode($tips,JSON_UNESCAPED_UNICODE));
	}

	public function updateContent(){
		$data = $this->input->post(array('name','borrow_type','success_time','status','content'));
		$id = $this->input->post('id');

		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('name','借款标题','required');
		$this->form_validation->set_rules('borrow_type','借款类型','required|callback_check_borrow_type');
		$this->form_validation->set_rules('status','标类型','required|in_list[0,1]');
		$this->form_validation->set_rules('success_time','标投放开始时间','callback_check_time[Y-m-d H:i]');
		$this->form_validation->set_message('required','{field}不能为空');
		$this->form_validation->set_message('in_list','{field}不在指定范围{param}内');
		$this->form_validation->set_message('re');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$this->load->model('admin/Borrow_model');
		if($res = $this->Borrow_model->update($id,$data)){
			$response = [
				'errcode'	=>	0,
				'msg'		=>	'修改成功'
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'修改失败'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function tenderList(){
		$page = $this->input->post('page')?:1;
		$page_size = $this->input->post('size')?:PAGE_SIZE;
		$tender_id = $this->input->post('tender_id');
		$borrow_id = $this->input->post('borrow_id');
		$name = $this->input->post('name');
		$username = $this->input->post('username');
		$realname = $this->input->post('realname');
		$time_start = $this->input->post('time_start');
		$time_end = $this->input->post('time_end');
		$status = $this->input->post('status');
		$type = $this->input->post('type');
		$money = $this->input->post('money')?:0;

		$where = $like = [];
		$tender_id and $where['a.id'] = $tender_id;
		$borrow_id and $where['c.id'] = $borrow_id;
		$name and $like['c.name'] = $name;
		$username and $like['b.username'] = $username;
		$realname and $like['b.realname'] = $realname;
		// if(!$time_start && !$time_end){
		// 	$time_start = date('Y-m-01');
		// 	$time_end = date('Y-m-t');
		// }
		$time_start and $where['a.addtime >='] = strtotime($time_start);
		$time_end and $where['a.addtime <='] = strtotime($time_end.' 23:59:59');
		if($type && in_array($type,['>=','=','<='])){
			switch($status){
				case 1:
					$where['a.first_reward '.$type] = $money;
					break;
				case 2:
					$where['a.max_reward '.$type] = $money;
					break;
				case 3:
					$where['a.first_reward '.$type] = $money;
					break;
				case 4:
					$where['a.coupon '.$type] = $money;
					break;
				case 5:
					$where['a.money  '.$type] = $money;
					break;
			}
		}
		$this->load->model('admin/Borrow_tender_model');
		$res1 = $this->Borrow_tender_model->getInvestList($where,$like,$page,$page_size);
		$res2 = $this->Borrow_tender_model->getInvestCount($where,$like);
		foreach($res1 as &$v){
			$v['addtime'] = $v['addtime']?date('Y-m-d',$v['addtime']):'';
			$v['end_time'] = $v['end_time']?date('Y-m-d',$v['end_time']):'';
			$v['tender_resource'] = $v['tender_resource']?'手机':'PC';
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$res1,
				'count'		=>	$res2['count'],
				'sum'		=>	[
					'moneys'	=>	$res2['moneys'],
					'interests'	=>	$res2['interests'],
					'rewards'	=>	$res2['rewards']
				]
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}
}
