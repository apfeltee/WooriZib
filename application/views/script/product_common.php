$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */
	$('.help').tooltip(); 



	/** 폼 유효성 체크는 입력이나 수정에 모두 필요하다. **/
	$("#product_form").validate({  
		errorElement: "span",
		wrapper: "span",  
		rules: {
			sido: {  
				required: true  
			},
			gugun: {  
				required: true
			},
			dong: {  
				required: true
			},
			category: {
				required: true
			},
			title: {  
				required: true,  
				minlength: 1
			},
			sell_price: {
				required: function(element){
					if( $("input[name='type']:checked").val() == "installation" || $("input[name='type']:checked").val() == "sell"){
						return true;
					} else {
						return false;
					}
				}
			},
			full_rent_price: {
				required: function(element){
					if( $("input[name='type']:checked").val() == "full_rent" || $("input[name='type']:checked").val() == "rent"){
						return true;
					} else {
						return false;
					}
				}
			},
			monthly_rent_deposit: {
				required: function(element){
					if( $("input[name='type']:checked").val() == "monthly_rent" || $("input[name='type']:checked").val() == "rent"){
						return true;
					} else {
						return false;
					}
				}
			},
			monthly_rent_price: {
				required: function(element){
					if( $("input[name='type']:checked").val() == "monthly_rent" || $("input[name='type']:checked").val() == "rent"){
						return true;
					} else {
						return false;
					}
				}
			}
		},  
		messages: {
			sido: {  
				required: "<?php echo lang("form.required");?>"
			},
			gugun: {  
				required: "<?php echo lang("form.required");?>"
			},
			dong: {
				required: "<?php echo lang("form.required");?>"
			},				
			category: {
				required: "<?php echo lang("form.required");?>"
			},
			title: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "최소 3자리 이상입니다"
			}
		}
	});  

	/**
	 * <?php echo lang("product");?> 종류 정보가 선택될 때 해당 유형의 선택 옵션 정보를 가져온다.
	 */
	$("input[name='category']").change(function(){
		get_option();
	});

	$("input[name='type']").change(function(){
		show_price_area();
	});

	$('input[name="part"]:radio').change(function(){
		get_part();
	});

	$(".operation_a").keyup(function(e) {
		if($("#real_area").val()){
			$("#real_pyoung").val(($("#real_area").val()/3.3058).toFixed(2));
		}
		if($("#law_area").val()){
			$("#law_pyoung").val(($("#law_area").val()/3.3058).toFixed(2));
		}
		$("#land_pyoung").val(($("#land_area").val()/3.3058).toFixed(2));
		$("#road_pyoung").val(($("#road_area").val()/3.3058).toFixed(2));

		cal_unit_price();
	});

	/* TAB키(keyCode=9)일 경우에는 수정도 없이 넘어와서 앞의 값을 수정해 버려서 동작하지 않도록 한다. */
	$(".operation_p").keyup(function(e) {
	
		if (e.keyCode == 9) {
	                    e.preventDefault;
	              } else {
			if($("#real_pyoung").val()){
				$("#real_area").val(($("#real_pyoung").val()*3.3058).toFixed(2));
			}
			$("#law_area").val(($("#law_pyoung").val()*3.3058).toFixed(2));
			$("#land_area").val(($("#land_pyoung").val()*3.3058).toFixed(2));
			$("#road_area").val(($("#road_pyoung").val()*3.3058).toFixed(2));

			cal_unit_price();
		}
	});

	/* 공실 전화번호 */
	$("#add_phone").click(function(e){
		e.preventDefault();
		$("#phone_section").append('<div class="multi-form-control-wrapper"><select name="phone_type[]" class="form-control input-small input-inline" autocomplete="off"><option value=\"사장\">사장</option><option value=\"사모\">사모</option><option value=\"관리\">관리</option><option value=\"부동산\">부동산</option><option value=\"가족\">가족</option><option value=\"자택\">자택</option><option value=\"세입자\">세입자</option></select><input type="text" name="phone[]" class="form-control input-inline input-medium" placeholder="휴대폰, 일반전화, 팩스 등" autocomplete="off"/> <button type="button" class="input_delete btn red btn-xs"><i class="fa fa-minus"></i></button></div>');

		$(".input_delete").on("click",function(e){
			e.preventDefault(); $(this).parent('div').remove();
		})
	});

	$(".input_delete").on("click",function(e){
		e.preventDefault(); $(this).parent('div').remove();
	});

	/* 첨부파일 */
	$("#add_file").click(function(e){
		e.preventDefault();
		$("#file_section").append('<div class="multi-form-control-wrapper"><input type="file" name="userfile[]" class="form-control input-inline input-xlarge" placeholder="첨부파일선택" autocomplete="off" style="height:auto"/> <button type="button" class="input_delete btn red btn-xs input-inline"><i class="fa fa-minus"></i></button></div>');

		$(".input_delete").on("click",function(e){
			e.preventDefault(); $(this).parent('div').remove();
		})
	});

	$("#add_monthly").click(function(){
		var add_monthly_input = "";
		add_monthly_input += '<input type="number" name="monthly_rent_deposit_add[]" class="form-control input-inline input-small" title="<?php echo lang('product.price.rent.deposit');?>" placeholder="<?php echo lang('product.price.rent.deposit');?>" value=""/> <small><?php echo lang('price_unit.form');?></small> ';

		add_monthly_input += '<input type="number" name="monthly_rent_price_add[]" class="operation_a form-control input-inline input-small" title="<?php echo lang('product.price.rent');?>" placeholder="<?php echo lang('product.price.rent');?>" value=""/> <small><?php echo lang('price_unit.form');?> <button type="button" class="btn red btn-xs input-inline" onclick="$(this).parent().parent().remove();"><i class="fa fa-minus"></i></button></small>';

		$("#monthly_add_section").append('<div class="margin-top-10">'+add_monthly_input+'</div>');
	});
});

/**
 * 값이 1이면 보여주고 0이면 보여주지 않는다.
 */
function display_form(item, value){
	if(value=="1"){
		$("#"+item).removeClass("display-none");
	} else {
		$("#"+item).addClass("display-none");
	}
}

/**
 * 입주일의 경우 선택해서 입력할 수도 있어야 하고 직접 입력을 할 수도 있어야 한다.
 */
function select_tag(section, item, value){
	var str = "(예:";
	if(value!=""){
		var v = value.split(",");

		for(i=0;i<v.length;i++)  {
			str += "<a style='text-decoration:underline;' href='javascript:set_select(\""+item+"\",\""+v[i]+"\");'>" + v[i] + "</a> ";
		}
		$("#"+item+"_text").html(str+")");
		$("#"+section).removeClass("display-none");
	} else {
		$("#"+section).addClass("display-none");
	}
}

/**
 * 우측 선택항목에서 클릭하여 입력을 하는 기능
 */
function set_select(a,b){
	$("#"+a).val(b);
}

function show_price_area(){
	
	$("#mgr_price_full_rent_section").hide();
	$("#mgr_price").attr("title","<?php echo lang('product.mgr_price');?>");
	$("#mgr_price").attr("placeholder","<?php echo lang('product.mgr_price');?>");

	if( $("input[name='type']:checked").val()=="installation" || $("input[name='type']:checked").val()=="sell"){

		$("#sell_price_area").show();
		$("#full_price_area").hide();

		if($("input[name='part']:checked").val()=="1"){
			$("#rent_price_area").hide();
		} else {
			$("#rent_price_area").hide();
			<?php if($config->ALL_RENT_PRICE){ ?>
			$("#rent_price_area").show();
			$("#monthly_rent_deposit").attr("title","<?php echo lang('product.price.rent.deposit.all');?>");
			$("#monthly_rent_price").attr("title","<?php echo lang('product.price.rent.all');?>");
			$("#monthly_rent_deposit").attr("placeholder","<?php echo lang('product.price.rent.deposit.all');?>");
			$("#monthly_rent_price").attr("placeholder","<?php echo lang('product.price.rent.all');?>");
			<?php } ?>
		}
		
	
		if($("input[name='type']:checked").val()=="installation"){
			 $("#sell_price").attr("title","<?php echo lang('product.price.installation.sell');?>");
			 $("#sell_price").attr("placeholder", "<?php echo lang('product.price.installation.sell');?>");
			 $("#lease_price").attr("title","<?php echo lang('product.price.installation.lease');?>");
			 $("#lease_price").attr("placeholder", "<?php echo lang('product.price.installation.lease');?>");
		} else {
			 $("#sell_price").attr("title", "<?php echo lang('product.price.sell.sell');?>");
			 $("#sell_price").attr("placeholder", "<?php echo lang('product.price.sell.sell');?>");
			 $("#lease_price").attr("title", "<?php echo lang('product.price.sell.lease');?>");
			 $("#lease_price").attr("placeholder", "<?php echo lang('product.price.sell.lease');?>");
		}

	} else if( $("input[name='type']:checked").val()=="full_rent"){
		$("#sell_price_area").hide();
		$("#full_price_area").show();
		$("#rent_price_area").hide();
	} else if( $("input[name='type']:checked").val()=="monthly_rent"){
		$("#sell_price_area").hide();
		$("#full_price_area").hide();
		$("#rent_price_area").show();

		$("#monthly_rent_deposit").attr("title","<?php echo lang('product.price.rent.deposit');?>");
		$("#monthly_rent_price").attr("title","<?php echo lang('product.price.rent');?>");
		$("#monthly_rent_deposit").attr("placeholder","<?php echo lang('product.price.rent.deposit');?>");
		$("#monthly_rent_price").attr("placeholder","<?php echo lang('product.price.rent');?>");
	} else if( $("input[name='type']:checked").val()=="rent"){
		$("#sell_price_area").hide();
		$("#full_price_area").show();
		$("#rent_price_area").show();
		
		if($("#mgr_price_full_rent").attr("data-use")=="1"){
			$("#mgr_price_full_rent_section").show();
			$("#mgr_price").attr("title","<?php echo lang('product.mgr_price');?>(월세)");
			$("#mgr_price").attr("placeholder","<?php echo lang('product.mgr_price');?>(월세)");			
		}

		$("#monthly_rent_deposit").attr("title","<?php echo lang('product.price.rent.deposit');?>");
		$("#monthly_rent_price").attr("title","<?php echo lang('product.price.rent');?>");
		$("#monthly_rent_deposit").attr("placeholder","<?php echo lang('product.price.rent.deposit');?>");
		$("#monthly_rent_price").attr("placeholder","<?php echo lang('product.price.rent');?>");

	}
}

/**
 * 방수, 욕실수를 모두 사용하지 않을 경우에는 방수 입력 섹션을 아예 보여주지 않는다. 2015년 11월 6일
 */
function get_part(){

	if($('input[name="part"]:checked').val()=="Y"){
		
		$("#real_area_label").html("<?php echo lang("product.realarea");?>");
		$("#law_area_label").html("<?php echo lang("product.lawarea");?>");
		$("#floor_label").html("<?php echo lang("product.floor");?>");

		if($("#bedcnt").hasClass("display-none") && $("#bathcnt").hasClass("display-none")){
			$("#roon_cnt").hide();
		} else {
			$("#roon_cnt").show();
		}
		$(".part_n").hide();
		
	} else {
		
		$("#real_area_label").html("건축면적");
		$("#law_area_label").html("연면적");

		<?php if($config->USE_FACTORY){?>
		$("#floor_label").html("층고(처마높이기준)");
		<?php } else {?>
		$("#floor_label").html("지상층/지하층");
		<?php }?>

		$("#roon_cnt").hide();
		$(".part_n").show();
	}

	show_price_area();
}

/**
 * 좌표가 지정되었을 경우에만 지도를 보여주고 그렇지 않으면 안내 문구가 보여지도록 한다.
 */
function show_map(map){
	if($('#lat').val()!="" && $('#lng').val()!=""){
		$("#gmap_info").addClass("display-none");
		$("#gmap").removeClass("display-none");
		map.relayout();
	}
}

/**
 * 단지의 좌표를 가져온다.
 */
function get_danzi_coords(id){
	$.getJSON("/danzi/get_danzi_coords/"+id+"/"+Math.round(new Date().getTime()),function(data){
		if(data!=""){
			$('#lat').val(data["lat"]);
			$('#lng').val(data["lng"]);
			if(data["bunzi"]!=""){
				$('#address').val(data["bunzi"]);
			}
		}
	});
}

function apply_autoComplete(owner_type,contacts_id,owner_name){
	owner_name.autocomplete({
		selectFirst: true, 
		autoFill: true,
		autoFocus: true,
		focus: function(event,ui){
			return false;
		},
		scrollHeight:40,
		minlength:1,
		select: function(a,b){
			if(b.item.name){
				var use_contacts_id = false;
				$.each($("input[name='contacts_id[]']"), function(){
					if($(this).val()==b.item.id){
						alert("이미 등록된 소유주입니다");
						owner_name.val("");
						use_contacts_id = true;
						return false;
					}
				});
				if(!use_contacts_id){
					contacts_id.val(b.item.id);
					owner_name.val(b.item.name+" ("+b.item.phone+")");				
				}
				a.stopPropagation();		
			}
			return false;
		},
		source: function(request, response){
			$.ajax({
				url: "/admincontact/contact_member",
				type: "POST",
				data: {
					search: owner_name.val()
				},
				dataType: "json",
				success: function(data) {
					if(data!=""){
						response( $.map( data, function( item ) {
							if(item.phone!=""){
								item.phone = item.phone.split("-dungzi-")[0];
								item.phone = item.phone.split("--")[2];						
							}					
							return {
								id: item.id,
								name: item.name,
								phone: item.phone
							}; 
						}));				
					}
					else{
						response(["no_result"]);			 
					}
				}
			});						
		},
	}).data("ui-autocomplete")._renderItem = autoCompleteRenderContact;
}

function autoCompleteRenderAdmin(ul, item) {
	return $("<li class='search_rows'></li>").data("item.autocomplete", item).append("<i class='fa fa-user'></i> " + item.member_name+'('+item.member_email+')').appendTo(ul);
}

function autoCompleteRenderContact(ul, item) {
	if(item.name){
		return $("<li class='search_rows'></li>").data("item.autocomplete", item).append("<i class='fa fa-user'></i> " + item.name+'('+item.phone+')').appendTo(ul);	
	}
	else{
		return $("<li class='search_rows'></li>").data("item.autocomplete", item).append("<i class='fa fa-user'></i> " + "소유주가 검색되지 않습니다" + " <a href='/admincontact/add' target='_blank'><button type='button' class='btn btn-xs btn-primary'>소유주 등록하러 가기</button></a>").appendTo(ul);
	}
}

function contacts_id_set(contacts_id){
	if(contacts_id.val()!=""){
		contacts_id.val(contacts_id.val());
	}
}

function owner_delete(element){
	$(element).parent('div').remove();
}

function get_gugun(obj,sido){
	$("#sido_label").addClass("active");
	$("#label_text").text("구군을 선택하세요");
	$("#sido_section > ul > li > div > button").removeClass("active");
	$("#gugun_label").removeClass("active");
	$("#dong_label").removeClass("active");
	$(obj).addClass("active");
	$("#sido").val($(obj).text());
	$("#dong_section > ul > li > div").html("");

	$.getJSON("/address/get_gugun/full/"+encodeURI(sido)+"/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			str = str + "<button type=\"button\" class=\"btn btn-default\" onclick=\"get_dong(this,"+val["parent_id"]+")\" data-id=\""+val["parent_id"]+"\">"+val["gugun"]+"</button>";
		});
		$("#gugun_section > ul > li > div").html(str);
		if(typeof gugun_scroll != "undefined"){
			RefreshScroll(gugun_scroll);
		}
	});
}

function get_dong(obj,parent_id){
	$("#gugun_label").addClass("active");
	$("#label_text").text("읍면동을 선택하세요");
	$("#gugun_section > ul > li > div > button").removeClass("active");
	$("#dong_label").removeClass("active");
	$(obj).addClass("active");
	$("#gugun").val($(obj).text());

	$.getJSON("/address/get_dong/full/"+parent_id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			str = str + "<button type=\"button\" class=\"btn btn-default\" onclick=\"area_search(this,"+val["id"]+")\" data-id=\""+val["id"]+"\">"+val["dong"]+"</button>";
		});
		$("#dong_section > ul > li > div").html(str);

		if(typeof dong_scroll != "undefined"){
			RefreshScroll(dong_scroll);
		}
	});
}

function area_search(obj,id){
	$("#dong_label").addClass("active");
	$("#dong_section > ul > li > div > button").removeClass("active");
	$(obj).addClass("active");
	$("#dong").val($(obj).text());

	var address_text = $("#sido").val()+" "+$("#gugun").val()+" "+$("#dong").val();
	$("#address_text").val(address_text);

	get_address(id);

	$("#address_modal").modal('hide');
}

function get_address(id){
	$("#address_id").val(id);

	if($("#danzi_id").length > 0){
		$("#danzi_id").remove();
	}

	$.getJSON("/danzi/get_danzi_name/"+id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value='' selected>아파트단지선택</option>";
		if(data!=""){
			$.each(data, function(key, val) {
				str = str + "<option value='"+val["name"]+"'>"+val["name"]+"</option>";
			});
		}
		else{
			str = str + "<option value=''><?php echo lang("msg.nodata");?></option>";
		}
		$("#danzi_name").html(str);

		$("#danzi_name").change(function(){
			get_danzi_area(id,this.value);
		});
	});
}

function get_danzi_area(id,name){

	if(name=="") return false;

	$.ajax({
		url: "/danzi/get_area",
		type: "POST",
		async: false,
		data: {
			address_id: id,
			danzi_name: name
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

			if($("#danzi_id").length > 0){
				$("#danzi_id").html(str);
			}
			else{
				$("#danzi_name").after("<select id='danzi_id' name='danzi_id' class='form-control input-inline'>"+str+"</select>");
			}

			$("#danzi_id").change(function(){
				get_danzi_coords(this.value);
			});
		}
	});
}

function address_modal(address_text,mode){

	if(address_text!=""){
		if(mode=="add" && $("#address_id").val()!=""){

		}
		else{
			$.ajaxSetup({async: false});
			var split = address_text.split(" ");

			var sido_obj = $("#sido_section > ul > li > div > button");
			sido_obj.each(function(){
				if(split[0] == $(this).text()){
					$(this).addClass("active");
					get_gugun($(this),$(this).text());
				}
			});

			if(typeof split[1] != "undefined"){
				var gugun_obj = $("#gugun_section > ul > li > div > button");
				gugun_obj.each(function(){
					if(split[1] == $(this).text()){
						$(this).addClass("active");;
						get_dong($(this),$(this).attr("data-id"));
					}
				});
			}
			
			if(typeof split[2] != "undefined"){
				var dong_obj = $("#dong_section > ul > li > div > button");
				$("#dong_label").addClass("active");
				dong_obj.each(function(){
					if(split[2] == $(this).text()){
						$(this).addClass("active");
					}
				});
			}
			$.ajaxSetup({async: true});		
		}
	}

	if(typeof sido_scroll != "undefined"){
		RefreshScroll(sido_scroll);
		if(mode=="edit"){
			RefreshScroll(gugun_scroll);
			RefreshScroll(dong_scroll);		
		}
	}
}

function RefreshScroll(i_scroll) {
    setTimeout(function () {
        i_scroll.scrollToElement('li:nth-child(1)', 100)
        setTimeout(function () {
            i_scroll.refresh();
			i_scroll.scrollTo(0, 0);
        }, 0);
    }, 500);
}