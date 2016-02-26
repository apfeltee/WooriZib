<!DOCTYPE html>
<html>
<head>
<title>SMS 문자충전</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="description" content="">
<meta name="keywords" content="" />
<meta name="author" content="Dungzi"/>
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
<link href="/assets/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
<link href="/assets/plugin/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>

<!-- BEGIN THEME STYLES -->
<link href="/assets/admin/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="/assets/admin/css/style.css">
<!-- END THEME STYLES -->

<script src="/assets/plugin/jquery.min.js" type="text/javascript"></script>
<script src="/assets/plugin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/common/js/init.js" type="text/javascript"></script>
<script>
function price_setting(val){
	var price = 0;
	if(val==1000) price = 22000;
	if(val==2000) price = 42000;
	if(val==3000) price = 62000;
	$("#price").html("<strong>"+number_format(price)+"원</strong>");
}
</script>
</head>
<body>
<?php echo form_open("adminpaysms/pay_action","id='sms_pay_form'");?>
<input type="hidden" name="member_id" value="<?php echo $this->session->userdata("admin_id")?>"/>
<div class="col-md-4">
	<div class="portlet-title" style="margin:30px 0px;">
		<h4><i class="icon-envelope vertical-bottom" style="font-size:24px;"></i> <strong>SMS 문자 충전하기</strong></h4>
	</div>	
	<div class="portlet-title padding-top-10">
		<table class="table table-bordered table-condensed flip-content">
			<tbody>
				<tr>
					<td class="text-center vertical-middle">결제 방식</td>
					<td class="vertical-middle text-center" height="70">
						<div data-toggle="buttons">
							<label class="btn btn-default active">
								<input type="radio" name="pgcode" value="1" checked/><strong>신용카드</strong>
							</label>
							<label class="btn btn-default">
								<input type="radio" name="pgcode" value="4"/><strong>계좌이체</strong>
							</label>
						</div>
					</td>
				</tr>
				<tr>
					<td class="text-center vertical-middle">충전건수</td>
					<td class="vertical-middle text-center" height="70">
						<select class="form-control input-small inline" name="sms_count" onchange="price_setting(this.value)">
							<option value="1000">1000건</option>
							<option value="2000">2000건</option>
							<option value="3000">3000건</option>
						</select>
						<small>(건당 20원)</small>
					</td>
				</tr>
				<tr>
					<td class="text-center vertical-middle success" style="border-right:1px solid #dff0d8;">충전금액</td>
					<td class="text-right vertical-middle success" height="90">
						<span id="price" style="font-size:20px;">
							<strong>22,000원</strong>
						</span>
						<span><br/><small>(부가세포함)</small></span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="portlet-title padding-top-10 well">
		<small>충전이 완료된 내역은 ( 통계 > SMS 충전내역 )에서 <br/><br/>확인 가능합니다.</small>
	</div>

	<div class="portlet-title text-center">
		<button class="btn btn-primary" type="submit">다음</button>
		<button class="btn btn-default" onclick="window.close()">취소</button>	
	</div>
</div>
<?php echo form_close();?>
</body>
</html>
