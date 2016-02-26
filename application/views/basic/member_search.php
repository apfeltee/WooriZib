<script>
	$(document).ready(function() {

		$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */		

		$("#searchpw_form").ajaxForm({
			beforeSubmit:function(){
				$("#searchpw_form").validate({ 
					rules: {
						spEmail: {  
							required: true,  
							email: true
						}
					},  
					messages: {  
						spEmail: {  
							required: "<?php echo lang("form.required");?>",  
							email: "<?php echo lang("form.emailerror");?>"
						}
					} 
				});
				if (!$("#searchpw_form").valid()) return false;
			},
			success:function(data){
				if(data=="1"){
					msg($("#msg"), "success" ,"초기화된 비밀번호를 발송하였습니다. (스팸함에도 확인해 주세요.)");
				} else {
					msg($("#msg"), "danger" ,"해당 이메일이 없습니다. 문의해 주세요.");
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
            <li class="active"><?php echo lang("menu.lostpw");?></li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-3">
            <ul class="list-group margin-bottom-25 sidebar-menu">
			  <?php if($this->session->userdata("id")==""){?>
				  <li class="list-group-item clearfix"><a href="/member/signin"><i class="fa fa-angle-right"></i> <?php echo lang("menu.login");?></a></li>
				  <?php if($config->MEMBER_TYPE=="general" || $config->MEMBER_TYPE=="both"){?>
				  <li class="list-group-item clearfix"><a href="/member/signup"><i class="fa fa-angle-right"></i> <?php echo lang("menu.signup");?></a></li>
				  <?php }?>
				  <?php if($config->MEMBER_TYPE=="biz" || $config->MEMBER_TYPE=="both"){?>
				  <li class="list-group-item clearfix"><a href="/member/signup_pre"><i class="fa fa-angle-right"></i>공인중개사 <?php echo lang("menu.signup");?></a></li>
				  <?php }?>
				  <li class="list-group-item clearfix active"><a href="/member/search"><i class="fa fa-angle-right"></i> <?php echo lang("menu.lostpw");?></a></li>
			  <?php }?>
			  <?php if($this->session->userdata("id")){?>
              <li class="list-group-item clearfix"><a href="/member/profile"><i class="fa fa-angle-right"></i> <?php echo lang("menu.modifyprofile");?></a></li>
			  <?php }?>
              <li class="list-group-item clearfix"><a href="/member/hope"><i class="fa fa-angle-right"></i> <?php echo lang("site.saved");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/history"><i class="fa fa-angle-right"></i> <?php echo lang("site.seen");?></a></li>	<?php if($config->BUILDING_ENQUIRE){?>
			  <li class="list-group-item clearfix"><a href="/member/building_enquire_list"><i class="fa fa-angle-right"></i> 건축물자가진단 의뢰</a></li>
			  <?php }?>              
            </ul>
          </div>
          <!-- END SIDEBAR -->

          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-9">
            <h1><?php echo lang("menu.lostpw");?></h1>
            <div class="content-form-page">
              <div class="row">
                <div class="col-md-7 col-sm-7">
				  <div id="msg"></div>
				  <?php echo form_open("member/search_action","id='searchpw_form' class='form-horizontal form-without-legend' role='form'");?>
                    <div class="form-group">
                      <label for="email" class="col-lg-4 control-label"><?php echo lang("site.email");?> <span class="require">*</span></label>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" name="spEmail">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-8 col-md-offset-4 padding-left-0 padding-top-20">
                        <button type="submit" class="btn btn-primary">임시 비밀번호 발송</button>
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
