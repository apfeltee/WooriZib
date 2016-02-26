<script language="javascript">	
	var recent_line = '<?php echo $line?>';
	recent_line = (recent_line) ? recent_line : 0; 

	$(document).ready(function() {
		$("input[name='recent']").change(function(){
			get_recent($(this).val());
		});

		get_recent("0");

	});

	/** listing_1의 유형의 경우에는 utilCarousel이 동작하면 안된다. **/
	function get_recent(category){
		<?php if($config->LISTING!="1") {?>
		var utilCarouselObj = $('#recent_list').data('utilCarousel');
		if(utilCarouselObj!=null) utilCarouselObj.destroy();
		<?php }?>
		$("#recent_list").hide();
		$.get("/home/recent/"+category+"/"+recent_line+"/"+Math.round(new Date().getTime()),function(data){
			$("#recent_list").html(data);
			<?php if($config->LISTING!="1") {?>
			if(recent_line <= 1){
				$('#recent_list').utilCarousel({
					indexChanged : function() {
						var height = $(document).scrollTop();
						window.scrollTo(0, height + 1);	
					},
					responsiveMode : 'itemWidthRange',
					itemWidthRange : [270, 270]}
				);			
			}
			<?php }?>
			$("#recent_list").show();
			$('.help').tooltip();
			link_init($("#recent_list").find('.view_product'));
			login_leanModal();
		});
	}
	
</script>
<div class="<?php echo $background?>">
	<div class="_container">
		<div class="text-left" style="position:relative;">
			<h1><i class="fa fa-home"></i> <?php echo $title?>
				<div class="btn-group" data-toggle="buttons">
				  <button class="btn btn-default btn-sm active">
				    <input type="radio" name="recent" autocomplete="off" value="0" checked> <?php echo lang("site.all");?>
				  </button>
				  <?php foreach($category_top as $val){
				  	if($val->cnt>0) {?>
				  <button class="btn btn-default btn-sm">
				    <input type="radio" name="recent" autocomplete="off" value="<?php echo $val->id; ?>"><?php echo $val->name ;?>
				  </button>
				  <?php }}?>
				</div>
			</h1>
			<hr class="hr_narrow  hr_color">
		</div>
		<div id="recent_wrap" <?php if($config->LISTING!="1") {?>style="height:332px;"<?php } ?>>
			<?php if($config->LISTING=="1") {?>
			<table class="table table-bordered table-striped table-condensed flip-content margin-top-10">
				<thead>
					<tr>
						<th style="width:80px;"><?php echo lang("site.photo");?></th>
						<th style="width:130px;"><?php echo lang("site.address");?></th>
						<th style="width:90px;"><?php echo lang("product.category");?></th>
						<th style="width:50px;"><?php echo lang("product.type");?></th>								
						<th><?php echo lang("site.title");?>/<?php echo lang("site.address");?></th>
						<?php if($config->PRODUCT_REALAREA || $config->PRODUCT_LAWAREA) {?>
						<th style="width:60px;"><?php echo lang("product.area");?></th>
						<?php }?>
						<!--th style="width:40px;">층</th-->
						<th style="width:60px;">현업종</th>
						<th style="width:80px;"><?php echo lang("site.regdate");?>/<?php echo lang("site.confirm");?></th>
					</tr>
				</thead>
				<tbody id="recent_list"></tbody>
			</table>
			<?php } else { ?>
				<div id="recent_list" class="util-carousel"></div>
			<?php } ?>
		</div>				
	</div>
</div>
