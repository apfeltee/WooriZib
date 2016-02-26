<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminbuilding extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	/**
	 * 엑셀 업로드
		[0] => 대지위치
		[1] => 시군구코드
		[2] => 법정동코드
		[3] => 번
		[4] => 지
		[5] => 관리건축물대장PK
		[6] => 도로명대지위치
		[7] => 건물명
		[8] => 외필지수
		[9] => 동명칭
		[10] => 대지면적(㎡)
		[11] => 건축면적(㎡)
		[12] => 건폐율(%)
		[13] => 연면적(㎡)
		[14] => 용적률산정연면적(㎡)
		[15] => 용적률(%)
		[16] => 구조코드
		[17] => 구조코드명
		[18] => 주용도코드
		[19] => 주용도코드명
		[20] => 기타용도
		[21] => 높이(m)
		[22] => 지상층수
		[23] => 지하층수
		[24] => 승용승강기수
		[25] => 비상용승강기수
		[26] => 엘리베이터수
		[27] => 옥내기계식대수(대)
		[28] => 옥외기계식대수(대)
		[29] => 옥내자주식대수(대)
		[30] => 옥외자주식대수(대)
		[31] => 총주차대수
		[32] => 사용승인일
		[33] => 에너지효율등급
	 */
	function upload_action(){
		$this->load->model("Mbuilding");
		$this->load->model("Maddress");

		$file = $_FILES["excel_file"]["tmp_name"];
		if(!$file){
			redirect("/adminbuilding/building_upload","refresh");
			exit;
		}
		$exe_type = explode(".",$_FILES["excel_file"]["name"]);
		if($exe_type[1]!="xls"){
			redirect("/adminbuilding/building_upload","refresh");
			exit;
		}

		$this->load->library("excel_reader");
		$this->excel_reader->setOutputEncoding("UTF-8");
		$this->excel_reader->read($file);
		$worksheet = $this->excel_reader->sheets;

		$data = array();
		foreach($worksheet as $val){
			unset($val["cells"][1]);
			$val["cells"] = array_values($val["cells"]);
			foreach($val["cells"] as $key=>$cell){
				$cell = array_values($cell);

				$address = str_replace("서울특별시","서울",trim(element(0,$cell)));
				$address = str_replace("번지","",trim($address));

				$address_id = $this->get_address_id($address);

				$cell_fix = array(
					'id' => $key + 1,
					'address' => $address,
					'address_id' => $address_id->id,
					'road_name' => element(6,$cell),
					'plottage' => element(10,$cell),
					'building_area' => element(11,$cell),
					'building_coverage' => element(12,$cell),
					'total_floor_area' => element(13,$cell),
					'floor_area_cal' => element(14,$cell),
					'floor_area_ratio' => element(15,$cell),
					'structure_name' => element(17,$cell),
					'main_use' => element(19,$cell),
					'etc_use' => element(20,$cell),
					'ground_floors' => element(22,$cell),
					'underground_floors' => element(23,$cell),
					'elevator_count' => element(26,$cell),
					'parking_count' => element(31,$cell),
					'use_approval_day' => element(32,$cell),
					'energy_efficiency' => element(33,$cell)
				);
				array_push($data, $cell_fix);
			}
		}
		$this->Mbuilding->delete();
		$this->Mbuilding->insert($data);

		redirect("/adminbuilding/building_upload","refresh");
	}

	private function get_address_id($val){
		$val = str_replace("서울특별시","서울",$val);
		$ad = explode(" ",$val);
		$this->load->model("Maddress");
		$query = $this->Maddress->get_address_like($ad[0],$ad[1],$ad[2]);
		return $query;
	}

	function building_upload(){
		$this->load->model("Mbuilding");
		$data["total"] = $this->Mbuilding->get_total_count();
		$this->layout->admin('building_upload', $data);
	}

	/**
	 * 건물의뢰 목록
	 */
	function building_enquire($page=0){

		$this->load->library('pagination');
		$this->load->model("Mbuilding");
		$this->load->model("Mattachment");

        $config['base_url'] = "/adminbuilding/building_enquire/";
        $config['total_rows'] = $this->Mbuilding->get_enquire_count();
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
        $data['query'] = $this->Mbuilding->get_enquire_list( $config['per_page'], $page);

		//첨부파일
		if(count($data['query'])){
			foreach($data['query'] as $key=>$val){
				$data['query'][$key]->estimate = $estimate_count = $this->Mattachment->get_estimate_list($val->id);
			}		
		}

        $this->layout->admin('building_enquire', $data);
	}

	/**
	 * 건물의뢰 상세
	 */
	function building_enquire_view($id){
		$this->load->model("Mbuilding");
		$this->load->model("Mattachment");

		$data["query"] = $this->Mbuilding->get_enquire($id);
		$data["building"] = $this->Mbuilding->get_id($data["query"]->building_id);
		$data["attachment"] = $this->Mattachment->get_estimate_list($id);

        $this->layout->admin('building_enquire_view', $data);
	}

	/**
	 * 견적서 업로드
	 */
	function upload_estimate(){
		$this->load->model("Mattachment");

		if(count($_FILES)>0){
	 		$this->load->library('upload');
	 		$folder = HOME.'/uploads/attachment/building_enquire/'.$this->input->post("id");
	 		$this->upload->initialize(array(
				"upload_path"   => $folder,
				"allowed_types" => 'xlsx|xls|doc|docx|hwp|ppt|pptx|pdf|zip|txt|jpg|png',
				"encrypt_name"	=> TRUE
			 ));
			
			if(!file_exists($folder)){
				mkdir($folder,0777);
				chmod($folder,0777);
			}

	 		if($this->upload->do_multi_upload("files")) {
	            foreach($this->upload->get_multi_upload_data() as $val){
					$param = Array(
						"enquire_id" => $this->input->post("id"),
						"originname"=> $val["orig_name"],
						"filename"	=> $val["file_name"],
						"file_ext"	=> $val["file_ext"],
						"file_size"	=> $val["file_size"],
						"regdate" 	=> date('Y-m-d H:i:s')
					);
					$this->Mattachment->insert_estimate($param);
	            }
	        }
	    }

		redirect("adminbuilding/building_enquire_view/".$this->input->post("id"),"refresh");
	}

}

/* End of file adminbuilding.php */
/* Location: ./application/controllers/adminbuilding.php */