<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminpyeongtemp extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	/**
	 * 등록화면에서 임시로 등록하는 사진들의 목록을 가져온다.
	 */
	function pyeong_temp_json($id){
		$this->load->model("Mpyeongtemp");
		echo json_encode($this->Mpyeongtemp->get_list($this->session->userdata("admin_id")));
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

		$config['upload_path'] = HOME.'/uploads/pyeong/temp';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload',$config);

		if ( ! $this->upload->do_upload("file")){
			$error = array('error' => $this->upload->display_errors());
			echo $this->upload->display_errors();
		} else {
			$data = array('upload_data' => $this->upload->data());
			$this->load->model("Mpyeongtemp");
			
			$sorting = $this->Mpyeongtemp->get_sorting($this->session->userdata("admin_id"));

			$param = Array(
				"member_id" => $this->session->userdata("admin_id"),
				"filename" => $data["upload_data"]["file_name"],
				"sorting" => (int)$sorting + 1,
				"regdate" => date('Y-m-d H:i:s')
			);

			$this->Mpyeongtemp->insert($param);
			$this->make_pyeong_thumb($data["upload_data"],890,"");
			$this->make_pyeong_thumb($data["upload_data"],450,"_thumb");
		}

		echo "1";
	}

	/**
	 * 갤러리 등록 이미지 썸네일 만들기
	 */
	private function make_pyeong_thumb($data, $width=300, $folder="thumb"){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		//썸네일 만들기
		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME.'/uploads/pyeong/temp/'.$data["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/pyeong/temp/'.$data["file_name"];
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

	public function no_image_action(){

		$this->load->model("Mpyeongtemp");
		
		$sorting = $this->Mpyeongtemp->get_sorting($this->session->userdata("admin_id"));

		$param = Array(
			"member_id" => $this->session->userdata("admin_id"),
			"filename" => "",
			"sorting" => (int)$sorting + 1,
			"regdate" => date('Y-m-d H:i:s')
		);

		$this->Mpyeongtemp->insert($param);

		echo "1";
	}

	function pyeong_delete($gid){
		$this->load->model("Mpyeongtemp");
		$pyeong = $this->Mpyeongtemp->get($gid);
		if($pyeong->filename){
			if( file_exists(HOME.'/uploads/pyeong/temp/'. $pyeong->filename) ){
				@unlink(HOME.'/uploads/pyeong/temp/'. $pyeong->filename);			//본 이미지 삭제
				$temp = explode(".",$pyeong->filename);
				@unlink(HOME.'/uploads/pyeong/temp/'. $temp[0]."_thumb.".$temp[1]);	//썸네일 이미지 삭제
			}		
		}
		// DB 삭제
		$this->Mpyeongtemp->delete($pyeong->id);
		echo "1";
	}

	function pyeong_json($id){
		$this->load->model("Mpyeong");
		echo json_encode($this->Mpyeong->get_list($id));
	}

	function pyeong_sorting($pyeong_id,$sorting){
		$this->load->model("Mpyeongtemp");
		$this->Mpyeongtemp->change_sorting($pyeong_id,$sorting);
		echo "1";
	}

	/**
	 * 평형정보 내용 저장하기
	 */
	function pyeong_update(){
		$this->load->model("Mpyeongtemp");

		$param = Array(
			"name"			=> $this->input->post('pyeong_name'),
			"presale_date"	=> $this->input->post('pyeong_presale_date'),
			"price_min"		=> $this->input->post('pyeong_price_min'),
			"price_max"		=> $this->input->post('pyeong_price_max'),
			"tax"			=> $this->input->post('pyeong_tax'),
			"real_area"		=> $this->input->post('pyeong_real_area'),
			"law_area"		=> $this->input->post('pyeong_law_area'),
			"road_area"		=> $this->input->post('pyeong_road_area'),
			"gate"			=> $this->input->post('pyeong_gate'),
			"cnt"			=> $this->input->post('pyeong_cnt'),
			"bedcnt"		=> $this->input->post('pyeong_bedcnt'),
			"bathcnt"		=> $this->input->post('pyeong_bathcnt'),
			"description"	=> $this->input->post('pyeong_description')
		);		

		$pyeong = $this->Mpyeongtemp->update($this->input->post('pyeong_id'),$param);
	}

	/**
	 * 로테이트 이미지
	 */
	function change_rotate($id){
		$this->load->model("Mpyeongtemp");

		$pyeong = $this->Mpyeongtemp->get($id);
	
		$data['rotate'] = 270;

		$data['image'] = HOME.'/uploads/pyeong/temp/'.$pyeong->filename;
		$this->make_rotate_image($data); //본문이미지

		$temp = explode(".",$pyeong->filename);
		$data['image'] = HOME.'/uploads/pyeong/temp/'. $temp[0]."_thumb.".$temp[1];
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

/* End of file admingellerytemp.php */
/* Location: ./application/controllers/admingellerytemp.php */

