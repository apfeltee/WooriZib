<link href="/assets/admin/css/timeline.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/css/tasks.css" rel="stylesheet" type="text/css"/>
<link href="/assets/plugin/icheck/skins/all.css" rel="stylesheet"/>
<script src="/assets/plugin/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="/assets/plugin/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<link href="/assets/plugin/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
<script src="/assets/plugin/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src="/assets/plugin/fullcalendar/lang-all.js" type="text/javascript"></script>
<script>
$(function(){            
	var h = {};            

	if ($('#calendar').width() <= 400) {
		$('#calendar').addClass("mobile");
		h = {
			left: 'title, prev, next',
			center: ''
		};
	} else {
		$('#calendar').removeClass("mobile");
		h = {
			left: 'title',
			center: ''
		};
	}

	$('#calendar').fullCalendar('destroy'); 
	$('#calendar').fullCalendar({ 
		disableDragging: false,
		header: h,
		lang: 'ko',
		timeFormat: 'H:mm',
		editable: true,
		events: '/admincontact/event'
	});	
})
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				변경<small>이력</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					변경 이력
				</li>
			</ul>
			<div class="page-toolbar">
				
			</div>
		</div>
	</div>
</div><!-- /.row -->

<div class="row">
	<div class="col-md-6">
		<div class="portlet tasks-widget">
			<div class="portlet-title line">
				<div class="caption">
					<i class="fa fa-history"></i> 변동 이력
				</div>
			</div>
			<div class="portlet-body">
				<div class="btn-toolbar margin-bottom-10">
					<div class="btn-group">
						<a href="/admincontact/history/0" class="btn default btn-sm <?php if($type=="0") echo "active";?>"><strong>전체 </strong></a>
						<a href="/admincontact/history/C" class="btn default btn-sm <?php if($type=="C") echo "active";?>">고객 </a>
						<a href="/admincontact/history/A" class="btn default btn-sm <?php if($type=="A") echo "active";?>" style="color:#1bbc9b">접촉 </a>
						<a href="/admincontact/history/M" class="btn default btn-sm <?php if($type=="M") echo "active";?>" style="color:#9b59b6">메모 </a>
						<a href="/admincontact/history/T" class="btn default btn-sm <?php if($type=="T") echo "active";?>" style="color:#89C4F4">업무 </a>
					</div>
					<div class="pull-right">
						<ul class="pagination">
							<?php echo $pagination;?>
						</ul>
					</div>
				</div>
				<div id="vmap_world" class="vmaps display-none">
				</div>
				<div id="vmap_usa" class="vmaps display-none">
				</div>
				<div id="vmap_europe" class="vmaps display-none">
				</div>
				<div id="vmap_russia" class="vmaps display-none">
				</div>
				<div id="vmap_germany" class="vmaps display-none">
				</div>
				
				<?php
				if(count($history)<1){
					echo "없음";
				} else {
				?>
				<div class="timeline">
						<?php 
						foreach($history as $val){?>
						<div class="timeline-item">
							<div class="timeline-badge">
								<?php if($val->profile!=""){?>
									<img class="timeline-badge-userpic" src="/uploads/member/<?php echo $val->profile?>">
								<?php } else { ?>
									<img class="timeline-badge-userpic" src="/assets/admin/img/w1.png">
								<?php } ?>
								
							</div>
							<div class="timeline-body">
								<div class="timeline-body-arrow"></div>
										<div class="timeline-body-head">
											<div class="timeline-body-head-caption">
												<a href="/admincontact/view/<?php echo $val->contacts_id;?>" class="timeline-body-title font-blue-madison"><?php echo $val->name;?></a>
												<span class="timeline-body-time font-grey-cascade"><?php echo $val->regdate;?> (담당: <?php echo $val->member_name;?>)</span>
											</div>
										</div>
										<div class="timeline-body-content">
											<span class="font-grey-cascade">
											<?php echo $val->name;?> 님(<?php echo $val->organization;?> <?php echo $val->role;?>)의 
											<?php 
												if($val->type=="C") {
													echo "고객정보";
												} else if($val->type=="M") {
													echo "메모정보";
												} else if($val->type=="A") {
													echo "접촉정보";
												} else if($val->type=="T") {
													echo "업무정보";
												}
											?>가 
											<?php 
												if($val->action=="A") {
													echo "추가";
												} else if($val->action=="M") {
													echo "수정";
												} else if($val->action=="E") {
													echo "종료";
												} else if($val->action=="S") {
													echo "시작";
												}
											?>되었습니다. <br/>
											<?php 
												if($val->type=="C") {
													echo "<i class=\"fa fa-user\"></i>";
												} else if($val->type=="M") {
													echo "<i class=\"fa fa-weixin\"></i>";
												} else if($val->type=="T") {
													echo "<i class=\"fa fa-check\"></i>";
												}
											?> <?php echo strip_tags($val->title);?>
											</span>
										</div>
							</div>
						</div>
						<?php }?>
				</div>
				<?php }?>

				<div class="row text-center">
					<div class="col-sm-12">
						<ul class="pagination">
							<?php echo $pagination;?>
						</ul>
				    </div>
				</div>

			</div>
		</div>

	</div>
	<div class="col-md-6">
		<!-- BEGIN PORTLET-->
		<div class="portlet tasks-widget">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-calendar"></i>업무 달력
				</div>
			</div>
			<div class="portlet-body light-grey">
				<div id="calendar">
				</div>
			</div>
		</div>
		<!-- END PORTLET-->

		<div class="portlet tasks-widget">
			<div class="portlet-title line">
				<div class="caption">
					<i class="fa fa-check"></i>나의 미완료 업무
				</div>
				<div class="actions">

				</div>
			</div>
			<div class="portlet-body">
				<div class="task-content">
					<ul class="task-list">
						<?php foreach($task as $key=>$val){?>
							<li <?php if($key>=(count($task)-1)) echo "class='last-line'";?>>
								<div class="task-title">
									<span class=\"task-title-sp\">
										<?php 
										
										if($val->important=="1"){
											echo "<span class=\"label label-sm label-danger\">중요</span>";
										} else if($val->important=="2"){
											echo "<span class=\"label label-sm label-info\">보통</span>";
										} else if($val->important=="3"){
											echo "<span class=\"label label-sm label-default\">낮음</span>";
										}

										?>

										<a href='/admincontact/view/<?php echo $val->contacts_id;?>'><b><?php echo $val->name;?></b></a> 

										<?php 
										
											$date1 = strtotime($val->deaddate);
											$datediff =  time() - $date1;
											if($datediff>0){
												echo "<span class=\"badge badge-danger\">D".floor($datediff/(60*60*24))."</span>";
											} else {
												echo "<span class=\"badge badge-success\">D".floor($datediff/(60*60*24))."</span>";
											}
											
										?>

										<b><?php echo $val->title;?></b>

									</span>
								</div>
							</li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
