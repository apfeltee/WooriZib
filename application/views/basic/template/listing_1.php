<?php

/*** 홈에서는 결과가 없어도 없다는 멘트를 보여주지 않는다. 그렇기 때문에 main에서만 보여주는 것으로 했다. ***/
/*** 홈에서는 Lazy Loading이 동작해야 한다. ***/

$home_flag = false;
if( $this->uri->segment(1) == "" || $this->uri->segment(1) == "home" ) {
	$home_flag = true;
}

if(!$home_flag){
	if(count($product)<1){
		?>
		<tr>
			<td colspan="8"><h4><?php echo lang("msg.nodata");?></h4>죄송합니다. 검색 조건에 맞는 검색 결과가 없습니다. <br/>그러나, 아직 등록되지 않은 <?php echo lang("product");?>(가)이 있으며 원하시는 조건의 <?php echo lang("product");?>을 찾아드릴 수 있으니 전화연락이나 매도/매수 문의를 이용하여 문의를 해주세요.</td>
		</tr>
		<?php
	}
}

$THE_REFER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/";

foreach($product as $val){
	?>
	<tr
	<?php if($val["category_opened"]=="N" && !$this->session->userdata("id")){?>
	class="leanModal" lean-id="#signup"
	<?php } else {?>
		<?php if($this->session->userdata("permit_area")){
				$permit_area = @explode(",",$this->session->userdata("permit_area"));
				if(in_array($val["address_id"],$permit_area)){?>
					class="view_product" data-id="<?php echo $val["id"];?>" data-toggle="modal" data-target="#view_dialog"
				<?php } else {?>
					class="leanModal" lean-id="#permit-area"
				<?php }?>
		<?php } else {?>
			class="view_product" data-id="<?php echo $val["id"];?>" data-toggle="modal" data-target="#view_dialog"
		<?php }?>
	<?php }?>
	title="<?php echo element("title",$val)?>">
		<td>
		<?php 
			$lazy_not = (strpos($THE_REFER, "grid") !== false) ? true : false;
			if($lazy_not){
		?>
			<div class="gallery_wrapper" style='background-position: center center;background-image:url(/photo/gallery_thumb/<?php echo $val['gallery_id']?>);background-size: cover;'>
				<?php if($val["video_url"]!="") {?>
				<div class='videourl'><img src="/assets/common/img/youtube.png" style="height:60px;"></div>
				<?php }?>
			</div>
		<?php }else{ ?>
			<div class="gallery_wrapper lazy" data-original='/photo/gallery_thumb/<?php echo $val['gallery_id']?>' style='background-position: center center;background-image:url(/assets/common/img/no_thumb.png);background-size: cover;'>
				<?php if($val["video_url"]!="") {?>
				<div class='videourl'><img src="/assets/common/img/youtube.png" style="height:60px;"></div>
				<?php }?>
			</div>
		<?php }?>
		</td>
		<td>
			<div class='address'>
				<i class="fa fa-map-marker"></i> 
					<?php echo toeng(element("address_name",$val))?> <br/>
					<?php 
							if($config->SHOW_ADDRESS) {
								echo element("address",$val) . "<br/>";
							}
					?>
				<?php foreach($val["subway"] as $key=>$val_subway){
					if($key<1){
				?>
					<div class='subway sub_<?php echo $val_subway->hosun_id;?>' title='<?php echo $val_subway->hosun;?> 호선'><?php echo $val_subway->name;?></div>
					<span class="help" data-toggle="tooltip" title="(직선거리기준) 도보 <?php echo round($val_subway->distance*12.5,1);?> 분"><?php echo round($val_subway->distance,1); ?> ㎞ </span>
				<?php 	} 
					}
				?>
			</div>
		</td>	
		<td>
			<?php echo element("name",$val)?><br/>
			<?php if( element("part",$val)=="N") {?>
				<i class="fa fa-building help"  data-toggle="tooltip" title="<?php echo lang("site.all");?>"></i>
		 	<?php }?>
		</td>
		<td>
			<?php if( element("type",$val)=="sell")  echo lang("sell"); ?>
			<?php if( element("type",$val)=="installation")  echo lang("installation"); ?>
			<?php if( element("type",$val)=="full_rent")  echo lang("full_rent"); ?>
			<?php if( element("type",$val)=="monthly_rent")  echo lang("monthly_rent"); ?>
			<?php if( element("type",$val)=="rent")  echo lang("rent"); ?>		
		</td>
		<td>
			<!-- 제목 및 주소, 가격 등 -->
			<p style="margin-bottom:5px;">
			<?php if( element("recommand",$val)=="1")  { ?><span class="label label-sm label-success">추천</span><?php }?>
			<?php if( element("is_finished",$val)=="1")  { ?><span class="label label-sm label-default">완료</span><?php }?>
			<?php if( element("is_speed",$val)=="1")  { ?><span class="label label-sm label-warning">급매</span><?php }?>
			<?php if( element("is_defer",$val)=="1")  { ?><span class="label label-sm label-info"><?php echo (element("type",$val)=="installation")?"분양":"계약"?>보류</span><?php }?>
			<b><a><?php echo cut(element("title",$val),60)?></a></b>
			</p>
			<p><?php echo str_replace( "<br/>","/", price($val,$config) );?></p>
			<?php if($config->GONGSIL_FLAG && element("gongsil_contact",$val)!="" ){?>
				<p style="font-size:11px;border-top:1px dashed #cacaca;padding-top:5px;margin-top:5px;">
					<?php echo str_replace("<br/>","&nbsp;",multi_view(element("gongsil_contact",$val),"gongsil",2));?>
				</p>
			<?php }?>
		</td>
		<?php if($config->PRODUCT_REALAREA || $config->PRODUCT_LAWAREA) {?>
		<td>
			<?php 
				if(element("part",$val) && $val["part"]=="Y"){
					if($config->AREA_SORTING){ //실면적 우선
						if($val["real_area"]!="0"){
							echo " ". area_list($val["real_area"],"") ;
						} else if($val["law_area"]!="0"){
							echo " ". area_list($val["law_area"],"") ;
						}
					}
					else{ //계약면적면적 우선
						if($val["law_area"]!="0"){
							echo " ". area_list($val["law_area"],"") ;
						} else if($val["real_area"]!="0"){
							echo " ". area_list($val["real_area"],"") ;
						}
					}

				} else {
					if($val["real_area"]!="0"){
						echo area_list($val["real_area"],"건") . "/" . area_list($val["land_area"]+$val["road_area"],"대");
					}
				}
				
			?>
		</td>
		<?php }?>
		<!--td>
			<?php 
				if(element("part",$val) && $val["part"]=="Y"){
					if($val["current_floor"]!="0"){
						echo $val["current_floor"] . "/" .$val["total_floor"] . "";
					}
				} else {
					echo "상" . $val["current_floor"] ;
					if($val["total_floor"]!="0")  echo "/하" . $val["total_floor"] ;
				}
			?>
		</td-->
		<td>
			<?php echo element("store_category",$val);?>
		</td>
		<td>
			<small>
				<?php echo date('Y/m/d', strtotime(element("date",$val) ) );?>
				<?php echo "<br/>".date('Y/m/d', strtotime(element("moddate",$val) ) );?>
				<?php if(element("last_check_date",$val)!="") {?><br/><span style="margin-top:5px;"><i class="fa fa-check"></i> 최종 매물확인일자 :  <?php echo element("last_check_date",$val)?></span><?php }?>
			</small><!-- <br/><i class="fa fa-pencil-square-o"></i> <?php echo element("member_name",$val)?> -->
		</td>
	</tr>
<?php } ?>
