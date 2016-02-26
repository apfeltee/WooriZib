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
						$.get("/adminservice/sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
							
						});
						i++;
					});
				}
	}).disableSelection();

	$("#add_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			service_name: {  
				required: true,  
				minlength: 2
			}
		},  
		messages: {  
			service_name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "서비스명은 최소 2자리 이상입니다"
			}
		} 
	});  


	$("#edit_dialog").dialog({
			title: "서비스 정보 수정",
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
			title: "서비스 정보 삭제",
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

	$('input[name="image"]').change(function(e){
		msg($("#msg"), "info" ,$(this).val());
	});

});

function edit(id,form){
	$.getJSON("/adminservice/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			if(key!="image") $("#"+form).find("#"+key).val(val);
		});
		$('#edit_dialog').dialog("open");	
	});
}

/**
 * 삭제시 등록된 정보를 다른 값으로 변경하는 기능을 추가하여야 한다.
 */
function data_delete(id,name){
	if(confirm("삭제하시면 복구가 되지 않습니다.\n사용여부에서 보이지 않기를 선택해 보면 어떨까요?\n혹시 나중에 필요할 수도 있잖아요~")){
		location.href="/adminservice/delete_action/"+id;
	}
	
}
</script>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				서비스 <small>정보 관리</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<a href="#">레이아웃 설정</a> <i class="fa fa-angle-right"></i>
				</li>
				<li>
					서비스 관리
				</li>
			</ul>
			<div class="page-toolbar">
				
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<div class="help-block">* 제목을 클릭하면 수정을 할 수 있습니다.</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" style="width:30px;"><i class="fa fa-arrows"></i></th>
					<th class="text-center">서비스 이름</th>
					<th class="text-center" style="width:50px;">삭제</th>
				</tr>
			</thead>
			<tbody id="sort_list">
				<?php 
				if(count($query)<1){
					echo "<tr><td colspan='3' class='text-center'>".lang("msg.nodata")."</td></tr>";
				}
				foreach($query as $val){?>
				<tr data-id="<?php echo $val->id;?>">
					<td class="text-center"><i class="fa fa-sort"></i></td>
					<td><a href="#" onclick="edit('<?php echo $val->id;?>','edit_form');"><?php echo $val->service_name;?></a></td>
					<td class="text-center"><button class="btn btn-xs btn-danger" onclick="data_delete('<?php echo $val->id;?>','<?php echo $val->service_name;?>');" <?php if(count($query)==1) echo "disabled"?> style="margin:0"><i class="fa fa-trash-o"></i></button></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	<div class="col-lg-6">
		<div class="portlet">
			<?php echo form_open_multipart("adminservice/add_action",Array("id"=>"add_form"))?>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th class="text-center vertical-middle">서비스 이름</th>
						<td>
							<input type="text" class="form-control" name="service_name" placeholder="이름"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">링크</th>
						<td>
							<input type="text" class="form-control" name="link" placeholder="외부 링크는 http://도 입력"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">새창여부</th>
						<td>
							<select name="target" class="form-control select2me">
								<option value="N">새창 아님</option>
								<option value="Y">새창</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">사용여부</th>
						<td>
							<select name="flag" class="form-control select2me">
								<option value="Y">보임</option>
								<option value="N">보이지 않음</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">셀 병합</th>
						<td>
							<select class="form-control select2me" name="col">
								<option value="1">1개</option>
								<option value="2">2개</option>
								<option value="3">3개</option>
								<option value="4">4개</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">이미지</th>
						<td>
							<input type="file" class="form-control input-xlarge" name="image" style="height:auto;margin-bottom:5px;"/>
							<span class="help-block">1셀 : 폭 223픽셀 * 높이 163 픽셀</span>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">설명</th>
						<td>
							<textarea name="description" class="form-control" rows="5" placeholder="설명"></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="text-center">
				<button type="submit" class="btn btn-primary"><?php echo lang("site.submit");?></button>			
			</div>
			<?php echo form_close();?>
		</div>
	</div>
</div>

<div id="edit_dialog" title="서비스 정보 수정" style="display:none;">
<?php echo form_open_multipart("adminservice/edit_action",Array("id"=>"edit_form"))?>
	<input type="hidden" id="id" name="id"/>
	<table class="table table-bordered table-striped table-condensed flip-content">
		<tr>
			<th>이름</th>
			<td><input type="text" class="form-control" id="service_name" name="service_name" placeholder="이름"/></td>
		</tr>
		<tr>
			<th>링크</th>
			<td><input type="text" class="form-control" id="link" name="link" placeholder="외부 링크는 http://도 입력"/></td>
		</tr>		
		<tr>
			<th>새창여부</th>
			<td>
				<select id="target" name="target" class="form-control input-small select2me">
					<option value="N">새창 아님</option>
					<option value="Y">새창</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>사용여부</th>
			<td>
				<select id="flag" name="flag" class="form-control input-small select2me">
					<option value="Y">보임</option>
					<option value="N">보이지 않음</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>셀 병합</th>
			<td>
				<select id="col" name="col" class="form-control input-small select2me">
					<option value="1">1개</option>
					<option value="2">2개</option>
					<option value="3">3개</option>
					<option value="4">4개</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>이미지(1셀 : 폭 223픽셀 * 높이 163 픽셀)</th>
			<td>
				<div id="msg"></div>
				<div class="btn btn-default btn-file margin-top-10">이미지 업로드<input type="file" id="image" name="image"/></div> 
			</td>
		</tr>
		<tr>
			<th>설명</th>
			<td>
				<textarea id="description" name="description" class="form-control" rows="5" placeholder="설명"></textarea>
			</td>
		</tr>
	</table>
<?php echo form_close();?>
</div>