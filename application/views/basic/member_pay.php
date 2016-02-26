<div class="main">
	<div class="_container">
		<ul class="breadcrumb">
            <li><a href="/"><?php echo lang("menu.home");?></a></li>
            <li class="active">결제내역</li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-3">
            <ul class="list-group margin-bottom-25 sidebar-menu">
              <li class="list-group-item clearfix"><a href="/member/product"><i class="fa fa-angle-right"></i> <?php echo lang("product");?>관리</a></li>
              <li class="list-group-item clearfix"><a href="/member/product_add"><i class="fa fa-angle-right"></i> <?php echo lang("product");?>등록</a></li>
              <li class="list-group-item clearfix"><a href="/member/product_pay"><i class="fa fa-angle-right"></i> <?php echo lang("pay");?></a></li>
              <li class="list-group-item clearfix active"><a href="/member/pay"><i class="fa fa-angle-right"></i> 결제내역</a></li>
			  <?php if($this->session->userdata("type")=="admin" || $this->session->userdata("type")=="biz"){?>
			  <li class="list-group-item clearfix"><a href="/member/blog"><i class="fa fa-angle-right"></i> 나의블로그</a></li>
			  <?php }?>
			</ul>
          </div>
          <!-- END SIDEBAR -->
          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-9">
            <h1>결제내역</h1>
			<span class="help-inline">* 유료광고 중복사용은 되지 않으며 하나가 종료되면 이어서 사용이 됩니다.</span>
            <div class="content-form-page" style="padding:0">
			  <!-- BEGIN ROW -->
			  <div class="row">
                <div class="col-md-12 col-sm-12">
					<table class="table">
						<tr>
							<th class="text-center">주문번호</th>
							<th class="text-center">상품명</th>
							<th class="text-center">이용가능일</th>
							<th class="text-center">공개가능횟수</th>
							<th class="text-center">결제금액</th>
							<th class="text-center">시작일</th>
							<th class="text-center">종료일</th>
							<th class="text-center"><?php echo lang("site.status");?></th>
						</tr>
						<?php
						foreach($query as $val){
							$today = date("YmdHi");
							$end_date = date("YmdHi",strtotime($val->end_date));
							if($now_order==$val->id){
								$state_text = "<span class='use_on'>이용중</span>";
							}
							else{
								$state_text = "<span class='use_ready'>이용대기</span>";
							}
							if($end_date < $today){
								$state_text = "<span class='use_end'>이용마감</span>";
							}

						?>
						<tr>
							<td class="text-center"><?php echo $val->id;?></td>
							<td class="text-center"><?php echo $val->order_name;?></td>
							<td class="text-center"><?php echo $val->use_day;?>일</td>
							<td class="text-center"><?php echo $val->use_count;?>회</td>
							<td class="text-center"><?php echo (!$val->price)?"무료":number_format($val->price)."원";?></td>
							<td class="text-center"><?php echo date("Y-m-d H:i",strtotime($val->start_date));?></td>
							<td class="text-center"><?php echo date("Y-m-d H:i",strtotime($val->end_date));?></td>
							<td class="text-center"><?php echo $state_text;?></td>
						</tr>
						<?php }
						
						if(count($query)<1){
							echo "<tr><td colspan='8' class='text-center'>".lang("msg.nodata")."</td></tr>";
						}
						?>
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
			  <!-- END ROW -->
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
	</div>
</div>