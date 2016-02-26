<script src='/assets/plugin/upload/jquery.MetaData.js' type="text/javascript" language="javascript"></script> 
<script src='/assets/plugin/upload/jquery.MultiFile.js' type="text/javascript" language="javascript"></script> 
<script src='/assets/plugin/upload/jquery.blockUI.js' type="text/javascript" language="javascript"></script> 
<script>
$(function() {
	get_comment();
});

function portfolio(){
		$(".blog_loading").show();

		$.get("/adminblogapi/news/<?php echo $query->id;?>/"+Math.round(new Date().getTime()),function(data){
			if(data=="0"){
				$(".blog_loading").hide();
				alert("등록에 실패하였습니다. 블로그 점검 중일 수 있습니다.");
			} else {
				$(".blog_loading").hide();
				alert("성공했습니다.");
			}
		})
}

function delete_portfolio(id){
	if(confirm("갤러리를 삭제하시겠습니까?\n뉴스 삭제는 슈퍼관리자와 등록한 직원만 가능합니다.")){
		location.href="/adminportfolio/remove/"+id;
	}
}

function change(type, id, status){
	if(confirm("상태를 변경하시겠습니까?")){
		$.get("/adminportfolio/change/"+type+"/"+id+"/"+status+"/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				location.reload();
		   } else {
				alert("변경 실패");
		   }
		})
	}
}

function get_comment(){
	$.getJSON("/comment/get_json/<?php echo $query->id;?>/"+Math.round(new Date().getTime()),function(data){
			var str = "";
			$.each(data, function(key, val) {
				if(val["step_id"]=="0") {
				str += "<div class='blog-reply-item'>";
				} else {
				str += "<div class='blog-reply-item blog-reply-item-reply'>";
				}
				str += "<b>"+val["name"]+"</b>  <small>"+val["date"]+"</small> ";

				str += "<br/><i class='fa fa-quote-left'></i> "+val["content"];
				str += "</div>";
			});
			if(str=="") str ="<div><?php echo lang("msg.nodata");?></div>";
			$("#comment_list").html(str);
	});
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				갤러리<small>보기</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li><i class="fa fa-file-image-o"></i> <a href="#">갤러리</a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<a href="/adminportfolio/index">목록</a> <i class="fa fa-angle-right"></i>
				</li>
				<li>
					보기
				</li>
			</ul>
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
					실행 <i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="/adminportfolio/index/">목록</a>
						</li>
						<li class="divider">
						</li>
						<li>
							<a href="/adminportfolio/edit/<?php echo $query->id;?>">수정</a>
						</li>
						<li>
						<?php if($query->is_activated=="1"){?>
							<a href="#" onclick="change('is_activated','<?php echo $query->id;?>','0');">공개 해제</a>
						<?php } else {	?>
							<a href="#" onclick="change('is_activated','<?php echo $query->id;?>','1');">공개 설정</a>
						<?php }?>
						</li>
						<li class="divider">
						<?php 
							if(count($blog)>0){
						?>
						<li>
							<a href="#" onclick="blog();">블로그발행 (<?php echo $query->is_blog;?>)</a>
						</li>
						<?php } else {?>
						<li>
							<a href="/adminblogapi/index">발행가능 블로그 없음(관리로 이동)</a>
						</li>
						<?php }?>
						
						<li class="divider">
						</li>
						<li>
							<a href="#" onclick="delete_portfolio('<?php echo $query->id;?>');"><?php echo lang("site.delete");?></a>
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
					<?php if($query->is_activated=="1") {echo "<font color='blue'>[공개]</font>";}?>
					<?php if($query->is_activated=="0") {echo "<font color='red'>[비공개]</font>";}?>
				</span></h4>
			<hr/>
			
			<input type="hidden" name="id" value="<?php echo $query->id?>"/>
			<div class="portlet">
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name"><?php echo lang("site.category");?></div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $query->category_name;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 hidden-xs name">대표사진</div>
					<div class="col-sm-10 col-xs-12 value">
						<?php if($query->thumb_name==""){
						?>
							<div class="help-block"><?php echo lang("msg.nodata");?></div>
						<?php
						} else {?>
						  <img src="/uploads/portfolios/thumb/<?php echo $query->thumb_name;?>" class="img-responsive"/>
						<?php }?>

					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 hidden-xs name">설명</div>
					<div class="col-sm-10 col-xs-12 value">
						<?php echo $query->content;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 hidden-xs name">키워드</div>
					<div class="col-sm-10 col-xs-12 value">
						<?php echo $query->tag;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 hidden-xs name">댓글</div>
					<div class="col-sm-10 col-xs-12 value">
						<div id="comment_list"></div>
					</div>
				</div>
				

	</div><!-- row -->
</div> 
