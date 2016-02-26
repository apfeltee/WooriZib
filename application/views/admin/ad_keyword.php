 <script>

$(document).ready(function(){

	 $("textarea").focus(function() {
	    var $this = $(this);
	    $this.select();

	    // Work around Chrome's little problem
	    $this.mouseup(function() {
	        // Prevent further mouseup intervention
	        $this.unbind("mouseup");
	        return false;
	    });
	});

});
</script>

 <div class="row">
  <div class="col-lg-12">
	<h3 class="page-title">
			키워드<small>목록</small>
	</h3>
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<a href="/adminhome/index"><?php echo lang("menu.home");?></a>
				<i class="fa fa-angle-right"></i> 키워드 목록
			</li>
			<!--li>
				<a href="#">Dashboard</a>
			</li-->
		</ul>
		<!--div class="page-toolbar">
			<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-salt" data-placement="top" data-original-title="Change dashboard date range">
				<i class="icon-calendar"></i>&nbsp;
				<span class="thin uppercase visible-lg-inline-block">December 6, 2014 - January 4, 2015</span>&nbsp;
				<i class="fa fa-angle-down"></i>
			</div>
		</div-->
	</div>
	<!--div class="alert alert-success alert-dismissable">
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	  Welcome to SB Admin by <a class="alert-link" href="http://startbootstrap.com">Start Bootstrap</a>! Feel free to use this template for your admin needs! We are using a few different plugins to handle the dynamic tables and charts, so make sure you check out the necessary documentation links provided.
	</div-->
  </div>
</div><!-- /.row -->


<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN Portlet PORTLET-->
		<div class="portlet box yellow">
			<div class="portlet-title">
				<div class="caption">
					1단계 : 광고주 가입
				</div>
				<div class="actions"></div>
			</div>
			<div class="portlet-body">
				<h3 class="block">검색 광고 사이트에 광고주로 회원가입을 합니다.</h3>
				<div class="help-block">* 랜딩페이지는 검색 키워드와 도달페이지간의 연관성을 높여서 반송률(Bounce Rate)을 낮추기 위한 기능입니다..</div>
				<div class="help-block">* 중개매물을 광고하기 위해서는 중개업등록번호가 기재되어 있어야 합니다.</div>
				<table class="table table-bordered">
					<thead>
				 		<tr>
				 			<th width="20%">광고사이트</th><th>사이트 주소</th>
				 		</tr>
				 	</thead>
				 	<tbody>
					 	<tr>
					 		<td width="20%">네이버 검색광고</td><td><a href="http://searchad.naver.com/" target="_blank">searchad.naver.com</a></td>
					 	</tr>
					 	<tr>
					 		<td width="20%">다음 검색광고</td><td><a href="http://adnetworks.biz.daum.net/top/index.do" target="_blank">adnetworks.biz.daum.net</a></td>
					 	</tr>										 	
					 </tbody>
				</table>
			</div>
		</div>
		<!-- END Portlet PORTLET-->

		<!-- BEGIN Portlet PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					2단계 : 광고 등록
				</div>
				<div class="actions"></div>
			</div>
			<div class="portlet-body">
				<h3 class="block">가입후 로그인하여 광고를 등록합니다.</h3>
				<div class="help-block">* 경쟁이 적어 가격이 저렴한 광고들은 기본적으로 광고 진행합니다.</div>
				<div class="help-block">* 자동 생성에 들어갔으면 하는 키워드는 얘기해 주시면 반영해 드리겠습니다.</div>
				<div class="help-block">* 경쟁이 심해 광고 단가가 높은 키워드들은 광고 효율을 보면서 광고를 진행합니다.</div>
				<div class="help-block">* URL은 광고 랜딩주소이며 텍스트영역은 광고 키워드입니다. 광고키워드를 클릭하신 후 <code>Ctrl+C</code>로 복사하시어 광고 키워드로 등록해 주세요.</div>
				<br/>

<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#step_1" aria-controls="step_1" role="tab" data-toggle="tab">지역+<?php echo lang("product.type");?> 조합</a></li>
    <li role="presentation"><a href="#step_2" aria-controls="step_2" role="tab" data-toggle="tab">지역 + <?php echo lang("product.category");?> 조합</a></li>
    <li role="presentation"><a href="#step_3" aria-controls="step_3" role="tab" data-toggle="tab">단지</a></li>
	<li role="presentation"><a href="#step_4" aria-controls="step_4" role="tab" data-toggle="tab"><?php echo lang("product.theme");?></a></li>
  </ul>

<?php
$type = Array(
	"all" => "",
	"installation"=>" 분양",
	"sell"=>"매매",
	"full_rent"=>" 전세",
	"monthly_rent"=>" 월세"
	);
?>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="step_1">
		<!-- step 1 start -->
		<div class="row">
			<div class="col-md-8">
						<div class="help-block">* <?php echo count($query) * count($type) * 3; ?>개의 키워드가 추출되었습니다. </div>
						<table class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th><code>광고 랜딩 주소로 사용</code></th>
									<th><code>Ctrl+C</code> -> <code>광고 키워드로 사용</code></th>
								</tr>
							</thead>
							<tbody id="search-items">
								<?php 

									foreach($query as $val){
										foreach($type as $k=>$t){	
											if($k!="all"){
									?>
									<tr>
										<td width="60%">http://<?php echo HOST?>/search/direct/address/<?php echo $val->id?>/<?php echo $k?></td>
										<td width="40%">
											<textarea class="form-control" style="height:80px;"><?php 
												
													echo $val->sido . " " . $val->dong . $t . "\n" ;
													echo $val->gugun . " " . $val->dong . $t . "\n";
													echo $val->dong .$t. "\n";
												
												
											?></textarea>
										</td>
									</tr>
									<?php 
											}
										}
									}?>
							</tbody>
						</table>
			</div>
		</div>
		<!-- step 1 end -->
	</div>
    <div role="tabpanel" class="tab-pane" id="step_2">
		<!-- step 2 start -->
		<div class="row">
			<div class="col-md-8">
						<div class="help-block">* <?php echo count($query) * count($category) * count($type) * 4; ?>개의 키워드가 추출되었습니다. </div>
						<table class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th><code>광고 랜딩 주소로 사용</code></th>
									<th><code>Ctrl+C</code> -> <code>광고 키워드로 사용</code></th>
								</tr>
							</thead>
							<tbody id="search-items">
								<?php 

									foreach($query as $val){
										foreach($category as $c){
											foreach($type as $k=>$t){
									?>
									<tr>
										<td width="60%">http://<?php echo HOST?>/search/direct/address/<?php echo $val->id?>/<?php echo $k?>/<?php echo $c->id;?></td>
										<td width="40%">
											<textarea class="form-control" style="height:80px;"><?php 

												$name=explode("/",$c->name); //매물 유형을 / 기호로 나눠 쓰는 경우가 있다. 그래서 이렇게 explode를 한다.
												foreach($name as $key=>$val3){
													if($key==0){
														echo $val->sido . " " . $val3  . $t . "\n";
														echo $val->sido . " " . $val->gugun . " " . $val3 . $t . "\n";
													}

													echo $val->gugun . " " . $val->dong . " " . $val3 . $t . "\n";

													echo $val->dong . " " . $val3 . $t . "\n";
												}
												
											?></textarea>
										</td>
									</tr>
									<?php 
											}
										}
									}?>
							</tbody>
						</table>
			</div>
		</div>
		<!-- step 2 end -->
	</div>
    <div role="tabpanel" class="tab-pane" id="step_3">
		<!-- step 3 start -->
		<div class="row">
			<div class="col-md-8">
						<div class="help-block">* <?php echo count($danzi); ?>개의 키워드가 추출되었습니다. </div>
						<table class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th><code>광고 랜딩 주소로 사용</code></th>
									<th><code>Ctrl+C</code> -> <code>광고 키워드로 사용</code></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($danzi as $val){?>
								<tr>
									<td>http://<?php echo HOST?>/search/direct/danzi/<?php echo $val->id?></td>
									<td><textarea class="form-control" rows="2"><?php echo $val->dong?> <?php echo $val->name?></textarea></td>
								</tr>
								<?php }?>
							</tbody>
						</table>
			</div>
		</div>
		<!-- step 3 end -->
	</div>
    <div role="tabpanel" class="tab-pane" id="step_4">
		<!-- step 4 start -->
		<div class="row">
			<div class="col-md-8">
						<div class="help-block">* <?php echo count($theme); ?>개의 키워드가 추출되었습니다. </div>
						<table class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th><code>광고 랜딩 주소로 사용</code></th>
									<th><code>Ctrl+C</code> -> <code>광고 키워드로 사용</code></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($theme as $val){
								?>
								<tr>
									<td>http://<?php echo HOST?>/search/direct/theme/<?php echo $val->id?></td>
									<td><textarea class="form-control" rows="2"><?php echo $val->theme_name?></textarea></td>
								</tr>
								<?php 
								}?>
							</tbody>
						</table>
			</div>
		</div>
		<!-- step 4 end -->
	</div>
  </div>

</div>
				
<!-- END Portlet PORTLET-->
