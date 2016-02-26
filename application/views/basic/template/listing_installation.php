<?php

/*** 홈에서는 결과가 없어도 없다는 멘트를 보여주지 않는다. 그렇기 때문에 main에서만 보여주는 것으로 했다. ***/
/*** 홈에서는 Lazy Loading이 동작해야 한다. ***/

$home_flag = false;
if( $this->uri->segment(1) == "" || $this->uri->segment(1) == "home" ) {
	$home_flag = true;
}

if(!$home_flag){
	if(count($installation)<1){
		?>
		<div style='margin-top:20px;'><h4><?php echo lang("msg.nodata");?></h4>죄송합니다. 검색 조건에 맞는 검색 결과가 없습니다. </div>
		<?php
	}
}

/**
 * 분양은 단독으로 광고에 걸 수 있으므로 모달로 띄우지 않고 새창으로 띄운다.
 * 분양은 등록비를 받고 하는 사업은 둥지에서 할 계획이기 때문에 판매시에는 유료플랫폼을 제공하지 않는 것을 원칙으로 한다.
 *
 */

foreach($installation as $val){
	?>
	<div class='search-item-wide'>
		<div style="position:relative;overflow:hidden;height:200px;">
			<a href="/installation/view/<?php echo $val["id"];?>" title="<?php echo $val["title"];?>" target="_blank">
				<div class='photo lazy' data-original='/photo/gallery_installation_image/<?php echo $val['gallery_id']?>' style='background-position: center center;background-image:url(/assets/common/img/no_thumb.png);background-size: cover;'>
					<div class="item_title">
						<h3><?php echo cut($val["title"],44);?></h3>
						<div class='address'>
							<i class="fa fa-map-marker"></i> 
							<?php echo toeng(element("address_name",$val))?> 
							<?php echo element("address",$val);?>
						</div>
					</div>
			
					<div class="tags">
						<?php if($val["status"]=="plan")	echo "<div class='tag installation_plan'>계획</div>"; ?>
						<?php if($val["status"]=="go")	echo "<div class='tag installation_go'>진행중</div>"; ?>
						<?php if($val["status"]=="end")	echo "<div class='tag installation_end'>종료</div>"; ?>
					</div>
							
					<?php if($val["video_url"]!="") {?>
					<div class='videourl'><img src="/assets/common/img/youtube.png"></div>
					<?php }?>


				</div> <!-- photo -->
			</a>
		</div>
		<div class='item'>
			<div class='price_info'>
				<?php echo $val["scale"];?>
			</div>
			<div class='meta'>
				<?php if(element("notice_year",$val)!="") echo "<i class=\"fa fa-clock-o\"></i> 공고: " . element("notice_year",$val) ?> 
				<?php if(element("enter_year",$val)!="") echo "<i class=\"fa fa-clock-o\"></i> 입주: " . element("enter_year",$val)?>
			</div>
		</div>
		<div style='clear:both;'></div>
	</div>
	<?php 
}
?>
