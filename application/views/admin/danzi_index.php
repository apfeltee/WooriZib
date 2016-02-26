<script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=<?php echo $config->DAUM_MAP_KEY;?>&libraries=services"></script>
<script>
var search_flag = 0;
var edit_flag = 0;
$(document).ready(function(){

	$('#check_all').change(function(){
		var check_state = $(this).prop('checked');
		$("input[name='check_danzi[]']").each(function(i){
			$(this).prop('checked',check_state);
		});
	});

	$("#add_danzi_form").validate({ 
		rules: {
			name: {  
				required: true
			},
			area: {  
				required: true
			},
			lng: {  
				required: true
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>"
			},
			area: {  
				required: "<?php echo lang("form.required");?>"
			},
			lng: {  
				required: "<?php echo lang("form.required");?>"
			}
		} 
	});

	$("#edit_danzi_form").validate({ 
		rules: {
			name: {  
				required: true
			},
			area: {  
				required: true
			},
			lng: {  
				required: true
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>"
			},
			area: {  
				required: "<?php echo lang("form.required");?>"
			},
			lng: {  
				required: "<?php echo lang("form.required");?>"
			}
		} 
	});

	get_sido('search_form');
});

function get_sido(form){
	$.getJSON("/address/get_sido/full/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>시도 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["sido"]+"'>"+val["sido"]+"</option>";
		});

		$("#"+form).find("#sido").html(str);

		if(form=="edit_danzi_form" && edit_flag==0) {
			$("#"+form).find("#sido").val($("#"+form).find("#current_sido").val());
			get_gugun($("#"+form).find("#current_sido").val(),form);
		}

		if(form=="search_form" && search_flag==0) {
			$("#"+form).find("#sido").val("<?php echo $this->input->get('sido')?>");
			get_gugun("<?php echo $this->input->get('sido')?>",form);
		}

		$("#"+form).find("#sido").change(function(){
			$("#"+form).find("#dong").html("<option value=''>읍면동 선택</option>");
			get_gugun(this.value,form);
		});
	});
}


function get_gugun(sido,form){
	$.getJSON("/address/get_gugun/full/"+encodeURI(sido)+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>구군 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["parent_id"]+"'>"+val["gugun"]+"</option>";
		});
		$("#"+form).find("#gugun").html(str);

		if(form=="edit_danzi_form" && edit_flag==0) {
			$("#"+form).find("#gugun").val($("#"+form).find("#current_gugun").val());
			get_dong($("#"+form).find("#current_gugun").val(),form);
		}

		if(form=="search_form" && search_flag==0) {
			$("#"+form).find("#gugun").val("<?php echo $this->input->get('gugun')?>");
			get_dong("<?php echo $this->input->get('gugun')?>",form);
		}

		$("#"+form).find("#gugun").change(function(){
			get_dong(this.value,form);
		});
	});
}

function get_dong(parent_id,form){
	$.getJSON("/address/get_dong/full/"+parent_id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>읍면동 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["id"]+"'>"+val["dong"]+"</option>";
		});

		$("#"+form).find("#dong").html(str);

		if(form=="edit_danzi_form" && edit_flag==0) {
			$("#"+form).find("#dong").val($("#"+form).find("#current_dong").val());
			get_address($("#"+form).find("#current_dong").val(),form);
			edit_flag = 1;
		}

		if(form=="search_form" && search_flag==0) {
			$("#"+form).find("#dong").val("<?php echo $this->input->get('dong')?>");
			get_address("<?php echo $this->input->get('dong')?>",form);
			search_flag = 1;
		}

		$("#"+form).find("#dong").change(function(){
			get_address(this.value,form);
		});
	});
}

function get_address(id,form){
	$("#"+form).find("#address_id").val(id);
}

function edit_danzi(id,form){
	edit_flag = 0;
	$.getJSON("/admindanzi/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			if(key=="pyeong_img"){
				if(val!="") $("#"+form).find("#"+key).attr("src","/uploads/danzi/"+val);
				else $("#"+form).find("#"+key).attr("src","/assets/common/img/no_thumb.png");
			}
			else{
				$("#"+form).find("#"+key).val(val);
			}
		});
		get_sido('edit_danzi_form');
	});
}

function get_coord(form){
	var sido = ($("#"+form).find("#sido").val()) ? $("#"+form).find("#sido option:selected").text() : "";
	var gugun = ($("#"+form).find("#gugun").val()) ? $("#"+form).find("#gugun option:selected").text() : "";
	var dong = ($("#"+form).find("#dong").val()) ? $("#"+form).find("#dong option:selected").text() : "";
	var bunzi = ($("#"+form).find("#bunzi").val()) ? $("#"+form).find("#bunzi").val() : "";
	
	if(sido=="" || gugun=="" || dong=="" || bunzi==""){
		alert("해당 지역과 번지를 모두 입력 해야합니다.");
		return false;
	}

	var address = sido+" "+gugun+" "+dong+" "+bunzi;

	var geocoder = new daum.maps.services.Geocoder();

	geocoder.addr2coord(address, function(status, result) {
		 if (status === daum.maps.services.Status.OK) {
			$("#"+form).find('#lat').val(result.addr[0].lat);
			$("#"+form).find('#lng').val(result.addr[0].lng);								
		}
		else{
			alert('번지가 올바르지 않거나, 해당하는 위치가 없습니다.');
		}
	});
}

function danzi_delete_all(){
	if(confirm("모든 단지 정보가 삭제 됩니다\n\n삭제 하시겠습니까?")){
		location.href="/admindanzi/delete_all_action/";
	}
}

function danzi_delete(){
	var check_length = $("input[name='check_danzi[]']:checked").length;
	if(!check_length){
		alert('삭제할 단지를 선택 해주시기 바랍니다.');
		return;
	}
	else{
		if(confirm('선택한 단지를 삭제 하시겠습니까?')){
			$('#danzi_delete_form').submit();
		}
	}	
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">아파트단지 설정</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="/adminhome/index"><?php echo lang("menu.home");?></a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="#">아파트단지 설정</a>
				</li>
			</ul>
			<div class="page-toolbar">
				<a href="javascript:danzi_delete_all();" class="btn red">단지 모두 삭제</a>
				<a href="javascript:danzi_delete();" class="btn btn-default">단지 삭제</a>
				<a href="#" class="btn blue" data-toggle="modal" data-target="#add_danzi_dialog" onclick="javascript:$('#add_danzi_form')[0].reset();get_sido('add_danzi_form');">단지 등록하기</a>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading"><?php echo lang("site.search");?></div>
		  <div class="panel-body">
				<!-- SEARCH FORM-->
				<?php echo form_open("admindanzi/index",Array("id"=>"search_form","class"=>"form-inline","method"=>"get"))?>
				<div class="form-group">
					<select id="sido" name="sido" class="form-control input-inline input-small"></select>
					<select id="gugun" name="gugun" class="form-control input-inline input-small"></select>
					<select id="dong" name="dong" class="form-control input-inline input-small"></select>
					<input type="text" name="keyword" class="form-control input-large input-inline" value="<?php echo $this->input->get("keyword")?>"/>
				</div>
				<button type="submit" class="btn btn-warning"><?php echo lang("site.search");?></button>
				<?php echo form_close();?>
				<!-- SEARCH FORM-->			
		  </div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="help-block">* 총 <strong><?php echo number_format($total);?></strong>건이 검색되었습니다.</div>
		<?php echo form_open_multipart("/admindanzi/delete_action",Array("id"=>"danzi_delete_form"))?>
		<table class="table table-bordered table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center"><input type='checkbox' id='check_all'/></th>
					<th class="text-center"><?php echo lang("site.location");?></th>
					<th class="text-center">단지명</th>
					<th class="text-center">번지</th>
					<th class="text-center"><?php echo lang("product.area");?>(㎡)</th>
					<th class="text-center">일반평균가</th>
					<th class="text-center">하위평균가</th>
					<th class="text-center">상위평균가</th>
					<th class="text-center">최종수정일</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!$query){?>
				<tr>
					<td class="text-center" colspan="8"><?php echo lang("msg.nodata");?></td>
				</tr>				
				<?php }?>
				<?php foreach($query as $val){?>
				<tr>
					<td class="text-center"><input type="checkbox" name="check_danzi[]" value="<?php echo $val->id;?>"></td>
					<td class="text-center"><?php echo $val->sido." ".$val->gugun." ".$val->dong;?></td>
					<td class="text-center">
						<a href="#" data-toggle="modal" data-target="#edit_danzi_dialog" onclick="edit_danzi(<?php echo $val->id;?>,'edit_danzi_form');"><?php echo $val->name;?></a>
					</td>
					<td class="text-center"><?php echo $val->bunzi;?></td>
					<td class="text-center"><?php echo $val->area;?></td>
					<td class="text-center"><?php echo number_format($val->salesprice);?>만원</td>
					<td class="text-center"><?php echo number_format($val->d_price);?>만원</td>
					<td class="text-center"><?php echo number_format($val->u_price);?>만원</td>
					<td class="text-center"><?php echo date("Y-m-d",strtotime($val->lastupdated));?></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		<?php echo form_close();?>

		<div class="row text-center">
			<div class="col-sm-12">
				<ul class="pagination" style="float:none;">
					<?php echo $pagination;?>
				</ul>
			</div>
		</div>
	</div>
</div>

<!-- DANZI ADD FORM -->
<?php echo form_open_multipart("admindanzi/add_action",Array("id"=>"add_danzi_form"))?>
<input type="hidden" id="address_id" name="address_id"/>
<div id="add_danzi_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:650px;">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">단지 등록</h4>
		</div>
		<div class="modal-body">
			<table class="table table-bordered table-striped-left table-condensed flip-content">
				<tbody>
					<tr>
						<td class="text-center vertical-middle" width="135"><?php echo lang("site.location");?></td>
						<td>
							<select id="sido" name="sido" class="form-control input-inline input-small"></select>
							<select id="gugun" name="gugun" class="form-control input-inline input-small"></select>
							<select id="dong" name="dong" class="form-control input-inline input-small"></select>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">번지</td>
						<td>
							<input type="text" class="form-control input-large inline" id="bunzi" name="bunzi" placeholder="번지"/>
							<button type="button" class="btn btn-success btn-sm" onclick="get_coord('add_danzi_form');">위치 검색</button>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">위치</td>
						<td>
							<input type="text" class="form-control input-small inline" id="lat" name="lat" placeholder="위도" readonly/>
							<input type="text" class="form-control input-small inline" id="lng" name="lng" placeholder="경도" readonly/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">단지명</td>
						<td>
							<input type="text" class="form-control input-large inline" name="name" placeholder="단지명"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle"><?php echo lang("product.area");?>(㎡)</td>
						<td>
							<input type="text" class="form-control input-large" name="area" placeholder="<?php echo lang("product.area");?>(㎡)"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">평면도 이미지</td>
						<td>
							<input type="file" class="input-large" name="pyeong_img"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">일반평균가</td>
						<td>
							<input type="text" class="form-control input-large" name="salesprice" placeholder="만원"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">하위평균가</td>
						<td>
							<input type="text" class="form-control input-large" name="d_price" placeholder="만원"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">상위평균가</td>
						<td>
							<input type="text" class="form-control input-large" name="u_price" placeholder="만원"/>
						</td>
					</tr>
				</tbody>
			</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
				<button type="submit" class="btn btn-primary">등록</button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close();?>
<!-- DANZI ADD FORM -->

<!-- DANZI UPDATE FORM -->
<?php echo form_open_multipart("admindanzi/edit_action",Array("id"=>"edit_danzi_form"))?>
<input type="hidden" id="id" name="id"/>
<input type="hidden" id="address_id" name="address_id"/>
<input type="hidden" id="current_sido"/>
<input type="hidden" id="current_gugun"/>
<input type="hidden" id="current_dong"/>
<div id="edit_danzi_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:650px;">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">단지 수정</h4>
		</div>
		<div class="modal-body">
			<table class="table table-bordered table-striped-left table-condensed flip-content">
				<tbody>
					<tr>
						<td class="text-center vertical-middle" width="135"><?php echo lang("site.location");?></td>
						<td>
							<select id="sido" name="sido" class="form-control input-inline input-small"></select>
							<select id="gugun" name="gugun" class="form-control input-inline input-small"></select>
							<select id="dong" name="dong" class="form-control input-inline input-small"></select>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">번지</td>
						<td>
							<input type="text" class="form-control input-large inline" id="bunzi" name="bunzi" placeholder="번지"/>
							<button type="button" class="btn btn-success btn-sm" onclick="get_coord('edit_danzi_form');">위치 검색</button>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">위치</td>
						<td>
							<input type="text" class="form-control input-small inline" id="lat" name="lat" placeholder="위도" readonly/>
							<input type="text" class="form-control input-small inline" id="lng" name="lng" placeholder="경도" readonly/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">단지명</td>
						<td>
							<input type="text" class="form-control input-large inline" id="name" name="name" placeholder="단지명"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle"><?php echo lang("product.area");?>(㎡)</td>
						<td>
							<input type="text" class="form-control input-large" id="area" name="area" placeholder="<?php echo lang("product.area");?>(㎡)"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">평면도 이미지</td>
						<td>
							<img id="pyeong_img" src="/assets/common/img/no_thumb.png" style="width:250px;height:250px;"/>
							<input type="file" class="input-large margin-top-10" name="pyeong_img"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">일반평균가</td>
						<td>
							<input type="text" class="form-control input-large" id="salesprice" name="salesprice" placeholder="일반평균가"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">하위평균가</td>
						<td>
							<input type="text" class="form-control input-large" id="d_price" name="d_price" placeholder="하위평균가"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">상위평균가</td>
						<td>
							<input type="text" class="form-control input-large" id="u_price" name="u_price" placeholder="상위평균가"/>
						</td>
					</tr>
				</tbody>
			</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
				<button type="submit" class="btn btn-primary">수정</button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close();?>
<!-- DANZI UPDATE FORM -->