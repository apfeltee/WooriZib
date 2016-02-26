<?php 
	$this->load->helper('contact');	
?>
<link href="/assets/admin/css/todo.css" rel="stylesheet" type="text/css"/>
<style>
.modal-smsdialog{ width:88%;max-width: 500px;}
.modal-dialog{ width:98%;max-width: 600px;}
</style>
<script>

$(function() {
	
	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	get_group();
	init_form();

    $('#add_form').ajaxForm({
    	beforeSubmit:function(data){
    		
    		$("#add_form").validate({  
		        errorElement: "span",
		        wrapper: "span",  
				rules: {
					group_name: {  
						required: true,  
						minlength: 2
					}
				},  
				messages: {  
					group_name: {  
						required: "그룹 이름을 입력합니다",  
						minlength: "그룹 이름은 최소 2자리 이상입니다"
					}
				} 
			});
    		if (!$("#add_form").valid()) return false;

    	},
		success:function(data){
			if(data == ""){
				alert("실패");
				alert(data);
			} else {
				get_group();
				$("input[name='group_name']").val("");
			} 
			
			$('#add_dialog').modal('hide');

		}
	});

    $('#edit_form').ajaxForm({
    	beforeSubmit:function(data){
    		
    		$("#edit_form").validate({  
		        errorElement: "span",
		        wrapper: "span",  
				rules: {
					change_name: {  
						required: true,  
						minlength: 2
					}
				},  
				messages: {  
					change_name: {  
						required: "그룹 이름을 입력합니다",  
						minlength: "그룹 이름은 최소 2자리 이상입니다"
					}
				} 
			});

    		if (!$("#edit_form").valid()) return false;
    	},
		success:function(data){
			if(data == ""){
				alert("실패");
				alert(data);
			} else {
				get_group();
				$("input[name='change_name']").val("");
			} 
			
			$('#edit_dialog').modal('hide');

		}
	});

    $('#delete_form').ajaxForm({
    	beforeSubmit:function(data){
    		
    		$("#delete_form").validate({  
		        errorElement: "span",
		        wrapper: "span",  
				rules: {
					replace_id: {  
						required: true
					}
				},  
				messages: {  
					replace_id: {  
						required: "대체할 그룹을 선택해 주세요"
					}
				} 
			});

    		if (!$("#delete_form").valid()) return false;
    	},
		success:function(data){
			if(data == ""){
				alert("실패");
				alert(data);
			} else {
				get_group();
				$("input[name='replace_id']").val("");
			} 
			
			$('#delete_dialog').modal('hide');

		}
    });

    $("#delete_id").change(function(){
    	replace_set();
    });	

    $('#search_form').trigger('submit');

});

function multiview(data,type){
	var text = "";
	var a = data.split("---dungzi---");

	for(i=0;i<a.length;i++){
		if(a[i]!=""){
			var b = a[i].split("--type--");
			var label = "";
			if(type=="email"){
				if(b[0]=="work") label="[업무용]";
				if(b[0]=="personal")  label="[개인용]";
			} else if(type=="phone"){
				if(b[0]=="mobile")  label="[휴대]";
				if(b[0]=="home")  label="[자택]";
				if(b[0]=="office")  label="[회사]";
				if(b[0]=="fax")  label="[팩스]";
				if(b[0]=="etc")  label="[기타]";
			} else if(type=="address"){
				if(b[0]=="work")  label="[직장]";
				if(b[0]=="home")  label="[자택]";
			} else if(type=="homepage"){
				if(b[0]=="work")  label="[회사]";
				if(b[0]=="personal") label="[개인]";
				if(b[0]=="blog")  label="[블로그]";
			}
			
			if(b[1]!=""){
				if(type=="email"){
					text += label +" "+ b[1] + "<br/>";
				} else if(type=="phone"){
					text += label +" <a href=\"tel:"+ b[1] + "\">" + b[1] + "</a><br/>";
				} else if(type=="address"){
					text += label +" "+ b[1] + "<br/>";
				} else if(type=="homepage"){			
					text += label +" <a href=\"http://"+ b[1].replace(/^https?:\/\//,'') + "\" target=\"_blank\">" + b[1].replace(/^https?:\/\//,'') + "</a><br/>";	
				}
			}
			
		}
	}
	return text;
}

function init_form(){
	$('#search_form').ajaxForm( {
		dataType: "json",
		success: function(data){
			var str = "";
			$.each(data, function(rkey, rval) {
				if(rkey=="result"){
					$.each(rval, function(key, val) {
						
						str += "<tr class='text-center'>";
                        str += "    <td>";
                        str += "        <input type='checkbox' class='checkbox' name='check_id[]' value='"+val["id"]+"'/>";
                        str += "    </td>";
						str += "	<td>";
						str += "		<a href='/admincontact/view/"+val["id"]+"'>"+val["name"]+"</a>";
						str += "	</td>";
						str += "	<td>";
						str += "		"+val["organization"] +" "+ val["role"];
						str += "	</td>";
						str += "	<td>";
						str += "		" + multiview(val["email"],"email");
						str += "	</td>";
						str += "	<td>";
						str += "		" + multiview(val["phone"],"phone");
						str += "	</td>";
						str += "	<td class=\"hidden-xs\">";
						str += "		" + multiview(val["homepage"],"homepage");
						str += "	</td>";
						str += "	<td class=\"hidden-xs\">";
						str += "		" + multiview(val["address"],"address");
						str += "	</td>";
						str += "	<td class=\"hidden-xs\">";
						str += "		" + val["cnt"] + "건";
						str += "	</td>";
						str += "	<td class=\"hidden-xs\">";
						str += "		" + val["history_date"];
						str += "	</td>";
						str += "	<td class=\"hidden-xs\">";
						if(val["member_id"]=="<?php echo $this->session->userdata("admin_id");?>" || "<?php echo $this->session->userdata("auth_id");?>"=="1"){
							str += "			<button class=\"btn btn-warning btn-xs\" onclick=\"row_delete('"+val["id"]+"')\">삭제</button>";
						}
						str += "	</td>";
						str += "</tr>";
					});
				}

				if(rkey=="paging"){
					$(".pagination").html(rval);
					$(".pagination").find("a").on("click", function() {
							$("#search_form").attr("action",$(this).attr('href'));
							$('#search_form').trigger('submit');
							return false;
					});
				}
			});

			if(str==""){
				str = "<tr><td class='text-center' colspan='10'><?php echo lang("msg.nodata");?></td></tr>";
			}
			$("#contact_list").html(str);	
		}
	});		
}

var change = "";

<?php 
	$group_id="";
	if(isset($contact["group_id"]))	$group_id = $contact["group_id"];
?>
function get_group(){

	change = "";

	$.getJSON("/admincontactgroup/get_list/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			change += "<option value='"+val["id"]+"'>"+val["group_name"]+"</option>";

			if(val["id"]=="<?php echo $group_id;?>"){
				str += "<li class='active'><a href=\"#\" class='group_link' data-group='"+val["id"]+"'><span class=\"badge badge-success badge-active\"> "+val["cnt"]+" </span> "+val["group_name"]+" </a></li>";	
			} else {
				str += "<li><a href=\"#\" class='group_link' data-group='"+val["id"]+"'><span class=\"badge badge-success\"> "+val["cnt"]+" </span> "+val["group_name"]+" </a></li>";
			}
		});

		<?php if($group_id=="all" || $group_id==""){?>
		var init = "<li class='active'><a href=\"#\" class='group_link' data-group='all'><span class=\"badge badge-success badge-active\"> <?php echo $all_cnt;?> </span> 전체목록 </a> </li>";
		<?php } else { ?>
		var init = "<li><a href=\"#\" class='group_link' data-group='all'><span class=\"badge badge-success\"> <?php echo $all_cnt;?> </span> 전체목록 </a> </li>";
		<?php } ?>

		$("#group_list").html(init+str);
		$("#group_id").html(change);
		$("#edit_form").find("#group_id").html(change);
		

		$("#delete_id").html(change);
		replace_set();

		$(".group_link").click(function(){
			$("#group_list li").removeClass("active");
			$("#group_list li span").removeClass("badge-active");
			$(this).parent().addClass("active");
			$(this).find("span").addClass("badge-active");
			$("#group_id").val($(this).attr("data-group"));
			$('#search_form').trigger('submit');
		});
	});
}

function replace_set(){
	$("#replace_id").html(change);
	$("#replace_id option[value='"+$("#delete_id").val()+"']").remove();
}

function row_delete(id){
	if(confirm("삭제하시겠습니까?")){
		location.href="/admincontact/row_delete/"+id;
	}
}

function sort(obj,sort_name,order_by){
	var text = (sort_name=="name") ? "이름": "최근작업일";
	if(order_by=="desc"){
		$(obj).attr("onclick","javascript:sort(this,'"+sort_name+"','asc');");
		$(obj).html(text + ' <i class="fa fa-sort-asc"></i>');
	}
	else if(order_by=="asc"){
		$(obj).attr("onclick","javascript:sort(this,'"+sort_name+"','desc');");
		$(obj).html(text + ' <i class="fa fa-sort-desc"></i>');
	}
	$("#sort_name").val(sort_name);
	$("#order_by").val(order_by);
	$('#search_form').trigger('submit');
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			연락<small>관리</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					연락 관리
				</li>
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
                            <a href="#" onclick="open_sms('');">선택 전송</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#" onclick="open_sms($('#group_id').val());">전체 전송</a>
                        </li>
                    </ul>
                </div>
				<a href="/admincontact/add" class="btn blue">연락처 등록</a>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<!-- BEGIN TODO SIDEBAR -->
		<div class="todo-ui">
			<div class="todo-sidebar">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption" data-toggle="collapse" data-target=".todo-project-list-content">
							<span class="caption-subject font-green-sharp bold uppercase">연락처 그룹 </span>
							<span class="caption-helper visible-sm-inline-block visible-xs-inline-block">연락처를 그룹별로 관리하세요.</span>
						</div>
						<div class="actions">
							<div class="btn-group">
								<a class="btn green-haze btn-circle btn-sm todo-projects-config" href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
								<i class="icon-settings"></i> &nbsp; <i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu pull-right">
									<li>
										<a href="javascript:;" data-toggle="modal" data-target="#add_dialog">
										<i class="fa fa-plus"></i> 그룹 추가 </a>
									</li>
									<li>
										<a href="javascript:;" data-toggle="modal" data-target="#edit_dialog">
										<i class="fa fa-pencil-square-o"></i> 그룹 수정 </a>
									</li>
									<li>
										<a href="javascript:;" data-toggle="modal" data-target="#delete_dialog">
										<i class="fa fa-times"></i> <?php echo lang("site.delete");?> </a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="portlet-body todo-project-list-content">
						<div class="todo-project-list">
							<ul id="group_list" class="nav nav-pills nav-stacked">
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- END TODO SIDEBAR -->
			<div class="todo-content">
				<?php
					$page = 0;
					$keyword = "";
					if(isset($contact["page"]))	$page = $contact["page"];
					if(isset($contact["keyword"]))	$keyword = $contact["keyword"];
				?>
				<form action="/admincontact/index_json/<?php echo $page?>" id="search_form" method="post" role="form">
					<input type="hidden" id="group_id" name="group_id" value="<?php if($group_id!=""){echo $group_id;} else {echo "all";}?>">
					<input type="hidden" id="sort_name" name="sort_name"/>
					<input type="hidden" id="order_by" name="order_by"/>
					<div class="row">
						<div class="col-lg-12">
							<div class="pull-left">
								<div class="input-group input-medium">
									<input type="text" class="form-control" name="keyword" placeholder="키워드 검색" value="<?php echo $keyword;?>">
									<span class="input-group-btn">
									<button type="submit" class="btn green"><i class="fa fa-search"></i></button>
									</span>
								</div>
							</div>
							<div class="pull-right"><ul class="pagination"></ul></div>
							<div class="clearfix"></div>
						</div>
					</div>
				</form>

				<br/>

				<table class="table table-bordered table-striped table-condensed flip-content">
					<thead class="flip-content">
						<tr>
                            <th style="width:25px;"><input type='checkbox' id='check_all'/></th>
							<th class="text-center"><span style="cursor:pointer" onclick="javascript:sort(this,'name','desc');"><?php echo lang("site.name");?> <i class="fa fa-sort-desc"></i></span></th>
							<th class="text-center">회사/직책</th>
							<th class="text-center"><?php echo lang("site.email");?></th>
							<th class="text-center"><?php echo lang("site.tel");?></th>
							<th class="text-center hidden-xs"><?php echo lang("site.homepage");?></th>
							<th class="text-center hidden-xs"><?php echo lang("site.address");?></th>
							<th class="text-center hidden-xs"><?php echo lang("product");?> 수</th>
							<th class="text-center hidden-xs"><span style="cursor:pointer" onclick="javascript:sort(this,'history_date','desc');">최근작업일 <i class="fa fa-sort-desc"></i></span></th>
							<th class="text-center hidden-xs">&nbsp;</th>
						</tr>
					</thead>
					<tbody id="contact_list"></tbody>
				</table>
				<div class="row text-center">
					<div class="col-sm-12">
						<ul class="pagination"></ul>
				    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo form_open("admincontactgroup/add_action",Array("id"=>"add_form","class"=>"form-horizontal"))?>
<div id="add_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">그룹 추가</h4>
      </div>
      <div class="modal-body">
			<div class="form">
				<div class="form-body">
					<div class="form-group">
						<label class="col-md-3 control-label">그룹명 <span class="required" aria-required="true"> * </span></label>
						<div class="col-md-9">
							<input type="text" name="group_name" class="form-control" autocomplete="off"/>
						</div>
					</div>	
				</div>				
			</div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">추가</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>

<?php echo form_open("admincontactgroup/edit_action",Array("id"=>"edit_form","class"=>"form-horizontal"))?>
<div id="edit_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">그룹 이름 변경</h4>
      </div>
      <div class="modal-body">
		<div class="form">
			<div class="form-body">
				<table class="table table-striped table-hover table-bordered">
					<tbody>
						<tr>
							<th>
								변경할 그룹
							</th>
							<td>
								<select id="group_id" name="group_id" class="form-control"></select>
							</td>
						</tr>
						<tr>
							<th>
								변경할 이름
							</th>
							<td>
								<input type="text" name="change_name" class="form-control">
							</td>
						</tr>							
					</tbody>					
				</table>
			</div>				
		</div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">변경</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>

<?php echo form_open("admincontactgroup/delete_action",Array("id"=>"delete_form","class"=>"form-horizontal"))?>
<div id="delete_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("site.delete");?></h4>
      </div>
      <div class="modal-body">
			<div class="form">
				<div class="form-body">
					<div class="help-block">삭제된 그룹의 연락처들이 대체될 그룹을 지정해 주세요.</div>
					<table class="table table-striped table-hover table-bordered">
						<tbody>
							<tr>
								<th>
									삭제할 그룹
								</th>
								<td>
									<select id="delete_id" name="delete_id" class="form-control"></select>
								</td>
							</tr>
							<tr>
								<th>
									대체할 이름
								</th>
								<td>
									<select id="replace_id" name="replace_id" class="form-control"></select>
								</td>
							</tr>			
						</tbody>					
					</table>
				</div>
				
			</div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">변경</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>

<!-- SMS FORM-->
<?php echo form_open_multipart("/adminsms/select_send",Array("id"=>"sms_form","class"=>"form-horizontal"))?>
<input type="hidden" name="send_page" value="contact"/>
<input type="hidden" name="send_all_type"/>
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