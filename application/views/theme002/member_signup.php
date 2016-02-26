<script>
$(document).ready(function() {

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	$("#general_signup_form").validate({ 
		rules: {
			suEmail: {  
				required: true,  
				email: true,
				remote : {
					type : "POST",
					async: false,
					url  : "/member/check_email"
				}
			},
			suPhone: {  
				required: true,  
				minlength: 9
			},
			suName: {  
				required: true,  
				minlength: 2
			},
			suPw: {  
				required: true,  
				minlength: 5
			},
			suRepw: {  
				required: true,  
				minlength: 5,
				equalTo: "#general_signup_form input[name='suPw']"
			}
		},  
		messages: {  
			suEmail: {  
				required: "<?php echo lang("form.required");?>",  
				email: "<?php echo lang("form.emailerror");?>",  
				remote: "<?php echo lang("form.imimember");?>"
			},
			suPhone: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.9");?>"
			},
			suName: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.2");?>"	
			},
			suPw: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.5");?>"
			},
			suRepw: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.5");?>",
				equalTo: "<?php echo lang("form.repwerror");?>"
			}
		} 
	});

	$("#biz_signup_form").validate({ 
		rules: {
			suEmail: {  
				required: true,  
				email: true,
				remote : {
					type : "POST",
					async: false,
					url  : "/member/check_email"
				}
			},
			suPw: {  
				required: true,  
				minlength: 5
			},
			suRepw: {  
				required: true,  
				minlength: 5,
				equalTo: "#biz_signup_form input[name='suPw']"
			},
			biz_name: {  
				required: true,  
				minlength: 2
			},
			biz_ceo : {  
				required: true,  
				minlength: 2
			},
			biz_auth: {
				required: true
			},
			biz_num: {
				required: true
			},			
			re_num: {
		        required: function(element) {
		            return $("select[name='biz_auth'] option:selected").val() != "0";
		        }
			}, 
			address: {
				required: true
			},
			address_detail:{
				required: true	
			},
			suName: {  
				required: true,  
				minlength: 2
			},
			suPhone: {  
				required: true,  
				minlength: 9
			},
			tel :{  
				required: true,  
				minlength: 8
			}
		},  
		messages: {  
			suEmail: {  
				required: "<?php echo lang("form.required");?>",  
				email: "<?php echo lang("form.emailerror");?>",  
				remote: "<?php echo lang("form.imimember");?>"
			},
			suPw: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.5");?>"
			},
			suRepw: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.5");?>",
				equalTo: "<?php echo lang("form.repwerror");?>"
			},
			biz_name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.2");?>"	
			},
			biz_ceo : {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.2");?>"	
			},			
			biz_auth: {  
				required: "<?php echo lang("form.required");?>"
			},
			biz_num: {
				required:  "<?php echo lang("form.required");?>"
			}, 					
			re_num: {
				required:  "<?php echo lang("form.required");?>"
			},
			address: {
				required:  "<?php echo lang("form.required");?>"
			},
			address_detail: {
				required:  "<?php echo lang("form.required");?>"
			},							
			suName: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.2");?>"	
			},
			suPhone: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.9");?>"
			},
			tel: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.8");?>"	
			},		
		} 
	});

	$("#general_signup_form").ajaxForm({
		beforeSubmit:function(){
			var sms_confirm = "<?php echo $config->MEMBER_PHONE_CONFIRM;?>";
			if(sms_confirm){
				if($("#general_sms_confirm").val()==""){
					msg($("#msg"), "danger" ,"SMS인증을 해주시기 바랍니다.");
					$("#general_sms_confirm_num").focus();
					return false;
				}			
			}

			if(!$("#general_agree").prop("checked")){
				alert("약관에 동의하지 않았습니다.");
				$("#general_agree").focus();
				return false;
			}
		},
		success:function(data){
			if(data=="1"){
				var redirect = "<?php echo $config->SIGNUP_REDIRECT?>";
				if(redirect){
					location.href = redirect;
				}
				else{
					location.href = "/member/enquire";
				}
			}	
			else if(data=="2"){
				form_reset();
				$('body, html').animate({scrollTop:0}, 500);
				msg($("#msg"), "success" ,"가입요청이 완료 되었습니다. 인증완료 후 로그인이 가능합니다.");
			}
			else if(data=="3"){
				$('body, html').animate({scrollTop:0}, 500);
				msg($("#msg"), "danger" ,"SMS인증번호가 잘못 되었거나 유효하지 않습니다.");
			}
			else {
				msg($("#msg"), "danger" ,"회원가입에 실패하였습니다. 전화주세요.");
			}
		}
	});

	$("#biz_signup_form").ajaxForm({
		beforeSubmit:function(){
			var sms_confirm = "<?php echo $config->MEMBER_PHONE_CONFIRM;?>";
			if(sms_confirm){
				if($("#biz_sms_confirm").val()==""){
					msg($("#msg"), "danger" ,"SMS인증을 해주시기 바랍니다.");
					$("#biz_sms_confirm_num").focus();
					return false;
				}
			}
			if(!$("#biz_agree").prop("checked")){
				alert("약관에 동의하지 않았습니다.");
				$("#biz_agree").focus();
				return false;	
			}
		},
		success:function(data){
			if(data=="1"){
				var redirect = "<?php echo $config->SIGNUP_REDIRECT?>";
				if(redirect){
					location.href = redirect;
				}
				else{
					location.href = "/member/product_add";
				}
			}	
			else if(data=="2"){
				form_reset();
				$('body, html').animate({scrollTop:0}, 500);
				msg($("#msg"), "success" ,"가입요청이 완료 되었습니다. 인증완료 후 로그인이 가능합니다.");
			}
			else if(data=="3"){
				$('body, html').animate({scrollTop:0}, 500);
				msg($("#msg"), "danger" ,"SMS인증번호가 잘못 되었거나 유효하지 않습니다.");
			}
			else {
				msg($("#msg"), "danger" ,"회원가입에 실패하였습니다. 전화주세요.");
			}
		}
	});

	$("select[name='biz_auth']").change(function(){
		if( $(this).val() == "0" || $(this).val() == "") $("#renum_zone").hide();
		else $("#renum_zone").show();
	});
});

function form_reset(){
	$("#general_signup_form")[0].reset();
	$("#general_signup_form").validate().resetForm();

	$("#biz_signup_form")[0].reset();
	$("#biz_signup_form").validate().resetForm();
}

var timer = null;
function sms_confirm_send(obj,form){

	var phone = form.find("input[name='suPhone']").val();

	if(phone==""){
		msg($("#msg"), "danger" ,"휴대전화를 입력해주세요");
		form.find("input[name='suPhone']").focus();
		return false;
	}

	$.getJSON("/member/sms_confirm_send/"+encodeURIComponent(phone)+"/"+Math.round(new Date().getTime()),function(data){

		$(obj).removeClass("btn-default");
		$(obj).addClass("btn-info");
		$(obj).attr("onclick","sms_confirm_check(this,'"+phone+"',$('#"+form.attr("id")+"'))");
		$(obj).html('<i class="glyphicon glyphicon-envelope"></i> 입력하기');

		if(data["result"]=="send"){
			var minute = 3;
			var second = 0;

		}
		else if(data["result"]=="ing"){
			var minute = data["minute"];
			var second = data["second"];
		}

		$(obj).next().find(".minute").text("0"+minute);

		if(second >= 10) $(obj).next().find(".second").text(second);
		else $(obj).next().find(".second").text("0"+second);

		timer = setInterval(function () {
			if(second == 0){
				minute = minute - 1;
				if(minute < 0){
					$(obj).removeClass("btn-info");
					$(obj).addClass("btn-default");
					$(obj).attr("onclick","sms_confirm_send(this,$('#general_signup_form'))");
					$(obj).html('<i class="glyphicon glyphicon-envelope"></i> 인증요청');
					clearInterval(timer);
				}
				else{
					$(obj).next().find(".minute").text("0"+minute);
					second = 60;
					$(obj).next().find(".second").text(second - 1);
				}			
			}
			else{
				if(second >= 10) $(obj).next().find(".second").text(second - 1);
				else $(obj).next().find(".second").text("0"+(second - 1));	
			}
			second = second - 1;
		}, 1000);
	});
}

function sms_confirm_check(obj,phone,form){
	var confirm_num = $(obj).prev().prev().val();
	$.getJSON("/member/sms_confirm_check/"+encodeURIComponent(phone)+"/"+confirm_num+"/"+Math.round(new Date().getTime()),function(data){
		if(data=="success"){
			$(obj).removeClass("btn-info");
			$(obj).addClass("btn-success");
			$(obj).attr("onclick","");
			$(obj).html('<i class="glyphicon glyphicon-envelope"></i> 인증완료');
			$("#msg").html("");
			clearInterval(timer);
			$(obj).prev().val(data);
			form.find("input[name='suPhone']").val(phone);
			form.find("input[name='suPhone']").attr("readonly",true);
			//form.find("input[name='sms_confirm_num']").attr("readonly",true);
		}
		else{
			msg($("#msg"), "danger" ,"인증번호가 잘못 되었습니다.");
		}
	});
}

</script>
	<div class="main">
		<div class="_container">

		<ul class="breadcrumb">
            <li><a href="/"><?php echo lang("menu.home");?></a></li>
            <li><a href="#"><?php echo lang("menu.mypage");?></a></li>
            <li class="active"><?php echo lang("menu.signup");?></li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-3">
            <ul class="list-group margin-bottom-25 sidebar-menu">
			  <?php if($this->session->userdata("id")==""){?>
              <li class="list-group-item clearfix"><a href="/member/signin"><i class="fa fa-angle-right"></i> <?php echo lang("menu.login");?></a></li>
              <li class="list-group-item clearfix active"><a href="/member/signup"><i class="fa fa-angle-right"></i> <?php echo lang("menu.signup");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/search"><i class="fa fa-angle-right"></i> <?php echo lang("menu.lostpw");?></a></li>
			  <?php }?>
			  <?php if($this->session->userdata("id")){?>
              <li class="list-group-item clearfix"><a href="/member/profile"><i class="fa fa-angle-right"></i> <?php echo lang("menu.modifyprofile");?></a></li>
			  <?php }?>
              <li class="list-group-item clearfix"><a href="/member/history"><i class="fa fa-angle-right"></i> <?php echo lang("site.seen");?></a></li>              
			  <li class="list-group-item clearfix"><a href="/member/hope"><i class="fa fa-angle-right"></i> <?php echo lang("site.saved");?></a></li>
            </ul>
          </div>
          <!-- END SIDEBAR -->

          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-9">
            <div class="content-form-page">
              <div class="row">
                <div class="col-md-7 col-sm-7">

					<div role="tabpanel">
					  <!-- Nav tabs -->
					  <?php if($config->MEMBER_TYPE=="both") {?>
					  <ul class="nav nav-tabs" role="tablist">
						<li role="presentation" <?php if($type!="biz") {?>class="active"<?php }?>><a onclick="form_reset();" href="#general_form" aria-controls="general_form" role="tab" data-toggle="tab">개인 회원</a></li>
						<li role="presentation" <?php if($type=="biz") {?>class="active"<?php }?>>
							<a onclick="form_reset();" href="#biz_form" aria-controls="biz_form" role="tab" data-toggle="tab">
								<?php if($config->INSTALLATION_FLAG){?>
								사업자 / 공인중개회원
								<?php } else {?>
								공인중개사 회원
								<?php } ?>
							</a>
						</li>
					  </ul>
					  <?php } else { ?>
						<h1><?php echo lang("menu.signup");?></h1>
					  <?php } ?>
					  <!-- Tab panes -->
					  <div class="tab-content" style="background-color:#FFF;">
						<div class="margin-top-20" id="msg"></div>
						<div role="tabpanel" class="tab-pane <?php if($type!="biz") {?>active in<?php }?> fade" id="general_form">
						<?php echo form_open("member/signup_action","id='general_signup_form' class='form-horizontal form-without-legend' role='form'");?>
						<input type="hidden" name="type" value="general"/>
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label"><?php echo lang("site.email");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="suEmail" autocomplete="off" placeholder="<?php echo lang("site.email");?>">
						  </div>
						</div>
						<div class="form-group">
						  <label for="password" class="col-lg-4 control-label"><?php echo lang("site.pw");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="password"  class="form-control" name="suPw" autocomplete="off" placeholder="<?php echo lang("site.pw");?>">
						  </div>
						</div>
						<div class="form-group">
						  <label for="password" class="col-lg-4 control-label"><?php echo lang("site.repw");?><span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="password"  class="form-control" name="suRepw" autocomplete="off" placeholder="<?php echo lang("site.repw");?>">
						  </div>
						</div>
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label"><?php echo lang("site.name");?>  <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="suName" autocomplete="off" placeholder="<?php echo lang("site.name");?>" <?php if($real_name) echo "readonly";?> value="<?php echo $real_name;?>">
						  </div>
						</div>
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label"><?php echo lang("site.tel");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="suPhone" autocomplete="off" placeholder="<?php echo lang("site.tel");?>">
						  </div>
						</div>
						<?php if($config->MEMBER_PHONE_CONFIRM){?>
						<div class="form-group form-inline">
						  <label for="email" class="col-lg-4 control-label">SMS인증 <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" id="general_sms_confirm_num" name="sms_confirm_num" autocomplete="off" placeholder="인증번호" style="width:40%"/>
							<input type="hidden" id="general_sms_confirm" name="sms_confirm" value=""/>
							<button type="button" class="btn btn-default" onclick="sms_confirm_send(this,$('#general_signup_form'));"><i class="glyphicon glyphicon-envelope"></i> 인증요청</button>
							<span class="help-inline">(<span class="minute">00</span>:<span class="second">00</span>)</span>
						  </div>
						</div>
						<?php }?>
						<div class="form-group">
						  <label class="col-lg-4 control-label"></label>						  
						  <div class="col-lg-8">
							<input type="checkbox" id="general_agree"/>
							<a href="/home/rule" target="_blank"><?php echo lang("menu.uselaw");?></a>,
							<a href="/home/privacy" target="_blank"><?php echo lang("menu.infolaw");?></a>,
							<a href="/home/location" target="_blank"><?php echo lang("menu.positionlaw");?></a>
						  </div>
						</div>
						<div class="row">
						  <div class="col-lg-8 col-md-offset-4 margin-bottom-10 text-right">
							<button type="submit" class="btn btn-primary">개인 회원가입</button>
						  </div>
						</div>
						<?php echo form_close();?>	
						</div>

						<div role="tabpanel" class="tab-pane <?php if($type=="biz") {?>active in<?php }?> fade" id="biz_form">
						  <?php echo form_open("member/signup_action","id='biz_signup_form' class='form-horizontal form-without-legend ' role='form'");?>
						  <input type="hidden" name="type" value="biz">
							<div class="form-group">
							  <label for="suEmail" class="col-lg-4 control-label"><?php echo lang("site.email");?> <span class="require">*</span></label>
							  <div class="col-lg-8">
								<input type="text" class="form-control" name="suEmail" autocomplete="off" placeholder="<?php echo lang("site.email");?>">
							  </div>
							</div>
							<div class="form-group">
							  <label for="suPw" class="col-lg-4 control-label"><?php echo lang("site.pw");?> <span class="require">*</span></label>
							  <div class="col-lg-8">
								<input type="password"  class="form-control" name="suPw" autocomplete="off" placeholder="<?php echo lang("site.pw");?>">
							  </div>
							</div>
							<div class="form-group">
							  <label for="suRepw" class="col-lg-4 control-label"><?php echo lang("site.repw");?><span class="require">*</span></label>
							  <div class="col-lg-8">
								<input type="password"  class="form-control" name="suRepw" autocomplete="off" placeholder="<?php echo lang("site.repw");?>">
							  </div>
							</div>
							<div class="form-group">
							  <label for="suName" class="col-lg-4 control-label"><?php echo lang("site.name");?>  <span class="require">*</span></label>
							  <div class="col-lg-8">
								<input type="text" class="form-control" name="suName" autocomplete="off" placeholder="<?php echo lang("site.name");?>" <?php if($real_name) echo "readonly";?> value="<?php echo $real_name;?>">
							  </div>
							</div>
							<div class="form-group">
							  <label for="suPhone" class="col-lg-4 control-label"><?php echo lang("site.mobile");?> <span class="require">*</span></label>
							  <div class="col-lg-8">
								<input type="text" class="form-control" name="suPhone" autocomplete="off" placeholder="<?php echo lang("site.mobile");?>">
							  </div>
							</div>
							<?php if($config->MEMBER_PHONE_CONFIRM){?>
							<div class="form-group form-inline">
							  <label for="email" class="col-lg-4 control-label">SMS인증 <span class="require">*</span></label>
							  <div class="col-lg-8">
								<input type="text" class="form-control" id="biz_sms_confirm_num" name="sms_confirm_num" autocomplete="off" placeholder="인증번호" style="width:40%"/>
								<input type="hidden" id="biz_sms_confirm" name="sms_confirm" value=""/>
								<button type="button" class="btn btn-default" onclick="sms_confirm_send(this,$('#biz_signup_form'));"><i class="glyphicon glyphicon-envelope"></i> 인증요청</button>
								<span class="help-inline">(<span class="minute">00</span>:<span class="second">00</span>)</span>
							  </div>
							</div>
							<?php }?>
							<div class="form-group">
							  <label for="tel" class="col-lg-4 control-label"><?php echo lang("site.tel");?> <span class="require">*</span></label>
							  <div class="col-lg-8">
								<input type="text" class="form-control" name="tel" autocomplete="off" placeholder="대표전화">
							  </div>
							</div>
							<div class="form-group">
							  <label for="kakao" class="col-lg-4 control-label">카카오톡아이디</label>
							  <div class="col-lg-8">
								<input type="text" class="form-control" name="kakao" autocomplete="off" placeholder="카카오톡아이디">
							  </div>
							</div>

							<h4 class="page-header">사업자 및 공인중개사 정보</h4>
							<div class="form-group">
							  <label for="biz_name" class="col-lg-4 control-label">
							  	<?php if($config->INSTALLATION_FLAG){?>
								사업자명
								<?php } else {?>
								중개사무소명
								<?php } ?>
							  	<span class="require">*</span>
							  </label>
							  <div class="col-lg-8">
								<input type="text" class="form-control" name="biz_name" autocomplete="off" placeholder="사업자명">
								<div class="help-block">* 중개업자일 경우에는 개설등록증에 기재된 명칭.</div>
							  </div>
							</div>
							<div class="form-group">
							  <label class="col-lg-4 control-label"><?php echo lang("site.ceo");?> <span class="require">*</span></label>
								<div class="col-lg-8">
									<input type="text" name="biz_ceo" class="form-control" placeholder="<?php echo lang("site.ceo");?>"/>
								</div>
							</div>							
							<div class="form-group">
							  <label for="biz_num" class="col-lg-4 control-label"><?php echo lang("site.biznum");?> <span class="require">*</span></label>
							  <div class="col-lg-8">
								<input type="text" class="form-control" name="biz_num" autocomplete="off" placeholder="<?php echo lang("site.biznum");?>">
							  </div>
							</div>
							<div class="form-group">
							  <label for="biz_auth" class="col-lg-4 control-label">권한/자격</label>
							  <div class="col-lg-8">
								<select name="biz_auth" class="form-control" autocomplete="off">
									<option value="">권한/자격</option>
									<?php if($config->INSTALLATION_FLAG){?>
									<option value="0">일반사업자(중개업자가 아님)</option>
									<?php } ?>
									<option value="1">대표공인중개사</option>
									<option value="2">소속공인중개사</option>
									<option value="3">중개보조원</option>
								</select>
							  </div>
							</div>							
							<div id="renum_zone" class="form-group" style="display:none;">
							  <label for="re_num" class="col-lg-4 control-label"><?php echo lang("site.renum");?> <span class="require">*</span></label>
							  <div class="col-lg-8">
								<input type="text" class="form-control" name="re_num" autocomplete="off" placeholder="<?php echo lang("site.renum");?>">
							  </div>
							</div>
							<div class="form-group">
							  <label class="col-lg-4 control-label">사업자주소 <span class="require"> * </span></label>
								<div class="col-lg-8">
									<button type="button" class="btn btn-default btn-sm" onclick="get_postcode('biz_signup_form')">주소검색</button>
								</div>
							</div>							
							<div class="form-group">
							  <label class="col-lg-4 control-label"></label>
								<div class="col-lg-8">
								<input type="text" class="form-control" id="address" name="address" autocomplete="off" placeholder="사업자주소" readonly>
								</div>
							</div>
							<div class="form-group">
							  <label class="col-lg-4 control-label"></label>
								<div class="col-lg-8">
									<input type="text" name="address_detail" class="form-control" placeholder="상세주소"/>
								</div>
							</div>
							<div class="form-group">
							  <label class="col-lg-4 control-label"></label>						  
							  <div class="col-lg-8">
								<input type="checkbox" id="biz_agree"/>
								<a href="/home/rule" target="_blank"><?php echo lang("menu.uselaw");?></a>,
								<a href="/home/privacy" target="_blank"><?php echo lang("menu.infolaw");?></a>,
								<a href="/home/location" target="_blank"><?php echo lang("menu.admin");?></a>
							  </div>
							</div>
							<div class="row">
							  <div class="col-lg-8 col-md-offset-4 margin-bottom-10 text-right">
								<button type="submit" class="btn btn-primary">
							  	<?php if($config->INSTALLATION_FLAG){?>
								사업자 / 공인중개 회원가입
								<?php } else {?>
								공인중개 회원가입
								<?php } ?>									
								</button>
							  </div>
							</div>
						  <?php echo form_close();?>	
						</div>

					  </div>
					</div>

                </div>
                <div class="col-md-4 col-sm-4 pull-right">
                  <div class="form-info">
                    <?php if($config->MEMBER_TYPE!="biz") {?>
						<h2><i class="fa fa-arrow-circle-right"></i> <em>개인회원</em> 가입안내</h2>
						<?php if($config->USER_PRODUCT){?>
							<p><span class="label label-danger">직거래</span>만 등록가능합니다.</p>
						<?php } else {?>
							<p>회원님들의 개인정보는 외부로 누출하거나 공개하지 않습니다.</p>
						<?php }?>
					<?php }?>
					<?php if($config->INSTALLATION_FLAG){?>
						<h2><i class="fa fa-arrow-circle-right"></i> <em>일반사업자</em> 가입안내</h2>
						<p><span class="label label-danger">분양</span>을 하는 부동산 만 등록가능하며 중개대상물을 등록할 수 없습니다.</p>
					<?php } ?>
					<?php if($config->MEMBER_TYPE!="general") {?>
						<?php if($config->GONGSIL_FLAG){?>
							<h2><i class="fa fa-arrow-circle-right"></i> 가입안내</h2>
							<ol>
								<li>공인중개사 및 중개보조원 만 가입가능합니다.</li>
								<li>부동산 거래 중개망입니다.</li>
								<li>매물 조회는 유료 서비스입니다.</li>
								<li>중개업개설등록이 되어 있지 않으면 가입이 불가능합니다.(컨설팅업체 등)</li>
							</ol>
						<?php } else {?>
							<h2><i class="fa fa-arrow-circle-right"></i> <em>공인중개사</em> 가입안내</h2>
							<p><span class="label label-danger">중개대상물</span>을 등록할 수 있으며 <b>공인중개사법</b>에 의하여 표시의무를 준수하여야 하며 <u>중개사업자 명칭</u>(등록증 상의 명칭), <u>전화번호</u>(KLIS에 신고된 전화번호), <u>소재지</u>, <u>대표자성명</u>(등록증에 기재되어 있는)을 정확하게 입력해 주세요.</p>
						<?php } ?>
					<?php } ?>
                    <a href="/member/signin" class="btn btn-default"><?php echo lang("menu.login");?></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->

	</div>
</div>

<!-- DAUM POST LAYER -->
<div id="layer" style="display:none;border:5px solid;position:fixed;width:420px;height:460px;left:50%;margin-left:-235px;top:50%;margin-top:-235px;overflow:hidden;-webkit-overflow-scrolling:touch;z-index:10">
<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="<?php echo lang("site.close");?>">
</div>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    var element_layer = document.getElementById('layer');

    function closeDaumPostcode() {
        element_layer.style.display = 'none';
    }

    function get_postcode(form) {
        new daum.Postcode({
            oncomplete: function(data) {
                var fullAddr = data.address;
                var extraAddr = '';

                if(data.addressType === 'R'){
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }
                $("#"+form).find('#address').val(fullAddr);
				$("#"+form).find('#address_detail').focus();

                element_layer.style.display = 'none';
            },
            width : '100%',
            height : '100%'
        }).embed(element_layer);

        element_layer.style.display = 'block';
    }
</script>