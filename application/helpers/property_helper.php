<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * 면적 정보 보여주기 위한 코드 반환
 * UNIT이 old일 경우에는 기본으로는 평수를 보여주고 help에서 평방미터를 보여준다.
 * UNIT이 old가 아니거나 new 일 경우에는 기본으로는 평방미터를 보여주고 help에서 평수를 보여준다.
 */
function area_list($area, $title){

	$CI =& get_instance();
	$CI->load->model("Mconfig");
	$config = $CI->Mconfig->get();

	$text = "<div class=\"help\" data-toggle=\"tooltip\" title=\"";
	if($config->UNIT=="old"){
		$text .= round($area,1) . "㎡\">".$title.round($area*0.3025 ,1)."평</div> ";
	} else {
		$text .= round($area*0.3025,1) . "평\">".$title.round($area ,1)."㎡</div> ";
	}

	return $text;
}

function area_admin($area, $title, $pyeong=false){

	$CI =& get_instance();
	$CI->load->model("Mconfig");
	$config = $CI->Mconfig->get();

	$text = "";
	if($config->UNIT=="old" || $pyeong){
		$text .= $title.round($area*0.3025 ,1)."평 ";
	} else {
		$text .= $title.round($area ,1)."㎡ ";
	}

	return $text;
}

/**
 * 관리자에서 면적 정보 보여주는 부분
 */
function area_view($area, $title){

	$CI =& get_instance();
	$CI->load->model("Mconfig");
	$config = $CI->Mconfig->get();

	$text = "";
	if($area!=0) {
		if($title!="") $text .= "<b>[" . $title . "]</b>";
		if($config->UNIT=="only"){
			$text .= $area . "㎡";
		}
		else{
			$text .= $area . "㎡ (" . round($area*0.3025,1) . "평) ";
		}
	} else {
		if($title!="") $text .= "<b>[" . $title . "]</b>";
		$text .= "문의 ";
	}

	return $text;
}