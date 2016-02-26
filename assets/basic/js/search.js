$(document).ready(function() {
	
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
			$("#search_form").trigger("submit");

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
			$("#search_form").trigger("submit");
		});

	$("#geo_btn").click(function(){
		if($.isNumeric($("#search").val())){
			location.href="/product/view/"+$("#search").val();
			return false;
		}
		else{
			$("#search").trigger("geocode");
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
		$("#search_form").trigger("submit");
	});

});

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
			$("#search_form").trigger("submit");
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
	$('#search_form').trigger('submit');
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
		$("#search_form").trigger("submit");
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
}

function init_search(type, category){

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
			if( $("#search_form").attr("action") != "/search/set_search/main" )	$('#search_form').submit();
		}
	}, true);

	/** 매물유형(category) 초기화 **/
	var cat = category.split(",");
	$("input[name='category[]']").each(function(){
		if( contains(cat, $(this).val()) ) {
			$(this).prop('checked',true);
		}
	});

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