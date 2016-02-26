<style>
.blog_loading {
	padding-top:420px;
	z-index:9999;
	background-color:#aaa;
	opacity:0.7;
}
</style>
<script>
$(document).ready(function(){

	$("#building_form").validate({ 
		rules: {
			excel_file: {  
				required: true
			}
		},  
		messages: {
			excel_file: {  
				required: "업로드 파일을 올려주세요"
			}
		} 
	});
});


function form_submit(){
	if($("#building_form").valid()){
		$(".blog_loading").show();
		$("#building_form").submit();	
	}
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">건축물정보 업로드</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i></li>
				<li>건축물정보 업로드</li>
			</ul>
		</div>
	</div>
</div>
<div class="blog_loading"><span>데이타 처리중 입니다. 기다려 주세요. <i class="fa fa-spinner fa-spin"></i></span></div>
<div class="row">
	<div class="col-lg-6">
		<div class="note note-success">
			<p>* 양식서를 다운 받아 입력 후 자료를 업로드 해주시기 바랍니다.</p>
			<p class="text-danger">* 변조된 양식서 또는 엑셀이 아닌 파일을 업로드 할 경우 업로드가 되지 않습니다.</p>
			<p class="text-danger">* 양식서를 새로 업로드하게 될 경우 이전 자료는 모두 삭제 됩니다.</p>
		</div>
		<div class="note note-info">
			<p>* 첫 번째 줄에 타이틀이 들어가야 합니다.</p>
			<p>* 대지위치(파란색)는 필수입력사항입니다.</p>
			<p>* 노란색 항목은 사용하는 항목이지만 필수입력은 아닙니다.</p>
			<p>* 흰색 셀의 값은 사용하지 않으므로 입력을 하지 않으셔도 됩니다.</p>
			<p>* 서울시 데이터만 적용됩니다. (서울시 API를 사용함)</p>			
		</div>
		<h4>등록된 총 건축물정보 : <strong><?php echo number_format($total);?></strong>건</h4>
	</div>
	<div class="portlet col-lg-6">
		<div class="portlet margin-top-20">
			<h4>양식서</h4>
			<div class="well" style="margin:0px;">
				<div>
					<span style="font-size:20px;"><i class="fa fa-file-excel-o" style="font-size:20px;"></i> 건축물정보 양식서.xlsx </span>
				</div>
				<div>
					<a href="/attachment/building_form_download"><button type="button" class="btn btn-success margin-top-10">양식서 다운 받기 <i class="fa fa-download"></i></button></a>		
				</div>
			</div>
		</div>
		<div class="portlet">
			<h4>파일 업로드</h4>
			<div class="well" style="margin:0px;">
				<?php echo form_open_multipart("adminbuilding/upload_action",Array("id"=>"building_form"))?>
				<div>
					<input type="file" class="form-control input-large" name="excel_file" style="display:inline;height:auto;" accept=".xls"/>
				</div>
				<div class="text-left">
					<button type="button" class="btn btn-primary margin-top-10" onclick="form_submit()">업로드</button>
				</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>