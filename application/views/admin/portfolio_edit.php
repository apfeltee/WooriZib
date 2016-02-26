<script>
$(document).ready(function(){

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

	$("#portfolio_form").validate({  
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
				뉴스<small>수정</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li><i class="fa fa-file-image-o"></i> <a href="#">뉴스</a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<a href="/adminportfolio/index">목록</a> <i class="fa fa-angle-right"></i>
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
</div><!-- /.row -->

<?php echo form_open_multipart("adminportfolio/edit_action","id='portfolio_form'");?>
<input type="hidden" name="id" value="<?php echo $query->id?>"/>
<div class="portlet">
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> <?php echo lang("site.title");?></div>
		<div class="col-sm-10 col-xs-8 value">
				<input type="text" name="title" class="form-control" value="<?php echo $query->title;?>">
		</div>
	</div>
	<div class="row static-info">
		<div class="col-sm-2 col-xs-4 name"><i class="fa fa-asterisk help" data-toggle="tooltip" title="<?php echo lang("form.required");?>"></i> 카테고리</div>
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
			<?php if($query->thumb_name==""){?>등록된 이미지 없음<?php } else {?><img src="/uploads/portfolios/thumb/<?php echo $query->thumb_name;?>" class="img-responsive"/><?php }?>
			<span class="btn btn-default btn-file help" data-toggle="tooltip" title="기타 사진들은 갤러리에서 등록">
			사진 촬영 또는 업로드<input type="file" name="thumb_name"/>
			</span>
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
			<input type="text" id="tag" name="tag" class="form-control" placeholder=", 콤마로 구분해 주세요"  value="<?php echo $query->tag;?>"> 
		</div>
	</div>	

<div style="text-align:right;">
	<button type="submit" class="btn btn-primary">수정</button>
</div>
<?php echo form_close();?>


<script>
	CKEDITOR.replace( 'content', {customConfig: '/ckeditor/dungzi_config.js'});
</script>

<div id="upload_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;padding:10px 0px 0px 0px;">
<div style="padding:10px;">
	<?php echo form_open_multipart("adminportfolio/upload_action","id='upload_form' autocomplete='off'");?>
	<div class="help-block">* 큰 이미지는 넓이(폭)이 890픽셀로 조정됩니다.</div>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
	<?php echo form_close();?>
</div>
</div>
