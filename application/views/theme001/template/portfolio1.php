<?php 
	foreach($portfolio as $val){
?>
	<div class="mega-entry cat-all cat-one" id="mega-entry-5" data-src="/uploads/portfolios/thumb/<?php echo $val->thumb_name?>" data-width="50" data-height="50">
		<div class="mega-hover">
			<div class="mega-hovertitle"><?php echo $val->title?>
				<div class="mega-hoversubtitle">
					<?php echo cut($val->content,100);?>
				</div>
			</div>
			<a href="/portfolio/view/<?php echo $val->id?>"><div class="mega-hoverlink"></div></a>
			<a class="fancybox" rel="group" href="/uploads/portfolios/<?php echo $val->thumb_name?>"><div class="mega-hoverview"></div></a>
		</div>
	</div>
<?php 
}
?>