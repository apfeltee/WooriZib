<link href="/assets/admin/css/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/admin/css/jquery-ui.theme.css">
<script src="/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="/ckeditor/bootstrap-ckeditor-fix.js"></script>
<script src="/assets/plugin/jquery-ui.min.js"></script>

<script>
$(document).ready(function() {

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */		

	$("#ask_form").validate({ 
		rules: {
			title: {  
				required: true
			},
			name: {  
				required: true,  
				minlength: 2
			},
			phone: {  
				required: true
			},
			content: {  
				required: true
			}
		},  
		messages: {
			title: {  
				required: "<?php echo lang("form.required");?>"
			},
			name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.2");?>"
			},
			phone: {  
				required: "<?php echo lang("form.required");?>"
			},
			content: {  
				required: "<?php echo lang("form.required");?>"
			}
		} 
	});

	$("#upload_dialog").dialog({
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:400,
			height: 230,
			modal: true,
			open: function (event, ui) {
				 $(".ui-dialog").css("z-index",9999);
			},
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

	$("input[name='open']").change(function(){
		if($(this).val()=="N"){
			$(".pw-div").slideDown();
		}
		else{
			$(".pw-div").slideUp();
		}
	});
});
</script>
<div class="main">
	<div class="_container">
		<ul class="breadcrumb">
			<li><a href="/"><?php echo lang("menu.home");?></a></li>
			<li>
			<?php foreach($mainmenu as $val){
				if($val->type=="enquire") echo $val->title;
			}?>		
			</li>
			<li class="active"><?php echo lang("qna_title");?></li>
		</ul>
		<div class="row margin-bottom-40">
			<div class="col-md-12 col-sm-12">
				<h1 class="margin-bottom-20"><?php echo lang("qna_title");?></h1>
				<ul class="nav nav-tabs">
					<li><a class="section_tab" href="/member/enquire"><?php echo lang("enquire.title");?></a></li>
					<li class="active"><a class="section_tab" href="/ask/index"><?php echo lang("qna_title");?></a></li>
				</ul>
			</div>
			<!-- BEGIN CONTENT -->
			<div class="col-md-12 col-sm-12">
				<div class="content-form-page">
					<?php echo form_open("/ask/add_action","id='ask_form' class='form-horizontal form-without-legend' role='form'");?>		
						<?php if($config->ASK_TYPE){?>
						<div class="form-group">
						  <label class="col-lg-2 control-label">공개여부 <span class="require">*</span></label>
							<div class="controls" style="padding-top:5px;padding-left:10px;">&nbsp;&nbsp;
								<label class="strip"><input type="radio" name="open" value="Y"/> 공개</label>
								<label class="strip"><input type="radio" name="open" value="N" checked="checked"/> 비공개</label>
							</div>
						</div>
						<div class="form-group pw-div">
							<label class="col-lg-2 control-label"><?php echo lang("site.pw");?> <span class="require">*</span></label>
							<div class="col-lg-10">
								<input type="password" class="form-control" name="pw"/>
							</div>
						</div>
						<?php }?>
						<div class="form-group">
							<label class="col-lg-2 control-label"><?php echo lang("site.title");?> <span class="require">*</span></label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="title"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label"><?php echo lang("site.name");?> <span class="require">*</span></label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="name" placeholder="<?php echo lang("site.name");?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label"><?php echo lang("site.tel");?> <span class="require">*</span></label>
							<div class="col-lg-10">
								<input class="form-control" name="phone"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label"><?php echo lang("site.email");?></label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="email"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label"><?php echo lang("site.content");?></label>
							<div class="col-lg-10">
								<textarea class="form-control" rows="10" id="content" name="content" target-dialog="upload_dialog"></textarea>
							</div>
						</div>
						<div class="pull-right margin-top-10 margin-bottom-30">
							<?php if($config->ASK_TYPE){?>
							<button type="button" class="btn btn-default btn-lg" onclick="location.href='/ask/index'"><i class="icon-ok"></i> 목록</button>
							<?php }?>
							<button type="submit" class="btn btn-primary btn-lg"><i class="icon-ok"></i> 보내기</button>
						</div>
						<script>
							CKEDITOR.replace('content', {customConfig: '/ckeditor/agent_config.js'});
						</script>
					<?php echo form_close();?>
				</div>
			</div>
			<!-- END CONTENT -->		
		</div>
		<!-- END SIDEBAR & CONTENT -->
	</div>
</div>

<div id="upload_dialog" title="이미지업로드" style="display:none;">
	<?php echo form_open_multipart("ask/upload_action","id='upload_form' autocomplete='off'");?>
	<div class="help-block">* 큰 이미지는 넓이(폭)이 500픽셀로 조정됩니다.</div>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
	<?php echo form_close();?>
</div>