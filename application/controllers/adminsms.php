<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminsms extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

    /**
     * 전송된 문자 내역 보기
     */
    function history($page=0){

        $this->load->library('pagination');

		$this->load->model("Msmshistory");

        $config['base_url'] = "/adminsms/history/";
        $config['total_rows'] = $this->Msmshistory->get_total_count();
        $config['per_page'] = 20;
        $config['first_link'] = '<<';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '>>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['num_tag_open'] = "<li>";
        $config['num_tag_close'] = "</li>";
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data["pagination"] = $this->pagination->create_links();

        $data['total'] = $config['total_rows'];
        $data['query'] = $this->Msmshistory->get_list( $config['per_page'], $page);

        $this->layout->admin('sms_history', $data);
    }

    /**
     * 수신자 내역 보기
     */
    function history_view($id){
        $this->load->model("Msmshistory");
		$data["query"] = $this->Msmshistory->get($id);

		if($data["query"]->sms_to){
			$sms_to = "";
			$phone = @explode(",",$data["query"]->sms_to);
			foreach($phone as $key=>$val){
				if($key % 5==0){
					$sms_to .= "\n".$val;
				}
				else{
					$sms_to .= "\t".$val;
				}
			}
			$data["query"]->sms_to = $sms_to;
		}
        $this->layout->admin('sms_history_view', $data);
    }

    /**
     * 선택 문자 보내기
     */
    function select_send(){

		$this->load->helper("sender");

		$this->load->model("Mconfig");
		$this->load->model("Mmember");
		$this->load->model("Mcontact");
		$this->load->model("Menquire");
		$this->load->model("Msmshistory");

		$config = $this->Mconfig->get();

		switch($this->input->post("send_page")){
			case "member":
				if($this->input->post("send_all_type")){
					$query = $this->Mmember->get_list($this->input->post("send_all_type"));
				}
				else{
					$query = $this->Mmember->get_list_in($this->input->post("check_id"));	
				}
				$phone = $this->member_phone($query);
				break;
			case "contact":
				if($this->input->post("send_all_type")){
					$send_all_type = ($this->input->post("send_all_type")=="all") ? "" : $this->input->post("send_all_type");
					$query = $this->Mcontact->get_list_obj($send_all_type);
				}
				else{
					$query = $this->Mcontact->get_list_in($this->input->post("check_id"));				
				}
				$phone = $this->contact_phone($query);
				break;
			case "enquire":
				if($this->input->post("send_all_type")){					
					$send_all_type = ($this->input->post("send_all_type")=="all") ? "" : $this->input->post("send_all_type");
					$query = $this->Menquire->get_list_obj($send_all_type);
				}
				else{
					$query = $this->Menquire->get_list_in($this->input->post("check_id"));
				}
				$phone = $this->enquire_phone($query);
				break;
			default :
				exit;
				break;
		}

		$phone = $this->phone_valid($phone);

		$sms_subject = $this->input->post("sms_subject");
		$sms_msg = $this->input->post("sms_msg");
		$sms_from = $config->mobile;

		$sms_to = $phone;

		$date = "";
		if($this->input->post("reserve")=="yes"){
			$date = $this->input->post("r_date")." ".$this->input->post("r_time");
		}

		if($this->input->post("sms_type")=="D"){

			$file_path = HOME.'/uploads/mms_file/';

			if(!file_exists($file_path)){
				mkdir($file_path,0777);
				chmod($file_path,0777);
			}

			$img_config['upload_path'] = $file_path;
			$img_config['allowed_types'] = 'jpg|jpeg';
			$img_config['encrypt_name'] = TRUE;
			$this->load->library('upload', $img_config);

			$filename = "";
			if (!$this->upload->do_upload("mms_file")){
				echo $this->image_lib->display_errors();
				return false;
			}
			else{
				$data = array('upload_data' => $this->upload->data());
				$filename = $data["upload_data"]["file_name"];
			}
			if($date) $result = mms_rv($sms_from,$sms_to,$sms_subject,$sms_msg,$filename);
			else $result = mms($sms_from,$sms_to,$sms_subject,$sms_msg,$filename,$date);
		}
		else{
			if($date) $result = sms_rv($sms_from,$sms_to,$sms_subject,$sms_msg,$date,$this->input->post("sms_type"));
			else $result = sms($sms_from,$sms_to,$sms_subject,$sms_msg,$this->input->post("sms_type"));
		}

		if($result=="발송성공"){
			if($this->input->post("sms_type")=="A") $minus_count = count($sms_to);
			else if($this->input->post("sms_type")=="C") $minus_count = count($sms_to) * 3;
			else if($this->input->post("sms_type")=="D") $minus_count = count($sms_to) * 10;
			else $minus_count = count($sms_to);
						 
			$this->Mconfig->update(Array("sms_cnt" => ($config->sms_cnt - $minus_count)),"");
		}

		$param = Array(
			"sms_from" => $sms_from,
			"sms_to" => @implode(",",$sms_to),
			"msg" => $sms_msg,
			"type" => $this->input->post("sms_type"),
			"minus_count" => (isset($minus_count)) ? $minus_count : 0,
			"result" => $result,
			"page" => $this->input->post("send_page"),
			"member_id" => $this->session->userdata("admin_id"),
			"date" => date('Y-m-d H:i:s')
		);
		$this->Msmshistory->insert($param);

		echo $result;
	}

    /**
     * 나에게 보내기
     */
    function self_send(){
		$this->load->helper("sender");

		$this->load->model("Mconfig");
		$this->load->model("Menquire");
		$this->load->model("Msmshistory");

		$config = $this->Mconfig->get();

		$sms_subject = $this->input->post("sms_subject");
		$sms_msg = $this->input->post("sms_msg");
		$sms_from = $config->mobile;

		$sms_to = Array($this->session->userdata("admin_phone"));

		$date = "";
		if($this->input->post("reserve")=="yes"){
			$date = $this->input->post("r_date")." ".$this->input->post("r_time");
		}

		if($this->input->post("sms_type")=="D"){

			$file_path = HOME.'/uploads/mms_file/';

			if(!file_exists($file_path)){
				mkdir($file_path,0777);
				chmod($file_path,0777);
			}

			$img_config['upload_path'] = $file_path;
			$img_config['allowed_types'] = 'jpg|jpeg';
			$img_config['encrypt_name'] = TRUE;
			$this->load->library('upload', $img_config);

			$filename = "";
			if (!$this->upload->do_upload("mms_file")){
				echo $this->image_lib->display_errors();
				return false;
			}
			else{
				$data = array('upload_data' => $this->upload->data());
				$filename = $data["upload_data"]["file_name"];
			}
			if($date) $result = mms_rv($sms_from,$sms_to,$sms_subject,$sms_msg,$filename);
			else $result = mms($sms_from,$sms_to,$sms_subject,$sms_msg,$filename,$date);
		}
		else{
			if($date) $result = sms_rv($sms_from,$sms_to,$sms_subject,$sms_msg,$date,$this->input->post("sms_type"));
			else $result = sms($sms_from,$sms_to,$sms_subject,$sms_msg,$this->input->post("sms_type"));
		}

		if($result=="발송성공"){
			if($this->input->post("sms_type")=="A") $minus_count = count($sms_to);
			else if($this->input->post("sms_type")=="C") $minus_count = count($sms_to) * 3;
			else if($this->input->post("sms_type")=="D") $minus_count = count($sms_to) * 10;
			else $minus_count = count($sms_to);
						 
			$this->Mconfig->update(Array("sms_cnt" => ($config->sms_cnt - $minus_count)),"");
		}

		$param = Array(
			"sms_from" => $sms_from,
			"sms_to" => @implode(",",$sms_to),
			"msg" => $sms_msg,
			"type" => $this->input->post("sms_type"),
			"minus_count" => (isset($minus_count)) ? $minus_count : 0,
			"result" => $result,
			"page" => $this->input->post("send_page"),
			"member_id" => $this->session->userdata("admin_id"),
			"date" => date('Y-m-d H:i:s')
		);

		$this->Msmshistory->insert($param);

		echo $result;
    }

    /**
     * 회원 데이타 중 휴대번호만 추출하여 배열로 반환
     */
    function member_phone($data){
		$phone = Array();
		if(count($data)){
			foreach($data as $row){
				$phone[] = $row->phone;
			}
			return $phone;
		}
    }

    /**
     * 고객 관리데이타 중 휴대번호만 추출하여 배열로 반환
     */
    function contact_phone($data){
		$phone = Array();
		if(count($data)){
			foreach($data as $rows){
				$rows = @explode("---dungzi---",$rows->phone);
				foreach($rows as $val){
					$row = @explode("--type--",$val);
					if( $row[0]=="mobile" && isset($row[1]) && $row[1]!=""){
						$phone[] = $row[1];
						break;
					}
				}
			}
			return $phone;
		}
    }

    /**
     * 의뢰하기 데이타 중 휴대번호만 추출하여 배열로 반환
     */
    function enquire_phone($data){
		$phone = Array();
		if(count($data)){
			foreach($data as $row){
				$phone[] = $row->phone;
			}
			return $phone;
		}
    }

    /**
     * 휴대번호 유효성 검사 후 제거
	 * 중복된 연락처 값 제거
     */
    function phone_valid($data){
		$phone = Array();
		$phone_valid = Array("010" , "011" , "016" , "017" , "019");
		if(count($data)){
			$data = array_values(array_unique($data));
			foreach($data as $val){
				if(in_array(substr($val,0,3),$phone_valid)){
					$phone[] = $val;
				}
			}
			return $phone;
		}
    }

    /**
     * 전체 전송시 카운트 얻어오기
     */
	function send_all_count($send_page, $send_all_type){
		$this->load->model("Mmember");
		$this->load->model("Mcontact");
		$this->load->model("Menquire");

		$count = 0;
		switch($send_page){
			case "member":
				$count = $this->Mmember->get_total_count($send_all_type);
				break;
			case "contact":
				$send_all_type = ($send_all_type=="all") ? "" : $send_all_type;
				$count = $this->Mcontact->get_list_obj($send_all_type,true);
				break;
			case "enquire":
				$send_all_type = ($send_all_type=="all") ? "" : $send_all_type;
				$count = $this->Menquire->get_list_obj($send_all_type,true);
				break;
			default :
				break;
		}
		echo $count;
	}
}

/* End of file adminsms.php */
/* Location: ./application/controllers/adminsms.php */


