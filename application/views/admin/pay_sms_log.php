<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">SMS 충전내역</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="/adminhome/index"><?php echo lang("menu.home");?></a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="#">SMS 충전내역</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="help-block">* 총 <strong><?php echo $total;?></strong>건의 SMS 충전 내역이 검색되었습니다.</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center">주문번호</th>
					<th class="text-center">주문명</th>
					<th class="text-center">결제방식</th>
					<th class="text-center">결제수단</th>
					<th class="text-center">SMS구매건수</th>
					<th class="text-center">결제금액</th>
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
				<?php foreach($query as $value){?>
				<tr>
					<td class="text-center"><?php echo $value->id;?></td>
					<td class="text-center"><?php echo $value->order_name;?></td>
					<td class="text-center">
						<?php if($value->pay_type==1) echo "카드";?>
						<?php if($value->pay_type==4) echo "계좌이체";?>
					</td>
					<td class="text-center"><?php echo $value->cardname;?></td>
					<td class="text-center"><?php echo $value->sms_count;?>건</td>
					<td class="text-center"><?php echo number_format($value->price);?>원</td>
					<td class="text-center">
						<?php echo ($value->state=="Y") ? "결제완료" : "결제실패";?>
					</td>
					<td class="text-center"><?php echo $value->payed_date;?></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
</div>