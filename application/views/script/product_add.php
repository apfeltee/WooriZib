$(document).ready(function(){

	<?php
		if(!MobileCheck()){
	?>

			var uploader = new plupload.Uploader({
				runtimes : 'html5,flash,silverlight,html4',
				browse_button : 'browse',

				url: "/gallerytemp/upload_action/<?php echo $type;?>/",
				filters : {
					max_file_size : '50mb',
					mime_types: [
						{title : "Image files", extensions : "jpg,gif,jpeg,png"}
					]
				},
				flash_swf_url : '/assets/plugin/plupload/Moxie.swf',
				silverlight_xap_url : '/assets/plugin/plupload/Moxie.xap',
				init: {
					FilesAdded: function(up, files) {
						/***
						plupload.each(files, function(file) {
							document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
						});
						***/
						uploader.start();
						$("#list").html("<div style='text-align:center;vertical-align:middle;'><img src='/assets/common/img/ajax-loader.gif'></div>");
					

					},
					UploadComplete: function(up, file) {
						get_list();
					},
					Error: function(up, err) {
						$("#console").html("\에러 #" + err.code + ": " + err.message);
					}
				}
			});	

			uploader.init();

			var uploader_admin = new plupload.Uploader({
				runtimes : 'html5,flash,silverlight,html4',
				browse_button : 'browse_admin',

				url: "/gallerytemp/upload_action/<?php echo $type;?>/_admin",
				filters : {
					max_file_size : '50mb',
					mime_types: [
						{title : "Image files", extensions : "jpg,gif,jpeg,png"}
					]
				},
				flash_swf_url : '/assets/plugin/plupload/Moxie.swf',
				silverlight_xap_url : '/assets/plugin/plupload/Moxie.xap',
				init: {
					FilesAdded: function(up, files) {
						/***
						plupload.each(files, function(file) {
							document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
						});
						***/
						uploader_admin.start();
						$("#list_admin").html("<div style='text-align:center;vertical-align:middle;'><img src='/assets/common/img/ajax-loader.gif'></div>");
					

					},
					UploadComplete: function(up, file) {
						get_list_admin();
					},
					Error: function(up, err) {
						$("#console_admin").html("\에러 #" + err.code + ": " + err.message);
					}
				}
			});	

			uploader_admin.init();
	<?php
		} else {	
	?>
			$("input[name='file']").change(function(e) {

				var formData = new FormData($("#product_form"));
				formData.append("file", $(this)[0].files[0]);

				e.stopPropagation();
				e.preventDefault();

				$.ajax({
					url: "/gallerytemp/upload_action/<?php echo $type;?>",
					type: "post",
					data: formData,
					processData: false,
					contentType: false,
					dataType: "json",
					success: function(data){
						get_list();
					},
					error:function(e){
					}
				});

			});
	<?php } ?>

	$("form").submit(function(e){
		if($("#flag").val()=="0") {
			e.preventDefault();
		}
	});

	var mapContainer = document.getElementById('gmap'),
		mapOption = { 
			center: new daum.maps.LatLng(<?php echo $config->lat?>, <?php echo $config->lng?>),
			level: 3
		};

	var map = new daum.maps.Map(mapContainer, mapOption);

	var markerPosition = new daum.maps.LatLng(<?php echo $config->lat?>, <?php echo $config->lng?>); 

	var marker = new daum.maps.Marker({
		position: markerPosition
	});

	marker.setMap(map);

	marker.setDraggable(true);

	$("#get_coord").click(function(){

		if($("#sido").val()=="" || $("#gugun").val()=="" || $("#dong").val()=="") return false;

		if($("#danzi_id").val()){
			var coords = new daum.maps.LatLng($('#lat').val(), $('#lng').val());

			marker.setMap(null);

			marker = new daum.maps.Marker({
				map: map,
				position: coords
			});
			
			marker.setDraggable(true);

			daum.maps.event.addListener(marker, 'dragend', function() {
				$('#lat').val(marker.getPosition().getLat());
				$('#lng').val(marker.getPosition().getLng());
			});
			$('#lat').val(coords.getLat());
			$('#lng').val(coords.getLng());

			show_map(map);
			map.panTo(coords);
		}
		else{

			var address = $("#sido").val()+" "+$("#gugun").val()+" "+$("#dong").val()+" "+$("#address").val();

			var geocoder = new daum.maps.services.Geocoder();

			geocoder.addr2coord(address, function(status, result) {

				 if (status === daum.maps.services.Status.OK) {

					var coords = new daum.maps.LatLng(result.addr[0].lat, result.addr[0].lng);

					marker.setMap(null);

					marker = new daum.maps.Marker({
						map: map,
						position: coords
					});
					
					marker.setDraggable(true);

					daum.maps.event.addListener(marker, 'dragend', function() {
						$('#lat').val(marker.getPosition().getLat());
						$('#lng').val(marker.getPosition().getLng());
					});

					$('#lat').val(coords.getLat());
					$('#lng').val(coords.getLng());
					show_map(map);
					map.panTo(coords);					
				} 
			});		
		}
	});

	$("#upload_dialog").dialog({
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:400,
			height: 230,
			modal: true,
			buttons: {
				'이미지 등록': function() {
					$("#flag").val("1");
					$("#upload_form").submit();
				}
			}
	});


	$('#upload_form').ajaxForm({
		success:function(data){
			$("#flag").val("0");
			if(data == ""){
				alert("실패");
				alert(data);
			} 
			else {
				
				 CKEDITOR.instances.content.insertHtml( "<img src='"+data+"'>" );
			} 
			$('#upload_dialog').dialog("close");

		}
	});	


	/** 매물등록시에만 버튼에 따라 처리가 다르기 때문에 추가함 **/
	/* 등록에서만 이렇게 함 */
	$(".regist_btn").click(function(){
		$("#flag").val("1"); 
		$("#is_activated").val($(this).attr("is_activated"));
		if(!$('#lat').val() || !$('#lng').val()){
			$('#marker-error').html('주소선택 후 위치 검색을 클릭 해주시기 바랍니다.');
			$('#sido').focus();
			return false;
		}
		else{
			$('#marker-error').html();
		}
		$("#product_form").trigger("submit");
	});

	get_option();
	get_part();

	$("input[name='category']").change(function(){
		$("input[name='category_sub']").attr("checked",false);
		$(".category_sub").hide();
		$(".main_"+$(this).val()).show();
		if($(".main_"+$(this).val()).length < 1){
			$("input[name='category_sub']").attr("checked",false);
			$("#sub_category").hide();
		}
		else{
			$("#sub_category").show();
		}
	});

	$(".main_"+$("input[name='category']").val()).show();

	if($(".main_"+$("input[name='category']").val()).length < 1){
		$("input[name='category_sub']").attr("checked",false);
		$("#sub_category").hide();
	}
});

/* 등록한 갤러리 이미지 삭제 */
function gallery_delete(id){
	if(confirm("정말로 삭제하시겠습니까?")){
		$.get("/gallerytemp/gallery_delete/"+id+"/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				get_list();
		   } else {
				alert("삭제 실패");
		   }
		});
	}
}

function gallery_delete_admin(id){
	if(confirm("정말로 삭제하시겠습니까?")){
		$.get("/gallerytemp/gallery_delete/"+id+"/_admin/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				get_list_admin();
		   } else {
				alert("삭제 실패");
		   }
		});
	}
}

/* 등록한 갤러리 이미지 회전 */
function image_rotate(id){
	var rotating_img = $("#rotating-img-"+id);
	var rotating = rotating_img.attr("rotating-id");

	if(rotating==450) rotating = 90;

	rotating_img.animate({rotate: rotating}, 400, 'linear', function() {
		$.ajax({
			type: "get",
			dataType: "json",
			url: "/gallerytemp/change_rotate/"+id,
			cache: false
		});
	});

	rotating = parseInt(rotating) + 90;

	rotating_img.attr("rotating-id",rotating);
}

/* 등록한 갤러리 이미지 회전 */
function image_rotate_admin(id){
	var rotating_img = $("#rotating-img-admin-"+id);
	var rotating = rotating_img.attr("rotating-id");

	if(rotating==450) rotating = 90;

	rotating_img.animate({rotate: rotating}, 400, 'linear', function() {
		$.ajax({
			type: "get",
			dataType: "json",
			url: "/gallerytemp/change_rotate/"+id+"/_admin",
			cache: false
		});
	});

	rotating = parseInt(rotating) + 90;

	rotating_img.attr("rotating-id",rotating);
}

/* 등록한 갤러리 사진 설명 추가 */
function image_text(id){
	var content = $("#content-"+id).val();

	$.ajax({
		type: "post",
		url: "/gallerytemp/gallery_content_update/"+id,
		data: {
			content : content
		},
		cache: false,
		success: function(data){
			get_list();
		},
		error:function(e){
		}
	});
}

/* 템프 갤러리 */
function get_list(){
	$.getJSON("/gallerytemp/gallery_temp_json/<?php echo $type;?>/"+Math.round(new Date().getTime()),function(data){
		if(data == ""){
			$("#list").html("<div style='padding-left:10px;'><?php echo lang("msg.nodata");?></div>");
		} 
		else {
			$("#list").html("");
		}
		var str = "";
		$.each(data, function(key, val) {
			if(val["filename"]!=""){
				var first_thumb = (key==0) ? "first_thumb" : "thumb";
				var photo = val["filename"].split('.');
				var content = "";
				if(val["content"]!=null) content = val["content"];	
				str += "<li><div class=\"thumbnail "+first_thumb+"\">";
				str += "<a href='/uploads/gallery/temp/"+val["filename"]+"' class='fancy'><img id='rotating-img-"+val["id"]+"' data-id='"+val["id"]+"' rotating-id='90' src='/uploads/gallery/temp/"+photo[0]+"_thumb."+photo[1]+"?"+Math.round(new Date().getTime())+"' style='width:180px;height:180px;'></a>";
				str += "	<div style='padding:5px;'>";
				str += "		<span class='help-inline'></br>이미지설명</span>";
				str += "		<textarea id='content-"+val["id"]+"' class='form-control' style='width:170px;height:80px;'>"+content+"</textarea>";
				str += "	</div>";
				str += "	<div class=\"caption\" style='text-align:center;padding:0px;padding:5px 0px 5px 0px;'>";
				str += "		<a style='cursor:pointer;' onclick=\"image_rotate('"+val["id"]+"');\"><span class=\"glyphicon glyphicon-refresh\"></span>회전</a>";
				str += "		<a style='cursor:pointer;' onclick=\"image_text('"+val["id"]+"');\"><span class=\"glyphicon glyphicon-pencil\"></span>설명등록</a>";
				str += "		<a style='cursor:pointer;' onclick=\"gallery_delete('"+val["id"]+"');\"><i class=\"fa fa-trash\"></i>삭제</a>";
				str += "	</div>";
				str += "</div></li>";
			}
		});

		if(str!=""){
			$("#list").html(str);
			$("#list" ).sortable({
				update: function (event, ui) {
					var i=1;
					$("#list").find("img").each(function(){
						if(i==1){
							$(this).parent().parent().addClass("first_thumb");
						}
						else{
							$(this).parent().parent().removeClass("first_thumb");
						}
						$.get("/gallerytemp/gallery_sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
							
						});
						i++;
					});
				}
			});
		}
	});
}

/* 템프 갤러리 */
function get_list_admin(){
	$.getJSON("/gallerytemp/gallery_temp_json/<?php echo $type;?>/_admin/"+Math.round(new Date().getTime()),function(data){
		if(data == ""){
			$("#list_admin").html("<div style='padding-left:10px;'><?php echo lang("msg.nodata");?></div>");
		} 
		else {
			$("#list_admin").html("");
		}
		var str = "";
		$.each(data, function(key, val) {
			if(val["filename"]!=""){
				var photo = val["filename"].split('.');
				var content = "";
				if(val["content"]!=null) content = val["content"];	
				str += "<li><div class=\"thumbnail\">";
				str += "<a href='/uploads/gallery_admin/temp/"+val["filename"]+"' class='fancy'><img id='rotating-img-admin-"+val["id"]+"' data-id='"+val["id"]+"' rotating-id='90' src='/uploads/gallery_admin/temp/"+photo[0]+"_thumb."+photo[1]+"?"+Math.round(new Date().getTime())+"' style='width:180px;height:180px;'></a>";
				str += "	<div class=\"caption\" style='text-align:center;padding:0px;padding:5px 0px 5px 0px;'>";
				str += "		<a style='cursor:pointer;' onclick=\"image_rotate_admin('"+val["id"]+"');\"><span class=\"glyphicon glyphicon-refresh\"></span>회전</a>";
				str += "		<a style='cursor:pointer;' onclick=\"gallery_delete_admin('"+val["id"]+"');\"><i class=\"fa fa-trash\"></i>삭제</a>";
				str += "	</div>";
				str += "</div></li>";
			}
		});

		if(str!=""){
			$("#list_admin").html(str);
			$("#list_admin").sortable({
				update: function (event, ui) {
					var i=1;
					$("#list_admin").find("img").each(function(){
						$.get("/gallerytemp/gallery_sorting/"+$(this).attr("data-id")+"/"+i+"/_admin/"+Math.round(new Date().getTime()),function(){
							
						});
						i++;
					});
				}
			});
		}
	});
}

/**
 * 매물 종류에 따라 선택 옵션 항목을 가져와서 입력폼을 구성해 준다.
 */
function get_option(){

	if($("input[name='category']:checked").val()!=""){

		$.getJSON("/admincategory/get_option_json/"+$("input[name='category']:checked").val()+"/"+Math.round(new Date().getTime()),function(data){
			var option_count = 0;
			var str = "<div class=\"btn-group\" data-toggle=\"buttons\">";
			$.each(data, function(key, val) {
				if(val!=""){
					str = str + "<label class=\"btn btn-default\">";
					str = str + "	<input type=\"checkbox\" name=\"option[]\" value='"+val+"'> " + val;
					str = str + "</label>";
					option_count ++;
				}			
									
			});
			str = str + "</div>";
			if(option_count >= 1){
				$("#option").html(str);
			}
			else{
				$("#option").parent().parent().remove();
			}
		});

		$.getJSON("/admincategory/get_input_json/"+$("input[name='category']:checked").val()+"/"+Math.round(new Date().getTime()),function(data){
			var str = "";
			$.each(data, function(key, val) {

				if(val!=""){
					str += "<div class=\"form-group\">";
					str += "	<div class=\"col-md-2 control-label\">"+val+"</div>";
					str += "	<div class=\"col-md-10\">";
					str += "		<input type=\"text\" name=\"etc[]\" class=\"form-control\"/>";
					str += "	</div>";
					str += "</div>";
				}
			});
			$("#add_section_item").html(str);
			if(str!=""){
				$("#add_section").show();
			} else {
				$("#add_section").hide();
			}
		});

		/** 폼 조정 **/

		$.getJSON("/category/get_form_json/"+$("input[name='category']:checked").val()+"/"+Math.round(new Date().getTime()),function(data){
			$.each(data, function(key, val) {
				if(key=="default_type") {
					if(val==""){
						$("input:radio[name='type'][value='sell']").attr("checked",true);
						
						$("input:radio[name='type'][value='sell']").parent().addClass('active').not(this).removeClass('active');
					} else {
						$("input:radio[name='type'][value='"+val+"']").attr("checked",true);

					}

					/* button group의 값이 변경되면 상태를 변경하기 위하여 */
					$(".btn-group").find(':input:checked').parent('.btn').addClass('active');
					$(".btn-group").find(':input:not(:checked)').parent('.btn').removeClass('active');
				}
				if(key=="danzi"){
					display_form("danzi_name", val);
					display_form("danzi_id", val);
					if(val=="2"){
					}else{
						display_form("t_interimpayments", val);
						display_form("t_premium", val);

						/* alert("'"+val+"'");*/
					}

				}
				if(key=="dongho") 			display_form("dongho", val);
				if(key=="lease_price") 			display_form("lease_price_section", val);
				if(key=="premium_price") 		display_form("premium_price_section", val);
				if(key=="mgr_price") 			display_form("mgr_price_section", val);
				if(key=="mgr_price_full_rent"){
					$("#mgr_price_full_rent").attr("data-use",val);
					if(val=="1"){						
						$("#mgr_price").attr("title","<?php echo lang('product.price.rent');?>(월세)");
						$("#mgr_price").attr("placeholder","<?php echo lang('product.price.rent');?>(월세)");
					}
					display_form("mgr_price_full_rent_section", val);
				}
				if(key=="monthly_rent_deposit_min") 	display_form("monthly_rent_deposit_min_section", val);
				if(key=="bedcnt") 			display_form("bedcnt", val);
				if(key=="bathcnt") 			display_form("bathcnt", val);
				if(key=="real_area") 			display_form("real_area_section", val);
				if(key=="law_area") 			display_form("law_area_section", val);
				if(key=="store_name") 			display_form("store_name", val);
				if(key=="store_category")		select_form("store_category_section","store_category", val);
				if(key=="profit") 			display_form("profit", val);
				if(key=="gongsil_status")		select_form("gongsil_status_section","gongsil_status", val);
				if(key=="gongsil_see")			select_form("gongsil_see_section","gongsil_see", val);
				if(key=="gongsil_contact") 		display_form("gongsil_contact", val);
				if(key=="enter_year") 			select_tag("enter_year_section", "enter_year", val);
				if(key=="build_year") 			display_form("build_year", val);
				if(key=="ground") 			display_form("ground_section", val);
				if(key=="land_area") 			display_form("land_area_section", val);
				if(key=="factory") 			display_form("factory_section", val);
				if(key=="current_floor") 			select_tag("current_floor_section", "current_floor", val);
				if(key=="total_floor") 			display_form("total_floor", val);
				if(key=="road_area") 			display_form("road_area_section", val);
				if(key=="vr") 				display_form("vr_section", val);
				if(key=="video_url")			display_form("video_url_section", val);
				if(key=="heating")  			select_form("heating_section","heating", val);
				if(key=="park") 			display_form("park_section", val);
				if(key=="ground_use")  			select_form("ground_use","ground_use", val);
				if(key=="ground_aim")  			select_form("ground_aim","ground_aim", val);
				if(key=="factory_hoist") 			select_form("factory_hoist","factory_hoist", val);
				if(key=="factory_use") 			select_form("factory_use","factory_use", val);
				if(key=="road_conditions")  		select_form("road_conditions_section","road_conditions", val);
				if(key=="extension")  			select_form("extension_section","extension", val);
				if(key=="loan")  				select_form("loan_section","loan", val);
				
				if(key=="default_part")  {
					if(val=="Y"){
						$("input:radio[name='part'][value='Y']").attr("checked",true);
						$("input:radio[name='part'][value='N']").attr("checked",false);
					} else {
						$("input:radio[name='part'][value='Y']").attr("checked",false);
						$("input:radio[name='part'][value='N']").attr("checked",true);
					}
				}
			});

			/* foreach가 끝난 후에 동작해야 함 */
			get_part();
			show_price_area();
		});	

	} else {
		$("#option").html("매물 종류를 먼저 선택해 주세요.");
		$("#add_section").hide();
	}
}

/**
 * 선택시에는 입력은 첫 항목을 선택하고 수정일 때에는 data-id값으로 선택한다. 
 */
function select_form(section, item, value){
	if(value!=""){
		var v = value.split(",");
		$("#"+item).empty();
		$("#"+item).append("<option value=''>- 입력안함 -</option>");
		for(i=0;i<v.length;i++)  {
			if(i==0) {
				$("#"+item).append("<option value='"+v[i]+"' selected>"+v[i]+"</option>");
			} else {
				$("#"+item).append("<option value='"+v[i]+"'>"+v[i]+"</option>");
			}
		}
		$("#"+section).removeClass("display-none");
	} else {
		$("#"+section).addClass("display-none");
	}
}