<style>
.pagination li a{
	float:none;
}
</style>
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

var scroll_top = 20;
function delete_product(obj,id){
	if(confirm("삭제 하시겠습니까?")){
		$.getJSON("/member/delete_product/"+id+"/"+Math.round(new Date().getTime()),function(data){});
		$(obj).parent().parent().remove();
		$('html,body').animate({
			scrollTop: scroll_top
		}, 'slow');
		scroll_top = scroll_top + 20;
	}
}

function state_change(id,state){
	if(!state){
		if(!confirm("대기중으로 변경하시겠습니까?")) return false;
	}
	$.ajax({
		url: "/member/state_change/is_activated",
		type: "POST",
		data: {
			state : state,
			id: id
		},
		success: function(data) {
			if(data=='success'){
				var enabled_count = parseInt($("#enabled_count").html());
				if(state){
					$("#enabled_count").html(enabled_count-1);				
					$("#state_button"+id).html('<button onclick="state_change('+id+',0);" type="button" class="btn btn-info" style="width:32%">게시중</button>');
				}
				else{
					$("#enabled_count").html(enabled_count+1);
					$("#state_button"+id).html('<button onclick="state_change('+id+',1);" type="button" class="btn btn-warning" style="width:32%">대기중</button>');
				}				
			}
			else if(data=='fail'){
				alert("광고 횟수를 모두 사용하였습니다.");
			}
			else if(data=='no_pay'){
				alert("PC에서 <?php echo lang("pay");?>을 구매 해주시기 바랍니다.");
			}
		}
	});	
}
</script>

<div class="page-content" id="main-stack">
  <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
    <div class="w-container">
      <!-- 상단 시작 -->
      <?php echo $menu;?>
      <div class="wrapper-mask" data-ix="menu-mask"></div>
      <div class="navbar-title"><?php echo lang("product");?>관리</div>
      <div class="w-nav-button navbar-button left" id="menu-button" data-ix="hide-navbar-icons">
        <div class="navbar-button-icon home-icon">
          <div class="bar-home-icon"></div>
          <div class="bar-home-icon"></div>
          <div class="bar-home-icon"></div>
        </div>
      </div>
	  <a class="w-inline-block navbar-button right" href="/mobile/product_add">
		<div class="navbar-button-icon smaller icon ion-ios-compose-outline"></div>
	  </a>
      <!-- 상단 종료 -->
    </div>
  </div>
  <div class="body">
    <ul id="list" class="list list-messages">
		<?php if($pay_info){?>
		<li class="list-message padding">			 
			<h5 class="text-center">광고게시 가능한 건 수 : <span id="enabled_count"><?php echo $pay_info->enabled_count;?></span>건</h5>			  
		</li>
		<?php }?>
		<?php 
		if(count($product) == 0){
			echo "<li class='padding'>".lang("msg.nodata")."</li>";
		}
		foreach($product as $val){
			?>
			<li class="list-message" data-ix="list-item" style="padding-bottom:35px;">			  
			  <div class="w-clearfix column-left">
			    <a href="/mobile/view/<?php echo $val->id;?>">
				  <div class="image-message" style='background-position: center center;background-image:url(/assets/common/img/no_thumb.png);background-size: cover;'>
					<div class="image-message lazy" data-original='/photo/gallery_thumb/<?php echo $val->gallery_id?>' style='background:url(/photo/gallery_thumb/<?php echo $val->gallery_id;?>);'></div>
				  </div>
			    </a>
			  </div>
			  <a href="/mobile/view/<?php echo $val->id;?>">
				<div class="column-right">
				  <div class="message-text">
					<?php if($val->is_speed=="1")		echo "<i class=\"label label-warning\">급매</i>"; ?>
					<?php if($val->recommand=="1")	echo "<i class=\"label label-info\">".lang("site.recommand")."</i>"; ?>
					<?php 
						if($val->is_finished=="1")	
						{
							if($val->type=="installation") {
								echo "<i class=\"label label-danger\">분양완료</i>";
							} else {
								echo "<i class=\"label label-danger\">거래완료</i>";
							}
						} 
					?>
					<?php echo toeng($val->address_name);?>
					<?php 
						if($config->SHOW_ADDRESS) {
							echo $val->address;
						}
					?>
				  </div>
				  <div class="message-title"><?php echo cut($val->title,45);?></div>
				  <div class="message-text">
					<?php echo price($val,$config);?>
					<!--i class="icon ion-arrow-expand"></i-->
				  </div>
				  <div class="message-text">
						<?php if($config->GONGSIL_FLAG && $val->gongsil_contact!="" ){?>
							<p style="font-size:11px;border-top:1px dashed #cacaca;padding-top:5px;margin-top:5px;">
								<?php echo str_replace("<br/>","&nbsp;",multi_view($val->gongsil_contact,"gongsil",1));?>
							</p>
						<?php }?>
						<?php 
							if($val->part && $val->part=="Y"){
								if($val->law_area!="0"){
									if($config->PRODUCT_LAWAREA) {
										echo "<div class='meta_cell'><img src='/assets/common/img/surface.png'> ";
										if($val->part=="Y") {echo cut_one(lang("product.lawarea"));} else {echo "연면적";}
										echo " ". area_list($val->law_area,"") . "</div>";
									}
								} else if($val->real_area!="0"){
									if($config->PRODUCT_REALAREA) {
										echo "<div class='meta_cell'>";
										if($val->part=="Y") {echo cut_one(lang("product.realarea"));} else {echo "건축면적";}
										echo " ". area_list($val->real_area,"") . "</div>";
									}
								}
								//부분일 경우에는 전체층(total_floor)을 필수로 보여준다.
								if($val->current_floor!="0" && $val->current_floor!=""){
									echo "<div class='meta_cell'><img src='/assets/common/img/floor.png'> " . $val->current_floor . lang("product.f")."/" .$val->total_floor . lang("product.f")."</div>";
								}
								
								if($val->bedcnt!="0") {
									echo "<div class='meta_cell'><img src='/assets/common/img/bed.png'> ";
									echo lang("product.bedcnt") . $val->bedcnt." ";
									if($val->bathcnt!="0") echo "/" . lang("product.bathcnt") . $val->bathcnt;
									echo "</div>";
								}
								
							} else {
								if($config->USE_FACTORY){
									?>
									<table class="borderless" style="width:100%;">
										<tr>
											<th>대지</th><td><?php echo area_list($val->land_area+$val->road_area,"");?></td>
											<th>연면적</th><td><?php echo area_list($val->law_area,"");?></td>
										</tr>
										<tr>
											<th>전기</th><td><?php echo $val->factory_power;?></td>
											<th>호이스트</th><td><?php echo $val->factory_hoist;?></td>
										</tr>
									</table>
									<?php			
								} else {
									if($val->real_area!="0"){
										if($config->PRODUCT_REALAREA) {
											echo "<div class='meta_cell'><img src='/assets/common/img/surface.png'> ";
											echo area_list($val->real_area,"건") . "/" . area_list($val->land_area+$val->road_area,"대");
											echo "</div>";
										}
									}
									//전체일 경우에는 지상층(current_floor)을 필수로 보여준다.
									if($val->current_floor!=0) {
										echo "<div class='meta_cell'><img src='/assets/common/img/floor.png'> 지상" . $val->current_floor . lang("product.f");
										if($val->total_floor!="0")  echo "/지하" . $val->total_floor . lang("product.f");
										echo "</div>";
									}
								}
								
							}
							
						?>       	
				  </div>
				</div>
			  </a>
			  <div class="padding text-right" style="margin-top:20px;">
				<span id="state_button<?php echo $val->id;?>">
					<?php if($val->is_activated){?>
					<button onclick="state_change(<?php echo $val->id;?>,0);" type="button" class="btn btn-info" style="width:32%">게시중</button>
					<?php } else { ?>
					<button onclick="state_change(<?php echo $val->id;?>,1);" type="button" class="btn btn-warning" style="width:32%">대기중</button>
					<?php } ?>
				</span>
			    <button class="btn btn-primary" onclick="javascript:location.href='/mobile/product_edit/<?php echo $val->id;?>'" style="width:33%"><?php echo lang("site.modify");?></button>
			    <button class="btn btn-danger" onclick="delete_product(this,'<?php echo $val->id;?>');" style="width:32%"><?php echo lang("site.delete");?></button>
			  </div>
			</li>
		<?php } ?>
    </ul>
  </div>
  <div class="row text-center">
	<ul class="pagination">
		<?php echo $pagination;?>
	</ul>
  </div>
</div>