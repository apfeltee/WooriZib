<script src="/assets/mobile/js/iscroll.js" type="text/javascript"></script>
<style>
#sido_section, #gugun_section, #dong_section{
	position: relative;
	width: 110px;
	overflow-x: hidden;
	overflow-y: auto;
	height: 450px;
}
#sido_section ul li div button, #gugun_section ul li div button, #dong_section ul li div button{
	width: 110px;
}
.select_label .active{
	color: #337ab7;
}
</style>
<script>
var sido_scroll;
var gugun_scroll;
var dong_scroll;
$(document).ready(function(){

	if(navigator.geolocation){
		navigator.geolocation.getCurrentPosition(function(position){
			var local_lat = position.coords.latitude;
			var local_lng = position.coords.longitude;

			$.ajax({
				url: "/search/set_geolocation",
				type: "POST",
				data: {
					local_lat : local_lat,
					local_lng : local_lng,
				},
				success: function(data){
				}
			});
		},
		function(){}
		,{maximumAge:60000, timeout:10000});
	}

	sido_scroll = new iScroll('sido_section');
	gugun_scroll = new iScroll('gugun_section');
	dong_scroll = new iScroll('dong_section');	
});

function get_gugun(obj,sido){
	$("#sido_label").addClass("active");
	$("#label_text").text("구군을 선택하세요");
	$("#sido_section > ul > li > div> button").removeClass("active");
	$("#gugun_label").removeClass("active");
	$(obj).addClass("active");
	$("#dong_section > ul > li > div").html("");

	$.getJSON("/address/get_gugun/front/"+encodeURI(sido)+"/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			str = str + "<button type=\"button\" class=\"btn btn-default\" onclick=\"get_dong(this,"+val["parent_id"]+")\">"+val["gugun"]+"</button>";
		});
		$("#gugun_section > ul > li > div").html(str);
		RefreshScroll(gugun_scroll);
	});
}

function get_dong(obj,parent_id){
	$("#gugun_label").addClass("active");
	$("#label_text").text("읍면동을 선택하세요");
	$("#gugun_section > ul > li > div > button").removeClass("active");
	$("#dong_label").removeClass("active");
	$(obj).addClass("active");

	$.getJSON("/address/get_dong/front/"+parent_id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			str = str + "<button type=\"button\" class=\"btn btn-default\" onclick=\"area_search(this,"+val["id"]+",'"+val["lat"]+"','"+val["lng"]+"')\">"+val["dong"]+"</button>";
		});
		$("#dong_section > ul > li > div").html(str);
		RefreshScroll(dong_scroll);
	});
}

function area_search(obj,id,lat,lng){
	$("#dong_label").addClass("active");
	$("#dong_section > ul > li > div > button").removeClass("active");
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

function all_search(){
	$("#search_type").val("");
	$("#search_form").submit();
}

function local_search(){
	$("#search_type").val("");
	$("#local_search").val("Y");
	$("#search_form").submit();
}
</script>
<div class="page-content" id="main-stack">
	<div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
		<div class="w-container">
			<!-- 상단 시작 -->
			<?php echo $menu;?>
			<div class="wrapper-mask" data-ix="menu-mask"></div>
			<div class="navbar-title"><?php echo lang("site.location");?> <?php echo lang("site.search");?></div>
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
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-default" onclick="all_search();"><i class="ion-forward"></i> 전체보기</button>
			</div>
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-default" onclick="local_search();"><i class="ion-man"></i> 내 위치 순으로 보기</button>
			</div>
		</div>
		<div class="bg-primary text-center">
			<div style="padding:7px 0;">
				<span id="label_text">시도를 선택하세요<span>
			</div>		
		</div>
		<div class="select_label bg-info text-center" style="padding:5px 0 25px 0">
			<strong id="sido_label"><span class="col-xs-4">시도<i class="ion-chevron-right pull-right"></i></span></strong>
			<strong id="gugun_label"><span class="col-xs-4"><strong>구군</strong><i class="ion-chevron-right pull-right"></i></span></strong>
			<strong id="dong_label"><span class="col-xs-4"><strong>읍면동</strong></span></strong>		
		</div>
		<div class="separator-fields"></div>
		<div class="text-center">
			<div class="btn-group-vertical" id="sido_section">
				<ul>
					<li>
						<div class="btn-group-vertical">
							<?php foreach($sido as $val){?>
							<button type="button" class="btn btn-default" onclick="get_gugun(this,'<?php echo $val->sido?>');"><?php echo $val->sido?></button>
							<?php }?>
						</div>
					</li>
				</ul>
			</div>
			<div class="btn-group-vertical" id="gugun_section">
				<ul>
					<li>
						<div class="btn-group-vertical"></div>
					</li>
				</ul>
			</div>
			<div class="btn-group-vertical" id="dong_section">
				<ul>
					<li>
						<div class="btn-group-vertical"></div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<form action="/search/set_search/<?php echo $direct;?>/1" id="search_form" method="post">
	<input type="hidden" id="search_type" name="search_type" value="address"/>
	<input type="hidden" id="search_value" name="search_value"/>
	<input type="hidden" id="lat" name="lat"/>
	<input type="hidden" id="lng" name="lng"/>
	<input type="hidden" id="local_search" name="local_search"/>
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