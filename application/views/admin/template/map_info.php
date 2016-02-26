<?php 
	foreach($product as $val){
		$title = "";
		if( $config->MAP_STYLE =="4" ) $title = cut($val["title"],70);
		else $title = cut($val["title"],35);
	?>
	<div class="relists relist list_<?php echo $val["id"]?>" data-lat="<?php echo $val["lat"]?>" data-lng="<?php echo $val["lng"]?>">
		<div class='info_image'>
			<?php if($val["category_opened"]=="N" && !$this->session->userdata("id")){?>
				<a href="#" class="leanModal" lean-id="#signup" title="<?php echo $val["title"];?>">
			<?php } else {?>
				<?php if($this->session->userdata("permit_area")){
						$permit_area = @explode(",",$this->session->userdata("permit_area"));
						if(in_array($val["address_id"],$permit_area)){?>
							<a href="#" class="view_product" data-id="<?php echo $val["id"];?>" data-toggle="modal" data-target="#view_dialog" title="<?php echo $val["title"];?>">	
						<?php } else {?>
							<a href="#" class="leanModal" lean-id="#permit-area" title="<?php echo $val["title"];?>">			
						<?php }?>
				<?php } else {?>
					<a href="#" class="view_product" data-id="<?php echo $val["id"];?>" data-toggle="modal" data-target="#view_dialog" title="<?php echo $val["title"];?>">
				<?php }?>
			<?php }?>
			<?php

				if($val["thumb_name"]!="")	{
					$temp = explode(".",$val["thumb_name"]);
					echo "<img src=\"/photo/gallery_thumb/".$val['gallery_id']."\" class='img-responsive'>";
				} else {
					if($config->no!=""){
						echo "<img src=\"/uploads/logo/thumb/". $config->no ."\" class='img-responsive'>";
					} else {
						echo "<img src=\"/assets/common/img/no_thumb.png\" class='img-responsive'>";
					}
				}
			?></a>
		</div>
		<div class='info_desc'>
			<div>
				<div style='height:28px;overflow:hidden;'>
				<?php if($val["category_opened"]=="N" && !$this->session->userdata("id")){?>
					<a href="#" class="leanModal" lean-id="#signup" title="<?php echo $val["title"];?>"><?php echo $title;?></a>
				<?php } else {?>
					<?php if($this->session->userdata("permit_area")){
							$permit_area = @explode(",",$this->session->userdata("permit_area"));
							if(in_array($val["address_id"],$permit_area)){?>
								<a href="#" class="view_product" data-id="<?php echo $val["id"];?>" data-toggle="modal" data-target="#view_dialog" title="<?php echo $val["title"];?>"><?php echo $title;?></a>
							<?php } else {?>
								<a href="#" class="leanModal" lean-id="#permit-area" title="<?php echo $val["title"];?>"><?php echo $title;?></a>			
							<?php }?>
					<?php } else {?>
						<a href="#" class="view_product" data-id="<?php echo $val["id"];?>" data-toggle="modal" data-target="#view_dialog" title="<?php echo $val["title"];?>"><?php echo $title;?></a>
					<?php }?>
				<?php }?>
					<?php if( element("is_finished",$val)=="1")  { ?><span class="label label-sm label-default">완료</span><?php }?>
					<?php if( element("is_speed",$val)=="1")  { ?><span class="label label-sm label-warning">급매</span><?php }?>
					<?php if( element("is_defer",$val)=="1")  { ?><span class="label label-sm label-info">보류</span><?php }?>				
				</div>
				<div style="letter-spacing:-2px;">
					<?php echo price($val,$config);?>
				</div>
				<div class='address'>
					<?php if($val["current_floor"]!=0) {?>
						<i class='fa fa-building-o'></i> <?php echo $val["current_floor"];?><?php echo lang("product.f");?>
					<?php } ?>
					<?php if($config->GONGSIL_FLAG) {?>
						<?php if(element("gongsil_contact",$val)!="") {?>
							<?php echo str_replace("<br/>","&nbsp;",multi_view(element("gongsil_contact",$val),"gongsil",1));?><br/>
						<?php }?>
					<?php } ?>
					<i class='fa fa-map-marker'></i> 
					<?php echo element("address_name",$val)?>
					<?php 
						if($config->SHOW_ADDRESS) {
							echo element("address",$val);
						}
					?>
				</div>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?php 
}
?>