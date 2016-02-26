<script>
$(document).ready(function(){
	login_leanModal();
});
</script>
<div class="page-content" id="main-stack">
  <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
    <div class="w-container">
      <div class="wrapper-mask" data-ix="menu-mask"></div>
      <div class="navbar-title">지도 <?php echo lang("product");?> <?php echo lang("site.list");?></div>
      <a href="#" class="w-inline-block navbar-button right" onclick="onBackKeyDown();">
        <div class="navbar-button-icon icon ion-ios-close-empty"></div>
      </a>
      <!-- 상단 종료 -->
    </div>
  </div>
  <div class="body">
    <ul id="list" class="list list-messages"><?php echo $result;?></ul>
  </div>
</div>