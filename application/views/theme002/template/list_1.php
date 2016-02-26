<?php 
foreach($product as $val){
	?>
	<div class='search-item'>
		<?php
			if($val["thumb_name"]!="")	{
				$temp = explode(".",$val["thumb_name"]);
				echo "		<div class='photo' style='background-image:url(/photo/gallery_thumb/".$val['gallery_id'].");background-size: cover;background-position: center center;'>";
			} else {
				if($config->no!=""){
					echo "		<div class='photo' style='background-image:url(/uploads/logo/thumb/". $config->no .");'>";
				} else {
					echo "		<div class='photo' style='background-image:url(/assets/common/img/no_thumb.png);'>";
				}
			}
		?>
		<a href='/product/view/<?php echo $val["id"];?>' target="_blank"><img src='/assets/common/img/bg/0.png' class='holder'></a>
		<div class="tags">
				<?php if($val["is_finished"]=="1")	echo "<span class='tag'>거래완료</span><div style='clear:both;height:3px;'></div>"; ?>
				<?php if($val["is_speed"]=="1")	echo "<span class='tag'>급매</span><div style='clear:both;height:3px;'></div>"; ?>
				<?php if($val["recommand"]=="1")	echo "<span class='tag'>추천</span><div style='clear:both;height:3px;'></div>"; ?>
				<?php
					$tag = @explode(",",$val["tag"]);
					
					for($i=0; $i<3 && $i <count($tag) ; $i++){
						if($tag[$i]!="") echo "<span class='tag1'>" . $tag[$i] . "</span><div style='clear:both;height:3px;'></div>";
					}
				?>
				</div><div class='figcaption'><?php echo lang($val["type"]);?> <?php if($val["part"]=="N") {echo "전체";}?></div>
		</div> <!-- tags -->
		<div class='item'>
			<a href='/product/view/<?php echo $val["id"];?>' target="_blank"><h3><?php echo cut($val["title"],80);?> <small><?php echo $val["address_name"];?></small></h3></a>
				<div class='price_info'>
					<?php echo price($val);?>
				</div>
				<div class='subway_info'>
					<?php foreach($val["subway"] as $val_subway){?>
							<div class='subway sub_<?php echo $val_subway->hosun_id;?>' title='<?echo $val_subway->hosun;?> 호선'><?php echo $val_subway->name;?></div>
							<?php echo round($val_subway->distance,2); ?> km 
							(<?php echo round($val_subway->distance*12.5,1);?> 분)
					<?php 	} ?>
				</div>
				<div class='meta'>
					<?php if($val["part"]=="Y"){
							if($val["real_area"]!="0"){
								echo "<i class='fa fa fa-arrows-alt'></i> " . area_list($val["real_area"],"실");
							}
							//부분일 경우에는 전체층(total_floor)을 필수로 보여준다.
							echo "				<i class='fa fa-building-o'></i> ";
							if($val["current_floor"]!="0") echo $val["current_floor"] . "층 /  ";
							echo "총" . $val["total_floor"] . "층";
							if($val["room_cnt"]!="0") echo "			<i class='fa fa-inbox'></i> 침실 " . $val["room_cnt"];
							if($val["rest_cnt"]!="0") echo "				<i class='fa fa-tint'></i> 욕실 " . $val["rest_cnt"];
					 } else {
							if($val["real_area"]!="0"){
								echo "<i class='fa fa-arrows-alt'></i>" . area_list($val["real_area"],"건평") . "/" . area_list($val["law_area"],"대지");
							}
							//전체일 경우에는 지상층(current_floor)을 필수로 보여준다.
							echo "				<i class='fa fa-building-o'></i> 지상 " . $val["current_floor"] . "층";
							if($val["total_floor"]!="0")  echo " /  지하 " . $val["total_floor"] . "층";
					 } ?>
				</div>
			</div><div style='clear:both;'></div>
	</div>
	<?php 
}
?>
