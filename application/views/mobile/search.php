<link href="/assets/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/assets/mobile/css/search.css">
<link href="/assets/plugin/icheck/skins/square/red.css" rel="stylesheet">
<link href="/assets/plugin/nouislider/jquery.nouislider.css" rel="stylesheet">

<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places&language=ko&region=KR"></script>
<script src="/assets/plugin/jquery.geocomplete.min.js" type="text/javascript" charset="UTF-8"></script>
<script src="/assets/plugin/organictabs.jquery.js"></script>
<script type="text/javascript" src="/assets/mobile/js/search.js"></script>
<script src="/assets/plugin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>    
<script src="/assets/plugin/icheck/icheck.min.js" type="text/javascript"></script>
<script src="/assets/common/js/init.js"></script>
<script src="/assets/plugin/nouislider/jquery.nouislider.all.min.js"></script>
<script>
var sell_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("sell_unit");?></font>";
var price_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("price_unit");?></font>";

$(document).ready(function(){

	init_search("<?php echo element('type',$search);?>","<?php echo element('category',$search);?>","<?php echo element('category_sub',$search);?>");
	$("#search_tab").organicTabs();

	$(".search_item").change(function(){
		init_price();
	});

	category_sub_change();
});

function search_form_submit(){
	if($.isNumeric($("#search").val())){
		/*$('#search_form').attr("target","_blank");*/
	}
	$('#search_form').submit();
}

function search_form_reset(){
	$("#reset").val(1);
	$('#search_form').submit();
}
</script>
<div class="page-content" id="main-stack">
  <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
    <div class="w-container">
      <!-- 상단 시작 -->
      <?php echo $menu;?>
      <div class="wrapper-mask" data-ix="menu-mask"></div>
       <div class="navbar-title"><?php echo lang("product");?> <?php echo lang("site.search");?></div>
      <div class="w-nav-button navbar-button left" id="menu-button" data-ix="hide-navbar-icons">
        <div class="navbar-button-icon home-icon">
          <div class="bar-home-icon"></div>
          <div class="bar-home-icon"></div>
          <div class="bar-home-icon"></div>
        </div>
      </div>
	  <a class="w-inline-block navbar-button right" onclick="search_form_submit();">
        <div class="navbar-button-icon smaller ion-ios-checkmark-outline"></div>
      </a>
	  <a class="w-inline-block navbar-button right" href="/mobile/subway">
		<div class="navbar-button-icon smaller icon ion-android-subway"></div>
	  </a>
	  <a class="w-inline-block navbar-button right" href="/mobile/area">
		<div class="navbar-button-icon smaller icon ion-ios-location-outline"></div>
	  </a>
      <!--<a class="w-inline-block navbar-button right" onclick="search_form_submit();">
        <div class="navbar-button-icon smaller ion-ios-checkmark-outline"></div>
      </a>
      <a class="w-inline-block navbar-button right" onclick="search_form_reset();">
        <div class="navbar-button-icon smaller ion-ios-refresh-outline"></div>
      </a>-->
      <!-- 상단 종료 -->
    </div>
  </div>
  <div id="search_section" class="search-wrapper">
    <form  action="/search/set_search/<?php echo $direct?>/1" id="search_form" method="post">
      <input type="hidden" id="search_type" name="search_type" value="<?php echo element("search_type",$search);?>"/>
      <input type="hidden" id="search_value" name="search_value" value="<?php echo element("search_value",$search);?>"/>
      <input type="hidden" id="lat" name="lat" value="<?php echo element("lat",$search);?>"/>
      <input type="hidden" id="lng" name="lng" value="<?php echo element("lng",$search);?>"/>
	  <input type="hidden" id="keyword_front" name="keyword_front" value="<?php echo element("keyword_front",$search);?>"/>
	  <input type="hidden" id="zoom" name="zoom"/>
	  <input type="hidden" id="reset" name="reset"/>
	  <div>
			<ul>
				<li>
					<div class="input-group">
						<input type="text" id="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product.no");?>, <?php echo lang("site.title");?>" autocomplete="off" value="<?php if(element("search_type",$search)=="google") echo element("search_value",$search);?>" style="font-size: 12px;"/>
						<div class="input-group-btn">
							<button id="go_keyword" class="btn btn-default" type="button" style="padding:6px 10px;"><i class="glyphicon glyphicon-search"></i></button>
						</div>
					</div>
				</li>
				<div class="separator-fields"></div>
				<?php if($config->INSTALLATION_FLAG!="2"){?>
				<li>
					<div class="btn-group btn-group-justified btn-group-sm" role="group" data-toggle="buttons">
						<!-- input radio에 search_item을 걸면 갯수만큼 call이 나가기 때문에 안된다. -->
						<label class="btn btn-default type_label active">
							<input type="radio" name="type" class="search_item" value="" selected><?php echo lang("site.all");?>
						</label>
						<?php if($config->INSTALLATION_FLAG=="1"){?>
						<label class="btn btn-default type_label" style="color:#f39c12;">
							<input type="radio" name="type" class="search_item" value="installation"><?php echo lang('installation');?>
						</label>
						<?php }?>
						<label class="btn btn-default type_label" style="color:#D22129">
							<input type="radio" name="type" class="search_item" value="sell"><?php echo lang('sell');?>
						</label>
						<?php if(lang('full_rent')!=""){?>
						<label class="btn btn-default type_label">
							<input type="radio" name="type" class="search_item" value="full_rent"><?php echo lang('full_rent');?>
						</label>
						<?php } ?>
						<label class="btn btn-default type_label" style="color:#209F4E">
							<input type="radio" name="type" class="search_item" value="monthly_rent"><?php echo lang('monthly_rent');?>
						</label>
					</div>
				</li>
				<?php }?>					
				<li class="<?php if($config->INSTALLATION_FLAG!="2") echo "price_range";?> price_sell">
					<div class="price_label sell_label" style="<?php if($config->INSTALLATION_FLAG=="2") echo "display:none";?>"><?php echo lang('sell');?>가</div>
					<div class="price_label installation_label"><?php echo lang('product.price.installation.sell');?></div>
					<div class="price_show">
							<input type="hidden" id="sell_start" name="sell_start">
							<input type="hidden" id="sell_end" name="sell_end">
							<div id="sell_label"></div>
					</div>
					<div style="clear:both;"></div>
					<div class="price_slider">
						<div id="sell_range" class="slider" 
							data-start="<?php echo element('sell_start',$search) ? element('sell_start',$search) : "0" ;?>" 
							data-end="<?php echo element('sell_end',$search) ? element('sell_end',$search) : $config->SELL_MAX ;?>" 
							data-min="0" 
							data-max="<?php echo $config->SELL_MAX;?>"
							data-step="1"
							data-type="sell" ></div>
					</div>
				</li>
				<li class="price_range price_full">
					<div class="price_label"><?php echo lang('full_rent');?>가</div>
					<div class="price_show">
						<input type="hidden" id="full_start" name="full_start">
						<input type="hidden" id="full_end" name="full_end">
						<div id="full_label"></div>
					</div>
					<div style="clear:both;"></div>
					<div class="price_slider">
						<div id="full_range" class="slider" 
							data-start="<?php echo element('full_start',$search) ? element('full_start',$search) : "0" ;?>" 
							data-end="<?php echo element('full_end',$search) ? element('full_end',$search) : $config->FULL_MAX ;?>" 
							data-min="0" 
							data-max="<?php echo $config->FULL_MAX;?>"
							data-step="500"
							data-type="full"></div>
					</div>
				</li>
				<li class="price_range price_rent">
					<div class="price_label"><?php echo lang("product.price.deposit");?></div>
					<div class="price_show">
						<input type="hidden" id="month_deposit_start" name="month_deposit_start">
						<input type="hidden" id="month_deposit_end" name="month_deposit_end">
						<div id="month_deposit_label"></div>
					</div>
					<div style="clear:both;"></div>
					<div class="price_slider">
						<div id="month_deposit_range" class="slider"
							data-start="<?php echo element('month_deposit_start',$search) ? element('month_deposit_start',$search) : "0" ;?>" 
							data-end="<?php echo element('month_deposit_end',$search) ? element('month_deposit_end',$search) : $config->MONTH_DEPOSIT_MAX ;?>" 
							data-min="0" 
							data-max="<?php echo $config->MONTH_DEPOSIT_MAX;?>"
							data-step="50"
							data-type="month_deposit"></div>
					</div>
					<div class="price_label"><?php echo lang('monthly_rent');?>가</div>
					<div class="price_show">
						<input type="hidden" id="month_start" name="month_start">
						<input type="hidden" id="month_end" name="month_end">
						<div id="month_label"></div>
					</div>
					<div style="clear:both;"></div>
					<div class="price_slider">
						<div id="month_range" class="slider"
							data-start="<?php echo element('month_start',$search) ? element('month_start',$search) : "0" ;?>" 
							data-end="<?php echo element('month_end',$search) ? element('month_end',$search) : $config->MONTH_DEPOSIT_MAX ;?>" 
							data-min="0" 
							data-max="<?php echo $config->MONTH_MAX;?>"
							data-step="5"
							data-type="month"></div>
					</div>
				</li>
				<li>
					<h3><?php echo lang("search.type");?></h3>
				</li>
				<?php foreach($category as $val){ ?>
				<li style="white-space: nowrap;">
					<input type="checkbox" name="category[]" id="category_<?php echo $val->id;?>" value="<?php echo $val->id;?>" class='category_checkbox search_item'/>
					<label> <?php echo $val->name;?></label>
				</li>
				<?php 
				if(isset($val->category_sub)){
					foreach($val->category_sub as $sub_val){?>
					<li class="category_sub_<?php echo $val->id;?>" style="white-space: nowrap;display:none;">
						<i class="ion-arrow-right-b" style="padding-left:10px;"></i> &nbsp;<input type="checkbox" name="category_sub[]" value="<?php echo $sub_val->id;?>" main-id="<?php echo $val->id;?>" class="category_checkbox search_item"/>
						<label> <?php echo $sub_val->name;?></label>
					</li>
					<?php }?>
				<?php }?>
				<?php }?>
				<li <?php if(!$config->USE_THEME) {echo "style='display:none;'";}?>>
					<h3><?php echo lang("product.theme")?></h3>
				</li>
				<?php foreach($theme as $val){ ?>
				<li style="white-space: nowrap;<?php if(!$config->USE_THEME) {echo "display:none;";}?>">
					<input type="checkbox" name="theme[]" value="<?php echo $val->id;?>" class='category_checkbox search_item' <?php echo isset($val->checked)?$val->checked:"";?>/>
					<label> <?php echo $val->theme_name;?></label>
				</li>
				<?php }?>
				<?php if($config->USE_FACTORY) {?>
				<li>
					<h3 for="select-property-type">대지면적</h3>
				</li>
				<li>
					<select id="site_area" name="site_area" class="search_item form-control">
						<option value="">대지면적선택</option>
						<option value="500">500py이하</option>
						<option value="1000">500py~1000py이하</option>
						<option value="2000">1000py~2000py이하</option>
						<option value="3000">2000py~3000py이하</option>
						<option value="10000">3000py이상</option>
					</select>
				</li>
				<li>
					<h3 for="select-property-type">연면적</h3>
				</li>
				<li>
					<select id="law_area" name="law_area" class="search_item form-control">
						<option value="">연면적 선택</option>
						<option value="60">60py이하</option>
						<option value="100">60py~100py이하</option>
						<option value="200">100py~200py이하</option>
						<option value="400">200py~400py이하</option>
						<option value="1000">400py이상</option>
					</select>
				</li>	
				<!--li>
					<select id="theme" name="factory_power" class="search_item form-control margin-bottom-10">
						<option value="">전기(Kw)</option>
						<option value="1-10kw">1-10kw</option>
						<option value="11-30kw">11-30kw</option>
						<option value="31-50kw">31-50kw</option>
						<option value="51-100kw">51-100kw</option>
						<option value="101-250kw">101-250kw</option>
						<option value="251kw-300kw">251kw-300kw</option>
						<option value="300kw-500kw">300kw-500kw</option>
						<option value="500kw이상">500kw이상</option>
					</select>
				</li>
				<li>
					<select id="theme" name="factory_hoist" class="search_item form-control margin-bottom-10">
						<option value="">호이스트(ton)</option>
						<option value="0ton">0ton</option>
						<option value="1ton">1ton</option>
						<option value="2.8ton">2.8ton</option>
						<option value="3ton">3ton</option>
						<option value="5ton">5ton</option>
						<option value="7ton">7ton</option>
						<option value="10ton이상">10ton이상</option>
					</select>
				</li>
				<li>
					<select id="theme" name="factory_use" class="search_item form-control margin-bottom-10">
						<option value="">공장용도 선택</option>
						<option value="공장">공장</option>
						<option value="제조장">제조장</option>
						<option value="기타">기타</option>
					</select>
				</li-->
				<?php }?>
			</ul>

			<div class="separator-fields"></div>
			<div class="text-center">
				<a class="btn btn-default" style="width:45%" onclick="search_form_reset();"><i class="glyphicon glyphicon-refresh"></i> <?php echo lang("site.initfilter");?></a>
				<a class="btn btn-primary" style="width:45%" onclick="search_form_submit();"><i class="glyphicon glyphicon-search"></i> <?php echo lang("site.search");?></a>
			</div>
			<div class="separator-fields"></div>

		</div>
    </form>
  </div>
</div>