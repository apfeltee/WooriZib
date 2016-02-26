<?php
	header("Content-Type: text/html; charset=UTF-8");
?>
<html>
<head>
<link href="/assets/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="/assets/plugin/icheck/skins/square/red.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="screen" href="/assets/basic/css/style.css">
<script src="/assets/plugin/jquery.min.js" type="text/javascript"></script>
<script src="/assets/plugin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/plugin/icheck/icheck.min.js" type="text/javascript"></script>
</head>
<body>
<style>
.blog_loading {
	color:black;
	text-align:center;
	padding-top:320px;
	z-index:100;
    position: fixed;
    display:none;
    height:100%;
    width:100%;
    top:0px;
    left:0px;
}

.blog_loading span {
	background-color:#efefef;
	border:1px solid #cacaca;
	padding:35px;
}
</style>
<script>
$(document).ready(function(){
	$(".category_checkbox").iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
		increaseArea: '20%'
	});
});

function blog_upload(id,type){

	var memeber_type ="<?php echo $this->session->userdata('type')?>";
	var process_url = (memeber_type=="biz") ? "member" : "adminblogapi";

	if($("#blog_id").val()==""){
		alert("블로그를 선택 해주시기 바랍니다.");
		$("#blog_id").focus();
		return false;
	}

	var is_equal = false;
	$("#history_list").find(".history").css("color","");
	$("#history_list").find("div").each(function(){
		if($(this).html() == $("#blog_title").val()){
			alert("이전에 등록한 블로그 제목이 동일합니다.\n\n유사문서로 노출이 안될 수 있으니\n\n제목을 다르게 수정하여 등록 해주시기 바랍니다.");
			$(this).css("color","red");
			is_equal = true;
			return false;
		}
	});

	if(is_equal){
		$("#blog_title").focus();
		return false;
	}

	$(".blog_loading").show();

	type = (type=="news" || type=="installation") ? type : "posting";

	$.ajax({
		url: "/"+process_url+"/"+type+"/"+id+"/"+Math.round(new Date().getTime()),
		type: "POST",
		data: {
			blog_id: $("#blog_id").val(),
			blog_title: $("#blog_title").val()
		},
		success: function(data) {
			if(data=="success"){
				$(".blog_loading").hide();
				alert("성공했습니다.");
				window.close();
			} else {
				$(".blog_loading").hide();
				alert("등록에 실패하였습니다. 블로그 점검 중일 수 있습니다.");
				window.close();
			}
		}
	});
}
function blog_history(id,type,blog_id){

	$.ajax({
		url: "/adminblogapi/get_history/"+Math.round(new Date().getTime()),
		type: "POST",
		dataType: "json",
		data: {
			id: id,
			type: type,
			blog_id: blog_id
		},
		success: function(data) {
			var str = "";
			if(data!=""){
				$.each(data, function(key, val) {
					if(val['title']){
						str += "<div class='history' style='border-bottom:1px solid #e5e5e5;padding:5px 0 5px 0;'>"+val['title']+"</div>";
					}					
				});			
			}
			if(!str) str = "<?php echo lang("msg.nodata");?>";

			$("#history_list").html(str);
		}
	});	

}
</script>
<div class="blog_loading"><span>블로그 등록중 입니다 <i class="fa fa-spinner fa-spin"></i></span></div>
<div class="modal-header" style="font-size:18px;"><strong>블로그 등록하기</strong></div>
<div class="modal-body">
	<div class="form-group">
	<h4># 블로그 제목</h4>
	<textarea class="form-control" id="blog_title" name="blog_title" rows="4"/><?php echo $query->title?></textarea>
	<select class="form-control margin-top-20 margin-bottom-20" id="blog_id" name="blog_id" onchange="blog_history('<?php echo $query->id?>','<?php echo $type?>',this.value);">
		<option value="">블로그 선택</option>
		<?php foreach($blog as $val){?>
		<option value="<?php echo $val->id?>">[ <?php echo ($val->type=="naver")?"네이버":"티스토리"?> ] <?php echo $val->address?></option>
		<?php }?>
	</select>
	<h4># 이미 등록된 블로그 제목</h4>
	<div class="well" id="history_list" style="height:270px;overflow-y:auto;padding-top:10px;"><?php echo lang("msg.nodata");?></div>
	<div class="help margin-top-20">※ 유사문서로 등록 될 경우는 노출이 잘 되지 않습니다.</div>
	<div class="help">※ 등록중에는 시간이 다소 소요될 수 있습니다.</div>
</div>
<div class="modal-footer" style="text-align:center;">
	<button type="button" onclick="blog_upload('<?php echo $query->id?>','<?php echo $type?>');" class="btn btn-primary btn-lg">등록하기</div>
</div>
</body>
</html>