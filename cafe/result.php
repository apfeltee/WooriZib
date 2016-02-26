<?php
header("Content-Type: text/html; charset=UTF-8");

session_start();

$message = urldecode($_GET['message']);

$cafe_url = ($_GET['cafe_url']) ? urldecode($_GET['cafe_url']) : "";
?>
<html>
<head>
<link href="/assets/plugin/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/assets/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="/assets/plugin/jquery.min.js" type="text/javascript"></script>
<script src="/assets/plugin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>
<div class="modal-header" style="font-size:18px;"><strong>카페등록하기</strong></div>
<div class="modal-body">
	<div class="form-group text-center" style="margin:70px 0px 70px 0px">
		<span style="font-size:18px;"><?php echo $message?></span>
	</div>
</div>
<div class="modal-footer"></div>
<div style="text-align:center">
	<?php if($cafe_url){?>
	<button type="button" onclick="go_cafe('<?php echo $cafe_url?>');" class="btn btn-primary btn-lg">글보러가기</button>
	<?php }?>
	<button type="button" onclick="self.close();" class="btn btn-default btn-lg">닫기</button>
</div>
<script>
function go_cafe(url){
	window.open(url,'','');
	self.opener = self;
	self.close(); 
}
</script>
</body>
</html>