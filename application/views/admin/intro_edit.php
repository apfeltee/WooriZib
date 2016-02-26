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

	$("#intro_form").validate({ 
		rules: {
			title: { required: true }
		},  
		messages: {  
			title: { required: "<?php echo lang("form.required");?>" }
		} 
	});
});

</script>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			회사소개메뉴<small>관리</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i></li>
				<li><a href="/adminintro/index">회사소개메뉴 관리</a> <i class="fa fa-angle-right"></i></li>
				<li>등록</li>
			</ul>
		</div>
	</div>
</div>

<?php echo form_open_multipart("adminintro/edit_action","id='intro_form'");?>
<input type="hidden" name="id" value="<?php echo $query->id?>"/>
<div class="portlet">
	<div class="form-group">
		<div class="row">
			<div class="col-md-2">
				<label><strong>메뉴명</strong></label>
				<input type="text" class="form-control input-medium" name="title" placeholder="메뉴명" maxlength="10" value="<?php echo $query->title;?>"/>
			</div>
			<div class="col-md-2">
				<label><strong>사용여부</strong></label>
				<select name="flag" class="form-control input-medium">
					<option value="Y" <?php if($query->flag=="Y") echo "selected";?>>사용함</option>
					<option value="N" <?php if($query->flag=="N") echo "selected";?>>사용안함</option>
				</select>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label><strong><?php echo lang("site.content");?></strong></label>
		<textarea name="content"><?php echo $query->content;?></textarea>
	</div>
</div>
<div style="text-align:center;margin-top:10px;">
	<button type="button" class="btn btn-danger btn-lg" onclick="history.go(-1);"><?php echo lang("site.back");?></button>
	<button type="submit" class="btn btn-primary btn-lg">등록</button>
</div>
<?php echo form_close();?>

<script>
	CKEDITOR.replace( 'content', {customConfig: '/ckeditor/dungzi_config.js'});
</script>

<div id="upload_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;">
	<?php echo form_open_multipart("adminintro/upload_action","id='upload_form' autocomplete='off'");?>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
	<?php echo form_close();?>
</div>
