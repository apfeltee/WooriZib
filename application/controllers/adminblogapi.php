<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 블로그API
 *
 *
 * @package		CodeIgniter
 * @subpackage	Controller
 * @author		Dejung Kang
 */
class Adminblogapi extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}

	public function index(){
		$this->load->model("Mblogapi");
		$data["query"] = $this->Mblogapi->get_list("","admin");
		$this->layout->admin('blogapi_index',$data);
	}

	public function get_json($id){
		$this->load->model("Mblogapi");
		$query = $this->Mblogapi->get($id);
		echo json_encode($query);
	}

	public function add_action(){
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
		$param = Array(
			"member_type" => "admin",
			"type" => $this->input->post("type"),
			"valid" => $this->input->post("valid"),
			"user_id" => $this->input->post("user_id"),
			"address" => $this->input->post("address"),
			"blog_id" => $this->input->post("blog_id"),
			"blog_key" => $this->input->post("blog_key")
		);

		if($param['type']=='naver'){
			$param['address'] = $param['user_id'];
			$param['blog_id'] = $param['user_id'];
		}

		$this->load->model("Mblogapi");
		$this->Mblogapi->insert($param);
		redirect("adminblogapi/index","refresh");
	}

	public function edit_action(){
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
		$param = Array(
			"type" => $this->input->post("type"),
			"valid" => $this->input->post("valid"),
			"address" => $this->input->post("address"),
			"user_id" => $this->input->post("user_id"),
			"blog_id" => $this->input->post("blog_id"),
			"blog_key" => $this->input->post("blog_key")
		);

		$this->load->model("Mblogapi");
		$this->Mblogapi->update($this->input->post("id"),$param);
		redirect("adminblogapi/index","refresh");
	}

	public function delete_action($id){
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
		$this->load->model("Mblogapi");
		$this->Mblogapi->delete_blog($id);
		redirect("adminblogapi/index","refresh");
	}

	/**
	 * 매물 포스팅 기능
	 *
	 * 20141218 - 인근 매물 10개 보여주는 기능 추가
	 * 20150620 - youtube, 격언, 속담을 random하게 보여주는 기능을 추가하여 유사문서를 피하기 위한 1차 작업 진행
	 * 
	 */
	public function posting($product_id){

		$blog_ids = $this->input->post("blog_id");
		$blog_title = $this->input->post("blog_title");

		$this->load->model("Madminproduct");
		$this->load->model("Mproduct");
		$this->load->model("Mgallery");
		$this->load->model("Mmember");
		$this->load->library('blogapi');
		$this->load->model("Mblogapi");	
		$this->load->model("Mbloghistory"); //블로그 등록 히스토리 내역
		$this->load->model("Mconfig");
		$this->load->model("Mcategory");
		$this->load->model("Mviral");

		$blog = $this->Mblogapi->get_in_list($blog_ids);

		foreach($blog as $val){
			
			$this->blogapi->init($val->type,$val->address, $val->user_id, $val->blog_id, $val->blog_key);
			$data["query"]=$this->Mproduct->get($product_id);
			$data["product_subway"] = $this->Mproduct->get_product_subway($product_id);
			$data["gallery"] = $this->Mgallery->get_list($product_id);
			$data["member"] = $this->Mmember->get($data["query"]->member_id);
			$data["recent"]= $this->Mproduct->get_products_recent($data["query"]->id, $data["query"]->category, $data["query"]->lat, $data["query"]->lng, 10);
			$data["category"] = $this->Mcategory->get_list();
			$data["category_one"] = $this->Mcategory->get($data["query"]->category);

			$data["proverb1"] = $this->Mviral->get_proverb();
			$data["proverb2"] = $this->Mviral->get_proverb();
			$data["proverb3"] = $this->Mviral->get_proverb();
			$data["statement"] = $this->Mviral->get_statement();
			$data["youtube"] = $this->Mviral->get_youtube();

			$this->layout->setLayout("list");
			$content   = $this->layout->view("basic/template/blog_product_1",$data,true);
			$content = str_replace("class=\"border-table\"","border=\"0\" style=\"width:100%;border:1px solid #dddddd;border-spacing:0;font-family:dotum;font-size:14px;\"",$content); 
			$content = str_replace("<th","<th style=\"background-color:#f4f4f4;padding:5px;color:#222;border-collapse:collapse;border:1px solid #dddddd;\"",$content);
			$content = str_replace("<td","<td  style=\"border:1px solid #dddddd;padding:5px;\"",$content);


			preg_match_all('/src="([^"]+)"/', $content, $imgs); 
			$result = array_unique($imgs["1"]);	//중복 삭제
			
			$this->load->helper('security');
			foreach($result as $key=>$val2){
				if($val2!=""){
					// 원격 파일일 경우에는 로컬에 저장한 후에 처리가 끝나면 삭제처리한다.
					if (strpos($val2, 'https') !== false){

					} else if (strpos($val2, '/photo/gallery_image/') !== false){
						//포함되어 있다.

						$filename = "/uploads/gallery/temp/".do_hash($val2,'md5') . ".jpg";
						$filedata = get_url($val2);
						write_file(HOME.$filename, $filedata);
						
						$r = $this->blogapi->add_file($filename);
						if(is_array($r)){
			
						} else {
							echo "0";
							exit;
						}

						unlink(HOME.$filename);
					} else if (strpos($val2, 'http') !== false){
						/** 갤러리 이미지가 아닌 http붙는 것들은 변환없이 그대로 넣는다.  **/
					} else {

						$r = $this->blogapi->add_file($val2);	
											if(is_array($r)){
						$content = str_replace($val2, $r["url"]->me["string"], $content);
						} else {
							echo "0";
							exit;
						}

					}
					
					$this->Mbloghistory->ping();
				}
			}

			//=========================================================================================================================
			// 포스팅을 하기 전에 히스토리를 추가한 후 포스팅이 완료되면 업데이트를 한다.
			// 결과값은 포스팅을 해야 알 수 있고 해당 포스팅의 조회수를 카운팅학기 위해서는 사전에 이력의 id를 넣어 줘야 하기 때문이다.
			//=========================================================================================================================
			$param = Array(
				"blog_id"	=> $val->id,
				"type"		=> "product",
				"data_id"	=> $product_id,
				"title"		=> $blog_title,
				"date"		=> date('Y-m-d H:i:s')
			);
	
			$blog_history_id = $this->Mbloghistory->insert($param);
			$config = $this->Mconfig->get();
			//위에서 구해온 블로그 히스토리 id를 넣어서 로고 URL을 만든다. 그러면 굳이 product_id를 넘길 필요는 없어진다.
			
			$post_footer = "<br><table border=\"0\" style=\"margin-top:50px\">";
			$post_footer .= "<tr>";
			$post_footer .= "	<td style=\"border:0px;border-right:1px solid #cacaca;padding:10px;\">";
			$post_footer .= "		<a href='http://".HOST."' target='_blank'><img src='http://".HOST."/logo/st/".$blog_history_id."' style='width:200px;'></a>";
			$post_footer .= "	</td>";
			$post_footer .= "	<td style=\"padding-left:20px;\">";
			$post_footer .= "		<p><b style=\"font-size:16px;\">".$config->name."</b> <br/><br/> " . $config->new_address . ", 대표: ".$config->ceo . " <br/>";
			$post_footer .= "		대표전화번호 : ".$config->tel." , 휴대전화번호" . $config->mobile . ", 팩스: ".$config->fax . " <br/>";
			$post_footer .= lang("site.biznum") . ": " . $config->biznum . " ";
			if($config->INSTALLATION_FLAG<1) $post_footer .= ", ". lang("site.renum") .": " . $config->renum ;
			$post_footer .= " <br>홈페이지: <a href=\"http://".HOST."\">" . HOST . "</a>";
			$post_footer .= "	</p></td>";
			$post_footer .= "</tr>";
			$post_footer .= "</table>";

			/** 제목에 거래 종류를 표시해 준다. **/
			$title_head = "";
			if($config->BLOG_TITLE_HEAD){
				if($data["query"]->type=="sell") {
					$title_head = lang("sell") ;
				} else if($data["query"]->type=="installation"){
					$title_head = lang("installation") ;
				} else if($data["query"]->type=="full_rent"){
					$title_head = lang("full_rent") ;
				} else if($data["query"]->type=="monthly_rent"){
					$title_head = lang("monthly_rent") ;
				}
			}

			$return = $this->blogapi->post($blog_title  . " " .  $title_head,$content.$post_footer,$data["query"]->tag);
			if($return!=""){
				$param = Array("return"=>$return);
				$this->Mbloghistory->update($param, $blog_history_id);
				$this->Madminproduct->update_blog($product_id);
				echo "success";
			} else {
				echo "fail";
			}			
		}
	}

	public function news($id){

		$blog_ids = $this->input->post("blog_id");
		$blog_title = $this->input->post("blog_title");

		$this->load->model("Mnews");
		$this->load->library('blogapi');
		$this->load->model("Mblogapi");
		$this->load->model("Mbloghistory"); //블로그 등록 히스토리 내역
		$this->load->model("Mconfig");
		$this->load->model("Mviral");
		
		$blog = $this->Mblogapi->get_in_list($blog_ids);

		foreach($blog as $val){
			$this->blogapi->init($val->type,$val->address, $val->user_id, $val->blog_id, $val->blog_key);
			
			$query=$this->Mnews->get($id);


			$data = array(
				'content'	=> $query->content,
				'thumb_name' => $query->thumb_name
			);

			$data["recent"] = $this->Mnews->get_recent($id);
			$data["proverb1"] = $this->Mviral->get_proverb();
			$data["proverb2"] = $this->Mviral->get_proverb();
			$data["statement"] = $this->Mviral->get_statement();

			$this->layout->setLayout("list");
			$content   = $this->layout->view("basic/template/blog_news",$data,true);

			preg_match_all('/src="([^"]+)"/', $content, $imgs); 
			$result = array_unique($imgs["1"]);	//중복 삭제

			foreach($result as $val2){
				//로컬파일이 아닐 경우에는 업로드를 하지 않는다.
				if (strpos($val2, 'http') !== false){
					//포함되어 있다.
				} else {
					$r = $this->blogapi->add_file($val2);
					if(is_array($r)){
						$content = str_replace($val2, $r["url"]->me["string"], $content);
					} else {
						echo "0";
						exit;
					}
				}
			}

			$param = Array(
				"blog_id"	=> $val->id,
				"type"		=> "news",
				"data_id"	=> $id,
				"title"		=> $blog_title,
				"date"		=> date('Y-m-d H:i:s')
			);

			$blog_history_id = $this->Mbloghistory->insert($param);
			$config = $this->Mconfig->get();
			//위에서 구해온 블로그 히스토리 id를 넣어서 로고 URL을 만든다. 그러면 굳이 product_id를 넘길 필요는 없어진다.

			$post_footer = "<br><table border=\"0\" style=\"margin-top:40px\">";
			$post_footer .= "<tr>";
			$post_footer .= "	<td style=\"border:0px;border-right:1px solid #cacaca;padding:10px;\">";
			$post_footer .= "		<a href='http://".HOST."' target='_blank'><img src='http://".HOST."/logo/st/".$blog_history_id."'></a>";
			$post_footer .= "	</td>";
			$post_footer .= "	<td style=\"padding-left:20px;\">";
			$post_footer .= "		<p><b style=\"font-size:16px;\">".$config->name."</b> <br/><br/> " . $config->new_address . ", 대표: ".$config->ceo . " <br/>";
			$post_footer .= "		대표전화번호 : ".$config->tel." , 휴대전화번호" . $config->mobile . ", 팩스: ".$config->fax . " <br/>";
			$post_footer .= lang("site.biznum").": " . $config->biznum . " ";
			if($config->INSTALLATION_FLAG<1) $post_footer .= ", " . lang("site.renum") .": " . $config->renum ;
			$post_footer .= " <br>홈페이지: <a href=\"http://".HOST."\">" . HOST . "</a>";
			$post_footer .= "	</p></td>";
			$post_footer .= "</tr>";
			$post_footer .= "</table>";

			$return = $this->blogapi->post($blog_title,$content.$post_footer,$query->tag);
			
			if($return!=""){
				$param = Array("return"=>$return);
				$this->Mbloghistory->update($param, $blog_history_id);
				$this->Mnews->update_news($id);
				echo "success";
			} else {
				echo "fail";
			}
		}
	}

	public function installation($installation_id){

		$blog_ids = $this->input->post("blog_id");
		$blog_title = $this->input->post("blog_title");

		$this->load->library('blogapi');

		$this->load->model("Madmininstallation");
		$this->load->model("Minstallation");
		$this->load->model("Mgalleryinstallation");
		$this->load->model("Mmember");
		$this->load->model("Mblogapi");	
		$this->load->model("Mbloghistory");
		$this->load->model("Mconfig");
		$this->load->model("Mviral");
		$this->load->model("Minstallationschedule");
		$this->load->model("Mpyeong");

		$blog = $this->Mblogapi->get_in_list($blog_ids);

		foreach($blog as $val){
			
			$this->blogapi->init($val->type,$val->address, $val->user_id, $val->blog_id, $val->blog_key);
			$data["query"]=$this->Minstallation->get($installation_id);
			$data["installation_subway"] = $this->Minstallation->get_installation_subway($installation_id);
			$data["gallery"] = $this->Mgalleryinstallation->get_list($installation_id);
			$data["member"] = $this->Mmember->get($data["query"]->member_id);

			$data["proverb1"] = $this->Mviral->get_proverb();
			$data["proverb2"] = $this->Mviral->get_proverb();
			$data["proverb3"] = $this->Mviral->get_proverb();
			$data["statement"] = $this->Mviral->get_statement();
			$data["youtube"] = $this->Mviral->get_youtube();
			$data["schedule"] = $this->Minstallationschedule->get_list($installation_id);
			$data["pyeong"] = $this->Mpyeong->get_list($installation_id);

			$this->layout->setLayout("list");
			$content   = $this->layout->view("basic/template/blog_installation_1",$data,true);
			$content = str_replace("class=\"border-table\"","border=\"0\" style=\"width:100%;border:1px solid #dddddd;border-spacing:0;font-family:dotum;font-size:14px;\"",$content); 
			$content = str_replace("<th","<th style=\"background-color:#f4f4f4;padding:5px;color:#222;border-collapse:collapse;border:1px solid #dddddd;\"",$content);
			$content = str_replace("<td","<td  style=\"border:1px solid #dddddd;padding:5px;\"",$content);


			preg_match_all('/src="([^"]+)"/', $content, $imgs); 
			$result = array_unique($imgs["1"]);	//중복 삭제
			
			$this->load->helper('security');
			foreach($result as $key=>$val2){
				if($val2!=""){
					// 원격 파일일 경우에는 로컬에 저장한 후에 처리가 끝나면 삭제처리한다.
					if (strpos($val2, 'https') !== false){

					} else if (strpos($val2, '/photo/gallery_installation_image/') !== false){
						//포함되어 있다.

						$filename = "/uploads/gallery_installation/temp/".do_hash($val2,'md5') . ".jpg";
						$filedata = get_url($val2);
						write_file(HOME.$filename, $filedata);
						$r = $this->blogapi->add_file($filename);
						if(is_array($r)){
							$content = str_replace($val2, $r["url"]->me["string"], $content);
						} else {
							echo "0";
							exit;
						}

						unlink(HOME.$filename);
					} else if (strpos($val2, 'http') !== false){
						/** 갤러리 이미지가 아닌 http붙는 것들은 변환없이 그대로 넣는다.  **/
					} else {

						$r = $this->blogapi->add_file($val2);	
											if(is_array($r)){
						$content = str_replace($val2, $r["url"]->me["string"], $content);
						} else {
							echo "0";
							exit;
						}

					}
					
					$this->Mbloghistory->ping();
				}
			}

			//=========================================================================================================================
			// 포스팅을 하기 전에 히스토리를 추가한 후 포스팅이 완료되면 업데이트를 한다.
			// 결과값은 포스팅을 해야 알 수 있고 해당 포스팅의 조회수를 카운팅학기 위해서는 사전에 이력의 id를 넣어 줘야 하기 때문이다.
			//=========================================================================================================================
			$param = Array(
				"blog_id"	=> $val->id,
				"type"		=> "installation",
				"data_id"	=> $installation_id,
				"title"		=> $blog_title,
				"date"		=> date('Y-m-d H:i:s')
			);
	
			$blog_history_id = $this->Mbloghistory->insert($param);
			$config = $this->Mconfig->get();
			//위에서 구해온 블로그 히스토리 id를 넣어서 로고 URL을 만든다. 그러면 굳이 installation_id를 넘길 필요는 없어진다.
			
			$post_footer = "<br><table border=\"0\" style=\"margin-top:50px\">";
			$post_footer .= "<tr>";
			$post_footer .= "	<td style=\"border:0px;border-right:1px solid #cacaca;padding:10px;\">";
			$post_footer .= "		<a href='http://".HOST."' target='_blank'><img src='http://".HOST."/logo/st/".$blog_history_id."' style='width:200px;'></a>";
			$post_footer .= "	</td>";
			$post_footer .= "	<td style=\"padding-left:20px;\">";
			$post_footer .= "		<p><b style=\"font-size:16px;\">".$config->name."</b> <br/><br/> " . $config->new_address . ", 대표: ".$config->ceo . " <br/>";
			$post_footer .= "		대표전화번호 : ".$config->tel." , 휴대전화번호" . $config->mobile . ", 팩스: ".$config->fax . " <br/>";
			$post_footer .= lang("site.biznum"). ": " . $config->biznum . " ";
			if($config->INSTALLATION_FLAG<1) $post_footer .= ", ".lang("site.renum").": " . $config->renum ;
			$post_footer .= " <br>홈페이지: <a href=\"http://".HOST."\">" . HOST . "</a>";
			$post_footer .= "	</p></td>";
			$post_footer .= "</tr>";
			$post_footer .= "</table>";

			$return = $this->blogapi->post($blog_title,$content.$post_footer,$data["query"]->tag);
			if($return!=""){
				$param = Array("return"=>$return);
				$this->Mbloghistory->update($param, $blog_history_id);
				$this->Madmininstallation->update_blog($installation_id);
				echo "success";
			} else {
				echo "fail";
			}			
		}
	}

	/**
	 * 블로그 선택 팝업 띄우기
	 */
	public function blog_popup($id,$type=""){

		$this->load->model("Mblogapi");
		$this->load->model("Mproduct");
		$this->load->model("Mnews");
		$this->load->model("Minstallation");

		if($type=="news"){
			$data["query"] = $this->Mnews->get($id);
		}
		else if($type=="installation"){
			$data["query"] = $this->Minstallation->get($id);
		}
		else{
			$data["query"] = $this->Mproduct->get($id);
		}

		if($this->session->userdata("admin_id")){
			$data["blog"] = $this->Mblogapi->get_valid_list("","admin");
		}
		else{
			$data["blog"] = $this->Mblogapi->get_valid_list($this->session->userdata("id"));
		}
		
		$data["type"] = $type;

		$this->load->view("admin/blog_popup",$data);
	}

	/**
	 * 블로그 히스토리 가져오기
	 */
	public function get_history(){

		$this->load->model("Mbloghistory");
		
		$id = $this->input->post("id");
		$type = $this->input->post("type");
		$blog_id = $this->input->post("blog_id");

		$type = (!$type) ? "product" : $type; 

		$query = $this->Mbloghistory->get_list($id,$type,$blog_id);

		echo json_encode($query);
	}

	/**
	 * 블로그 히스토리(DAUM) 가져오기
	 */
	public function get_history_daum(){

		$this->load->model("Mbloghistory");
		
		$id = $this->input->post("id");
		$type = $this->input->post("type");
		$blog_name = $this->input->post("blog_name");
		$blog_category = $this->input->post("blog_category");

		$type = (!$type) ? "product" : $type; 

		$query = $this->Mbloghistory->get_list_daum($id,$type,$blog_name,$blog_category);

		echo json_encode($query);
	}

	/**
	 * DAUM 블로그 OAuth인증 팝업
	 */
	public function daum_OAuth($id){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$this->session->set_userdata("daum_upload_id",$id);

		if($this->session->userdata("daum_access_token")){
			redirect("adminblogapi/daum_blog_callback","refresh");
		}

		$authorize_url = "https://apis.daum.net/oauth2/authorize";
		$callback_uri = urlencode ("http://".HOST."/adminblogapi/daum_blog_callback");

		$auth_url = sprintf ( "%s?client_id=%s&redirect_uri=%s&response_type=code", $authorize_url, $config->daumclientkey, $callback_uri);
		header('Location: ' . $auth_url);
		exit;
	}

	/**
	 * DAUM 블로그 callback
	 */
	public function daum_blog_callback(){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$code = $this->input->get("code");

		$callback_uri = urlencode ("http://".HOST."/adminblogapi/daum_blog_callback");

		if (!$this->session->userdata("daum_access_token")) {

			$r = new HttpRequest ( "https://apis.daum.net/oauth2/token", HttpRequest::METH_POST );

			$r->setHeaders ( 
				array (
					'content-type' => 'application/x-www-form-urlencoded'
				) 
			);

			$r->addPostFields ( array (
				'client_id' => $config->daumclientkey,
				'client_secret' => $config->daumclientsecret,
				'redirect_uri' => $callback_uri,
				'code' => $code,
				'grant_type' => 'authorization_code'
			));

			$auth_token_result = json_decode ( $r->send ()->getBody () );

			if($auth_token_result->access_token){
				$this->session->set_userdata("daum_access_token",$auth_token_result->access_token);
				redirect("adminblogapi/daum_blog_callback","refresh");
			}
			else{
				echo '<script>self.close();</script>';
				exit;
			}
		}
		else{

			if($this->input->post("blog_name")){

				$this->load->model("Mproduct");
				
				$data["insert_blog_name"] = true;
				$data['id'] = $this->session->userdata("daum_upload_id");
				$data["query"] = $this->Mproduct->get($data['id']);
				$data['blog_name'] = $this->input->post("blog_name");
				$data['category'] = $this->daum_blog_category($data['blog_name'],$this->session->userdata("daum_access_token"));			

				$this->load->view("admin/blog_popup_daum",$data);				
			}
			else{
				$data["insert_blog_name"] = false;
				$this->load->view("admin/blog_popup_daum",$data);
			}
		}
	}

	/**
	 * DAUM 블로그 카테고리 추출
	 */
	private function daum_blog_category($blog_name){

		$url = file_get_contents("https://apis.daum.net/blog/v1/".$blog_name."/categories.json?access_token=".$this->session->userdata("daum_access_token"));
		$result = json_decode($url, true);

		if(isset($result['channel']['items'])){
			$result = $result['channel']['items'];
		}

		return $result;
	}

	/**
	 * DAUM 블로그 업로드
	 */
	public function product_upload_daum(){

		$blog_name = $this->input->post("blog_name");
		$id = $this->input->post("id");

		$this->load->model("Mproduct");
		$this->load->model("Madminproduct");
		$this->load->model("Mgallery");
		$this->load->model("Mmember");
		$this->load->model("Mcategory");
		$this->load->model("Mconfig");
		$this->load->model("Maddress");
		$this->load->model("Mbloghistory");	
		$this->load->model("Mviral");

		$data["product_subway"] = $this->Mproduct->get_product_subway($id);
		$data["query"] = $this->Mproduct->get($id);

		$data["gallery"] = $this->Mgallery->get_list($id);
		$data["member"] = $this->Mmember->get($data["query"]->member_id);
		$data["recent"]= $this->Mproduct->get_products_recent($data["query"]->id, $data["query"]->category, $data["query"]->lat, $data["query"]->lng, 10);
		$data["category"] = $this->Mcategory->get_list();
		$data["category_one"] = $this->Mcategory->get($data["query"]->category);
		$data["config"] = $this->Mconfig->get();

		$data["proverb1"] = $this->Mviral->get_proverb();
		$data["proverb2"] = $this->Mviral->get_proverb();
		$data["proverb3"] = $this->Mviral->get_proverb();
		$data["statement"] = $this->Mviral->get_statement();
		$data["youtube"] = $this->Mviral->get_youtube();


		$this->layout->setLayout("list");
		$content   = $this->layout->view("basic/template/blog_product_daum",$data,true);

		$r = new HttpRequest ( "https://apis.daum.net/blog/v1/".$blog_name."/write.json", HttpRequest::METH_POST );
		$r->setHeaders ( 
			array (
				'Authorization' => 'Bearer ' .$this->session->userdata("daum_access_token")
			) 
		);

		$file_url = "";
		if(isset($data["gallery"][0]->id)){
			$file_url = 'http://'.HOST.'/photo/gallery_image/'.$data["gallery"][0]->id;
		}

		$r->addPostFields ( 
			array (
				'blogName'  => $blog_name,
				'title' => $this->input->post("blog_title"),
				'content' => $content,
				'tag'  => $data["config"]->site_name,
				'categoryId' => $this->input->post("blog_category"),
				'fileUrl' => $file_url,
				'fileSize' => 'Y',
				'fileType' => 'im'
			) 
		);

		//매물정보 전송
		$api_result = json_decode ( $r->send ()->getBody () );

		if (isset($api_result->channel->status) && $api_result->channel->status == "200") {
			$data['blog_url'] = $api_result->channel->url;
			$data['message'] = "블로그에 등록 되었습니다.";
			$param = Array("is_blog_daum"=>$data["query"]->is_blog_daum + 1);
			$this->Madminproduct->update($param,$id);

			$param = Array(
				"type"		=> "product",
				"blog_name"	=> $blog_name,
				"blog_category"	=> $this->input->post("blog_category"),
				"data_id"	=> $id,
				"title"		=> $this->input->post("blog_title"),
				"date"		=> date('Y-m-d H:i:s')
			);		
			$this->Mbloghistory->insert_daum($param);

		} else {
			$data['blog_url'] = "";
			$data['message'] = $api_result->channel->errorMessage;			
		}

		$this->load->view("admin/blog_result_daum",$data);
	}
}

/* End of file adminblogapi.php */
/* Location: ./application/controllers/adminblogapi.php */