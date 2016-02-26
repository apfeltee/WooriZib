<?php 
	foreach($product as $val){
?>
<div class='search-item-wide'>
<?php if($val["category_opened"]=="N" && !$this->session->userdata("id")){?>
	<div style="position:relative;overflow:hidden;height:200px;" class="leanModal" lean-id="#signup" title="<?php echo $val["title"];?>">
<?php } else {?>
	<?php if($this->session->userdata("permit_area")){
			$permit_area = @explode(",",$this->session->userdata("permit_area"));
			if(in_array($val["parent_id"],$permit_area)){?>
				<div style="position:relative;overflow:hidden;height:200px;" class="view_product" data-id="<?php echo $val["id"];?>" data-toggle="modal" data-target="#view_dialog" title="<?php echo $val["title"];?>">	
			<?php } else {?>
				<div style="position:relative;overflow:hidden;height:200px;" class="leanModal" lean-id="#permit-area" title="<?php echo $val["title"];?>">			
			<?php }?>
	<?php } else {?>
		<div style="position:relative;overflow:hidden;height:200px;" class="view_product" data-id="<?php echo $val["id"];?>" data-toggle="modal" data-target="#view_dialog" title="<?php echo $val["title"];?>">	
	<?php }?>
<?php }?>
		<div class='photo' style='background-position: center center;background-image:url(/assets/common/img/no_thumb.png);background-size: cover;'>
			<div class='photo lazy' data-original='/photo/gallery_thumb/<?php echo $val['gallery_id']?>' style='background-position: center center;background-image:url(/assets/common/img/no_thumb.png);background-size: cover;'>
			<?php if($val["is_finished"]=="0")	{?>
				<?php if($val["is_defer"]=="1"){?>
					<img src='/assets/basic/img/<?php echo ($val["type"]=="installation") ? "finished_hold_installation" : "finished_hold"?>.png' class='holder'>
				<?php } else {?>
					<img src='/assets/common/img/bg/0.png' class='holder'>
				<?php }?>
			<?php } else {?>
				<img src='/assets/basic/img/finished.png' class='holder'>
			<?php } ?>
				<div class="item_title">
					<h3><?php echo cut($val["title"],44);?></h3>
					<div class='address'><i class="fa fa-map-marker"></i> <?php echo $val["address_name"];?>
					<?php 
						foreach($val["subway"] as $key=>$val_subway){
							if($key<1){	?>
						<div class='subway sub_<?php echo $val_subway->hosun_id;?>' title='<?php echo $val_subway->hosun;?> 호선'><?php echo $val_subway->name;?></div>
						<span class="help" data-toggle="tooltip" title="(직선거리기준) 도보 <?php echo round($val_subway->distance*12.5,1);?> 분"><?php echo round($val_subway->distance,1); ?> ㎞ </span>
						<?php 	
							} 
						}
						?>
					</div>
				</div>
				<div class="tags">
					<?php if($val["is_speed"]=="1")		echo "<div class='tag'>급매</div>"; ?>
					<?php if($val["recommand"]=="1")	echo "<div class='tag'>추천</div>"; ?>
					<?php
						/**
						$tag = explode(",",$val["tag"]);

						for($i=0; $i<3 && $i <count($tag) ; $i++){
							if($tag[$i]!="") echo "<div class='tag1'>" . $tag[$i] . "</div>";
						}
						**/
					?>
				</div>

				<?php if($val["is_finished"]=="0" && $val["is_defer"]=="0")	{?>
				<div class='figcaption'><?php echo lang($val["type"]);?></div>
				<?php }?>
				<?php if($val["video_url"]!="") {?>
				<div class='videourl'><img src="/assets/common/img/youtube.png"></div>
				<?php }?>
			</div><!-- photo 2-->
		</div><!-- photo 1-->
	</div><!-- view_product -->
	<div class='item'>
		<div class='price_info'>
			<div><?php echo price($val);?></div>
		</div>
		<div class='meta'>
			<?php 
				if(element("part",$val) && $val["part"]=="Y"){
					if($val["law_area"]!="0"){
						echo "<div class='meta_cell'><img src='/assets/common/img/surface.png'> ";
						if($val["part"]=="Y") {echo cut_one(lang("site_part_law_area"));} else {echo "연면적";}
						echo " ". area_list($val["law_area"],"") . "</div>";
					} else if($val["real_area"]!="0"){
						echo "<div class='meta_cell'>";
						if($val["part"]=="Y") {echo cut_one(lang("site_part_real_area"));} else {echo "건축면적";}
						echo " ". area_list($val["real_area"],"") . "</div>";
					}
					//부분일 경우에는 전체층(total_floor)을 필수로 보여준다.
					if($val["current_floor"]!="0"){
						echo "<div class='meta_cell'><img src='/assets/common/img/floor.png'> " . $val["current_floor"] . "층/ 총 " .$val["total_floor"] . "층</div>";
					}
					
					if($val["room_cnt"]!="0") {
						echo "<div class='meta_cell'><img src='/assets/common/img/bed.png'> ";
						echo "침실" . $val["room_cnt"]." ";
						if($val["rest_cnt"]!="0") echo "/ 욕실" . $val["rest_cnt"];
						echo "</div>";
					}
					
				} else {
					if($config->USE_FACTORY){
						?>
						<table class="borderless" style="width:100%;">
							<tr>
								<th>대지</th><td><?php echo area_list($val["land_area"]+$val["road_area"],"");?></td>
								<th>연면적</th><td><?php echo area_list($val["law_area"],"");?></td>
							</tr>
							<tr>
								<th>전기</th><td><?php echo element("factory_power",$val);?></td>
								<th>호이스트</th><td><?php echo element("factory_hoist",$val);?></td>
							</tr>
						</table>
						<?php			
					} else {
						if($val["real_area"]!="0"){
							echo "<div class='meta_cell'><img src='/assets/common/img/surface.png'> ";
							echo area_list($val["real_area"],"건") . "/" . area_list($val["land_area"]+$val["road_area"],"대");
							echo "</div>";
						}
						//전체일 경우에는 지상층(current_floor)을 필수로 보여준다.
						echo "<div class='meta_cell'><img src='/assets/common/img/floor.png'> 지상" . $val["current_floor"] . "층";					
						if($val["total_floor"]!="0")  echo " /  지하" . $val["total_floor"] . "층";
						echo "</div>";
					}
					
				}
				
			?>
		</div>
	</div>
	<div style='clear:both;'></div>
</div>
	<?php 
}
?>
