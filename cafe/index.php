<?php
require "config.php";
require "naverCafeApi.php";
header("Content-Type: text/html; charset=UTF-8");

session_start();

// 매물 id값을 콜백 후에도 사용하기 위하여 session에 저장.
if(isset($_GET['id'])) $_SESSION["c_id"] = $_GET['id'];
if(isset($_GET['type'])) $_SESSION["c_type"] = $_GET['type'];

// access_token이 발급된 상태가 아니라면, OAuth 인증 절차 시작
if(!$_SESSION['access_token'] ) {
	try {
		// Request Token 요청
		$request_token_info = $oauth->getRequestToken($request_token_url, $callback_url);

		// 얻어온 Request Token을 이후 Access Token과 교환하기 위해 session에 저장.
		$_SESSION["request_token_secret"] = $request_token_info["oauth_token_secret"];

		// 사용자 인증 URL로 redirect
		header('Location: '.$authorize_url.'&oauth_token='.$request_token_info['oauth_token']);
		exit;
	} catch(OAuthException $E) {
		//print_r($E);
		//exit;
	}
} else {
	// Access_token 이 있다면 API 호출하여 결과 값 반환
	// access_token 을 이용하여 set하여 인증값 생성 
	$oauth->setToken($_SESSION['access_token'],$_SESSION['access_token_secret']);
	
	// cafeAPI 호출을 위한 class 객체 생성 
	$cafe_api = new naverCafeApi($oauth, $api_url);

	//타입(mode)에 따라 해당 API 호출 
	switch($_GET['mode']){
		// 카페의 게시판 목록 표시 
		case "getMenuList":
			$clubid = $_GET['clubid'];

			$page = $_GET['page'];
			(!$page) ? $page = 1 : $page = $_GET['page'];

			$per_page = $_GET['perpage'];
			(!$per_page) ? $per_page = 500 : $per_page = $_GET['perpage'];

			$result = $cafe_api->getMenuList($getMenuList, $clubid, $page, $per_page);
			break;
		//가입한 카페 목록 표시
		case "getMyCafeList":
		default:
			$page = $_GET['page'];
			(!$page) ? $page = 1 : $page = $_GET['page'];

			$per_page = $_GET['perpage'];
			(!$per_page) ? $per_page = 500 : $per_page = $_GET['perpage'];

			$order = $_GET['order'];
			(!$order) ? $order = "C" : $order = $_GET['order'];

			$result = $cafe_api->getMyCafeList($getMyCafeList, $page, $per_page, $order);

			if($result->error_code[0]=="024"){ //네이버 세션이 유효하지 않거나 변조 되었을 경우 재인증 처리 함
				unset($_SESSION['access_token']);
				header('Location: http://'.$_SERVER['HTTP_HOST'].'/admincafeapi/OAuth/'.$_GET['id']);
			}
			break;
	}
}


?>
<html>
<head>
<link href="/assets/plugin/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/assets/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="/assets/plugin/jquery.min.js" type="text/javascript"></script>
<script src="/assets/plugin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<style>
.cafe_loading {
	color:black;
	text-align:center;
	padding-top:350px;
	z-index:100;
    position: fixed;
    display:none;
    height:100%;
    width:100%;
    top:0px;
    left:0px;
}

.cafe_loading span {
	background-color:#efefef;
	border:1px solid #cacaca;
	padding:35px;
}
</style>
</head>
<body id="container">
<?php 
switch($_GET['mode']){
	case "getMenuList":
		include "view/getMenuList.html";
		break;
	case "getMyCafeList":
	default:
		include "view/getMyCafeList.html";
		break;
}
?>
<script>
$(window).load(function() {
  var strWidth = 460;
  var strHeight = 760;

  if ( window.innerWidth && window.innerHeight && window.outerWidth && window.outerHeight ) {
    strWidth = $('#container').outerWidth() + (window.outerWidth - window.innerWidth);
    strHeight = $('#container').outerHeight() + (window.outerHeight - window.innerHeight);
  }
  else {
    var strDocumentWidth = $(document).outerWidth();
    var strDocumentHeight = $(document).outerHeight();

    window.resizeTo ( strDocumentWidth, strDocumentHeight );

	var strMenuWidth = strDocumentWidth - $(window).width();
    var strMenuHeight = strDocumentHeight - $(window).height();
    strWidth = $('#container').outerWidth() + strMenuWidth;
    strHeight = $('#container').outerHeight() + strMenuHeight;
  }
  window.resizeTo( strWidth, strHeight );
}); 

function get_article(value){
	$.ajax({
		type: 'get',
		url: '/cafe/index.php?mode=getMenuList&clubid='+value,
		dataType : 'html',
		success: function(data) {
			$("#menu_list").html(data);
		}
	});
}

function cafe_upload(){

	var id = $("#id").val();
	var type = $("#type").val();
	var cafe_id = $("#cafe_id option:selected").val();
	var menu_id = "";
	var cafe_title = $("#cafe_title").val();

	if($("#menu_id").length > 0){
		menu_id = $("#menu_id option:selected").val();
	}
	if(!cafe_id){
		alert('카페를 선택 해주시기 바랍니다.');
		return false;
	}
	if(!menu_id){
		alert('게시판을 선택 해주시기 바랍니다.');
		return false;
	}

	var is_equal = false;
	$("#history_list").find(".history").css("color","");
	$("#history_list").find(".history").each(function(){
		if($(this).html() == cafe_title){
			alert("이전에 등록한 카페 제목이 동일합니다.\n\n유사문서로 노출이 안될 수 있으니\n\n제목을 다르게 수정하여 등록 해주시기 바랍니다.");
			$(this).css("color","red");
			is_equal = true;
			return false;
		}
	});

	if(is_equal){
		$("#cafe_title").focus();
		return false;
	}

	if(confirm("카페글로 등록 하시겠습니까?")){
		$(".cafe_loading").show();
		$("#cafe_form").submit();
	}
}

function replace_url_encode(str){
	str = str.replace(/[/]/gi,"~");
	return str;
}

function cafe_history(menu_id){

	var id = $("#id").val();
	var type = $("#type").val();
	var cafe_id = $("#cafe_id option:selected").val();

	$.ajax({
		url: "/admincafeapi/get_history/"+Math.round(new Date().getTime()),
		type: "POST",
		dataType: "json",
		data: {
			id: id,
			type: type,
			cafe_id: cafe_id,
			menu_id: menu_id
		},
		success: function(data) {
			var str = "";
			if(data!=""){
				$.each(data, function(key, val) {
					if(val['title']){
						str += "<div class='history' style='border-bottom:1px solid #e5e5e5;padding:5px 0 5px 0;'>"+val['title']+"</div>";
					}					
				});			
			}
			if(!str) str = "카페 등록 이력이 없습니다.";

			$("#history_list").html(str);
		}
	});	

}
</script>
</body>
</html>