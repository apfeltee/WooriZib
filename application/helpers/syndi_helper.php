<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 상품 xml 출력
*/
function product_xml($id="",$state=""){

	$CI = get_instance();

	$CI->load->model("Mproduct");
	$CI->load->model("Mconfig");
	$CI->load->model("Mcategory");

	$product = $CI->Mproduct->get($id);
	$config = $CI->Mconfig->get();
	$category = $CI->Mcategory->get($product->category);

	if(!$product) return;

	$xml = '';

	header ("Content-Type:text/xml");
	
	$xml .= '<?xml version="1.0" encoding="UTF-8"?>';
	$xml .= '<feed xmlns="http://webmastertool.naver.com">';
	$xml .= '	<id>';
	$xml .= '		http://'.HOST.'/product/view/'.$id;
	$xml .= '	</id>';
	$xml .= '	<title>'.$product->title.'</title>';
	$xml .= '	<author>';
	$xml .= '		<name>'.$config->ceo.'</name>';
	$xml .= '		<email>'.$config->email.'</email>';
	$xml .= '	</author>';
	$xml .= '	<updated>'.date('c').'</updated>';
	$xml .= '	<link rel="site"';
	$xml .= '		href="http://'.HOST.'"';
	$xml .= '		title="'.$config->name.'" />';
	$xml .= '	<entry>';
	$xml .= '		<id>';
	$xml .= '			http://'.HOST.'/product/view/'.$id;
	$xml .= '		</id>';
	$xml .= '		<title><![CDATA['.$product->title.']]></title>';
	$xml .= '		<author>';
	$xml .= '			<name>'.$product->member_name.'</name>';
	$xml .= '		</author>';
	$xml .= '		<updated>'.date('c').'</updated>';
	$xml .= '		<published>'.date('c').'</published>';
	$xml .= '		<link rel="via"';
	$xml .= '			href="http://'.HOST.'/main/grid"';
	$xml .= '			title="'.$config->name.' - '.lang('product').'검색(목록)'.'" />';
	$xml .= '		<content type="html"><![CDATA['.$product->content.']]></content>';
	$xml .= '		<summary type="text"><![CDATA['.$product->title.']]></summary>';
	$xml .= '		<category term="'.$category->id.'" label="'.$category->name.'" />';
	$xml .= '	</entry>';
	if($state=="delete"){
		$xml .= '<deleted-entry ref="http://'.HOST.'/product/view/'.$id.'" when="'.date('c').'" />';
	}
	$xml .= '</feed>';

	echo $xml;
}

/**
* 뉴스 xml 출력
*/
function news_xml($id="",$state=""){

	$CI = get_instance();

	$CI->load->model("Mnews");
	$CI->load->model("Mconfig");

	$news = $CI->Mnews->get($id);
	$config = $CI->Mconfig->get();

	if(!$news) return;

	$news->content = '<p><img src="'."http://".HOST."/uploads/news/".$news->thumb_name.'" /></p>'.$news->content;

	$news->content = str_replace("/uploads/news/contents/","http://".HOST."/uploads/news/contents/",$news->content);

	$xml = '';

	header ("Content-Type:text/xml");
	
	$xml .= '<?xml version="1.0" encoding="UTF-8"?>';
	$xml .= '<feed xmlns="http://webmastertool.naver.com">';
	$xml .= '	<id>';
	$xml .= '		http://'.HOST.'/news/view/'.$id;
	$xml .= '	</id>';
	$xml .= '	<title>'.$news->title.'</title>';
	$xml .= '	<author>';
	$xml .= '		<name>'.$config->ceo.'</name>';
	$xml .= '		<email>'.$config->email.'</email>';
	$xml .= '	</author>';
	$xml .= '	<updated>'.date('c').'</updated>';
	$xml .= '	<link rel="site"';
	$xml .= '		href="http://'.HOST.'"';
	$xml .= '		title="'.$config->name.'" />';
	$xml .= '	<entry>';
	$xml .= '		<id>';
	$xml .= '			http://'.HOST.'/news/view/'.$id;
	$xml .= '		</id>';
	$xml .= '		<title><![CDATA['.$news->title.']]></title>';
	$xml .= '		<author>';
	$xml .= '			<name>'.$news->member_name.'</name>';
	$xml .= '		</author>';
	$xml .= '		<updated>'.date('c').'</updated>';
	$xml .= '		<published>'.date('c').'</published>';
	$xml .= '		<link rel="via"';
	$xml .= '			href="http://'.HOST.'/news/index"';
	$xml .= '			title="'.$config->name.' - 뉴스" />';
	$xml .= '		<content type="html"><![CDATA['.$news->content.']]></content>';
	$xml .= '		<category term="'.$news->category.'" label="'.$news->category_name.'" />';
	$xml .= '	</entry>';
	if($state=="delete"){
		$xml .= '<deleted-entry ref="http://'.HOST.'/news/view/'.$id.'" when="'.date('c').'" />';
	}
	$xml .= '</feed>';

	echo $xml;
}

/**
* API 핑 전송
*/
function send_ping($id="",$type="",$state=""){
	
	$CI = get_instance();

	$CI->load->model("Mconfig");
	$config = $CI->Mconfig->get();

	if(!$id || !$type || !$config->naverwebmastertoken) return;

	if($type=="product"){
		$ping_url = urlencode("http://".HOST."/adminsyndiapi/product_xml/".$id."/".$state);
	}
	else if($type="news"){
		$ping_url = urlencode("http://".HOST."/adminsyndiapi/news_xml/".$id."/".$state);
	}

	$ping_client_opt = array(
		CURLOPT_URL => "https://apis.naver.com/crawl/nsyndi/v2",
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => "ping_url=".$ping_url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_TIMEOUT => 10,
		CURLOPT_HTTPHEADER => array(
			"User-Agent: request",
			"Host: apis.naver.com",
			"Pragma: no-cache",
			"Content-type: application/x-www-form-urlencoded",
			"Accept: */*",
			"Authorization: Bearer ".$config->naverwebmastertoken
		)
	);

	$ping = curl_init();
	curl_setopt_array($ping, $ping_client_opt);
	curl_exec($ping); curl_close($ping);

}