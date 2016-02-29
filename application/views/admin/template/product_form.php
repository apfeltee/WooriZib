<!-- 매물 관리 공통 폼-->
<!-- 관리자에서만 제공하는 항목 : 연락처, 담당자, 첨부파일 -->
<!-- 기본 정보 -->
<div class="portlet box grey">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-star"></i> 필수입력정보<!--<?php echo lang("site.information");?>-->
		</div>
		<div class="tools">
			<!-- 기본 정보입니다. -->
		</div>
	</div>
<!--14아파트, 12빌라 19분양권-->
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-2 control-label"><b><?php echo lang("product");?> <?php echo lang("product.category");?></b> <span class="required" aria-required="true"> * </span></label>
				<div class="col-md-10">
					<?php if($mode!="edit"){?>
						<div class="btn-group" data-toggle="buttons">
							<?php foreach($category as $key=>$val){?>
								<label class="btn btn-default <?php if($key==0) echo "active";?>">
									<input type="radio" name="category" value="<?php echo $val->id;?>"  <?php if($key==0) echo "checked";?> /> <?php echo $val->name;?>
								</label> 
							<?php }?>
					            </div>
					<?php } else {?>
						<div class="btn-group" data-toggle="buttons">
							<?php foreach($category as $key=>$val){?>
								<label class="btn btn-default  <?php if($val->id==$query->category){ echo "active";}?>">
									<input type="radio" name="category" value="<?php echo $val->id;?>"  <?php if($val->id==$query->category){ echo "checked";}?>/> <?php echo $val->name;?>
								</label> 
							<?php }?>
					            </div>					
					<?php } ?>

					<?php if($this->session->userdata("auth_id")=="1"){ echo anchor("admincategory/index", "<i class=\"fa fa-cog\"></i>");}?>
				</div>
			</div>
			<div class="form-group" id="sub_category">
				<label class="col-md-2 control-label">소분류</label>
				<div class="col-md-10">
					<div class="btn-group" data-toggle="buttons">
					<?php 
					if($mode=="edit"){
						foreach($category as $key=>$val){
							if(isset($val->category_sub)){
								foreach($val->category_sub as $sub_val){
					?>
					<label class="btn btn-default category_sub main_<?php echo $val->id;?>" style="display:none">
						<input type="radio" name="category_sub" value="<?php echo $sub_val->id;?>"/> <?php echo $sub_val->name;?>
					</label> 
					<?php
								}
							}
						}
					} else {
						foreach($category as $key=>$val){
							if(isset($val->category_sub)){
								foreach($val->category_sub as $sub_val){						
					?>
					<label class="btn btn-default category_sub main_<?php echo $val->id;?>" style="display:none">
						<input type="radio" name="category_sub" value="<?php echo $sub_val->id;?>"/> <?php echo $sub_val->name;?>
					</label>
					<?php
								}
							}
						}
					}
					?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label"><b><?php echo lang("site.title");?></b> <span class="required" aria-required="true"> * </span></label>
				<div class="col-md-10">
					<?php if($module=="admin"){?>
					<div class="input-group">
						<span class="input-group-addon "><label style="margin:0px;"><?php echo lang("site.recommand");?> <input type="checkbox" id="recommand" name="recommand" <?php if($mode=="edit"){ if($query->recommand=="1") echo "checked='checked'";}?>></label></span>
						<span class="input-group-addon"><label>급매 <input type="checkbox" id="is_speed" name="is_speed" <?php if($mode=="edit"){ if($query->is_speed=="1") echo "checked='checked'";}?>></label></span>
						<span class="input-group-addon"><label>보류 <input type="checkbox" id="is_defer" name="is_defer" <?php if($mode=="edit"){ if($query->is_defer=="1") echo "checked='checked'";}?>></label></span>
						<input type="text" name="title" class="form-control" placeholder="제목이며 앞에서 부터 중요한 단어를 작성해 주시면 더 좋습니다." value="<?php if($mode=="edit") echo $query->title;?>"/>
					</div>
					<?php } else {?>
						<input type="text" name="title" class="form-control" placeholder="제목이며 앞에서 부터 중요한 단어를 작성해 주시면 더 좋습니다." value="<?php if($mode=="edit") echo $query->title;?>"/>
					<?php }?>
				</div>
			</div>
			<?php if($module=="admin"){?>
			<div class="form-group">
				<label class="col-md-2 control-label"><?php echo lang("site.secretmemo");?></label>
				<div class="col-md-10">
					<textarea class="form-control help" name="secret"  data-toggle="tooltip" placeholder="외부에는 공개되지 않으며 관리상의 필요한 메모를 작성해 주세요."><?php if($mode=="edit") echo $query->secret;?></textarea>
				</div>
			</div>
			<?php }?>


			<div class="form-group" id="mgr_price_section">
				<label class="col-md-2 control-label"><?php echo lang("product.mgr_price");?></label>
				<div class="col-md-10">
					<!-- 공실에서만 관리비를 기본으로 0으로 세팅한다. -->
					<span class="display-none" id="mgr_price_full_rent_section">
						<input type="text" id="mgr_price_full_rent" name="mgr_price_full_rent" class="form-control input-inline input-small help" data-toggle="tooltip" title="<?php echo lang("product.mgr_price");?>(전세)" placeholder="<?php echo lang("product.mgr_price");?>(전세)" value="<?php if($mode=="edit") echo $query->mgr_price_full_rent; else {if($config->GONGSIL_FLAG) echo "0";}?>" data-use=""/>
						<small><?php echo lang('price_unit.form');?></small>
					</span>

					<input type="text" id="mgr_price" name="mgr_price" class="form-control input-inline input-small help" data-toggle="tooltip" title="<?php echo lang("product.mgr_price");?>" placeholder="<?php echo lang("product.mgr_price");?>(월세)" value="<?php if($mode=="edit") echo $query->mgr_price; else {if($config->GONGSIL_FLAG) echo "0";}?>"/>
					<small><?php echo lang('price_unit.form');?></small>

					<input type="text" id="mgr_include" name="mgr_include" class="form-control input-large input-inline help" data-toggle="tooltip" title="<?php echo lang("product.mgr_include");?>" placeholder="<?php echo lang("product.mgr_include");?>" value="<?php if($mode=="edit") echo $query->mgr_include;?>">
					
					<!--input type="number" id="park_price" name="park_price" class="form-control input-inline input-small help" data-toggle="tooltip" title="주차비" placeholder="주차비"/>
					<small><?php echo lang('price_unit.form');?></small-->
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label"><b>지역</b><span class="required" aria-required="true"> * </span></label>
				<div class="col-md-10">
					<button type="button" class="btn btn-default" data-toggle="modal" data-target="#address_modal" onclick="address_modal('<?php echo $address_text;?>','<?php echo $mode;?>')">주소찾기</button>					
					<input type="hidden" id="address_id" name="address_id" value="<?php if($mode=="edit") echo $query->address_id;?>">
					<input type="hidden" id="sido" name="sido" value="<?php if($mode=="edit") echo $address->sido;?>"/>
					<input type="hidden" id="gugun" name="gugun" value="<?php if($mode=="edit") echo $address->gugun;?>"/>
					<input type="hidden" id="dong" name="dong" value="<?php if($mode=="edit") echo $address->dong;?>"/>
					<input type="text" id="address_text" class="form-control input-inline input-medium" placeholder="주소찾기를 클릭해주세요" autocomplete="off" value="<?php if($mode=="edit") echo $address_text;?>" readonly/>
					<select id="danzi_name" name="danzi_name" class="form-control input-inline select2me display-none">
						<?php if($mode=="edit" && $danzi){?>
						<option value="">아파트단지선택</option>
							<?php foreach($danzi as $val){?>
							<option value="<?php echo $val->name;?>" <?php if($query->danzi_name==$val->name) echo "selected";?>><?php echo $val->name;?></option>
							<?php }?>
						<?php } else {?>
							<option value="">아파트단지선택</option>
						<?php } ?>
					</select>					

					</br>
					<p class="product_help">* 아파트 단지 선택 중 등록하려는 아파트가 나오지 않는경우 1544-4858 로 문의주시면 즉시 수정 가능합니다.</p>
				</div>
			</div>


			<div class="form-group">
				<label class="col-md-2 control-label"><b>상세주소</b>
					<?php if($config->SHOW_ADDRESS){?>
					<i class="fa fa-unlock" title="공개"></i>
					<?php  } else { ?>
					<i class="fa fa-lock" title="비공개"></i>
					<?php }?>
				</label>
				<div class="col-md-10">
					<input type="text" id="address" name="address" class="form-control input-inline input-small" placeholder="번지" value="<?php if($mode=="edit") echo $query->address;?>" autocomplete="off"/>
					<input type="text" name="address_unit" class="form-control input-inline input-large" placeholder="건물 / 층수 / 호수 / 현관번호 등" value="<?php if($mode=="edit") echo $query->address_unit;?>" autocomplete="off">
				</div>
			</div>
			<div class="form-group display-none" id="dongho">
				<label class="col-md-2 control-label">동/호수</label>
				<div class="col-md-10">
					<input type="text"  name="apt_dong" class="form-control input-inline input-small" placeholder="동(공개)"  value="<?php if($mode=="edit") echo $query->apt_dong;?>" autocomplete="off"/>
					<input type="text" name="apt_ho" class="form-control input-inline input-small" placeholder="호수(비공개)"  value="<?php if($mode=="edit") echo $query->apt_ho;?>" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label"><b>면적/평수</b><span class="required" aria-required="true"> * </span></label>
				<div class="col-md-10">
					<input type="number" id="road_area" name="road_area" class="operation_a form-control input-inline input-small" value="<?php if($mode=="edit") echo $query->road_area;?>">
					<span class="help-inline">㎡</span>
					<input type="number" id="road_pyoung" class="operation_p form-control input-inline input-small">
					<span class="help-inline">평</span>
					<span class="help-inline" id="road_rate"></span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label"><b>위치</b> <span class="required" aria-required="true"> * </span></label>
				<div class="col-md-10">
					<input type="hidden" id="lat" name="lat" class="form-control" value="<?php if($mode=="edit") echo $query->lat;?>"/> 
					<input type="hidden" id="lng" name="lng" class="form-control" value="<?php if($mode=="edit") echo $query->lng;?>"/>
					<button type="button" id="get_coord" class="btn btn-primary help" data-toggle="tooltip" title="주소 입력 후 클릭해 주세요.">위치 검색</button>
					<span class="error" id="marker-error" style="display:inline;"></span>
					<span class="product_help">* 위치검색이 정상적으로 작동하지 않으면 1544-4858 로 문의주시면 즉시 수정 가능합니다.</span>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-md-2 control-label" id="gmap_label"></label>
				<div class="col-md-10">
					<?php if($mode=="edit") {?>
						<div id="gmap"></div>
					<?php } else {?>
						<div id="gmap_info">
							<p><i class="fa fa-map-marker" style="font-size:40px;"></i></p>
							<div class="help-block">
							<p>주소를 입력하신 후</p>
							<p>위치 검색 버튼을 반드시 클릭해 주세요.</p>
							<p>마커를 마우스로 이동할 수 있습니다.</p>
							</div>
						</div>
						<div id="gmap" class="display-none"></div>
					<?php } ?>
				</div>
			</div>
			<!-- 연락처 관리 기능은 관리자에서만 제공한다. -->
			<?php if($module=="admin"){?>
			<?php if($this->session->userdata("auth_contact")=="Y"){?>
			<div class="form-group">
				<label class="col-md-2 control-label"><?php echo lang("site.proprietor");?> <i class="fa fa-lock"></i></label>
				<div class="col-md-10">
					<?php if($mode!="edit" || !count($contact)) {?>
						<select id="owner_type" name="owner_type[]" class="form-control input-inline input-small">
							<option value="">종류 선택</option>
							<option value="seller">매도인</option>
							<option value="buyer">매수인</option>
							<option value="lessee">임차인</option>
							<option value="lessor">임대인</option>
							<option value="broker">중개인</option>
							<option value="agent">대리인</option>
							<option value="etc">기타</option>
						</select>
						<input type="hidden" id="contacts_id" name="contacts_id[]"/>
						<input type="text" id="owner_name"  class="form-control ui-autocomplete-input input-inline input-xlarge" value="" placeholder="회원이름 검색" autocomplete="off"/>

						<button type="button" id="add_owner" class="btn blue btn-xs input-inline"><i class="fa fa-plus"></i></button>
					<?php } ?>

					<div id="add_owner_section" class="margin-top-10">
						<?php 
						if(count($contact)){
							foreach($contact as $key=>$val){ //소유주 - 추가
								if($key > 0){
									?>
									<div>
										<select id="owner_type<?php echo $key?>" name="owner_type[]" class="form-control input-inline input-small">
											<option value="">종류 선택</option>
											<option value="seller" <?php echo ($val->type=="seller")?"selected":""?>>매도자</option>
											<option value="buyer" <?php echo ($val->type=="buyer")?"selected":""?>>매수자</option>
											<option value="lessee" <?php echo ($val->type=="lessee")?"selected":""?>>임차인</option>
											<option value="lessor" <?php echo ($val->type=="lessor")?"selected":""?>>임대인</option>
											<option value="broker" <?php echo ($val->type=="broker")?"selected":""?>>중개인</option>
											<option value="agent" <?php echo ($val->type=="agent")?"selected":""?>>대리인</option>
											<option value="etc" <?php echo ($val->type=="etc")?"selected":""?>>기타</option>
										</select>
										<input type="hidden" id="contacts_id<?php echo $key?>" name="contacts_id[]" value="<?php echo $val->contacts_id?>"/>
										<input type="text" id="owner_name<?php echo $key?>"  class="form-control ui-autocomplete-input input-inline input-xlarge" value="<?php echo $val->name?>( <?php echo $val->phone?>)" placeholder="회원이름 검색" autocomplete="off"/>					
										<button type="button" class="btn red btn-xs" onclick="owner_delete(this)"><i class="fa fa-minus"></i></button></br></br>
									</div>
									<script>
										apply_autoComplete($("#owner_type<?php echo $key?>"),$("#contacts_id<?php echo $key?>"),$("#owner_name<?php echo $key?>"));
									</script>
									 <?php
								} else {
									?>
									<select id="owner_type" name="owner_type[]" class="form-control input-inline input-small">
										<option value="">종류 선택</option>
										<option value="seller" <?php echo ($val->type=="seller")?"selected":""?>>매도자</option>
										<option value="buyer" <?php echo ($val->type=="buyer")?"selected":""?>>매수자</option>
										<option value="lessee" <?php echo ($val->type=="lessee")?"selected":""?>>임차인</option>
										<option value="lessor" <?php echo ($val->type=="lessor")?"selected":""?>>임대인</option>
										<option value="broker" <?php echo ($val->type=="broker")?"selected":""?>>중개인</option>
										<option value="agent" <?php echo ($val->type=="agent")?"selected":""?>>대리인</option>
										<option value="etc" <?php echo ($val->type=="etc")?"selected":""?>>기타</option>
									</select>
									<input type="hidden" id="contacts_id" name="contacts_id[]" value="<?php echo $val->contacts_id?>"/>
									<input type="text" id="owner_name"  class="form-control ui-autocomplete-input input-inline input-xlarge" value="<?php echo $val->name?>( <?php echo $val->phone?>)" placeholder="회원이름 검색" autocomplete="off"/>					
									<button type="button" id="add_owner" class="btn blue btn-xs input-inline"><i class="fa fa-plus"></i></button>
									 <?php
								}
							?>
							<?php
							}
						}
						?>
					</div>
				</div>
			</div>
			<?php }?>
						<?php }?>
			<!--!가격및기타-->
			<div class="form-group">
				<label class="col-md-2 control-label"><b><?php echo lang("product.type");?></b> <span class="required" aria-required="true"> * </span></label>
				<div class="col-md-10">
					<!--<div class="btn-group" data-toggle="buttons">-->
					<div class="btn-group">
						<?php if($config->INSTALLATION_FLAG!="0"){?>
							<label class="btn btn-default <?php if($mode=="edit" && $query->type=="installation") echo "active";?>">
								<input type="radio" name="type" value="installation" <?php if($mode=="edit" && $query->type=="installation") echo "checked";?> /> <?php echo lang('installation');?>
							</label> 
						<?php }?>
						<?php if($config->INSTALLATION_FLAG!="2"){?>
							<?php if(lang('sell')!=""){?>
								<label class=" btn-default <?php if($mode=="edit" && $query->type=="sell") echo "active";?>">
									<input type="radio" name="type" value="sell" <?php if($mode=="edit" && $query->type=="sell") echo "checked";?> onclick="div_OnOff('1');"/> <?php echo lang('sell');?>
								</label> 							
							<?php }?>
							<?php if(lang('full_rent')!=""){?>
								<label class=" btn-default <?php if($mode=="edit" && $query->type=="full_rent") echo "active";?>">
									<input type="radio" name="type" value="full_rent" <?php if($mode=="edit" && $query->type=="full_rent") echo "checked";?>  onclick="div_OnOff('4');"/> <?php echo lang('full_rent');?>
								</label>
							<?php }?>
							<?php if(lang('monthly_rent')!=""){?>
								<label class=" btn-default <?php if($mode=="edit" && $query->type=="monthly_rent") echo "active";?>">
									<input type="radio" name="type" value="monthly_rent" <?php if($mode=="edit" && $query->type=="monthly_rent") echo "checked";?>  onclick="div_OnOff('3');"/> <?php echo lang('monthly_rent');?>
								</label>
							<?php }?>
							<!--
							<?php if(lang('rent')!=""){?>
								<label class="btn btn-default   <?php if($mode=="edit" && $query->type=="rent") echo "active";?>">
									<input type="radio" name="type" value="rent" <?php if($mode=="edit" && $query->type=="rent") echo "checked";?>/> <?php echo lang('rent');?>
								</label>
							<?php }?>
							-->
						<?php }?>						
					</div>
				</div>
			</div>
			<div class="form-group">
				<input type="hidden" id="part" name="part" class="form-control input-inline input-small"  value="<?php if($mode=="edit") echo $query->part;?>" />
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label"><b>가격</b> <span class="required" aria-required="true"> * </span></label>
				<div class="col-md-10">
					 <div id="sell_price_area" style="margin-bottom:10px;">
						<input type="number" id="sell_price" name="sell_price" class="operation_a form-control input-inline input-small help" data-toggle="tooltip" title="<?php echo lang('product.price.sell.sell');?>" placeholder="<?php echo lang('product.price.sell.sell');?>" value="<?php if($mode=="edit") echo $query->sell_price;?>"/>
						<small><?php echo lang('price_unit.form');?></small>

						<span id="lease_price_section">
							<input type="number" id="lease_price" name="lease_price" class="form-control input-inline input-small help"  data-toggle="tooltip" title="<?php echo lang('product.price.sell.lease');?>" placeholder="<?php echo lang('product.price.sell.lease');?>" value="<?php if($mode=="edit") echo $query->lease_price;?>"/>
							<small><?php echo lang('price_unit.form');?></small>
						</span>
					</div>
					<div id="full_price_area" style="margin-bottom:10px;">
						<input type="number" id="full_rent_price" name="full_rent_price" class="operation_a form-control input-inline input-small" title="<?php echo lang('product.price.fullrent');?>" placeholder="<?php echo lang('product.price.fullrent');?>" value="<?php if($mode=="edit") echo $query->full_rent_price;?>"/>
						<small><?php echo lang('price_unit.form');?></small>


							<input type="number" id="lease_price" name="lease_price" class="form-control input-inline input-small help"  data-toggle="tooltip" title="<?php echo lang('product.price.sell.lease');?>" placeholder="<?php echo lang('product.price.sell.lease');?>" value="<?php if($mode=="edit") echo $query->lease_price;?>"/>
							<small><?php echo lang('price_unit.form');?></small>

					</div>
					<div id="rent_price_area" style="margin-bottom:10px;">
						<input type="number" id="monthly_rent_deposit" name="monthly_rent_deposit" class="form-control input-inline input-small" title="<?php echo lang('product.price.rent.deposit');?>" placeholder="<?php echo lang('product.price.rent.deposit');?>" value="<?php if($mode=="edit") echo $query->monthly_rent_deposit;?>"/>
						<small><?php echo lang('price_unit.form');?></small>

						<input type="number" id="monthly_rent_price" name="monthly_rent_price" class="operation_a form-control input-inline input-small" title="<?php echo lang('product.price.rent');?>" placeholder="<?php echo lang('product.price.rent');?>" value="<?php if($mode=="edit") echo $query->monthly_rent_price;?>"/>
						<small><?php echo lang('price_unit.form');?> <button type="button" class="btn blue btn-xs input-inline" id="add_monthly"><i class="fa fa-plus"></i></button></small>

						<span id="premium_price_section">
							<input type="number" id="premium_price" name="premium_price" class="form-control input-inline input-small help" data-toggle="tooltip" title="<?php echo lang('product.price.premium');?>" placeholder="<?php echo lang('product.price.premium');?>" value="<?php if($mode=="edit") echo $query->premium_price;?>"/>
							<small><?php echo lang('price_unit.form');?></small>
						</span>

						<span id="monthly_rent_deposit_min_section">
							<input type="number" id="monthly_rent_deposit_min" name="monthly_rent_deposit_min" class="form-control input-inline input-small help" data-toggle="tooltip" title="보증금 한도액" placeholder="<?php echo lang('product.price.monthly_rent_deposit_min');?>" value="<?php if($mode=="edit") echo $query->monthly_rent_deposit_min;?>"/> 
							<small><?php echo lang('price_unit.form');?></small>
						</span>
						<div id="monthly_add_section" class="margin-top-10">
							<?php
							if($mode=="edit"){
								foreach($query->add_price as $val){
							?>
							<div class="margin-top-10">
								<input type="number" name="monthly_rent_deposit_add[]" class="form-control input-inline input-small" title="<?php echo lang('product.price.rent.deposit');?>" placeholder="<?php echo lang('product.price.rent.deposit');?>" value="<?php echo $val->monthly_rent_deposit;?>"/> <small><?php echo lang('price_unit.form');?></small>

								<input type="number" name="monthly_rent_price_add[]" class="operation_a form-control input-inline input-small" title="<?php echo lang('product.price.rent');?>" placeholder="<?php echo lang('product.price.rent');?>" value="<?php echo $val->monthly_rent_price;?>"/> <small><?php echo lang('price_unit.form');?> <button type="button" class="btn red btn-xs input-inline" onclick="$(this).parent().parent().remove();"><i class="fa fa-minus"></i></button></small>
							</div>
							<?php 
								}
							}?>
						</div>
					</div>
					<div>
						<input type="checkbox" name="price_adjustment" class="form-control input-inline" value="1" <?php if($mode=="edit" && $query->price_adjustment) echo "checked";?>/>
						<small>가격조정가능</small>
					</div>
				</div>
			</div>
<!--//가격및기타-->



<div id="stype01" style="display:block">
			<div id="roon_cnt " class="form-group">
				<label class="col-md-2 control-label">세입자유무</label>
				<div class="col-md-10">

<label>유 <input type="radio" id="t_tenants" name="t_tenants" value="1"<?php if($mode=="edit"){ if($query->t_tenants=="1") echo "checked='checked'";}?>></label>
<label>무 <input type="radio" id="t_tenants" name="t_tenants" value="2" <?php if($mode=="edit"){ if($query->t_tenants=="2") echo "checked='checked'";}?>></label>
				<label >임대현황</label>
					<input type="text" id="t_leasingstatus" name="t_leasingstatus" class="form-control input-inline input-small" placeholder="보증금 / 월세" value="<?php if($mode=="edit") echo $query->t_leasingstatus;?>" autocomplete="off"/>
				</div>
			</div>
</div>
<div id="stype03" style="display:none">
			<div id="roon_cnt " class="form-group">
				<label class="col-md-2 control-label">보증금조절여부</label>
				<div class="col-md-10">
<label>가능 <input type="radio" id="t_deposit" name="t_deposit" value="1"<?php if($mode=="edit"){ if($query->t_deposit=="1") echo "checked='checked'";}?>></label>
<label>불가능 <input type="radio" id="t_deposit" name="t_deposit" value="2" <?php if($mode=="edit"){ if($query->t_deposit=="2") echo "checked='checked'";}?>></label>
				</div>
			</div>
</div>
<!--
			<div id="roon_cnt " class="form-group">
				<label class="col-md-2 control-label">프리미엄</label>
				<div class="col-md-10">
					<input type="text" id="t_premium" name="t_premium" class="form-control input-inline input-small" placeholder="예)200만원" value="<?php if($mode=="edit") echo $query->t_premium;?>" autocomplete="off"/>만원
				</div>
			</div>
-->
			<div id="roon_cnt" class="form-group">
				<label class="col-md-2 control-label">방개수</label>
				<div class="col-md-10">
					<select id="bedcnt" name="bedcnt" class="form-control input-inline input-small help select2me">
						<?php for($i=0;$i<=10;$i++){?>
						<option value="<?php echo $i;?>" <?php if($mode=="edit"){ if($i==$query->bedcnt) echo "selected";}?>><?php echo lang("product.bedcnt");?> <?php echo $i;?>실</option>
						<?php }?>
					</select>
				<label >총층수</label>
					<input type="text" id="current_floor" name="current_floor" class="form-control input-inline input-xsmall help"  data-toggle="tooltip" title="1,2,3... 또는 지하, 반지하, 저, 중, 고 등을 입력하며 입력하지 않을 경우에는 표시되지 않습니다." autocomplete="off" value="<?php if($mode=="edit") echo $query->current_floor;?>"/> 
					<?php echo lang("floor_unit");?>
					<span id="current_floor_text"></span>

					<select type="text" id="total_floor" name="total_floor" class="form-control input-inline input-small help select2me">
						<?php for($i=0;$i<=100;$i++){?>
						<option value="<?php echo $i;?>" <?php if($mode=="edit"){ if($i==$query->total_floor) echo "selected";}?>><?php echo $i;?><?php echo lang("product.f");?></option>
						<?php }?>
					</select>
				</div>
			</div>


			<div id="roon_cnt" class="form-group">
				<label class="col-md-2 control-label">방향</label>
				<div class="col-md-10">
					<input type="text" id="t_direction" name="t_direction" class="form-control input-inline input-small" placeholder="방향" value="<?php if($mode=="edit") echo $query->t_direction;?>" autocomplete="off"/>
				<label >욕실수</label>
					<select id="bathcnt" name="bathcnt" class="form-control input-inline input-small help select2me">
						<?php for($i=0;$i<=5;$i++){?>
						<option value="<?php echo $i;?>" <?php if($mode=="edit"){ if($i==$query->bathcnt) echo "selected";}?>><?php echo lang("product.bathcnt");?> <?php echo $i;?>실</option>
						<?php }?>
					</select>
				</div>
			</div>

			<div class="form-group" id="enter_year_section">
				<label class="col-md-2 control-label"><!--<?php echo lang("product.enter_year");?>-->입주가능일</label>
				<div class="col-md-10">
					<input type="text" class="form-control input-inline input-large" id="enter_year" name="enter_year" placeholder="예: 2014년 2월 , 바로 입주, 7일 이내 등등" value="<?php if($mode=="edit") echo $query->enter_year;?>">
					<span id="enter_year_text"></span>
				</div>
			</div>


		</div> <!-- form-body -->
	</div> <!-- portlet-body -->
</div>
<!-- 기본 정보 -->


<!-- 추가 정보 -->
<div class="portlet box grey">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-info-circle"></i> 추가정보
		</div>
		<div class="tools" id="product_info"></div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">

			<div class="form-group" id="enter_year_section">
				<label class="col-md-2 control-label">총세대수</label>
				<div class="col-md-10">
										<input type="text" id="t_households" name="t_households" class="form-control input-inline input-small" placeholder="예)1 " value="<?php if($mode=="edit") echo $query->t_households;?>" autocomplete="off"  data-toggle="tooltip"/>세대
					<span id="enter_year_text"></span>
				</div>
			</div>
			<div class="form-group" id="enter_year_section">
				<label class="col-md-2 control-label">현관구조</label>
				<div class="col-md-10">
										<input type="text" id="t_porchstructure" name="t_porchstructure" class="form-control input-inline input-small" placeholder="예)계단식 복도식 " value="<?php if($mode=="edit") echo $query->t_porchstructure;?>" autocomplete="off"  data-toggle="tooltip"/>
					<span id="enter_year_text"></span>
				</div>
			</div>

			<div class="form-group" id="enter_year_section">
				<label class="col-md-2 control-label">총동수</label>
				<div class="col-md-10">
										<input type="text" id="t_totalinitiates" name="t_totalinitiates" class="form-control input-inline input-small" placeholder="예)2동 " value="<?php if($mode=="edit") echo $query->t_totalinitiates;?>" autocomplete="off"  data-toggle="tooltip"/>동
					<span id="enter_year_text"></span>
				</div>
			</div>
			<div class="form-group" id="enter_year_section">
				<label class="col-md-2 control-label">총주차대수</label>
				<div class="col-md-10">
										<input type="text" id="t_parkinglotcan" name="t_parkinglotcan" class="form-control input-inline input-large" placeholder="예)총 몇대 / 세대당 몇대" value="<?php if($mode=="edit") echo $query->t_parkinglotcan;?>" autocomplete="off"  data-toggle="tooltip"/>
					<span id="enter_year_text"></span>
				</div>
			</div>
			<div class="form-group" id="heating_section">
				<label class="col-md-2 control-label">난방방식</label>
				<div class="col-md-10">
					<select  id="heating" name="heating" class="form-control input-inline input-small help select2me" data-id="<?php if($mode=="edit") echo $query->heating;?>">
						<option value="">- 입력안함 -</option>
					</select>
										<!--<input type="text" id="t_heatingsystem" name="t_heatingsystem" class="form-control input-inline input-large" placeholder="예)개별난방,중앙난방등 " value="<?php if($mode=="edit") echo $query->t_heatingsystem;?>" autocomplete="off"  data-toggle="tooltip"/>-->
					<span id="enter_year_text"></span>
				</div>
			</div>
			<div class="form-group" id="enter_year_section">
				<label class="col-md-2 control-label">건설사</label>
				<div class="col-md-10">
										<input type="text" id="t_erection" name="t_erection" class="form-control input-inline input-large" placeholder="예)현대건설 " value="<?php if($mode=="edit") echo $query->t_erection;?>" autocomplete="off"  data-toggle="tooltip"/>
					<span id="enter_year_text"></span>
				</div>
			</div>
			<div class="form-group" id="enter_year_section">
				<label class="col-md-2 control-label">준공년도</label>
				<div class="col-md-10">
										<input type="text" id="t_yearbuilt" name="t_yearbuilt" class="form-control input-inline input-large" placeholder="예)예: 2014년 2월 " value="<?php if($mode=="edit") echo $query->t_yearbuilt;?>" autocomplete="off"  data-toggle="tooltip"/>
					<span id="enter_year_text"></span>
				</div>
			</div>
			<div class="form-group display-none" id="t_interimpayments">
				<label class="col-md-2 control-label">납부중도금 채권액</label>
				<div class="col-md-10">
										<input type="text" id="t_interimpayments" name="t_interimpayments" class="form-control input-inline input-small" placeholder="예)예: 200만원 " value="<?php if($mode=="edit") echo $query->t_interimpayments;?>" autocomplete="off"  data-toggle="tooltip"/> 만원
					<span id="enter_year_text"></span>
				</div>
			</div>
		</div> <!-- form-body -->
	</div> <!-- portlet-body -->
</div>
<!-- 추가 정보 -->



























<!-- 설명 -->
<div class="portlet box grey">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square"></i> 매물설명
		</div>
		<!--
		<div class="tools">
			에디터에서 이미지 버튼을 클릭하시면 사진을 업로드할 수 있습니다.
		</div>
		-->
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-2 control-label">설명</label>
				<div class="col-md-10">
					<!--<textarea name="content" class="form-control" rows="5"><?php if($mode=="edit") echo $query->content;?></textarea> 2016-02-22 에디터사용하지않도록-->
					<textarea name="content_01" class="form-control" rows="5"><?php if($mode=="edit") echo $query->content;?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label">키워드</label>
				<div class="col-md-10">
					<input type="text" id="tag" name="tag" class="form-control help" placeholder=", 콤마로 구분해 주세요"  data-toggle="tooltip" title="메인사진에 3번째 키워드까지 표시되며 블로그 등록시 키워드 등록" value="<?php if($mode=="edit") echo $query->tag;?>"> 
				</div>
			</div>
			<?php if($module=="admin"){?>
			<div class="form-group">
				<label class="col-md-2 control-label">담당자 및 회원 검색</label>
				<div class="col-md-10">
					<input type="text" id="member_name" class="form-control help inline" value="" data-toggle='tooltip' placeholder="회원이름 검색" autocomplete="off" class="ui-autocomplete-input" style="max-width:350px;"/>
					<button id="go_member_name" class="btn btn-default" type="button" style="margin-bottom:4px;"><i class="fa fa-arrow-down"></i> 검색적용</button>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label"><b><?php echo lang("product.owner");?></b> <span class="required" aria-required="true"> * </span></label>
				<div class="col-md-10">
					<input type="hidden" id="member_id" name="member_id" value="<?php if($mode=="edit") { echo $query->member_id;} else {echo $this->session->userdata("admin_id");}?>"/>
					<input type="hidden" id="member_id_temp"/>
					<input type="hidden" id="member_info_temp"/>
					<?php if($mode=="edit"){?>
					<input type="text" id="member_info" class="form-control help" value="<?php echo $query->member_name;?> (<?php echo $query->member_email;?>, <?php echo $query->member_phone;?>)" readonly/>
					<?php } else {?>
					<input type="text" id="member_info" class="form-control help" value="<?php echo $this->session->userdata("admin_name");?> (<?php echo $this->session->userdata("admin_email");?>, <?php echo $this->session->userdata("admin_email");?>)" readonly/>
					<?php }?>
				</div>
			</div>
			<?php }?>
		</div> <!-- form-body -->
	</div> <!-- portlet-body -->
</div>
<!-- 설명 -->

<!-- 미디어 섹션 -->
<div class="portlet box grey">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-picture-o"></i> 미디어
		</div>
		<div class="tools">
			사진은 자동으로 사이즈가 조정됩니다. 사진을 일부러 줄여서 업로드할 필요가 없습니다.
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<?php
			if(MobileCheck()){?>
				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo lang("site.photo");?></label>
					<div class="col-md-10">
						<span class="btn btn-primary btn-file">
						사진 촬영 또는 업로드<input type="file" name="file" accept="image/*; capture=camera/picture"><br/>
						</span>
						<div class="help-inline">* 첫번째 이미지는 대표이미지로 사용 됩니다. (사진을 마우스로 옮기시면 순서가 변경이 됩니다)</div>
						<pre id="console" style="display:none;"></pre>
						<ul class="row" id="list"></ul>
					</div>
				</div>
			<?php } else {?>
				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo lang("site.photo");?></label>
					<div class="col-md-10">
						<button type="button" id="browse" class="btn btn-primary"><i class="fa fa-file-image-o"></i> 멀티 파일 선택</button>
						<?php if($mode=="edit"){?>
						<button type="button" class="btn btn-danger" onclick="gallery_all_delete(<?php echo $query->id;?>)"><i class="fa fa-times"></i> 사진 모두 삭제</button>
						<?php }?>
						<div class="help-inline">* 첫번째 이미지는 대표이미지로 사용 됩니다. (사진을 마우스로 옮기시면 순서가 변경이 됩니다)</div>
						<pre id="console" style="display:none;"></pre>
						<ul class="row" id="list"></ul>
					</div>
				</div>
			<?php }?>
			<div class="form-group display-none" id="video_url_section">
				<label class="col-md-2 control-label">유튜브 주소 <a href="http://youtu.be/Fa1te_bbb8w" target="_blank"><i class="fa fa-question-circle"></i></a></label>
				<div class="col-md-10">
					<input type="text" id="video_url" name="video_url" class="form-control help" placeholder="유튜브에서 가져온 동영상 주소"  data-toggle="tooltip" title="유투브에서 가져온 동영상 주소" value="<?php if($mode=="edit") echo $query->video_url;?>"> 
				</div>
			</div>
			<div class="form-group display-none" id="vr_section">
				<label class="col-md-2 control-label">VR 파노라마 주소 </label>
				<div class="col-md-10">
					<input type="text" name="panorama_url" class="form-control help" placeholder="파노라마가 등록된 URL"  data-toggle="tooltip" title="VR 파노라마가 등록된 URL" value="<?php if($mode=="edit") echo $query->panorama_url;?>">
				</div>
			</div>
			<?php if($module=="admin"){?>
			<div class="form-group">
				<label class="col-md-2 control-label">첨부파일</label>
				<div class="col-md-10">
					<div class="help-block">* 업로드가능한 파일 : doc,docx,hwp,ppt,pptx,pdf,zip,txt,jpg,png</div>
					<div id="file_section" class="form-inline">
						<div class="multi-form-control-wrapper">
							<input type="file" name="userfile[]" class="form-control input-inline input-xlarge" placeholder="첨부파일선택" autocomplete="off" style="height:auto"/> <button type="button" id="add_file" class="btn blue btn-xs input-inline"><i class="fa fa-plus"></i></button>
						</div>
					</div>
				</div>
			</div>
			<?php }?>
		</div> <!-- form-body -->
	</div>
</div>
<!-- 미디어 섹션 -->

<!-- 관리자용 미디어 섹션 -->
<?php if($module=="admin"){?>
<div class="portlet box grey">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-picture-o"></i> 관리자 전용 이미지
		</div>
		<div class="tools">
			관리자만 볼 수 있는 관리용 이미지 입니다.
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-2 control-label"><?php echo lang("site.photo");?></label>
				<div class="col-md-10">
					<button type="button" id="browse_admin" class="btn btn-primary"><i class="fa fa-file-image-o"></i> 멀티 파일 선택</button>
					<?php if($mode=="edit"){?>
					<button type="button" class="btn btn-danger" onclick="gallery_all_delete_admin(<?php echo $query->id;?>)"><i class="fa fa-times"></i> 사진 모두 삭제</button>
					<?php }?>
					<div class="help-inline">* 사진을 마우스로 옮기시면 순서가 변경이 됩니다</div>
					<pre id="console_admin" style="display:none;"></pre>
					<ul class="row" id="list_admin"></ul>
				</div>
			</div>
		</div> <!-- form-body -->
	</div>
</div>
<?php }?>
<!-- 관리자용 미디어 섹션 -->

<div id="address_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" style="padding:0">
				<div class="body">
					<div class="bg-primary text-center">
						<div style="padding:7px 0;">
							<span id="label_text">시도를 선택하세요<span>
						</div>		
					</div>
					<div class="select_label bg-info text-center" style="padding:5px 0 25px 0">
						<strong id="sido_label"><span class="col-xs-4">시도<i class="ion-chevron-right pull-right"></i></span></strong>
						<strong id="gugun_label"><span class="col-xs-4"><strong>구군</strong><i class="ion-chevron-right pull-right"></i></span></strong>
						<strong id="dong_label"><span class="col-xs-4"><strong>읍면동</strong></span></strong>		
					</div>
					<div class="separator-fields"></div>
					<div class="text-center">
						<div class="btn-group-vertical" id="sido_section">
							<ul>
								<li>
									<div class="btn-group-vertical">
										<?php foreach($sido as $val){?>
										<button type="button" class="btn btn-default" onclick="get_gugun(this,'<?php echo $val->sido?>');"><?php echo $val->sido?></button>
										<?php }?>
									</div>
								</li>
							</ul>
						</div>
						<div class="btn-group-vertical" id="gugun_section">
							<ul>
								<li>
									<div class="btn-group-vertical"></div>
								</li>
							</ul>
						</div>
						<div class="btn-group-vertical" id="dong_section">
							<ul>
								<li>
									<div class="btn-group-vertical"></div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
/*직업 view*/
function div_OnOff(selectList){

	var obj1 = document.getElementById("stype01");
	var obj2 = document.getElementById("stype02");
	var obj3 = document.getElementById("stype03");
	var obj4 = document.getElementById("stype04");
	var obj5 = document.getElementById("stype05");


	if( selectList == "1" ) { // 학생 리스트
		obj1.style.display = "block"; 
		obj2.style.display = "none";
		obj3.style.display = "none";
		obj4.style.display = "none";
		obj5.style.display = "none";
	}else if(selectList == "2" ){
		obj1.style.display = "none"; 
		obj2.style.display = "block";
		obj3.style.display = "none";
		obj4.style.display = "none";
		obj5.style.display = "none";
	}else if(selectList == "3" ){
		obj1.style.display = "none"; 
		obj2.style.display = "none";
		obj3.style.display = "block";
		obj4.style.display = "none";
		obj5.style.display = "none";
	}else if(selectList == "4" ){
		obj1.style.display = "none"; 
		obj2.style.display = "none";
		obj3.style.display = "none";
		obj4.style.display = "block";
		obj5.style.display = "none";
	}else if(selectList == "5" ){
		obj1.style.display = "none"; 
		obj2.style.display = "none";
		obj3.style.display = "none";
		obj4.style.display = "none";
		obj5.style.display = "block";
	} else { //디폴트
		obj1.style.display = "block"; 
		obj2.style.display = "none";
		obj3.style.display = "none";
		obj4.style.display = "none";
		obj5.style.display = "none";
	}

}

</script>