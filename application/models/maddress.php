<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Maddress Model Class
 *
 * @author	Dejung Kang
 */
class Maddress extends CI_Model {

	var $type = "admin";

	private $config;
	
	public function __construct() {
		parent::__construct();
		$this->load->model("Mconfig");
		$this->config = $this->Mconfig->get();
	}


	function get($id){
		$this->db->where("id",$id);
		$query = $this->db->get("address");
		return $query->row();
	}

	function get_parent($id){
		$this->db->where("id",$id);
		$query = $this->db->get("parent_address");
		return $query->row();
	}
	function set_type($t){
		$this->type = $t;
	}

	function get_sido(){
		$this->db->distinct();
		$this->db->select("sido");
		if($this->type=="front"){
			// $this->db->where('`id` IN (SELECT `address_id` FROM `products` WHERE is_activated = 1)', NULL, FALSE);
			$this->db->where('`id` IN (SELECT `id` FROM `address`)', NULL, FALSE);
		} else if($this->type=="admin"){
				// $this->db->where('`id` IN (SELECT `address_id` FROM `products`)', NULL, FALSE);
				$this->db->where('`id` IN (SELECT `id` FROM `address`)', NULL, FALSE);
		}
		$this->db->from("address");
		$result = $this->db->get();
		return $result->result();
	}

	function get_gugun($sido){
		$this->db->select("distinct(parent_id)");
		$this->db->select("gugun");
		$this->db->from("address");
		$this->db->where("sido",$sido);
		if($this->type=="front"){
			// $this->db->where('`id` IN (SELECT `address_id` FROM `products` WHERE is_activated = 1)', NULL, FALSE);
			$this->db->where('`id` IN (SELECT `id` FROM `address`)', NULL, FALSE);
		} else if($this->type=="admin"){
				// $this->db->where('`id` IN (SELECT `address_id` FROM `products`)', NULL, FALSE);
				$this->db->where('`id` IN (SELECT `id` FROM `address`)', NULL, FALSE);
		}
		$this->db->order_by("gugun","asc");
		$result = $this->db->get();
		return $result->result();
	}


	/** 
	 * 광고 키워드를 위하여 동 목록을 가져온다.
	 */
	function get_ad_dong(){
		$this->db->from("address");
		$this->db->where('`id` IN (SELECT `address_id` FROM `products` WHERE is_activated = 1)', NULL, FALSE);
		$result = $this->db->get();
		return $result->result();
	}

	function get_dong($parent_id){
		$this->db->select("id,sido,gugun,dong,lat,lng");
		$this->db->from("address");
		$this->db->where("parent_id",$parent_id);

		if($this->type=="front"){
			// $this->db->where('`id` IN (SELECT `address_id` FROM `products` WHERE is_activated = 1)', NULL, FALSE);
			$this->db->where('`id` IN (SELECT `id` FROM `address`)', NULL, FALSE);
		} else if($this->type=="admin"){
				// $this->db->where('`id` IN (SELECT `address_id` FROM `products`)', NULL, FALSE);
				$this->db->where('`id` IN (SELECT `id` FROM `address`)', NULL, FALSE);
		}
		$this->db->order_by("dong","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_address($sido, $gugun, $dong){
		$this->db->from("address");
		$this->db->where("sido",$sido);
		$this->db->where("gugun",$gugun);
		$this->db->where("dong",$dong);
		$result = $this->db->get();
		return $result->row();
	}

	function get_address_like($sido, $gugun, $dong){
		$this->db->like("sido",$sido);
		$this->db->like("gugun",$gugun);
		$this->db->like("dong",$dong);
		$result = $this->db->get("address");
		return $result->row();
	}

	/**
	 * 검색에서 구군정보를 이용해 좌표로 이동시키기 위해서 좌표값을 가져오는 목적
	 */
	function get_gugun_coord($sido,$gugun){
		$this->db->select_avg("lat");
		$this->db->select_avg("lng");
		$this->db->from("address");
		$this->db->where("sido",$sido);
		$this->db->where("gugun",$gugun);
		$result = $this->db->get();
		return $result->row();	
	}


	/**
	 * 지하철역의 매물 수를 반환해 주는데 products에서 is_activated 값이 1일 경우에만 계산해야 해서 수정
	 * 다시 조인을 해야하기 때문에 어쩔 수가 없다.
	 * 
	 */
	function get_subway($search){
		$display_query = (!$this->config->COMPLETE_DISPLAY) ? "and products.is_finished='0'" : "";

		$this->db->select("subway.*");
		$this->db->select(" (select count(*) from product_subway join products on products.id=product_subway.product_id where products.is_activated='1' and products.is_valid='1' ".$display_query." and product_subway.subway_id=subway.id) as cnt");
		$this->db->like('name', $search); 
		$query = $this->db->get("subway");
		return $query->result();
	}

	function get_bound($param){
		$this->db->where($param);
		$this->db->select("max(lat) as lat_max , max(lng) as lng_max, min(lat) as lat_min, min(lng) as lng_min",FALSE);
		$query = $this->db->get("address");
		return $query->row();
	}

	/**
	 * 모바일앱에서는 sido선택하고 그 다음으로 gugun 선택하고 그 다음으로 동을 선택하여 지역을 선택하는 기능을 구현하고자 한다.
	 * 기존에는 gugun과 dong의 정보만을 가져왔다면 sido정보도 추가한다.
	 * 전체 매물 갯수를 보여주는 이유는 사용자가 선택을 하였을 때 볼 수 있는 매물이 갯수를 알려줌으로써 헛수고를 하지 않도록 하기 위한 목적이다.
	 * 
	 */
	function get_local($type){
		
		$this->db->select("products.address_id");

		if($type=="sido"){
			$this->db->select("address.sido");
			$this->db->group_by("address.sido");
		} else if($type=="dong"){
			$this->db->select("address.parent_id, address.lat as parent_lat,address.lng as parent_lng,address.sido,address.gugun,address.dong");
			$this->db->select("concat(address.sido,' ',address.gugun,' ',address.dong) as title, address.dong as label",false);
			$this->db->group_by("address.dong");
		} else if($type=="gugun"){
			$this->db->select("address.parent_id,address.sido,address.gugun");
			$this->db->select("concat(address.sido,' ',address.gugun) as title, address.gugun as label",false);
			$this->db->select("(select lat from parent_address where id=address.parent_id) as parent_lat");
			$this->db->select("(select lng from parent_address where id=address.parent_id) as parent_lng");
			$this->db->select("(select area from parent_address where id=address.parent_id) as parent_area");
			$this->db->group_by("address.gugun");
		} else {
			exit;
		}

		$this->db->select("count(*) as cnt");
		$this->db->from("products");
		$this->db->where("products.is_activated","1");
		$this->db->where("products.is_valid","1");
		if(!$this->config->COMPLETE_DISPLAY){
			$this->db->where("products.is_finished","0");
		}
		$this->db->join("address","address.id=products.address_id");
		$result = $this->db->get();
		return $result->result();
	}

	/**
	 * 모바일에서 현재 위치에서 가까운 네 개의 동을 보여주기 위함.
	 */
	function get_recent_list($lat, $lng){

		$this->db->select("round(SQRT(POW(69.1 * (address.lat - ".$lat."), 2) + POW(69.1 * (".$lng." - address.lng) * COS(address.lat / 57.3), 2))*1.61,2) AS distance",false);
		$this->db->select("address.id as address_id,address.parent_id, address.lat as parent_lat,address.lng as parent_lng,address.sido,address.gugun,address.dong");
		$this->db->select("concat(address.sido,' ',address.gugun,' ',address.dong) as title, address.dong as label",false);
		$this->db->group_by("address.dong");

		$this->db->limit("4");
		$this->db->order_by("distance","asc");
		$this->db->select("count(*) as cnt");
		$this->db->from("products");
		$this->db->where("products.is_activated","1");
		$this->db->where("products.is_valid","1");
		if(!$this->config->COMPLETE_DISPLAY){
			$this->db->where("products.is_finished","0");
		}
		$this->db->join("address","address.id=products.address_id");
		$result = $this->db->get();
		return $result->result();
	}

	function insert_danzi($param){

		$this->db->insert("danzi",$param);
	}

	function get_sido_building(){
		$this->db->distinct();
		$this->db->select("sido");
		$this->db->where('`id` IN (SELECT `address_id` FROM `building`)', NULL, FALSE);
		$this->db->from("address");
		$result = $this->db->get();
		return $result->result();
	}

	function get_gugun_building($sido){
		$this->db->select("distinct(parent_id)");
		$this->db->select("gugun");
		$this->db->from("address");
		$this->db->where("sido",$sido);
		$this->db->where('`id` IN (SELECT `address_id` FROM `building`)', NULL, FALSE);
		$this->db->order_by("gugun","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_dong_building($parent_id){
		$this->db->select("id,sido,gugun,dong");
		$this->db->from("address");
		$this->db->where("parent_id",$parent_id);
		$this->db->where('`id` IN (SELECT `address_id` FROM `building`)', NULL, FALSE);
		$this->db->order_by("dong","asc");
		$result = $this->db->get();
		return $result->result();
	}

	/**
	 
	 주소 좌표 업데이트 벌크 작업 용으로 남김
	 
	function get_no(){
		$this->db->where("lat","0");
		$this->db->from("address");
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	function update_address($param,$id){
		$this->db->where("id",$id);
		$this->db->update("address",$param);
	}

	**/

}

/* End of file msubway.php */
/* Location: ./application/models/msubway.php */