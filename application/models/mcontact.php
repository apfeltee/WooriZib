<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mcontact Model Class
 *
 * @author	Dejung Kang
 */
class Mcontact extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("contacts");
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * 로그인 체크할 때 사용한다.
	 */
	function get_array($id){
		$this->db->where("id",$id);
		$this->db->from("contacts");
		$query = $this->db->get();
		return $query->row_array();
	}

	function insert($param){
		$this->db->insert("contacts",$param);
		return $this->db->insert_id();
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("contacts",$param);
	}

	/**
	 * 로그인 체크
	 *
	 */
	function check_login($id,$pw){
			$where = Array("email"=>$id,"pw"=>$pw);
			$this->db->where("secession","N");
			$query = $this->db->get_where("contacts",$where);
			if(count($query->row())>0){
				return $query->row();
			} else {
				return null;
			}
	}

	/**
	 * 이메일 존재 여부
	 */
	function have_email($email=""){
		$this->db->where("email",$email);
		$this->db->where("secession","N");
		$result = $this->db->count_all_results("contacts");
		return $result;
	}

	/**
	 * 회원 정보 수정할 때 자신의 이메일을 제외하고 다른 사람이 이메일을 사용하고 있는지 여부를 체크한다.
	 */
	function have_email_profile($id, $email=""){
		$this->db->where("email",$email);
		$this->db->where("id !=",$id);
		$result = $this->db->count_all_results("contacts");
		return $result;
	}
	
	function get_from_email($email){
		$this->db->where("email",$email);
		$this->db->from("contacts");
		$query = $this->db->get();
		return $query->row();
	}

	function password_init($email, $pw){
		$param = Array(
			"pw" => $pw	
		);
		$this->db->where("email",$email);
		$this->db->update("contacts",$param);		
	}

	/**
	 * 전체 문의 수 반환
	 */
	function get_total_count($group_id,$keyword){
		if($group_id!="all"){
			$this->db->where("group_id",$group_id);
		}
		if($keyword!=""){
			$this->db->like("name",$keyword);
			$this->db->or_like("organization",$keyword);
			$this->db->or_like("role",$keyword);
			$this->db->or_like("email",$keyword);
			$this->db->or_like("address",$keyword);
			$this->db->or_like("phone",$keyword);
			$this->db->or_like("homepage",$keyword);
			$this->db->or_like("background",$keyword);
		}
		$query = $this->db->get("contacts");
		return $query->num_rows(); 
	}

	function get_list($group_id, $keyword, $sort_name, $order_by, $num, $offset){

		$this->db->select("contacts.*");
		$this->db->select(" (select MAX(regdate) from contacts_history where contacts_id=contacts.id ) as history_date");
		$this->db->select("(select count(*) from contacts_product where contacts_id=contacts.id) as cnt");

		if($group_id!="all"){
			$this->db->where("group_id",$group_id);
		}
		if($keyword!=""){
			$this->db->like("name",$keyword);
			$this->db->or_like("organization",$keyword);
			$this->db->or_like("role",$keyword);
			$this->db->or_like("email",$keyword);
			$this->db->or_like("address",$keyword);
			$this->db->or_like("phone",$keyword);
			$this->db->or_like("homepage",$keyword);
			$this->db->or_like("background",$keyword);
		}

		if($sort_name && $order_by){
			$this->db->order_by($sort_name, $order_by);
		}
		else{
			$this->db->order_by("contacts.regdate","desc");
		}

		$query = $this->db->get("contacts",$num, $offset);
		return $query->result_array();
	}

	function get_list_obj($group_id="",$count=false){
		if($group_id){
			$this->db->where("group_id",$group_id);
		}
		$this->db->order_by("regdate","desc");
		$query = $this->db->get("contacts");
		if($count){
			return $query->num_rows();
		}
		else{
			return $query->result();
		}
	}

	function get_list_in($contact_ids){
		$this->db->where_in("id",$contact_ids);
		$query = $this->db->get("contacts");
		return $query->result();		
	}

	/**
	 * 관리자 매물 목록에서 매물에 연계된 고객 목록을 가져오는 기능
	 *
	 */
	function get_list_by_product($product_id){
		$this->db->select("contacts.*");
		$this->db->where("contacts_product.product_id",$product_id);
		$this->db->join("contacts","contacts.id=contacts_product.contacts_id");
		$this->db->from("contacts_product");
		$result = $this->db->get();
		return $result->result();
	}

	function contact_member_list($search){
		if($search){
			$this->db->where("name like '%".$search."%'", NULL, FALSE);
		}
		$query = $this->db->get("contacts");
		return $query->result();
	}

	/**
	 * 그룹을 삭제할 때에는 삭제할 그룹으로 되어 있는 연락 정보를 다른 연락정보로 대체한다.
	 */
	function update_group($source, $target){
		$param = Array("group_id"=>$target);
		$this->db->where("group_id",$source);
		$this->db->update("contacts",$param);
	}

	/**
	 * 연락 정보 삭제
	 */
	function delete_contact($id){
		$this->db->where("id",$id);
		$this->db->delete("contacts");
	}

	function change_area_contact($delete_id,$change_id){
		$this->db->set("member_id",$change_id);
		$this->db->where("member_id",$delete_id);
		$this->db->update("contacts");
	}
}

/* End of file mcontact.php */
/* Location: ./application/models/mcontact.php */