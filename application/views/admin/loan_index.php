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
						$.get("/adminloan/sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
							
						});
						i++;
					});
				}
	}).disableSelection();

	$("#add_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			bank_name: {  
				required: true,  
				minlength: 2
			}
		},  
		messages: {  
			bank_name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "최소 2자리 이상입니다"
			}
		} 
	});  


	$("#edit_dialog").dialog({
			title: "대출 정보 수정",
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

});

function edit(id,form){
	$.getJSON("/adminloan/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			if(key!="image") $("#"+form).find("#"+key).val(val);
		});
		$('#edit_dialog').dialog("open");	
	});
}

function data_delete(id){
	if(confirm("삭제하시겠습니까?")){
		location.href="/adminloan/delete_loan/"+id;		
	}
}
</script>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				대출 <small>정보 관리</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					대출 관리
				</li>
			</ul>
			<div class="page-toolbar">
				
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<div class="help-block">* 대출정보는 거래종류가 매매일 경우에만 보입니다.</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" style="width:30px;"><i class="fa fa-arrows"></i></th>
					<th class="text-center">은행명</th>
					<th class="text-center">이율(최소)(%)</th>
					<th class="text-center">이율(최대)(%)</th>
					<th class="text-center">담보비율(%)</th>
					<th class="text-center">기타</th>
					<th class="text-center" style="width:50px;">삭제</th>
				</tr>
			</thead>
			<tbody id="sort_list">
				<?php 
				if(count($query)<1){
					echo "<tr><td colspan='7' class='text-center'>등록된 데이터가 없습니다.</td></tr>";
				}
				foreach($query as $val){?>
				<tr data-id="<?php echo $val->id;?>">
					<td class="text-center"><i class="fa fa-sort"></i></td>
					<td><a href="#" onclick="edit('<?php echo $val->id;?>','edit_form');"><?php echo $val->bank_name;?></a></td>
					<td class="text-center"><?php echo $val->rate_min;?></td>
					<td class="text-center"><?php echo $val->rate_max;?></td>
					<td class="text-center"><?php echo $val->rate_loan;?></td>
					<td><?php echo $val->etc;?></td>
					<td class="text-center"><button class="btn btn-xs btn-danger" onclick="data_delete('<?php echo $val->id;?>');" style="margin:0"><i class="fa fa-trash-o"></i></button></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	<div class="col-lg-6">
		<div class="portlet">
			<?php echo form_open_multipart("adminloan/add_action",Array("id"=>"add_form"))?>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th class="text-center vertical-middle">은행명</th>
						<td>
							<input type="text" class="form-control" name="bank_name" placeholder="은행명"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">대출이율(%)</th>
						<td>
							<input type="text" name="rate_min" class="form-control input-small input-inline" placeholder="최소"> ~
							<input type="text" name="rate_max" class="form-control input-small input-inline" placeholder="최대">
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">담보비율(%)</th>
						<td>
							<input type="text" name="rate_loan" class="form-control input-small input-inline" placeholder="담보비율">
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">기타</th>
						<td>
							<input type="text" class="form-control" name="etc"/>
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

<div id="edit_dialog" title="대출 정보 수정" style="display:none;">
	<?php echo form_open_multipart("adminloan/edit_action",Array("id"=>"edit_form"))?>
	<input type="hidden" id="id" name="id"/>
	<table class="table table-bordered table-striped table-condensed flip-content">
		<tr>
			<th>은행명</th>
			<td><input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="은행명"/></td>
		</tr>
		<tr>
			<th class="text-center vertical-middle">대출이율</th>
			<td>
				<input type="text" id="rate_min" name="rate_min" class="form-control input-small input-inline" placeholder="최소"> ~
				<input type="text" id="rate_max" name="rate_max" class="form-control input-small input-inline" placeholder="최대">
			</td>
		</tr>
		<tr>
			<th class="text-center vertical-middle">기타</th>
			<td>
				<input type="text" class="form-control" id="etc" name="etc"/>
			</td>
		</tr>
	</table>
	<?php echo form_close();?>
</div>