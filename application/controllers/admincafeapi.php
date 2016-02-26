<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/***
 * http://php.net/manual/en/http.install.php
 * https://nid.naver.com/devcenter/docs.nhn?menu=CafeTutorial
 * https://nid.naver.com/devcenter/main.nhn
 */
class Admincafeapi extends CI_Controller {

	// 네이버 아이디로 로그인에 애플리케이션을 등록하고 발급받은 클라이언트 아이디
	private $client_id;

	// 네이버 아이디로 로그인에 애플리케이션을 등록하고 발급받은 클라이언트 시크릿
	private $client_secret;

	private $authorize_url = 'https://nid.naver.com/oauth2.0/authorize';

	private $access_token_url = 'https://nid.naver.com/oauth2.0/token';

	// 네이버 아이디로 로그인에 애플리케이션을 등록할 때 [Callback URL]에 입력한 주소(예: http://www.example.com/callback.php)
	private $callback_uri;
	
	// 네이버 아이디로 로그인에 애플리케이션을 등록할 때 [서비스 URL]에 입력한 주소(예: http://www.example.com/index.php)
	private $index_uri;
	
	private $cafe_apply_api_uri = 'https://openapi.naver.com/cafe/cafeApply.json';

	private $write_post_api_uri = 'https://openapi.naver.com/cafe/articlePost.json';

	public function __construct() {
		parent::__construct();

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$this->client_id = $config->naverclientkey;
		$this->client_secret = $config->naverclientsecret;
		$this->callback_uri = 'http://'.HOST.'/admincafeapi/cafe_callback';
		$this->index_uri = 'http://'.HOST;
	}

	/**
	 * 카페 글쓰기 인증 페이지 호출
	 */
	public function cafe_auth(){
		
		$id = ($this->input->post("id")) ? $this->input->post("id") : $this->session->userdata("c_id");
		$cafe_id = ($this->input->post("cafe_id")) ? $this->input->post("cafe_id") : $this->session->userdata("c_cafeid");
		$menu_id = ($this->input->post("menu_id")) ? $this->input->post("menu_id") : $this->session->userdata("c_menuid");
		$type = ($this->input->post("type")) ? $this->input->post("type") : $this->session->userdata("c_type");
		$cafe_title = ($this->input->post("cafe_title")) ? $this->input->post("cafe_title") : $this->session->userdata("c_title");

		$this->session->set_userdata("c_id",$id);
		$this->session->set_userdata("c_cafeid",$cafe_id);
		$this->session->set_userdata("c_menuid",$menu_id);
		$this->session->set_userdata("c_type",$type);
		$this->session->set_userdata("c_title",$this->input->post("cafe_title"));

		if(!$this->session->userdata("write_access_token")){

		    $rand = mt_rand ();

			$state = md5 ( $mt . $rand );
			$this->session->set_userdata("state",$state);
			$encoded_callback_uri = urlencode ( $this->callback_uri);
			$auth_url = sprintf ( "%s?client_id=%s&response_type=code&redirect_uri=%s&state=%s", $this->authorize_url, $this->client_id, $encoded_callback_uri, $state);

			// 사용자 인증이 되어 있지 않으면 인증 페이지로 이동합니다.
			header('Location: ' . $auth_url);
			exit;
		}

		if($this->input->post("type")=="news"){
			$this->news_upload();
		}
		else if($this->input->post("type")=="installation"){
			$this->installation_upload();
		}
		else{
			$this->product_upload();
		}
	}

	/**
	 * 인증 후 콜백
	 */
	public function cafe_callback(){

		$code = $this->input->get("code");
		$state = $this->input->get("state");

		if ($state == $this->session->userdata("state")) {

			$r = new HttpRequest ( $this->access_token_url, HttpRequest::METH_GET );
			
			$r->addQueryData ( array (
				'client_id' => $this->client_id,
				'client_secret' => $this->client_secret,
				'grant_type' => 'authorization_code',
				'state' => $state,
				'code' => $code
			) );

			$r->addSslOptions ( array (
				'version' => HttpRequest::SSL_VERSION_SSLv3
			) );

			$auth_token_result = json_decode ( $r->send ()->getBody () );

			if ($auth_token_result->access_token) {
				
				$this->session->set_userdata("write_access_token",$auth_token_result->access_token);

				if($this->input->post("type")=="news"){
					$this->news_upload();
				}
				else if($this->input->post("type")=="installation"){
					$this->installation_upload();
				}
				else{
					$this->product_upload();
				}
			}
		} else {			
			echo "<script>self.close();</script>";
			exit;
		}
	}

	/**
	 * 카페 글 등록(매물)
	 */
	public function product_upload(){

		$id = $this->session->userdata("c_id");
		$cafeid = $this->session->userdata("c_cafeid");
		$menuid = $this->session->userdata("c_menuid");
		$title = $this->session->userdata("c_title");

		$this->load->model("Mproduct");
		$this->load->model("Madminproduct");
		$this->load->model("Mgallery");
		$this->load->model("Mmember");
		$this->load->model("Mcategory");
		$this->load->model("Mconfig");
		$this->load->model("Maddress");
		$this->load->model("Mcafehistory");	
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

		$access_token = $this->session->userdata("write_access_token");
		$r = new HttpRequest ( $this->write_post_api_uri, HttpRequest::METH_POST );
		$r->setHeaders ( 
			array (
				'Authorization' => 'Bearer ' . $access_token
			) 
		);

		$this->layout->setLayout("list");
		$content   = $this->layout->view("basic/template/cafe_product",$data,true);

		$content = $this->compress($content);

		$encodedSubject = urlencode($title);
		$encodedContent = urlencode($content);

		$r->addPostFields ( 
			array (
				'clubid'  => $cafeid,
				'subject' => $encodedSubject,
				'content' => $encodedContent,
				'menuid'  => $menuid
			) 
		);

		//이미지가 한개라도 없으면 네이버에서 에러를 리턴하여 추가함. 이유는 현재 알 수 없음.
		$r->addPostFile ( 'image', HOME."/assets/common/img/none.png" );

		//매물정보 전송
		$api_result = json_decode ( $r->send ()->getBody () );

		//네이버 세션이 유효하지 않거나 변조 되었을 경우 재인증
		if(isset($api_result->error_code)){
			if($api_result->error_code=="024"){
				$this->session->unset_userdata("write_access_token");
				header('Location: '.$this->index_uri."/admincafeapi/cafe_auth/");
			}
		}

		if (isset ( $api_result->message ) && isset ( $api_result->message->result )) {
			$post_url = $api_result->message->result->articleUrl;

			$param = Array("is_cafe"=>$data["query"]->is_cafe + 1);
			$this->Madminproduct->update($param,$id);

			$param = Array(
				"type"		=> "product",
				"cafe_id"	=> $cafeid,
				"menu_id"	=> $menuid,
				"data_id"	=> $id,
				"title"		=> $title,
				"date"		=> date('Y-m-d H:i:s')
			);		
			$cafe_history_id = $this->Mcafehistory->insert($param);

			$this->cafe_result('카페글로 등록 되었습니다.',$post_url);

		} else {
			if(isset ($api_result->message->error->message)){
				$this->cafe_result($api_result->message->error->message);
			}
			else{
				$this->cafe_result('카페글 등록이 실패 하였습니다.');
			}			
		}
	}

	/**
	 * 카페 글 등록(분양)
	 */
	public function installation_upload(){

		$id = $this->session->userdata("c_id");
		$cafeid = $this->session->userdata("c_cafeid");
		$menuid = $this->session->userdata("c_menuid");
		$type = $this->session->userdata("c_type");
		$title = $this->session->userdata("c_title");

		$this->load->model("Minstallation");
		$this->load->model("Madmininstallation");
		$this->load->model("Mgallery");
		$this->load->model("Mmember");
		$this->load->model("Mconfig");
		$this->load->model("Maddress");
		$this->load->model("Mcafehistory");	
		$this->load->model("Mviral");

		$data["installation_subway"] = $this->Minstallation->get_installation_subway($id);
		$data["query"] = $this->Minstallation->get($id);
		$data["gallery"] = $this->Mgallery->get_list($id);
		$data["member"] = $this->Mmember->get($data["query"]->member_id);
		$data["config"] = $this->Mconfig->get();

		$data["proverb1"] = $this->Mviral->get_proverb();
		$data["proverb2"] = $this->Mviral->get_proverb();
		$data["proverb3"] = $this->Mviral->get_proverb();
		$data["statement"] = $this->Mviral->get_statement();
		$data["youtube"] = $this->Mviral->get_youtube();

		$access_token = $this->session->userdata("write_access_token");
		$r = new HttpRequest ( $this->write_post_api_uri, HttpRequest::METH_POST );
		$r->setHeaders ( 
			array (
				'Authorization' => 'Bearer ' . $access_token
			) 
		);

		$this->layout->setLayout("list");
		$content   = $this->layout->view("basic/template/cafe_installation",$data,true);

		$content = $this->compress($content);

		$encodedSubject = urlencode($title);
		$encodedContent = urlencode($content);

		$r->addPostFields ( 
			array (
				'clubid'  => $cafeid,
				'subject' => $encodedSubject,
				'content' => $encodedContent,
				'menuid'  => $menuid
			) 
		);

		//이미지가 한개라도 없으면 네이버에서 에러를 리턴하여 추가함. 이유는 현재 알 수 없음.
		$r->addPostFile ( 'image', HOME."/assets/common/img/none.png" );

		//매물정보 전송
		$api_result = json_decode ( $r->send ()->getBody () );

		//네이버 세션이 유효하지 않거나 변조 되었을 경우 재인증
		if(isset($api_result->error_code)){
			if($api_result->error_code=="024"){
				$this->session->unset_userdata("write_access_token");
				header('Location: '.$this->index_uri."/admincafeapi/cafe_auth/");
			}
		}

		if (isset ( $api_result->message ) && isset ( $api_result->message->result )) {
			$post_url = $api_result->message->result->articleUrl;

			$param = Array("is_cafe"=>$data["query"]->is_cafe + 1);
			$this->Madmininstallation->update($param,$id);

			$param = Array(
				"type"		=> "installation",
				"cafe_id"	=> $cafeid,
				"menu_id"	=> $menuid,
				"data_id"	=> $id,
				"title"		=> $title,
				"date"		=> date('Y-m-d H:i:s')
			);		
			$cafe_history_id = $this->Mcafehistory->insert($param);

			$this->cafe_result('카페글로 등록 되었습니다.',$post_url);

		} else {
			if(isset ($api_result->message->error->message)){
				$this->cafe_result($api_result->message->error->message);
			}
			else{
				$this->cafe_result('카페글 등록이 실패 하였습니다.');
			}			
		}
	}

	/**
	 * 카페 글 등록(뉴스글)
	 */
	public function news_upload(){

		$id = $this->session->userdata("c_id");
		$cafeid = $this->session->userdata("c_cafeid");
		$menuid = $this->session->userdata("c_menuid");
		$type = $this->session->userdata("c_type");
		$title = $this->session->userdata("c_title");

		$this->load->model("Mnews");
		$this->load->model("Mconfig");
		$this->load->model("Mcafehistory");
		$this->load->model("Mviral");
		$this->load->model("Mmember");

		$query = $this->Mnews->get($id);

		$access_token = $this->session->userdata("write_access_token");
		$r = new HttpRequest ( $this->write_post_api_uri, HttpRequest::METH_POST );
		$r->setHeaders ( 
			array (
				'Authorization' => 'Bearer ' . $access_token
			) 
		);

		$data = array(
			'content'	=> $query->content,
			'thumb_name' => $query->thumb_name
		);

		$data["config"] = $this->Mconfig->get();
		$data["recent"] = $this->Mnews->get_recent($id);
		$data["member"] = $this->Mmember->get($query->member_id);
		$data["proverb1"] = $this->Mviral->get_proverb();
		$data["proverb2"] = $this->Mviral->get_proverb();
		$data["statement"] = $this->Mviral->get_statement();

		$this->layout->setLayout("list");
		$content = $this->layout->view("basic/template/cafe_news",$data,true);

		$content = $this->compress($content);

		$encodedSubject = urlencode($title);
		$encodedContent = urlencode($content);

		$r->addPostFields ( 
			array (
				'clubid'  => $cafeid,
				'subject' => $encodedSubject,
				'content' => $encodedContent,
				'menuid'  => $menuid
			) 
		);

		//이미지가 한개라도 없으면 네이버에서 에러를 리턴하여 추가함. 이유는 현재 알 수 없음.
		$r->addPostFile ( 'image', HOME."/assets/common/img/none.png" );

		//뉴스정보 전송
		$api_result = json_decode ( $r->send ()->getBody () );

		//네이버 세션이 유효하지 않거나 변조 되었을 경우 재인증
		if(isset($api_result->error_code)){
			if($api_result->error_code=="024"){
				$this->session->unset_userdata("write_access_token");
				header('Location: '.$this->index_uri."/admincafeapi/cafe_auth/");
			}
		}

		if (isset ( $api_result->message ) && isset ( $api_result->message->result )) {
			$post_url = $api_result->message->result->articleUrl;
			$param = Array("is_cafe"=>$query->is_cafe + 1);
			$this->Mnews->update($param,$id);

			$param = Array(
				"type"		=> "news",
				"cafe_id"	=> $cafeid,
				"menu_id"	=> $menuid,
				"data_id"	=> $id,
				"title"		=> $title,
				"date"		=> date('Y-m-d H:i:s')
			);	
			$cafe_history_id = $this->Mcafehistory->insert($param);

			$this->cafe_result('카페글로 등록 되었습니다.',$post_url);

		} else {
			if(isset ($api_result->message->error->message)){
				$this->cafe_result($api_result->message->error->message);
			}
			else{
				$this->cafe_result('카페글 등록이 실패 하였습니다.');
			}
		}
	}

	/**
	 * 태그 엔터, 공백 제거
	 */
	public function compress($content){
		$CI =& get_instance();
		$buffer = $CI->output->get_output();

		 $search = array(
			'/\n/',				// replace end of line by a space
			'/\t/',				// replace end of line by a tap
			'/\>[^\S ]+/s',		// strip whitespaces after tags, except space
			'/[^\S ]+\</s',		// strip whitespaces before tags, except space
			'/(\s)+/s'			// shorten multiple whitespace sequences
		  );
		 $replace = array(
			' ',
			' ',
			'>',
			'<',
			'\\1'
		  );

		$buffer = preg_replace($search, $replace, $content);

		$CI->output->set_output($buffer);
		$content = $CI->output->get_output();
		return $content;
	}

	/**
	 * 워터마크 생성
	 */
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

			if($config->watermark!=""){
				$thumb_config['image_library'] = 'ImageMagick';
				$thumb_config['library_path'] = '/usr/local/bin/';
				$thumb_config['source_image'] = $thumb_config['source_image'];
				$thumb_config['wm_overlay_path'] = HOME.'/uploads/logo/'.$config->watermark;
				$thumb_config['new_image'] = $thumb_config['new_image'];
				$thumb_config['wm_type'] = 'overlay';
				$thumb_config['wm_vrt_alignment'] = $config->watermark_position_vertical;
				$thumb_config['wm_hor_alignment'] = $config->watermark_position_horizontal;
				$this->image_lib->initialize($thumb_config);
				$this->image_lib->watermark();
				$this->image_lib->clear();
			}
		}
	}

	/**
	 * 가입한 카페를 불러오기 위한 OAuth 인증 페이지 이동
	 */
	public function OAuth($id,$type=""){
		header('Location: http://'.HOST.'/cafe/index.php?id='.$id.'&type='.$type);
		exit;
	}

	/**
	 * 결과 페이지 이동
	 */
	public function cafe_result($message,$cafe_url=""){
		$cafe_url = ($cafe_url) ? urlencode($cafe_url) : "";		
		$message = urlencode($message);
		header('Location: http://'.HOST.'/cafe/result.php?message='.$message.'&cafe_url='.$cafe_url);
		exit;
	}
	
	/**
	 * 카페 히스토리 가져오기
	 */
	public function get_history(){

		$this->load->model("Mcafehistory");
		
		$id = $this->input->post("id");
		$type = $this->input->post("type");
		$cafe_id = $this->input->post("cafe_id");
		$menu_id = $this->input->post("menu_id");

		$type = (!$type) ? "product" : $type; 

		$query = $this->Mcafehistory->get_list($id,$type,$cafe_id,$menu_id);

		echo json_encode($query);
	}
}

/* End of file admincafeapi.php */
/* Location: ./application/controllers/admincafeapi.php */