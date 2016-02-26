<script>
function delete_faq(id){
	if(confirm("자주 묻는 질문을 삭제하시겠습니까?")){
		location.href="/adminfaq/delete_faq/"+id;
	}
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			<?php echo lang("menu.faq");?><small>보기</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li><?php echo lang("menu.faq");?> 보기</li>
			</ul>
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
					실행 <i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="/adminfaq/index/"><?php echo lang("site.list");?></a>
						</li>
						<li class="divider">
						</li>
						<li>
							<a href="/adminfaq/edit/<?php echo $query->id;?>"><?php echo lang("site.modify");?></a>
						</li>
						<li class="divider">
						</li>
						<li>
							<a href="#" onclick="delete_faq('<?php echo $query->id;?>');"><?php echo lang("site.delete");?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<h4><?php echo $query->title;?></h4>
		<hr/>			
		<input type="hidden" name="id" value="<?php echo $query->id?>"/>
		<div class="portlet">
			<div class="row static-info">
				<div class="col-sm-2 hidden-xs name"><?php echo lang("enquire.answer");?></div>
				<div class="col-sm-10 col-xs-12 value">
					<?php echo $query->content;?>
				</div>
			</div>
		</div>
	</div>
</div> 
