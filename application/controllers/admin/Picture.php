<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Picture extends Common {

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
		$this->load->model('admin/scrollpic_model');
		$this->load->model('admin/linkage_model');
	}

	public function lists(){
		$search = $this->input->post('search');
		$page = $this->input->post('page')?$this->input->post('page'):1;
		$page_size = $this->input->post('size')?$this->input->post('size'):PAGE_SIZE;
		$res = $this->scrollpic_model->pictureList($search,$page,$page_size);
		foreach($res as &$v){
			$v['show_time_begin'] = $v['show_time_begin']?date('Y-m-d H:i:s',$v['show_time_begin']):'';
			$v['show_time_end'] = $v['show_time_end']?date('Y-m-d H:i:s',$v['show_time_end']):'';
			$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
		}
		$count = $this->scrollpic_model->pictureListCount($search);
		$response = [
			'errcode'	=>	0,
			'data'		=>	[
				'count'		=>	$count,
				'lists' 	=>	$res
			]
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function picType(){
		$res = $this->linkage_model->getConstsByNid('picture_type');
		$response = $data = [];
		foreach($res as $v){
			$new_v['const_id'] = $v['id'];
			$new_v['name'] = $v['name'];
			$data[] = $new_v;
		}
		$response = [
			'errcode'	=>	0,
			'data'		=>	$data
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function picAdd(){
		// $domain_path = $this->config->item('system_path').$this->config->item('system_img_path').'/';
		// $img_path = 'upload/image/'.date('Y/m/d');
		// $config['upload_path'] = $domain_path.$img_path;
		// $config['allowed_types'] = 'gif|jpg|png';
		// // $config['max_size'] = '100';
		// // $config['max_width'] = '1024';
		// // $config['max_height'] = '768';

		// $this->load->library('upload', $config);
		// $this->upload->initialize($config);
		// $this->upload->display_errors('<p>', '</p>');
		// var_dump($this->upload->data());
		// var_dump($this);
		$url = $_POST['url'];
		$data = $this->input->post(array('href','remind_name','order','show_time_begin','show_time_end','status','type_id'));
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('status','状态','in_list[0,1]');
		$this->form_validation->set_rules('type_id','图片类型','callback_check_type_id');
		$this->form_validation->set_message('required','{field}不能为空');
		$this->form_validation->set_message('in_list','{field}不在指定范围内');
		if(!$url){
			$response = [
				'errcode'	=> 1,
				'error'		=>	[
					'url'	=>	'上传图片不能为空'
				]
			];
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		// 将base:64位数据保存到文件夹
		
		$relative_path = $this->config->item('system_path').$this->config->item('system_img_path').'/';
		$path = 'upload/image/'.date('Y/m/d').'/';
		if(preg_match('/^(data:\s*image\/(\w+);base64,)/',$url,$result)){
			$filename = 'br'.date("His").get_rand().'.'.$result[2];
			$contents = base64_decode(str_replace($result[1],'',$url));
		}else{
			$response = [
				'errcode'	=> 1,
				'error'		=>	[
					'url'	=>	'上传图片格式不正确'
				]
			];
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		if(!is_dir($relative_path.$path)){
			mkdir($relative_path.$path,0777,true);
		}
		file_put_contents($relative_path.$path.$filename,$contents);
		$data['size'] = getimagesize($relative_path.$path.$filename)[0];
		$data['url'] = $path.$filename;
		$data['name'] = $filename;
		$data['show_time_begin'] = $data['show_time_begin']?strtotime($data['show_time_begin']):0;
		$data['show_time_end'] = $data['show_time_end']?strtotime($data['show_time_end']):0;
		$data['addtime'] = time();
		$data['addip'] = $this->input->ip_address();
		if($this->scrollpic_model->add($data)){
			$response['errcode'] = 0;
			$response['msg'] = '添加成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '添加失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function picEdit(){
		$id = $this->input->post('id');
		$res = $this->scrollpic_model->getPictureInfo($id);
		$response = [];
		if($res){
			$res['url'] = $this->config->item('system_http').'://'.$this->config->item('system_domain').'/'.$res['url'];
			$res['show_time_begin'] = $res['show_time_begin']?date('Y-m-d H:i:s',$res['show_time_begin']):'';
			$res['show_time_end'] = $res['show_time_end']?date('Y-m-d H:i:s',$res['show_time_end']):'';
			$response['errcode'] = 0;
			$response['data'] = $res;
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '图片不存在';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function picUpdate(){
		$id = $this->input->post('id');
		$data = $this->input->post(array('type_id','href','remind_name','order','show_time_begin','show_time_end','status'));
		$url = $_POST['url'];
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('status','状态','in_list[0,1]');
		$this->form_validation->set_rules('type_id','图片类型','callback_check_type_id');
		// $this->form_validation->set_message('required','{field}不能为空');
		$this->form_validation->set_message('in_list','{field}不在指定范围内');
		if(!$url){
			$response = [
				'errcode'	=> 1,
				'error'		=>	[
					'url'	=>	'上传图片不能为空'
				]
			];
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}
		if(strpos($url,'data:') === 0){
			$relative_path = $this->config->item('system_path').$this->config->item('system_img_path').'/';
			$path = 'upload/image/'.date('Y/m/d').'/';
			if(preg_match('/^(data:\s*image\/(\w+);base64,)/',$url,$result)){
				$filename = 'br'.date("His").get_rand().'.'.$result[2];
				$contents = base64_decode(str_replace($result[1],'',$url));
			}else{
				$response = [
					'errcode'	=> 1,
					'error'		=>	[
						'url'	=>	'上传图片格式不正确'
					]
				];
				die(json_encode($response,JSON_UNESCAPED_UNICODE));
			}
			if(!is_dir($relative_path.$path)){
				mkdir($relative_path.$path,0777,true);
			}
			file_put_contents($relative_path.$path.$filename,$contents);
			$data['size'] = getimagesize($relative_path.$path.$filename)[0];
			$data['url'] = $path.$filename;
			$data['name'] = $filename;
		}
		$data['show_time_begin'] = $data['show_time_begin']?strtotime($data['show_time_begin']):0;
		$data['show_time_end'] = $data['show_time_end']?strtotime($data['show_time_end']):0;
		if($this->scrollpic_model->update($id,$data)){
			$response['errcode'] = 0;
			$response['msg'] = '更新成功';
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '更新失败';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function picDel(){
		$id = $this->input->post('id');
		if($this->scrollpic_model->getPictureInfo($id)){
			if($this->scrollpic_model->del($id)){
				$response['errcode'] = 0;
				$response['msg'] = '删除成功';
			}else{
				$response['errcode'] = 1;
				$response['msg'] = '删除失败';
			}
		}else{
			$response['errcode'] = 1;
			$response['msg'] = '请求数据不存在';
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function check_type_id($id){
		$this->form_validation->set_message('check_type_id','{field}不在指定范围内');
		return $this->linkage_model->getConstsByNidAndId('picture_type',$id);
	}
}
