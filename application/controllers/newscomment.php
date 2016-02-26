<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Newscomment extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}

	/**
	 * get reply list of a news
	 */
	public function get_json($news_id){
		$this->load->model("Mnewscomment");
		echo json_encode($this->Mnewscomment->get_list($news_id));
	}

	public function get_one_json($id, $step_id, $news_id){
		$this->load->model("Mnewscomment");
		echo json_encode($this->Mnewscomment->get($id, $step_id,$news_id));
	}

	/**
	 * 댓글 등록
	 */
	public function add_action(){
		
		$this->load->model("Mnewscomment");
		$id = $this->Mnewscomment->get_max_id($this->input->post("news_id"));
		$param = Array(
			"id"			=> $id+1,
			"step_id"		=> "0",
			"news_id"		=> $this->input->post("news_id"),
			"member_id"		=> $this->input->post("member_id"),
			"type"			=> "front",
			"name"			=> $this->input->post("name"),
			"pw"			=> $this->_prep_password($this->input->post("pw")),
			"content"		=> $this->input->post("content"),
			"date"			=>date('Y-m-d H:i:s')
		);
		$this->Mnewscomment->insert($param);
		echo "success";
	}

	/**
	 * 댓글 수정
	 */
	public function edit_action(){
		$this->load->model("Mnewscomment");
		$comment = $this->Mnewscomment->get($this->input->post("id"),$this->input->post("step_id"),$this->input->post("news_id"));
		
		if($comment["pw"] == $this->_prep_password($this->input->post("pw"))){
			$where = Array(
				"id"			=> $this->input->post("id"),
				"step_id"		=> $this->input->post("step_id"),
				"news_id"		=> $this->input->post("news_id")
			);
			$param = Array(
				"news_id"		=> $this->input->post("news_id"),
				"member_id"		=> $this->input->post("member_id"),
				"type"			=> "front",
				"name"			=> $this->input->post("name"),
				"pw"			=> $this->_prep_password($this->input->post("pw")),
				"content"		=> $this->input->post("content"),
				"date"			=>date('Y-m-d H:i:s')
			);
			$this->Mnewscomment->update($param, $where);
			echo "success";
		} else {
			echo "fail";
		}
	}


	/**
	 * 댓글 삭제
	 */
	public function delete_action(){
		$this->load->model("Mnewscomment");

		$id = $this->input->post("id");
		$step_id = $this->input->post("step_id");
		$news_id = $this->input->post("news_id");

		$comment = $this->Mnewscomment->get($id,$step_id,$news_id);

		if($comment['pw'] == $this->_prep_password($this->input->post("pw"))){
			$where = Array(
				"id"		=> $id,
				"step_id"	=> $step_id,
				"news_id"	=> $news_id
			);
			//하위댓글 존재 여부 체크
			if($this->input->post("step_id")==0){
				$child_count = $this->Mnewscomment->get_child_count($id,$news_id);
				$param = array('delete' => 'Y');
				if($child_count) $this->Mnewscomment->update_delete_flag($param,$where);
				else $this->Mnewscomment->delete_comment($where);
			}
			else{
				$this->Mnewscomment->delete_comment($where);
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
		$this->load->model("Mnewscomment");
		$step_id = $this->Mnewscomment->get_comment_step_id($this->input->post("news_id"),$this->input->post("id"));

		$param = Array(
			"id"			=> $this->input->post("id"),
			"step_id"		=> $step_id+1,
			"news_id"		=> $this->input->post("news_id"),
			"member_id"		=> $this->input->post("member_id"),
			"type"			=> "front",
			"name"			=> $this->input->post("name"),
			"pw"			=> $this->_prep_password($this->input->post("pw")),
			"content"		=> $this->input->post("content"),
			"date"			=>date('Y-m-d H:i:s')
		);

		$result = $this->Mnewscomment->insert($param);

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

/* End of file newscomment.php */
/* Location: ./application/controllers/newscomment.php */