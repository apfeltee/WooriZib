	<div class="main">
      <div class="_container">
        <ul class="breadcrumb">
            <li><a href="/"><?php echo lang("menu.home");?></a></li>
            <li class="active"><?php if( $config->site_name!="" ) { echo $config->site_name; } else { echo $config->name; } ?> <?php echo lang("site.introduction");?></li>
        </ul>
		
		<div class="row margin-bottom-40">
		  <div class="col-md-12">
            <h1><?php if( $config->site_name!="" ) { echo $config->site_name; } else { echo $config->name; } ?> <?php echo lang("site.introduction");?></h1>
            <div class="content-page">
				<div class="row">
					<div class="col-md-12">
					  <div id="map" class="gmaps margin-bottom-40" style="height:400px;"></div>
					</div>
					<div class="col-md-9 col-sm-9">
						<?php echo $config->content;?>
					</div>
					<div class="col-md-3 col-sm-3 sidebar2">
						<h2><?php echo lang("site.introduction");?></h2>
		                  <address>
							<strong><?php if( $config->site_name!="" ) { echo $config->site_name; } else { echo $config->name; } ?></strong><br>
							<i class="fa fa-map-marker"></i> <?php echo toeng($config->new_address);?><br>
							<i class="fa fa-map-marker"></i> <?php echo toeng($config->address);?><br>
							<abbr title="Phone">P:</abbr> <?php echo $config->tel;?><br>
							<abbr title="Phone">F:</abbr> <?php echo $config->fax;?><br>
						  </address>
						  <address>
							<strong><?php echo lang("site.email");?></strong><br>
							<a href="mailto:<?php echo $config->email;?>"><?php echo $config->email;?></a>
						  </address>
						  <address>
							<strong><?php echo lang("site.bizinfo");?></strong><br>
								<?php echo lang("site.biznum");?>: <?php echo $config->biznum;?><br/>
								<?php if($config->INSTALLATION_FLAG!="1") {?>
								<?php echo lang("site.renum");?>: <?php echo $config->renum;?><br><?php }?>
								<?php echo lang("site.ceo");?>: <?php echo $config->ceo;?>
						  </address>
					</div>
                </div>
              </div>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
      </div>
    </div>
			
<script>
	var mapContainer = document.getElementById('map'), 
		mapOption = {
			center: new daum.maps.LatLng(<?php echo $config->lat;?>, <?php echo $config->lng;?>),
			level: 3 
		};

	var map = new daum.maps.Map(mapContainer, mapOption);


	var markerPosition  = new daum.maps.LatLng(<?php echo $config->lat;?>, <?php echo $config->lng;?>); 


	var marker = new daum.maps.Marker({
		position: markerPosition
	});

	var map_type = "<?php echo $this->config->item('intro_map');?>";

	if(map_type=='sky'){
		map.setMapTypeId(daum.maps.MapTypeId.HYBRID);
	}
	else{
		map.setMapTypeId(daum.maps.MapTypeId.ROADMAP);
	}
	marker.setMap(map);

</script>