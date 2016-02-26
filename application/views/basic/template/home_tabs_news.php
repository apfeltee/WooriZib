<div class="boxes box inside">
	<ul class="list">
		<?php foreach($news as $val){?>
		<?php if(!$val->thumb_name) continue;?>
		<li>
			<a href="/news/view/<?php echo $val->id?>" title="<?php echo $val->title?>">
				<img src="/uploads/news/thumb/<?php echo $val->thumb_name?>" alt="<?php echo $val->title?>" width="40" height="40" class="zozo-image thumbnail">
				<?php echo cut($val->title,40)?>
			</a>
			<span class="meta"><?php echo cut($val->content,80)?></span>
            <div class="fix clearfix"></div>		
		</li>	
		<?php }?>
	</ul>
</div>
