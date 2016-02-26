<script src="/assets/mobile/js/iscroll.js" type="text/javascript"></script>
<script>
var sido_scroll;
var gugun_scroll;
var dong_scroll;
var gallery_css_change = true;
var flag = 0;
$(document).ready(function(){
	$(".caption").css("color","#333333");
	$(".caption > i").css("color","#333333");
	$(".form-body").css("padding","0px 10px");
	$(".form-group > label").removeAttr("class");
	$(".form-group > label").addClass("col-md-12").addClass("control-label").css("float","none");
	$(".control-label").css("text-align","left").css("margin-bottom","10px");
	$(".control-label").css("padding","15px 15px 0 15px").css("font-weight","bold");
	$(".form-group").css("margin-right","-10px").css("margin-left","-10px");
	$(".col-md-9").removeClass('col-md-9').addClass('col-md-12').css("float","none");
	$(".col-md-10").removeClass('col-md-10').addClass('col-md-12').css("float","none");
	$(".btn-group").css("display","inline");
	$(".area_btn_group").css("display","inline-block");
	$(".input-small").removeClass('input-small').addClass('input-medium');
	$(".btn").css("border-radius","0px");
	$(".form-control").css("margin-bottom","10px");

	$("input[name='category']").parent().css("width","50%").css("margin-bottom","10px");
	$("input[name='type']").parent().css("width","25%").css("margin-bottom","10px");
	$("input[name='part']").parent().css("width","50%").css("margin-bottom","10px");
	$("input[name='theme[]']").parent().css("width","50%").css("margin-bottom","10px");
	setTimeout(function () {
		$("input[name='option[]']").parent().css("width","50%").css("margin-bottom","10px");
		$(".btn").css("border-radius","0px");
	}, 3000);
	$("select[name='phone_type[]'").removeClass('input-medium').addClass('input-small');
	if($(".fa-cog").length > 0){
		$(".fa-cog").parent().remove();
	}
	$("#gmap_label").css("margin","0").css("padding","0");
	
	$(".body").css("padding-top","0px");
	$(".area_part").css("padding","10px 0px");
	$(".modal-dialog").css("margin","10px");
	$("#address_text").css("margin-top","10px");

	sido_scroll = new iScroll('sido_section');
	gugun_scroll = new iScroll('gugun_section');
	dong_scroll = new iScroll('dong_section');
});
</script>

<script src="/assets/plugin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/common/js/init.js"></script>
<script src="/script/product_common"></script>
<script src="/script/product_add/front"></script>
<script src="/assets/plugin/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/plugin/plupload/plupload.full.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="/assets/plugin/fancybox/source/jquery.fancybox.pack.js"></script>
<script src="/assets/plugin/jquery.rotate.js"></script>
<script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=<?php echo $config->DAUM_MAP_KEY;?>&libraries=services"></script>

<link href="/assets/plugin/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/admin/css/jquery-ui.theme.css">
<link href="/assets/common/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="/assets/admin/css/style.css">
<link href="/assets/admin/css/jquery-ui.css" rel="stylesheet">
<style>
h3 {
	margin-top: 20px;
	margin-bottom: 10px;
	font-size: 24px;
	line-height: 30px;
	font-weight: 700;
}
.first_thumb{
	border:4px solid #83BEE0;
}
</style>

<div class="page-content" id="main-stack">
  <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
    <div class="w-container">
      <!-- 상단 시작 -->
      <?php echo $menu;?>
      <div class="wrapper-mask" data-ix="menu-mask"></div>
      <div class="navbar-title"><?php echo lang("product");?> <?php echo lang("site.submit");?></div>
      <div class="w-nav-button navbar-button left" id="menu-button" data-ix="hide-navbar-icons">
        <div class="navbar-button-icon home-icon">
          <div class="bar-home-icon"></div>
          <div class="bar-home-icon"></div>
          <div class="bar-home-icon"></div>
        </div>
      </div>
	  <a href="#" class="w-inline-block navbar-button right" onclick="onBackKeyDown();">
		<div class="navbar-button-icon icon ion-ios-close-empty"></div>
	  </a>
      <!-- 상단 종료 -->
    </div>
  </div>
  <div class="body">
	<?php echo form_open_multipart("member/product_add_action/1","id='product_form' class='product form-horizontal'");?>
	<input type="hidden" id="flag" value="0">
	<input type="hidden" id="is_activated" name="is_activated" value="0">
	<input type="hidden" name="member_id" value="<?php echo $this->session->userdata("id");?>">
	<?php
		echo $product_form;
	?>
	<div class="text-center margin-top-20 margin-bottom-20">
		<button type="button" class="btn btn-primary regist_btn btn-lg" is_activated="0" style="margin:20px;"><?php echo lang("site.submit");?></button>
	</div>
	<?php echo form_close();?>
  </div>
</div>

<div id="upload_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;">
	<?php echo form_open_multipart("product/upload_action","id='upload_form'");?>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;" autocomplete='off'/>
	<?php echo form_close();?>
</div>