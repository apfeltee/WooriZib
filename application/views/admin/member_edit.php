<script>
$(document).ready(function(){

	$('.help').tooltip(); 

	$("#member_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			name: {  
				required: true,  
				minlength: 3
			}
		},  
		messages: {  
			name: {  
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
				<?php echo lang("site.contact");?> <small>수정</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i> 
					<a href="/adminhome/index"><?php echo lang("menu.home");?></a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="/adminproduct/index"><?php echo lang("site.contact");?> 관리</a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					수정
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="history.go(-1);"><?php echo lang("site.back");?></button>
			</div>
		</div>
	</div>
</div><!-- /.row -->

<?php echo form_open("adminmember/edit_action","id='member_form' class='form-horizontal'");?>
<input type="hidden" name="id" value="<?php echo $query->id;?>">
<div class="portlet box green">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-user"></i> <?php echo lang("site.information");?>
			</div>
			<div class="tools">
				이름, 회사, 직책 정보를 입력합니다.
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">

				<div class="form-group">
					<label class="col-md-3 control-label">이름 <span class="required" aria-required="true"> * </span></label>
					<div class="col-md-9">
						<input type="text" name="name" class="form-control input-inline input-small" placeholder="이름: 홍길동" value="<?php echo $query->name;?>"/>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-md-3 control-label">회사</label>
					<div class="col-md-9">
						<input type="text" name="organization" class="form-control input-inline input-small" placeholder="회사" maxlength="100" value="<?php echo $query->organization;?>"/>
						<input type="text" name="role" class="form-control input-inline input-small" placeholder="직책" maxlength="100" value="<?php echo $query->role;?>"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">홈페이지 </label>
					<div class="col-md-9">
						<input type="text" name="homepage" class="form-control input-inline input-large" placeholder="www.example.com" autocomplete="off" value="<?php echo $query->homepage;?>"/>
					</div>
				</div>	
			</div> <!-- form-body -->
		</div> <!-- portlet-body -->
	</div><!-- portlet -->


<div class="portlet box red">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-phone-square"></i> 연락 상세 정보
			</div>
			<div class="tools">
				전화번호, <?php echo lang("site.email");?>, 주소 등의 정보를 입력합니다.
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang("site.email");?></label>
					<div class="col-md-9">
						<input type="text" name="email" class="form-control input-inline input-large" placeholder="user@example.com" value="<?php echo $query->email;?>"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">휴대번호</label>
					<div class="col-md-9">
						<input type="text" name="phone" class="form-control input-inline input-large" placeholder="010-000-0000" value="<?php echo $query->phone;?>"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">유선번호</label>
					<div class="col-md-9">
						<input type="text" name="tel" class="form-control input-inline input-large" placeholder="02-000-0000" value="<?php echo $query->tel;?>"/>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-md-3 control-label">성별</label>
					<div class="col-md-9">
						<label class="radio-inline">
						<input type="radio" name="sex" id="sex1" value="M" <?php if( $query->sex == "M") {echo "checked";}?>> 남자 </label>
						<label class="radio-inline">
						<input type="radio" name="sex" id="sex2" value="F" <?php if( $query->sex == "F") {echo "checked";}?>> 여자 </label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">집주소</label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="address_home" value="<?php echo $query->address_home;?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">회사주소</label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="address_office" value="<?php echo $query->address_office;?>">
					</div>
				</div>
			</div> <!-- form-body -->
		</div> <!-- portlet-body -->
	</div><!-- portlet -->

<div class="portlet box green">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-pencil-square"></i> 담당 및 배경
			</div>
			<div class="tools">
				담당자 정보와 배경 정보
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<div class="form-group">
					<label class="col-md-3 control-label">배경설명</label>
					<div class="col-md-9">
						<textarea name="background" class="form-control" rows="5"><?php echo $query->background;?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang("product.owner");?> <span class="required" aria-required="true"> * </span></label>
					<div class="col-md-9">
						<select name="member_id" class="form-control select2me">
							<?php foreach($members as $val) {?>
								<option value="<?php echo $val->id?>" <?php if($val->id==$query->member_id){echo "selected";}?>><?php echo $val->name?> (<?php echo $val->email?>, <?php echo $val->phone?>)</option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">공개</label>
					<div class="col-md-9">
						<label class="radio-inline">
						<input type="radio" name="is_opened" value="1" <?php if( $query->is_opened == "1") {echo "checked";}?>> 전체 공개 </label>
						<label class="radio-inline">
						<input type="radio" name="is_opened" value="0" <?php if( $query->is_opened == "0") {echo "checked";}?>> 담당자만 상세 정보를 볼 수 있도록 </label>
					</div>
				</div>
			</div> <!-- form-body -->
			<div class="form-actions right">
				<button type="submit" class="btn blue"> 수정</button>
			</div>
		</div> <!-- portlet-body -->
		<?php echo form_close();?>
</div><!-- portlet -->
