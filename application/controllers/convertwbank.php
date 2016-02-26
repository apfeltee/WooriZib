<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Convertwbank extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function check_idx(){
		$results = $this->init();

		foreach($results as $val){
				if(count($val)>0){
					echo $val["idx"];
					echo "<br/>";
				}
		}
	}


	/**
	 * 주소가 모두 잘 들어가져 있는 지를 체크한다.
	 */
	function check_address(){
		$results = $this->init();

		foreach($results as $val){
				if(count($val)>0){
					$address = $this->get_address_id($val["address"]);
					echo iconv("UTF-8", "EUC-KR", $val["address"]);
					print_r($address);
					echo "<br/>";
				}
		}
	}

	function add_address(){
		$results = $this->init();
		$this->load->model("Maddress");
		foreach($results as $val){
				if(count($val)>0){
					$address = $this->get_address_id($val["address"]);

					$param = Array(
						"address_id" => $address->id,
						"name"=> $val["danji"],
						"area"=> $val["area"],
						"lastupdated" => $val["lastupdated"],
						"salesprice" => $val["salesprice"],
						"d_price" => $val["d_price"],
						"u_price" => $val["u_price"]
					);

					$this->Maddress->insert_danzi($param);					
				}
		}
	}

	function check_category(){
		$results = $this->init();

		foreach($results as $val){
				if(count($val)>0){
					echo iconv("UTF-8", "EUC-KR", $val["category"]);

					print_r($this->get_category($val["category"]));
					echo "<br/>";
				}
		}
	}

	/**
	 *
	 * state		:  숨김일 경우에는 is_activated가 0
	 * build_check	:  2이면 전체가 아니고 파트라는 얘기이고, 1이면 전체라는 얘기이다.
	 */
	function index(){
		$this->load->model("Madminproduct");

		$member_id = "19";
		$host = "http://oneroom-nara.com";
		$url = "/admin/a_maemul_list_convert.php";
		$img_dir = "/data/maemul/big/";

		$results = $this->init();

		foreach($results as $val){
			//print_r($val);
			if(count($val)>0){
				
				if($val["build_check"]=="2"){
					$part = "N";
				} else {
					$part = "Y";
				}
				
				$address = $this->get_address_id($val["address"]);
				$param = Array(
					"id"					=> $val["idx"],
					"title"					=> $val["title"],
					"thumb_name"		=> $val["picname1"],
					"secret"				=> $val["admin_memo"] . $val["traffice"] ,
					"type"					=> "monthly_rent",
					"monthly_rent_deposit"	=> $val["security_money"],
					"monthly_rent_price"	=> $val["month_money"],
					"address_id"	=> $address->id,
					"lat"			=> $address->lat,
					"lng"			=> $address->lng,
					"category"	=> $this->get_category($val["category"]),
					"real_area"	=> $val["sil_size"],
					"law_area" => $val["size"],
					"bedcnt"	=> "1",
					"bathcnt"	=> "1",
					"current_floor"	=> $val["now_floor"],
					"total_floor"	=> $val["total_floor"],
					"enter_year"	=> $val["movein_day"],
					"abstract"	=> $val["room"],
					"member_id"	=> $member_id ,
					"is_activated" => "1",
					"tag"			=> $val["room"],
					"date"		=> $val["wdate"]
				);


				$product_cnt = $this->Madminproduct->get_count($val["idx"]);

				if($product_cnt < 1){

					$idx = $this->Madminproduct->insert($param);


					$subway = $this->Madminproduct->get_subway($address->lat,$address->lng);
					foreach($subway as $val_subway){
						$this->Madminproduct->insert_subway($idx,$val_subway->id);
					}

					//메인이미지
					$this->add_main($idx, $host . $img_dir . $val["picname1"], $val["picname1"]);

					//갤러리이미지
					if($val["picname2"]!="") $this->add_gallery($idx, $host . $img_dir . $val["picname2"], $val["picname2"]);
					if($val["picname3"]!="") $this->add_gallery($idx, $host . $img_dir . $val["picname3"], $val["picname3"]);
					if($val["picname4"]!="") $this->add_gallery($idx, $host . $img_dir . $val["picname4"], $val["picname4"]);
					if($val["picname5"]!="") $this->add_gallery($idx, $host . $img_dir . $val["picname5"], $val["picname5"]);
					if($val["picname6"]!="") $this->add_gallery($idx, $host . $img_dir . $val["picname6"], $val["picname6"]);
					if($val["picname7"]!="") $this->add_gallery($idx, $host . $img_dir . $val["picname7"], $val["picname7"]);
					if($val["picname8"]!="") $this->add_gallery($idx, $host . $img_dir . $val["picname8"], $val["picname8"]);
					if($val["picname9"]!="") $this->add_gallery($idx, $host . $img_dir . $val["picname9"], $val["picname9"]);
					if($val["picname10"]!="") $this->add_gallery($idx, $host . $img_dir . $val["picname10"], $val["picname10"]);

				}
			}			
		}
	}

	private function init(){
		$host = "http://w-bank.co.kr";
		$url = "/admin/loan/area_test.php";
		
		$homepage = file_get_contents($host.$url);
		$homepage = iconv("EUC-KR", "UTF-8", $homepage);

		$results = Array();
		$arrs = explode("\n",$homepage);


		foreach($arrs as $val){
			$result = Array();
			$arr = explode("&",$val);

			foreach($arr as $val2){
				if($val2!=""){
					$ar = explode("=",$val2);
					if(count($ar)>1)	if($ar[0]!="")	$result[$ar[0]]=$ar[1];
				}
			}

			array_push($results,$result);
		}

		return $results;

	}

	/**
	 * 이름에 대해서 카테고리 ID를 가져오거나 아니면 카테고리를 추가한 후 ID를 가져온다.
	 */
	private function get_category($category){
		$this->load->model("Mcategory");
		$check = $this->Mcategory->get_by_name($category);
		if($check==null){
			$param = Array(
				"name"	=> $category,
				"opened" => "N"
			);
			return $this->Mcategory->insert($param);
		} else {
			return $check->id;
		}
	}

	private function get_address_id($val)
	{
		$ad = explode(",",$val);
		$this->load->model("Maddress");
		$query = $this->Maddress->get_address_like($ad[0],$ad[1],$ad[2]);
		return $query;
	}

	private function add_main($id, $url, $filename)
	{
		$folder = HOME.'/uploads/products';
		$file_name = $folder . "/" . $filename;
		file_put_contents($file_name, file_get_contents($url));
		$this->make_main_thumb($filename,300);
	}

	private function add_gallery($id, $url, $filename)
	{
		$folder = HOME.'/uploads/gallery/'.$id;
		if(!file_exists($folder)){
			mkdir($folder,0777);
			chmod($folder,0777);
		}
		$file_name = $folder . "/" . $filename;
		file_put_contents($file_name, file_get_contents($url));

		$this->load->model("Mgallery");
		$sorting = $this->Mgallery->get_sorting($id);

		$param = Array(
			"product_id" => $id,
			"filename" => $filename,
			"sorting" => (int)$sorting + 1,
			"regdate" => date('Y-m-d H:i:s')
		);
		$this->Mgallery->insert($param);
		$this->make_gallery_thumb($filename,$id,100);
	}


	/**
	 * 갤러리 등록 이미지 썸네일 만들기
	 */
	private function make_main_thumb($filename,$width=300)
	{
		//썸네일 만들기
		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME."/uploads/products/".$filename;
		$thumb_config['new_image']	  = HOME.'/uploads/products/thumb/'.$filename;

		$thumb_config['create_thumb'] = TRUE;
		$thumb_config['thumb_marker'] = "";
		$thumb_config['maintain_ratio'] = TRUE;
		$thumb_config['width'] = $width;
		$thumb_config['quality'] = "80%";

		$CI =& get_instance();
		$CI->load->library('image_lib');
		$CI->image_lib->initialize($thumb_config);
		$CI->image_lib->resize();
	}

	/**
	 * 갤러리 등록 이미지 썸네일 만들기
	 */
	private function make_gallery_thumb($filename, $id, $width=300)
	{
		//썸네일 만들기
		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME.'/uploads/gallery/'.$id."/".$filename;
		$thumb_config['new_image'] = HOME.'/uploads/gallery/'.$id."/".$filename;
		$thumb_config['create_thumb'] = TRUE;
		$thumb_config['thumb_marker'] = "_thumb";
		$thumb_config['maintain_ratio'] = TRUE;
		$thumb_config['width'] = $width;
		$thumb_config['quality'] = "80%";

		$CI =& get_instance();
		$CI->load->library('image_lib');
		$CI->image_lib->initialize($thumb_config);
		$CI->image_lib->resize();
	}
}

/* End of file convertwbank.php */
/* Location: ./application/controllers/convertwbank.php */

