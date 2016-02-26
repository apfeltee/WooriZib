<script>
$(document).ready(function(){

	$("#add_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			name: {  
				required: true,  
				minlength: 2
			},
			sorting: {  
				required: true,
				number:true
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "지역명은 최소 2자리 이상입니다"
			},
			sorting: {  
				required: "<?php echo lang("form.required");?>",
				number:"숫자 형식으로 입력해주세요"
			}
		} 
	});  


	$("#edit_dialog").dialog({
			title: "지역 정보 수정",
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
				'등록': function(){
					$("#edit_form").submit();
				}
			}
	});

	$("#delete_dialog").dialog({
			title: "지역 정보 수정",
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

function edit(id){
	$.getJSON("/adminarea/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			if(key=="id") {
				$("#id").val(val);
			}
			if(key=="name") {
				$("#name").val(val);
			}
			if(key=="sorting") {
				$("#sorting").val(val);
			}
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
	
	$.getJSON("/adminarea/get_others_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		option = "";
		$.each(data, function(key, val) {
			option = option + "<option value='"+val["id"]+"'>"+val["name"]+"</option>";
		});
		$("#change_id").html(option);
		$('#delete_dialog').dialog("open");	
	});
}
</script>	

	   <div class="row">
          <div class="col-lg-12">
            <h1>지역 관리<small>목록 및 등록</small></h1>
            <ol class="breadcrumb">
              <li><a href="/adminhome/index"><i class="icon-dashboard"></i> <?php echo lang("menu.home");?> </a></li>
              <li class="active"><i class="icon-file-alt"></i> 지역 관리</li>
            </ol>
          </div>
        </div><!-- /.row -->
		 <div class="row">
			<div class="col-lg-6">
				<div class="help-block">* 제목을 클릭하면 수정 및 삭제를 할 수 있습니다.</div>
				<table class="table table-bordered table-striped table-condensed flip-content">
					<thead>
						<tr>
							<th style="width:40px;"><?php echo lang("site.number");?> </th>
							<th><?php echo lang("site.name");?> </th>
							<th style="width:40px;"><?php echo lang("site.order");?> </th>
							<th style="width:30px;">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($query as $val){?>
						<tr>
							<td><?php echo $val->id;?></td>
							<td><a href="#" onclick="edit('<?php echo $val->id;?>');"><?php echo $val->name;?></a></td>
							<td><?php echo $val->sorting;?></td>
							<td><button class="btn btn-xs btn-danger" onclick="data_delete('<?php echo $val->id;?>','<?php echo $val->name;?>');"><i class="fa fa-trash-o"></i></button></td>
						</tr>
						<?php }?>
					</tbody>
				</table>
			</div>
			<div class="col-lg-6">
				<?php echo form_open("adminarea/add_action",Array("id"=>"add_form"))?>
				<div class="form-group">
					<label>지역 명</label>
					<input type="text" class="form-control" name="name" placeholder="<?php echo lang("site.name");?> ">
				</div>
				<div class="form-group">
					<label>순번</label>
					<input type="text" class="form-control" name="sorting" placeholder="순번">
				</div>
				<button type="submit" class="btn btn-primary"><?php echo lang("site.submit");?> </button>
				<?php echo form_close();?>
			</div>
		</div>
</div>


<div id="edit_dialog" title="지역정보 수정">
<?php echo form_open("adminarea/edit_action",Array("id"=>"edit_form"))?>
	<input type="hidden" id="id" name="id">
	<table class="table table-bordered table-striped table-condensed flip-content">
		<tr>
			<th><?php echo lang("site.name");?> </th>
			<td><input type="text" class="form-control" id="name" name="name" placeholder="<?php echo lang("site.name");?> "></td>
		</tr>
		<tr>
			<th><?php echo lang("site.order");?> </th>
			<td><input type="text" class="form-control" id="sorting" name="sorting" placeholder="<?php echo lang("site.order");?> "></td>
		</tr>
	</table>
<?php echo form_close();?>
</div>

<div id="delete_dialog" title="지역정보 삭제">
<div class="help-block">* 삭제 시 물건을 다른 지역정보로 변경해야 합니다.</div>
<?php echo form_open("adminarea/delete_action",Array("id"=>"delete_form"))?>
	<input type="hidden" id="delete_id" name="delete_id">
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