<script src="/script/product_common"></script>
<script src="/script/product_add/front"></script>
<link rel="stylesheet" href="/assets/admin/css/jquery-ui.theme.css">
<link href="/assets/admin/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="/assets/admin/css/style.css">
<link href="/assets/admin/css/jquery-ui.css" rel="stylesheet">
<link href="/style/index" rel="stylesheet">
<style>
.first_thumb{
	border:4px solid #83BEE0;
}
.dropdown-menu{
	margin-top:0px !important;
}
</style>
<!--[if lt IE 9]>
<script src="/assets/plugin/respond.min.js"></script>
<script src="/assets/plugin/excanvas.min.js"></script> 
<![endif]-->
<script src="/assets/plugin/jquery-ui.min.js"></script>
<script src="/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="/ckeditor/bootstrap-ckeditor-fix.js"></script>
<script type="text/javascript" src="/assets/plugin/plupload/plupload.full.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="/assets/plugin/fancybox/source/jquery.fancybox.pack.js"></script>
<script src="/assets/plugin/jquery.rotate.js"></script>
<script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=<?php echo $config->DAUM_MAP_KEY;?>&libraries=services"></script>
<script>
var flag = 1;
</script>
	<div class="main">
		<div class="_container">

		<ul class="breadcrumb">
			<li><a href="/"><?php echo lang("menu.home");?></a></li>
			<li class="active"><?php echo lang("product");?>등록</li>
		</ul>
		<!-- BEGIN SIDEBAR & CONTENT -->
		<div class="row margin-bottom-40">
		  <!-- BEGIN SIDEBAR -->
		  <div class="sidebar col-md-3 col-sm-3">
			<ul class="list-group margin-bottom-25 sidebar-menu">
			  <li class="list-group-item clearfix"><a href="/member/product"><i class="fa fa-angle-right"></i> <?php echo lang("product");?>관리</a></li>
			  <li class="list-group-item clearfix active"><a href="/member/product_add"><i class="fa fa-angle-right"></i> <?php echo lang("product");?>등록</a></li>
			  <?php if($config->USE_PAY){?>
			  <li class="list-group-item clearfix"><a href="/member/product_pay"><i class="fa fa-angle-right"></i> <?php echo lang("pay");?></a></li>
			  <li class="list-group-item clearfix"><a href="/member/pay"><i class="fa fa-angle-right"></i> 결제내역</a></li>
			  <?php }?>
			  <?php if($this->session->userdata("type")=="admin" || $this->session->userdata("type")=="biz"){?>
			  <li class="list-group-item clearfix"><a href="/member/blog"><i class="fa fa-angle-right"></i> 나의블로그</a></li>
			  <?php }?>
			</ul>
		  </div>
		  <!-- END SIDEBAR -->

		  <!-- BEGIN CONTENT -->
		  <div class="col-md-9 col-sm-9">
			<h1><?php echo lang("product");?>등록</h1>
			<div class="content-form-page" style="padding:0">
			  <div class="row">
				<div class="col-md-12 col-sm-12">
				<?php echo form_open_multipart("member/product_add_action","id='product_form' class='product form-horizontal'");?>
				<input type="hidden" id="flag" value="0">
				<input type="hidden" id="is_activated" name="is_activated" value="0">
				<input type="hidden" name="member_id" value="<?php echo $this->session->userdata("id");?>">
			
				<?php
					echo $product_form;
				?>
				<div class="text-center margin-top-20 margin-bottom-20">
					<button type="button" class="btn blue regist_btn btn-lg" is_activated="0" style="margin:20px;"><?php echo lang("site.submit");?></button>
				</div>
				<?php echo form_close();?>
				</div>
			  </div>
			</div>
		  </div>
		  <!-- END CONTENT -->
		</div>
		<!-- END SIDEBAR & CONTENT -->
	</div>
</div>


<?php
	if(!MobileCheck()){
?>
<script>
	CKEDITOR.replace( 'content', {customConfig: '/ckeditor/dungzi_config.js'});
</script>
<?php } ?>

<div id="upload_dialog" title="이미지업로드" style="display:none;">
	<?php echo form_open_multipart("member/upload_action","id='upload_form'");?>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;" autocomplete='off'/>
	<?php echo form_close();?>
</div>

