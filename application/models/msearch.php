<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Msearch Model Class
 *
 * @author	Dejung Kang
 */
class Msearch extends CI_Model {

	private $config;
	
	public function __construct() {
		parent::__construct();
		$this->load->model("Mconfig");
		$this->config = $this->Mconfig->get();
	}

	function spot($search){
		if($search!=""){
			$this->db->select("spot.*");
			$this->db->like("name",$search);
			$result = $this->db->get("spot");
			return $result->result();	
		}
	}

	function parent_address($search){
		if($search!=""){
			$display_query = (!$this->config->COMPLETE_DISPLAY) ? "and products.is_finished='0'" : "";

			$this->db->select("parent_address.*");
			$this->db->select(" (select count(*) from products join members on products.member_id=members.id where members.valid='Y' and products.is_activated='1' and products.is_valid='1' ".$display_query." and products.address_id in(select id from address where address.parent_id=parent_address.id)) as cnt");
			$this->db->like("sido",$search);
			$this->db->or_like("gugun",$search);
			$this->db->limit(5);
			$this->db->order_by("cnt","desc");
			$result = $this->db->get("parent_address");
			return $result->result();	
		}
	}

	function address($search){
		if($search!=""){
			$display_query = (!$this->config->COMPLETE_DISPLAY) ? "and products.is_finished='0'" : "";

			$this->db->select("address.*");
			$this->db->select(" (select count(*) from products join members on products.member_id=members.id where members.valid='Y' and products.is_activated='1' and products.is_valid='1' ".$display_query." and products.address_id=address.id) as cnt");
			$this->db->like("dong",$search);
			$this->db->limit(5);
			$this->db->order_by("cnt","desc");
			$result = $this->db->get("address");
			return $result->result();	
		}
	}

	function subway($search){
		if($search!=""){
			$display_query = (!$this->config->COMPLETE_DISPLAY) ? "and products.is_finished='0'" : "";

			$this->db->select("subway.*");
			$this->db->select("(select count(*) from product_subway join products on products.id=product_subway.product_id join members on products.member_id=members.id where members.valid='Y' and products.is_activated='1' and products.is_valid='1' ".$display_query." and product_subway.subway_id=subway.id) as cnt");
			$this->db->like('name', $search);
			$this->db->limit(5);
			$this->db->order_by("cnt","desc");
			$query = $this->db->get("subway");
			return $query->result();
		}
	}

	/**
	 * 매물 번호 검색
	 */
	function product($search){
		if($search!=""){
			$this->db->select("products.id,products.title");
			$this->db->like('products.id',$search);
			$this->db->where("members.valid","Y");
			$this->db->limit(5);
			$this->db->join("members","products.member_id=members.id");
			$this->db->order_by("products.id","desc");
			$query = $this->db->get("products");
			return $query->result();
		}
	}

}

/* End of file msearch.php */
/* Location: ./application/models/msearch.php */