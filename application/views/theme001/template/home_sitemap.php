<div class="footer-sitemap">
	<div class="_container">
		<div class="row">
			<?php 
				$percent = intval(100 / count($mainmenu));
				foreach($mainmenu as $val){?>
				<div style="float:left;width:<?php echo $percent;?>%;padding:20px;">
					<?php if($val->type=="main") {?>
						<h4><?php echo $val->title;?></h4>
						<ul>
							<li><a href="/main/map"><?php echo lang("site.map");?> <?php echo lang("site.search");?></a></li>
							<li><a href="/main/grid"><?php echo lang("site.list");?> <?php echo lang("site.search");?></a></li>
						</ul>
					<?php } ?>
					<?php if($val->type=="enquire") {?>
						<h4><?php echo $val->title;?></h4>
						<ul>
							<li><a href="/member/enquire"><?php echo lang("enquire.title");?></a></li>
							<li><a href="/ask/index"><?php echo lang("qna_title");?></a></li>
						</ul>
					<?php } ?>
					<?php if($val->type=="news") {?>
						<h4><?php echo $val->title;?></h4>
						<ul>
							<?php foreach($menu_news as $v){?>
							<li><a href="/news/index/<?php echo $val->id?>"><?php echo $v->name?></a></li>
							<?php }?>
						</ul>
					<?php } ?>
					<?php if($val->type=="gallery") {?>
						<h4><?php echo $val->title;?></h4>
						<ul>
							<?php foreach($menu_gallery as $v){?>
							<li><a href="/portfolio/index/<?php echo $val->id?>"><?php echo $v->name?></a></li>
							<?php }?>
						</ul>
					<?php } ?>
					<?php if($val->type=="company") {?>
						<h4><?php echo $val->title;?></h4>
						<ul>
							<li><?php echo anchor("main/intro",lang("menu.aboutus"));?></li>
							<li><a href="/notice/index"><?php echo lang("menu.notice");?></a></li>
							<li><a href="/faq/index"><?php echo lang("menu.faq");?></a></li>
						</ul>
					<?php } ?>															
				</div>
			<?php } ?>
		</div>
	</div>	
</div>
<style>
.footer-sitemap{
	padding-top:30px;
	padding-bottom:30px;
	background-color: #f5f5f5;
}

.footer-sitemap h4{
	font-size:14px;
	font-weight:900;
	margin-bottom:20px;
}

.footer-sitemap li {
    border-bottom: 1px solid #dedede;
    margin-bottom: 10px;
}
</style>