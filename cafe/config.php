<?php
define('BASEPATH',$_SERVER['DOCUMENT_ROOT']);

include(BASEPATH."/application/config/database.php");

/*** 데이터베이스 접속 시작 ***/
$MYSQL_HOST = $db['default']['hostname'];
$MYSQL_DB = $db['default']['database'];
$MYSQL_ID = $db['default']['username'];
$MYSQL_PASSWORD = $db['default']['password'];
$DB_CONNECT = mysql_connect($MYSQL_HOST, $MYSQL_ID, $MYSQL_PASSWORD);
mysql_select_db($MYSQL_DB, $DB_CONNECT);
mysql_query("SET NAMES 'utf8'");
if ( !$DB_CONNECT ) {echo "mysql 데이터 베이스에 연결할 수 없습니다."; exit;}
/*** 데이터베이스 접속 종료 ***/

// Request Token 요청 주소
$request_token_url = 'https://nid.naver.com/naver.oauth?mode=req_req_token';  // 신버전

// 사용자 인증 URL
$authorize_url = 'https://nid.naver.com/naver.oauth?mode=auth_req_token'; //신버전

// Access Token URL
$access_token_url = 'https://nid.naver.com/naver.oauth?mode=req_acc_token'; //신버전

// Consumer 정보 (Consumer를 등록하면 얻어올 수 있음.)

$sqlstr = "select navercskey,navercssecret from config limit 1";
$sqlqry = mysql_query($sqlstr);
$list = mysql_fetch_array($sqlqry);

$consumer_key = $list['navercskey'];
$consumer_secret = $list['navercssecret'];
$callback_url = "http://".$_SERVER['HTTP_HOST']."/cafe/callback.php";

// API prefix (보호된 자원이 있는 URL의 prefix)
$api_url = 'http://openapi.naver.com';
$getMyCafeList = "/cafe/getMyCafeList.xml";
$getArticleList = "/cafe/getArticleList.xml";
$getMenuList = "/cafe/getMenuList.xml";

echo mysql_error();
mysql_close();
// Service Provider와 통신할 인터페이스를 갖고 있는 객체 생성.

$oauth = new OAuth($consumer_key, $consumer_secret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_AUTHORIZATION);
?>