<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Photo extends CI_Controller {

	//갤러리 사진 실시간 워터마크 
	public function gallery_image($id,$admin_gallery=""){
		if(is_numeric($admin_gallery)) $admin_gallery = "";
		$this->load->model("Mgallery");
		$this->load->model("Mproduct");
		$this->load->model("Mmember");
		$gallery_info = $this->Mgallery->get($id,$admin_gallery);
		$product_info = $this->Mproduct->get($gallery_info->product_id);
		$member_info = ($product_info) ? $this->Mmember->get($product_info->member_id) : "";

		if($gallery_info && $member_info){
			if($gallery_info->filename){

				$image_root = "/uploads/gallery".$admin_gallery."/".$gallery_info->product_id."/";
				$temp_root = "/uploads/watermark/";

				$data['source_image'] = HOME.$image_root.$gallery_info->filename;
				$data['new_image'] = HOME.$temp_root.$gallery_info->filename;
				$data['member_watermark'] = HOME."/uploads/member/".$member_info->watermark;
				$data['member_watermark_position_vertical'] = $member_info->watermark_position_vertical;
				$data['member_watermark_position_horizontal'] = $member_info->watermark_position_horizontal;

				$this->load->helper('file');
				header('Content-Type: image/png');

				if(is_file($data['source_image'])){
					$this->make_thumb($data);
					echo read_file(HOME.$temp_root.$gallery_info->filename);
				}
				else{
					echo read_file(HOME."/assets/common/img/no.png");
				}

				unlink($data['new_image']);
			}		
		}
		else{
			echo read_file(HOME."/assets/common/img/no.png");
		}
	}

	public function gallery_thumb($id="",$admin_gallery=""){
		if(is_numeric($admin_gallery)) $admin_gallery = "";

		$this->load->helper('file');
		header('Content-Type: image/png');

		if($id==""){
			echo read_file(HOME."/assets/common/img/no_thumb.png");
		}

		$this->load->model("Mgallery");		
		$gallery_info = $this->Mgallery->get($id,$admin_gallery);

		if($gallery_info){
			if($gallery_info->filename){
				$temp = explode(".",$gallery_info->filename);
				if(is_file(HOME."/uploads/gallery".$admin_gallery."/".$gallery_info->product_id."/".$temp[0]."_thumb.".$temp[1])){
					echo read_file(HOME."/uploads/gallery".$admin_gallery."/".$gallery_info->product_id."/".$temp[0]."_thumb.".$temp[1]);
				}
				else{
					echo read_file(HOME."/assets/common/img/no_thumb.png");
				}				
			}		
		}
	}

	//분양 사진 실시간 워터마크
	public function gallery_installation_image($id){
		$this->load->model("Mgalleryinstallation");
		$this->load->model("Minstallation");
		$this->load->model("Mmember");
		$gallery_info = $this->Mgalleryinstallation->get($id);
		$installation_info = $this->Minstallation->get($gallery_info->installation_id);
		$member_info = ($installation_info) ? $this->Mmember->get($installation_info->member_id) : "";

		if($gallery_info && $member_info){
			if($gallery_info->filename){

				$image_root = "/uploads/gallery_installation/".$gallery_info->installation_id."/";
				$temp_root = "/uploads/watermark/";

				$data['source_image'] = HOME.$image_root.$gallery_info->filename;
				$data['new_image'] = HOME.$temp_root.$gallery_info->filename;
				$data['member_watermark'] = HOME."/uploads/member/".$member_info->watermark;
				$data['member_watermark_position_vertical'] = $member_info->watermark_position_vertical;
				$data['member_watermark_position_horizontal'] = $member_info->watermark_position_horizontal;

				$this->load->helper('file');
				header('Content-Type: image/png');

				if(is_file($data['source_image'])){
					$this->make_thumb($data);
					echo read_file(HOME.$temp_root.$gallery_info->filename);
				}
				else{
					echo read_file(HOME."/assets/common/img/no.png");
				}

				unlink($data['new_image']);
			}		
		}
		else{
			echo read_file(HOME."/assets/common/img/no.png");
		}
	}

	public function gallery_installation_thumb($id){

		$this->load->model("Mgalleryinstallation");		
		$gallery_info = $this->Mgalleryinstallation->get($id);

		if($gallery_info){
			if($gallery_info->filename){
				$this->load->helper('file');
				header('Content-Type: image/png');
				$temp = explode(".",$gallery_info->filename);
				if(is_file(HOME."/uploads/gallery_installation/".$gallery_info->installation_id."/".$temp[0]."_thumb.".$temp[1])){
					echo read_file(HOME."/uploads/gallery_installation/".$gallery_info->installation_id."/".$temp[0]."_thumb.".$temp[1]);
				}
				else{
					echo read_file(HOME."/assets/common/img/no_thumb.png");
				}				
			}		
		}
	}

	//워터마크 생성
	private function make_thumb($data){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$thumb_config['source_image'] = $data['source_image'];
		$thumb_config['new_image']	  = $data['new_image'];

		$thumb_config['image_library'] = 'gd2';
		$thumb_config['create_thumb'] = TRUE;
		$thumb_config['thumb_marker'] = "";
		$thumb_config['maintain_ratio'] = TRUE;
		$thumb_config['quality'] = $config->QUALITY;

		$CI =& get_instance();
		$CI->load->library('image_lib');
		$CI->image_lib->initialize($thumb_config);

		if ( ! $CI->image_lib->resize()){

			echo $CI->image_lib->display_errors();

		} else {

			$thumb_config['image_library'] = 'ImageMagick';
			$thumb_config['library_path'] = '/usr/local/bin/';
			$thumb_config['source_image'] = $thumb_config['source_image'];
			$thumb_config['wm_type'] = 'overlay';

			if(is_file($data['member_watermark'])){ //회원 워터마크
				$thumb_config['wm_overlay_path'] = $data['member_watermark'];
				$thumb_config['new_image'] = $thumb_config['new_image'];
				$thumb_config['wm_vrt_alignment'] = $data["member_watermark_position_vertical"];
				$thumb_config['wm_hor_alignment'] = $data["member_watermark_position_horizontal"];
				$this->image_lib->initialize($thumb_config);
				$this->image_lib->watermark();
				$this->image_lib->clear();
			}
			else{
				if($config->watermark!=""){ //사이트 워터마크
					$thumb_config['wm_overlay_path'] = HOME.'/uploads/logo/'.$config->watermark;
					$thumb_config['new_image'] = $thumb_config['new_image'];
					$thumb_config['wm_vrt_alignment'] = $config->watermark_position_vertical;
					$thumb_config['wm_hor_alignment'] = $config->watermark_position_horizontal;
					$this->image_lib->initialize($thumb_config);
					$this->image_lib->watermark();
					$this->image_lib->clear();
				}
			}
		}
	}
}

/* End of file photo.php */
/* Location: ./application/controllers/photo.php */