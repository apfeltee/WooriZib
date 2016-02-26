<div id="loading"><i class="fa fa-spinner fa-spin"></i></div>
<a href="#" class="pocp_button pocp_button_left btn_active btn-primary"><i class="fa fa-chevron-left"></i> <?php echo lang("site.search");?></a>
<a href="#" class="pocp_button pocp_button_left btn_active pocp_button_reset btn-primary" onclick="search_reset()"><i class="glyphicon glyphicon-refresh"></i> <?php echo lang("site.initfilter");?></a>
<div id="search_section" class="search-wrapper">
	<!-- https://css-tricks.com/organic-tabs/ -->
	<div class="loading_content">
		<div class="loading_background" style="overflow:auto"></div>
		<div class="loading_image"><img src="/assets/common/img/load.gif"></div>
	</div>
	<div id="search_tab">
		<?php if($config->SEARCH_ORDER==2){?>
		<ul class="nav">
			<li class="nav-one">
				<a href="#google_section" <?php if(element("search_type",$search)=="" or element("search_type",$search)=="google") {echo "class='current'";}?>><?php echo lang("search.total");?></a>
			</li>
			<li class="nav-two">
				<a href="#local_section" <?php if(element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {echo "class='current'";}?>><?php echo lang("site.location");?></a>
			</li>
			<?php if($config->SUBWAY) {?>
			<li class="nav-three last">
				<a href="#sybway_section" <?php if(element("search_type",$search)=="subway") {echo "class='current'";}?>><?php echo lang("site.subway");?></a>
			</li>
			<?php } ?>
		</ul>
		<div class="list-wrap">
			<ul id="google_section" <?php if(element("search_type",$search)=="" or element("search_type",$search)=="google") {} else {echo "style='display:none;'";}?>>
				<li>
					<div class="input-group">
						<input type="text" id="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product.no");?>, <?php echo lang("site.title");?>" autocomplete="off" value="<?php if(element("search_type",$search)=="google") echo element("search_value",$search);?>" style="font-size: 12px;height:28px;"/>
						<div class="input-group-btn">
							<button id="go_keyword" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</li>
			</ul>
			<ul id="local_section"  
				<?php if(element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {} else {echo "style='display:none;'";}?>>
				<li>
				<?php if($config->REGION_USE){?>
				<div class="col-xs-12" style="padding:0px;">
					<select id="region" name="region" onchange="region_select(this.value);">
						<option value="">지역 선택</option>
						<?php foreach($region as $val){?>
						<option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
						<?php }?>
					</select>
				</div>
				<?php } else {?>
				<div class="col-xs-4" style="padding:0px;">
					<select id="sido" name="sido" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
				</div>
				<div class="col-xs-4" style="padding:0px;">
					<select id="gugun" name="gugun"><option value="">-</option></select>
				</div>
				<div class="col-xs-4" style="padding:0px;">
					<select id="dong" name="dong"><option value="">-</option></select>
				</div>
				<?php }?>
				</li>
			</ul>
			<ul id="sybway_section" <?php if(element("search_type",$search)=="subway") {} else {echo "style='display:none;'";}?>>
				<li>
					<div class="row" style="margin:0px;">
						<div class="col-xs-4" style="padding:0px;">
							<select id="subway_local" name="subway_local" onchange="$('#station').html('<option value=\'\'>-</option>');"><option value="">-</option></select>
						</div>
						<div class="col-xs-4" style="padding:0px;">
							<select id="hosun" name="hosun"><option value="">-</option></select>
						</div>
						<div class="col-xs-4" style="padding:0px;">
							<select id="station" name="station"><option value="">-</option></select>
						</div>
					</div>							
				</li>
			</ul>
		</div>
		<?php } else {?>
		<ul class="nav">
			<li class="nav-one">
				<a href="#local_section" <?php if(element("search_type",$search)=="" or element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {echo "class='current'";}?>><?php echo lang("site.location");?></a>
			</li>
			<?php if($config->SUBWAY) {?>
			<li class="nav-two">
				<a href="#sybway_section" <?php if(element("search_type",$search)=="subway") {echo "class='current'";}?>><?php echo lang("site.subway");?></a>
			</li>
			<?php } ?>
			<li class="nav-three last">
				<a href="#google_section" <?php if(element("search_type",$search)=="google") {echo "class='current'";}?>><?php echo lang("search.total");?></a>
			</li>
		</ul>
		<div class="list-wrap">			
			<ul id="local_section"  
				<?php if(element("search_type",$search)=="" or element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {} else {echo "style='display:none;'";}?>>
				<li>
				<div class="row" style="margin:0px;">
					<?php if($config->REGION_USE){?>
					<div class="col-xs-12" style="padding:0px;">
						<select id="region" name="region" onchange="region_select(this.value);">
							<option value="">지역 선택</option>
							<?php foreach($region as $val){?>
							<option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
							<?php }?>
						</select>
					</div>
					<?php } else {?>
					<div class="col-xs-4" style="padding:0px;">
						<select id="sido" name="sido" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
					</div>
					<div class="col-xs-4" style="padding:0px;">
						<select id="gugun" name="gugun"><option value="">-</option></select>
					</div>
					<div class="col-xs-4" style="padding:0px;">
						<select id="dong" name="dong"><option value="">-</option></select>
					</div>
					<?php }?>
				</div>
				</li>
			</ul>				 
			<ul id="sybway_section" <?php if(element("search_type",$search)=="subway") {} else {echo "style='display:none;'";}?>>
				<li>
					<div class="row" style="margin:0px;">
						<div class="col-xs-4" style="padding:0px;">
							<select id="subway_local" name="subway_local" onchange="$('#station').html('<option value=\'\'>-</option>');"><option value="">-</option></select>
						</div>
						<div class="col-xs-4" style="padding:0px;">
							<select id="hosun" name="hosun"><option value="">-</option></select>
						</div>
						<div class="col-xs-4" style="padding:0px;">
							<select id="station" name="station"><option value="">-</option></select>
						</div>
					</div>							
				</li>
			 </ul>				 
			 <ul id="google_section" <?php if(element("search_type",$search)=="google") {} else {echo "style='display:none;'";}?>>
				<li>
					<div class="input-group">
						<input type="text" id="search" class="form-control ui-autocomplete-input" placeholder="<?php echo lang("site.address");?>, <?php echo lang("site.subway");?>, <?php echo lang("product.no");?>, <?php echo lang("site.title");?>" autocomplete="off" value="<?php if(element("search_type",$search)=="google") echo element("search_value",$search);?>" style="font-size: 12px;height:28px;"/>
						<div class="input-group-btn">
							<button id="go_keyword" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</li>
			 </ul>
		</div>
		<?php } ?>
	</div><!-- search_tab -->

	<!-- 좌측 검색 영역 -->
	<ul>
		<?php if($config->INSTALLATION_FLAG!="2"){?>
		<li>
			<div class="btn-group btn-group-justified btn-group-sm" role="group" data-toggle="buttons">
				<!-- input radio에 search_item을 걸면 갯수만큼 call이 나가기 때문에 안된다. -->
				<label class="btn btn-default type_label active">
					<input type="radio" name="type" class="search_item" value="" selected><?php echo lang("site.all");?>
				</label>
				<?php if($config->INSTALLATION_FLAG=="1"){?>
				<label class="btn btn-default type_label" style="color:#f39c12;">
					<input type="radio" name="type" class="search_item" value="installation"><?php echo lang('installation');?>
				</label>
				<?php }?>
				<label class="btn btn-default type_label" style="color:#D22129">
					<input type="radio" name="type" class="search_item" value="sell"><?php echo lang('sell');?>
				</label>
				<?php if(lang('full_rent')!=""){?>
				<label class="btn btn-default type_label">
					<input type="radio" name="type" class="search_item" value="full_rent"><?php echo lang('full_rent');?>
				</label>
				<?php } ?>
				<label class="btn btn-default type_label" style="color:#209F4E">
					<input type="radio" name="type" class="search_item" value="monthly_rent"><?php echo lang('monthly_rent');?>
				</label>
			</div>
		</li>
		<?php } else {?>
		<input type="hidden" name="type" value="installation">
		<?php }?>	
		<li class="<?php if($config->INSTALLATION_FLAG!="2") echo "price_range";?> price_sell">
			<div class="price_label sell_label" style="<?php if($config->INSTALLATION_FLAG=="2") echo "display:none";?>"><?php echo lang('sell');?>가</div>
			<div class="price_label installation_label"><?php echo lang('product.price.installation.sell');?></div>
			<div class="price_show">
					<input type="hidden" id="sell_start" name="sell_start">
					<input type="hidden" id="sell_end" name="sell_end">
					<div id="sell_label"></div>
			</div>
			<div style="clear:both;"></div>
			<div class="price_slider">
				<div id="sell_range" class="slider" 
					data-start="<?php echo element('sell_start',$search) ? element('sell_start',$search) : "0" ;?>" 
					data-end="<?php echo element('sell_end',$search) ? element('sell_end',$search) : $config->SELL_MAX ;?>" 
					data-min="0" 
					data-max="<?php echo $config->SELL_MAX;?>"
					data-step="1"
					data-type="sell" ></div>
			</div>
		</li>
		<li class="price_range price_full">
			<div class="price_label"><?php echo lang('full_rent');?>가</div>
			<div class="price_show">
				<input type="hidden" id="full_start" name="full_start">
				<input type="hidden" id="full_end" name="full_end">
				<div id="full_label"></div>
			</div>
			<div style="clear:both;"></div>
			<div class="price_slider">
				<div id="full_range" class="slider" 
					data-start="<?php echo element('full_start',$search) ? element('full_start',$search) : "0" ;?>" 
					data-end="<?php echo element('full_end',$search) ? element('full_end',$search) : $config->FULL_MAX ;?>" 
					data-min="0" 
					data-max="<?php echo $config->FULL_MAX;?>"
					data-step="500"
					data-type="full"></div>
			</div>
		</li>
		<li class="price_range price_rent">
			<div class="price_label"><?php echo lang("product.price.deposit");?></div>
			<div class="price_show">
				<input type="hidden" id="month_deposit_start" name="month_deposit_start">
				<input type="hidden" id="month_deposit_end" name="month_deposit_end">
				<div id="month_deposit_label"></div>
			</div>
			<div style="clear:both;"></div>
			<div class="price_slider">
				<div id="month_deposit_range" class="slider"
					data-start="<?php echo element('month_deposit_start',$search) ? element('month_deposit_start',$search) : "0" ;?>" 
					data-end="<?php echo element('month_deposit_end',$search) ? element('month_deposit_end',$search) : $config->MONTH_DEPOSIT_MAX ;?>" 
					data-min="0" 
					data-max="<?php echo $config->MONTH_DEPOSIT_MAX;?>"
					data-step="50"
					data-type="month_deposit"></div>
			</div>
			<div class="price_label"><?php echo lang('monthly_rent');?>가</div>
			<div class="price_show">
				<input type="hidden" id="month_start" name="month_start">
				<input type="hidden" id="month_end" name="month_end">
				<div id="month_label"></div>
			</div>
			<div style="clear:both;"></div>
			<div class="price_slider">
				<div id="month_range" class="slider"
					data-start="<?php echo element('month_start',$search) ? element('month_start',$search) : "0" ;?>" 
					data-end="<?php echo element('month_end',$search) ? element('month_end',$search) : $config->MONTH_DEPOSIT_MAX ;?>" 
					data-min="0" 
					data-max="<?php echo $config->MONTH_MAX;?>"
					data-step="5"
					data-type="month"></div>
			</div>
		</li>
		<li>
			<h3><?php echo lang("search.type");?></h3>
		</li>
		<?php foreach($category as $val){ ?>
			<li>
				<input type="checkbox" name="category[]" id="category_<?php echo $val->id;?>" value="<?php echo $val->id;?>" class='category_checkbox search_item' >
				<label> <?php echo $val->name;?></label>
			</li>
			<?php 
			if(isset($val->category_sub)){
				foreach($val->category_sub as $sub_val){?>
				<li class="category_sub_<?php echo $val->id;?>" style="display:none">
					<i class="fa fa-level-up fa-rotate-90"></i> <input type="checkbox" name="category_sub[]" value="<?php echo $sub_val->id;?>" main-id="<?php echo $val->id;?>" class="category_checkbox search_item"/>
					<label> <?php echo $sub_val->name;?></label>
				</li>
				<?php }?>
			<?php }?>
		<?php }?>
		<?php if($config->USE_THEME){?>
		<li>
			<h3 style="cursor:pointer;" onclick="theme_display();"><?php echo lang("product.theme")?> <i id="theme_fa" class="pull-right fa fa-chevron-down"></i></h3>
		</li>
		<?php 
		$theme_check = false;
		foreach($theme as $val){
			if(isset($val->checked)) $theme_check = true;
		?>
		<li class="theme_li" style="white-space: nowrap;display:none;">
			<input type="checkbox" name="theme[]" value="<?php echo $val->id;?>" class='category_checkbox search_item' <?php echo isset($val->checked)?$val->checked:"";?>/>
			<label> <?php echo $val->theme_name;?></label>
		</li>
		<?php }?>
		<?php if($theme_check){?><script>theme_display();</script><?php }?>
		<?php }?>
		<?php if($config->SUBWAY && $subway_line){?>
		<li>
			<h3 style="cursor:pointer;" onclick="subway_line_display();"><?php echo lang("site.subwaylinesearch");?> <i id="subway_line_fa" class="pull-right fa fa-chevron-down"></i></h3>
		</li>
			<?php
			$subway_line_check = false;
			foreach($subway_line as $val){
				if(isset($val->checked)) $subway_line_check = true;
			?>
			<li class="subway_line_li" style="white-space: nowrap;display:none;">
				<input type="checkbox" name="subway_line[]" value="<?php echo $val->hosun_id;?>" class='category_checkbox search_item' <?php echo isset($val->checked)?$val->checked:"";?>/>
				<i class="fa fa-square sub_color_<?php echo $val->hosun_id;?>"></i>
				<label> <?php echo (is_numeric($val->hosun)) ? $val->hosun.toeng("호선") : $val->hosun.toeng("선");?></label>
			</li>
			<?php }?>
			<?php if($subway_line_check){?><script>subway_line_display();</script><?php }?>			
		<?php }?>
		<?php if($danzi){?>
		<li>
			<h3>단지</h3>
		</li>
		<li style="white-space: nowrap;">
			<input type="hidden" id="danzi_temp" value="<?php echo element("danzi",$search)?>"/>
			<select id="danzi_name" name="danzi_name" class="form-control" onchange="get_danzi_name(this.value);">
				<option value="">아파트단지 선택</option>
				<?php foreach($danzi as $val){ ?>
				<option value="<?php echo $val->address_id;?>|<?php echo $val->name;?>" <?php if(element("danzi_name",$search)==$val->name) echo "selected";?>><?php echo $val->name;?></option>
				<?php }?>
			</select>
		</li>
		<?php }?>
		<?php if($config->USE_FACTORY) {?>
		<li>
			<h3 for="select-property-type">대지면적</h3>
		</li>
		<li>
			<select id="site_area" name="site_area" class="search_item form-control">
				<option value="">대지면적선택</option>
				<option value="500">500py이하</option>
				<option value="1000">500py~1000py이하</option>
				<option value="2000">1000py~2000py이하</option>
				<option value="3000">2000py~3000py이하</option>
				<option value="10000">3000py이상</option>
			</select>
		</li>
		<li>
			<h3 for="select-property-type">연면적</h3>
		</li>
		<li>
			<select id="law_area" name="law_area" class="search_item form-control">
				<option value="">연면적 선택</option>
				<option value="60">60py이하</option>
				<option value="100">60py~100py이하</option>
				<option value="200">100py~200py이하</option>
				<option value="400">200py~400py이하</option>
				<option value="1000">400py이상</option>
			</select>
		</li>	
		<!--li>
			<select id="theme" name="factory_power" class="search_item form-control margin-bottom-10">
				<option value="">전기(Kw)</option>
				<option value="1-10kw">1-10kw</option>
				<option value="11-30kw">11-30kw</option>
				<option value="31-50kw">31-50kw</option>
				<option value="51-100kw">51-100kw</option>
				<option value="101-250kw">101-250kw</option>
				<option value="251kw-300kw">251kw-300kw</option>
				<option value="300kw-500kw">300kw-500kw</option>
				<option value="500kw이상">500kw이상</option>
			</select>
		</li>
		<li>
			<select id="theme" name="factory_hoist" class="search_item form-control margin-bottom-10">
				<option value="">호이스트(ton)</option>
				<option value="0ton">0ton</option>
				<option value="1ton">1ton</option>
				<option value="2.8ton">2.8ton</option>
				<option value="3ton">3ton</option>
				<option value="5ton">5ton</option>
				<option value="7ton">7ton</option>
				<option value="10ton이상">10ton이상</option>
			</select>
		</li>
		<li>
			<select id="theme" name="factory_use" class="search_item form-control margin-bottom-10">
				<option value="">공장용도 선택</option>
				<option value="공장">공장</option>
				<option value="제조장">제조장</option>
				<option value="기타">기타</option>
			</select>
		</li-->
		<?php }?>
	</ul>
</div><!-- search_section -->