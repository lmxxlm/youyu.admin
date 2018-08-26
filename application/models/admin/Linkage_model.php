
<?php
	class Linkage_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function getConstsByTypeId($type_id,$search,$page,$page_size){
			$this->db->where('type_id',$type_id);
			$search and $this->db->like('name',$search,'both');
			$this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('id desc');
			$query = $this->db->get('linkage');
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getConstsCountByTypeId($type_id,$search){
			$this->db->where('type_id',$type_id);
			$search and $this->db->like('name',$search,'both');
			$query = $this->db->count_all_results('linkage');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getConstsByNid($nid){
			$this->db->select('a.*');
			$this->db->where('b.nid = ',$nid);
			$this->db->from('linkage a');
			$this->db->join('linkage_type b','a.type_id = b.id','inner');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function getConstsByNidAndId($nid,$id){
			$this->db->select('a.*');
			$this->db->where('a.id = ',$id);
			$this->db->where('b.nid = ',$nid);
			$this->db->from('linkage a');
			$this->db->join('linkage_type b','a.type_id = b.id','inner');
			$query = $this->db->count_all_results();
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getConstById($id){
			$this->db->where('id',$id);
			$query = $this->db->get('linkage');
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

		public function addConst($data){
			$query = $this->db->insert('linkage',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function updateConst($id,$data){
			$this->db->where('id',$id);
			$query = $this->db->update('linkage',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function delConst($id){
			$this->db->where('id',$id);
			$query = $this->db->delete('linkage');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function check_const_name_add($name,$type_id){
			$this->db->where('name',$name);
			$this->db->where('type_id',$type_id);
			$query = $this->db->count_all_results('linkage');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function check_const_value_add($value,$type_id){
			$this->db->where('value',$value);
			$this->db->where('type_id',$type_id);
			$query = $this->db->count_all_results('linkage');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function check_const_name_update($name,$id,$type_id){
			$this->db->where('name',$name);
			$this->db->where('type_id',$type_id);
			$this->db->where('id !=',$id);
			$query = $this->db->count_all_results('linkage');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function check_const_value_update($value,$id,$type_id){
			$this->db->where('value',$value);
			$this->db->where('type_id',$type_id);
			$this->db->where('id !=',$id);
			$query = $this->db->count_all_results('linkage');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

	}
?>