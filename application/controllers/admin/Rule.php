<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rule extends Common {

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
		$this->load->model('admin/rule_model');
	}

	public function getUserRules(){
		$rules = isset($_SESSION['user_info']['rule'])?$_SESSION['user_info']['rule']:'';
		$rules = $rules?explode(',',$rules):[];
		echo json_encode($rules,JSON_UNESCAPED_UNICODE);
	}

	public function getSystemRules(){
		$res = $this->rule_model->getRules();
		$rules = unlimitedForLayer($res,'rule_id','parent_id','child',0);
		echo json_encode($rules,JSON_UNESCAPED_UNICODE);
	}

}
