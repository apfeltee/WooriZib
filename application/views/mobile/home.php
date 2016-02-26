<script>
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

	login_leanModal();
});
function go_search(name,value){
    if(name=="theme[]") $("input[name='category[]']").val("");  /** 임시 **/
    if(name=="category[]") $("input[name='theme[]']").val("");  /** 임시 **/
	$("input[name='"+name+"']").val(value);
	$("#search_form").submit();
}
</script>
<div class="page-content woorizib_m" id="main-stack">
  <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
    <div class="w-container">
      <!-- 상단 시작 -->
      <?php echo $menu;?>
      <div class="wrapper-mask" data-ix="menu-mask"></div>
      <div class="navbar-title">
        <?php if($config->logo==""){echo "<img src='/assets/common/img/dungzi.png' class=\"img-responsive\">";} else {?><img src="/assets/mobile/images/transform/logo.png" alt="<?php echo $config->name;?>" class="img-responsive" style="display:inline"/><?php }?>
      </div>
      <div class="w-nav-button navbar-button left" id="menu-button" data-ix="hide-navbar-icons">
        <div class="navbar-button-icon home-icon">
          <div class="bar-home-icon"></div>
          <div class="bar-home-icon"></div>
          <div class="bar-home-icon"></div>
        </div>
      </div>
      <!-- <a class="w-inline-block navbar-button right" href="/mobile/search">
        <div class="navbar-button-icon smaller icon ion-ios-search"></div>
      </a> -->
      <!-- 상단 종료 -->
    </div>
  </div>
  <div class="top_banner"></div> 
  <!-- <div class="body padding well"> -->
  <div class="body">
    <?php if($config->kakaochat!=""){?>
    <div class="kakaochat">
      <a href="http://open.kakao.com/o/<?php echo $config->kakaochat;?>">
      <img src="/assets/common/img/kakaoopenchat.png" style="height:50px;width:50px;">
      </a>
    </div>
    <?php } else { ?>
    <a href="sms:<?php echo $config->mobile;?>"><div class="sms-button icon ion-android-mail"></div></a>
    <?php } ?>
    
    <a href="tel:<?php echo $config->tel;?>"><div class="call-button icon ion-ios-telephone"></div></a>
    <div class="grid">
      <div class="col-1-2">
         <div class="content">
			  <a href="/mobile/map/clear" <?php if($config->LIST_ENCLOSED && !$this->session->userdata("id")) echo 'class="leanModal" lean-id="#signup"';?>>
					<div class="btn_map btn-1-2 grid_btn"></div>
					<!-- <?php echo lang("site.map");?> <?php echo lang("site.search");?> -->
			  </a>
         </div>
      </div>
      <div class="col-1-2">
         <div class="content">
			  <a href="/mobile/grid" <?php if($config->LIST_ENCLOSED && !$this->session->userdata("id")) echo 'class="leanModal" lean-id="#signup"';?>>
					<div class="btn_list btn-2-2 grid_btn"></div>
					<!-- <?php echo lang("site.list");?> <?php echo lang("site.search");?> -->
			  </a>
         </div>
      </div>
      <div class="col-1-2">
         <div class="content">
			  <a href="/mobile/area" <?php if($config->LIST_ENCLOSED && !$this->session->userdata("id")) echo 'class="leanModal" lean-id="#signup"';?>>
					<div class="btn_location btn-1-2 grid_btn"></div>
					<!-- 지역 <?php echo lang("site.search");?> -->
			  </a>
         </div>
      </div>
      <div class="col-1-2">
         <div class="content">
			  <!-- <a href="/mobile/subway" <?php if($config->LIST_ENCLOSED && !$this->session->userdata("id")) echo 'class="leanModal" lean-id="#signup"';?>> -->
        <a href="/mobile/search" <?php if($config->LIST_ENCLOSED && !$this->session->userdata("id")) echo 'class="leanModal" lean-id="#signup"';?>>
          <div class="btn_detail btn-2-2 grid_btn"></div>
					<!-- <?php echo lang("site.subway");?> <?php echo lang("site.search");?> -->
			  </a>
         </div>
      </div>
    </div>

  </div>
  <div class="padding">

    <form action="/search/set_search/area/1" name="search_form" id="search_form" method="post">
    <div class="separator-fields" style="margin-top:10px;"></div>
    <h2 class="title-new"><?php echo lang("product");?> 종류별 검색</h2>
    <div class="grid grid-pad">
    <input type="hidden" name="category[]" value=""/>
      <?php foreach($category as $val){?>
      <div class="col-2-4"><div class="content category"><a href="javascript:go_search('category[]',<?php echo $val->id?>)" <?php if($config->LIST_ENCLOSED && !$this->session->userdata("id")) echo 'class="leanModal" lean-id="#signup"';?>><?php echo $val->name?></a></div></div>
      <?php } ?>
    </div>

    <?php if($is_theme=="Y"){ ?>
    <div class="separator-fields" style="margin-top:10px;"></div>
    <h2 class="title-new"><?php echo lang("product.theme")?><?php echo lang("site.search");?></h2>    

    <div class="grid grid-pad">
    <input type="hidden" name="theme[]" value=""/>
      <?php foreach($theme as $key=>$val){?>
      <div class="col-<?php echo $val->col?>-4" onclick="javascript:go_search('theme[]',<?php echo $val->id?>)">
         <div class="content cell" style="background-image:url(<?php if($val->image){?>/uploads/theme/<?php echo $val->image?><?php }else{?>/assets/common/img/bg/theme/back<?php echo $key?>.jpg<?php }?>)">
              <div class="cell_holder" data-id="<?php echo $val->id;?>">
              <a class="cover-wrapper">
                <?php echo $val->theme_name;?>
              </a>            
            </div>          
            <div>
              <a class="cover-wrapper">
                <?php echo $val->theme_name;?>
              </a>
            </div>
         </div>
      </div>
      <?php } ?>
    </div>
    <?php } ?>

	<?php if($service_valid=="Y" && count($service)>0){?>
	<div class="separator-fields"></div>
	<h2 class="title-new"><?php echo $service_title;?></h2>
	<div class="service-box">
		<div class="grid grid-pad">
			<?php foreach($service as $key=>$val){?>
			<div class="col-2-4">
				<div class="service-cell">
					<a href="<?php echo $val->link?>">
						<h5 class="text-center"><?php echo $val->service_name?></h5>
						<img src="/uploads/theme/<?php echo $val->image?>" class="img-responsive" style="max-height:100px;">
						<p style="margin-top:5px;"><?php echo cut($val->description,55)?></p>
					</a>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
	<?php }?>

  </form>
  </div>
  <?php if($config->GPLAY!=""){?>
  <div style="margin-top:20px;height:80px;padding-bottom:10px;background-color:#<?php echo $this->config->item('skin_color');?>;">
  <a href="https://market.android.com/details?id=<?php echo $config->GPLAY;?>">
  <div style="float:left;width:30%;padding:20px 10px 0px 10px;">
    <img src="/assets/mobile/images/gplay.png">
  </div>
  <div style="float:left;width:69%;padding:10px;">
    <p style="color:white;font-size:15px;font-weight:900;"><?php if($config->site_name!="") {echo $config->site_name;} else {echo $config->name;}?> 앱을</p>
    <img src="/assets/mobile/images/download.png">
  </div>
  </a>
  </div>
  <?php }?>
  <div class="padding" style="margin-top:20px;border-top:1px solid #cacaca;color:#676767;font-size:11px;">
      <?php echo anchor("mobile/about",$config->name);?>, <?php echo lang("site.ceo");?>: <?php echo $config->ceo;?> <br/>
      <?php echo lang("site.biznum");?>: <?php echo $config->biznum;?>
      <?php if($config->renum!=""){?> | <?php echo lang("site.renum");?>: <?php echo $config->renum;?><?php } ?><br>
      <?php echo lang("site.tel");?>: <?php echo $config->tel;?> | <?php echo lang("site.fax");?>: <?php echo $config->fax;?> <br>
      <?php echo anchor("mobile/rule",lang("menu.uselaw"));?> | <?php echo anchor("mobile/privacy",lang("menu.infolaw"));?> | <?php echo anchor("mobile/location",lang("menu.positionlaw"));?>
      <?php if($config->email!=""){?><br/><a href="mailto:<?php echo $config->email;?>"> <?php echo $config->email;?></a><?php }?>
      <?php if($config->DUNGZI=="1"){?>
      .powered by <a href="http://www.dungzi.com/">dungzi.com</a>
      <?php }?>

      <div class="social_link">
          <?php if(isset($social->naver_cafe) && $social->naver_cafe){?>
          <a href="http://<?php echo $social->naver_cafe;?>"><img src="/assets/common/img/icon_cafe.png"></a>
          <?php } if(isset($social->naver_blog) && $social->naver_blog){?>
          <a href="http://<?php echo $social->naver_blog;?>"><img src="/assets/common/img/icon_blog.png"></a>
          <?php } if(isset($social->facebook) && $social->facebook){?>
          <a href="http://<?php echo $social->facebook;?>"><img src="/assets/common/img/icon_facebook.png"></a>
          <?php } if(isset($social->twitter) && $social->twitter){?>
          <a href="http://<?php echo $social->twitter;?>"><img src="/assets/common/img/icon_twitter.png"></a>
          <?php } if(isset($social->google_plus) && $social->google_plus){?>
          <a href="http://<?php echo $social->google_plus;?>"><img src="/assets/common/img/icon_plus.png"></a>
          <?php } if(isset($social->youtube_channel) && $social->youtube_channel){?>
          <a href="http://<?php echo $social->youtube_channel;?>"><img src="/assets/common/img/icon_youtube.png"></a>
          <?php }?>
      </div>

  </div>

</div>
