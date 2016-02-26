<script>
$(document).ready(function(){

	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};

	$("#sort_list").find("tr").each(function(){
		$(this).css('cursor','s-resize');
	});

	$("#sort_list").sortable({
				helper: fixHelper,
				update: function (event, ui) {
					var i=1;
					$("#sort_list").find("tr").each(function(){
						$.get("/adminportfoliocategory/sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
							
						});
						i++;
					});
				}
	}).disableSelection();

	$("#add_form").validate({  
        errorElement: "span",
        wrapper: "span", 
		rules: {
			name: {  
				required: true,  
				minlength: 2
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "종류명은 최소 2자리 이상입니다"
			}
		} 
	});  


	$("#edit_dialog").dialog({
			title: "종류 정보 수정",
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:650,
			height: 600,
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
	$.getJSON("/adminportfoliocategory/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			$("#"+form).find("#"+key).val(val);
		});
		$('#edit_dialog').dialog("open");	
	});
}

/**
 * 삭제시 등록된 정보를 다른 값으로 변경하는 기능을 추가하여야 한다.
 */
function data_delete(id,name){
	$("#delete_label").html(name);
	$("#delete_id").val(id);
	
	$.getJSON("/adminportfoliocategory/get_others_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		var option = "";
		var data_count = 0;
		var first_val = 0;
		$.each(data, function(key, val) {
			option = option + "<option value='"+val["id"]+"'>"+val["name"]+"</option>";
			if(data_count==0) first_val = val["id"];
			data_count++;
		});
		$("#change_id").html(option);
		if(first_val) $("#change_id").val(first_val);
		$('#delete_dialog').dialog("open");	
	});
}
</script>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			갤러리 <?php echo lang("site.category");?><small>관리</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li><i class="fa fa-file-image-o"></i> <a href="#">갤러리</a> <i class="fa fa-angle-right"></i> </li>
				<li>카테고리 관리</li>
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" style="width:30px;"><i class="fa fa-arrows"></i></th>
					<th class="text-center">이름</th>
					<th class="text-center">회원인증</th>
					<th class="text-center">사용여부</th>
					<th class="text-center" style="width:50px;">삭제</th>
				</tr>
			</thead>
			<tbody id="sort_list">
				<?php
				if(count($query)<1){
					echo "<tr><td colspan='5' class='text-center'>".lang("msg.nodata")."</td></tr>";
				}
				foreach($query as $val){?>
				<tr data-id="<?php echo $val->id;?>">
					<td class="text-center"><i class="fa fa-sort"></i></td>
					<td><a href="#" onclick="edit('<?php echo $val->id;?>','edit_form');"><?php echo $val->name;?></a></td>
					<td class="text-center"><?php if($val->opened=="Y"){echo "불필요";} else {echo "필요";}?></td>
					<td class="text-center"><?php if($val->valid=="Y") {echo "유효";} else {echo "무효";}?></td>
					<td class="text-center"><button class="btn btn-xs btn-danger" onclick="data_delete('<?php echo $val->id;?>','<?php echo $val->name;?>');" <?php if(count($query)==1) echo "disabled"?> style="margin:0"><i class="fa fa-trash-o"></i></button></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	<div class="col-lg-6">
		<div class="portlet">
			<?php echo form_open("adminportfoliocategory/add_action",Array("id"=>"add_form"))?>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th class="text-center vertical-middle">카테고리 이름</th>
						<td>
							<input type="text" class="form-control" name="name" placeholder="이름"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">회원인증</th>
						<td>
							<select name="opened" class="form-control select2me">
								<option value="Y" selected>로그인하지 않아도 볼 수 있음</option>
								<option value="N">로그인을 해야만 볼 수 있음</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">사용여부</th>
						<td>
							<select name="valid" class="form-control select2me">
								<option value="Y" selected>사용함(홈페이지에서 보임)</option>
								<option value="N">사용하지 않음(홈페이지에서 보이지 않음)</option>
							</select>
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

<div id="edit_dialog" title="카테고리 정보 수정" style="display:none;">
	<?php echo form_open("adminportfoliocategory/edit_action",Array("id"=>"edit_form"))?>
	<input type="hidden" id="id" name="id"/>
	<input type="hidden" id="sorting" name="sorting"/>
	<table class="table table-bordered table-striped table-condensed flip-content">
		<tr>
			<th>카테고리 이름</th>
			<td><input type="text" class="form-control" id="name" name="name" placeholder="이름"/></td>
		</tr>
		<tr>
			<th>회원인증</th>
			<td>
				<select id="opened" name="opened" class="form-control input-small select2me">
					<option value="Y">불필요</option>
					<option value="N">필요</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>유효</th>
			<td>
				<select id="valid" name="valid" class="form-control input-small select2me">
					<option value="Y">유효</option>
					<option value="N">무효</option>
				</select>
			</td>
		</tr>
	</table>
	<?php echo form_close();?>
</div>

<div id="delete_dialog" title="뉴스카테고리 정보 삭제" style="display:none;">
	<div class="help-block">* 삭제 시 물건을 다른 종류로 변경해야 합니다.</div>
	<?php echo form_open("adminportfoliocategory/delete_action",Array("id"=>"delete_form"))?>
	<input type="hidden" id="delete_id" name="delete_id"/>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<tr>
				<th>변경 전</th>
				<td><div id="delete_label"></div></td>
			</tr>
			<tr>
				<th>변경 후</th>
				<td>
					<select id="change_id" name="change_id" class="form-control input-small select2me"></select>
				</td>
			</tr>
		</table>
	<?php echo form_close();?>
</div>