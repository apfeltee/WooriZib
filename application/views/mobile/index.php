<script>
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
});
</script>
<style>
  .padding, .navbar {background-color:transparent;}
  .body * {color:white;}
</style>
<div class="page-content splash" id="main-stack" data-scroll="0" data-splash="2000" data-redirect="/mobile/home" style="background-image:url(/assets/mobile/images/splash/<?php echo $config->MOBILE_SPLASH;?>.jpg);">
  <div class="w-nav navbar"></div>
  <div class="body padding">
    <!-- <div class="splash-logo" style="background-image:url(<?php if($config->logo==""){echo "/assets/common/img/dungzi.png";} else {?>/uploads/logo/<?php echo $config->logo;?><?php }?>);"></div> -->
    <!-- <div class="bottom-section padding text-centered">
      <h4><?php if( $config->site_name!="" ) { echo $config->site_name; } else { echo $config->name; } ?></h4>
      <p class="welcome-splash-text"><?php echo $config->description?>
        <br><?php echo $config->tel?> / <?php echo $config->mobile?></p>
      <div class="separator-big"></div>
      <div class="link-upper">로딩중...</div>
      <div class="separator-bottom"></div>
    </div> -->
  </div>
</div>
