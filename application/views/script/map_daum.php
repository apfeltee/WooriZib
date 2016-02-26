var map;
var markers = [];
var markers_circle = [];
var subways = [];
var infowindow;
var clusterwindow;
var infoClicked = false; //인포창이 클릭되어 있는 지 여부를 판단하여 map click event발생시 창을 닫는 행위를 하지 않도록 한다.
var cmarkers;
var smarkers = []; //이도 마찬가지로 지하철 삭제를 위해서 필요하다.
var markerCluster;
var init=0;
var init_lat;
var init_lng;
var idle_disable=0; 
var spot;	//바로가기에서 사용함
var param = [];
var maxzoom;
var flightPath;
var timer;
var resize_timer; //resize를 처리하기 위한 타이머
var call_timer; //resize를 처리하기 위한 타이머
var calling = 0; /**지도에서 정렬순서를 변경하면 자동으로 show_infowindow가 호출이 되어서 데이터 갱신을 하지 않는 문제점이 있어서 수정하였다. **/

function initialize(lat, lng, level,  maxzoom, ml) {
	
	init_lat = lat;
	init_lng = lng;
	
	maxzoom = maxzoom;

	var mapOptions = {
		center: new daum.maps.LatLng(lat, lng),
		level: level
	};
	
	map = new daum.maps.Map(document.getElementById('map'), mapOptions);
	var mapTypeControl = new daum.maps.MapTypeControl();
	map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);
	var zoomControl = new daum.maps.ZoomControl();
	map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);

	daum.maps.event.addListener(map, 'idle', call_map);
	
	//다음 지도는 숫자가 작아질 수록 지도가 확대되는 것이므로 maxzoom보다 더 작아지지 않도록 해야 한다.
	daum.maps.event.addListener(map, 'zoom_changed', function() {      
		if( maxzoom > map.getLevel() ) {
			map.setLevel(maxzoom);
			$('.toast').stop().fadeIn(400).delay(3000).fadeOut(400);
		}
	});


	$( window ).resize(function() {
		mapsize()
		//idle_disable=1;
		clearTimeout(resize_timer);
		resize_timer = setTimeout(mapsize, 200);
	});
}

/***
 * 이 부분은 테마별로 클래스가 다르면 다른 사이즈 규칙이 있을 수 있으니 테마 변경시에 잘 고려해서 제작한다.
 *
 */
function mapsize(){

	var top_size = $(".pre-header").outerHeight() + $(".header").outerHeight()+$(".band-wrapper").outerHeight();
	var all_size = $(window).height();
	$("#map").height(all_size-top_size);

	//idle_disable=0;
	//call_map();
	//relayout(); /** 지도 타일 재설정 **/
}

/**
 * 지도 구성
 * 지도 정보를 가져올 때에는 다시 호출되지 않도록 드래그와 줌을 막아 놓는다.
 */
function call_map(){
	
	if(idle_disable==0){ 

		close_infos();	//이미 열려 있는 창이 있다면 닫는다.
		
		map.setDraggable(false); 
		map.setZoomable(false);

		$('#map').children(':first').css("cursor", "wait");


		clearTimeout(call_timer);
		call_timer = setTimeout(function(){
			remove_data();
			get_map_data();
			get_map_list(0);
		}, 300);
	}
}

function remove_data(){

	for (var i = 0; i < smarkers.length; i++) {
		smarkers[i].setMap(null);
	}

	if(smarkers.length>0)	smarkers = [];

	for (var i = 0; i < markers_circle.length; i++) {
		markers_circle[i].setMap(null);
	}	

	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	 }

	if(markers.length>0)	markers = [];
}

function get_map_data(){
	
	$.ajax({
		type: 'POST',
		url: '/main/get_json',
		cache: false,
		data: {'zoom':map.getLevel(),'swlat':map.getBounds().getSouthWest().getLat(), 'nelat':map.getBounds().getNorthEast().getLat(), 'swlng':map.getBounds().getSouthWest().getLng(), 'nelng':map.getBounds().getNorthEast().getLng()},
		dataType: 'json',
		success: function(jsonData){
			$.each(jsonData, function(key, val) {
				
				if(key=="subway"){
					
					/*** subway start ***/

					$.each(val, function(key1, val1) {

						if(map.getLevel()<=5){

							// 커스텀 오버레이를 생성합니다
							smarkers[key1] = new daum.maps.CustomOverlay({
								map: map,
								position: new daum.maps.LatLng(val1["lat"], val1["lng"]),
								content: "<div style='text-align:center;'><img src='/assets/common/img/train.png'><br/><div class='subway_map sub_"+val1["hosun_id"]+"'>"+val1["name"]+"</div></div>",
								yAnchor: 1 
							});

						} else {

							if(map.getLevel()<7){
								var imageSrc = "/assets/common/img/subway_18.png", // 마커이미지의 주소입니다    
								imageSize = new daum.maps.Size(17, 17), // 마커이미지의 크기입니다
								imageOption = {offset: new daum.maps.Point(8, 8)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.
							} else {
								var imageSrc = "/assets/common/img/subway_small.png", // 마커이미지의 주소입니다    
								imageSize = new daum.maps.Size(10, 10), // 마커이미지의 크기입니다
								imageOption = {offset: new daum.maps.Point(5, 5)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.
							}
							
							smarkers[key1] = new daum.maps.Marker({
								position: new daum.maps.LatLng(val1["lat"], val1["lng"]),
								image: new daum.maps.MarkerImage(imageSrc, imageSize, imageOption),
								map: map,
								title: "[" + val1["hosun"]+"]"+val1["name"]
							});
						}

						daum.maps.event.addListener(smarkers[key1], 'click', function () {
							//아래 두개의 명령이 실행이 되면 idle이 2번 발생하여 꼬인다. 그래서 이벤트를 삭제했다가 다시 추가한다.
							daum.maps.event.removeListener(map, 'idle', call_map);
								map.setLevel(20-maxzoom);
								map.panTo(new daum.maps.LatLng(val1["lat"], val1["lng"]));
							daum.maps.event.addListener(map, 'idle', call_map);
						});

					}); /***  subway end ***/


				} else if(key=="marker"){
					
					// marker start

					$.each(val, function(key1, val1) {

						// 마커의 위치 지정
						var loc = new daum.maps.LatLng(val1["lat"], val1["lng"]);
						
						// 원의 클래스 정의
						var c_class = "s";
						if(val1["count"]>=10) c_class = "m";
						else if(val1["count"]>=100) c_class = "l";

						// count가 1일 경우에는 금액을 표시해 주고 1 이상일 경우에는 클러스터를 보여준다.
						if(val1["count"]=="1"){

							var price = "";
								
							if(val1["type"]=="sell"){
								if(val1["sell_price"]!="0"){
									price = numberWithCommas(val1["sell_price"]) + price_unit;
								} else {
									price = numberWithCommas(val1["lease_price"]) + price_unit;
								}
							} else if(val1["type"]=="installation"){
								if(val1["sell_price"]!="0"){
									price = numberWithCommas(val1["sell_price"]) + price_unit;
								} else {
									price = numberWithCommas(val1["lease_price"]) + price_unit;
								}
							} else if(val1["type"]=="full_rent"){
								price = numberWithCommas(val1["full_rent_price"]) + price_unit;
							} else if(val1["type"]=="monthly_rent"){
								price = numberWithCommas(val1["monthly_rent_deposit"]) + price_unit + "/" + numberWithCommas(val1["monthly_rent_price"]) + price_unit;
							} else if(val1["type"]=="rent"){
								price = numberWithCommas(val1["monthly_rent_deposit"]) + price_unit +  "/" + numberWithCommas(val1["monthly_rent_price"]) + price_unit ;
							}

							var imageSrc = "/assets/common/img/trans_marker.png",
								imageSize = new daum.maps.Size(110, 23),
								imageOption = {offset: new daum.maps.Point(55, -2)};

							if(val1["icon_only"]=="1"){

								var icon_img = "";

								if(val1["type"]=="installation"){
									icon_img = "/assets/common/img/map_installation.png";
								}
								if(val1["type"]=="sell"){
									icon_img = "/assets/common/img/map_sell.png";
								}
								if(val1["type"]=="full_rent"){
									icon_img = "/assets/common/img/map_fullrent.png";
								}
								if(val1["type"]=="monthly_rent"){
									icon_img = "/assets/common/img/map_monthlyrent.png";
								}

								imageSrc = icon_img,
								imageSize = new daum.maps.Size(18, 17),
								imageOption = {offset: new daum.maps.Point(8, -2)};

								markers[key1] = new daum.maps.CustomOverlay({
									map: map,
									position: loc,
									content: "",
									yAnchor: -0.3,
									zIndex: 1
								});
							}
							else{

								markers[key1] = new daum.maps.CustomOverlay({
									map: map,
									position: loc,
									content: "<div class='marker marker-"+val1["id"]+"'><span class='"+val1["type"]+"'>"+price+"</span></div>",
									yAnchor: -0.3,
									zIndex: 1
								});							
							}

							markers_circle[key1] = new daum.maps.Marker({
								position: loc,
								image: new daum.maps.MarkerImage(imageSrc, imageSize, imageOption),
								title: val1["id"]+"",
								map: map,
								zIndex:2
							});

							daum.maps.event.addListener(markers_circle[key1], 'mouseover', function() {
								var d = this;
								if(val1["type"]=="installation"){
									$(".marker-"+d.getTitle()).find("span").css('background-color','#fff2de');
								}
								if(val1["type"]=="sell"){
									$(".marker-"+d.getTitle()).find("span").css('background-color','#fddfe1');
								}
								if(val1["type"]=="full_rent"){
									$(".marker-"+d.getTitle()).find("span").css('background-color','#e2ebfe');
								}
								if(val1["type"]=="monthly_rent"){
									$(".marker-"+d.getTitle()).find("span").css('background-color','#e6fdee');
								}
								timer = setTimeout(function () {
									get_property(d);
								}, 400);						
								
							});

							daum.maps.event.addListener(markers_circle[key1], 'mouseout', function() {
								$(".marker").find("span").css('background-color','#fff');

								clearTimeout(timer);
							});

							daum.maps.event.addListener(markers_circle[key1], 'click', function() {
								get_property(this);
							});
												

						} else {

							var is_yAnchor = 0;
							var is_xAnchor = 0;

							if(c_class=="s"){
								is_yAnchor = -0.4;
								is_xAnchor = 0.3;
							} else if(c_class=="m"){
								is_yAnchor = -0.7;
								is_xAnchor = 0.5;
							} else if(c_class=="l"){
								is_yAnchor = -1;
								is_xAnchor = 0.5;
							}

							markers[key1] = new daum.maps.CustomOverlay({
								clickable : false,
								map: map,
								position: loc,
								content: "<div style='text-align:center;' class='cluster_wrapper'><div class='cluster_"+c_class+"'>"+val1["count"]+"</div></div>",
								yAnchor: is_yAnchor,
								xAnchor: is_xAnchor,
								zIndex: 1
							});

							if(c_class=="s"){
								var imageSrc = "/assets/common/img/obSt.png",
								imageSize = new daum.maps.Size(45, 45),
								imageOption = {offset: new daum.maps.Point(20, 0)};
							} else if(c_class=="m"){
								var imageSrc = "/assets/common/img/obMt.png",
								imageSize = new daum.maps.Size(60, 60),
								imageOption = {offset: new daum.maps.Point(30, 0)};
							} else if(c_class=="l"){
								var imageSrc = "/assets/common/img/obLt.png",
								imageSize = new daum.maps.Size(75, 75),
								imageOption = {offset: new daum.maps.Point(37, 0)};						    
							}

							markers_circle[key1] = new daum.maps.Marker({
								position: loc,
								image: new daum.maps.MarkerImage(imageSrc, imageSize, imageOption),
								title: val1["id"]+"",
								map: map,
								yAnchor: 1,
								zIndex:0
							});

							daum.maps.event.addListener(markers_circle[key1], 'mouseover', function() {
								var d = this;
								/*** this.setZIndex(2);  굳이 이렇게 하지 말고 숫자 부분에 pointer-events : none 속성을 주면 된다. ***/
								timer = setTimeout(function () {
									get_cluster_list(d.getTitle(), d.getPosition().getLat(), d.getPosition().getLng());

								}, 400);
							});

							daum.maps.event.addListener(markers_circle[key1], 'mouseout', function() {
								/*** this.setZIndex(0); ***/
								clearTimeout(timer);
							});

							daum.maps.event.addListener(markers_circle[key1], 'click', function() {
								get_cluster_list(this.getTitle(), this.getPosition().getLat(), this.getPosition().getLng());
							});

							$(".cluster_wrapper").parent().css("pointer-events","none");
						}

					});

				} // marker end

			}); /** jsonData foreach end **/

			map.setDraggable(true);    
			map.setZoomable(true);
			idle_disable = 0;		
			codeLatLng();

		}
	});
}

/**
 * 매물 하나의 정보를 가져오는 기능
 * 지도가 움직일 수 있기 때문에 처음에는 idle_disable을 1로 세팅한 후 움직임이 끝나면 1초 후 0으로 세팅한다.
 */
function get_property(a){

	$.ajax(
	{
		type	: "GET",
		url		: "/main/get_property/"+a.getTitle(),
		cache: false,
		dataType: 'html',
		success: function(data) {
			close_infos();
			
			idle_disable =1;
			
			var width = "350";
			if($(".map_wrapper").hasClass("maplist_1")){width="352";}
			if($(".map_wrapper").hasClass("maplist_2")){width="382";}
			if($(".map_wrapper").hasClass("maplist_3")){width="382";}
			if($(".map_wrapper").hasClass("maplist_4")){width="602";}


			if(infowindow!=null) infowindow.close();
			infowindow = new daum.maps.InfoWindow({
				map: map,
				position : a.getPosition(),
				content : "<div style='width:"+width+"px;'>"+data+"</div>",
				removable : true,
				zIndex:5
			});

			$('.relist').on({
				mouseenter: function() {

				},
				mouseleave: function() {
					setTimeout(function() {
						close_infos();
					}, 100);
				}
			});

			link_init($('.view_product'));
			
			setTimeout(function() {
				  idle_disable = 0;
			}, 1000);
			
			login_leanModal();
		}
	});
}

/**
 * 매물 클러스터의 정보를 가져오는 기능
 * 지도가 움직일 수 있기 때문에 처음에는 idle_disable을 1로 세팅한 후 움직임이 끝나면 1초 후 0으로 세팅한다.
 */
function get_cluster_list(title, x,y){
	$.ajax(
	{
		type	: "POST",
		url		: "/main/get_all_server_cluster_list/"+title+"/"+map.getLevel()+"/"+x+"/"+y+"/"+map.getBounds().getSouthWest().getLat()+"/"+map.getBounds().getNorthEast().getLat()+"/"+map.getBounds().getSouthWest().getLng()+"/"+map.getBounds().getNorthEast().getLng()+"/"+Math.round(new Date().getTime()),
		cache: false,
		data: $("#search_form").serialize(),
		dataType: 'html',
		success: function(data) {
			
				close_infos();
				if(infowindow!=null) infowindow.close();

				

				if($( document ).width()<800){
					$("#clusterlist_title").html("<a class='btn btn-xs btn-warning' style='margin-bottom:3px;' href='#' onclick=\"expand('"+x+"','"+y+"')\"><i class='fa fa-arrows-alt'></i>&nbsp;확대</a>");
					$("#clusterlist_inner").html(data);
					$("#clusterlist").fadeIn(0500)
				} else {
					idle_disable = 1;
				
					var iwContent = "<div class=\"map_list_wrapper\"><a class='btn btn-xs btn-warning' style='margin-bottom:3px;' href='#' onclick=\"expand('"+x+"','"+y+"')\"><i class='fa fa-arrows-alt'></i>&nbsp;확대</a><div id='ib' class=\"map_list\">"+data+"</div></div>", // 인포윈도우에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
						iwPosition = new daum.maps.LatLng(x, y), //인포윈도우 표시 위치입니다
						iwRemoveable = true; // removeable 속성을 ture 로 설정하면 인포윈도우를 닫을 수 있는 x버튼이 표시됩니다

					// 인포윈도우를 생성하고 지도에 표시합니다
					clusterwindow = new daum.maps.InfoWindow({
						map: map, // 인포윈도우가 표시될 지도
						position : iwPosition,
						content : iwContent,
						removable : iwRemoveable,
						zIndex:3
					});

					link_init($('.view_product'));

					setTimeout(function() {
						  idle_disable = 0;
					}, 1000);

				}
				login_leanModal();
		}
	});
}

function close_infos(){

	//그룹 info close
	if(clusterwindow!=null){
		clusterwindow.close();
	}
	if(infowindow!=null){
		infowindow.close();
	}
	
}

function get_map_list(page){

	$.ajax(
	{
		type	: "POST",
		url		: "/main/get_all_server_list/"+page+"/"+map.getBounds().getSouthWest().getLat()+"/"+map.getBounds().getNorthEast().getLat()+"/"+map.getBounds().getSouthWest().getLng()+"/"+map.getBounds().getNorthEast().getLng()+"/"+Math.round(new Date().getTime()),
		cache: false,
		data: $("#search_form").serialize(),
		dataType: 'json',
		success: function(data) {
				$.each(data, function(key, val) {

					if(key=="result") {
						str = val;
					}
					if(key=="total"){
						total = val;
						$(".result_label").html("<i class=\"fa fa-search\"></i> <?php echo lang("search.result");?> " + val);
					}

					if(key=="paging"){
						if(total<=val){
							$("#pagination_more").hide();
						} else {
							$("#pagination_more").show();
						}
						
						next_page = val;
						$("#next_page").val(val);
					}

				}); //each

				if(str==""){
					$("#pagination_more").hide();
					str = "<div style='text-align:center;padding:10px;'><i class=\"fa fa-hand-o-down\"></i> 검색 결과가 없습니다. 연락주세요.</div>";
				}

				if(next_page<21){
					$("#map_search_list").html(str);			
					$("#map_list").animate({
						scrollTop: 0
					}, 600);
				} else {
					$("#map_search_list").append(str);
				}
				$("#loading").hide();
				
				$(".relist").mouseover(function(){
					show_infowindow($(this));
				});

				map.setDraggable(true);    
				map.setZoomable(true);
				link_init($('.view_product'));
				
				setTimeout(function() {
					  idle_disable = 0;
				}, 1000);
			login_leanModal();
		}
	});
}

function more(){
	get_map_list($("#next_page").val());
}

/**
 * 구글지도는 infowindow가 하단에 생기기 때문에 지도가 이동될 확률이 적다.
 */
function show_infowindow(obj){
	if(calling != 1){
		close_infos();

		$(".pocp_button").removeClass("btn_active");
		$(".pocp_button").not($(".pocp_button_reset")).html("<i class=\"fa fa-chevron-right\"></i> <?php echo lang("site.search");?>");
		$("#search_section").stop().animate({left: '-250px'}, 400, 'easeInOutCirc');

		var width = "350";
		if($(".map_wrapper").hasClass("maplist_1")){width="352";}
		if($(".map_wrapper").hasClass("maplist_2")){width="382";}
		if($(".map_wrapper").hasClass("maplist_3")){width="382";}
		if($(".map_wrapper").hasClass("maplist_4")){width="602";}

		if(infowindow!=null) infowindow.close();
		idle_disable = 1;
		infowindow = new daum.maps.InfoWindow({
			map: map, // 인포윈도우가 표시될 지도
			position : new daum.maps.LatLng(obj.attr("data-lat"), obj.attr("data-lng")),
			content : "<div style='width:"+width+"px;'>"+"<div class=\"relist\" style='background-color:transparent;'>"+obj.html()+"</div>"+"</div>",
			removable : true,
			zIndex:5
		});

		setTimeout(function() {
			idle_disable = 0;
		}, 1000);
	}
}

/**
 * 기존에 zoomin이었는데 다른데 쓰고 있는 함수라 expand로 변경함
 * 다음 지도는 줌을 키우는 것이 -1 이다.
 */
function expand(lat, lng){
	if(clusterwindow!=null){
		clusterwindow.close();
	}
	map.panTo(new  daum.maps.LatLng(lat, lng));
	map.setLevel(map.getLevel()-1);
	remove_data();
}

/**
 * 다음 지도는 구글지도와 줌이 반대이기 때문에 20에서 빼준다.
 * 
 */
function set_zoom(z){
	//z=20-z;
	if(z>0){
		map.setLevel(parseInt(z));
	}
}

/**
 * 다음 지도는 구글 지도랑 꺼꾸로
 */ 
function zoomin(){
	map.setLevel(map.getLevel()-1);
}

/**
 * 다음 지도는 구글 지도랑 꺼꾸로
 */ 
function zoomout(){
	map.setLevel(map.getLevel()+1);
}

function move_map(lat,lng, maxzoom){
		map.panTo(new  daum.maps.LatLng(lat, lng));
		map.setLevel(maxzoom + 1);
}

function refresh_map(){
	var refresh_lat = "0";
	var refresh_lng = "0";
	var refresh_level = 0;
}

function codeLatLng() {
	var geocoder = new daum.maps.services.Geocoder();
	geocoder.coord2addr(map.getCenter(), function(status,result){
		if (status === daum.maps.services.Status.OK) {
			$("#center_address").html(result[0].fullName);
		}
	}); 
}
