<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminfront extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index(){
	
		$this->load->model("Madminfront");
		$this->load->model("Mnewscategory");
		$this->load->model("Mportfoliocategory");

		$news_option = "";
		$portfolio_option = "";

		$data["news_category"] = $this->Mnewscategory->get_list();
		$data["portfolio_category"] = $this->Mportfoliocategory->get_list();

		//뉴스카테고리 가져오기
		if(is_array($data["news_category"])){
			foreach($data["news_category"] as $news_category){
				$news_option .= "<option value='".$news_category->id."'>".$news_category->name."</option>";
			}
			$data["news_option"] = $news_option;
		}
		//포트폴리오카테고리 가져오기
		if(is_array($data["portfolio_category"])){
			foreach($data["portfolio_category"] as $portfolio_category){
				$portfolio_option .= "<option value='".$portfolio_category->id."'>".$portfolio_category->name."</option>";
			}
			$data["portfolio_option"] = $portfolio_option;
		}

		$data["query"] = $this->Madminfront->get_list();
		$this->layout->admin('front_index',$data);
	}

	public function sorting($id,$sorting){
		$this->load->model("Madminfront");
		$param = Array("sorting"=>$sorting);
		$this->Madminfront->update($id,$param);
	}

	public function get_json($id){
		$this->load->model("Madminfront");
		$query = $this->Madminfront->get($id);
		echo json_encode($query);
	}


	public function edit_action(){

		$param = Array(
			"title" => $this->input->post("title"),
			"valid" => $this->input->post("valid")
		);

		if($this->input->post("slide_code")) $param['code'] = $this->input->post("slide_code");
		else if($this->input->post("map_code")) $param['code'] = $this->input->post("map_code");
		else if($this->input->post("landing_code")) $param['code'] = $this->input->post("landing_code");
		else if($this->input->post("html_code")) $param['code'] = $this->input->post("html_code");
		else if($this->input->post("category_code")) $param['code'] = $this->input->post("category_code");
		else if($this->input->post("line_code")) $param['code'] = $this->input->post("line_code");
		else $param['code'] = "";

		if($this->input->post("top_type")){
			$top_type = $this->input->post("top_type");
			$param['module'] = $top_type;
			
			$topbar = ($top_type=="landing") ? FALSE : TRUE;

			if($top_type=="map"){
				$use_spot = ($this->input->post("use_spot")=="Y") ? TRUE : FALSE;
			}
			else{
				$use_spot = FALSE;
			}

			$this->config->load('layouts');

			$save = array (
				'theme'		=> $top_type,
				'topbar'	=> $topbar,
				'use_spot'	=> $use_spot,
				'use_home'	=> $this->config->item('use_home'),
				'intro_map'	=> $this->config->item('intro_map'),
				'view_map_use'	=> $this->config->item('view_map_use'),
				'view_map_position'	=> $this->config->item('view_map_position')
			);

			$this->load->helper('file');

			//새로 설정 파일을 쓴다.
			$header = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');";
			$footer1 = "/* intro_map(map, sky), view_map_position(middle, bottom) End of file layouts.php */";
			$footer2 = "/* Location: ./application/config/layouts.php */";
			write_file(HOME."/application/config/layouts.php", $header . "\n" . "\$config = " . var_export($save, true) . ";\n" . $footer1 . "\n" . $footer2);

		}

		$this->load->model("Madminfront");
		$this->Madminfront->update($this->input->post("id"),$param);
		redirect("adminfront/index","refresh");
	}


	public function slide_upload_action(){

		$CI =& get_instance();
		$CI->load->library('image_lib');

		$config['upload_path'] = HOME.'/uploads/slide';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload',$config);

		if ( ! $this->upload->do_upload("file")){
			$error = array('error' => $this->upload->display_errors());
			echo $this->upload->display_errors();
		} else {
			$data = array('upload_data' => $this->upload->data());
			$this->load->model("Madminfront");
			
			$sorting = $this->Madminfront->slide_get_sorting($this->session->userdata("admin_id"));

			$param = Array(
				"filename" => $data["upload_data"]["file_name"],
				"sorting" => (int)$sorting + 1,
				"regdate" => date('Y-m-d H:i:s')
			);

			$this->Madminfront->slide_insert($param);
		}

		echo "1";
	}

	/**
	 * 등록화면에서 임시로 등록하는 사진들의 목록을 가져온다.
	 */
	function slide_json(){
		$this->load->model("Madminfront");
		echo json_encode($this->Madminfront->slide_get_list());
	}

	function slide_sorting($slide_id,$sorting){
		$this->load->model("Madminfront");
		$this->Madminfront->slide_change_sorting($slide_id,$sorting);
		echo "1";
	}

	function slide_delete($slide_id){
		$this->load->model("Madminfront");
		$slide = $this->Madminfront->slide_get($slide_id);
		if( file_exists(HOME.'/uploads/slide/'. $slide->filename) ){
			unlink(HOME.'/uploads/slide/'. $slide->filename);
		}
		// DB 삭제
		$this->Madminfront->slide_delete($slide->id);
		echo "1";
	}

	public function landing_upload_action(){

		$CI =& get_instance();
		$CI->load->library('image_lib');

		$config['upload_path'] = HOME.'/uploads/landing';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload',$config);

		if ( ! $this->upload->do_upload("file")){
			$error = array('error' => $this->upload->display_errors());
			echo $this->upload->display_errors();
		} else {
			$data = array('upload_data' => $this->upload->data());
			$this->load->model("Madminfront");
			
			$sorting = $this->Madminfront->landing_get_sorting($this->session->userdata("admin_id"));

			$param = Array(
				"filename" => $data["upload_data"]["file_name"],
				"sorting" => (int)$sorting + 1,
				"regdate" => date('Y-m-d H:i:s')
			);

			$this->Madminfront->landing_insert($param);
		}

		echo "1";
	}

	/**
	 * 등록화면에서 임시로 등록하는 사진들의 목록을 가져온다.
	 */
	function landing_json(){
		$this->load->model("Madminfront");
		echo json_encode($this->Madminfront->landing_get_list());
	}

	function landing_sorting($landing_id,$sorting){
		$this->load->model("Madminfront");
		$this->Madminfront->landing_change_sorting($landing_id,$sorting);
		echo "1";
	}

	function landing_delete($landing_id){
		$this->load->model("Madminfront");
		$landing = $this->Madminfront->landing_get($landing_id);
		if( file_exists(HOME.'/uploads/landing/'. $landing->filename) ){
			unlink(HOME.'/uploads/landing/'. $landing->filename);
		}
		// DB 삭제
		$this->Madminfront->landing_delete($landing->id);
		echo "1";
	}

	function top_layout_link($id,$type){
		$this->load->model("Madminfront");
		$param = Array(
			"link" => str_replace("http://","",$this->input->post("link"))
		);
		$this->Madminfront->link_update($param,$id,$type);
	}

}

/* End of file Adminfront.php */
/* Location: ./application/controllers/Adminfront.php */