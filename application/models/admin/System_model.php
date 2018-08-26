<?php
	class System_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getSystemParams($search = '',$page = 0,$page_size = 0){
			if($search){
				$this->db->like('name',$search);
				$this->db->or_like('nid',$search);
				$this->db->or_like('value',$search);
			}
			$page and $this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('style asc');
			$query = $this->db->get('system');
			return $query->result_array();
		}

		public function getSystemParamsCount($search){
			if($search){
				$this->db->like('name',$search);
				$this->db->or_like('nid',$search);
				$this->db->or_like('value',$search);
			}
			$query = $this->db->count_all_results('system');
			return $query;
		}

		public function getSystemParamsById($id){
			$this->db->where('id',$id);
			$query = $this->db->get('system');
			return $query->row_array();
		}

		public function add($data){
			$query = $this->db->insert('system',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function update($id,$data){
			$this->db->where('id',$id);
			$query = $this->db->update('system',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}
		

		public function del($id){
			$this->db->where('id',$id);
			$query = $this->db->delete('system');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function checkName($name,$id){
			$this->db->where('name',$name);
			$this->db->where('id != ',$id);
			$query = $this->db->count_all_results('system');
			return $query;
		}

		public function checkNid($nid,$id){
			$this->db->where('nid',$nid);
			$this->db->where('id != ',$id);
			$query = $this->db->count_all_results('system');
			return $query;
		}

	}
?>