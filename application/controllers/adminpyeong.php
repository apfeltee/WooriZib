<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminpyeong extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	/**
	 * 갤러리 사진 업로드
	 *
	 * 1. 890 픽셀로 본 이미지가 업로드 되고 120픽셀로 썸네일 이미지가 등록된다.
	 * 2. 포토타이틀을 달 수 있도록 했다.
	 * 3. watermark 추가
	 */
	public function upload_image_action($id){
		$folder = HOME.'/uploads/pyeong/'.$id;
		if(!file_exists($folder))	{
			mkdir($folder,0777);
			chmod($folder,0777);
		}
		
		$CI =& get_instance();
		$CI->load->library('image_lib');

		$config['upload_path'] = $folder;
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload',$config);

		if ( ! $this->upload->do_upload("file")){
			$error = array('error' => $this->upload->display_errors());
			echo $this->upload->display_errors();
		} else {
			$data = array('upload_data' => $this->upload->data());
			$this->load->model("Mpyeong");

			$sorting = $this->Mpyeong->get_sorting($id);

			$param = Array(
				"installation_id" => $id,
				"filename" => $data["upload_data"]["file_name"],
				"sorting" => (int)$sorting + 1,
				"regdate" => date('Y-m-d H:i:s')
			);
			$this->Mpyeong->insert($param);
			$this->make_pyeong_thumb($data["upload_data"],$id,890,"");
			$this->make_pyeong_thumb($data["upload_data"],$id,450,"_thumb");
		}

		echo "1";
	}

	public function no_image_action(){

		$this->load->model("Mpyeong");
		
		$sorting = $this->Mpyeong->get_sorting($this->session->userdata("admin_id"));

		$param = Array(
			"installation_id" => $this->input->post('installation_id'),
			"filename" => "",
			"sorting" => (int)$sorting + 1,
			"regdate" => date('Y-m-d H:i:s')
		);

		$this->Mpyeong->insert($param);

		echo "1";
	}

	function pyeong_json($id){
		$this->load->model("Mpyeong");
		echo json_encode($this->Mpyeong->get_list($id));
	}

	function pyeong_sorting($pyeong_id,$sorting){
		$this->load->model("Mpyeong");
		$this->Mpyeong->change_sorting($pyeong_id,$sorting);
		echo "1";
	}

	/**
	 * 갤러리 이미지 삭제
	 */
	function pyeong_delete($gid){
		//파일 삭제
		$this->load->model("Mpyeong");
		$pyeong = $this->Mpyeong->get($gid);

		if( file_exists(HOME.'/uploads/pyeong/'.$pyeong->installation_id ."/". $pyeong->filename) ){
			@unlink(HOME.'/uploads/pyeong/'.$pyeong->installation_id ."/". $pyeong->filename);			//본 이미지 삭제
			$temp = explode(".",$pyeong->filename);
			@unlink(HOME.'/uploads/pyeong/'.$pyeong->installation_id ."/". $temp[0]."_thumb.".$temp[1]);	//썸네일 이미지 삭제
		}

		if($pyeong->sorting==1){
			$this->Mpyeong->sorting_refresh($pyeong->installation_id);
		}

		// DB 삭제
		$this->Mpyeong->delete($pyeong->installation_id, $gid);
		echo "1";
	}

	/**
	 * 평형정보 내용 저장하기
	 */
	function pyeong_update(){
		$this->load->model("Mpyeong");

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

		$pyeong = $this->Mpyeong->update($this->input->post('pyeong_id'),$param);
	}

	/**
	 * 갤러리 등록 이미지 썸네일 만들기
	 */
	private function make_pyeong_thumb($data, $id, $width=300, $folder="thumb"){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		//썸네일 만들기
		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME.'/uploads/pyeong/'.$id."/".$data["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/pyeong/'.$id."/".$data["file_name"];
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
			return "1";
		}
	}
}

/* End of file admingellery.php */
/* Location: ./application/controllers/admingellery.php */

