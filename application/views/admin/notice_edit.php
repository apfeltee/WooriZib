<script>
$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	$("#upload_dialog").dialog({
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:400,
			height: 230,
			modal: true,
			buttons: {
				'이미지 등록': function() {
					$("#upload_form").submit();
				}
			}
	});

	$('#upload_form').ajaxForm({
		success:function(data){
			if(data == ""){
				alert("실패");
				alert(data);
			} 
			else {
				
				 CKEDITOR.instances.content.insertHtml( "<img src='"+data+"'>" );
			} 
			$('#upload_dialog').dialog("close");

		}
	});

	$("#notice_form").validate({  
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
				minlength: "제목은 최소 3자리 이상입니다"
			}
		} 
	});  

});

</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				<?php echo lang("menu.notice");?><small><?php echo lang("site.modify");?></small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<?php echo lang("menu.notice");?> <?php echo lang("site.modify");?>
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn red" onclick="history.go(-1);"><?php echo lang("site.back");?></button>
			</div>
		</div>
	</div>
</div><!-- /.row -->

<?php echo form_open_multipart("adminnotice/edit_action","id='notice_form'");?>
<input type="hidden" name="id" value="<?php echo $query->id?>"/>
<div class="portlet">
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> <?php echo lang("site.title");?></div>
		<div class="col-sm-10 col-xs-8 value">
				<input type="text" name="title" class="form-control" value="<?php echo $query->title;?>">
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> 설명</div>
		<div class="col-sm-10 col-xs-8 value">
			<div class="help-block">
				* 폭은 560px 크기로 이미지 업로드시 자동 조정됩니다. <br/>
				* 내용이 길 경우에는 창이 길어져서 보기 안 좋기 때문에 최대한 에디터 안의 크기에 맞춰서 제작해 주세요.
			</div>
			<div style="width:540px;">
				<textarea name="content" class="form-control" rows="5"><?php echo $query->content;?></textarea>
			</div>
		</div>
	</div>	

<div style="text-align:right;">
	<button type="submit" class="btn btn-primary"><?php echo lang("site.modify");?></button>
</div>
<?php echo form_close();?>

</div>

<script>
	CKEDITOR.replace( 'content', {customConfig: '/ckeditor/dungzi_config.js'});
</script>

<div id="upload_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;padding:10px 0px 0px 0px;">
<div style="padding:10px;">
	<?php echo form_open_multipart("adminnotice/upload_action","id='upload_form' autocomplete='off'");?>
	<div class="help-block">* 큰 이미지는 넓이(폭)이 560픽셀 이하로 조정됩니다.</div>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
	<?php echo form_close();?>
</div>
</div>
