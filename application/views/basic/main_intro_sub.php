<div class="main">
  <div class="_container">
	<ul class="breadcrumb">
		<li><a href="/"><?php echo lang("menu.home");?></a></li>
		<li><?php if( $config->site_name!="" ) { echo $config->site_name; } else { echo $config->name; } ?> 소개</li>
		<li class="active"><?php echo $query->title;?></li>
	</ul>	
	<div class="row margin-bottom-40">
	  <div class="col-md-12">
		<h1><?php echo $query->title;?></h1>
		<div class="content-page">
			<div class="row">
				<div class="col-md-12">
					<?php echo $query->content;?>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>