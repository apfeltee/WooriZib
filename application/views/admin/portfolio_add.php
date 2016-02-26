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


	$(".regist_btn").click(function(){
		$("#is_activated").val($(this).attr("is_activated"));
		$("#flag").val("1");
		$("#portfolio_form").trigger("submit");
	});

	$("#portfolio_form").validate({  
        errorElement: "span",
        wrapper: "span", 
		rules: {
			title: {  
				required: true,  
				minlength: 2
			},
			category: {
				required: true
			}
		},  
		messages: {  
			title: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "제목은 최소 2자리 이상입니다"
			},
			category: {
				required: "<?php echo lang("form.required");?>"
			}
		} 
	});  
});

</script>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				갤러리<small>등록</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li><i class="fa fa-file-image-o"></i> <a href="#">갤러리</a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<a href="/adminportfolio/index">목록</a> <i class="fa fa-angle-right"></i>
				</li>
				<li>
					등록
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn red" onclick="history.go(-1);"><?php echo lang("site.back");?></button>
			</div>
		</div>
	</div>
</div><!-- /.row -->

<?php echo form_open_multipart("adminportfolio/add_action","id='portfolio_form'");?>
<input type="hidden" id="is_activated" name="is_activated" value="0">

<div class="portlet">
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> <?php echo lang("site.title");?></div>
		<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="title" class="form-control"/>
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> <?php echo lang("site.category");?></div>
		<div class="col-sm-10 col-xs-8 value">
					<select name="category" class="form-control input-large select2me">
						<option value="">카테고리를 선택</option>
						<?php foreach($category as $val){?>
						<option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
						<?php }?>
					</select>
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> 대표사진</div>
		<div class="col-sm-10 col-xs-8 value">
			<span class="btn btn-default btn-file help" data-toggle="tooltip" title="기타 사진들은 갤러리에서 등록">
				사진 촬영 또는 업로드<input type="file" name="thumb_name"/>
			</span>
			<div class="help-block">* 썸네일과 썸네일을 클릭하였을 때 큰 이미지를 보여주기 위한 사진입니다.</div>
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> 설명</div>
		<div class="col-sm-10 col-xs-8 value">
		<textarea name="content" class="form-control" rows="5"></textarea>
		</div>
	</div>	
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name">키워드</div>
		<div class="col-sm-10 col-xs-8 value">
			<input type="text" id="tag" name="tag" class="form-control help" placeholder=", 콤마로 구분해 주세요"  data-toggle="tooltip" title="메인사진에 3번째 키워드까지 표시되며 뉴스 등록시 키워드 등록"> 
		</div>
	</div>	
</div>

<div style="text-align:center;margin-top:10px;">
	<button type="button" class="btn blue regist_btn" is_activated="1"><i class="fa fa-unlock"></i> 공개로 등록</button>
	<button type="button" class="btn red regist_btn" is_activated="0"><i class="fa fa-lock"></i> 비공개로 등록</button>
</div>
<?php echo form_close();?>

<script>
	CKEDITOR.replace( 'content', {customConfig: '/ckeditor/dungzi_config.js'});
</script>

<div id="upload_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;">
	<?php echo form_open_multipart("adminportfolio/upload_action","id='upload_form' autocomplete='off'");?>
	<div class="help-block">* 큰 이미지는 넓이(폭)이 890픽셀로 조정됩니다.</div>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
	<?php echo form_close();?>
</div>
