<?php
	class User_type_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function userTypePageList($search,$page,$page_size){
			if($search){
				$this->db->where('name','$search');
			}
			$this->db->limit($page_size,($page-1)*$page_size);
			$query = $this->db->get('user_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function userTypeListCount($search){
			if($search){
				$this->db->where('name','$search');
			}
			$query = $this->db->count_all_results('user_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function allUserTypeList(){
			$this->db->select('type_id,name');
			$query = $this->db->get('user_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function checkTypeId($type_id){
			$this->db->where('type_id',$type_id);
			$query = $this->db->count_all_results('user_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function userTypeAdd($data){
			$query = $this->db->insert('user_type',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getUserTypeById($type_id){
			$this->db->select('type_id,name,purview rule,status,summary,remark');
			$this->db->where('type_id',$type_id);
			$query = $this->db->get('user_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function checkUserTypeName($name,$type_id){
			$this->db->where('name',$name);
			$this->db->where('type_id !=',$type_id);
			$query = $this->db->count_all_results('user_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function userTypeUpdate($type_id,$data){
			$this->db->where('type_id',$type_id);
			$query = $this->db->update('user_type',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function userTypeDel($type_id){
			$this->db->where('type_id',$type_id);
			$query = $this->db->delete('user_type');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

	}
?>
