<div style="padding-top:70px;padding-bottom:70px;border-top: 1px solid #efefef;">
	<div class="_container padding-top-20">
			<div class="row">
				<!-- BEGIN BOTTOM ABOUT BLOCK -->
				<div class="col-md-6 col-sm-6 padding-top-50 text-right">
					<h3><?php echo $config->site_name;?> 앱을 다운로드받으세요!</h3>
					<div class="hr hr-short hr-left  avia-builder-el-41  el_after_av_textblock  el_before_av_button "><span class="hr-inner "><span class="hr-inner-style"></span></span></div>
					<p class="help-block">스마트폰에서 언제 어디서라도 편리하게 검색하세요.</p>
					<br/>
					<?php if($config->GPLAY!=""){?>
					<a href="https://play.google.com/store/apps/details?id=<?php echo $config->GPLAY;?>" target="_blank"><img src="/assets/theme001/img/market_google.png"></a>
					<?php } else {?>
					<a onclick="alert('준비중입니다.')" style="cursor:pointer;"><img src="/assets/theme001/img/market_google.png"></a>
					<?php } ?>
				</div>
				<div class="col-md-6 col-sm-6 text-center">
					<img src="uploads/mockup/mockup.png">
				</div>
		</div><!--row-->
	</div><!--container-->
</div>

<style>
.hr {
    clear: both;
    display: block;
    width: 100%;
    height: 25px;
    line-height: 25px;
    position: relative;
    margin: 30px 0;
    float: left;
}

.main_color .hr-short .hr-inner {
    background-color: #ffffff;
}

.hr-short.hr-left .hr-inner {
    right: 0%;
}

.hr-short .hr-inner {
    width: 70%;
    left: 30%;
}

.hr-short.hr-left .hr-inner-style {
    left: 5px;
}

.hr-short {
    height: 20px;
    line-height: 20px;
    margin: 30px 0;
    float: none;
}

.hr-inner {
    width: 100%;
    position: absolute;
    height: 1px;
    right: 0;
    top: 50%;
    width: 100%;
    margin-top: -1px;
    border-top-width: 1px;
    border-top-style: solid;
	    border-color: #e1e1e1;
}

.hr-short .hr-inner-style {
    border-radius: 20px;
    height: 9px;
    width: 9px;
    border-width: 2px;
    border-style: solid;
    display: block;
    position: absolute;
    left: 50%;
    margin-left: -5px;
    margin-top: -5px;
	    border-color: #e1e1e1;
}
</style>