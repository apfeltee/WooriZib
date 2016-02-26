<script language="javascript">
$(document).ready(function() {
	$(".submenu_link").mouseover(function(){
		$(".submenu").hide();
		$("#submenu_"+$(this).attr("data-id")).show();
	});

	$(".submenu").mouseleave(function(){
		$(".submenu").hide();
	});		
});

function go_theme(v){
	location.href='/search/set_theme/'+v;
}
</script>
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
<?php if($this->uri->segment(1)==""){?>
<div style="background-color:#A7053E;">
	<div class="_container">
		<a href="/news/view/1"><img src="/assets/theme002/img/top.gif"/></a>
	</div>
</div>
<?php }?>
<!--div class="pre-header">
    <div class="_container">

        <div class="row" style="position:relative;">
            <img src="/assets/common/img/bookmark.png" style="position:absolute;left:2px;top:-10px;">
            <div class="col-md-6 col-sm-6 col-xs-3">
                <ul class="list-unstyled list-inline">
					<?php if(strpos(element('HTTP_USER_AGENT',$_SERVER), 'Dungzi/') !== false){?>
					<li><a href="tel:<?php echo $config->mobile;?>"><?php echo $config->mobile;?></a></li>
					<?php } else { ?>
					<li <?php if(lang("topbar_ment")==""){echo "style='border:0px;'";}?>><a href="#" class="bookmarkMeLink" style="color:#FCF102;font-weight:bold;padding-left:5px;"><?php echo lang('site_menu_bookmark');?></a></li>
					<?php }?>
                    <li class="hidden-xs"><?php echo lang("topbar_ment");?></li>
                </ul>
            </div>
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
								<li><a href="/member/logout"><?php echo lang('menu.logout');?></a></li>
								<li class="divider"></li>
							</ul>
						</li>
					<?php } else { ?>
						<?php if($config->MEMBER_JOIN){?>
						<li><a href="/member/signin"><?php echo lang('menu.login');?></a></li>
						<li><a href="/member/signup"><?php echo lang('menu.signup');?></a></li>
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
        </div>
    </div>        
</div-->
<!-- END TOP BAR -->

<!-- 메뉴 영역 시작 -->	
<div class="header">
  <div class="_container">
  	<div class="row">
  		<div class="col-lg-4 padding-top-10">
  			<img src="/assets/theme002/img/top_left.png"/>
  		</div>
  		<div class="col-lg-4 logoarea">
  			<a href="/"><?php if($config->logo==""){echo "<img src='/assets/common/img/dungzi.png' class=\"img-responsive\">";} else {?><img src="/uploads/logo/<?php echo $config->logo;?>" alt="<?php echo $config->name;?>" class="img-responsive"/><?php }?></a>
  		</div>
  		<div class="col-lg-4 padding-top-10 text-right">
			<img src="/assets/theme002/img/top_right.png"/>
  		</div>
  	</div>
   </div>
</div>	
<!-- 메뉴 영역 종료 -->

<div style="background-color:#000;">
	<div class="_container">
		<div class="row">
			<div class="col-lg-2" style="text-align:center;padding-top:6px;padding-bottom:6px;"><a href="#" class="submenu_link" data-id="1"><img src="/assets/theme002/img/menu_1.png"></a></div>
			<div class="col-lg-2" style="text-align:center;padding-top:6px;padding-bottom:6px;"><a href="#" class="submenu_link" data-id="2"><img src="/assets/theme002/img/menu_2.png"></a></div>
			<div class="col-lg-2" style="text-align:center;padding-top:6px;padding-bottom:6px;"><a href="#" class="submenu_link" data-id="3"><img src="/assets/theme002/img/menu_3.png"></a></div>
			<div class="col-lg-2" style="text-align:center;padding-top:6px;padding-bottom:6px;"><a href="#" onclick="go_theme('21');"><img src="/assets/theme002/img/menu_4.png"></a></div>
			<div class="col-lg-2" style="text-align:center;padding-top:6px;padding-bottom:6px;"><a href="#" onclick="go_theme('22');"><img src="/assets/theme002/img/menu_5.png"></a></div>
			<div class="col-lg-2" style="text-align:center;padding-top:6px;padding-bottom:6px;"><a href="/portfolio/"><img src="/assets/theme002/img/menu_6.png"></a></div>
		</div>
	</div>
	<div class="_container" style="position:relative;">
		<div id="submenu_1" class="row submenu" style="position:absolute;width:100%;top:0px;z-index:1000;display:none;">
			<div class="row" style="position:relative;">
				<div style="background-color:white;padding:10px;float:left;">
					<a href="#" onclick="go_theme('1');"><img src="/assets/theme002/img/theme_1.jpg"></a>
					<a href="#" onclick="go_theme('2');"><img src="/assets/theme002/img/theme_2.jpg"></a>
					<a href="#" onclick="go_theme('3');"><img src="/assets/theme002/img/theme_3.jpg"></a>
					<div style="clear:both"></div>
				</div>
			</div>
		</div>
		<div id="submenu_2" class="row submenu" style="position:absolute;width:100%;top:0px;z-index:1000;display:none">
			<div class="row" style="position:relative;">
				<div class="col-lg-12" style="background-color:white;padding:10px;">
					<a href="#" onclick="go_theme('6');"><img src="/assets/theme002/img/theme_price_1.png"></a>
					<a href="#" onclick="go_theme('7');"><img src="/assets/theme002/img/theme_price_2.png"></a>
					<a href="#" onclick="go_theme('8');"><img src="/assets/theme002/img/theme_price_3.png"></a>
					<a href="#" onclick="go_theme('9');"><img src="/assets/theme002/img/theme_price_4.png"></a>
					<a href="#" onclick="go_theme('10');"><img src="/assets/theme002/img/theme_price_5.png"></a>
				</div>
			</div>
		</div>		
		<div id="submenu_3" class="row submenu" style="position:absolute;width:100%;top:0px;z-index:1000;display:none">
			<div class="row" style="position:relative;">
				<div class="col-lg-12" style="background-color:white;padding:10px;">
					
						<a href="#" onclick="go_theme('11');"><img src="/assets/theme002/img/subway_1.png"></a>
						<a href="#" onclick="go_theme('12');"><img src="/assets/theme002/img/subway_2.png"></a>
						<a href="#" onclick="go_theme('13');"><img src="/assets/theme002/img/subway_3.png"></a>
						<a href="#" onclick="go_theme('14');"><img src="/assets/theme002/img/subway_4.png"></a>
						<a href="#" onclick="go_theme('15');"><img src="/assets/theme002/img/subway_5.png"></a><br/>
						<a href="#" onclick="go_theme('16');"><img src="/assets/theme002/img/subway_6.png"></a>
						<a href="#" onclick="go_theme('17');"><img src="/assets/theme002/img/subway_7.png"></a>
						<a href="#" onclick="go_theme('18');"><img src="/assets/theme002/img/subway_8.png"></a>
						<a href="#" onclick="go_theme('19');"><img src="/assets/theme002/img/subway_9.png"></a>
						<a href="#" onclick="go_theme('20');"><img src="/assets/theme002/img/subway_10.png"></a>
				</div>
			</div>
		</div>			
	</div>

</div>