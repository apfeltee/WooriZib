<script>
function pay(id,type){
	if(confirm("구매 하시겠습니까?")){
		$("#pay_setting_id").val(id);
		$("#pgcode").val($(":input:radio[name=select_pgcode]:checked").val());
		if(type!='free'){
			window.open("about:blank","pay_window","width=370, height=360, resizable=no, scrollbars=no, status=no;");
			$("#pay_form").attr("target","pay_window");		
		}
		$("#pay_form").submit();
	}
}
</script>
<div class="main">
	<div class="_container">
		<ul class="breadcrumb">
            <li><a href="/"><?php echo lang("menu.home");?></a></li>
            <li class="active"><?php echo lang("pay");?></a></li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-3">
            <ul class="list-group margin-bottom-25 sidebar-menu">
              <li class="list-group-item clearfix"><a href="/member/product"><i class="fa fa-angle-right"></i> <?php echo lang("product");?>관리</a></li>
              <li class="list-group-item clearfix"><a href="/member/product_add"><i class="fa fa-angle-right"></i> <?php echo lang("product");?>등록</a></li>
              <li class="list-group-item clearfix active"><a href="/member/product_pay"><i class="fa fa-angle-right"></i> <?php echo lang("pay");?></a></li>
			  <li class="list-group-item clearfix"><a href="/member/pay"><i class="fa fa-angle-right"></i> 결제내역</a></li>
			  <?php if($this->session->userdata("type")=="admin" || $this->session->userdata("type")=="biz"){?>
			  <li class="list-group-item clearfix"><a href="/member/blog"><i class="fa fa-angle-right"></i> 나의블로그</a></li>
			  <?php }?>
			</ul>
          </div>
          <!-- END SIDEBAR -->
          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-9">
            <h1><?php echo lang("pay");?> 신청 안내</h1>
			<span class="help-inline">* <?php echo lang("pay.info");?></span>
            <div class="content-form-page" style="padding:0">
			  <!-- BEGIN ROW -->
			  <div class="row">
				<div class="pg-select margin-bottom-10 margin-top-10" data-toggle="buttons" style="padding-left:18px;">
				  <label class="btn btn-default active">
					<input type="radio" id="pgcode1" name="select_pgcode" value="1" checked/><strong>신용카드</strong>
				  </label>
				  <label class="btn btn-default">
					<input type="radio" id="pgcode4" name="select_pgcode" value="4"/><strong>계좌이체</strong>
				  </label>
				  <!--<label class="btn btn-default">
					<input type="radio" id="pgcode18" name="select_pgcode" value="18"/><strong>가상계좌(무통장입금)</strong>
				  </label>-->
				</div>
			  <?php foreach($query as $key=>$val){?>
                <div class="col-md-4 pay-row">
                  <div class="pricing hover-effect">
                    <div class="pricing-head">
                      <h3><?php echo $val->name;?></h3>
                      <h4><i>&#8361;<?php echo ($val->price==0) ? "무료" : number_format($val->price)."원";?></i>
					  <?php if($val->price==0){?>
						<span>무료 1회 구매 가능</span>
					  <?php } else { ?>
						<span>&nbsp;</span>
					  <?php } ?>
                      </h4>
                    </div>
                    <ul class="pricing-content list-unstyled">
                      <li>
                        <i class="fa fa-bullhorn"></i> <?php echo lang('product')."게시 ".$val->count;?>건 가능
                      </li>
                      <li>
                        <i class="fa fa-calendar"></i> <?php echo $val->day;?>일동안 이용 가능
                      </li>
                    </ul>
                    <div class="pricing-footer">
					  <?php if($val->price==0){?>
						  <?php if($val->in_use){?>
								<a onClick="pay('<?php echo $val->id;?>','free');" class="btn btn-success" disabled>신청완료</a>
						  <?php } else {?>
								<a onClick="pay('<?php echo $val->id;?>','free');" class="btn btn-primary"><?php echo lang("site.submit");?></a>
						  <?php }?>
					  <?php } else {?>
						  <a onClick="pay('<?php echo $val->id;?>','');" class="btn btn-primary"><?php echo lang("site.submit");?></a>
					  <?php }?>
					</div>
                  </div>
                </div>			  
			  <?php }?>
			  </div>
			  <!-- END ROW -->
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
	</div>
</div>

<?php echo form_open("pay/pay_action","id='pay_form'");?>
<input type="hidden" id="pay_setting_id" name="pay_setting_id">
<input type="hidden" id="member_id" name="member_id" value="<?php echo $this->session->userdata("id")?>">
<input type="hidden" id="name" name="name" value="<?php echo $this->session->userdata("name")?>">
<input type="hidden" id="email" name="email" value="<?php echo $this->session->userdata("email")?>">
<input type="hidden" id="pgcode" name="pgcode">
<?php echo form_close();?>