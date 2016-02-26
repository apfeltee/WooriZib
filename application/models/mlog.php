<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mlog Model Class
 *
 * @author	Dejung Kang
 */
class Mlog extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function add($param){
		$bot_kind = '/(DotBot|bingbot|spbot|MJ12bot|ZumBot|Yeti|bot.html|bot.php)/';
		if(!preg_match($bot_kind, $param['user_agent'])) {
			$this->db->insert("log_site",$param);
		}
	}

	function add_blog($param){
		$this->db->insert("log_blog",$param);
	}

	function get_total($session_id){
		$this->db->distinct();
		$this->db->select("products.*");
		$this->db->where("log_site.session_id",$session_id);
		$this->db->join("log_site","log_site.data_id=products.id");
		$this->db->order_by("log_site.date","desc");
		return $this->db->count_all_results("products");	
	}

	/**
	 * 본 매물 내역을 가져오는 기능이다. 
	 *
	 */
	function get_list($session_id, $num, $offset){
		$this->db->distinct();
		$this->db->select("products.*");
		$this->db->select("gallery.filename as thumb_name, gallery.id as gallery_id");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);		
		$this->db->select("members.name as member_name, members.phone as member_phone, members.profile as member_profile");	
		$this->db->where("log_site.session_id",$session_id);
		$this->db->join("log_site","log_site.data_id=products.id and log_site.type='product'");
		$this->db->join("address","address.id=products.address_id");
		$this->db->where("members.valid","Y");
		$this->db->join("members","members.id=products.member_id");
		$this->db->join("gallery","gallery.product_id=products.id and gallery.sorting=1","left");
		$this->db->order_by("log_site.date","desc");
		$query = $this->db->get("products",$num, $offset);
		return $query->result();
	}

	/**
	 * 본 분양 매물 정보를 가져온다.
	 *
	 */
	function get_list_installation($session_id, $num, $offset){
		$this->db->distinct();
		$this->db->select("installations.*");
		$this->db->select("gallery_installation.filename as thumb_name, gallery_installation.id as gallery_id");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);		
		$this->db->select("members.name as member_name, members.phone as member_phone, members.profile as member_profile");	
		$this->db->where("log_site.session_id",$session_id);
		$this->db->join("log_site","log_site.data_id=installations.id and log_site.type='installation'");
		$this->db->join("address","address.id=installations.address_id");
		$this->db->where("members.valid","Y");
		$this->db->join("members","members.id=installations.member_id");
		$this->db->join("gallery_installation","gallery_installation.installation_id=installations.id and gallery_installation.sorting=1","left");
		$this->db->order_by("log_site.date","desc");
		$query = $this->db->get("installations",$num, $offset);
		return $query->result();
	}
	
	function add_call($param){
		$this->db->insert("call_log",$param);
	}


	function get_site_total($where=""){
		$this->db->select("log_site.*");

		if($where){
			if(isset($where['mobile'])) $this->db->where("mobile",$where['mobile']);
			if(isset($where['date1'])) $this->db->where("DATE_FORMAT(date, '%Y-%m-%d') >= '".$where['date1']."'");
			if(isset($where['date2'])) $this->db->where("DATE_FORMAT(date, '%Y-%m-%d') <= '".$where['date2']."'");
		}

		return $this->db->count_all_results("log_site");
	}

	function get_site_log($where="",$num="", $offset=""){
		$this->db->select("log_site.*");
		$this->db->order_by("date","desc");

		if($where){
			if(isset($where['mobile'])) $this->db->where("mobile",$where['mobile']);
			if(isset($where['date1'])) $this->db->where("DATE_FORMAT(date, '%Y-%m-%d') >= '".$where['date1']."'");
			if(isset($where['date2'])) $this->db->where("DATE_FORMAT(date, '%Y-%m-%d') <= '".$where['date2']."'");
		}

		$query = $this->db->get("log_site",$num, $offset);

		return $query->result();
	}

	function get_site_product_log(){
		$this->db->select("log_site.data_id,log_site.date");
		$this->db->select("products.title");
		$this->db->join("products","products.id=log_site.data_id");
		$this->db->where("log_site.type","product");
		$this->db->where("DATE_FORMAT(log_site.date, '%Y-%m-%d') = CURRENT_DATE()");
		$this->db->order_by("log_site.date","desc");

		$query = $this->db->get("log_site");

		return $query->result();
	}

	function get_call_total($where=""){
		$this->db->select("call_log.id, call_log.member, call_log.product_id");
		$this->db->join("products","call_log.product_id=products.id");
		$this->db->join("members","call_log.member=members.id");

		if($where){
			if(isset($where['member'])) $this->db->where("call_log.member",$where['member']);
			if(isset($where['date1'])) $this->db->where("DATE_FORMAT(call_log.date, '%Y-%m-%d') >= '".$where['date1']."'");
			if(isset($where['date2'])) $this->db->where("DATE_FORMAT(call_log.date, '%Y-%m-%d') <= '".$where['date2']."'");
		}

		return $this->db->count_all_results("call_log");
	}

	function get_call_log($today="",$where="",$num="", $offset=""){
		$this->db->select("call_log.id, call_log.member, call_log.product_id, call_log.user_agent");
		$this->db->select("DATE_FORMAT(call_log.date, '%y-%m-%d %h:%i:%s') as date",FALSE);
		$this->db->select("products.title");
		$this->db->select("members.name");
		$this->db->join("products","call_log.product_id=products.id");
		$this->db->join("members","call_log.member=members.id");
		$this->db->order_by("call_log.date","desc");

		if($where){
			if(isset($where['member'])) $this->db->where("call_log.member",$where['member']);
			if(isset($where['date1'])) $this->db->where("DATE_FORMAT(call_log.date, '%Y-%m-%d') >= '".$where['date1']."'");
			if(isset($where['date2'])) $this->db->where("DATE_FORMAT(call_log.date, '%Y-%m-%d') <= '".$where['date2']."'");
		}

		if($today){
			$this->db->where("members.type","admin");
			$this->db->where("DATE_FORMAT(call_log.date, '%Y-%m-%d') = CURRENT_DATE()");
			$query = $this->db->get("call_log");
		}
		else{
			$query = $this->db->get("call_log",$num, $offset);
		}

		return $query->result();
	}

	function call_log_members($today=""){
		$this->db->distinct();
		$this->db->select("call_log.member");
		$this->db->select("members.name");
		$this->db->join("members","call_log.member=members.id");

		if($today){
			$this->db->where("members.type","admin");
			$this->db->where("DATE_FORMAT(call_log.date, '%Y-%m-%d') = CURRENT_DATE()");
		}
		$this->db->order_by("members.id","asc");
		$query = $this->db->get("call_log");
		return $query->result();
	}

	function init_db($sql){
		foreach (explode(";", $sql) as $sql){
			$sql = trim($sql);
			if($sql){
			//print_r($sql);
			$this->db->query($sql);
			} 
		} 
	}

	function init_db_sample($sql){
		foreach (explode("--dungzi--", $sql) as $sql){
			$sql = trim($sql);
			if($sql){
			//print_r($sql);
			$this->db->query($sql);
			} 
		} 
	}
}

/* End of file mlog.php */
/* Location: ./application/models/mlog.php */