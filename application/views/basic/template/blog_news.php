<div style='font-family:Malgun Gothic;font-size:14px;'>
<center><img src="http://<?php echo HOST;?>/uploads/news/<?php echo $thumb_name?>"/></center><br/>
<br/><br/>
<div style="border:1px dashed #cacaca;padding:10px;"><center><b>오늘의 좋은 글 1 </b><br><?php echo $proverb1->code?></center></div>
<br/><br/>
<?php echo $content?>
</div>
<br/><br/>
<div style='border:1px dashed #cacaca;padding:10px;'><center><b>오늘의 좋은 글 2 </b><br><?php echo $proverb2->code?></center></div>
<br/><br/>
<!--
<?php if(count($recent)>0){?>
	<b style='font-size:14px;'>[ 최근 컨텐츠 ]</b><br/>
	<ul style="list-style:none;padding:10px; 0px 0px 0px;margin:0px;">
	<?php
	foreach($recent as $val){ ?>
		<li style="padding-bottom:5px;"><a href="http://<?php echo HOST;?>/news/view/<?php echo $val->id;?>" target="_blank"><b><?php echo $val->title;?></b></a>&nbsp;&nbsp;&nbsp;<i><?php echo $val->date;?></i></li>
	<?php }?>
	</ul>
<?php }?>
<br/>
-->
<div style='border:1px dashed #cacaca;padding:10px;'><center><b>오늘의 속담</b><br><?php echo $statement->code?></center></div>