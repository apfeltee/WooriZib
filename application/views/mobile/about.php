  <section class="w-section mobile-wrapper">
    <div class="page-content" id="main-stack">
      <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
        <div class="w-container">
		  <?php echo $menu?>
          <div class="wrapper-mask" data-ix="menu-mask"></div>
          <div class="navbar-title"><?php if( $config->site_name!="" ) { echo $config->site_name; } else { echo $config->name; } ?></div>
          <div class="w-nav-button navbar-button left" id="menu-button" data-ix="hide-navbar-icons">
            <div class="navbar-button-icon home-icon">
              <div class="bar-home-icon"></div>
              <div class="bar-home-icon"></div>
              <div class="bar-home-icon"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="body">
        <div class="news-container item-new">
          <div>
            <div class="grey-header">
              <h2 class="grey-heading-title"><?php echo lang("menu.aboutus");?></h2>
            </div>
            <div class="text-new no-borders">
              <div>
                <div class="separator-fields"></div>
                
              <div>
        				<p class="description-new">
                  <?php echo $config->content;?>
        				</p>
                <div class="separator-button"></div>
                <div id="map" class="gmaps margin-bottom-40" style="border:1px solid #cacaca; height:300px;"></div>
              

              <hr style="margin:10px 0px 10px 0px;"/>
              
              <strong><?php if( $config->site_name!="" ) { echo $config->site_name; } else { echo $config->name; } ?></strong><br>
              <i class="fa fa-map-marker"></i> <?php echo toeng($config->new_address);?><br>
              <i class="fa fa-map-marker"></i> <?php echo toeng($config->address);?><br>
              <abbr title="Phone">P:</abbr> <?php echo $config->tel;?><br>
              <abbr title="Phone">F:</abbr> <?php echo $config->fax;?><br>

              <a href="mailto:<?php echo $config->email;?>"><?php echo $config->email;?></a>

              <br><br><strong>사업자정보</strong><br>
                사업자등록번호: <?php echo $config->biznum;?><br/>
                <?php if($config->INSTALLATION_FLAG!="1") {?>
                <?php echo lang("site.renum");?>: <?php echo $config->renum;?><br><?php }?>
                대표자: <?php echo $config->ceo;?>
              
              </div>

            </div>
    		  </div>
        </div>
      </div>
    </div>
    <div class="page-content loading-mask" id="new-stack">
      <div class="loading-icon">
        <div class="navbar-button-icon icon ion-load-d"></div>
      </div>
    </div>
    <div class="shadow-layer"></div>
  </section>


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