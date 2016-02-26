<?php
$email = ($this->input->cookie("wb_user_save")=="on") ? $this->input->cookie("wb_user_id") : "";
?>
<script>
$(document).ready(function() {

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	$("#signin_form").ajaxForm({
		beforeSubmit:function(){
			$("#signin_form").validate({
				rules: {
					siEmail: {
						required: true,
						email: true
					},
					siPw: {
						required: true,
						minlength: 5
					}
				},
				messages: {
					siEmail: {
						required: "<?php echo lang("form.required");?>",
						email: "<?php echo lang("form.emailerror");?>"
					},
					siPw: {
						required: "<?php echo lang("form.required");?>",
						minlength: "<?php echo lang("form.5");?>"
					}
				}
			});
			if (!$("#signin_form").valid()) return false;
		},
		success:function(data){

			if(data=="0"){
				msg($("#msg"), "danger" ,"비밀번호가 틀립니다. 다른 비밀번호를 입력하시거나 비밀번호 찾기를 해주세요.");
				return false;
			} else if(data=="2"){
				msg($("#msg"), "danger" ,"관리자에 의해 로그인이 거부되었습니다.");
				return false;
			} else if(data=="3"){
				msg($("#msg"), "danger" ,"하나의 스마트폰 기기에서만 접속이 가능합니다.");
				return false;
			} else if(data=="4"){
				msg($("#msg"), "danger" ,"로그인 기간이 만료 되었습니다.");
				return false;
			} else if(data =="9" ){
				msg($("#msg"), "danger" ,"해당 이메일로 가입된 회원이 없습니다.회원가입을 해주세요.");
				return false;
			} else{
				location.href=data;
			}
		}
	});
});
</script>
<div class="page-content" id="main-stack">
    <div class="page-content" id="main-stack" data-scroll="0">
      <div class="w-nav navbar">
		<a href="/mobile/home" class="w-inline-block navbar-button left">
			<div class="navbar-button-icon icon smaller ion-ios-home-outline"></div>
		</a>
		<a href="#" class="w-inline-block navbar-button right" onclick="onBackKeyDown();">
			<div class="navbar-button-icon icon ion-ios-close-empty"></div>
		</a>
	  </div>
      <div class="body padding">
        <div class="logo-login" style="text-align:center;">
            <?php if($config->logo==""){echo "<img src='/assets/common/img/dungzi.png'>";} else {?><img src="/uploads/logo/<?php echo $config->logo;?>" alt="<?php echo $config->name;?>"/><?php }?></a>
        </div>
        <div class="">
            <div id="msg"></div>
            <div class="w-form">
				<?php echo form_open("mobile/signin_action","id='signin_form' role='form'");?>
				<input type="hidden" name="back_url" value="<?php echo($backurl);?>"/>
				<div>
					<input type="email" class="form-control input-lg" name="siEmail" placeholder="<?php echo lang("site.email");?>" value="<?php echo $email;?>"/>
					<div class="separator-fields"></div>
				</div>
				<div>					
					<input type="password" class="form-control input-lg" name="siPw" placeholder="<?php echo lang("site.pw");?>"/>
				</div>

				<?php $checked = ($this->input->cookie("wb_user_save")=="on") ? "checked" : "";?>
				<div class="w-clearfix radios-container pull-left">
					<div class="w-clearfix checkbox-field pull-left">
						<div class="checkbox-handle <?php echo $checked;?>"></div>
						<input class="w-checkbox-input checkbox-input" id="checkbox" type="checkbox" name="save" data-name="Checkbox" <?php echo $checked;?>/>
						<label class="w-form-label checkbox-label <?php echo $checked;?>" for="checkbox"></label>
					</div>
					<div style="width:150px;">&nbsp;&nbsp;이메일 저장</div>	
				</div>

				<div class="separator-button-input"></div>
				<div class="separator-button-input"></div>
				<a class="btn btn-info btn-lg btn-block" href="/mobile/signup"><strong>회 원 가 입</strong></a>
				<button class="btn btn-primary btn-lg btn-block" type="submit"><strong>로 그 인</strong></button>
				<div class="separator-button"></div>
				<?php echo form_close();?>
          </div>
        </div>
      </div>
    </div>
    <div class="page-content loading-mask" id="new-stack">
		<div class="loading-icon">
			<div class="navbar-button-icon icon ion-load-d"></div>
		</div>
    </div>
    <div class="shadow-layer"></div>
</div>