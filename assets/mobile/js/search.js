$(document).ready(function() {
	
	/** 매매유형을 클릭할 때마다 바꿔주는 코드 **/
	$(".btn-group > .btn").click(function(){
		$(".btn-group > .btn").removeClass("active");
		$(this).addClass("active");
	});

	/** 주소 검색 초기화 **/
	get_sido("search_form",'front');

	/** 지하철 검색 초기화 **/
	get_subway_local("search_form");

	/** 검색 탭 처리 **/
	$("ul.nav-search li").click(function(e){
		search_reset();
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
			$("#search_form").attr("action","/search/set_search/map/1");
			$("#search_type").val("google");
			$("#search_value").val($("#search").val());
			$("#keyword_front").val("");
			$("#lat").val(result.geometry.location.lat());
			$("#lng").val(result.geometry.location.lng());
			$("#zoom").val(16);
			$("#search_form").trigger("submit");

		})
		.bind("geocode:error", function(event, status){
			if($.isNumeric($("#search").val())){
				location.href="/mobile/view/"+$("#search").val();
				return false;
			}
			$("#search_form").attr("action","/search/set_search/grid/1");
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
			location.href="/mobile/view/"+$("#search").val();
			return false;
		}
		else{
			//$("#search").trigger("geocode");
		}
		//$("#search_form").submit();
	});

	$("#go_keyword").click(function(){
		if($.isNumeric($("#search").val())){
			location.href="/mobile/view/"+$("#search").val();
			return false;
		}
		$("#search_form").attr("action","/search/set_search/grid/1");
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
}

/**
 * 검색 방식을 변경할 때 값을 초기화하는 함수
 */
function search_reset(){
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
			if( $("#sido_val").val()==val["sido"] ){
				str = str + "<option value='"+val["sido"]+"' selected>"+val["sido"]+"</option>";
				selected = true;
			} else {
				str = str + "<option value='"+val["sido"]+"'>"+val["sido"]+"</option>";
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
			if( $("#gugun_val").val()==val["parent_id"] ){
				str = str + "<option value='"+val["parent_id"]+"' selected>"+val["gugun"]+"</option>";
				selected = true;
			} else {
				str = str + "<option value='"+val["parent_id"]+"'>"+val["gugun"]+"</option>";
			}
		});

		$("#"+form).find("#gugun").html(str);
		if(selected) get_dong(form, type, $("#gugun").val());	/** 초기값만 세팅하는 것이기 때문에 이것만 호출해 주면 된다. **/

		$("#"+form).find("#gugun").change(function(){
			$("#"+form).find("#search_type").val("parent_address");
			$("#"+form).find("#search_value").val(this.value);
			$("#"+form).find("#lat").val();
			$("#"+form).find("#lng").val();

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
			if( $("#dong_val").val()==val["id"] ){
				str = str + "<option value='"+val["id"]+"' selected>"+val["dong"]+"</option>";
				selected = true;
			} else {
				str = str + "<option value='"+val["id"]+"'>"+val["dong"]+"</option>";
			}
		});

		$("#"+form).find("#dong").html(str);

		if(selected) get_address(form, $("#dong_val").val());	/** 초기값만 세팅하는 것이기 때문에 이것만 호출해 주면 된다. **/

		$("#"+form).find("#dong").change(function(){
			get_address(form,this.value);
		});

		//refresh_lang();
	});
}

function get_address(form,id){
	$.getJSON("/address/get/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$("#"+form).find("#lat").val(data["lat"]);
		$("#"+form).find("#lng").val(data["lng"]);
		$("#"+form).find("#search_type").val("address");
		$("#"+form).find("#search_value").val(id);
		//$("#search_form").trigger("submit");
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
			if( $("#hosun_val").val()==val["hosun_id"] ){
				str = str + "<option value='"+val["hosun_id"]+"' selected>"+val["hosun"]+"호선</option>";
				selected = true;
			} else {
				str = str + "<option value='"+val["hosun_id"]+"'>"+val["hosun"]+"호선</option>";
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
			if( $("#station_val").val()==val["id"] ){
				str = str + "<option value='"+val["id"]+"' selected>"+val["name"]+"</option>";
				selected = true;

			} else {
				str = str + "<option value='"+val["id"]+"'>"+val["name"]+"</option>";
			}
		});

		$("#"+form).find("#station").html(str);

		if(selected) get_subway(form, $("#station_val").val());	/** 초기값만 세팅하는 것이기 때문에 이것만 호출해 주면 된다. **/

		$("#"+form).find("#station").change(function(){
			get_subway(form,this.value);
		});

		//refresh_lang();
	});
}

function get_subway(form,id){
	$.getJSON("/subway/get/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$("#"+form).find("#lat").val(data["lat"]);
		$("#"+form).find("#lng").val(data["lng"]);
		$("#"+form).find("#search_type").val("subway");
		$("#"+form).find("#search_value").val(id);
		//$("#search_form").trigger("submit");
	});
}


function init_price(){
	$(".price_range").hide();

	if($("input[name='type']:checked").val()=="installation"){
		$(".price_sell").fadeIn();
		$(".installation_label").fadeIn();
		$(".sell_label").hide();
	} else if($("input[name='type']:checked").val()=="sell"){
		$(".price_sell").fadeIn();
		$(".sell_label").fadeIn();
		$(".installation_label").hide();
	} else if($("input[name='type']:checked").val()=="full_rent"){
		$(".price_full").fadeIn();
	} else if($("input[name='type']:checked").val()=="monthly_rent"){
		$(".price_rent").fadeIn();
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
			//if( $("#search_form").attr("action") != "/search/set_search/main" )	$('#search_form').submit();
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