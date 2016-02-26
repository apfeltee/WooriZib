<script src="/script/src/view"></script>
<style>
#container {overflow:hidden;position:relative;}
#container.view_map #mapWrapper {z-index: 10;}
#container.view_roadview #mapWrapper {z-index: 0;}
#container.view_roadview #btnRoadview {display: none;}
</style>
<div class="page-content" id="main-stack">
  <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
    <div class="w-container">
      <div class="wrapper-mask" data-ix="menu-mask"></div>
      <div class="navbar-title">지도/로드뷰 </div>
      <a class="w-inline-block navbar-button right" onclick="onBackKeyDown();">
        <div class="navbar-button-icon icon ion-ios-close-empty"></div>
      </a>
      <!-- 상단 종료 -->
    </div>
  </div>
  <div class="body">
	<div style="padding-left:3px;">
		<a class="action-button" style="width:49%" onclick="toggleMap(true)"><?php echo lang("site.map");?></a>
		<a class="action-button" style="width:49%" onclick="toggleMap(false)"><?php echo lang("site.roadview");?></a>
		<div class="separator-fields"></div>
	</div>
	<!-- daum map START -->
	<div id="container" class="view_map">
		<div id="mapWrapper" style="width:100%;height:100%;position:relative;">
			<div id="point_map" style="width:100%;height:100%"></div><!-- 지도를 표시할 div 입니다 -->
		</div>
		<div id="rvWrapper" style="width:100%;height:100%;position:absolute;top:0;left:0;">
			<div id="roadview" style="width:100%;height:100%"></div> <!-- 로드뷰를 표시할 div 입니다 -->
		</div>
	</div>
	<!-- daum map END -->
  </div>
</div>
<script>
$(document).ready(function(){
	var all_size = $(window).height();
	$("#mapWrapper").height(all_size-120);
	$("#rvWrapper").height(all_size-120);
	set_radius(<?php echo $config->RADIUS;?>);
	position_daum("<?php echo $query->lat;?>", "<?php echo $query->lng;?>", 4, <?php echo $config->maxzoom;?>);
});
</script>