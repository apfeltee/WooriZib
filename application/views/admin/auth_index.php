<script>
$(document).ready(function(){

	$("#add_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			auth_name: {  
				required: true
			}			
		},  
		messages: {  
			auth_name: {  
				required: "<?php echo lang("form.required");?>"
			}
		} 
	}); 

	$("#edit_form").validate({  
        errorElement: "span",
        wrapper: "span",
		rules: {
			auth_name: {  
				required: true
			}			
		},  
		messages: {  
			auth_name: {  
				required: "<?php echo lang("form.required");?>"
			}
		} 
	});

	$("#edit_dialog").dialog({
			title: "그룹 정보 수정",
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:650,
			height: 710,
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
			title: "그룹 정보 삭제",
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
	$.getJSON("/adminauth/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			if(key=="id"){
				if(val=="1"){
					$("#auth_member").attr("disabled",true);
					$("#auth_name").attr("readonly",true);
				}
				else{
					$("#auth_member").attr("disabled",false);
					$("#auth_name").attr("readonly",false);
				}
			}
			$("#"+form).find("#"+key).val(val);
		});

		$('#edit_dialog').dialog("open");	
	});
}

function data_delete(id,auth_name){
	$("#delete_label").html(auth_name);
	$("#delete_id").val(id);
	
	$.getJSON("/adminauth/get_others_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		var option = "";
		$.each(data, function(key, val) {
			option = option + "<option value='"+val["id"]+"'>"+val["auth_name"]+"</option>";
		});
		$("#change_id").html(option);
		$("#change_id").val(1); 
		$('#delete_dialog').dialog("open");	
	});
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				권한그룹<small>관리</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					권한그룹 관리
				</li>
			</ul>
			<div class="page-toolbar">
				
			</div>
		</div>
	</div>
</div><!-- /.row -->

		 <div class="row">
			<div class="col-lg-6">
				<div class="help-block">* 제목을 클릭하면 수정을 할 수 있습니다. (기본권한 그룹은 삭제할 수 없습니다)</div>
				<table class="table table-bordered table-striped table-condensed flip-content">
					<thead>
						<tr>
							<th class="text-center">권한 이름</th>
							<th class="text-center" style="width:50px;"><?php echo lang("site.delete");?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(count($query)<1){
							echo "<tr><td colspan='5'>".lang("msg.nodata")."</td></tr>";
						}
						foreach($query as $val){?>
						<tr>
							<td class="text-center">
								<a href="#" onclick="edit('<?php echo $val->id;?>','edit_form');"><?php echo $val->auth_name;?></a>
							</td>
							<td class="text-center"><button class="btn btn-xs btn-danger <?php if($val->id<=2) echo "disabled"?>" onclick="data_delete('<?php echo $val->id;?>','<?php echo $val->auth_name;?>');" style="margin:0"><i class="fa fa-trash-o"></i></button></td>
						</tr>
						<?php
						}?>
					</tbody>
				</table>
			</div>
			<div class="col-lg-6">
				<?php echo form_open("adminauth/add_action",Array("id"=>"add_form"))?>
				<table class="table table-bordered table-striped-left table-condensed flip-content">
					<tbody>
						<tr>
							<td class="text-center vertical-middle"><?php echo lang("site.title");?></td>
							<td>
								<input type="text" class="form-control input-lg" name="auth_name" placeholder="<?php echo lang("site.title");?>">
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle"><?php echo lang("menu.home");?></td>
							<td>
								<select name="auth_home" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle"><?php echo lang("product");?>관리</td>
							<td>
								<select name="auth_product" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">회원관리</td>
							<td>
								<select name="auth_member" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">고객관리</td>
							<td>
								<select name="auth_contact" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">의뢰관리</td>
							<td>
								<select name="auth_request" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">뉴스관리</td>
							<td>
								<select name="auth_news" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">갤러리</td>
							<td>
								<select name="auth_portfolio" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">커스텀</td>
							<td>
								<select name="auth_custom" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle"><?php echo lang("menu.customercenter");?></td>
							<td>
								<select name="auth_popup" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">레이아웃 설정</td>
							<td>
								<select name="auth_layout" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">설정관리</td>
							<td>
								<select name="auth_set" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">통계</td>
							<td>
								<select name="auth_stats" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<?php if($config->USE_PAY){?>
						<tr>
							<td class="text-center vertical-middle">결제관리</td>
							<td>
								<select name="auth_pay" class="form-control input-small">
									<option value="Y" selected>가능</option>
									<option value="N">불가능</option>
								</select>
							</td>
						</tr>
						<?php }?>
					</tbody>
				</table>
				<div class="text-center">
					<button type="submit" class="btn btn-primary btn-lg"><?php echo lang("site.submit");?> </button>
				</div>
				<?php echo form_close();?>
			</div>
		</div>
</div>

<div id="edit_dialog" title="권한 그룹 수정" style="display:none;">
<?php echo form_open("adminauth/edit_action",Array("id"=>"edit_form"))?>
	<input type="hidden" id="id" name="id">
	<table class="table table-bordered table-striped table-condensed flip-content">
		<tr>
			<th><?php echo lang("site.title");?></th>
			<td><input type="text" class="form-control" id="auth_name" name="auth_name" placeholder="<?php echo lang("site.title");?>"></td>
		</tr>
		<tr>
			<th><?php echo lang("menu.home");?></th>
			<td>
				<select id="auth_home" name="auth_home" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<tr>
			<th><?php echo lang("product");?>관리</th>
			<td>
				<select id="auth_product" name="auth_product" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>회원관리</th>
			<td>
				<select id="auth_member" name="auth_member" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>고객관리</th>
			<td>
				<select id="auth_contact" name="auth_contact" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>의뢰관리</th>
			<td>
				<select id="auth_request" name="auth_request" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>뉴스관리</th>
			<td>
				<select id="auth_news" name="auth_news" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>갤러리</th>
			<td>
				<select id="auth_portfolio" name="auth_portfolio" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>커스텀</th>
			<td>
				<select id="auth_custom" name="auth_custom" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<tr>
			<th><?php echo lang("menu.customercenter");?></th>
			<td>
				<select id="auth_popup" name="auth_popup" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>레이아웃 설정</th>
			<td>
				<select id="auth_layout" name="auth_layout" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>설정관리</th>
			<td>
				<select id="auth_set" name="auth_set" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>통계</th>
			<td>
				<select id="auth_stats" name="auth_stats" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<?php if($config->USE_PAY){?>
		<tr>
			<th>결제관리</th>
			<td>
				<select id="auth_pay" name="auth_pay" class="form-control input-small select2me">
					<option value="Y">가능</option>
					<option value="N">불가능</option>
				</select>
			</td>
		</tr>
		<?php }?>
	</table>
<?php echo form_close();?>
</div>

<div id="delete_dialog" title="권한그룹 정보 삭제" style="display:none;">
<div class="help-block">* 삭제 시 다른 그룹으로 변경해야 합니다.</div>
<?php echo form_open("adminauth/delete_action",Array("id"=>"delete_form"))?>
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