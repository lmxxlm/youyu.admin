<?php
	class Power_node_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function findAllbyPath($node_id){
			$this->db->select('node_id,code,name,parentid,inpath,node_order,node_url,icon');
			$this->db->like('inpath',$node_id,'after');
			$query = $this->db->get('power_node');
			$res = $query->result_array();
			return $res;
		}

	}
?>