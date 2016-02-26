<script>
$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	$("#pw_form").validate({  
		rules: {
			pw: {  
				required: true
			}
		},  
		messages: {  
			pw: {  
				required: "<?php echo lang("form.required");?>"
			}
		} 
	});

	$("#pw_form").ajaxForm({
		success:function(data){
			if(data){
				$("#pw_dialog").modal('hide');
				$("#enquire_dialog").modal('show');

				var obj = $.parseJSON(data);
				$.each(obj, function(key, val) {
					switch(key){
						case "status" :
							if(val=="N") $("#"+key).html("대기");
							if(val=="G") $("#"+key).html("진행");
							if(val=="Y") $("#"+key).html("완료");
							break;
						case "gubun" :
							if(val=="buy") $("#"+key).html("매수");
							if(val=="sell") $("#"+key).html("매도");
							break;
						case "type" :
							if(val=="sell") $("#"+key).html("<?php echo lang('sell')?>");
							if(val=="full_rent") $("#"+key).html("<?php echo lang('full_rent')?>");
							if(val=="monthly_rent") $("#"+key).html("<?php echo lang('monthly_rent')?>");
							if(val=="installation") $("#"+key).html("<?php echo lang('installation')?>");
							break;
						default :
							$("#"+key).html(val);
							break;
					}
				});
			}
			else{
				$("#msg").css("padding","15px");
				msg($("#msg"), "danger" ,"<?php echo lang("form.pwerror");?>");
			}
		}
	});
});

function open_pw_modal(id){
	$("#msg").html("");
	$("#msg").css("padding","0px");
	$("input[name='pw']").val("");
	$("#enquire_id").val(id);
}

function get_enquire(id){
	$.ajax({
		url: "/member/enquire_get",
		type: "POST",
		data: {
			enquire_id : id,
		},
		success: function(data) {
			if(data){
				var obj = $.parseJSON(data);
				$.each(obj, function(key, val) {
					switch(key){
						case "status" :
							if(val=="N") $("#"+key).html("대기");
							if(val=="G") $("#"+key).html("진행");
							if(val=="Y") $("#"+key).html("완료");
							break;
						case "gubun" :
							if(val=="buy") $("#"+key).html("매수");
							if(val=="sell") $("#"+key).html("매도");
							break;
						case "type" :
							if(val=="sell") $("#"+key).html("<?php echo lang('sell')?>");
							if(val=="full_rent") $("#"+key).html("<?php echo lang('full_rent')?>");
							if(val=="monthly_rent") $("#"+key).html("<?php echo lang('monthly_rent')?>");
							if(val=="installation") $("#"+key).html("<?php echo lang('installation')?>");
							break;
						default :
							$("#"+key).html(val);
							break;
					}
				});
			}
		}
	});
}
</script>
<div class="main">
	<div class="_container">
		<ul class="breadcrumb">
			<li><a href="/"><?php echo lang("menu.home");?></a></li>
			<li>
			<?php foreach($mainmenu as $val){
				if($val->type=="enquire") echo $val->title;
			}?>
			</li>
			<li class="active"><?php echo lang("enquire.title");?></li>
		</ul>
		<!-- BEGIN SIDEBAR & CONTENT -->
		<div class="row margin-bottom-40">
			<div class="col-md-12 col-sm-12">
				<h1 class="margin-bottom-20"><?php echo lang("enquire.title");?></h1>
				<ul class="nav nav-tabs">
					<li class="active"><a class="section_tab" href="/member/enquire"><?php echo lang("enquire.title");?></a></li>
					<li><a class="section_tab" href="/ask/index"><?php echo lang("qna_title");?></a></li>
				</ul>
			</div>
			<!-- BEGIN CONTENT -->
			<div class="col-md-12 col-sm-12">
				<table class="table table-bordered table-striped table-condensed flip-content margin-top-20">
					<thead>
						<tr>
							<th class="text-center" width="10%"><?php echo lang("site.number");?></th>
							<th class="text-center" width="10%"><?php echo lang("site.type");?></th>
							<th class="text-center" width="15%"><?php echo lang("site.name");?></th>
							<th class="text-center" width="10%"><?php echo lang("product.type");?></th>
							<th class="text-center" width="*"><?php echo lang("product.category");?></th>
							<th class="text-center" width="10%"><?php echo lang("site.status");?></th>
							<th class="text-center" width="20%"><?php echo lang("site.regdate");?></th>
						</tr>
					</thead>
					<tbody>
						<?php if(count($query)==0){?>
							<tr class="text-center">
								<td colspan="7"><?php echo lang("msg.nodata");?></td>
							</tr>
						<?php }?>
						<?php foreach($query as $val){?>
						<tr class="text-center">
							<td>
								<?php echo $val["id"]?>
							</td>
							<td>
							<?php
								if($val["gubun"]=="buy") echo "매수";
								else if($val["gubun"]=="sell") echo "매도";
							?>
							</td>
							<td>
							<?php if($val["open"]=="N"){?>
								<?php if($this->session->userdata("auth_id")==1){?>
									<a href="#" data-toggle="modal" data-target="#enquire_dialog" onclick="get_enquire(<?php echo $val["id"]?>)"><?php echo $val["name"]?></a> <i class="fa fa-lock"></i>
								<?php }else{?>
									<a href="#" data-toggle="modal" data-target="#pw_dialog" onclick="open_pw_modal(<?php echo $val["id"]?>)"><?php echo $val["name"]?></a> <i class="fa fa-lock"></i>				
								<?php }?>
							<?php } else {?>
								<?php echo $val["name"]?>
							<?php }?>
							</td>
							<td>
							<?php
								if($val["type"]=="sell") echo lang('sell');
								else if($val["type"]=="full_rent") echo lang('full_rent');
								else if($val["type"]=="monthly_rent") echo lang('monthly_rent');
								else if($val["type"]=="installation") echo lang('installation');
							?>
							</td>
							<td>
							<?php foreach( $val["category_list"] as $val2){echo $val2->name . " " ;}?>
							</td>
							<td>
							<?php if($val["status"]=="N"){echo "<span class=\"label label-danger\">대기</span>";}?>
							<?php if($val["status"]=="G"){echo "<span class=\"label label-primary\">진행</span>";}?>
							<?php if($val["status"]=="Y"){echo "<span class=\"label label-default\">완료</span>";}?>
							</td>
							<td><?php echo $val["date"]?></td>
						</tr>
						<?php }?>
					</tbody>
				</table>
				<div class="row text-center">
					<div class="col-sm-12">
						<ul class="pagination">
							<?php echo $pagination?>
						</ul>
					</div>
				</div>
				<button class="btn btn-primary pull-right" onclick="javascript:location.href='/member/enquire_add'"><?php echo lang("site.submit");?></button>
			</div>
			<!-- END CONTENT -->
		</div>
		<!-- END SIDEBAR & CONTENT -->
	</div>
</div>

<!-- ENQUIRE DIALOG -->
<div id="enquire_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="max-width:700px;">
		<div class="modal-content">
			<div class="modal-header">
				<h2 id="name"></h2>				
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped-left table-condensed flip-content margin-top-20">
					<tbody>
						<tr>
							<td class="text-center" width="200"><?php echo lang("site.status");?></td>
							<td id="status"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("site.type");?></td>
							<td id="gubun"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("site.tel");?></td>
							<td id="phone"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("product.type");?></td>
							<td id="type"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("product.category");?></td>
							<td id="category_list"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("enquire.hopearea");?></td>
							<td id="location"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("enquire.movedate");?></td>
							<td id="movedate"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("enquire.visit");?></td>
							<td id="visitdate"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("enquire.price");?></td>
							<td id="price"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("enquire.etcrequest");?></td>
							<td id="content"></td>
						</tr>
						<tr style="border:2px solid black;">
							<td class="text-center"><?php echo lang("enquire.answer");?></td>
							<td id="work"></td>
						</tr>
					</tbody>
				</table>
			</div>	
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
				<button type="submit" class="btn btn-primary"><?php echo lang("site.confirm");?></button>
			</div>
		</div>
	</div>
</div>
<!-- ENQUIRE DIALOG -->

<!-- PW FORM -->
<form id="pw_form" name="pw_form" action="/member/enquire_pw" method="post">
<input type="hidden" id="enquire_id" name="enquire_id"/> 
<div id="pw_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="max-width:275px;">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">비밀번호 입력</h4>
		  </div>
			<div id="msg"></div>

				<div class="form-inline" style="margin:20px;">
					 <div class="form-group">
						<input class="form-control" type="password" name="pw"/>
						<button type="submit" class="btn btn-primary"><?php echo lang("site.confirm");?></button>
					  </div>
				</div>

			</div>
		</div>
	</div>
</div>
</form>
<!-- PW FORM -->