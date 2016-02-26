<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminenquire extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	/** List상에서 바로 ajax로 수정을 했던 것을 페이지 이동으로 변경하면서 불필요해 졌다.
	 
	public function get_json($id){
		$this->load->model("Menquire");
		$query = $this->Menquire->get($id);
		echo json_encode($query);
	}
	
	**/
	
	/**
	 * 검색을 할 경우에는 검색 내용을 session에 저장한 후 목록 페이지로 이동시킨다.
	 */
	function search($status=""){
		$search = Array(
			"status" => $this->input->post("status"),
			"gubun" => $this->input->post("gubun"),
			"type" => $this->input->post("type"),
			"category" => $this->input->post("category"),
			"member_id" => $this->input->post("member_id"),
			"keyword" => $this->input->post("keyword")
		);
		$this->session->set_userdata("enquire_search",$search);
		redirect("adminenquire/index/".$status,"refresh");
	}

	function clean(){
		$this->session->unset_userdata('enquire_search');
		redirect("adminenquire/index","refresh");
	}

	/**
	 * 의뢰 목록
	 */
	function index($status="all",$page=0){

		$this->load->model("Menquire");
		$this->load->model("Mcategory");
		$this->load->model("Mmember");
		$this->load->model("Menquirememo");
		$this->load->model("Menquirecontract");

		$search = $this->session->userdata("enquire_search");

		if(empty($search)){
			$search = Array(
				"status" => "",
				"gubun" => "",
				"type" => "",
				"category" => "",
				"member_id" => ($this->session->userdata("auth_id")!=1) ? $this->session->userdata("admin_id") : "",
				"keyword" => ""
			);
		}

		$search["status"] = $data["status"] = $status;

		$this->load->library('pagination');
		$config['base_url'] = "/adminenquire/index/".$status;
		$config['total_rows'] = $this->Menquire->get_total_count($search);
		$config['per_page'] = 15;
		$config['uri_segment'] = 4;
		$config['first_link'] = '<처음';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = '마지막>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';

		$config['next_link'] = '&larr; [다음]';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '[이전] &rarr;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$data["query"] = $this->Menquire->get_list($search, $config['per_page'], $page);

		$data["members"] = $this->Mmember->get_list("admin");

		foreach($data["query"] as $key=>$val){
			$data["query"][$key]["category_list"] = $this->Mcategory->get_list_multi($val["category"]);
			$data["query"][$key]["count_contact"] = $this->Menquirememo->contact_count($data["query"][$key]["id"]);
			$data["query"][$key]["count_memo"] = $this->Menquirememo->memo_count($data["query"][$key]["id"]);
			$data["query"][$key]["count_contract"] = $this->Menquirecontract->contract_count($data["query"][$key]["id"]);
		}

		$data["search"] = $search;

		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();

		$data["category"] = $this->Mcategory->get_list();

		$data["status_category"] = $this->Menquire->status_category();

		$data["count_all"] = $this->Menquire->enquire_count("all",$search);
		$data["count_N"] = $this->Menquire->enquire_count("N",$search);
		$data["count_G"] = $this->Menquire->enquire_count("G",$search);
		$data["count_H"] = $this->Menquire->enquire_count("H",$search);
		$data["count_F"] = $this->Menquire->enquire_count("F",$search);
		$data["count_D"] = $this->Menquire->enquire_count("D",$search);
		$data["count_Y"] = $this->Menquire->enquire_count("Y",$search);
		$data["count_R"] = $this->Menquire->enquire_count("R",$search);
		$data["count_X"] = $this->Menquire->enquire_count("X",$search);
		$data["count_Y"] = $this->Menquire->enquire_count("Y",$search);
		$data["count_Z"] = $this->Menquire->enquire_count("Z",$search);

		$this->layout->admin('enquire_index', $data);
	}

	/**
	 * 매도/매수 의뢰하기 등록
	 */
	function add(){
		$this->load->model("Mcategory");
		$this->load->model("Mconfig");
		$this->load->model("Mmember");
		$this->load->model("Menquire");

		$data["config"] = $this->Mconfig->get();
		$data["category"] = $this->Mcategory->get_list();
		$data["members"] = $this->Mmember->get_list("admin");
		$data["status_category"] = $this->Menquire->status_category();

		$this->layout->admin('enquire_add', $data);	
	}

	function view($id,$type=""){
		$this->load->model("Mcategory");
		$this->load->model("Menquire");
		$this->load->model("Mmember");
		$this->load->model("Menquirememo");
		$this->load->model("Menquirecontract");

		$data["query"] = $this->Menquire->get($id);
		$data["category"] = $this->Mcategory->get_list();
		$data["member"] = $this->Mmember->get($data["query"]->member_id);

		$data["count_memo"] = $this->Menquirememo->memo_count($id);
		$data["count_contact"] = $this->Menquirememo->contact_count($id);
		$data["count_contract"] = $this->Menquirecontract->contract_count($id);
		$data["type"] = ($type) ? $type : "";
		$data["query"]->status_label = "";
		if($data["query"]->status){
			$status_label = $this->Menquire->status_get($data["query"]->status);
			$data["query"]->status_label = $status_label->label;
		}

		$this->layout->admin('enquire_view', $data);	
	}

	function add_action(){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$this->session->unset_userdata('enquire_search');

		$category = "";
		if($this->input->post("category")!=""){
			$category = implode(",",$this->input->post("category"));
		}

		$param = Array(
			"status"	=> $this->input->post("status"),
			"gubun"	=> $this->input->post("gubun"),
			"name"		=> $this->input->post("name"),
			"feature"	=> $this->input->post("feature"),			
			"phone"	=> $this->input->post("phone"),
			"phone_etc1" => $this->input->post("phone_etc1"),
			"phone_etc2" => $this->input->post("phone_etc2"),
			"location"	=> $this->input->post("location"),
			"visitdate"	=>$this->input->post("visitdate"), 
			"movedate"	=>$this->input->post("movedate"),
			"price"		=> $this->input->post("price"),
			"type"		=> $this->input->post("type"),
			"category"	=> $category,
			"content"	=> $this->input->post("content"),
			"work"		=> $this->input->post("work"),
			"secret"		=> $this->input->post("secret"),
			"member_id"	=> $this->input->post("member_id"),
			"date"		=> date('Y-m-d H:i:s')
		);

		if($config->INSTALLATION_FLAG=="2"){
			$param["type"] = "installation";
		}

		$this->load->model("Menquire");
		$this->Menquire->add($param);
		redirect("adminenquire/index","refresh");
	}

	function edit($id){
		$this->load->model("Mcategory");
		$this->load->model("Menquire");
		$this->load->model("Mmember");
		$data["query"] = $this->Menquire->get($id);
		$data["category"] = $this->Mcategory->get_list();
		$data["members"] = $this->Mmember->get_list("admin");
		$data["status_category"] = $this->Menquire->status_category();
		$this->layout->admin('enquire_edit', $data);
	}

	/**
	 * 관리자에 의해 수정한다.
	 */
	function edit_action(){

		$this->load->model("Mconfig");
		$this->load->model("Menquire");

		$config = $this->Mconfig->get();
		$enquire = $this->Menquire->get($this->input->post("id"));

		$category = "";
		if($this->input->post("category")!=""){
			$category = implode(",",$this->input->post("category"));
		}

		$param = Array(
			"status"	=> $this->input->post("status"),
			"gubun"		=> $this->input->post("gubun"),
			"name"		=> $this->input->post("name"),
			"feature"	=> $this->input->post("feature"),
			"phone"		=> $this->input->post("phone"),
			"phone_etc1" => $this->input->post("phone_etc1"),
			"phone_etc2" => $this->input->post("phone_etc2"),
			"location"	=> $this->input->post("location"),
			"visitdate"	=>$this->input->post("visitdate"), 
			"movedate"	=>$this->input->post("movedate"),
			"price"		=> $this->input->post("price"),
			"type"		=> $this->input->post("type"),
			"category"	=> $category,
			"content"	=> $this->input->post("content"),
			"work"		=> $this->input->post("work"),
			"secret"	=> $this->input->post("secret"),
			"member_id"	=> $this->input->post("member_id"),
			"moddate"	=> date('Y-m-d H:i:s')
		);

		$this->Menquire->update($param,$this->input->post("id"));

		if($enquire->member_id != $this->input->post("member_id")){
			
			if($this->input->post("member_id")){

				$this->load->model("Mmember");
				$member = $this->Mmember->get($this->input->post("member_id"));

				if($config->sms_cnt && $member){
					$this->load->helper("sender");
					$this->load->model("Msmshistory");

					$gubun = "매도";
					if($this->input->post("gubun")=="buy"){
						$gubun = "매수";
					}				

					$msg = $this->input->post("name")."님께서 ".$gubun." 의뢰를 접수하셨습니다";
					$sms_result = sms($config->mobile,$member->phone,"",$msg);

					if($sms_result=="발송성공"){					 
						$this->Mconfig->update(Array("sms_cnt" => ($config->sms_cnt - 1)),"");
					}
					$param = Array(
						"sms_from" => $config->mobile,
						"sms_to" => $member->phone,
						"msg" => $msg,
						"type" => "A",
						"minus_count" => ($sms_result=="발송성공") ? 1 : 0,
						"result" => $sms_result,
						"page" => "user_enquire",
						"date" => date('Y-m-d H:i:s')
					);
					$this->Msmshistory->insert($param);				
				}
			}		
		}

		redirect("adminenquire/index","refresh");
	}

	function row_delete($id){
		$this->load->model("Menquire");
		$this->load->model("Menquirememo");
		$this->load->model("Menquirecontract");
		$this->load->model("Menquirehistory");
		$this->Menquire->delete_enquire($id);
		$this->Menquirecontract->delete_contract_contacts_id($id);
		$this->Menquirememo->delete_memo_enquire_id($id);
		$this->Menquirememo->delete_action_enquire_id($id);
		$this->Menquirehistory->delete_log($id);
		redirect("adminenquire/index","refresh");
	}

	/**
	 * 각종 변동 히스토리를 보여준다.
	 * type: 0=전체, C=Contact, M=Memo, L=계약
	 */
	function history($type="0", $page="0"){
		$this->load->model("Menquirehistory");

		$this->load->library('pagination');
	
		$config['base_url'] = '/adminenquire/history/' . $type . "/";
		$config['total_rows'] = $this->Menquirehistory->get_total_count($type);
		$config['per_page'] = 20;
		$config['uri_segment'] = 4;
		$config['first_link'] = '<처음';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = '마지막>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';

		$config['next_link'] = '[다음] »';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '« [이전]';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();

		$data["history"] = $this->Menquirehistory->get_list($type,$config['per_page'], $page);

		$data["type"]	 = $type;
		$this->layout->admin('enquire_history', $data);	
	}

	function event(){
        /**'blue': '#89C4F4',
        'red': '#F3565D',
        'green': '#1bbc9b',
        'purple': '#9b59b6',
        'grey': '#95a5a6',
        'yellow': '#F8CB00'
        **/
		$this->load->model("Menquirememo");
		$this->load->model("Menquirecontract");

		$calendar_search = $this->session->userdata("calendar_search");

		$result_action = new stdClass;
		$result_memo = new stdClass;
		$result_contract = new stdClass;
		$result_all = false;

		if(!$calendar_search["action"] && !$calendar_search["memo"] && !$calendar_search["contract"]){
			$result_all = true;
		}

		if($result_all || $calendar_search["action"]=="on"){
			$result_action = $this->Menquirememo->get_month_action($this->input->get("start",TRUE), $this->input->get("end",TRUE), $calendar_search);
		}

		if($result_all || $calendar_search["memo"]=="on"){
			$result_memo = $this->Menquirememo->get_month_memo($this->input->get("start",TRUE), $this->input->get("end",TRUE), $calendar_search);		
		}

		if($result_all || $calendar_search["contract"]=="on"){
			$result_contract = $this->Menquirecontract->get_month_contract($this->input->get("start",TRUE), $this->input->get("end",TRUE), $calendar_search);
		}	

		$return = Array();
		
		foreach($result_action as $val){

			$type = "";
			if($val->type=="meeting") $type = "<i class=\"glyphicon glyphicon-user\"></i>";
			if($val->type=="call") $type = "<i class=\"glyphicon glyphicon-earphone\"></i>";
			if($val->type=="etc") $type = "<i class=\"glyphicon glyphicon-list-alt\"></i>";

			array_push($return,Array(
				"title" => $type." [".$val->name."]<br/>".$val->content,
				"description"=> $val->content,
				"allday" => "false",
				"backgroundColor" => ($val->color) ? "#".$val->color : "#1bbc9b",
				"start"=>$val->actiondate,
				"end"=>$val->actiondate,
				"url"=>"/adminenquire/view/".$val->enquire_id."/contact"
			));
		}
		
		foreach($result_memo as $val){
			array_push($return,Array(
				"title" => "<i class=\"glyphicon glyphicon-pencil\"></i> [".$val->name."]<br/>".$val->content,
				"description"=> $val->content,
				"allday" => "false",
				"backgroundColor" => ($val->color) ? "#".$val->color : "#9b59b6",
				"start"=>$val->regdate,
				"end"=>$val->regdate,
				"url"=>"/adminenquire/view/".$val->enquire_id."/memo"
			));
		}
		
		foreach($result_contract as $val){
			array_push($return,Array(
				"title" => "<i class=\"glyphicon glyphicon-file\"></i> [".$val->name."]<br/>".$val->title,
				"description"=> $val->title,
				"allday" => "false",
				"backgroundColor" => ($val->color) ? "#".$val->color : "#89C4F4",
				"start"=>$val->date,
				"end"=>$val->date,
				"url"=>"/adminenquire/view/".$val->enquire_id."/contract"
			));

			//계약금지급날짜, 중도금지급날짜, 잔금지급날짜가 있을 경우엔 해당 날짜로 한번더 출력시킨다.
			if($val->contract_pay_date){
				array_push($return,Array(
					"title" => "<i class=\"glyphicon glyphicon-file\"></i> [".$val->name."] [계약금지급]<br/>".$val->title,
					"description"=> $val->title,
					"allday" => "false",
					"backgroundColor" => ($val->color) ? "#".$val->color : "#89C4F4",
					"start"=>$val->contract_pay_date,
					"end"=>$val->contract_pay_date,
					"url"=>"/adminenquire/view/".$val->enquire_id."/contract"
				));
			}
			if($val->part_pay_date){
				array_push($return,Array(
					"title" => "<i class=\"glyphicon glyphicon-file\"></i> [".$val->name."] [중도금지급]<br/>".$val->title,
					"description"=> $val->title,
					"allday" => "false",
					"backgroundColor" => ($val->color) ? "#".$val->color : "#89C4F4",
					"start"=>$val->part_pay_date,
					"end"=>$val->part_pay_date,
					"url"=>"/adminenquire/view/".$val->enquire_id."/contract"
				));
			}
			if($val->balance_pay_date){
				array_push($return,Array(
					"title" => "<i class=\"glyphicon glyphicon-file\"></i> [".$val->name."] [잔금지급]<br/>".$val->title,
					"description"=> $val->title,
					"allday" => "false",
					"backgroundColor" => ($val->color) ? "#".$val->color : "#89C4F4",
					"start"=>$val->balance_pay_date,
					"end"=>$val->balance_pay_date,
					"url"=>"/adminenquire/view/".$val->enquire_id."/contract"
				));
			}
		}

		echo json_encode($return);
	}

    /**
     * 의뢰하기 상태설정 페이지
     */
	public function status(){
		$this->load->model("Menquire");
		$data["query"] = $this->Menquire->status_category(true);
		$this->layout->admin('enquire_status', $data);
	}

    /**
     * 의뢰하기 상태설정 정렬
     */
	public function sorting($id,$sorting){
		$this->load->model("Menquire");
		$param = Array("sorting"=>$sorting);
		$this->Menquire->sorting_update($id,$param);
	}

    /**
     * 의뢰하기 상태설정 수정하기
     */
	public function status_update(){
		$this->load->model("Menquire");
		$id = $this->input->post("id");
		$param = Array(
			"label"	=> $this->input->post("label"),
			"valid"	=> $this->input->post("valid")
		);
		$this->Menquire->status_update($id,$param);
		redirect("adminenquire/status","refresh");
	}

    /**
     * 의뢰하기 상태 수정하기
     */
	function status_change(){
		$this->load->model("Menquire");

		if($this->input->post("check_id")){

			$check = array_filter($this->input->post("check_id"));	

			foreach($check as $val){
				$param = array("status"	=> $this->input->post("status"));
				$this->Menquire->update($param,$val);			
			}
		}
		redirect($_SERVER["HTTP_REFERER"],"refresh");
	}

    /**
     * 업무달력
     */
	function calendar($member_id=""){
		$this->load->model("Mmember");
		$data["calendar_search"] = $this->session->userdata("calendar_search");
		$data["members"] = $this->Mmember->get_list("admin");
		$data["member_id"] = $member_id;
		$this->layout->admin('enquire_calendar', $data);
	}

	function calendar_search(){
		$calendar_search = Array(
			"action" => ($this->input->post("action")) ? $this->input->post("action") : "",
			"meeting" => ($this->input->post("meeting")) ? $this->input->post("meeting") : "",
			"call" => ($this->input->post("call")) ? $this->input->post("call") : "",
			"etc" => ($this->input->post("etc")) ? $this->input->post("etc") : "",
			"memo" => ($this->input->post("memo")) ? $this->input->post("memo") : "",
			"contract" => ($this->input->post("contract")) ? $this->input->post("contract") : "",
			"member_id" => $this->input->post("member_id")
		);

		$this->session->set_userdata("calendar_search",$calendar_search);
	}
}

/* End of file Adminenquire.php */
/* Location: ./application/controllers/Adminenquire.php */

