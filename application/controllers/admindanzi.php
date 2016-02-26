<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admindanzi extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	/**
	 * 단지 목록
	 */
	function index($page=0){
		$this->load->model("Mdanzi");

		$search = Array(
			"sido"	=> $this->input->get("sido"),
			"gugun" => $this->input->get("gugun"),
			"dong"	=> $this->input->get("dong"),
			"keyword" => $this->input->get("keyword")
		);

		$this->load->library('pagination');

		$config['base_url'] = "/admindanzi/index";
		$config['total_rows'] = $this->Mdanzi->get_total_count($search);
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
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
		$data['query'] = $this->Mdanzi->get_list($search, $config['per_page'], $page);

		$this->layout->admin('danzi_index', $data);
	}

	/**
	 * 단지 정보 가져오기
	 */
	public function get_json($id){
		$this->load->model("Mdanzi");
		$this->load->model("Maddress");

		$query = $this->Mdanzi->get($id);

		$address = $this->Maddress->get($query->address_id);

		$query->current_sido = $address->sido;
		$query->current_gugun = $address->parent_id;
		$query->current_dong = $address->id;

		echo json_encode($query);
	}

	/**
	 * 단지 정보 등록
	 */
	public function add_action(){

		$this->load->model("Mdanzi");

		$config['upload_path'] = HOME.'/uploads/danzi';
		if(!file_exists($config['upload_path'])){
			mkdir($config['upload_path'],0777);
			chmod($config['upload_path'],0777);
		}
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);

		//프로필 사진
		$pyeong_img = "";
		if(!$this->upload->do_upload("pyeong_img")){
			$error = array('error' => $this->upload->display_errors());
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$pyeong_img = $data["upload_data"]["file_name"];
			$this->make_thumb($data,890);
		}

		$param = Array(
			"address_id"	=> $this->input->post("address_id"),
			"bunzi"			=> $this->input->post("bunzi"),
			"name"			=> $this->input->post("name"),
			"area"			=> $this->input->post("area"),
			"salesprice"	=> $this->input->post("salesprice"),
			"d_price"		=> $this->input->post("d_price"),
			"u_price"		=> $this->input->post("u_price"),
			"lat"			=> $this->input->post("lat"),
			"lng"			=> $this->input->post("lng"),
			"pyeong_img"	=> $pyeong_img,
			"lastupdated"	=> date('Y-m-d H:i:s')
		);

		$this->Mdanzi->insert($param);
		redirect("admindanzi/index","refresh");
	}

	private function make_thumb($data, $width=890){

		if($data["upload_data"]["image_width"] < $width){	
			$width = $data["upload_data"]["image_width"];
		}

		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME.'/uploads/danzi/'.$data["upload_data"]["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/danzi/'.$data["upload_data"]["file_name"];
		$thumb_config['create_thumb'] = TRUE;
		$thumb_config['thumb_marker'] = "";
		$thumb_config['maintain_ratio'] = TRUE;
		$thumb_config['width'] = $width;
		$thumb_config['height'] = intval($data["upload_data"]["image_height"])*$thumb_config['width']/intval($data["upload_data"]["image_width"]);
		$thumb_config['quality'] = "100%";

		$CI =& get_instance();
		$CI->load->library('image_lib');
		$CI->image_lib->initialize($thumb_config);
		$CI->image_lib->resize();
	}

	/**
	 * 단지 정보 수정
	 */
	public function edit_action(){

		$this->load->model("Mdanzi");

		$config['upload_path'] = HOME.'/uploads/danzi';
		if(!file_exists($config['upload_path'])){
			mkdir($config['upload_path'],0777);
			chmod($config['upload_path'],0777);
		}
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);

		//프로필 사진
		$pyeong_img = "";
		if(!$this->upload->do_upload("pyeong_img")){
			$error = array('error' => $this->upload->display_errors());
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$pyeong_img = $data["upload_data"]["file_name"];
			$this->make_thumb($data,890);
		}

		if($pyeong_img){
			$query = $this->Mdanzi->get($this->input->post("id"));
			@unlink($config['upload_path']."/".$query->pyeong_img);			
		}

		$param = Array(
			"address_id"	=> $this->input->post("address_id"),
			"bunzi"			=> $this->input->post("bunzi"),
			"name"			=> $this->input->post("name"),
			"area"			=> $this->input->post("area"),
			"salesprice"	=> $this->input->post("salesprice"),
			"d_price"		=> $this->input->post("d_price"),
			"u_price"		=> $this->input->post("u_price"),
			"lat"			=> $this->input->post("lat"),
			"lng"			=> $this->input->post("lng"),
			"pyeong_img"	=> $pyeong_img,
			"lastupdated"	=> date('Y-m-d H:i:s')
		);

		$this->Mdanzi->update($this->input->post("id"),$param);
		redirect($_SERVER["HTTP_REFERER"],"refresh");
	}

	/**
	 * 단지 삭제
	 */
	public function delete_action(){

		$this->load->model("Mdanzi");

		$check_danzi = $this->input->post('check_danzi');

		$this->Mdanzi->delete($check_danzi);

		redirect("admindanzi/index","refresh");
	}

	/**
	 * 단지 전체 삭제
	 */
	public function delete_all_action(){

		$this->load->model("Mdanzi");

		$this->Mdanzi->delete_all();

		redirect("admindanzi/index","refresh");
	}
}

/* End of file admindanzi.php */
/* Location: ./application/controllers/admindanzi.php */