<div style='font-family:dotum;font-size:14px;'>
<center><img src="/uploads/blogs/{thumb_name}" alt="{title}"></center><br/>
<br/><br/>
{content}
</div>
<?php if(count($recent)>0){?>
	<b style='font-size:14px;margin:20px 0px 20px 0px;'>[ 최근 컨텐츠 ]</b><br/>
	<ul>
	<?php foreach($recent as $val){ ?>
		<li><a href="http://<?php echo HOST;?>/blog/view/<?php echo $val->id;?>" target="_blank"><b><?php echo $val->title;?></b></a> <i><?php echo $val->date;?></i></li>
	<?php }?>
	</ul>
<?php }?>