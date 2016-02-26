<!-- 좌측 메뉴 시작 -->
      <nav class="w-nav-menu nav-menu" role="navigation">
        <div class="nav-menu-header">
          <div class="logo"><a href="/mobile/home"><?php if($config->logo==""){echo "<img src='/assets/common/img/dungzi.png' class=\"img-responsive\">";} else {?><img src="/uploads/logo/<?php echo $config->logo;?>" alt="<?php echo $config->name;?>" class="img-responsive"/><?php }?></a></div>
          <div class="slogan"><?php if( $config->site_name!="" ) { echo $config->site_name; } else { echo $config->name; } ?></div>
        </div>
        <a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/home" data-load="1">
          <div class="icon-list-menu">
            <div class="icon ion-ios-home-outline"></div>
          </div>
          <div class="nav-menu-titles">홈</div>
        </a>
        <?php if($config->USER_PRODUCT && $this->session->userdata("id")!=""){ ?>
        <a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/product_add">
          <div class="icon-list-menu">
              <div class="icon ion-ios-compose-outline"></div>
          </div>
          <div class="nav-menu-titles"><?php echo lang('product');?> <?php echo lang("site.submit");?></div>
        </a>
		<?php }?>
        <?php if($config->USER_PRODUCT && $this->session->userdata("id")!=""){ ?>
        <a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/product">
          <div class="icon-list-menu">
              <div class="icon ion-ios-browsers-outline"></div>
          </div>
          <div class="nav-menu-titles"><?php echo lang('product');?>관리</div>
        </a>
		<?php }?>
		<?php foreach($mainmenu as $key=>$val){?>
			<?php if($val->type=="main") { ?>
			<a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/grid">
			  <div class="icon-list-menu">
				<div class="icon ion-ios-list-outline"></div>
			  </div>
			  <div class="nav-menu-titles"><?php echo lang("product")?> <?php echo lang("site.list");?></div>
			</a>
			<a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/map/clear">
			  <div class="icon-list-menu">
				<div class="icon ion-map"></div>
			  </div>
			  <div class="nav-menu-titles"><?php echo lang("site.map");?> <?php echo lang("site.search");?></div>
			</a>
			<a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/search">
			  <div class="icon-list-menu">
				<div class="icon ion-ios-search"></div>
			  </div>
			  <div class="nav-menu-titles"><?php echo lang("site.search");?></div>
			</a>
			<a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/seen">
			  <div class="icon-list-menu">
				<div class="icon ion-ios-eye-outline"></div>
			  </div>
			  <div class="nav-menu-titles"><?php echo lang("site.seen");?></div>
			</a>
			<?php } else if($val->type=="news") { ?>
			<a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/news" data-load="1">
			  <div class="icon-list-menu">
				<div class="icon ion-ios-paper-outline"></div>
			  </div>
			  <div class="nav-menu-titles"><?php echo $val->title;?></div>
			</a>
			<?php } else if($val->type=="gallery") { ?>
			<!-- 일단 갤러리는 추후에~ -->
			<?php } else if($val->type=="company") { ?>
			<!-- 회사소개도 추후에~ -->
			<?php } ?>
		<?php }?>
        <a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/hope">
          <div class="icon-list-menu">
            <div class="icon ion-ios-heart-outline"></div>
          </div>
          <div class="nav-menu-titles"><?php echo lang("site.saved");?></div>
        </a>
        <a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/notice" data-load="1">
          <div class="icon-list-menu">
            <div class="icon ion-ios-chatbubble-outline"></div>
          </div>
          <div class="nav-menu-titles"><?php echo lang("menu.notice");?></div>
        </a>

        <?php if($this->session->userdata("id")==""){ ?>
        <a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/signin">
          <div class="icon-list-menu">
              <div class="icon ion-ios-checkmark-outline"></div>
          </div>
          <div class="nav-menu-titles"><?php echo lang("menu.login");?></div>
        </a>
        <?php }else{?>
		<a class="w-clearfix w-inline-block nav-menu-link" href="/mobile/logout">
		<div class="icon-list-menu">
			<div class="icon ion-ios-minus-outline"></div>
		</div>
		<div class="nav-menu-titles">로그아웃</div>
		</a>
		<?php }?>
        <div class="separator-bottom"></div>
        <h3 class="menu_tel"><a href="tel:<?php echo $config->tel;?>"><?php echo $config->tel;?> <i class="icon ion-social-whatsapp"></i></a></h3>
        <div class="separator-bottom"></div>
        <div class="separator-bottom"></div>
      </nav>