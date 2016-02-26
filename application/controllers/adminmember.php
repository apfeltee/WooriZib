<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminmember extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	/**
	 * 관리자 목록
	 */
	public function index($type="",$page=0){
		$this->load->model("Mmember");
		$this->load->model("Madminauth");
		$this->load->model("Mconfig");
		$this->load->model("Maddress");

		$this->load->library('pagination');

		$config = $this->Mconfig->get();

		$keyword = $this->input->get("keyword");

		$type = (!$type) ? $config->MEMBER_TYPE : $type;
		$type = ($type=="both") ? "general" : $type;

		$page_config['base_url'] = "/adminmember/index/".$type;
		$page_config['total_rows'] = $this->Mmember->get_total_count($type,$keyword);
		if (count($_GET) > 0) $page_config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$page_config['first_url'] = $page_config['base_url'].'?'.http_build_query($_GET);
		$page_config['per_page'] = 20;
		$page_config['uri_segment'] = 4;
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

		$data['total'] = $page_config['total_rows'];
		$data["type"] = $type;

		if($type=="admin"){
			$data["pagination"] = "";
			$data["query"] = $this->Mmember->get_list($type,$keyword);
		}
		else{
			$data["query"] = $this->Mmember->get_list($type,$keyword,$page_config['per_page'], $page);
		}

		$data["member_auth"] = $this->Madminauth->get_auth_list();

		//유료결제상품 정보
		$this->load->model("Mpay");
		$data["pay_setting"] = $this->Mpay->get_setting_list();	

		$data["sido"] = $this->Maddress->get_sido();

		$this->layout->admin('member_index',$data);
	}

	/**
	 * 관리자 정보 가져오기
	 */
	public function get_json($id){
		$this->load->model("Mmember");
		$this->load->model("Maddress");

		$query = $this->Mmember->get($id);

		if($query->permit_area){			
			$permit_area = explode(",",$query->permit_area);
			$permit_area_array = Array();
			foreach($permit_area as $val){
				$address = $this->Maddress->get($val);
				$permit_area_array[$val] = $address->sido." ".$address->gugun." ".$address->dong;
			}
			$query->permit_area = $permit_area_array;
		}
		echo json_encode($query);
	}

	/**
	 * 관리자 본인을 제외한 다른 관리자 리스트를 가져오기
	 * 삭제 시 매물 이전을 위해서 만듬
	 */
	public function get_others_json($id){
		$this->load->model("Mmember");
		$query = $this->Mmember->get_others($id,"admin");
		echo json_encode($query);
	}

	/**
	 * 관리자 추가
	 *
	 * 20141013 - 시그니처 추가(sign)
	 */
	public function add_action(){

		$this->load->model("Mmember");

		//email 유효성체크
		if($this->input->post("email")){
			$cnt = $this->Mmember->have_email($this->input->post("email"));
			if($cnt){
				redirect("adminmember/index/general","refresh");
				exit;
			}
		}
		else{
			redirect("adminmember/index/general","refresh");
			exit;
		}

		$config['upload_path'] = HOME.'/uploads/member';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		
		$profile = "";
		if(!$this->upload->do_upload("profile")){
			$error = array('error' => $this->upload->display_errors());
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$profile = $data["upload_data"]["file_name"];
		}

		$watermark = "";
		if(!$this->upload->do_upload("watermark")){
			$error = array('error' => $this->upload->display_errors());
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$watermark = $data["upload_data"]["file_name"];
		}

		$param = Array(
			"type"		=> $this->input->post("type"),
			"email"		=> $this->input->post("email"),
			"name"		=> $this->input->post("name"),
			"pw"		=> $this->_prep_password($this->input->post("pw")),
			"phone"		=> $this->input->post("phone"),
			"auth_id"	=> ($this->input->post("auth_id")) ? $this->input->post("auth_id") : 0,
			"biz_name"	=> ($this->input->post("biz_name")) ? $this->input->post("biz_name") : "",
			"biz_ceo"	=> ($this->input->post("biz_ceo")) ? $this->input->post("biz_ceo") : "",
			"biz_auth"	=> ($this->input->post("biz_auth")) ? $this->input->post("biz_auth") : "0", 
			"biz_num"	=> ($this->input->post("biz_num")) ? $this->input->post("biz_num") : "",
			"re_num"	=> ($this->input->post("re_num")) ? $this->input->post("re_num") : "",
			"tel"		=> ($this->input->post("tel")) ? $this->input->post("tel") : "",
			"address"	=> ($this->input->post("address")) ? $this->input->post("address") : "",
			"address_detail" => ($this->input->post("address_detail")) ? $this->input->post("address_detail") : "",
			"kakao"		=> ($this->input->post("kakao")) ? $this->input->post("kakao") : "",
			"profile"	=> $profile,
			"watermark"	=> $watermark,
			"watermark_position_vertical" => $this->input->post("watermark_position_vertical"),
			"watermark_position_horizontal" => $this->input->post("watermark_position_horizontal"),
			"sign"		=> ($this->input->post("sign_add")) ? $this->input->post("sign_add") : "",
			"bio"		=> ($this->input->post("bio")) ? $this->input->post("bio") : "",
			"permit_ip" => ($this->input->post("permit_ip")) ? $this->input->post("permit_ip") : "",
			"permit_area" => ($this->input->post("permit_area")) ? implode(",",$this->input->post("permit_area")) : "",
			"color"		=> ($this->input->post("color")) ? $this->input->post("color") : "",
			"date"		=> date('Y-m-d H:i:s')
		);

		$this->Mmember->insert($param);
		redirect("adminmember/index/".$this->input->post("type"),"refresh");
	}

	/**
	 * 관리자 수정
	 */
	public function edit_action($type=''){

		$this->load->model("Mmember");
		$this->load->model("Mconfig");

		$config = $this->Mconfig->get();

		//email 유효성체크
		if(!$this->input->post("email")){
			redirect("adminmember/index/general","refresh");
			exit;
		}

		$upload_config['upload_path'] = HOME.'/uploads/member';
		$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
		$upload_config['encrypt_name'] = TRUE;
		$this->load->library('upload', $upload_config);

		$profile = "";
		if(!$this->upload->do_upload("profile")){
			$error = array('error' => $this->upload->display_errors());
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$profile = $data["upload_data"]["file_name"];
		}

		$watermark = "";
		if(!$this->upload->do_upload("watermark")){
			$error = array('error' => $this->upload->display_errors());
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$watermark = $data["upload_data"]["file_name"];
		}

		$param = Array(
			"email"		=> $this->input->post("email"),
			"name"		=> $this->input->post("name"),
			"phone"		=> $this->input->post("phone"),
			"biz_name"	=> ($this->input->post("biz_name")) ? $this->input->post("biz_name") : "",
			"biz_ceo"	=> ($this->input->post("biz_ceo")) ? $this->input->post("biz_ceo") : "",
			"biz_auth"	=> ($this->input->post("biz_auth")) ? $this->input->post("biz_auth") : "0", 
			"biz_num"	=> ($this->input->post("biz_num")) ? $this->input->post("biz_num") : "",
			"re_num"	=> ($this->input->post("re_num")) ? $this->input->post("re_num") : "",
			"tel"		=> ($this->input->post("tel")) ? $this->input->post("tel") : "",
			"address"	=> ($this->input->post("address")) ? $this->input->post("address") : "",
			"address_detail" => ($this->input->post("address_detail")) ? $this->input->post("address_detail") : "",
			"kakao"		=> ($this->input->post("kakao")) ? $this->input->post("kakao") : "",
			"watermark_position_vertical" => $this->input->post("watermark_position_vertical"),
			"watermark_position_horizontal" => $this->input->post("watermark_position_horizontal"),
			"sign"		=> ($this->input->post("sign_edit")) ? $this->input->post("sign_edit") : "",
			"bio"		=> ($this->input->post("bio")) ? $this->input->post("bio") : "",
			"permit_ip" => ($this->input->post("permit_ip")) ? $this->input->post("permit_ip") : "",
			"permit_area" => ($this->input->post("permit_area")) ? implode(",",$this->input->post("permit_area")) : "",
			"color"		=> ($this->input->post("color")) ? $this->input->post("color") : "",
			"expire_date" => ($this->input->post("expire_date")) ? $this->input->post("expire_date") : NULL,
			"moddate"	=> date('Y-m-d H:i:s')
		);

		if($this->input->post("pw")!=""){
			$param["pw"] = $this->_prep_password($this->input->post("pw"));
		}

		if($this->input->post("valid")!=""){
			$param["valid"] = $this->input->post("valid");
		}

		if($this->input->post("auth_id")!=""){
			$param["auth_id"] = $this->input->post("auth_id");
		}

		if($config->GONGSIL_FLAG){
			$param["uuid"] = $this->input->post("uuid");
		}

		$member = $this->Mmember->get($this->input->post("id"));

		if($profile!=""){
			//기존 파일 제거
			@unlink($upload_config['upload_path']."/".$member->profile);
			$param["profile"] = $profile;
		}

		if($watermark!=""){
			//기존 파일 제거
			@unlink($upload_config['upload_path']."/".$member->watermark);
			$param["watermark"] = $watermark;
		}

		$this->Mmember->update($this->input->post("id"),$param);
		if($type=="member") redirect($_SERVER["HTTP_REFERER"],"refresh");
		else echo "true";
	}

	public function delete_action(){
		$this->load->model("Mmember");
		$this->load->model("Mnews");
		$this->load->model("Mcontact");

		//매물 이전
		$this->Mmember->change_area_products($this->input->post("delete_id"),$this->input->post("change_id"));		

		//뉴스 이전
		$this->Mnews->change_area_news($this->input->post("delete_id"),$this->input->post("change_id"));

		//고객 이전
		$this->Mcontact->change_area_contact($this->input->post("delete_id"),$this->input->post("change_id"));

		//회원삭제
		$this->Mmember->delete_area($this->input->post("delete_id"));
		
		redirect($_SERVER["HTTP_REFERER"],"refresh");
	}

	/**
	 * 회원 삭제
	 */
	public function delete_all($member_id){
		$this->load->model("Mmember");
		$this->load->model("Mpay");
		$member = $this->Mmember->get($member_id);
		if($member){
			if($member->type!="admin"){
				//프로필 삭제
				$this->delete_profile_image($member_id);
				@unlink(HOME."/uploads/member/".$member->profile);

				//워터마크 삭제
				$this->delete_watermark_image($member_id);
				@unlink(HOME."/uploads/member/".$member->watermark);

				//결제내역 삭제
				$this->Mpay->member_pay_delete($member_id);

				//매물삭제
				$product = $this->Mmember->get_member_product($member_id);
				foreach($product as $val){
					$this->delete_product($val->id);
				}

				//회원DB삭제
				$this->Mmember->delete_area($member_id);
			}		
		}
		redirect($_SERVER["HTTP_REFERER"],"refresh");
	}

	/**
	 * 매물 삭제
	 * 1. 갤러리 삭제, 2. 썸네일 삭제, 3. DB삭제, 4. 지하철역 정보 삭제
	 */
	function delete_product($product_id){
		$this->load->Model("Madminproduct");
		$product = $this->Madminproduct->get($product_id);
		if($product!=null){
			//갤러리 삭제		
			if( file_exists(HOME.'/uploads/gallery/'.$product_id) ){
				$this->rrmdir(HOME.'/uploads/gallery/'.$product_id);
			}
			$this->load->model("Mgallery");
			$this->Mgallery->delete_product($product_id);

			//네이버 신디케이션 전송
			$this->load->helper("syndi");
			send_ping($product_id,"product","delete");

			//DB 삭제
			$this->Madminproduct->delete_product($product_id);
			$this->Madminproduct->delete_subway($product_id);
		}
	}

	/**
	 * 비어 있지 않은 디렉토리를 삭제한다.
	 */
	private function rrmdir($dir) {
	   if (is_dir($dir)) {
		 $objects = scandir($dir);
		 foreach ($objects as $object){
		   if ($object != "." && $object != "..") {
			 if (filetype($dir."/".$object) == "dir"){
				rrmdir($dir."/".$object);
			 }else{ 
				unlink($dir."/".$object);
			 }
		   }
		 }
		 reset($objects);
		 rmdir($dir);
	  }
	}

	private function _prep_password($password){
		 return sha1($password.$this->config->item('encryption_key'));
	}

	//프로필사진 삭제
	public function delete_profile_image(){
		$this->load->model("Mmember");
		$member_id = $this->input->post("member_id");
		$profile_img_name = $this->input->post("profile_img_name");
		if($member_id && $profile_img_name){
			$this->Mmember->delete_profile_image($member_id);
			@unlink(HOME."/uploads/member/".$profile_img_name);
		}
	}

	//회원워터마크 삭제
	public function delete_watermark_image(){
		$this->load->model("Mmember");
		$member_id = $this->input->post("member_id");
		$watermark_img_name = $this->input->post("watermark_img_name");
		if($member_id && $watermark_img_name){
			$this->Mmember->delete_watermark_image($member_id);
			@unlink(HOME."/uploads/member/".$watermark_img_name);
		}
	}

	/**
	 * 회원의 결제내역
	 */
	public function get_member_pay(){
		$this->load->model("Mpay");
		$query = $this->Mpay->get_list_all($this->input->post("member_id"));
		echo json_encode($query);
	}

	/**
	 * 회원 결제서비스 관리자수동 적용
	 */
	public function give_member_pay(){
		$this->load->model("Mpay");

		$pay_setting = $this->Mpay->setting_get($this->input->post("pay_setting_id"));

		$day = date('Y-m-d H:i:s');

		$paying_info = $this->Mpay->last_valid_pay($this->input->post("member_id"));

		if($paying_info){ //이미 사용중인 결제정보가 있을 경우
			$start_date = $paying_info->end_date;
			$end_date = date("Y-m-d H:i:s", strtotime($paying_info->end_date." +".$pay_setting->day." day"));
		}
		else{ //없을 경우 현재날짜 기준
			$start_date = $day;
			$end_date = date("Y-m-d H:i:s", strtotime($day." +".$pay_setting->day." day"));
		}

		$pay_param = Array(
			"member_id"	=> $this->input->post("member_id"),
			"pay_setting_id" => $this->input->post("pay_setting_id"),
			"order_name"=> $pay_setting->name,
			"use_day"	=> $pay_setting->day,
			"use_count"	=> $pay_setting->count,
			"price"		=> $pay_setting->price,
			"start_date"=> $start_date,
			"end_date"	=> $end_date,
			"payed_date"=> date('Y-m-d H:i:s'),
			"state"		=> "Y",
			"is_admin"	=> "Y", //관리자지급
			"date"		=> date('Y-m-d H:i:s')
		);


		$this->Mpay->insert($pay_param);

		//회원이 가지고 있는 결제유효일 업데이트
		$member_param = Array(
			"end_date"	=> $end_date
		);
		$this->load->model("Mmember");
		$this->Mmember->update($this->input->post("member_id"),$member_param);

		echo "1";
	}

	public function sorting($id,$sorting){
		$this->load->model("Mmember");
		$param = Array("sorting"=>$sorting);
		$this->Mmember->update($id,$param);
	}
}

/* End of file adminqmember.php */
/* Location: ./application/controllers/adminmember.php */