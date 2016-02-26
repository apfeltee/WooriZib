<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				<?php echo lang("menu.notice");?><small><?php echo lang("site.list");?></small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<?php echo lang("menu.notice");?> <?php echo lang("site.list");?>
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="location.href='/adminnotice/add'"><?php echo lang("site.register");?></button>
			</div>
		</div>
	</div>
</div><!-- /.row -->

<div class="row">
	<div class="col-lg-12">
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" style="width:100px;">팝업</th>
					<th class="text-center"><?php echo lang("site.title");?></th>
					<th class="text-center hidden-xs" style="width:100px;">조회</th>
					<th class="text-center hidden-xs" style="width:100px;"><?php echo lang("site.regdate");?></th>
				</tr>
			</thead>
			<tbody id="search-items">
				<?php 
					if(count($result)<1){
						echo "<tr><td colspan='4' class='text-center'>".lang("msg.nodata")."</td></tr>";
					}
					foreach($result as $val){?>
					<tr>
						<td style="padding:0px;width:90px;"><?php if($val->is_popup=="1") echo "팝업";?></td>
						<td><?php echo anchor("adminnotice/view/".$val->id,$val->title);?></td>
						<td class="hidden-xs"><?php echo $val->viewcnt;?></td>
						<td class="hidden-xs">
							<?php echo substr($val->date,0,10);?>
						</td>
					</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
</div>

