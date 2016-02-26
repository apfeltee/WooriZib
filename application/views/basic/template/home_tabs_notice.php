<div class="boxes box inside">
	<ul class="list">

		<?php foreach($news as $val){?>
		<li>
			<a href="javascript:notice_show('<?php echo $val->id?>');" title="<?php echo $val->title?>">
				<?php echo cut($val->title,40)?>
			</a>
			<span class="meta"><?php echo cut($val->content,80)?></span>
            <div class="fix clearfix"></div>		
		</li>	
		<?php }?>
	</ul>
</div>
