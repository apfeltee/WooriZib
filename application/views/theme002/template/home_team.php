<div class="<?php echo $background?> margin-bottom-20">
	<div class="_container">
		<div class="text-left" style="position:relative;">
			<h1><i class="fa fa-home"></i> <?php echo $title;?></h1>
			<hr class="hr_narrow  hr_color">
		</div>
		<div id="team-showcase" class="util-carousel team-showcase">
			<?php foreach($team_member as $val){?>
			<div class="item">
				<div class="media-holder">
					<?php if($val->profile){?>
					<img src="/uploads/member/<?php echo $val->profile?>" alt="" width="260" />
					<?php } else {?>
					<img src="/assets/common/img/no_human.png" alt="" width="260" />
					<?php }?>
				</div>
				<div class="detail-container">
					<div class="detail-title">
						<?php echo $val->name;?>
					</div>
					<div class="detail-subtitle">
						<?php echo $val->phone;?> | <?php echo $val->email;?>
					</div>
					<p>
						<?php echo cut($val->bio,200);?>
					</p>
				</div>
			</div>
			<?php }?>
			
		</div>
	</div>
</div>


<script>
$(function() {
	$('#team-showcase').utilCarousel({
		responsiveMode : 'itemWidthRange',
		itemWidthRange : [260, 320]
	});
});
</script>