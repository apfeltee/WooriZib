<!-- 분양가격 템플릿 시작 -->
<?php if($query["sell_price"]!="0"){ ?>
	<span style='font-size:12px;border-radius:2px;color:white;padding:2px 4px 2px 1px;margin-right:2px;background-color:#D22129;'><?php echo cut_one(lang("product.price.installation.sell"))?></span>
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo number_format($query["sell_price"])?></strong> <?php echo lang("price_unit") ?> &nbsp;
<?php } ?>

<?php if($query["lease_price"]!="0"){ ?>
	<span style='font-size:12px;border-radius:2px;color:white;padding:2px 4px 2px 1px;margin-right:2px;background-color:#D22129;'><?php echo cut_one(lang("product.price.installation.lease"))?></span>
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo number_format($query["lease_price"])?></strong> <?php echo lang("price_unit") ?> &nbsp;
<?php } ?>

<?php if($query["sell_price"]!="0" && $query["lease_price"]!="0"){ ?>
	<span style='font-size:12px;border-radius:2px;color:white;padding:2px 4px 2px 1px;margin-right:2px;background-color:#D22129;'><?php echo cut_one(lang("product.price.installation.loan"))?></span>
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'> <?php echo number_format(abs($query["lease_price"] - $query["sell_price"])) ?></strong> <?php echo lang("price_unit") ?>
<?php } ?>
<!-- 분양가격 템플릿 종료 -->

<?php if($config->GONGSIL_FLAG && element("mgr_price",$query)!="0"){ ?>
	<?php if($query["monthly_rent_price"]!="0"){ ?>/<?php } ?>
	<?php echo cut_one(lang("product.mgr_price"))?>
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo element("mgr_price",$query)?></strong> <?php echo lang("price_unit") ?>
<?php } ?>
