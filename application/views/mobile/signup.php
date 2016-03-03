<script src="/assets/plugin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
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
				location.href = "/mobile/home";
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
				location.href = "/mobile/home";
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
<div class="page-content" id="main-stack">
	<div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
		<div class="w-container">
			<div class="wrapper-mask" data-ix="menu-mask"></div>
				<div class="navbar-title">회원가입</div>
				<a href="/mobile/home" class="w-inline-block navbar-button left">
					<div class="navbar-button-icon icon smaller ion-ios-home-outline"></div>
				</a>
				<a href="#" class="w-inline-block navbar-button right" onclick="onBackKeyDown();">
					<div class="navbar-button-icon icon ion-ios-close-empty"></div>
				</a>
				</div>
			</div>

			<div class="body padding">

				<!-- BEGIN CONTENT -->
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
				  <?php }?>

				  <!-- Tab panes -->
				  <div class="tab-content" style="background-color:#FFF;">
					<div id="msg" style="margin-top:10px;"></div>
					<div role="tabpanel" class="tab-pane <?php if($type!="biz") {?>active in<?php }?> fade" id="general_form">
					<?php echo form_open("/member/signup_action","id='general_signup_form' class='form-horizontal form-without-legend' role='form'");?>
					<input type="hidden" name="type" value="general"/>
					<div class="form-group">
					  <label><?php echo lang("site.email");?> <span class="require">*</span></label>
					  <input type="text" class="form-control" name="suEmail" autocomplete="off" placeholder="<?php echo lang("site.email");?>"/>
					</div>
					<div class="form-group">
					  <label><?php echo lang("site.pw");?> <span class="require">*</span></label>
						<input type="password"  class="form-control" name="suPw" autocomplete="off" placeholder="<?php echo lang("site.pw");?>"/>
					</div>
					<div class="form-group">
					  <label><?php echo lang("site.repw");?> <span class="require">*</span></label>
					  <input type="password"  class="form-control" name="suRepw" autocomplete="off" placeholder="<?php echo lang("site.repw");?>"/>
					</div>
					<div class="form-group">
					  <label><?php echo lang("site.name");?> <span class="require">*</span></label>
					  <input type="text" class="form-control" name="suName" autocomplete="off" placeholder="<?php echo lang("site.name");?>" <?php if($real_name) echo "readonly";?> value="<?php echo $real_name;?>"/>
					</div>
					<div class="form-group">
					  <label><?php echo lang("site.tel");?> <span class="require">*</span></label>
					  <input type="text" class="form-control" name="suPhone" autocomplete="off" placeholder="<?php echo lang("site.tel");?>"/>
					</div>
					<?php if($config->MEMBER_PHONE_CONFIRM){?>
					<div class="form-group form-inline">
					  <label>SMS인증 <span class="require">*</span></label>
						<input type="text" class="form-control" id="general_sms_confirm_num" name="sms_confirm_num" autocomplete="off" placeholder="인증번호" style="width:40%"/>
						<input type="hidden" id="general_sms_confirm" name="sms_confirm" value=""/>
						<button type="button" class="btn btn-default" onclick="sms_confirm_send(this,$('#general_signup_form'));"><i class="glyphicon glyphicon-envelope"></i> 인증요청</button>
						<span class="help-inline">(<span class="minute">00</span>:<span class="second">00</span>)</span>
					</div>
					<?php }?>
					<div class="form-group">
						<div class="w-clearfix radios-container">
						  <a href="/mobile/rule"><?php echo lang("menu.uselaw");?></a>,
						  <a href="/mobile/privacy"><?php echo lang("menu.infolaw");?></a>,
						  <a href="/mobile/location"><?php echo lang("menu.positionlaw");?></a>
							<div class="w-checkbox w-clearfix checkbox-field">
							  <input type="checkbox" id="general_agree" name="general_agree" data-name="Checkbox"/> 동의합니다
							</div>
						</div>
					</div>
					<div class="text-right">
					  <button type="submit" class="btn btn-primary btn-lg btn-block btn-join"><?php echo lang("menu.signup");?></button>
					</div>
					<?php echo form_close();?>	
					</div>

					<div role="tabpanel" class="tab-pane <?php if($type=="biz") {?>active in<?php }?> fade" id="biz_form">
					  <?php echo form_open("/member/signup_action","id='biz_signup_form' class='form-horizontal form-without-legend ' role='form'");?>
					  <input type="hidden" name="type" value="biz"/>
						<div class="form-group">
						  <label><?php echo lang("site.email");?> <span class="require">*</span></label>
						  <input type="text" class="form-control" name="suEmail" autocomplete="off" placeholder="<?php echo lang("site.email");?>"/>
						</div>
						<div class="form-group">
						  <label><?php echo lang("site.pw");?> <span class="require">*</span></label>
						  <input type="password"  class="form-control" name="suPw" autocomplete="off" placeholder="<?php echo lang("site.pw");?>"/>
						</div>
						<div class="form-group">
						  <label><?php echo lang("site.repw");?><span class="require">*</span></label>
						  <input type="password"  class="form-control" name="suRepw" autocomplete="off" placeholder="<?php echo lang("site.repw");?>"/>
						</div>
						<div class="form-group">
						  <label><?php echo lang("site.name");?> <span class="require">*</span></label>
						  <input type="text" class="form-control" name="suName" autocomplete="off" placeholder="<?php echo lang("site.name");?>" <?php if($real_name) echo "readonly";?> value="<?php echo $real_name;?>"/>
						</div>
						<div class="form-group">
						  <label><?php echo lang("site.mobile");?> <span class="require">*</span></label>
						  <input type="text" class="form-control" name="suPhone" autocomplete="off" placeholder="<?php echo lang("site.mobile");?>"/>
						</div>
						<?php if($config->MEMBER_PHONE_CONFIRM){?>
						<div class="form-group form-inline">
						  <label>SMS인증 <span class="require">*</span></label>
							<input type="text" class="form-control" id="biz_sms_confirm_num" name="sms_confirm_num" autocomplete="off" placeholder="인증번호" style="width:40%"/>
							<input type="hidden" id="biz_sms_confirm" name="sms_confirm" value=""/>
							<button type="button" class="btn btn-default" onclick="sms_confirm_send(this,$('#biz_signup_form'));"><i class="glyphicon glyphicon-envelope"></i> 인증요청</button>
							<span class="help-inline">(<span class="minute">00</span>:<span class="second">00</span>)</span>
						</div>
						<?php }?>
						<div class="form-group">
						  <label><?php echo lang("site.tel");?> <span class="require">*</span></label>
						  <input type="text" class="form-control" name="tel" autocomplete="off" placeholder="<?php echo lang("site.tel");?>"/>
						</div>
						<div class="form-group">
						  <label>카카오톡아이디</label>
						  <input type="text" class="form-control" name="kakao" autocomplete="off" placeholder="카카오톡아이디"/>
						</div>

						<h5 class="page-header">사업자 및 공인중개사 정보</h5>
						<div class="form-group">
						  <label>
							<?php if($config->INSTALLATION_FLAG){?>
							사업자명
							<?php } else {?>
							중개사무소명
							<?php } ?>
							<span class="require">*</span>
						  </label>
						  <input type="text" class="form-control" name="biz_name" autocomplete="off" placeholder="사업자명"/>
						  <div class="help-block">* 중개업자일 경우에는 개설등록증에 기재된 명칭.</div>
						</div>
						<div class="form-group">
						  <label><?php echo lang("site.ceo");?> <span class="require">*</span></label>
						  <input type="text" name="biz_ceo" class="form-control" placeholder="<?php echo lang("site.ceo");?>"/>
						</div>							
						<div class="form-group">
						  <label><?php echo lang("site.biznum");?> <span class="require">*</span></label>
						  <input type="text" class="form-control" name="biz_num" autocomplete="off" placeholder="<?php echo lang("site.biznum");?>"/>
						</div>
						<div class="form-group">
						  <label>권한/자격</label>
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
						<div class="form-group">
						  <label><?php echo lang("site.renum");?> <span class="require">*</span></label>
						  <input type="text" class="form-control" name="re_num" autocomplete="off" placeholder="<?php echo lang("site.renum");?>"/>
						</div>
						<div class="form-group">
						  <label>사업자주소 <span class="require"> * </span></label>
						  <button type="button" class="btn btn-default btn-sm" onclick="get_postcode('biz_signup_form')">주소검색</button>
						</div>							
						<div class="form-group">
						  <input type="text" class="form-control" id="address" name="address" autocomplete="off" placeholder="사업자주소" readonly/>
						</div>
						<div class="form-group">
						  <input type="text" name="address_detail" class="form-control" placeholder="상세주소"/>
						</div>
						<div class="form-group">
							<div class="w-clearfix radios-container">
							  <a href="/mobile/rule"><?php echo lang("menu.uselaw");?></a>,
							  <a href="/mobile/privacy"><?php echo lang("menu.infolaw");?></a>,
							  <a href="/mobile/location"><?php echo lang("menu.positionlaw");?></a>
							  <div class="w-checkbox w-clearfix checkbox-field">
								<input type="checkbox" id="biz_agree" name="biz_agree" data-name="Checkbox"/> 동의합니다
							  </div>
							</div>
						</div>
						<div class="text-right" style="margin-bottom:30px;">
						  <button type="submit" class="btn btn-primary btn-lg btn-block btn-join">
						  <?php echo lang("menu.signup");?></button>
						</div>
					  <?php echo form_close();?>	
					</div>

				  </div>
				</div>
				<!-- END CONTENT -->

			</div>
		</div>
	</div>
</div>

<!-- DAUM POST LAYER -->
<div id="layer" style="display:none;border:5px solid;position:fixed;width:100%px;height:460px;left:5%;top:5%;overflow:hidden;-webkit-overflow-scrolling:touch;z-index:10">
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