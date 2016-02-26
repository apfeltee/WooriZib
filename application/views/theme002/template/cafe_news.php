<div style='font-family:Malgun Gothic;font-size:14px;'>
<center><img src='http://<?php echo HOST;?>/uploads/news/<?php echo $thumb_name?>' style='max-width:710px;padding:15px; 0px; 15px; 0px;'/></center>
<br/><br/>
<div style='border:1px dashed #cacaca;padding:10px;'><center><b>오늘의 좋은 글 1 </b><br><?php echo $proverb1->code?></center></div>
<br/>
<?php 
if($content){
	$content = trim($content);
	$content = str_replace("\n","",$content);
	$content = str_replace("\"","'",$content);
	$content = str_replace("/uploads/news/contents/","http://".HOST."/uploads/news/contents/",$content);
	$content = str_replace("<img","<img style='max-width:710px;padding:15px; 0px; 15px; 0px;'",$content);
	$content = str_replace("<p","<p style='line-height:200%;'",$content);
	echo $content;
}
?>
</div>
<br/><br/>
<div style='border:1px dashed #cacaca;padding:10px;'><center><b>오늘의 좋은 글 2 </b><br><?php echo $proverb2->code?></center></div>
<br/><br/>
<!--
<?php if(count($recent)>0){?>
	<b style='font-size:14px;'>[ 최근 컨텐츠 ]</b><br/>
	<ul style='padding:10px; 0px 0px 0px;margin:0px;'>
	<?php foreach($recent as $val){ ?>
		<li style='list-style:none;padding-bottom:5px;'><a href='http://<?php echo HOST;?>/news/view/<?php echo $val->id;?>' target='_blank'><b><?php echo $val->title;?></b></a>&nbsp;&nbsp;&nbsp;<i><?php echo $val->date;?></i></li>
	<?php }?>
	</ul>
<?php }?>
<br/>
-->
<div style='border:1px dashed #cacaca;padding:10px;'><center><b>오늘의 속담</b><br><?php echo $statement->code?></center></div>

<table border='0' style='margin-top:50px'>
<tr>
<?php if($member->type=="biz"){?>
	<td style='border:0px;border-right:1px solid #cacaca;padding:10px;'>
		<?php if($member->profile){?>
		<a href='http://<?php echo HOST?>/product/view/<?php echo $query->id?>' target='_blank'><img src='http://<?php echo HOST?>/uploads/member/<?php echo $member->profile?>'/></a>
		<?php } else {?>
		<a href='http://<?php echo HOST?>/product/view/<?php echo $query->id?>' target='_blank'><img src='http://<?php echo HOST.'/logo/is_logo'?>'/></a>
		<?php }?>
	</td>
	<td style='padding-left:20px;'>
		<p><b style='font-size:16px;'><?php echo $member->biz_name?></b><br/><br/>
		<?php echo $member->address." ".$member->address_detail?> | <?php echo lang("product.owner");?>: <?php echo $member->name?><br/>
		<?php echo lang("site.tel");?> : <?php echo $member->tel?> | <?php echo lang("site.mobile");?> : <?php echo $member->phone?><br/>
		<?php echo lang("site.homepage");?>: <a href='http://<?php echo HOST?>/product/view/<?php echo $query->id?>' target='_blank'>http://<?php echo HOST?>/product/view/<?php echo $query->id?></a>
	</p></td>
<?php } else {?>
	<td style='border:0px;border-right:1px solid #cacaca;padding:10px;'>
		<a href='http://<?php echo HOST?>' target='_blank'><img src='http://<?php echo HOST.'/logo/is_logo'?>'/></a>
	</td>
	<td style='padding-left:20px;'>
		<p><b style='font-size:16px;'><?php echo $config->name?></b><br/><br/>
		<?php echo $config->new_address?> | <?php echo lang("site.ceo");?> <?php echo $config->ceo?><br/>
		<?php echo lang("site.biznum");?> <?php echo $config->biznum?> | <?php echo lang("site.tel");?> : <?php echo $config->tel?> | <?php echo lang("site.fax");?>: <?php echo $config->fax?><br/>
		<?php if($config->INSTALLATION_FLAG<1){ ?><?php echo lang("site.renum");?>: <?php echo $config->renum?><br/><?php }?>
		<?php echo lang("site.homepage");?>: <a href='http://<?php echo HOST?>' target='_blank'><?php echo HOST?></a>
	</p></td>
<?php }?>
</tr>
</table>