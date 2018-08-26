<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Capital extends Common {

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
		$this->load->model('admin/User_reward_model');
	}

	public function getAllReward(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$user_id = $this->input->post('user_id');
		$username = $this->input->post('username');
		$money = $this->input->post('money');
		$get_time_begin = $this->input->post('get_time_begin')?$this->input->post('get_time_begin'):date('Y-m-01');
		$get_time_end = $this->input->post('get_time_end')?$this->input->post('get_time_end'):date('Y-m-t');
		$use_time_begin = $this->input->post('use_time_begin');
		$use_time_end = $this->input->post('use_time_end');
		$use_together = $this->input->post('use_together');
		$reward_name = $this->input->post('reward_name');
		$use_type = $this->input->post('use_type');

		$where = $like = [];

		$user_id and $where['a.user_id'] = $user_id;
		$username and $like['a.username'] = $username;
		$money and $where['a.money'] = $money;
		$get_time_begin and $where['a.addtime >='] = strtotime($get_time_begin);
		$get_time_end and $where['a.addtime <='] = strtotime($get_time_end.' 23:59:59');
		$use_time_begin and $where['a.usetime >='] = strtotime($use_time_begin);
		$use_time_end and $where['a.usetime <='] = strtotime($use_time_end.' 23:59:59');
		$use_together and $where['a.use_together'] = $use_together;
		$reward_name and $where['a.reward_name'] = $reward_name;

		$time = time();
		switch($use_type){
			case '1':
				$where['a.is_use'] = 0;
				break;
			case '2':
				$where['a.is_use'] = 1;
				break;
			case '3':
				$where['a.is_use'] = 0;
				$where['a.timeout <'] = $time;
				break;
			case '4':
				$where['a.is_use'] = 0;
				$where['a.timeout >='] = $time;
				break;
			case '5':
				$where['a.is_use'] = 2;
				break;
		}
		$res1 = $this->User_reward_model->rewardList($where,$like,$page,$page_size);
		$res2 = $this->User_reward_model->rewardListSumCount($where,$like);
		$count = $sum = 0;
		// foreach($res2 as $n){
		// 	$count ++;
		// 	$sum += $n['sum'];
		// }
		$use_status = [
			'未使用',
			'已使用',
			'冻结'
		];
		foreach($res1 as &$v){
			$v['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
			$v['timeout'] = $v['timeout']?date('Y-m-d',$v['timeout']):'长期有效';
			$v['usetime'] = $v['usetime']?date('Y-m-d',$v['usetime']):'';
			$v['use_together'] = $v['use_together'] == 1?'现金券':'红包券';
			if($v['is_use'] == 0 && $v['timeout'] <= $time){
				$v['is_use'] = '已过期';
			}else{
				$v['is_use'] = $use_status[$v['is_use']];
			}
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'	=>	$res1,
				'count'	=>	$res2['count'],
				'sum'	=>	[
					'countUsers'	=>	$res2['countUsers'],
					'countMoney'	=>	$res2['countMoney'],
				]
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function export(){
		$user_id = $this->input->post('user_id');
		$username = $this->input->post('username');
		$money = $this->input->post('money');
		$get_time_begin = $this->input->post('get_time_begin')?$this->input->post('get_time_begin'):date('Y-m-01');
		$get_time_end = $this->input->post('get_time_end')?$this->input->post('get_time_end'):date('Y-m-t');
		$use_time_begin = $this->input->post('use_time_begin');
		$use_time_end = $this->input->post('use_time_end');
		$use_together = $this->input->post('use_together');
		$reward_name = $this->input->post('reward_name');
		$use_type = $this->input->post('use_type');

		$where = $like = [];

		$user_id and $where['a.user_id'] = $user_id;
		$username and $like['a.username'] = $username;
		$money and $where['a.money'] = $money;
		$get_time_begin and $where['a.addtime >='] = strtotime($get_time_begin);
		$get_time_end and $where['a.addtime <='] = strtotime($get_time_end.' 23:59:59');
		$use_time_begin and $where['a.usetime >='] = strtotime($use_time_begin);
		$use_time_end and $where['a.usetime <='] = strtotime($use_time_end.' 23:59:59');
		$use_together and $where['a.use_together'] = $use_together;
		$reward_name and $where['a.reward_name'] = $reward_name;

		$time = time();
		switch($use_type){
			case '1':
				$where['a.is_use'] = 0;
				break;
			case '2':
				$where['a.is_use'] = 1;
				break;
			case '3':
				$where['a.is_use'] = 0;
				$where['a.timeout <'] = $time;
				break;
			case '4':
				$where['a.is_use'] = 0;
				$where['a.timeout >='] = $time;
				break;
			case '5':
				$where['a.is_use'] = 2;
				break;
		}
		$res = $this->User_reward_model->rewardListExport($where,$like);
		$use_status = [
			'未使用',
			'已使用',
			'冻结'
		];

		// $data = "用户ID,用户名,编号,上线ID,金额(元),来源活动,红包限额,天数限额,赠送的微信号,类型,获取时间,失效时间,使用时间,状态\n";
		// if($res){
		// 	foreach($res as &$v){
		// 		$v['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
		// 		$v['timeout'] = $v['timeout']?date('Y-m-d',$v['timeout']):'长期有效';
		// 		$v['usetime'] = $v['usetime']?date('Y-m-d',$v['usetime']):'';
		// 		$v['use_together'] = $v['use_together'] == 1?'现金券':'红包券';
		// 		if($v['is_use'] == 0 && $v['timeout'] <= $time){
		// 			$v['is_use'] = '已过期';
		// 		}else{
		// 			$v['is_use'] = $use_status[$v['is_use']];
		// 		}
		// 	}
		// 	$newContent = [];
		// 	foreach($res as $n){
		// 		$content = array_values($n);
		// 		$newContent[] = implode(',',$content);
		// 	}
		// 	$data .= implode("\n",$newContent);
		// }
		// $this->load->helper('download');
		// force_download('customer_lists.csv',$data);
		if($res){
			foreach($res as &$v){
				$v['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
				$v['timeout'] = $v['timeout']?date('Y-m-d',$v['timeout']):'长期有效';
				$v['usetime'] = $v['usetime']?date('Y-m-d',$v['usetime']):'';
				$v['use_together'] = $v['use_together'] == 1?'现金券':'红包券';
				if($v['is_use'] == 0 && $v['timeout'] <= $time){
					$v['is_use'] = '已过期';
				}else{
					$v['is_use'] = $use_status[$v['is_use']];
				}
			}
		}
		$response = [
			'errcode'		=>	0,
			'data'			=>	[
				'headKey'	=>	['user_id','username','reward_no','invite_userid','money','reward_name','reward_limit','borrow_days','weixin_id','use_together','addtime','timeout','usetime','is_use'],
				'headVal'	=>	['用户ID','用户名','编号','上线ID','金额(元)','来源活动','红包限额','天数限额','赠送的微信号','类型','获取时间','失效时间','使用时间','状态'],
				'data'	=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function count(){
		$res = $this->User_reward_model->rewardAllForCount();
		$all = $active = $invite = [
			'all'	=>	0,
			'used'	=>	0,
			'unused'=>	0,
			'overdue'=>	0
		];
		if($res){
			$time = time();
			foreach($res as $v){
				$all['all'] += $v['money'];
				if($v['reward_style'] == 1){
					$active['all'] += $v['money'];
				}elseif($v['invite'] == 2){
					$invite['all'] += $v['money'];
				}
				if($v['is_use']){
					$all['used'] += $v['money'];
					if($v['reward_style'] == 1){
						$active['used'] += $v['money'];
					}elseif($v['invite'] == 2){
						$invite['used'] += $v['money'];
					}
				}else{
					if($v['timeout'] >= $time){
						$all['unused'] += $v['money'];
						if($v['reward_style'] == 1){
							$active['unused'] += $v['money'];
						}elseif($v['invite'] == 2){
							$invite['unused'] += $v['money'];
						}
					}else{
						$all['overdue'] += $v['money'];
						if($v['reward_style'] == 1){
							$active['overdue'] += $v['money'];
						}elseif($v['invite'] == 2){
							$invite['overdue'] += $v['money'];
						}
					}
				}
			}
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'all'		=>	$all,
				'active'	=>	$active,
				'invite'	=>	$invite
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function getRewardType(){
		$res = $this->User_reward_model->getRewardType();
		$response = [
			'errcode'	=>	0,
			'data'		=>	$res
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function rewardUseList(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$user_id = $this->input->post('user_id');
		$username = $this->input->post('username');
		$money = $this->input->post('money');
		$borrow_type = $this->input->post('borrow_type');
		$borrow_id = $this->input->post('borrow_id');
		$tender_id = $this->input->post('tender_id');
		$get_time_begin = $this->input->post('get_time_begin');
		$get_time_end = $this->input->post('get_time_end');
		$use_time_begin = $this->input->post('use_time_begin')?$this->input->post('use_time_begin'):date('Y-m-01');
		$use_time_end = $this->input->post('use_time_end')?$this->input->post('use_time_end'):date('Y-m-t');
		$use_together = $this->input->post('use_together');
		$reward_name = $this->input->post('reward_name');

		$where = $like = [];

		$user_id and $where['a.user_id'] = $user_id;
		$username and $like['a.username'] = $username;
		$money and $where['a.money'] = $money;
		$borrow_type and $where['a.borrow_type'] = $borrow_type;
		$borrow_id and $where['a.borrow_id'] = $borrow_id;
		$tender_id and $where['a.tender_id'] = $tender_id;
		$get_time_begin and $where['a.addtime >='] = strtotime($get_time_begin);
		$get_time_end and $where['a.addtime <='] = strtotime($get_time_end.' 23:59:59');
		$use_time_begin and $where['a.usetime >='] = strtotime($use_time_begin);
		$use_time_end and $where['a.usetime <='] = strtotime($use_time_end.' 23:59:59');
		$use_together and $where['a.use_together'] = $use_together;
		$reward_name and $where['a.reward_name'] = $reward_name;

		$where['a.is_use'] = 1;
		$where['b.type_id'] = 2;
		$where['b.is_borrow_tender'] = 1;

		$res1 = $this->User_reward_model->rewardUseList($where,$like,$page,$page_size);
		$res2 = $this->User_reward_model->rewardUseListCountSum($where,$like);

		foreach($res1 as &$v){
			$v['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
			$v['regtime'] = date('Y-m-d H:i:s',$v['regtime']);
			$v['usetime'] = $v['usetime']?date('Y-m-d H:i:s',$v['usetime']):'';
			$v['use_together'] = $v['use_together'] == 1?'现金券':'红包券';
		}

		// print_r($res1);
		// print_r($res2);
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'	=>	$res1,
				'count'	=>	$res2['count'],
				'sum'	=>	[
					'countUsers'	=>	$res2['countUsers'],
					'countMoney'	=>	$res2['countMoney'],
					'countTmoney'	=>	$res2['countTmoney'],
					'CountPerformance'	=>	$res2['CountPerformance'],
				]
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function rewardUseExport(){
		$user_id = $this->input->post('user_id');
		$username = $this->input->post('username');
		$money = $this->input->post('money');
		$borrow_type = $this->input->post('borrow_type');
		$borrow_id = $this->input->post('borrow_id');
		$tender_id = $this->input->post('tender_id');
		$get_time_begin = $this->input->post('get_time_begin');
		$get_time_end = $this->input->post('get_time_end');
		$use_time_begin = $this->input->post('use_time_begin')?$this->input->post('use_time_begin'):date('Y-m-01');
		$use_time_end = $this->input->post('use_time_end')?$this->input->post('use_time_end'):date('Y-m-t');
		$use_together = $this->input->post('use_together');
		$reward_name = $this->input->post('reward_name');

		$where = $like = [];

		$user_id and $where['a.user_id'] = $user_id;
		$username and $like['a.username'] = $username;
		$money and $where['a.money'] = $money;
		$borrow_type and $where['a.borrow_type'] = $borrow_type;
		$borrow_id and $where['a.borrow_id'] = $borrow_id;
		$tender_id and $where['a.tender_id'] = $tender_id;
		$get_time_begin and $where['a.addtime >='] = strtotime($get_time_begin);
		$get_time_end and $where['a.addtime <='] = strtotime($get_time_end.' 23:59:59');
		$use_time_begin and $where['a.usetime >='] = strtotime($use_time_begin);
		$use_time_end and $where['a.usetime <='] = strtotime($use_time_end.' 23:59:59');
		$use_together and $where['a.use_together'] = $use_together;
		$reward_name and $where['a.reward_name'] = $reward_name;

		$where['a.is_use'] = 1;
		$where['b.type_id'] = 2;
		$where['b.is_borrow_tender'] = 1;
		$res = $this->User_reward_model->rewardUseListExport($where,$like);
		// $data = "用户ID,用户名,注册时间,编号,金额(元),来源活动,赠送的微信号,类型,获取时间,使用时间,标ID,投资ID,投资金额,业绩\n";
		if($res){
			foreach($res as &$v){
				$v['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
				$v['regtime'] = date('Y-m-d H:i:s',$v['regtime']);
				$v['usetime'] = $v['usetime']?date('Y-m-d',$v['usetime']):'';
				$v['use_together'] = $v['use_together'] == 1?'现金券':'红包券';
			}
			// $newContent = [];
			// foreach($res as $n){
			// 	$content = array_values($n);
			// 	$newContent[] = implode(',',$content);
			// }
			// $data .= implode("\n",$newContent);
		}
		// $this->load->helper('download');
		// force_download('customer_lists.csv',$data);
		$response = [
			'errcode'		=>	0,
			'data'			=>	[
				'headKey'	=>	['user_id','username','regtime','reward_no','money','reward_name','weixin_id','use_together','addtime','usetime','uborrow_id','utender_id','tmoney','performance'],
				'headVal'	=>	['用户ID','用户名','注册时间','编号','金额(元)','来源活动','赠送的微信号','类型','获取时间','使用时间','标ID','投资ID','投资金额','业绩'],
				'data'	=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function sendReward(){
		$data = $this->input->post(array('use_together','username','money','timeout','reward_name','money_limit','borrow_days'));
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('use_together','红包类型','required|in_list[1,2]');
		$this->form_validation->set_rules('username','用户名(手机号)','required|callback_check_username');
		$this->form_validation->set_rules('money','金额','required|greater_than[0]');
		$this->form_validation->set_rules('timeout','过期时间','required');
		$this->form_validation->set_rules('reward_name','红包说明','required');
		$this->form_validation->set_rules('money_limit','红包限额','numeric');
		$this->form_validation->set_rules('borrow_days','投资期限','numeric');
		$this->form_validation->set_message('required','{field}不能为空!');
		$this->form_validation->set_message('in_list','{field}不在{param}内!');
		$this->form_validation->set_message('is_unique','{field}不在用户表内!');
		$this->form_validation->set_message('greater_than','{field}不能小于{param}!');
		$this->form_validation->set_message('numeric','{field}必须为数字!');
		$response = [];
		if($this->form_validation->run() == false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		!$data['money_limit'] and $data['money_limit'] = 1000.00;
		!$data['borrow_days'] and $data['borrow_days'] = 0;
		$data['recommended_id'] = 0;
		$data['addtime'] = time();
		$data['tender_id'] = 0;
		$data['borrow_id'] = 0;
		$data['is_use'] = 0;
		$data['timeout'] = $data['timeout']?strtotime($data['timeout'].' 23:59:59'):0;
		$data['reward_no'] = $this->getRewardNo();
		if($res = $this->User_model->checkInUsername(['username'=>$data['username'],'type_id'=>2])){
			$data['user_id'] = $res['user_id'];
			if($this->User_reward_model->add($data)){
				$response['errcode'] = 0;
				$response['msg'] = '发送成功';
			}else{
				$response['errcode'] = 1;
				$response['msg'] = '发送失败';
			}
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '用户名不存在';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function check_username($username){
		$this->load->model('admin/User_model');
		$this->form_validation->set_message('check_username', '{field}不在用户表中');
		return $this->User_model->checkInUsername(['username'=>$username,'type_id'=>2])?true:false;
	}

	public function getRewardNo(){
		$reward_no = reward_no();
		if($this->User_reward_model->checkRewardNo($reward_no)){
			return $this->getRewardNo();
		}
		return $reward_no;
	}

	public function sendRewardBatch(){
		if(!$_FILES['file']['error']){
			$tmp_file = fopen($_FILES['file']['tmp_name'],"r");
			$index = -1;		// 文件共$index+1行,待上传的文件有$index行
			$headArr = $dataArr = $data = [];
			while($line = trim(fgets($tmp_file))){
				$index++;
				$line = iconv('GBK','utf-8',$line);
				if($index == 0){
					$headArr = explode(',',$line);
				}else{
					$dataArr[] = explode(',',$line);
				}
			}

			$head = [
				'用户名','金额','过期时间','红包说明','红包限额','投资期限','红包类型'
			];
			if($headArr !== $head){
				die(json_encode([
					'errcode'	=>	1,
					'msg'		=>	'表头不正确哦'
				],JSON_UNESCAPED_UNICODE));
			}
			$import = 0;		// 最终上传的有$import行
			$time = time();
			$this->load->model('admin/User_model');
			// `use_together`, `username`, `money`, `timeout`, `reward_name`, `money_limit`, `borrow_days`,
			// `recommended_id`, `addtime`, `tender_id`, `borrow_id`, `is_use`, `reward_no`, `user_id`
			$i = $j = 0;
			$response = [];
			$tomorrow = strtotime(date('Y-m-d 23:59:59',strtotime('+1 days')));
			foreach($dataArr as $v){
				$showArr = [
					'state'		=>	true,
					'msg'		=>	''
				];
				if(!$res = $this->User_model->checkInUsername(['username'=>$v[0],'type_id'=>2])){
					$showArr['state']	=	false;
					$showArr['msg']		=	'用户不存在|';
				}
				if(!$v[0] || !$v[1] || !$v[2] || !$v[3] || !$v[4] || !$v[5] || !$v[6]){
				// if($res = array_map('is_empty',$v)){
					$showArr['state']	=	false;
					$showArr['msg']		.=	'数据有空值哦|';
				}
				$timeout_unix = $v[2]?strtotime($v[2].' 23:59:59'):0;
				$money_limit = $v[4]?$v[4]:'1000.00';

				if($timeout_unix <= $tomorrow){
					$showArr['state']	=	false;
					$showArr['msg']		.=	'过期时间不能小于明天|';
				}
				if(!in_array($v[6],[1,2])){
					$showArr['state']	=	false;
					$showArr['msg']		.=	'红包类型[1，2]|';
				}
				// 返回给前台的数据
				$showArr['username']	=	$v[0];
				$showArr['money']		=	$v[1];
				$showArr['timeout']		=	$v[2];
				$showArr['timeout_unix']=	$timeout_unix;
				$showArr['reward_name']=	$v[3];
				$showArr['use_together']=	$v[6] == 1?'现金券':($v[6] == 2?'红包券':'类型错误');
				$showArr['money_limit']	=	$money_limit;
				$showArr['borrow_days']	=	$v[5];

				$showData[$i] = $showArr;
				$i++;
				if(!$showArr['state']) continue;
				$j++;
				$arr = [
					'user_id'	=>	$res['user_id'],
					'username'	=>	$v[0],
					'money'		=>	$v[1],
					'timeout'	=>	$timeout_unix,
					'reward_name'	=>	$v[3],
					'money_limit'	=>	$money_limit,
					'borrow_days'	=>	$v[5],
					'recommended_id'=>	0,
					'addtime'	=>	$time,
					'tender_id'	=>	0,
					'borrow_id'	=>	0,
					'is_use'	=>	0,
					'reward_no'	=>	$this->getRewardNo(),
					'use_together'	=>	$v[6]					// 活动券，现金券
				];
				$data[] = $arr;
			}
			if(count($showData) != count($data)){
				$response = [
					'errcode'	=>	1,
					'msg'		=>	"发送失败:文件内共有{$index}条记录，有".($index-$j)."条记录需要修改",
					'data'		=>	$showData
				];
			}else{
				if($this->User_reward_model->addBatch($data)){
					$response = [
						'errcode'	=>	0,
						'msg'		=>	"发送成功:文件内共有{$index}条记录，实际发送{$j}条记录",
						'data'		=>	$showData
					];
				}else{
					$response = [
						'errcode'	=>	1,
						'msg'		=>	"发送失败",
						'data'		=>	$showData
					];
				}
			}

		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	"文件上传失败"
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function rechargeList(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$trade_no = $this->input->post('trade_no');
		$username = $this->input->post('username');
		$realname = $this->input->post('realname');
		$money = $this->input->post('money');
		$bank = $this->input->post('bank');
		$recharge_time_begin = $this->input->post('recharge_time_begin');
		$recharge_time_end = $this->input->post('recharge_time_end');
		$status = $this->input->post('status');
		$pay_type = $this->input->post('pay_type');

		$where = $like = [];

		$trade_no and $like['a.trade_no'] = $trade_no;
		$username and $like['b.username'] = $username;
		$realname and $like['b.realname'] = $realname;
		$money	  and $where['a.money'] = $money;
		$bank	  and $where['a.bank_code'] = $bank;
		$recharge_time_begin and $where['a.addtime >= '] = strtotime($recharge_time_begin);
		$recharge_time_end and $where['a.addtime <= '] = strtotime($recharge_time_end.' 23:59:59');
		is_numeric($status) and $where['a.status'] = $status;
		$pay_type and $where['a.pay_type'] = $pay_type;

		$this->load->model('admin/Account_recharge_model');
		$res1 = $this->Account_recharge_model->getRechargeList($where,$like,$page,$page_size);
		$res2 = $this->Account_recharge_model->getRechargeCount($where,$like);
		$pay_types = [
			'F'	=>	'线下充值',
			'M'	=>	'手工返现',
			'O'	=>	'还款充值',
			'R'	=>	'其他'
		];
		foreach($res1 as &$v){
			$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
			$v['verify_time'] = $v['verify_time']?date('Y-m-d H:i:s',$v['verify_time']):'';
			$v['pay_type'] = isset($pay_types[$v['pay_type']])?$pay_types[$v['pay_type']]:'';
			$v['status'] = !$v['status']?'审核':($v['status'] == 1?'成功':'失败');
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	$res1,
			'count'		=>	$res2['count'],
			'sum'		=>	$res2['sum']
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function rechargeAdd(){
		$data = $this->input->post(array('username','pay_type','money','remark'));
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('username','用户名','required|callback_check_username');
		$this->form_validation->set_rules('pay_type','充值类型','required|in_list[F,M,R,O]');
		$this->form_validation->set_rules('money','金额','required|greater_than[0]');
		$this->form_validation->set_rules('remark','备注','required');
		$this->form_validation->set_message('required','{field}不能为空');
		$this->form_validation->set_message('in_list','{field}不在{param}内');
		$this->form_validation->set_message('greater_than','{field}不能小于{param}');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		$this->load->model('admin/User_model');
		$userInfo = $this->User_model->checkInUsername(['username'=>$data['username'],'type_id'=>2]);
		$data['user_id'] = $userInfo['user_id'];
		$data['trade_no'] = time() . $data['user_id'] . rand(1, 9);
		$data['payment'] = 0;
		$data['type'] = 2;
		$data['addtime'] = time();
		unset($data['username']);
		$this->load->model('admin/Account_recharge_model');
		if($res = $this->Account_recharge_model->rechargeAdd($data)){
			$response['errcode'] = 0;
			$response['msg'] = '添加成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '添加失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function rechargeExport(){
		$trade_no = $this->input->post('trade_no');
		$username = $this->input->post('username');
		$realname = $this->input->post('realname');
		$money = $this->input->post('money');
		$bank = $this->input->post('bank');
		$recharge_time_begin = $this->input->post('recharge_time_begin');
		$recharge_time_end = $this->input->post('recharge_time_end');
		$status = $this->input->post('status');
		$pay_type = $this->input->post('pay_type');

		$where = $like = [];

		$trade_no and $like['a.trade_no'] = $trade_no;
		$username and $like['b.username'] = $username;
		$realname and $like['b.realname'] = $realname;
		$money	  and $where['a.money'] = $money;
		$bank	  and $where['a.bank_code'] = $bank;
		$recharge_time_begin and $where['a.addtime >= '] = strtotime($recharge_time_begin);
		$recharge_time_end and $where['a.addtime <= '] = strtotime($recharge_time_end.' 23:59:59');
		is_numeric($status) and $where['a.status'] = $status;
		$pay_type and $where['a.pay_type'] = $pay_type;
		$this->load->model('admin/Account_recharge_model');
		$res = $this->Account_recharge_model->rechargeExport($where,$like);

		foreach($res as &$v){
			$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
			$v['verify_time'] = $v['verify_time']?date('Y-m-d H:i:s',$v['verify_time']):'';
			$v['pay_type'] = isset($pay_types[$v['pay_type']])?$pay_types[$v['pay_type']]:'';
			$v['status'] = !$v['status']?'审核':($v['status'] == 1?'成功':'失败');
		}
		$response = [
			'errcode'		=>	0,
			'data'			=>	[
				'headKey'	=>	['id','trade_no','username','realname','pay_type','bank_name','money','fee','remark','addtime','verify_time','status'],
				'headVal'	=>	['ID','订单编号','用户名','真实姓名','充值类型','所属银行','充值金额','费用','备注','添加时间','审核时间','状态'],
				'data'	=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function accountList(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$username = $this->input->post('username');
		$realname = $this->input->post('realname');
		$condition = $this->input->post('condition');
		$money = $this->input->post('money');
		// $is_handel = $this->input->post('is_handel');

		$where = $like = [];
		$where['b.type_id'] = 2;
		$username and $like['b.username'] = $username;
		$realname and $like['b.realname'] = $realname;
		if($money && $condition){
			switch($condition){
				case 1:
					$where['a.use_money >='] = $money;
					break;
				case 2:
					$where['a.use_money <='] = $money;
					break;
				case 3:
					$where['a.no_use_money >='] = $money;
					break;
				case 4:
					$where['a.no_use_money <='] = $money;
					break;
				case 5:
					$where['(a.wait_capital+ a.wait_interest) >='] = $money;
					break;
				case 6:
					$where['(a.wait_capital+ a.wait_interest) <='] = $money;
					break;
				case 7:
					$where['(a.use_money+ a.no_use_money+a.wait_capital+ a.wait_interest) >='] = $money;
					break;
				case 8:
					$where['(a.use_money+ a.no_use_money+a.wait_capital+ a.wait_interest) <='] = $money;
					break;
			}
		}
		// is_numeric($is_handel) and $where['is_handel'] = $is_handel;

		$this->load->model('admin/Account_sina_model');
		$res1 = $this->Account_sina_model->getAccountList($where,$like,$page,$page_size);
		foreach($res1 as &$v){
			$v['total'] = $v['use_money'] + $v['no_use_money'] + $v['wait_capital'] + $v['wait_interest'];
			$v['wait_money'] = $v['wait_capital'] + $v['wait_interest'];
		}
		$res2 = $this->Account_sina_model->getAccountListCount($where,$like);
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$res1,
				'count'		=>	$res2['count'],
				'sum'		=>	[
					'total'			=>	$res2['use_money'] + $res2['no_use_money'] + $res2['wait_capital'] + $res2['wait_interest'],
					'use_money'		=>	$res2['use_money'],
					'no_use_money'	=>	$res2['no_use_money'],
					'wait_money'	=>	$res2['wait_capital'] + $res2['wait_interest'],
					'wait_capital'	=>	$res2['wait_capital'],
					'wait_interest'	=>	$res2['wait_interest']
				]
			]

		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function accountExport(){
		$username = $this->input->post('username');
		$realname = $this->input->post('realname');
		$condition = $this->input->post('condition');
		$money = $this->input->post('money');
		// $is_handel = $this->input->post('is_handel');

		$where = $like = [];
		$where['b.type_id'] = 2;
		$username and $like['b.username'] = $username;
		$realname and $like['b.realname'] = $realname;
		if($money && $condition){
			switch($condition){
				case 1:
					$where['a.use_money >='] = $money;
					break;
				case 2:
					$where['a.use_money <='] = $money;
					break;
				case 3:
					$where['a.no_use_money >='] = $money;
					break;
				case 4:
					$where['a.no_use_money <='] = $money;
					break;
				case 5:
					$where['(a.wait_capital+ a.wait_interest) >='] = $money;
					break;
				case 6:
					$where['(a.wait_capital+ a.wait_interest) <='] = $money;
					break;
				case 7:
					$where['(a.use_money+ a.no_use_money+a.wait_capital+ a.wait_interest) >='] = $money;
					break;
				case 8:
					$where['(a.use_money+ a.no_use_money+a.wait_capital+ a.wait_interest) <='] = $money;
					break;
			}
		}
		// is_numeric($is_handel) and $where['is_handel'] = $is_handel;

		$this->load->model('admin/Account_sina_model');
		$res = $this->Account_sina_model->getAccountList($where,$like);
		foreach($res as &$v){
			$v['total'] = $v['use_money'] + $v['no_use_money'] + $v['wait_capital'] + $v['wait_interest'];
			$v['wait_money'] = $v['wait_capital'] + $v['wait_interest'];
		}
		$response = [
			'errcode'		=>	0,
			'data'			=>	[
				'headKey'	=>	['id','username','realname','total','use_money','no_use_money','wait_money','wait_capital','wait_interest','fund','taste_money','xutou'],
				'headVal'	=>	['ID','用户名','真实姓名','总额','可用','冻结','待收','待收本金','待收利息','网银充值','taste_money','续投'],
				'data'	=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function accountBorrowList(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$username = $this->input->post('username');
		$realname = $this->input->post('realname');
		$condition = $this->input->post('condition');
		$money = $this->input->post('money');
		// $is_handel = $this->input->post('is_handel');

		$where = $like = [];
		$where['b.type_id'] = 17;
		$username and $like['b.username'] = $username;
		$realname and $like['b.realname'] = $realname;
		if($money && $condition){
			switch($condition){
				case 1:
					$where['a.use_money >='] = $money;
					break;
				case 2:
					$where['a.use_money <='] = $money;
					break;
				case 3:
					$where['a.no_use_money >='] = $money;
					break;
				case 4:
					$where['a.no_use_money <='] = $money;
					break;
				case 5:
					$where['a.collection >='] = $money;
					break;
				case 6:
					$where['a.collection <='] = $money;
					break;
			}
		}
		// is_numeric($is_handel) and $where['is_handel'] = $is_handel;

		$this->load->model('admin/Account_sina_model');
		$res1 = $this->Account_sina_model->getAccountList($where,$like,$page,$page_size);
		$res2 = $this->Account_sina_model->getAccountListCount($where,$like);
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$res1,
				'count'		=>	$res2['count'],
				'sum'		=>	[
					'total'			=>	$res2['use_money'] + $res2['no_use_money'] + $res2['wait_capital'] + $res2['wait_interest'],
					'use_money'		=>	$res2['use_money'],
					'no_use_money'	=>	$res2['no_use_money'],
					'wait_money'	=>	$res2['wait_capital'] + $res2['wait_interest'],
					'wait_capital'	=>	$res2['wait_capital'],
					'wait_interest'	=>	$res2['wait_interest']
				]
			]

		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function userCouponList(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$user_id = $this->input->post('user_id');
		$username = $this->input->post('username');
		$coupon = $this->input->post('coupon');
		$is_use = $this->input->post('is_use');
		$use_time_begin = $this->input->post('use_time_begin');
		$use_time_end = $this->input->post('use_time_end');
		$get_time_begin = $this->input->post('get_time_begin');
		$get_time_end = $this->input->post('get_time_end');
		$coupon_name = $this->input->post('coupon_name');
		$tender_id = $this->input->post('tender_id');
		$borrow_id = $this->input->post('borrow_id');
		$borrow_type = $this->input->post('borrow_type');

		$where = $like = [];
		$whereStr = '';
		$time = time();

		$user_id and $where['b.user_id'] = $user_id;
		$username and $like['b.username'] = $username;
		$coupon and $where['a.coupon'] = $coupon;
		switch($is_use) {
			case 1:
				$where['a.is_use'] = 0;
				$whereStr = '(a.timeout = 0 or a.timeout > '.$time.')';
				break;
			case 2:
				$where['a.is_use'] = 1;
				break;
			case 3:
				$where['a.is_use'] = 0;
				$where['a.timeout <> '] = 0;
				$where['a.timeout <= '] = $time;
				break;
			case 4:
				$where['a.is_use'] = 2;
				break;
		}
		$use_time_begin and $where['a.usedtime >='] = strtotime($use_time_begin);
		$use_time_end and $where['a.usedtime <='] = strtotime($use_time_end.' 23:59:59');
		$get_time_begin and $where['a.addtime >='] = strtotime($get_time_begin);
		$get_time_end and $where['a.addtime <='] = strtotime($get_time_end.' 23:59:59');
		$coupon_name and $like['a.coupon_name'] = $coupon_name;
		$tender_id and $where['a.tender_id'] = $tender_id;
		$borrow_id and $where['a.borrow_id'] = $borrow_id;
		$borrow_type and $where['d.borrow_type'] = $borrow_type;

		$this->load->model('admin/User_coupon_model');
		$res1 = $this->User_coupon_model->getConponList($where,$whereStr,$like,$page,$page_size);
		$res2 = $this->User_coupon_model->getConponCount($where,$whereStr,$like);

		foreach($res1 as &$v){
			$v['regtime'] = $v['regtime']?date('Y-m-d',$v['regtime']):'';
			$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
			$v['usedtime'] = $v['usedtime']?date('Y-m-d H:i:s',$v['usedtime']):'';
			if($v['is_use'] == 1){
				$v['is_use'] = '已使用';
			}else if($v['is_use'] == 2){
				$v['is_use'] = '冻结';
			}else if($v['is_use'] == 0 && ($v['timeout'] && $v['timeout'] >= $time)){
				$v['is_use'] = '未使用';
			}else{
				$v['is_use'] = '已过期';
			}
			$v['timeout'] = $v['timeout']?date('Y-m-d H:i:s',$v['timeout']):'永久有效';
			$v['borrow_limit'] = $v['borrow_limit']?explode(',',$v['borrow_limit']):[];
		}

		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$res1,
				'count'		=>	$res2['count'],
				'sum'		=>	[
					'count_users'	=>	$res2['count_users'],
					'tender_moneys'	=>	$res2['tender_moneys'],
					'performances'	=>	$res2['performances'],
					'coupon_capitals'	=>	$res2['coupon_capitals']
				]
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function addUserCoupon(){
		$data = $this->input->post(array('username','coupon_name','coupon','timeout','money_minimun_limit','money_limit','borrow_days','borrow_limit'));
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('username','用户名(手机号)','required|callback_check_username');
		$this->form_validation->set_rules('coupon_name','年化券名称','required');
		$this->form_validation->set_rules('coupon','年化券','required');
		$this->form_validation->set_rules('timeout','过期时间','');
		$this->form_validation->set_rules('money_minimun_limit','起投金额','greater_than_equal_to[0]');
		$this->form_validation->set_rules('money_limit','最大限额','greater_than_equal_to[0]');
		$this->form_validation->set_rules('borrow_days','投资期限','greater_than_equal_to[0]');
		$this->form_validation->set_rules('borrow_limit[]','限定投资类型','required');

		$this->form_validation->set_message('required','{field}不能为空');
		$this->form_validation->set_message('greater_than_equal_to','{field}不能小于{param}');

		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
		}
		if($data['money_minimun_limit'] > $data['money_limit'] && $data['money_limit'] != 0){
			!isset($error['money_minimun_limit'])?$error['money_minimun_limit'] = '起投金额不能大于最大限额':'';
		}
		if(isset($error)){
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}

		$data['type'] = 1;
		$data['is_use'] = 0;
		$data['timeout'] = $data['timeout']?strtotime($data['timeout'].' 23:59:59'):0;
		$data['addtime'] = time();
		$data['tender_id'] = 0;
		$data['borrow_id'] = 0;
		$data['borrow_limit'] = implode(',',$data['borrow_limit']);

		$this->load->model('admin/User_model');
		$this->load->model('admin/User_coupon_model');
		$userInfo = $this->User_model->getUserInfoByUserName(['username'=>$data['username'],'type'=>2]);
		$data['user_id'] = $userInfo['user_id'];
		$res = $this->User_coupon_model->add($data);
		if($res){
			$response = [
				'errcode'	=>	0,
				'msg'		=>	'添加成功'
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'添加失败'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function getCouponType(){
		$this->load->model('admin/User_coupon_model');
		$res = $this->User_coupon_model->getCouponType();
		$response = [
			'errcode'	=>	0,
			'data'		=>	$res
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function borrowType(){
		$this->load->model('admin/Linkage_model');
		$res = $this->Linkage_model->getConstsByNid('borrow_use');
		if($res){
			$response = [
				'errcode'	=>	0,
				'data'		=>	$res
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'标类型没有数据'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function borrowStyle(){
		$this->load->model('admin/Linkage_model');
		$res = $this->Linkage_model->getConstsByNid('borrow_style');
		if($res){
			$response = [
				'errcode'	=>	0,
				'data'		=>	$res
			];
		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	'还款方式没有数据'
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function addUserCouponBatch(){
		if(!$_FILES['file']['error']){
			$tmp_file = fopen($_FILES['file']['tmp_name'],"r");
			$index = -1;		// 文件共$index+1行,待上传的文件有$index行
			$headArr = $dataArr = $data = [];
			while($line = trim(fgets($tmp_file))){
				$index++;
				$line = iconv('GBK','utf-8',$line);
				if($index == 0){
					$headArr = explode(',',$line);
				}else{
					$dataArr[] = explode(',',$line);
				}
			}

			$head = [
				'用户名','年化券名称','年化率','过期时间','起投金额','最大限额','投资期限'
			];
			if($headArr !== $head){
				die(json_encode([
					'errcode'	=>	1,
					'msg'		=>	'表头不正确哦'
				],JSON_UNESCAPED_UNICODE));
			}
			$import = 0;		// 最终上传的有$import行
			$time = time();
			$this->load->model('admin/User_model');
			// `use_together`, `username`, `money`, `timeout`, `reward_name`, `money_limit`, `borrow_days`,
			// `recommended_id`, `addtime`, `tender_id`, `borrow_id`, `is_use`, `reward_no`, `user_id`
			$i = $j = 0;
			$response = [];
			$tomorrow = strtotime(date('Y-m-d 23:59:59',strtotime('+1 days')));
			foreach($dataArr as $v){
				$showArr = [
					'state'		=>	true,
					'msg'		=>	''
				];
				if(!$res = $this->User_model->checkInUsername(['username'=>$v[0],'type_id'=>2])){
					$showArr['state']	=	false;
					$showArr['msg']		=	'用户不存在|';
				}
				if(!$v[0]){
					$showArr['state']	=	false;
					$showArr['msg']		.=	'用户名不能为空哦|';
				}
				if(!$v[1]){
					$showArr['state']	=	false;
					$showArr['msg']		.=	'年化券名称不能为空|';
				}
				if($v[2] < 0){
					$showArr['state']	=	false;
					$showArr['msg']		.=	'年化率不能小于0|';
				}

				$timeout_unix = $v[3]?strtotime($v[3].' 23:59:59'):0;
				if(!is_numeric($v[4])){
					$showArr['state']	=	false;
					$showArr['msg']		.=	'起投金额只能为数字|';
				}
				if(!is_numeric($v[5])){
					$showArr['state']	=	false;
					$showArr['msg']		.=	'最大限额只能为数字|';
				}
				if($v[5] != 0 && $v[4] > $v[5]){
					$showArr['state']	=	false;
					$showArr['msg']		.=	'起投金额不能大于最大限额|';
				}
				if(!is_numeric($v[6])){
					$showArr['state']	=	false;
					$showArr['msg']		.=	'投资期限只能为数字|';
				}
				$money_limit = $v[4]?$v[4]:'1000.00';

				if($timeout_unix && $timeout_unix <= $tomorrow){
					$showArr['state']	=	false;
					$showArr['msg']		.=	'过期时间不能小于明天|';
				}
				// 返回给前台的数据
				$showArr['username']			=	$v[0];
				$showArr['coupon_name']			=	$v[1];
				$showArr['coupon']				=	$v[2];
				$showArr['timeout_unix']		=	$timeout_unix;
				$showArr['timeout']				=	$v[3];
				$showArr['money_minimun_limit']	=	$v[4];
				$showArr['money_limit']			=	$v[5];
				$showArr['borrow_days']			=	$v[6];

				$showData[$i] = $showArr;
				$i++;
				if(!$showArr['state']) continue;
				$j++;
				$arr = [
					'user_id'				=>	$res['user_id'],
					'username'				=>	$v[0],
					'coupon_name'			=>	$v[1],
					'coupon'				=>	$v[2],
					'timeout'				=>	$timeout_unix,
					'money_minimun_limit'	=>	$v[4],
					'money_limit'			=>	$v[5],
					'borrow_days'			=>	$v[6],
					'addtime'				=>	$time,
					'tender_id'				=>	0,
					'borrow_id'				=>	0,
					'is_use'				=>	0,
					'type'					=>	1
				];
				$data[] = $arr;
			}
			if(count($showData) != count($data)){
				$response = [
					'errcode'	=>	1,
					'msg'		=>	"发送失败:文件内共有{$index}条记录，有".($index-$j)."条记录需要修改",
					'data'		=>	$showData
				];
			}else{
				$this->load->model('admin/User_coupon_model');
				if($this->User_coupon_model->addBatch($data)){
					$response = [
						'errcode'	=>	0,
						'msg'		=>	"发送成功:文件内共有{$index}条记录，实际发送{$j}条记录",
						'data'		=>	$showData
					];
				}else{
					$response = [
						'errcode'	=>	1,
						'msg'		=>	"发送失败",
						'data'		=>	$showData
					];
				}
			}

		}else{
			$response = [
				'errcode'	=>	1,
				'msg'		=>	"文件上传失败"
			];
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function projectCount(){
		$pattern = $this->input->post('pattern')?$this->input->post('pattern'):1;
		$time_begin = $this->input->post('time_begin');
		$time_end = $this->input->post('time_end');
		if(!$time_begin && !$time_end){
			$time_begin = date('Y-m-01');
			$time_end = date('Y-m-t');
		}

		$where['stat_date >='] = $time_begin;
		$where['stat_date <='] = $time_end;
		switch($pattern){
			case 1:
				$field = "`stat_date` s_date";
				break;
			case 2:
				$field = "DATE_FORMAT(`stat_date`,'%Y-%u') s_date";
				break;
			case 3:
				$field = "DATE_FORMAT(`stat_date`,'%Y-%m') s_date";
				break;
			case 4:
				$field = "CONCAT(DATE_FORMAT(`stat_date`,'%Y'),'-',QUARTER(`stat_date`)) s_date";
				break;
			case 5:
				$field = "DATE_FORMAT(`stat_date`,'%Y') s_date";
				break;
			default:
				$field = "`stat_date` s_date";
		}
		$this->load->model('admin/Account_sina_model');
		$res = $this->Account_sina_model->getProjectCount($field,$time_begin,$time_end);
		$sum = [
			'registered_users'		=>	0,
			'verified_users'		=>	0,
			'new_investors'			=>	0,
			'channel_new_investors'	=>	0,
			'investment_users'		=>	0,
			'reward_money'			=>	0,
			'cash_coupon'			=>	0,
			'coupon_money'			=>	0,
			'investment_money'		=>	0,
			'performance'			=>	0,
			'recharge_users'		=>	0,
			'recharge_money'		=>	0,
			'cash_users'			=>	0,
			'cash_money'			=>	0,
			'repayment_users'			=>	0,
			'repayment_interest'	=>	0,
			'repayment_money'		=>	0,
			'new_borrowings'		=>	0
		];
		foreach($res as $v){
			$sum['registered_users'] += $v['registered_users'];
			$sum['verified_users'] += $v['verified_users'];
			$sum['new_investors'] += $v['new_investors'];
			$sum['channel_new_investors'] += $v['channel_new_investors'];
			$sum['investment_users'] += $v['investment_users'];
			$sum['reward_money'] += $v['reward_money'];
			$sum['cash_coupon'] += $v['cash_coupon'];
			$sum['coupon_money'] += $v['coupon_money'];
			$sum['investment_money'] += $v['investment_money'];
			$sum['performance'] += $v['performance'];
			$sum['registered_users'] += $v['recharge_users'];
			$sum['recharge_money'] += $v['recharge_money'];
			$sum['cash_users'] += $v['cash_users'];
			$sum['cash_money'] += $v['cash_money'];
			$sum['repayment_users'] += $v['repayment_users'];
			$sum['repayment_interest'] += $v['repayment_interest'];
			$sum['repayment_money'] += $v['repayment_money'];
			$sum['new_borrowings'] += $v['new_borrowings'];
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$res,
				'sum'		=>	$sum
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function freshProjectCount(){
		$month = $this->input->post('date');
		$this_month_unix = strtotime(date('Y-m-01'));
		if($month){
			$month_unix = strtotime($month);
			if($this_month_unix > $month_unix){
				$time_begin = strtotime($month.'-01');
				$time_end = strtotime($month.'-'.date('t',strtotime($month)));
			}elseif($this_month_unix == $month_unix){
				$time_begin = strtotime($month.'-01');
				$time_end = strtotime(date('Y-m-d'));
			}else{
				$response = [
					'errcode'	=>	1,
					'msg'		=>	'月份超过当前月份'
				];
				die(json_encode($response,JSON_UNESCAPED_UNICODE));
			}
		}
		$this->load->model('admin/Account_sina_model');
		$this->Account_sina_model->addStatDate($time_begin,$time_end);
		$res = $this->Account_sina_model->freshProjectCount($time_begin,$time_end);
		if($res){
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
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function rewardReport(){
		$time_begin = $this->input->post('time_begin');
		$time_end = $this->input->post('time_end');
		if(!$time_begin && !$time_end){
			$time_begin = date('Y-m-01');
			$time_end = date('Y-m-t');
		}
		$this->load->model('admin/User_reward_model');
		$res = $this->User_reward_model->rewardReport(strtotime($time_begin),strtotime($time_end.' 23:59:59'));
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'				=>	$res1,
				'sum'				=>	$res2
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function investList(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$username = $this->input->post('username');
		$money = $this->input->post('money');
		$invite_userid = $this->input->post('invite_userid');
		$name = $this->input->post('name');
		$time_begin = $this->input->post('time_begin');
		$time_end = $this->input->post('time_end');
		if(!$time_begin && !$time_end){
			$time_begin = date('Y-m-01');
			$time_end = date('Y-m-t');
		}
		$where = $like = [];
		$username and $like['b.username'] = $username;
		$money and $where['a.money'] = $money;
		$invite_userid and $where['b.invite_userid'] = $invite_userid;
		$name and $like['c.name'] = $name;
		$time_begin and $where['a.addtime >='] = strtotime($time_begin);
		$time_end and $where['a.addtime <='] = strtotime($time_end.' 23:59:59');


		$this->load->model('admin/Borrow_tender_model');
		$res1 = $this->Borrow_tender_model->getInvestList($where,$like,$page,$page_size);
		$res2 = $this->Borrow_tender_model->getInvestCount($where,$like);
		foreach($res1 as &$v){
			$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
			$v['end_time'] = $v['end_time']?date('Y-m-d H:i:s',$v['end_time']):'';
		}
		$response = [
			'errcode'	=>	0,
			'data'	=>	[
				'res'		=>	$res1,
				'count'		=>	$res2['count'],
				'sum'		=>	$res2['moneys']
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function loanList(){
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$apr = $this->input->post('apr');
		$borrow_type = $this->input->post('borrow_type');
		$real_status = $this->input->post('real_status');
		$success_time_begin = $this->input->post('success_time_begin');
		$success_time_end = $this->input->post('success_time_end');
		$repay_time_begin = $this->input->post('repay_time_begin');
		$repay_time_end = $this->input->post('repay_time_end');

		$where = $like = [];
		$apr and $where['a.apr'] = $apr;
		if($borrow_type){
			if($borrow_type == 1){
				$where['a.new_hand'] = 1;
			}else{
				$where['a.borrow_type'] = $borrow_type;
			}
		}
		is_numeric($real_status) and $where['real_status'] = $real_status;
		$success_time_begin and $where['a.success_time >= '] = strtotime($success_time_begin);
		$success_time_end and $where['a.success_time <= '] = strtotime($success_time_end.' 23:59:59');
		$repay_time_begin and $where['a.repayment_time >= '] = strtotime($repay_time_begin);
		$repay_time_end and $where['a.repayment_time <= '] = strtotime($repay_time_end.' 23:59:59');

		$this->load->model('admin/Borrow_model');
		$res1 = $this->Borrow_model->getLoanList($where,$page,$page_size);
		$res2 = $this->Borrow_model->getLoanCount($where);

		foreach($res1 as &$v){
			$v['success_time'] = $v['success_time']?date('Y-m-d',$v['success_time']):'';
			$v['end_time'] = $v['end_time']?date('Y-m-d',$v['end_time']):'';
			$v['repayment_time'] = $v['repayment_time']?date('Y-m-d',$v['repayment_time']):'';
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'			=>	$res1,
				'count'		=>	$res2['count'],
				'sum'		=>	[
					'account'	=>	$res2['account'],
					'performance'	=>	$res2['performance'],
					'reward'	=>	$res2['reward'],
					'interest'	=>	$res2['interest'],
					'coupon_money'	=>	$res2['coupon_money'],
				]
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function loanExport(){
		$apr = $this->input->post('apr');
		$borrow_type = $this->input->post('borrow_type');
		$real_status = $this->input->post('real_status');
		$success_time_begin = $this->input->post('success_time_begin');
		$success_time_end = $this->input->post('success_time_end');
		$repay_time_begin = $this->input->post('repay_time_begin');
		$repay_time_end = $this->input->post('repay_time_end');

		$where = $like = [];
		$apr and $where['a.apr'] = $apr;
		if($borrow_type){
			if($borrow_type == 1){
				$where['a.new_hand'] = 1;
			}else{
				$where['a.borrow_type'] = $borrow_type;
			}
		}
		is_numeric($real_status) and $where['real_status'] = $real_status;
		$success_time_begin and $where['a.success_time >= '] = strtotime($success_time_begin);
		$success_time_end and $where['a.success_time <= '] = strtotime($success_time_end.' 23:59:59');
		$repay_time_begin and $where['a.repayment_time >= '] = strtotime($repay_time_begin);
		$repay_time_end and $where['a.repayment_time <= '] = strtotime($repay_time_end.' 23:59:59');

		$this->load->model('admin/Borrow_model');
		$res = $this->Borrow_model->getLoanList($where);
		$borrow_types = [
			1	=>	'新手标',
			245	=>	'有余车贷',
			246	=>	'新车贷',
			247	=>	'有余房贷',
			248	=>	'有余福标',
			565	=>	'票据贷',
			815	=>	'VIP定制标',
			900	=>	'车商贷',
		];
		$real_states = [
			0	=>	'等待审核',
			1	=>	'审核失败',
			2	=>	'投资中',
			3	=>	'已满标',
			4	=>	'已流标',
			5	=>	'还款中',
			6	=>	'已还款',
			7	=>	'满标待审',
		];
		foreach($res as &$v){
			$v['success_time'] = $v['success_time']?date('Y-m-d',$v['success_time']):'';
			$v['end_time'] = $v['end_time']?date('Y-m-d',$v['end_time']):'';
			$v['repayment_time'] = $v['repayment_time']?date('Y-m-d',$v['repayment_time']):'';
			$v['borrow_type'] = $v['new_hand']?'新手标':(isset($borrow_types[$v['borrow_type']])?$borrow_types[$v['borrow_type']]:'');
			$v['real_state'] = isset($real_states[$v['real_state']])?$real_states[$v['real_state']]:'';
			unset($v['time_limit_day']);
			unset($v['account_yes']);
			unset($v['repayment_account']);
			unset($v['repayment_yesaccount']);
			unset($v['new_hand']);
			unset($v['money']);
		}
		$response = [
			'errcode'		=>	0,
			'data'			=>	[
				'headKey'	=>	['id','realname','name','success_time','end_time','repayment_time','account','performance','apr','reward','interest','coupon_money','borrow_type','real_state'],
				'headVal'	=>	['ID','真实姓名','标题','开标时间','还款时间','实际还款时间','总金额','业绩','年化','使用红包','利息','贴息金额','标类型','状态'],
				'data'	=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function rechargeCount(){
		$time_begin = $this->input->post('time_begin');
		$time_end = $this->input->post('time_end');
		if(!$time_begin && !$time_end){
			$time_begin = date('Y-m-01');
			$time_end = date('Y-m-t');
		}
		$begin = strtotime($time_begin);
		$end = strtotime($time_end.' 23:59:59');
		$this->load->model('admin/Account_sina_model');
		$type="'".ACCOUNT_LOG_TYPE_RECHARGE."','".ACCOUNT_LOG_REAL_TYPE_RECHARGE."'";
		$tender_list = $this->Account_sina_model->report_recharge_type_day($begin, $end, $type); //用户充值
        $repayment_list = $this->Account_sina_model->report_rechargelog_day($begin, $end, 2);//充值日志

		/*$cash_user_list = $this->account_sina_model->report_cash_day($begin, $end, 2);
        $cash_user_list = $this->get_result($cash_user_list);*/
		$type_user="'".ACCOUNT_LOG_TYPE_CASH_SUCCESS."','".ACCOUNT_LOG_TYPE_CASH_FROST."'";
        $cash_user_list = $this->Account_sina_model->report_recharge_type_day($begin, $end, $type_user);

        $cash_borr_list = $this->Account_sina_model->report_cash_day($begin, $end, 17);

		$type_rep="'".ACCOUNT_LOG_REAL_TYPE_PAYBACK."'";
        $tender_borr_list = $this->Account_sina_model->report_recharge_realtype_day($begin, $end, $type_rep);//借款人充值
        $arr = [];
				$sum = [
					'moneys'		=>	0,
					'camoneys'	=>	0,
					'brmoneys'	=>	0,
				];
        foreach ($tender_list as $ent) {
            $ent->rppv = 0;
            $ent->rpmoneys = 0;
            $ent->brpv = 0;
            $ent->brmoneys = 0;
            $ent->capv = 0;
            $ent->camoneys = 0;
            $ent->tbrpv = 0;
            $ent->tbrmoneys = 0;
            $arr[$ent->dt] = $ent;
        }
        foreach ($repayment_list as $ent) {
            if (array_key_exists($ent->dt, $arr)) {
                $ent_up = $arr[$ent->dt];
                $ent_up->rppv = $ent->pv;
                $ent_up->rpmoneys = $ent->moneys;
            }
        }
        foreach ($cash_user_list as $ent) {
            if (array_key_exists($ent->dt, $arr)) {
                $ent_up = $arr[$ent->dt];
                $ent_up->capv = $ent->pv;
                $ent_up->camoneys = $ent->moneys;
            }
        }
        foreach ($cash_borr_list as $ent) {
            if (array_key_exists($ent->dt, $arr)) {
                $ent_up = $arr[$ent->dt];
                $ent_up->brpv = $ent->pv;
                $ent_up->brmoneys = $ent->moneys;
            }
        }
        foreach ($tender_borr_list as $ent) {
            if (array_key_exists($ent->dt, $arr)) {
                $ent_up = $arr[$ent->dt];
                $ent_up->tbrpv = $ent->pv;
                $ent_up->tbrmoneys = $ent->moneys;
            }
		}
		foreach($arr as $v){
			$sum['moneys'] += $v->moneys;
			$sum['camoneys'] += $v->camoneys;
			$sum['brmoneys'] += $v->brmoneys;
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$arr,
				'sum'		=>	$sum
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function moneyReport(){
		$time_begin = $this->input->post('time_begin');
		$time_end = $this->input->post('time_end');
		if(!$time_begin && !$time_end){
			$time_begin = date('Y-m-01');
			$time_end = date('Y-m-t');
		}
		$where1 = $where2 = [
			'status' => 1
		];
		if($time_begin){
			$where1['addtime >= '] = strtotime($time_begin);
			$where2['repayment_time >= '] = strtotime($time_begin);
		}
		if($time_end){
			$where1['addtime <= '] = strtotime($time_end.' 23:59:59');
			$where2['repayment_time <= '] = strtotime($time_end.' 23:59:59');
		}
		$this->load->model('admin/Borrow_tender_model');
		$res1 = $this->Borrow_tender_model->moneyUse($where1);
		$res2 = $this->Borrow_tender_model->moneyRepay($where2);
		$keys = array_merge(array_column($res1,'dt'),array_column($res2,'dt'));

		$aa = [
			'moneys'		=>	0,
			'rewards'		=>	0,
			'performance'	=>	0,
			'pv'			=>	0
		];
		$bb = [
			'repayments'	=>	0,
			'interests'		=>	0,
			'hpv'			=>	0
		];
		$newArr = [];
		foreach($res1 as $k=>$v){
			$v = array_merge($v,$bb);
			$newArr[$v['dt']] = $v;
		}
		foreach($res2 as $m=>$n){
			if(array_key_exists($n['dt'],$newArr)){
				$n = array_merge($newArr[$n['dt']],$n);
			}else{
				$n = array_merge($aa,$n);
			}
			$newArr[$n['dt']] = $n;
		}
		krsort($newArr);

		$sum = [
			'moneys'	=>	0,
			'rewards'	=>	0,
			'performance'	=>	0,
			'pv'		=>	0,
			'repayments'=>	0,
			'interests'	=>	0,
			'hpv'		=>	0
		];
		foreach($newArr as $v){
			$sum['moneys'] += $v['moneys'];
			$sum['rewards'] += $v['rewards'];
			$sum['performance'] += $v['performance'];
			$sum['pv'] += $v['pv'];
			$sum['repayments'] += $v['repayments'];
			$sum['interests'] += $v['interests'];
			$sum['hpv'] += $v['hpv'];
		}

		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'res'		=>	$newArr,
				'sum'		=>	$sum
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function overview(){
		$this->load->model('admin/User_reward_model');
		$this->load->model('admin/Account_sina_model');
		$this->load->model('admin/Account_recharge_model');
		$this->load->model('admin/Account_cash_model');
		$this->load->model('admin/Borrow_tender_model');
		$this->load->model('admin/Borrow_repayment_model');

		$sql = "select `is_use`,sum(`money`) moneys from `dw_user_reward` group by `is_use`";
		$query = $this->User_reward_model->db->query($sql);
		$res = $query->result_array();
		$rewards = [];
		foreach($res as $v){
			if($v['is_use'] == 0){
				$rewards['no_use'] = $v['moneys'];
			}else{
				$rewards['used'] = $v['moneys'];
			}
		}

		$sql = "SELECT sum(use_money) use_moneys,sum(no_use_money) no_use_moneys,sum( wait_capital+wait_interest) wait_moneys FROM dw_account_sina where user_id > 0";
		$query = $this->Account_sina_model->db->query($sql);
		$accounts = $query->row_array();

		$sql = "select sum(money) as recharges from dw_account_recharge where status = 1";
		$query = $this->Account_recharge_model->db->query($sql);
		$recharges = $query->row_array();
		$accounts['recharges'] = $recharges['recharges'];

		$sql = "select sum(total) as withdrawals from dw_account_cash where status = 1";
		$query = $this->Account_cash_model->db->query($sql);
		$withdrawals = $query->row_array();
		$accounts['withdrawals'] = $withdrawals['withdrawals'];

		$sql = "SELECT sum(money) invests, sum(reward) use_rewards FROM dw_borrow_tender";
		$query = $this->Borrow_tender_model->db->query($sql);
		$invests = $query->row_array();

		$sql = "SELECT sum(repayment_account) repays, sum(interest) Interests FROM dw_borrow_repayment WHERE status > 0";
		$query = $this->Borrow_repayment_model->db->query($sql);
		$repays = $query->row_array();
		
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'rewards'		=>	$rewards,
				'accounts'		=>	$accounts,
				'invests'		=>	$invests,
				'repays'		=>	$repays
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}
}
