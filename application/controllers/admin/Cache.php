<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cache extends Common {

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

	public function cacheDel(){
		$name = $this->input->post('name');
		$response = [];
		if($name){
			$cache = str_replace('-','/',$name);
			$path = $this->config->item('system_path').$this->config->item('system_code_path').'/cache/'.$cache;
			if(file_exists($path)){
				if(unlink($path)){
					$response = [
						'errcode'	=>	0,
						'msg'		=>	'删除成功'
					];
				}else{
					$response = [
						'errcode'	=>	0,
						'msg'		=>	'删除失败'
					];
				}
			}else{
				$response = [
					'errcode'	=>	0,
					'msg'		=>	'没有缓存文件或缓存路径错误（'.$path.'）'
				];
			}
		}
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
	}
	
}
