  <section class="w-section mobile-wrapper">
    <div class="page-content" id="main-stack">
      <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
        <div class="w-container">
          <?php echo $menu?>
          <div class="wrapper-mask" data-ix="menu-mask"></div>
          <div class="navbar-title"><?php echo $news_title?></div>
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
        <div class="hero-image">
          <div class="hero-image-title">
            <h2><?php echo $news_title?></h2>
            <!--div class="sub-title-small">&nbsp;3 of 10 tasks &nbsp;|&nbsp;&nbsp;75% completed</div-->
          </div>
          <div class="hero-image-img"><img src="/assets/mobile/images/photo-1429032021766-c6a53949594f.jpg">
          </div>
        </div>
        <ul class="list">
      <?php foreach($newscategory as $val){?>
          <li class="list-item" data-ix="list-item">
            <a class="w-clearfix w-inline-block" href="/mobile/news_list/<?php echo $val->id?>">
              <div class="icon-list highlighted">
                <div class="icon ion-ios-checkmark-empty"></div>
              </div>
              <div class="title-list"><?php echo $val->name?></div>
            </a>
          </li>
      <?php }?>
        </ul>
      </div>
    </div>
    <div class="page-content loading-mask" id="new-stack">
      <div class="loading-icon">
        <div class="navbar-button-icon icon ion-load-d"></div>
      </div>
    </div>
    <div class="shadow-layer"></div>
  </section>