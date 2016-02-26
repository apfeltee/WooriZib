<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">문자 수신자 확인</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>문자 발송이력<i class="fa fa-angle-right"></i> </li>
				<li>문자 수신자 확인</li>
			</ul>
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-danger" onclick="javascript:history.back(-1);"><?php echo lang("site.back");?></button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<!-- 기본 정보 시작 -->
		<table class="table table-bordered table-striped table-condensed flip-content" style="margin:0px;">
			<tr>
				<th width="20%">발신자</th>
				<td width="80%" colspan="3">
					<?php echo $query->sms_from;?>
				</td>
			</tr>
			<tr>
				<th width="20%">메세지</th>
				<td width="80%" colspan="3">
					<?php echo $query->msg;?>
				</td>
			</tr>
			<tr>
				<th width="20%">전송타입</th>
				<td width="80%" colspan="3">
					<?php
						if($query->type=="A") echo "단문(SMS)";
						if($query->type=="C") echo "장문(LMS)";
						if($query->type=="D") echo "포토(MMS)";
					?>
				</td>
			</tr>
			<tr>
				<th width="20%">차감횟수</th>
				<td width="80%" colspan="3">
					<?php echo $query->minus_count;?>
				</td>
			</tr>
			<tr>
				<th width="20%">결과값</th>
				<td width="80%" colspan="3">
					<?php echo $query->result;?>
				</td>
			</tr>
			<tr>
				<th width="20%">발송페이지</th>
				<td width="80%" colspan="3">
					<?php
						if($query->page=="member")	echo "회원관리";
						if($query->page=="contact")	echo "고객관리";
						if($query->page=="enquire")	echo "의뢰하기";
						if($query->page=="signup")	echo "회원가입";
						if($query->page=="concern")	echo "실시간연락받기";
						if($query->page=="user_enquire") echo "의뢰접수";
						if($query->page=="confirm")	echo "SMS인증";
					?>
				</td>
			</tr>
			<tr>
				<th width="20%">발송인</th>
				<td width="80%" colspan="3">
				<?php 
				if($query->member_id){
					echo $query->member_name."(".$query->member_email.")";							
				}else{
					echo "사이트발송";
				}?>	
				</td>
			</tr>
		</table>
	</div>
	<div class="col-md-6">
		<table class="table table-bordered table-condensed flip-content" style="margin:0px;">
			<tr>
				<td><strong>수신자</strong></td>
			</tr>
			<tr>
				<td>
					<textarea class="form-control" rows="30" readonly><?php echo $query->sms_to;?></textarea>				
				</td>
			</tr>
		</table>
	</div>
</div>