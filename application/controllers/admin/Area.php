<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area extends Common {

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
		$this->load->model('admin/area_model');
	}

	public function getAllArea(){
		$this->load->driver('cache');
		if(!$area = $this->cache->file->get('area')){
			$res = $this->area_model->getAllArea();
			$area = unlimitedForLayer($res,'id','pid','child',0);
			$area = json_encode($area,JSON_UNESCAPED_UNICODE);
			$this->cache->file->save('area',$area);
		}
		echo $area;
	}
}
