<?php 
if(count($installation)<1){
?>
<tr><td class="text-center" colspan='8'><?php echo lang("msg.nodata");?> </td></tr>
<?php
}
foreach($installation as $val){
?>
<tr>
	<td style="vertical-align:middle;padding-left:10px;">
		<?php if($this->session->userdata("auth_id")=="1" || $this->session->userdata("admin_id")==element("member_id",$val)){?>
		<input type="checkbox" class="checkbox checkboxes" name="check_installation[]" value="<?php echo element("id",$val)?>" self-data="on"/>
		<?php } else {?>
		<input type="checkbox" class="checkbox checkboxes" name="check_installation[]" value="<?php echo element("id",$val)?>" disabled/>
		<?php }?>
	</td>
	<td>
		<div class="gallery_wrapper">
			<a href="<?php echo "/admininstallation/view/".element("id",$val)?>">
			<?php if( element("thumb_name",$val) == "" ) {?>
				<img src="/assets/common/img/no_thumb.png" width="100%"/>
			<?php } else { ?>
				<img class="img-responsive" src="/photo/gallery_installation_thumb/<?php echo element("gallery_id",$val);?>"  style="height:80px;"></a>
			<?php }?>
			</a>
		</div>
	</td>
	<td>
		<a href="<?php echo "/admininstallation/view/".element("id",$val)?>"><strong><?php echo element("id",$val)?></strong></a><br/>
		<?php echo lang("installation.category.".element("category",$val));?>
		<p>
			<?php if(element("status",$val)=="plan")	echo "계획"; ?>
			<?php if(element("status",$val)=="go")		echo "진행중"; ?>
			<?php if(element("status",$val)=="end")	echo "종료"; ?>
		</p>
					
	</td>
	<td>
		<!-- 제목 및 주소, 가격 등 -->
		<?php if( element("is_activated",$val)=="1")  { ?><span class="label label-sm label-primary">공개</span><?php } else {?><span class="label label-sm label-danger">비공개</span><?php }?>
		<?php if( element("recommand",$val)=="1")  { ?><span class="label label-sm label-success">추천</span><?php }?>
		<a href="<?php echo "/admininstallation/view/".element("id",$val)?>"><b><?php echo element("title",$val)?></b></a>
		<p><small><?php echo element("address_name",$val)?> <?php echo element("address",$val)?> <?php echo element("address_unit",$val)?></p>
		<p><?php echo element("scale",$val);?></p>
		<!-- 비밀메모, 집주인명, 연락처 -->
		<?php if(element("secret",$val)!="") {?><p class="text-danger" style="margin-top:5px;"><i class="fa fa-lock"></i> <?php echo element("secret",$val)?></p><?php }?>
	</td>
	<td class="hidden-xs">
		<?php if(element("notice_year",$val)!="") echo "공고: " . element("notice_year",$val) . "<br/>"?>
		<?php if(element("enter_year",$val)!="") echo "입주: " . element("enter_year",$val)?>
	</td>
	<td class="hidden-xs">
		<button type="button" type="button" class="btn btn-link btn-sm" onclick="blog('<?php echo element("id",$val)?>');"><i class="fa fa-share-alt"></i> 블로그(<?php echo element("is_blog",$val)?>)</button>
		<?php if($config->navercskey && $config->navercssecret && $config->naverclientkey && $config->naverclientsecret ){?>
		<button type="button" type="button" class="btn btn-link btn-sm" onclick="cafe('<?php echo element("id",$val)?>');"><i class="fa fa-share-alt"></i> N카페(<?php echo element("is_cafe",$val)?>)</button>
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
					<a href="/admininstallation/edit/<?php echo element("id",$val);?>">수정</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="/admininstallation/copy/<?php echo element("id",$val);?>/list">복사</a>
				</li>
				<li>
					<a href="/admininstallation/refresh/<?php echo element("id",$val);?>/list"><?php echo lang("site.refresh");?></a>
				</li>
				<li>
				<?php if(element("is_activated",$val)=="1"){?>
					<a href="#" onclick="change('is_activated','<?php echo element("id",$val);?>','0');">비공개하기</a>
				<?php } else {	?>
					<a href="#" onclick="change('is_activated','<?php echo element("id",$val);?>','1');">공개하기</a>
				<?php }?>
				</li>
				<li>
				<?php if(element("recommand",$val)=="1"){?>
					<a href="#" onclick="change('recommand','<?php echo element("id",$val);?>','0');">추천해제하기</a>
				<?php } else {	?>
					<a href="#" onclick="change('recommand','<?php echo element("id",$val);?>','1');">추천하기</a>
				<?php }?>
				</li>
				<li class="divider"></li>
				<li>
					<a href="#" onclick="delete_installation('<?php echo element("id",$val);?>');"><?php echo lang("site.delete");?></a>
				</li>
			</ul>
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
