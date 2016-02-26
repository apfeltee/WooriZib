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
				$.get("/adminpay/sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
					
				});
				i++;
			});
		}
	}).disableSelection();

	$("#add_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			day: {  
				required: true,
				number: true
			},
			count: {  
				required: true,
				number: true
			},
			price: {  
				required: true,
				number: true
			}
		},  
		messages: {  
			day: {  
				required: "<?php echo lang("form.required");?>",
				number: "숫자만 입력해 주세요"
			},
			count: {
				required: "<?php echo lang("form.required");?>",
				number: "숫자만 입력해 주세요"
			},
			price: {  
				required: "<?php echo lang("form.required");?>",
				number: "숫자만 입력해 주세요"
			}
		} 
	});  


	$("#edit_dialog").dialog({
			title: "<?php echo lang("pay");?> 정보 수정",
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
				'수정': function(){
					$("#edit_form").submit();
				}
			}
	});
});

function edit(id,form){
	$.getJSON("/adminpay/setting_get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			$("#"+form).find("#"+key).val(val);
		});

		$('#edit_dialog').dialog("open");	
	});
}

function data_delete(id){
	if(confirm("삭제 하시겠습니까?")){
		$("#delete_id").val(id);
		$("#delete_form").submit();
	}
	return false;
}
</script>	

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			<?php echo lang("pay");?> 가격설정
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<a href="#">결제 관리</a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="#"><?php echo lang("pay");?> 가격설정</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<div class="help-block">* <?php echo lang("pay");?>명을 클릭하면 수정을 할 수 있습니다.</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" style="width:30px;"><i class="fa fa-arrows"></i></th>
					<th class="text-center"><?php echo lang("pay");?>명</th>
					<th class="text-center">이용일수</th>
					<th class="text-center"><?php echo lang("product");?>등록 가능횟수</th>
					<th class="text-center">가격</th>
					<th class="text-center" style="width:50px;">삭제</th>
				</tr>
			</thead>
			<tbody id="sort_list">
				<?php
				if(count($query)<1){
					echo "<tr><td class='text-center' colspan='6'>".lang("msg.nodata")."</td></tr>";
				}
				foreach($query as $val){?>
				<tr data-id="<?php echo $val->id;?>">
					<td class="text-center"><i class="fa fa-sort"></i></td>
					<td class="text-right"><a href="#" onclick="edit('<?php echo $val->id;?>','edit_form');"><?php echo $val->name;?></a></td>
					<td class="text-right"><?php echo $val->day;?>일</td>
					<td class="text-right"><?php echo $val->count;?>회</td>
					<td class="text-right"><?php echo number_format($val->price);?>원</td>
					<td class="text-center"><button class="btn btn-xs btn-danger" onclick="data_delete('<?php echo $val->id;?>')" style="margin:0"><i class="fa fa-trash-o"></i></button></td>
				</tr>
				<?php
				}?>
			</tbody>
		</table>
	</div>
	<div class="col-lg-6">
		<div class="portlet">
			<?php echo form_open("adminpay/setting_add_action",Array("id"=>"add_form"))?>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th class="text-center vertical-middle"><?php echo lang("pay");?>명</th>
						<td>
							<input type="text" class="form-control" name="name" placeholder="<?php echo lang("pay");?>명"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">이용일수</th>
						<td>
							<input type="text" class="form-control" name="day" placeholder="일"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">등록 가능횟수</th>
						<td>
							<input type="text" class="form-control" name="count" placeholder="<?php echo lang("product");?>등록 가능횟수"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle"><?php echo lang("site.price");?></th>
						<td>
							<input type="text" class="form-control" name="price" placeholder="원단위"/>
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

<div id="edit_dialog" title="<?php echo lang("pay");?> 수정" style="display:none;">
<?php echo form_open("adminpay/setting_edit_action",Array("id"=>"edit_form"))?>
	<input type="hidden" id="id" name="id"/>
	<table class="table table-bordered table-striped table-condensed flip-content">
		<tr>
			<th><?php echo lang("pay");?>명</th>
			<td><input type="text" class="form-control" id="name" name="name" placeholder="<?php echo lang("pay");?>명"/></td>
		</tr>
		<tr>
			<th>이용일수</th>
			<td><input type="text" class="form-control" id="day" name="day" placeholder="일"/></td>
		</tr>
		<tr>
			<th><?php echo lang("product");?>등록 가능횟수</th>
			<td><input type="text" class="form-control" id="count" name="count" placeholder="<?php echo lang("product");?>등록 가능횟수"/></td>
		</tr>
		<tr>
			<th>가격</th>
			<td><input type="text" class="form-control" id="price" name="price" placeholder="원단위"/></td>
		</tr>
	</table>
<?php echo form_close();?>
</div>

<?php echo form_open("adminpay/setting_delete_action",Array("id"=>"delete_form"))?>
<input type="hidden" id="delete_id" name="delete_id">
<?php echo form_close();?>