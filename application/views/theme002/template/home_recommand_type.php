<?php
	if($line <= 1){
		$carousel_class = "category-carousel util-carousel";
	}
	else{
		$carousel_class = "";
	}

	$type_text = array(
		'installation'	=>lang('installation'),
		'sell'			=>lang('sell'),
		'full_rent'		=>lang('full_rent'),
		'monthly_rent'	=>lang('monthly_rent'),
		'rent'			=>lang('rent')
	);

	if(isset($recommand_type)){
		foreach($recommand_type as $key=>$val2){ ?>
			
			<div class="_container margin-bottom-20" style="padding-top:20px;">
				<div class="row">
						<div class="col-lg-12">
							<img src="/assets/theme002/img/banner<?php echo mt_rand(1,3);?>.jpg">
						</div>
				</div>
			</div>

				<div class="<?php echo $background?>">
					<div class="_container">
						<div class="text-left" style="position:relative;">
							<h1><i class="fa fa-home"></i> <?php echo $type_text[$val2["name"]];?> <?php echo $title;?></h1>
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
									<!--th style="width:40px;">lang("product.f")</th-->
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
<?php 
		}
	}
?>

