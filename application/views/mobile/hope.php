<script src="/assets/plugin/jquery.lazyload.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
	lazy();	
});

function lazy(){
	$("div.lazy").lazyload({
	  failure_limit : 10,
      effect : "fadeIn",
	  /*effectspeed : 100,*/
	  skip_invisible : false
	});
}
</script>

<div class="page-content" id="main-stack">
  <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
    <div class="w-container">
      <!-- 상단 시작 -->
      <?php echo $menu;?>
      <div class="wrapper-mask" data-ix="menu-mask"></div>
      <div class="navbar-title"><?php echo lang("site.saved");?></div>
      <div class="w-nav-button navbar-button left" id="menu-button" data-ix="hide-navbar-icons">
        <div class="navbar-button-icon home-icon">
          <div class="bar-home-icon"></div>
          <div class="bar-home-icon"></div>
          <div class="bar-home-icon"></div>
        </div>
      </div>
	  <a href="#" class="w-inline-block navbar-button right" onclick="onBackKeyDown();">
		<div class="navbar-button-icon icon ion-ios-close-empty"></div>
	  </a>
      <!-- 상단 종료 -->
    </div>
  </div>
  <div class="body">
    <ul id="list" class="list list-messages">
    	<?php 
		if(count($product)<1){
			echo "<div class='padding' style='margin-top:30px;'><i class='glyphicon glyphicon-ban-circle' style='color:red'></i> ".lang("msg.nodata")."</div>";
		}
		foreach($product as $val){
			$val = get_object_vars($val);
			?>
		    <li class="list-message" data-ix="list-item">
		      <a href="/mobile/view/<?php echo $val["id"];?>">
		        <div class="w-clearfix column-left">
				  <div class="image-message" style='background-position: center center;background-image:url(/assets/common/img/no_thumb.png);background-size: cover;'>
					<div class="image-message lazy" data-original='/photo/gallery_thumb/<?php echo $val['gallery_id']?>' style='background:url(/photo/gallery_thumb/<?php echo $val["gallery_id"];?>);'></div>
				  </div>
		        </div>
		        <div class="column-right">
		          <div class="message-text">
		          	<?php if(element("is_speed",$val)=="1")		echo "<i class=\"label label-warning\">급매</i>"; ?>
					<?php if(element("recommand",$val)=="1")	echo "<i class=\"label label-info\">".lang("site.recommand")."</i>"; ?>
					<?php if(element("is_finished",$val)=="1")	echo "<i class=\"label label-danger\">판매완료</i>"; ?>
		       		<?php echo toeng(element("address_name",$val))?>
					<?php 
						if($config->SHOW_ADDRESS) {
							echo element("address",$val);
						}
					?>
		          </div>
		          <div class="message-title"><?php echo cut($val["title"],45);?></div>
		          <div class="message-text">
		          	<?php echo price($val,$config);?>
		            <!--i class="icon ion-arrow-expand"></i-->
		          </div>
		          <div class="message-text">
					<?php 
						if(element("part",$val) && $val["part"]=="Y"){
							
							if(element("real_area",$val)!="0"){
								echo "<i class='icon ion-arrow-expand'></i> " . area_list(element("real_area",$val),"실");
							}
							//부분일 경우에는 전체층(total_floor)을 필수로 보여준다.
							echo "<i class='icon ion-information-circled'></i> ";					
							if($val["current_floor"]!="0") echo $val["current_floor"] . lang("product.f")."/";
							echo $val["total_floor"] . lang("product.f");					
							if(element("bedcnt",$val)!="0") echo "			<i class='icon ion-cube'></i> " . lang("product.bedcnt") . element("bedcnt",$val)." ";					
							if(element("bathcnt",$val)!="0") echo "			<i class='icon ion-cube'></i> " . lang("product.bathcnt") . element("bathcnt",$val)." ";					
						} else {
							if($config->USE_FACTORY){
								?>
								<table class="borderless" style="width:100%;">
									<tr>
										<th>대지</th><td><?php echo area_list($val["land_area"]+$val["road_area"],"");?></td>
										<th>연면적</th><td><?php echo area_list(element("law_area",$val),"");?></td>
									</tr>
									<tr>
										<th>전기</th><td><?php echo element("factory_power",$val);?></td>
										<th>호이스트</th><td><?php echo element("factory_hoist",$val);?></td>
									</tr>
								</table>
								<?php			
							} else {
								if(element("real_area",$val)!="0"){
									echo "<i class='icon ion-arrow-expand'></i>" . area_list(element("real_area",$val),"건평") . "/" . area_list($val["land_area"]+$val["road_area"],"대지");
								}
								//전체일 경우에는 지상층(current_floor)을 필수로 보여준다.
								echo "				<i class='icon ion-information-circled'></i> 지상" . $val["current_floor"] . lang("product.f");
								if($val["total_floor"]!="0")  echo " /  지하" . $val["total_floor"] . lang("product.f");
							}
							
						}
						
					?>     
		          </div>
		        </div>
		      </a>
		    </li>
		 <?php }?>
    </ul>
  </div>
</div>