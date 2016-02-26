<link type="text/css" href="/assets/plugin/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
<script src="/assets/plugin/jquery.rotate.js"></script>
<script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=<?php echo $config->DAUM_MAP_KEY;?>&libraries=services"></script>
<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/locales/bootstrap-datepicker.kr.js"></script>
<script>
var flag = 1;

/** 초기 주소를 사용하려면 flag값을 0으로 세팅해야 한다. **/
<?php if($config->INIT_DONG!="") {?>
	flag = 0;
<?php } ?>

$(document).ready(function(){
	
	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	if($.datepicker){
		$('.date-picker').datepicker({
			format: "yyyy-mm-dd",
			orientation: "left",
			language: "kr",
			autoclose: true
		});
	}

	<?php
		if(!MobileCheck()){
	?>

			var uploader = new plupload.Uploader({
				runtimes : 'html5,flash,silverlight,html4',
				browse_button : 'browse',

				url: "/admingalleryinstallationtemp/upload_action/",
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
			
			var uploader_pyeong = new plupload.Uploader({
				runtimes : 'html5,flash,silverlight,html4',
				browse_button : 'browse_pyeong',

				url: "/adminpyeongtemp/upload_action/",
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
						uploader_pyeong.start();
						$("#list_pyeong").html("<div style='text-align:center;vertical-align:middle;'><img src='/assets/common/img/ajax-loader.gif'></div>");
					},
					UploadComplete: function(up, file) {
						get_list_pyeong();
					},
					Error: function(up, err) {
						$("#console_pyeong").html("\에러 #" + err.code + ": " + err.message);
					}
				}
			});	

			uploader_pyeong.init();
	<?php
		} else {	
	?>
			$("input[name='file']").change(function(e) {

				var formData = new FormData($("#installation_form"));
				formData.append("file", $(this)[0].files[0]);

				e.stopPropagation();
				e.preventDefault();

				$.ajax({
					url: "/admingalleryinstallationtemp/upload_action",
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

			$("input[name='file_pyeong']").change(function(e) {

				var formData = new FormData($("#installation_form"));
				formData.append("file_pyeong", $(this)[0].files[0]);

				e.stopPropagation();
				e.preventDefault();

				$.ajax({
					url: "/adminpyeongtemp/upload_action",
					type: "post",
					data: formData,
					processData: false,
					contentType: false,
					dataType: "json",
					success: function(data){
						get_list_pyeong();
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

	$('.help').tooltip(); 

	
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

		var geocoder = new google.maps.Geocoder();

		var address = $("#sido option:selected").text()+" "+$("#gugun option:selected").text()+" "+$("#dong option:selected").text()+" "+$("#address").val();

		var geocoder = new daum.maps.services.Geocoder();

		geocoder.addr2coord(address, function(status, result) {

			 if (status === daum.maps.services.Status.OK) {

				var coords = new daum.maps.LatLng(result.addr[0].lat, result.addr[0].lng);

				marker.setMap(null);

				marker = new daum.maps.Marker({
					map: map,
					position: coords
				});
				
				map.panTo(coords);
				marker.setDraggable(true);

				daum.maps.event.addListener(marker, 'dragend', function() {
					$('#lat').val(marker.getPosition().getLat());
					$('#lng').val(marker.getPosition().getLng());
				});

				$('#lat').val(coords.getLat());
				$('#lng').val(coords.getLng());
			} 
		});
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

	$(".regist_btn").click(function(){
		$("#flag").val("1"); 
		$("#is_activated").val($(this).attr("is_activated"));
		if(!$('#lat').val() || !$('#lng').val()){
			$('#marker-error').html('주소선택 후 위치 검색버튼을 클릭 해주시기 바랍니다.');
			$('#sido').focus();
			return false;
		}
		else{
			$('#marker-error').html();
		}
		$("#installation_form").trigger("submit");
	});

	$("#installation_form").validate({  
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
				minlength: 3
			},
			category: {
				required: true
			}, 
			member_id: {
				required: true				
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
			},
			category: {
				required: "<?php echo lang("form.required");?>"
			},
			member_id: {
				required: "<?php echo lang("form.required");?>"
			}
		}
	});

	var schedule_index = 0;
	$("#add_schedule").click(function(e){
		e.preventDefault();
		schedule_index ++;
		var add_html = '';
			add_html += '<div class="schedule_info">';
			add_html += '	<div class="form-group">';
			add_html += '		<select name=\"schedule_name['+schedule_index+']\" class=\"form-control input-inline\" autocomplete=\"off\">';
			add_html += '			<option value=\"입주자모집공고\">입주자모집공고</option>';
			add_html += '			<option value=\"청약접수 1순위\">청약접수 1순위</option>';
			add_html += '			<option value=\"청약접수 2순위\">청약접수 2순위</option>';
			add_html += '			<option value=\"당첨자발표\">당첨자발표</option>';
			add_html += '			<option value=\"당첨자계약\">당첨자계약</option>';
			add_html += '			<option value=\"기타\">기타</option>';
			add_html += '		</select>';
			add_html += '		<input type="text" name="schedule_date['+schedule_index+']"  class="form-control input-inline date-picker input-small" placeholder="일자" autocomplete="off"/>';
			add_html += '		<input type="text" name="schedule_description['+schedule_index+']"  class="form-control input-inline input-large" placeholder="일정설명" autocomplete="off"/>';
			add_html += '		<button type="button" class="btn red" onclick="schedule_delete(this)"><i class="fa fa-minus"></i></button>';
			add_html += '	</div>';
			add_html += '</div>';

		$("#add_schedule_section").append(add_html);
		$("#add_schedule_section .schedule_info:last-child").hide().slideDown();

		$('.date-picker').datepicker({
			format: "yyyy-mm-dd",
			orientation: "left",
			language: "kr",
			autoclose: true
		});
	});

	get_sido();
});

function get_sido(){
	$.getJSON("/address/get_sido/admin/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>시도 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["sido"]+"'>"+val["sido"]+"</option>";
		});

		$("#sido").html(str);
		if(flag==0) {
			$("#sido").val($("#current_sido").val());
			get_gugun($("#current_sido").val());
		}

		$("#sido").change(function(){
			$("#dong").html("<option value=''>읍면동 선택</option>");
			get_gugun(this.value);
		});
	});
}


function get_gugun(sido){
	$.getJSON("/address/get_gugun/admin/"+encodeURI(sido)+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>구군 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["parent_id"]+"'>"+val["gugun"]+"</option>";
		});
		$("#gugun").html(str);
		
		if(flag==0) {
			$("#gugun").val($("#current_gugun").val());
			get_dong($("#current_gugun").val());
		}
		
		$("#gugun").change(function(){
			get_dong(this.value);
		});
	});
}

function get_dong(parent_id){
	$.getJSON("/address/get_dong/admin/"+parent_id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>읍면동 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["id"]+"'>"+val["dong"]+"</option>";
		});

		$("#dong").html(str);
		
		if(flag==0) {
			$("#dong").val($("#current_dong").val());
			get_address($("#current_dong").val());
			flag=1;
		}

		$("#dong").change(function(){
			get_address(this.value);
		});
	});
}

function get_address(id){
	$("#address_id").val(id);
}

function get_list(){
	$.getJSON("/admingalleryinstallationtemp/gallery_temp_json/"+Math.round(new Date().getTime()),function(data){
		if(data == ""){
			$("#list").html("<div style='padding-left:10px;'>등록된 이미지가 없습니다.</div>");
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
				str += "<a href='/uploads/gallery_installation/temp/"+val["filename"]+"' class='fancy'><img id='rotating-img-"+val["id"]+"' data-id='"+val["id"]+"' rotating-id='90' src='/uploads/gallery_installation/temp/"+photo[0]+"_thumb."+photo[1]+"?"+Math.round(new Date().getTime())+"' style='width:180px;height:180px;'></a>";
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
						$.get("/admingalleryinstallationtemp/gallery_sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
							
						});
						i++;
					});
				}
			});
		}
	});
}

function get_list_pyeong(){
	$.getJSON("/adminpyeongtemp/pyeong_temp_json/"+Math.round(new Date().getTime()),function(data){
		if(data == ""){
			$("#list_pyeong").html("<div style='padding-left:10px;'>등록된 이미지가 없습니다.</div>");
		} 
		else {
			$("#list_pyeong").html("");
		}
		var str = '';
		$.each(data, function(key, val) {
				
			var pyeong_name			= (val["name"]!=null) ? val["name"] : "";
			var pyeong_presale_date = (val["presale_date"]!=null) ? val["presale_date"] : "";
			var pyeong_price_min	= (val["price_min"]!=null && val["price_min"]!=0) ? val["price_min"] : "";
			var pyeong_price_max	= (val["price_max"]!=null && val["price_max"]!=0) ? val["price_max"] : "";
			var pyeong_tax			= (val["tax"]!=null && val["tax"]!=0) ? val["tax"] : "";
			var pyeong_real_area	= (val["real_area"]!=null && val["real_area"]!=0) ? val["real_area"] : "";
			var pyeong_law_area		= (val["law_area"]!=null && val["law_area"]!=0) ? val["law_area"] : "";
			var pyeong_road_area	= (val["road_area"]!=null && val["road_area"]!=0) ? val["road_area"] : "";
			var pyeong_gate			= (val["gate"]!=null) ? val["gate"] : "";
			var pyeong_cnt			= (val["cnt"]!=null && val["cnt"]!=0) ? val["cnt"] : "";
			var pyeong_bedcnt		= (val["bedcnt"]!=null && val["bedcnt"]!=0) ? val["bedcnt"] : "";
			var pyeong_bathcnt		= (val["bathcnt"]!=null && val["bathcnt"]!=0) ? val["bathcnt"] : "";
			var pyeong_description	= (val["description"]!=null) ? val["description"] : "";

			str += '<li>';
			str += '	<input type="hidden" name="pyeong_id['+key+']" value="'+val["id"]+'"/>';
			str += '	<div class="thumbnail">';
			if(val["filename"]!=""){
				var photo = val["filename"].split('.');
				str += '	<a href="/uploads/pyeong/temp/'+val["filename"]+'" class="fancy"><img src="/uploads/pyeong/temp/'+photo[0]+'_thumb.'+photo[1]+'?'+Math.round(new Date().getTime())+'" data-id="'+val["id"]+'" style="width:180px;height:100px;"></a>';				
			}
			else{
				str += '	<a href="/assets/common/img/no.png" class="fancy"><img src="/assets/common/img/no_thumb.png?'+Math.round(new Date().getTime())+'" data-id="'+val["id"]+'" style="width:180px;height:100px;"></a>';				
			}
			str += '		<div>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_name['+key+']" value="'+pyeong_name+'" placeholder="평형이름"  title="평형이름"/> ㎡<br/>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_presale_date['+key+']" value="'+pyeong_presale_date+'" placeholder="전매기간" title="전매기간" /><br/>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_price_min['+key+']" value="'+pyeong_price_min+'" placeholder="분양최소가" title="분양최소가"/> 만원<br/>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_price_max['+key+']" value="'+pyeong_price_max+'" placeholder="분양최대가" title="분양최대가"/> 만원<br/>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_tax['+key+']" value="'+pyeong_tax+'" placeholder="취득세" title="취득세"/> 만원<br/>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_real_area['+key+']" value="'+pyeong_real_area+'" placeholder="전용면적" title="전용면적"/> ㎡<br/>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_law_area['+key+']" value="'+pyeong_law_area+'" placeholder="공급면적" title="공급면적"/> ㎡<br/>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_road_area['+key+']" value="'+pyeong_road_area+'" placeholder="대지지분" title="대지지분"/> ㎡<br/>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_gate['+key+']" value="'+pyeong_gate+'" placeholder="현관" title="현관"/><br/>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_cnt['+key+']" value="'+pyeong_cnt+'" placeholder="분양세대수" title="분양세대수"/> 세대<br/>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_bedcnt['+key+']" value="'+pyeong_bedcnt+'" placeholder="방"  title="방"/> 실<br/>';
			str += '			<input type="text" class="form-control input-small inline" name="pyeong_bathcnt['+key+']" value="'+pyeong_bathcnt+'" placeholder="욕실" title="욕실"/> 실<br/>';
			str += '			<textarea class="form-control" name="pyeong_description['+key+']" rows="3" maxlength="100" placeholder="평형설명" title="평형설명">'+pyeong_description+'</textarea><br/>';
			str += '		</div>';
			str += '		<div class="caption" style="text-align:center;padding:0px;padding:5px 0px 5px 0px;">';
			str += '			<a style="cursor:pointer;" onclick="pyeong_save('+key+');"><span class="glyphicon glyphicon-pencil"></span>내용등록</a>';
			str += '			<a style="cursor:pointer;" onclick="pyeong_delete('+val["id"]+');"><i class="fa fa-trash"></i>삭제</a>';
			str += '		</div>';
			str += '	</div>';
			str += '</li>';

		});

		if(str!=""){
			$("#list_pyeong").html(str);
			$("#list_pyeong" ).sortable({
				update: function (event, ui) {
					var i=1;
					$("#list_pyeong").find("img").each(function(){
						$.get("/adminpyeongtemp/pyeong_sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
							
						});
						i++;
					});
				}
			});
		}
	});
}

function gallery_delete(id){
	if(confirm("정말로 삭제하시겠습니까?")){
		$.get("/admingalleryinstallationtemp/gallery_delete/"+id+"/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				get_list();
		   } else {
				alert("삭제 실패");
		   }
		});
	}
}

function pyeong_delete(id){
	if(confirm("정말로 삭제하시겠습니까?")){
		$.get("/adminpyeongtemp/pyeong_delete/"+id+"/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				get_list_pyeong();
		   } else {
				alert("삭제 실패");
		   }
		});
	}
}

function image_rotate(id){
	var rotating_img = $("#rotating-img-"+id);
	var rotating = rotating_img.attr("rotating-id");

	if(rotating==450) rotating = 90;

	rotating_img.animate({rotate: rotating}, 400, 'linear', function() {
		$.ajax({
			type: "get",
			dataType: "json",
			url: "/admingalleryinstallationtemp/change_rotate/"+id,
			cache: false,
			success: function(data){
			},
			error:function(e){
			}
		});
	});

	rotating = parseInt(rotating) + 90;

	rotating_img.attr("rotating-id",rotating);

}

function image_text(id){
	var content = $("#content-"+id).val();

	$.ajax({
		type: "post",
		url: "/admingalleryinstallationtemp/gallery_content_update/"+id,
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

function pyeong_save(key){

	$.ajax({
		type: "post",
		url: "/adminpyeongtemp/pyeong_update",
		data: {
			pyeong_id : $("input[name='pyeong_id["+key+"]']").val(),
			pyeong_name : $("input[name='pyeong_name["+key+"]']").val(),
			pyeong_presale_date : $("input[name='pyeong_presale_date["+key+"]']").val(),
			pyeong_price_min : $("input[name='pyeong_price_min["+key+"]']").val(),
			pyeong_price_max : $("input[name='pyeong_price_max["+key+"]']").val(),
			pyeong_tax : $("input[name='pyeong_tax["+key+"]']").val(),
			pyeong_real_area : $("input[name='pyeong_real_area["+key+"]']").val(),
			pyeong_law_area : $("input[name='pyeong_law_area["+key+"]']").val(),
			pyeong_road_area : $("input[name='pyeong_road_area["+key+"]']").val(),
			pyeong_gate : $("input[name='pyeong_gate["+key+"]']").val(),
			pyeong_cnt : $("input[name='pyeong_cnt["+key+"]']").val(),
			pyeong_bedcnt : $("input[name='pyeong_bedcnt["+key+"]']").val(),
			pyeong_bathcnt : $("input[name='pyeong_bathcnt["+key+"]']").val(),
			pyeong_description : $("textarea[name='pyeong_description["+key+"]']").val()
		},
		cache: false,
		success: function(data){
			get_list_pyeong();
		},
		error:function(e){
		}
	});
}

function schedule_delete(element){
	$(element).parent().parent('div').slideUp(function() {
		$(this).remove();
	});	
}

function no_image_insert(){

	$.ajax({
		url: "/adminpyeongtemp/no_image_action",
		type: "post",
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(data){
			get_list_pyeong();
		},
		error:function(e){
		}
	});
}
</script>

<input type="hidden" id="current_sido" value="<?php echo $config->INIT_SIDO;?>"/>
<input type="hidden" id="current_gugun" value="<?php echo $config->INIT_GUGUN;?>"/>
<input type="hidden" id="current_dong" value="<?php echo $config->INIT_DONG;?>"/>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				<?php echo lang("installation");?> 관리<small>등록</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i> 
					<a href="/adminhome/index"><?php echo lang("menu.home");?></a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="/admininstallation/index"><?php echo lang("installation");?> 관리</a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					등록
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="history.go(-1);"><?php echo lang("site.back");?></button>
			</div>
		</div>
	</div>
</div>

<div class="help-block text-right">
	
</div>	

<div class="note note-success">
	<p>
		<i class="fa fa-lock"></i> 표시가 있는 항목은 홈페이지 방문자가 볼 수 없는 <u>비공개 정보</u>입니다.
		<span class="required" aria-required="true" style="color:red;"> * </span> 표시는 <u>필수입력항목</u>입니다.
	</p>
</div>

<?php echo form_open_multipart("admininstallation/add_action","id='installation_form' class='installation form-horizontal'");?>
<input type="hidden" id="flag" value="0"/>
<input type="hidden" id="is_activated" name="is_activated" value="0"/>

<div class="portlet box green">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-map-marker"></i> 위치
		</div>
		<div class="tools">
			<?php 
			if($config->SHOW_ADDRESS){
				echo lang("ment.address.open");
			} else { 
				echo lang("ment.address.close");				
			}
			?>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("site.location");?><span class="required" aria-required="true"> * </span></label>
				<div class="col-md-9">
					<input type="hidden" id="address_id" name="address_id"/>
					<input type="hidden" id="sido_text"/>
					<input type="hidden" id="gugun_text"/>
					<input type="hidden" id="dong_text"/>					
					<select id="sido" name="sido" class="form-control input-inline input-small select2me"></select>
					<select id="gugun" name="gugun" class="form-control input-inline input-small select2me"><option value="">- 구군 -</option></select>
					<select id="dong" name="dong" class="form-control input-inline input-small select2me"><option value="">- 읍면동 -</option></select>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-md-3 control-label">상세주소 </label>
				<div class="col-md-9">
					<input type="text"  id="address" name="address" class="form-control input-inline input-small" placeholder="번지" autocomplete="off"/>
				</div>
			</div>				
			<div class="form-group">
				<label class="col-md-3 control-label">위치 <span class="required" aria-required="true"> * </span></label>
				<div class="col-md-9">
					<input type="hidden" id="lat" name="lat" class="form-control"/> 
					<input type="hidden" id="lng" name="lng" class="form-control"/>
					<button type="button" id="get_coord" class="btn btn-primary help" data-toggle="tooltip" title="상세 주소 입력후 실행하여 위치가 입력되어야 지도에 표시">위치 검색</button>
					<span class="help-inline">마커를 마우스로 이동할 수 있습니다.</span>
					<span class="error" id="marker-error" style="display:inline;"></span>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-9">
					<div id="gmap"></div>
				</div>
			</div>		
		</div> <!-- form-body -->
	</div> <!-- portlet-body -->
</div><!-- portlet -->

<div class="portlet box red">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-info-circle"></i> <?php echo lang("installation");?> 정보
		</div>
		<div class="tools" id="installation_info"></div>
	</div>

	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("installation");?> <?php echo lang("product.category");?><span class="required" aria-required="true"> * </span></label>
				<div class="col-md-9">
					<select id="category" name="category" class="form-control input-inline input-small select2me help">
						<option value=""><?php echo lang("installation");?><?php echo lang("product.category");?> 선택</option>
						<option value="apt">아파트</option>
						<option value="villa">빌라</option>
						<option value="officetel">오피스텔</option>
						<option value="city">도시형생활주택</option>
						<option value="shop">상가</option>
					</select>

					<select name="status" class="form-control input-inline input-small select2me help">
						<option value="plan">분양 계획중</option>
						<option value="go" selected>분양 진행중</option>
						<option value="end">분양 종료</option>
					</select>
				</div>
			</div>			
			<div class="form-group">
				<label class="col-md-3 control-label">규모</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="scale" placeholder="총 OO세대(아파트: OO세대, 오피스텔: OO세대, 상가: OO실)"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">공고/입주시기</label>
				<div class="col-md-9">
					<input type="text" class="form-control input-inline input-medium" name="notice_year" placeholder="공고시기(예: 2014년 2월 )" title="공고시기"/>
					<input type="text" class="form-control input-inline input-medium" name="enter_year" placeholder="입주시기(예: 2014년 2월 )" title="입주시기"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("site.contact");?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="tel" placeholder="문의 전화번호 기재(입력하지 않으면 표시되지 않습니다.)"/>
				</div>
			</div>				
			<div class="form-group">
				<label class="col-md-3 control-label">건설사</label>
				<div class="col-md-9">
					<input type="text" class="form-control input-inline" name="builder" placeholder="건설사 이름"/>
					<input type="text" class="form-control input-inline input-large" name="builder_url" placeholder="건설사 홈페이지  (예: www.samsung.com )"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">난방</label>
				<div class="col-md-9">
					<select id="heating" name="heating" class="form-control input-inline input-small help select2me">
						<option value="">- 입력안함 -</option>
						<?php
							$heating = explode(",",$config->USE_HEATING);
							foreach($heating as $h){
								echo "<option value='".$h."'>".$h."</option>";
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">주차</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="park" placeholder="총 몇대 / 세대당 몇대"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">청약가능통장</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="bank" placeholder="청약가능통장"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">전매가능여부</label>
				<div class="col-md-9">
					<select name="is_presale" class="form-control input-inline input-small select2me help">
						<option value="1">전매가능</option>
						<option value="0">전매제한</option>
					</select>
				</div>
			</div>
		</div> <!-- form-body -->
	</div> <!-- portlet-body -->
</div><!-- portlet -->

<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-calendar-o"></i> <?php echo lang("installation");?> 일정
		</div>
		<div class="tools">
			날짜를 입력하지 않으면 일정이 추가되지 않습니다.
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-3 control-label">일정정보</label>
				<div class="col-md-9">
					<div class="schedule_info">
						<div class="form-group">
							<select name="schedule_name[0]" class="form-control input-inline" autocomplete="off">
								<option value="입주자모집공고">입주자모집공고</option>
								<option value="청약접수 1순위">청약접수 1순위</option>
								<option value="청약접수 2순위">청약접수 2순위</option>
								<option value="당첨자발표">당첨자발표</option>
								<option value="당첨자계약">당첨자계약</option>
								<option value="기타">기타</option>
							</select>
							<input type="text" name="schedule_date[0]"  class="form-control input-inline input-small date-picker" placeholder="일자" autocomplete="off"/>
							<input type="text" name="schedule_description[0]"  class="form-control input-inline input-large" placeholder="일정설명" autocomplete="off"/>
							<button type="button" id="add_schedule" class="btn blue"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div id="add_schedule_section" class="margin-top-10"></div>
				</div>
			</div>
		</div> <!-- form-body -->
	</div> <!-- portlet-body -->
</div><!-- portlet -->

<div class="portlet box blue-steel">
	<div class="portlet-title">
		<div class="caption">
			<?php echo lang("installation");?> 평형정보
		</div>
		<div class="tools">
			<?php echo lang("installation");?>의 평형에 대한 정보를 입력합니다.
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
		<?php
		if(MobileCheck()){?>
			<div class="form-group">
				<label class="col-md-3 control-label">평형사진</label>
				<div class="col-md-9">
					<span class="btn btn-primary btn-file">
					사진 촬영 또는 업로드<input type="file" name="file" accept="image/*; capture=camera/picture"/><br/>
					</span>
					<div class="help-inline">* 사진을 마우스로 옮기시면 순서가 변경이 됩니다, 저장을 눌러야 내용이 저장됩니다</div>
					<pre id="console" style="display:none;"></pre>
					<ul class="row" id="list_pyeong"></ul>
				</div>
			</div>
		<?php } else {?>
			<div class="form-group">
				<label class="col-md-3 control-label">사진</label>
				<div class="col-md-9">
					<button type="button" id="browse_pyeong" class="btn btn-primary"><i class="fa fa-file-image-o"></i> 멀티 파일 선택</button>
					<button type="button" class="btn btn-default" onclick="no_image_insert()">이미지 없이 정보추가</button>
					<div class="help-inline">* 사진을 마우스로 옮기시면 순서가 변경이 됩니다, 저장을 눌러야 내용이 저장됩니다</div>
					<pre id="console_pyeong" style="display:none;"></pre>
					<ul class="row" id="list_pyeong"></ul>
				</div>
			</div>
		<?php }?>
		</div> <!-- form-body -->
	</div> <!-- portlet-body -->
</div><!-- portlet -->

<div class="portlet box green">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square"></i> 설명
		</div>
		<div class="tools">
			에디터에서 이미지 버튼을 클릭하시면 사진을 업로드할 수 있습니다.
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("site.title");?> <span class="required" aria-required="true"> * </span></label>
				<div class="col-md-9">
					<div class="input-group">
						<span class="input-group-addon">추천
						<input type="checkbox" id="recommand" name="recommand"/>
						</span>
						<input type="text" name="title" class="form-control"/>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">관리메모 <i class="fa fa-lock" title="비공개"></i><br>(비공개)</label>
				<div class="col-md-9">
					<textarea class="form-control help" name="secret" data-toggle="tooltip"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">설명</label>
				<div class="col-md-9">
					<textarea name="content" class="form-control" rows="5"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">키워드</label>
				<div class="col-md-9">
					<input type="text" id="tag" name="tag" class="form-control help" placeholder=", 콤마로 구분해 주세요"  data-toggle="tooltip" title="메인사진에 3번째 키워드까지 표시되며 블로그 등록시 키워드 등록"/> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo lang("product.owner");?> <span class="required" aria-required="true"> * </span></label>
				<div class="col-md-9">
					<select name="member_id" class="form-control input-inline">
						<option value="">등록직원선택</option>
						<?php foreach($members as $val){?>
						<option value="<?php echo $val->id?>" <?php if($val->id==$this->session->userdata("admin_id")) echo "selected";?>><?php echo $val->name?> (<?php echo $val->email?>)</option>
						<?php }?>
					</select>
					<span>* 분양물건은 관리자만 등록할 수 있습니다.</span>
				</div>
			</div>
		</div> <!-- form-body -->
	</div> <!-- portlet-body -->
</div><!-- portlet -->

<div class="portlet box purple">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-picture-o"></i> 미디어
			</div>
			<div class="tools">
				사진은 자동으로 사이즈가 조정됩니다. 사진을 일부러 줄여서 업로드할 필요가 없습니다.
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<?php
				if(MobileCheck()){?>
					<div class="form-group">
						<label class="col-md-3 control-label">사진</label>
						<div class="col-md-9">
							<span class="btn btn-primary btn-file">
							사진 촬영 또는 업로드<input type="file" name="file" accept="image/*; capture=camera/picture"/><br/>
							</span>
							<div class="help-inline">* 첫번째 이미지는 대표이미지로 사용 됩니다. (사진을 마우스로 옮기시면 순서가 변경이 됩니다)</div>
							<pre id="console" style="display:none;"></pre>
							<ul class="row" id="list"></ul>
						</div>
					</div>
				<?php } else {?>
					<div class="form-group">
						<label class="col-md-3 control-label">사진</label>
						<div class="col-md-9">
							<button type="button" id="browse" class="btn btn-primary"><i class="fa fa-file-image-o"></i> 멀티 파일 선택</button>
							<div class="help-inline">* 첫번째 이미지는 대표이미지로 사용 됩니다. (사진을 마우스로 옮기시면 순서가 변경이 됩니다)</div>
							<pre id="console" style="display:none;"></pre>
							<ul class="row" id="list"></ul>
						</div>
					</div>
				<?php }?>
				<div class="form-group">
					<label class="col-md-3 control-label">유튜브 주소 <a href="http://youtu.be/Fa1te_bbb8w" target="_blank"><i class="fa fa-question-circle"></i></a></label>
					<div class="col-md-9">
						<input type="text" id="video_url" name="video_url" class="form-control help" placeholder="유튜브에서 가져온 동영상 주소"  data-toggle="tooltip" title="유투브에서 가져온 동영상 주소"/> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">입주자모집요강 </label>
					<div class="col-md-9">
						<div id="file_section" class="form-inline">
							<div class="multi-form-control-wrapper">
								<input type="file" name="application_file" class="form-control input-inline input-xlarge" placeholder="입주자모집요강파일선택" autocomplete="off" style="height:auto;"/><div class="help-inline">* 업로드가능한 파일 : doc,docx,hwp,ppt,pptx,pdf,zip,txt,jpg,png</div>
							</div>
						</div>
					</div>
				</div>					
			</div> <!-- form-body -->
			<div class="form-actions right">
				<button type="button" class="btn blue regist_btn" is_activated="1"><i class="fa fa-unlock"></i> 공개로 등록</button>
				<button type="button" class="btn red regist_btn" is_activated="0"><i class="fa fa-lock"></i> 비공개로 등록</button>
			</div>
		</div> <!-- portlet-body -->
</div><!-- portlet -->
<?php echo form_close();?>

<?php
	if(!MobileCheck()){
?>
<script>
	CKEDITOR.replace( 'content', {customConfig: '/ckeditor/dungzi_config.js'});
</script>
<?php } ?>

<div id="upload_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;">
	<?php echo form_open_multipart("admininstallation/upload_action","id='upload_form'");?>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;" autocomplete='off'/>
	<?php echo form_close();?>
</div>