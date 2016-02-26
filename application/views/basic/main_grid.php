<style>
.pac-container {
	display:none;
}
</style>
<script src="/assets/plugin/organictabs.jquery.js"></script>
<script type="text/javascript" src="/script/src/search"></script>
<link rel="stylesheet" href="/assets/plugin/multiselect/css/bootstrap-multiselect.css" type="text/css">
<script type="text/javascript" src="/assets/plugin/multiselect/js/bootstrap-multiselect.js"></script>
<script>
var data;
var idle_init = 0;
var total;

$(document).ready(function(){

	init_search("<?php echo element('type',$search);?>","<?php echo element('category',$search);?>","<?php echo element('category_sub',$search);?>");

	// 폼전송할 때에는 먼저 폼값을 세션에 저장한 후에 목록을 가져오는 순서로 진행이 된다.
	// 검색조건이 바뀌게 되면 next_page값이 0이 되어야 하며 more를 누르게 되면 next_page는 현재 값으로 유지가 된다.

	$('#search_form').ajaxForm( {
		beforeSubmit: function()
		{
			/** map은 여기서 move_map을 했는데 grid는 그럴 필요가 없으니까 그냥 냅두자 **/
		},
		success: function(data)
		{
			//beforeSend에서 next_page값을 변경하였다고 해서 url이 변경되는 것은 아니다.
			$.ajax({
				type: 'POST',
				url: '/main/listing_json/'+$("#next_page").val(),
				data : {per_page:"<?php if($config->SEARCH_POSITION){?>12<?php } else { ?>9<?php } ?>"},
				cache: false,
				dataType: 'json',
				beforeSend: function(){
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

					if(next_page<13){
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
		$("#next_page").val("0");
		send_form();
	});

	$("#search_tab").organicTabs();

	/** 주소가 이미 있다면 주소가 세팅된 후에 다시 submit이 날라가기 때문에 여기서는 동작하지 않아야한다. **/
	
	<?php if( element("search_type",$search) == "" || element("search_type",$search) == "parent_address" || element("search_type",$search) == "google"  || element("search_type",$search) == "theme"){?>
		$("#next_page").val("0");
		send_form();
	<?php }?>

	$('.category_checkbox').on('ifChanged', function(event){
		$("#next_page").val("0");
		send_form();
		
	});

	category_sub_change();

	var region = "<?php echo element("region",$search);?>";
	if(region!=""){
		$("#region").val(region);
		send_form();
	}
});

function init_position(){
	
	if($("#danzi").length > 0){
		$("#danzi").remove();
	}

	$("#search_1").multiselect("refresh"); //상단검색시 초기화
	$("#search_2").multiselect("refresh"); //상단검색시 초기화
	$("#search_3").multiselect("refresh"); //상단검색시 초기화
	$("#category_count").text("0");
	$(".multiselect_category").find("li").removeClass("active");

	//-- 아래 코드는 지도에서만 필요하므로 주석처리한다.
	//initialize(<?php echo $config->lat;?>, <?php echo $config->lng;?>, <?php echo $config->MAP_INIT_LEVEL?>, <?php echo $config->maxzoom;?>);
}

/**
 * 페이징에서 더 보기를 클릭했을 때 동작하는 기능
 */
function more(){
	//more에서는 send_form()을 호출하면 안된다. 이건 next_page를 0으로 만들고 하는 것이다.
	$("#search_form").trigger("submit");
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
			<div class="top_search pull-left">
				<?php if($config->SEARCH_POSITION){?>
					<?php echo $this->load->view("templates/top_search");?>
				<?php } else { ?>
					<div class="result_label"></div>
				<?php }?>
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

<div class="main">
	<div class="_container margin-bottom-20">
		<div class="row">
			<?php if($config->SEARCH_POSITION==0){?>
			<div class="col-md-3">
				<div class="margin-bottom-10">
					<button class="btn btn-primary" onclick="search_reset()" style="width:100%;"><i class="glyphicon glyphicon-refresh"></i> <?php echo lang("site.initfilter");?></button>
				</div>
				<div class="search-wrapper">
						<div id="search_tab">
							<?php if($config->SUBWAY) {?>
							<ul class="nav">
								
								<li class="nav-one">
									<a href="#local_section" <?php if(element("search_type",$search)=="" or element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {echo "class='current'";}?>>지역</a>
								</li>
								<li class="nav-two">
									<a href="#sybway_section" <?php if(element("search_type",$search)=="subway") {echo "class='current'";}?>>지하철</a>
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
						<?php echo $this->load->view("templates/left_search");?>
				</div> <!-- search-wrapper -->

				<?php echo $template_left;?>

			</div>
			<?php } ?>
			<div class="col-md-<?php if($config->SEARCH_POSITION==0){?>9<?php } else { ?>12<?php } ?>">
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
								<th style="width:50px;">거래 </th>								
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