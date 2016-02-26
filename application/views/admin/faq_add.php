<script>
$(document).ready(function(){	
	$("#faq_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			title: {  
				required: true,  
				minlength: 3
			}
		},  
		messages: {  
			title: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "최소 3자리 이상입니다"
			}
		} 
	});  
});
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			<?php echo lang("menu.faq");?><small>등록</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>팝업 등록</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn red" onclick="history.go(-1);"><?php echo lang("site.back");?></button>
			</div>
		</div>
	</div>
</div>
<?php echo form_open_multipart("adminfaq/add_action","id='faq_form'");?>
<div class="portlet">
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> <?php echo lang("site.title");?></div>
		<div class="col-sm-10 col-xs-8 value">
			<input type="text" name="title" class="form-control"/>
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> <?php echo lang("enquire.answer");?></div>
		<div class="col-sm-10 col-xs-8 value">
			<div>
				<textarea name="content" class="form-control" rows="25"></textarea>
			</div>
		</div>
	</div>	
</div>
<div style="text-align:center;margin-top:10px;">
	<button type="submit" class="btn btn-primary"><?php echo lang("site.submit");?></button>
</div>
<?php echo form_close();?>