<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends Common {

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
		$this->load->model('admin/menu_model');
	}

	public function getMenu(){
		$rule = isset($_SESSION['user_info']['rule'])?$_SESSION['user_info']['rule']:'';
		$rule = explode(',',$rule);
		$all_menu = $this->menu_model->getAllMenu();
		$menu = [];
		$new_menu = [];
		foreach($all_menu as $v){
			if(!in_array($v['rule'],$rule)) continue;
			$new_menu[$v['menu_id']] = $v;
		}
		$menu = unlimitedForLayer($new_menu,'menu_id','parent_id','child',0);
		foreach($menu as &$v){
			$orderby = array_column($v['child'],'orderby');
			array_multisort($orderby,SORT_NUMERIC,SORT_ASC,$v['child']);
		}
		echo json_encode($menu,JSON_UNESCAPED_UNICODE);
	}

}
