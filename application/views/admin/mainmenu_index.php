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
				$.get("/adminmenu/sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
					
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
			메인메뉴 설정
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					메인메뉴 설정
				</li>
			</ul>
			<div class="page-toolbar">
				
			</div>
		</div>
	</div>
</div><!-- /.row -->

<div class="row">
	<div class="col-lg-6">
		<div class="help-block">* 마우스를 드래그하여 위치를 변경 하실 수 있습니다.</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" style="width:25px;"><i class="fa fa-arrows"></i></th>
					<th class="text-center">메뉴명</th>
					<th class="text-center">사용여부</th>
					<th class="text-center" style="width:80px;">저장</th>
				</tr>
			</thead>
			<tbody id="sort_list">
				<?php
				foreach($query as $key=>$val){
					if(!$config->INSTALLATION_MENU_FLAG && $val->type=="installation") continue;
					if(!$config->USER_PRODUCT && $val->type=="product_add") continue;
					if(!$config->BUILDING_ENQUIRE && $val->type=="building") continue;
				?>
					<?php echo form_open("adminmenu/update");?>
					<tr class="is_sortable" data-id="<?php echo $val->id;?>">
						<td class="text-center vertical-middle"><i class="fa fa-sort"></i></td>
						<td class="text-center">
							<input type="hidden" name="id" value="<?php echo $val->id;?>">
							<input type="text" name="title" value="<?php echo $val->title;?>" class="form-control">
						</td>
						<td>
							<select name="flag" class="form-control">
								<option value="Y" <?php if($val->flag=="Y") echo "selected";?>>사용하기</option>
								<option value="N" <?php if($val->flag=="N") echo "selected";?>>사용하지 않기</option>
							</select>
						</td>
						<td>
							<button type="submit" class="btn btn-primary btn-block">저장</button>
						</td>
					</tr>
					<?php echo form_close();?>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
</div>
