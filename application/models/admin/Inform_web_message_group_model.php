<?php
	class Inform_web_message_group_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function batchAdd($data){
			$query = $this->db->insert_batch('inform_web_message_group',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

	}
?>