<link href="/assets/admin/css/timeline.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/css/tasks.css" rel="stylesheet" type="text/css"/>
<link href="/assets/plugin/icheck/skins/all.css" rel="stylesheet"/>
<script src="/assets/plugin/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="/assets/plugin/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<link href="/assets/plugin/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
<script src="/assets/plugin/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src="/assets/plugin/fullcalendar/lang-all.js" type="text/javascript"></script>
<style>
.calendar .search-wrapper ul li:first-child {
	border-bottom: 1px solid #eee;
	padding-bottom: 5px;
	margin-bottom: 10px;
}

.calendar .line {
	height:25px;
}

.fc-title i{
	font-size:10px;
}

.fc-content{
	padding-bottom:2px;
}
</style>
<script>
$(document).ready(function(){

	$('#search_form').ajaxForm( {
		beforeSubmit: function(){
		},
		success: function(data){
			calendar_print();
		}
	});
	$('.search_checkbox').on('ifChanged', function(event){
		switch($(this).attr("name")){
			case "meeting" :
			case "call" :
			case "etc" :
				$("input[name='action']").prop("checked",true);
				break;
		}
		if(!$("input[name='action']").prop("checked")){
			$("input[name='meeting']").prop("checked",false);
			$("input[name='call']").prop("checked",false);
			$("input[name='etc']").prop("checked",false);		
		}
		$(".search_checkbox").iCheck("update");
		$('#search_form').submit();
	});

	$(".search_checkbox").iCheck({
		checkboxClass: 'icheckbox_square-aero',
		radioClass: 'iradio_square-red',
		increaseArea: '20%'
	});

	calendar_print();
});

function calendar_print(){

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
		events: '/adminenquire/event',
		eventRender: function(event, element) {
			element.find(".fc-title").html(element.find(".fc-title").text());					  
		}
	});
}

function search_reset(){
	$(".search_checkbox").prop("checked",false);
	$(".search_checkbox").iCheck("update");
	$('#search_form').submit();
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">업무달력</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>업무달력</li>
			</ul>
		</div>
	</div>
</div>

<div class="calendar row">
	<div class="col-md-12">
		<div class="portlet tasks-widget">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-calendar"></i>업무 달력
				</div>
			</div>
			<div class="portlet-body light-grey row">
				<form name="search_form" id="search_form" action="/adminenquire/calendar_search" method="post">
				<div class="col-lg-2">
					<div class="text-center">
						<a href="javascript:search_reset();" class="btn btn-default" style="width:100%"><i class="fa fa-toggle-on"></i> 검색 초기화</a>
					</div>
					<div class="search-wrapper" style="margin-top:10px;padding-top:0px;">
						<ul>
							<li>
								<h3><i class="fa fa-search"></i> <?php echo lang("site.search");?></h3>
							</li>
							<li>
								<div class="line">
									<span><input type="checkbox" class="search_checkbox" name="action" <?php if($calendar_search["action"]=="on") echo "checked";?>/></span>
									<label>접촉</label>
								</div>
								<div class="line">
									<i class="fa fa-level-up fa-rotate-90"></i>
									<span><input type="checkbox" class="search_checkbox" name="meeting" <?php if($calendar_search["meeting"]=="on") echo "checked";?>/></span>
									<label>미팅 <i class="glyphicon glyphicon-user"></i></label>
								</div>
								<div class="line">
									<i class="fa fa-level-up fa-rotate-90"></i>
									<span><input type="checkbox" class="search_checkbox" name="call" <?php if($calendar_search["call"]=="on") echo "checked";?>/></span>
									<label>전화 <i class="glyphicon glyphicon-earphone"></i></label>
								</div>
								<div class="line">
									<i class="fa fa-level-up fa-rotate-90"></i>
									<span><input type="checkbox" class="search_checkbox" name="etc" <?php if($calendar_search["etc"]=="on") echo "checked";?>/></span>
									<label>기타 <i class="glyphicon glyphicon-list-alt"></i></label>
								</div>
							</li>
							<li class="line">
								<span><input type="checkbox" class="search_checkbox" name="memo" <?php if($calendar_search["memo"]=="on") echo "checked";?>/></span>
								<label>메모 <i class="glyphicon glyphicon-pencil"></i></label>
							</li>
							<li class="line">
								<span><input type="checkbox" class="search_checkbox" name="contract" <?php if($calendar_search["contract"]=="on") echo "checked";?>/></span>
								<label>계약 <i class="glyphicon glyphicon-file"></i></label>
							</li>
						</ul>
					</div>

					<div class="search-wrapper" style="padding-top:0px;">
						<ul>
							<li>
								<h3><i class="fa fa-user"></i> <?php echo lang("product.owner");?></h3>
							</li>
							<?php 
							foreach($members as $val){
								$checked = false;
								if($calendar_search["member_id"]){
									$checked = in_array($val->id,$calendar_search["member_id"]);
								}
							?>
							<li class="line">								
								<span><input type="checkbox" class="search_checkbox" name="member_id[]" value="<?php echo $val->id?>" <?php if($checked) echo "checked";?>/></span>
								<label title="<?php echo $val->email?>"><font color="<?php echo "#".$val->color;?>"><i class="fa fa-square"></i></font> <?php echo $val->name?></label>
							</li>
							<?php }?>
						</ul>
					</div>
				</div>
				</form>
				<div id="calendar" class="col-lg-10" style="min-height:1030px"></div>
			</div>
		</div>
	</div>
</div>
