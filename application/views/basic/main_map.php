<style>
html, body {
	height:100%;
}

/*모달창 뜰때 움찔거리는거 제거(map만) */
body.modal-open{
	margin:0px !important;
	padding:0px !important;
}
</style>
<script src="/script/src/map_daum"></script>
<script src="/assets/plugin/organictabs.jquery.js"></script>
<script type="text/javascript" src="/script/src/search"></script>

<link rel="stylesheet" href="/assets/plugin/multiselect/css/bootstrap-multiselect.css" type="text/css">
<script type="text/javascript" src="/assets/plugin/multiselect/js/bootstrap-multiselect.js"></script>

<script>
var data;
var idle_init = 0;

$(document).ready(function(){

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

	init_search("<?php echo element('type',$search);?>","<?php echo element('category',$search);?>","<?php echo element('category_sub',$search);?>");

	<?php 
		/*** 검색에서 넘어왔으면 검색에 대한 값으로 초기화를 하고 그렇지 않으면 부동산의 주소로 초기화를 한다 ***/
		if(element("search_type",$search)!="" && element("keyword_front",$search)=="" && element('lat',$search)!=""){
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

	//밑에 로직에서 보면 send_form이 어떤 경우에는 2번 날라가게 되어 있다. 이 부분을 수정을 좀 해야 한다.
	<?php if( element("search_type",$search) == "" || element("search_type",$search) == "parent_address" || element("search_type",$search) == "google" || element("search_type",$search) == "theme" || element("search_type",$search) == "address" || element("search_type",$search) == "subway"){?>
	send_form();
	<?php }?>

	$(".search_item").change(function(){
		calling = 1; /**지도에서 정렬순서를 변경하면 자동으로 show_infowindow가 호출이 되어서 데이터 갱신을 하지 않는 문제점이 있어서 수정하였다. **/
		loading_delay(true);
		setTimeout(function () {
			loading_delay(false);
			calling = 0;
		}, 400);

		init_price();
		send_form();
	});

	$('.category_checkbox').on('ifChanged', function(event){
		loading_delay(true);
		setTimeout(function () {
			loading_delay(false);
		}, 400);		

		send_form();
	});

	category_sub_change();

	var region = "<?php echo element("region",$search);?>";
	if(region!=""){
		$("#region").val(region);
		send_form();
	}
});

/**
 * /script/src/search 에서 search_reset()안에서 호출하는 함수
 */
function init_position(){

	if($("#danzi").length > 0){
		$("#danzi").remove();
	}

	$("#search_1").multiselect("refresh"); //상단검색시 초기화
	$("#search_2").multiselect("refresh"); //상단검색시 초기화
	$("#search_3").multiselect("refresh"); //상단검색시 초기화
	$("#category_count").text("0");
	$(".multiselect_category").find("li").removeClass("active");

	initialize(<?php echo $config->lat;?>, <?php echo $config->lng;?>, <?php echo $config->MAP_INIT_LEVEL?>, <?php echo $config->maxzoom;?>);
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
<?php if($config->SEARCH_POSITION!=2){?>
<div class="band-wrapper">
	<div class="_container">
		<div class="inner">
			<div class="top_search pull-left">
				<!-- 검색 위치 상단형태 START-->
				<?php if($config->SEARCH_POSITION==1){?>
					<?php echo $this->load->view("templates/top_search");?>
				<?php } else { ?>
					<div class="result_label"></div>
				<?php }?>
				<!-- 검색 위치 상단형태 END-->				
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
<?php }?>

<!-- 지도 시작 -->
<div class="map_wrapper maplist_<?php echo $config->MAP_STYLE;?>">
	<!-- 검색 위치 좌측형태 START-->
	<?php if($config->SEARCH_POSITION==0){?>
		<?php echo $this->load->view("templates/left_search_map");?>
	<?php } ?>
	<!-- 검색 위치 좌측형태 END-->
	<div id="map"></div>
	<?php if($config->SEARCH_POSITION==2){?>
	<div id="map_tile">
		<div class="tiles">
			<div class="tile bg-blue-hoki" onclick="location.href='/member/hope'">
				<div class="tile-body">
					<i class="fa fa-heart"></i>
				</div>
				<div class="tile-object">
					<div class="name">
						 찜한 매물
					</div>
					<div class="number"></div>
				</div>
			</div>
			<div class="tile bg-red-sunglo" onclick="location.href='/member/history'">
				<div class="tile-body">
					<i class="fa fa-eye"></i>
				</div>
				<div class="tile-object">
					<div class="name">
						 본 매물
					</div>
					<div class="number"></div>
				</div>
			</div>
			<!--div class="tile bg-green-turquoise">
				<div class="tile-body">
					<i class="fa fa-eye"></i>
				</div>
				<div class="tile-object">
					<div class="name">
						 본 매물
					</div>
					<div class="number"></div>
				</div>
			</div-->
			<div class="tile bg-yellow-saffron" onclick="location.href='/main/intro'">
				<div class="corner">
				</div>
				<div class="tile-body">
					<i class="fa fa-map-marker"></i>
				</div>
				<div class="tile-object">
					<div class="name">
						 회사 소개
					</div>
					<div class="number"></div>
				</div>
			</div>
			<div class="tile bg-blue-madison bookmarkMeLink">
				<div class="corner">
				</div>
				<div class="tile-body">
					<i class="fa fa-star"></i>
				</div>
				<div class="tile-object">
					<div class="name">
						즐겨찾기
					</div>
					<div class="number"></div>
				</div>
			</div>
			<div class="tile bg-purple-studio" onclick="location.href='/main/grid'">
				<div class="corner">
				</div>
				<div class="tile-body">
					<i class="fa fa-list-alt"></i>
				</div>
				<div class="tile-object">
					<div class="name">
						목록
					</div>
					<div class="number"></div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<div id="map_list">
		<?php if($config->SEARCH_POSITION==2){?>
			<?php echo $this->load->view("templates/right_search");?>
		<?php }?>

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