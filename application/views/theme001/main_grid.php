<style>
.pac-container {
	display:none;
}
</style>
<script>
	var sell_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("sell_unit");?></font>";
	var price_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("price_unit");?></font>";
	var address_type="front"; /** 공개매물 등록된 주소 가져오도록 **/
</script>
<script src="/assets/plugin/organictabs.jquery.js"></script>
<script type="text/javascript" src="/assets/basic/js/search.js"></script>
<script>
var data;
var idle_init = 0;
var total;

$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	init_search("<?php echo element('type',$search);?>","<?php echo element('category',$search);?>");

	$('#search_form').ajaxForm( {
		beforeSubmit: function()
		{
			/** map은 여기서 move_map을 했는데 grid는 그럴 필요가 없으니까 그냥 냅두자 **/
		},
		success: function(data)
		{
			$.ajax({
				type: 'GET',
				url: '/main/listing_json/'+$("#next_page").val(),
				cache: false,
				dataType: 'json',
				beforeSend: function(){
					$("#next_page").val("0");	/*** 검색 조건이 바뀌면 다시 페이지가 0이 되면서 시작되어야 한다. more가 되면 submit이 아닌 success 된 이후에 ajax만 동작하면 된다. ***/
					loading_delay(true);
				},
				success: function(jsonData){
					var str = "";
					$.each(jsonData, function(rkey, rval) {
						if(rkey=="result") {
							str = rval;
						}
						if(rkey=="total"){
							total = rval;
							$(".result_label").html("<i class=\"fa fa-search\"></i> <?php echo lang("search.result");?> " + rval);
						}

						if(rkey=="paging"){

							if(total<=rval){
								$("#pagination_more").hide();
							} else if(rval=="0"){
								$("#pagination_more").hide();
							} else {
								$("#pagination_more").show();
							}
							
							next_page = rval;
							$("#next_page").val(rval);
						}
					});
					
					if(total=="0"){
						$("#pagination_more").hide();
					}

					if(next_page<10){
						$("#search-items").html(str);			
					} else {
						$("#search-items").append(str);
					}

					$('.help').tooltip();	

					link_init($('.view_product'));
					loading_delay(false);
					login_leanModal();
				}
			});
		}
	});

	$(".search_item").change(function(){
		init_price();
		$('#search_form').trigger('submit');
	});

	$("#search_tab").organicTabs();

	/** 주소가 이미 있다면 주소가 세팅된 후에 다시 submit이 날라가기 때문에 여기서는 동작하지 않아야한다. **/
	
	<?php if( element("search_type",$search) == "" || element("search_type",$search) == "parent_address" || element("search_type",$search) == "google"  || element("search_type",$search) == "theme"){?>
		$("#next_page").val("0");
		$("#search_form").trigger("submit");
	<?php }?>

	$(".search_item").change(function(){
		$("#next_page").val("0");
		$('#search_form').trigger('submit');
	});

	$('.category_checkbox').on('ifChanged', function(event){
		$("#next_page").val("0");
		$("#search_form").trigger("submit");
	});
});

function search_reset(){
	loading_delay(true);

	$("#search_form").find("select").not(".sorting_select").each(function() { 
		$(this).val("");
	});

	$("#search_form").find("input").each(function() {
		$(this).prop("selected",false);
		$(this).prop("checked",false);
	});

	$(".type_label").removeClass("active");
	$(".type_label").eq(0).addClass("active");
	
	$("#search_type, #search_value, #search, #keyword_front").val("");
	$("#lat, #lng").val("");
	$("#sido_val, #gugun_val, #dong_val").val("");
	$("#subway_local_val, #hosun_val, #station_val").val("");
	$("#address_id").val("");

	$('input').iCheck('uncheck');
	$('input').iCheck('update');

	calling = 1;
	init_price();

	setTimeout(function () {
		loading_delay(false);
		calling = 0;
	}, 400);

	$("#reset").val(1);
	$("#search_form").trigger("submit");
	$("#reset").val(0);
}

function more(){
	$('#search_form').trigger('submit');
}


function set_theme(id){
	$("#theme").val(id);
	$("#search_form").trigger("submit");		
}

function theme_display(){

	var theme_show = function(){
		$("#theme_fa").removeClass("fa-chevron-down").addClass("fa-chevron-up");
		$(".theme_li").slideDown("slow");	
	}

	var theme_hide = function(){
		$("#theme_fa").removeClass("fa-chevron-up").addClass("fa-chevron-down");
		$(".theme_li").slideUp("slow");
	}

	if($(".theme_li").css("display") != "none") theme_hide();
	else theme_show();
}

function subway_line_display(){

	var subway_line_show = function(){
		$("#subway_line_fa").removeClass("fa-chevron-down").addClass("fa-chevron-up");
		$(".subway_line_li").slideDown("slow");	
	}

	var subway_line_hide = function(){
		$("#subway_line_fa").removeClass("fa-chevron-up").addClass("fa-chevron-down");
		$(".subway_line_li").slideUp("slow");
	}

	if($(".subway_line_li").css("display") != "none") subway_line_hide();
	else subway_line_show();
}
</script>
<form  action="/search/set_search/" id="search_form" method="post">
<input type="hidden" id="search_type" name="search_type" value="<?php echo element("search_type",$search);?>">
<input type="hidden" id="search_value" name="search_value" value="<?php echo element("search_value",$search);?>">
<input type="hidden" id="lat" name="lat" value="<?php echo element("lat",$search);?>">
<input type="hidden" id="lng" name="lng" value="<?php echo element("lng",$search);?>">
<input type="hidden" id="sido_val" name="sido_val" value="<?php echo element("sido_val",$search);?>">
<input type="hidden" id="gugun_val" name="gugun_val" value="<?php echo element("gugun_val",$search);?>">
<input type="hidden" id="dong_val" name="dong_val" value="<?php echo element("dong_val",$search);?>">
<input type="hidden" id="subway_local_val" name="subway_local_val" value="<?php echo element("subway_local_val",$search);?>">
<input type="hidden" id="hosun_val" name="hosun_val" value="<?php echo element("hosun_val",$search);?>">
<input type="hidden" id="station_val" name="station_val" value="<?php echo element("station_val",$search);?>">
<input type="hidden" id="gugun_submit" value="1"><!--구군클릭시 서브밋할지 여부-->
<input type="hidden" id="keyword_front" name="keyword_front" value="<?php echo element("keyword_front",$search);?>">
<div class="band-wrapper">
	<div class="_container">
		<div class="inner">
			<div class="pull-left">
				<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=1 START-->
				<?php if($config->SEARCH_POSITION){?>
					<div class="btn-group">
						<div class="col-xs-4" style="padding:5px 10px 5px 0px;">
							<select id="sido" name="sido" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
						</div>
						<div class="col-xs-4" style="padding:5px 10px 5px 0px;">
							<select id="gugun" name="gugun"><option value="">-</option></select>
						</div>
						<div class="col-xs-4" style="padding:5px 10px 5px 0px;">
							<select id="dong" name="dong"><option value="">-</option></select>
						</div>
					</div>
					<?php foreach($category as $val){ ?>
						<input type="checkbox" name="category[]" id="category_<?php echo $val->id;?>" value="<?php echo $val->id;?>" class='category_checkbox search_item' >
						<label> <?php echo $val->name;?></label>
					<?php }?>
				<?php }?>
				<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=1 END-->
			</div>
			<div class="pull-right text-right">
				<!-- 매물지도 사용여부 MAP_USE=1 START-->
				<?php if($config->MAP_USE){?>
					<div class="btn-group">
					  <a href="/main/map" class="btn btn-default"><i class="fa fa-map-marker"></i> <?php echo lang("site.map");?></a>
					  <a href="#" class="btn btn-default active"><i class="fa fa-th-large"></i> <?php echo lang("site.list");?></a>
					</div>
				<?php } ?>
				<!-- 매물지도 사용여부 MAP_USE=1 END-->
			</div>
			<div style="clear:both"></div>
		</div>
	</div>
</div>

    <div class="main" style="padding-top:20px;">
		<div class="_container">		
			<div class="row">
				<div class="col-md-3">
					<div class="margin-bottom-10">
						<button class="btn btn-primary" onclick="search_reset()" style="width:100%;"><i class="glyphicon glyphicon-refresh"></i> <?php echo lang("site.initfilter");?></button>
					</div>
					<div class="search-wrapper">
						<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=0 START-->
						<?php if(!$config->SEARCH_POSITION){?>
						<div id="search_tab">
							<?php if($config->SUBWAY) {?>
							<ul class="nav">
								
								<li class="nav-one">
									<a href="#local_section" <?php if(element("search_type",$search)=="" or element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {echo "class='current'";}?>><?php echo lang("site.location");?></a>
								</li>
								<li class="nav-two">
									<a href="#sybway_section" <?php if(element("search_type",$search)=="subway") {echo "class='current'";}?>><?php echo lang("site.subway");?></a>
								</li>
								<li class="nav-three last">
									<a href="#google_section" <?php if(element("search_type",$search)=="google") {echo "class='current'";}?>><?php echo lang("search.total");?></a>
								</li>
							</ul>
							<?php } ?>
							
							<div class="list-wrap">
							
								<ul id="local_section"  
									<?php if(element("search_type",$search)=="" or element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {} else {echo "style='display:none;'";}?>>
									<li>
									<div class="row" style="margin:0px;">
										<div class="col-xs-4" style="padding:0px;">
											<select id="sido" name="sido" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
										</div>
										<div class="col-xs-4" style="padding:0px;">
											<select id="gugun" name="gugun"><option value="">-</option></select>
										</div>
										<div class="col-xs-4" style="padding:0px;">
											<select id="dong" name="dong"><option value="">-</option></select>
										</div>
									</div>
									</li>
								</ul>
								 
								 <ul id="sybway_section" <?php if(element("search_type",$search)=="subway") {} else {echo "style='display:none;'";}?>>
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
								 
								 <ul id="google_section" <?php if(element("search_type",$search)=="google") {} else {echo "style='display:none;'";}?>>
									<li>
										<div class="input-group">
											<input type="text" id="search" name="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("product");?>번호, <?php echo lang("product");?>제목" autocomplete="off" value="<?php if(element("search_type",$search)=="google") echo element("search_value",$search);?>" style="font-size: 12px;height:28px;"/>
											<div class="input-group-btn">
												<button id="go_keyword" class="btn btn-default" type="button" style="padding:3px 6px;"><i class="fa fa-search"></i></button>
											</div>
										</div>
									</li>
								 </ul>

							 </div> <!-- END List Wrap -->
						</div> <!-- search_tab -->
						<?php }?>
						<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=0 END-->
						<ul>
							<?php if($config->INSTALLATION_FLAG!="2"){?>
							<li>
								<div class="btn-group btn-group-justified btn-group-sm" role="group" data-toggle="buttons">
									<!-- input radio에 search_item을 걸면 갯수만큼 call이 나가기 때문에 안된다. -->
									<label class="btn btn-default type_label active">
										<input type="radio" name="type" class="search_item" value="" selected><?php echo lang("site.all");?>
									</label>
									<?php if($config->INSTALLATION_FLAG=="1"){?>
									<label class="btn btn-default type_label">
										<input type="radio" name="type" class="search_item" value="installation"><?php echo lang('installation');?>
									</label>
									<?php }?>
									<label class="btn btn-default type_label">
										<input type="radio" name="type" class="search_item" value="sell"><?php echo lang('sell');?>
									</label>
									<?php if(lang('full_rent')!=""){?>
									<label class="btn btn-default type_label">
										<input type="radio" name="type" class="search_item" value="full_rent"><?php echo lang('full_rent');?>
									</label>
									<?php } ?>
									<label class="btn btn-default type_label">
										<input type="radio" name="type" class="search_item" value="monthly_rent"><?php echo lang('monthly_rent');?>
									</label>
								</div>
							</li>
							<?php } else {?>
							<input type="hidden" name="type" value="installation"/>
							<?php }?>
							<li class="price_range price_sell">
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
										data-end="<?php echo element('month_end',$search) ? element('month_end',$search) : $config->SELL_MAX ;?>" 
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
										data-start="0" 
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
										data-start="0" 
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
										data-start="0" 
										data-end="<?php echo element('month_end',$search) ? element('month_deposit_end',$search) : $config->MONTH_DEPOSIT_MAX ;?>" 
										data-min="0" 
										data-max="<?php echo $config->MONTH_MAX;?>"
										data-step="5"
										data-type="month"></div>
								</div>
							</li>
							<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=1 START-->
							<?php if($config->SEARCH_POSITION){?>
								<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=1 END-->
								<li>
									<div class="input-group">
										<input type="text" id="search" name="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("product");?>번호, <?php echo lang("product");?>제목" autocomplete="off" value="<?php if(element("search_type",$search)=="google") echo element("search_value",$search);?>" style="font-size: 12px;height:28px;"/>
										<div class="input-group-btn">
											<button id="go_keyword" class="btn btn-default" type="button" style="padding:3px 6px;"><i class="fa fa-search"></i></button>
										</div>
									</div>
								</li>
								<?php if($config->SUBWAY) {?>
								<li>
									<h3 style="margin-top:20px;"><?php echo lang("site.subway");?> <?php echo lang("site.search");?></h3>
									<div id="search_tab">
										<div class="list-wrap">
											 <ul id="sybway_section" >
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
										 </div> <!-- END List Wrap -->
									</div> <!-- search_tab -->
								</li>
								<?php }?>
							<?php }?>
							<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=1 END-->
							<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=0 START-->
							<?php if(!$config->SEARCH_POSITION){?>
								<li>
									<h3><?php echo lang("search.type");?></h3>
								</li>
								<?php foreach($category as $val){ ?>
									<li>
										<input type="checkbox" name="category[]" id="category_<?php echo $val->id;?>" value="<?php echo $val->id;?>" class='category_checkbox search_item' >
										<label> <?php echo $val->name;?></label>
									</li>
								<?php }?>
							<?php }?>
							<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=0 END-->
							<?php if($config->USE_THEME){?>
								<li>
									<h3 style="cursor:pointer;" onclick="theme_display();"><?php echo lang("product.theme")?> <i id="theme_fa" class="pull-right fa fa-chevron-down"></i></h3>
								</li>
								<?php 
								$theme_check = false;
								foreach($theme as $val){
									if(isset($val->checked)) $theme_check = true;
								?>
								<li class="theme_li" style="white-space: nowrap;display:none;">
									<input type="checkbox" name="theme[]" value="<?php echo $val->id;?>" class='category_checkbox search_item' <?php echo isset($val->checked)?$val->checked:"";?>/>
									<label> <?php echo $val->theme_name;?></label>
								</li>
								<?php }?>
								<?php if($theme_check){?><script>theme_display();</script><?php }?>
							<?php }?>
							<?php if($config->SUBWAY && $subway_line){?>
							<li>
								<h3 style="cursor:pointer;" onclick="subway_line_display();"><?php echo lang("site.subwaylinesearch");?> <i id="subway_line_fa" class="pull-right fa fa-chevron-down"></i></h3>
							</li>
								<?php
								$subway_line_check = false;
								foreach($subway_line as $val){
									if(isset($val->checked)) $subway_line_check = true;
								?>
								<li class="subway_line_li" style="white-space: nowrap;display:none;">
									<input type="checkbox" name="subway_line[]" value="<?php echo $val->hosun_id;?>" class='category_checkbox search_item' <?php echo isset($val->checked)?$val->checked:"";?>/>
									<i class="fa fa-square sub_color_<?php echo $val->hosun_id;?>"></i>
									<label> <?php echo (is_numeric($val->hosun)) ? $val->hosun.toeng("호선") : $val->hosun.toeng("선");?></label>
								</li>
								<?php }?>
								<?php if($subway_line_check){?><script>subway_line_display();</script><?php }?>			
							<?php }?>
							<?php if($danzi){?>
							<li>
								<h3>단지</h3>
							</li>
							<li style="white-space: nowrap;">
								<input type="hidden" id="danzi_temp" value="<?php echo element("danzi",$search)?>"/>
								<select id="danzi_name" name="danzi_name" class="form-control" onchange="get_danzi_name(this.value);">
									<option value="">아파트단지 선택</option>
									<?php foreach($danzi as $val){ ?>
									<option value="<?php echo $val->address_id;?>|<?php echo $val->name;?>" <?php if(element("danzi_name",$search)==$val->name) echo "selected";?>><?php echo $val->name;?></option>
									<?php }?>
								</select>
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
							<?php }?>

						</ul>
					</div> <!-- search-wrapper -->

			</div>
			<div class="col-md-9">
				<!-- 매물 그리드 시작 -->
				<div class="margin-bottom-20">
					<div class="loading_content">
						<div class="loading_background"></div>
						<div class="loading_image"><img src="/assets/common/img/load_360.gif"></div>
					</div>
					<div  style="float:left;display:inline;">
							<?php 
								$sch = "";
								if(isset($search["sorting"])){
									$sch = $search["sorting"];
								}
							?>
							<select name="sorting" class="search_item sorting_select" style="height:24px;border:1px solid #cacaca;">
								<option value="basic" <?php if($sch=="" && "basic"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="basic") {echo "selected";}?>><?php echo lang("sort.recommend");?></option>
								<option value="speed" <?php if($sch=="" && "speed"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="speed") {echo "selected";}?>><?php echo lang("sort.recommend");?></option>
								<option value="date_desc" <?php if($sch=="" && "date_desc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="date_desc") {echo "selected";}?>><?php echo lang("sort.newest");?></option>
								<option value="date_asc" <?php if($sch=="" && "date_asc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="date_asc") {echo "selected";}?>><?php echo lang("sort.oldest");?></option>
								<option value="price_desc" <?php if($sch=="" && "price_desc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="price_desc") {echo "selected";}?>><?php echo lang("sort.high");?></option>
								<option value="price_asc" <?php if($sch=="" && "price_asc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="price_asc") {echo "selected";}?>><?php echo lang("sort.low");?></option>
								<option value="area_desc" <?php if($sch=="" && "area_desc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="area_desc") {echo "selected";}?>><?php echo lang("sort.big");?></option>
								<option value="area_asc" <?php if($sch=="" && "area_asc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="area_asc") {echo "selected";}?>><?php echo lang("sort.small");?></option>
							</select>
					</div>
					<div class="result_label" style="float:right;display:inline;padding:0px;"></div>
					<div style="clear:both;"></div>
					

					<?php if($config->LISTING=="1") {?>
					<table class="table table-bordered table-striped table-condensed flip-content margin-top-10">
						<thead>
							<tr>
								<th style="width:80px;"><?php echo lang("site.photo");?></th>
								<th style="width:130px;"><?php echo lang("site.address");?></th>
								<th style="width:90px;"><?php echo lang("product.category");?></th>
								<th style="width:50px;"><?php echo lang("product.type");?></th>								
								<th><?php echo lang("site.title");?>/<?php echo lang("site.address");?></th>
								<?php if($config->PRODUCT_REALAREA || $config->PRODUCT_LAWAREA) {?>
								<th style="width:60px;"><?php echo lang("product.area");?></th>
								<?php }?>
								<!--th style="width:40px;">층</th-->
								<th style="width:60px;">현업종</th>
								<th style="width:80px;"><?php echo lang("site.regdate");?>/<?php echo lang("site.confirm");?></th>
							</tr>
						</thead>
						<tbody id="search-items"></tbody>
					</table>
					<?php } else { ?>
					<div id="search-items"></div>					
					<?php } ?>

					<div style="clear:both;"></div>
					<div style="padding:10px;text-align:center;">
						<input type="hidden" id="next_page" value="0" autocomplete="off"/>
						<button type="button" id="pagination_more" class="btn btn-default" style="width:30%;" onclick="more();"><i class="fa fa-chevron-circle-down"></i> <?php echo lang("site.more");?></button>
					</div>
					<div style="clear:both;"></div>
				</div>
				<!-- 매물 그리드 종료 -->
			</div><!-- col-md-9 -->
		</div><!-- row -->		
	</div><!-- _container -->
</div>
</form>