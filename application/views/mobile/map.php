<script>
  var sell_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("sell_unit");?></font>";
  var price_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("price_unit");?></font>";
  
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

    mapsize();
  <?php 
    /*** 검색에서 넘어왔으면 검색에 대한 값으로 초기화를 하고 그렇지 않으면 부동산의 주소로 초기화를 한다 ***/
    if(element("search_type",$search)!="" && element("keyword_front",$search)==""){
  ?>
    initialize(<?php echo element('lat',$search);?>, <?php echo element('lng',$search);?>, <?php echo element('zoom',$search);?>, <?php echo $config->maxzoom;?>);
  <?php 
    } else { 
  ?>

    if( $("#lat").val() != "" && $("#lng").val() != "" ){
      initialize($("#lat").val(), $("#lng").val(), $("#zoom").val(), <?php echo $config->maxzoom;?>);
    } else {
      initialize(<?php echo $config->lat;?>, <?php echo $config->lng;?>, <?php echo $config->MAP_INIT_LEVEL?>, <?php echo $config->maxzoom;?>);    
    }
  <?php 
    } 
  ?>
  call_map();
  });

function area_view(){
	var lat_s = map.getBounds().getSouthWest().getLat();
	var lat_e = map.getBounds().getNorthEast().getLat();
	var lng_s = map.getBounds().getSouthWest().getLng();
	var lng_e = map.getBounds().getNorthEast().getLng();
	location.href="/mobile/grid/"+lat_s+"/"+lat_e+"/"+lng_s+"/"+lng_e;
}
</script>
<input type="hidden" id="lat" value="">
<input type="hidden" id="lng" value="">
<input type="hidden" id="zoom" value="">
<div class="page-content" id="main-stack">
  <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
    <div class="w-container">
      <!-- 상단 시작 -->
      <?php echo $menu;?>

      <div class="wrapper-mask" data-ix="menu-mask"></div>
      <div class="navbar-title"><?php echo lang("site.map");?> <?php echo lang("site.search");?></div>
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
    <div id="map"></div>
  </div>
</div>

<div class='toast' style='display:none;'><?php echo lang("msg.map.not");?></div>
<div class='area_view' onclick="area_view()">이지역 <?php echo lang("product");?>보기</div>