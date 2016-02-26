<div class="<?php echo $background?> margin-bottom-20">
	<div class="_container">
		<div class="text-center">
			<h1><?php echo $title;?></h1>
			<hr class="hr_narrow  hr_color">
			<p>저희는 고객에게 최고의 가치를 제공해 주기 위해서 열심히 노력하고 있습니다.</p>
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