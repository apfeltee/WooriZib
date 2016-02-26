	<div class="main">
		<div class="_container">

		<ul class="breadcrumb">
            <li><a href="/"><?php echo lang("menu.home");?></a></li>
            <li><a href="#"><?php echo lang("menu.mypage");?></a></li>
            <li class="active"><?php echo lang("site.seen");?></li>
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
              <li class="list-group-item clearfix active"><a href="/member/history"><i class="fa fa-angle-right"></i> <?php echo lang("site.seen");?></a></li>
              <li class="list-group-item clearfix"><a href="/member/hope"><i class="fa fa-angle-right"></i> <?php echo lang("site.saved");?></a></li>
			  <?php if($config->BUILDING_ENQUIRE){?>
			  <li class="list-group-item clearfix"><a href="/member/building_enquire_list"><i class="fa fa-angle-right"></i> 건축물자가진단 의뢰</a></li>
			  <?php }?>
            </ul>
          </div>
          <!-- END SIDEBAR -->

          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-9">
            <h1><?php echo lang("site.seen");?></h1>
            <div class="content-form-page">
              <div class="row">
                <div class="col-md-12 col-sm-12">
					<table class="table">
						<tr>
							<th><?php echo lang("site.photo");?></th>
							<th><?php echo lang("site.price");?></th>
							<th><?php echo lang("site.title");?></th>
						</th>
						<?php foreach($query as $val){?>
						<tr>
							<td>
								<?php	if($val->thumb_name==""){
									echo anchor("product/view/".$val->id,"<img src=\"/assets/common/img/no_thumb.png\" style=\"width:110px;\">");
								} else {
									echo anchor("product/view/".$val->id,"<img src=\"/photo/gallery_thumb/".$val->gallery_id."\" style=\"width:110px;\">");
								}?>		
							</td>
							<td>
								<?php echo anchor("product/view/".$val->id,$val->title);?>
							</td>
							<td>
								<?php echo price($val,$config);?>
							</td>
						</tr>
						<?php }
						
						if(count($query)<1){
							echo "<tr><td class='text-center' colspan='4'>".lang("msg.nodata")."</td></tr>";
						}
						?>
					</table>

				</div>
              </div>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
	</div>
</div>
