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
header {
	background-image: url(/uploads/landing/<?php echo $landing;?>);
}
</style>
<header>
<div class="_container">
	<div class="intro-text">
	<?php if($title) {?>
		<div class="intro-heading"><?php echo $title;?></div>
	<?php }?>
		<?php if($landing_code==1){ //탭있음?>
			<div class="searchbox_wrapper">
				<div class="<?php echo $searchbox_class;?>">
					<?php $this->load->view("templates/home_search_in_tab");?>
				</div>
			</div>
		<?php } else if($landing_code==2){//탭없음 ?>
			<div class="searchbox_wrapper_notab">
				<div class="<?php echo $searchbox_class;?>">
					<?php $this->load->view("templates/home_search_in_notab");?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
</header>