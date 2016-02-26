<div class="<?php echo $background?> alternative_color_no">
	<div class="text-center">
		<h1><?php echo $title?></h1>
		<hr class="hr_narrow  hr_color">
	</div>
	<div id="fullwidth" class="util-carousel fullwidth">
		<?php foreach($mainnews_items as $val){?>
		<?php if(!$val->thumb_name) continue;?>
		<div class="item item-first">
			<div class="meida-holder">
				<img src="/uploads/news/thumb/<?php echo $val->thumb_name?>" alt="" />			
			</div>

			<div class="hover-content">
				<div class="overlay"></div>
				<div class="link-container">
					<a href="/news/view/<?php echo $val->id;?>" ><i class="icon-link" style="font-size:22px;"></i></a>
				</div>
				<div class="detail-container">
					<h4><?php echo $val->title?></h4>
					<p>
						<?php echo cut($val->content,100)?>
					</p>
				</div>
			</div>
		</div>
		<?php }?>
	</div>

</div>

<script>
$(function() {
	$('#fullwidth').utilCarousel({
		breakPoints : [[600, 1], [900, 2], [1200, 3], [1500, 4], [1800, 5]],
		mouseWheel : false,
		rewind : true,
		autoPlay : true,
		pagination : false
	});
});
</script>
