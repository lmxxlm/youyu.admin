<?php
	class Userinfo_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function companyUpdate($id,$data){
			$this->db->where('id',$id);
			$query = $this->db->update('userinfo',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

	}
?>
