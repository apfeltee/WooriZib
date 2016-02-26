<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mgalleryinstallation Model Class
 *
 * @author	Dejung Kang
 */
class Mgalleryinstallation extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$result = $this->db->get("gallery_installation");
		return $result->row();	
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("gallery_installation",$param);
	}

	function get_main_gallery($id){
		$this->db->where("installation_id",$id);
		$this->db->order_by("sorting","asc");
		$this->db->limit(1);
		$result = $this->db->get("gallery_installation");
		return $result->row();	
	}

	function get_sorting($id){
		$this->db->select_max('sorting');
		$this->db->where("installation_id",$id);
		$result = $this->db->get("gallery_installation");
		return $result->row()->sorting;
	}

	/**
	 * 매물의 사진들을 가져온다.
	 */
	function get_list($id,$type="obj"){
		$this->db->where("installation_id",$id);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get("gallery_installation");
		if($type=="obj"){
			return $result->result();
		} else {
			return $result->result_array();
		}
	}

	function change_sorting($gallery_id,$sorting){
		$this->db->where("id",$gallery_id);
		$this->db->set("sorting",$sorting);
		$this->db->update("gallery_installation");
	}

	/**
	 * 매물 사진을 등록한다.
	 */
	 function insert($param){
		$this->db->insert("gallery_installation",$param);
	 }

	 function delete($pid, $gid){
		$this->db->where("installation_id",$pid);
		$this->db->where("id",$gid);
		$this->db->delete("gallery_installation");
	 }

	 function delete_installation($id){
		$this->db->where("installation_id",$id);
		$this->db->delete("gallery_installation");	 
	 }

	/**
	 * 대표이미지가 삭제 되었을 경우 순서를 한칸씩 당긴다.
	 */
	function sorting_refresh($installation_id){
		$this->db->set("sorting","sorting-1",false);
		$this->db->where("installation_id",$installation_id);
		$this->db->update("gallery_installation");
	}
}

/* End of file mgalleryinstallation.php */
/* Location: ./application/models/mgalleryinstallation.php */