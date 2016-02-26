<script>
function blog(id){
	window.open("/adminblogapi/blog_popup/"+id+"/news","blog_window","width=460, height=700, resizable=no, scrollbars=no, status=no;");
}

function cafe(id){
	window.open("/admincafeapi/OAuth/"+id+"/news","cafe_window","width=460, height=480, resizable=no, scrollbars=no, status=no;");
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			뉴스<small>목록</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					뉴스 목록
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="location.href='/adminnews/add'">등록</button>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-3">
		<!-- 뉴스 카테고리 -->
		<!-- search start -->
		<div class="search-left">
			<h3 style="margin-bottom:10px;">뉴스카테고리</h3>
			<div class="list-group">
				 <a href="/adminnews/index" class="list-group-item <?php if(!$category_id) echo "active"?>"><?php echo lang("site.all");?></a>
				<?php foreach($category as $val){?>
				 <a href="/adminnews/index/<?php echo $val->id;?>/" class="list-group-item <?php if($category_id==$val->id) echo "active"?>"><?php echo $val->name;?><?php if($val->opened=="N") {echo " <i class='fa fa-user'></i>";}?></a>
				<?php }?>
			</div>
		</div>
		<!-- seartch end -->
	</div>
	<div class="col-lg-9">
		<div class="sorting"></div>
		<ul id="paging" class="pagination"><?php echo $pagination;?></ul>
		<div style="clear:both;margin-bottom:10px;"></div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" style="width:90px;">대표사진</th>
					<th class="text-center"><?php echo lang("site.category");?>/<?php echo lang("site.title");?></th>
					<th class="text-center hidden-xs" style="width:100px;">포스팅</th>
					<th class="text-center" style="width:100px;">댓글</th>
					<th class="text-center hidden-xs" style="width:100px;">조회</th>
					<th class="text-center" style="width:100px;">첨부파일</th>
					<th class="text-center hidden-xs" style="width:100px;"><?php echo lang("site.regdate");?>/담당</th>
				</tr>
			</thead>
			<tbody id="search-items">
				<?php 
					if(count($result)<1){
						echo "<tr><td colspan='7' class='text-center'>".lang("msg.nodata")."</td></tr>";
					}
					foreach($result as $val){?>
					<tr>
						<td style="padding:0px;width:90px;"><?php if($val->thumb_name!=""){echo "<img src='/uploads/news/thumb/".$val->thumb_name."' class='img-responsive' style='width:90px;'>";}?></td>
						<td class="vertical-middle">
							<small>
								<?php if($val->is_activated=="1"){echo "<font color='blue'>[공개]</font>";} else {echo "<font color='red'>[비공개]</font>";}?>
								<?php if($val->product_print=="Y") echo "<font color='green'>[매물우측출력]</font>";?>
								[<i><?php echo $val->name;?></i>]								
							</small><br/><?php echo anchor("adminnews/view/".$val->id,$val->title);?>
						</td>
						<td class="text-center vertical-middle hidden-xs">
							<button type="button" type="button" class="btn btn-link btn-sm" onclick="blog('<?php echo $val->id?>');"><i class="fa fa-share-alt"></i> 블로그(<?php echo $val->is_blog?>)</button>
							<?php if($config->navercskey && $config->navercssecret && $config->naverclientkey && $config->naverclientsecret ){?>
							<button type="button" type="button" class="btn btn-link btn-sm" onclick="cafe('<?php echo $val->id?>');"><i class="fa fa-share-alt"></i> N카페(<?php echo $val->is_cafe?>)</button>
							<?php }?>
						</td>						
						<td class="text-center vertical-middle"><?php echo $val->cnt;?></td>
						<td class="text-center vertical-middle hidden-xs"><?php echo $val->viewcnt;?></td>
						<td class="text-center vertical-middle">
						<?php foreach($val->attachment as $file){?>
							<i class="fa fa-file-text"></i>
						<?php }?>
						</td>
						<td class="text-center vertical-middle hidden-xs">
							<?php echo substr($val->date,0,10);?><br/><?php echo $val->member_name;?>
						</td>
					</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
</div>

