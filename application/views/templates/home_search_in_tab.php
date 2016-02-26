<?php if($config->SEARCH_ORDER==2){?>
<ul class="nav-search">
	<li class="active"><a href="javascript:;"><?php echo lang("search.total");?></a></li>
	<li><a href="javascript:;"><?php echo lang("site.address");?> <?php echo lang("site.search");?></a></li>
	<?php if($config->SUBWAY) {?>
	<li><a href="javascript:;"><?php echo lang("site.subway");?> <?php echo lang("site.search");?></a></li>
	<?php } ?>
</ul>
<?php } else {?>
<ul class="nav-search">
	<li class="active"><a href="javascript:;"><?php echo lang("site.address");?> <?php echo lang("site.search");?></a></li>
	<?php if($config->SUBWAY) {?>
	<li><a href="javascript:;"><?php echo lang("site.subway");?> <?php echo lang("site.search");?></a></li>
	<?php } ?>
	<li><a href="javascript:;"><?php echo lang("search.total");?></a></li>
</ul>
<?php }?>
<div style="clear:both;"></div>
<form action="/search/set_search/main" id="search_form" method="post">
<input type="hidden" id="search_type" name="search_type">
<input type="hidden" id="search_value" name="search_value">
<input type="hidden" id="type" name="type" class="type">
<input type="hidden" id="lat" name="lat">
<input type="hidden" id="lng" name="lng">
<input type="hidden" id="sido_val" name="sido_val" value="<?php echo $config->INIT_SIDO;?>">
<input type="hidden" id="gugun_val" name="gugun_val" value="<?php if($config->INIT_SIDO) echo $config->INIT_GUGUN;?>">
<input type="hidden" id="dong_val" name="dong_val">
<input type="hidden" id="subway_local_val" name="subway_local_val">
<input type="hidden" id="hosun_val" name="hosun_val">
<input type="hidden" id="station_val" name="station_val">
<input type="hidden" id="theme" name="theme[]">
<input type="hidden" id="keyword_front" name="keyword_front">
<input type="hidden" id="region" name="region">
<div class="search-inner">
	<?php if($config->SEARCH_ORDER==2){?>
	<ul id="tab">
		<li class="active">
			<div class="row">
				<div class="col-xs-5ths">
					<select class="form-control type" id="type3" onchange="set_type(this.value)">
						<option value=""><?php echo lang("product.type");?></option>
						<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation"><?php echo lang('installation');?></option><?php }?>
						<?php if($config->INSTALLATION_FLAG!="2") {?>
						<option value="sell"><?php echo lang('sell');?></option>
						<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
						<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
						<?php }?>
					</select>
				</div>
				<div class="col-xs-5ths-2">
					<input type="text" id="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product.no");?>, <?php echo lang("site.title");?>" autocomplete="off">
				</div>
				<div class="col-xs-5ths">
					<button id="geo_btn" type="button" class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
				</div>
			</div>
		</li>
		<li>
			<div class="row">
				<div class="col-xs-5ths">
					<select class="form-control type" id="type1" onchange="set_type(this.value)">
						<option value=""><?php echo lang("product.type");?></option>
						<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation" <?php if($config->INSTALLATION_FLAG=="2") {?>selected<?php }?>><?php echo lang('installation');?></option><?php }?>
						<?php if($config->INSTALLATION_FLAG!="2") {?>
						<option value="sell"><?php echo lang('sell');?></option>
						<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
						<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
						<?php }?>
					</select>
					</div>
					<?php if($config->REGION_USE){?>
					<div class="col-xs-5ths-2">
						<select id="region" name="region" class="form-control" onchange="region_select(this.value);">
							<option value="">지역 선택</option>
							<?php foreach($region as $val){?>
							<option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
							<?php }?>
						</select>
					</div>
					<div class="col-xs-5ths">
						<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
					</div>	
					<?php } else { ?>
					<div class="col-xs-5ths">
						<select id="sido" name="sido" class="form-control" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
					</div>
					<div class="col-xs-5ths">
						<select id="gugun" name="gugun" class="form-control"><option value="">-</option></select>
					</div>
					<div class="col-xs-5ths">
						<select id="dong" name="dong" class="form-control"><option value="">-</option></select>
					</div>
					<div class="col-xs-5ths">
						<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
					</div>									
					<?php }?>
				</div>
			</li>
			<?php if($config->SUBWAY) {?>
			<li>
				<div class="row">
					<div class="col-xs-5ths">
						<select class="form-control type" id="type2" onchange="set_type(this.value)">
							<option value=""><?php echo lang("product.type");?></option>
							<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation"><?php echo lang('installation');?></option><?php }?>
							<?php if($config->INSTALLATION_FLAG!="2") {?>
							<option value="sell"><?php echo lang('sell');?></option>
							<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
							<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
							<?php }?>
						</select>
					</div>
					<div class="col-xs-5ths">
						<select id="subway_local" name="subway_local" class="form-control" onchange="$('#station').html('<option value=\'\'>-</option>');">
							<option value=""><?php echo lang("site.location");?></option>
							<option value="1"><?php echo toeng("수도권");?></option>
							<option value="2"><?php echo toeng("부산");?></option>
							<option value="3"><?php echo toeng("대구");?></option>
							<option value="4"><?php echo toeng("광주");?></option>
							<option value="5"><?php echo toeng("대전");?></option>
						</select>
					</div>
					<div class="col-xs-5ths">
						<select id="hosun" name="hosun" class="form-control"><option value="">-</option></select>
					</div>
					<div class="col-xs-5ths">
						<select id="station" name="station" class="form-control"><option value="">-</option></select>
					</div>
					<div class="col-xs-5ths">
						<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
					</div>
				</div>
			</li>
			<?php }?>
		</ul>
		<?php } else { ?>
		<ul id="tab">
			<li class="active">
			<div class="row">
				<div class="col-xs-5ths">
					<select class="form-control type" id="type1" onchange="set_type(this.value)">
						<option value=""><?php echo lang("product.type");?></option>
						<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation" <?php if($config->INSTALLATION_FLAG=="2") {?>selected<?php }?>><?php echo lang('installation');?></option><?php }?>
						<?php if($config->INSTALLATION_FLAG!="2") {?>
						<option value="sell"><?php echo lang('sell');?></option>
						<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
						<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
						<?php }?>
					</select>
				</div>
				<?php if($config->REGION_USE){?>
				<div class="col-xs-5ths-2">
					<select id="region" name="region" class="form-control" onchange="region_select(this.value);">
						<option value="">지역 선택</option>
						<?php foreach($region as $val){?>
						<option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
						<?php }?>
					</select>
				</div>
				<div class="col-xs-5ths">
					<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
				</div>	
				<?php } else { ?>
				<div class="col-xs-5ths">
					<select id="sido" name="sido" class="form-control" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
				</div>
				<div class="col-xs-5ths">
					<select id="gugun" name="gugun" class="form-control"><option value="">-</option></select>
				</div>
				<div class="col-xs-5ths">
					<select id="dong" name="dong" class="form-control"><option value="">-</option></select>
				</div>
				<div class="col-xs-5ths">
					<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
				</div>									
				<?php }?>
			</div>
		</li>
		<?php if($config->SUBWAY) {?>
		<li>
			<div class="row">
				<div class="col-xs-5ths">
					<select class="form-control type" id="type2" onchange="set_type(this.value)">
						<option value=""><?php echo lang("product.type");?></option>
						<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation"><?php echo lang('installation');?></option><?php }?>
						<?php if($config->INSTALLATION_FLAG!="2") {?>
						<option value="sell"><?php echo lang('sell');?></option>
						<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
						<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
						<?php }?>
					</select>
				</div>
				<div class="col-xs-5ths">
					<select id="subway_local" name="subway_local" class="form-control" onchange="$('#station').html('<option value=\'\'>-</option>');">
						<option value=""><?php echo lang("site.location");?></option>
						<option value="1"><?php echo toeng("수도권");?></option>
						<option value="2"><?php echo toeng("부산");?></option>
						<option value="3"><?php echo toeng("대구");?></option>
						<option value="4"><?php echo toeng("광주");?></option>
						<option value="5"><?php echo toeng("대전");?></option>
					</select>
				</div>
				<div class="col-xs-5ths">
					<select id="hosun" name="hosun" class="form-control"><option value="">-</option></select>
				</div>
				<div class="col-xs-5ths">
					<select id="station" name="station" class="form-control"><option value="">-</option></select>
				</div>
				<div class="col-xs-5ths">
					<button class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
				</div>
			</div>
		</li>
		<?php }?>
		<li>
			<div class="row">
				<div class="col-xs-5ths">
					<select class="form-control type" id="type3" onchange="set_type(this.value)">
						<option value=""><?php echo lang("product.type");?></option>
						<?php if($config->INSTALLATION_FLAG!="0") {?><option value="installation"><?php echo lang('installation');?></option><?php }?>
						<?php if($config->INSTALLATION_FLAG!="2") {?>
						<option value="sell"><?php echo lang('sell');?></option>
						<?php if( lang('full_rent') != "") {?><option value="full_rent"><?php echo lang('full_rent');?></option><?php }?>
						<option value="monthly_rent"><?php echo lang('monthly_rent');?></option>
						<?php }?>
					</select>
				</div>
				<div class="col-xs-5ths-2">
					<input type="text" id="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product.no");?>, <?php echo lang("site.title");?>" autocomplete="off">
				</div>
				<div class="col-xs-5ths">
					<button id="geo_btn" type="button" class="btn btn-block btn-search"><i class="fa fa-search"></i> <?php echo lang("site.search");?></button>
				</div>
			</div>
		</li>
	</ul>
	<?php } ?>
</div> <!--search-inner-->
<ul>
	<?php foreach($category as $val){ ?>
	<li>
		<span class="radiobox-wrap-yellow"><input type="checkbox" name="category[]" id="category_<?php echo $val->id;?>" value="<?php echo $val->id;?>" class='checkbox' ></span> <label for="category_<?php echo $val->id;?>"> <?php echo $val->name;?></label>
	</li>
	<?php }?>
</ul>
</form>