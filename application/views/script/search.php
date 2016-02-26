/*************************************************************************************************
 * 검색 스크립트
 * category_sub_change 	: 매물종류에서 서브종류가 변경되었을 때 동작
 * search_reset 		: 검색 초기화(클릭시 선택된 검색 조건이 모두 초기화된다.)
 * end_price_trim		: 클릭으로 가격검색을 할 때 최대값은 선택된 최소값보다 큰 선택사항만 남도록 한다.
 * send_form 			: 검색을 실행한다. (만약 중복으로 요청이 왔을 때 처리절차도 포함되어 있다.)
 * theme_display		: 테마검색 토글기능
 * set_theme 			: 테마검색시 테마선택되면 검색 실행하도록 한다. (근데 쓰이는 곳이?)
 * subway_line_display	: 호선별 검색 토글
 * set_type				: 매물유형 선택시 실행(근데 쓰이는 곳이?)
 * search_tab_reset		: 검색 방식을 변경할 대 초기화하는 함수
 * get_sido				: 시도가져오기
 * get_gugun 			: 구군가져오기
 * get_dong 			: 동가져오기
 * get_address 			: 주소아이디로 가져오기
 * left_loading			: 좌측 로딩 보여주기
 * get_danzi_name		: 단지이름 가져오기
 * get_subway_local		: 지하철 지역 가져오기
 * get_hosun 			: 지하철 호선 가져오기
 * get_station 			: 지하철 역 가져오기
 * get_subway 			: 지하철 아이디로 가져오기
 * init_price 			: 가격 슬라이드 초기화하기
 * init_search 			: 검색 초기화하기(처음에)
 * contains 			: 배열에 값이 있는지 체크하는 함수
 * numberWithCommas		: 숫자에 콤마 붙여주기
 * get_gugun_modal		: 구군가져오기(modal)
 * get_dong_modal		: 동가져오기(modal)
 * area_search			: 해당 지역으로 좌표이동(modal)
 * get_hosun_modal		: 지하철 호선 가져오기(modal)
 * get_station_modal	: 지하철 역 가져오기(modal)
 * subway_search		: 해당 지하철역으로 좌표이동(modal)
 * region_select		: 사전지역으로 검색하기
 *************************************************************************************************/

var sell_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("sell_unit");?></font>";
var price_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("price_unit");?></font>";
var address_type="front"; /** 공개매물 등록된 주소 가져오도록 **/

$(document).ready(function() {
	
	$('button').tooltip(); /* 버튼 툴팁 보여주기 */

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */	

	/** 매매유형을 클릭할 때마다 바꿔주는 코드 **/
	$(".btn-group > .btn").click(function(){
		$(".btn-group > .btn").removeClass("active");
		$(this).addClass("active");
	});

	/** 주소 검색 초기화 **/
	get_sido("search_form",address_type);

	/** 지하철 검색 초기화 **/
	get_subway_local("search_form");


	/** 검색 탭 처리 **/
	$("ul.nav-search li").click(function(e){
		search_tab_reset();
		if (!$(this).hasClass("active")) {
			var tabNum = $(this).index();
			var nthChild = tabNum+1;
			$("ul.nav-search li.active").removeClass("active");
			$(this).addClass("active");
			$("ul#tab li.active").removeClass("active");
			$("ul#tab li:nth-child("+nthChild+")").addClass("active");
		};
	});
	
	$("#search").geocomplete({componentRestrictions: {country: 'kr'}})
		.bind("geocode:result", function(event, result){
			$("#search_type").val("google");
			$("#search_value").val($("#search").val());
			$("#keyword_front").val("");
			$("#lat").val(result.geometry.location.lat());
			$("#lng").val(result.geometry.location.lng());
			send_form();

		})
		.bind("geocode:error", function(event, status){
			if($.isNumeric($("#search").val())){
				location.href="/product/view/"+$("#search").val();
				return false;
			}
			var keyword_front = $("#search").val();
			if(keyword_front==""){
				keyword_front = $("#search_value").val();
			}
			if($("#next_page").length > 0) $("#next_page").val("0");
			$("#search_type").val("google");
			$("#search").val(keyword_front);
			$("#search_value").val(keyword_front);
			$("#keyword_front").val(keyword_front);
			send_form();
		});

	$("#geo_btn").click(function(){
		if($.isNumeric($("#search").val())){
			location.href="/product/view/"+$("#search").val();
			return false;
		}
		else{
			send_form();
		}
		$("#search_form").submit();
	});

	if($("#danzi_name").length > 0){
		if($("#danzi_name").val()!=""){
			get_danzi_name($("#danzi_name").val());
			$("#danzi").val($("#danzi_temp").val());
		}
	}

	$("#go_keyword").click(function(){
		if($.isNumeric($("#search").val())){
			location.href="/product/view/"+$("#search").val();
			return false;
		}
		var keyword_front = $("#search").val();
		if(keyword_front==""){
			keyword_front = $("#search_value").val();
		}
		if($("#next_page").length > 0) $("#next_page").val("0");
		$("#search_type").val("google");
		$("#search").val(keyword_front);
		$("#search_value").val(keyword_front);
		$("#keyword_front").val(keyword_front);
		send_form();
	});

	/* 클릭으로 가격검색 하는 기능 추가 */
	var priceLabelObj;
	
	$(".price-label").focus(function (event) {
		end_price_trim();
		priceLabelObj=$(this);
		$(".price-range").addClass("hide");
		$("#"+$(this).data("dropdownId")).removeClass("hide");
	});

	/* 가격선택하면 창이 닫혀버리는 것을 막는다. */
	$(".band-wrapper .dropdown-menu").click(function (event) {
		event.stopPropagation();
	});

	$(".right_search_wrap .dropdown-menu").click(function (event) {
		event.stopPropagation();
	});

	$(".button-section .dropdown-menu").click(function (event) {
		event.stopPropagation();
	});

	//버튼 클릭하면 버튼과 함께 있는 첫 번째 값에 포커스가 가도록 한다.
	var btn;
	$(".btn-price").click(function(event){
		btn = $(this);
		setTimeout(function(){ btn.parent().find(".price-label").first().focus();},0);
	});

	$(".price-range li").click(function(event){
		priceLabelObj.val($(this).attr("data-value")); //focus되어 있는 곳에 선택된 값을 입력해 준다.
		var curElmIndex=$( ".price-label" ).index( priceLabelObj );
    	end_price_trim();
    	if(curElmIndex % 2 == 0){
    		$(".price-range li").removeClass("hide");
			$( ".price-label" ).eq(curElmIndex+1).focus();
		}else{
			btn.dropdown('toggle'); //최근 눌렸던 버튼을 토글한다.
			if($(this).attr("notsubmit")==undefined){
				send_form();
			}
		}
	});

	$('.price-label').keypress(function(event){
		if(event.which==13){
			btn.dropdown('toggle'); //최근 눌렸던 버튼을 토글한다.
			//$('.price_select').removeClass('open');
			if($(this).attr("notsubmit")==undefined){
				send_form();
			}
		}		
	});	

});

/**
 * 매물 종류에서 서브 종류가 변경되었을 때 동작
 */
var category_sub_change = function(){
	$("input[name='category[]']").on('ifChanged', function(){
		if($(this).prop("checked")){
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
		if($(this).prop("checked")){
			$(".category_sub_"+$(this).val()).show();				
		}
		else{
			$(".category_sub_"+$(this).val()).attr("checked",false);
			$(".category_sub_"+$(this).val()).iCheck('uncheck');
			$(".category_sub_"+$(this).val()).iCheck('update');
		}
	});
}

/**
 * 검색을 하다가 초기화를 누르면 모든 검색 내용이 초기화된다.
 */
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
	send_form();
	$("#reset").val(0);

	init_position();	
}


/**
 * 새로운 가격검색(클릭으로 검색)에서 최소값을 선택하였을 때 최대값은 최소값보다 큰 값들을 선택하기 위한 함수
 */
function end_price_trim(){

	if($("input[name='sell_start']").val()!=""){
		$("#sell-price-max li").each(function(){
			if(eval($("input[name='sell_start']").val()) >= eval($(this).attr("data-value"))){
				$(this).addClass("hide");
			}
		});
	}

	if($("input[name='full_start']").val()!=""){
		$("#fullrent-price-max li").each(function(){
			if(eval($("input[name='full_start']").val()) >= eval($(this).attr("data-value"))){
				$(this).addClass("hide");
			}
		});
	}	

	if($("input[name='month_deposit_start']").val()!=""){
		$("#month_deposit-price-max li").each(function(){
			if(eval($("input[name='month_deposit_start']").val()) >= eval($(this).attr("data-value"))){
				$(this).addClass("hide");
			}
		});
	}		

	if($("input[name='month_start']").val()!=""){
		$("#month-price-max li").each(function(){
			if(eval($("input[name='month_start']").val()) >= eval($(this).attr("data-value"))){
				$(this).hide();
			}
		});
	}		
}

var send_form_flag=0;
function send_form(){
	if(send_form_flag==0){
		send_form_flag=1;
		setTimeout(function () {
			$("#next_page").val("0");	/*** 검색 조건이 바뀌면 다시 페이지가 0이 되면서 시작되어야 한다. more가 되면 submit이 아닌 success 된 이후에 ajax만 동작하면 된다. ***/
			$("#search_form").trigger("submit");
			send_form_flag=0;
		}, 100);
	}
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

function set_theme(id){
	$("#theme").val(id);
	send_form();
}

/**
 * 지하철 호선별 검색영억 토글
 */
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

/**
 * 검색시 매물 유형을 선택하는 기능이다.
 * 하나를 선택하면 모두 동일한 값으로 세팅한다.
 */
function set_type(val){
	$(".type").val(val);
	$("#type").val(val);
}

/**
 * 검색 방식을 변경할 때 값을 초기화하는 함수
 */
function search_tab_reset(){
	$("#type1, #type2, #type3").val($("#type").val());
	$("#sido, #gugun, #subway_local, #hosun, #station, #search").val("");
	$("#gugun").html('<option value="">-</option>');
	$("#dong").html('<option value="">-</option>');
	$("#hosun").html('<option value="">-</option>');
	$("#station").html('<option value="">-</option>');
}

function get_sido(form,type){
	$.getJSON("/address/get_sido/"+type+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>시도 선택</option>";
		var selected = false;
		$.each(data, function(key, val) {
			
			var sido_label;
			if(typeof val["sido_label"] !== 'undefined') {
				sido_label = val["sido_label"];
			} else {
				sido_label = val["sido"];
			}
	

			if( $("#sido_val").val()==val["sido"] ){
				str = str + "<option value='"+val["sido"]+"' selected>"+sido_label+"</option>";
				selected = true;
			} else {
				str = str + "<option value='"+val["sido"]+"'>"+sido_label+"</option>";
			}
		});

		$("#"+form).find("#sido").html(str);
		/** 값이 있으면 change event와는 별개로 get_gugun을 호출해 준다. **/
		if(selected) get_gugun(form, type, $("#sido").val());
		
		$("#"+form).find("#sido").change(function(){
			get_gugun(form, type, this.value);
		});

	});
}

function get_gugun(form,type,sido){
	$.getJSON("/address/get_gugun/"+type+"/"+encodeURI(sido)+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>구군 선택</option>";
		var selected = false;
		$.each(data, function(key, val) {
			
			var gugun_label;
			if(typeof val["gugun_label"] !== 'undefined') {
				gugun_label = val["gugun_label"];
			} else {
				gugun_label = val["gugun"];
			}

			if( $("#gugun_val").val()==val["parent_id"] ){
				str = str + "<option value='"+val["parent_id"]+"' selected>"+gugun_label+"</option>";
				selected = true;
			} else {
				str = str + "<option value='"+val["parent_id"]+"'>"+gugun_label+"</option>";
			}
		});

		$("#"+form).find("#gugun").html(str);
		if(selected) get_dong(form, type, $("#gugun").val());	/** 초기값만 세팅하는 것이기 때문에 이것만 호출해 주면 된다. **/

		$("#"+form).find("#gugun").change(function(){
			$.getJSON("/address/get_parent/"+this.value+"/"+Math.round(new Date().getTime()),function(data){
				$("#"+form).find("#search_type").val("parent_address");
				$("#"+form).find("#search_value").val(data["id"]);
				$("#"+form).find("#lat").val(data["lat"]);
				$("#"+form).find("#lng").val(data["lng"]);
				if($("#gugun_submit").length > 0){
					if($("#gugun_submit").val()){
						if($("#next_page").length > 0) $("#next_page").val("0");
						$("#"+form).trigger("submit");
					}
				}
			});

			get_dong(form, type, this.value);
		});

		//refresh_lang();
	});
}

function get_dong(form,type,parent_id){
	$.getJSON("/address/get_dong/"+type+"/"+parent_id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>읍면동 선택</option>";
		var selected = false;

		$.each(data, function(key, val) {

			var dong_label;
			if(typeof val["dong_label"] !== 'undefined') {
				dong_label = val["dong_label"];
			} else {
				dong_label = val["dong"];
			}

			if( $("#dong_val").val()==val["id"] ){
				str = str + "<option value='"+val["id"]+"' selected>"+dong_label+"</option>";
				selected = true;
			} else {
				str = str + "<option value='"+val["id"]+"'>"+dong_label+"</option>";
			}
		});

		$("#"+form).find("#dong").html(str);

		if(selected) get_address(form, $("#dong_val").val());	/** 초기값만 세팅하는 것이기 때문에 이것만 호출해 주면 된다. **/

		$("#"+form).find("#dong").change(function(){
			$("#next_page").val("0");
			get_address(form,this.value);
		});

		//refresh_lang();
	});
}

function get_address(form,id){
	$("#danzi").val("");
	if(id){
		$.getJSON("/address/get/"+id+"/"+Math.round(new Date().getTime()),function(data){
			$("#"+form).find("#lat").val(data["lat"]);
			$("#"+form).find("#lng").val(data["lng"]);
			$("#"+form).find("#search_type").val("address");
			$("#"+form).find("#search_value").val(id);
			send_form();
		});	
	}
}

function left_loading(){
	calling = 1;
	loading_delay(true);
	setTimeout(function () {
		loading_delay(false);
		calling = 0;
	}, 400);

	init_price();
	$("#next_page").val("0");
	send_form();
}

function get_danzi_name(value){
	if(value==""){
		$("#danzi").remove();
		left_loading();
		return false;
	}
	var string = value.split("|");
	$.ajax({
		url: "/danzi/get_danzi_area",
		type: "POST",
		async: false,
		data: {
			address_id: string[0],
			danzi_name: string[1]
		},
		dataType: "json",
		success: function(data) {
			var	str = "<option value='' selected>면적선택</option>";
			if(data!=""){
				$.each(data, function(key, val) {
					str = str + "<option value='"+val["id"]+"'>"+val["area"]+"㎡</option>";
				});
			}
			str = str + "";

			if($("#danzi").length > 0){
				$("#danzi").html(str);
			}
			else{
				$("#danzi_name").after("<select id='danzi' name='danzi' class='form-control search_item margin-top-10'>"+str+"</select>");
			}			

			$(".search_item").change(function(){
				left_loading();
			});
		}
	});
}

function get_subway_local(form){
	$.getJSON("/subway/get_local/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>지역 선택</option>";
		var selected = false;

		$.each(data, function(key, val) {
			if( $("#subway_local_val").val()==val["local"] ){
				str = str + "<option value='"+val["local"]+"' selected>"+val["local_text"]+"</option>";
				selected = true;
			} else {
				str = str + "<option value='"+val["local"]+"'>"+val["local_text"]+"</option>";
			}
		});

		$("#"+form).find("#subway_local").html(str);

		if(selected) get_hosun(form, $("#subway_local_val").val());	/** 초기값만 세팅하는 것이기 때문에 이것만 호출해 주면 된다. **/

		$("#"+form).find("#subway_local").change(function(){
			get_hosun(form, this.value);
		});


	});
}

function get_hosun(form,local){
	$.getJSON("/subway/get_hosun/"+local+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>호선 선택</option>";
		var selected = false;

		$.each(data, function(key, val) {
			var hosun_label;
			if(typeof val["hosun_label"] !== 'undefined') {
				hosun_label = val["hosun_label"];
			} else {
				hosun_label = val["hosun"];
			}

			if( $("#hosun_val").val()==val["hosun_id"] ){
				str = str + "<option value='"+val["hosun_id"]+"' selected>"+hosun_label+"</option>";
				selected = true;
			} else {
				str = str + "<option value='"+val["hosun_id"]+"'>"+hosun_label+"</option>";
			}
		});

		$("#"+form).find("#hosun").html(str);

		if(selected) get_station(form, local, $("#hosun_val").val());	/** 초기값만 세팅하는 것이기 때문에 이것만 호출해 주면 된다. **/

		$("#"+form).find("#hosun").change(function(){
			get_station(form,local,this.value);
		});

		//refresh_lang();
	});
}

function get_station(form,local,hosun){
	$.getJSON("/subway/get_station/"+hosun+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>역 선택</option>";
		var selected = false;

		$.each(data, function(key, val) {

			var name_label;
			if(typeof val["name_label"] !== 'undefined') {
				name_label = val["name_label"];
			} else {
				name_label = val["name"];
			}

			if( $("#station_val").val()==val["id"] ){
				str = str + "<option value='"+val["id"]+"' selected>"+name_label+"</option>";
				selected = true;

			} else {
				str = str + "<option value='"+val["id"]+"'>"+name_label+"</option>";
			}
		});

		$("#"+form).find("#station").html(str);

		if(selected) get_subway(form, $("#station_val").val());	/** 초기값만 세팅하는 것이기 때문에 이것만 호출해 주면 된다. **/

		$("#"+form).find("#station").change(function(){
			$("#next_page").val("0");
			get_subway(form,this.value);
		});

		//refresh_lang();
	});
}

function get_subway(form,id){
	$("#danzi").val("");
	$.getJSON("/subway/get/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$("#"+form).find("#lat").val(data["lat"]);
		$("#"+form).find("#lng").val(data["lng"]);
		$("#"+form).find("#search_type").val("subway");
		$("#"+form).find("#search_value").val(id);
		send_form();
	});
}


function init_price(){
	$(".price_range").hide();

	if($("input[name='type']:checked").val()=="installation"){
		$(".price_sell").show();
		$(".installation_label").show();
		$(".sell_label").hide();
	} else if($("input[name='type']:checked").val()=="sell"){
		$(".price_sell").show();
		$(".sell_label").show();
		$(".installation_label").hide();
	} else if($("input[name='type']:checked").val()=="full_rent"){
		$(".price_full").show();
	} else if($("input[name='type']:checked").val()=="monthly_rent"){
		$(".price_rent").show();
	}

	if($("select[name='type']").val()=="installation"){
		$(".price_sell").css('display', 'inline-block');
		$(".installation_label").css('display', 'inline-block');
		$(".sell_label").hide();
	} else if($("select[name='type']").val()=="sell"){
		$(".price_sell").css('display', 'inline-block');
		$(".sell_label").css('display', 'inline-block');
		$(".installation_label").hide();
	} else if($("select[name='type']").val()=="full_rent"){
		$(".price_full").css('display', 'inline-block');
	} else if($("select[name='type']").val()=="monthly_rent"){
		$('.price_rent').css('display', 'inline-block');
	}
}

function init_search(type, category, category_sub){

	/** 거래 유형 초기화 **/
	$(".type_label").removeClass("active");
	var type_obj = $("input[name='type']").filter("[value='"+type+"']");
	type_obj.prop('checked', true);
	type_obj.parent().addClass("active");
	
	init_price(); //슬라이드 영역 보여주기

	$(".slider").each(function(){

		/*** 슬라이드 초기화 ***/
		$(this).noUiSlider({
		  start: [ parseInt($(this).attr("data-start")), parseInt($(this).attr("data-end")) ],
		  range: {'min':0,'max':parseInt($(this).attr("data-max"))},
		  step: parseInt($(this).attr("data-step"))
		}, true);
		
		/*** 슬라이드와 가격정보 연동 ***/
		$(this).Link('lower').to($("#"+$(this).attr("data-type")+"_start"),null,wNumb({decimals: 0}));
		$(this).Link('upper').to($("#"+$(this).attr("data-type")+"_end"),null,wNumb({decimals: 0}));

		/*** 가격 레벨 초기갑 표시 ***/
		var str = ""
		var	unit = price_unit;	/** 금액단위 **/
		if($(this).attr("data-type")=="sell") unit = sell_unit;

		str += numberWithCommas($("#"+$(this).attr("data-type")+"_start").val());
		str += unit;
		str += " - ";
		if($("#"+$(this).attr("data-type")+"_end").val() < $(this).attr("data-max")){
			str += numberWithCommas($("#"+$(this).attr("data-type")+"_end").val());
			str += unit;
		} else {
			str += "무제한";
		}

		$("#"+$(this).attr("data-type")+"_label").html(str);

	});

	/*** 슬라이드 수정시 행동 ***/
	$(".slider").on({
		slide: function(){
			var str = ""
			var	unit = price_unit;	/** 금액단위 **/
			if($(this).attr("data-type")=="sell") unit = sell_unit;

			str += numberWithCommas($("#"+$(this).attr("data-type")+"_start").val());
			str += unit;
			str += " - ";
			if(parseInt($("#"+$(this).attr("data-type")+"_end").val()) < parseInt($(this).attr("data-max"))){
				str += numberWithCommas($("#"+$(this).attr("data-type")+"_end").val());
				str += unit;
			} else {
				str += "무제한";
			}
			
			$("#"+$(this).attr("data-type")+"_label").html(str);
		},
		change: function(){
			if( $("#search_form").attr("action") != "/search/set_search/main" )	send_form();
		}
	}, true);

	/** 매물유형(category) 초기화 **/
	var cat = category.split(",");
	$("input[name='category[]']").each(function(){
		if( contains(cat, $(this).val()) ) {
			$(this).prop('checked',true);
		}
	});

	if(category_sub!=null){
		var cat = category_sub.split(",");
		$("input[name='category_sub[]']").each(function(){
			if( contains(cat, $(this).val()) ) {
				$(this).prop('checked',true);
			}
		});	
	}

	$(".category_checkbox").iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
		increaseArea: '20%' // optional
	  });


}

/**
 * 배열에 값이 있는지 여부를 체크하는 함수
 */
function contains(a, obj) {
    for (var i = 0; i < a.length; i++) {
        if (a[i] === obj) {
            return true;
        }
    }
    return false;
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


function get_gugun_modal(obj,sido){
	$("#sido_label").addClass("active");
	$("#label_text").text("구군을 선택하세요");
	$("#sido_section > ul > li > div> button").removeClass("active");
	$("#gugun_label").removeClass("active");
	$("#dong_label").removeClass("active");
	$(obj).addClass("active");
	$("#sido").val($(obj).text());
	$("#dong_section > ul > li > div").html("");

	$.getJSON("/address/get_gugun/full/"+encodeURI(sido)+"/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			str = str + "<button type=\"button\" class=\"btn btn-default\" onclick=\"get_dong_modal(this,"+val["parent_id"]+")\" data-id=\""+val["parent_id"]+"\">"+val["gugun"]+"</button>";
		});
		$("#gugun_section > ul > li > div").html(str);
	});
}

function get_dong_modal(obj,parent_id){
	$("#gugun_label").addClass("active");
	$("#label_text").text("읍면동을 선택하세요");
	$("#gugun_section > ul > li > div > button").removeClass("active");
	$("#dong_label").removeClass("active");
	$(obj).addClass("active");
	$("#gugun").val($(obj).text());

	$.getJSON("/address/get_dong/full/"+parent_id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			str = str + "<button type=\"button\" class=\"btn btn-default\" onclick=\"area_search(this,"+val["id"]+",'"+val["lat"]+"','"+val["lng"]+"','')\">"+val["dong"]+"</button>";
		});
		$("#dong_section > ul > li > div").html(str);
	});
}

function area_search(obj,id,lat,lng){
	$("#search_type").val("address");
	$("#search_value").val(id);
	$("#lat").val(lat);
	$("#lng").val(lng);

	$("#dong_label").addClass("active");
	$("#dong_section > ul > li > div > button").removeClass("active");
	$(obj).addClass("active");
	
	send_form();
	$("#address_modal").modal("hide");
}

function get_hosun_modal(obj,local){
	$("#local_label").addClass("active");
	$("#label_text").text("호선을 선택하세요");
	$("#local_section > ul > li > div > button").removeClass("active");
	$("#hosun_label").removeClass("active");
	$(obj).addClass("active");
	$("#station_section > ul > li > div").html("");
	$.getJSON("/subway/get_hosun/"+local+"/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			var hosun = ($.isNumeric(val["hosun"])) ? val["hosun"]+"호선" : val["hosun"];
			str = str + "<button type=\"button\" class=\"btn btn-default\" onclick=\"get_station_modal(this,"+val["hosun_id"]+")\">"+hosun+"</button>";
		});
		$("#hosun_section > ul > li > div").html(str);
	});
}

function get_station_modal(obj,hosun_id){
	$("#hosun_label").addClass("active");
	$("#label_text").text("역을 선택하세요");
	$("#hosun_section > ul > li > div > button").removeClass("active");
	$("#station_label").removeClass("active");
	$(obj).addClass("active");
	$.getJSON("/subway/get_station/"+hosun_id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			str = str + "<button type=\"button\" class=\"btn btn-default\" onclick=\"subway_search(this,"+val["id"]+",'"+val["lat"]+"','"+val["lng"]+"','"+val["local"]+"','"+val["hosun"]+"')\">"+val["name"]+"역</button>";
		});
		$("#station_section > ul > li > div").html(str);
	});
}

function subway_search(obj,id,lat,lng,subway_local,hosun){
	$("#search_type").val("subway");
	$("#search_value").val(id);
	$("#lat").val(lat);
	$("#lng").val(lng);
	$("#subway_local_val").val(subway_local);
	$("#hosun_val").val(hosun);
	$("#station_val").val(id);

	$("#station_label").addClass("active");
	$("#station_section > ul > li > div > button").removeClass("active");
	$(obj).addClass("active");
	
	send_form();
	$("#subway_modal").modal("hide");
}

function region_select(id){
	$.getJSON("/region/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$("#search_type").val("address");
		$("#search_value").val(data["address_id"]);
		$("#lat").val(data["lat"]);
		$("#lng").val(data["lng"]);
		send_form();
	});
}