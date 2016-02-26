<link href="/assets/admin/css/timeline.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/css/tasks.css" rel="stylesheet" type="text/css"/>
<link href="/assets/plugin/icheck/skins/all.css" rel="stylesheet"/>
<script src="/assets/plugin/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="/assets/plugin/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			변경<small>이력</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>변경 이력</li>
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="portlet tasks-widget">
			<div class="portlet-title line">
				<div class="caption">
					<i class="fa fa-history"></i> 변동 이력
				</div>
			</div>
			<div class="portlet-body">
				<div class="btn-toolbar margin-bottom-10">
					<div class="btn-group">
						<a href="/adminenquire/history/0" class="btn default btn-sm <?php if($type=="0") echo "active";?>"><strong>전체 </strong></a>
						<a href="/adminenquire/history/C" class="btn default btn-sm <?php if($type=="C") echo "active";?>" style="color:#1bbc9b">접촉 </a>
						<a href="/adminenquire/history/M" class="btn default btn-sm <?php if($type=="M") echo "active";?>" style="color:#9b59b6">메모 </a>
						<a href="/adminenquire/history/L" class="btn default btn-sm <?php if($type=="L") echo "active";?>" style="color:#89C4F4">계약 </a>
					</div>

					<div class="pull-right">
						<ul class="pagination">
							<?php echo $pagination;?>
						</ul>
					</div>
				</div>
				<div id="vmap_world" class="vmaps display-none">
				</div>
				<div id="vmap_usa" class="vmaps display-none">
				</div>
				<div id="vmap_europe" class="vmaps display-none">
				</div>
				<div id="vmap_russia" class="vmaps display-none">
				</div>
				<div id="vmap_germany" class="vmaps display-none">
				</div>
				
				<?php
				if(count($history)<1){
					echo "없음";
				} else {
				?>
				<div class="timeline">
						<?php 
						foreach($history as $val){?>
						<div class="timeline-item">
							<div class="timeline-badge">
								<?php if($val->profile!=""){?>
									<img class="timeline-badge-userpic" src="/uploads/member/<?php echo $val->profile?>">
								<?php } else { ?>
									<img class="timeline-badge-userpic" src="/assets/admin/img/w1.png">
								<?php } ?>
								
							</div>
							<div class="timeline-body">
								<div class="timeline-body-arrow"></div>
										<div class="timeline-body-head">
											<div class="timeline-body-head-caption">
												<a href="/adminenquire/view/<?php echo $val->enquire_id;?>" class="timeline-body-title font-blue-madison"><?php echo $val->name;?></a>
												<span class="timeline-body-time font-grey-cascade"><?php echo $val->regdate;?> (담당: <?php echo $val->member_name;?>)</span>
											</div>
										</div>
										<div class="timeline-body-content">
											<span class="font-grey-cascade">
											<?php echo $val->name;?> 님의 
											<?php 
												if($val->type=="C") {
													echo "접촉정보";
												} else if($val->type=="M") {
													echo "메모정보";
												} else if($val->type=="L") {
													echo "계약정보";
												}
											?>가 
											<?php 
												if($val->action=="A") {
													echo "추가";
												} else if($val->action=="M") {
													echo "수정";
												}
											?>되었습니다. <br/>
											<?php 
												if($val->type=="C") {
													echo "<i class=\"fa fa-user\"></i>";
												} else if($val->type=="M") {
													echo "<i class=\"fa fa-weixin\"></i>";
												} else if($val->type=="L") {
													echo "<i class=\"fa fa-check\"></i>";
												}
											?> <?php echo strip_tags($val->title);?>
											</span>
										</div>
							</div>
						</div>
						<?php }?>
				</div>
				<?php }?>

				<div class="row text-center">
					<div class="col-sm-12">
						<ul class="pagination">
							<?php echo $pagination;?>
						</ul>
				    </div>
				</div>

			</div>
		</div>
	</div>
</div>
