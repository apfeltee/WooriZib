<script>
$(document).ready(function() {

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
				$("#ask_dialog").modal('show');

				var obj = $.parseJSON(data);
				$.each(obj, function(key, val) {
					$("#"+key).html(val);
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
	$("#ask_id").val(id);
}

function get_ask(id){
	$.ajax({
		url: "/ask/get",
		type: "POST",
		data: {
			ask_id : id,
		},
		success: function(data) {
			if(data){
				var obj = $.parseJSON(data);
				$.each(obj, function(key, val) {
					$("#"+key).html(val);
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
			<li class="active"><?php echo lang("qna_title");?></li>
		</ul>
		<div class="row margin-bottom-40">
			<div class="col-md-12 col-sm-12">
				<h1 class="margin-bottom-20"><?php echo lang("qna_title");?></h1>
				<ul class="nav nav-tabs">
					<li><a class="section_tab" href="/member/enquire"><?php echo lang("enquire.title");?></a></li>
					<li class="active"><a class="section_tab" href="/ask/index"><?php echo lang("qna_title");?></a></li>
				</ul>
			</div>

			<!-- BEGIN CONTENT -->
			<div class="col-md-12 col-sm-12">
				<table class="table table-bordered table-striped table-condensed flip-content margin-top-20">
					<thead>
						<tr>
							<th class="text-center" width="10%"><?php echo lang("site.number");?></th>
							<th class="text-center" width="*"><?php echo lang("site.title");?></th>
							<th class="text-center" width="15%"><?php echo lang("site.name");?></th>
							<th class="text-center" width="10%"><?php echo lang("site.status");?></th>
							<th class="text-center" width="20%"><?php echo lang("site.regdate");?></th>
						</tr>
					</thead>
					<tbody>
						<?php if(count($query)==0){?>
						<tr class="text-center">
							<td colspan="5">
								<?php echo lang("msg.nodata");?>
							</td>
						</tr>						
						<?php }?>
						<?php foreach($query as $val){?>
						<tr class="text-center">
							<td>
								<?php echo $val->id?>
							</td>
							<td>
							<?php if($val->open=="N"){?>
								<?php if($this->session->userdata("auth_id")==1){?>
									<a href="#" data-toggle="modal" data-target="#ask_dialog" onclick="get_ask(<?php echo $val->id?>)"><?php echo $val->title?></a> <i class="fa fa-lock"></i>
								<?php }else{?>
									<a href="#" data-toggle="modal" data-target="#pw_dialog" onclick="open_pw_modal(<?php echo $val->id?>)"><?php echo $val->title?></a> <i class="fa fa-lock"></i>				
								<?php }?>
							<?php } else {?>
								<a href="#" data-toggle="modal" data-target="#ask_dialog" onclick="get_ask(<?php echo $val->id?>)"><?php echo $val->title?></a>			
							<?php }?>
							</td>
							<td>
								<?php echo $val->name?>
							</td>
							<td>
								<?php echo ($val->answer) ? "<strong>답변완료</strong>" : "미답변"?>
							</td>
							<td>
								<?php echo $val->date?>
							</td>
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
				<div class="pull-right">
					<button class="btn btn-primary" onclick="javascript:location.href='/ask/add'"><?php echo lang("site.submit");?></button>
				</div>
			</div>
			<!-- END CONTENT -->	
		</div>
		<!-- END SIDEBAR & CONTENT -->
	</div>
</div>

<!-- ENQUIRE DIALOG -->
<div id="ask_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="max-width:700px;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 id="title"></h4>				
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped-left table-condensed flip-content margin-top-20">
					<tbody>
						<tr>
							<td class="text-center" width="120"><?php echo lang("site.name");?></td>
							<td id="name"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("site.contact");?></td>
							<td id="phone"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("site.email");?></td>
							<td id="email"></td>
						</tr>
						<tr>
							<td class="text-center"><?php echo lang("site.content");?></td>
							<td id="content"></td>
						</tr>
						<tr>
							<td class="text-center">답변내용</td>
							<td id="answer"></td>
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
<form id="pw_form" name="pw_form" action="/ask/ask_pw" method="post">
<input type="hidden" id="ask_id" name="ask_id"/> 
<div id="pw_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="max-width:450px;">
		<div class="modal-content">
			<div id="msg"></div>
			<div class="modal-footer">
				<div class="inline">
					<i class="fa fa-lock"></i> <?php echo lang("site.pw");?> <input class="form-control inline" type="password" name="pw" style="width:200px;"/>
				</div>
				<div class="inline">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
					<button type="submit" class="btn btn-primary"><?php echo lang("site.confirm");?></button>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
<!-- PW FORM -->