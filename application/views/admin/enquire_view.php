<link rel="stylesheet" type="text/css" href="/assets/plugin/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<script>
$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	$("#constract_add_form").validate({  
		rules: {
			title: {  
				required: true
			}
		},  
		messages: {
			title: {  
				required: "<?php echo lang("form.required");?>" 
			}
		} 
	});

	$("#constract_edit_form").validate({  
		rules: {
			title: {  
				required: true
			}
		},  
		messages: {
			title: {  
				required: "<?php echo lang("form.required");?>" 
			}
		} 
	});

	get_memo();
	get_action();
	get_contract();

	$('#memo_form').ajaxForm({
		success:function(data){
			$(this).find("#content").val("");
			get_memo();
		}
	});

	$('#action_form').ajaxForm({
		success:function(data){
			$(this).find("#content").val("");
			get_action();
		}
	});

	$('#contract_form').ajaxForm({
		success:function(data){
			get_contract();
		}
	});


	$('#constract_add_form').ajaxForm( {
		beforeSubmit: function(){
		},
		success: function(data){
			$("#add_constract_dialog").modal("hide");
			get_contract();
		}
	});

	$('#constract_edit_form').ajaxForm( {
		beforeSubmit: function(){
		},
		success: function(data){
			$("#edit_constract_dialog").modal("hide");
			get_contract();
		}
	});

	if (jQuery().datepicker) {
		$(".date-picker").each(function() {
			$(this).datepicker({
				format: "yyyy-mm-dd",
				orientation: "left",
				language: "kr",
				autoclose: true
			});
		});
    }

    if (jQuery().timepicker) {
		$('.timepicker-24').timepicker({
			autoclose: true,
			minuteStep: 5,
		    showSeconds: false,
		    showMeridian: false
		});
	}

	$("#add_contacts_name").autocomplete({
		selectFirst: true, 
		autoFill: true,
		autoFocus: true,
		focus: function(event,ui){
			return false;
		},
		scrollHeight:40,
		minlength:1,
		select: function(a,b){
			$("#add_contacts_id").val(b.item.id);
			$("#add_contacts_name").val(b.item.name);
			a.stopPropagation();
			return false;
		},
		source: function(request, response){
			$.ajax({
				url: "/search/contacts_member_list",
				type: "POST",
				data: {
					search: $("#add_contacts_name").val()
				},
				dataType: "json",
				success: function(data) {
					if(data!=""){
						response( $.map( data, function( item ) {
							if(item.phone!=""){
								item.phone = item.phone.split("-dungzi-")[0];
								item.phone = item.phone.split("--")[2];						
							}					
							return {
								id: item.id,
								name: item.name,
								phone: item.phone
							}; 
						}));				
					}
				}
			});						
		},
	}).data("ui-autocomplete")._renderItem = autoCompleteRenderContact;

	$("#edit_contacts_name").autocomplete({
		selectFirst: true, 
		autoFill: true,
		autoFocus: true,
		focus: function(event,ui){
			return false;
		},
		scrollHeight:40,
		minlength:1,
		select: function(a,b){
			$("#edit_contacts_id").val(b.item.id);
			$("#edit_contacts_name").val(b.item.name);
			a.stopPropagation();
			return false;
		},
		source: function(request, response){
			$.ajax({
				url: "/search/contacts_member_list",
				type: "POST",
				data: {
					search: $("#edit_contacts_name").val()
				},
				dataType: "json",
				success: function(data) {
					if(data!=""){
						response( $.map( data, function( item ) {
							if(item.phone!=""){
								item.phone = item.phone.split("-dungzi-")[0];
								item.phone = item.phone.split("--")[2];						
							}					
							return {
								id: item.id,
								name: item.name,
								phone: item.phone
							}; 
						}));				
					}
				}
			});						
		},
	}).data("ui-autocomplete")._renderItem = autoCompleteRenderContact;
});

function autoCompleteRenderContact(ul, item) {
	if(item.name){
		return $("<li class='search_rows'></li>").data("item.autocomplete", item).append("<i class='fa fa-user'></i> " + item.name+'('+item.phone+')').appendTo(ul);	
	}
}

/** 메모  **/
function get_memo(){
	$.getJSON("/adminenquirememo/get_list/<?php echo $query->id;?>/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			/*** 담당자는 왼쪽에 기타는 오른쪽에 메모를 남겨서 분리되어 보일 수 있도록 한다. ***/
			var memo_class="out";
			
			if(val["member_id"]=="<?php echo $query->member_id;?>"){
				memo_class="in";
			}
			
			var profile = "/assets/admin/img/w1.png";
			if(val["profile"]!=""){
				profile = "/uploads/member/" + val["profile"];
			}

			var delete_section = "";
			if(val["member_id"]=="<?php echo $this->session->userdata("admin_id");?>"){
				delete_section = "<a class='remove_memo' data-id='"+val["id"]+"' href='javascript:;'><i class=\"fa fa-times\"></i></a>";
			}

			str += "<li class=\""+memo_class+"\"><img class=\"avatar\" src=\""+profile+"\"/><div class=\"message\"><span class=\"arrow\"></span>";
			str += "<a href=\"#\" class=\"name\">"+val["name"]+"</a> ";
			str += "<span class=\"datetime\">"+val["regdate"]+"</span>";
			str += delete_section;
			str += "<span class=\"body\">"+val["content"]+"</span></div></li>";
		});

		$("#memo_list").html(str);

		$(".remove_memo").click(function(){
				if(confirm("메모를 삭제하시겠습니까?")){
				$.get("/adminenquirememo/delete_memo/"+$(this).attr("data-id")+"/"+Math.round(new Date().getTime()),function(data){
					if(data=="1"){
						get_memo();
					} else {
						
						alert("메모 삭제에 실패하였습니다.");
					}
				});
			}
		});

	});
}

function get_action(){
	$.getJSON("/adminenquirememo/get_action_list/<?php echo $query->id;?>/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		$.each(data, function(key, val) {
			/*** 담당자는 왼쪽에 기타는 오른쪽에 메모를 남겨서 분리되어 보일 수 있도록 한다. ***/
			var memo_class="out";
			
			if(val["member_id"]=="<?php echo $query->member_id;?>"){
				memo_class="in";
			}
			
			var profile = "/assets/admin/img/w1.png";
			if(val["profile"]!=""){
				profile = "/uploads/member/" + val["profile"];
			}

			var delete_section = "";
			if(val["member_id"]=="<?php echo $this->session->userdata("admin_id");?>"){
				delete_section = "<a class='remove_action' data-id='"+val["id"]+"' href='javascript:;'><i class=\"fa fa-times\"></i></a>";
			}

			str += "<li class=\""+memo_class+"\"><img class=\"avatar\" src=\""+profile+"\"/><div class=\"message\"><span class=\"arrow\"></span>";
			str += "<a href=\"#\" class=\"name\">"+val["name"]+"</a> ";
			str += val["dday"]+" <span class=\"datetime\">"+val["actiondate"]+"</span>";
			str += delete_section;
			str += "<span class=\"body\">";
			if(val["type"]=="call"){
				str += "<span class=\"label label-sm label-info\">전화</span>";	
			} else if(val["type"]=="meeting"){
				str += "<span class=\"label label-sm label-danger\">미팅</span>";
			} else if(val["type"]=="etc"){
				str += "<span class=\"label label-sm label-default\">기타</span>";
			}
			
			str += val["content"]+"</span></div></li>";
		});

		$("#action_list").html(str);

		$(".remove_action").click(function(){
				if(confirm("일정을 삭제하시겠습니까?")){
				$.get("/adminenquirememo/delete_action/"+$(this).attr("data-id")+"/"+Math.round(new Date().getTime()),function(data){
					if(data=="1"){
						get_action();
					} else {
						
						alert("일정 삭제에 실패하였습니다.");
					}
				});
			}
		});

	});
}

function get_contract(){
	$.getJSON("/adminenquirecontract/get_list/<?php echo $query->id;?>/"+Math.round(new Date().getTime()),function(data){
		var str = '<table class="table table-bordered table-condensed flip-content">';
			str += '<tr>';
			str += '	<th><?php echo lang("site.status");?></th>';
			str += '	<th>계약명</th>';
			str += '	<th><?php echo lang("product.type");?></th>';
			str += '	<th>계약서파일</th>';
			str += '	<th><?php echo lang("site.regdate");?></th>';
			str += '	<th><i class="fa fa-trash-o"></i></th>';
			str += '</tr>';
		
		if(data!=""){
			$.each(data, function(key, val) {
				var type = "";
				var status = "";
				var filename = "";

				if(val["type"]=="installation") type = '<?php echo lang("installation")?>';
				if(val["type"]=="sell") type = '<?php echo lang("installation")?>';
				if(val["type"]=="full_rent") type = '<?php echo lang("installation")?>';
				if(val["type"]=="monthly_rent") type = '<?php echo lang("installation")?>';

				if(val["status"]=="Y"){
					status = '<button class="btn btn-primary btn-xs">완료</button>';
				}
				else{
					status = '<button class="btn btn-danger btn-xs">미완료</button>';	
				}

				if(val["originname"]){
					filename = val["originname"]+" ("+val["file_size"]+'KB)';
				}

				str += '<tr>';
				str += '	<td>'+status+'</td>';
				str += '	<td><a data-toggle="modal" data-target="#edit_constract_dialog" onclick="contract_edit('+val["id"]+',\'constract_edit_form\')">'+val["title"]+'</a></td>';
				str += '	<td>'+type+'</td>';
				str += '	<td><a href="/attachment/enquired_contract_download/'+val["id"]+'">'+filename+'</a></td>';
				str += '	<td>'+val["date"]+'</td>';
				str += '	<td><a class="remove_contract" data-id="'+val["id"]+'" href="javascript:;"><i class="fa fa-times"></i></a></td>';
				str += '</tr>';	
			});
		}
		else{
			str += '<tr>';
			str += '	<td class="text-center" colspan="6"><?php echo lang("msg.nodata");?></td>';
			str += '</tr>';				
		}

		str += '</table>';	
		$("#contract_list").html(str);

		$(".remove_contract").click(function(){
				if(confirm("계약정보를 삭제하시겠습니까?")){
				$.get("/adminenquirecontract/delete_action/"+$(this).attr("data-id")+"/"+Math.round(new Date().getTime()),function(data){
					if(data=="1"){
						get_contract();
					} else {						
						alert("계약정보 삭제에 실패하였습니다.");
					}
				});
			}
		});
	});
}

function contract_edit(id,form){
	$("#"+form).find("input[name='contract_id']").val(id);
	$.getJSON("/adminenquirecontract/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			switch(key){
				case "enquire_id":
					break;
				case "status":
				case "tax_type":
				case "tax_use":
					$("#"+form).find("input:radio[name='"+key+"']:radio[value='"+val+"']").attr("checked",true);
					break;
				case "category":
					$("#"+form).find("input[name='category[]']").each(function(){
						$(this).prop('checked', false);
					});

					var category = val.split(",");

					for(var i=0; i<category.length; i++){
						$("#"+form).find("input[name='category[]']").each(function(){
							if(category[i]==$(this).val()){
								$(this).prop('checked', true);
							}
						});
					}
					break;
				case "originname":
					$("#"+form).find("span[name='originname']").html('<a href="/attachment/enquired_contract_download/'+id+'">'+val+'</a>');
					break;
				default:
					$("#"+form).find("input[name='"+key+"']").val(val);
					break;
			}
		});
		$(".date-picker").each(function() {
			if($(this).val()!="" && $(this).val()!="0000-00-00"){
				$(this).datepicker('setDate', $(this).val());
			}
			else{
				$(this).datepicker('setDate', "");
			}
		});
	});
}

function price_sum(obj){
	var contract_price = $(obj).parent().find("input[name='contract_price']").val();
	var part_price = $(obj).parent().find("input[name='part_price']").val();
	var balance_price = $(obj).parent().find("input[name='balance_price']").val();

	if(!contract_price) contract_price = 0;
	if(!part_price) part_price = 0;
	if(!balance_price) balance_price = 0;

	$(obj).parent().find("#total_price").val(number_format(parseInt(contract_price) + parseInt(part_price) + parseInt(balance_price)));
}

function date_reset(){
	$(".date-picker").each(function() {
		$(this).datepicker('setDate', "");
	});
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			<?php echo lang("enquire.title");?> <small>등록</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li><?php echo lang("enquire.title");?> 관리 <i class="fa fa-angle-right"></i> </li>
				<li><?php echo lang("enquire.title");?> <small>등록</small></li>
			</ul>
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<?php if($this->session->userdata("auth_id")=="1" || $this->session->userdata("admin_id")==$query->member_id){?>
						<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
						실행 <i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li>
								<a href="/adminenquire/index/"><?php echo lang("site.list");?></a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="/adminenquire/edit/<?php echo $query->id;?>">수정</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="/adminenquire/check_product/<?php echo $query->id;?>"><?php echo lang("product")?>확인하기</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="#" onclick="view_print();"><?php echo lang("site.print");?></a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="#" onclick="delete_product('<?php echo $query->id;?>');"><?php echo lang("site.delete");?></a>
							</li>
						</ul>
						<?php } else {?>
						<button type="button" class="btn btn-danger" onclick="history.back(-1)"><?php echo lang("site.back");?></button>					
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<!-- 기본 정보 시작 -->
		<table class="table table-bordered table-striped table-condensed flip-content" style="margin:0px;">
			<tr>
				<th width="20%">구분</th>
				<td width="80%" colspan="3">
					<?php 
						if($query->gubun=="buy") {
							echo "구해요(매수)";
						} else {
							echo "팔아요(매도)";
						}
					?>
				</td>
			</tr>
			<tr>
				<th width="20%">단계</th>		
				<td width="80%" colspan="3">
					<?php 
						echo $query->status_label;
					?>
				</td>
			</tr>
			<tr>
				<th width="20%">고객정보</th>
				<td width="80%" colspan="3">
					<?php echo $query->name; ?> (<?php echo $query->phone; ?><?php if($query->phone_etc1) echo ", "
					.$query->phone_etc1;?><?php if($query->phone_etc2) echo ", "
					.$query->phone_etc2;?>)
					<?php if($query->feature!="") echo " - " . $query->feature ; ?> 
				</td>
			</tr>
		</table>

		<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"><?php echo lang("product");?> 정보</h4>
		<table class="table table-bordered table-striped table-condensed flip-content margin-top-10">	
			<tr>
				<th width="20%"><?php echo lang("enquire.hopearea");?></th>
				<td width="80%" colspan="3">
					<?php echo $query->location; ?>
				</td>
			</tr>
			<?php if($config->INSTALLATION_FLAG!="2"){?>
			<tr>
				<th width="20%"><?php echo lang("product.type");?></th>
				<td width="80%" colspan="3">
					<?php 
						if($query->type=="installation") {
							echo lang('installation');
						} else if($query->type=="sell") {
							echo lang('sell');
						} else if($query->type=="full_rent") {
							echo lang('full_rent');
						} else if($query->type=="monthly_rent") {
							echo lang('monthly_rent');
						}
					?>			
				</td>
			</tr>
			<?php }?>
			<tr>
				<th width="20%"><?php echo lang("search.type");?></th>
				<td width="80%" colspan="3">
					<?php 
						$category_vals  = explode(",",$query->category);	/** category값들을 array로 추출 **/
						foreach($category as $val){
							if (in_array($val->id, $category_vals)) {echo "[".$val->name."] ";}
						}
					?>
				</td>
			</tr>
			<tr>		
				<th width="20%"><?php echo lang("enquire.price");?></th>
				<td width="80%" colspan="3">
					<?php echo $query->price; ?>
				</td>
			</tr>
		</table>

		<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;">상세 설명</h4>
		<table class="table table-bordered table-striped table-condensed flip-content margin-top-10">
			<tr>
				<th width="20%"><?php echo lang("enquire.movedate");?></th>
				<td width="80%" colspan="3">
					<?php echo $query->movedate; ?>
				</td>
			</tr>
			<tr>		
				<th width="20%"><?php echo lang("enquire_visit");?></th>
				<td width="80%" colspan="3">
					<?php echo $query->visitdate; ?>
				</td>
			</tr>
			<tr>
				<th width="20%">의뢰내용</th>
				<td width="80%" colspan="3">
					<?php echo $query->content; ?>
				</td>
			</tr>
			<tr>
				<th width="20%"><?php echo lang("enquire.answer");?></th>
				<td width="80%" colspan="3">
					<?php echo $query->work; ?>
				</td>
			</tr>
			<tr>
				<th width="20%">관리메모 <i class="fa fa-lock"></i></th>
				<td width="80%" colspan="3">
					<?php echo $query->secret; ?>
				</td>
			</tr>
			<tr>		
				<th width="20%"><?php echo lang("product.owner");?></th>
				<td width="80%" colspan="3">
					<?php if(isset($member->name)){
						echo $member->name." (".$member->phone.")";
					} else {
						echo "지정된 담당자가 없습니다";
					}?>
				</td>
			</tr>	
		</table>
		<!-- 기본 정보 시작 -->
	</div>
	<div class="col-md-6">
		<!-- 우측 기타 정보 영역 시작 -->
			<!--BEGIN TABS-->
			<div class="tabbable tabbable-custom">
				<ul class="nav nav-tabs">
					<li class="<?php if($type=="" || $type=="contact") echo "active";?>">
						<a href="#tab_1_2" data-toggle="tab">
						접촉(<?php echo $count_contact?>) </a>
					</li>
					<li class="<?php if($type=="memo") echo "active";?>">
						<a href="#tab_1_1" data-toggle="tab">
						메모(<?php echo $count_memo?>) </a>
					</li>
					<li class="<?php if($type=="contract") echo "active";?>">
						<a href="#tab_1_3" data-toggle="tab">
						계약(<?php echo $count_contract?>) </a>
					</li>					
				</ul>
				<div class="tab-content">
					<div class="tab-pane <?php if($type=="memo") echo "active";?>" id="tab_1_1">						
						<div class="chat-form">
							<?php echo form_open("adminenquirememo/add_action","id='memo_form'");?>
							<input type="hidden" name="enquire_id" value="<?php echo $query->id;?>"/>
							<div class="input-cont">
								<input id="content" name="content" class="form-control" type="text" placeholder="메모를 남겨주세요." autocomplete="off"/>
							</div>
							<div class="btn-cont">
								<span class="arrow">
								</span>
								<button class="btn blue icn-only">
								<i class="fa fa-check icon-white"></i>
								</button>
							</div>
							<?php echo form_close();?>
						</div>
						<div class="scroller" style="height: 250px;">
							<ul id="memo_list" class="chats"></ul>
						</div>
					</div>					
					<div class="tab-pane <?php if($type=="" || $type=="contact") echo "active";?>" id="tab_1_2">
						<div class="chat-form">
							<?php echo form_open("adminenquirememo/add_action_action","id='action_form'");?>
							<input type="hidden" name="enquire_id" value="<?php echo $query->id;?>"/>
							<div class="margin-bottom-10">
								<input type="text" name="actiondate" class="form-control input-inline input-small date-picker" placeholder="날짜" autocomplete="off" value="<?php echo date('Y-m-d');?>"/>
								<input type="text" name="actiontime" class="form-control input-inline input-small timepicker-24"  placeholder="시간" autocomplete="off" value="<?php echo date('H:i');?>"/>
								<div class="input-inline">
									<label class="radio-inline">
									<input type="radio" name="type" value="call" checked> 전화 </label>
									<label class="radio-inline">
									<input type="radio" name="type" value="meeting"> 미팅 </label>
									<label class="radio-inline">
									<input type="radio" name="type" value="etc"> 기타 </label>
								</div>
							</div>																		
							<div class="input-cont">
								<input id="content" name="content" class="form-control" type="text" placeholder="전화 연락 및 미팅 등을 남겨주세요." autocomplete="off"/>
							</div>
							<div class="btn-cont">
								<span class="arrow">
								</span>
								<button class="btn blue icn-only">
								<i class="fa fa-check icon-white"></i>
								</button>
							</div>
							<?php echo form_close();?>
						</div>
						<div class="scroller" style="height: 250px;">
							<ul id="action_list" class="chats"></ul>
						</div>
					</div>
					<div class="tab-pane <?php if($type=="contract") echo "active";?>" id="tab_1_3">
						<div class="pull-right margin-bottom-10">
							<button class="btn btn-primary" data-toggle="modal" data-target="#add_constract_dialog" onclick="$('#constract_add_form')[0].reset();date_reset();"><?php echo lang("site.submit");?></button>
						</div>
						<div id="contract_list"></div>
					</div>
				</div>
			</div>
			<!--END TABS-->	
		<!-- 우측 기타 정보 영역 종료-->
	</div>
</div>

<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/locales/bootstrap-datepicker.kr.js" charset="UTF-8"></script>
<script type="text/javascript" src="/assets/plugin/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="/assets/plugin/icheck/icheck.min.js"></script>

<!-- CONSTRACT ADD FORM -->
<?php echo form_open_multipart("adminenquirecontract/add_action",Array("id"=>"constract_add_form"))?>
<input type="hidden" name="enquire_id" value="<?php echo $query->id?>"/>
<div id="add_constract_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">계약등록</h4>
		</div>
		<div class="modal-body">
			<!--암호를 입력하지 않으면 암호는 변경되지 않습니다-->
			<table class="table table-bordered table-striped-left table-condensed flip-content">
				<tbody>
					<tr>
						<td class="text-center vertical-middle" width="120"><?php echo lang("site.status");?></td>
						<td>
							<label class="radio-inline">
								<input type="radio" name="status" value="N" checked/>미완료
							</label>
							<label class="radio-inline">
								<input type="radio" name="status" value="Y"/>완료
							</label>							
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle" width="120">계약명</td>
						<td>
							<input type="text" class="form-control input-xlarge" name="title" placeholder="계약명"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">계약일</td>
						<td>
							<input type="text" class="form-control input-small date-picker" name="contract_date" placeholder="계약일" readonly/>
						</td>
					</tr>		
					<?php if($config->INSTALLATION_FLAG!="2"){?>
					<tr>
						<td class="text-center vertical-middle"><?php echo lang("product.type");?></td>
						<td>
							<select class="form-control input-small" name="type">
								<?php if($config->INSTALLATION_FLAG=="1"){?>
								<option value="installation"><?php echo lang('installation');?></option>
								<?php }?>
								<option value="sell" selected><?php echo lang('sell');?></option>
								<option value="full_rent"><?php echo lang('full_rent');?></option>
								<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
							</select>
						</td>
					</tr>
					<?php }?>
					<tr>
						<td class="text-center vertical-middle"><?php echo lang("product");?>형태</td>
						<td>
							<?php foreach($category as $val){?>
								<label style="margin-right:20px;"><input type="checkbox" name="category[]" value="<?php echo $val->id;?>"><?php echo $val->name;?> </label>
							<?php }?>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">금액</td>
						<td>
							<input type="text" class="form-control input-small input-inline" name="contract_price" placeholder="계약금" title="계약금" onkeyup="price_sum(this);"/>
							<input type="text" class="form-control input-small input-inline" name="part_price" placeholder="중도금" title="중도금" onkeyup="price_sum(this);"/>
							<input type="text" class="form-control input-small input-inline" name="balance_price" placeholder="잔금" title="잔금" onkeyup="price_sum(this);"/>
							<input type="text" class="form-control input-small input-inline" id="total_price" placeholder="합계" readonly title="합계"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">중개수수료</td>
						<td>
							<input type="text" class="form-control input-small input-inline" name="commission_price" placeholder="중개수수료" title="중개수수료"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">지급날짜</td>
						<td>
							<input type="text" class="form-control input-small input-inline date-picker" name="contract_pay_date" placeholder="계약금지급날짜" readonly/>
							<input type="text" class="form-control input-small input-inline date-picker" name="part_pay_date" placeholder="중도금지급날짜" readonly/>
							<input type="text" class="form-control input-small input-inline date-picker" name="balance_pay_date" placeholder="잔금지급날짜" readonly/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">세금형태</td>
						<td>
							<label class="radio-inline">
								<input type="radio" name="tax_type" value="cash" checked/>현금영수증
							</label>
							<label class="radio-inline">
								<input type="radio" name="tax_type" value="tax"/>세금계산서
							</label>							
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">발행여부</td>
						<td>
							<label class="radio-inline">
								<input type="radio" name="tax_use" value="N" checked/>미발행
							</label>
							<label class="radio-inline">
								<input type="radio" name="tax_use" value="Y"/>발행
							</label>							
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">계약서파일</td>
						<td>
							<input type="file" class="form-control input-xlarge" name="filename" placeholder="계약서파일" style="height:auto"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">협력중개소</td>
						<td>
							<input type="hidden" id="add_contacts_id" name="contacts_id"/>
							<input type="text" class="form-control input-medium ui-autocomplete-input inline" id="add_contacts_name" name="contacts_name" placeholder="협력중개소" autocomplete="off"/>
							<i class="fa fa-search"></i>
						</td>
					</tr>
				</tbody>
			</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
				<button type="submit" class="btn btn-primary"><?php echo lang("site.submit");?></button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close();?>
<!-- CONSTRACT ADD FORM -->

<!-- CONSTRACT EDIT FORM -->
<?php echo form_open_multipart("adminenquirecontract/edit_action",Array("id"=>"constract_edit_form"))?>
<input type="hidden" name="enquire_id" value="<?php echo $query->id?>"/>
<input type="hidden" name="contract_id"/>
<div id="edit_constract_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">계약수정</h4>
		</div>
		<div class="modal-body">
			<!--암호를 입력하지 않으면 암호는 변경되지 않습니다-->
			<table class="table table-bordered table-striped-left table-condensed flip-content">
				<tbody>
					<tr>
						<td class="text-center vertical-middle" width="120"><?php echo lang("site.status");?></td>
						<td>
							<label class="radio-inline">
								<input type="radio" name="status" value="N" checked/>미완료
							</label>
							<label class="radio-inline">
								<input type="radio" name="status" value="Y"/>완료
							</label>							
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle" width="120">계약명</td>
						<td>
							<input type="text" class="form-control input-xlarge" name="title" placeholder="계약명"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">계약일</td>
						<td>
							<input type="text" class="form-control input-small date-picker" name="contract_date" placeholder="계약일" readonly/>
						</td>
					</tr>		
					<?php if($config->INSTALLATION_FLAG!="2"){?>
					<tr>
						<td class="text-center vertical-middle"><?php echo lang("product.type");?></td>
						<td>
							<select class="form-control input-small" name="type">
								<?php if($config->INSTALLATION_FLAG=="1"){?>
								<option value="installation"><?php echo lang('installation');?></option>
								<?php }?>
								<option value="sell" selected><?php echo lang('sell');?></option>
								<option value="full_rent"><?php echo lang('full_rent');?></option>
								<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
							</select>
						</td>
					</tr>
					<?php }?>
					<tr>
						<td class="text-center vertical-middle"><?php echo lang("product");?>형태</td>
						<td>
							<?php foreach($category as $val){?>
								<label style="margin-right:20px;"><input type="checkbox" name="category[]" value="<?php echo $val->id;?>"><?php echo $val->name;?> </label>
							<?php }?>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">금액</td>
						<td>
							<input type="text" class="form-control input-small input-inline" name="contract_price" placeholder="계약금" title="계약금" onkeyup="price_sum(this);"/>
							<input type="text" class="form-control input-small input-inline" name="part_price" placeholder="중도금" title="중도금" onkeyup="price_sum(this);"/>
							<input type="text" class="form-control input-small input-inline" name="balance_price" placeholder="잔금" title="잔금" onkeyup="price_sum(this);"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">중개수수료</td>
						<td>
							<input type="text" class="form-control input-small input-inline" name="commission_price" placeholder="중개수수료" title="중개수수료"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">지급날짜</td>
						<td>
							<input type="text" class="form-control input-small input-inline date-picker" name="contract_pay_date" placeholder="계약금지급날짜" readonly/>
							<input type="text" class="form-control input-small input-inline date-picker" name="part_pay_date" placeholder="중도금지급날짜" readonly/>
							<input type="text" class="form-control input-small input-inline date-picker" name="balance_pay_date" placeholder="잔금지급날짜" readonly/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">세금형태</td>
						<td>
							<label class="radio-inline">
								<input type="radio" name="tax_type" value="cash" checked/>현금영수증
							</label>
							<label class="radio-inline">
								<input type="radio" name="tax_type" value="tax"/>세금계산서
							</label>							
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">발행여부</td>
						<td>
							<label class="radio-inline">
								<input type="radio" name="tax_use" value="N" checked/>미발행
							</label>
							<label class="radio-inline">
								<input type="radio" name="tax_use" value="Y"/>발행
							</label>							
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">계약서파일</td>
						<td>
							<input type="file" class="form-control input-xlarge" name="filename" placeholder="계약서파일" style="height:auto"/>
							<div class="margin-top-10">
								업로드된 파일 : <span name="originname"></span>
							</div>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">협력중개소</td>
						<td>
							<input type="hidden" id="edit_contacts_id" name="contacts_id"/>
							<input type="text" class="form-control input-medium ui-autocomplete-input inline" id="edit_contacts_name" name="contacts_name" placeholder="협력중개소" autocomplete="off"/>
							<i class="fa fa-search"></i>
						</td>
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
<?php echo form_close();?>
<!-- CONSTRACT EDIT FORM -->