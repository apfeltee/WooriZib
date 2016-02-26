<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}


	/**
	 * 갤러리 사진 업로드
	 *
	 * 1. 890 픽셀로 본 이미지가 업로드 되고 120픽셀로 썸네일 이미지가 등록된다.
	 * 3. watermark 추가
	 */
	public function upload_action($id,$admin_gallery=""){
		if(is_numeric($admin_gallery)) $admin_gallery = "";

		$folder = HOME.'/uploads/gallery'.$admin_gallery.'/'.$id;
		if(!file_exists($folder))	{
			mkdir($folder,0777);
			chmod($folder,0777);
		}
		
		$this->load->library('image_lib');

		$config['upload_path'] = $folder;
		$config['allowed_types'] = '*';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload',$config);

		if ( ! $this->upload->do_upload("file")){
			$error = array('error' => $this->upload->display_errors());
			echo $this->upload->display_errors();
		} else {
			$data = array('upload_data' => $this->upload->data());
			$this->load->model("Mgallery");
			
			$sorting = $this->Mgallery->get_sorting($id,$admin_gallery);

			$param = Array(
				"product_id" => $id,
				"filename" => $data["upload_data"]["file_name"],
				"sorting" => (int)$sorting + 1,
				"regdate" => date('Y-m-d H:i:s')
			);
			$this->Mgallery->insert($param,$admin_gallery);
			$this->make_gallery_thumb($data["upload_data"],$id,890,"",$admin_gallery);
			$this->make_gallery_thumb($data["upload_data"],$id,450,"_thumb",$admin_gallery);
		}

		echo "1";
	}

	/**
	 * 갤러리 등록 이미지 썸네일 만들기
	 */
	private function make_gallery_thumb($data, $id, $width=300, $folder="thumb",$admin_gallery=""){
		if(is_numeric($admin_gallery)) $admin_gallery = "";

		if(intval($data["image_width"])<=$width) $width = $data["image_width"];

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		//썸네일 만들기
		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME.'/uploads/gallery'.$admin_gallery.'/'.$id."/".$data["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/gallery'.$admin_gallery.'/'.$id."/".$data["file_name"];
		$thumb_config['create_thumb'] = TRUE;
		$thumb_config['thumb_marker'] = $folder;
		$thumb_config['maintain_ratio'] = TRUE;
		$thumb_config['width'] = $width;
		$thumb_config['height'] = intval($data["image_height"])*$thumb_config['width']/intval($data["image_width"]);
		$thumb_config['quality'] = $config->QUALITY;

		$this->load->library('image_lib');
		$this->image_lib->initialize($thumb_config);
		
		if ( ! $this->image_lib->resize())
		{
			echo $this->image_lib->display_errors();
			return "0";
		} else {
			return "1";
		}
	}

	/**
	 * 갤러리 이미지 삭제
	 */
	function gallery_delete($gid,$admin_gallery=""){
		if(is_numeric($admin_gallery)) $admin_gallery = "";
		//파일 삭제
		$this->load->model("Mgallery");
		$gallery = $this->Mgallery->get($gid,$admin_gallery);

		if( file_exists(HOME.'/uploads/gallery'.$admin_gallery.'/'.$gallery->product_id ."/". $gallery->filename) ){
			@unlink(HOME.'/uploads/gallery'.$admin_gallery.'/'.$gallery->product_id ."/". $gallery->filename);			//본 이미지 삭제
			$temp = explode(".",$gallery->filename);
			@unlink(HOME.'/uploads/gallery'.$admin_gallery.'/'.$gallery->product_id ."/". $temp[0]."_thumb.".$temp[1]);	//썸네일 이미지 삭제
		}

		if($gallery->sorting==1){
			$this->Mgallery->sorting_refresh($gallery->product_id,$admin_gallery);
		}

		// DB 삭제
		$this->Mgallery->delete($gallery->product_id, $gid,$admin_gallery);
		echo "1";
	}

	/**
	 * 갤러리 이미지 전체 삭제
	 */
	function gallery_all_delete($admin_gallery=""){
		if(is_numeric($admin_gallery)) $admin_gallery = "";
		$this->load->model("Mgallery");
		$gallery = $this->Mgallery->get_list($this->input->post("id"),"obj","",$admin_gallery);
		foreach($gallery as $val){
			$this->gallery_delete($val->id,$admin_gallery);
		}
	}


	function gallery_json($id,$admin_gallery=""){
		if(is_numeric($admin_gallery)) $admin_gallery = "";
		$this->load->model("Mgallery");
		echo json_encode($this->Mgallery->get_list($id,"","",$admin_gallery));
	}



	function gallery_sorting($gallery_id,$sorting,$admin_gallery=""){
		if(is_numeric($admin_gallery)) $admin_gallery = "";
		$this->load->model("Mgallery");
		$this->Mgallery->change_sorting($gallery_id,$sorting,$admin_gallery);
		echo "1";
	}

	/**
	 * 갤러리 이미지설명 수정
	 */
	function gallery_content_update($id){
		$this->load->model("Mgallery");

		$param = Array(
			"content" => $this->input->post('content')
		);		

		$gallery = $this->Mgallery->update($id,$param);
	}

	/**
	 * 로테이트 이미지
	 */
	function change_rotate($id,$admin_gallery=""){
		if(is_numeric($admin_gallery)) $admin_gallery = "";
		$this->load->model("Mgallery");
		
		$gallery = $this->Mgallery->get($id,$admin_gallery);

		$data['rotate'] = 270;

		$data['image'] = HOME.'/uploads/gallery'.$admin_gallery.'/'.$gallery->product_id ."/". $gallery->filename;
		$this->make_rotate_image($data); //본문이미지

		$temp = explode(".",$gallery->filename);
		$data['image'] = HOME.'/uploads/gallery'.$admin_gallery.'/'.$gallery->product_id ."/". $temp[0]."_thumb.".$temp[1];
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

/* End of file gallery.php */
/* Location: ./application/controllers/gallery.php */

