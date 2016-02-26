<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Madmininstallation Model Class
 *
 * @author	Dejung Kang
 */
class Madmininstallation extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 지하철 정보를 추가하기 위해서 id값을 반환해 준다.
	 */
	function insert($param){
		$this->db->insert("installations",$param);
		return $this->db->insert_id();
	}

	function update($param, $id){
		$this->db->where("id",$id);
		$this->db->update("installations",$param);
	}

	/**
	 * 전체 지하철 역을 대상으로 근접 지하철역 3개를 찾는 함수
	 */
	function get_subway($lat,$lng){
		$this->db->select("id,name,lat,lng");
		$this->db->select("round(SQRT(POW(69.1 * (lat - ".$lat."), 2) + POW(69.1 * (".$lng." - lng) * COS(lat / 57.3), 2))*1.61,2) AS distance",false);
		$this->db->from("subway");
		$this->db->having('distance < 10'); 
		$this->db->order_by("distance","asc");
		$this->db->limit(3);
		$result = $this->db->get();
		return $result->result();
	}


	/**
	 * 매물 삭제
	 */
	function delete_installation($id){
		$this->db->where("id",$id);
		$this->db->delete("installations");
	}

	/**
	 * 수정 시 기존에 등록되어 있는 지하철 정보를 모두 삭제하여야 한다.
	 */
	function delete_subway($id){
		$this->db->where("installation_id",$id);
		$this->db->delete("installation_subway");
	}

	function insert_subway($id, $subway_id) {
		$param = Array(
					"installation_id"=>$id,
					"subway_id"=>$subway_id
				 );

		$this->db->insert("installation_subway",$param);	
	}

	/**
	 * 단일 매물 정보 가져오기
	 */
	function get($id){
		$this->db->select("installations.*");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun, ' ', address.dong) AS address_name", FALSE); 
		$this->db->select("members.name as member_name, members.email as member_email, members.phone as member_phone, members.tel as member_tel");
		$this->db->select("installation_schedule.name as schedule_name, installation_schedule.description as schedule_description, installation_schedule.date as schedule_date");
		$this->db->where("installations.id",$id);
		$this->db->from("installations");
		$this->db->join("members","installations.member_id=members.id");
		$this->db->join("address","address.id=installations.address_id");
		$this->db->join("installation_schedule","installation_schedule.installation_id=installations.id","left");
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * 매물 복사를 위해서 RAW데이터를 반환
	 */
	function get_raw($id){
		$this->db->where("id",$id);
		$this->db->from("installations");
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * 컨버전할 때 중복된 id값은 입력하지 않기 위해서 체크하기 위한 용도
	 */
	function get_count($id){
		$this->db->where("id",$id);
		return $this->db->count_all_results("installations");
	}

	function change($param, $id){
		$this->db->where("id", $id);
		$this->db->update("installations", $param);
	}


	/**
	 * 블로그 포스팅을 할 때마다 1씩 증가시킨다.
	 */
	function update_blog($id){
		$this->db->set("is_blog","is_blog+1",false);
		$this->db->where("id",$id);
		$this->db->update("installations");
	}

	function delete_thumb_image($installation_id){
		$param = Array(
			"thumb_name" => ""	
		);
		$this->db->where("id",$installation_id);
		$this->db->update("installations",$param);	
	}
}

/* End of file Madmininstallation.php */
/* Location: ./application/models/Madmininstallation.php */