<?php
	$price_detail = 'style="font-size:24px;color:#FF2200;font-family:helvetica;"';
	$subway_css = "white-space:break-all;font-family:dotum;margin-left:3px;position:relative;border-radius:10px;display: inline;color:white;font-size:10.5px;padding:3px 6px 3px 4px; box-shadow: 0px 2px 2px rgba(0,0,0,0.1);text-shadow: 0 0 4px rgba(0,0,0,0.8);";
?>
<br/>

<div style="border:1px dashed #cacaca;padding:10px;"><center><b>오늘의 좋은 글 1 </b><br><?php echo $proverb1->code?></center></div>
<br/>


<?php 

$css1 = "background-color:#f4f4f4;padding:5px;color:#222;border-collapse:collapse;border:1px solid #dddddd;";

foreach($gallery as $key=>$val){
	if($key==0){?>
<a href="http://<?php echo HOST;?>/product/view/<?php echo $query->id;?>" target="_blank"><img  src="http://<?php echo HOST;?>/photo/gallery_image/<?php echo $val->id;?>" style="max-width:100%"></a><br/><br/>
<div style="font-family:Malgun Gothic;font-size:14px;text-align:left;"><?php echo $val->content;?></div><br/>
<?php }}?>

	  <?php if( $query->video_url != "" ){
		preg_match(
		    '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/',
			$query->video_url,
	        $matches
		);
		$id  = $matches[2];
		echo "<br><iframe src=\"https://www.youtube.com/embed/".$id."\" frameborder=\"0\" allowfullscreen style='width:350px;height:220px;'></iframe><br>";
	  }?>

<div style="font-size:18px;color: #555555;font-weight: bold;padding-left: 5px;padding-top:10px;"><?php echo lang("site.price");?> / <?php echo lang("site.address");?></div>
<table width="100%" border="0" style="border: 1px solid #dddddd;border-spacing:0;font-family:dotum;font-size:14px;">
	<?php if($query->part=="N") {?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>">거래범위</th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;">
			<?php echo lang("site.all");?>	
		</td>
	</tr>
	<?php }?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>"><?php echo lang("site.price");?></th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;font-weight:700;font-size:15px;">
					<?php echo price($query,$config);?><?php if(price_description($query)!=""){?><div style="color:#454545;"><?php echo price_description($query);?></div><?php }?>
		</td>
	</tr>
	<?php if($query->mgr_price!=false) {?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>"><?php echo lang("product.mgr_price");?></th>
		<td width="80%" style="border:1px solid #dddddd;padding:5px;" colspan="3">
			<?php 

				if($query->mgr_price!="0" && $query->mgr_price!="") {
					if(is_numeric($query->mgr_price)){
						echo number_format($query->mgr_price) . lang("price_unit");
					} else {
						echo $query->mgr_price;
					}
				}

			?>
			<?php if($query->mgr_include!=""){?>(<?php echo lang("product.mgr_include");?> : <?php echo $query->mgr_include;?>)<?php } ?>
		</td>
		<!--th width="20%" style="<?php echo $css1;?>">주차비</th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">
			<?php if($query->park_price!="0") echo number_format($query->park_price) . lang("price_unit");?>
		</td-->
	</tr>
	<?php }?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>">주소</th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;">
			<?php 
				echo toeng($query->address_name);

				//블로그 포스팅에서는 주소 공개 설정에 관계 없이 상세 주소가 보이지 않는다. 2015.9.30
			?>
		</td>
	</tr>
	<tr <?php if($config->SUBWAY=="0") echo 'style="display:none;"';?>>
		<th width="20%" style="<?php echo $css1;?>"><?php echo lang("site.subway");?></th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;">
			<?php foreach($product_subway as $sub){
				?>
					[<?php echo $sub->hosun?> 호선]<?php echo $sub->name?> <?php echo round($sub->distance,1)?> ㎞
				<?php
			}?>
		</td>
	</tr>	
</table>
<?php if($query->part=="N") {?>
<div style="font-size:18px;color: #555555;font-weight: bold;padding-left: 5px;padding-top:10px;">대지(토지) 설명</div>
<table width="100%" border="0" style="border: 1px solid #dddddd;border-spacing:0;font-family:dotum;font-size:14px;">
	<tr>
		<th width="20%" style="<?php echo $css1;?>">대지면적</th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">
			<?php echo area_view($query->land_area, "");?>
		</td>
		<th width="20%">도로면적</th>
		<td width="30%">
			<?php echo area_view($query->road_area, "");?>
		</td>
	</tr>
	<tr>
		<th width="20%" style="<?php echo $css1;?>">총면적</th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;">
			<?php echo area_view($query->land_area+$query->road_area, "");?>
			<?php echo price_land_area($query);?>
		</td>
	</tr>							
	<?php if($query->ground_use!="" || $query->ground_aim!="") {?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>">용도지역</th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">
			<?php echo $query->ground_use;?>
		</td>
		<th width="20%" style="<?php echo $css1;?>">지목</th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">
			<?php echo $query->ground_aim;?>
		</td>								
	</tr>
	<?php } ?>
</table>
<?php }?>

<br/>
<div style="border:1px dashed #cacaca;padding:10px;"><center><b>오늘의 좋은 글 2 </b><br><?php echo $proverb2->code?></center></div>
<br/>

<div style="font-size:18px;color: #555555;font-weight: bold;padding-left: 5px;padding-top:10px;"><?php echo lang("product");?> 정보</div>
<table width="100%" border="0" style="border: 1px solid #dddddd;border-spacing:0;font-family:dotum;font-size:14px;">
	<tr>
		<th width="20%" style="<?php echo $css1;?>"><?php echo lang("product");?></th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;">
			[<?php 
				echo $query->id;
			?>] 
			<?php 
				echo $query->title;
			?>
		</td>
	</tr>						
	<tr>
		<th width="20%" style="<?php echo $css1;?>"><?php echo lang("product.category");?></th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;">
			<?php foreach($category as $val){?>
				<?php if($val->id==$query->category){ echo $val->name;}?>
			<?php }?>									
		</td>
	</tr>
	<tr>
		<th width="20%" style="<?php echo $css1;?>"><?php if($query->part=="Y") {echo lang("product.realarea");} else {echo "건축면적";}?></th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">
			<?php 
				echo area_view($query->real_area, "");
			?>
		</td>
		<th width="20%" style="<?php echo $css1;?>"><?php if($query->part=="Y") {echo lang("product.lawarea");} else {echo "연면적";}?></th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">
			<?php 
				echo area_view($query->law_area, "");
				echo price_product_area($query);
			?>
		</td>
	</tr>								
	<?php if(!$config->USE_FACTORY){?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>"><?php echo lang("product.floor");?></th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;">
			<?php if($query->current_floor!=0){ echo lang("product.floor.current.".$query->part) . $query->current_floor . lang("product.f"); } ?>
			<?php if($query->total_floor!=0){echo lang("product.floor.total.".$query->part) . $query->total_floor . lang("product.f"); }?>
		</td>
	</tr>
	<?php }?>
	<?php if($query->part=="Y") {?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>"><?php echo lang("product.roomcnt");?></th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;">
			<?php if($query->bedcnt!="0") echo lang("product.bedcnt") . $query->bedcnt . "실 ";?>
			<?php if($query->bathcnt!="0") echo lang("product.bathcnt") . $query->bathcnt . "실 ";?>
		</td>
	</tr>
	<?php }?>
	<?php if($query->enter_year!=""){?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>"><?php echo lang("product.enter_year");?></th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;"><?php echo $query->enter_year;?></td>
	</tr>
	<?php }?>
	<?php if($query->build_year!=""){?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>"><?php echo lang("product.build_year");?></th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;"><?php echo $query->build_year;?></td>
	</tr>
	<?php }?>
	<?php if($category_one->template!=""){?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>"><?php echo lang("site.option");?></th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;">
			<?php 
			if($config->OPTION_FLAG=="1"){
				$cate = explode(",",$category_one->template);
				
				foreach($cate as $key=>$val){

					if (strpos($query->option,$val) !== false) {
						echo ' <font style="color:black;margin-right:20px;font-weight:400;"><img src="/assets/common/img/option_check.png"> ' . $val . "</font>";
					} else {
						// echo ' <font style="color:#cacaca;margin-right:20px;"><img src="/assets/common/img/option_none.png"> ' . $val . "</font>";
					}

				}
			} else {
					echo $query->option;
			}
			?>
		</td>
	</tr>
	<?php }?>
	<?php 
	$cate = explode(",",$category_one->meta);
	$etc  = explode("--dungzi--", $query->etc);
	foreach($cate as $key=>$val){
		if($val!=""){
			if(isset($etc[$key])){
				if(strpos($etc[$key],"http://") !== false || strpos($etc[$key],"https://") !== false){
					$etc[$key] = '<a href="'.$etc[$key].'" target="_blank">'.$etc[$key].'</a>';
				}
				if(strpos($etc[$key],"www.") !== false){
					$etc[$key] = '<a href="http://'.$etc[$key].'" target="_blank">'.$etc[$key].'</a>';
				}
			}
		?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>"><?php echo $val;?></th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;"><?php if(isset($etc[$key])){ echo $etc[$key]; }?></td>
	</tr>
	<?php }
		}	?>
<?php if($config->USE_FACTORY){?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>">층고</th>
		<td width="80%" colspan="3" style="border:1px solid #dddddd;padding:5px;">
			<?php if($query->current_floor!=0){ echo $query->current_floor . " m "; } ?>
		</td>
	</tr>							
	<tr>
		<th width="20%" style="<?php echo $css1;?>">도로조건</th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">
			<?php echo $query->road_conditions?> 
		</td>
		<th width="20%" style="<?php echo $css1;?>">용도</th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">
			<?php echo $query->factory_use?> 
		</td>								
	</tr>
	<tr>
		<th width="20%" style="<?php echo $css1;?>">전기</th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">
			<?php echo $query->factory_power?> 
		</td>
		<th width="20%" style="<?php echo $css1;?>">호이스트</th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">
			<?php echo $query->factory_hoist?> 
		</td>								
	</tr>
<?php }?>
	<tr>
		<th width="20%" style="<?php echo $css1;?>">
			<?php 
				if( $member->biz_name!="" ) {
					echo $member->biz_name;
				} else {
					echo lang("product.owner");
				}?>
		</th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">					
			<b><?php echo $member->name;?></b>
		</td>
		<th width="20%" style="<?php echo $css1;?>"><?php echo lang("site.contact");?></th>
		<td width="30%" style="border:1px solid #dddddd;padding:5px;">					
			<a href="tel:<?php echo $member->phone;?>"><?php echo $member->phone;?></a>
		</td>
	</tr>
</table>

<br/>
<div style="border:1px dashed #cacaca;padding:10px;"><center><b>오늘의 좋은 글 3 </b><br><?php echo $proverb3->code?></center></div>
<br/>

<br/>

<div style="font-family:dotum;font-size:14px;">
<?php
	echo blog_content_replace($query->content.$member->sign,false);
?>
</div>
<br/><br/>

<div style="font-size:18px;color: #555555;font-weight: bold;padding-left: 5px;padding-top:10px;"><?php echo lang("site.photo");?></div>
<center>
<?php 
shuffle($gallery);
foreach($gallery as $key=>$val){
	if($key>0){?>
<a href="http://<?php echo HOST;?>/product/view/<?php echo $query->id;?>" target="_blank"><img src="http://<?php echo HOST;?>/photo/gallery_image/<?php echo $val->id;?>" style="max-width:100%"></a><br/><br/>
<div style="font-family:Malgun Gothic;font-size:14px;text-align:center;"><?php echo $val->content;?></div><br/>
<?php } }?>

<?php if($query->panorama_url){?>
<div style="margin-bottom:10px;text-align:center">
	<a href="http://<?php echo str_replace("http://","",$query->panorama_url)?>" target="_blank"><img src="http://<?php echo HOST;?>/assets/common/img/vr.png"/></a>
</div>
<?php }?>

<?php if($this->config->item('view_map_use')) {?>
<div style="font-size:18px;color: #555555;font-weight: bold;padding-left: 5px;padding-top:10px;"><?php echo lang("product");?> 위치</div>
<img src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo $query->lat;?>,<?php echo $query->lng;?>&zoom=15&size=650x300&markers=color:red%7C<?php echo $query->lat;?>,<?php echo $query->lng;?>&language=ko" style="border:1px solid #cacaca;">
<?php } ?>
<br><br>
</center>

<div style="border:1px dashed #cacaca;padding:10px;"><center><b>오늘의 속담</b><br><?php echo $statement->code?></center></div>


<!--
<div style="font-size:18px;color: #555555;font-weight: bold;padding-left: 5px;padding-top:10px;">인근 <?php echo lang("product");?></div>

<div style="border:1px dashed #cacaca;background-color:#f6f6f6;">
<?php 
foreach($recent as $val){
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100" style="border-top:0; padding:10px;">
			<a href="http://<?php echo HOST;?>/product/view/<?php echo $val["id"]?>" target="_blank">
			<?php if($val["thumb_name"]==""){?>
				<img src="http://<?php echo HOST;?>/assets/common/img/no_thumb.png" width="100"/>
			<?php } else {?>
				<img src="http://<?php echo HOST;?>/photo/gallery_thumb/<?php echo $val["gallery_id"]?>" width="100"/>
			<?php }?>
			</a>
		</td>
		<td style="padding:10px;">
			<?php echo price($val,$config);?>			
		</td>
	</tr>
</table>
<?php }?>
</div>
-->

<br><br>
<center>
<b><?php echo $youtube->title?></b>
<br/>
<iframe width="350" height="220" src="https://www.youtube.com/embed/<?php echo $youtube->code?>?&autoplay=0" frameborder="0" allowfullscreen></iframe>
</center>