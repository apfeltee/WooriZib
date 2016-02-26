<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mmenu Model Class
 *
 * @author	Dejung Kang
 */
class Mmenu extends CI_Model {

	private $config;
	
	public function __construct() {
		parent::__construct();
		$this->load->model("Mconfig");
		$this->config = $this->Mconfig->get();
	}

	function get_area(){
		$this->db->select("address.id, address.parent_id, address.gugun, address.dong, address.lat, address.lng");
		$this->db->select("count(*) as cnt");
		$this->db->group_by("address.dong");
		$this->db->from("products");
		$this->db->where("is_activated","1");
		$this->db->where("is_valid","1");
		if(!$this->config->COMPLETE_DISPLAY){
			$this->db->where("is_finished","0");
		}
		$this->db->where("members.valid","Y");
		$this->db->join("members","products.member_id=members.id");
		$this->db->join("address","address.id=products.address_id");
		$this->db->order_by("address.parent_id","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_theme(){
		$this->db->from("theme");
		$result = $this->db->get();
		return $result->result();	
	}

	function get_spot(){
		$this->db->from("spot");
		$result = $this->db->get();
		return $result->result();
	}

	function get_news(){
		$this->db->from("news_category");
		$this->db->where("opened","Y");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_gallery(){
		$this->db->from("component_portfolio_category");
		$this->db->where("opened","Y");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_member(){
		$this->db->select("members.*");
		$this->db->where("email !=","webplug@gmail.com");
		$this->db->where("profile !=","");
		$this->db->order_by("date","desc");
		$query = $this->db->get("members");
		return $query->result();
	}

	function get_subway(){
		$this->db->select("subway.*");
		$this->db->distinct();
		$this->db->join("product_subway","product_subway.subway_id=subway.id");
		$this->db->order_by("subway.hosun","asc");
		$query = $this->db->get("subway");
		return $query->result();
	}
}

/* End of file mmenu.php */
/* Location: ./application/models/mmenu.php */