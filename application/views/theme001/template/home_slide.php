<?php 
	$browser_info = getBrowser();

	if($browser_info['name']=='IE' && $browser_info['version'] <= 8){
		$searchbox_class = "searchbox-ie8";
		$select_style_class = "select-style-ie8";
	}
	else{
		$searchbox_class = "searchbox";
		$select_style_class = "select-style";
	}

?>
<style>
<?php if($slide){?>
.wrap_slider {
	background: url(/uploads/slide/<?php echo $slide[0]['filename']?>)  no-repeat top center;
}
<?php } else {?>
.wrap_slider { /*default image*/
	background: url(/assets/theme/seven/slide/bg3.jpg)  no-repeat top center;
}
<?php } ?>
</style>

<!-- BEGIN SLIDER -->
<div class="wrap_slider">
	<a href="#" id="top_layer_link" target="_blank">
		<div class="pattern"></div>
		<div class="pattern-bg"></div>
	</a>
	<div class="_container">

		<div class="slide_wrapper" style="pointer-events:none;">

		<div style="text-align:center;margin-bottom:20px;">
			<?php if($title) {?>
				<h1><?php echo $title;?></h1>
			<?php }?>
		</div>

			<div class="searchbox_wrapper">
				<div class="<?php echo $searchbox_class;?>" style="pointer-events:auto;">
			<?php if($config->SEARCH_ORDER==2){?>
					<ul class="nav-search">
						<li class="active"><a href="javascript:;"><?php echo lang("search.total");?></a></li>
						<li><a href="javascript:;"><?php echo lang("site.address");?> <?php echo lang("site.search");?></a></li>
						<?php if($config->SUBWAY) {?>
			<li><a href="javascript:;"><?php echo lang("site.subway");?> <?php echo lang("site.search");?></a></li>
						<?php } ?>
			</ul>
			<?php }else {?>
					<ul class="nav-search">
						<li class="active"><a href="javascript:;"><?php echo lang("site.address");?> <?php echo lang("site.search");?></a></li>
						<?php if($config->SUBWAY) {?>
						<li><a href="javascript:;"><?php echo lang("site.subway");?> <?php echo lang("site.search");?></a></li>
						<?php } ?>
						<li><a href="javascript:;"><?php echo lang("search.total");?></a></li>
					</ul>
			<?php }?>
					<div style="clear:both;"></div>
					<?php
						/*************************************************************************
						 * search_type  : 검색방식(구군:parent_address, 읍면동: address, 지하철: subway, 통합검색: google)
						 * search_value : 검색방식에 해당하는 값
						 * type     : 거래 종류(installation, sell, full_rent, monthly_rent)
						 *
						 * 시도구군까지 선택하면 search_type = parent_address가 되고
						 * 읍면동까지 선택하면 search_type = address가 되며
						 * 지하철지역, 호선, 역명을 클릭하면 search_type = subway가 되고
						 * 통합검색에서 검색을 하면 search_type = google이 된다.
						 *
						 * sido_val, gugun_val, dong_val : 검색 후 이동하여 검색 결과대로 시도,구군,읍면동을 보여주기 위한 변수이다.
						 * subway_local_val, hosun_val, station_val : 검색 후 이동하여 검색 결과대로 지하철역 정보를 보여주기 위한 변수이다.
						 * 
						 *************************************************************************/
					?>
					<form action="/search/set_search/main" id="search_form" method="post">
						<input type="hidden" id="search_type" name="search_type">
						<input type="hidden" id="search_value" name="search_value">
						<input type="hidden" id="type" name="type" class="type">
						<input type="hidden" id="lat" name="lat">
						<input type="hidden" id="lng" name="lng">
						<input type="hidden" id="sido_val" name="sido_val" value="<?php echo $config->INIT_SIDO;?>">
						<input type="hidden" id="gugun_val" name="gugun_val" value="<?php if($config->INIT_SIDO) echo $config->INIT_GUGUN;?>">
						<input type="hidden" id="dong_val" name="dong_val">
						<input type="hidden" id="subway_local_val" name="subway_local_val">
						<input type="hidden" id="hosun_val" name="hosun_val">
						<input type="hidden" id="station_val" name="station_val">
						<input type="hidden" id="theme" name="theme[]">
						<input type="hidden" id="keyword_front" name="keyword_front">
						<input type="hidden" id="region" name="region">
						<div class="search-inner">
			 <?php if($config->SEARCH_ORDER==2){?>
						 <ul id="tab">
								<li class="active">
								<div class="row">
									<div class="col-xs-5ths">
										<select class="form-control type" id="type3" onchange="set_type(this.value)">
											<option value=""><?php echo lang("product.type");?></option>
											<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation"><?php echo lang('installation');?></option><?php }?>
											<?php if($config->INSTALLATION_FLAG!="2") {?> 
											<option value="sell"><?php echo lang('sell');?></option>
											<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
											<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
											<?php }?>
										</select>
									</div>
									<div class="col-xs-5ths-2">
										<input type="text" id="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product.no");?>, <?php echo lang("site.title");?>" autocomplete="off">
									</div>
									<div class="col-xs-5ths">
										<button id="geo_btn" type="button" class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
									</div>
								</div>
								</li>
								<li>
								<div class="row">
									<div class="col-xs-5ths">
										<select class="form-control type" id="type1" onchange="set_type(this.value)">
											<option value=""><?php echo lang("product.type");?></option>
											<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation" <?php if($config->INSTALLATION_FLAG=="2") {?>selected<?php }?>><?php echo lang('installation');?></option><?php }?>
											<?php if($config->INSTALLATION_FLAG!="2") {?>
											<option value="sell"><?php echo lang('sell');?></option>
											<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
											<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
											<?php }?>
										</select>                
									</div>
									<?php if($config->REGION_USE){?>
									<div class="col-xs-5ths-2">
										<select id="region" name="region" class="form-control" onchange="region_select(this.value);">
											<option value="">지역 선택</option>
											<?php foreach($region as $val){?>
											<option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
											<?php }?>
										</select>
									</div>
									<div class="col-xs-5ths">
										<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
									</div>	
									<?php } else { ?>
									<div class="col-xs-5ths">
										<select id="sido" name="sido" class="form-control" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
									</div>
									<div class="col-xs-5ths">
										<select id="gugun" name="gugun" class="form-control"><option value="">-</option></select>
									</div>
									<div class="col-xs-5ths">
										<select id="dong" name="dong" class="form-control"><option value="">-</option></select>
									</div>
									<div class="col-xs-5ths">
										<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
									</div>									
									<?php }?>
								</div>
								</li>
								<?php if($config->SUBWAY) {?>
								<li>
								<div class="row">
									<div class="col-xs-5ths">
										<select class="form-control type" id="type2" onchange="set_type(this.value)">
											<option value=""><?php echo lang("product.type");?></option>
											<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation"><?php echo lang('installation');?></option><?php }?>
											<?php if($config->INSTALLATION_FLAG!="2") {?> 
											<option value="sell"><?php echo lang('sell');?></option>
											<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
											<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
											<?php }?>
										</select>
									</div>
									<div class="col-xs-5ths">
										<select id="subway_local" name="subway_local" class="form-control" onchange="$('#station').html('<option value=\'\'>-</option>');">
											<option value=""><?php echo lang("site.location");?></option>
											<option value="1"><?php echo toeng("수도권");?></option>
											<option value="2"><?php echo toeng("부산");?></option>
											<option value="3"><?php echo toeng("대구");?></option>
											<option value="4"><?php echo toeng("광주");?></option>
											<option value="5"><?php echo toeng("대전");?></option>
										</select>
									</div>
									<div class="col-xs-5ths">
										<select id="hosun" name="hosun" class="form-control"><option value="">-</option></select>
									</div>
									<div class="col-xs-5ths">
										<select id="station" name="station" class="form-control"><option value="">-</option></select>
									</div>
									<div class="col-xs-5ths">
										<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
									</div>
								</div>
								</li>
								<?php }?>
							</ul>
				<?php } else { ?>
							<ul id="tab">
								<li class="active">
								<div class="row">
									<div class="col-xs-5ths">
										<select class="form-control type" id="type1" onchange="set_type(this.value)">
											<option value=""><?php echo lang("product.type");?></option>
											<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation" <?php if($config->INSTALLATION_FLAG=="2") {?>selected<?php }?>><?php echo lang('installation');?></option><?php }?>
											<?php if($config->INSTALLATION_FLAG!="2") {?>
											<option value="sell"><?php echo lang('sell');?></option>
											<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
											<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
											<?php }?>
										</select>                
									</div>
									<?php if($config->REGION_USE){?>
									<div class="col-xs-5ths-2">
										<select id="region" name="region" class="form-control" onchange="region_select(this.value);">
											<option value="">지역 선택</option>
											<?php foreach($region as $val){?>
											<option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
											<?php }?>
										</select>
									</div>
									<div class="col-xs-5ths">
										<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
									</div>	
									<?php } else { ?>
									<div class="col-xs-5ths">
										<select id="sido" name="sido" class="form-control" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
									</div>
									<div class="col-xs-5ths">
										<select id="gugun" name="gugun" class="form-control"><option value="">-</option></select>
									</div>
									<div class="col-xs-5ths">
										<select id="dong" name="dong" class="form-control"><option value="">-</option></select>
									</div>
									<div class="col-xs-5ths">
										<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
									</div>									
									<?php }?>
								</div>
								</li>
								<?php if($config->SUBWAY) {?>
								<li>
								<div class="row">
									<div class="col-xs-5ths">
										<select class="form-control type" id="type2" onchange="set_type(this.value)">
											<option value=""><?php echo lang("product.type");?></option>
											<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation"><?php echo lang('installation');?></option><?php }?>
											<?php if($config->INSTALLATION_FLAG!="2") {?>
											<option value="sell"><?php echo lang('sell');?></option>
											<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
											<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
											<?php }?>
										</select>
									</div>
									<div class="col-xs-5ths">
										<select id="subway_local" name="subway_local" class="form-control" onchange="$('#station').html('<option value=\'\'>-</option>');">
											<option value=""><?php echo lang("site.location");?></option>
											<option value="1"><?php echo toeng("수도권");?></option>
											<option value="2"><?php echo toeng("부산");?></option>
											<option value="3"><?php echo toeng("대구");?></option>
											<option value="4"><?php echo toeng("광주");?></option>
											<option value="5"><?php echo toeng("대전");?></option>
										</select>
									</div>
									<div class="col-xs-5ths">
										<select id="hosun" name="hosun" class="form-control"><option value="">-</option></select>
									</div>
									<div class="col-xs-5ths">
										<select id="station" name="station" class="form-control"><option value="">-</option></select>
									</div>
									<div class="col-xs-5ths">
										<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
									</div>
								</div>
								</li>   
								<?php }?>
								<li>
								<div class="row">
									<div class="col-xs-5ths">
										<select class="form-control type" id="type3" onchange="set_type(this.value)">
											<option value=""><?php echo lang("product.type");?></option>
											<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation"><?php echo lang('installation');?></option><?php }?>
											<?php if($config->INSTALLATION_FLAG!="2") {?>
											<option value="sell"><?php echo lang('sell');?></option>
											<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
											<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
											<?php }?>
											</select>
									</div>
									<div class="col-xs-5ths-2">
										<input type="text" id="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product.no");?>, <?php echo lang("site.title");?>" autocomplete="off">
									</div>
									<div class="col-xs-5ths">
										<button id="geo_btn" type="button" class="btn btn-block btn-search"><i class="fa fa-search"></i> 검색</button>
									</div>
								</div>
								</li>
							</ul>
				<?php }?>
						</div> <!--search-inner-->

						<ul>
							<?php foreach($category as $val){ ?>
							<li><span class="radiobox-wrap-yellow"><input type="checkbox" name="category[]" id="category_<?php echo $val->id;?>" value="<?php echo $val->id;?>" class='checkbox' ></span> <label for="category_<?php echo $val->id;?>"> <?php echo $val->name;?></label></li>
							<?php }?>
						</ul>
				
						<!--div class="spot_link">
							<?php foreach($spot as $val){
								echo " <a href='#' onclick=\"set_spot('".$val->lat ."','".$val->lng ."');\">".$val->name . "</a> ";
							}?>
					</div-->
				</form>
			</div>
		</div>
	</div> <!-- slide-wrapper -->

	</div> <!-- container -->
</div>
<!-- END SLIDER -->
<script>
var slide_background = <?php echo $slide_json;?>;
var slide_length = slide_background.length;
var now_index = 1;

function change_slide_image(index){
	$('.pattern-bg').hide();
	$('.pattern-bg').css({
		"background" : "url(/uploads/slide/"+slide_background[index].filename+") no-repeat top center"
	});
	$('.pattern-bg').fadeIn(2000, function() {
		$('.wrap_slider').css({
			"background" : "url(/uploads/slide/"+slide_background[index].filename+") no-repeat top center"
		});
	});	

	chage_link(slide_background[index].link);

	now_index++;
}


function chage_link(link){
	if(link && link!=null){
		$("#top_layer_link").css("cursor","pointer");
		$("#top_layer_link").attr("target","_blank");
		$("#top_layer_link").attr("href","http://"+link);
	}
	else{
		$("#top_layer_link").css("cursor","default");
		$("#top_layer_link").attr("target","");
		$("#top_layer_link").attr("href","javascript:void(0)");
	}
}

$(document).ready(function(){
	if(slide_length > 1){
		setInterval(function() {
			if(now_index > slide_length-1) now_index = 0;
			change_slide_image(now_index);
		}, 7000);
	}
	chage_link("<?php echo $slide[0]['link']?>");
});
</script>