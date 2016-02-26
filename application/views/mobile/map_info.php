<?php 
	foreach($product as $val){
	?>
	<div>
		<div class='info_image'>
			<a href="/mobile/view/<?php echo $val["id"];?>">
			<?php
				echo "<img src=\"/photo/gallery_thumb/".$val['gallery_id']."\" class='img-responsive'>";
			?></a>
		</div>
		<div class='info_desc'>
			<div>
				<div style='height:28px;'>
					<a href="/mobile/view/<?php echo $val["id"];?>"><?php echo cut($val["title"],45)?></a>				
				</div>
			</div>
		</div>		
		<div style="clear:both;"></div>
	</div>
	<?php 
}
?>