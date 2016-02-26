    <div class="main">
      <div class="_container">
        <ul class="breadcrumb">
            <li><a href="/"><?php echo lang("menu.home");?></a></li>
            <li class="active">
				<?php foreach($mainmenu as $val){
					if($val->type=="news") echo $val->title;
				}?>			
			</li>
        </ul>

       <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN CONTENT -->
          <div class="col-md-12 col-sm-12">
            <h1 class="margin-bottom-20">
			<?php foreach($mainmenu as $val){
				if($val->type=="news") echo $val->title;
			}?>
			</h1>
            <div class="content-page">
              <div class="row">

                <div class="col-md-9 col-sm-9 news-posts">
				<?php 
				if(count($result)<1){
					echo "<div class='help-block' style='padding:20px;'>".lang("msg.nodata")."</div>";
				}
				foreach($result as $val){ ?>
				<!-- BEGIN LEFT SIDEBAR -->            
                  <div class="row">
                    <div class="col-md-4 col-sm-4">
							<a href='/news/view/<?php echo $val->id;?>'>
							<?php if($val->thumb_name!=""){?>
								<img src="/uploads/news/thumb/<?php echo $val->thumb_name?>" class="img-responsive">
							<?php } else {?>
								<img src="/assets/common/img/no_thumb.png" class="img-responsive">
							<?php } ?>
							</a>
					</div>
                    <div class="col-md-8 col-sm-8">
                      <h2><?php echo anchor("news/view/".$val->id, $val->title);?></h2>
                      <?php if($config->NEWS_DATE_VIEW){?>
                        <ul class="news-info">
                          <li><i class="fa fa-calendar"></i> <?php echo $val->date;?></li>
                          <li><i class="fa fa-eye"></i> <?php echo $val->viewcnt;?></li>
                          <li><i class="fa fa-tags"></i> <?php echo $val->member_name;?></li>
                        </ul>
                      <?php }?>
                      <p><?php echo cut(strip_tags($val->content),500);?></p>
                      <?php echo anchor("news/view/".$val->id, lang("site.more")." <i class=\"icon-angle-right\"></i>","class='more'");?>
                    </div>
                  </div>
 				  <hr class="news-post-sep">
                <!-- END LEFT SIDEBAR -->
				<?php }?>
					<?php if( $pagination!="") {?>
					<div class="row text-center">
						<div class="col-sm-12">
							<ul class="pagination" style="float:none;">
								<?php echo $pagination;?>
							</ul>
						</div>
					</div>
					<?php } ?>
				</div>

                <!-- BEGIN RIGHT SIDEBAR -->            
                <div class="col-md-3 col-sm-3 news-sidebar">
                  <!-- CATEGORIES START -->
                  <h2 class="no-top-space"><?php echo lang("site.category");?></h2>
                  <ul class="nav sidebar-categories margin-bottom-40">
						<li <?php if($current_newscategory=="0"){?>class="active"<?php }?>> <a href="/news/index"><?php echo lang("site.all");?></a></li>
						<?php foreach($newscategory as $val1){?>
						 <li <?php if($current_newscategory==$val1->id){?>class="active"<?php }?>><a href="/news/index/<?php echo $val1->id;?>/"><?php echo $val1->name;?><?php if($val1->opened=="N") {echo " <i class='fa fa-user'></i>";}?></a></li>
						<?php }?>
                  </ul>
                  <!-- CATEGORIES END -->

            <h4>최신 등록 <?php echo lang("product");?></h4>
            <ul class="list_widget">
            <?php if(count($recent)<1){
              echo "<li>".lang("msg.nodata")."</li>";
            }?>
            <?php foreach($recent as $val){?>
            <li class="row">
              <div class="col-md-4 col-xs-4 margin-bottom-10" style="padding-right:0px;">
                <div class="img_wrapper" style="background-image:url(<?php  if($val["thumb_name"]==""){
                  echo "/assets/common/img/no_thumb.png";
                } else {
				  $temp = explode(".",$val["thumb_name"]);
                  echo "/uploads/gallery/".$val["id"]."/".$temp[0]."_thumb.".$temp[1];
                }?>);">
                  <a href="/product/view/<?php echo $val["id"]?>" target="_blank"><img src="/assets/common/img/bg/0.png" class="holder"></a>
                
                </div>
              </div>
              <div class="col-md-8 col-xs-8 margin-bottom-10">
                <div class="title margin-bottom-10" title="<?php echo $val["title"];?>"><?php echo anchor("product/view/".$val["id"],cut($val["title"],65));?></div>
                <?php echo price($val,$config);?>
              </div>
            </li>
            <?php }?>
            </ul>				
            <!-- END RIGHT SIDEBAR -->            
          </div>
        </div>
      </div>
      <!-- END CONTENT -->
    </div>
    <!-- END SIDEBAR & CONTENT -->
  </div>
</div>
