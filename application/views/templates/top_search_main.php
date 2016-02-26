<style>
.nopadding {
   padding: 0 !important;
   margin: 0 !important;
}

.searchbox_wrapper_notab {
  position: relative;
  text-align: center;
  vertical-align: middle;
  width: 100%;
}

.wrap_slide {
	color:black;
}

.searchbox_wrapper_notab #search {
	padding:18px 65px 18px 20px;
	width:100%;
	color:black;
	font-size: 1.5rem;
	border-radius:10px;
	border:1px solid #cacaca;
	-webkit-appearance: none;
    -moz-appearance : none;
}

.searchbox_wrapper_notab #go_keyword {
	padding: 18px 21px;
	width:100%;
	font-size: 1.5rem;
	border-radius:10px;
	-webkit-appearance: none;
    -moz-appearance : none;          
}

.searchbox_wrapper_notab #search_1 {
	width:100%;
	padding: 16px 21px;
	font-size: 1.5rem;
	font-weight: 100;
	border-radius:10px;
	-webkit-appearance: none;
    -moz-appearance : none;
}


.searchbox_wrapper_notab .searchbox {
  text-shadow: none;
  margin: 30px auto 0;
  padding: 15px;
  max-width: 850px;
}

.button-section button {
	padding:10px 20px;
}

.price_range {
    padding: 0px;
}

.searchbox_wrapper_notab .price-range li {
    padding: 10px 15px 5px 10px;
    cursor: pointer;
    color:black;
    display:block;
}

.searchbox_wrapper_notab .price-range li:hover {
    background: #f3f3f3;
}

</style>
<script>
$(document).ready(function(){

	/*매물유형 검색*/
	$("#search_1").change(function(){
		init_price();
	});

	/*테마 검색*/
	$("#search_2").multiselect({
		buttonText: function(options) {
			return "<?php echo lang("product.theme")?>("+options.length+")";
		},
		includeSelectAllOption: true,
		selectAllText: "<?php echo lang("site.all")?>",
		buttonClass: "btn btn-default"
	});

	/*지하철 호선별 검색*/
	$("#search_3").multiselect({
		buttonText: function(options) {
			return "<?php echo lang("site.subwaylinesearch");?>("+options.length+")";
		},
		includeSelectAllOption: true,
		enableHTML: true,
		selectAllText: "<?php echo lang("site.all")?>",
		buttonClass: "btn btn-default"
	});

	/*매물종류 대분류 검색*/
	$(".group_checkbox").click(function(event){
		$(".multiselect_category").addClass("open");
		event.stopPropagation();
		if($(this).prop("checked")){
			$(".multiselect_sub"+$(this).attr("data-id")).attr("checked",true);
			$(".multiselect_li"+$(this).attr("data-id")).addClass("active");
		}
		else{
			$(".multiselect_sub"+$(this).attr("data-id")).attr("checked",false);
			$(".multiselect_li"+$(this).attr("data-id")).removeClass("active")			
		}
		if($("#category_count").length > 0){
			$("#category_count").text($(".multiselect_category").find("input:checked").length);
		}
	});

	/*매물종류 소분류 검색*/
	$(".sub_checkbox").click(function(event){
		$(".multiselect_category").addClass("open");
		event.stopPropagation();
		if($(this).prop("checked")){
			$(this).parent().parent().parent().addClass("active");
		}
		else{
			$(this).parent().parent().parent().removeClass("active");
		}
		if($("#category_count").length > 0){
			$("#category_count").text($(".multiselect_category").find("input:checked").length);
		}
	});

	$("#category_count").text($(".multiselect_category").find("input:checked").length);
});
</script>

<div class="row">
	<div class="col-md-2 nopadding">
		<?php if($config->INSTALLATION_FLAG!="2"){?>
		<select id="search_1" name="type" class="search_item">
			<option value=""><?php echo lang("site.all");?></option>
			<?php if($config->INSTALLATION_FLAG=="1"){?>
			<option value="installation"><?php echo lang('installation');?></option>
			<?php }?>
			<option value="sell"><?php echo lang('sell');?></option>
			<?php if(lang('full_rent')!=""){?>
			<option value="full_rent"><?php echo lang('full_rent');?></option>
			<?php } ?>
			<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
		</select>
		<?php } else {?>
			<input type="hidden" name="type" value="installation"/>
		<?php }?>
	</div>
	<div class="col-md-8">
		<input type="text" id="search" class="ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product.no");?>, <?php echo lang("site.title");?>" autocomplete="off" value=""/>	
	</div>
	<div class="col-md-2 nopadding">
		<button id="go_keyword" class="btn btn-warning" type="button" title="검색"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
	</div>
</div>
<div class="row button-section" style="margin-top:10px;">
	<div class="col-md-12 text-left nopadding">
		<button class="btn btn-default" type="button" data-toggle="modal" data-target="#address_modal" title="주소검색" style="margin-right:10px;"><i class="fa fa-map"></i> 주소</button>
		<button class="btn btn-default" type="button" data-toggle="modal" data-target="#subway_modal" title="지하철검색" style="margin-right:10px;"><i class="fa fa-subway"></i> 지하철</button>
		<div class="btn-group multiselect_category" style="margin-right:10px;">
			<button class="multiselect dropdown-toggle btn btn-default" aria-expanded="true" type="button" data-toggle="dropdown"><span class="multiselect-selected-text"><?php echo lang("search.type")?>(<span id="category_count">0</span>)</span> <b class="caret"></b></button>
			<ul class="multiselect-container dropdown-menu">
			<?php
			foreach($category as $val){ ?>
				<li class="multiselect-item multiselect-group">
					<a><label><input class="group_checkbox" name="category[]" type="checkbox" value="<?php echo $val->id;?>" style="margin:5px 0px 5px -10px;" data-id="<?php echo $val->id;?>"> <?php echo $val->name;?></label></a>
				</li>
				<?php 
				if(isset($val->category_sub)){
					foreach($val->category_sub as $sub_val){?>
					<li class="multiselect_li<?php echo $val->id;?>">									
						<a><label class="checkbox"><input class="sub_checkbox multiselect_sub<?php echo $val->id;?>" name="category_sub[]" type="checkbox" value="<?php echo $sub_val->id;?>"> <?php echo $sub_val->name;?></label></a>
					</li>
					<?php }?>
				<?php }?>
			<?php }?>
			</ul>
		</div>

		<div class="btn-group <?php if($config->INSTALLATION_FLAG!="2") echo "price_range";?> price_sell" style="margin-right:10px;">
			<button type="button" class="dropdown-toggle btn-price btn btn-default" href="#" aria-expanded="true" data-toggle="dropdown"><div class="price_label sell_label"><?php echo ($config->INSTALLATION_FLAG=="2") ? lang('installation') : lang('sell');?>가</div>&nbsp;<strong class="caret"></strong></button>
			<div class="dropdown-menu" style="min-width:250px;padding-top:10px;">
				<div class="text-center">
					<input name="sell_start" class="form-control price-label inline" placeholder="최소(<?php echo lang("sell_unit");?>)" data-dropdown-id="sell-price-min" notsubmit style="width:105px;"/> - 
					<input name="sell_end" class="form-control price-label inline" placeholder="최대(<?php echo lang("sell_unit");?>)" data-dropdown-id="sell-price-max" notsubmit style="width:105px;"/>
				</div>
				<div class="clearfix"></div>
				<ul id="sell-price-min" class="price-range list-unstyled">
				<?php for($i=0; $i<=10; $i++){?>
					<li data-value="<?php echo ($config->SELL_MAX * $i/10)?>" notsubmit><?php echo ($config->SELL_MAX * $i/10).lang("sell_unit");?> + </li>
				<?php }?>
				</ul>
				<ul id="sell-price-max" class="price-range text-right list-unstyled hide">
				<?php for($i=0; $i<=10; $i++){?>
					<?php if($i==10){?>
					<li data-value="<?php echo ($config->SELL_MAX * $i/10)?>" notsubmit>제한없음</li>
					<?php } else { ?>
					<li data-value="<?php echo ($config->SELL_MAX * $i/10)?>" notsubmit><?php echo ($config->SELL_MAX * $i/10).lang("sell_unit");?></li>
					<?php } ?>
				<?php }?>
				</ul>
			</div>
		</div>

		<!-- 전세 가격 검색 -->
		<div class="btn-group price_range price_full" style="margin-right:10px;">
			<button type="button" class="dropdown-toggle btn-price btn btn-default" href="#" aria-expanded="true" data-toggle="dropdown"><div class="price_label"><?php echo lang('full_rent');?>가</div>  <strong class="caret"></strong></button>
			<div class="dropdown-menu" style="min-width:250px;padding-top:10px;">
				<div class="text-center">
					<input name="full_start" class="form-control price-label inline" placeholder="최소(<?php echo lang("price_unit.form");?>)" data-dropdown-id="fullrent-price-min" notsubmit style="width:105px;"/> - 
					<input name="full_end" class="form-control price-label inline" placeholder="최대(<?php echo lang("price_unit.form");?>)" data-dropdown-id="fullrent-price-max" notsubmit style="width:105px;"/>
				</div>
				<div class="clearfix"></div>
				<ul id="fullrent-price-min" class="price-range list-unstyled">
				<?php for($i=0; $i<=10; $i++){?>
					<li data-value="<?php echo ($config->FULL_MAX * $i/10)?>" notsubmit><?php echo ($config->FULL_MAX * $i/10).lang("price_unit.form");?> + </li>
				<?php }?>
				</ul>
				<ul id="fullrent-price-max" class="price-range text-right list-unstyled hide">
				<?php for($i=0; $i<=10; $i++){?>
					<?php if($i==10){?>
					<li data-value="<?php echo ($config->FULL_MAX * $i/10)?>" notsubmit>제한없음</li>
					<?php } else { ?>
					<li data-value="<?php echo ($config->FULL_MAX * $i/10)?>" notsubmit><?php echo ($config->FULL_MAX * $i/10).lang("price_unit.form");?></li>
					<?php } ?>
				<?php }?>
				</ul>
			</div>
		</div>	

		<!-- 월세 가격 검색 -->
		<div class="btn-group price_range price_rent" style="margin-right:10px;">
			<!-- 월세는 보증금과 월세로 입력한다. -->
			<div style="display:inline-block">
				<button type="button" class="dropdown-toggle btn-price btn btn-default" href="#" aria-expanded="true" data-toggle="dropdown"><div class="price_label"><?php echo lang("product.price.deposit");?></div>  <strong class="caret"></strong></button>
				<div class="dropdown-menu" style="min-width:250px;padding-top:10px;">
					<div class="text-center">
						<input name="month_deposit_start" class="form-control price-label inline" placeholder="최소(<?php echo lang("price_unit.form");?>)" data-dropdown-id="month_deposit-price-min" notsubmit style="width:105px;"/> - 
						<input name="month_deposit_end" class="form-control price-label inline" placeholder="최대(<?php echo lang("price_unit.form");?>)" data-dropdown-id="month_deposit-price-max" notsubmit style="width:105px;"/>
					</div>
					<div class="clearfix"></div>
					<ul id="month_deposit-price-min" class="price-range list-unstyled">
					<?php for($i=0; $i<=10; $i++){?>
						<li data-value="<?php echo ($config->MONTH_DEPOSIT_MAX * $i/10)?>" notsubmit><?php echo ($config->MONTH_DEPOSIT_MAX * $i/10).lang("price_unit.form");?> + </li>
					<?php }?>
					</ul>
					<ul id="month_deposit-price-max" class="price-range text-right list-unstyled hide">
					<?php for($i=0; $i<=10; $i++){?>
						<?php if($i==10){?>
						<li data-value="<?php echo ($config->MONTH_DEPOSIT_MAX * $i/10)?>" notsubmit>제한없음</li>
						<?php } else { ?>
						<li data-value="<?php echo ($config->MONTH_DEPOSIT_MAX * $i/10)?>" notsubmit><?php echo ($config->MONTH_DEPOSIT_MAX * $i/10).lang("price_unit.form");?></li>
						<?php } ?>
					<?php }?>
					</ul>
				</div>
			</div>
			<div style="display:inline-block" style="margin-right:10px;">
				<button type="button" class="dropdown-toggle btn-price btn btn-default" href="#" aria-expanded="true" data-toggle="dropdown"><div class="price_label"><?php echo lang("monthly_rent");?></div>  <strong class="caret"></strong></button>							
				<div class="dropdown-menu" style="min-width:250px;padding-top:10px;">
					<div class="text-center">
						<input name="month_start" class="form-control price-label inline" placeholder="최소(<?php echo lang("price_unit.form");?>)" data-dropdown-id="month-price-min" notsubmit style="width:105px;"/> - 
						<input name="month_end" class="form-control price-label inline" placeholder="최대(<?php echo lang("price_unit.form");?>)" data-dropdown-id="month-price-max" notsubmit style="width:105px;"/>
					</div>
					<div class="clearfix"></div>
					<ul id="month-price-min" class="price-range list-unstyled">
					<?php for($i=0; $i<=10; $i++){?>
						<li data-value="<?php echo ($config->MONTH_MAX * $i/10)?>" notsubmit><?php echo ($config->MONTH_MAX * $i/10).lang("price_unit.form");?> + </li>
					<?php }?>
					</ul>
					<ul id="month-price-max" class="price-range text-right list-unstyled hide">
					<?php for($i=0; $i<=10; $i++){?>
						<?php if($i==10){?>
						<li data-value="<?php echo ($config->MONTH_MAX * $i/10)?>" notsubmit>제한없음</li>
						<?php } else { ?>
						<li data-value="<?php echo ($config->MONTH_MAX * $i/10)?>" notsubmit><?php echo ($config->MONTH_MAX * $i/10).lang("price_unit.form");?></li>
						<?php } ?>
					<?php }?>
					</ul>
				</div>
			</div>
		</div>


		<?php if($config->USE_THEME){?>
			<select id="search_2" name="theme[]" multiple="multiple" class="btn btn-link display-none">
			<?php 
			foreach($theme as $val){ ?>
				<option value="<?php echo $val->id;?>"> <?php echo $val->theme_name;?></option>
			<?php }?>
			</select>
		<?php }?>

	</div>
</div>



<!--지역검색 modal-->
<div id="address_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" style="padding:0">
				<div class="body">
					<div class="bg-primary text-center">
						<div style="padding:7px 0;">
							<span id="label_text">시도를 선택하세요<span>
						</div>		
					</div>
					<div class="select_label bg-info text-center" style="padding:5px 0 25px 0">
						<strong id="sido_label"><span class="col-xs-4" style="color:#333">시도<i class="ion-chevron-right pull-right"></i></span></strong>
						<strong id="gugun_label"><span class="col-xs-4" style="color:#333"><strong>구군</strong><i class="ion-chevron-right pull-right"></i></span></strong>
						<strong id="dong_label"><span class="col-xs-4" style="color:#333"><strong>읍면동</strong></span></strong>		
					</div>
					<div class="separator-fields"></div>
					<div class="text-center">
						<div class="btn-group-vertical" id="sido_section">
							<ul>
								<li>
									<div class="btn-group-vertical">
										<?php foreach($sido as $val){?>
										<button type="button" class="btn btn-default" onclick="get_gugun_modal(this,'<?php echo $val->sido?>');"><?php echo $val->sido?></button>
										<?php }?>
									</div>
								</li>
							</ul>
						</div>
						<div class="btn-group-vertical" id="gugun_section">
							<ul>
								<li>
									<div class="btn-group-vertical"></div>
								</li>
							</ul>
						</div>
						<div class="btn-group-vertical" id="dong_section">
							<ul>
								<li>
									<div class="btn-group-vertical"></div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--지하철검색 modal-->
<div id="subway_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" style="padding:0">
				<div class="body">
					<div class="bg-primary text-center">
						<div style="padding:7px 0;">
							<span id="label_text"><?php echo lang("site.location");?>을 선택하세요<span>
						</div>		
					</div>
					<div class="select_label bg-info text-center" style="padding:5px 0 25px 0">
						<strong id="local_label"><span class="col-xs-4" style="color:#333"><?php echo lang("site.location");?><i class="ion-chevron-right pull-right"></i></span></strong>
						<strong id="hosun_label"><span class="col-xs-4" style="color:#333"><strong>호선</strong><i class="ion-chevron-right pull-right"></i></span></strong>
						<strong id="station_label"><span class="col-xs-4" style="color:#333"><strong>역</strong></span></strong>		
					</div>
					<div class="separator-fields"></div>

					<div class="text-center">
						<div class="btn-group-vertical" id="local_section">
							<ul>
								<li>
									<div class="btn-group-vertical">
										<?php foreach($local as $val){?>
										<button type="button" class="btn btn-default" onclick="get_hosun_modal(this,'<?php echo $val->local?>');"><?php echo $val->local_text?></button>
										<?php }?>
									</div>
								</li>
							</ul>
						</div>
						<div class="btn-group-vertical" id="hosun_section">
							<ul>
								<li>
									<div class="btn-group-vertical"></div>
								</li>
							</ul>
						</div>
						<div class="btn-group-vertical" id="station_section">
							<ul>
								<li>
									<div class="btn-group-vertical"></div>
								</li>
							</ul>
						</div>		
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div style="clear:both;"></div>