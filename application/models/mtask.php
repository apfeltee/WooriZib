<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mmemo Model Class
 *
 * @author	Dejung Kang
 */
class Mtask extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("contacts_task");
		$query = $this->db->get();
		return $query->row();
	}

	function insert($param){
		$this->db->insert("contacts_task",$param);
		return $this->db->insert_id();
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("contacts_task",$param);
	}

	function get_list($id){
		$this->db->select("contacts_task.*,members.name,members.profile");
		$this->db->where("contacts_id",$id);
		$this->db->join("members","members.id=contacts_task.member_id");
		$this->db->order_by("regdate","desc");
		$query = $this->db->get("contacts_task");
		return $query->result();
	}

	/**
	 * 해당 담당자의 미완료 업무 내역을 가져온다.
	 * 완료된 업무까지 모두 가져오려면 양이 어마어마하게 많을 것이기 때문이다. 그리고 굳이 홈기능을 하는 페이지에서 굳이 다 볼 필요는 없다.
	 */
	function get_list_by_member($member_id){
		$this->db->select("contacts_task.*");
		$this->db->select("contacts.name");
		$this->db->where("contacts_task.member_id",$member_id);
		$this->db->where("finished","N");
		$this->db->join("contacts","contacts.id=contacts_task.contacts_id");
		$this->db->order_by("regdate","desc");
		$query = $this->db->get("contacts_task");
		return $query->result();
	}

	/**
	 * delete memo
	 */
	function delete_task($id){
		$this->db->where("id",$id);
		$this->db->delete("contacts_task");
	}

	function delete_task_contacts_id($contacts_id){
		$this->db->where("contacts_id",$contacts_id);
		$this->db->delete("contacts_task");
	}

	function get_month_task($start,$end){
		$this->db->where("regdate <",$end);
		$this->db->where("regdate >=",$start);
		$query = $this->db->get("contacts_task");
		return $query->result();		
	}

}

/* End of file mtask.php */
/* Location: ./application/models/mtask.php */