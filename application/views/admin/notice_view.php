<script src='/assets/plugin/upload/jquery.MetaData.js' type="text/javascript" language="javascript"></script> 
<script src='/assets/plugin/upload/jquery.MultiFile.js' type="text/javascript" language="javascript"></script> 
<script src='/assets/plugin/upload/jquery.blockUI.js' type="text/javascript" language="javascript"></script> 
<script>
$(function() {

});

function delete_notice(id){
	if(confirm("공지사항을 삭제하시겠습니까?")){
		location.href="/adminnotice/delete_notice/"+id;
	}
}

function change(type, id, status){
	if(confirm("상태를 변경하시겠습니까?")){
		$.get("/adminnotice/change/"+type+"/"+id+"/"+status+"/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				location.reload();
		   } else {
				alert("변경 실패");
		   }
		})
	}
}

</script>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				<?php echo lang("menu.notice");?><small>보기</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<?php echo lang("menu.notice");?> 보기
				</li>
			</ul>
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
					실행 <i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="/adminnotice/index/">목록</a>
						</li>
						<li class="divider">
						</li>
						<li>
							<a href="/adminnotice/edit/<?php echo $query->id;?>">수정</a>
						</li>
						<li>
						<?php if($query->is_popup=="1"){?>
							<a href="#" onclick="change('is_popup','<?php echo $query->id;?>','0');">팝업 해제</a>
						<?php } else {	?>
							<a href="#" onclick="change('is_popup','<?php echo $query->id;?>','1');">팝업 띄우기</a>
						<?php }?>
						</li>
						<li class="divider">
						</li>
						<li>
							<a href="#" onclick="delete_notice('<?php echo $query->id;?>');"><?php echo lang("site.delete");?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.row -->

<div class="row">
	<div class="col-lg-12">

			<!-- order meta start-->
			<h4>
				<?php echo $query->title;?>
				
				<span style="font-size:12px;padding-left:10px;">
					<?php if($query->is_popup=="1") {echo "<font color='blue'>[팝업띄우기]</font>";}?>
					<?php if($query->is_popup=="0") {echo "<font color='red'>[팝업해제]</font>";}?>
				</span></h4>
			<hr/>
			
			<input type="hidden" name="id" value="<?php echo $query->id?>"/>
			<div class="portlet">
				<div class="row static-info">
					<div class="col-sm-2 hidden-xs name">설명</div>
					<div class="col-sm-10 col-xs-12 value">
						<?php echo $query->content;?>
					</div>
				</div>
			</div>
	</div><!-- row -->
</div> 
