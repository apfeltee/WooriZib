<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Copyright (C) dungzi <http://www.dungzi.com> */

/**
 * @file 	concern.php
 * @brief 	상세보기 화면에서 방문자가 연락처를 남기는 기능을 수행합니다.\n
 *		기존에 ask controller에 들어 있어서 문의하기와 혼용되었기에 따로 분리하여 제작을 하였습니다.\n
 *		기존에는 매물만 사용했으나 분양이나 추후 확장되는 것까지 모두 사용해야 하기 때문에 모듈구분자를 추가하였습니다.\n
 *
 * @todo 	SMS발송 기능을 cafe24가 아닌 dotname에 새롭게 구현을 하였기에 전송부분과 로그를 남기는 부분을 수정해야 합니다. 기존 로그 남기는 기능은 불필요해 졌습니다.
 * @author 	Kang,Dejung (webplug@gmail.com)
 */
class Concern extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}

	/**
	 *
	 * @brief 	이 기능은 매물 상세 페이지나 분양 상세 페이지 등에서 전화번호를 남겼을 때 동작하는 함수이다.\n
	 *		매물 상세 정보를 보고 바로 전화를 할 수도 있지만 전화번호를 남길 수도 있으므로 중요한 기능이다.
	 *
	 * @param 	module : product, installation
	 * @param 	id : 매물번호, 분양번호 등등
	 * @param 	mobile : 남긴 전화번호
	 */
	public function action(){

		$this->load->model("Mmember");
		$this->load->model("Mconfig");

		$config = $this->Mconfig->get();		

		//해당 매물 담당자에게 SMS로 알림 발송
		if($this->input->post("module")=="product"){
			$msg = "[".$this->input->post("mobile")."] http://".HOST . "/product/view/" . $this->input->post("id") . " ";
			$member = $this->Mmember->get_by_product($this->input->post("id"));
		} else if($this->input->post("module")=="installation"){
			$msg = "[".$this->input->post("mobile")."] http://".HOST . "/installation/view/" . $this->input->post("id") . " ";	
			$member = $this->Mmember->get_by_installation($this->input->post("id"));
		} else {
			echo "0"; //실패
			exit;
		}

		if($config->sms_cnt){
			$this->load->helper("sender");
			$this->load->model("Msmshistory");

			$sms_result = sms($config->mobile,$member->phone,"",$msg);

			if($sms_result=="발송성공"){						 
				$this->Mconfig->update(Array("sms_cnt" => ($config->sms_cnt - 1)),"");
			}
			$param = Array(
				"sms_from" => $config->mobile,
				"sms_to" => $member->phone,
				"msg" => $msg,
				"type" => "A",
				"minus_count" => ($sms_result=="발송성공") ? 1 : 0,
				"result" => $sms_result,
				"page" => "concern",
				"date" => date('Y-m-d H:i:s')
			);
			$this->Msmshistory->insert($param);				
		}

		//로그 남기기
		$this->load->model("Mconcern");
		$param = Array(
			"source"=>$this->input->post("mobile"),
			"data_id"=>$this->input->post("id"),
			"module"=>$this->input->post("module"),
			"member"=>$member->phone,
			"result"=>($sms_result=="발송성공") ? 1 : 0,
			"date"=>date('Y-m-d H:i:s')
		);

		$this->Mconcern->insert($param);

		echo "1"; //성공
	}
}

/* End of file concern.php */
/* Location: ./application/controllers/concern.php */