	<div class="main">
		<div class="_container">
		<ul class="breadcrumb">
            <li><a href="/"><?php echo lang("menu.home");?></a></li>
            <li><a href="#"><?php echo lang("menu.mypage");?></a></li>
            <li class="active">건축물자가진단 의뢰</li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">          
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-3">
            <ul class="list-group margin-bottom-25 sidebar-menu">
			  <?php if($this->session->userdata("id")==""){?>
              <li class="list-group-item clearfix"><a href="/member/signin"><i class="fa fa-angle-right"></i> <?php echo lang("menu.login");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/signup"><i class="fa fa-angle-right"></i> <?php echo lang("menu.signup");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/search"><i class="fa fa-angle-right"></i> <?php echo lang("menu.lostpw");?></a></li>
			  <?php }?>
			  <?php if($this->session->userdata("id")){?>
              <li class="list-group-item clearfix"><a href="/member/profile"><i class="fa fa-angle-right"></i> <?php echo lang("menu.modifyprofile");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/delete"><i class="fa fa-angle-right"></i> <?php echo lang("menu.withdrawal");?></a></li>
			  <?php }?>
              <li class="list-group-item clearfix"><a href="/member/history"><i class="fa fa-angle-right"></i> <?php echo lang("site.seen");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/hope"><i class="fa fa-angle-right"></i> <?php echo lang("site.saved");?>></a></li>
			  <?php if($config->BUILDING_ENQUIRE){?>
			  <li class="list-group-item clearfix active"><a href="/member/building_enquire_list"><i class="fa fa-angle-right"></i> 건축물자가진단 의뢰</a></li>
			  <?php }?>
            </ul>
          </div>
          <!-- END SIDEBAR -->

          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-9">
            <h1>건축물자가진단 의뢰</h1>
            <div class="content-form-page">
              <div class="row">
                <div class="col-md-12 col-sm-12">
					<table class="table">
						<tr>
							<th><?php echo lang("site.status");?></th>
							<th>지번주소</th>
							<th>견적서</th>
							<th>의뢰일자</th>
						</th>
						<?php foreach($query as $val){?>
						<tr>
							<td>
								<?php if($val->type=="e​stimate"){?>
								<button type="button" class="btn btn-success btn-xs" style="cursor:default">견적의뢰</button>
								<?php } else {?>
								<button type="button" class="btn btn-danger btn-xs" style="cursor:default">중개의뢰</button>
								<?php }?>
							</td>
							<td><a href="/member/building_enquire_view/<?php echo $val->id;?>"><?php echo $val->address;?></a></td>
							<td>
								<?php foreach($val->estimate as $file){?>
									<i class="glyphicon glyphicon-list-alt"></i>
								<?php }?>
							</td>
							<td><?php echo date("Y-m-d",strtotime($val->date));?></td>
						</tr>
						<?php }						
						if(count($query)<1){
							echo "<tr><td class='text-center' colspan='4'>의뢰가 없습니다</td></tr>";
						}
						?>
					</table>
					<div class="row text-center">
						<div class="col-sm-12">
							<ul class="pagination" style="float:none;">
								<?php echo $pagination;?>
							</ul>
						</div>
					</div>
				</div>
              </div>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
	</div>
</div>
