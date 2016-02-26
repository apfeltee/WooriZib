<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admincustom extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}
	
	/**
	 * 스킨목록
	 */
	function index(){
		$this->load->helper('file');
		//먼저 기존 파일을 백업한다.
		$this->config->load('custom');
		
		$data["custom"] = Array(
			'skin_color' => $this->config->item('skin_color'),
			'user_color' => $this->config->item('user_color'),
			'menu_name' => $this->config->item('menu_name'),
			'menu_link' => $this->config->item('menu_link'),
			'title_font' => $this->config->item('title_font'),
			'menu_font' => $this->config->item('menu_font'),
			'menu_size' => $this->config->item('menu_size'),
			'menu_selected' => $this->config->item('menu_selected'),
			'body_font' => $this->config->item('body_font'),
			'top_bar_favor' => $this->config->item('top_bar_favor'),
			'top_bar_font' => $this->config->item('top_bar_font'),
			'header_bg' => $this->config->item('header_bg'),
			'header_font' => $this->config->item('header_font'),
			'checkbox' => $this->config->item('checkbox'),
			'request_bg' => $this->config->item('request_bg'),
			'request_font' => $this->config->item('request_font'),
			'footer_bg' => $this->config->item('footer_bg'),
			'footer_font' => $this->config->item('footer_font'),
			'map_list_border' => $this->config->item('map_list_border'),
			'home_number' => $this->config->item('home_number')
		);

		$this->layout->admin('custom_index', $data);
	}

	/**
	 * 적용
	 */
	public function apply_action(){

		$this->config->load('custom');

		$user_color = $this->config->item('user_color');
		$wide_type = $this->config->item('wide_type');
		$bg_image = $this->config->item('bg_image');

		$save =  Array(
			'skin_color'	=> $this->input->post('skin_color'),
			'user_color'	=> $user_color,
			'wide_type'		=> $wide_type,
			'bg_image'		=> $bg_image,
			'menu_name'		=> $this->input->post('menu_name'),
			'menu_link'		=> $this->input->post('menu_link'),
			'menu_size'		=> $this->input->post('menu_size'),
			'menu_selected' => $this->input->post('menu_selected'),
			'top_bar_favor' => $this->input->post('top_bar_favor'),
			'top_bar_font'	=> $this->input->post('top_bar_font'),
			'header_bg'		=> $this->input->post('header_bg'),
			'header_font'	=> $this->input->post('header_font'),
			'checkbox'		=> $this->input->post('checkbox'),
			'request_bg'	=> $this->input->post('request_bg'),
			'request_font'	=> $this->input->post('request_font'),
			'footer_bg'		=> $this->input->post('footer_bg'),
			'footer_font'	=> $this->input->post('footer_font'),
			'map_list_border' => $this->input->post('map_list_border'),
			'home_number'	=> $this->input->post('home_number')
		);

		$this->load->helper('file');

		//새로 설정 파일을 쓴다.
		$header = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');";
		$footer1 = "/* End of file custom.php */";
		$footer2 = "/* Location: ./application/config/custom.php */";
		write_file(HOME."/application/config/custom.php", $header . "\n" . "\$config = " . var_export($save, true) . ";\n" . $footer1 . "\n" . $footer2);
		redirect("admincustom/index","refresh");
	}

	public function skin_select_action(){

		$this->config->load('custom');

		if($this->input->post('skin_color')) $skin_color = $this->input->post('skin_color');
		else $skin_color = $this->config->item('skin_color');

		if($this->input->post('user_color')) $user_color = $this->input->post('user_color');
		else $user_color = '';

		if($this->input->post('wide_type'))	$wide_type = $this->input->post('wide_type');
		else $wide_type = $this->config->item('wide_type');

		if($this->input->post('bg_image')) $bg_image = $this->input->post('bg_image');
		else $bg_image = $this->config->item('bg_image');

		$save =  Array(
			'skin_color'	=> $skin_color,
			'user_color'	=> $user_color,
			'wide_type'		=> $wide_type,
			'bg_image'		=> $bg_image,
			'menu_name'		=> $this->config->item('menu_name'),
			'menu_link'		=> $this->config->item('menu_link'),
			'title_font'	=> $this->config->item('title_font'),
			'menu_font'		=> $this->config->item('menu_font'),
			'menu_size'		=> $this->config->item('menu_size'),
			'menu_selected' => $this->config->item('menu_selected'),
			'body_font'		=> $this->config->item('body_font'),
			'top_bar_favor' => $this->config->item('top_bar_favor'),
			'top_bar_font'	=> $this->config->item('top_bar_font'),
			'header_bg'		=> $this->config->item('header_bg'),
			'header_font'	=> $this->config->item('header_font'),
			'checkbox'		=> $this->config->item('checkbox'),
			'request_bg'	=> $this->config->item('request_bg'),
			'request_font'	=> $this->config->item('request_font'),
			'footer_bg'		=> $this->config->item('footer_bg'),
			'footer_font'	=> $this->config->item('footer_font'),
			'map_list_border' => $this->config->item('map_list_border'),
			'home_number'	=> $this->config->item('home_number')
		);

		$this->load->helper('file');

		//새로 설정 파일을 쓴다.
		$header = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');";
		$footer1 = "/* End of file custom.php */";
		$footer2 = "/* Location: ./application/config/custom.php */";
		write_file(HOME."/application/config/custom.php", $header . "\n" . "\$config = " . var_export($save, true) . ";\n" . $footer1 . "\n" . $footer2);
		
		redirect("home/index/1","refresh");
	}

	public function select(){
		redirect("home/index/1","refresh");
	}
}

/* End of file Admincustom.php */
/* Location: ./application/controllers/Admincustom.php */