<?php
class naverCafeApi {
        private $oauth, $apiurl; 

        /* 
        * naverCafeAPI class 생성자
        * $oauth 로 상위 Oauth 객체를 전달 받는다
        * $api_url 은 config.php 에서 변수로 받는다
        */
        public function __construct($oauth, $api_url){
			$this->oauth = $oauth;
			$this->apiurl = $api_url;
		}
        
        /*
        * xml 결과를 호출하여 파싱하는 내부호출  메소드 
        * SimpleXML_Load_String 을 사용하여 받아온 결과를 반환한다.
        */
        private function parse($xml){
			//$result = simplexml_load_string($this->oauth->getLastResponse(), 'SimpleXMLElement', LIBXML_NOCDATA);
			$result = simplexml_load_string($this->oauth->getLastResponse(), 'SimpleXMLElement');

			return $result;
        }
        
        /*
        * 내가 가입한 카페의 목록을 호출하는 메소드 
        */
        public function getMyCafeList($url, $page = 1, $perpage = 20, $order = "C"){
			$url = sprintf('%s%s?search.page=%d&search.perPage=%d&order=%s', $this->apiurl, $url, $page, $perpage, $order);
			$result = $this->parse($this->oauth->fetch($url));

			return $result;
        }

        /*
        * 카페의 게시판 목록을 가져오는 메소드 
        */
        public function getMenuList($url, $clubid, $page, $perpage){
			$url = sprintf('%s%s?clubid=%s&search.page=%d&search.perPage=%d', $this->apiurl, $url, $clubid, $page, $perpage);
			$result = $this->parse($this->oauth->fetch($url));

			return $result;
        }
        
        /*
        * 카페 게시판의 목록을 가져오는 메소드 
        */ 
        public function getArticleList($url, $clubid, $menuid, $page, $perpage){
			$url = sprintf('%s%s?search.clubid=%s&search.menuid=%s&search.page=%d&search.perPage=%d', $this->apiurl, $url, $clubid, $menuid, $page, $perpage);
			$result = $this->parse($this->oauth->fetch($url));

			return $result;
        }
}
?>