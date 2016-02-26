<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Real Estate Theme Admin Control Class
 *
 *
 * @package		CodeIgniter
 * @subpackage	Controller
 * @author		Dejung Kang
 */
class Admintheme extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index(){
		$this->load->model("Mtheme");
		$data["query"] = $this->Mtheme->get_list();
		$this->layout->admin('theme_index',$data);
	}

	public function sorting($id,$sorting){
		$this->load->model("Mtheme");
		$param = Array("sorting"=>$sorting);
		$this->Mtheme->update($id,$param);
	}

	public function get_json($id){
		$this->load->model("Mtheme");
		$query = $this->Mtheme->get($id);
		echo json_encode($query);
	}

	public function get_others_json($id){
		$this->load->model("Mtheme");
		$query = $this->Mtheme->get_others($id);
		echo json_encode($query);
	}

	public function add_action(){

		$this->load->model("Mtheme");

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

		$sorting = $this->Mtheme->get_max_sorting();

		$param = Array(
			"theme_name" => $this->input->post("theme_name"),
			"col" => $this->input->post("col"),
			"image" => $image,
			"description" => $this->input->post("description"),
			"sorting" => $sorting + 1
		);

		$this->Mtheme->insert($param);
		redirect("admintheme/index","refresh");
	}

	public function edit_action(){

		$this->load->model("Mtheme");

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
			$theme = $this->Mtheme->get($this->input->post("id"));
			if($theme->image) @unlink($config['upload_path']."/".$theme->image);
		}

		$param = Array(
			"theme_name" => $this->input->post("theme_name"),
			"col" => $this->input->post("col"),
			"description" => $this->input->post("description")
		);

		if($image){
			$param["image"] = $image;
		}

		$this->Mtheme->update($this->input->post("id"),$param);
		redirect("admintheme/index","refresh");
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

	public function delete_action(){
		$this->load->model("Mtheme");
		$theme = $this->Mtheme->get($this->input->post("delete_id"));
		if($theme->image){
			@unlink(HOME."/uploads/theme/".$theme->image);
		}
		$this->Mtheme->change_area_products($this->input->post("delete_id"),$this->input->post("change_id"));
		$this->Mtheme->delete_area($this->input->post("delete_id"));
		redirect("admintheme/index","refresh");
	}
}

/* End of file admintheme.php */
/* Location: ./application/controllers/admintheme.php */