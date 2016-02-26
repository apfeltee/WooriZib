<script>
	$(document).ready(function() {

		$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

		<?php if($this->session->userdata("id")=="general"){?>
		$("#profile_form").ajaxForm({
			beforeSubmit:function(){
				$("#profile_form").validate({ 
					rules: {
						prPw: {  
							required: true,  
							minlength: 5
						},
						prRepw: {  
							required: true,  
							minlength: 5,
							equalTo: "#profile_form input[name='prPw']"
						},
						prName: {  
							required: true,  
							minlength: 2
						},
						prPhone: {  
							required: true,  
							minlength: 9
						}
					},  
					messages: {  
						prPw: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.5");?>"
						},
						prRepw: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.5");?>",
							equalTo: "<?php echo lang("form.repwerror");?>"
						},
						prName: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.2");?>"	
						},
						prPhone: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.9");?>"
						}
					} 
				});
				if (!$("#profile_form").valid()) return false;
			},
			success:function(data){
				if(data=="1"){
					msg($("#msg"),"success","회원 정보가 수정되었습니다.");
				} else {
					msg($("#msg"),"danger","회원 정보 수정에 실패했습니다. 전화주세요.");
				}
			}
		});

		<?php } else { ?>

		$("#profile_form").ajaxForm({
			dataType:'json',
			beforeSubmit:function(){
				$("#profile_form").validate({ 
					rules: {
						prPw: {  
							required: true,  
							minlength: 5
						},
						prRepw: {  
							required: true,  
							minlength: 5,
							equalTo: "#profile_form input[name='prPw']"
						},
						biz_name: {  
							required: true,  
							minlength: 2
						},
						biz_ceo : {  
							required: true,  
							minlength: 2
						},						
						prName: {  
							required: true,  
							minlength: 2
						},
						prPhone: {  
							required: true,  
							minlength: 9
						}
					},  
					messages: {  
						prPw: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.5");?>"
						},
						prRepw: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.5");?>",
							equalTo: "<?php echo lang("form.repwerror");?>"
						},
						biz_name: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.2");?>"	
						},
						biz_ceo : {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.2");?>"	
						},								
						prName: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.2");?>"	
						},
						prPhone: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.9");?>"
						}
					} 
				});
				if (!$("#profile_form").valid()) return false;
			},
			success:function(data){
				if(data["result"]=="1"){
					msg($("#msg"),"success","회원 정보가 수정되었습니다.");
					if(data["profile"]){
						$('#profile_img_name').val(data["profile"]);
						$('#profile_img').removeClass("is-delete");
						$("#profile_img").html('<img src="/uploads/member/'+data["profile"]+'" style="width:60px;height:60px;"/>');
						$("#profile_msg").html('');
					}
					if(data["logo"]){
						$('#logo_img_name').val(data["logo"]);
						$('#logo_img').removeClass("is-delete");
						$("#logo_img").html('<img src="/uploads/member/logo/'+data["logo"]+'"/>');
						$("#logo_msg").html('');
					}
				} else {
					msg($("#msg"),"danger","회원 정보 수정에 실패했습니다. 전화주세요.");
				}
			}
		});
		<?php } ?>


		$('#profile_form').find('#delete_profile').click(function(){

			var profile_img_name = $('#profile_form').find('#profile_img_name').val();

			if(!profile_img_name || $('#profile_form').find('#profile_img').hasClass("is-delete")){
				alert("<?php echo lang("msg.nodata");?>");
				return false;
			}
			if(confirm("프로필사진이 바로 삭제 됩니다. 삭제하시겠습니까?")){
				$.ajax({
					url: "/member/delete_profile",
					type: "POST",
					success: function(data) {
						$('#profile_form').find('#profile_img').addClass("is-delete");
						$('#profile_form').find('#profile_img').html("<img src='/assets/common/img/no_human.png' style='width:60px;height:60px;'>");
						msg($('#profile_form').find("#profile_msg"), "success" ,"삭제 되었습니다.");
					}
				});		
			}
		});

		$('#profile_form').find('input[name="profile"]').change(function(e){
			msg($('#profile_form').find("#profile_msg"), "info" ,$(this).val());
		});

		$('#profile_form').find('#delete_logo').click(function(){

			var logo_img_name = $('#profile_form').find('#logo_img_name').val();

			if(!logo_img_name || $('#profile_form').find('#logo_img').hasClass("is-delete")){
				alert("<?php echo lang("msg.nodata");?>");
				return false;
			}
			if(confirm("홈페이지 로고가 바로 삭제 됩니다. 삭제하시겠습니까?")){
				$.ajax({
					url: "/member/delete_logo",
					type: "POST",
					success: function(data) {
						$('#profile_form').find('#logo_img').addClass("is-delete");
						$('#profile_form').find('#logo_img').html("등록된 홈페이지 로고가 없습니다.");
						msg($('#profile_form').find("#logo_msg"), "success" ,"삭제 되었습니다.");
					}
				});		
			}
		});

		$('#profile_form').find('input[name="logo"]').change(function(e){
			msg($('#profile_form').find("#logo_msg"), "info" ,$(this).val());
		});
	});
</script>
	<div class="main">
		<div class="_container">

		<ul class="breadcrumb">
            <li><a href="index.html"><?php echo lang("menu.home");?></a></li>
            <li><a href="#"><?php echo lang("menu.mypage");?></a></li>
            <li class="active"><?php echo lang("menu.modifyprofile");?></li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-3">
            <ul class="list-group margin-bottom-25 sidebar-menu">
			  <?php if($this->session->userdata("id")==""){?>
              <li class="list-group-item clearfix"><a href="/member/signin"><i class="fa fa-angle-right"></i> <?php echo lang("menu.login");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/signup"><i class="fa fa-angle-right"></i> <?php echo lang("menu.signup");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/search"><i class="fa fa-angle-right"></i> <?php echo lang("menu.lostpw");?></a></li>
			  <?php }?>
              <li class="list-group-item clearfix active"><a href="/member/profile"><i class="fa fa-angle-right"></i> <?php echo lang("menu.modifyprofile");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/delete"><i class="fa fa-angle-right"></i> <?php echo lang("menu.withdrawal");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/history"><i class="fa fa-angle-right"></i> <?php echo lang("site.seen");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/hope"><i class="fa fa-angle-right"></i> <?php echo lang("site.saved");?></a></li>
			  <?php if($config->BUILDING_ENQUIRE){?>
			  <li class="list-group-item clearfix"><a href="/member/building_enquire_list"><i class="fa fa-angle-right"></i> 건축물자가진단 의뢰</a></li>
			  <?php }?>
            </ul>
          </div>
          <!-- END SIDEBAR -->

          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-9">
            <h1><?php echo lang("menu.modifyprofile");?></h1>
            <div class="content-form-page">
              <div class="row">
                <div class="col-md-7 col-sm-7">
				  <div id="msg"></div>
				  <?php echo form_open("member/profile_action","id='profile_form' class='form-horizontal form-without-legend' role='form'");?>

					<?php if($query->type=="general"){?>
						<input type="hidden" name="type" value="<?php echo $query->type;?>">
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label"><?php echo lang("site.email");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="prEmail" value="<?php echo $query->email?>" readonly>
						  </div>
						</div>
						<div class="form-group">
						  <label for="password" class="col-lg-4 control-label"><?php echo lang("site.pw");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="password"  class="form-control" name="prPw">
						  </div>
						</div>
						<div class="form-group">
						  <label for="password" class="col-lg-4 control-label"><?php echo lang("site.repw");?><span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="password"  class="form-control" name="prRepw">
						  </div>
						</div>
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label"><?php echo lang("site.name");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="prName" value="<?php echo $query->name?>" placeholder="<?php echo lang("site.name");?>">
						  </div>
						</div>
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label"><?php echo lang("site.tel");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="prPhone" value="<?php echo $query->phone?>">
						  </div>
						</div>
						<div class="row">
						  <div class="col-lg-8 col-md-offset-4 padding-left-0 padding-top-20">
							<button type="submit" class="btn btn-primary"><?php echo lang("site.modify");?></button>
						  </div>
						</div>

					<?php } else { ?>
						<input type="hidden" name="type" value="<?php echo $query->type;?>">
						<input type="hidden" id="profile_img_name" name="logo_img_name" value="<?php echo $query->profile;?>">
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label"><?php echo lang("site.email");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="prEmail" value="<?php echo $query->email?>" readonly>
						  </div>
						</div>
						<div class="form-group">
						  <label for="password" class="col-lg-4 control-label"><?php echo lang("site.pw");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="password"  class="form-control" name="prPw">
						  </div>
						</div>
						<div class="form-group">
						  <label for="password" class="col-lg-4 control-label"><?php echo lang("site.repw");?><span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="password"  class="form-control" name="prRepw">
						  </div>
						</div>
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label"><?php echo lang("site.name");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="prName" value="<?php echo $query->name?>" placeholder="<?php echo lang("site.name");?>">
						  </div>
						</div>
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label"><?php echo lang("site.mobile");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="prPhone" value="<?php echo $query->phone?>">
						  </div>
						</div>
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label"><?php echo lang("site.tel");?> <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="tel" value="<?php echo $query->tel?>">
						  </div>
						</div>
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label">카카오톡아이디 <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="kakao" value="<?php echo $query->kakao?>">
						  </div>
						</div>
						<div class="form-group">
						  <label for="profile" class="col-lg-4 control-label">프로필사진 <span class="require">*</span></label>
						  <div class="col-lg-8">
						    <?php if($query->profile){?>
							<div id="profile_img"><img src="/uploads/member/<?php echo $query->profile;?>" style='width:60px;height:60px;'/></div>
							<?php } else {?>
							<div id="profile_img"><img src='/assets/common/img/no_human.png' style='width:60px;height:60px;'></div>
							<?php }?>
							<div id="profile_msg"></div>
							<div class="btn btn-default btn-file margin-top-10">사진업로드<input type="file" id="profile" name="profile"></div>
							<div id="delete_profile" class="btn btn-danger"><i class="fa fa-trash-o"></i> 사진삭제</div>
						  </div>
						</div>
						
						<h4 class="page-header">
							<?php if($config->INSTALLATION_FLAG){?>
							사업자 / 공인중개사 정보
							<?php } else {?>
							공인중개사 정보
							<?php } ?>
						</h4>						
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label">
							<?php if($config->INSTALLATION_FLAG){?>
							사업자명
							<?php } else {?>
							중개사무소명
							<?php } ?>
						   <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="biz_name" value="<?php echo $query->biz_name?>">
							<div class="help-block">* 중개업자일 경우에는 개설등록증에 기재된 명칭.</div>
						  </div>
						</div>
						<div class="form-group">
						  <label for="email" class="col-lg-4 control-label">
							<?php echo lang("site.ceo");?>
						   <span class="require">*</span></label>
						  <div class="col-lg-8">
							<input type="text" class="form-control" name="biz_ceo" value="<?php echo $query->biz_ceo?>">
						  </div>
						</div>

						<div class="form-group">
						  <label for="biz_num" class="col-lg-4 control-label"><?php echo lang("site.biznum");?> <span class="require">*</span></label>
						  <div class="col-lg-8" style="padding-top: 7px;">
							<b><?php echo $query->biz_num?></b>
						  </div>
						</div>
						<div class="form-group">
						  <label for="biz_auth" class="col-lg-4 control-label">권한/자격</label>
						  <div class="col-lg-8" style="padding-top: 7px;"><b>
								<?php if($query->biz_auth=="0") {echo "일반사업자(중개업자가 아님)";}?>
								<?php if($query->biz_auth=="1") {echo "대표공인중개사";}?>
								<?php if($query->biz_auth=="2") {echo "소속공인중개사";}?>
								<?php if($query->biz_auth=="3") {echo "중개보조원";}?></b>
						  </div>
						</div>	
						<div id="renum_zone" class="form-group" <?php if($query->biz_auth=="" || $query->biz_auth=="0") {?>style="display:none;"<?php }?>>
						  <label for="re_num" class="col-lg-4 control-label"><?php echo lang("site.renum");?> <span class="require">*</span></label>
						  <div class="col-lg-8" style="padding-top: 7px;">
							<b><?php echo $query->re_num?></b>
						  </div>
						</div>						
						<div class="form-group">
						  <label class="col-lg-4 control-label">사업자주소 <span class="require"> * </span></label>
							<div class="col-lg-8">
								<button type="button" class="btn btn-default btn-sm" onclick="get_postcode()">주소검색</button>
							</div>
						</div>							
						<div class="form-group">
						  <label class="col-lg-4 control-label"></label>
							<div class="col-lg-8">
							<input type="text" class="form-control" id="address" name="address" autocomplete="off" placeholder="사업자주소" readonly value="<?php echo $query->address?>">
							</div>
						</div>
						<div class="form-group">
						  <label class="col-lg-4 control-label"></label>
							<div class="col-lg-8">
								<input type="text" name="address_detail" class="form-control" placeholder="상세주소" value="<?php echo $query->address_detail?>"/>
							</div>
						</div>

						<div class="row">
						  <div class="col-lg-8 col-md-offset-4 padding-left-0">
							<button type="submit" class="btn btn-primary">중개 및 사업자 회원 정보 수정</button>
						  </div>
						</div>					
					<?php } ?>

                  <?php echo form_close();?>
                </div>
                <div class="col-md-4 col-sm-4 pull-right">
                  <div class="form-info">
                    <h2><em>회원</em> 정보관리</h2>
                    <p>불 필요한 정보를 요구하지 않으며 암호는 관리자도 풀 수 없는 암호화 방식으로 저장됩니다. 비밀번호를 분실할 경우 알 수 있는 방법이 없으므로 비밀번호를 초기화합니다.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->

	</div>
</div>

<!-- DAUM POST LAYER -->
<div id="layer" style="display:none;border:5px solid;position:fixed;width:420px;height:460px;left:50%;margin-left:-235px;top:50%;margin-top:-235px;overflow:hidden;-webkit-overflow-scrolling:touch;z-index:10">
<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="<?php echo lang("site.close");?>">
</div>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    var element_layer = document.getElementById('layer');

    function closeDaumPostcode() {
        element_layer.style.display = 'none';
    }

    function get_postcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                var fullAddr = data.address;
                var extraAddr = '';

                if(data.addressType === 'R'){
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }
                document.getElementById('address').value = fullAddr;
				document.getElementById('address').focus();

                element_layer.style.display = 'none';
            },
            width : '100%',
            height : '100%'
        }).embed(element_layer);

        element_layer.style.display = 'block';
    }
</script>