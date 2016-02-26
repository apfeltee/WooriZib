<style>
.dropdown-menu li{
	border:none;
	padding:0;
}
.divider {
	margin:5px !important;
}
</style>
<!-- BEGIN TOP BAR -->
<div class="pre-header">
    <div class="_container">

        <div class="row" style="position:relative;">
            <img src="/assets/common/img/bookmark.png" style="position:absolute;left:2px;top:-10px;">
            <!-- BEGIN TOP BAR LEFT PART -->
            <div class="col-md-6 col-sm-6 col-xs-3">
                <ul class="list-unstyled list-inline">
					<?php if(strpos(element('HTTP_USER_AGENT',$_SERVER), 'Dungzi/') !== false){?>
					<li><a href="tel:<?php echo $config->mobile;?>"><?php echo $config->mobile;?></a></li>
					<?php } else { ?>
					<li <?php if(lang("topbar_ment")==""){echo "style='border:0px;'";}?>><a href="#" class="bookmarkMeLink" style="color:#FCF102;font-weight:bold;padding-left:5px;"><?php echo lang('site_menu_bookmark');?></a></li>
					<?php }?>
                    <!--li><i class="fa fa-phone"></i><span>1566-2395</span></li-->
                    <li class="hidden-xs"><?php echo lang("topbar_ment");?></li>
                </ul>
            </div>
            <!-- END TOP BAR LEFT PART -->
            <!-- BEGIN TOP BAR MENU -->
            <div class="col-md-6 col-sm-6 col-xs-9">
                <ul id="myarea" class="list-unstyled list-inline pull-right">
					<?php if( $this->config->item('menu_name')!="" && $this->config->item('menu_link')!=""){?>
					<li><a href="<?php echo $this->config->item('menu_link');?>" target="_blank"><?php echo $this->config->item('menu_name');?></a></li>
					<?php } ?>
					<?php if($this->session->userdata("id")!=""){?>
						<?php if($config->USER_PRODUCT){?>
						<li><a href="/member/product"><?php echo lang('product')."관리";?></a></li>
						<?php } ?>
						<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><?php echo $this->session->userdata("name");?>님<b class="caret" style="margin-left:5px;"></b></a>
							<ul class="dropdown-menu">
								<li class="divider"></li>
								<li><a href="/member/profile"><?php echo lang("menu.modifyprofile");?></a></li>
								<li class="divider"></li>
								<li><a href="/member/history"><?php echo lang("site.seen");?></a></li>
								<li><a href="/member/hope"><?php echo lang("site.saved");?></a></li>
								<li class="divider"></li>
								<?php if($config->BUILDING_ENQUIRE){?>
								<li><a href="/member/building_enquire_list">건축물자가진단 의뢰</a></li>
								<li class="divider"></li>
								<?php }?>
								<li><a href="/member/logout"><?php echo lang('menu.logout');?></a></li>
								<li class="divider"></li>
							</ul>
						</li>
					<?php } else { ?>
						<?php if($config->MEMBER_JOIN){?>
							<li><a href="/member/signin"><?php echo lang('menu.login');?></a></li>
							<?php if($config->MEMBER_TYPE=="general" || $config->MEMBER_TYPE=="both"){?>
							<li><a href="/member/signup"><?php echo lang('menu.signup');?></a></li>
							<?php } ?>
							<?php if($config->MEMBER_TYPE=="biz" || $config->MEMBER_TYPE=="both"){?>
							<li><a href="/member/signup/biz">공인중개사 <?php echo lang('menu.signup');?></a></li>
							<?php } ?>
						<?php } ?>
						<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user" aria-hidden="true" style="margin-right:5px;"></span><?php echo lang("menu.mypage");?><b class="caret" style="margin-left:5px;"></b></a>
							<ul class="dropdown-menu">
								<li><a href="/member/history"><?php echo lang("site.seen");?></a></li>
								<li><a href="/member/hope"><?php echo lang("site.saved");?></a></li>
							</ul>
						</li>
					<?php } ?>
                </ul>
            </div>
            <!-- END TOP BAR MENU -->
        </div>
    </div>        
</div>
<!-- END TOP BAR -->

<!-- 메뉴 영역 시작 -->	
<div class="header">
  <div class="_container">
	<a class="site-logo" href="/"><?php if($config->logo==""){echo "<img src='/assets/common/img/dungzi.png' class=\"img-responsive\">";} else {?><img src="/uploads/logo/<?php echo $config->logo;?>" alt="<?php echo $config->name;?>" class="img-responsive"/><?php }?></a>

	<!-- BEGIN NAVIGATION -->
	<div class="header-navigation pull-right font-transform-inherit">
	  <ul>
		<?php foreach($mainmenu as $key=>$val){?>

	  		<?php if($val->type=="main") { ?>
				<?php if($config->LIST_ENCLOSED && !$this->session->userdata("id")){?>
					<li <?php if($this->uri->segment(1)=="main" && $this->uri->segment(2)!="intro") {echo "class='active'";}?>><a href="javascript:void(0)" class="leanModal" lean-id="#signup"><?php echo $val->title;?></a></li>
				<?php }else{?>
					<li <?php if($this->uri->segment(1)=="main" && $this->uri->segment(2)!="intro") {echo "class='active'";}?>><a href="/main/index"><?php echo $val->title;?></a></li>				
				<?php }?>		
	  		<?php } else if($val->type=="enquire") { ?>
					<li class="dropdown <?php if(($this->uri->segment(1)=="member" && $this->uri->segment(2)=="enquire")||$this->uri->segment(1)=="ask") echo "active";?>">
						<a href="/member/enquire" class="dropdown-toggle"><?php echo $val->title;?></a>
						<ul class="dropdown-menu">
						<?php if($config->ENQUIRE_TYPE){?>
							<li><a href="/member/enquire/"><?php echo lang('enquire.title');?></a></li>
							<li><a href="/ask/index"><?php echo lang('qna_title');?></a></li>
						<?php } else {?>
							<li><a href="/member/enquire/buy"><?php echo lang("site.buying");?></a></li>
							<?php if($config->ENQUIRE_SELL){?>
							<li><a href="/member/enquire/sell"><?php echo lang("site.selling");?></a></li>
							<?php }?>
							<li><a href="/ask/index"><?php echo lang('qna_title');?></a></li>						
						<?php }?>
						</ul>
					</li>
			<?php } else if($val->type=="product_add") { ?>
					<li <?php if($this->uri->segment(1)=="member" && $this->uri->segment(2)=="product_add") {echo "class='active'";}?>><a href="/member/product_add"><?php echo $val->title;?></a></li>
	  		<?php } else if($val->type=="news") { ?>
				<?php if($news_use_count==1){?>		
					<li <?php if($this->uri->segment(1)=="news") {echo "class='active'";}?>><a href="/news/view/<?php echo $news_use_id?>"><?php echo $val->title;?></a></li>
				<?php } else {?>
					<li class="dropdown <?php if($this->uri->segment(1)=="news") echo "active";?>">
						<a href="/news/index" class="dropdown-toggle"><?php echo $val->title;?></a>
						<ul class="dropdown-menu">
							<?php foreach($menu_news as $val){?>
							<li><a href="/news/index/<?php echo $val->id?>"><?php echo $val->name?></a></li>
							<?php }?>
						</ul>
					</li>
				<?php }	?>
	  		<?php } else if($val->type=="gallery") { ?>
				<li <?php if($this->uri->segment(1)=="portfolio") {echo "class='active'";}?>><a href="/portfolio/index"><?php echo $val->title;?></a></li>
	  		<?php } else if($val->type=="company") { ?>
				<?php if(count($menu_intro)==1){?>
					<li <?php if($this->uri->segment(2)=="intro") {echo "class='active'";}?>><a href="/main/intro"><?php echo $val->title;?></a></li>
				<?php } else { ?>
					<li class="dropdown <?php if($this->uri->segment(2)=="intro") echo "active";?>">
						<a href="/main/intro" class="dropdown-toggle"><?php echo $val->title;?></a>
						<ul class="dropdown-menu">
							<?php foreach($menu_intro as $intro){?>
							<li><a href="/main/intro/<?php echo $intro->id?>"><?php echo ($val->id==1) ? $val->title : $intro->title;?></a></li>
							<?php }?>
						</ul>
					</li>
				<?php }?>
	  		<?php } else if($val->type=="installation") { ?>
				<li <?php if($this->uri->segment(2)=="installation") {echo "class='active'";}?>><a href="/installation/index"><?php echo $val->title;?></a></li>
			<?php } else if($val->type=="building") { ?>
				<li <?php if($this->uri->segment(1)=="member" && $this->uri->segment(2)=="building_enquire") {echo "class='active'";}?>><a href="/member/building_enquire"><?php echo $val->title;?></a></li>
			<?php } ?>
	  	<?php } ?>
	  </ul>
	</div>
	<!-- END NAVIGATION -->
  </div>
</div>	
<!-- 메뉴 영역 종료 -->