<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/assets/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="/assets/plugin/payletter.js" type="text/javascript"></script>
<script>
	window.opener.location="/member/pay";
</script>
</head>
<body>
	<div class="row">
		<div class="col-lg-12">
			<h1 class="text-center">결제 결과</h1>
			<div class="help-block margin-bottom-30" style="padding-left:10px;">
				<?php if($result["result"]== "OK" ) {?>
				결제가 완료되었습니다.
				<?php } else { ?>
				결제가 실패하였습니다.
				<?php } ?>
			</div>
			<div>
				<table class="table">
					<tr>
						<th width="30%">주문번호</th>
						<td width="70%"><?php echo $result["ordno"]?></td>
					</tr>
					<tr>
						<th width="30%">결제상품명</th>
						<td width="70%"><?php echo $result["pname"]?></td>
					</tr>
					<?php if($result["result"]== "OK" ) {?>
					<tr>
						<th width="30%">거래번호</th>
						<td width="70%"><?php echo $result["pltid"]?></td>
					</tr>
					<tr>
						<th width="30%">결제금액</th>
						<td width="70%"><?php echo number_format($result["price"])?>원</td>
					</tr>
					<tr>
						<th width="30%">결제시간</th>
						<td width="70%"><?php echo $result["date"]?></td>
					</tr>
					<tr>
						<th width="30%">결제수단</th>
						<td width="70%"><?php echo $result["cardname"]?></td>
					</tr>
					<?php } else {?>
					<tr>
						<th width="30%">오류사유</th>
						<td width="70%"><?php echo $result["errmsg"]?></td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<div style="text-align:center;">
				<button class="btn btn-primary" onclick="OpenReceipt('<?php echo $result["pltid"];?>')">(<?php echo $result["pltid"];?>)영수증</button>
				<button class="btn btn-danger" onclick="self.close();"><?php echo lang("site.close");?></button>
			</div>
		</div>
	</div>
</body>
</html>