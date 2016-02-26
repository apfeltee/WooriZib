  <section class="w-section mobile-wrapper">
    <div class="page-content" id="main-stack">
      <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
        <div class="w-container">
          <?php echo $menu?>
          <div class="wrapper-mask" data-ix="menu-mask"></div>
          <div class="navbar-title">뉴스</div>
          <div class="w-nav-button navbar-button left" id="menu-button" data-ix="hide-navbar-icons">
            <div class="navbar-button-icon home-icon">
              <div class="bar-home-icon"></div>
              <div class="bar-home-icon"></div>
              <div class="bar-home-icon"></div>
            </div>
          </div>
		  <a href="#" class="w-inline-block navbar-button right" onclick="onBackKeyDown();">
			<div class="navbar-button-icon icon ion-ios-close-empty"></div>
		  </a>
        </div>
      </div>
      <div class="body">
        <div class="news-container item-new">
          <div>
            <div><img src="/uploads/news/<?php echo $result->thumb_name?>"/></div>
            <div class="text-new">
              <div class="separator-fields"></div>
              <h2 class="title-new"><?php echo $result->title?></h2>
              <div class="separator-fields"></div>
              <p class="description-new"><?php echo $result->content?></p>
              <div class="separator-button"></div>
              <div class="separator-button"></div>			  
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