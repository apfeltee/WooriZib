<script>
$(document).ready(function(){
	
	$('.help').tooltip(); 

	$(".regist_btn").click(function(){
		$("#data-next").val($(this).attr("data-next"));
		$("#contact_form").trigger("submit");
	});

	$("#contact_form").validate({  
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

	$("#add_email").click(function(e){
		e.preventDefault();
		$("#email_section").append('<div class="multi-form-control-wrapper"><select name="email_type[]" class="form-control input-small input-inline" autocomplete="off"><option value="work">업무용</option><option value="personal">개인용</option></select><input type="text" name="email[]" class="form-control input-inline input-xlarge" placeholder="user@example.com"/><button type="button" class="input_delete btn btn-link"><i class="fa fa-times"></i></button></div>');

		$(".input_delete").on("click",function(e){
			e.preventDefault(); $(this).parent('div').remove();
		})
	});

	$("#add_phone").click(function(e){
		e.preventDefault();
		$("#phone_section").append('<div class="multi-form-control-wrapper"><select name="phone_type[]" class="form-control input-small input-inline" autocomplete="off"><option value="mobile">휴대전화</option><option value="home">집전화</option><option value="office">사무실전화</option><option value="fax">팩스전화</option><option value="etc">기타전화</option></select><input type="text" name="phone[]" class="form-control input-inline input-xlarge" placeholder="휴대폰, 일반전화, 팩스 등" autocomplete="off"/><button type="button" class="input_delete btn btn-link"><i class="fa fa-times"></i></button></div>');

		$(".input_delete").on("click",function(e){
			e.preventDefault(); $(this).parent('div').remove();
		})
	});

	$("#add_address").click(function(e){
		e.preventDefault();
		$("#address_section").append('<div class="multi-form-control-wrapper"><select name="address_type[]" class="form-control input-small input-inline" autocomplete="off"><option value="work">직장</option><option value="home">집</option></select><input type="text" name="address[]" class="form-control input-inline input-xlarge" placeholder="주소"/><button type="button" class="input_delete btn btn-link"><i class="fa fa-times"></i></button></div>');

		$(".input_delete").on("click",function(e){
			e.preventDefault(); $(this).parent('div').remove();
		})
	});

	$("#add_homepage").click(function(e){
		e.preventDefault();
		$("#homepage_section").append('<div class="multi-form-control-wrapper"><select name="homepage_type[]" class="form-control input-small input-inline" autocomplete="off"><option value="work">직장</option><option value="personal">개인</option><option value="blog">블로그</option></select><input type="text" name="homepage[]" class="form-control input-inline input-xlarge" placeholder="www.example.com"/><button type="button" class="input_delete btn btn-link"><i class="fa fa-times"></i></button></div>');

		$(".input_delete").on("click",function(e){
			e.preventDefault(); $(this).parent('div').remove();
		})
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
					<a href="/admincontact/index"><?php echo lang("site.contact");?> 관리</a>
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

<?php echo form_open("admincontact/edit_action","id='contact_form' class='form-horizontal'");?>
<input type="hidden" name="id" value="<?php echo $query->id;?>">
<input type="hidden" id="data-next" name="data-next" value="0">
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
						<input type="text" name="name" class="form-control input-inline input-small" placeholder="이름: 홍길동" value="<?php echo $query->name;?>" autocomplete="off"/>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-md-3 control-label">회사</label>
					<div class="col-md-9">
						<input type="text" name="organization" class="form-control input-inline input-small" placeholder="회사" maxlength="100" value="<?php echo $query->organization;?>" autocomplete="off"/>
						<input type="text" name="role" class="form-control input-inline input-small" placeholder="직책" maxlength="100" value="<?php echo $query->role;?>" autocomplete="off"/>

					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">성별</label>
					<div class="col-md-9">
						<label class="radio-inline">
						<input type="radio" name="sex" id="sex1" value="M" <?php if($query->sex=="M"){echo "checked";}?> autocomplete="off"> 남자 </label>
						<label class="radio-inline">
						<input type="radio" name="sex" id="sex2" value="F" <?php if($query->sex=="F"){echo "checked";}?> autocomplete="off"> 여자 </label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">그룹</span></label>
					<div class="col-md-9">
						<select name="group_id" class="form-control input-large select2me" autocomplete="off">
							<option value="0">그룹없음</group>
							<?php foreach($group as $val) {?>
								<option value="<?php echo $val->id?>" <?php if($val->id==$query->group_id) {echo "selected";}?>><?php echo $val->group_name?></option>
							<?php } ?>
						</select>
					</div>
				</div>						
			</div> <!-- form-body -->
		</div> <!-- portlet-body -->
	</div><!-- portlet -->

<!-- 상세정보 시작-->
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
				<label class="col-md-3 control-label"><?php echo lang("site.email");?> <button type="button" id="add_email" class="btn blue btn-xs"><i class="fa fa-plus"></i></button></label>
				<div class="col-md-9" id="email_section">
					<?php 
						$lines = explode("---dungzi---",$query->email);
						foreach($lines as $key=>$line){
							if($line!=""){
								$col = explode("--type--",$line);

								?>
									<div class="multi-form-control-wrapper">
										<select name="email_type[]" class="form-control input-small input-inline" autocomplete="off">
											<option value="work" <?php if($col[0]=="work") {echo "selected";}?>>업무용</option>
											<option value="personal" <?php if($col[0]=="personal") {echo "selected";}?>>개인용</option>
										</select><input type="text" name="email[]" class="form-control input-inline input-xlarge" value="<?php echo $col[1];?>" placeholder="user@example.com" autocomplete="off"/>
										<?php 
											if($key!=0){
												?>
													<button type="button" class="input_delete btn btn-link"><i class="fa fa-times"></i></button>
												<?php
											}
										?>
									</div>
								<?php								
							}

						}

						if($query->email==""){
							?>
								<div class="multi-form-control-wrapper">
									<select name="email_type[]" class="form-control input-small input-inline" autocomplete="off">
										<option value="work">업무용</option>
										<option value="personal">개인용</option>
									</select><input type="text" name="email[]" class="form-control input-inline input-xlarge" placeholder="user@example.com" autocomplete="off"/>
								</div>
							<?php							
						}
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("site.tel");?>  <button type="button" id="add_phone" class="btn blue btn-xs"><i class="fa fa-plus"></i></button></label>
				<div class="col-md-9" id="phone_section">
					<?php 
						$lines = explode("---dungzi---",$query->phone);
						foreach($lines as $key=>$line){
							if($line!=""){
								$col = explode("--type--",$line);

								?>
									<div class="multi-form-control-wrapper">
										<select name="phone_type[]" class="form-control input-small input-inline" autocomplete="off">
											<option value="mobile" <?php if($col[0]=="mobile") {echo "selected";}?>>휴대</option>
											<option value="office" <?php if($col[0]=="office") {echo "selected";}?>>회사</option>
											<option value="home" <?php if($col[0]=="home") {echo "selected";}?>>자택</option>
											<option value="fax" <?php if($col[0]=="fax") {echo "selected";}?>>팩스</option>
											<option value="etc" <?php if($col[0]=="etc") {echo "selected";}?>>기타</option>
										</select><input type="text" name="phone[]" class="form-control input-inline input-xlarge" value="<?php echo $col[1];?>" placeholder="휴대폰, 일반전화, 팩스 등" autocomplete="off"/>
										<?php 
											if($key!=0){
												?>
													<button type="button" class="input_delete btn btn-link"><i class="fa fa-times"></i></button>
												<?php
											}
										?>
									</div>
								<?php								
							}

						}

						if($query->phone==""){
							?>
								<div class="multi-form-control-wrapper">
									<select name="phone_type[]" class="form-control input-small input-inline" autocomplete="off">
										<option value="mobile">휴대</option>
										<option value="office">회사</option>
										<option value="home">자택</option>
										<option value="fax">팩스</option>
										<option value="etc">기타</option>
									</select><input type="text" name="phone[]" class="form-control input-inline input-xlarge" placeholder="휴대폰, 일반전화, 팩스 등" autocomplete="off"/>
								</div>
							<?php							
						}
					?>					
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">홈페이지  <button type="button" id="add_homepage" class="btn blue btn-xs"><i class="fa fa-plus"></i></button></label>
				<div class="col-md-9" id="homepage_section">
					<?php 
						$lines = explode("---dungzi---",$query->homepage);
						foreach($lines as $key=>$line){
							if($line!=""){
								$col = explode("--type--",$line);

								?>
									<div class="multi-form-control-wrapper">
										<select name="homepage_type[]" class="form-control input-small input-inline" autocomplete="off">
											<option value="work" <?php if($col[0]=="work") {echo "selected";}?>>직장</option>
											<option value="personal" <?php if($col[0]=="personal") {echo "selected";}?>>개인</option>
											<option value="blog" <?php if($col[0]=="blog") {echo "selected";}?>>블로그</option>
										</select><input type="text" name="homepage[]" class="form-control input-inline input-xlarge" value="<?php echo $col[1];?>" placeholder="www.example.com" autocomplete="off"/>										
										<?php 
											if($key!=0){
												?>
													<button type="button" class="input_delete btn btn-link"><i class="fa fa-times"></i></button>
												<?php
											}
										?>
									</div>
								<?php								
							}

						}

						if($query->homepage==""){
							?>
								<div class="multi-form-control-wrapper">
									<select name="homepage_type[]" class="form-control input-small input-inline" autocomplete="off">
										<option value="work">직장</option>
										<option value="personal">개인</option>
										<option value="blog">블로그</option>
									</select><input type="text" name="homepage[]" class="form-control input-inline input-xlarge" placeholder="www.example.com" autocomplete="off"/>
								</div>
							<?php							
						}
					?>						
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">주소  <button type="button" id="add_address" class="btn blue btn-xs"><i class="fa fa-plus"></i></button></label>
				<div class="col-md-9" id="address_section">
					<?php 
						$lines = explode("---dungzi---",$query->address);
						foreach($lines as $key=>$line){
							if($line!=""){
								$col = explode("--type--",$line);

								?>
									<div class="multi-form-control-wrapper">
										<select name="address_type[]" class="form-control input-small input-inline" autocomplete="off">
											<option value="work" <?php if($col[0]=="work") {echo "selected";}?>>직장</option>
											<option value="home" <?php if($col[0]=="home") {echo "selected";}?>>자택</option>
										</select><input type="text" name="address[]" class="form-control input-inline input-xlarge" value="<?php echo $col[1];?>" placeholder="주소" autocomplete="off"/>										
										<?php 
											if($key!=0){
												?>
													<button type="button" class="input_delete btn btn-link"><i class="fa fa-times"></i></button>
												<?php
											}
										?>
									</div>
								<?php								
							}

						}

						if($query->address==""){
							?>
								<div class="multi-form-control-wrapper">
									<select name="address_type[]" class="form-control input-small input-inline" autocomplete="off">
										<option value="work">직장</option>
										<option value="home">자택</option>
									</select><input type="text" name="address[]" class="form-control input-inline input-xlarge" placeholder="주소" autocomplete="off"/>
								</div>
							<?php							
						}
					?>							
				</div>
			</div>
		</div> <!-- form-body -->
	</div> <!-- portlet-body -->
</div>
<!-- 상세정보 종료 -->

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
						<textarea name="background" class="form-control" rows="5" autocomplete="off"><?php echo $query->background;?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang("product.owner");?> <span class="required" aria-required="true"> * </span></label>
					<div class="col-md-9">
						<select name="member_id" class="form-control input-xlarge select2me" autocomplete="off">
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
						<input type="radio" name="is_opened" value="1" <?php if($query->is_opened=="1"){echo "checked";}?>> 전체 공개 </label>
						<label class="radio-inline">
						<input type="radio" name="is_opened" value="0" <?php if($query->is_opened=="0"){echo "checked";}?>> 담당자만 상세 정보를 볼 수 있도록 </label>
					</div>
				</div>
				
			</div> <!-- form-body -->
			<div class="form-actions right">
				<button type="submit" class="btn blue"><i class="fa fa-pencil-square-o"></i> 수정</button>
			</div>
			<?php echo form_close();?>
		</div> <!-- portlet-body -->
</div><!-- portlet -->
