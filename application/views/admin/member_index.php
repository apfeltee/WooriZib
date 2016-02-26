<link href="/assets/admin/css/todo.css" rel="stylesheet" type="text/css"/>
<link href="/assets/plugin/icheck/skins/all.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="/assets/plugin/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<link rel="stylesheet" href="/assets/plugin/colorpicker/css/colorpicker.css" type="text/css" />
<script type="text/javascript" src="/assets/plugin/colorpicker/js/colorpicker.js"></script>
<script type="text/javascript" src="/assets/plugin/colorpicker/js/eye.js"></script>
<script type="text/javascript" src="/assets/plugin/colorpicker/js/utils.js"></script>
<script type="text/javascript" src="/assets/plugin/colorpicker/js/layout.js?ver=1.0.2"></script>
<script>
$(document).ready(function(){

	$('.color').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).css("color","#"+hex);
			$(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	$("#general_add_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			name: {  
				required: true,  
				minlength: 2
			},
			email: {  
				required: true,
				email:true,
				remote : {
					type : "POST",
					async: false,
					url  : "/adminlogin/check_email"
				}
			},
			pw: {  
				required: true,
				minlength:5
			},
			phone: {  
				required: true,
				minlength:8
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "최소 2자리 이상입니다"
			},
			email: {  
				required: "<?php echo lang("form.required");?>",
				email:"이메일 형식으로 입력해주세요",
				remote: "이미 가입된 이메일 입니다."
			},
			pw: {  
				required: "<?php echo lang("form.required");?>",
				minlength:"최소 5자리 이상입니다"
			},
			phone: {  
				required: "<?php echo lang("form.required");?>",
				minlength:"최소 8자리 이상입니다"
			}
		} 
	});

	$("#biz_add_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			name: {  
				required: true,  
				minlength: 2
			},
			email: {  
				required: true,
				email:true,
				remote : {
					type : "POST",
					async: false,
					url  : "/adminlogin/check_email"
				}
			},
			pw: {  
				required: true,
				minlength:5
			},
			phone: {  
				required: true,
				minlength:8
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.2");?>"
			},
			email: {  
				required: "<?php echo lang("form.required");?>",
				email:"이메일 형식으로 입력해주세요",
				remote: "이미 가입된 이메일 입니다."
			},
			pw: {  
				required: "<?php echo lang("form.required");?>",
				minlength:"<?php echo lang("form.5");?>"
			},
			phone: {  
				required: "<?php echo lang("form.required");?>",
				minlength:"<?php echo lang("form.8");?>"
			}
		} 
	});

	$("#admin_add_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			name: {  
				required: true,  
				minlength: 2
			},
			email: {  
				required: true,
				email:true,
				remote : {
					type : "POST",
					async: false,
					url  : "/adminlogin/check_email"
				}
			},
			pw: {  
				required: true,
				minlength:5
			},
			phone: {  
				required: true,
				minlength:8
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "<?php echo lang("form.2");?>"
			},
			email: {  
				required: "<?php echo lang("form.required");?>",
				email:"<?php echo lang("form.emailerror");?>",
				remote: "<?php echo lang("form.imimember");?>"
			},
			pw: {  
				required: "<?php echo lang("form.required");?>",
				minlength:"<?php echo lang("form.5");?>"
			},
			phone: {  
				required: "<?php echo lang("form.required");?>",
				minlength:"<?php echo lang("form.8");?>"
			}
		} 
	});

	$("#member_edit_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			name: {  
				required: true,  
				minlength: 2
			},
			email: {  
				required: true,
				email:true
			},
			phone: {  
				required: true,
				minlength:8
			}
		},  
		messages: {  
			name: {  
				required: "직원명을 입력해 주세요",  
				minlength: "직원명은 최소 2자리 이상입니다"
			},
			email: {  
				required: "이메일을 입력해주세요",
				email:"이메일 형식으로 입력해주세요"
			},
			phone: {  
				required: "전화번호를 입력해주세요",
				minlength:"전화번호는 최소 8자리 이상입니다"
			}
		} 
	});

	$("#type_add").change(function(){
		if($("#type_add").val()=="other"){
			$("#other_add").css("display","inline");
		} else {
			$("#other_add").hide();		
		}
	});

	$("#type_edit").change(function(){
		if($("#type_edit").val()=="other"){
			$("#other_edit").css("display","inline");
		} else {
			$("#other_edit").hide();		
		}
	});

	$("#upload_add_dialog").dialog({
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:400,
			height: 230,
			modal: true,
			open: function (event, ui) {
				 $(".ui-dialog").css("z-index",9999);
			},
			buttons: {
				'이미지 등록': function() {
					$("#upload_add_form").submit();
				}
			}
	});

	$('#upload_add_form').ajaxForm({
		success:function(data){
			if(data == ""){
				alert("실패");
				alert(data);
			} 
			else {				
				CKEDITOR.instances.sign_member_add.insertHtml( "<img src='"+data+"'>" );				
			} 
			$('#upload_add_dialog').dialog("close");

		}
	});

	$("#upload_edit_dialog").dialog({
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:400,
			height: 230,
			modal: true,
			open: function (event, ui) {
				 $(".ui-dialog").css("z-index",9999);
			},
			buttons: {
				'이미지 등록': function() {
					$("#upload_edit_form").submit();
				}
			}
	});

	$('#upload_edit_form').ajaxForm({
		success:function(data){
			if(data == ""){
				alert("실패");
				alert(data);
			} 
			else {				
				CKEDITOR.instances.sign_member_edit.insertHtml( "<img src='"+data+"'>" );				
			} 
			$('#upload_edit_dialog').dialog("close");

		}
	});

	$("#delete_dialog").dialog({
			title: "직원 정보 삭제",
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width: 600,
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

	$('#member_edit_form').find('#delete_profile').click(function(){

		var member_id = $("#member_edit_form input[id=id]").val();
		var profile_img_name = $('#member_edit_form').find('#profile_img_name').val();
		if(!profile_img_name || $('#member_edit_form').find('#profile_img').hasClass("is-delete")){
			alert("현재 등록된 프로필사진이 없습니다.");
			return false;
		}
		if(confirm("프로필사진이 바로 삭제 됩니다. 삭제하시겠습니까?")){
			$.ajax({
				url: "/adminmember/delete_profile_image",
				type: "POST",
				data: {
					member_id: member_id,
					profile_img_name: profile_img_name
				},
				success: function(data) {
					$('#member_edit_form').find('#profile_img').addClass("is-delete");
					$('#member_edit_form').find('#profile_img').html("<img src='/assets/common/img/no_human.png' style='width:60px;height:60px;'>");
					msg($('#member_edit_form').find("#profile_msg"), "success" ,"삭제 되었습니다.");
				}
			});		
		}
	});

	$('#member_edit_form').find('input[name="profile"]').change(function(e){
		msg($('#member_edit_form').find("#profile_msg"), "info" ,$(this).val());
	});

	$('#member_edit_form').find('#delete_watermark').click(function(){

		var member_id = $("#member_edit_form input[id=id]").val();
		var watermark_img_name = $('#member_edit_form').find('#watermark_img_name').val();
		if(!watermark_img_name || $('#member_edit_form').find('#watermark_img').hasClass("is-delete")){
			alert("현재 등록된 워터마크가 없습니다.");
			return false;
		}
		if(confirm("워터마크가 바로 삭제 됩니다. 삭제하시겠습니까?")){
			$.ajax({
				url: "/adminmember/delete_watermark_image",
				type: "POST",
				data: {
					member_id: member_id,
					watermark_img_name: watermark_img_name
				},
				success: function(data) {
					$('#member_edit_form').find('#watermark_img').addClass("is-delete");
					$('#member_edit_form').find('#watermark_img').html("등록된 워터마크가 없습니다.");
					msg($('#member_edit_form').find("#watermark_msg"), "success" ,"삭제 되었습니다.");
				}
			});		
		}
	});

	$('#member_edit_form').find('input[name="watermark"]').change(function(e){
		msg($('#member_edit_form').find("#watermark_msg"), "info" ,$(this).val());
	});

<?php if($type=='admin'){?>
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
		items: ".is_sortable",
		helper: fixHelper,
		update: function (event, ui) {
			var i=1;
			$("#sort_list").find("tr").each(function(){
				$.get("/adminmember/sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
					
				});
				i++;
			});
		}
	}).disableSelection();
<?php }?>
});

/**
 * 삭제시 등록된 정보를 다른 값으로 변경하는 기능을 추가하여야 한다.
 */
function data_delete(id,name){
	$("#delete_label").html(name);
	$("#delete_id").val(id);
	
	$.getJSON("/adminmember/get_others_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
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

function member_delete(id){
	if(confirm("해당 회원의 모든 정보가 삭제 됩니다\n\n삭제 하시겠습니까?")){
		location.href="/adminmember/delete_all/"+id;
	}
}

function member_pay(id){
	
	$("#member_id").val(id);
	$("#pay_setting_id").val("");

	$.ajax({
		url: "/adminmember/get_member_pay",
		type: "POST",
		dataType: "json",
		cash: false,
		async: false,
		data: {
			member_id: id
		},
		success: function(data) {
			var table = "";
				table += '<table class="table" style="margin-bottom:0px;">';
				table +=	'<tr>';
				table +=		'<th class="text-center">주문번호</th>';
				table +=		'<th class="text-center">상품명</th>';
				table +=		'<th class="text-center">이용가능일</th>';
				table +=		'<th class="text-center">등록가능횟수</th>';
				table +=		'<th class="text-center">결제금액</th>';
				table +=		'<th class="text-center">시작일</th>';
				table +=		'<th class="text-center">종료일</th>';
				table +=		'<th class="text-center"><?php echo lang("site.status");?></th>';
				table +=	'</tr>';

			$.each(data, function(key, val) {
				val['price'] = (val['price']=='0') ? "무료" : val['price']+"원";
				val['state'] = (val['state']=='Y') ? "결제완료" : "";
				val['state'] = (val['is_admin']=='Y') ? "관리자지급" : val['state'];
				table +=	'<tr>';
				table +=		'<td class="text-center">'+val['id']+'</td>';
				table +=		'<td class="text-center">'+val['order_name']+'</td>';
				table +=		'<td class="text-center">'+val['use_day']+'일</td>';
				table +=		'<td class="text-center">'+val['use_count']+'회</td>';
				table +=		'<td class="text-center">'+number_format(val['price'])+'</td>';
				table +=		'<td class="text-center">'+val['start_date']+'</td>';
				table +=		'<td class="text-center">'+val['end_date']+'</td>';
				table +=		'<td class="text-center">'+val['state']+'</td>';
				table +=	'</tr>';
			});

			if(data==''){
				table +=	'<tr>';
				table +=		'<td class="text-center" colspan="8"><?php echo lang("msg.nodata");?></td>';
				table +=	'</tr>';			
			}

			table +='</table>';
			$("#pay_list").html(table);

		}
	});	
}

function give_member_pay(member_id,pay_setting_id){
	if(!pay_setting_id){
		alert("결제서비스를 선택해주세요.");
		$("#pay_setting_id").focus();
		return false;
	}
	if(confirm("서비스를 적용하시겠습니까?")){
		$.ajax({
			url: "/adminmember/give_member_pay",
			type: "POST",
			dataType: "json",
			data: {
				member_id: member_id,
				pay_setting_id: pay_setting_id
			},
			success: function(data) {
				member_pay(member_id);
			}
		});
	}
}

function form_reset(type){
	$("#general_add_form")[0].reset();
	$("#general_add_form").validate().resetForm();

	$("#biz_add_form")[0].reset();
	$("#biz_add_form").validate().resetForm();

	$("#admin_add_form")[0].reset();
	$("#admin_add_form").validate().resetForm();
}

function get_gugun(obj){
	$.getJSON("/address/get_gugun/admin/"+encodeURI(obj.value)+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>구군 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["parent_id"]+"'>"+val["gugun"]+"</option>";
		});
		$(obj).next().html(str);
	});

	$(obj).next().next().html("<option value=''>읍면동 선택</option>");
}

function get_dong(obj){
	$.getJSON("/address/get_dong/admin/"+obj.value+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>읍면동 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["id"]+"'>"+val["dong"]+"</option>";
		});
		$(obj).next().html(str);
	});
}

function apply_area(obj){
	var sido = $(obj).prev().prev().prev();
	var gugun = $(obj).prev().prev();
	var dong = $(obj).prev();
	var address_id = $(obj).next();
	var area_td = $(obj).parents("tr").next().find("td");

	if(!sido.val()){
		alert("시도를 선택 해주세요");
		sido.focus();
		return false;
	}
	if(!gugun.val()){
		alert("구군을 선택 해주세요");
		gugun.focus();
		return false;
	}
	if(!dong.val()){

		$.getJSON("/address/get_dong/admin/"+gugun.val()+"/"+Math.round(new Date().getTime()),function(data){

			$.each(data, function(key, val) {

				if(area_td.find("#"+val['id']).length > 0){
					if(area_td.find("#"+val['id']).val()){
						return true;
					}
				}

				var area_button = '<button type="button" class="btn btn-default" style="margin:2px 2px;" onclick="$(this).remove()">'+val['sido']+' '+val['gugun']+' '+val['dong']+' <i class="fa fa-minus-square" style="color:#d84a38"></i><input type="hidden" id="'+val['id']+'" name="permit_area[]" value="'+val['id']+'" /></button>';
				area_td.html(area_td.html() + area_button);
				
			});

		});

	}
	else{
		$.getJSON("/address/get/"+dong.val()+"/"+Math.round(new Date().getTime()),function(data){

			if(area_td.find("#"+data['id']).length > 0){
				if(area_td.find("#"+data['id']).val()){
					alert("이미 추가한 지역 입니다");
					return false;
				}
			}

			var area_button = '<button type="button" class="btn btn-default" style="margin:2px 2px;" onclick="$(this).remove()">'+data['sido']+' '+data['gugun']+' '+data['dong']+' <i class="fa fa-minus-square" style="color:#d84a38"></i><input type="hidden" id="'+data['id']+'" name="permit_area[]" value="'+data['id']+'" /></button>';
			area_td.html(area_td.html() + area_button);
		});
	}
}
</script>

 <div class="row">
  <div class="col-lg-12">
	<h3 class="page-title">
			회원 관리<small>목록 및 등록,수정,삭제</small>
	</h3>
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<a href="/adminhome/index"><?php echo lang("menu.home");?></a>
				<i class="fa fa-angle-right"></i> 회원 관리
			</li>
			<!--li>
				<a href="#">Dashboard</a>
			</li-->
		</ul>
		<div class="page-toolbar">
            <div class="dropdown input-inline">
				<?php if($config->IS_DEMO){?>
                <a href="#" class="btn btn-default" onclick="javascript:alert('데모사이트는 사용할 수 없습니다.');">
				<?php } else {?>
                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">				
				<?php }?>
                    <i class="icon-envelope"></i> 문자 보내기
                </a>
                <ul class="dropdown-menu dropdown-menu-default">
					<li>
						<a href="#" onclick="open_sms();">선택 전송</a>
					</li>
                    <li class="divider"></li>
                    <li>
                        <a href="#" onclick="open_sms('<?php echo $type?>');">전체 전송</a>
                    </li>

                </ul>
            </div>
			<button class="btn blue" data-toggle="modal" data-target="#add_dialog">등록</button>
		</div>
	</div>
  </div>
</div><!-- /.row -->

<div class="note note-success">
	<p>
		정보를 변경하시려면 이름을 클릭해 주세요.
	</p>
</div>
<?php if($type!="admin"){?>
<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-body text-center">
			<!-- BEGIN FORM-->
			<?php echo form_open("adminmember/index/".$type,Array("id"=>"search_form","class"=>"form-inline","method"=>"get"))?>
				<div class="input-group">
					<input type="text" class="form-control input-xlarge" name="keyword" placeholder="회원명, <?php echo lang("site.email");?>, 전화번호<?php if($type=="biz") echo ", 사업자명, 대표자, ".lang("site.biznum").", 중개사등록번호";?>" autocomplete="off"  value="<?php echo $this->input->get("keyword");?>" style="font-size:12px;"/>
				</div>
				<button type="submit" class="btn btn-warning"><?php echo lang("site.search");?></button>
			<?php echo form_close();?>
			<!-- END FORM-->			
		  </div>
		</div>
	</div>
</div>
<?php }?>

<div role="tabpanel">
	<!-- Nav tabs -->
	<?php if($type=="general"){?><h4>일반회원 (<?php echo number_format($total)?>)</h4><?php }?>
	<?php if($type=="biz"){?><h4>사업자회원 (<?php echo number_format($total)?>)</h4><?php }?>
	<?php if($type=="admin"){?><h4>직원 (<?php echo number_format($total)?>)</h4><?php }?>
</div>

<table class="table table-bordered table-striped table-condensed flip-content">
	<thead class="flip-content">
		<tr>
			<?php if($type=='admin'){?>
			<th class="text-center" style="width:25px;"><i class="fa fa-arrows"></i></th>
			<?php }?>
            <th style="width:25px;"><input type='checkbox' id='check_all'/></th>
			<th class="text-center"><?php echo lang("menu.login");?></th>
			<th class="text-center"><?php echo lang("site.name");?></th>
			<th class="text-center">연락</th>
			<th class="text-center"><?php echo lang("product");?>수</th>
			<th class="text-center">가입일</th>
			<?php if($type!='admin'){?>
			<th class="text-center">고객추가</th>
			<?php }?>
			<?php if($type!='admin' && $config->USE_PAY){?>
			<th class="text-center">결제</th>
			<?php }?>
			<th style="width:30px;">&nbsp;</th>
		</tr>
	</thead>
	<tbody id="sort_list">
		<?php foreach($query as $val){?>
		<tr class="is_sortable" data-id="<?php echo $val->id;?>">
			<?php if($type=='admin'){?>
			<td class="text-center"><i class="fa fa-sort"></i></td>
			<?php }?>
            <td>
                <input type='checkbox' class='checkbox' name='check_id[]' value='<?php echo $val->id?>'/>
            </td>
			<td><?php if($val->valid=="Y"){echo "가능";} else {echo "불가능";}?></td>
			<td>
				<?php if($val->biz_name!="" && $val->biz_name!="0"){echo "<small>" . $val->biz_name . "</small></br>"; }?>
				<a href="#" onclick="member_edit('<?php echo $val->id;?>','member_edit_form');"  data-toggle="modal" data-target="#edit_member_dialog"><?php echo $val->name;?></a>

			</td>
			<td>
				<?php echo $val->email;?><br/><a href="tel:<?php echo $val->phone;?>"><i class="fa fa-phone-square"></i> <?php echo $val->phone;?></a>
				<?php if($val->tel!=""){?><br/><a href="tel:<?php echo $val->tel;?>"><i class="fa fa-phone-square"></i> <?php echo $val->tel;?><?php }?></a>
			</td>
			<td><?php echo $val->cnt;?>건</td>
			<td><small><?php echo $val->date;?></small></td>
			<?php if($type=='admin'){?>
			<td><button class="btn btn-xs btn-danger" onclick="data_delete('<?php echo $val->id;?>','<?php echo $val->name;?>');"><i class="fa fa-trash-o"></i></button></td>
			<?php }?>
			<?php if($type!='admin'){?>
			<td><a class="btn btn-success btn-sm" href="/admincontact/add_flashdata/member/<?php echo $val->id;?>">고객전환</a>
			<?php }?></td>
			<?php if($type!='admin' && $config->USE_PAY){?>
			<td><a href="#" onclick="member_pay('<?php echo $val->id;?>');" data-toggle="modal" data-target="#member_pay_dialog">결제내역</a>
			</td>
			<?php }?>
			<?php if($type!='admin'){?>
			<td><button class="btn btn-xs btn-danger" onclick="member_delete('<?php echo $val->id;?>');"><i class="fa fa-trash-o"></i></button></td>
			<?php }?>
		</tr>
		<?php }?>
		<?php if(count($query) == 0){?>
		<tr>
			<td class="text-center" colspan="9"><?php echo lang("msg.nodata");?></td>
		</tr>		
		<?php }?>
	</tbody>
</table>

<div class="row text-center">
	<div class="col-sm-12">
		<ul class="pagination" style="float:none;">
			<?php echo $pagination;?>
		</ul>
	</div>
</div>

<!-- MEMBER ADD FORM -->
<div id="add_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<?php if($type=="general"){?> <h4 class="modal-title" id="myModalLabel">일반회원 등록</h4><?php }?>
		<?php if($type=="biz"){?> <h4 class="modal-title" id="myModalLabel">사업자회원 등록</h4><?php }?>
		<?php if($type=="admin"){?> <h4 class="modal-title" id="myModalLabel">직원 등록</h4><?php }?>
      </div>
      <div class="modal-body">

		<div role="tabpanel">
			<!-- Nav tabs -->

			<!-- Tab panes -->
			<div class="tab-content">
				<!-- 일반회원 -->
				<div role="tabpanel" class="tab-pane <?php if($type=="general") echo "active";?>" id="general">
					<?php echo form_open_multipart("adminmember/add_action",Array("id"=>"general_add_form"))?>
					<input type="hidden" name="type" value="general"/>
					<table class="table table-bordered table-striped-left table-condensed flip-content">
						<tbody>
							<tr>
								<td class="text-center vertical-middle"><?php echo lang("site.email");?> / <?php echo lang("site.pw");?></td>
								<td>
									<input type="text" class="form-control input-medium input-inline" name="email" placeholder="이메일"/>
									<input type="password" class="form-control input-medium input-inline" name="pw" placeholder="암호"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle"><?php echo lang("site.name");?></td>
								<td>
									<input type="text" class="form-control input-small" name="name" placeholder="<?php echo lang("site.name");?>"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle"><?php echo lang("site.tel");?></td>
								<td>
									<input type="text" class="form-control input-medium input-inline" name="phone" placeholder="<?php echo lang("site.mobile");?>" />
									<input type="text" class="form-control input-medium input-inline" name="tel" placeholder="<?php echo lang("site.tel");?>"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">허용 IP</td>
								<td>
									<textarea class="form-control" rows="3" name="permit_ip" placeholder="엔터 값으로 구분"></textarea>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle" rowspan="2" width="120">허용 지역</td>
								<td>
									<select class="form-control input-small input-inline" onchange="get_gugun(this)">
										<option value=''>시도 선택</option>
									<?php foreach($sido as $val){?>
										<option value="<?php echo $val->sido?>"><?php echo $val->sido?></option>
									<?php }?>
									</select>
									<select class="form-control input-small input-inline" onchange="get_dong(this)">
										<option value=''>구군 선택</option>
									</select>
									<select class="form-control input-small input-inline">
										<option value=''>읍면동 선택</option>
									</select>
									<button type="button" class="btn btn-default" onclick="apply_area(this)">적용 <i class="fa fa-plus-square" style="color:#4B8DF8"></i></button>
								</td>
							</tr>
							<tr>
								<td style="background-color:white"></td>
							</tr>
						</tbody>
					</table>							  
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
						<button type="submit" class="btn btn-primary">일반회원 등록</button>
					</div>
					<?php echo form_close();?>
				</div>

				<!-- 중개소회원 -->
				<div role="tabpanel" class="tab-pane <?php if($type=="biz") echo "active";?>"" id="biz">
				<?php echo form_open_multipart("adminmember/add_action",Array("id"=>"biz_add_form"))?>
					<input type="hidden" name="type" value="biz"/>
					<table class="table table-bordered table-striped-left table-condensed flip-content">
						<tbody>
							<tr>
								<td class="text-center vertical-middle"><?php echo lang("site.email");?> / 암호</td>
								<td>
									<input type="text" class="form-control input-medium input-inline" name="email" placeholder="<?php echo lang("site.email");?>"/>
									<input type="password" class="form-control input-medium input-inline" name="pw" placeholder="<?php echo lang("site.pw");?>"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">사업자명</td>
								<td>
									<input type="text" class="form-control input-medium" name="biz_name" placeholder="사업자명"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">대표자</td>
								<td>
									<input type="text" class="form-control input-small" name="biz_ceo" placeholder="<?php echo lang("site.ceo");?>"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle"><?php echo lang("site.biznum");?></td>
								<td>
									<input type="text" class="form-control input-large" name="biz_num" placeholder="<?php echo lang("site.biznum");?>"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">권한/자격</td>
								<td>
									<select name="biz_auth" class="form-control input-medium">
										<option value="" selected>권한/자격</option>
										<?php if($config->INSTALLATION_FLAG){?>
										<option value="0">일반사업자(중개업자가 아님)</option>
										<?php } ?>
										<option value="1">대표공인중개사</option>
										<option value="2">소속공인중개사</option>
										<option value="3">중개보조원</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle"><?php echo lang("site.renum");?></td>
								<td>
									<input type="text" class="form-control input-large" name="re_num" name="re_num" placeholder="<?php echo lang("site.renum");?>"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle" rowspan="2">사업자주소</td>
								<td>
									<input type="text" id="address" name="address" class="form-control input-xlarge input-inline" placeholder="사업자주소" readonly/> <button type="button" class="btn btn-default" onclick="get_postcode('biz_add_form')">주소검색</button>								
								</td>
							</tr>
							<tr>
								<td style="background-color:white">
									<input type="text" id="address_detail" name="address_detail" class="form-control input-xlarge" placeholder="상세주소"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle"><?php echo lang("site.name");?></td>
								<td>
									<input type="text" class="form-control input-small" name="name" placeholder="<?php echo lang("site.name");?>"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle"><?php echo lang("site.tel");?></td>
								<td>
									<input type="text" class="form-control input-medium input-inline" name="phone" placeholder="<?php echo lang("site.mobile");?>"/>
									<input type="text" class="form-control input-medium input-inline" name="tel" placeholder="<?php echo lang("site.tel");?>"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">카카오톡아이디</td>
								<td>
									<input type="text" class="form-control input-large" name="kakao" placeholder="카카오톡아이디"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">프로필 사진<br/>(60 * 60 픽셀)</td>
								<td>
									<input type="file" class="form-control input-xlarge" style="height:auto;" name="profile" placeholder="프로필 사진"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">워터마크<br/>(200 * 60 픽셀)</td>
								<td>
									<input type="file" class="form-control input-xlarge" style="height:auto;" name="watermark" placeholder="프로필 사진"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">워터마크위치</td>
								<td>
									<select name="watermark_position_vertical" class="form-control input-inline input-small">
										<option value="middle" selected>중앙</option>
										<option value="top">위</option>
										<option value="bottom">아래</option>
									</select>
									<select name="watermark_position_horizontal" class="form-control input-inline input-small">
										<option value="center" selected>중앙</option>
										<option value="left">왼쪽</option>
										<option value="right">오른쪽</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">허용 IP</td>
								<td>
									<textarea class="form-control" rows="3" name="permit_ip" placeholder="엔터 값으로 구분"></textarea>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle" rowspan="2" width="120">허용 지역</td>
								<td>
									<select class="form-control input-small input-inline" onchange="get_gugun(this)">
										<option value=''>시도 선택</option>
									<?php foreach($sido as $val){?>
										<option value="<?php echo $val->sido?>"><?php echo $val->sido?></option>
									<?php }?>
									</select>
									<select class="form-control input-small input-inline" onchange="get_dong(this)">
										<option value=''>구군 선택</option>
									</select>
									<select class="form-control input-small input-inline">
										<option value=''>읍면동 선택</option>
									</select>
									<button type="button" class="btn btn-default" onclick="apply_area(this)">적용 <i class="fa fa-plus-square" style="color:#4B8DF8"></i></button>
								</td>
							</tr>
							<tr>
								<td style="background-color:white"></td>
							</tr>
						</tbody>
					</table>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
						<button type="submit" class="btn btn-primary">사업자/중개소회원 등록</button>
					</div>
					<?php echo form_close();?>
				</div>

				<!-- 직원 -->
				<div role="tabpanel" class="tab-pane <?php if($type=="admin") echo "active";?>" id="admin">
				<?php echo form_open_multipart("adminmember/add_action",Array("id"=>"admin_add_form"))?>
					<input type="hidden" name="type" value="admin"/>
					<table class="table table-bordered table-striped-left table-condensed flip-content">
						<tbody>
							<tr>
								<td class="text-center vertical-middle"><?php echo lang("site.email");?> / <?php echo lang("site.pw");?></td>
								<td>
									<input type="text" class="form-control input-medium input-inline" name="email" placeholder="<?php echo lang("site.email");?>"/>
									<input type="password" class="form-control input-medium input-inline" name="pw" placeholder="<?php echo lang("site.pw");?>"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle"><?php echo lang("site.name");?></td>
								<td>
									<input type="text" class="form-control input-small input-inline" name="name" placeholder="<?php echo lang("site.name");?>"/>
									<input type="text" class="form-control input-inline color" name="color" placeholder="색상표시"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle"><?php echo lang("site.tel");?></td>
								<td>
									<input type="text" class="form-control input-medium input-inline" name="phone" placeholder="<?php echo lang("site.mobile");?>"/>
									<input type="text" class="form-control input-medium input-inline" name="tel" placeholder="<?php echo lang("site.tel");?>"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">카카오톡아이디</td>
								<td>
									<input type="text" class="form-control input-large" name="kakao" placeholder="카카오톡아이디"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">직원 권한</td>
								<td>
									<select class="form-control input-small select2me help" id="auth_id" name="auth_id">
									<?php foreach($member_auth as $val){?>
										<option value="<?php echo $val->id;?>"><?php echo $val->auth_name;?></option>
									<?php }?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">프로필 사진<br/>(60 * 60 픽셀)</td>
								<td>
									<input type="file" class="form-control input-xlarge" style="height:auto;" name="profile" placeholder="프로필 사진"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">워터마크<br/>(200 * 60 픽셀)</td>
								<td>
									<input type="file" class="form-control input-xlarge" style="height:auto;" name="watermark" placeholder="워터마크"/>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">워터마크위치</td>
								<td>
									<select name="watermark_position_vertical" class="form-control input-inline input-small">
										<option value="middle" selected>중앙</option>
										<option value="top">위</option>
										<option value="bottom">아래</option>
									</select>
									<select name="watermark_position_horizontal" class="form-control input-inline input-small">
										<option value="center" selected>중앙</option>
										<option value="left">왼쪽</option>
										<option value="right">오른쪽</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle">직원 소개글</td>
								<td>
									<textarea class="form-control" name="bio" rows="5"></textarea>
								</td>
							</tr>
							<tr>
								<td class="text-center vertical-middle" width="120">시그니쳐</td>
								<td>
									<textarea id="sign_member_add" target-dialog="upload_add_dialog" name="sign_add" class="form-control" rows="5"></textarea>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
						<button type="submit" class="btn btn-primary">직원 등록</button>
					</div>
					<script>
						CKEDITOR.replace( 'sign_member_add', {customConfig: '/ckeditor/agent_config.js'});
					</script>
				<?php echo form_close();?>
				</div>
			</div>
		</div>	
      </div>
    </div>
  </div>
</div>
<!-- MEMBER ADD FORM -->

<div id="upload_add_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;">
	<?php echo form_open_multipart("adminproduct/upload_action","id='upload_add_form' autocomplete='off'");?>
	<div class="help-block">* 큰 이미지는 넓이(폭)이 890픽셀로 조정됩니다.</div>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
	<?php echo form_close();?>
</div>

<!-- MEMBER UPDATE FORM -->
<?php echo form_open_multipart("adminmember/edit_action/member",Array("id"=>"member_edit_form"))?>
<input type="hidden" id="id" name="id">
<input type="hidden" id="profile_img_name" name="profile_img_name">
<input type="hidden" id="watermark_img_name" name="watermark_img_name">
<div id="edit_member_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">정보 수정</h4>
			</div>
			<div class="modal-body">
				<!--암호를 입력하지 않으면 암호는 변경되지 않습니다-->
				<table class="table table-bordered table-striped-left table-condensed flip-content">
					<tbody>
						<tr>
							<td class="text-center vertical-middle"><?php echo lang("site.email");?> / 암호</td>
							<td>
								<input type="text" class="form-control input-medium input-inline" id="email" name="email" placeholder="<?php echo lang("site.email");?>" readonly/>
								<input type="text" class="form-control input-medium input-inline" name="pw" placeholder="<?php echo lang("site.pw");?>"/>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">로그인가능여부</td>
							<td>
								<select class="form-control input-small select2me inline" id="valid" name="valid">
									<option value="Y" selected>로그인가능</option>
									<option value="N">로그인불가능</option>
								</select>
								<span class="general_biz_field help-inline">로그인 만료일 설정 <input type="input" class="form-control input-small inline date-picker" id="expire_date" name="expire_date" placeholder="로그인 만료일"/></span>
							</td>
						</tr>
						<tr class="biz_field">
							<td class="text-center vertical-middle">사업자명</td>
							<td>
								<input type="text" class="form-control input-medium" id="biz_name" name="biz_name" placeholder="사업자명"/>
							</td>
						</tr>
						<tr class="biz_field">
							<td class="text-center vertical-middle">대표자</td>
							<td>
								<input type="text" class="form-control input-small" id="biz_ceo" name="biz_ceo" placeholder="<?php echo lang("site.ceo");?>"/>
							</td>
						</tr>
						<tr class="biz_field">
							<td class="text-center vertical-middle"><?php echo lang("site.biznum");?></td>
							<td>
								<input type="text" class="form-control input-large" id="biz_num" name="biz_num" placeholder="<?php echo lang("site.biznum");?>"/>
							</td>
						</tr>
						<tr class="biz_field">
							<td class="text-center vertical-middle">권한/자격</td>
							<td>
								<select id="biz_auth" name="biz_auth" class="form-control input-medium">
									<option value="" selected>권한/자격</option>
									<?php if($config->INSTALLATION_FLAG){?>
									<option value="0">일반사업자(중개업자가 아님)</option>
									<?php } ?>
									<option value="1">대표공인중개사</option>
									<option value="2">소속공인중개사</option>
									<option value="3">중개보조원</option>
								</select>
							</td>
						</tr>
						<tr class="biz_field">
							<td class="text-center vertical-middle"><?php echo lang("site.renum");?></td>
							<td class="vertical-middle">
								<input type="text" class="form-control input-large" id="re_num" name="re_num" placeholder="<?php echo lang("site.renum");?>"/>
							</td>
						</tr>
						<tr class="biz_field">
							<td class="text-center vertical-middle" rowspan="2">사업자주소</td>
							<td>
								<input type="text" id="address" name="address" class="form-control input-xlarge input-inline" placeholder="사업자주소" readonly/> <button type="button" class="btn btn-default" onclick="get_postcode('member_edit_form')">주소검색</button>
							</td>
						</tr>
						<tr class="biz_field">
							<td style="background-color:white">
								<input type="text" id="address_detail" name="address_detail" class="form-control input-xlarge" placeholder="상세주소"/>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">회원명</td>
							<td>
								<input type="text" class="form-control input-small input-inline" id="name" name="name" placeholder="<?php echo lang("site.name");?>"/>
								<input type="text" class="form-control input-inline admin_field color" id="color" name="color" placeholder="색상표시"/>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">전화</td>
							<td>
								<input type="text" class="form-control input-medium input-inline" id="phone" name="phone" placeholder="<?php echo lang("site.mobile");?>"/>
								<input type="text" class="form-control input-medium input-inline" id="tel" name="tel" placeholder="<?php echo lang("site.tel");?>"/>
							</td>
						</tr>
						<tr>
							<td class="text-center vertical-middle">카카오톡아이디</td>
							<td>
								<input type="text" class="form-control input-large" id="kakao" name="kakao" placeholder="카카오톡아이디"/>
							</td>
						</tr>
						<tr class="admin_field">
							<td class="text-center vertical-middle">직원 권한</td>
							<td>
								<select class="form-control input-small select2me" id="auth_id" name="auth_id">
								<?php foreach($member_auth as $val){?>
									<option value="<?php echo $val->id;?>"><?php echo $val->auth_name;?></option>
								<?php }?>
								</select>
							</td>
						</tr>
						<tr class="biz_admin_field">
							<td class="text-center vertical-middle">프로필 사진<br/>(60 * 60 픽셀)</td>
							<td>
								<div id="profile_img"></div>
								<div id="profile_msg"></div>
								<div class="btn btn-default btn-file help margin-top-10">프로필 사진 업로드<input type="file" id="profile" name="profile"></div>
								<div id="delete_profile" class="btn btn-primary"><i class="fa fa-trash-o"></i> 프로필 사진 삭제</div>
							</td>
						</tr>
						<tr class="biz_admin_field">
							<td class="text-center vertical-middle">워터마크<br/>(200 * 60 픽셀)</td>
							<td>
								<div id="watermark_img"></div>
								<div id="watermark_msg"></div>
								<div class="btn btn-default btn-file help margin-top-10">워터마크 업로드<input type="file" id="watermark" name="watermark"></div>
								<div id="delete_watermark" class="btn btn-primary"><i class="fa fa-trash-o"></i> 워터마크 삭제</div>
							</td>
						</tr>
						<tr class="biz_admin_field">
							<td class="text-center vertical-middle">워터마크위치</td>
							<td>
								<select id="watermark_position_vertical" name="watermark_position_vertical" class="form-control input-small input-inline">
									<option value="middle" selected>중앙</option>
									<option value="top">위</option>
									<option value="bottom">아래</option>
								</select>
								<select id="watermark_position_horizontal" name="watermark_position_horizontal" class="form-control input-small input-inline">
									<option value="center" selected>중앙</option>
									<option value="left">왼쪽</option>
									<option value="right">오른쪽</option>
								</select>
							</td>
						</tr>
						<tr class="admin_field">
							<td class="text-center vertical-middle">직원 소개글</td>
							<td>
								<textarea class="form-control" id="bio" name="bio" rows="5"></textarea>
							</td>
						</tr>
						<tr class="admin_field">
							<td class="text-center vertical-middle" width="120">시그니쳐</td>
							<td>
								<textarea id="sign_member_edit" target-dialog="upload_edit_dialog" name="sign_edit" class="form-control" rows="5"></textarea>
							</td>
						</tr>
						<?php if($config->GONGSIL_FLAG){?>
						<tr>
							<td class="text-center vertical-middle" width="150">스마트폰 고유번호</td>
							<td>
								<input type="text" class="form-control input-large" id="uuid" name="uuid" placeholder="스마트폰 고유번호"/>
							</td>
						</tr>
						<?php }?>
						<tr class="general_biz_field">
							<td class="text-center vertical-middle">허용 IP</td>
							<td>
								<textarea class="form-control" rows="3" id="permit_ip" name="permit_ip" placeholder="엔터 값으로 구분"></textarea>
							</td>
						</tr>
						<tr class="general_biz_field">
							<td class="text-center vertical-middle" rowspan="2" width="120">허용 지역</td>
							<td>
								<select class="form-control input-small input-inline" onchange="get_gugun(this)">
									<option value=''>시도 선택</option>
								<?php foreach($sido as $val){?>
									<option value="<?php echo $val->sido?>"><?php echo $val->sido?></option>
								<?php }?>
								</select>
								<select class="form-control input-small input-inline" onchange="get_dong(this)">
									<option value=''>구군 선택</option>
								</select>
									<select class="form-control input-small input-inline">
										<option value=''>읍면동 선택</option>
									</select>
								<button type="button" class="btn btn-default" onclick="apply_area(this)">적용 <i class="fa fa-plus-square" style="color:#4B8DF8"></i></button>
							</td>
						</tr>
						<tr>
							<td style="background-color:white" id="permit_area" colspan="2"></td>
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
<script>
	CKEDITOR.replace( 'sign_member_edit', {customConfig: '/ckeditor/agent_config.js'});
</script>
<?php echo form_close();?>
<!-- MEMBER UPDATE FORM -->

<div id="upload_edit_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;">
	<?php echo form_open_multipart("adminproduct/upload_action","id='upload_edit_form' autocomplete='off'");?>
	<div class="help-block">* 큰 이미지는 넓이(폭)이 890픽셀로 조정됩니다.</div>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
	<?php echo form_close();?>
</div>

<div id="delete_dialog" title="직원 정보 삭제" style="display:none;">
<div class="help-block">* 삭제 시 물건을 다른 직원정보로 변경해야 합니다.</div>
<?php echo form_open("adminmember/delete_action",Array("id"=>"delete_form"))?>
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

<!-- MEMBER PAY FORM -->
<?php echo form_open_multipart("",Array("id"=>"member_pay_form"))?>
<input type="hidden" id="member_id" name="member_id">
<div id="member_pay_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width:850px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">결제내역</h4>
      </div>
      <div class="modal-body" id="pay_list" style="max-height:500px;overflow-y:auto;"></div>
	  <div class="modal-footer">
		<div class="form-group">
			<span class="sorting" style="padding-top:10px;font-size:14px;"><strong>결제서비스</strong></span>
			<span class="sorting" style="margin:0 20px;">
				<select id="pay_setting_id" name="pay_setting_id" class="search_item form-control">
					<option value="">결제서비스 선택</option>
					<?php foreach($pay_setting as $val){?>
					<option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
					<?php }?>
				</select>
			</span>
			<span class="sorting">
				<button onclick="give_member_pay($('#member_id').val(),$('#pay_setting_id').val())" type="button" class="btn btn-primary">서비스 적용</button>
			</span>
		</div>
      </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>
<!-- MEMBER PAY FORM -->

<!-- SMS FORM-->
<?php echo form_open_multipart("/adminsms/select_send",Array("id"=>"sms_form","class"=>"form-horizontal"))?>
<input type="hidden" name="send_page" value="member"/>
<input type="hidden" name="send_all"/>
<div id="check_id_clone"></div>
<div id="sms_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-smsdialog modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">문자 보내기</h4>
			</div>
			<div class="modal-body">
				<div class="form">
					<div class="form-body">
						<div class="form-group alert alert-info" role="alert">
							<div style="font-size:16px;">
								전송 건 : <span id="send_count">0</span>건 (차감 건 : <span id="minus_count">0</span>건)
							</div>
							<div class="margin-top-10">* 단문 : 건당1, 장문 : 건당3, 포토 : 건당10 차감</div>
							<div class="margin-top-10">* 휴대전화가 아니거나 중복된 연락처는 건수에서 제외되어 발송 됩니다.</div>
						</div>
						<div class="form-group" id="sms_result"></div>
						<div class="form-group">
							<div data-toggle="buttons">
								<label class="btn btn-default active">
									<input type="radio" name="sms_type" value="A" checked/><strong>단문(SMS)</strong>
								</label>
								<label class="btn btn-default">
									<input type="radio" name="sms_type" value="C"/><strong>장문(LMS)</strong>
								</label>
								<label class="btn btn-default">
									<input type="radio" name="sms_type" value="D"/><strong>포토(MMS)</strong>
								</label>
							</div>
						</div>
						<div class="form-group display-none lms">
							<input class="form-control" type="text" name="sms_subject" placeholder="제목을 입력하세요." maxlength="30">
						</div>
						<div class="form-group display-none mms">
							<input type="file" class="form-control input-xlarge" name="mms_file" placeholder="이미지파일" style="height:auto" accept="image/jpg, image/jpeg"/>
							</br>* 1MB 이하 파일, JPG형식의 파일만 전송 가능합니다.
						</div>
						<div class="form-group">
							<textarea class="form-control" rows="8" name="sms_msg"></textarea>
						</div>
						<div class="form-group text-left">
							<span class="remaining">
								<span class="count">0</span>/<span class="maxcount">80</span>byte
							</span>
						</div>
						<div class="form-group">
							<div class="inline" data-toggle="buttons">
								<label class="btn btn-default active">
									<input type="radio" name="reserve" value="no" checked> 즉시 발송
								</label>
								<label class="btn btn-default">
									<input type="radio" name="reserve" value="yes"> 예약 발송
								</label>
							</div>
							<div id="reserve_date" class="inline" style="display:none">
								<input type="text" name="r_date" class="form-control input-inline input-small date-picker" placeholder="날짜" autocomplete="off"/>
								<input type="text" name="r_time" class="form-control input-inline input-small timepicker-24" placeholder="시간" autocomplete="off"/>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary pull-left" onclick="javascript:self_send();">나에게 보내기</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
				<button type="submit" class="btn btn-primary">전송</button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close();?>
<link rel="stylesheet" type="text/css" href="/assets/plugin/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/locales/bootstrap-datepicker.kr.js"></script>
<script type="text/javascript" src="/assets/plugin/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="/assets/admin/js/sms.js"></script>
<!-- SMS FORM-->

<!-- DAUM POST LAYER -->
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    function get_postcode(form) {
        new daum.Postcode({
            oncomplete: function(data) {

				var fullAddr = '';
                var extraAddr = '';

                if (data.userSelectedType === 'R') {
                    fullAddr = data.roadAddress;

                } else {
                    fullAddr = data.jibunAddress;
                }

                if(data.userSelectedType === 'R'){
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                $("#"+form).find('#address').val(fullAddr);
				$("#"+form).find('#address_detail').focus();
            }
        }).open();
    }
</script>