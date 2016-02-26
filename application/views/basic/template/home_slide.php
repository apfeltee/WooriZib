<?php 
	$browser_info = getBrowser();

	if($browser_info['name']=='IE' && $browser_info['version'] <= 8){
		$searchbox_class = "searchbox-ie8";
		$select_style_class = "select-style-ie8";
	}
	else{
		$searchbox_class = "searchbox";
		$select_style_class = "select-style";
	}

?>
<style>
<?php if($slide_code==3){?>
.searchbox-ie8{
  background-color: transparent;
  max-width: 850px;
}
<?php } ?>

<?php if($slide){?>
.wrap_slider {
	background: url(/uploads/slide/<?php echo $slide[0]['filename']?>)  no-repeat top center;
}
<?php } else {?>
.wrap_slider { /*default image*/
	background: url(/assets/theme/seven/slide/bg3.jpg)  no-repeat top center;
}
<?php } ?>
</style>

<!-- BEGIN SLIDER -->

<div class="wrap_slider">
	<a href="#" id="top_layer_link" target="_blank">
		<div class="pattern"></div>
		<div class="pattern-bg"></div>
	</a>
	<div class="_container">

		<div class="slide_wrapper" style="pointer-events:none;">

			<div style="text-align:center;margin-bottom:20px;">
				<?php if($title) {?>
					<h1><?php echo $title;?></h1>
				<?php }?>
			</div>

			<?php if($slide_code==1){ //중앙형태?>
				<div class="searchbox_wrapper">
					<div class="<?php echo $searchbox_class;?>" style="pointer-events:auto;">
						<?php $this->load->view("templates/home_search_in_tab");?>
					</div>
				</div><!-- searchbox_wrapper -->
			<?php } else if($slide_code==3) { ?>
				<div class="searchbox_wrapper_notab">
					<div class="<?php echo $searchbox_class;?>" style="pointer-events:auto;">
						<?php $this->load->view("templates/home_search_in_notab");?>
					</div>
				</div><!-- searchbox_wrapper -->
			<?php } ?>
		</div> <!-- slide-wrapper -->

	</div> <!-- container -->
</div>
<!-- END SLIDER -->

<?php if($slide_code==2){//하단형태?>
	<div class="searchbox_wrapper_bottom">
		<div class="container <?php echo $searchbox_class;?>" style="pointer-events:auto;">
			<?php $this->load->view("templates/home_search_in_tab");?>
		</div>
	</div><!-- searchbox_wrapper_bottom -->			
<?php }?>

<script>
var slide_background = <?php echo $slide_json;?>;
var slide_length = slide_background.length;
var now_index = 1;

function change_slide_image(index){
	$('.pattern-bg').hide();
	$('.pattern-bg').css({
		"background" : "url(/uploads/slide/"+slide_background[index].filename+") no-repeat top center"
	});
	$('.pattern-bg').fadeIn(2000, function() {
		$('.wrap_slider').css({
			"background" : "url(/uploads/slide/"+slide_background[index].filename+") no-repeat top center"
		});
	});	

	chage_link(slide_background[index].link);

	now_index++;
}


function chage_link(link){
	if(link && link!=null){
		$("#top_layer_link").css("cursor","pointer");
		$("#top_layer_link").attr("target","_blank");
		$("#top_layer_link").attr("href","http://"+link);
	}
	else{
		$("#top_layer_link").css("cursor","default");
		$("#top_layer_link").attr("target","");
		$("#top_layer_link").attr("href","javascript:void(0)");
	}
}

$(document).ready(function(){
	if(slide_length > 1){
		setInterval(function() {
			if(now_index > slide_length-1) now_index = 0;
			change_slide_image(now_index);
		}, 7000);
	}
	chage_link("<?php echo $slide[0]['link']?>");
});
</script>