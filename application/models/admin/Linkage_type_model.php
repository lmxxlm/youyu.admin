<?php
	class Linkage_type_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getSystemConstsType($search,$page,$page_size){
			if($search){
				$this->db->like('name',$search,'both');
				$this->db->like('nid',$search,'both');
			}
			$this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('id desc');
			$query = $this->db->get('linkage_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getSystemConstsTypeCount($search){
			if($search){
				$this->db->like('name',$search,'both');
				$this->db->like('nid',$search,'both');
			}
			$query = $this->db->count_all_results('linkage_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getSystemConstTypeById($id){
			$this->db->where('id',$id);
			$query = $this->db->get('linkage_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function add($data){
			$query = $this->db->insert('linkage_type',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function update($id,$data){
			$this->db->where('id',$id);
			$query = $this->db->update('linkage_type',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function check_name($name,$id){
			$this->db->where('name',$name);
			$this->db->where('id != ',$id);
			$query = $this->db->count_all_results('linkage_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function check_nid($nid,$id){
			$this->db->where('nid',$nid);
			$this->db->where('id != ',$id);
			$query = $this->db->count_all_results('linkage_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

	}
?>