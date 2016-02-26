<?php

/*** 홈에서는 결과가 없어도 없다는 멘트를 보여주지 않는다. 그렇기 때문에 main에서만 보여주는 것으로 했다. ***/
/*** 홈에서는 Lazy Loading이 동작해야 한다. ***/

$home_flag = false;
if( $this->uri->segment(1) == "" || $this->uri->segment(1) == "home" ) {
	$home_flag = true;
}

if(!$home_flag){
	if(count($product)<1){
		?>
		<div style='margin-top:20px;'><h4><?php echo lang("msg.nodata");?></h4>죄송합니다. 검색 조건에 맞는 검색 결과가 없습니다. <br/>그러나, 아직 등록되지 않은 <?php echo lang("product");?>(가)이 있으며 원하시는 조건의 <?php echo lang("product");?>을 찾아드릴 수 있으니 전화연락이나 매도/매수 문의를 이용하여 문의를 해주세요.</div>
		<?php
	}
}

$THE_REFER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/";

foreach($product as $val){
	?>
	<div class='search-item-wide'>
		<?php if($val["category_opened"]=="N" && !$this->session->userdata("id")){?>
			<div style="position:relative;overflow:hidden;height:200px;" class="leanModal" lean-id="#signup" title="<?php echo $val["title"];?>">
		<?php } else {?>
			<?php if($this->session->userdata("permit_area")){
					$permit_area = @explode(",",$this->session->userdata("permit_area"));
					if(in_array($val["address_id"],$permit_area)){?>
						<div style="position:relative;overflow:hidden;height:200px;" class="view_product" data-id="<?php echo $val["id"];?>" data-toggle="modal" data-target="#view_dialog" title="<?php echo $val["title"];?>">	
					<?php } else {?>
						<div style="position:relative;overflow:hidden;height:200px;" class="leanModal" lean-id="#permit-area" title="<?php echo $val["title"];?>">			
					<?php }?>
			<?php } else {?>
				<div style="position:relative;overflow:hidden;height:200px;" class="view_product" data-id="<?php echo $val["id"];?>" data-toggle="modal" data-target="#view_dialog" title="<?php echo $val["title"];?>">	
			<?php }?>
		<?php }?>

		<?php 
			$lazy_not = (strpos($THE_REFER, "grid") !== false) ? true : false;
			if($lazy_not){
		?>
			<div class='photo' style='background-position: center center;background-image:url(/photo/gallery_thumb/<?php echo $val['gallery_id']?>);background-size: cover;'>		
		<?php }else{ ?>
			<div class='photo lazy' data-original='/photo/gallery_thumb/<?php echo $val['gallery_id']?>' style='background-position: center center;background-image:url(/assets/common/img/no_thumb.png);background-size: cover;'>		
		<?php }?>

			<?php if(element("is_finished",$val)=="0")	{?>
				<?php if(element("is_defer",$val)=="1"){?>
					<img src='/assets/basic/img/<?php echo ($val["type"]=="installation") ? "finished_hold_installation" : "finished_hold"?>.png' class='holder'>
				<?php } else {?>
					<img src='/assets/common/img/bg/0.png' class='holder'>
				<?php }?>
			<?php } else {?>
				<img src='/assets/basic/img/finished.png' class='holder'>
			<?php } ?>
			<div class="item_title">
				<h3><?php echo cut($val["title"],44);?></h3>
				<div class='address'>
					<i class="fa fa-map-marker"></i> 
						<?php echo toeng(element("address_name",$val))?> 
						<?php 
							if($config->SHOW_ADDRESS) {
								echo element("address",$val);
							}
						?>
					<?php foreach($val["subway"] as $key=>$val_subway){
						if($key<1){
					?>
						<div class='subway sub_<?php echo $val_subway->hosun_id;?>' title='<?php echo $val_subway->hosun;?>'><?php echo $val_subway->name;?></div>
						<span class="help" data-toggle="tooltip" title="(직선거리기준) 도보 <?php echo round($val_subway->distance*12.5,1);?> 분"><?php echo round($val_subway->distance,1); ?> ㎞ </span>
					<?php 	} 
						}
					?>

				</div>
			</div>
	
				<div class="tags">
					<?php if(element("is_speed",$val)=="1")		echo "<div class='tag'>급매</div>"; ?>
					<?php if(element("recommand",$val)=="1")	echo "<div class='tag'>".lang("site.recommand")."</div>"; ?>
				</div>
				<?php if(element("is_finished",$val)=="0" && element("is_defer",$val)=="0")	{?>
					<!--<div class='figcaption'><?php echo lang($val["type"]);?></div>-->
				<?php }?>

				<?php if(element("video_url",$val)!="") {?>
				<div class='videourl'><img src="/assets/common/img/youtube.png"></div>
				<?php }?>
			</div>

		</div> <!-- photo -->
		<div class='item'>
			<div class='price_info'>
				<div>
					<?php
						echo price($val,$config);
					?>
				</div>
			</div>
			<div class='meta'>
			<?php if($config->GONGSIL_FLAG && element("gongsil_contact",$val)!="" ){?>
				<p style="font-size:11px;border-top:1px dashed #cacaca;padding-top:5px;margin-top:5px;">
					<?php echo str_replace("<br/>","&nbsp;",multi_view(element("gongsil_contact",$val),"gongsil",2));?>
				</p>
			<?php }?>
			<?php 
				if(element("part",$val) && $val["part"]=="Y"){
					if($config->AREA_SORTING){ //실면적 우선
						if(element("real_area",$val)!="0"){
							if($config->PRODUCT_REALAREA) {
								echo "<div class='meta_cell'>";
								if($val["part"]=="Y") {echo cut_one(lang("product.realarea"));} else {echo "건축면적";}
								echo " ". area_list(element("real_area",$val),"") . "</div>";
							}
						} else if(element("law_area",$val)!="0"){
							if($config->PRODUCT_LAWAREA) {
								echo "<div class='meta_cell'><img src='/assets/common/img/surface.png'> ";
								if($val["part"]=="Y") {echo cut_one(lang("product.lawarea"));} else {echo "연면적";}
								echo " ". area_list(element("law_area",$val),"") . "</div>";
							}
						}
					}
					else{ //계약면적면적 우선
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
					}
					//부분일 경우에는 전체층(total_floor)을 필수로 보여준다.
					if($val["current_floor"]!="0"){
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
		<div style='clear:both;'></div>
	</div>
	<?php 
}
?>
