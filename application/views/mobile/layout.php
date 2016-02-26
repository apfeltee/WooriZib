<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php if( $config->site_name!="" ) { echo $config->site_name; } else { echo $config->name; } ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, target-densitydpi=medium-dpi, user-scalable=0" />
<link href="/assets/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/assets/mobile/css/normalize.css">
<link rel="stylesheet" type="text/css" href="/assets/mobile/css/framework.css">
<link rel="stylesheet" type="text/css" href="/assets/mobile/css/style.css?v=2">
<link rel="stylesheet" type="text/css" href="/assets/mobile/css/simplegrid.css">
<link rel="stylesheet" type="text/css" href="/assets/mobile/css/custom.css">
<link rel="stylesheet" type="text/css" href="/style/mobile">
<script src="/assets/mobile/js/webfont.js"></script>
<script>
WebFont.load({
  google: {
	families: ["Montserrat:400,700"]
  }
});
</script>
<script type="text/javascript" src="/assets/mobile/js/modernizr.js"></script>
<script type="text/javascript" src="/assets/mobile/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/plugin/jquery.form.js"></script>
<script type="text/javascript" src="/assets/plugin/jquery-validation/js/jquery.validate.min.js"></script>
<script src="/assets/mobile/js/init.js"></script>
<script src="/assets/plugin/jquery-leanmodal/jquery.leanModal.min.js"></script>

<link rel="apple-touch-icon" href="/favicon.ico">
<link href="/assets/mobile/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/assets/mobile/css/map.css">
<script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=<?php echo $config->DAUM_MAP_KEY;?>&libraries=services"></script>
<script src="/assets/mobile/js/map.js?d=20150803"></script>
<script>
$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	$("#modal_login_form").validate({
		rules: {
			siEmail: {  
				required: true
			},
			siPw: {  
				required: true
			}
		},
		errorPlacement: function(error, element){
			element.addClass("error_input");
		}
	});

	$("#modal_login_form").ajaxForm({
		beforeSubmit:function(){
			if (!$("#modal_login_form").valid()) return false;
		},
		success:function(data){
			if(data=="0"){
				msg($("#login_error_msg"), "danger" ,"비밀번호가 틀립니다. 다른 비밀번호를 입력하시거나 비밀번호 찾기를 해주세요.");
			} else if(data=="2"){
				msg($("#login_error_msg"), "danger" ,"관리자에 의해 로그인이 거부되었습니다.");
			} else if(data=="3"){
				msg($("#login_error_msg"), "danger" ,"하나의 스마트폰 기기에서만 접속이 가능합니다.");
			} else if(data=="4"){
				msg($("#login_error_msg"), "danger" ,"로그인 기간이 만료 되었습니다.");
			} else if(data=="9"){
				msg($("#login_error_msg"), "danger" ,"해당 이메일로 가입된 회원이 없습니다.회원가입을 해주세요.");
			} else {
				location.href=data;
			}
		}
	});

	$(".view_phone").click(function(){
		$("#target_url").attr("src","/product/view_log/"+$(this).attr("data-id"));
	});
});

function login_leanModal(){
	$('.leanModal').leanModal({
		top : 25,
		closeButton: ".modal_close"
	});
}
</script>  
<?php require_once(HOME.'/uploads/script/logs.php');?>
</head>
<body>
	<!-- BEGIN MODAL-LOGIN -->
	<div id="signup">
		<div style="float:right;">
			<a class="modal_close" href="javascript:void(0)"><img src="/assets/common/img/close.png"/></a>
		</div>
		<div id="signup-ct">
			<div id="signup-header">
				<h2><?php echo lang("menu.login");?></h2>
			</div>
			<form name="modal_login_form" id="modal_login_form" action="/mobile/signin_action" method="post">
				<div id="login_error_msg"></div>
				<div class="txt-fld">
					<label for="siEmail"><?php echo lang("site.email");?></label>
					<?php 
					$email = ($this->input->cookie("wb_user_save")=="on") ? $this->input->cookie("wb_user_id") : "";
					?>
					<input id="siEmail" name="siEmail" type="text" value="<?php echo $email;?>"/>
				</div>
				<div class="txt-fld">
					<label for="siPw"><?php echo lang("site.pw");?></label>
					<input type="password" id="siPw" name="siPw" type="text"/>
				</div>
				<?php $checked = ($this->input->cookie("wb_user_save")=="on") ? "checked" : "";?>
				<div class="txt-fld w-clearfix radios-container pull-left" style="border-bottom:none;">
					<div class="w-clearfix checkbox-field pull-left" style="line-height:none;">
						<div class="checkbox-handle <?php echo $checked;?>"></div>
						<input class="w-checkbox-input checkbox-input" id="checkbox" type="checkbox" name="save" data-name="Checkbox" <?php echo $checked;?>/>
						<label class="w-form-label checkbox-label <?php echo $checked;?>" for="checkbox"></label>
					</div>
					<div style="width:110px;">이메일 저장</div>	
				</div>
				<div class="btn-fld text-right">
					<button type="submit" class="btn btn-primary"><strong><i class="fa fa-sign-in"></i> 로그인</strong></button>
				</div>
			</form>
		</div>
	</div>
	<!-- END MODAL-LOGIN -->

	<!-- BEGIN MODAL-PERMIT AREA -->
	<div id="permit-area">
		<div style="float:right;">
			<a class="modal_close" href="javascript:void(0)" style="z-index:10"><img src="/assets/common/img/close.png"/></a>
		</div>
		<div class="txt-fld">
			<label><i class="ion-android-remove-circle" style="color:red;font-size:22px;"></i> 허용된 지역만 볼 수 있습니다.</label>
		</div>
	</div>
	<!-- END MODAL-PERMIT AREA -->

	<section class="w-section mobile-wrapper">
		<?php echo $content_for_layout;?>
		<div class="page-content loading-mask" id="new-stack">
			<div class="loading-icon">
				<div class="navbar-button-icon icon ion-load-d"></div>
			</div>
		</div>
		<div class="shadow-layer"></div>
	</section>
	<script type="text/javascript" src="/assets/mobile/js/framework.js"></script>
	<!--[if lte IE 9]><script src="js/placeholders.min.js"></script><![endif]-->

	<iframe  src="/common/autologout" style="display:none;"></iframe>	
</body>
</html>
