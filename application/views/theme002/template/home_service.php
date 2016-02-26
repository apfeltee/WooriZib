<?php if(count($service)>0){?>
<div class="service-box">
	<div class="_container padding-top-20">
			<div class="row">
				<?php if($title!=""){?>
				<div class="text-center" style="margin-bottom:60px;">
					<h1><?php echo $title;?></h1>
					<hr class="hr_narrow  hr_color">
				</div>
				<?php } ?>
				<?php foreach($service as $key=>$val){?>
				<div class="col-md-<?php echo $val->col*3 ;?> col-xs-6 text-center">
					<div class="service-cell">
						<a href="<?php echo $val->link?>" <?php if($val->target!="N"){?>target="_blang"<?php }?>>
							<h4 style="font-weight:900;"><?php echo $val->service_name?></h4>
							<img src="/uploads/theme/<?php echo $val->image?>" class="img-responsive">
							<p><?php echo $val->description?></p>
						</a>
					</div>
				</div>
				<?php }?>
		</div><!--row-->
	</div><!--container-->
</div>

<style>
.service-box {
	border-color: #eae9e9;
	border-bottom-width: 0px;
	border-top-width: 0px;
	border-bottom-style: solid;
	border-top-style: solid;
	padding-bottom: 100px;
	padding-left: 0px;
	padding-right: 0px;
	padding-top: 95px;
	background-attachment: fixed;
	background-color: #ffffff;
	background-position: left top;
	background-repeat: no-repeat;
	background-size: cover;
	/** background-image: url(/assets/common/img/bg/homeback_1.jpg	); **/
}

.service-box .service-cell {
	padding: 15px;
	min-height: 307px;
	height: auto;
	background-color: rgba(253, 253, 253, 0.9);
	border:1px solid #efefef;
	margin-bottom:20px;
}

.service-box .service-cell a {
	color:#343434;
	text-decoration:none;
}

.service-box h4 {
	font-size: 18px;
}

.service-box img {
	margin-top:20px;
	margin-bottom:20px;
	border: 3px solid rgba(000,000,000,.04);
}

</style>
<?php }?>