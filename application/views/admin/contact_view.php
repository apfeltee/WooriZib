<?php 
	$this->load->helper('contact');
?>
<link href="/assets/admin/css/tasks.css" rel="stylesheet" type="text/css"/>
<link href="/assets/plugin/icheck/skins/all.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="/assets/plugin/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<style>
.modal-dialog{ width:98%;max-width: 600px;/* your width */ }
</style>
<script>
$(function() {

	get_memo();
	get_action();
	get_task();

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	$('#memo_form').ajaxForm({
		success:function(data){
			$("#content").val("");
			get_memo();
		}
	});

	$('#action_form').ajaxForm({
		success:function(data){
			$("#content").val("");
			get_action();
		}
	});

	if (jQuery().datepicker) {

        $('.date-picker').datepicker({
        	format: "yyyy-mm-dd",
            orientation: "left",
            language: "kr",
            autoclose: true
        });
        /** $('body').removeClass("modal-open"); **/
    }

    if (jQuery().timepicker) {
		$('.timepicker-24').timepicker({
			autoclose: true,
			minuteStep: 5,
		    showSeconds: false,
		    showMeridian: false
		});
	}

    $('#add_form').ajaxForm({
		beforeSerialize: function(form, options) { 
		  for (instance in CKEDITOR.instances)
		        CKEDITOR.instances[instance].updateElement();
		},
    	beforeSubmit:function(data){
    		
    		$("#add_form").validate({  
		        errorElement: "span",
		        wrapper: "span",  
				rules: {
					title: {  
						required: true,  
						minlength: 2
					}
				},  
				messages: {  
					title: {  
						required: "작업 제목을 입력합니다",  
						minlength: "작업 제목은 최소 2자리 이상입니다"
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
				get_task();

				
				$("input[name='title']").val("");
				$("input[name='important']").val("2");
				CKEDITOR.instances.content_add.setData();
				$("input[name='member_id']").val("<?php echo $this->session->userdata("admin_id");?>");
			} 
			
			$('#add_dialog').modal('hide');

		}
	});


    $('#edit_form').ajaxForm({
		beforeSerialize: function(form, options) { 
		  for (instance in CKEDITOR.instances)
		        CKEDITOR.instances[instance].updateElement();
		},
    	beforeSubmit:function(data){
    		
    		$("#edit_form").validate({  
		        errorElement: "span",
		        wrapper: "span",  
				rules: {
					title: {  
						required: true,  
						minlength: 2
					}
				},  
				messages: {  
					title: {  
						required: "작업 제목을 입력합니다",  
						minlength: "작업 제목은 최소 2자리 이상입니다"
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
				get_task();

				
				$("input[name='title']").val("");
				$("input[name='important']").val("2");
				CKEDITOR.instances.content_edit.setData();
				$("input[name='member_id']").val("<?php echo $this->session->userdata("admin_id");?>");
			} 
			
			$('#edit_dialog').modal('hide');

		}
	});	

	$("#upload_dialog").dialog({
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
					$("#upload_form").submit();
				}
			}
	});

	$('#upload_form').ajaxForm({
		success:function(data){
			if(data == ""){
				alert("실패");
				alert(data);
			} 
			else {
				
				if($("#edit_dialog").css('display')=="none"){
					 CKEDITOR.instances.content_add.insertHtml( "<img src='"+data+"'>" );
				} else {
					 CKEDITOR.instances.content_edit.insertHtml( "<img src='"+data+"'>" );
				}
				
			} 
			$('#upload_dialog').dialog("close");

		}
	});	
});

function delete_product(id){
	if(confirm("<?php echo lang("product");?>(를)을 삭제하시겠습니까?\n<?php echo lang("product");?>삭제는 슈퍼관리자와 등록한 직원만 가능합니다.")){
		location.href="/adminproduct/delete_product/"+id;
	}
}

function change(type, id, status){
	if(confirm("상태를 변경하시겠습니까?")){
		$.get("/adminproduct/change/"+type+"/"+id+"/"+status+"/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				location.reload();
		   } else {
				alert("변경 실패");
		   }
		})
	}
}

/** 메모  **/
function get_memo(){
	$.getJSON("/adminmemo/get_list/<?php echo $query->id;?>/"+Math.round(new Date().getTime()),function(data){
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
				$.get("/adminmemo/delete_memo/"+$(this).attr("data-id")+"/"+Math.round(new Date().getTime()),function(data){
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

/** 메모  **/
function get_action(){
	$.getJSON("/adminmemo/get_action_list/<?php echo $query->id;?>/"+Math.round(new Date().getTime()),function(data){
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
				$.get("/adminmemo/delete_action/"+$(this).attr("data-id")+"/"+Math.round(new Date().getTime()),function(data){
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

/** 작업 **/
function get_task(){
	$.getJSON("/admintask/get_list/<?php echo $query->id;?>/"+Math.round(new Date().getTime()),function(data){
		var str = "";
		var finished = "";
		var important = "";
		var check = "";
		var disable = "";
		$.each(data, function(key, val) {
			
			if(val["finished"]=="Y"){
				finished = "task-done";
				check = "checked";
			} else {
				finished = "";
				check = "";
			}

			if(val["important"]=="1"){
				important = "<span class=\"label label-sm label-danger\">중요</span>";
			} else if(val["important"]=="2"){
				important = "<span class=\"label label-sm label-info\">보통</span>";
			} else if(val["important"]=="3"){
				important = "<span class=\"label label-sm label-default\">낮음</span>";
			}

			if(val["member_id"]=="<?php echo $this->session->userdata("admin_id")?>"){
				disable = "";
			} else {
				disable = "disabled";
			}

			str += "<li class=\""+finished+"\"><div class=\"task-checkbox\"><input type=\"checkbox\" class=\"icheck\" value=\""+val["id"]+"\" name=\"task[]\" "+check+" "+disable+"/></div>";
			str += "<div class=\"task-title\"><span class=\"task-title-sp\"> " + important;
			str += " "+val["deaddate"]+" <a href='javascript:;' onclick=\"edit('"+val["id"]+"');\" data-toggle=\"modal\" data-target=\"#edit_dialog\"><b>"+val["title"]+"</b></a> (<a href='#'>"+val["name"]+"</a>)</span></div>";
			str += "</li>";
		});

		$("#task-list").html(str);

		$('.icheck').iCheck({
		    checkboxClass: 'icheckbox_minimal-red',
			increaseArea: '20%' 
		});


		$('input').on('ifChanged', function(event){
			if($(this).is(':checked')==true){
				$(this).parent().parent().parent().addClass("task-done");
				$.get("/admintask/finish_action/"+$(this).val()+"/Y/"+Math.round(new Date().getTime()),function(data){
					
					if(data!="1") alert("권한이 없습니다.");
				});
			} else {
				$(this).parent().parent().parent().removeClass("task-done");
				$.get("/admintask/finish_action/"+$(this).val()+"/N/"+Math.round(new Date().getTime()),function(data){
					
					if(data!="1") alert("권한이 없습니다.");
				});				
			}
		});
	});
}

function edit(id){
	$.getJSON("/admintask/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			
			if(key=="id") {
				$("#task_id").val(val);
			}
			
			if(key=="title") {
				$("#title").val(val);
			}

			if(key=="name") {
				$("#name").val(val);
			}

			if(key=="deaddate") {
				$("#deaddate").val(val);
			}

			if(key=="important") {
				$("#important"+val).prop('checked',true);
				$("#important"+val).parent().addClass("checked");
			}

			if(key=="content") {
				CKEDITOR.instances.content_edit.setData(val);
			}

			if(key=="member_id") {
				$("#member_id").val(val);
			}

		});
	});
}

</script>

<!-- 페이지 헤더 시작 -->
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				<?php echo lang("site.contact");?> <small>보기</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="/admincontact/index"><?php echo lang("site.contact");?></a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="#">보기</a>
				</li>
			</ul>
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
					메뉴 <i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="/admincontact/index/"><?php echo lang("site.list");?></a>
						</li>
						<?php if($this->session->userdata("admin_id")==$query->member_id || $this->session->userdata("auth_id")==1) {?>
						<li class="divider">
						</li>
						<li>
							<a href="/admincontact/edit/<?php echo $query->id;?>"><?php echo lang("site.modify");?></a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="#" onclick="$.print('#detail');"><?php echo lang("site.print");?></a>
						</li>
						<li class="divider">
						</li>
						<li>
							<a href="#" onclick="delete_product('<?php echo $query->id;?>');"><?php echo lang("site.delete");?></a>
						</li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div><!-- /row -->
<!-- 페이지 헤더 종료 -->


<!-- 상세 정보 시작 -->
 <div class="row">
	<div class="col-md-12">
		<input type="hidden" name="id" value="<?php echo $query->id?>"/>
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-user"></i> <?php echo $query->name;?> 
					<?php if($query->is_opened=="1") {echo "[공개]";} else {echo "[비공개]";}?> 
				</div>
				<div class="tools">
					<i class="fa fa-sign-in"></i> <?php echo $query->regdate;?> 
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					<form class='form-horizontal'>
						<div class="row"><!-- row start -->
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">회사 / 직책</label>
									<div class="col-md-9">
										<span class="form-control-static"> <?php echo $query->organization?> <?php echo $query->role?></span>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label"><?php echo lang("site.email");?></label>
									<div class="col-md-9">
										<span class="form-control-static"> 
											<?php echo multi_view($query->email,"email");?>
										</span>
									</div>
								</div>
							</div>
						</div><!-- row end -->
						<div class="row"><!-- row start -->
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">성별</label>
									<div class="col-md-9">
										<span class="form-control-static"> <?php if($query->sex=="M") {echo "남자";} else {echo "여자";}?></span>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label"><?php echo lang("site.tel");?></label>
									<div class="col-md-9">
										<span class="form-control-static"> <?php echo multi_view($query->phone,"phone");?></span>
									</div>
								</div>
							</div>
						</div><!-- row end -->
						<div class="row"><!-- row start -->
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label"><?php echo lang("product.owner");?></label>
									<div class="col-md-9">
										<span class="form-control-static">
										<?php if($member!=""){
											echo $member->name . "(" . $member->email . ")";
										}?>
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label"><?php echo lang("site.address");?></label>
									<div class="col-md-9">
										<span class="form-control-static"> <?php echo multi_view($query->address,"address");?></span>
									</div>
								</div>
							</div>
						</div><!-- row end -->
						<div class="row"><!-- row start -->
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">배경설명</label>
									<div class="col-md-9">
										<span class="form-control-static"> <?php echo $query->background?></span>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">홈페이지</label>
									<div class="col-md-9">
										<span class="form-control-static"> <?php echo multi_view($query->homepage,"homepage");?></span>
									</div>
								</div>
							</div>
						</div><!-- row end -->

						</form>

				</div> <!-- form-body -->
			</div> <!-- portlet-body -->
		</div><!-- portlet -->
	</div>
</div><!-- row -->
<!-- 상세 정보 종료 -->

<div class="row">
	<div class="col-md-6">
		<!-- BEGIN PORTLET-->
		<div class="portlet paddingless">
			<div class="portlet-title line">
				<div class="caption">
					<i class="fa fa-comments"></i>메모 & 일정
				</div>
				<div class="tools">
					<a href="" class="collapse">
					</a>
					<a href="" class="reload">
					</a>
					<a href="" class="fullscreen">
					</a>
				</div>
			</div>
			<div class="portlet-body" id="chats">
				<!--BEGIN TABS-->
				<div class="tabbable tabbable-custom">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#tab_1_1" data-toggle="tab">
							접촉(<?php echo $count_contact?>) </a>
						</li>
						<li>
							<a href="#tab_1_2" data-toggle="tab">
							메모(<?php echo $count_memo?>) </a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1_1">
							<div class="chat-form">
								<?php echo form_open("adminmemo/add_action_action","id='action_form'");?>
								<input type="hidden" name="contacts_id" value="<?php echo $query->id;?>"/>

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
						<div class="tab-pane" id="tab_1_2">
							
							<div class="chat-form">
								<?php echo form_open("adminmemo/add_action","id='memo_form'");?>
								<input type="hidden" name="contacts_id" value="<?php echo $query->id;?>"/>
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
					</div>
				</div>
				<!--END TABS-->		
			</div>
		</div>
		<!-- END PORTLET-->
		<!-- BEGIN PORTLET-->
		<div class="portlet tasks-widget">
			<div class="portlet-title line">
				<div class="caption">
					<i class="fa fa-check"></i>업무
				</div>
				<div class="actions">
					<a href="javascript:;" class="btn btn-default easy-pie-chart-reload" data-toggle="modal" data-target="#add_dialog">
						<i class="fa fa-plus"></i> 추가 </a>
				</div>
			</div>
			<div class="portlet-body">
				<div class="task-content">
					<ul id="task-list" class="task-list"></ul>
				</div>
			</div>
		</div>
		<!-- END PORTLET-->	
	</div>
	<div class="col-md-6">
		<!-- BEGIN PORTLET-->
		<div class="portlet tasks-widget">
			<div class="portlet-title line">
				<div class="caption">
					<i class="fa fa-folder-open"></i>소유주의 <?php echo lang("product");?>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-bordered table-striped table-condensed flip-content">
					<thead>
						<tr>
							<th style="width:110px;"><?php echo lang("site.photo");?></i></th>
							<th style="width:100px;"><?php echo lang("site.information");?></th>
							<th><?php echo lang("site.title");?>/<?php echo lang("site.address");?></th>
							<th style="width:100px;" class="hidden-xs">정보</th>
							<th style="width:100px;" class="hidden-xs">등록일/담당</th>
						</tr>
					</thead>
					<tbody id="search-items">
						<?php if(count($products)<1){?>
						<tr><td class="text-center" colspan='5'><?php echo lang("msg.nodata");?></td></tr>
						<?php } ?>
						<?php
						$link = "#";
						foreach($products as $val){
							if($this->session->userdata("auth_id")=="1" || $this->session->userdata("admin_id")==element("member_id",$val)){
								$link = "/adminproduct/view/" . element("id",$val) ;
							}
						?>
						<tr>
							<td>
								<!-- 썸네일 -->
								<div class="gallery_wrapper">
									<a href="<?php echo $link?>" target="_blank">
									<?php if( element("thumb_name",$val) == "" ) {?>
										<img src="/assets/common/img/no_thumb.png" width="100%"/>
									<?php } else { ?>
										<img class="img-responsive" src="/photo/gallery_thumb/<?php echo element("gallery_id",$val);?>"  style="height:80px;"></a>
									<?php }?>
									</a>
								</div>
							</td>
							<td>
								<!-- 기본정보 -->
								<a href="<?php echo $link?>" target="_blank">
									<strong><?php echo element("id",$val)?></strong>
								</a><br/>
								<?php echo element("name",$val)?><br/>
								<?php if( element("part",$val)=="N") {?>
									<i class="fa fa-building help"  data-toggle="tooltip" title="전체 거래"></i>
							<?php }?>
								<?php if( element("type",$val)=="sell")  echo lang("sell"); ?>
								<?php if( element("type",$val)=="installation")  echo lang("installation"); ?>
								<?php if( element("type",$val)=="full_rent")  echo lang("full_rent"); ?>
								<?php if( element("type",$val)=="monthly_rent")  echo lang("monthly_rent"); ?>
								<?php if( element("type",$val)=="rent")  echo lang("rent"); ?>
							</td>
							<td>
								<!-- 제목 및 주소, 가격 등 -->
								<?php if( element("is_activated",$val)=="1")  { ?><span class="label label-sm label-primary">공개</span><?php } else {?><span class="label label-sm label-danger">비공개</span><?php }?>
								<?php if( element("recommand",$val)=="1")  { ?><span class="label label-sm label-success">추천</span><?php }?>
								<?php if( element("is_finished",$val)=="1")  { ?><span class="label label-sm label-default">완료</span><?php }?>
								<?php if( element("is_speed",$val)=="1")  { ?><span class="label label-sm label-warning">급매</span><?php }?>
								<a href="<?php echo $link?>" target="_blank"><b><?php echo element("title",$val)?></b></a>
								<br><small><?php echo element("address_name",$val)?> <?php echo element("address",$val)?></small>
								<br><?php echo price($val, $config);?>
								
								<!-- 비밀메모, 집주인명, 연락처 -->
								<?php if(element("secret",$val)!="") {?><br/><span class="text-danger" style="margin-top:5px;"><i class="fa fa-lock"></i> <?php echo element("secret",$val)?></span><?php }?>
							</td>
							<td class="hidden-xs">
								<small><?php echo lang("product.area");?> <?php echo area_admin(element("real_area",$val),"");?>/<?php echo area_admin(element("law_area",$val),"");?> </small>
								<?php if( element("part",$val)=="Y") {?>
								<small><?php echo lang("product.roomcnt");?> <?php echo element("bedcnt",$val)?>/<?php echo element("bathcnt",$val)?> </small><br/>
								<?php }?>
								<?php if(!$config->USE_FACTORY){?>
								<small><?php echo lang("product.floor");?> <?php echo element("current_floor",$val)?>/<?php echo element("total_floor",$val)?> </small>
								<?php } ?>


							</td>
							<td class="hidden-xs">
								<small><?php echo element("date",$val)?></small><br/>
								<i class="fa fa-pencil-square-o"></i> <?php echo element("member_name",$val)?><br/>조회 <span class='badge'><?php echo element("viewcnt",$val)?></span>
							</td>
						</tr>
						<?php 
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<!-- END PORTLET-->
	</div><!-- SECTION 2-2 -->
</div><!-- row -->

<?php echo form_open("admintask/add_action",Array("id"=>"add_form","class"=>"form-horizontal"))?>
<input type="hidden" name="contacts_id" value="<?php echo $query->id;?>"/>
<div id="add_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">할일 추가</h4>
      </div>
      <div class="modal-body">
			<div class="form">
				
				<div class="form-body">
			
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang("site.title");?> <span class="required" aria-required="true"> * </span></label>
						<div class="col-md-9">
							<input type="text" name="title" class="form-control" placeholder="작업내용(100자이내)" autocomplete="off"/>
						</div>
					</div>	
					<div class="form-group">
						<label class="col-md-3 control-label">완료기한일</label>
						<div class="col-md-9">
							<input type="text" name="deaddate" class="form-control form-control-inline input-small date-picker" autocomplete="off"  value="<?php echo date('Y-m-d');?>"/>
						</div>
					</div>						
					<div class="form-group">
						<label class="col-md-3 control-label">중요도</label>
						<div class="col-md-9">
							<label class="radio-inline">
							<input type="radio" name="important" value="1" checked autocomplete="off"> 상 </label>
							<label class="radio-inline">
							<input type="radio" name="important" value="2" checked autocomplete="off"> 중 </label>
							<label class="radio-inline">
							<input type="radio" name="important" value="3" autocomplete="off"> 하 </label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">작업내용 </label>
						<div class="col-md-9">
							<textarea name="content_add" class="form-control" rows="5" autocomplete="off"></textarea>
						</div>
					</div>		
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang("product.owner");?> <span class="required" aria-required="true"> * </span></label>
						<div class="col-md-9">
							<select name="member_id" class="form-control input-large select2me" autocomplete="off">
								<?php foreach($members as $val) {?>
									<option value="<?php echo $val->id?>" <?php if($val->id==$this->session->userdata("admin_id")){echo "selected";}?>><?php echo $val->name?> (<?php echo $val->email?>, <?php echo $val->phone?>)</option>
								<?php } ?>
							</select>
						</div>
					</div>					
				</div>
				
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
        <button type="submit" class="btn btn-primary"><?php echo lang("site.submit");?></button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>

<?php echo form_open("admintask/edit_action",Array("id"=>"edit_form","class"=>"form-horizontal"))?>
<input type="hidden" id="contacts_id" name="contacts_id" value="<?php echo $query->id;?>"/>
<input type="hidden" id="task_id" name="task_id"/>
<div id="edit_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">할일 수정</h4>
      </div>
      <div class="modal-body">
			<div class="form">
				<div class="form-body">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang("site.title");?> <span class="required" aria-required="true"> * </span></label>
						<div class="col-md-9">
							<input type="text" id="title" name="title" class="form-control" placeholder="작업내용(100자이내)" autocomplete="off"/>
						</div>
					</div>	
					<div class="form-group">
						<label class="col-md-3 control-label">완료기한일</label>
						<div class="col-md-9">
							<input type="text" id="deaddate" name="deaddate" class="form-control form-control-inline input-small date-picker" autocomplete="off"/>
						</div>
					</div>						
					<div class="form-group">
						<label class="col-md-3 control-label">중요도</label>
						<div class="col-md-9">
							<label class="radio-inline">
							<input type="radio" name="important" id="important1" value="1" autocomplete="off"> 상 </label>
							<label class="radio-inline">
							<input type="radio" name="important" id="important2" value="2" autocomplete="off"> 중 </label>
							<label class="radio-inline">
							<input type="radio" name="important" id="important3" value="3" autocomplete="off"> 하 </label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">작업내용</label>
						<div class="col-md-9">
							<textarea id="content_edit" name="content_edit" class="form-control" rows="5" autocomplete="off"></textarea>
						</div>
					</div>		
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang("product.owner");?> <span class="required" aria-required="true"> * </span></label>
						<div class="col-md-9">
							<select id="member_id" name="member_id" class="form-control input-large select2me" autocomplete="off">
								<?php foreach($members as $val) {?>
									<option value="<?php echo $val->id?>" <?php if($val->id==$this->session->userdata("admin_id")){echo "selected";}?>><?php echo $val->name?> (<?php echo $val->email?>, <?php echo $val->phone?>)</option>
								<?php } ?>
							</select>
						</div>
					</div>					
				</div>
				
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
        <button type="submit" class="btn btn-primary">수정</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>

<script>
	CKEDITOR.replace( 'content_add', {customConfig: '/ckeditor/task_config.js'});
	CKEDITOR.replace( 'content_edit', {customConfig: '/ckeditor/task_config.js'});
</script>

<div id="upload_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;">
	<?php echo form_open_multipart("admintask/upload_action","id='upload_form' autocomplete='off'");?>
	<div class="help-block">* 큰 이미지는 넓이(폭)이 600픽셀 이하로 조정됩니다.</div>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
	<?php echo form_close();?>
</div>

<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/locales/bootstrap-datepicker.kr.js" charset="UTF-8"></script>
<script type="text/javascript" src="/assets/plugin/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="/assets/plugin/icheck/icheck.min.js"></script>
