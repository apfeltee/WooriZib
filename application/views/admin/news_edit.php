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

	$("#news_form").validate({  
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
	
	$('#delete_thumb').click(function(){
		var news_id = '<?php echo $query->id?>';
		var thumb_name = '<?php echo $query->thumb_name?>';
		if(!thumb_name || $('#thumb_image').hasClass("is-delete")){
			alert("<?php echo lang("msg.nodata");?>");
			return false;
		}
		if(confirm("대표사진이 바로 삭제 됩니다. 삭제하시겠습니까?")){
			$.ajax({
				url: "/adminnews/delete_thumb_image",
				type: "POST",
				data: {
					news_id: news_id,
					thumb_name: thumb_name
				},
				success: function(data) {
					$('#thumb_image').addClass("is-delete");
					$('#thumb_image').html('등록된 이미지 없음');
					msg($("#thumb_msg"), "success" ,"삭제 되었습니다.");
				}
			});		
		}
	});

	$('input[name="thumb_name"]').change(function(e){
		msg($("#thumb_msg"), "info" ,$(this).val());
	});

	/* 첨부파일 */
	$("#add_file").click(function(e){
		e.preventDefault();
		$("#file_section").append('<div class="multi-form-control-wrapper"><input type="file" name="file[]" class="form-control input-inline input-xlarge" placeholder="첨부파일선택" autocomplete="off" style="height:auto"/> <button type="button" class="input_delete btn red btn-xs input-inline"><i class="fa fa-minus"></i></button></div>');

		$(".input_delete").on("click",function(e){
			e.preventDefault(); $(this).parent('div').remove();
		})
	});

});

function file_delete(obj,id){
	if(confirm("파일을 삭제 하시겠습니까?")){
		$.getJSON("/adminnews/delete_file/"+id+"/"+Math.round(new Date().getTime()),function(data){
			$(obj).prev().remove();
			$(obj).remove();
		});	
	}
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			뉴스<small>수정</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<a href="/adminnews/index">뉴스</a> <i class="fa fa-angle-right"></i>
				</li>
				<li>
					수정
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn red" onclick="history.go(-1);"><?php echo lang("site.back");?></button>
			</div>
		</div>
	</div>
</div>

<?php echo form_open_multipart("adminnews/edit_action","id='news_form'");?>
<input type="hidden" name="id" value="<?php echo $query->id?>"/>
<div class="portlet">
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> <?php echo lang("site.title");?></div>
		<div class="col-sm-10 col-xs-8 value">
			<input type="text" name="title" class="form-control" value="<?php echo $query->title;?>">
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> <?php echo lang("menu.category");?></div>
		<div class="col-sm-10 col-xs-8 value">
			<select name="category" class="form-control input-small select2me">
				<option value="">카테고리를 선택해 주세요.</option>
				<?php foreach($category as $val){?>
				<option value="<?php echo $val->id;?>" <?php if($query->category==$val->id) {echo "selected";}?>><?php echo $val->name;?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name">대표사진</div>
		<div class="col-sm-10 col-xs-8 value">
		<?php if($query->thumb_name==""){?>
			<div id="thumb_image">등록된 이미지 없음</div>
		<?php } else {?>
			<div id="thumb_image"><img src="/uploads/news/thumb/<?php echo $query->thumb_name;?>" class="img-responsive"/></div>
		<?php }?>
			<span class="btn btn-default btn-file margin-top-10">사진 촬영 또는 업로드<input type="file" name="thumb_name"/></span>
			<div id="thumb_msg"></div>
			<div id="delete_thumb" class="btn btn-primary"><i class="fa fa-trash-o"></i> 사진 삭제</div>
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><?php echo lang("product");?>우측 출력</div>
		<div class="col-sm-10 col-xs-8 value">
			<label class="radio-inline"><input type="radio" name="product_print" value="N" <?php if($query->product_print=="N") echo "checked";?>/> 출력안함</label>
			<label class="radio-inline"><input type="radio" name="product_print" value="Y" <?php if($query->product_print=="Y") echo "checked";?>/> 출력함</label>
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> 설명</div>
		<div class="col-sm-10 col-xs-8 value">
		<textarea name="content" class="form-control" rows="5"><?php echo $query->content;?></textarea>
		</div>
	</div>	
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name">키워드</div>
		<div class="col-sm-10 col-xs-8 value">
			<input type="text" id="tag" name="tag" class="form-control" placeholder=", 키워드로 구분해 주세요"  value="<?php echo $query->tag;?>"> 
		</div>
	</div>	
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> 담당자</div>
		<div class="col-sm-10 col-xs-8 value">
			<select name="member_id" class="form-control input-large select2me">
				<?php foreach($members as $val) {?>
					<option value="<?php echo $val->id?>"  <?php if($val->id==$query->member_id){ echo "selected";}?>><?php echo $val->name?> (<?php echo $val->email?>, <?php echo $val->phone?>)</option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name">첨부된파일</div>
		<div class="col-sm-10 col-xs-8 value">
			<?php foreach($attachment as $val){?>
			<button type="button" class="btn btn-default" onclick="location.href='/attachment/news_download/<?php echo $val->news_id;?>/<?php echo $val->id;?>'" style="margin:3px;"><?php echo $val->originname?> <i class="fa fa-download"></i></button><button type="button" class="btn btn-default" onclick="file_delete(this,'<?php echo $val->id;?>');"><i class="fa fa-times"></i></button>
			<?php }?>
			<?php if(count($attachment)==0){?>
			첨부파일이 없습니다.
			<?php }?>
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name">첨부파일</div>
		<div class="col-sm-10 col-xs-8 value">
			<div class="help-block">* 업로드가능한 파일 : doc,docx,hwp,ppt,pptx,pdf,zip,txt,jpg,png</div>
			<div id="file_section" class="form-inline">
				<div class="multi-form-control-wrapper">
					<input type="file" name="file[]" class="form-control input-inline input-xlarge" placeholder="첨부파일선택" autocomplete="off" style="height:auto"/> <button type="button" id="add_file" class="btn blue btn-xs input-inline"><i class="fa fa-plus"></i></button>
				</div>
			</div>		
		</div>
	</div>
</div>
<div class="text-center margin-top-10 margin-bottom-10">
	<button type="submit" class="btn btn-primary">수정</button>
</div>
<?php echo form_close();?>


<script>
	CKEDITOR.replace( 'content', {customConfig: '/ckeditor/dungzi_config.js'});
</script>

<div id="upload_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;padding:10px 0px 0px 0px;">
<div style="padding:10px;">
	<?php echo form_open_multipart("adminnews/upload_action","id='upload_form' autocomplete='off'");?>
	<div class="help-block">* 큰 이미지는 넓이(폭)이 890픽셀로 조정됩니다.</div>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
	<?php echo form_close();?>
</div>
</div>
