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
              <li class="list-group-item clearfix"><a href="/member/hope"><i class="fa fa-angle-right"></i> <?php echo lang("site.saved");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/history"><i class="fa fa-angle-right"></i> <?php echo lang("site.seen");?></a></li>              
            </ul>
          </div>
          <!-- END SIDEBAR -->

			<!-- BEGIN CONTENT -->
			<div class="col-md-9 col-sm-9">
				<div class="content-form-page">
					<div class="row">
						<div class="col-md-8 col-sm-8">
							<h1>실명인증</h1>
							<?php if($config->CP_CODE){?>
							<div class="col-md-6">
								<div class="pricing hover-effect">
									<div class="pricing-head">
										<h3>휴대폰 실명인증</h3>
									</div>
									<ul class="pricing-content list-unstyled">
										<li>
											<i class="fa fa-asterisk"></i> 실명인증이 되지 않는 경우 아래의 실명인증기관에 실명등록을 요청할 수 있습니다 (나이스신용평가정보 1588-2486 한국정보통신산업협회(KAIT) 02)580-0571).
										</li>
										<li>
											<i class="fa fa-star"></i> 본인인증기관을 통해 본인인증 및 가입여부 확인 후 회원가입이 가능합니다.
										</li>
									</ul>
									<div class="pricing-footer">
										<p>
											<img src="/assets/basic/img/namecheck.gif" style="max-width:100%;">
										</p>
										<a href="javascript:fnPopup();" class="btn yellow-crusta">
										실명인증 <i class="m-icon-swapright m-icon-white"></i>
										</a>
									</div>
								</div>
							</div>
							<?php }?>
						</div>
						<div class="col-md-4 col-sm-4 pull-right">
							<div class="form-info">
								<h2><i class="fa fa-arrow-circle-right"></i> <em>실명인증</em> 절차</h2>
								 <p>정보통신망 이용촉진 및 정보보호 등에 관한 법률』 제 23의 2 “주민등록번호의 사용 제한” 에 의거하여 고객님의 주민등록번호를 일절 수집·이용하지 않습니다. 만 14세 미만은 회원으로 가입할 수 없습니다</p>
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
<script language='javascript'>
window.name ="Parent_window";
function fnPopup(){
	window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
	document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
	document.form_chk.target = "popupChk";
	document.form_chk.submit();
}
</script>
<form name="form_chk" method="post">
	<input type="hidden" name="m" value="checkplusSerivce"/>
	<input type="hidden" name="EncodeData" value="<?php echo $enc_data;?>"/>
	<input type="hidden" name="param_r1" value=""/>
	<input type="hidden" name="param_r2" value=""/>
	<input type="hidden" name="param_r3" value=""/>	
</form>

<script>
$(document).ready(function() {
	var real_name = "<?php echo $real_name;?>";
	if(real_name){
		$("#real_name",opener.document).val("<?php echo $real_name;?>");
		$("#hidden_form",opener.document).submit();
		self.close();
	}
});
</script>
<form id="hidden_form" name="hidden_form" action="/member/signup" method="post">
	<input type="hidden" id="real_name" name="real_name" value="<?php echo $real_name;?>"/>
</form>