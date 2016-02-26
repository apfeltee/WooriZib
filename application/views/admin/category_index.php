<style>
.none-border {
	border-left:0px solid #000 !important;
	border-right:0;
	border-bottom:0;
}
.sortable li div {
	border:1px solid #cacaca;
	cursor: move;
	margin:3px 0px;
}
ul.sortable ul {
    margin: 0 0 0 25px;
    padding: 0;
    list-style-type: none;
}

.sortable ul div{
	padding: 5px 0px;
}
</style>
<script>
$(document).ready(function(){

	$("ul.sortable").sortable({
		disableNesting: 'no-nest',
		forcePlaceholderSize: true,
		handle: 'div',
		helper:	'clone',
		placeholder: "placeholder",
		revert: 250
	});

	$(".sub_list").sortable({
		disableNesting: 'no-nest',
		forcePlaceholderSize: true,
		handle: 'div',
		helper:	'clone',
		placeholder: "placeholder",
		revert: 250
	});

	$('#save').click(function(){
		var main = [];
		$('ul.sortable').children('li').each(function(idx, elm) {
			main.push(elm.id.split('_')[1]);
		});

		var sub = [];
		$('ul.sortable').children('li').each(function(idx, elm) {
			var id = elm.id.split('_')[1];
			var param = "";
			$(this).find('.sub_list').children('li').each(function(sub_idx, sub_elm) {
				param += sub_elm.id.split('_')[1]+",";
			});
			if(param !="") sub[id]=param;

		});

		$.ajax({
			type: 'post',
			url: '/admincategory/sorting_sub/',
			data: {
				main : main,
				sub : sub
			}
		}).done(function(data) {
			if(data=="1"){
				msg($("#msg"),"success","저장되었습니다");
			}
		});
	});

	$("#add_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			name: {  
				required: true,
				minlength: 2
			},
			main: {  
				required: true
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "최소 2자리 이상입니다"
			},
			main: {  
				required: "<?php echo lang("form.required");?>"
			}
		} 
	});  

	$("#edit_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			name: {  
				required: true,
				minlength: 2
			},
			main: {  
				required: true
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "최소 2자리 이상입니다"
			},
			main: {  
				required: "<?php echo lang("form.required");?>"
			}
		} 
	});

	$("#add_sub_form").validate({
		rules: {
			sub_name: {  
				required: true
			}
		},  
		messages: {  
			sub_name: {  
				required: "<?php echo lang("form.required");?>"
			}
		} 
	}); 

	$("#edit_dialog").dialog({
			title: "<?php echo lang("product.category");?> 정보 수정",
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

	$("#delete_dialog").dialog({
			title: "<?php echo lang("product.category");?> 정보 삭제",
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

	$("#sub_dialog").dialog({
			title: "소분류 추가",
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:400,
			height: 210,
			modal: true,
			buttons: {
				'취소': function() {
					$(this).dialog("close");
				},
				'등록': function(){
					$("#add_sub_form").submit();
				}
			}
	});

	$("#edit_sub_dialog").dialog({
			title: "소분류 수정",
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:400,
			height: 210,
			modal: true,
			buttons: {
				'취소': function() {
					$(this).dialog("close");
				},
				'수정': function(){
					$("#edit_sub_form").submit();
				}
			}
	});

});

function edit(id,form){
	$.getJSON("/admincategory/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			$("#"+form).find("#"+key).val(val);
		});

		$('#edit_dialog').dialog("open");	
	});
}

function edit_sub(id,name){
	$("#edit_sub_form").find("#sub_id").val(id);
	$("#edit_sub_form").find("#sub_name_text").text(name);
	$("#edit_sub_dialog").dialog("open");
}

/**
 * 삭제시 등록된 정보를 다른 값으로 변경하는 기능을 추가하여야 한다.
 */
function main_delete(id,name){
	$("#delete_label").html(name);
	$("#delete_id").val(id);
	
	$.getJSON("/admincategory/get_others_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
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

function add_sub(id,name){
	$("#main_id").val(id);
	$("#main_label").text(name);
	$("#sub_name").val("");
	$("#sub_dialog").dialog("open");
}

function sub_delete(id){
	if(confirm("삭제 하시겠습니까?")){
		$.getJSON("/admincategory/delete_sub/"+id+"/"+Math.round(new Date().getTime()),function(data){
			
		});
		$("#list_"+id).remove();
	}
}
</script>	

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			<?php echo lang("product.category");?><small>관리</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<?php echo lang("product.category");?> 관리
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<div class="help-block">* 제목을 클릭하면 수정을 할 수 있습니다. (소분류 추가는 선택사항입니다)</div>
		<table class="table table-condensed flip-content">
			<thead>
				<colgroup>
					<col width="5%">
					<col width="10%">
					<col width="*">
					<col width="15%">
					<col width="15%">
					<col width="10%">
				</colgroup>
				<tr>
					<th>&nbsp;</th>
					<th class="text-center">대분류</th>
					<th class="text-center"><?php echo lang("product.category");?> <?php echo lang("site.title");?></th>
					<th class="text-center">회원인증</th>
					<th class="text-center">유효/무효</th>
					<th class="text-center"><i class="fa fa-cog"></i></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="6">
						<ul class="sortable ui-sortable">
							<?php
							foreach($query as $val){?>
							<li id="list_<?php echo $val->id;?>">
								<div style="1px solid #cacaca;">
									<table class="table table-condensed flip-content" style="margin:0px;">
										<colgroup>
											<col width="5%">
											<col width="10%">
											<col width="*">
											<col width="15%">
											<col width="15%">
											<col width="10%">
										</colgroup>
										<tr class="text-center info">
											<td>
												<i class="fa fa-sort"></i>
											</td>
											<td>
												<?php	
													if($val->main=="1"){
														echo "원룸/투룸";
													} else if($val->main=="2"){
														echo "아파트";
													} else if($val->main=="3"){
														echo "주택";
													} else if($val->main=="4"){
														echo "빌라";
													} else if($val->main=="5"){
														echo "오피스텔";
													} else if($val->main=="6"){
														echo "상가/점포";
													} else if($val->main=="7"){
														echo "토지/임야";
													} else if($val->main=="8"){
														echo "경매";
													} else if($val->main=="9"){
														echo "공장";
													} else if($val->main=="10"){
														echo "사무실";
													} else if($val->main=="11"){
														echo "분양권";														
													}
												?>
											</td>
											<td>
												<a href="javascript:edit('<?php echo $val->id;?>','edit_form');"><strong><?php echo $val->name;?></strong></a>
											</td>
											<td>
												<?php if($val->opened=="Y"){echo "불필요";} else {echo "필요";}?>
											</td>
											<td>
												<?php if($val->valid=="Y") {echo "유효";} else {echo "무효";}?>
											</td>
											<td>
												<button class="btn btn-xs btn-primary" onclick="add_sub('<?php echo $val->id;?>','<?php echo $val->name;?>')"><i class="fa fa-plus"></i></button>
												<button class="btn btn-xs btn-danger" onclick="main_delete('<?php echo $val->id;?>','<?php echo $val->name;?>');" <?php if(count($query)==1) echo "disabled"?>><i class="fa fa-minus"></i></button>
											</td>
										</tr>
									</table>
								</div>
								<?php if(isset($val->category_sub)){?>
								<ul class="sub_list">
									<?php foreach($val->category_sub as $sub){?>
									<li id="list_<?php echo $sub->id;?>">
										<div style="padding-left:10px;">
											<i class="fa fa-level-up fa-rotate-90"></i> <a href="javascript:edit_sub('<?php echo $sub->id;?>','<?php echo $sub->name;?>');"><?php echo $sub->name;?></a>
											<button class="btn btn-xs btn-danger" onclick="sub_delete('<?php echo $sub->id;?>');" style="margin-left:10px;"><i class="fa fa-minus"></i></button>
										</div>
									</li>
									<?php }?>
								</ul>
								<?php }?>
							</li>
							<?php }?>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>
		<div id="msg" class="margin-bottom-20 text-center"></div>
		<div class="text-right">			
			<button id="save" type="button" class="btn btn-primary">순서 저장</button>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="help-block">&nbsp;</div>
		<div class="portlet">
			<?php echo form_open("admincategory/add_action",Array("id"=>"add_form"))?>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th class="text-center vertical-middle"><?php echo lang("product.category");?> <?php echo lang("site.title");?></th>
						<td>
							<input type="text" class="form-control" name="name" placeholder="<?php echo lang("product.category");?> <?php echo lang("site.title");?>"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">대분류</th>
						<td>
							<select class="form-control select2me" name="main">
								<option value="">선택(참고용 사이트 표시 안됨)</option>
								<option value="1">원룸/투룸</option>
								<option value="2">아파트</option>
								<option value="3">주택</option>
								<option value="4">빌라</option>
								<option value="5">오피스텔</option>
								<option value="6">상가/점포</option>
								<option value="7">토지/임야</option>
								<option value="8">경매</option>
								<option value="9">공장</option>
								<option value="10">사무실</option>
								<option value="11">분양권</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">회원인증</th>
						<td>
							<select name="opened" class="form-control select2me">
								<option value="Y" selected>불필요</option>
								<option value="N">필요</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">유효/무효</th>
						<td>
							<select name="valid" class="form-control select2me">
								<option value="Y" selected>유효</option>
								<option value="N">무효</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle"><?php echo lang("site.option");?></th>
						<td>
							<div class="help-block"> 콤마(,) 기호로 제공옵션을 입력해 주세요. (<?php echo lang("product");?> 입력시 체크할 사항입니다)</div>
							<textarea name="template" class="form-control" rows="5">엘리베이터,주거용,업무용,복도식,계단식,남향,주차가능,중앙난방,개별난방,도시가스,자체경비원,인터폰,CCTV,옷장,붙박이장,식탁,신발장,냉장고,세탁기,샤워부스,싱크대,가스레인지</textarea>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">추가입력항목들</th>
						<td>
							<div class="help-block"> 콤마(,) 기호로 입력해 주세요. (<?php echo lang("product");?> 입력시 글로 입력할 사항입니다)</div>
							<div class="help-block"> 항목을 추가하는 것은 상관없으나 항목을 뺄 경우에는 데이터의 정합성이 깨질 수 있습니다.</div>
							<textarea name="meta" class="form-control" rows="5"></textarea>
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

<div id="edit_dialog" title="<?php echo lang("product.category");?> 정보 수정" style="display:none;">
	<?php echo form_open("admincategory/edit_action",Array("id"=>"edit_form"))?>
	<input type="hidden" id="id" name="id"/>
	<table class="table table-bordered table-striped table-condensed flip-content">
		<tr>
			<th>이름</th>
			<td><input type="text" class="form-control" id="name" name="name" placeholder="이름"/></td>
		</tr>
		<tr>
			<th>대분류</th>
			<td>
				<select class="form-control select2me" id="main" name="main">
					<option value="">선택(참고용 사이트 표시 안됨)</option>
					<option value="1">원룸/투룸</option>
					<option value="2">아파트</option>
					<option value="3">주택</option>
					<option value="4">빌라</option>
					<option value="5">오피스텔</option>
					<option value="6">상가/점포</option>
					<option value="7">토지/임야</option>
					<option value="8">경매</option>
					<option value="9">공장</option>
					<option value="10">사무실</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>회원인증</th>
			<td>
				<select id="opened" name="opened" class="form-control select2me">
					<option value="Y">불필요</option>
					<option value="N">필요</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>유효</th>
			<td>
				<select id="valid" name="valid" class="form-control select2me">
					<option value="Y">유효</option>
					<option value="N">무효</option>
				</select>
			</td>
		</tr>
		<tr>
			<th><?php echo lang("site.option");?></th>
			<td>
				<textarea id="template" name="template" class="form-control" rows="5"></textarea>
			</td>
		</tr>
		<tr>
			<th>추가입력항목들</th>
			<td>
				<textarea id="meta" name="meta" class="form-control" rows="5"></textarea>
			</td>
		</tr>
	</table>
	<?php echo form_close();?>
</div>

<div id="delete_dialog" title="<?php echo lang("product.category");?> 정보 삭제" style="display:none;">
	<div class="help-block">* 삭제 시 물건을 다른 <?php echo lang("product.category");?>으로 변경해야 합니다.</div>
	<?php echo form_open("admincategory/delete_action",Array("id"=>"delete_form"))?>
		<input type="hidden" id="delete_id" name="delete_id"/>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<tr>
				<th>변경 전</th>
				<td><div id="delete_label"></div></td>
			</tr>
			<tr>
				<th>변경 후</th>
				<td>
					<select id="change_id" name="change_id" class="form-control select2me" style="width:130px"></select>
				</td>
			</tr>
		</table>
	<?php echo form_close();?>
</div>

<div id="sub_dialog" title="소분류 추가" style="display:none;">
	<?php echo form_open("admincategory/add_sub_action",Array("id"=>"add_sub_form"))?>
	<input type="hidden" id="main_id" name="main_id"/>
	<table class="table table-bordered table-striped table-condensed flip-content">
		<tr>
			<th>대분류</th>
			<td><div id="main_label"></div></td>
		</tr>
		<tr>
			<th>소분류</th>
			<td>
				<input type="text" class="form-control" id="sub_name" name="sub_name" placeholder="소분류" maxlength="20"/>
			</td>
		</tr>
	</table>
	<?php echo form_close();?>
</div>

<div id="edit_sub_dialog" title="소분류 수정" style="display:none;">
	<?php echo form_open("admincategory/edit_sub_action",Array("id"=>"edit_sub_form"))?>
	<input type="hidden" id="sub_id" name="sub_id"/>
	<table class="table table-bordered table-condensed flip-content">
		<tr>
			<th>수정 전</th>
			<td><div id="sub_name_text"></div></td>
		</tr>
		<tr>
			<th>수정 후</th>
			<td>
				<input type="text" class="form-control" id="sub_name" name="sub_name" placeholder="소분류" maxlength="20"/>
			</td>
		</tr>
	</table>
	<?php echo form_close();?>
</div>