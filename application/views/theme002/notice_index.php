<script>
function notice_show(id,form){
	$("#"+form).find("#profile_msg").html('');
	$.getJSON("/notice/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			if(key=="title") $("#notice_modal_title").html(val);
			if(key=="content") $("#notice_modal_content").html(val);
			$('#notice_modal').modal('show');
		});
	});
}
</script>

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

<div class="modal" id="notice_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:98%;max-width: 580px;">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:5px;"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-circle"></i> 
		<span id="notice_modal_title"></span></h4>
      </div>
      <div class="modal-body" style="padding:10px;">
        <div id="notice_modal_content"></div>
      </div>
      <div class="modal-footer" style="padding:10px">
		<button type="button" class="btn btn-warning btn-xs" onclick="$('#notice_modal').modal('hide')"><?php echo lang("site.close");?></button>
      </div>
    </div>
  </div>
</div>