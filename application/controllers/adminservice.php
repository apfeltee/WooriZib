<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 메인페이지에서 서비스 소개 박스 기능을 위한 Controller
 *
 *
 * @package		CodeIgniter
 * @subpackage	Controller
 * @author		Dejung Kang
 */
class Adminservice extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index(){
		$this->load->model("Mservice");
		$data["query"] = $this->Mservice->get_list();
		$this->layout->admin('service_index',$data);
	}

	public function sorting($id,$sorting){
		$this->load->model("Mservice");
		$param = Array("sorting"=>$sorting);
		$this->Mservice->update($id,$param);
	}

	public function get_json($id){
		$this->load->model("Mservice");
		$query = $this->Mservice->get($id);
		echo json_encode($query);
	}

	public function get_others_json($id){
		$this->load->model("Mservice");
		$query = $this->Mservice->get_others($id);
		echo json_encode($query);
	}

	/**
	 * 이미지는 그냥 theme이랑 같이 쓰자.
	 */
	public function add_action(){

		$this->load->model("Mservice");

		$config['upload_path'] = HOME.'/uploads/theme';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		
		$image = "";
		if(!$this->upload->do_upload("image")){
			$error = array('error' => $this->upload->display_errors());
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$image = $data["upload_data"]["file_name"];
			$this->make_thumb($data, 800);
		}

		$sorting = $this->Mservice->get_max_sorting();

		$param = Array(
			"service_name" => $this->input->post("service_name"),
			"link" => $this->input->post("link"),
			"target" => $this->input->post("target"),
			"flag" => $this->input->post("flag"),
			"col" => $this->input->post("col"),
			"image" => $image,
			"description" => $this->input->post("description"),
			"sorting" => $sorting + 1
		);

		$this->Mservice->insert($param);
		redirect("adminservice/index","refresh");
	}

	public function edit_action(){

		$this->load->model("Mservice");

		$config['upload_path'] = HOME.'/uploads/theme';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		
		$image = "";
		if(!$this->upload->do_upload("image")){
			$error = array('error' => $this->upload->display_errors());
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$image = $data["upload_data"]["file_name"];
			$this->make_thumb($data, 800);
		}

		if($image){
			$service = $this->Mservice->get($this->input->post("id"));
			if($service->image) @unlink($config['upload_path']."/".$service->image);
		}

		$param = Array(
			"service_name" => $this->input->post("service_name"),
			"link" => $this->input->post("link"),
			"target" => $this->input->post("target"),			
			"flag" => $this->input->post("flag"),
			"col" => $this->input->post("col"),
			"description" => $this->input->post("description")
		);

		if($image){
			$param["image"] = $image;
		}

		$this->Mservice->update($this->input->post("id"),$param);
		redirect("adminservice/index","refresh");
	}

	private function make_thumb($data, $width=800){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$thumb_config['source_image'] = HOME.'/uploads/theme/'.$data["upload_data"]["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/theme/'.$data["upload_data"]["file_name"];

		if($data["upload_data"]["image_width"] > $width){
			$thumb_config['image_library'] = 'gd2';
			$thumb_config['create_thumb'] = TRUE;
			$thumb_config['thumb_marker'] = "";
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['width'] = $width;
			$thumb_config['height'] = intval($data["upload_data"]["image_height"])*$thumb_config['width']/intval($data["upload_data"]["image_width"]);
			$thumb_config['quality'] = $config->QUALITY;

			$CI =& get_instance();
			$CI->load->library('image_lib');
			$CI->image_lib->initialize($thumb_config);

			if ( ! $CI->image_lib->resize())
			{
				echo $CI->image_lib->display_errors();
				return "0";
			} else {
				return "1";
			}
		} else {
			copy($thumb_config['source_image'],$thumb_config['new_image']);
		}
	}

	public function delete_action($id){
		$this->load->model("Mservice");
		$service = $this->Mservice->get($id);
		if($service->image){
			@unlink(HOME."/uploads/theme/".$service->image);
		}

		$this->Mservice->delete_area($id);
		redirect("adminservice/index","refresh");
	}
}

/* End of file adminservice.php */
/* Location: ./application/controllers/adminservice.php */