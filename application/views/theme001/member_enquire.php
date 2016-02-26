<link href="/assets/admin/css/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/admin/css/jquery-ui.theme.css">
<script src="/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="/ckeditor/bootstrap-ckeditor-fix.js"></script>
<script src="/assets/plugin/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {	

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	$("#enquire_form").ajaxForm({
		beforeSubmit:function(){
			$("#enquire_form").validate({ 
				rules: {
					pw: {  
						required: true
					},
					enName: {  
						required: true,  
						minlength: 2
					},
					enPhone: {  
						required: true,  
						minlength: 9
					}
				},  
				messages: {
					pw: {  
						required: "<?php echo lang("form.required");?>"
					},					
					enName: {  
						required: "<?php echo lang("form.required");?>",  
						minlength: "<?php echo lang("form.2");?>"
					},
					enPhone: {  
						required: "<?php echo lang("form.required");?>",  
						minlength: "<?php echo lang("form.9");?>"
					}
				} 
			});
			if (!$("#enquire_form").valid()) return false;
		},
		success:function(data){
			if(data=="1"){
				$("#enquire_form")[0].reset();
				$('body, html').animate({scrollTop:0}, 500);
				CKEDITOR.instances.enContent.setData("");
				msg($("#msg"), "success" ,"<?php echo lang("form.success");?>");
			} else {
				$('body, html').animate({scrollTop:0}, 500);
				msg($("#msg"), "danger" ,"<?php echo lang("form.fail");?>");
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
				CKEDITOR.instances.enContent.insertHtml( "<img src='"+data+"'>" );
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
			<li class="active"><?php echo lang("enquire.title");?></li>
		</ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
		  <div class="col-md-12 col-sm-12">
			<h1 class="margin-bottom-20"><?php echo lang("enquire.title");?></h1>
			<ul class="nav nav-tabs">
				<li class="active"><a class="section_tab" href="/member/enquire"><?php echo lang("enquire.title");?></a></li>
				<li><a class="section_tab" href="/ask/index"><?php echo lang("qna_title");?></a></li>
			</ul>
		  </div>
          <!-- BEGIN CONTENT -->
          <div class="col-md-12 col-sm-12">
            <div class="content-form-page">
              <div class="row">
				  <div id="msg"></div>				  			
				  <?php echo form_open("member/enquire_action","id='enquire_form' class='form-horizontal form-without-legend' role='form'");?>			
				  <input type="hidden" name="open" value="N">
				  <?php if($config->ENQUIRE_SELL){?>
                    <div class="form-group">
                      <label class="col-lg-2 control-label"><?php echo lang("site.type");?> <span class="require">*</span></label>
  						<div class="controls">
							<label class="strip"><input type="radio" name="enGubun" value="buy" checked="checked"/> <?php echo lang("site.buying");?></label>
							<label class="strip"><input type="radio" name="enGubun" value="sell" <?php echo ($enGubun=='sell') ? "checked" : "";?>/> <?php echo lang("site.selling");?></label>
						</div>
                    </div>
					<?php }else {?>
					<input type="hidden" name="enGubun" value="buy"/>
					<?php }?>
					<?php if($config->ENQUIRE_TYPE){?>
                    <div class="form-group pw-div">
                      <label class="col-lg-2 control-label"><?php echo lang("site.pw");?> <span class="require">*</span></label>
                      <div class="col-lg-10">
                        <input type="password" class="form-control" name="pw"/>
                      </div>
                    </div>
					<?php }?>
					<div class="form-group">
                      <label class="col-lg-2 control-label"><?php echo lang("site.name");?> <span class="require">*</span></label>
                      <div class="col-lg-10">
                        <input type="text" class="form-control" name="enName" placeholder="<?php echo lang("site.name");?>"/>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-lg-2 control-label"><?php echo lang("site.tel");?> <span class="require">*</span></label>
                      <div class="col-lg-10">
                        <input type="text" class="form-control" name="enPhone"/>
                      </div>
                    </div>
					<?php if($config->INSTALLATION_FLAG!="2"){?>
					<div class="form-group">
                      <label class="col-lg-2 control-label"><?php echo lang("product.type");?></label>
                      <div class="col-lg-10">
						<select class="form-control" id="enType" name="enType">
							<option value=""><?php echo lang("site.all");?></option>
							<?php if(lang('sell')!=""){?><option value="sell"><?php echo lang('sell');?></option><?php }?>
							<?php if(lang('full_rent')!=""){?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
							<?php if(lang('monthly_rent')!=""){?><option value="monthly_rent"><?php echo lang('monthly_rent');?></option><?php }?>
							<?php if($config->INSTALLATION_FLAG=="1"){?><option value="installation"><?php echo lang('installation');?></option><?php }?>
						</select>
                      </div>
                    </div>
					<?php }else{?>
					  <input type="hidden" name="enType" value="installation"/>
					<?php }?>
					 <div class="form-group">
						<label class="col-lg-2 control-label"><?php echo lang("product.category");?></label>
						<div class="col-lg-10">
							<div class="controls" style="padding-top:5px;">
						  <?php foreach($category as $val){ ?>
								<label class="strip"><input name="enCategory[]" value="<?php echo $val->id;?>" type="checkbox"> <?php echo $val->name;?></label>
							<?php }?>
							</div>
						</div>
					 </div>

                    <div class="form-group">
                      <label class="col-lg-2 control-label"><?php echo lang("enquire.hopearea");?></label>
                      <div class="col-lg-10">
                        <input type="text" class="form-control" name="enLocation" placeholder="<?php echo lang("enquire.hopearea");?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-lg-2 control-label"><?php echo lang("enquire.movedate");?></label>
                      <div class="col-lg-10">
                        <input type="text" class="form-control" name="movedate" placeholder="<?php echo lang("enquire.movedate");?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-lg-2 control-label"><?php echo lang("enquire.visit");?></label>
                      <div class="col-lg-10">
                        <input type="text" class="form-control" name="visitdate" placeholder="<?php echo lang("enquire.visit");?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-lg-2 control-label"><?php echo lang("enquire.price");?></label>
                      <div class="col-lg-10">
                        <input type="text" class="form-control" name="enPrice" placeholder="<?php echo lang("enquire.price");?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-lg-2 control-label"><?php echo lang("product.area");?></label>
                      <div class="col-lg-10">
                        <input type="text" class="form-control" name="enArea" placeholder="<?php echo lang("product.area");?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-lg-2 control-label"><?php echo lang("enquire.etcrequest");?></label>
                      <div class="col-lg-10">
						  <textarea class="form-control" id="enContent" name="enContent" rows="5" target-dialog="upload_dialog"></textarea>
                      </div>
                    </div>
                    <div class="row">
					  <label class="col-lg-2 control-label"></label>
                      <div class="col-lg-10 padding-top-20 text-center">
                        <button type="submit" class="btn btn-primary"><?php echo lang("site.submit");?></button>
                      </div>
                    </div>
					<script>
						CKEDITOR.replace('enContent', {customConfig: '/ckeditor/agent_config.js'});
					</script>
                  <?php echo form_close();?>
                </div>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
	</div>
</div>

<div id="upload_dialog" title="이미지업로드" style="display:none;">
	<?php echo form_open_multipart("member/upload_action","id='upload_form' autocomplete='off'");?>
	<div class="help-block">* 큰 이미지는 넓이(폭)이 500픽셀로 조정됩니다.</div>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
	<?php echo form_close();?>
</div>