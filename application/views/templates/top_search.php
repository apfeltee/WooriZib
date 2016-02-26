<script>
$(document).ready(function(){

	/*매물유형 검색*/
	$("#search_1").multiselect({
		buttonClass: "btn btn-link",
		onChange: function(element) {
			init_price();
		}
	});

	/*테마 검색*/
	$("#search_2").multiselect({
		buttonText: function(options) {
			return "<?php echo lang("product.theme")?>("+options.length+")";
		},
		includeSelectAllOption: true,
		selectAllText: "<?php echo lang("site.all")?>",
		buttonClass: "btn btn-link"
	});

	/*지하철 호선별 검색*/
	$("#search_3").multiselect({
		buttonText: function(options) {
			return "<?php echo lang("site.subwaylinesearch");?>("+options.length+")";
		},
		includeSelectAllOption: true,
		enableHTML: true,
		selectAllText: "<?php echo lang("site.all")?>",
		buttonClass: "btn btn-link"
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
		send_form();
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
		send_form();
	});

	$("#category_count").text($(".multiselect_category").find("input:checked").length);
});
</script>

<div class="input-group pull-left" style="width:250px;margin-top:5px;padding-right:10px;">
	<input type="text" id="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product.no");?>, <?php echo lang("site.title");?>" autocomplete="off" value="<?php if(element("search_type",$search)=="google") echo element("search_value",$search);?>"/>
	<div class="input-group-btn">
		<button id="go_keyword" class="btn btn-default" type="button" style="margin:0 0 0 -1px;" title="검색"><i class="fa fa-search"></i></button>
	</div>
</div>

<div class="pull-left">
	<button class="btn btn-default" type="button" data-toggle="modal" data-target="#address_modal" title="주소검색"><i class="fa fa-map"></i></button>
	<button class="btn btn-default" type="button" data-toggle="modal" data-target="#subway_modal" title="지하철검색"><i class="fa fa-subway"></i></button>

	<div class="btn-group multiselect_category">
		<button class="multiselect dropdown-toggle btn btn-link" aria-expanded="true" type="button" data-toggle="dropdown"><span class="multiselect-selected-text"><?php echo lang("search.type")?>(<span id="category_count">0</span>)</span> <b class="caret"></b></button>
		<ul class="multiselect-container dropdown-menu">
		<?php
		$search_category = array();
		if(element("category",$search)!=""){
			$search_category = @explode(",",element("category",$search));
		}
		foreach($category as $val){ ?>
			<li class="multiselect-item multiselect-group">
				<a><label><input class="group_checkbox" name="category[]" type="checkbox" value="<?php echo $val->id;?>" style="margin:5px 0px 5px -10px;" data-id="<?php echo $val->id;?>" <?php echo (in_array($val->id,$search_category)) ? "checked":"";?>> <?php echo $val->name;?></label></a>
			</li>
			<?php 
			if(isset($val->category_sub)){
				$search_category_sub = array();
				if(element("category",$search)!=""){
					$search_category_sub = @explode(",",element("category_sub",$search));
				}
				foreach($val->category_sub as $sub_val){?>
				<li class="multiselect_li<?php echo $val->id;?> <?php echo (in_array($sub_val->id,$search_category_sub)) ? "active":"";?>">									
					<a><label class="checkbox"><input class="sub_checkbox multiselect_sub<?php echo $val->id;?>" name="category_sub[]" type="checkbox" value="<?php echo $sub_val->id;?>" <?php echo (in_array($sub_val->id,$search_category_sub)) ? "checked":"";?>> <?php echo $sub_val->name;?></label></a>
				</li>
				<?php }?>
			<?php }?>
		<?php }?>
		</ul>
	</div>

	<?php if($config->INSTALLATION_FLAG!="2"){?>
	<select id="search_1" name="type" class="search_item display-none">
		<option value=""><?php echo lang("site.all");?></option>
		<?php if($config->INSTALLATION_FLAG=="1"){?>
		<option value="installation" <?php echo (element("type",$search)=="installation") ? "selected":"";?>><?php echo lang('installation');?></option>
		<?php }?>
		<option value="sell" <?php echo (element("type",$search)=="sell") ? "selected":"";?>><?php echo lang('sell');?></option>
		<?php if(lang('full_rent')!=""){?>
		<option value="full_rent" <?php echo (element("type",$search)=="full_rent") ? "selected":"";?>><?php echo lang('full_rent');?></option>
		<?php } ?>
		<option value="monthly_rent" <?php echo (element("type",$search)=="monthly_rent") ? "selected":"";?>><?php echo lang('monthly_rent');?></option>
	</select>
	<?php } else {?>
		<input type="hidden" name="type" value="installation"/>
	<?php }?>

	<!-- 
		$config->INSTALLATION_FLAG는 2면 분양만 사용하는 것이므로 늘 매매유형이 고정이어서 가격검색을 항상 보여주도록 하기 때문에 price_range부분을 없엔다. 
		price_range는 최초에 안 보이도록 하는 클래스이다. 
	-->
	<!-- 매매, 분양 가격 검색 -->
	<div class="btn-group <?php if($config->INSTALLATION_FLAG!="2") echo "price_range";?> price_sell">
		<button type="button" class="dropdown-toggle btn-price btn btn-link" href="#" aria-expanded="true" data-toggle="dropdown"><div class="price_label sell_label"><?php echo ($config->INSTALLATION_FLAG=="2") ? lang('installation') : lang('sell');?>가</div>&nbsp;<strong class="caret"></strong></button>
		<div class="dropdown-menu" style="min-width:250px;padding-top:10px;">
			<div class="text-center">
				<input name="sell_start" class="form-control price-label inline" placeholder="최소(<?php echo lang("sell_unit");?>)" data-dropdown-id="sell-price-min" style="width:105px;" value="<?php echo element("sell_start",$search);?>"/> - 
				<input name="sell_end" class="form-control price-label inline" placeholder="최대(<?php echo lang("sell_unit");?>)" data-dropdown-id="sell-price-max" style="width:105px;" value="<?php echo element("sell_end",$search);?>"/>
			</div>
			<div class="clearfix"></div>
			<ul id="sell-price-min" class="price-range list-unstyled">
			<?php for($i=0; $i<=10; $i++){?>
				<li data-value="<?php echo ($config->SELL_MAX * $i/10)?>"><?php echo ($config->SELL_MAX * $i/10).lang("sell_unit");?> + </li>
			<?php }?>
			</ul>
			<ul id="sell-price-max" class="price-range text-right list-unstyled hide">
			<?php for($i=0; $i<=10; $i++){?>
				<?php if($i==10){?>
				<li data-value="<?php echo ($config->SELL_MAX * $i/10)?>">제한없음</li>
				<?php } else { ?>
				<li data-value="<?php echo ($config->SELL_MAX * $i/10)?>"><?php echo ($config->SELL_MAX * $i/10).lang("sell_unit");?></li>
				<?php } ?>
			<?php }?>
			</ul>
		</div>
	</div>
	<!-- 전세 가격 검색 -->
	<div class="btn-group price_range price_full">
		<button type="button" class="dropdown-toggle btn-price btn btn-link" href="#" aria-expanded="true" data-toggle="dropdown"><div class="price_label"><?php echo lang('full_rent');?>가</div>  <strong class="caret"></strong></button>
		<div class="dropdown-menu" style="min-width:250px;padding-top:10px;">
			<div class="text-center">
				<input name="full_start" class="form-control price-label inline" placeholder="최소(<?php echo lang("price_unit.form");?>)" data-dropdown-id="fullrent-price-min" style="width:105px;" value="<?php echo element("full_start",$search);?>"/> - 
				<input name="full_end" class="form-control price-label inline" placeholder="최대(<?php echo lang("price_unit.form");?>)" data-dropdown-id="fullrent-price-max" style="width:105px;" value="<?php echo element("full_end",$search);?>"/>
			</div>
			<div class="clearfix"></div>
			<ul id="fullrent-price-min" class="price-range list-unstyled">
			<?php for($i=0; $i<=10; $i++){?>
				<li data-value="<?php echo ($config->FULL_MAX * $i/10)?>"><?php echo ($config->FULL_MAX * $i/10).lang("price_unit.form");?> + </li>
			<?php }?>
			</ul>
			<ul id="fullrent-price-max" class="price-range text-right list-unstyled hide">
			<?php for($i=0; $i<=10; $i++){?>
				<?php if($i==10){?>
				<li data-value="<?php echo ($config->FULL_MAX * $i/10)?>">제한없음</li>
				<?php } else { ?>
				<li data-value="<?php echo ($config->FULL_MAX * $i/10)?>"><?php echo ($config->FULL_MAX * $i/10).lang("price_unit.form");?></li>
				<?php } ?>
			<?php }?>
			</ul>
		</div>
	</div>	
	<!-- 월세 가격 검색 -->
	<div class="btn-group price_range price_rent">
		<!-- 월세는 보증금과 월세로 입력한다. -->
		<div style="display:inline-block">
			<button type="button" class="dropdown-toggle btn-price btn btn-link" href="#" aria-expanded="true" data-toggle="dropdown"><div class="price_label"><?php echo lang("product.price.deposit");?></div>  <strong class="caret"></strong></button>
			<div class="dropdown-menu" style="min-width:250px;padding-top:10px;">
				<div class="text-center">
					<input name="month_deposit_start" class="form-control price-label inline" placeholder="최소(<?php echo lang("price_unit.form");?>)" data-dropdown-id="month_deposit-price-min" style="width:105px;" value="<?php echo element("month_deposit_start",$search);?>"/> - 
					<input name="month_deposit_end" class="form-control price-label inline" placeholder="최대(<?php echo lang("price_unit.form");?>)" data-dropdown-id="month_deposit-price-max" style="width:105px;" value="<?php echo element("month_deposit_end",$search);?>"/>
				</div>
				<div class="clearfix"></div>
				<ul id="month_deposit-price-min" class="price-range list-unstyled">
				<?php for($i=0; $i<=10; $i++){?>
					<li data-value="<?php echo ($config->MONTH_DEPOSIT_MAX * $i/10)?>"><?php echo ($config->MONTH_DEPOSIT_MAX * $i/10).lang("price_unit.form");?> + </li>
				<?php }?>
				</ul>
				<ul id="month_deposit-price-max" class="price-range text-right list-unstyled hide">
				<?php for($i=0; $i<=10; $i++){?>
					<?php if($i==10){?>
					<li data-value="<?php echo ($config->MONTH_DEPOSIT_MAX * $i/10)?>">제한없음</li>
					<?php } else { ?>
					<li data-value="<?php echo ($config->MONTH_DEPOSIT_MAX * $i/10)?>"><?php echo ($config->MONTH_DEPOSIT_MAX * $i/10).lang("price_unit.form");?></li>
					<?php } ?>
				<?php }?>
				</ul>
			</div>
		</div>
		<div style="display:inline-block">
			<button type="button" class="dropdown-toggle btn-price btn btn-link" href="#" aria-expanded="true" data-toggle="dropdown"><div class="price_label"><?php echo lang("monthly_rent");?></div>  <strong class="caret"></strong></button>							
			<div class="dropdown-menu" style="min-width:250px;padding-top:10px;">
				<div class="text-center">
					<input name="month_start" class="form-control price-label inline" placeholder="최소(<?php echo lang("price_unit.form");?>)" data-dropdown-id="month-price-min" style="width:105px;" value="<?php echo element("month_start",$search);?>"/> - 
					<input name="month_end" class="form-control price-label inline" placeholder="최대(<?php echo lang("price_unit.form");?>)" data-dropdown-id="month-price-max" style="width:105px;" value="<?php echo element("month_end",$search);?>"/>
				</div>
				<div class="clearfix"></div>
				<ul id="month-price-min" class="price-range list-unstyled">
				<?php for($i=0; $i<=10; $i++){?>
					<li data-value="<?php echo ($config->MONTH_MAX * $i/10)?>"><?php echo ($config->MONTH_MAX * $i/10).lang("price_unit.form");?> + </li>
				<?php }?>
				</ul>
				<ul id="month-price-max" class="price-range text-right list-unstyled hide">
				<?php for($i=0; $i<=10; $i++){?>
					<?php if($i==10){?>
					<li data-value="<?php echo ($config->MONTH_MAX * $i/10)?>">제한없음</li>
					<?php } else { ?>
					<li data-value="<?php echo ($config->MONTH_MAX * $i/10)?>"><?php echo ($config->MONTH_MAX * $i/10).lang("price_unit.form");?></li>
					<?php } ?>
				<?php }?>
				</ul>
			</div>
		</div>
	</div>

	<?php if($config->USE_THEME){?>
		<select id="search_2" name="theme[]" multiple="multiple" class="search_item btn btn-link display-none">
		<?php 
		$search_theme = array();
		if(element("theme",$search)!=""){
			$search_theme = @explode(",",element("theme",$search));
		}
		foreach($theme as $val){ ?>
			<option value="<?php echo $val->id;?>" <?php echo (in_array($val->id,$search_theme)) ? "selected":"";?>> <?php echo $val->theme_name;?></option>
		<?php }?>
		</select>
	<?php }?>

	<?php if($config->SUBWAY && $subway_line){?>
		<select id="search_3" name="subway_line[]" multiple="multiple" class="search_item btn btn-link display-none">
		<?php 
		$search_subway_line = array();
		if(element("subway_line",$search)!=""){
			$search_subway_line = @explode(",",element("subway_line",$search));
		}							
		foreach($subway_line as $val){ ?>
			<option value="<?php echo $val->hosun_id;?>" <?php echo (in_array($val->hosun_id,$search_subway_line)) ? "selected":"";?>> &lt;i class="fa fa-square sub_color_<?php echo $val->hosun_id;?>"&gt;&lt;/i&gt; <?php echo (is_numeric($val->hosun)) ? $val->hosun.toeng("호선") : $val->hosun.toeng("선");?></option>
		<?php }?>
		</select>
	<?php }?>

	<button class="btn btn-link" type="button" onclick="search_reset()"> <i class="glyphicon glyphicon-refresh"></i> 초기화</button>
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
						<strong id="sido_label"><span class="col-xs-4">시도<i class="ion-chevron-right pull-right"></i></span></strong>
						<strong id="gugun_label"><span class="col-xs-4"><strong>구군</strong><i class="ion-chevron-right pull-right"></i></span></strong>
						<strong id="dong_label"><span class="col-xs-4"><strong>읍면동</strong></span></strong>		
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
						<strong id="local_label"><span class="col-xs-4"><?php echo lang("site.location");?><i class="ion-chevron-right pull-right"></i></span></strong>
						<strong id="hosun_label"><span class="col-xs-4"><strong>호선</strong><i class="ion-chevron-right pull-right"></i></span></strong>
						<strong id="station_label"><span class="col-xs-4"><strong>역</strong></span></strong>		
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