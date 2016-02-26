<script>
$(document).ready(function(){
	$("#add_form").validate({
		rules: {
			name: {  
				required: true,
			},
			sido: {  
				required: true,
			},
			gugun: {  
				required: true,
			},
			dong: {  
				required: true,
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang('form.required');?>"
			},
			sido: {  
				required: "<?php echo lang('form.required');?>"
			},
			gugun: {  
				required: "<?php echo lang('form.required');?>"
			},
			dong: {  
				required: "<?php echo lang('form.required');?>"
			},
		} 
	});

	get_sido();
});
function get_sido(){
	$("select[name='gugun']").html("<option value=''>구군 선택</option>");
	$("select[name='dong']").html("<option value=''>읍면동 선택</option>");
	$.getJSON("/address/get_sido/full/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>시도 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["sido"]+"'>"+val["sido"]+"</option>";
		});
		$("select[name='sido']").html(str);
		$("select[name='sido']").change(function(){
			get_gugun(this.value);
		});
	});
}

function get_gugun(sido){
	$("select[name='dong']").html("<option value=''>읍면동 선택</option>");
	$.getJSON("/address/get_gugun/full/"+encodeURI(sido)+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>구군 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["parent_id"]+"'>"+val["gugun"]+"</option>";
		});
		$("select[name='gugun']").html(str);
		$("select[name='gugun']").change(function(){
			get_dong(this.value);
		});
	});
}

function get_dong(parent_id){
	$.getJSON("/address/get_dong/full/"+parent_id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>읍면동 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["id"]+"'>"+val["dong"]+"</option>";
		});
		$("select[name='dong']").html(str);
		$("select[name='dong']").change(function(){
			$("input[name='address_id']").val($(this).val());
		});
	});
}

function data_delete(obj,id){
	if(confirm("삭제 하시겠습니까?")){
		$.getJSON("/adminregion/delete_action/"+id+"/"+Math.round(new Date().getTime()),function(data){
			$(obj).parent().parent().remove();
		});	
	}
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			지역 사전 설정
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					지역 사전 설정
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<table class="table table-bordered table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center">지역명</th>
					<th class="text-center">시도</th>
					<th class="text-center">구군</th>
					<th class="text-center">읍면동</th>
					<th class="text-center" style="width:50px;"><?php echo lang("site.delete");?></th>
				</tr>
			</thead>
			<tbody id="sort_list">
				<?php
				if(count($query)<1){
					echo "<tr><td class='text-center' colspan='5'>".lang("msg.nodata")."</td></tr>";
				}
				foreach($query as $val){?>
				<tr data-id="<?php echo $val->id;?>">
					<td class="text-center"><?php echo $val->name;?></td>
					<td class="text-center"><?php echo $val->sido;?></td>
					<td class="text-center"><?php echo $val->gugun;?></td>
					<td class="text-center"><?php echo $val->dong;?></td>
					<td class="text-center"><button class="btn btn-xs btn-danger" onclick="data_delete(this,'<?php echo $val->id;?>');" style="margin:0"><i class="fa fa-trash-o"></i></button></td>
				</tr>
				<?php
				}?>
			</tbody>
		</table>
	</div>
	<div class="col-lg-6">
		<div class="portlet">
			<?php echo form_open("adminregion/add_action",Array("id"=>"add_form"))?>
			<input type="hidden" name="address_id"/>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th class="text-center vertical-middle">지역명</th>
						<td>
							<input type="text" class="form-control" name="name" placeholder="지역명"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">시도</th>
						<td>
							<select class="form-control input-medium" name="sido"></select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">구군</th>
						<td>
							<select class="form-control input-medium" name="gugun"></select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">읍면동</th>
						<td>
							<select class="form-control input-medium" name="dong"></select>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="text-center">
				<button type="submit" class="btn btn-primary">등록</button>
			</div>
			<?php echo form_close();?>
		</div>
	</div>
</div>