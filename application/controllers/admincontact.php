<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admincontact extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}
	
	/**
	 * ci는 페이징 검색 붙이기가 참 골치아픈 url pattern을 가지고 있다.
	 * segment를 마지막으로 지정을 한번 해보고 진행을 해보자.
	 */
	function index(){
		$data["contact"] = $this->session->userdata("contact");

		$this->load->model("Mcontactgroup");
		$data["all_cnt"] = $this->Mcontactgroup->get_all_cnt();
		$data["no_cnt"] =  $this->Mcontactgroup->get_no_cnt();
		$this->layout->admin('contact_index',$data);
	}

	function index_json($page=0){
		
		$param = Array(
			"page"=>$page,
			"keyword"=>$this->input->post("keyword"),
			"group_id"=>$this->input->post("group_id")
		);

		$this->load->model("Mcontact");
		$this->load->library('pagination');
		$config['base_url'] = "/admincontact/index_json/";
		$config['total_rows'] = $this->Mcontact->get_total_count($this->input->post("group_id"), $this->input->post("keyword"));
		$config['per_page'] = 50;
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

		
		$this->pagination->initialize($config);
		$query["paging"] = $this->pagination->create_links();

		$query["result"] = $this->Mcontact->get_list($this->input->post("group_id"), $this->input->post("keyword"),  $this->input->post("sort_name"), $this->input->post("order_by"), $config['per_page'], $page);
		$this->session->set_userdata("contact",$param);
		echo json_encode($query);
	}

	function view($id,$page=0){
		$this->load->model("Mcontact");
		$this->load->model("Mcontactproduct");
		$this->load->model("Mmember");
		$this->load->model("Mproduct");
		$this->load->model("Mmemo");

		$data["products"] = $this->Mcontactproduct->get_product_list($id);

		foreach($data["products"] as $key=>$val){
			$data["products"][$key]["subway"] = $this->Mproduct->get_product_subway($val["id"]);
		}

		$data["query"] = $this->Mcontact->get($id);
		$data["member"] = $this->Mmember->get($data["query"]->member_id); 	//해당 담당자의 정보
		$data["members"] = $this->Mmember->get_list("admin");				//전체 관리자 정보

		$data["count_memo"] = $this->Mmemo->memo_count($id);
		$data["count_contact"] = $this->Mmemo->contact_count($id);

		$this->layout->admin('contact_view', $data);	
	}

	/**
	 * 연락처 추가하기
	 */
	function add(){
		$this->load->model("Mmember");
		$this->load->model("Mcontactgroup");
		$data["group"] = $this->Mcontactgroup->get_list();
		$data["members"] = $this->Mmember->get_list("admin");

		$this->layout->admin('contact_add', $data);	
	}

	/**
	 * 연락처 추가하기전에 플래쉬데이타를 넘겨 받고 가기
	 */
	function add_flashdata($type,$id){

		if($type=="enquire"){
			$this->load->model("Menquire");
			$enquire = $this->Menquire->get($id);
			$this->session->set_flashdata('name', $enquire->name);
			$this->session->set_flashdata('phone', $enquire->phone);
		}
		if($type=="ask"){
			$this->load->model("Mask");
			$ask = $this->Mask->get($id);
			$this->session->set_flashdata('name', $ask->name);
			$this->session->set_flashdata('phone', $ask->phone);
			$this->session->set_flashdata('email', $ask->email);
		}

		if($type=="member"){
			$this->load->model("Mmember");
			$member = $this->Mmember->get($id);
			$this->session->set_flashdata('name', $member->name);
			$this->session->set_flashdata('phone', $member->phone);
			$this->session->set_flashdata('email', $member->email);
			$this->session->set_flashdata('address', $member->address." ".$member->address_detail);
		}

		redirect("admincontact/add","refresh");
	}

	/**
	 * 연락처 추가 등록
	 */
	function add_action(){
		
		$email = "";
		if($this->input->post("email")!=""){
			$type = $this->input->post("email_type");
			foreach($this->input->post("email") as $key=>$val){
				$email .= $type[$key] ."--type--". $val . "---dungzi---";
			}
		}


		$phone = "";
		if($this->input->post("phone")!=""){
			$type = $this->input->post("phone_type");
			foreach($this->input->post("phone") as $key=>$val){
				$phone .= $type[$key] ."--type--". $val . "---dungzi---";
			}
		}

		$address = "";
		if($this->input->post("address")!=""){
			$type = $this->input->post("address_type");
			foreach($this->input->post("address") as $key=>$val){
				$address .= $type[$key] ."--type--". $val . "---dungzi---";
			}
		}

		$homepage = "";
		if($this->input->post("homepage")!=""){
			$type = $this->input->post("homepage_type");
			foreach($this->input->post("homepage") as $key=>$val){
				$homepage .= $type[$key] ."--type--". $val . "---dungzi---";
			}
		}


		$param = Array(
			"name"			=> 	$this->input->post("name"),
			"role"			=> 	$this->input->post("role"),
			"organization"	=> 	$this->input->post("organization"),
			"sex"			=> 	$this->input->post("sex"),
			"group_id"		=> 	$this->input->post("group_id"),			
			"email"			=> 	$email,
			"phone"			=> 	$phone,
			"address"		=> 	$address,
			"homepage"		=> 	$homepage,
			"background"	=> 	$this->input->post("background"),
			"member_id"		=> 	$this->input->post("member_id"),
			"is_opened"		=> 	$this->input->post("is_opened"),
			"regdate"		=> date('Y-m-d H:i:s'),
			"moddate"		=> date('Y-m-d H:i:s')

		);
		
		$this->load->model("Mcontact");
		$insert_id = $this->Mcontact->insert($param);

		//**********************************************
		// 히스토리추가
		//**********************************************
		$this->load->model("Mhistory");
		$this->Mhistory->insert($insert_id,0,"C","A",$this->input->post("name")); 

		if($this->input->post("data-next")!="1"){
			redirect("admincontact/add","refresh");		
		} else {
			redirect("admincontact/index","refresh");
		}
	}

	function edit($id){
		$this->load->model("Mcontact");
		$this->load->model("Mmember");
		$this->load->model("Mcontactgroup");
		$data["group"] = $this->Mcontactgroup->get_list();

		$data["query"] = $this->Mcontact->get($id);
		$data["member"] = $this->Mmember->get($data["query"]->member_id); 	//해당 담당자의 정보
		$data["members"] = $this->Mmember->get_list("admin");					//전체 관리자 정보

		$this->layout->admin('contact_edit', $data);	
	}

	function edit_action(){
		$email = "";
		if($this->input->post("email")!=""){
			$type = $this->input->post("email_type");
			foreach($this->input->post("email") as $key=>$val){
				$email .= $type[$key] ."--type--". $val . "---dungzi---";
			}
		}


		$phone = "";
		if($this->input->post("phone")!=""){
			$type = $this->input->post("phone_type");
			foreach($this->input->post("phone") as $key=>$val){
				$phone .= $type[$key] ."--type--". $val . "---dungzi---";
			}
		}

		$address = "";
		if($this->input->post("address")!=""){
			$type = $this->input->post("address_type");
			foreach($this->input->post("address") as $key=>$val){
				$address .= $type[$key] ."--type--". $val . "---dungzi---";
			}
		}

		$homepage = "";
		if($this->input->post("homepage")!=""){
			$type = $this->input->post("homepage_type");
			foreach($this->input->post("homepage") as $key=>$val){
				$homepage .= $type[$key] ."--type--". $val . "---dungzi---";
			}
		}


		$param = Array(
			"name"			=> 	$this->input->post("name"),
			"role"			=> 	$this->input->post("role"),
			"organization"	=> 	$this->input->post("organization"),
			"sex"			=> 	$this->input->post("sex"),
			"group_id"		=> 	$this->input->post("group_id"),						
			"email"			=> 	$email,
			"phone"			=> 	$phone,
			"address"		=> 	$address,
			"homepage"		=> 	$homepage,
			"background"	=> 	$this->input->post("background"),
			"member_id"		=> 	$this->input->post("member_id"),
			"is_opened"		=> 	$this->input->post("is_opened"),
			"moddate"		=> date('Y-m-d H:i:s')
		);
		
		$this->load->model("Mcontact");
		$this->Mcontact->update($this->input->post("id"), $param);

		//**********************************************
		// 히스토리추가
		//**********************************************
		$this->load->model("Mhistory");
		$this->Mhistory->insert($this->input->post("id"),0,"C","M",$this->input->post("name")); 

		redirect("admincontact/view/".$this->input->post("id"),"refresh");
	}

	/**
	 * 각종 변동 히스토리를 보여준다.
	 * type: 0=전체, C=Contact, M=Memo, T=Task
	 */
	function history($type="0", $page="0"){
		$this->load->model("Mhistory");
		$this->load->model("Mtask");

		$this->load->library('pagination');
	
		$config['base_url'] = '/admincontact/history/' . $type . "/";
		$config['total_rows'] = $this->Mhistory->get_total_count($type);
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

		$data["history"] = $this->Mhistory->get_list($type,$config['per_page'], $page);
		$data["task"]	 = $this->Mtask->get_list_by_member($this->session->userdata("admin_id"));
		$data["type"]	 = $type;
		$this->layout->admin('contact_history', $data);	
	}

	function event(){
        /**'blue': '#89C4F4',
        'red': '#F3565D',
        'green': '#1bbc9b',
        'purple': '#9b59b6',
        'grey': '#95a5a6',
        'yellow': '#F8CB00'
        **/
		$this->load->model("Mmemo");
		$result_action = $this->Mmemo->get_month_action($this->input->get("start",TRUE), $this->input->get("end",TRUE));
		$result_memo = $this->Mmemo->get_month_memo($this->input->get("start",TRUE), $this->input->get("end",TRUE));

		$this->load->model("Mtask");
		$result_task = $this->Mtask->get_month_task($this->input->get("start",TRUE), $this->input->get("end",TRUE));

		$return = Array();
		foreach($result_action as $val){
			array_push($return,Array(
				"title" => $val->content,
				"description"=> $val->content,
				"allday" => "false",
				"backgroundColor" => "#1bbc9b",
				"start"=>$val->actiondate,
				"end"=>$val->actiondate,
			));
		}

		foreach($result_memo as $val){
			array_push($return,Array(
				"title" => $val->content,
				"description"=> $val->content,
				"allday" => "false",
				"backgroundColor" => "#9b59b6",
				"start"=>$val->regdate,
				"end"=>$val->regdate,
			));
		}

		foreach($result_task as $val){
			array_push($return,Array(
				"title" => $val->title,
				"description"=> $val->content,
				"allday" => "false",
				"backgroundColor" => "#89C4F4",
				"start"=>$val->regdate,
				"end"=>$val->regdate,
			));
		}
		echo json_encode($return);
	}

	function row_delete($id){
		$this->load->model("Mcontact");
		$this->load->model("Mmemo");
		$this->load->model("Mtask");
		$this->load->model("Mhistory");
		$this->Mcontact->delete_contact($id);
		$this->Mtask->delete_task_contacts_id($id);
		$this->Mmemo->delete_memo_contacts_id($id);
		$this->Mmemo->delete_action_contacts_id($id);
		$this->Mhistory->delete_log($id);
		redirect("admincontact/index","refresh");
	}

	function contact_member(){
		$this->load->model("Mcontact");
		$data = $this->Mcontact->contact_member_list($this->input->post("search"));
		echo json_encode($data);
	}
}

/* End of file Admincontact.php */
/* Location: ./application/controllers/Admincontact.php */

