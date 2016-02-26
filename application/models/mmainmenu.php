<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mmainmenu Model Class
 *
 * @author	Dejung Kang
 */
class Mmainmenu extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 전체 메뉴가 표시(관리 목적)
	 */
	function get_list(){
		$this->db->from("mainmenu");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	/**
	 * 사용 메뉴가 표시(메뉴 표시 목적)
	 * 메뉴는 총 10개까지로 한다. (안 넣으면 LIMIT 1이 적용된다. 다른 데서 설정한게 여기서 먹는 듯...)
	 */
	function get_list_valid(){
		$this->db->where("flag","Y");
		$this->db->order_by("sorting","asc");
		$this->db->limit(10);
		$result = $this->db->get("mainmenu");
		return $result->result();
	}

	/**
	 * 메뉴 정보를 수정한다.
	 */
	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("mainmenu",$param);
	}

	/**
	 * 유형별 메뉴명을 가져온다.
	 */
	function get_name($type){
		$this->db->where("type",$type);
		$query = $this->db->get("mainmenu");
		return $query->row()->title;
	}

}

/* End of file mmainmenu.php */
/* Location: ./application/models/mmainmenu.php */