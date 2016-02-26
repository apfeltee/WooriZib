<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attachment extends CI_Controller {

	public function download($news_id, $id, $filename)
	{
		$this->load->model("Mattachment");
		$attachment = $this->Mattachment->get($news_id, $id);

		$this->load->helper('download');
		$data = file_get_contents(HOME."/uploads/attachment/".$news_id."/".$attachment->filename); // Read the file's contents
		$name = mb_convert_encoding($attachment->originname, 'euc-kr','utf-8');
		force_download($name, $data);
	}

	public function installation_download($news_id, $id, $filename)
	{
		$this->load->model("mattachmentinstallation");
		$attachment = $this->mattachmentinstallation->get($news_id, $id);

		$this->load->helper('download');
		$data = file_get_contents(HOME."/uploads/attachment_installation/".$news_id."/".$attachment->filename); // Read the file's contents
		$name = mb_convert_encoding($attachment->originname, 'euc-kr','utf-8');
		force_download($name, $data);
	}

	public function enquired_contract_download($id)
	{
		$this->load->model("Menquirecontract");
		$attachment = $this->Menquirecontract->get($id);

		$this->load->helper('download');
		$data = file_get_contents(HOME."/uploads/attachment/contract/".$attachment->filename); // Read the file's contents
		$name = mb_convert_encoding($attachment->originname, 'euc-kr','utf-8');
		force_download($name, $data);
	}

	public function estimate_download($enquire_id,$id)
	{
		$this->load->model("Mattachment");

		$attachment = $this->Mattachment->get_estimate($id);

		$this->load->helper('download');
		$data = file_get_contents(HOME."/uploads/attachment/building_enquire/".$enquire_id."/".$attachment->filename); // Read the file's contents
		$name = mb_convert_encoding($attachment->originname, 'euc-kr','utf-8');
		force_download($name, $data);
	}

	public function building_form_download()
	{
		$this->load->helper('download');
		$data = file_get_contents(HOME."/uploads/attachment/building/building.xls");
		$name = mb_convert_encoding("건물정보 양식서.xls", 'euc-kr','utf-8');
		force_download($name, $data);
	}

	public function news_download($news_id,$id)
	{
		$this->load->model("Mnews");

		$attachment = $this->Mnews->get_attachment($id);

		$this->load->helper('download');
		$data = file_get_contents(HOME."/uploads/news/attachment/".$news_id."/".$attachment->filename); // Read the file's contents
		$name = mb_convert_encoding($attachment->originname, 'euc-kr','utf-8');
		force_download($name, $data);
	}

}

/* End of file attachment.php */
/* Location: ./application/controllers/attachment.php */
