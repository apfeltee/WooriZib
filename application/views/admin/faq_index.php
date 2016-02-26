<script>
$(document).ready(function(){

	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};

	$("#sort_list").find("tr").each(function(index){
		if(index!=0) $(this).css('cursor','s-resize');
	});

	$("#sort_list").sortable({
		items: ".is_sortable",
		helper: fixHelper,
		update: function (event, ui) {
			var i=1;
			$("#sort_list").find("tr").each(function(){
				$.get("/adminfaq/sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
					
				});
				i++;
			});
		}
	}).disableSelection();
});
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			<?php echo lang("menu.faq");?><small>목록</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li><?php echo lang("menu.faq");?> <?php echo lang("site.list");?></li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="location.href='/adminfaq/add'"><?php echo lang("site.submit");?></button>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" style="width:25px;"><i class="fa fa-arrows"></i></th>
					<th class="text-center"><?php echo lang("site.title");?></th>
					<th class="text-center hidden-xs" style="width:150px;"><?php echo lang("site.regdate");?></th>
				</tr>
			</thead>
			<tbody id="sort_list">
				<?php 
					if(count($result)<1){
						echo "<tr><td colspan='4' class='text-center'>".lang("msg.nodata")."</td></tr>";
					}
					foreach($result as $val){?>
					<tr class="is_sortable" data-id="<?php echo $val->id;?>">
						<td class="text-center"><i class="fa fa-sort"></i></td>
						<td><?php echo anchor("adminfaq/view/".$val->id,$val->title);?></td>
						<td class="text-center hidden-xs">
							<?php echo substr($val->date,0,10);?>
						</td>
					</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
</div>

