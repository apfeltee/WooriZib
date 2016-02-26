<script src="/assets/mobile/js/iscroll.js" type="text/javascript"></script>
<style>
#local_section, #hosun_section, #station_section{
	position: relative;
	width: 110px;
	overflow-x: hidden;
	overflow-y: auto;
	height: 400px;
}
#local_section ul li div button, #hosun_section ul li div button, #station_section ul li div button{
	width: 110px;
}

.select_label .active{
	color: #337ab7;
}
</style>
<script>
var local_scroll;
var hosun_scroll;
var station_scroll;
$(document).ready(function(){
	local_scroll = new iScroll('local_section');
	hosun_scroll = new iScroll('hosun_section');
	station_scroll = new iScroll('station_section');
});

function get_hosun(obj,local){
	$("#local_label").addClass("active");
	$("#label_text").text("호선을 선택하세요");
	$("#local_section > ul > li > div > button").removeClass("active");
	$("#hosun_label").removeClass("active");
	$(obj).addClass("active");
	$("#station_section > ul > li > div").html("");
	$.getJSON("/subway/get_hosun/"+local+"/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			var hosun = ($.isNumeric(val["hosun"])) ? val["hosun"]+"호선" : val["hosun"];
			str = str + "<button type=\"button\" class=\"btn btn-default\" onclick=\"get_station(this,"+val["hosun_id"]+")\">"+hosun+"</button>";
		});
		$("#hosun_section > ul > li > div").html(str);
		RefreshScroll(hosun_scroll);
	});
}

function get_station(obj,hosun_id){
	$("#hosun_label").addClass("active");
	$("#label_text").text("역을 선택하세요");
	$("#hosun_section > ul > li > div > button").removeClass("active");
	$("#station_label").removeClass("active");
	$(obj).addClass("active");
	$.getJSON("/subway/get_station/"+hosun_id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			str = str + "<button type=\"button\" class=\"btn btn-default\" onclick=\"subway_search(this,"+val["id"]+",'"+val["lat"]+"','"+val["lng"]+"')\">"+val["name"]+"역</button>";
		});
		$("#station_section > ul > li > div").html(str);
		RefreshScroll(station_scroll);
	});
}

function subway_search(obj,id,lat,lng){
	$("#station_label").addClass("active");
	$("#station_section > ul > li > div > button").removeClass("active");
	$(obj).addClass("active");
	$("#lat").val(lat);
	$("#lng").val(lng);
	$("#search_value").val(id);
	$("#search_form").submit();
}

function RefreshScroll(i_scroll) {
    setTimeout(function () {
        i_scroll.scrollToElement('li:nth-child(1)', 100)
        setTimeout(function () {
            i_scroll.refresh();
			i_scroll.scrollTo(0, 0);
        }, 0);
    }, 400);
}
</script>
<div class="page-content" id="main-stack">
	<div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
		<div class="w-container">
			<!-- 상단 시작 -->
			<?php echo $menu;?>
			<div class="wrapper-mask" data-ix="menu-mask"></div>
			<div class="navbar-title"><?php echo lang("site.subway");?> <?php echo lang("site.search");?></div>
			<div class="w-nav-button navbar-button left" id="menu-button" data-ix="hide-navbar-icons">
				<div class="navbar-button-icon home-icon">
					<div class="bar-home-icon"></div>
					<div class="bar-home-icon"></div>
					<div class="bar-home-icon"></div>
				</div>
			</div>
			<a class="w-inline-block navbar-button right" href="/mobile/search">
				<div class="navbar-button-icon smaller icon ion-ios-search"></div>
			</a>
			<a class="w-inline-block navbar-button right" href="/mobile/subway">
				<div class="navbar-button-icon smaller icon ion-android-subway"></div>
			</a>
			<a class="w-inline-block navbar-button right" href="/mobile/area">
				<div class="navbar-button-icon smaller icon ion-ios-location-outline"></div>
			</a>
			<!-- 상단 종료 -->
		</div>
	</div>
	<div class="body">
		<div class="separator-fields"></div>
		<div class="bg-primary text-center">
			<div style="padding:7px 0;">
				<span id="label_text"><?php echo lang("site.location");?>을 선택하세요<span>
			</div>		
		</div>
		<div class="select_label bg-info text-center" style="padding:5px 0 25px 0">
			<strong id="local_label"><span class="col-xs-4"><?php echo lang("site.location");?><i class="ion-chevron-right pull-right"></i></span></strong>
			<strong id="hosun_label"><span class="col-xs-4"><strong>호선</strong><i class="ion-chevron-right pull-right"></i></span></strong>
			<strong id="station_label"><span class="col-xs-4"><strong>역</strong></span></strong>		
		</div>
		<div class="separator-fields"></div>

		<div class="text-center">
			<div class="btn-group-vertical" id="local_section">
				<ul>
					<li>
						<div class="btn-group-vertical">
							<?php foreach($local as $val){?>
							<button type="button" class="btn btn-default" onclick="get_hosun(this,'<?php echo $val->local?>');"><?php echo $val->local_text?></button>
							<?php }?>
						</div>
					</li>
				</ul>
			</div>
			<div class="btn-group-vertical" id="hosun_section">
				<ul>
					<li>
						<div class="btn-group-vertical"></div>
					</li>
				</ul>
			</div>
			<div class="btn-group-vertical" id="station_section">
				<ul>
					<li>
						<div class="btn-group-vertical"></div>
					</li>
				</ul>
			</div>		
		</div>		
	</div>
</div>

<form action="/search/set_search/<?php echo  $direct;?>/1" id="search_form" method="post">
	<input type="hidden" name="search_type" value="subway"/>
	<input type="hidden" id="search_value" name="search_value"/>
	<input type="hidden" id="lat" name="lat">
	<input type="hidden" id="lng" name="lng">
	<input type="hidden" name="type" value="<?php echo element("type",$search);?>"/>
	<?php

		if(element("theme",$search)){
			$theme = explode(",",element("theme",$search));
			foreach($theme as $val){
				echo '<input type="hidden" name="theme[]" value="'.$val.'"/>';			
			}
		}
	?>		
	<?php
		if(element("category",$search)){
			$category = explode(",",element("category",$search));
			foreach($category as $val){
				echo '<input type="hidden" name="category[]" value="'.$val.'"/>';			
			}
		}
	?>	
	<input type="hidden" name="sell_start" value="<?php echo element("sell_start",$search);?>"/>
	<input type="hidden" name="sell_end" value="<?php echo element("sell_end",$search);?>"/>
	<input type="hidden" name="full_start" value="<?php echo element("full_start",$search);?>"/>
	<input type="hidden" name="full_end" value="<?php echo element("full_end",$search);?>"/>
	<input type="hidden" name="month_deposit_start" value="<?php echo element("month_deposit_start",$search);?>"/>
	<input type="hidden" name="month_deposit_end" value="<?php echo element("month_deposit_end",$search);?>"/>
	<input type="hidden" name="month_start" value="<?php echo element("month_start",$search);?>"/>
	<input type="hidden" name="month_end" value="<?php echo element("month_end",$search);?>"/>
	<input type="hidden" name="site_area" value="<?php echo element("site_area",$search);?>"/>
	<input type="hidden" name="law_area" value="<?php echo element("law_area",$search);?>"/>
</form>