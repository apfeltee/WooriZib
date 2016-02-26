<div class="main">
	<div class="_container">
		<ul class="breadcrumb">
			<li><a href="/"><?php echo lang("menu.home");?></a></li>
			<li><?php echo lang("menu.customercenter");?></li>
			<li class="active"><?php echo lang("menu.notice");?></li>
		</ul>
		<div class="row margin-bottom-40">
			<div class="col-lg-12">
				<h1 class="margin-bottom-20"><?php echo lang("menu.notice");?></h1>
				<ul class="nav nav-tabs">
					<li class="active"><a class="section_tab" href="/notice/index"><?php echo lang("menu.notice");?></a></li>
					<li><a class="section_tab" href="/faq/index"><?php echo lang("menu.faq");?></a></li>
				</ul>
			</div>
			<!-- BEGIN CONTENT -->
			<div class="col-lg-12">
				<div class="content-page margin-top-20">
					<?php 
					if(count($result) < 1){
						?>
						<div class="search-result-item"><?php echo lang("msg.nodata");?></div>
						<?php
					}
					foreach($result as $val){?>
					<div class="search-result-item">
						<h4><a href="javascript:notice_show('<?php echo $val->id?>');"><strong><?php echo $val->title;?></strong></a></h4>
						<p><?php echo cut($val->content,700);?></p>
						<p class="search-link pull-right" href="#"><?php echo $val->date;?></p>
					</div>
					<?php }?>
					<div class="row">
						<div class="col-lg-4 items-info"></div>
						<div class="col-lg-8">
							<ul class="pagination pull-right">
							<?php echo $pagination;?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- END CONTENT -->		
		</div>
		<!-- END SIDEBAR & CONTENT -->
	</div>
</div>