<!-- 매매가격 템플릿 시작 -->
<?php if(element("sell_price",$query)!="0"){ ?>
	<span style='border-radius:2px;color:white;padding:2px 4px 2px 1px;margin-right:2px;background-color:#D22129;'><?php echo cut_one(lang("product.price.sell.sell"))?></span>	
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo number_format(element("sell_price",$query))?></strong> <?php echo lang("price_unit") ?> &nbsp;
<?php } ?>

<?php if(element("lease_price",$query)!="0"){ ?>
	<span style='border-radius:2px;color:white;padding:2px 4px 2px 1px;margin-right:2px;background-color:#D22129;'><?php echo cut_one(lang("product.price.sell.lease"))?></span>
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo number_format(element("lease_price",$query))?></strong> <?php echo lang("price_unit") ?> &nbsp;
<?php } ?>


<?php if($config->GONGSIL_FLAG && $query["mgr_price"]!="0"){ ?>
	<?php if(element("monthly_rent_price",$query)!="0"){ ?>/<?php } ?>
	<?php echo cut_one(lang("product.mgr_price"))?>
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo element("mgr_price",$query)?></strong> <?php echo lang("price_unit") ?>
<?php } ?>
<!-- 매매가격 템플릿 종료 -->