<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Portfoliocomment extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}

	/**
	 * get reply list of a portfolio
	 */
	public function get_json($portfolio_id){
		$this->load->model("Mportfoliocomment");
		echo json_encode($this->Mportfoliocomment->get_list($portfolio_id));
	}

	public function get_one_json($id, $step_id, $portfolio_id){
		$this->load->model("Mportfoliocomment");
		echo json_encode($this->Mportfoliocomment->get($id, $step_id,$portfolio_id));
	}

	/**
	 * 댓글 등록
	 */
	public function add_action(){
		
		$this->load->model("Mportfoliocomment");
		$id = $this->Mportfoliocomment->get_max_id($this->input->post("portfolio_id"));
		$param = Array(
			"id"			=> $id+1,
			"step_id"		=> "0",
			"portfolio_id"		=> $this->input->post("portfolio_id"),
			"member_id"		=> $this->input->post("member_id"),
			"type"			=> "front",
			"name"			=> $this->input->post("name"),
			"pw"			=> $this->_prep_password($this->input->post("pw")),
			"content"		=> $this->input->post("content"),
			"date"			=>date('Y-m-d H:i:s')
		);
		$this->Mportfoliocomment->insert($param);
		echo "success";
	}

	/**
	 * 댓글 수정
	 */
	public function edit_action(){
		$this->load->model("Mportfoliocomment");
		$comment = $this->Mportfoliocomment->get($this->input->post("id"),$this->input->post("step_id"),$this->input->post("portfolio_id"));
		
		if($comment["pw"] == $this->_prep_password($this->input->post("pw"))){
			$where = Array(
				"id"			=> $this->input->post("id"),
				"step_id"		=> $this->input->post("step_id"),
				"portfolio_id"		=> $this->input->post("portfolio_id")
			);
			$param = Array(
				"portfolio_id"		=> $this->input->post("portfolio_id"),
				"member_id"		=> $this->input->post("member_id"),
				"type"			=> "front",
				"name"			=> $this->input->post("name"),
				"pw"			=> $this->_prep_password($this->input->post("pw")),
				"content"		=> $this->input->post("content"),
				"date"			=>date('Y-m-d H:i:s')
			);
			$this->Mportfoliocomment->update($param, $where);
			echo "success";
		} else {
			echo "fail";
		}
	}


	/**
	 * 댓글 삭제
	 */
	public function delete_action(){
		$this->load->model("Mportfoliocomment");

		$id = $this->input->post("id");
		$step_id = $this->input->post("step_id");
		$portfolio_id = $this->input->post("portfolio_id");

		$comment = $this->Mportfoliocomment->get($id,$step_id,$portfolio_id);

		if($comment['pw'] == $this->_prep_password($this->input->post("pw"))){
			$where = Array(
				"id"			=> $id,
				"step_id"		=> $step_id,
				"portfolio_id"	=> $portfolio_id
			);
			//하위댓글 존재 여부 체크
			if($this->input->post("step_id")==0){
				$child_count = $this->Mportfoliocomment->get_child_count($id,$portfolio_id);
				$param = array('delete' => 'Y');
				if($child_count) $this->Mportfoliocomment->update_delete_flag($param,$where);
				else $this->Mportfoliocomment->delete_comment($where);
			}
			else{
				$this->Mportfoliocomment->delete_comment($where);
			}
			echo "success";
		} else {
			echo "fail";
		}
	}

	/**
	 * 댓글의 댓글 등록
	 */
	public function add_reply_action(){
		$this->load->model("Mportfoliocomment");
		$step_id = $this->Mportfoliocomment->get_comment_step_id($this->input->post("portfolio_id"),$this->input->post("id"));

		$param = Array(
			"id"			=> $this->input->post("id"),
			"step_id"		=> $step_id+1,
			"portfolio_id"	=> $this->input->post("portfolio_id"),
			"member_id"		=> $this->input->post("member_id"),
			"type"			=> "front",
			"name"			=> $this->input->post("name"),
			"pw"			=> $this->_prep_password($this->input->post("pw")),
			"content"		=> $this->input->post("content"),
			"date"			=>date('Y-m-d H:i:s')
		);

		$result = $this->Mportfoliocomment->insert($param);

		echo ($result) ? "success" : "fail";
	}


	/**
	 * 비밀번호 인크립션
	 */
	private function _prep_password($password)
	{
		 return sha1($password.$this->config->item('encryption_key'));
	}
}

/* End of file portfolio_comment.php */
/* Location: ./application/controllers/portfolio_comment.php */