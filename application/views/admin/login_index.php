<?php
	if($this->input->cookie("wb_admin_save")=="on"){
		$email = $this->input->cookie("wb_admin_id");	
	}
	else{
		$email = ($config->IS_DEMO) ? "test1@test.com" : "";
	}
?>
<link href="/assets/admin/css/login-soft.css" rel="stylesheet" type="text/css"/>
<script src="/assets/common/js/init.js" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->

<script src="/assets/admin/js/login-soft.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	$("#login_form").ajaxForm({
		beforeSubmit: function(){
			if (!$("#login_form").valid()) return false;
		},
		success:function(data){
			$("#login_form")[0].reset();
			switch(data){
				case "home":
					location.replace("/adminhome/index");
					break;
				case "product":
					location.replace("/adminproduct/index");
					break;
				case "fail":
					msg($("#login_msg"), "danger" ,"로그인에 실패했습니다.");
					break;
				case "valid":
					msg($("#login_msg"), "info" ,"승인요청이 진행 중 입니다.");
					break;
			}
			return;
		}
	});
	$("#forget_form").ajaxForm({
		beforeSubmit: function(){
			if (!$("#forget_form").valid()) return false;
		},
		success:function(data){
			$("#forget_form")[0].reset();
			if(data=="1"){
				msg($("#forget_msg"), "success" ,"변경된 패스워드를 메일로 발송 해드렸습니다.");
			} else {
				msg($("#forget_msg"), "danger" ,"오류가 발생합니다. 전화주세요.");
			}
			return;
		}
	});
	$("#register_form").ajaxForm({
		beforeSubmit: function(){
			if (!$("#register_form").valid()) return false;
		},
		success:function(data){
			$("#register_form")[0].reset();			
			$("#accept").parent().removeClass("checked");
			if(data=="1"){
				msg($("#register_msg"), "success" ,"가입신청이 완료되었습니다. 관리자 승인 후 이용 가능합니다.");
			} else {
				msg($("#register_msg"), "danger" ,"오류가 발생합니다. 전화주세요.");
			}
			return;
		}
	});
	
	$("form").keydown(function (e) {
		if (e.which == 13) {
			$(this).trigger("submit");
		}
	});

	Login.init();
});
</script>
<style>
.loading {
	position:absolute;
	left:50%;
	top:50%;
	display:none;
}
</style>
<!-- END JAVASCRIPTS -->

<!-- BEGIN LOGO -->
<div class="logo">
	 <a href="/" target="_blank"><?php if($config->logo==""){echo "<img src='/assets/common/img/dungzi.png'>";} else {?><img src="/uploads/logo/<?php echo $config->logo;?>" alt="<?php echo $config->name;?>" /><?php }?></a>
</div>
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->

<!-- BEGIN LOGIN -->
<div class="content">
	<div class="loading"><img src="/assets/common/img/load_360.gif"></div>
	<!-- BEGIN LOGIN FORM -->
	<?php echo form_open("adminlogin/login_action","id='login_form' class='login-form' role='form'");?>
		<h3 class="form-title"><?php echo lang('admin_administrator');?></h3>
		<div class="margin-bottom-20" id="login_msg"></div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9"><?php echo lang("site.email");?></label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input type="email" name="email" class="form-control placeholder-no-fix" value="<?php echo $email;?>"  autocomplete="off" placeholder="이메일" required autofocus>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo lang("site.pw");?></label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input type="password" class="form-control placeholder-no-fix"  name="pw"  autocomplete="off" placeholder="패스워드" value="<?php echo ($config->IS_DEMO) ? "12345" : "";?>" required/>
			</div>
		</div>
		<div class="form-actions">
			
			<div class="checkbox" style="padding-left:20px;">
				<label>
					<input type="checkbox" id="save" name="save" <?php if($this->input->cookie("wb_admin_save")=="on"){echo "checked='checked'";}?>> 이메일 기억하기 
				</label>
			</div>

			<?php if($config->PAY=="1"){?>
				<button type="submit" class="btn blue pull-right">
					<?php echo lang("menu.login");?> <i class="m-icon-swapright m-icon-white"></i>
				</button>
			<?php } else { ?>
				<button type="button" class="btn default disabled pull-right">
				요금미납
				</button>
			<?php }  ?>

		</div>
		<!--div class="login-options">
			<h4>Or login with</h4>
			<ul class="social-icons">
				<li>
					<a class="facebook" data-original-title="facebook" href="#">
					</a>
				</li>
				<li>
					<a class="twitter" data-original-title="Twitter" href="#">
					</a>
				</li>
				<li>
					<a class="googleplus" data-original-title="Goole Plus" href="#">
					</a>
				</li>
				<li>
					<a class="linkedin" data-original-title="Linkedin" href="#">
					</a>
				</li>
			</ul>
		</div-->
		<?php if($config->ADMIN_JOIN){ ?>
		<div class="forget-password">
			<h4>비밀번호를 분실하셨습니까 ?</h4>
			<p>
				 비밀번호를 초기화 하시려면 <a href="javascript:;" id="forget-password">
				이곳을 </a>
				클릭하여 주시기 바랍니다.
			</p>
		</div>
		<div class="create-account">
			<p>
				 직원계정을 신청 하시겠습니까?&nbsp; <a href="javascript:;" id="register-btn">
				 계정신청 </a>
			</p>
		</div>
		<?php }?>
	<?php echo form_close();?>
	<!-- END LOGIN FORM -->
	<!-- BEGIN FORGOT PASSWORD FORM -->
	<?php echo form_open("adminlogin/forget_action","id='forget_form' class='forget-form' role='form'");?>
	<div id="forget_msg"></div>
		<h3>패스워드 찾기</h3>
		<p>
			 패스워드를 초기화하여 입력한 메일계정으로 발송 해드립니다.
		</p>
		<div class="form-group">
			<div class="input-icon">
				<i class="fa fa-envelope"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="직원 이메일" name="email" maxlength="50" required/>
			</div>
		</div>
		<div class="form-actions">
			<button type="button" id="back-btn" class="btn">
			<i class="m-icon-swapleft"></i> <?php echo lang("site.back");?> </button>
			<button type="submit" class="btn blue pull-right">
			발송 <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
	<?php echo form_close();?>
	<!-- END FORGOT PASSWORD FORM -->
	<!-- BEGIN REGISTRATION FORM -->
	<?php echo form_open("adminlogin/simple_add_action","id='register_form' class='register-form' role='form'");?>
		<div id="register_msg"></div>
		<h3>계정 신청</h3>
		<p>
			 계정 정보를 입력하여 주시기 바랍니다.
		</p>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9"><?php echo lang("site.email");?></label>
			<div class="input-icon">
				<i class="fa fa-envelope"></i>
				<input class="form-control placeholder-no-fix" type="text" placeholder="직원 이메일" name="email" maxlength="50" required/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo lang("site.pw");?></label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="<?php echo lang("site.pw");?>" name="password" maxlength="20" required/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo lang("site.repw");?></label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="패스워드 재입력" name="rpassword" maxlength="20"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo lang("site.name");?></label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" placeholder="직원명" name="name" maxlength="10" required/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo lang("site.mobile");?></label>
			<div class="input-icon">
				<i class="fa fa-phone"></i>
				<input class="form-control placeholder-no-fix" type="text" placeholder="<?php echo lang("site.mobile");?>" name="phone" maxlength="15" required/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo lang("site.tel");?></label>
			<div class="input-icon">
				<i class="fa fa-phone"></i>
				<input class="form-control placeholder-no-fix" type="text" placeholder="<?php echo lang("site.tel");?>" name="tel" maxlength="15"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">카카오톡아이디</label>
			<div class="input-icon">
				<i class="fa fa-font"></i>
				<input class="form-control placeholder-no-fix" type="text" placeholder="카카오톡아이디" name="kakao" maxlength="50"/>
			</div>
		</div>
		<div class="form-group">
			<label>
			<input type="checkbox" name="accept" id="accept"/> 개인정보 수집 및 이용에 동의합니다.
			</label>
			<div id="register_accept_error">
			</div>
		</div>
		<div class="form-actions">
			<button id="register-back-btn" type="button" class="btn">
			<i class="m-icon-swapleft"></i> <?php echo lang("site.back");?> </button>
			<button type="submit" class="btn blue pull-right">
			계정 신청 <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
	<?php echo form_close();?>
	<!-- END REGISTRATION FORM -->
</div>
<!-- END LOGIN -->

<div class="copyright">
	<samp>IP: <?php echo $this->input->ip_address()?></samp><br/>
	<?php if($config->DUNGZI=="1"){?>2014 &copy; powered by <a href="http://www.dungzi.com/" target="_blank">dungzi.com</a><?php }?>
</div>