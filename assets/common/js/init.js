// If JavaScript is enabled remove 'no-js' class and give 'js' class
jQuery('html').removeClass('no-js').addClass('js');

// When DOM is fully loaded
jQuery(document).ready(function($) {

	if($('.help').length > 0){
		$('.help').tooltip();	//리스트 상에 help class가 있으면 동작하게 한다.
	}

	//북마크버튼
	$('.bookmarkMeLink').click(function() {
			if (window.sidebar && window.sidebar.addPanel) { 
				// Mozilla Firefox Bookmark
				window.sidebar.addPanel(document.title,window.location.href,'');
			}
			else if(window.sidebar && jQuery.browser.mozilla){
				//for other version of FF add rel="sidebar" to link like this:
				//<a id="bookmarkme" href="#" rel="sidebar" title="bookmark this page">Bookmark This Page</a>
				jQuery(this).attr('rel', 'sidebar');
			}
			else if(window.external && ('AddFavorite' in window.external)) { 
				// IE Favorite
				window.external.AddFavorite(location.href,document.title); 
			} else if(window.opera && window.print) { 
				// Opera Hotlist
				this.title=document.title;
				return true;
			} else { 
				// webkit - safari/chrome
				alert('Press ' + (navigator.userAgent.toLowerCase().indexOf('mac') != - 1 ? 'Command/Cmd' : 'CTRL') + ' + D to bookmark this page.');

			}
	});
});

/**
 * http://getbootstrap.com/components/#alerts
 * 
 * @type : success, info, warning, dander, error(bootstrap 3.x 에서는 사라짐)
 */
function msg(obj,type,msg){
	obj.hide();
	obj.html("<div class=\"alert alert-"+type+"\" style='font-size:12px;margin-bottom:0px;'><button type=\"button\" class=\"close\" data-dismiss=\"alert\" onclick=\"$(this).parent().hide();\">&times;</button>"+msg+"</div>");
	obj.fadeIn("slow")
}

function number_format(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function loading_delay(display){
	if(display){
		//$(".loading_content" ).parent().css("overflow-y", "hidden");
		$(".loading_content" ).show();
	}
	else{
		$(".loading_content" ).hide("fast", function(){
			//$(".loading_content" ).parent().css("overflow-y", "auto");			
		});

	}
}

function loading(display){
	if(display){
		$(".loading_content" ).parent().css("overflow-y", "hidden");
		$(".loading_content" ).show();
	}
	else{
		$(".loading_content" ).hide();
		$(".loading_content" ).parent().css("overflow-y", "auto");
	}
}

/**
 * 프린트 기능(프론트)
 *
 */
function print(){
	var printWindow = window.open('', '', 'height=400,width=800,scrollbars=yes');
	printWindow.document.write('<html><head><title></title>');
	printWindow.document.write('<link href="/assets/common/css/print.css" rel="stylesheet"></head><body >');
	$(".not_print").hide();
	$(".is_print").show();
	printWindow.document.write($("#print_area").html());
	$(".not_print").show();
	$(".is_print").hide();
	printWindow.document.write('</body></html>');
	printWindow.document.close();
	setTimeout(function() {
      printWindow.print();
	}, 1000);

}

/**
 * 면적 대비 가격 표시하기 위한 함수
 */
function cal_unit_price(){

	/** 토지 면적대비가격 **/

	var sell_price 			= isNaN(parseInt($("#sell_price").val())) ? 0 : parseInt($("#sell_price").val());
	var full_rent_price 	= isNaN(parseInt($("#full_rent_price").val())) ? 0 : parseInt($("#full_rent_price").val());
	var monthly_rent_price 	= isNaN(parseInt($("#monthly_rent_price").val())) ? 0 : parseInt($("#monthly_rent_price").val());
	var land_area			= isNaN(parseInt($("#land_area").val())) ? 0 : parseInt($("#land_area").val());
	var road_area			= isNaN(parseInt($("#road_area").val())) ? 0 : parseInt($("#road_area").val());
	var law_area			= isNaN(parseInt($("#law_area").val())) ? 0 : parseInt($("#law_area").val());


	if( land_area != 0 )
	{

		if( sell_price != 0 ){

			var up_land_m = ( sell_price / ( land_area + road_area ) ).toFixed(0);
			var up_land_p = ( up_land_m*3.305785 ).toFixed(0);

			$("#land_info").html("<i class=\"fa fa-krw\"></i> 면적대비가격:가격/총면적(대지+도로) => <b>"+number_format(up_land_m)+"</b>만원/㎡, <b>" + number_format(up_land_p) + "</b>만원/평");	
		}

		if( road_area != 0 ){
			var road_rate = ( parseInt($("#road_area").val()) / (parseInt($("#land_area").val())+ parseInt($("#road_area").val()) )).toFixed(2) * 100;
			$("#road_rate").html( "도로지분율:" + road_rate +"%" );
		}

	}

	/** 매물 면적 대비 가격 : 계약면적 또는 연면적 기준으로 가격을 산정 **/
	var product_price = 0;
	
	if( law_area != 0 ){
		if( $("#type").val() == "sell" || $("#type").val() == "installation" ){

			product_price = ( sell_price / law_area ).toFixed(0);

		} else if( $("#type").val() == "full_rent" ){

			product_price = ( full_rent_price / law_area ).toFixed(0);

		} else if( $("#type").val() == "monthly_rent" ){

			product_price = ( monthly_rent_price / law_area ).toFixed(0);

		}
	}

	if(product_price!=0){

		$("#product_info").html("<i class=\"fa fa-krw\"></i> 면적대비가격:가격/면적(계약 또는 연면적) => <b>"+number_format(product_price)+"</b>만원/㎡, <b>" + number_format((product_price*3.305785).toFixed(0)) + "</b>만원/평");

	}

}

function closeWin(param){
	pop_setcookie("pop"+param,"done",1);
	$("#notice_"+param).modal('hide');
}

function pop_setcookie(name,value,expire){
	var todayDate = new Date();
	todayDate.setDate(todayDate.getDate() + expire);
	document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function getCookieVal(offset) {
	var endstr = document.cookie.indexOf(";", offset);
	if (endstr == -1) endstr = document.cookie.length;
	return unescape(document.cookie.substring(offset, endstr));
}

function GetCookie(name) {
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen) {
        var j = i + alen;
        if (document.cookie.substring(i, j) == arg) return getCookieVal(j);
		i = document.cookie.indexOf(" ", i) + 1;
			if (i == 0) break;
    }
	return null;
}

function notice_show(id,form){
	$("#"+form).find("#profile_msg").html('');
	$.getJSON("/notice/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			if(key=="title") $("#notice_modal_title").html(val);
			if(key=="content") $("#notice_modal_content").html(val);
			$('#notice_modal').modal('show');
		});
	});
}

/**
 * 금액 만원 단위 한글로 변환
 */
function setWon(pWon) {
	if(!pWon) return 0;

	var won  = (pWon+"").replace(/,/g, "");
	var arrWon  = ["원", "만", "억 ", "조 ", "경 ", "해 "];
	var changeWon = "";
	var pattern = /(-?[0-9]+)([0-9]{4})/;

	while(pattern.test(won)) {                   
		won = won.replace(pattern,"$1,$2");
	}

	var arrCnt = won.split(",").length-1;

	for(var ii=0; ii<won.split(",").length; ii++) {
		changeWon += won.split(",")[ii]+arrWon[arrCnt];
		arrCnt--;
	}

	if(won.length > 4){
		var split_won = changeWon.split("만");
		return split_won[0]+"만원";	 
	}
	else{
		return changeWon;
	}
}