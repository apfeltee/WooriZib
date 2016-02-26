<div style='border:1px dashed #cacaca;padding:10px;'><center><b>오늘의 좋은 글 1 </b><br><?php echo $proverb1->code?></center></div>
<br/>

<?php
$css1 = "background-color:#f4f4f4;padding:5px;color:#222;border-collapse:collapse;border:1px solid #dddddd;";

foreach($gallery as $key=>$gallery_row){
	if($key==0){
?>
<a href='http://<?php echo HOST;?>/installation/view/<?php echo $query->id;?>' target='_blank'><img  src='http://<?php echo HOST;?>/uploads/gallery_installation/<?php echo $query->id;?>/<?php echo $gallery_row->filename;?>' style='max-width:100%'></a><br/><br/>
<div style='font-family:Malgun Gothic;font-size:14px;text-align:left;'><?php echo $gallery_row->content;?></div><br/>
<?php 
	}
}
?>
<div style='font-size:18px;color: #555555;font-weight: bold;'><?php echo lang("site.price");?> / <?php echo lang("site.address");?></div>
<table width='100%' border='0' style='border: 1px solid #dddddd;border-spacing:0;font-family:dotum;font-size:14px;'>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'>주소</th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php 
				echo toeng($query->address_name);
				//블로그 포스팅에서는 주소 공개 설정에 관계 없이 상세 주소가 보이지 않는다. 2015.9.30
			?>
		</td>
	</tr>
	<?php if(count($installation_subway)){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'><?php echo lang("site.subway");?></th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php foreach($installation_subway as $sub){
				?>
					[<?php echo $sub->hosun?> 호선]<?php echo $sub->name?> <?php echo round($sub->distance,1)?> ㎞
				<?php
			}?>
		</td>
	</tr>
	<?php }?>	
</table>

<br/>
<div style='border:1px dashed #cacaca;padding:10px;'><center><b>오늘의 좋은 글 2 </b><br><?php echo $proverb2->code?></center></div>
<br/>

<div style='font-size:18px;color: #555555;font-weight: bold;padding-left: 5px;padding-top:30px;'><?php echo lang("installation");?> 정보</div>
<table width='100%' border='0' style='border: 1px solid #dddddd;border-spacing:0;font-family:dotum;font-size:14px;'>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'><?php echo lang("installation");?></th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			[<?php 
				echo $query->id;
			?>] 
			<?php 
				echo $query->title;
			?>
		</td>
	</tr>						
	<?php if($query->category!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'><?php echo lang("installation");?> 종류</th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php if($query->category=="apt") echo "아파트";?>
			<?php if($query->category=="villa") echo "빌라";?>
			<?php if($query->category=="officetel") echo "오피스텔";?>
			<?php if($query->category=="city") echo "도시형생활주택";?>
			<?php if($query->category=="shop") echo "상가";?>		
		</td>
	</tr>
	<?php }?>
	<?php if($query->status!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'><?php echo lang("installation");?> <?php echo lang("site.status");?></th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php if($query->status=="plan") echo lang("installation")." 계획중";?>
			<?php if($query->status=="go") echo lang("installation")." 진행중";?>
			<?php if($query->status=="end") echo lang("installation")." 종료";?>		
		</td>
	</tr>
	<?php }?>
	<?php if($query->scale!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'>규모</th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php echo $query->scale;?>									
		</td>
	</tr>
	<?php }?>
	<?php if($query->notice_year!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'>공고시기</th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php echo $query->notice_year;?>	
		</td>
	</tr>
	<?php }?>
	<?php if($query->enter_year!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'>입주시기</th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php echo $query->enter_year;?>	
		</td>
	</tr>
	<?php }?>
	<?php if($query->tel!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'><?php echo lang("site.contact");?></th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php echo $query->tel;?>									
		</td>
	</tr>
	<?php }?>
	<?php if($query->builder!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'>건설사</th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php echo $query->builder;?>	
		</td>
	</tr>
	<?php }?>
	<?php if($query->builder_url!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'>건설사 홈페이지</th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php echo $query->builder_url;?>	
		</td>
	</tr>
	<?php }?>
	<?php if($query->heating!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'>난방</th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php echo $query->heating;?>									
		</td>
	</tr>
	<?php }?>

	<?php if($query->park!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'>주차</th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php echo $query->park;?>									
		</td>
	</tr>
	<?php }?>
	<?php if($query->bank!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'>청약가능통장</th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php echo $query->bank;?>									
		</td>
	</tr>
	<?php }?>

	<?php if($query->is_presale!=""){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'>전매가능여부</th>
		<td width='80%' colspan='3' style='border:1px solid #dddddd;padding:5px;'>
			<?php if($query->is_presale=="1") echo "전매가능";?>
			<?php if($query->is_presale=="0") echo "전매제한";?>										
		</td>
	</tr>
	<?php }?>	
	<tr>
		<th width='20%' style='<?php echo $css1;?>'>
			<?php 
				if( $member->biz_name!="" ) {
					echo $member->biz_name;
				} else {
					echo lang("product.owner");
				}?>
		</th>
		<td width='30%' style='border:1px solid #dddddd;padding:5px;'>					
			<b><?php echo $member->name;?></b>
		</td>
		<th width='20%' style='<?php echo $css1;?>'><?php echo lang("site.contact");?></th>
		<td width='30%' style='border:1px solid #dddddd;padding:5px;'>					
			<a href='tel:<?php echo $member->phone;?>'><?php echo $member->phone;?></a>
		</td>
	</tr>
</table>

<?php if(count($schedule)){?>
<br/>
<div style='font-size:18px;color: #555555;font-weight: bold;padding-left: 5px;padding-top:10px;'><?php echo lang("installation");?> 일정</div>
<table width='100%' border='0' style='border: 1px solid #dddddd;border-spacing:0;font-family:dotum;font-size:14px;'>
	<?php foreach($schedule as $val){?>
	<tr>
		<th width='20%' style='<?php echo $css1;?>'><?php echo $val->name;?></th>
		<td width='20%' style='border:1px solid #dddddd;padding:5px;'>
			<?php echo $val->date;?>
		</td>
		<td width='60%' style='border:1px solid #dddddd;padding:5px;'>
			<?php echo $val->description;?>
		</td>
	</tr>
	<?php }?>
</table>
<?php }?>

<?php if(count($pyeong)){?>
<br/>
<div style='font-size:18px;color: #555555;font-weight: bold;padding-left: 5px;padding-top:10px;'>평형정보</div>
	<?php 
		foreach($pyeong as $val){
		$temp = explode(".",$val->filename);
	?>
	<div style='margin-bottom:20px;'>
		<div style='display:inline;float:left;text-align:center;'>
			<img src='/uploads/pyeong/<?php echo $query->id?>/<?php echo $temp[0].'_thumb.'.$temp[1];?>' width='350'>
		</div>
		<div style='display:inline;float:left;width:40%'>
			<table style='border: 1px solid #dddddd;border-spacing:0;font-family:dotum;font-size:14px;width:382px;margin-left:10px;'>
				<colgroup>
					<col width='30%'/>
					<col width='70%'/>
				</colgroup>
				<tr>
					<td colspan='2' style='text-align:center;font-weight:bold;font-size:16px;;border:1px solid #dddddd;padding:5px;'><?php echo $val->name;?>㎡</td>
				</tr>
				<tr>
					<th width='30%'>분양세대수</th>
					<td width='70%' style='text-align:right;border:1px solid #dddddd;padding:5px;'><?php echo $val->cnt;?> 세대</td>
				</tr>
				<tr>
					<th>분양가</th>
					<td style='text-align:right;border:1px solid #dddddd;padding:5px;'><?php echo $val->price_min;?> ~ <?php echo $val->price_max;?>만원</td>
				</tr>
				<tr>
					<th>취득세</th>
					<td style='text-align:right;border:1px solid #dddddd;padding:5px;'><?php echo $val->tax;?> 만원</td>
				</tr>
				<tr>
					<th>전용/공급</th>
					<td style='text-align:right;border:1px solid #dddddd;padding:5px;'><?php echo $val->real_area;?> ㎡/<?php echo $val->law_area;?> ㎡</td>
				</tr>
				<tr>
					<th>대지지분</th>
					<td style='text-align:right;border:1px solid #dddddd;padding:5px;'><?php echo $val->road_area;?> ㎡</td>
				</tr>
				<tr>
					<th>현관</th>
					<td style='text-align:right;border:1px solid #dddddd;padding:5px;'><?php echo $val->gate;?></td>
				</tr>
				<tr>
					<th>방/욕실</th>
					<td style='text-align:right;border:1px solid #dddddd;padding:5px;'><?php echo $val->bedcnt;?><?php echo $val->bathcnt;?></td>
				</tr>
				<tr>
					<th>전매기간</th>
					<td style='text-align:right;border:1px solid #dddddd;padding:5px;'><?php echo $val->presale_date;?></td>
				</tr>
				<?php if($val->description){?>
				<tr>
					<td colspan='2' style='border:1px solid #dddddd;padding:5px;'><?php echo $val->description;?></td>
				</tr>
				<?php }?>
			</table>
		</div>
		<div style='clear:both;'></div>
	</div>
	<?php }?>
<?php }?>

<br/>
<div style='border:1px dashed #cacaca;padding:10px;'><center><b>오늘의 좋은 글 3 </b><br><?php echo $proverb3->code?></center></div>
<br/>

<div style='font-family:dotum;font-size:14px;padding-top:20px;'>
<?php
/** 내새 줄로 배열을 나눈 다. **/	
$content_1 = explode("</p>", $query->content.$member->sign);
$content_2 = "";
foreach($content_1 as $val){
	$emo_1 = rand(1, 5);
	$emo_2 = rand(1, 42);
	if($emo_2<10) $emo_2 = "0".$emo_2;

	$content_2 .= $val . "<img src='http://static.se2.naver.com/static/full/20130612/emoticon/".$emo_1."_".$emo_2.".gif'/></p>";
}
if($content_2){
	$content_2 = trim($content_2);
	$content_2 = str_replace("\n","",$content_2);
	$content_2 = str_replace("\"","'",$content_2);
	$content_2 = str_replace("<img","<img style='max-width:100%'",$content_2);
	$content_2 = str_replace("/uploads/contents/","http://".HOST."/uploads/contents/",$content_2);	
}
echo $content_2;
?>
</div>

<?php if($this->config->item('view_map_use')) {?>
<div style='font-size:18px;color: #555555;font-weight: bold;padding-left: 5px;padding-top:30px;'><?php echo lang("site.address");?></div>
<img src='https://maps.googleapis.com/maps/api/staticmap?center=<?php echo $query->lat;?>,<?php echo $query->lng;?>&zoom=15&size=740x300&markers=color:red%7C<?php echo $query->lat;?>,<?php echo $query->lng;?>&language=ko' style='border:1px solid #cacaca;' width='740'/>
<?php } ?>

<div style='font-size:18px;color: #555555;font-weight: bold;padding-left: 5px;padding-top:30px;'><?php echo lang("site.photo");?></div>
<?php
shuffle($gallery);
foreach($gallery as $key=>$gallery_row){
?>
<img src='http://<?php echo HOST."/uploads/gallery_installation/".$query->id."/".$gallery_row->filename;?>' style='border:1px solid #cacaca;margin-bottom:15px;' width='740'/><br/>
<div style='font-family:Malgun Gothic;font-size:14px;text-align:left;'><?php echo $gallery_row->content;?></div>
<?php }?>

<?php if($query->panorama_url){?>
<div style='margin-bottom:10px;text-align:center'>
	<a href='http://<?php echo str_replace("http://","",$query->panorama_url)?>' target='_blank'><img src='http://<?php echo HOST?>/assets/common/img/vr.png'/></a>
</div>
<?php }?>

<table border='0' style='margin-top:50px'>
<tr>
<?php if($member->type=="biz"){?>
	<td style='border:0px;border-right:1px solid #cacaca;padding:10px;'>
		<?php if($member->profile){?>
		<a href='http://<?php echo HOST?>/installation/view/<?php echo $query->id?>' target='_blank'><img src='http://<?php echo HOST?>/uploads/member/<?php echo $member->profile?>'/></a>
		<?php } else {?>
		<a href='http://<?php echo HOST?>/installation/view/<?php echo $query->id?>' target='_blank'><img src='http://<?php echo HOST.'/logo/is_logo'?>'/></a>
		<?php }?>
	</td>
	<td style='padding-left:20px;'>
		<p><b style='font-size:16px;'><?php echo $member->biz_name?></b><br/><br/>
		<?php echo $member->address." ".$member->address_detail?> | 중개업자: <?php echo $member->name?><br/>
		전화 : <?php echo $member->tel?> | 휴대전화 : <?php echo $member->phone?><br/>
		홈페이지: <a href='http://<?php echo HOST?>/installation/view/<?php echo $query->id?>' target='_blank'>http://<?php echo HOST?>/installation/view/<?php echo $query->id?></a>
	</p></td>
<?php } else {?>
	<td style='border:0px;border-right:1px solid #cacaca;padding:10px;'>
		<a href='http://<?php echo HOST?>' target='_blank'><img src='http://<?php echo HOST.'/logo/is_logo'?>'/></a>
	</td>
	<td style='padding-left:20px;'>
		<p><b style='font-size:16px;'><?php echo $config->name?></b><br/><br/>
		<?php echo $config->new_address?> | 대표: <?php echo $config->ceo?><br/>
		사업자등록번호: <?php echo $config->biznum?> | 전화 : <?php echo $config->tel?> | 팩스: <?php echo $config->fax?><br/>
		<?php if($config->INSTALLATION_FLAG<1){ ?><?php echo lang("site.renum");?>: <?php echo $config->renum?><br/><?php }?>
		홈페이지: <a href='http://<?php echo HOST?>' target='_blank'><?php echo HOST?></a>
	</p></td>
<?php }?>
</tr>
</table>