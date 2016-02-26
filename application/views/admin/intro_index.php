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
				$.get("/adminintro/sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
					
				});
				i++;
			});
		}
	}).disableSelection();
});

function data_delete(id){
	if(confirm("메뉴를 삭제하시겠습니까?")){
		location.href="/adminintro/delete_action/"+id;
	}	
}
</script>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			회사소개메뉴 <small>관리</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li><a href="#">레이아웃 설정</a> <i class="fa fa-angle-right"></i></li>
				<li>회사소개메뉴 관리</li>
			</ul>
			<div class="page-toolbar">
				
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<div class="help-block">* 제목을 클릭하면 수정을 할 수 있습니다.</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" style="width:30px;"><i class="fa fa-arrows"></i></th>
					<th class="text-center">메뉴명</th>
					<th class="text-center" style="width:100px;">사용여부</th>
					<th class="text-center" style="width:50px;"><?php echo lang("site.delete");?></th>
				</tr>
			</thead>
			<tbody id="sort_list">
				<?php 
				if(count($query)<1){
					echo "<tr><td colspan='4' class='text-center'>등록된 정보가 없습니다</td></tr>";
				}
				foreach($query as $val){?>
				<tr class="<?php echo ($val->id!=1) ? "is_sortable" : "";?>" data-id="<?php echo $val->id;?>">
					<td class="text-center">
						<?php if($val->id==1){?>
							<i class="fa fa-times"></i>
						<?php } else {?>
							<i class="fa fa-sort"></i>
						<?php }?>						
					</td>
					<td class="text-center">
						<?php if($val->id==1){?>
							<?php echo $val->title;?>
						<?php } else {?>
							<a href="/adminintro/edit/<?php echo $val->id;?>"><?php echo $val->title;?></a>
						<?php }?>
					</td>
					<td class="text-center">
						<?php echo ($val->flag=="Y") ? "사용함" : "사용안함";?>
					</td>
					<td class="text-center"><button class="btn btn-xs btn-danger" onclick="data_delete('<?php echo $val->id;?>');" <?php if($val->id==1) echo "disabled"?> style="margin:0"><i class="fa fa-trash-o"></i></button></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		<div class="text-right">
			<a href="/adminintro/add"><button type="button" class="btn btn-primary">메뉴등록</button></a>
		</div>
	</div>
</div>