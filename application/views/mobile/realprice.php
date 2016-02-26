<script src="/assets/basic/js/view.js"></script>
<div class="page-content" id="main-stack">
  <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
    <div class="w-container">
      <div class="navbar-title">6개월간 실거래건</div>
      <a class="w-inline-block navbar-button right" onclick="history.back();">
        <div class="navbar-button-icon icon ion-ios-close-empty"></div>
      </a>
      <!-- 상단 종료 -->
    </div>
  </div>
  <div class="body">
	<div>
		<?php $bunzi_address = urlencode($query->address_name." ".$query->address);?>
		<iframe id="realprice_frame" name="realprice_frame" src="http://hub.dungzi.com/realprice/chart/?address=<?php echo $bunzi_address;?>&uri_segment=<?php echo $this->uri->segment(1);?>" frameborder="0" border="0" scrolling="no" style="display:block;width:100%;height:1000px;"></iframe>
	</div>
  </div>
</div>