<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logo extends CI_Controller {

	/**
	 * 이 함수를 수정하면 기존에 올린 글들의 로고가 안 보이기 때문에 별도의 함수를 만들어서 신규 로그 측정기능을 구현해야 한다.
	 * 이 함수에서 로그축적하던 코드는 삭제하였다.
	 */
	public function blog($blog_id, $product_id){
		$this->load->helper('file');
		header('Content-Type: image/png'); //<-- send mime-type header
		$this->load->model("Mconfig");
		$config = $this->Mconfig->get("logo");
		echo read_file(HOME."/uploads/logo/".$config->logo);
	}

	/**
	 * 2014년 12월 17일 블로그 업그레이드에 따라 새롭게 제작한 로그 측정 함수
	 */
	public function st($blog_id){
		$this->load->helper('file');
		header('Content-Type: image/png'); //<-- send mime-type header

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get("logo");

		echo read_file(HOME."/uploads/logo/".$config->logo);
		
		if($this->input->ip_address()!=$config->ip){
			
			$this->load->model("Mlog");
			$this->load->library('user_agent');

			$this->load->helper("check");
			if(MobileCheck()){
				$mobile = "1";
			} else {
				$mobile = "0";
			}
			
			$param = Array(
				"ip"				=> $this->input->ip_address(),
				"mobile"			=> $mobile, 
				"user_agent"	=> $this->session->userdata("user_agent"),
				"blog_id"		=> $blog_id,
				"date"			=> date('Y-m-d H:i:s')
			);

			$this->Mlog->add_blog($param);
		}	
	}

	/***
	 * 현재 사이트의 로고를 출력
	 */
	public function is_logo(){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$this->load->helper('file');

		header('Content-Type: image/png');
		echo read_file(HOME."/uploads/logo/".$config->logo);

	}

	public function init_db(){
		$this->load->helper('file');
		$this->load->model("Mlog");
		if(!$this->db->table_exists('products')){
			$this->Mlog->init_db(read_file(HOME."/data/init.sql"));
			$this->Mlog->init_db_sample(read_file(HOME."/data/init_data.sql"));
			$this->Mlog->init_db(read_file(HOME."/data/address.sql"));
			$this->Mlog->init_db(read_file(HOME."/data/parent_address.sql"));
			$this->Mlog->init_db(read_file(HOME."/data/subway_busan.sql"));
			$this->Mlog->init_db(read_file(HOME."/data/subway_daejon.sql"));
			$this->Mlog->init_db(read_file(HOME."/data/subway_seoul.sql"));
			$this->Mlog->init_db(read_file(HOME."/data/subway_seoul_etc.sql"));
			$this->Mlog->init_db(read_file(HOME."/data/subway_kwangju.sql"));
			$this->Mlog->init_db(read_file(HOME."/data/subway_daegu.sql"));
			$this->Mlog->init_db_sample(read_file(HOME."/data/init_viral.sql"));
			$this->Mlog->init_db(read_file(HOME."/data/init_danzi.sql"));
			echo 'success';
		}
		else{
			echo "ERROR";
		}
	}

	public function init_db_danzi(){
		$this->load->helper('file');
		$this->load->model("Mlog");
		if(!$this->db->table_exists('products')){
			$this->Mlog->init_db(read_file(HOME."/data/init_dungzi.sql"));
			echo 'success';
		}
		else{
			echo "ERROR";
		}
	}	

	public function mailer(){

		$this->load->library('email');

		$config['smtp_host'] = 'smtp.works.naver.com';
		$config['smtp_user'] = 'tech@dungzi.com';
		$config['smtp_pass'] = 'Kks26459557!';
		$config['wordwrap'] = TRUE;

		$this->email->initialize($config);

		$this->email->from('tech@dungzi.com', '강대중');
		$this->email->to('webplug@gmail.com'); 
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');	
		$this->email->send();

		echo $this->email->print_debugger();
	}	

	public function schema(){
		$this->load->database();

		$query = $this->db->query("SHOW TABLES");
		foreach($query->result() as $val){
			foreach($val as $v){
				echo "<br>TABLE:".$v."<br>";
				$q = $this->db->query("DESC ".$v);
				//print_r($q->result());
			}
		}

	}
}

/* End of file logo.php */
/* Location: ./application/controllers/logo.php */
