<!-- 월세가격 템플릿 시작 -->
<span style='font-size:12px;border-radius:2px;color:white;padding:2px 4px 2px 1px;margin-right:2px;background-color:#209F4E;'><?php echo cut_one(lang("product.price.rent"))?></span>

<?php if($query["monthly_rent_deposit"]!="0"){ ?>
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo number_format($query["monthly_rent_deposit"])?></strong> <?php echo lang("price_unit") ?>
<?php } ?>

<?php if($query["monthly_rent_price"]!="0"){ ?>
	<?php if($query["monthly_rent_deposit"]!="0"){ ?>/<?php } ?>
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo number_format($query["monthly_rent_price"])?></strong> <?php echo lang("price_unit") ?>
<?php } ?>

<?php if($query["premium_price"]!="0"){ ?>
	<?php if($query["monthly_rent_price"]!="0"){ ?>/<?php } ?>
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo number_format($query["premium_price"])?></strong> <?php echo lang("price_unit") ?>
<?php } ?>

<?php if($config->GONGSIL_FLAG && element("mgr_price",$query)!="0"){ ?>
	<?php if($query["monthly_rent_price"]!="0"){ ?>/<?php } ?>
	<?php echo cut_one(lang("product.mgr_price"))?>
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo element("mgr_price",$query)?></strong> <?php echo lang("price_unit") ?>
<?php } ?>
<!-- 월세가격 템플릿 종료 -->

<!-- 월세 추가 가격 -->
<?php
if(isset($query["add_price"])){
	foreach($query["add_price"] as $val){?>
	<div style="display:block;">
		<span style='font-size:12px;border-radius:2px;color:white;padding:2px 4px 2px 1px;margin-right:2px;background-color:#209F4E;'><?php echo cut_one(lang("product.price.rent"))?></span>

		<?php if($val["monthly_rent_deposit"]!="0"){ ?>
			<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo number_format($val["monthly_rent_deposit"])?></strong> <?php echo lang("price_unit") ?>
		<?php } ?>

		<?php if($val["monthly_rent_price"]!="0"){ ?>
			<?php if($val["monthly_rent_deposit"]!="0"){ ?>/<?php } ?>
			<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo number_format($val["monthly_rent_price"])?></strong> <?php echo lang("price_unit") ?>
		<?php } ?>
	</div>
<?php 
	}
}?>