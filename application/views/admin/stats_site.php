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
});
function search_reset(){
	$("#mobile").val("");
	$("#date1").val("");
	$("#date2").val("");
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">사이트 방문 통계</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="/adminhome/index"><?php echo lang("menu.home");?></a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="#">사이트 방문 통계</a>
				</li>
			</ul>
		</div>
	</div>
</div><!-- /.row -->


<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading">검색</div>
		  <div class="panel-body">
				<!-- BEGIN FORM-->
				<?php echo form_open("adminstats/site",Array("id"=>"list_form","class"=>"form-inline","method"=>"get"))?>
				<div class="form-group">
					<select class="form-control input-small select2me" name="mobile" id="mobile">
						 <option value="">접속 종류</option>
						 <option value="0" <?php echo ($this->input->get("mobile")==0) ? "selected" : "";?>>웹</option>
						 <option value="1" <?php echo ($this->input->get("mobile")==1) ? "selected" : "";?>>모바일</option>
					</select>
				</div>
				<div class="input-group">
					<input type="text" class="form-control date-picker" name="date1" id="date1" placeholder="검색시작일" value="<?php echo $this->input->get("date1");?>">
				</div>
				<div class="input-group">
					<input type="text" class="form-control date-picker" name="date2" id="date2" placeholder="검색종료일" value="<?php echo $this->input->get("date2");?>">
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
		<div class="help-block">* 총 <?php echo $total;?>건의 로그가 검색되었습니다.</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" style="width:150px;">아이피</th>
					<th class="text-center">접속전페이지</th>
					<th class="text-center">사용자정보</th>
					<th class="text-center" style="width:140px;">웹/모바일</th>
					<th class="text-center" style="width:150px;">로그일자</th>

				</tr>
			</thead>
			<tbody>
				<?php if(!$query){?>
				<tr>
					<td class="text-center" colspan="5"><?php echo lang("msg.nodata");?></td>
				</tr>				
				<?php }?>
				<?php foreach($query as $value){?>
				<tr>
					<td class="text-center"><?php echo $value->ip;?></td>
					<td><?php echo $value->user_referrer;?></td>
					<td><?php echo $value->user_agent;?></td>
					<td class="text-center"><?php echo ($value->mobile==0) ? "웹" : "모바일";?></td>
					<td class="text-center"><?php echo $value->date;?></td>
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
</div>