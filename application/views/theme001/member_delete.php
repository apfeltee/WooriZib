<script>
$(document).ready(function() {

	$("#delete_form").validate({ 
		rules: {
			pw: {  
				required: true
			}
		},  
		messages: {  
			pw: {  
				required: "입력해 주세요"
			}
		} 
	});

	$("#delete_form").ajaxForm({
		beforeSubmit:function(){
			if(!confirm("탈퇴 하시겠습니까?")){
				return false;
			}
		},
		success:function(data){
			if(data=="1"){
				location.href="/";
			} else {
				msg($("#msg"),"danger","비밀번호가 틀립니다. 다시 입력해주세요.");
			}
		}
	});

});
</script>
<div class="main">
	<div class="_container">
		<ul class="breadcrumb">
			<li><a href="index.html"><?php echo lang("menu.home");?></a></li>
			<li><a href="#"><?php echo lang("menu.mypage");?></a></li>
			<li class="active"><?php echo lang("menu.withdrawal");?></li>
		</ul>
		<div class="row margin-bottom-40">
			<!-- BEGIN SIDEBAR -->
			<div class="sidebar col-md-3 col-sm-3">
				<ul class="list-group margin-bottom-25 sidebar-menu">
					<?php if($this->session->userdata("id")==""){?>
					<li class="list-group-item clearfix"><a href="/member/signin"><i class="fa fa-angle-right"></i> <?php echo lang("menu.login");?></a></li>
					<li class="list-group-item clearfix"><a href="/member/signup"><i class="fa fa-angle-right"></i> <?php echo lang("menu.signup");?></a></li>
					<li class="list-group-item clearfix"><a href="/member/search"><i class="fa fa-angle-right"></i> <?php echo lang("menu.lostpw");?></a></li>
					<?php }?>
					<li class="list-group-item clearfix"><a href="/member/profile"><i class="fa fa-angle-right"></i> <?php echo lang("menu.modifyprofile");?></a></li>
					<li class="list-group-item clearfix active"><a href="/member/delete"><i class="fa fa-angle-right"></i> <?php echo lang("menu.withdrawal");?></a></li>
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
				<h1>회원탈퇴</h1>
				<div class="content-form-page">
					<div class="row">
						<div class="col-md-7 col-sm-7">
							<div id="msg"></div>
							<?php echo form_open("member/delete_action","id='delete_form' class='form-horizontal form-without-legend' role='form'");?>
							<div class="form-group">
								<label class="col-lg-4 control-label"><span class="require">*</span> <?php echo lang("site.pw");?></label>
								<div class="col-lg-8">
									<input type="password" class="form-control" name="pw" maxlength="50" placeholder="<?php echo lang("site.pw");?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">탈퇴이유</label>
								<div class="col-lg-8">
									<textarea class="form-control" rows="5" name="reason" maxlength="200" placeholder="탈퇴이유가 무엇인가요?"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label"></label>
								<div class="col-lg-8 text-right">
									<button type="submit" class="btn btn-danger">회원탈퇴</button>
								</div>
							</div>
							<?php echo form_close();?>
						</div>
						<div class="col-md-4 col-sm-4 pull-right">
							<div class="form-info">
								<h2><em>회원</em> 탈퇴</h2>
								<p style="color:red">회원탈퇴시 모든 회원정보가 삭제되며 바로 로그아웃 처리 됩니다.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END CONTENT -->
	</div>
</div>