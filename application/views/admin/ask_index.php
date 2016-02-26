<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			질문과 답변<small>목록</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li><?php echo lang("enquire.title");?> 목록</li>
			</ul>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" width="15%"><?php echo lang("site.name");?> </th>
					<th class="text-center" width="*"><?php echo lang("site.title");?></th>
					<th class="text-center" width="15%"><?php echo lang("site.status");?></th>
					<th class="text-center" width="20%"><?php echo lang("site.regdate");?></th>
					<th class="text-center" width="10%">고객추가</th>
				</tr>
			</thead>
			<tbody id="sort_list">
				<?php 
					if(count($result)<1){
						echo "<tr><td colspan='5' class='text-center'>".lang("msg.nodata")."</td></tr>";
					}
					foreach($result as $val){?>
					<tr class="is_sortable" data-id="<?php echo $val->id;?>">
						<td><?php echo $val->name;?></td>
						<td><?php echo anchor("adminask/view/".$val->id,cut($val->title,"200"));?></td>
						<td>
						<?php if($val->answer){?>
							<span>답변완료</span>
						<?php }else{?>
							<span style="color:red">미답변</span>
						<?php }?>
						</td>
						<td class="hidden-xs">
							<?php echo $val->date;?>
						</td>
						<td class="hidden-xs">
							<a class="btn btn-success btn-sm" href="/admincontact/add_flashdata/ask/<?php echo $val->id;?>">고객전환</a>
						</td>
					</tr>
				<?php }?>
			</tbody>
		</table>
		<div class="row">
			<div class="col-lg-12">
				<ul class="pagination pull-right">
				<?php echo $pagination;?>
				</ul>
			</div>
		</div>
	</div>
</div>