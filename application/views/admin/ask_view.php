<script>
function delete_faq(id){
	if(confirm("문의내용을 삭제하시겠습니까?")){
		location.href="/adminask/delete_ask/"+id;
	}
}
</script>
<form name="answer_form" action="/adminask/answer" method="post">
<input type="hidden" name="id" value="<?php echo $query->id?>"/>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			<?php echo lang("enquire.title");?><small> 보기</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?> </a> <i class="fa fa-angle-right"></i> </li>
				<li><?php echo lang("enquire.title");?> 보기</li>
			</ul>
			<div class="page-toolbar">
				<button type="button" class="btn btn-default" onclick="location.href='/adminask/index'">목록</button>&nbsp;
				<button type="submit" class="btn btn-primary"><?php echo lang("site.submit");?> </button>&nbsp;
				<button type="button" class="btn btn-danger" onclick="delete_faq('<?php echo $query->id;?>');">삭제</button>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<h4><?php echo $query->title;?></h4>
		<hr/>
		<div class="portlet">
			<div class="row static-info">
				<div class="col-sm-2 hidden-xs name"><?php echo lang("site.name");?> </div>
				<div class="col-sm-10 col-xs-12 value">
					<?php echo $query->name;?>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 hidden-xs name"><?php echo lang("site.tel");?> </div>
				<div class="col-sm-10 col-xs-12 value">
					<?php echo $query->phone;?>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 hidden-xs name"><?php echo lang("site.email");?> </div>
				<div class="col-sm-10 col-xs-12 value">
					<?php echo $query->email;?>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 hidden-xs name">문의</div>
				<div class="col-sm-10 col-xs-12 value">
					<?php echo $query->content;?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="portlet well">
			<div class="row static-info">
				<div class="col-sm-2 hidden-xs name"><?php echo lang("enquire.answer");?></div>
				<div class="col-sm-10 col-xs-12 value">
					<textarea class="form-control" rows="10" id="answer" name="answer"><?php echo $query->answer;?></textarea>
					<script>
						CKEDITOR.replace('answer', {customConfig: '/ckeditor/simple_config.js'});
					</script>
				</div>
			</div>
		</div>
	</div>
</div>
</form>