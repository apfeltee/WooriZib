<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Msubwayinstallation Model Class
 *
 * @author	Dejung Kang
 */
class Msubwayinstallation extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$query = $this->db->get("subway");
		return $query->row();
	}

	/**
	 * 매물 번호에 대한 지하철역 아이디를 넣는다.
	 */
	function insert($installation_id,$subway_id){
		$param= Array("installation_id"=>$installation_id,"subway_id"=>$subway_id);
		$this->db->insert("installation_subway",$param);
	}

	/**
	 * 모바일에서 호선별로 그루핑해서 보여주기 위해서 정의한 함수
	 */
	function get_registered_hosun(){
		$this->db->select("subway.hosun");
		$this->db->distinct();
		$this->db->join("installation_subway","installation_subway.subway_id=subway.id");
		$this->db->order_by("subway.hosun","asc");
		$query = $this->db->get("subway");
		return $query->result();
	}

	/**
	 * 모바일에서 현재 위치에서 가까운 세 개의 지하철 역을 보여주기 위함.
	 */
	function get_recent_list($lat, $lng){
		$this->db->select("subway.*");
		$this->db->select("round(SQRT(POW(69.1 * (subway.lat - ".$lat."), 2) + POW(69.1 * (".$lng." - subway.lng) * COS(lat / 57.3), 2))*1.61,2) AS distance",false);
		$this->db->distinct();
		$this->db->join("installation_subway","installation_subway.subway_id=subway.id");
		$this->db->limit("4");
		$this->db->order_by("distance","asc");
		$query = $this->db->get("subway");
		return $query->result();
	}

	function get_registered_list(){
		$this->db->select("subway.*");
		$this->db->distinct();
		$this->db->join("installation_subway","installation_subway.subway_id=subway.id");
		$query = $this->db->get("subway");
		return $query->result();
	}

	/**
	 * 해당 매물 번호의 지하철역 정보를 가져온다.
	 */
	function get_installation_list($installation_id){
		$this->db->from("installation_subway");
		$this->db->where("installation_id",$installation_id);
		$result = $this->db->get();
		return $result->result();
	}

	function get_local(){
		$this->db->select("subway.local");	
		$this->db->distinct();
		$this->db->join("installation_subway","installation_subway.subway_id=subway.id");
		$this->db->join("installations","installations.id=installation_subway.installation_id");
		$this->db->where("installations.is_activated",1);
		$this->db->order_by("subway.local","asc");
		$query = $this->db->get("subway");
		return $query->result();
	}

	function get_hosun($local){
		$this->db->select("distinct(subway.local)");
		$this->db->select("subway.hosun_id, subway.hosun");
		$this->db->where("subway.local",$local);
		$this->db->join("installation_subway","installation_subway.subway_id=subway.id");
		$this->db->join("installations","installations.id=installation_subway.installation_id");
		$this->db->where("installations.is_activated",1);
		$this->db->order_by("subway.hosun_id","asc");
		$this->db->from("subway");
		$result = $this->db->get();
		return $result->result();
	}

	function get_station($hosun){
		$this->db->distinct();
		$this->db->select("subway.*");
		$this->db->where("subway.hosun_id",$hosun);
		$this->db->join("installation_subway","installation_subway.subway_id=subway.id");
		$this->db->join("installations","installations.id=installation_subway.installation_id");
		$this->db->where("installations.is_activated",1);
		$this->db->order_by("subway.id","asc");
		$this->db->from("subway");
		$result = $this->db->get();
		return $result->result();
	}
}

/* End of file msubwayinstallation.php */
/* Location: ./application/models/msubwayinstallation.php */