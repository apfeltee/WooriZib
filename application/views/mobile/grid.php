<script src="/assets/plugin/jquery.lazyload.js" type="text/javascript"></script>
<script>
$(document).ready(function() {

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

	$(window).scroll(function (){
		if($(window).scrollTop() == $(document).height() - $(window).height()){
			if($(".list li:last").length > 0){
				if($(".list li").length >= $("#total").val()){
					$("#LodingImage").css("visibility","hidden");
					return false;
				}
			}
			$("#LodingImage").css("visibility","visible");
			get_list('1');
		}
		$("#scroll").val($(window).scrollTop());
	});

	if($("#next_page").val()<1){
		get_list('1');
	} else {
		get_list('0');
	}
});

var pstate = 0;

function get_list(init){

	if(pstate == 0){

		pstate == 1;

		if($(".list li:last").length > 0){
			if (navigator.userAgent.indexOf('iPhone') != -1){
				setTimeout(scrollTo, 0, 0, $(window).scrollTop() + 30);
			}else{
				window.scrollTo(0, $(window).scrollTop() + 30);
			}
		}

		var lat_s = "<?php echo $lat_s;?>";
		var lat_e = "<?php echo $lat_e;?>";
		var lng_s = "<?php echo $lng_s;?>";
		var lng_e = "<?php echo $lng_e;?>";

		$.getJSON("/mobile/grid_json/"+$("#next_page").val()+"/"+init+"/"+lat_s+"/"+lat_e+"/"+lng_s+"/"+lng_e+"/"+Math.round(new Date().getTime()),function(data){
			$.each(data, function(key, val) {
				if(key=="total"){
					$("#total").val(val);
				}
				if(key=="paging"){
					$("#next_page").val(val);
				}
				if(key=="result"){
					$(".lazy").removeClass("lazy");
					if($(".list li:last").length > 0){
						$(".list li:last").after(val);
					}
					else{
						if(val!=""){
							$(".list").html(val);
						} else {
							$(".list").css("padding-top","50px");
							$(".list").css("line-height","30px");
							$(".list").html("<div class='padding'><i class='glyphicon glyphicon-ban-circle' style='color:red'></i> <?php echo lang("msg.nodata");?><br/>등록되지 않은 <?php echo lang('product');?>이 있으니 문의해주세요.<br/>또는 검색 조건을 수정해주세요.</div>");
						}
					}
					lazy();
					if(init<1){
						$(window).scrollTop($("#scroll").val());
						init = 1;
					}
				}
			});
			$("#LodingImage").css("visibility","hidden");
			pstate = 0;

			login_leanModal();
		});
	}
}

function lazy(){
	$("div.lazy").lazyload({
	  failure_limit : 10,
      effect : "fadeIn",
	  /*effectspeed : 100,*/
	  skip_invisible : false
	});
}
</script>
<input type="hidden" id="scroll" value="0">
<div class="page-content" id="main-stack">
  <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
    <div class="w-container">
      <!-- 상단 시작 -->
      <?php echo $menu;?>

      <div class="wrapper-mask" data-ix="menu-mask"></div>
      <div class="navbar-title"><?php echo lang("product");?> 목록</div>
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
    <input type="hidden" id="next_page" value="0"/>
	<input type="hidden" id="total" value="0"/>
    <ul id="list" class="list list-messages"></ul>
	<div id="LodingImage" style="visibility:hidden;background-color:#EEE;text-align:center;visitable">
		<img src="/assets/mobile/images/page_roading.gif" style="margin:10px 0;width:35px;height:35px;"/>
	</div>
  </div>
</div>