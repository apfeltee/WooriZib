<link type="text/css" href="/assets/plugin/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/locales/bootstrap-datepicker.kr.js" charset="UTF-8"></script>
<script>
$(document).ready(function(){
	if($.datepicker){
		$('.date-picker').datepicker({
			format: "yyyy-mm-dd",
			orientation: "left",
			language: "kr",
			autoclose: true
		});
	}

	$( "#member_name" ).autocomplete({
		selectFirst: true, 
		autoFill: true,
		autoFocus: true,
		focus: function(event,ui){
			return false;
		},
		scrollHeight:40,
		minlength:1,
		select: function(a,b){
			$("#member_name").val(b.item.member_name);
			$("#member_id").val(b.item.member_id);
			a.stopPropagation(); 
			return false;
		},
		source: function(request, response){
			$.ajax({
				url: "/search/member_list",
				type: "POST",
				data: {
					search: $("#member_name").val()
				},
				dataType: "json",
				success: function(data) {
					response( $.map( data, function( item ) {
						return {
							member_id: item.id,
							member_name: item.name,
							member_email: item.email
						}; 
					}));
				}
			});						
		},
	}).data("ui-autocomplete")._renderItem = autoCompleteRenderAdmin;

});

function autoCompleteRenderAdmin(ul, item) {
	return $("<li class='search_rows'></li>").data("item.autocomplete", item).append("<i class='fa fa-user'></i> " + item.member_name+'('+item.member_email+')').appendTo(ul);
}

function search_reset(){
	$("#member_id").val("");
	$("#member_name").val("");
	$("#date1").val("");
	$("#date2").val("");
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">결제 내역</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="/adminhome/index"><?php echo lang("menu.home");?></a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="#">결제 관리</a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="#">결제 내역</a>
				</li>
			</ul>
		</div>
	</div>
</div><!-- /.row -->


<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading"><?php echo lang("site.search");?></div>
		  <div class="panel-body">
				<!-- BEGIN FORM-->
				<?php echo form_open("/adminpay/index",Array("id"=>"list_form","class"=>"form-inline","method"=>"get"))?>
				<input type="hidden" name="member_id" id="member_id">
				<div class="input-group">
					<input type="text" class="form-control" name="member_name" id="member_name" placeholder="회원명" autocomplete="off" class="ui-autocomplete-input" style="width:270px;" value="<?php echo $this->input->get("member_name");?>">
				</div>
				<div class="input-group">
					<input type="text" class="form-control date-picker" name="date1" id="date1" placeholder="결제일(부터)" value="<?php echo $this->input->get("date1");?>">
				</div>
				<div class="input-group">
					<input type="text" class="form-control date-picker" name="date2" id="date2" placeholder="결제일(까지)" value="<?php echo $this->input->get("date2");?>">
				</div>
				<button type="submit" class="btn btn-warning"><?php echo lang("site.search");?></button>
				<button type="button" class="btn btn-default" onclick="search_reset()"><?php echo lang("site.initfilter");?></button>
				<?php echo form_close();?>
				<!-- END FORM-->			
		  </div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="help-block">* 총 <strong><?php echo $total;?></strong>건의 내역이 검색되었습니다.</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center">주문번호</th>
					<th class="text-center">회원</th>
					<th class="text-center">상품명</th>
					<th class="text-center">시작일</th>
					<th class="text-center">종료일</th>
					<th class="text-center">금액</th>
					<th class="text-center"><?php echo lang("site.status");?></th>
					<th class="text-center">결제일</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!$query){?>
				<tr>
					<td class="text-center" colspan="8"><?php echo lang("msg.nodata");?></td>
				</tr>				
				<?php }?>
				<?php foreach($query as $val){?>
				<tr>
					<td class="text-center"><?php echo $val->id;?></td>
					<?php if($val->type=='biz'){?>
					<td class="text-center"><?php echo $val->biz_name;?> (<?php echo $val->name;?>)</td>
					<?php } else { ?>
					<td class="text-center"><?php echo $val->name;?></td>
					<?php } ?>
					<td class="text-center"><?php echo $val->order_name;?></td>
					<td class="text-center"><?php echo date("Y-m-d H:i",strtotime($val->start_date));?></td>
					<td class="text-center"><?php echo date("Y-m-d H:i",strtotime($val->end_date));?></td>
					<td class="text-center"><?php echo ($val->price==0)?"무료":number_format($val->price)."원";?></td>
					<td class="text-center"><?php echo ($val->state=="Y") ? "결제완료" : "미결제";?></td>
					<td class="text-center"><?php echo $val->date;?></td>
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
	</div>