<!-- 매매가격 템플릿 시작 -->
<span style='font-size:12px;border-radius:2px;color:white;padding:2px 4px 2px 1px;margin-right:2px;background-color:#3865C0;'><?php echo cut_one(lang("product.price.fullrent"))?></span>
<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo number_format($query["full_rent_price"])?></strong> <?php echo lang("price_unit") ?>

<?php if($config->GONGSIL_FLAG && element("mgr_price",$query)!="0"){ ?>
	<?php if($query["monthly_rent_price"]!="0"){ ?>/<?php } ?>
	<?php echo cut_one(lang("product.mgr_price"))?>
	<strong style='letter-spacing:-1px;color: #EE5555;font-size: 14px;'><?php echo element("mgr_price",$query)?></strong> <?php echo lang("price_unit") ?>
<?php } ?>
