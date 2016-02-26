<?php if(count($theme)>0){?>
<div class="<?php echo $background?>">
	<div class="_container">
		<div class="text-center">
			<h1><?php echo $title;?></h1>
			<hr class="hr_narrow  hr_color">
		</div>
		<div class="row theme">
			<?php foreach($theme as $key=>$val){?>
				  <div class="col-md-<?php echo $val->col*3 ;?> col-xs-6">
				  <?php if($val->image){?>
				  	<div class="cell" style="background:url(/uploads/theme/<?php echo $val->image?>)">
				  <?php }else{?>
				  	<div class="cell" style="background:url(/assets/common/img/bg/theme/back<?php echo $key?>.jpg)">
				  <?php }?>
	  					<div class="cell_holder" data-id="<?php echo $val->id;?>">
							<a class="cover-wrapper">
								<?php echo $val->theme_name;?>
								<div class="description"><?php echo $val->description;?></div>
							</a>						
						</div>
						<div>
							<a class="cover-wrapper">
								<?php echo $val->theme_name;?>
								<div class="description"><?php echo $val->description;?></div>
							</a>
						</div>
				  	</div>
				  </div>
			<?php }?>
		</div>
		<div style="clear:both;"></div>
	</div> <!-- container -->
</div>
<?php }?>

<script>
$(document).ready(function(){
	$(".cell_holder").click(function(){
		$("#search_form")[0].reset();
		$("#search_form").find("#theme").val($(this).attr("data-id"));
		$("#search_form").trigger("submit");
	});


});
</script>
