<span id="print_area">
	<div class="is_print">
		<h4><?php echo $query->id;?>. <?php echo $query->title;?></h4>
	</div>
	<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"><?php echo lang("site.information");?>
		<?php if($config->DATE_DISPLAY){?>
		<span class="pull-right" style="font-weight:normal;font-size:12px;padding-right:5px;"> <?php echo lang("site.regdate");?> : <?php echo date("Y-m-d",strtotime($query->date));?> | <?php echo lang("product.moddate");?> : <?php echo date("Y-m-d",strtotime($query->moddate));?>
		</span>
		<?php }?>
	</h4>

	<table class="border-table">
		<tr>
			<th width="20%"><?php echo lang("product.category");?></th>
			<td width="30%">
				<?php echo $category_one->name;?>
				<?php if($query->part=="N") {?>[<?php echo lang("site.all");?>]<?php }?>
			</td>
			<th width="20%"><?php echo lang("product.no");?></th>
			<td width="30%">
				<strong><?php echo $query->id;?></strong>
			</td>		
		</tr>
		<tr>
			<th width="20%"><?php echo lang("site.price");?></th>
			<td width="80%" colspan="3" class="price_detail_wrapper" id="pulsate-once-target">
				<?php echo price($query,$config,true);?>
				<?php if($query->monthly_rent_deposit_min!=false) {echo "(".lang('product.price.monthly_rent_deposit_min').": ".number_format($query->monthly_rent_deposit_min)." 만원)";}?>
				<?php if(price_description($query)!=""){?><span style='color:#454545;'>(<?php echo price_description($query);?>)</span><?php }?>
				<?php echo ($query->price_adjustment) ? "(가격조정가능)":"";?>
			</td>
		</tr>

		<?php if($form->mgr_price && $query->mgr_price!="") {?>
		<tr>
			<th width="20%"><?php echo lang("product.mgr_price");?></th>
			<td width="80%" colspan="3">
				<!-- 일반적으로 주차비를 별도로 받지 않기 때문에 빼고 관리비 포함 내역을 기재하도록 수정하였다. 2015년 9월 20일 강대중 -->
				<?php
					if(is_numeric($query->mgr_price)){
						$mgr_price = number_format($query->mgr_price).lang("price_unit");
					} else {
						$mgr_price = $query->mgr_price;
					}

					if($query->mgr_price_full_rent!=""){
						if(is_numeric($query->mgr_price_full_rent)){
							$mgr_price_full_rent = number_format($query->mgr_price_full_rent).lang("price_unit");
						}
						else{
							$mgr_price_full_rent = $query->mgr_price_full_rent;
							
						}
						$mgr_price = $mgr_price_full_rent."(전세) / ".$mgr_price."(월세)";
					}
					echo $mgr_price;
				?>
				<?php if($query->mgr_include!=""){?>(<?php echo lang("product.mgr_include");?> : <?php echo $query->mgr_include;?>)<?php } ?>
			</td>
		</tr>
		<?php }?>
		<tr>
		<?php if($config->GONGSIL_FLAG){?>
			<th width="20%"><?php echo lang("site.address");?></th>
			<td width="30%">
				<?php 
					$danzi_info = (isset($danzi)) ? " ".$danzi->name." ".$danzi->area."(㎡) " : "";
					echo toeng($query->address_name).$danzi_info;
					if($config->SHOW_ADDRESS) {
						echo $query->address;
					}

					if($this->session->userdata("admin_id")!="") {
						echo " <font color='red' style='padding:1px 3px;background-color:#efefef;border-radius:5px;'><i class=\"fa fa-user-secret\" title=\"관리자로그인시에만 보입니다.\"></i> ".$query->address."</font>";
					}

				?>
			</td>
			<th width="20%"><?php echo lang("product.address_detail");?></th>
			<td width="30%">
				<?php 
					if($config->SHOW_ADDRESS) {
						echo " " . $query->address_unit;
					}
				?>
			</td>
		<?php } else {?>
			<th width="20%"><?php echo lang("site.address");?></th>
			<td width="80%" colspan="3">
				<?php 
					$danzi_info = (isset($danzi)) ? " ".$danzi->name." ".$danzi->area."(㎡) " : "";
					echo toeng($query->address_name).$danzi_info;
					if($config->SHOW_ADDRESS) {
						echo " ".$query->address;
					} 
				?>
			</td>
		<?php } ?>
		</tr>
		<?php if($query->apt_dong){?>
		<tr>			
			<th width="20%">동/호수</th>
			<td width="80%" colspan="3"><?php echo $query->apt_dong;if($this->session->userdata("admin_id")!="") echo " ".$query->apt_ho;?></td>
		</tr>
		<?php }?>
		<tr <?php if($config->SUBWAY=="0") echo "style='display:none;'";?>>
			<th width="20%"><?php echo lang("site.subway");?></th>
			<td width="80%" colspan="3">
				<?php foreach($product_subway as $sub){
					?>
						<span class="subway sub_<?php echo $sub->hosun_id?>" title="<?php echo $sub->hosun?> 호선"><?php echo $sub->name?></span> <?php echo round($sub->distance,1)?> ㎞
					<?php
				}?>
			</td>
		</tr>
		<?php if(isset($near_data)){?>
			<?php foreach($near_data as $key=>$val){?>
			<tr>
				<th width="20%"><?php echo $key;?></th>
				<td width="80%" colspan="3">
					<?php foreach($val as $near){?>
					<span class="near" title="<?php echo $near->title?>"><?php echo $near->title?></span> <?php echo round($near->distance,1)?> ㎞
					<?php }?>
				</td>
			</tr>							
			<?php }?>
		<?php }?>
	</table>

	<?php if($this->session->userdata("admin_id")!="") {?>
	<div class="alert alert-warning">
	<div class="help-block">관리자로그인시에만 보이는 정보입니다. (관리자시스템에서 로그아웃을 하신 후에는 보이지 않습니다.)</div>
	<table class="border-table">
		<tr>
			<th width="20%"><?php echo lang("site.address");?></th>
			<td width="80%">
				<?php echo $query->address?>
			</td>
		</tr>
		<tr>
			<th width="20%"><?php echo lang("site.proprietor");?></th>
			<td width="80%">
				<?php
				if($query->owner_name) {
					echo $query->owner_name." ".$query->owner_phone." ";
				}?>
				<?php
				if(isset($contact)){
					foreach($contact as $val){
						if(end($contact)==$val) echo $val->name." ".$val->phone."";
						else echo $val->name." ".$val->phone.", ";
					}
				}
				?>
			</td>
		</tr>
		<tr>
			<th width="20%"><?php echo lang("site.secretmemo");?></th>
			<td width="80%">
				<div class="help-inline"><?php echo $query->secret;?></div>
			</td>
		</tr>			
	</table>
	</div>
	<?php }?>

	<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"><?php echo lang("site.etc");?></h4>

	<table class="border-table">
		<?php if($query->last_check_date){?>
		<tr>
			<th width="20%"><?php echo lang("product");?> 확인일</th>
			<td width="80%" colspan="3" class="text-danger">
				<strong><?php echo date("Y-m-d",strtotime($query->last_check_date));?></strong>
			</td>
		</tr>
		<?php }?>	
		<?php if($config->USE_THEME && $theme){?>
		<tr>
			<th width="20%">테마</th>
			<td width="80%" colspan="3">
				<?php
				foreach($theme as $val){
					echo '<font style="color:black;margin-right:20px;font-weight:400;"><img src="/assets/common/img/option_check.png"> '.$val->theme_name."</font>";
				}
				?>
			</td>
		</tr>							
		<?php }?>
		<?php if($category_one->template!=""){?>
		<tr>
			<th width="20%"><?php echo lang("site.option");?></th>
			<td width="80%" colspan="3">
				<?php 
				if($config->OPTION_FLAG=="1"){
					$cate = explode(",",$category_one->template);
					
					foreach($cate as $key=>$val){

						if (strpos($query->option,$val) !== false) {
							echo ' <font style="color:black;margin-right:20px;font-weight:400;"><img src="/assets/common/img/option_check.png"> ' . $val . "</font>";
						} 

					}
				} else {
						echo $query->option;
				}
				?>
			</td>
		</tr>
		<?php }?>
		<?php if($form->real_area || $form->law_area) {?>
			<?php if($query->real_area!= 0 || $query->law_area!= 0){?>
			<tr>
				<th width="20%"><?php echo lang("product.area");?></th>
				<td width="80%" colspan="3">
					
					<!-- 실면적 사용여부 체크 -->
					<?php if($form->real_area) {?>
						<span class='label label-info '>
						<?php if($query->part=="Y") {echo lang("product.realarea");} else {echo "건축면적";}?> 
						</span>
						<?php 
							echo area_view($query->real_area, "");
						?>
					<?php }?>
					
					<!-- 계약면적 사용여부 체크 -->
					<?php if($form->law_area) {?>
						<span class='label label-info '>
						<?php if($query->part=="Y") {echo lang("product.lawarea");} else {echo "연면적";}?> 
						</span>
						<?php 
							echo area_view($query->law_area, "");
							echo price_product_area($query);
						?>
					<?php }?>

				</td>
			</tr>
			<?php }?>
		<?php }?>
		<?php if($form->loan && $query->loan!=""){?>
		<tr>
			<th width="20%">기대출금</th>
			<td width="80%" colspan="3">
				<?php echo $query->loan;?>
			</td>
		</tr>
		<?php }?>
		<?php if($query->part=="N") {?>
			<tr>
				<th width="20%">대지면적</th>
				<td width="30%">
					<?php echo area_view($query->land_area, "");?>
				</td>
				<th width="20%">도로면적</th>
				<td width="30%">
					<?php echo area_view($query->road_area, "");?>
				</td>
			</tr>
			<tr>
				<th width="20%">총면적</th>
				<td width="80%" colspan="3">
					<?php echo area_view($query->land_area+$query->road_area, "");?>
					<?php echo price_land_area($query);?>
				</td>
			</tr>					
			<?php if($query->ground_use!="" || $query->ground_aim!="") {?>
			<tr>
				<th width="20%">용도지역</th>
				<td width="30%">
					<?php echo $query->ground_use;?>
				</td>
				<th width="20%">지목</th>
				<td width="30%">
					<?php echo $query->ground_aim;?>
				</td>								
			</tr>
			<?php } ?>
		<?php }?>
		<?php if($config->USE_FACTORY){?>
			<tr>
				<th width="20%">층고</th>
				<td width="80%" colspan="3">
					<?php if($query->current_floor!=0){ echo $query->current_floor . " m "; } ?>
				</td>
			</tr>							
			<tr>
				<th width="20%">도로조건</th>
				<td width="30%">
					<?php echo $query->road_conditions?> 
				</td>
				<th width="20%">용도</th>
				<td width="30%">
					<?php echo $query->factory_use?> 
				</td>								
			</tr>
			<tr>
				<th width="20%">전기</th>
				<td width="30%">
					<?php echo $query->factory_power?> 
				</td>
				<th width="20%">호이스트</th>
				<td width="30%">
					<?php echo $query->factory_hoist?> 
				</td>								
			</tr>
		<?php }?>	
		<?php if($query->bedcnt!="0" || $query->bathcnt!="0"){?>
			<?php if($query->part=="Y") {?>
			<tr>
				<th width="20%"><?php echo lang("product.roomcnt");?></th>
				<td width="80%" colspan="3">
					<?php if($query->bedcnt!="0") echo lang("product.roomcnt") . $query->bedcnt . "실 ";?>
					<?php if($query->bathcnt!="0") echo lang("product.bathcnt") . $query->bathcnt . "실 ";?>
				</td>
			</tr>
			<?php }?>
		<?php }?>
		<?php if($query->gongsil_status || $query->gongsil_see){?>
			<tr>
				<th width="20%"><?php echo lang("site.status");?></th>
				<td width="30%">
					<?php echo $query->gongsil_status?> 
				</td>
				<th width="20%">방볼때</th>
				<td width="30%">
					<?php echo $query->gongsil_see?> 
				</td>								
			</tr>
		<?php }?>
		<?php if($this->session->userdata("admin_id")!="" && $gongsil_contact){?>
			<tr>
				<th width="20%"><?php echo lang("site.contact");?></th>
				<td width="80%" colspan="3">
					<?php echo $gongsil_contact;?>
				</td>
			</tr>
		<?php }?>
		<?php if($form->extension!=""){?>
		<tr>
			<th width="20%">확장</th>
			<td width="80%" colspan="3">
				<?php echo $query->extension;?>
			</td>
		</tr>
		<?php }?>
		<?php if($query->total_floor!=0) {?>
			<tr>
				<th width="20%"><?php echo lang("product.floor");?></th>
				<td width="80%" colspan="3">
					<?php if($query->current_floor!=0){ echo lang("product.floor.current.".$query->part) . $query->current_floor . lang("product.f"); } ?>
					<?php if($query->total_floor!=0){echo lang("product.floor.total.".$query->part) . $query->total_floor . lang("product.f"); }?>
				</td>
			</tr>
		<?php }?>
		<?php if($query->store_category!="" || $query->store_name!=""){?>
			<tr>
				<th width="20%">현재 업종</th>
				<td width="30%">
					<?php 	echo $query->store_category;?>
				</td>
				<th width="20%">상호명</th>
				<td width="30%">
					<?php 	echo $query->store_name;?>
				</td>
			</tr>
			<?php if(isset($store_data) && $store_data){?>
			<tr>
				<th width="20%">300M내 동일업종</th>
				<td width="80%" colspan="3">
					<?php foreach($store_data as $store){?>
					<span class="near" title="<?php echo $store->title?>"><?php echo $store->title?></span> <?php echo round($store->distance,1)?> ㎞
					<?php }?>
				</td>
			</tr>							
			<?php }?>
		<?php }?>
		<?php if($form->profit){?>
			<tr>
				<th width="20%"><?php echo lang("site.revenue");?></th>
				<td width="80%" colspan="3">
					총 매출(<strong><?php echo number_format($query->profit_income);?></strong> <small><?php echo lang('price_unit');?></small>) 
					총 지출(<strong><?php echo number_format($query->profit_outcome);?></strong> <small><?php echo lang('price_unit');?></small>) 
					<br/>지출 재료비(<b><?php echo number_format($query->outcome_matcost);?></b> <small><?php echo lang('price_unit');?></small>) 
					지출 인건비(<b><?php echo number_format($query->outcome_salary);?></b> <small><?php echo lang('price_unit');?></small>) 
					지출 기타(<b><?php echo number_format($query->outcome_etc);?></b> <small><?php echo lang('price_unit');?></small>) 
				</td>
			</tr>	
		<?php }?>
		<?php if($query->heating!=""){?>
			<tr>
				<th width="20%">난방</th>
				<td width="80%" colspan="3"><?php echo $query->heating;?></td>
			</tr>
		<?php }?>
		<?php if($query->enter_year!=""){?>
		<tr>
			<th width="20%"><?php echo lang("product.enter_year");?></th>
			<td width="80%" colspan="3"><?php echo $query->enter_year;?></td>
		</tr>
		<?php }?>
		<?php if($query->build_year!=""){?>
		<tr>
			<th width="20%"><?php echo lang("product.build_year");?></th>
			<td width="80%" colspan="3"><?php echo $query->build_year;?></td>
		</tr>
		<?php }?>
		<?php if($query->park!=""){?>
		<tr>
			<th width="20%">주차</th>
			<td width="80%" colspan="3"><?php echo $query->park;?></td>
		</tr>
		<?php }?>
		<?php 
		$cate = explode(",",$category_one->meta);
		$etc  = explode("--dungzi--", $query->etc);
		foreach($cate as $key=>$val){
			if($val!=""){
				if(isset($etc[$key])){
					if(strpos($etc[$key],"http://") !== false || strpos($etc[$key],"https://") !== false){
						$etc[$key] = "<a href='".$etc[$key]."' target='_blank'>".$etc[$key]."</a>";
					}
					if(strpos($etc[$key],"www.") !== false){
						$etc[$key] = "<a href='http://".$etc[$key]."' target='_blank'>".$etc[$key]."</a>";
					}
				}
			?>
		<tr>
			<th width="20%"><?php echo $val;?></th>
			<td width="80%" colspan="3"><?php if(isset($etc[$key])){ echo $etc[$key]; }?></td>
		</tr>
		<?php 
			}
		}	
		?>
	</table>

	<?php
	if($config->BUILDING_DISPLAY && $building){?>
	<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;">건축물 정보 <small class="text-danger"> * 상기 정보는 건축물대장 기준임</small></h4>
	<table class="border-table">
		<tr>
			<th width="20%">지번주소</th>
			<td width="30%">
				<?php echo $building->address;?>
			</td>
			<th width="20%">도로명주소</th>
			<td width="30%">
				<?php echo $building->road_name;?>
			</td>		
		</tr>
		<tr>
			<th width="20%">대지면적</th>
			<td width="30%">
				<?php if($building->plottage) echo $building->plottage."㎡";?>
			</td>
			<th width="20%">건축면적</th>
			<td width="30%">
				<?php if($building->building_area) echo $building->building_area."㎡";?>
			</td>		
		</tr>
		<tr>
			<th width="20%">연면적</th>
			<td width="30%">
				<?php if($building->total_floor_area) echo $building->total_floor_area."㎡";?>
			</td>
			<th width="20%">주차</th>
			<td width="30%">
				<?php if($building->parking_count) echo $building->parking_count."대";?>
			</td>		
		</tr>
		<tr>
			<th width="20%">규모</th>
			<td width="30%">
				<?php if($building->underground_floors) echo "지하 : ".$building->underground_floors.lang("product.f")."<br/>";?>
				<?php if($building->ground_floors) echo "지상 : ".$building->ground_floors.lang("product.f");?>
			</td>
			<th width="20%">엘리베이터</th>
			<td width="30%">
				<?php if($building->elevator_count) echo $building->elevator_count."대";?>
			</td>		
		</tr>
		<tr>
			<th width="20%">주용도</th>
			<td width="80%" colspan="3">
				<?php if($building->main_use) echo $building->main_use;?>
			</td>
		</tr>
		<tr>
			<th width="20%">기타용도</th>
			<td width="80%" colspan="3">
				<?php if($building->etc_use) echo $building->etc_use;?>
			</td>
		</tr>
		<tr>
			<th width="20%">사용승인</th>
			<td width="80%" colspan="3">
				<?php if($building->use_approval_day) echo $building->use_approval_day;?>
			</td>
		</tr>
	</table>
	
		<?php if($building_protection){?>
		<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;">상가임대차보호법 적용내역</h4>
		<table class="border-table">
			<tr>
				<th width="20%">적용유무</th>
				<td width="30%">
					적용
				</td>
				<th width="20%">대항력</th>
				<td width="30%">
					제3자에게 임차권 주장 가능
				</td>
			</tr>
			<tr>
				<th width="20%">우선변제권</th>
				<td width="80%" colspan="3">
					보증금에 대한 우선변제 가능(6,500만원 이하)
				</td>
			</tr>
			<tr>
				<th width="20%">최우선변제금액</th>
				<td width="80%" colspan="3">
					보증금의 일정액을 최우선변제 가능(2,200만원 이하)
				</td>
			</tr>
			<tr>
				<th width="20%">계약갱신요구권</th>
				<td width="30%">
					5년 기간동안 계약갱신 가능
				</td>
				<th width="20%">임대료증액한도</th>
				<td width="30%">
					연간 9%이내
				</td>
			</tr>
			<tr>
				<th width="20%">권리금보호</th>
				<td width="30%">
					권리계약시 임대인(건물주) 방해 금지
				</td>
				<th width="20%">필수사항</th>
				<td width="30%">
					상가건물인도, 사업자등록, 확정일자
				</td>
			</tr>
		</table>
		<?php }?>
	<?php }?>

	<div class="is_print">
		<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 설명</h4>
		<table class="border-table">
			<tr>
				<th width="20%">설명</th>
				<td width="80%" colspan="3">
					<span class="help-inline"><?php echo strip_tags(str_replace("&nbsp;","",$query->content),"<p><br>");?></font></span>
				</td>
			</tr>									
			<tr>
				<th width="20%">키워드</th>
				<td width="80%">
					<span class="help-inline"><?php echo $query->tag;?></span>
				</td>								
			</tr>
			<tr>
				<th width="20%">날짜</th>
				<td width="80%" colspan="3">
					<span class="help-inline"><?php echo $query->date;?></span>
				</td>								
			</tr>
		</table>
	</div>

	<?php if($this->session->userdata("admin_id") || $config->MEMBER_INFO_RIGHT){?>
	<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> <?php echo lang("product.owner");?></h4>
	<table class="border-table">
		<tr>
			<th width="20%"><?php echo lang("site.name");?></th>
			<td width="30%">
				<?php 
				if( $member->biz_name!="" ) {
					echo "[".$member->biz_name."] ";
				}
				?>
				<?php echo $member->name;?>
			</td>
			<th width="20%"><?php echo lang("site.contact");?></th>
			<td width="30%">
				<?php echo $member->phone;?>
			</td>								
		</tr>
	</table>
	<?php }?>

	<?php if(isset($danzi) && $danzi->pyeong_img!=""){?>
	<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 평면도</h4>
	<div>
		<img src="/uploads/danzi/<?php echo $danzi->pyeong_img;?>"/>
	</div>
	<?php }?>

	<?php if($config->REALPRICE && $query->address){?>
	<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 실거래가 정보</h4>
		<?php if($this->uri->segment(1)=="mobile"){?>
			<a href="/mobile/realprice/<?php echo $query->id;?>" class="btn btn-info btn-lg" style="width:100%;">실거래가 조회</a>
		<?php } else {?>
			<div>
				<?php $bunzi_address = urlencode($query->address_name." ".$query->address);?>
				<iframe id="realprice_frame" name="realprice_frame" src="http://hub.dungzi.com/realprice/chart/?address=<?php echo $bunzi_address;?>" frameborder="0" border="0" scrolling="no" style="display:block;width:100%;height:550px;"></iframe>
			</div>
		<?php }?>
	<?php }?>

</span><!-- print_area -->