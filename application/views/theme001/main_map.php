<style>
	html, body {
		height:100%;
	}
</style>
<script>
	
	/*************************************************************************************************
	 * sell_unit은 매매가 단위, etc_unit은 기타 금액 단위
	 * 이렇게 한 이유는 통상적으로 매매가는 억원 단위이고 기타 전세, 월세는 10000원 단위이기 때문이며
	 * 매매가의 경우 천만원을 단위로 쓰고 싶어하는 곳도 있다.
	 *************************************************************************************************/
	
	var sell_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("sell_unit");?></font>";
	var price_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("price_unit");?></font>";
	var address_type="front"; /** 공개매물 등록된 주소 가져오도록 **/
</script>

<link href="/assets/basic/css/map_daum.css" rel="stylesheet">
<script src="/script/src/map_daum"></script>
<script src="/assets/plugin/organictabs.jquery.js"></script>
<script type="text/javascript" src="/assets/basic/js/search.js"></script>

<script>
var data;
var idle_init = 0;

$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */	

	/** 지도 크기 설정 **/
	$(".footer").hide();
	$("body").css("overflow", "hidden");
	mapsize();

	$("#pagination_top").click(function(){
		$("#map_list").animate({
			scrollTop: 0
		}, 600);	
	});
		
	/** 모바일에서 클러스터 창을 닫는 버튼 **/
	$("div.clusterClose").click(function() {
		 $("#clusterlist").fadeOut("normal");  
	});

	init_search("<?php echo element('type',$search);?>","<?php echo element('category',$search);?>");

	<?php 
		/*** 검색에서 넘어왔으면 검색에 대한 값으로 초기화를 하고 그렇지 않으면 부동산의 주소로 초기화를 한다 ***/
		if(element("search_type",$search)!="" && element("keyword_front",$search)==""){
	?>
		initialize(<?php echo element('lat',$search);?>, <?php echo element('lng',$search);?>, <?php echo element('zoom',$search);?>, <?php echo $config->maxzoom;?>);
	<?php 
		} else { 
	?>
		initialize(<?php echo $config->lat;?>, <?php echo $config->lng;?>, <?php echo $config->MAP_INIT_LEVEL?>, <?php echo $config->maxzoom;?>);
	<?php 
		} 
	?>

	/************************************************************************************
	 * 폼을 전송하기 전에 지정된 위치가 있다면 해당 위치로 먼저 이동한 후에 submit을 한다.
	 * 이미 초기화를 하여 정보가 일치하면 move_map이 되면 안된다.
	 ************************************************************************************/
	$('#search_form').ajaxForm( {
		beforeSubmit: function()
		{	
			/** if($("#lat").val()!='' && $("#lng").val()!='' && $("#lat").val()!="<?php echo element('lat',$search);?>" && $("#lng").val()!="<?php echo element('lng',$search);?>")  **/
			/** 위의 코드는 move_map을 줄이기 위해서 넣었던 코드였는데 문제가 있다. 예를 들어서 메인에서 신림역을 검색해서 들어온 후 다시 잠실로 가고 다시 신림역으로 돌아올 때 이동이 안된다. **/

			if($("#danzi").val()!=""){
				$.ajax({
					url: "/danzi/get_json/"+$("#danzi").val(),
					type: "GET",
					async: false,
					dataType: "json",
					success: function(data) {
						$("#lat").val(data["lat"]);
						$("#lng").val(data["lng"]);
					}
				});			
			}

			if($("#lat").val()!='' && $("#lng").val()!='')
			{
				if($("#search_type").val()=="parent_address"){
					move_map($("#lat").val(), $("#lng").val(),5);
				}
				else{
					move_map($("#lat").val(), $("#lng").val(),<?php echo $config->maxzoom;?>);
				}
			}
		},
		success: function(data)
		{
			call_map();
		}
	});

	$("#search_tab").organicTabs();

	$(".pocp_button").not($(".pocp_button_reset")).click(function(){
		if($(this).hasClass("btn_active")){
			$(this).removeClass("btn_active");
			$(".pocp_button_reset").removeClass("btn_active");
			$(this).html("<i class=\"fa fa-chevron-right\"></i> <?php echo lang("site.search");?>");
			$("#search_section").stop().animate({left: '-250px'}, 400, 'easeInOutCirc');
		} else {
			$(this).addClass("btn_active");
			$(".pocp_button_reset").addClass("btn_active");
			$(this).html("<i class=\"fa fa-chevron-left\"></i> <?php echo lang("site.search");?>");
			$("#search_section").stop().animate({left: '0px'}, 400, 'easeInOutCirc');
		}
	});

	<?php if( element("search_type",$search) == "" || element("search_type",$search) == "parent_address" || element("search_type",$search) == "google" || element("search_type",$search) == "theme"){?>
	$("#search_form").trigger("submit");
	<?php }?>

	$(".search_item").change(function(){
		calling = 1; /**지도에서 정렬순서를 변경하면 자동으로 show_infowindow가 호출이 되어서 데이터 갱신을 하지 않는 문제점이 있어서 수정하였다. **/
		loading_delay(true);
		setTimeout(function () {
			loading_delay(false);
			calling = 0;
		}, 400);

		init_price();
		$('#search_form').trigger('submit');
	});

	$('.category_checkbox').on('ifChanged', function(event){
		loading_delay(true);
		setTimeout(function () {
			loading_delay(false);
		}, 400);
		

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

	if($("#danzi").length > 0){
		$("#danzi").remove();
	}

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

	initialize(<?php echo $config->lat;?>, <?php echo $config->lng;?>, <?php echo $config->MAP_INIT_LEVEL?>, <?php echo $config->maxzoom;?>);
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
<input type="hidden" id="reset" name="reset"/>
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
				<?php } else { ?>
					<div class="result_label"></div>
				<?php }?>
				<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=1 END-->				
				
			</div>
			<div class="pull-right text-right">
				<div class="btn-group">
				  <a href="#" class="btn btn-default active"><i class="fa fa-map-marker"></i> <?php echo lang("site.map");?></a>
				  <a href="/main/grid" class="btn btn-default"><i class="fa fa-th-large"></i> <?php echo lang("site.list");?></a>
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
	</div>
</div>

<!-- 지도 시작 -->
<div class="map_wrapper maplist_<?php echo $config->MAP_STYLE;?>">
	<div id="loading"><i class="fa fa-spinner fa-spin"></i></div>
	<a href="#" class="pocp_button pocp_button_left btn_active btn-primary"><i class="fa fa-chevron-left"></i> <?php echo lang("site.search");?></a>
	<a href="#" class="pocp_button pocp_button_left btn_active pocp_button_reset btn-primary" onclick="search_reset()"><i class="glyphicon glyphicon-refresh"></i> <?php echo lang("site.initfilter");?></a>
	<div id="search_section" class="search-wrapper">
		<!-- https://css-tricks.com/organic-tabs/ -->
		<div class="loading_content">
			<div class="loading_background" style="overflow:auto"></div>
			<div class="loading_image"><img src="/assets/common/img/load.gif"></div>
		</div>
		<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=0 START-->
		<?php if(!$config->SEARCH_POSITION){?>
		<div id="search_tab">
			<?php if($config->SEARCH_ORDER==2){?>
			<ul class="nav">
				<li class="nav-one">
					<a href="#google_section" <?php if(element("search_type",$search)=="" or element("search_type",$search)=="google") {echo "class='current'";}?>><?php echo lang("search.total");?></a>
				</li>
				<li class="nav-two">
					<a href="#local_section" <?php if(element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {echo "class='current'";}?>><?php echo lang("site.location");?></a>
				</li>
				<?php if($config->SUBWAY) {?>
				<li class="nav-three last">
					<a href="#sybway_section" <?php if(element("search_type",$search)=="subway") {echo "class='current'";}?>><?php echo lang("site.subway");?></a>
				</li>
				<?php } ?>
			</ul>
			<div class="list-wrap">
				<ul id="google_section" <?php if(element("search_type",$search)=="" or element("search_type",$search)=="google") {} else {echo "style='display:none;'";}?>>
					<li>
						<div class="input-group">
							<input type="text" id="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product");?>번호, 제목" autocomplete="off" value="<?php if(element("search_type",$search)=="google") echo element("search_value",$search);?>" style="font-size: 12px;height:28px;"/>
							<div class="input-group-btn">
								<button id="go_keyword" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</li>
				</ul>
				<ul id="local_section"  
					<?php if(element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {} else {echo "style='display:none;'";}?>>
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
			</div>
			<?php } else {?>
			<ul class="nav">
				<li class="nav-one">
					<a href="#local_section" <?php if(element("search_type",$search)=="" or element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {echo "class='current'";}?>><?php echo lang("site.location");?></a>
				</li>
				<?php if($config->SUBWAY) {?>
				<li class="nav-two">
					<a href="#sybway_section" <?php if(element("search_type",$search)=="subway") {echo "class='current'";}?>><?php echo lang("site.subway");?></a>
				</li>
				<?php } ?>
				<li class="nav-three last">
					<a href="#google_section" <?php if(element("search_type",$search)=="google") {echo "class='current'";}?>><?php echo lang("search.total");?></a>
				</li>
			</ul>
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
							<input type="text" id="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product");?>번호, 제목" autocomplete="off" value="<?php if(element("search_type",$search)=="google") echo element("search_value",$search);?>" style="font-size: 12px;height:28px;"/>
							<div class="input-group-btn">
								<button id="go_keyword" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</li>
				 </ul>
			</div>
			<?php } ?>
		</div><!-- search_tab -->
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
			<?php } else {?>
			<input type="hidden" name="type" value="installation">
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
			<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=1 START-->
			<?php if($config->SEARCH_POSITION){?>
				<!-- 주소 및 종류 검색 위치 상단 SEARCH_POSITION=1 END-->
				<li>
					<div class="input-group">
						<input type="text" id="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product");?>번호, 제목" autocomplete="off" value="<?php if(element("search_type",$search)=="google") echo element("search_value",$search);?>" style="font-size: 12px;height:28px;"/>
						<div class="input-group-btn">
							<button id="go_keyword" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
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
	</div><!-- search_section -->
	<div id="map"></div>
	<div id="map_list">
		<div class="right_tiles" style="padding:3px 0 5px 10px;border-bottom:1px solid #efefef;">
			<span id="center_address"></span>
			<?php 
				$sch = "";
				if(isset($search["sorting"])){
					$sch = $search["sorting"];
				}
			?>
			<select name="sorting" class="search_item sorting_select">
				<option value="basic" <?php if($sch=="" && "basic"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="basic") {echo "selected";}?>><?php echo lang("sort.recommend");?></option>
				<option value="speed" <?php if($sch=="" && "speed"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="speed") {echo "selected";}?>><?php echo lang("sort.recommend");?></option>
				<option value="date_desc" <?php if($sch=="" && "date_desc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="date_desc") {echo "selected";}?>><?php echo lang("sort.newest");?></option>
				<option value="date_asc" <?php if($sch=="" && "date_asc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="date_asc") {echo "selected";}?>><?php echo lang("sort.oldest");?></option>
				<option value="price_desc" <?php if($sch=="" && "price_desc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="price_desc") {echo "selected";}?>><?php echo lang("sort.high");?></option>
				<option value="price_asc" <?php if($sch=="" && "price_asc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="price_asc") {echo "selected";}?>><?php echo lang("sort.low");?></option>
				<option value="area_desc" <?php if($sch=="" && "area_desc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="area_desc") {echo "selected";}?>><?php echo lang("sort.big");?></option>
				<option value="area_asc" <?php if($sch=="" && "area_asc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="area_asc") {echo "selected";}?>><?php echo lang("sort.small");?></option>
			</select>
			<div style="clear:both"></div>
		</div>
		<div id="map_search_list"></div>
		<div style="padding:10px;text-align:center;">
			<input type="hidden" id="next_page"/>
			<button type="button" id="pagination_more" class="btn btn-default" style="width:80%;" onclick="more();"><i class="fa fa-chevron-circle-down"></i> <?php echo lang("site.more");?></button>
			<button type="button" id="pagination_top" class="btn btn-default" style="width:15%;"><i class="fa fa-arrow-circle-up"></i></button>
		</div>
	</div>
</div>
<!-- 지도 종료 -->

<?php echo form_close();?>
<div style="clear:both;"></div>

<div id="clusterlist">
	<h4 style="position:absolute;top:0px;"><a href="#" id="clusterlist_title" onclick="$('#clusterlist').fadeOut('normal');"></a></h4>
	<div class="clusterClose"></div>
	<div id="clusterlist_inner"></div>	
</div>

<div class='toast' style='display:none'><?php echo lang("msg.map.not");?></div>