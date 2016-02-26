<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Minstallation Model Class
 *
 * @author	Dejung Kang
 */
class Minstallation extends CI_Model {

	private $config;
	
	public function __construct() {
		parent::__construct();
		$this->load->model("Mconfig");
		$this->config = $this->Mconfig->get();
	}

	/**
	 * 조회수 증가
	 */
	function view($id){
		$this->db->where("id",$id);
		$this->db->set("viewcnt","viewcnt+1",false);
		$this->db->update("installations");
	}

	function get($id){
		$this->db->select("installations.*");
		$this->db->select("members.name as member_name, members.email as member_email");
		$this->db->select("gallery_installation.filename as thumb_name, gallery_installation.id as gallery_id");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);
		$this->db->select("address.parent_id as parent_id");
		$this->db->where("installations.id",$id);
		$this->db->where("members.valid","Y");

		$this->db->join("members","installations.member_id=members.id");
		$this->db->join("address","address.id=installations.address_id");
		$this->db->join("gallery_installation","gallery_installation.installation_id=installations.id and gallery_installation.sorting=1","left");
		$query = $this->db->get("installations");
		return $query->row();
	}

	function get_property($id){

		$this->db->select("installations.*");
		$this->db->select("gallery_installation.filename as thumb_name, gallery_installation.id as gallery_id");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);
		$this->db->select("address.parent_id as parent_id");
		$this->db->where("installations.id",$id);

		$this->db->join("address","address.id=installations.address_id");
		$this->db->join("gallery_installation","gallery_installation.installation_id=installations.id and gallery_installation.sorting=1","left");
		$query = $this->db->get("installations");
		return $query->result_array();	
	}

	/**
	 * 목록 전체 갯수 반환
	 * 20140810 - $area가 코드가 아니라 지역검색텍스트 내용이 넘어온다.
	 * 20150422 - [DJ] ground_use, ground_aim, factory_use, factory_hoist, factory_power 항목 추가
	 */
	function get_total_count($where,$type="front"){
	
		if(element("category",$where)!=""){
			$this->db->where("installations.category",element("category",$where));
		}

		if(element("search_member_id",$where)!="")	$this->db->where("installations.member_id",element("search_member_id",$where));

		if(element("keyword",$where)!=""){
			$this->db->where("(`installations`.`address` LIKE '%".element("keyword",$where)."%' OR `installations`.`secret` LIKE '%".element("keyword",$where)."%' OR `installations`.`tag` LIKE '%".element("keyword",$where)."%' OR `installations`.`title` LIKE '%".element("keyword",$where)."%')");
		}

		if(element("search_type",$where)==""){

		} else if(element("search_type",$where)=="spot"){
			$this->db->select("installations.lat, installations.lng, (select lat from spot where id='".$where['search_value']."') as spot_lat, (select lng from spot where id='".$where['search_value']."') as spot_lng",false);
			$this->db->having('(round(SQRT(POW(69.1 * (installations.lat - spot_lat), 2) + POW(69.1 * (spot_lng - installations.lng) * COS(installations.lat / 57.3), 2))*1.61,2) ) < 1'); 
		} else if(element("search_type",$where)=="subway"){

			$this->db->where("installations.id in (select installation_id from installation_subway where subway_id='".element("search_value",$where)."')", NULL, FALSE);

		} else if(element("search_type",$where)=="parent_address"){

			$this->db->where("installations.address_id in (select id from address where parent_id='".element("search_value",$where)."')", NULL, FALSE);

		} else if(element("search_type",$where)=="address"){

			$this->db->where("installations.address_id",element("search_value",$where));

		}

		if($type=="front"){
			$this->db->where("installations.is_activated","1");
			$this->db->where("installations.is_valid","1");
		} else {
			if(element("only",$where)==""){
				//없음
			} else if(element("only",$where)=="plan"){
				$this->db->where("installations.status","plan");
			} else if(element("only",$where)=="go"){
				$this->db->where("installations.status","go");
			} else if(element("only",$where)=="end"){
				$this->db->where("installations.status","end");
			} else if(element("only",$where)=="public"){
				$this->db->where("installations.is_activated","1");
			} else if(element("only",$where)=="private"){
				$this->db->where("installations.is_activated","0");
			} else if(element("only",$where)=="recommand"){
				$this->db->where("installations.recommand","1");
			}

			if(element("valid",$where)!=""){
				$this->db->where("installations.is_valid",element("valid",$where));
			}
			if(element("search_admin_member_id",$where)!=""){
				$this->db->where("members.id",element("search_admin_member_id",$where));
			}
		}

		$this->db->where("members.valid","Y");
		$this->db->join("members","installations.member_id=members.id");

		$query = $this->db->get("installations");

		return $query->num_rows(); 
	}

	private function IsPriceNull($v){
    		return (!isset($v) || trim($v)==='' || $v=="0");
	}

	/**
	 * 매물 목록
	 *
	 * @type - sell, full_rend, monthly_rent 가 있는데 내부적으로는 rent가 있다. (이건 전세도 되고 월세도 되는 물건을 의미한다.
	 *  최대 금액일 경우에는 최대 제한을 하지 않는다.( SELL_MAX, FULL_MAX, MONTH_DEPOSIT_MAX_MAX, MONTH_MAX)
	 *  sorting은 random 기능이 추가되었다.
	 * 20150422 - [DJ] ground_use, ground_aim, factory_use, factory_hoist, factory_power 항목 추가
	 */
	function get_list($where, $num, $offset,$type="front"){
		$this->db->select("installations.id,installations.date,installations.title,installations.address,installations.secret,installations.status,installations.scale,installations.notice_year,installations.enter_year,installations.viewcnt,installations.category");
		$this->db->select("installations.recommand,installations.is_activated");
		$this->db->select("installations.is_blog,installations.is_cafe,installations.video_url");
		//$this->db->select("(select count(*) from hope where installation_id=installations.id) as hope_cnt");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);
		$this->db->select("address.parent_id as parent_id");
		$this->db->select("members.id as member_id, members.name as member_name, members.phone as member_phone, members.profile as member_profile");
		$this->db->select("gallery_installation.filename as thumb_name, gallery_installation.id as gallery_id");
		
		if(element("category",$where)!=""){
			$this->db->where_in("installations.category",explode(",",element("category",$where)));
		}

		if(element("search_type",$where)==""){

		} else if(element("search_type",$where)=="parent_address"){

			$this->db->where("installations.address_id in (select id from address where parent_id='".element("search_value",$where)."')", NULL, FALSE);

		} else if(element("search_type",$where)=="address"){

			$this->db->where("installations.address_id",element("search_value",$where));

		}

		if(element("search_member_id",$where)!="")	$this->db->where("installations.member_id",element("search_member_id",$where));

		if(element("keyword",$where)!=""){
			$this->db->where("(`installations`.`address` LIKE '%".element("keyword",$where)."%' OR `installations`.`secret` LIKE '%".element("keyword",$where)."%' OR `installations`.`tag` LIKE '%".element("keyword",$where)."%' OR `installations`.`title` LIKE '%".element("keyword",$where)."%')");
		}

		if(element("search_type",$where)==""){

		} else if(element("search_type",$where)=="spot"){
			$this->db->select("(select lat from spot where id='".$where['search_value']."') as spot_lat, (select lng from spot where id='".$where['search_value']."') as spot_lng",false);
			$this->db->having('(round(SQRT(POW(69.1 * (installations.lat - spot_lat), 2) + POW(69.1 * (spot_lng - installations.lng) * COS(installations.lat / 57.3), 2))*1.61,2) ) < 1'); 
		} else if(element("search_type",$where)=="subway"){

			$this->db->where("installations.id in (select installation_id from installation_subway where subway_id='".element("search_value",$where)."')", NULL, FALSE);

		} else if(element("search_type",$where)=="parent_address"){

			$this->db->where("installations.address_id in (select id from address where parent_id='".element("search_value",$where)."')", NULL, FALSE);

		} else if(element("search_type",$where)=="address"){

			$this->db->where("installations.address_id",element("search_value",$where));

		}

		//sorting
		// 기본정렬은 추천순, 시간순
		// 관리자에서는 랜덤기능이 없음
		// 랜덤일 경우에는 추천순으로 정렬한 후에 랜덤으로 표시한다.
		// 급매순을 뺏기 때문에 추천순에서는 급매항목을 삭제한다.
		if(element("sorting",$where)=="basic" || element("sorting",$where) ==""){
			if($type=="front"){
				if($this->config->RANDOM=="1"){
					$this->db->order_by("installations.recommand desc, rand()");
				} else {
					$this->db->order_by("installations.recommand desc, installations.date desc");
				}
			} else {
					$this->db->order_by("installations.recommand desc, installations.date desc");
			}
		} else if(element("sorting",$where)=="date_desc"){
			$this->db->order_by("installations.date","desc");
		} else if(element("sorting",$where)=="date_asc"){
			$this->db->order_by("installations.date","asc");
		}

		if($type=="front"){
			$this->db->where("installations.is_activated","1");
			$this->db->where("installations.is_valid","1");
		}else {
			if(element("only",$where)==""){
				//없음
			} else if(element("only",$where)=="plan"){
				$this->db->where("installations.status","plan");
			} else if(element("only",$where)=="go"){
				$this->db->where("installations.status","go");
			} else if(element("only",$where)=="end"){
				$this->db->where("installations.status","end");
			} else if(element("only",$where)=="public"){
				$this->db->where("installations.is_activated","1");
			} else if(element("only",$where)=="private"){
				$this->db->where("installations.is_activated","0");
			} else if(element("only",$where)=="recommand"){
				$this->db->where("installations.recommand","1");
			} 
			if(element("valid",$where)!=""){
				$this->db->where("installations.is_valid",element("valid",$where));
			}
			if(element("search_admin_member_id",$where)!=""){
				$this->db->where("members.id",element("search_admin_member_id",$where));
			}
		}

		$this->db->where("members.valid","Y");

		$this->db->join("members","members.id=installations.member_id");
		$this->db->join("address","address.id=installations.address_id");
		$this->db->join("gallery_installation","gallery_installation.installation_id=installations.id and gallery_installation.sorting=1","left");

		$this->db->order_by("date","desc");
		$query = $this->db->get("installations",$num, $offset);
		return $query->result_array();
	}

	/**
	 * 지도 표시를 위해서 모든 활성 매물을 반환한다.
	 */
	function get_all_list($lat_s, $lat_e, $lng_s, $lng_e, $where){
	
		$this->db->select("members.name as member_name, members.phone as member_phone, members.profile as member_profile");
		$this->db->select("gallery_installation.filename as thumb_name, gallery_installation.id as gallery_id");

		if(element("category",$where)!=""){
			$this->db->where_in("installations.category",explode(",",element("category",$where)));
		}

		$this->db->select("installations.*, installations.lat as latitude, installations.lng as longitude");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);
		$this->db->select("address.parent_id as parent_id");
		$this->db->where("installations.is_activated","1");
		$this->db->where("installations.is_valid","1");

		$this->db->where("installations.lat >=",$lat_s);
		$this->db->where("installations.lat <=",$lat_e);
		$this->db->where("installations.lng >=",$lng_s);
		$this->db->where("installations.lng <=",$lng_e);

		$this->db->where("members.valid","Y");

		$this->db->join("members","members.id=installations.member_id");
		$this->db->join("address","address.id=installations.address_id");
		$this->db->join("gallery_installation","gallery_installation.installation_id=installations.id and gallery_installation.sorting=1","left");
		if($this->config->RANDOM=="1"){
			$this->db->order_by("rand()");
		} else {
			$this->db->order_by("installations.recommand desc, installations.date desc");
		}
		$query = $this->db->get("installations");
		return $query->result_array();
	}

	/**
	 * 아래 목록 가져오는 것과 로직이 동일하고 반환값을 숫자인 것만 다르다.
	 */
	function get_all_total($lat_s, $lat_e, $lng_s, $lng_e, $where){

		if(element("category",$where)!=""){
			$this->db->where_in("installations.category",explode(",",element("category",$where)));
		}

		$this->db->where("installations.is_activated","1");
		$this->db->where("installations.is_valid","1");

		$this->db->where("installations.lat >=",$lat_s);
		$this->db->where("installations.lat <=",$lat_e);
		$this->db->where("installations.lng >=",$lng_s);
		$this->db->where("installations.lng <=",$lng_e);
		
		$this->db->where("members.valid","Y");

		$this->db->join("members","installations.member_id=members.id");
		$this->db->join("address","address.id=installations.address_id");
		$this->db->order_by("installations.recommand desc, installations.date desc");
		$cnt = $this->db->count_all_results("installations");
		return $cnt;
	}

	/**
	 * 지도 표시를 위해서 모든 활성 매물을 반환한다.
	 */
	function get_all_server_list($num, $offset, $lat_s, $lat_e, $lng_s, $lng_e, $where){

		$this->db->select("gallery_installation.filename as thumb_name, gallery_installation.id as gallery_id");

		if(element("category",$where)!=""){
			$this->db->where_in("installations.category",explode(",",element("category",$where)));
		}

		if(element("sorting",$where)=="basic" || element("sorting",$where) ==""){

				if($this->config->RANDOM=="1"){
					$this->db->order_by("installations.recommand desc, rand()");
				} else {
					$this->db->order_by("installations.recommand desc, installations.date desc");
				}
		} else if(element("sorting",$where)=="date_desc"){
			$this->db->order_by("installations.date","desc");
		} else if(element("sorting",$where)=="date_asc"){
			$this->db->order_by("installations.date","asc");
		} else if(element("sorting",$where)=="price_desc"){
			//$this->db->order_by("installations.sell_price desc,installations.full_rent_price desc, installations.monthly_rent_deposit desc, installations.monthly_rent_price desc");
		} else if(element("sorting",$where)=="price_asc"){
			//$this->db->order_by("installations.sell_price asc,installations.full_rent_price asc, installations.monthly_rent_deposit asc, installations.monthly_rent_price asc");
		} else if(element("sorting",$where)=="area_desc"){
			//$this->db->order_by("installations.real_area","desc");
		} else if(element("sorting",$where)=="area_asc"){
			//$this->db->order_by("installations.real_area","asc");
		}

		$this->db->select("installations.id, installations.title, installations.lat, installations.lng, installations.category, installations.recommand");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);
		$this->db->select("address.parent_id as parent_id");
		$this->db->where("installations.is_activated","1");
		$this->db->where("installations.is_valid","1");

		$this->db->where("installations.lat >=",$lat_s);
		$this->db->where("installations.lat <=",$lat_e);
		$this->db->where("installations.lng >=",$lng_s);
		$this->db->where("installations.lng <=",$lng_e);

		$this->db->where("members.valid","Y");

		$this->db->join("members","installations.member_id=members.id");
		$this->db->join("address","address.id=installations.address_id");
		$this->db->join("gallery_installation","gallery_installation.installation_id=installations.id and gallery_installation.sorting=1","left");
		$this->db->order_by("installations.recommand desc, installations.date desc");

		$query = $this->db->get("installations",$num, $offset);

		return $query->result_array();
	}

	/**
	 * 지도 표시를 위해서 모든 활성 매물을 반환한다.
	 * 가격 정보는 count==1 일 경우에 마커로 가격 정보를 보여주려고 한다.
	 */
	function get_all_server_latlng($lat_s, $lat_e, $lng_s, $lng_e, $where){
		$this->db->select("installations.id,installations.lat,installations.lng");
		$this->db->select("members.name as member_name, members.phone as member_phone, members.profile as member_profile");

		if(element("category",$where)!=""){
			$this->db->where_in("installations.category",explode(",",element("category",$where)));
		}

		$this->db->where("members.valid","Y");

		$this->db->where("installations.is_activated","1");
		$this->db->where("installations.is_valid","1");
		$this->db->where("installations.lat >=",$lat_s);
		$this->db->where("installations.lat <=",$lat_e);
		$this->db->where("installations.lng >=",$lng_s);
		$this->db->where("installations.lng <=",$lng_e);

		$this->db->join("address","address.id=installations.address_id");
		$this->db->join("members","installations.member_id=members.id");

		$this->db->order_by("installations.recommand desc, installations.date desc");

		$query = $this->db->get("installations");
		return $query->result_array();
	}

	function get_subway_list($lat_s, $lat_e, $lng_s, $lng_e){
		$this->db->distinct();
		$this->db->select("subway.id,subway.name,subway.hosun, subway.hosun_id, subway.lat, subway.lng");
		$this->db->where("subway.lat >=",$lat_s);
		$this->db->where("subway.lat <=",$lat_e);
		$this->db->where("subway.lng >=",$lng_s);
		$this->db->where("subway.lng <=",$lng_e);
		$result  = $this->db->get("subway");
		return $result->result_array();
	}

	function get_installation_subway($id,$top=3){
		$this->db->select("subway.id,subway.name,subway.hosun, subway.hosun_id, subway.lat, subway.lng");
		$this->db->select("round(SQRT(POW(69.1 * (subway.lat - installations.lat), 2) + POW(69.1 * (installations.lng - subway.lng) * COS(subway.lat / 57.3), 2))*1.61,2) AS distance",false);
		$this->db->join("subway","subway.id=installation_subway.subway_id");
		$this->db->join("installations","installations.id=installation_subway.installation_id");
		$this->db->order_by("distance","asc");
		$this->db->where("installation_subway.installation_id",$id);
		$this->db->limit($top);
		$result  = $this->db->get("installation_subway");
		return $result->result();
	}

	function add_home($param){
		$this->db->insert("hope_installations",$param);
	}

	/**
	 * 동별 매물 집계 보여주기
	 */
	function get_stat(){
		if($this->config->STATS=="dong"){
			$this->db->select("address.id as parent_id, address.lat as parent_lat,address.lng as parent_lng,address.sido,address.gugun,address.dong");
			$this->db->select("concat(address.sido,' ',address.gugun,' ',address.dong) as title, address.dong as label",false);
			$this->db->group_by("address.dong");
		} else {
			$this->db->select("address.parent_id");
			$this->db->select("concat(address.sido,' ',address.gugun) as title, address.gugun as label",false);
			$this->db->select("(select lat from parent_address where id=address.parent_id) as parent_lat");
			$this->db->select("(select lng from parent_address where id=address.parent_id) as parent_lng");
			$this->db->select("(select area from parent_address where id=address.parent_id) as parent_area");
			$this->db->group_by("address.gugun");
		}
		$this->db->select("count(*) as cnt");
		$this->db->from("installations");
		$this->db->where("is_activated","1");
		$this->db->where("is_valid","1");
		$this->db->where("members.valid","Y");

		$this->db->join("members","installations.member_id=members.id");
		$this->db->join("address","address.id=installations.address_id");
		$result = $this->db->get();
		return $result->result();
	}


	/**
	 * 인근 분양 목록 가져오기(동일 유형)
	 *
	 */
	function get_nearby($category="",$line=0){
		$this->db->select("installations.*");		
		$this->db->select("gallery_installation.filename as thumb_name, gallery_installation.id as gallery_id");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);
		$this->db->select("address.parent_id as parent_id");
		$this->db->where("is_activated","1");
		$this->db->where("is_valid","1");

		if($category!=""){
			$this->db->where("category",$category);
		}
		$this->db->where("members.valid","Y");

		if($line > 1){
			$this->db->limit($line*4);
		}
		else{
			$this->db->limit(10);
		}


		$this->db->order_by("installations.date","desc");
		$this->db->join("members","installations.member_id=members.id");
		$this->db->join("address","address.id=installations.address_id");
		$this->db->join("gallery_installation","gallery_installation.installation_id=installations.id and gallery_installation.sorting=1","left");
		$query = $this->db->get("installations");

		return $query->result_array();	
	}

	function get_recommand($category,$line=0){
		$this->db->select("installations.*");
		$this->db->select("gallery_installation.filename as thumb_name, gallery_installation.id as gallery_id");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);
		$this->db->select("address.parent_id as parent_id");
		$this->db->where("is_activated","1");
		$this->db->where("is_valid","1");
		$this->db->where("recommand","1");

		if($category!="all"){
			$this->db->where("category",$category);
		}
		
		$this->db->where("members.valid","Y");

		if($line > 1){
			$this->db->limit($line*4);
		} else {
			$this->db->limit(10);
		}
		
		if($this->config->RANDOM=="1"){
			$this->db->order_by("rand()");
		} else {
			$this->db->order_by("installations.date","desc");
		}

		$this->db->join("members","installations.member_id=members.id");
		$this->db->join("address","address.id=installations.address_id");
		$this->db->join("gallery_installation","gallery_installation.installation_id=installations.id and gallery_installation.sorting=1","left");
		$query = $this->db->get("installations");
		return $query->result_array();	
	}

	/**
	 *
	 * @brief 	분양 홈에서 최신 분양 3건(종료된 분양은 안 보임) 보여주는데 사용한다.
	 * @author 	강대중
	 */
	function get_favorite($line=0){
		$this->db->select("installations.*");
		$this->db->select("gallery_installation.filename as thumb_name, gallery_installation.id as gallery_id");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);
		$this->db->select("address.parent_id as parent_id");
		$this->db->where("installations.is_activated","1");
		$this->db->where("installations.is_valid","1");
		$this->db->where("installations.status !=","end"); //인기 분양은 종료되지 않은 분양에서만 나오도록 한다.
		$this->db->where("members.valid","Y");

		if($line > 1){
			$this->db->limit($line);
		} else {
			$this->db->limit(3);
		}
		$this->db->order_by("installations.viewcnt","desc");

		$this->db->join("members","installations.member_id=members.id");
		$this->db->join("address","address.id=installations.address_id");
		$this->db->join("gallery_installation","gallery_installation.installation_id=installations.id and gallery_installation.sorting=1","left");
		$query = $this->db->get("installations");
		return $query->result_array();	
	}	

	function get_near_meta(){
		$this->db->select("product_near_meta.*");
		$this->db->where("valid","Y");
		$this->db->from("product_near_meta");
		$query = $this->db->get();
		return $query->result();
	}
}

/* End of file minstallation.php */
/* Location: ./application/models/minstallation.php */