<script>
$(document).ready(function(){

	var map = new google.maps.Map( document.getElementById("gmap"),  {
		center: new google.maps.LatLng(0,0),
		zoom: 3,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		panControl: false,
		streetViewControl: false,
		mapTypeControl: false
	});

	$("#add_coord").click(function(){
		var geocoder = new google.maps.Geocoder(); 
		geocoder.geocode({
				address : $("#add_address").val(),
				region: 'no' 
			},
		    function(results, status) {
		    	if (status.toLowerCase() == 'ok') {
					
					var coords = new google.maps.LatLng(
						results[0]['geometry']['location'].lat(),
						results[0]['geometry']['location'].lng()
					);

					$('#add_lat').val(coords.lat());
					$('#add_lng').val(coords.lng());
		    	}
				else{
					alert('해당하는 위치가 없습니다.');
				}
			}
		);
	});

	$("#add_form").validate({  
        errorElement: "span",
        wrapper: "span", 
		rules: {
			name: {  
				required: true,  
				minlength: 2
			},
			lat: {
				required: true
			},
			lng: {
				required: true
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "종류명은 최소 2자리 이상입니다"
			},
			lat: {  
				required: "위치 검색을 클릭 해주세요"
			},
			lng: {  
				required: "위치 검색을 클릭 해주세요"
			}				
		} 
	});  


	$("#edit_dialog").dialog({
			title: "지도위치 바로가기 정보 수정",
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:650,
			height: 450,
			modal: true,
			buttons: {
				'취소': function() {
					$(this).dialog("close");
				},
				'등록': function(){
					$("#edit_form").submit();
				}
			}
	});

	$("#delete_dialog").dialog({
			title: "종류 정보 삭제",
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:450,
			height: 220,
			modal: true,
			buttons: {
				'취소': function() {
					$(this).dialog("close");
				},
				'변경 후 삭제': function(){
					$("#delete_form").submit();
				}
			}
	});

});

function edit(id,form){
	$.getJSON("/adminspot/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			$("#"+form).find("#"+key).val(val);
		});

		$('#edit_dialog').dialog("open");	
	});
}

/**
 * 삭제시 등록된 정보를 다른 값으로 변경하는 기능을 추가하여야 한다.
 */
function data_delete(id){
	if(confirm("삭제하시겠습니까?")){
		location.href="/adminspot/delete_action/"+id;
	}
}
</script>	
<div id="gmap" style="width:0px; height:0px;"></div>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			지도좌표 바로가기<small>관리</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					지도위치 바로가기 관리
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<div class="help-block">* 대학교, 공공기관 등 홈에서 방문자가 바로 이동할 수 있는 기관 링크</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center"><?php echo lang("site.name");?></th>
					<th class="text-center"><?php echo lang("site.address");?></th>
					<th class="text-center" style="width:50px;"><?php echo lang("site.delete");?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(count($query)<1){
					echo "<tr><td class='text-center' colspan='3'>".lang("msg.nodata")."</td></tr>";
				}
				foreach($query as $val){?>
				<tr>
					<td><a href="#" onclick="edit('<?php echo $val->id;?>','edit_form');"><?php echo $val->name;?></a></td>
					<td><?php echo $val->address;?></td>
					<td class="text-center"><button class="btn btn-xs btn-danger" onclick="data_delete('<?php echo $val->id;?>','<?php echo $val->name;?>');" style="margin:0"><i class="fa fa-trash-o"></i></button></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	<div class="col-lg-6">
		<div class="portlet">
			<?php echo form_open("adminspot/add_action",Array("id"=>"add_form"))?>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th class="text-center vertical-middle">이름*</th>
						<td>
							<input type="text" class="form-control" name="name" placeholder="이름"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle"><?php echo lang("site.address");?>*</th>
						<td>
							<div class="help-block">* 정확한 기관명이나 검색서비스를 이용해 주소를 찾아 입력해 주세요.(<a href="http://www.naver.com/" target="blank">네이버</a>)</div>
							<input type="text" class="form-control" id="add_address" name="address" placeholder="<?php echo lang("site.address");?>" style="margin-bottom:5px;"/>
							<button id="add_coord" type="button" class="btn btn-warning">위치 검색</button>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">위도*</th>
						<td>
							<input type="text" class="form-control" id="add_lat" name="lat" placeholder="위도" readonly/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">경도*</th>
						<td>
							<input type="text" class="form-control" id="add_lng" name="lng" placeholder="경도" readonly/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">설명(옵션)</th>
						<td>
							<textarea class="form-control" name="content" rows="5"></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="text-center">
				<button type="submit" class="btn btn-primary">위치 등록</button>
			</div>
			<?php echo form_close();?>
		</div>
	</div>
</div>

<div id="edit_dialog" title="지도위치 바로가기 정보 수정" style="display:none;">
<?php echo form_open("adminspot/edit_action",Array("id"=>"edit_form"))?>
	<input type="hidden" id="id" name="id">
	<table class="table table-bordered table-striped table-condensed flip-content">
		<tr>
			<th>이름*</th>
			<td><input type="text" class="form-control" id="name" name="name" placeholder="이름"></td>
		</tr>
		<tr>
			<th><?php echo lang("site.address");?>*</th>
			<td><input type="text" class="form-control" id="address" name="address" placeholder="주소"></td>
		</tr>
		<tr>
			<th>위도*</th>
			<td><input type="text" class="form-control" id="lat" name="lat" placeholder="위도"></td>
		</tr>
		<tr>
			<th>경도*</th>
			<td><input type="text" class="form-control" id="lng" name="lng" placeholder="경도"></td>
		</tr>
		<tr>
			<th>설명(옵션)</th>
			<td><textarea class="form-control" id="content" name="content" rows="5"></textarea></td>
		</tr>
	</table>
<?php echo form_close();?>
</div>
