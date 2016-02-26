<?php 
if(count($product)<1){
?>
<tr><td class="text-center" colspan='9'><?php echo lang("msg.nodata");?> </td></tr>
<?php
}
foreach($product as $val){
?>
<tr>
	<td style="vertical-align:middle;padding-left:10px;">
		<?php if($this->session->userdata("auth_id")=="1" || $this->session->userdata("admin_id")==element("member_id",$val)){?>
		<input type="checkbox" class="checkbox checkboxes" name="check_product[]" value="<?php echo element("id",$val)?>" self-data="on"/>
		<?php } else {?>
		<input type="checkbox" class="checkbox checkboxes" name="check_product[]" value="<?php echo element("id",$val)?>" disabled/>
		<?php }?>
	</td>
	<td>
		<!-- 썸네일 -->
		<?php
		$cnt = count(element("gallery_thumb",$val));
		if($cnt > 1){
			if($cnt==2) $width = "122px";
			if($cnt==3) $width = "237px";
			if($cnt==4) $width = "352px";
			if($cnt==5) $width = "466px";
		}
		?>
		<div class="gallery_wrapper">
			<a href="<?php echo "/adminproduct/view/".element("id",$val)?>">
			<?php if( element("thumb_name",$val) == "" ) {?>
				<img src="/assets/common/img/no_thumb.png" width="100%"/>
			<?php } else { ?>
					<img class="img-responsive" src="/photo/gallery_thumb/<?php echo element("gallery_id",$val);?>"  style="height:80px;"/>
					<?php if($cnt > 1){?>
					<div class="thumb_button" onmouseover="thumb_display(this,'show');" onmouseout="thumb_display(this,'hide');">
						<img src="/assets/common/img/plus.png"/>
					</div>
					<?php }?>
			<?php }?>
			</a>
			<?php if($cnt > 1){?>
			<div class="gallery_thumb" style="width:<?php echo $width;?>;" onmouseover="thumb_display(this,'show');" onmouseout="thumb_display(this,'hide');">
				<ul>
					<?php
					foreach(element("gallery_thumb",$val) as $key=>$gallery_thumb){
						if($key==0) continue;
					?>
					<li class="inline">
						<a href="<?php echo "/adminproduct/view/".element("id",$val)?>">
							<img src="/photo/gallery_thumb/<?php echo $gallery_thumb->id;?>"/>
						</a>
					</li>
					<?php }?>
				</ul>				
			</div>
			<?php }?>
		</div>
	</td>
	<td>
		<!-- 썸네일 -->
		<?php
		$cnt = count(element("gallery_thumb_admin",$val));
		if($cnt > 1){
			if($cnt==2) $width = "122px";
			if($cnt==3) $width = "237px";
			if($cnt==4) $width = "352px";
			if($cnt==5) $width = "466px";
		}
		?>
		<div class="gallery_wrapper">
			<?php if(isset(element("gallery_thumb_admin",$val)[0])){?>
				<a href="<?php echo "/adminproduct/view/".element("id",$val)?>">
					<img class="img-responsive" src="/photo/gallery_thumb/<?php echo element("gallery_thumb_admin",$val)[0]->id;?>/_admin"  style="height:80px;"/>
					<?php if($cnt > 1){?>
					<div class="thumb_button" onmouseover="thumb_display(this,'show');" onmouseout="thumb_display(this,'hide');">
						<img src="/assets/common/img/plus.png"/>
					</div>
					<?php }?>
				</a>
			<?php } else {?>
				<img src="/assets/common/img/no_thumb.png" width="100%"/>			
			<?php }?>
			<?php if($cnt > 1){?>
			<div class="gallery_thumb" style="width:<?php echo $width;?>;" onmouseover="thumb_display(this,'show');" onmouseout="thumb_display(this,'hide');">
				<ul>
					<?php
					foreach(element("gallery_thumb_admin",$val) as $key=>$gallery_thumb_admin){
						if($key==0) continue;
					?>
					<li class="inline">
						<a href="<?php echo "/adminproduct/view/".element("id",$val)?>">
							<img src="/photo/gallery_thumb/<?php echo $gallery_thumb_admin->id;?>/_admin"/>
						</a>
					</li>
					<?php }?>
				</ul>				
			</div>
			<?php }?>
		</div>
	</td>
	<td>
		<!-- 기본정보 -->
		<a href="<?php echo "/adminproduct/view/".element("id",$val)?>">
			<strong><?php echo element("id",$val)?></strong>
		</a><br/>
		<?php echo element("name",$val)?><br/>
		<?php if( element("part",$val)=="N") {?>
			<i class="fa fa-building help"  data-toggle="tooltip" title="전체 거래"></i>
	 	<?php }?>
		<?php if( element("type",$val)=="sell")  echo lang("sell"); ?>
		<?php if( element("type",$val)=="installation")  echo lang("installation"); ?>
		<?php if( element("type",$val)=="full_rent")  echo lang("full_rent"); ?>
		<?php if( element("type",$val)=="monthly_rent")  echo lang("monthly_rent"); ?>
		<?php if( element("type",$val)=="rent")  echo lang("rent"); ?>
	</td>
	<td>
		<!-- 제목 및 주소, 가격 등 -->
		<?php if( element("is_activated",$val)=="1")  { ?><span class="label label-sm label-primary">공개</span><?php } else {?><span class="label label-sm label-danger">비공개</span><?php }?>
		<?php if( element("recommand",$val)=="1")  { ?><span class="label label-sm label-success">추천</span><?php }?>
		<?php if( element("is_finished",$val)=="1")  { ?><span class="label label-sm label-default">완료</span><?php }?>
		<?php if( element("is_speed",$val)=="1")  { ?><span class="label label-sm label-warning">급매</span><?php }?>
		<?php if( element("is_defer",$val)=="1")  { ?><span class="label label-sm label-info"><?php echo (element("type",$val)=="installation")?"분양":"계약"?>보류</span><?php }?>
		<a href="<?php echo "/adminproduct/view/".element("id",$val)?>"><b><?php echo element("title",$val)?></b></a>
		<br><small><?php echo element("address_name",$val)?> <?php echo element("address",$val)?> <?php echo element("address_unit",$val)?></small>
		<br><?php echo price($val,$config);?>
		<br><?php echo get_profile( element("contact",$val) );?>
		
		<!-- 비밀메모, 집주인명, 연락처 -->
		<?php if(element("secret",$val)!="") {?><br/><span class="text-danger" style="margin-top:5px;"><i class="fa fa-lock"></i> <?php echo element("secret",$val)?></span><?php }?>
		<?php if(element("owner_name",$val)!="" && element("owner_name",$val)!="0") {?><span class="text-info" style="margin-top:5px;"><i class="fa fa-user"></i> <?php echo element("owner_name",$val)?> <?php echo element("owner_phone",$val)?></span><?php }?>
		<?php if(element("last_check_date",$val)!="") {?><br/><span style="margin-top:5px;"><i class="fa fa-check"></i> 최종 매물확인일자 :  <?php echo element("last_check_date",$val)?> <a href="#" onclick="get_check_list(<?php echo element("id",$val)?>)" data-toggle="modal" data-target="#check_log">전체 확인기록보기</a></span><?php }?>
	</td>
	<td class="hidden-xs">

		<?php if(element("real_area",$val)!=0 || element("law_area",$val)!=0) {?>
			<div><img src="/assets/common/img/surface.png"> <?php echo area_admin(element("real_area",$val),"");?>/<?php echo area_admin(element("law_area",$val),"");?> </div>
		<?php }?>

		<?php if(element("real_area",$val)!=0 || element("law_area",$val)!=0) {?>
			<div><img src="/assets/common/img/surface.png"> <?php echo area_admin(element("real_area",$val),"",true);?>/<?php echo area_admin(element("law_area",$val),"",true);?> </div>
		<?php }?>

		<?php if(element("bedcnt",$val)!="") {?>
			<?php if( element("part",$val)=="Y") {?>
			<div><img src="/assets/common/img/bed.png"> <?php echo element("bedcnt",$val)?>/<?php echo element("bathcnt",$val)?> </div>
			<?php }?>
		<?php }?>

		<?php if(element("store_category",$val)!="")	echo "<p>" . element("store_category",$val) . "</p>";?>
		<?php if(element("store_name",$val)!="")		echo "<p>" . element("store_name",$val) . "</p>";?>

		<?php if(element("current_floor",$val)!=0){?>
			<div><img src="/assets/common/img/floor.png"> <?php echo element("current_floor",$val)?>/<?php echo element("total_floor",$val)?> </div>
		<?php } ?>

		<?php if(element("gongsil_status",$val)!="")	echo "<p>" . element("gongsil_status",$val) . "</p>";?>
		<?php if(element("gongsil_see",$val)!="")	echo "<p>" . element("gongsil_see",$val) . "</p>";?>
		<?php if(element("gongsil_contact",$val)!="")	echo "<p>" . multi_view(element("gongsil_contact",$val),"gongsil") . "</p>";?>

	</td>
	<td class="hidden-xs">
		<button type="button" type="button" class="btn btn-link btn-sm" onclick="blog('<?php echo element("id",$val)?>');"><i class="fa fa-share-alt"></i> 블로그(<?php echo element("is_blog",$val)?>)</button>
		<?php if($config->navercskey && $config->navercssecret && $config->naverclientkey && $config->naverclientsecret ){?>
		<button type="button" type="button" class="btn btn-link btn-sm" onclick="cafe('<?php echo element("id",$val)?>');"><i class="fa fa-share-alt"></i> N카페(<?php echo element("is_cafe",$val)?>)</button>
		<?php }?>
		<?php if($config->daumclientkey && $config->daumclientsecret ){?>
		<button type="button" type="button" class="btn btn-link btn-sm" onclick="daum_blog('<?php echo element("id",$val)?>');"><i class="fa fa-share-alt"></i> 다음블로그(<?php echo element("is_blog_daum",$val)?>)</button>
		<?php }?>
	</td>
	<td class="hidden-xs">
		
		<?php if($this->session->userdata("auth_id")=="1" || $this->session->userdata("admin_id")==element("member_id",$val)){?>
		<div class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
				<i class="icon-settings"></i> 실행
			</a>
			<ul class="dropdown-menu dropdown-menu-default">
				<li>
					<a href="/adminproduct/edit/<?php echo element("id",$val);?>">수정</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="/adminproduct/check_product/<?php echo element("id",$val);?>/list"><?php echo lang("product")?>확인하기</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="/adminproduct/copy/<?php echo element("id",$val);?>/list">복사</a>
				</li>
				<li>
					<a href="/adminproduct/refresh/<?php echo element("id",$val);?>/list"><?php echo lang("site.refresh");?></a>
				</li>
				<li>
				<?php if(element("is_valid",$val)=="1"){?>
					<a href="javascript:change('is_valid','<?php echo element("id",$val);?>','0');">비승인하기</a>
				<?php } else {	?>
					<a href="javascript:change('is_valid','<?php echo element("id",$val);?>','1');">승인하기</a>
				<?php }?>
				</li>
				<li>
				<?php if(element("is_activated",$val)=="1"){?>
					<a href="javascript:change('is_activated','<?php echo element("id",$val);?>','0');">비공개하기</a>
				<?php } else {	?>
					<a href="javascript:change('is_activated','<?php echo element("id",$val);?>','1');">공개하기</a>
				<?php }?>
				</li>
				<li>
				<?php if(element("recommand",$val)=="1"){?>
					<a href="javascript:change('recommand','<?php echo element("id",$val);?>','0');">추천해제하기</a>
				<?php } else {	?>
					<a href="javascript:change('recommand','<?php echo element("id",$val);?>','1');">추천하기</a>
				<?php }?>
				</li>
				<li>
				<?php if(element("is_finished",$val)=="1"){?>
					<a href="javascript:change('is_finished','<?php echo element("id",$val);?>','0');"><?php echo (element("type",$val)=="installation") ? "분양" : "계약"?>완료처리 취소하기</a>
				<?php } else {	?>
					<a href="javascript:change('is_finished','<?php echo element("id",$val);?>','1');"><?php echo (element("type",$val)=="installation") ? "분양" : "계약"?>완료 처리하기</a>
				<?php }?>
				</li>
				<li>
				<?php if(element("is_speed",$val)=="1"){?>
					<a href="javascript:change('is_speed','<?php echo element("id",$val);?>','0');">급매로 설정 취소하기</a>
				<?php } else {	?>
					<a href="javascript:change('is_speed','<?php echo element("id",$val);?>','1');">급매로 설정하기</a>
				<?php }?>
				</li>
				<li>
				<?php if(element("is_defer",$val)=="1"){?>
					<a href="javascript:change('is_defer','<?php echo element("id",$val);?>','0');"><?php echo (element("type",$val)=="installation") ? "분양" : "계약"?>보류 설정 취소하기</a>
				<?php } else {	?>
					<a href="javascript:change('is_defer','<?php echo element("id",$val);?>','1');"><?php echo (element("type",$val)=="installation") ? "분양" : "계약"?>보류로 설정하기</a>
				<?php }?>
				</li>
				<li class="divider"></li>
				<li>
					<a href="javascript:delete_product('<?php echo element("id",$val);?>');"><?php echo lang("site.delete");?></a>
				</li>
			</ul>
		</div>
		<?php }?>

		<?php if($this->session->userdata("auth_id")=="1" || $this->session->userdata("admin_id")==element("member_id",$val)){?>
		<div>
			<a href="#" onclick="memo_view('<?php echo element("id",$val);?>','<?php echo element("title",$val)?>');" data-toggle="modal" data-target="#memo_dialog"><i class="icon-speech"></i> 메모(<?php echo element("memo_count",$val)?>)</a>
		</div>
		<?php }?>

	</td>
	<td class="hidden-xs">
		<small><?php echo element("date",$val)?></small><br/>
		<i class="fa fa-pencil-square-o"></i> <?php echo element("member_name",$val)?><br/>조회 <span class='badge'><?php echo element("viewcnt",$val)?></span>
	</td>
</tr>

<?php 
	}
?>
