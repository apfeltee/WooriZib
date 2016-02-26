<!DOCTYPE html>
<html>
<head>
<link href="/assets/plugin/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/assets/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="/assets/plugin/jquery.min.js" type="text/javascript"></script>
<script src="/assets/plugin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	var strWidth = 460;
	var strHeight = 760;

	if ( window.innerWidth && window.innerHeight && window.outerWidth && window.outerHeight ) {
		strWidth = $('#container').outerWidth() + (window.outerWidth - window.innerWidth);
		strHeight = $('#container').outerHeight() + (window.outerHeight - window.innerHeight);
	}
	else {
		var strDocumentWidth = $(document).outerWidth();
		var strDocumentHeight = $(document).outerHeight();

		window.resizeTo ( strDocumentWidth, strDocumentHeight );

		var strMenuWidth = strDocumentWidth - $(window).width();
		var strMenuHeight = strDocumentHeight - $(window).height();
		strWidth = $('#container').outerWidth() + strMenuWidth;
		strHeight = $('#container').outerHeight() + strMenuHeight;
	}
	window.resizeTo( strWidth, strHeight );

	$("#blog_form").validate({
		rules: {
			blog_name: {  
				required: true
			}
		},  
		messages: {  
			blog_name: {  
				required: "<?php echo lang("form.required");?>"
			}
		} 
	});
});

function blog_history(menu_id){

	var id = $("#id").val();
	var type = $("#type").val();
	var blog_name = $("#blog_name").val();
	var blog_category = $("#blog_category").val();

	$.ajax({
		url: "/adminblogapi/get_history_daum/"+Math.round(new Date().getTime()),
		type: "POST",
		dataType: "json",
		data: {
			id: id,
			type: type,
			blog_name: blog_name,
			blog_category: blog_category
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

function blog_upload(){
	if($("#blog_category").val()){

		var blog_title = $("#blog_title").val();

		var is_equal = false;
		$("#history_list").find(".history").css("color","");
		$("#history_list").find(".history").each(function(){
			if($(this).html() == blog_title){
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

		if(confirm("블로그에 등록 하시겠습니까?")){
			$("#blog_form").submit();
		}	
	}
	else{
		alert("블로그 카테고리를 선택해주시기 바랍니다.");
		$("#blog_category").focus();
		return false;
	}
}
</script>
</head>
<body id="container">
<div class="modal-header" style="font-size:18px;"><strong>블로그등록하기</strong></div>
<?php if($insert_blog_name){?>
	<form id="blog_form" name="blog_form" action="/adminblogapi/product_upload_daum" method="post">
	<input type="hidden" name="type" id="type" value=""/>
	<input type="hidden" name="id" id="id" value="<?php echo $query->id;?>"/>
	<input type="hidden" name="blog_name" id="blog_name" value="<?php echo $blog_name;?>"/>
	<div class="modal-body">
		<div class="form-group">
			<textarea class="form-control" id="blog_title" name="blog_title" rows="4" maxlength="100"><?php echo cut($query->title,200);?></textarea>
			<label style="margin-top:10px;"># 블로그 카테고리</label>
			<select class="form-control" id="blog_category" name="blog_category" onchange="blog_history(this.value);">
			<option value="">카테고리 선택</option>
			<?php
				foreach($category as $val){
					echo '<option value="'.$val['categoryId'].'">'.$val['name'].'</option>';
				}
			?>
			</select>
		</div>
		<div class="form-group" id="menu_list" style="min-height:60px;margin:0px 0px 20px 0px;"></div>
		<label># 이미 등록된 블로그 제목</label>
		<div class="well" id="history_list" style="height:270px;overflow-y:auto;padding-top:10px;">블로그 등록 이력이 없습니다.</div>
		<div class="help">※유사문서에 포함되지 않도록 이전에 등록된 제목은 다르게 하여 등록해주시기 바랍니다.</div>
	</div>
	<div class="modal-footer" style="text-align:center;">
		<button type="button" onclick="blog_upload();" class="btn btn-primary btn-lg">등록하기</button>
	</div>
	</form>
<?php } else { ?>
	<form id="blog_form" name="blog_form" action="/adminblogapi/daum_blog_callback" method="post">
	<div class="modal-body">
			<div class="form-group">
				<label style="margin-top:10px;"># 블로그 아이디</label>
				<input type="text" class="form-control" name="blog_name" placeholder="블로그 아이디" style="margin:20px 0px;" maxlength="20"/>
				<div class="help">ex) http://blog.daum.net/<strong style="color:red">webtron</strong></div>
				<div class="help">※블로그 주소 뒤에 아이디값을 입력하여 주시기 바랍니다.</div>
				<div class="help">※블로그 아이디는 DAUM 로그인을 했을때 소유주와 같아야 합니다.</div>
				<div class="help">※블로그 아이디를 본인과 무관한 아이디를 입력하였을땐 등록되지 않습니다.</div>
			</div>
	</div>
	<div class="modal-footer" style="text-align:center;">
		<button type="submit" class="btn btn-primary btn-lg">다음</button>
	</div>
	</form>
<?php }?>
</body>
</html>