<div class="page-content" id="checkplus_body">
	<div class="page-content" id="main-stack" data-scroll="0">
	  <div class="navbar-title">실명인증</div>
	  <a href="/mobile/home" class="w-inline-block navbar-button left">
		<div class="navbar-button-icon icon smaller ion-ios-home-outline"></div>
	  </a>
	  <a href="#" class="w-inline-block navbar-button right" onclick="onBackKeyDown();">
		<div class="navbar-button-icon icon ion-ios-close-empty"></div>
	  </a>

	  <div class="body padding">
		<?php if($config->CP_CODE){?>
			<div class="text-center">
				<div class="separator-button-input"></div>
				<img src="/assets/basic/img/namecheck.gif" style="max-width:100%;"/>
				<div class="separator-button-input"></div>
				<form name="form_chk" id="form_chk" method="post" action="https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb" target="checkplus_frame">
					<input type="hidden" name="m" value="checkplusSerivce"/>
					<input type="hidden" name="EncodeData" value="<?php echo $enc_data;?>"/>
					<input type="hidden" name="param_r1" value=""/>
					<input type="hidden" name="param_r2" value=""/>
					<input type="hidden" name="param_r3" value=""/>
					<button onclick="checkplus();" type="button" class="btn btn-primary btn-lg">휴대폰 실명인증</button>
				</form>				
			</div>
		<?php }?>
	  </div>
	</div>
	<div class="page-content loading-mask" id="new-stack">
		<div class="loading-icon">
			<div class="navbar-button-icon icon ion-load-d"></div>
		</div>
	</div>
	<div class="shadow-layer"></div>
</div>
<iframe id="checkplus_frame" name="checkplus_frame" style="width:100%;height:100%;display:none;" frameborder=0></iframe>
<script>
$(document).ready(function() {
	var real_name = "<?php echo $real_name;?>";
	if(real_name){
		$("#real_name").val("<?php echo $real_name;?>");
		$("#hidden_form").submit();
	}
});
function checkplus(){
	$("#checkplus_body").hide();
	$("#checkplus_frame").show();
	$("#form_chk").submit();
}
</script>
<form id="hidden_form" name="hidden_form" action="/mobile/signup" method="post">
	<input type="hidden" id="real_name" name="real_name" value="<?php echo $real_name;?>"/>
</form>