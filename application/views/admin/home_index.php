<?php 
	$this->load->helper("chart");
?>
<style>
.feeds li a {
	color:#82949A;
	text-decoration:none;
}
</style>
 <div class="row">
  <div class="col-lg-12">
	<h3 class="page-title">
			<?php echo lang("menu.home");?> <small>전체 요약 정보 <?php if($config->glogkey!="") {?><a href="https://www.google.com/analytics/web/" target="_blank">( <i class="fa fa-bar-chart"></i> Google Analytics )</a><?php } ?></small> 
	</h3>
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<a href="/adminhome/index"><?php echo lang("menu.home");?></a>
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

			<!-- BEGIN DASHBOARD STATS -->
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat blue-madison">
						<div class="visual">
							<i class="fa fa-bar-chart-o"></i>
						</div>
						<div class="details">
							<div class="number">
								 <?php echo $active_product_count;?> / <?php echo $inactive_product_count;?>
							</div>
							<div class="desc">
								 공개 / 비공개 <?php echo lang("product");?>수
							</div>
						</div>
						<a class="more" href="/adminproduct/index">
						<?php echo lang("product");?> 관리 <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat red-intense">
						<div class="visual">
							<i class="fa fa-comments"></i>
						</div>
						<div class="details">
							<div class="number">
								<?php echo $enquire_count;?>
							</div>
							<div class="desc">
								총 의뢰수
							</div>
						</div>
						<a class="more" href="/adminenquire/index">
						의뢰관리 <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat purple-plum">
						<div class="visual">
							<i class="fa fa-globe"></i>
						</div>
						<div class="details">
							<div class="number">
								 <?php echo $ask_count;?>
							</div>
							<div class="desc">
								 총 문의접수건
							</div>
						</div>
						<a class="more" href="/adminask/index">
						<?php echo lang("enquire.title");?> <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat green-haze">
						<div class="visual">
							<i class="fa fa-shopping-cart"></i>
						</div>
						<div class="details">
							<div class="number">
								 <?php echo $today_site_count;?>
							</div>
							<div class="desc">
								 오늘 방문 수
							</div>
						</div>
						<span class="more" href="#">
						통계 <!--i class="m-icon-swapright m-icon-white"></i-->
						</span>
					</div>
				</div>
			</div>
			<!-- END DASHBOARD STATS -->
<?php if($this->session->userdata("is_mobile")=="0"){?>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<!-- BEGIN PORTLET-->
					<div class="portlet solid bordered grey-cararra">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bar-chart-o"></i>사이트 방문 통계
							</div>
							<div class="actions">
								<div class="btn-group" data-toggle="buttons">
									<label class="btn grey-steel btn-sm active">
									<input type="radio" name="options" class="toggle" value="today_site_visit">금일</label>
									<label class="btn grey-steel btn-sm">
									<input type="radio" name="options" class="toggle" value="month_site_visit">30일</label>
								</div>
							</div>
						</div>
						<div class="portlet-body">
							<div id="site_statistics_loading">
								<img src="/assets/admin/img/loading.gif" alt="loading"/>
							</div>
							<div id="site_statistics_content" class="display-none">
								<div id="site_statistics" class="chart">
								</div>
							</div>
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
				<div class="col-md-6 col-sm-6">
					<!-- BEGIN PORTLET-->
					<div class="portlet solid grey-cararra bordered">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bullhorn"></i>블로그 방문 통계
							</div>
							<div class="actions">
								<div class="btn-group" data-toggle="buttons">
									<label class="btn grey-steel btn-sm active">
									<input type="radio" name="options" class="toggle" value="today_blog_visit">금일</label>
									<label class="btn grey-steel btn-sm">
									<input type="radio" name="options" class="toggle" value="month_blog_visit">30일</label>
								</div>
							</div>
						</div>
						<div class="portlet-body">
							<div id="site_activities_loading">
								<img src="/assets/admin/img/loading.gif" alt="loading"/>
							</div>
							<div id="site_activities_content" class="display-none">
								<div id="site_activities" class="chart">
								</div>
							</div>
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
			<div class="clearfix"></div>


			<div class="row">
				<div class="col-md-6 col-sm-6">
					<!-- BEGIN CALL LOG-->
					<div class="portlet box blue-steel">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bell-o"></i><?php echo lang("product");?>페이지 방문이력 (금일)
							</div>
						</div>
						<div class="portlet-body">
							<div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
								<ul class="feeds">
									<?php if(!$site_log){?>
									<div><?php echo lang("msg.nodata");?></div>
									<?php }?>
									<?php foreach($site_log as $value){?>
									<li>
										<div class="col1" style="width:80%">
											<div class="cont">
												<div class="cont-col1">
													<div class="label label-sm label-info">
														<i class="fa fa-check"></i>
													</div>
												</div>
												<div class="cont-col2">
													<div class="desc"><a href="/product/view/<?php echo $value->data_id;?>" target="_blank"><?php echo $value->title;?></a></div>
												</div>
											</div>
										</div>
										<div class="col2" style="width:20%;margin-left:0px;">
											<div class="date"><?php echo $value->date;?></div>
										</div>
									</li>
									<?php }?>
								</ul>
							</div>
							<div class="scroller-footer">
								<div class="btn-arrow-link pull-right">
									<a href="/adminstats/site">전체보기</a>
									<i class="icon-arrow-right"></i>
								</div>
							</div>
						</div>
					</div>
					<!-- END CALL LOG-->
				</div>

				<div class="col-md-6 col-sm-6">
					<!-- BEGIN CALL LOG-->
					<div class="portlet box green-haze">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bell-o"></i><?php echo lang("site.contact");?> 조회 통계 (금일)
							</div>
							<div class="actions">
								<div class="btn-group">
									<a class="btn btn-default btn-sm dropdown-toggle" id="member_category" href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
									직원<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-right">
										<li><a href="javascript:change_member('','직원');"><i class="i"></i><?php echo lang("site.all");?></a></li>
										<li class="divider"></li>
										<?php foreach($call_log_members as $value){?>
										<li><a href="javascript:change_member(<?php echo $value->member;?>,'<?php echo $value->name?>');"><?php echo $value->name?></a></li>
										<?php }?>
									</ul>
								</div>
							</div>
						</div>
						<div class="portlet-body">
							<div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
								<ul class="feeds" id="call_log_list">
									<?php if(!$call_log){?>
									<div><?php echo lang("msg.nodata");?></div>
									<?php }?>
									<?php foreach($call_log as $value){?>
									<li>
										<div class="col1" style="width:80%">
											<div class="cont">
												<div class="cont-col1">
													<div class="label label-sm label-success">
														<i class="fa fa-user"></i>
													</div>
												</div>
												<div class="cont-col2">
													<div class="desc"><a href="/product/view/<?php echo $value->product_id;?>" target="_blank"><?php echo $value->title;?></a></div>
												</div>
											</div>
										</div>
										<div class="col2" style="width:20%;margin-left:0px;">
											<div class="date"><?php echo $value->date;?></div>
										</div>
									</li>
									<?php }?>
								</ul>
							</div>
							<div class="scroller-footer">
								<div class="btn-arrow-link pull-right">
									<a href="/adminstats/call"><?php echo lang("site.all");?></a>
									<i class="icon-arrow-right"></i>
								</div>
							</div>
						</div>
					</div>
					<!-- END CALL LOG-->
				</div>

			</div>
			<div class="clearfix"></div>

			
			<script>
			var change_site_visit = change_blog_visit = null;

			var today_site_visit = [<?php echo index_stats($today_site_visit,false,6);?>];

			var today_blog_visit = [<?php echo index_stats($today_blog_visit,false,6);?>];

			var month_site_visit = [<?php echo index_stats($month_site_visit,true,5);?>];

			var month_blog_visit = [<?php echo index_stats($month_blog_visit,true,5);?>];

			var change_site_visit_all = change_blog_visit_all = null;

			var today_site_visit_all = [<?php echo index_stats($today_site_visit,false);?>];

			var today_blog_visit_all = [<?php echo index_stats($today_blog_visit,false);?>];

			var month_site_visit_all = [<?php echo index_stats($month_site_visit,true);?>];

			var month_blog_visit_all = [<?php echo index_stats($month_blog_visit,true);?>];

			</script>
			<script src="/assets/admin/js/index.js" type="text/javascript"></script>
			<script src="/assets/plugin/flot/jquery.flot.min.js" type="text/javascript"></script>
			<script src="/assets/plugin/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
			<script src="/assets/plugin/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
			<script>
			$(document).ready(function() {
				Index.init();   
				Index.initCharts(); 
			});
			$('input[name="options"]').on('change',function(){
				switch($(this).val()){
					case "today_site_visit":
						change_site_visit = today_site_visit;
						change_site_visit_all = today_site_visit_all;
						Index.initCharts();
						break;
					case "today_blog_visit":
						change_blog_visit = today_blog_visit;
						change_blog_visit_all = today_blog_visit_all;
						Index.initCharts();
						break;
					case "month_site_visit":
						change_site_visit = month_site_visit;
						change_site_visit_all = month_site_visit_all;
						Index.initCharts();
						break;
					case "month_blog_visit":
						change_blog_visit = month_blog_visit;
						change_blog_visit_all = month_blog_visit_all;
						Index.initCharts();
						break;
				}
			});

			function change_member(member_id,member_name){
				var member_id = (member_id) ? member_id : "";
				$.ajax({
					type: "get",
					dataType: "json",
					url: "/product/get_call_log/"+member_id,
					cache: false,
					success: function(data){
						var list_tag = "";
						$.each(data, function(key, val) {
							list_tag +=	"<li>";
							list_tag +=		"<div class='col1' style='width:80%'>";
							list_tag +=			"<div class='cont'>";
							list_tag +=				"<div class='cont-col1'>";
							list_tag +=					"<div class='label label-sm label-success'>";
							list_tag +=						"<i class='fa fa-user'></i>";
							list_tag +=					"</div>";
							list_tag +=				"</div>";
							list_tag +=				"<div class='cont-col2'>";
							list_tag +=					"<div class='desc'>"+val['title']+"</div>";
							list_tag +=				"</div>";
							list_tag +=			"</div>";
							list_tag +=		"</div>";
							list_tag +=		"<div class='col2' style='width:20%;margin-left:0px;'>";
							list_tag +=			"<div class='date'>"+val['date']+"</div>";
							list_tag +=		"</div>";
							list_tag +=	"</li>";
						});
						$("#member_category").html(member_name);
						$("#call_log_list").html(list_tag);
				    },
					error:function(e){  
						
					}
				});
			}
			</script>

<?php }?>
<?php if($config->BUG_LAYER){?>
<script src="http://www.dungzi.com/assets/js/bug_report.js"></script>
<?php }?>