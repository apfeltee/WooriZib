<div class="main">
	<div class="_container">
		<!-- BEGIN SIDEBAR & CONTENT -->
		<div class="row margin-bottom-40">
			<!-- BEGIN CONTENT -->
			<div class="col-md-12 col-sm-12">
				<div class="content-page page-404" style="padding-bottom:20px;">
					 <div class="number" style="top:0;">
							404
					 </div>
					 <div class="details">
							<?php if($this->config->item('language')=="korean"){?>
								<p><h3>요청하신 페이지를 찾을 수 없습니다.</h3></p>
								<p>방문하시려는 페이지의 주소가 잘못 입력되었거나,</p>
								<p>페이지의 주소가 변경 혹은 삭제되어 요청하신 페이지를 찾을 수 없습니다.</p>
								<p>입력하신 주소가 정확한지 다시 한번 확인해 주시기 바랍니다.</p>
							<?php } else { ?>
								<p><h3>The page you requested could not be found . </h3> </p>
								<p>or mistyped the address of the page you would like to visit ,</p>
								<p>is the address of the page change or delete the page you requested could not be found.</p>
								<p>The address you entered is correct please check once again.</p>
							<?php } ?>
					 </div>
				</div>
	<div class="text-center padding-top-40 margin-bottom-40">
		<button type="button" class="btn btn-primary" onclick="javascript:location.href='/'"><?php echo lang("menu.home");?></button>
	</div>
			</div>
			<!-- END CONTENT -->
		</div>
		<!-- END SIDEBAR & CONTENT -->
	</div>
</div>