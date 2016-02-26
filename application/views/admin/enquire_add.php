<script>
$(document).ready(function(){	
	$("#add_form").validate({  
		errorElement: "span",
		wrapper: "span",  
		rules: {
			name: {  
				required: true,  
				minlength: 2
			},
			phone: {  
				required: true,
				minlength:8
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "최소 2자리 이상입니다"
			},
			phone: {  
				required: "<?php echo lang("form.required");?>",
				minlength:"최소 8자리 이상입니다"
			}
		} 
	});

});
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			<?php echo lang("enquire.title");?> <small>등록</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li><?php echo lang("enquire.title");?> 관리 <i class="fa fa-angle-right"></i> </li>
				<li><?php echo lang("enquire.title");?> <small>등록</small></li>
			</ul>
			<div class="page-toolbar">
				<button class="btn red" onclick="history.go(-1);"><?php echo lang("site.back");?></button>
			</div>
		</div>
	</div>
</div>

<?php echo form_open("adminenquire/add_action",Array("id"=>"add_form","class"=>"form-horizontal"))?>
<div class="portlet box green">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-map-marker"></i> 구분 및 단계
		</div>
		<div class="tools">
			구분 및 단계를 정의합니다.
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-3 control-label">구분</label>
				<div class="col-md-9">
					<select class="form-control input-inline input-small select2me" name="gubun">
						<option value="buy">구해요(매수)</option>
						<option value="sell">팔아요(매도)</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">단계</label>
				<div class="col-md-9">
					<select  class="form-control input-inline input-small select2me" name="status" autocomplete="off">
					<?php foreach($status_category as $val){?>
						<option value="<?php echo $val->name?>"><?php echo $val->label?></option>
					<?php }?>
					</select>
				</div>
			</div>
			
		</div> <!-- form-body -->
	</div> <!-- portlet-body -->
</div><!-- portlet -->

<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-map-marker"></i> 고객정보
		</div>
		<div class="tools">
			고객에 대한 정보입니다.
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-3 control-label">이름 *</label>
				<div class="col-md-9">
					<input type="text" class="form-control input-inline" name="name" placeholder="이름" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">특징 <i class="fa fa-lock"></i></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="feature" placeholder="특징 (이름만 입력하면 나중에 까먹을 수가 있어요~)" autocomplete="off">
				</div>
			</div>			
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("site.tel");?> *</label>
				<div class="col-md-9">
					<input type="text" class="form-control input-inline" name="phone" placeholder="<?php echo lang("site.tel");?>" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">기타전화1</label>
				<div class="col-md-9">
					<input type="text" class="form-control input-inline" name="phone_etc1" placeholder="기타전화1" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">기타전화2</label>
				<div class="col-md-9">
					<input type="text" class="form-control input-inline" name="phone_etc2" placeholder="기타전화2" autocomplete="off">
				</div>
			</div>
			
		</div> <!-- form-body -->		
	</div> <!-- portlet-body -->
</div><!-- portlet -->

<div class="portlet box red">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-map-marker"></i> 부동산 정보
		</div>
		<div class="tools">
			부동산에 대한 정보입니다.
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("enquire.hopearea");?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="location" placeholder="<?php echo lang("enquire.hopearea");?>" autocomplete="off">
				</div>
			</div>
			<?php if($config->INSTALLATION_FLAG!="2"){?>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("product.type");?></label>
				<div class="col-md-9">
					<?php if($config->INSTALLATION_FLAG=="1"){?><label  class="radio-inline"><input type="radio" name="type" value="installation"> <?php echo lang('installation');?></label ><?php }?>
					<?php if(lang('sell')!=""){?><label  class="radio-inline"><input type="radio" name="type" value="sell" checked> <?php echo lang('sell');?></label ><?php }?>
					<?php if(lang('full_rent')!=""){?><label  class="radio-inline"><input type="radio" name="type" value="full_rent"> <?php echo lang('full_rent');?></label ><?php }?>
					<?php if(lang('monthly_rent')!=""){?><label  class="radio-inline"><input type="radio" name="type" value="monthly_rent"> <?php echo lang('monthly_rent');?></label ><?php }?>
				</div>
			</div>
			<?php }?>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("search.type");?></label>
				<div class="col-md-9">
					<?php foreach($category as $val){?>
						<label style="margin-right:20px;"><input type="checkbox" name="category[]" value="<?php echo $val->id;?>" autocomplete="off"><?php echo $val->name;?> </label>
					<?php }?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("enquire.price");?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="price" placeholder="<?php echo lang("enquire.price");?>" autocomplete="off">
				</div>
			</div>
			
		</div> <!-- form-body -->						
	</div> <!-- portlet-body -->
</div><!-- portlet -->


<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-map-marker"></i> 설명
		</div>
		<div class="tools">
			처리내용은 수정 시 히스토리로 저장됩니다.
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("enquire.movedate");?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="movedate" placeholder="<?php echo lang("enquire.movedate");?>" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("enquire_visit");?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="visitdate" placeholder="<?php echo lang("enquire_visit");?>" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("site.content");?></label>
				<div class="col-md-9">
					<div class="row">
						<div class="col-md-6">
							<div class="help-block">* <?php echo lang("site.content");?></div>
							<textarea class="form-control" id="add_content" name="content" placeholder="내용" autocomplete="off"></textarea>
							<script>
								CKEDITOR.replace('add_content', {customConfig: '/ckeditor/simple_config.js'});
							</script>
						</div>
						<div class="col-md-6">
							<div class="help-block">* 답변내용(고객에게 보여지므로 비밀사항은 관리메모에 입력)</div>
							<textarea class="form-control" id="add_work" name="work" rows="2" placeholder="담당자 작성 처리 내역"></textarea>
							<script>
								CKEDITOR.replace('add_work', {customConfig: '/ckeditor/simple_config.js'});
							</script>
						</div>
					</div>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-md-3 control-label">관리메모 <i class="fa fa-lock"></i></label>
				<div class="col-md-9">
					<div class="help-block">* 고객에게 보이지 않으며 수정시 변경이력을 볼 수 있습니다.</div>
					<textarea class="form-control" name="secret" title="관리자들만 볼 수 있음"></textarea>
				</div>
			</div>			
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("product.owner");?> <span class="required" aria-required="true"> * </span></label>
				<div class="col-md-9">
					<select name="member_id" class="form-control input-xlarge select2me" autocomplete="off">
						<?php foreach($members as $val) {?>
							<option value="<?php echo $val->id?>" <?php if($val->id==$this->session->userdata("admin_id")){echo "selected";}?>><?php echo $val->name?> (<?php echo $val->email?>, <?php echo $val->phone?>)</option>
						<?php } ?>
					</select>
				</div>
			</div>		
		</div> <!-- form-body -->
		<div class="form-actions right">
			<button type="submit" class="btn blue regist_btn"><?php echo lang("site.submit");?></button>
		</div>		
	</div> <!-- portlet-body -->
</div><!-- portlet -->
<?php echo form_close();?>
