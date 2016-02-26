  <section class="w-section mobile-wrapper">
    <div class="page-content" id="main-stack">
      <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
        <div class="w-container">
          <?php echo $menu?>
          <div class="wrapper-mask" data-ix="menu-mask"></div>
          <div class="navbar-title"><?php echo $category->name?></div>
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
      <div class="news-mask"></div>
      <div class="news-container">
        <ul class="w-clearfix list-news">
		  <?php foreach($result as $val){?>
          <li class="list-item-new">
            <a class="w-inline-block" href="/mobile/news_view/<?php echo $val->id?>">
              <div class="image-new" style="background-image:url('/uploads/news/thumb/<?php echo $val->thumb_name?>')"></div>
              <div class="text-new">
                <h2 class="title-new"><?php echo $val->title?></h2>
                <p class="description-new"><?php echo cut(strip_tags($val->content),200)?></p>
              </div>
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