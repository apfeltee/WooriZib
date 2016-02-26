<?php 
if(count($product)>0){
}
foreach($product as $val){
	?>
    <li class="list-message" data-ix="list-item">
		<?php if($val["category_opened"]=="N" && !$this->session->userdata("id")){?>
			<a class="leanModal" lean-id="#signup">
		<?php } else {?>
			<?php if($this->session->userdata("permit_area")){
					$permit_area = @explode(",",$this->session->userdata("permit_area"));
					if(in_array($val["address_id"],$permit_area)){?>
						<a href="/mobile/view/<?php echo $val["id"];?>">
					<?php } else {?>
						<a class="leanModal" lean-id="#permit-area">		
					<?php }?>
			<?php } else {?>
				<a href="/mobile/view/<?php echo $val["id"];?>">
			<?php }?>
		<?php }?>
      
        <div class="w-clearfix column-left">
		  <div class="image-message" style='background-position: center center;background-image:url(/assets/common/img/no_thumb.png);background-size: cover;'>
			<div class="image-message lazy" data-original='/photo/gallery_thumb/<?php echo $val['gallery_id']?>' style='background:url(/photo/gallery_thumb/<?php echo $val["gallery_id"];?>);'></div>
		  </div>
        </div>
        <div class="column-right">
          <div class="message-text">
          	<?php if(element("is_speed",$val)=="1")		echo "<i class=\"label label-warning\">급매</i>"; ?>
			<?php if(element("recommand",$val)=="1")	echo "<i class=\"label label-info\">".lang("site.recommand")."</i>"; ?>
			<?php 
				if(element("is_finished",$val)=="1")	
				{
					if($val["type"]=="installation") {
						echo "<i class=\"label label-danger\">분양완료</i>";
					} else {
						echo "<i class=\"label label-danger\">거래완료</i>";
					}
				} 
			?>
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
				<?php if($config->GONGSIL_FLAG && element("gongsil_contact",$val)!="" ){?>
					<p style="font-size:11px;border-top:1px dashed #cacaca;padding-top:5px;margin-top:5px;">
						<?php echo str_replace("<br/>","&nbsp;",multi_view(element("gongsil_contact",$val),"gongsil",1));?>
					</p>
				<?php }?>
				<?php 
					if(element("part",$val) && $val["part"]=="Y"){
						if(element("law_area",$val)!="0"){
							if($config->PRODUCT_LAWAREA) {
								echo "<div class='meta_cell'><img src='/assets/common/img/surface.png'> ";
								if($val["part"]=="Y") {echo cut_one(lang("product.lawarea"));} else {echo "연면적";}
								echo " ". area_list(element("law_area",$val),"") . "</div>";
							}
						} else if(element("real_area",$val)!="0"){
							if($config->PRODUCT_REALAREA) {
								echo "<div class='meta_cell'>";
								if($val["part"]=="Y") {echo cut_one(lang("product.realarea"));} else {echo "건축면적";}
								echo " ". area_list(element("real_area",$val),"") . "</div>";
							}
						}
						//부분일 경우에는 전체층(total_floor)을 필수로 보여준다.
						if($val["current_floor"]!="0" && $val["current_floor"]!=""){
							echo "<div class='meta_cell'><img src='/assets/common/img/floor.png'> " . $val["current_floor"] . lang("product.f")."/" .$val["total_floor"] . lang("product.f")."</div>";
						}
						
						if(element("bedcnt",$val)!="0") {
							echo "<div class='meta_cell'><img src='/assets/common/img/bed.png'> ";
							echo lang("product.bedcnt") . element("bedcnt",$val)." ";
							if(element("bathcnt",$val)!="0") echo "/" . lang("product.bathcnt") . element("bathcnt",$val);
							echo "</div>";
						}
						
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
								if($config->PRODUCT_REALAREA) {
									echo "<div class='meta_cell'><img src='/assets/common/img/surface.png'> ";
									echo area_list(element("real_area",$val),"건") . "/" . area_list($val["land_area"]+$val["road_area"],"대");
									echo "</div>";
								}
							}
							//전체일 경우에는 지상층(current_floor)을 필수로 보여준다.
							if($val["current_floor"]!=0) {
								echo "<div class='meta_cell'><img src='/assets/common/img/floor.png'> 지상" . $val["current_floor"] . lang("product.f");
								if($val["total_floor"]!="0")  echo "/지하" . $val["total_floor"] . lang("product.f");
								echo "</div>";
							}
						}
						
					}
					
				?>       	
          </div>
        </div>
      </a>
    </li>
<?php } ?>