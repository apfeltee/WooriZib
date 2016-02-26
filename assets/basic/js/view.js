var map_detail;
var radius_val = 50;

function set_radius(t){
	radius_val = t;
}
/**
 * 매물 위치 표시 (google)
 */
function position_google(lat, lng, level, maxzoom) {
	var mapOptions = {
		zoom: level,
		maxZoom: maxzoom,
		minZoom: 10,
		center: new google.maps.LatLng(lat, lng),
		mapTypeControlOptions: {
			mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID]
		},
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl : false,
		panControl : false,
		zoomControl : true,    
		zoomControlOptions: {        
			style: google.maps.ZoomControlStyle.LARGE,        
			position: google.maps.ControlPosition.LEFT_TOP
		}
	};
	var position = new google.maps.Map(document.getElementById('point_map'), mapOptions);
	var issueOptions = {
				strokeColor: '#E5003B',
				strokeOpacity: 0.5,
				strokeWeight: 3,
				fillColor: "#E5003B",
				fillOpacity: 0.25,
				map: position,
				center:  new google.maps.LatLng(lat, lng),
				radius: radius_val
	};
	
	var issueCircle = new google.maps.Circle(issueOptions);

	var issues = new MarkerWithLabel({
		position: new google.maps.LatLng(lat, lng),
		map: position,
		icon: " ",
		labelAnchor: new google.maps.Point(35, -10)
	 });
}

/**
 * 매물 위치 표시 (daum)
 */
function position_daum(lat, lng, level, maxzoom) {
	
	
	var container = document.getElementById('container'), // 지도와 로드뷰를 감싸고 있는 div 입니다
		mapWrapper = document.getElementById('mapWrapper'), // 지도를 감싸고 있는 div 입니다
		btnRoadview = document.getElementById('btnRoadview'), // 지도 위의 로드뷰 버튼, 클릭하면 지도는 감춰지고 로드뷰가 보입니다 
		btnMap = document.getElementById('btnMap'), // 로드뷰 위의 지도 버튼, 클릭하면 로드뷰는 감춰지고 지도가 보입니다 
		rvContainer = document.getElementById('roadview'), // 로드뷰를 표시할 div 입니다
		mapContainer = document.getElementById('point_map'); // 지도를 표시할 div 입니다

	// 지도와 로드뷰 위에 마커로 표시할 특정 장소의 좌표입니다 
	var placePosition = new daum.maps.LatLng(lat, lng);

	// 지도 옵션입니다 
	var mapOption = {
		center: placePosition, // 지도의 중심좌표 
		level: level // 지도의 확대 레벨
	};

	// 지도를 표시할 div와 지도 옵션으로 지도를 생성합니다
	map_detail = new daum.maps.Map(mapContainer, mapOption);

	// 일반 지도와 스카이뷰로 지도 타입을 전환할 수 있는 지도타입 컨트롤을 생성합니다
	var mapTypeControl = new daum.maps.MapTypeControl();

	// 지도에 컨트롤을 추가해야 지도위에 표시됩니다
	// daum.maps.ControlPosition은 컨트롤이 표시될 위치를 정의하는데 TOPRIGHT는 오른쪽 위를 의미합니다
	map_detail.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);

	// 지도 확대 축소를 제어할 수 있는  줌 컨트롤을 생성합니다
	var zoomControl = new daum.maps.ZoomControl();
	map_detail.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);

	// 로드뷰 객체를 생성합니다
	var roadviewClient = new daum.maps.RoadviewClient();
	var	roadview = new daum.maps.Roadview(rvContainer);

	// 로드뷰의 위치를 특정 장소를 포함하는 파노라마 ID로 설정합니다
	// 로드뷰의 파노라마 ID는 Wizard를 사용하면 쉽게 얻을수 있습니다 
	roadviewClient.getNearestPanoId(placePosition, 50, function(panoId) {
		roadview.setPanoId(panoId, placePosition);
	});

	// 특정 장소가 잘보이도록 로드뷰의 적절한 시점(ViewPoint)을 설정합니다 
	// Wizard를 사용하면 적절한 로드뷰 시점(ViewPoint)값을 쉽게 확인할 수 있습니다
	roadview.setViewpoint({
		pan: 0,
		tilt: 0,
		zoom: 0
	});
	// 로드뷰 초기화가 완료되면
	daum.maps.event.addListener(roadview, 'init', function() {

		// 로드뷰에 특정 장소를 표시할 마커를 생성하고 로드뷰 위에 표시합니다 
		var rvMarker = new daum.maps.Marker({
			position: placePosition,
			map: roadview
		});
	});

	if(radius_val>0){

		// 지도에 표시할 원을 생성합니다
		var circle = new daum.maps.Circle({
			center : new daum.maps.LatLng(lat, lng),  // 원의 중심좌표 입니다 
			radius: radius_val, // 미터 단위의 원의 반지름입니다 
			strokeWeight: 5, // 선의 두께입니다 
			strokeColor: '#75B8FA', // 선의 색깔입니다
			strokeOpacity: 1, // 선의 불투명도 입니다 1에서 0 사이의 값이며 0에 가까울수록 투명합니다
			strokeStyle: 'dashed', // 선의 스타일 입니다
			fillColor: '#CFE7FF', // 채우기 색깔입니다
			fillOpacity: 0.7  // 채우기 불투명도 입니다   
		}); 


		// 지도에 원을 표시합니다 
		circle.setMap(map_detail);
	}

	// 마우스휠로 자동 확대 축소 안되게 하기
	map_detail.setZoomable(false);
}

// 다음지도와 로드뷰를 감싸고 있는 div의 class를 변경하여 지도를 숨기거나 보이게 하는 함수입니다 
function toggleMap(active) {
    if (active) {
        // 지도가 보이도록 지도와 로드뷰를 감싸고 있는 div의 class를 변경합니다
        container.className = "view_map";
    } else {
		// 지도가 숨겨지도록 지도와 로드뷰를 감싸고 있는 div의 class를 변경합니다
		container.className = "view_roadview";
    }
}


function refresh(){
   	map_detail.relayout();
}

/**
 * 관심으로 등록하기
 */
function hope(id){
	$.get("/product/hope_action/"+id+"/"+Math.round(new Date().getTime()),function(data){
		if($( document ).width()<600){
			alert("등록되었습니다. 회원로그인 시 영구저장됩니다.");
		} else {
			msg($("#err"), "success" ,"회원로그인 시 영구저장됩니다.");
		}
	});
}

function hope_installation(id){
	$.get("/installation/hope_action/"+id+"/"+Math.round(new Date().getTime()),function(data){
		if($( document ).width()<600){
			alert("등록되었습니다. 회원로그인 시 영구저장됩니다.");
		} else {
			msg($("#err"), "success" ,"회원로그인 시 영구저장됩니다.");
		}
	});
}

function local(code,lat,lng){
	$.getJSON("/product/local/"+lat+"/"+lng+"/"+code+"/"+Math.round(new Date().getTime()),function(data){
		if(data){
			$.each(data, function(key, val) {
				if(key=="channel"){
					$.each(val, function(key1, val1) {
							if(key1=="item"){
								var str = "<table class='border-table'><tr><th>장소명</th><th>거리</th><th class='hidden-xs'>주소</th><th>전화번호</th></tr>";
								if(val1.length<1){
									str += "<tr><td colspan='4'>결과가 없습니다.</td></tr>";
								} else {

									$.each(val1, function(key2, val2) {
										phone = "";
										if(val2["phone"]!=""){
											phone = "<a href='tel:"+val2["phone"]+"'><i class='fa fa-phone-square mhidden'></i> " + val2["phone"] + "</a>";
										}
										str += "<tr><td><b>"+val2["title"]+"</b></td><td> "+val2["distance"]+"m</td><td class='hidden-xs'><i class='fa fa-map-marker'></i>"+val2["address"]+"</td><td>" + phone + "</td></tr>";
									});
								}

								str +="</table>";
								$("#"+code).html(str);
							}
					});
				}
			});
		}
	});
}

/**
 * 슬라이드 초기화
 * p값이 0이면 gallery 썸네일이 하단, 1이면 썸네일이 우측에 위치하도록 한다.
 */
function view_init(p){

	var gallery_height = 720;
	var gallery_thumbnail = "horizontal";
	if(p=="1") {
		gallery_height = 540;
		gallery_thumbnail = "vertical";
	}

	$('#gallery-1').royalSlider({
		fullscreen: {  enabled: false,  nativeFS: false},
		controlNavigation: 'thumbnails',
		transitionType : 'fade',
    	autoPlay: {
    		enabled: true,
    		pauseOnHover: true,
			delay: 4000
    	},
		autoScaleSlider: true, 
		autoScaleSliderWidth: 960,
		autoScaleSliderHeight: gallery_height,
		loop: true,
		imageScaleMode: 'fit-if-smaller',
		navigateByClick: true,
		numImagesToPreload:2,
		arrowsNav:true,
		arrowsNavAutoHide: true,
		arrowsNavHideOnTouch: true,
		keyboardNavEnabled: true,
		fadeinLoadedSlide: true,
		globalCaption: true,
		globalCaptionInside: false,
		thumbs: {
		      orientation: gallery_thumbnail,
		      paddingBottom: 4,
		      appendSpan: true
		},
		sliderTouch: true
	  });

	$('#concern_form').ajaxForm({
		beforeSubmit : function(a,f,o){
			if($("#mobile").val()==""){
				msg($("#err"), "danger" ,"전화번호를 입력해주세요.");
				return false;
			} else {
				return true;
			}
		},
		success:function(data){
			if(data == "1"){
				msg($("#err"), "success" ,"접수되었습니다. 연락드리겠습니다.");
			} else {
				msg($("#err"), "danger" ,"잘못된 번호입니다.");
			}
		}
	}); 



	$('#htab').easyResponsiveTabs({
		type: 'default', //Types: default, vertical, accordion
		width: 'auto', //auto or any width like 600px
		fit: true, // 100% fit in a container
		tabidentify: 'hor_1', // The tab groups identifier
		activate: function(event) { // Callback function if tab is switched
			local($(this).find("a").attr("data-key"),$(this).find("a").attr("data-lat"),$(this).find("a").attr("data-lng"));
		}
	});


	$("#view_phone").click(function(){
		
		$.get("/product/add_call_view/"+$(this).attr("data-id")+"/"+$(this).attr("data-member-id")+"/"+Math.round(new Date().getTime()),function(data){
			$("#target_url").attr("src","/product/view_log/"+$("#view_phone").attr("data-id"));
		});
		
		$(".view_phone_area").removeClass("hidden");
		$("#view_phone").addClass("hidden");
		
	});

	$("#request_call").click(function(){
		$(".member").fadeIn(0500)
	});

	$(".memberClose").click(function(){
		$(".member").fadeOut("normal");  
	});

	$(".rsNavItem").mouseover(function() {
		$('.royalSlider').royalSlider('goTo', $(this).index() );
	});
}