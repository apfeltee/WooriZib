<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			갤러리<small>목록</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li><i class="fa fa-file-image-o"></i> <a href="#">갤러리</a> <i class="fa fa-angle-right"></i> </li>
				<li>
					목록
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="location.href='/adminportfolio/add'">등록</button>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-3">
		<!-- 뉴스 카테고리 -->
		<!-- search start -->
			<div class="search-left">
				<h3 style="margin-bottom:10px;"><?php echo lang("site.category");?></h3>
				<div class="list-group">
					 <a href="/adminportfolio/index" class="list-group-item <?php if($category_id=="0") echo "active";?>"><?php echo lang("site.all");?></a>
					<?php foreach($category as $val){?>
					 <a href="/adminportfolio/index/<?php echo $val->id;?>/" class="list-group-item <?php if($category_id==$val->id) echo "active";?>"><?php echo $val->name;?><?php if($val->opened=="N") {echo " <i class='fa fa-user'></i>";}?></a>
					<?php }?>
				</div>
			</div>
		<!-- seartch end -->
	</div>
	<div class="col-lg-9">
			<div class="sorting">
			</div>
			<ul id="paging" class="pagination"><?php echo $pagination;?></ul>
			<div style="clear:both;margin-bottom:10px;"></div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center" style="width:90px;"><?php echo lang("site.photo");?></th>
					<th class="text-center"><?php echo lang("site.category");?>/<?php echo lang("site.title");?></th>
					<th class="text-center" style="width:50px;">댓글</th>
					<th class="text-center hidden-xs" style="width:50px;">조회</th>
					<th class="text-center hidden-xs" style="width:100px;"><?php echo lang("site.regdate");?></th>
				</tr>
			</thead>
			<tbody id="search-items">
				<?php 
					if(count($result)<1){
						echo "<tr><td colspan='5' class='text-center'>".lang("msg.nodata")."</td></tr>";
					}
					foreach($result as $val){?>
					<tr>
						<td style="padding:0px;width:90px;"><?php if($val->thumb_name!=""){echo "<img src='/uploads/portfolios/thumb/".$val->thumb_name."' class='img-responsive' style='width:90px;'>";}?></td>
						<td class="vertical-middle">
							<small>
								<?php if($val->is_activated=="1"){echo "<font color='blue'>[공개]</font>";} else {echo "<font color='red'>[비공개]</font>";}?>
								[<i><?php echo $val->name;?></i>]
							</small><br/><?php echo anchor("adminportfolio/view/".$val->id,$val->title);?></td>
						<td class="text-center vertical-middle"><?php echo $val->viewcnt;?></td>
						<td class="text-center vertical-middle hidden-xs"><?php echo $val->viewcnt;?></td>
						<td class="text-center vertical-middle hidden-xs">
							<?php echo substr($val->date,0,10);?>
						</td>
					</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
</div>

