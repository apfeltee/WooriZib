<script>
	$(document).ready(function() {
		
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
				if(data=="1"){
					location.href="/";
				} else if(data=="0"){
					msg($("#msg"), "danger" ,"비밀번호가 틀립니다. 다른 비밀번호를 입력하시거나 비밀번호 찾기를 해주세요.");
				} else if(data=="2"){
					msg($("#msg"), "danger" ,"로그인 승인이 완료되지 않았거나, 관리자에 의해 로그인이 거부되었습니다.");
				} else if(data=="3"){
					msg($("#msg"), "danger" ,"접근이 허용된 IP에서 로그인 해주세요.");
				} else if(data=="4"){
					msg($("#msg"), "danger" ,"로그인 기간이 만료 되었습니다.");
				} else {
					msg($("#msg"), "danger" ,"해당 이메일로 가입된 회원이 없습니다.회원가입을 해주세요.");
				}
			}
		});

	});
</script>
	<div class="main">
		<div class="_container">

		<ul class="breadcrumb">
            <li><a href="/"><?php echo lang("menu.home");?></a></li>
            <li><a href="#"><?php echo lang("menu.mypage");?></a></li>
            <li class="active"><?php echo lang("menu.login");?></li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-3">
            <ul class="list-group margin-bottom-25 sidebar-menu">
			  <?php if($this->session->userdata("id")==""){?>
              <li class="list-group-item clearfix active"><a href="/member/signin"><i class="fa fa-angle-right"></i> <?php echo lang("menu.login");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/signup"><i class="fa fa-angle-right"></i> <?php echo lang("menu.signup");?></a></li>
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
            <h1><?php echo lang("menu.login");?></h1>
            <div class="content-form-page">
              <div class="row">
                <div class="col-md-7 col-sm-7">
				  <div id="msg"></div>
				  <?php echo form_open("member/signin_action","id='signin_form' class='form-horizontal form-without-legend' role='form'");?>
                    <div class="form-group">
                      <label for="email" class="col-lg-4 control-label"><?php echo lang("site.email");?> <span class="require">*</span></label>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" name="siEmail">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="password" class="col-lg-4 control-label"><?php echo lang("site.pw");?> <span class="require">*</span></label>
                      <div class="col-lg-8">
                        <input type="password"  class="form-control" name="siPw">
                      </div>
                    </div>
                    <!--
					<div class="row">
                      <div class="col-lg-8 col-md-offset-4 padding-left-0">
                        <a href="page-forgotton-password.html">비밀번호를 분실했습니까?</a>
                      </div>
                    </div>
					-->
                    <div class="row">
                      <div class="col-lg-4 col-md-offset-4 padding-left-0 padding-top-20">
                        <button type="submit" class="btn btn-primary btn-block"><?php echo lang("menu.login");?></button>
                      </div>
                      <div class="col-lg-4 padding-left-0 padding-top-20">
                        <a class="btn btn-default" href="/member/search"><?php echo lang("menu.lostpw");?></a>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-8 col-md-offset-4 padding-left-0 padding-top-10 padding-right-30">
                        <!--hr>
                        <div class="login-socio">
                            <p class="text-muted">or login using:</p>
                            <ul class="social-icons">
                                <li><a href="#" data-original-title="facebook" class="facebook" title="facebook"></a></li>
                                <li><a href="#" data-original-title="Twitter" class="twitter" title="Twitter"></a></li>
                                <li><a href="#" data-original-title="Google Plus" class="googleplus" title="Google Plus"></a></li>
                                <li><a href="#" data-original-title="Linkedin" class="linkedin" title="LinkedIn"></a></li>
                            </ul>
                        </div-->
                      </div>
                    </div>
                  <?php echo form_close();?>
                </div>
                <div class="col-md-4 col-sm-4 pull-right">
                  <div class="form-info">
                    <h2><em>회원</em> 정보관리</h2>
                    <p>불 필요한 정보를 요구하지 않으며 암호는 관리자도 풀 수 없는 암호화 방식으로 저장됩니다. 비밀번호를 분실할 경우 알 수 있는 방법이 없으므로 비밀번호를 초기화합니다.</p>
                        <a href="/member/signup" class="btn btn-danger margin-bottom-20 btn-block" style="color:white;">개인 회원가입</a>
                        <?php if($config->USE_PAY){?>
                        <a href="/member/signup/biz" type="submit" class="btn btn-danger btn-block" style="color:white;">
                          <?php if($config->INSTALLATION_FLAG){?>
                          사업자 / 중개사 회원가입
                          <?php } else {?>
                          공인중개사 회원가입
                          <?php } ?>  
                          </a>
                        <?php }?>
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
