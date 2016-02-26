<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 메인의 형태를 여러 형태로 가져가기 위한 구조
 */
class Mobile extends CI_Controller {

	/**
	 * 홈에서 메뉴를 눌러서 이동했을 경우에는 검색 정보를 초기화해야 한다.
	 * 먄약 홈에서 테마를 눌러 이동한 후 뒤로 가기를 하여 다시 매물 목록으로 이동할 경우에 아까 입력된 테마 정보가 검색조건에 이미 막혀 있어서 헤깔려 한다.
	 * 홈에서 것들은 검색을 모두 초기화해서 보여주도록 한다. (2015년 9월 25일)
	 *
	 */
	public function __construct() {
		parent::__construct(); 

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		if($this->uri->segment(2)!=""){
			if($this->uri->segment(2)=="index" && $this->uri->segment(3)!="" && $config->GONGSIL_FLAG){
				$this->session->set_userdata("uuid",$this->uri->segment(3));
			}
		}

		if($config->GONGSIL_FLAG && $this->session->userdata("uuid")==""){
			if($config->GPLAY){
				header('Location: ' . "https://play.google.com/store/apps/details?id=".$config->GPLAY);
			}
		}
	}

	/**
	 * 모바일 홈
	 */
	public function index(){

		$this->load->library("Components");
		$this->load->model("Mlayout");
		$this->load->model("Mconfig");

		$config = $this->Mconfig->get();

		$data["home_layout"] = $this->components->get($this->Mlayout->get_list());
		$this->layout->mobile('index',$data);			

		$this->load->helper("check");

		/*** LOG START ***/
		if($this->input->ip_address()!=$config->ip){
			$this->load->model("Mlog");
			$this->load->library('user_agent');
			if($this->session->userdata("session_cnt")==""){ $this->session->set_userdata("session_cnt",1); }
			else { $this->session->set_userdata("session_cnt", $this->session->userdata("session_cnt") + 1); }

			$param = Array(
				"session_id"=> $this->session->userdata("session_id"),
				"session_cnt"=> $this->session->userdata("session_cnt"),
				"user_agent"=> $this->session->userdata("user_agent"),
				"user_referrer"=> $this->agent->referrer(),
				"ip"		=> $this->input->ip_address(),
				"type"		=> "home",
				"mobile"	=> "1", 
				"data_id"	=> "0",
				"date"		=> date('Y-m-d H:i:s')
			);
			$this->Mlog->add($param);
			
		}
		/*** LOG START ***/
	}

	/**
	 * 홈
	 */
	public function home(){
		$this->load->model("Mconfig");
		$this->load->model("Msocial");

    	$data["config"] = $this->Mconfig->get();

    	/** 매물 종류 ***/
    	$this->load->model("Mcategory");
		$data["category"] = $this->Mcategory->get_list();

		/****/
		$this->load->model("Mlayout");
		$data["is_theme"] = $this->Mlayout->check("theme");

    	/** 테마 **/
		$this->load->model("Mtheme");
		$data["theme"] = $this->Mtheme->get_list();
		$data["social"] = $this->Msocial->get();

		/** 서비스소개 **/
		$this->load->model("Madminfront");
		$this->load->model("Mservice");
		$module = $this->Madminfront->get_module("service");
		$data["service_title"] = $module->title;
		$data["service"] = $this->Mservice->get_list_valid();
		$data["service_valid"] = $module->valid;

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();		
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('home',$data);
	}

	/**
	 * 지도 검색 메뉴
	 */
	public function map($clear=""){
		if($clear!=""){
			$this->session->unset_userdata('search');
			$this->session->unset_userdata('search_installation');
		}
		$this->load->model("Mconfig");
		$this->load->model("Mcategory");
		$this->load->model("Mtheme");

    	$data["config"] = $this->Mconfig->get();

		if($data["config"]->LIST_ENCLOSED && !$this->session->userdata("id")){
			redirect("/mobile/signin","refresh");
		}
		
		$data["search"] = $this->session->userdata("search");

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();	
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('map',$data);		
	}

	public function maplist($title, $zoom, $x, $y, $lat_s, $lat_e, $lng_s, $lng_e){
		$zoom = 20-$zoom;
		$this->load->model("Mproduct");
		$this->load->helper("map");
		define('OFFSET', 268435456);
		define('RADIUS_1', 85445659.4471); /* $offset / pi() */
 
		$query = $this->Mproduct->get_all_list($lat_s, $lat_e, $lng_s, $lng_e, $this->session->userdata("search"));
		$result["product"] = cluster_list($title, $x, $y,$query,50,$zoom);

		$this->layout->setLayout("list");
		$data["result"] = $this->layout->view("mobile/list",$result,true);
		$this->layout->mobile('maplist',$data);

	}

	/**
	 * 목록
	 */
	public function grid($lat_s="", $lat_e="", $lng_s="", $lng_e=""){
		$this->load->model("Mconfig");
    	$data["config"] = $this->Mconfig->get();

		if($data["config"]->LIST_ENCLOSED && !$this->session->userdata("id")){
			redirect("/mobile/signin","refresh");
		}
		
		$data["lat_s"] = $lat_s;
		$data["lat_e"] = $lat_e;
		$data["lng_s"] = $lng_s;
		$data["lng_e"] = $lng_e;

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('grid',$data);
	}

	/**
	 * 목록 json
	 */
	public function grid_json($page=0,$init="1",$lat_s="", $lat_e="", $lng_s="", $lng_e=""){

		$this->load->model("Mconfig");
		$this->load->model("Mproduct");
		$this->load->model("Madminproduct");

		$data["config"] = $this->Mconfig->get();

		$per_page=10;

		$search = $this->session->userdata("search");

		$search["lat_s"] = $lat_s;
		$search["lat_e"] = $lat_e;
		$search["lng_s"] = $lng_s;
		$search["lng_e"] = $lng_e;
		
		$search["sorting"] = $data["config"]->DEFAULT_SORT;

		$query["total"] = $this->Mproduct->get_total_count($search);
		
		if($query["total"] <= 10){
			$query["paging"] = 0;
		} else {
			$query["paging"] =  $page + $per_page;
		}

		if($init=="0"){
			$result["product"] = $this->Mproduct->get_list($search, $page, 0);
		} else {
			$result["product"] = $this->Mproduct->get_list($search, $per_page, $page);
		}
		
		foreach($result["product"] as $key=>$val){
			$result["product"][$key]["add_price"] = $this->Madminproduct->get_add_price($val["id"]);
		}		
		
		$this->layout->setLayout("list");
		$query["result"]   = $this->layout->view("mobile/list",$result,true);

		echo json_encode($query);

	}

	/**
	 * 매물 정보 보기 팝업
	 * 메뉴는 필요없음(우측에는 닫기 버튼, 좌측에는 좋아요 버튼)
	 */
	public function view($id){

		if($id==""){
			redirect("/","refresh");
			exit;
		}

		$this->load->library("Productview");
		$data = $this->productview->_get($id);

		if($data["query"]==null){
			redirect("/","refresh");
			exit;
		}
		if($data["query"]->category_opened=="N" && !$this->session->userdata("id")){
			redirect("/mobile/signin","refresh");
			exit;		
		}
		else{
			if($this->session->userdata("permit_area")){
				$permit_area = @explode(",",$this->session->userdata("permit_area"));
				if(!in_array($data["query"]->parent_id,$permit_area)){
					redirect("/","refresh");
					exit;					
				}
			}
		}

		//대출정보		
		if($data["query"]->type=="sell"){
			$this->load->model("Mloan");
			$data['loan'] = $this->Mloan->get_list();
			foreach($data['loan'] as $val){
				$val->loan_limit = $data['query']->sell_price * $val->rate_loan/100;
			}
		}
		

		$this->layout->mobile('view',$data);
	}

	/**
	 * 본 매물
	 */
	public function seen(){
		$this->load->model("Mhistory");
		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();

		if($data["config"]->LIST_ENCLOSED && !$this->session->userdata("id")){
			redirect("/mobile/signin","refresh");
		}

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$data["product"] = $this->Mhistory->get_list_by_session($this->session->userdata("session_id"));
		$this->layout->mobile('seen',$data);
	}

	/**
	 * 관심 매물
	 */
	public function hope(){

		$this->load->model("Mhope");
		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();

		if($data["config"]->LIST_ENCLOSED && !$this->session->userdata("id")){
			redirect("/mobile/signin","refresh");
		}

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		if($this->session->userdata("id")!=""){
			$data["product"] = $this->Mhope->get_list_by_member($this->session->userdata("id"));
		} else {
			$data["product"] = $this->Mhope->get_list_by_session($this->session->userdata("session_id"));
		}
		$this->layout->mobile('hope',$data);
	}

	/**
	 * 회사 소개
	 */
	public function about(){
		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('about',$data);
	}

	/**
	 * 뉴스 카테고리
	 */
	public function news(){

		$this->load->model("Mconfig");
		$this->load->model("Mnewscategory");

    	$data["config"] = $this->Mconfig->get();
		$data["newscategory"] = $this->Mnewscategory->get_list();

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["news_title"]=$this->Mmainmenu->get_name("news");

		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('news',$data);
	}

	/**
	 * 뉴스 목록
	 */
	public function news_list($category){

		$this->load->model("Mconfig");
		$this->load->model("Mnewscategory");
		$this->load->model("Mnews");

		$data["config"] = $this->Mconfig->get();
		$data["category"] = $this->Mnewscategory->get($category);
		$data["result"] = $this->Mnews->get_list($category);

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('news_list',$data);
	}

	/**
	 * 뉴스 상세
	 */
	public function news_view($id){

		$this->load->model("Mconfig");
		$this->load->model("Mnews");

		$data["config"] = $this->Mconfig->get();
		$data["result"] = $this->Mnews->get($id);

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('news_view',$data);
	}

	function search($direct="",$search_type=""){
		$this->load->model("Mconfig");
		$this->load->model("Mcategory");
		$this->load->model("Mtheme");

    	$data["config"] = $this->Mconfig->get();

		if($data["config"]->LIST_ENCLOSED && !$this->session->userdata("id")){
			redirect("/mobile/signin","refresh");
		}
		
		$data["search"] = $this->session->userdata("search");
		$data["category"] = $this->Mcategory->get_list();
		foreach($data["category"] as $key=>$val){
			$category_sub = $this->Mcategory->get_sub_list($val->id);
			if($category_sub){
				$data["category"][$key]->category_sub = $category_sub;
			}
		}
		$data["theme"] = $this->Mtheme->get_list();
		$direct = (isset($_SERVER["HTTP_REFERER"])) ? end(explode('/',$_SERVER["HTTP_REFERER"])) : "";
		if($direct!="map") $direct = "grid";
		$data["direct"] = $direct;

		if(isset($data["search"]["theme"]) && $data["search"]["theme"]){
			$query_theme = @explode(",",$data["search"]["theme"]);
			foreach($data["theme"] as $val){
				if(in_array($val->id,$query_theme)){
					$val->checked = "checked";
				}
				else{
					$val->checked = "";
				}
			}
		}		

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('search',$data);	
	}

	function mapdetail($id){

		$this->load->model("Mconfig");
		$this->load->model("Mproduct");

    	$data["config"] = $this->Mconfig->get();
		$data["query"] = $this->Mproduct->get($id);

		$this->layout->mobile('mapdetail',$data);
	}

	public function hope_add($id){
		$this->load->model("Mhope");
		$param = Array(
				"session_id"=>$this->session->userdata("session_id"),
				"product_id"=>$id,
				"date"=>date('Y-m-d H:i:s')
			);
		$this->Mhope->add($param);
	}

	public function hope_remove($id){
		$this->load->model("Mhope");
		$this->Mhope->remove($id,$this->session->userdata("session_id"));
	}

	/**
	 * 공지사항
	 */
	public function notice(){
		$this->load->model("Mconfig");
		$this->load->model("Mnotice");
		
		$data["config"] = $this->Mconfig->get();
		$data["result"] = $this->Mnotice->get_list(10, 0);

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('notice',$data);
	}

	/**
	 * 이용약관
	 */
	public function rule(){
		$this->load->model("Mconfig");
    	$data["config"] = $this->Mconfig->get();

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('rule',$data);
	}

	/**
	 * 개인정보보호정책
	 */
	public function privacy(){
		$this->load->model("Mconfig");
    	$data["config"] = $this->Mconfig->get();

		$data["menu"]= $this->load->view("mobile/menu",$data, true);
		$this->layout->mobile('privacy',$data);
	}

	/**
	 * 위치정보약관
	 */
	public function location(){
		$this->load->model("Mconfig");
    	$data["config"] = $this->Mconfig->get();

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('location',$data);
	}

	public function get_property($id){
		$this->load->model("Mproduct");
		$result["product"] = $this->Mproduct->get_property($id);
		$this->layout->setLayout("list");
		echo $this->layout->view("mobile/map_info",$result,true);
	}

    /**
     * 회원 로그인
     */
    public function signin($backurl = "", $id=""){

        if( $this->session->userdata("id")!=""){

            redirect("/mobile/home","refresh");
            exit;
        }

        $this->load->model("Mconfig");
        $data["config"] = $this->Mconfig->get();

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
        $data["menu"]= $this->load->view("mobile/menu",$data, true);

        $backurl = empty($id) ? $backurl : $backurl."/".$id;
        $data['backurl'] = empty($backurl) ? "/mobile/home" : "/mobile/".$backurl;
        $data["page_title"] =  "회원로그인";
        $this->layout->mobile('signin',$data);
    }

    /**
     * 회원 로그인 액션
     */
    public function signin_action(){
		$pw = $this->_prep_password($this->input->post("siPw"));
		$this->load->model("Mmember");
		$this->load->model("Mconfig");

		$config = $this->Mconfig->get();

		$this->load->helper('cookie'); 
		if($this->input->post("save")=="on"){
			$cookie1 = array(
                   'name'   => 'user_id',
                   'value'  => $this->input->post("siEmail"),
                   'expire' => '8650000',
                   'domain' => HOST,
                   'path'   => '/',
                   'prefix' => 'wb_',
               );

			$cookie2 = array(
                   'name'   => 'user_save',
                   'value'  => "on",
                   'expire' => '8650000',
                   'domain' => HOST,
                   'path'   => '/',
                   'prefix' => 'wb_',
               );
			set_cookie($cookie1); 
			set_cookie($cookie2);

		} else {
			delete_cookie('user_id',HOST,'/','wb_');
			delete_cookie('user_save',HOST,'/','wb_');
		}

		$result = $this->Mmember->check_login($this->input->post("siEmail"),$pw);

		if($result!=null){

			if($result->valid=='N'){
				echo "2";
				exit;
			}

			if($config->GONGSIL_FLAG && $result->auth_id != "1"){
				if($result->uuid==""){
					$param = Array(
						"uuid" => ($this->session->userdata("uuid")) ? $this->session->userdata("uuid") : ""
					);
					$this->Mmember->update($result->id,$param);				
				}
				else{
					if($result->uuid != $this->session->userdata("uuid")){
						echo "3";
						exit;
					}
				}
			}

			if($result->expire_date){
				if(str_replace("-","",$result->expire_date) < date("Ymd")){
					echo "4";
					exit;
				}
			}

			$this->session->set_userdata("email",$result->email);
			$this->session->set_userdata("id",$result->id);
			$this->session->set_userdata("type",$result->type);
			$this->session->set_userdata("biz_name",$result->biz_name);
			$this->session->set_userdata("biz_auth",$result->biz_auth);
			$this->session->set_userdata("name",$result->name);
			$this->session->set_userdata("phone",$result->phone);
			$this->session->set_userdata("tel",$result->tel);
			$this->session->set_userdata("kakao",$result->kakao);
			$this->session->set_userdata("timeout",time());	/* 자동로그아웃을 구현하기 위해서 추가한 세션 */
			$this->session->set_userdata("permit_area",$result->permit_area);

			echo $_SERVER['HTTP_REFERER'];

		} else {
			//해당 사용자가 없으면 이메일이 존재하는지 검사한다.
			$cnt = $this->Mmember->have_email($this->input->post("siEmail"));
			if($cnt==1){
				echo "0";
			} else {
				echo "9";
			}
		}
    }

    /**
     * 회원 가입
     */
    public function signup($type=""){

        if($this->session->userdata("id")!=""){
            redirect("/mobile/home","refresh");
            exit;
        }

		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();

		$type = (!$type) ? $data["config"]->MEMBER_TYPE : $type;
		$type = ($type=="both") ? "general" : $type;

		$data["type"] = $type;
		$data["real_name"] = $this->input->post("real_name");

		if($data["config"]->CP_CODE || $data["config"]->IPIN_CODE){
			if(!$data["real_name"]){
				$data["enc_data"] = "";
				redirect("/mobile/checkplus","refresh");
				exit;
			}
		}

        $this->layout->mobile('signup',$data);
    }

	/**
	 * 실명인증 페이지
	 */
	public function checkplus(){

		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();

		$data["real_name"] = "";

		$data["enc_data"] = $this->input->post("EncodeData");
		$sReserved1 = $this->input->post("param_r1");
		$sReserved2 = $this->input->post("param_r2");
		$sReserved3 = $this->input->post("param_r3");

		if($data["enc_data"]){//인증체크 페이지에서 넘어 왔을 경우 실명정보 추출
			$data["real_name"] = $this->get_checkplus_info($data["enc_data"],$sReserved1,$sReserved2,$sReserved3);
		}
		else{//인증체크 진행
			$data["enc_data"] = $this->cp_check();
		}
		$this->layout->mobile('checkplus',$data);
	}

	/**
	 * 휴대폰 실명인증체크
	 */
	public function cp_check(){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$sitecode = $config->CP_CODE;// NICE로부터 부여받은 사이트 코드
		$sitepasswd = $config->CP_PASSWORD;// NICE로부터 부여받은 사이트 패스워드
		
		$authtype = "";// 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드
			
		$popgubun 	= "N";//Y : 취소버튼 있음 / N : 취소버튼 없음
		$customize 	= "Mobile";//없으면 기본 웹페이지 / Mobile : 모바일페이지			
			 
		$reqseq = "REQ_0123456789";// 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로
		
		$reqseq = get_cprequest_no($sitecode);
		
		// CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
		$returnurl = "http://".HOST."/mobile/checkplus";	// 성공시 이동될 URL
		$errorurl = "http://".HOST."/mobile/checkplus";	// 실패시 이동될 URL
		
		// reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.		
		$this->session->set_userdata("REQ_SEQ",$reqseq);

		// 입력될 plain 데이타를 만든다.
		$plaindata ="7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
					"8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
					"9:AUTH_TYPE" . strlen($authtype) . ":". $authtype .
					"7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
					"7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
					"11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
					"9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;

		$enc_data = get_encode_data($sitecode, $sitepasswd, $plaindata);

		return $enc_data;
	}

	/**
	 * 실명인증 반환 정보
	 */
	function get_checkplus_info($enc_data,$sReserved1,$sReserved2,$sReserved3){
		
		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$sitecode = $config->CP_CODE;// NICE로부터 부여받은 사이트 코드
		$sitepasswd = $config->CP_PASSWORD;// NICE로부터 부여받은 사이트 패스워드

		$name = "";

		//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
		if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {echo "입력 값 확인이 필요합니다 : ".$match[0]; exit;} // 문자열 점검 추가. 
		if(base64_encode(base64_decode($enc_data))!=$enc_data) {echo "입력 값 확인이 필요합니다"; exit;}
		
		if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved1, $match)) {/*echo "문자열 점검 : ".$match[0];*/ exit;}
		if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved2, $match)) {/*echo "문자열 점검 : ".$match[0];*/ exit;}
		if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved3, $match)) {/*echo "문자열 점검 : ".$match[0];*/ exit;}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		if ($enc_data != "") {

			$plaindata = get_decode_data($sitecode, $sitepasswd, $enc_data);// 암호화된 결과 데이터의 복호화

			$plaindata = iconv("EUC-KR","UTF-8", $plaindata);
			//echo "[plaindata]  " . $plaindata . "<br>";

			if ($plaindata == -1){
				$returnMsg  = "암/복호화 시스템 오류";
			}else if ($plaindata == -4){
				$returnMsg  = "복호화 처리 오류";
			}else if ($plaindata == -5){
				$returnMsg  = "HASH값 불일치 - 복호화 데이터는 리턴됨";
			}else if ($plaindata == -6){
				$returnMsg  = "복호화 데이터 오류";
			}else if ($plaindata == -9){
				$returnMsg  = "입력값 오류";
			}else if ($plaindata == -12){
				$returnMsg  = "사이트 비밀번호 오류";
			}else{
				// 복호화가 정상적일 경우 데이터를 파싱합니다.  
				$requestnumber = $this->GetValue($plaindata , "REQ_SEQ");
				//$responsenumber = $this->GetValue($plaindata , "RES_SEQ");
				//$authtype = $this->GetValue($plaindata , "AUTH_TYPE");
				$name = $this->GetValueName($plaindata , "NAME");
				//$birthdate = $this->GetValue($plaindata , "BIRTHDATE");
				//$gender = $this->GetValue($plaindata , "GENDER");
				//$nationalinfo = $this->GetValue($plaindata , "NATIONALINFO");	//내/외국인정보(사용자 매뉴얼 참조)
				//$dupinfo = $this->GetValue($plaindata , "DI");
				//$conninfo = $this->GetValue($plaindata , "CI");

				if(strcmp($this->session->userdata("REQ_SEQ"), $requestnumber) != 0){
					//echo "세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다.<br>";
					$requestnumber = "";
					$responsenumber = "";
					$authtype = "";
					$name = "";
					$birthdate = "";
					$gender = "";
					$nationalinfo = "";
					$dupinfo = "";
					$conninfo = "";
				}
			}
		}
		return $name;
	}

	/**
	 * encode문제와 글씨 잘림현상이 나타나 GetValue를 사용하지 않고 이름만 추출 하도록 새로 만듬
	 */
	function GetValueName($str, $name){
		$str = explode(":",$str);
		$new_str = array();
		foreach($str as $key=>$val){
			$str[$key] = substr($val,0,-1);
			if(isset($str[$key+1])){
				$new_str[$str[$key]] = substr($str[$key+1],0,-1);
			}
		}
		return $new_str["NAME"];
	}

	/**
	 * NICE신용정보꺼 문제 있음
	 */
    function GetValue($str , $name){
        $pos1 = 0;  //length의 시작 위치
        $pos2 = 0;  //:의 위치

        while( $pos1 <= strlen($str) ){
            $pos2 = strpos( $str , ":" , $pos1);
            $len = substr($str , $pos1 , $pos2 - $pos1);
            $key = substr($str , $pos2 + 1 , $len);
            $pos1 = $pos2 + $len + 1;
            if( $key == $name ){
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $value = substr($str , $pos2 + 1 , $len);
                return $value;
            }
            else{
                // 다르면 스킵한다.
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $pos1 = $pos2 + $len + 1;
            }            
        }
    }

    /**
     * 비밀번호 인크립션
     */
    private function _prep_password($password){
        return sha1($password.$this->config->item('encryption_key'));
    }


    /**
     * 회원 로그아웃
     */
    public function logout(){
		$this->session->sess_destroy();
		redirect("/mobile/home","refresh");
    }

    /**
     * 매물 관리
     */
    public function product($page=0){
		if($this->session->userdata("id")==""){
			redirect("/mobile/signin","refresh");
		}

		$this->load->model("Mconfig");
		$this->load->model("Mmember");

		$data["config"] = $this->Mconfig->get();		

		if(!$data["config"]->USER_PRODUCT){
			redirect("/mobile/home","refresh");
		}

		$this->load->library('pagination');

		$page_config['base_url'] = "/mobile/product/";
		$page_config['total_rows'] = $this->Mmember->get_member_product_total($this->session->userdata("id"));
		$page_config['per_page'] = 10;
		$page_config['first_link'] = '<<';
		$page_config['first_tag_open'] = '<li>';
		$page_config['first_tag_close'] = '</li>';

		$page_config['last_link'] = '>>';
		$page_config['last_tag_open'] = '<li>';
		$page_config['last_tag_close'] = '</li>';

		$page_config['num_tag_open'] = "<li>";
		$page_config['num_tag_close'] = "</li>";
		$page_config['cur_tag_open'] = '<li class="active"><a href="#">';
		$page_config['cur_tag_close'] = '</a></li>';

		$page_config['next_link'] = '>';
		$page_config['next_tag_open'] = '<li>';
		$page_config['next_tag_close'] = '</li>';

		$page_config['prev_link'] = '<';
		$page_config['prev_tag_open'] = '<li>';
		$page_config['prev_tag_close'] = '</li>';

		$this->pagination->initialize($page_config);
		$data["pagination"] = $this->pagination->create_links();

		$data["product"] = $this->Mmember->get_member_product($this->session->userdata("id"),$page_config['per_page'], $page);

		$data["pay_info"] = $this->use_pay_info();

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);
		
		$this->layout->mobile('product',$data);
    }

    /**
     * 매물 등록 페이지
     */
    public function product_add(){
		if($this->session->userdata("id")==""){
			redirect("/mobile/signin","refresh");
		}

		$this->load->model("Mmember");
		$this->load->model("Mcategory");
		$this->load->model("Mtheme");
		$this->load->model("Mconfig");
		$this->load->model("Maddress");

		$data["config"] = $this->Mconfig->get();

		if(!$data["config"]->USER_PRODUCT){
			redirect("/mobile/home","refresh");
		}

		$this->cleanTemp(); //임시 저장 파일을 모두 삭제한다.

		$data["category"] = $this->Mcategory->get_list();
		$this->Maddress->set_type("full");
		$data["sido"] = $this->Maddress->get_sido();
		if($data["config"]->INIT_SIDO !=""){
			$init_gugun = ($data["config"]->INIT_GUGUN) ? $this->Maddress->get_parent($data["config"]->INIT_GUGUN)->gugun : "";
			$init_dong = ($data["config"]->INIT_DONG) ? $this->Maddress->get($data["config"]->INIT_DONG)->dong : "";
			$data["address_text"] = $data["config"]->INIT_SIDO." ".$init_gugun." ".$init_dong;
		}
		else{
			$data["address_text"] = "";
		}

		$data["theme"] = $this->Mtheme->get_list();
		$data["mode"] = "add";
		$data["module"] = "front";
		$data["product_form"] = $this->load->view("admin/template/product_form",$data,true);

		/** 메뉴 정보 **/		
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('product_add',$data);
    }

    /**
     * 매물 수정 페이지
     */
    public function product_edit($id=""){
		if($this->session->userdata("id")==""){
			redirect("/mobile/signin","refresh");
		}

		$this->load->model("Madminproduct");
		$this->load->model("Mcategory");
		$this->load->model("Mmember");
		$this->load->model("Maddress");
		$this->load->model("Mtheme");
		$this->load->model("Mconfig");
		$this->load->model("Mdanzi");

		$data["config"] = $this->Mconfig->get();

		if(!$data["config"]->USER_PRODUCT || $id==""){
			redirect("/mobile/home","refresh");
		}

		$data["members"] = $this->Mmember->get_list();
		$data["query"] = $this->Madminproduct->get($id);
		$data["category"] = $this->Mcategory->get_list();
		$data["address"] = $this->Maddress->get($data["query"]->address_id);
		$data["address_text"] = $data["address"]->sido." ".$data["address"]->gugun." ".$data["address"]->dong;
		$this->Maddress->set_type("full");
		$data["sido"] = $this->Maddress->get_sido();
		$data["danzi"] = $this->Mdanzi->get_danzi($data["query"]->address_id);
		$data["theme"] = $this->Mtheme->get_list();

		if($data["query"]->theme){
			$query_theme = @explode(",",$data["query"]->theme);
			foreach($data["theme"] as $val){
				if(in_array($val->id,$query_theme)){
					$val->checked = "checked";
				}
				else{
					$val->checked = "";
				}
			}
		}

		$data["mode"] = "edit";
		$data["module"] = "front";			

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();
		$data["menu"]= $this->load->view("mobile/menu",$data, true);
		
		$data["product_form"] = $this->load->view("admin/template/product_form",$data,true);
		$this->layout->mobile('product_edit',$data);
    }

	/**
	 * 로그인한 회원의 임시 갤러리 파일을 모두 삭제한다.
	 */
	private function cleanTemp(){
		$this->load->model("Mgallerytemp");
		$gallery = $this->Mgallerytemp->get_list($this->session->userdata("id"));

		foreach($gallery as $val){
			if( file_exists(HOME.'/uploads/gallery/temp/'. element("filename",$val)) ){
				unlink(HOME.'/uploads/gallery/temp/'. element("filename",$val));			//본 이미지 삭제
				$temp = explode(".",element("filename",$val));
				unlink(HOME.'/uploads/gallery/temp/'. $temp[0]."_thumb.".$temp[1]);	//썸네일 이미지 삭제
			}
			$this->Mgallerytemp->delete($val["id"]);
		}
	}

	/**
	 * 회원이 현재 사용중인 상품 정보
	 */
	public function use_pay_info(){
		$this->load->model("Mpay");
		$this->load->model("Mmember");

		$open_count = $this->Mmember->get_member_open_product_total($this->session->userdata("id"));
		$paying_info = $this->Mpay->is_valid_pay($this->session->userdata("id"));
		
		if($paying_info){
			$paying_info->enabled_count = $paying_info->use_count - $open_count;
		}
		return $paying_info;
	}

    /**
     * 지역검색
     */
    public function area(){
		$this->load->model("Mconfig");
		$this->load->model("Maddress");

		$data["config"] = $this->Mconfig->get();
		$data["sido"] = $this->Maddress->get_sido("front");
		$data["search"] = $this->session->userdata("search");

		$direct = (isset($_SERVER["HTTP_REFERER"])) ? end(explode('/',$_SERVER["HTTP_REFERER"])) : "grid";
		if($direct!="map") $direct = "grid";
		$data["direct"] = $direct;

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();		
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('area',$data);
    }

    /**
     * 지하철검색
     */
    public function subway(){
		$this->load->model("Mconfig");
		$this->load->model("Msubway");

		$data["config"] = $this->Mconfig->get();
		$data["local"] = $this->Msubway->get_local();
		$data["search"] = $this->session->userdata("search");

		$direct = end(explode('/',$_SERVER["HTTP_REFERER"]));
		if($direct!="map") $direct = "grid";
		$data["direct"] = $direct;

		foreach($data["local"] as $val){
			if($val->local==1) $val->local_text = toeng("수도권");
			if($val->local==2) $val->local_text = toeng("부산");
			if($val->local==3) $val->local_text = toeng("대구");
			if($val->local==4) $val->local_text = toeng("광주");
			if($val->local==5) $val->local_text = toeng("대전");
		}

		/** 메뉴 정보 **/
		$this->load->model("Mmainmenu");
		$data["mainmenu"] = $this->Mmainmenu->get_list_valid();		
		$data["menu"]= $this->load->view("mobile/menu",$data, true);

		$this->layout->mobile('subway',$data);
    }

    /**
     * 실거래가 조회
     */
    public function realprice($id){
		if($id==""){
			redirect("/","refresh");
			exit;
		}

		$this->load->library("Productview");
		$data = $this->productview->_get($id);

		if($data["query"]==null){
			redirect("/","refresh");
			exit;
		}

		$this->layout->mobile('realprice',$data);
    }
}

/* End of file mobile.php */
/* Location: ./application/controllers/mobile.php */
