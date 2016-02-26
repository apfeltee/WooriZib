<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminnews extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	function index($category="0", $page="0"){
		$this->load->library('pagination');
		$this->load->model("Mnews");
		$this->load->model("Mnewscategory");

		$config['base_url'] = '/adminnews/index/' . $category . "/";
		$config['total_rows'] = $this->Mnews->get_total_count($category,"admin");

		$config['per_page'] = 20;
		//$config['uri_segment'] = count($this->uri->segment_array());
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

		$data["category"] = $this->Mnewscategory->get_list();

		$data["category_id"] = $category;

		$data["pagination"] = $this->pagination->initialize($config);
		$data["result"] = $this->Mnews->get_list($category,$config['per_page'], $page,"admin");

		//첨부파일
		if(count($data['result'])){
			foreach($data['result'] as $key=>$val){
				$data['result'][$key]->attachment = $attachment_count = $this->Mnews->get_attachment_list($val->id);
			}		
		}

		$this->layout->admin('news_index', $data);
	}

	/**
	 * 매물 추가 화면
	 */
	function add(){
		$this->load->model("Mmember");
		$this->load->model("Mnewscategory");
		$data["members"] = $this->Mmember->get_list("admin");
		$data["category"] = $this->Mnewscategory->get_list();

		$this->layout->admin('news_add', $data);
	}

	function add_action(){

		$config['upload_path'] = HOME.'/uploads/news';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		
		$thumb_name = ""; //대표이미지
		if ( ! $this->upload->do_upload("thumb_name"))
		{
			$error = array('error' => $this->upload->display_errors());
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$thumb_name = $data["upload_data"]["file_name"];

			$this->make_thumb($data,$this->input->post("member_id"), 890, "");		//본문에 사용
			$this->make_thumb($data,$this->input->post("member_id"), 300, "thumb/"); //리스트에 사용
		}

		$param = Array(
			"title"			=> $this->input->post("title"),
			"thumb_name"	=> $thumb_name,
			"category"		=> $this->input->post("category"),
			"content"		=> $this->input->post("content"),
			"tag"			=> $this->input->post("tag"),
			"product_print"	=> $this->input->post("product_print"),
			"member_id"		=> $this->input->post("member_id"),
			"is_activated" 	=> $this->input->post("is_activated"),
			"date"			=> date('Y-m-d H:i:s')
		);

		$this->load->model("Mnews");
		$idx = $this->Mnews->insert($param);

		//네이버 신디케이션 전송
		$this->load->helper("syndi");
		send_ping($idx,"news");

		if(count($_FILES)>0){

	 		$this->load->library('upload');
			$folder = HOME.'/uploads/news/attachment';
	 		$this->upload->initialize(array(
				"upload_path"   => $folder."/".$idx,
				"allowed_types" => 'doc|docx|hwp|ppt|pptx|pdf|zip|txt|jpg|png',
				"encrypt_name"	=> TRUE
			 ));
			
			if(!file_exists($folder)){
				mkdir($folder,0777);
				chmod($folder,0777);
			}

			if(!file_exists($folder."/".$idx)){
				mkdir($folder."/".$idx,0777);
				chmod($folder."/".$idx,0777);
			}

	 		if($this->upload->do_multi_upload("file")) {

	            foreach($this->upload->get_multi_upload_data() as $val){
					$param = Array(
						"news_id"=> $idx,
						"originname"=> $val["orig_name"],
						"filename"	=> $val["file_name"],
						"file_ext"	=> $val["file_ext"],
						"file_size"	=> $val["file_size"],
						"regdate" 	=> date('Y-m-d H:i:s')
					);
					$this->Mnews->insert_attachment($param);
	            }
	        }
	    }

		redirect("adminnews/index","refresh");
	}

	/**
	 * 대표 이미지 썸네일 만들기
	 * 2015-2-16: news의 thumb은 정사각형으로 만든다.
	 * 2015-6-09: 이미지 축소 후 크롭하는데 오류가 발생하였다.
	 */
	private function make_thumb($data, $member_id, $limit_size=300, $folder="")
	{

		//높이 대비 넓이의 비율
		// $dim은 width가 더 길 경우에는 1보다 크고, height가 더 길 경우에는 1보다 작고, width와 height가 같을 때에는 1이다.
		$dim = (intval($data["upload_data"]["image_width"]) / intval($data["upload_data"]["image_height"]));

		//가로형 사진은 양수, 세로형 사진은 음수
		if($dim > 1) {
			$thumb_config['master_dim'] = "width";
		} else if($dim < 1) {
			$thumb_config['master_dim'] = "height";
		} else {
			$thumb_config['master_dim'] = "same";
		}


		if($thumb_config['master_dim']=="height"){
			
			$thumb_config['width'] = $limit_size;
			$thumb_config['height'] = $thumb_config['width']/$dim;

		} else if($thumb_config['master_dim']=="width"){

			$thumb_config['height'] = $limit_size;
			$thumb_config['width'] = $thumb_config['height']*$dim;

		} else if($thumb_config['master_dim']=="same"){

			$thumb_config['height'] = $limit_size;
			$thumb_config['width'] = $limit_size;

		} else {
			exit;
		}

		if($data["upload_data"]["image_width"] < $limit_size){	
			$thumb_config['width'] = $data["upload_data"]["image_width"];
		}

		if($data["upload_data"]["image_height"] < $limit_size){	
			$thumb_config['height'] = $data["upload_data"]["image_height"];
		}		

		//짧은 쪽이 300픽셀에 맞춰서 썸네일을 만듬
		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME.'/uploads/news/'. $data["upload_data"]["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/news/'.$folder.$data["upload_data"]["file_name"];
		$thumb_config['create_thumb'] = TRUE;
		$thumb_config['thumb_marker'] = "";
		$thumb_config['maintain_ratio'] = TRUE;
		$thumb_config['quality'] = "100%";


		//300을 초과하는 영역을 잘라냄
		$CI =& get_instance();
		$CI->load->library('image_lib');

		$CI->image_lib->initialize($thumb_config);
		if ( ! $CI->image_lib->resize())
		{
			echo $CI->image_lib->display_errors();
			echo "fail";

			return "0";
		} else {

			if($folder=="thumb/"){
				//크기에 맞춰서 크롭
				$thumb_config['maintain_ratio'] = FALSE;
				$thumb_config['source_image'] = HOME.'/uploads/news/'.$folder.$data["upload_data"]["file_name"];
				$thumb_config['new_image']	  = HOME.'/uploads/news/'.$folder.$data["upload_data"]["file_name"];
				$thumb_config['width'] = 300;
				$thumb_config['height'] = 300;
				$CI->image_lib->initialize($thumb_config);
				$CI->image_lib->crop();
			}
			return "1";
		}
	}

	/**
	 * 블로그 수정
	 */
	function edit($id){
		$this->load->model("Mnews");
		$this->load->model("Mnewscategory");
		$this->load->model("Mmember");

		$data["members"] = $this->Mmember->get_list("admin");
		$data["query"] = $this->Mnews->get($id);
		$data["category"] = $this->Mnewscategory->get_list();
		$data["attachment"] = $this->Mnews->get_attachment_list($id);

		$this->layout->admin('news_edit', $data);
	}

	/**
	 * 매물 상세 보기 화면
	 * 
	 * 20141007 - 관리자 권한 또는 자신이 작성한 매물이 아닐 경우에는 비정상 접속으로 홈으로 보내버린다.
	 */
	function view($id){
		$this->load->model("Mnews");
		$this->load->model("Mmember");
		$this->load->model("Mblogapi");		

		$data["blog"]  = $this->Mblogapi->get_valid_list();

		$data["query"] = $this->Mnews->get($id);
		$data["member"] = $this->Mmember->get($data["query"]->member_id);
		$data["attachment"] = $this->Mnews->get_attachment_list($id);
		
		$this->layout->admin('news_view', $data);	
	}

	function edit_action(){

		$config['upload_path'] = HOME.'/uploads/news';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		
		$thumb_name = "";
		if ( ! $this->upload->do_upload("thumb_name"))
		{
			$error = array('error' => $this->upload->display_errors());
			//echo $this->upload->display_errors();
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$thumb_name = $data["upload_data"]["file_name"];

			$this->make_thumb($data, $this->input->post("member_id"), 890, "");			//본문에 사용
			$this->make_thumb($data, $this->input->post("member_id"), 300, "thumb/"); 	//리스트에 사용
		}

		$param = Array(
			"title"		=> $this->input->post("title"),
			"category"	=> $this->input->post("category"),
			"content"	=> $this->input->post("content"),
			"tag"		=> $this->input->post("tag"),
			"product_print"	=> $this->input->post("product_print"),
			"member_id"	=> $this->input->post("member_id")
		);

		$this->load->model("Mnews");
		
		if($thumb_name!=""){
			$news = $this->Mnews->get($this->input->post("id"));
			//기존 파일 제거
			@unlink($config['upload_path']."/".$news->thumb_name);
			@unlink($config['upload_path']."/thumb/".$news->thumb_name);
			$param["thumb_name"] = $thumb_name;
		}

		$this->Mnews->update($param,$this->input->post("id"));

		//네이버 신디케이션 전송
		$this->load->helper("syndi");
		send_ping($this->input->post("id"),"news");

		if(count($_FILES)>0){

	 		$this->load->library('upload');
			$folder = HOME.'/uploads/news/attachment';
	 		$this->upload->initialize(array(
				"upload_path"   => $folder."/".$this->input->post("id"),
				"allowed_types" => 'doc|docx|hwp|ppt|pptx|pdf|zip|txt|jpg|png',
				"encrypt_name"	=> TRUE
			 ));
			
			if(!file_exists($folder)){
				mkdir($folder,0777);
				chmod($folder,0777);
			}

			if(!file_exists($folder."/".$this->input->post("id"))){
				mkdir($folder."/".$this->input->post("id"),0777);
				chmod($folder."/".$this->input->post("id"),0777);
			}

	 		if($this->upload->do_multi_upload("file")) {

	            foreach($this->upload->get_multi_upload_data() as $val){
					$param = Array(
						"news_id"=> $this->input->post("id"),
						"originname"=> $val["orig_name"],
						"filename"	=> $val["file_name"],
						"file_ext"	=> $val["file_ext"],
						"file_size"	=> $val["file_size"],
						"regdate" 	=> date('Y-m-d H:i:s')
					);
					$this->Mnews->insert_attachment($param);
	            }
	        }
	    }

		redirect("adminnews/index","refresh");
	}

	/**
	 * 에디터에서 이미지를 업로드할 때 실행된다.
	 */
	public function upload_action(){
		$config['upload_path'] = HOME.'/uploads/news/contents/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		$filename = "";
		if ( ! $this->upload->do_upload("uploadfile"))
		{
			echo $CI->image_lib->display_errors();
			return false;
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$filename = $data["upload_data"]["file_name"];
			$this->make_body($data);
			echo "/uploads/news/contents/". $data["upload_data"]["file_name"];
		}
	}

	private function make_body($data){
		//썸네일 만들기
		if($data["upload_data"]["image_width"] > 890){
			$thumb_config['image_library'] = 'gd2';
			$thumb_config['source_image'] = HOME."/uploads/news/contents/". $data["upload_data"]["file_name"];
			$thumb_config['create_thumb'] = FALSE;
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['width'] = 890;
			$thumb_config['height'] = intval($data["upload_data"]["image_height"])*$thumb_config['width']/intval($data["upload_data"]["image_width"]);
			$thumb_config['quality'] = "100%";
			$thumb_config['overwrite'] = TRUE;

			$CI =& get_instance();
			$CI->load->library('image_lib');
			$CI->image_lib->initialize($thumb_config);

			if ( ! $CI->image_lib->resize())
			{
				$CI->image_lib->display_errors();
				return false;
			} 
		}
	}

	/**
	 * 물건의 상태 변경
	 * $type : is_activated, is_finished, is_speed, recommand
	 */
	function change($type, $id, $status){
		$this->load->model("Mnews");
		$param = Array($type=>$status);
		$this->Mnews->change($param,$id);
		echo "1";
	}

	function delete_news($id){
		$this->load->Model("Mnews");
		$news = $this->Mnews->get($id);

		//매물등록자이거나 관리자라면 삭제가 가능하다.
		if($this->session->userdata("admin_id")==$news->member_id || $this->session->userdata("auth_id")==1){

			//메인이미지 삭제
			@unlink(HOME.'/uploads/news/'.$news->thumb_name);	
			@unlink(HOME.'/uploads/news/thumb/'.$news->thumb_name);	
			
			//첨부파일 삭제
			if(file_exists(HOME.'/uploads/news/attachment/'.$id)){
				$this->rrmdir(HOME.'/uploads/news/attachment/'.$id);
			}

			//네이버 신디케이션 전송
			$this->load->helper("syndi");
			send_ping($id,"news","delete");

			//DB 삭제
			$this->Mnews->delete_news($id);
			$this->Mnews->delete_all_attachment($id);

			redirect("adminnews/index","refresh");
		} else {
			echo "<script>alert('Authentification Error!');history.go(-1);</script>";
		}
	}

	//대표사진 삭제
	public function delete_thumb_image(){
		$this->load->model("Mnews");
		$news_id = $this->input->post("news_id");
		$thumb_name = $this->input->post("thumb_name");
		if($news_id && $thumb_name){
			$this->Mnews->delete_thumb_image($news_id);
			@unlink(HOME."/uploads/news/".$thumb_name);
			@unlink(HOME."/uploads/news/thumb/".$thumb_name);
		}
	}

	//대표사진 삭제
	public function delete_file($id){
		$this->load->model("Mnews");
		$query = $this->Mnews->get_attachment($id);
		@unlink(HOME."/uploads/news/attachment/".$query->news_id."/".$query->filename);
		$this->Mnews->delete_attachment($query->id);
	}

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

}

/* End of file Adminnews.php */
/* Location: ./application/controllers/Adminnews.php */

