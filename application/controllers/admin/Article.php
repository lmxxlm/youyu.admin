<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends Common {

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

	public function siteList(){
		$page = $this->input->post('page')?:1;
		$page_size = $this->input->post('size')?:PAGE_SIZE;
		$site_id = $this->input->post('site_id');
		$code = $this->input->post('code');
		$pid = $this->input->post('pid');
		$nid = $this->input->post('nid');
		$name = $this->input->post('name');

		$where = $like = [];

		$site_id and $where['site_id'] = $site_id;
		$code and $like['code'] = $code;
		$pid and $where['pid'] = $pid;
		$nid and $where['nid'] = $nid;
		$name and $like['name'] = $name;

		$this->load->model('admin/Site_model');
		$res1 = $this->Site_model->getSiteList($where,$like,$page,$page_size);
		$res2 = $this->Site_model->getSiteCount($where,$like);
		foreach($res1 as &$v){
			$v['addtime'] = $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'';
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

	public function siteAdd(){
		$data = $this->input->post(array('code','name','nid','pid','rank','url','aurl','isurl','order','status','style','litpic','content','list_name','content_name','sitedir','visit_type','index_tpl','list_tpl','content_tpl','title','keywords','description'));
		$this->form_validation->set_data($data);
        $this->form_validation->set_rules('code', 'code', 'required');
        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('nid', 'nid', 'required');
        $this->form_validation->set_rules('pid', 'pid', 'required');
        $this->form_validation->set_rules('rank', 'rank', 'trim|xss_clean');
        $this->form_validation->set_rules('url', 'url', 'trim|xss_clean');
        $this->form_validation->set_rules('aurl', 'aurl', 'trim|xss_clean');
        $this->form_validation->set_rules('isurl', 'isurl', 'trim|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');
        $this->form_validation->set_rules('status', 'status', 'trim|xss_clean');
        $this->form_validation->set_rules('style', 'style', 'trim|xss_clean');
        $this->form_validation->set_rules('litpic', 'litpic', 'trim|xss_clean');
        $this->form_validation->set_rules('content', 'content', 'trim|required');
        $this->form_validation->set_rules('list_name', 'list_name', 'trim|xss_clean');
        $this->form_validation->set_rules('content_name', 'content_name', 'trim|xss_clean');
        $this->form_validation->set_rules('sitedir', 'sitedir', 'trim|xss_clean');
        $this->form_validation->set_rules('visit_type', 'visit_type', 'trim|xss_clean');
        $this->form_validation->set_rules('index_tpl', 'index_tpl', 'trim|xss_clean');
        $this->form_validation->set_rules('list_tpl', 'list_tpl', 'trim|xss_clean');
        $this->form_validation->set_rules('content_tpl', 'content_tpl', 'trim|xss_clean');
        $this->form_validation->set_rules('title', 'title', 'trim|xss_clean');
        $this->form_validation->set_rules('keywords', 'keywords', 'trim|xss_clean');
		$this->form_validation->set_rules('description', 'description', 'trim|xss_clean');

		$data['addtime'] = time();
		$data['addip'] = $this->input->ip_address();
		$this->form_validation->set_message('required','{field}不能为空');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}

		$this->load->model('admin/Site_model');
		$res = $this->Site_model->siteAdd($data);
		$response = [
			'errcode'	=>	0,
			'msg'		=>	'添加成功'
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function siteEdit(){
		$id = $this->input->post('id');
		$this->load->model('admin/Site_model');
		$res = $this->Site_model->getSiteInfo($id);
		$res['addtime'] = $res['addtime']?date('Y-m-d H:i:s',$res['addtime']):'';
		$response = [
			'errcode'	=>	0,
			'data'		=>	$res
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}

	public function siteUpdate(){
		$data = $this->input->post(array('code','name','nid','pid','rank','url','aurl','isurl','order','status','style','litpic','content','list_name','content_name','sitedir','visit_type','index_tpl','list_tpl','content_tpl','title','keywords','description'));
		$id = $this->input->post('id');
		$this->form_validation->set_data($data);
        $this->form_validation->set_rules('code', 'code', 'required');
        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('nid', 'nid', 'required');
        $this->form_validation->set_rules('pid', 'pid', 'required');
        $this->form_validation->set_rules('rank', 'rank', 'trim|xss_clean');
        $this->form_validation->set_rules('url', 'url', 'trim|xss_clean');
        $this->form_validation->set_rules('aurl', 'aurl', 'trim|xss_clean');
        $this->form_validation->set_rules('isurl', 'isurl', 'trim|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');
        $this->form_validation->set_rules('status', 'status', 'trim|xss_clean');
        $this->form_validation->set_rules('style', 'style', 'trim|xss_clean');
        $this->form_validation->set_rules('litpic', 'litpic', 'trim|xss_clean');
        $this->form_validation->set_rules('content', 'content', 'trim|required');
        $this->form_validation->set_rules('list_name', 'list_name', 'trim|xss_clean');
        $this->form_validation->set_rules('content_name', 'content_name', 'trim|xss_clean');
        $this->form_validation->set_rules('sitedir', 'sitedir', 'trim|xss_clean');
        $this->form_validation->set_rules('visit_type', 'visit_type', 'trim|xss_clean');
        $this->form_validation->set_rules('index_tpl', 'index_tpl', 'trim|xss_clean');
        $this->form_validation->set_rules('list_tpl', 'list_tpl', 'trim|xss_clean');
        $this->form_validation->set_rules('content_tpl', 'content_tpl', 'trim|xss_clean');
        $this->form_validation->set_rules('title', 'title', 'trim|xss_clean');
        $this->form_validation->set_rules('keywords', 'keywords', 'trim|xss_clean');
		$this->form_validation->set_rules('description', 'description', 'trim|xss_clean');

		$this->form_validation->set_message('required','{field}不能为空');
		if($this->form_validation->run() === false){
			$error = $this->form_validation->error_array();
			$response['errcode'] = 1;
			$response['error'] = $error;
			die(json_encode($response,JSON_UNESCAPED_UNICODE));
		}

		$this->load->model('admin/Site_model');
		$res = $this->Site_model->siteUpdate($id,$data);
		$response = [
			'errcode'	=>	0,
			'msg'		=>	'更新成功'
		];
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}
	
}
