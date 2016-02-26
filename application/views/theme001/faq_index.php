<div class="main">
	<div class="_container">
		<ul class="breadcrumb">
			<li><a href="/"><?php echo lang("menu.home");?></a></li>
			<li><?php echo lang("menu.customercenter");?></li>
			<li class="active"><?php echo lang("menu.faq");?></li>
		</ul>
		<div class="row margin-bottom-40">
			<div class="col-md-12 col-sm-12">
				<h1 class="margin-bottom-20"><?php echo lang("menu.faq");?></h1>
				<ul class="nav nav-tabs">
					<li><a class="section_tab" href="/notice/index"><?php echo lang("menu.notice");?></a></li>
					<li class="active"><a class="section_tab" href="/faq/index"><?php echo lang("menu.faq");?></a></li>
				</ul>
			</div>
			<!-- BEGIN CONTENT -->
			<div class="col-md-12 col-sm-12">
				<div class="tab-content" style="margin-top:30px; padding:0; background: #fff;">
				  <!-- START TAB 1 -->
				  <div class="tab-pane active" id="tab_1">
					 <div class="panel-group" id="accordion1">
						<?php 
						foreach($query as $key=>$val){
						?>
						<div class="panel panel-default">
						   <div class="panel-heading">
							  <h4 class="panel-title">
								 <a href="#accordion1_<?php echo $key;?>" data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle">
								 <?php echo $val->title;?>
								 </a>
							  </h4>
						   </div>
						   <div class="panel-collapse collapse" id="accordion1_<?php echo $key;?>">
							  <div class="panel-body">
								 <?php echo $val->content;?>
							  </div>
						   </div>
						</div>
						<?php }?>
					</div>
				</div>
			</div>
			<!-- END CONTENT -->		
		</div>
		<!-- END SIDEBAR & CONTENT -->
	</div>
</div>