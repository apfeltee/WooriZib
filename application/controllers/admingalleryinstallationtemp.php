<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admingalleryinstallationtemp extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}


	/**
	 * 등록화면에서 임시로 등록하는 사진들의 목록을 가져온다.
	 */
	function gallery_temp_json($id){
		$this->load->model("Mgalleryinstallationtemp");
		echo json_encode($this->Mgalleryinstallationtemp->get_list($this->session->userdata("admin_id")));
	}

	/**
	 * 갤러리 사진 업로드
	 *
	 * 1. 890 픽셀로 본 이미지가 업로드 되고 120픽셀로 썸네일 이미지가 등록된다.
	 * 3. watermark 추가
	 */
	public function upload_action(){

		$CI =& get_instance();
		$CI->load->library('image_lib');

		$config['upload_path'] = HOME.'/uploads/gallery_installation/temp';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload',$config);

		if ( ! $this->upload->do_upload("file")){
			$error = array('error' => $this->upload->display_errors());
			echo $this->upload->display_errors();
		} else {
			$data = array('upload_data' => $this->upload->data());
			$this->load->model("Mgalleryinstallationtemp");
			
			$sorting = $this->Mgalleryinstallationtemp->get_sorting($this->session->userdata("admin_id"));

			$param = Array(
				"member_id" => $this->session->userdata("admin_id"),
				"filename" => $data["upload_data"]["file_name"],
				"sorting" => (int)$sorting + 1,
				"regdate" => date('Y-m-d H:i:s')
			);

			$this->Mgalleryinstallationtemp->insert($param);
			$this->make_gallery_thumb($data["upload_data"],890,"");
			$this->make_gallery_thumb($data["upload_data"],450,"_thumb");
		}

		echo "1";
	}

	/**
	 * 갤러리 등록 이미지 썸네일 만들기
	 */
	private function make_gallery_thumb($data, $width=300, $folder="thumb"){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		//썸네일 만들기
		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME.'/uploads/gallery_installation/temp/'.$data["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/gallery_installation/temp/'.$data["file_name"];
		$thumb_config['create_thumb'] = TRUE;
		$thumb_config['thumb_marker'] = $folder;
		$thumb_config['maintain_ratio'] = TRUE;
		$thumb_config['width'] = $width;
		$thumb_config['height'] = intval($data["image_height"])*$thumb_config['width']/intval($data["image_width"]);
		$thumb_config['quality'] = $config->QUALITY;

		$CI =& get_instance();
		$CI->load->library('image_lib');
		$CI->image_lib->initialize($thumb_config);
		
		//관리자 정보를 가져와서 watermark의 취치를 지정한다.
		$this->load->model("Mmember");
		$member = $this->Mmember->get($this->session->userdata("admin_id"));
		if ( ! $CI->image_lib->resize())
		{
			echo $CI->image_lib->display_errors();
			return "0";
		} else {
			if($width>500){
				
				//config
				$this->load->model("Mconfig");
				$site_config = $this->Mconfig->get();
				
				$this->load->model("Mmember");
			}
			return "1";
		}
	}

	function gallery_delete($gid){
		$this->load->model("Mgalleryinstallationtemp");
		$gallery = $this->Mgalleryinstallationtemp->get($gid);
		if( file_exists(HOME.'/uploads/gallery_installation/temp/'. $gallery->filename) ){
			unlink(HOME.'/uploads/gallery_installation/temp/'. $gallery->filename);			//본 이미지 삭제
			$temp = explode(".",$gallery->filename);
			unlink(HOME.'/uploads/gallery_installation/temp/'. $temp[0]."_thumb.".$temp[1]);	//썸네일 이미지 삭제
		}
		// DB 삭제
		$this->Mgallerytemp->delete($gallery->id);
		echo "1";
	}

	function gallery_json($id){
		$this->load->model("Mgalleryinstallation");
		echo json_encode($this->Mgalleryinstallation->get_list($id));
	}

	function gallery_sorting($gallery_id,$sorting){
		$this->load->model("Mgalleryinstallationtemp");
		$this->Mgalleryinstallationtemp->change_sorting($gallery_id,$sorting);
		echo "1";
	}

	/**
	 * 갤러리 이미지설명 수정
	 */
	function gallery_content_update($id){
		$this->load->model("Mgalleryinstallationtemp");

		$param = Array(
			"content" => $this->input->post('content')
		);		

		$gallery = $this->Mgalleryinstallationtemp->update($id,$param);
	}

	/**
	 * 로테이트 이미지
	 */
	function change_rotate($id){
		$this->load->model("Mgalleryinstallationtemp");

		$gallery = $this->Mgalleryinstallationtemp->get($id);
	
		$data['rotate'] = 270;

		$data['image'] = HOME.'/uploads/gallery_installation/temp/'.$gallery->filename;
		$this->make_rotate_image($data); //본문이미지

		$temp = explode(".",$gallery->filename);
		$data['image'] = HOME.'/uploads/gallery_installation/temp/'. $temp[0]."_thumb.".$temp[1];
		$this->make_rotate_image($data); //썸네일이미지
	}

	/**
	 * 로테이트 이미지 전환
	 */
	private function make_rotate_image($data){

		$config['image_library']	= 'gd2';
		$config['source_image']		= $data['image'];
		$config['create_thumb']		= FALSE;
		$config['maintain_ratio']	= TRUE;
		$config['quality']			= '100%';
		$config['rotation_angle']	= $data['rotate'];

		$CI =& get_instance();
		$CI->load->library('image_lib');
		$CI->image_lib->initialize($config);

		if (!$CI->image_lib->rotate()){
			echo "0";
		} else {
			echo "1";
		}
	}
}

/* End of file dmingalleryinstallationtemp.php */
/* Location: ./application/controllers/dmingalleryinstallationtemp.php */

