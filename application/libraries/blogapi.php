<?php
include_once(dirname(__FILE__) . '/xmlrpc-3.0.0.beta/lib/xmlrpc.inc');

class Blogapi {
    private $xmlrpc;
	private $url;
    private $user_id;
    private $api_id;
    private $api_pw;
	private $obj;
		
    public function __construct()
    {

	}
    
    public function init($type, $address, $user_id, $blog_id,$key)
    {
        $ret = FALSE;
		if($type=="naver"){
			$this->url = "https://api.blog.naver.com/xmlrpc";
		} else if($type=="tistory"){
			$this->url = "http://".$address.".tistory.com/api";
		} 

		$this->api_id = $blog_id;
		$this->user_id = $user_id;
		$this->api_pw = $key;
		
		$GLOBALS['xmlrpc_internalencoding']='UTF-8';
		$this->xmlrpc = new xmlrpc_client($this->url);
		$this->xmlrpc->verifyhost = 2;
		$this->xmlrpc->setSSLVerifyPeer(FALSE);
		$this->xmlrpc->request_charset_encoding = 'UTF-8';
		$ret = TRUE;
        
        return $ret;
    }
    
    public function post($title, $desc, $tags = NULL)
    {

        $msg = new xmlrpcmsg('metaWeblog.newPost',
            array(
                new xmlrpcval($this->api_id, 'string'), // blog id 
                new xmlrpcval($this->user_id, 'string'), // User id
                new xmlrpcval($this->api_pw, 'string'), // 글쓰기 API Password
                new xmlrpcval(array(
                        'title' => new xmlrpcval($title, 'string'),
                        'description' => new xmlrpcval($desc, 'string'),
                        'dateCreated'  => new xmlrpcval(date('Ymd\TH:i:s'), 'dateTime.iso8601'),
                        'tags' => new xmlrpcval($tags)
                    ), 'struct'), // 블러그 포스팅 내용
                new xmlrpcval(TRUE, 'boolean')
            )
        );

        $res = $this->xmlrpc->send($msg);

        return $res->faultCode() ? htmlspecialchars($res->faultString()) : $res->value()->scalarval();
    }
    
    public function get_cate()
    {
        $ret = array();
        $msg = new xmlrpcmsg("metaWeblog.getCategories",
            array(
                new xmlrpcval($this->api_id, "string"),
                new xmlrpcval($this->user_id, "string"),
                new xmlrpcval($this->api_pw, "string"),
            )
        );
        
        $res = $this->xmlrpc->send($msg)->value();
        for($i=0; $i < $res->arraysize(); $i++) {
            $ret[] = $res->arraymem($i)->me['struct']['title']->me['string']; // 카테고리 이름 배열
        }
        
        return $ret;
    }
    
	/**
	 * 이미지업로드
	 */
    public function add_file($filename)
    {	
		$CI =& get_instance();
		$CI->load->helper('file');
		
		$this->add_random($filename);
		$path_parts = pathinfo($filename);
		$img = getimagesize(HOME.$filename);
		$im = read_file(HOME.$filename);

		$msg = new xmlrpcmsg('metaWeblog.newMediaObject',
            array(
                new xmlrpcval($this->api_id, 'string'), // blog id (네이버 ID)
                new xmlrpcval($this->user_id, 'string'), // User id (네이버 ID)
                new xmlrpcval($this->api_pw, 'string'), // 글쓰기 API Password
                new xmlrpcval(array(
                        'name' => new xmlrpcval(basename($filename), 'string'),
                        'type' => new xmlrpcval($img['mime'], 'string'),
                        'bits'  => new xmlrpcval($im, 'base64')
                    ), 'struct') // 업로드 파일
            )
        );
		
		$msg->request_charset_encoding = 'UTF-8'; 
        $res = $this->xmlrpc->send($msg);

        return $res->faultCode() ? htmlspecialchars($res->faultString()) : $res->value()->scalarval();
    }


    /**
     * 보이는 것은 동일해도 다른 이미지로 인식하기 위한 장치를 추가한 것인데 다르게 인식이 될까?
     */
	private function add_random($filename){
			$this->obj =& get_instance();
			$this->obj->load->library('image_lib');
			$config['image_library'] = 'ImageMagick';
			$config['library_path'] = '/usr/local/bin/';
			$config['source_image'] = HOME.$filename;
			$config['wm_overlay_path'] = HOME.'/assets/common/img/none.png';
			$config['wm_type'] = 'overlay';
			$config['wm_opacity'] = '1';
			$config['wm_vrt_alignment'] = 'bottom';;
			$config['wm_hor_alignment'] = 'right';
			$this->obj->image_lib->initialize($config);
			$this->obj->image_lib->watermark();
	}
}
