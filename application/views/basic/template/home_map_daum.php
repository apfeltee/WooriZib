<script>
	var sell_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("sell_unit");?></font>";
	var price_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("price_unit");?></font>";
	var address_type="front"; /** 공개매물 등록된 주소 가져오도록 **/
</script>

<script src="/assets/plugin/organictabs.jquery.js"></script>
<script type="text/javascript" src="/script/src/search"></script>
<script>
var map;
var markers = [];
var spot;

$(document).ready(function(){
	
	index_initialize("<?php echo $config->lat;?>", "<?php echo $config->lng;?>");
	get_stat(map);

	init_search("","");

	$("#search_tab").organicTabs();

	$(".search_item").change(function(){
		init_price();
	});

	$(".icheck").iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
		increaseArea: '20%'
	});

	daum.maps.event.addListener(map, 'bounds_changed', function() {
		if(map.getLevel() > 7){
			$(".gugun").removeClass("gugun").addClass("gugun-small");
		}
		else{
			$(".gugun-small").removeClass("gugun-small").addClass("gugun");
		}
	});

	var category_sub_change = function(){
		$("input[name='category[]']").on('ifChanged', function(){
			if($(this).attr("checked")=="checked"){
				$(".category_sub_"+$(this).val()).slideDown("slow");
			}
			else{
				$(".category_sub_"+$(this).val()).attr("checked",false);
				$(".category_sub_"+$(this).val()).iCheck('uncheck');
				$(".category_sub_"+$(this).val()).iCheck('update');
				$(".category_sub_"+$(this).val()).slideUp("slow");
			}
		});
		
		$("input[name='category[]']").each(function(){
			if($(this).attr("checked")){
				$(".category_sub_"+$(this).val()).show();				
			}
			else{
				$(".category_sub_"+$(this).val()).attr("checked",false);
				$(".category_sub_"+$(this).val()).iCheck('uncheck');
				$(".category_sub_"+$(this).val()).iCheck('update');
			}
		});
	}

	category_sub_change();
});

function get_stat(map){

	var bounds = new daum.maps.LatLngBounds();
	var level = map.getLevel();
	level = level - <?php echo $config->HOME_MAP_ERROR;?>;

	if(level > 7){
		var gugun_icon = "gugun-small";
	}
	else{
		var gugun_icon = "gugun";
	}

	<?php foreach($stat as $key=>$val){ ?>
	markers[<?php echo $key;?>] = new daum.maps.CustomOverlay({
		map: map,
		position: new daum.maps.LatLng('<?php echo $val->parent_lat;?>', '<?php echo $val->parent_lng;?>'),
		content: "<div class='"+gugun_icon+" hvr-float-shadow'><a href='#' onclick=\"set_search('<?php echo $val->parent_id?>')\"><?php echo $val->label;?><?php if($this->config->item('home_number')=="1") { echo "(" . $val->cnt . ")"; }?></a></div>",
		yAnchor: 1
	});

	bounds.extend(new daum.maps.LatLng('<?php echo $val->parent_lat;?>', '<?php echo $val->parent_lng;?>'));
	<?php } ?>

	map.setBounds(bounds);
    map.setLevel(level);
}

function index_initialize(lat, lng) {
	var mapOptions = {
		level: 9,
		center: new daum.maps.LatLng(lat, lng)
	};

	map = new daum.maps.Map(document.getElementById('home_map'), mapOptions);
	var zoomControl = new daum.maps.ZoomControl();
	map.addControl(zoomControl, daum.maps.ControlPosition.LEFT);
	map.setZoomable(false); 
}

function set_search(id){
	<?php if($config->STATS=="gugun"){?>$("#search_type").val("parent_address");<?php }?>
	<?php if($config->STATS=="dong"){?> $("#search_type").val("address");<?php }?>
	$("#search_value").val(id);
	$("#search_form").trigger("submit");
}
</script>

<div class="page-slider margin-bottom-30">
		<?php if($map_code==2){ //바 형태 검색창 사용?>
		<div>
			<form  action="/search/set_search/main" id="search_form" method="post">
			<input type="hidden" id="search_type" name="search_type" autocomplete="off">
			<input type="hidden" id="search_value" name="search_value" autocomplete="off">
			<input type="hidden" id="zoom" name="zoom" autocomplete="off">
			<input type="hidden" id="lat" name="lat" autocomplete="off">
			<input type="hidden" id="lng" name="lng" autocomplete="off">
			<input type="hidden" id="keyword_front" name="keyword_front" autocomplete="off">
			<input type="hidden" id="region" name="region">
			<div class="band-wrapper padding-top-5 padding-bottom-5 text-center">
				<div class="inline-block">
					<?php foreach($category as $val){ ?>
						<input type="checkbox" name="category[]" id="category_<?php echo $val->id;?>" value="<?php echo $val->id;?>" class="icheck"> <label for="category_<?php echo $val->id;?>"> <?php echo $val->name;?></label>
					<?php }?>
				</div>
				<div class="inline-block">
					<?php if($config->INSTALLATION_FLAG!="2") {?>
					<select id="type" name="type"  class="form-control inline-block" style="width:120px;">
						<option value=""><?php echo lang("product.type");?></option>
						<?php if($config->INSTALLATION_FLAG!="0"){?><option value="installation"><?php echo lang('installation');?></option><?php }?>
						<?php if(lang('sell')!=""){?><option value="sell"><?php echo lang('sell');?></option><?php }?>
						<?php if(lang('full_rent')!=""){?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
						<?php if(lang('monthly_rent')!=""){?><option value="monthly_rent"><?php echo lang('monthly_rent');?></option><?php }?>
					</select>
					<?php }?>
					<select id="theme" name="theme" class="form-control inline-block">
						<option value="0"><?php echo lang("product.theme")?></option>
						<?php foreach($theme as $val){?>
						<option value="<?php echo $val->id;?>"><?php echo $val->theme_name;?></option>
						<?php }?>
					</select>
				</div>
				<div class="inline-block" style="width:230px;">
					<input type="text" id="search" name="search" placeholder="<?php echo lang("site.location");?>, <?php echo lang("site.subway");?>, <?php echo lang("product");?> <?php echo lang("site.number");?>, <?php echo lang("product");?> <?php echo lang("site.title");?>" class="form-control inline-block" autocomplete="off" style="width:150px;"/>
					<button type="submit" class="btn btn-search inline-block"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
				</div>
				<div style="clear:both"></div>
			</div>
			</form>
		</div>
		<?php }?>

		<div id="home_map"></div>

	    <!-- BEGIN TOP SEARCH -->
	    <div class="_container">
			<?php if($map_code==1){ //우측 형태 검색창 사용?>
	    	<div class="row">
	    		<div class="span3">
		          	<div class="search-box">

						<form action="/search/set_search/main" id="search_form" method="post">
							<input type="hidden" id="search_type" name="search_type">
							<input type="hidden" id="search_value" name="search_value">
							<input type="hidden" id="lat" name="lat">
							<input type="hidden" id="lng" name="lng">
							<input type="hidden" id="zoom" name="zoom" autocomplete="off">
							<input type="hidden" id="sido_val" name="sido_val" value="<?php echo $config->INIT_SIDO;?>">
							<input type="hidden" id="gugun_val" name="gugun_val" value="<?php if($config->INIT_SIDO) echo $config->INIT_GUGUN;?>">
							<input type="hidden" id="dong_val" name="dong_val">
							<input type="hidden" id="subway_local_val" name="subway_local_val">
							<input type="hidden" id="hosun_val" name="hosun_val">
							<input type="hidden" id="station_val" name="station_val">
							<input type="hidden" id="theme" name="theme[]">
							<input type="hidden" id="keyword_front" name="keyword_front">
							<input type="hidden" id="region" name="region">
							<div id="search_tab">
								<?php if($config->SEARCH_ORDER==2){?>
								<ul class="nav">
									<li class="nav-one">
										<a href="#google_section" class='current'><?php echo lang("search.total");?></a>
									</li>
									<li class="nav-two">
										<a href="#local_section"><?php echo lang("site.address");?></a>
									</li>
									<li class="nav-three las">
										<a href="#sybway_section"><?php echo lang("site.subway");?></a>
									</li>
								</ul>
								<div class="list-wrap">
									<ul id="google_section">
										<li>
											<input type="text" id="search" name="search" class="ui-autocomplete-input search_item" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product.no");?>, <?php echo lang("site.title");?>" autocomplete="off" value="">
										</li>
									</ul>
									<ul id="local_section" style='display:none;'>
										<li>
										<div class="row" style="margin:0px;">
											<?php if($config->REGION_USE){?>
											<div class="col-xs-12" style="padding:0px;">
												<select id="region" name="region" onchange="region_select(this.value);">
													<option value="">지역 선택</option>
													<?php foreach($region as $val){?>
													<option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
													<?php }?>
												</select>
											</div>
											<?php } else {?>
											<div class="col-xs-4" style="padding:0px;">
												<select id="sido" name="sido" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
											</div>
											<div class="col-xs-4" style="padding:0px;">
												<select id="gugun" name="gugun"><option value="">-</option></select>
											</div>
											<div class="col-xs-4" style="padding:0px;">
												<select id="dong" name="dong"><option value="">-</option></select>
											</div>
											<?php }?>
										</div>
										</li>
									</ul>
									<ul id="sybway_section" style='display:none;'>
										<li>
											<div class="row" style="margin:0px;">
												<div class="col-xs-4" style="padding:0px;">
													<select id="subway_local" name="subway_local" onchange="$('#station').html('<option value=\'\'>-</option>');"><option value="">-</option></select>
												</div>
												<div class="col-xs-4" style="padding:0px;">
													<select id="hosun" name="hosun"><option value="">-</option></select>
												</div>
												<div class="col-xs-4" style="padding:0px;">
													<select id="station" name="station"><option value="">-</option></select>
												</div>
											</div>							
										</li>
									</ul>
								 </div>
								<?php } else { ?>
								<ul class="nav">
									<li class="nav-one">
										<a href="#local_section" class='current'><?php echo lang("site.location");?></a>
									</li>
									<li class="nav-two">
										<a href="#sybway_section"><?php echo lang("site.subway");?></a>
									</li>
									<li class="nav-three last">
										<a href="#google_section"><?php echo lang("search.total");?></a>
									</li>
								</ul>
								<div class="list-wrap">
									<ul id="local_section">
										<li>
										<div class="row" style="margin:0px;">
											<?php if($config->REGION_USE){?>
											<div class="col-xs-12" style="padding:0px;">
												<select id="region" name="region" onchange="region_select(this.value);">
													<option value="">지역 선택</option>
													<?php foreach($region as $val){?>
													<option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
													<?php }?>
												</select>
											</div>
											<?php } else {?>
											<div class="col-xs-4" style="padding:0px;">
												<select id="sido" name="sido" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
											</div>
											<div class="col-xs-4" style="padding:0px;">
												<select id="gugun" name="gugun"><option value="">-</option></select>
											</div>
											<div class="col-xs-4" style="padding:0px;">
												<select id="dong" name="dong"><option value="">-</option></select>
											</div>
											<?php }?>
										</div>
										</li>
									</ul>
									<ul id="sybway_section" style='display:none;'>
										<li>
											<div class="row" style="margin:0px;">
												<div class="col-xs-4" style="padding:0px;">
													<select id="subway_local" name="subway_local" onchange="$('#station').html('<option value=\'\'>-</option>');"><option value="">-</option></select>
												</div>
												<div class="col-xs-4" style="padding:0px;">
													<select id="hosun" name="hosun"><option value="">-</option></select>
												</div>
												<div class="col-xs-4" style="padding:0px;">
													<select id="station" name="station"><option value="">-</option></select>
												</div>
											</div>							
										</li>
									 </ul>
									 <ul id="google_section" style='display:none;'>
										<li>
											<input type="text" id="search" name="search" class="ui-autocomplete-input search_item" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product.no");?>, <?php echo lang("site.title");?>" autocomplete="off" value="">
										</li>
									 </ul>
								 </div>
								<?php } ?>
							</div> <!-- search_tab -->

							<ul>
								<?php if($config->INSTALLATION_FLAG!="2") {?>
								<li>
									<div class="btn-group btn-group-justified btn-group-sm margin-bottom-20" role="group" data-toggle="buttons">
										<!-- input radio에 search_item을 걸면 갯수만큼 call이 나가기 때문에 안된다. -->
										<label class="btn btn-default type_label active">
											<input type="radio" name="type" class="search_item" value="" selected><?php echo lang("site.all");?>
										</label>
										<?php if($config->INSTALLATION_FLAG!="0"){?>
										<label class="btn btn-default type_label">
											<input type="radio" name="type" class="search_item" value="installation"><?php echo lang('installation');?>
										</label>
										<?php }?>
										<label class="btn btn-default type_label">
											<input type="radio" name="type" class="search_item" value="sell"><?php echo lang('sell');?>
										</label>
										<label class="btn btn-default type_label">
											<input type="radio" name="type" class="search_item" value="full_rent"><?php echo lang('full_rent');?>
										</label>
										<label class="btn btn-default type_label">
											<input type="radio" name="type" class="search_item" value="monthly_rent"><?php echo lang('monthly_rent');?>
										</label>		
									</div>
								</li>
								<?php }?>
								<li class="price_range price_sell margin-bottom-20">
									<div class="price_label"><?php echo lang('sell');?>가</div>
									<div class="price_show">
											<input type="hidden" id="sell_start" name="sell_start">
											<input type="hidden" id="sell_end" name="sell_end">
											<div id="sell_label"></div>
									</div>
									<div style="clear:both;"></div>
									<div class="price_slider">
										<div id="sell_range" class="slider" 
											data-start="0" 
											data-end="<?php echo $config->SELL_MAX ;?>" 
											data-min="0" 
											data-max="<?php echo $config->SELL_MAX;?>"
											data-step="1"
											data-type="sell" ></div>
									</div>
								</li>
								<li class="price_range price_full margin-bottom-20">
									<div class="price_label"><?php echo lang('full_rent');?>가</div>
									<div class="price_show">
										<input type="hidden" id="full_start" name="full_start">
										<input type="hidden" id="full_end" name="full_end">
										<div id="full_label"></div>
									</div>
									<div style="clear:both;"></div>
									<div class="price_slider margin-bottom-20">
										<div id="full_range" class="slider" 
											data-start="0" 
											data-end="<?php echo $config->FULL_MAX ;?>" 
											data-min="0" 
											data-max="<?php echo $config->FULL_MAX;?>"
											data-step="500"
											data-type="full"></div>
									</div>
								</li>
								<li class="price_range price_rent margin-bottom-20">
									<div class="price_label"><?php echo lang("product.price.deposit");?></div>
									<div class="price_show">
										<input type="hidden" id="month_deposit_start" name="month_deposit_start">
										<input type="hidden" id="month_deposit_end" name="month_deposit_end">
										<div id="month_deposit_label"></div>
									</div>
									<div style="clear:both;"></div>
									<div class="price_slider">
										<div id="month_deposit_range" class="slider"
											data-start="0" 
											data-end="<?php echo $config->MONTH_DEPOSIT_MAX ;?>" 
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
											data-start="0" 
											data-end="<?php echo $config->MONTH_DEPOSIT_MAX ;?>" 
											data-min="0" 
											data-max="<?php echo $config->MONTH_MAX;?>"
											data-step="5"
											data-type="month"></div>
									</div>
								</li>
								<?php foreach($category as $val){ ?>
								<li style="white-space: nowrap;">
									<input type="checkbox" name="category[]" id="category_<?php echo $val->id;?>" value="<?php echo $val->id;?>" class="category_checkbox search_item"/>
									<label> <?php echo $val->name;?></label>
								</li>
									<?php 
									if(isset($val->category_sub)){
										foreach($val->category_sub as $sub_val){?>
										<li class="category_sub_<?php echo $val->id;?>" style="display:none">
											<i class="fa fa-level-up fa-rotate-90"></i> <input type="checkbox" name="category_sub[]" value="<?php echo $sub_val->id;?>" main-id="<?php echo $val->id;?>" class="category_checkbox search_item"/>
											<label> <?php echo $sub_val->name;?></label>
										</li>
										<?php }?>
									<?php }?>
								<?php }?>
				            </ul>
							<br/>
							<button type="submit" class="btn btn-block btn-search" style="margin-top:5px;"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>

			            </form>
		          	</div> 
		        </div>
				<!-- END TOP SEARCH -->
			</div>
			<?php }?>
		</div>
</div>