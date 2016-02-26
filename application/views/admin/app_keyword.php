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


<div class="row">
	<div class="col-lg-12">
		<textarea style="width:100%;height:300px;">매매,분양,월세,전세,<?php foreach($query as $val){echo $val->dong.",";} foreach($category as $val2){echo $val2->name.",";}?> <?php foreach($query as $val){echo $val->gugun . " " . $val->dong . "," ;} ?></textarea>
	</div>
</div>

