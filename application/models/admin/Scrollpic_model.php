<?php
	class Scrollpic_model extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			// Your own constructor code
		}

		public function pictureList($search,$page,$page_size){
			$this->db->select('a.*,b.name type_name');
			$this->db->from('scrollpic a');
			$this->db->join('linkage b','a.type_id = b.id','left');
			if($search){
				$this->db->like('a.url',$search);
				$this->db->or_like('a.name',$search);
				$this->db->or_like('a.remind_name',$search);
			}
			$this->db->limit($page_size,($page-1)*$page_size);
			$this->db->order_by('id desc');
			$query = $this->db->get();
			log_message('info','sql:'.$this->db->last_query());
			return $query->result_array();
		}

		public function pictureListCount($search){
			if($search){
				$this->db->like('url',$search);
				$this->db->or_like('name',$search);
				$this->db->or_like('remind_name',$search);
			}
			$query = $this->db->count_all_results('scrollpic');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function add($data){
			$query = $this->db->insert('scrollpic',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function update($id,$data){
			$this->db->where('id',$id);
			$query = $this->db->update('scrollpic',$data);
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function del($id){
			$this->db->where('id',$id);
			$query = $this->db->delete('scrollpic');
			log_message('info','sql:'.$this->db->last_query());
			return $query;
		}

		public function getPictureInfo($id){
			$this->db->select('id,status,order,type_id,url,name,remind_name,href,show_time_begin,show_time_end');
			$this->db->where('id',$id);
			$query = $this->db->get('scrollpic');
			log_message('info','sql:'.$this->db->last_query());
			return $query->row_array();
		}

	}
?>