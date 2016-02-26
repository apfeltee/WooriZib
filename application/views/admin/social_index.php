<style>
tr{
	height:50px;
}
th, td{
	vertical-align:middle !important;
}
th {
	text-align:right;
	padding:10px;
	width:300px;
}
</style>
<?php echo form_open("adminhome/social_link_edit","id='social_form'");?>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">소셜링크<small>설정</small></h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i></li>
				<li>나의 소셜링크	<a href="https://sites.google.com/site/dungzimanual/5-hwal-yong/sosyeollingkeu" target="_blank"><i class="fa fa-question-circle"></i></a></li>
			</ul>
			<div class="page-toolbar">
				<button type="submit" class="btn blue">저장하기</button>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-8">
		<div class="help-block">* 홈페이지 하단에 등록한 소셜 링크가 표시됩니다. <a href="https://sites.google.com/site/dungzimanual/5-hwal-yong/sosyeollingkeu" target="_blank">매뉴얼</a>을 보시고 정확하게 입력해 주세요.</div>
		<div class="help-block">* 주소 입력시 앞에 http://나 https://는 생략해 주세요.</div>
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th>네이버카페</th>
					<td>
						<input type="text" name="naver_cafe" class="form-control" value="<?php echo isset($query->naver_cafe) ? $query->naver_cafe : "";?>" maxlength="100"/>
					</td>
				</tr>
				<tr>
					<th>네이버블로그</th>
					<td>
						<input type="text" name="naver_blog" class="form-control" value="<?php echo isset($query->naver_blog) ? $query->naver_blog : "";?>" maxlength="100"/>
					</td>
				</tr>
				<tr>
					<th>페이스북</th>
					<td>
						<input type="text" name="facebook" class="form-control" value="<?php echo isset($query->facebook) ? $query->facebook : "";?>" maxlength="100"/>
					</td>
				</tr>
				<tr>
					<th>트위터</th>
					<td>
						<input type="text" name="twitter" class="form-control" value="<?php echo isset($query->twitter) ? $query->twitter : "";?>" maxlength="100"/>
					</td>
				</tr>
				<tr>
					<th>구글플러스</th>
					<td>
						<input type="text" name="google_plus" class="form-control" value="<?php echo isset($query->google_plus) ? $query->google_plus : "";?>" maxlength="100"/>
					</td>
				</tr>
				<tr>
					<th>유투브채널</th>
					<td>
						<input type="text" name="youtube_channel" class="form-control" value="<?php echo isset($query->youtube_channel) ? $query->youtube_channel : "";?>" maxlength="100"/>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php echo form_close();?>