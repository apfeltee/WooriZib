<?php
	if($line <= 1){
		$carousel_class = "category-carousel util-carousel";
	}
	else{
		$carousel_class = "";
	}

	foreach($recommand_theme as $key=>$val2){
		if($val2["result"]!=null){?>
			<div class="<?php echo $background?>">
				<div class="_container">
					<div class="text-center">
						<h1><?php echo $val2["name"];?> <?php echo $title;?></h1>
						<hr class="hr_narrow  hr_color">
					</div>					
					<div class="<?php echo $carousel_class?>">
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
							<tbody id="search-items"><?php echo $val2["result"];?></tbody>
						</table>
						<?php } else { ?>
							<?php echo $val2["result"];?>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php }
	}
?>
