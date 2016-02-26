<div class="<?php echo $background?>">

	<div class="_container">
		<!-- <div class="text-center">
			<h1><?php echo $title?></h1>
			<hr class="hr_narrow  hr_color">
		</div> -->
		<div class="left-green-title">
			<h1><?php echo $title;?></h1>
		</div>
		<div id="megafolio-container" class="megafolio-container">
				<?php foreach(array_reverse($portfolio) as $val){ ?>
					<div class="mega-entry cat-all cat-one" id="mega-entry-5" data-src="/uploads/portfolios/thumb/<?php echo $val->thumb_name?>" data-width="50" data-height="50">
						<div class="mega-hover">
							<div class="mega-hovertitle"><?php echo $val->title?></div>
							<a href="/portfolio/view/<?php echo $val->id?>"><div class="mega-hoverlink"></div></a>
					 		<a class="fancybox" rel="group" href="/uploads/portfolios/<?php echo $val->thumb_name?>"><div class="mega-hoverview"></div></a>
						</div>
					</div>
				<?php } ?>

		</div>
	</div> <!-- container -->
</div>