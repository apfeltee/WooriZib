<script>
function get_config(obj,id){
	$(".list").removeClass("active");
	$(obj).parent().addClass("active");
	$.getJSON("/adminproductconfig/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			$("input[name='"+key+"']").val(val);
			$("select[name='"+key+"']").val(val);
		});
	});
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			<?php echo lang("product");?> 폼 <small>설정</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<?php echo lang("product");?> 폼 설정
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="javascript:$('#edit_form').submit();">저장하기</button>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-3">
		<div class="help-block">* 유형 제목을 클릭하면 수정을 할 수 있습니다.</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th class="text-center"><?php echo lang("product");?> 유형</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($query as $key=>$val){?>
				<tr>
					<td class="text-center list <?php echo ($key==0) ? "active": "";?>"><a href="#" style="color:#000;" onclick="get_config(this,<?php echo $val->id;?>)"><?php echo $val->name?></a></td>
				</tr>
				<?php
				}?>
			</tbody>
		</table>
	</div>
	<div class="col-lg-9">
		<div class="help-block">&nbsp;</div>
		<div class="portlet">
			<?php echo form_open("adminproductconfig/edit_action",Array("id"=>"edit_form"))?>
			<input type="hidden" name="id" value="<?php echo $query[0]->id?>"/>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th class="text-center vertical-middle" width="300"><?php echo lang("product");?> 유형</th>
						<td>
							<input type="text" class="form-control input-large" name="name" value="<?php echo $query[0]->name?>" readonly/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">기본 거래 종류</th>
						<td>
							<select class="form-control input-large" name="default_type">
								<option value="" <?php echo ($query[0]->default_type=="") ? "selected" : "";?>>- 선택없음 -</option>
								<option value="installation" <?php echo ($query[0]->default_type=="installation") ? "selected" : "";?>>분양</option>
								<option value="sell" <?php echo ($query[0]->default_type=="sell") ? "selected" : "";?>>매매</option>
								<option value="fullrent" <?php echo ($query[0]->default_type=="fullrent") ? "selected" : "";?>>전세</option>
								<option value="monthly_rent" <?php echo ($query[0]->default_type=="monthly_rent") ? "selected" : "";?>>월세</option>
								<option value="rent" <?php echo ($query[0]->default_type=="rent") ? "selected" : "";?>>전/월세</option>
							</select>
						</td>
					</tr>					
					<tr>
						<th class="text-center vertical-middle">건물 부분여부 기본 값</th>
						<td>
							<select class="form-control input-large" name="default_part">
								<option value="N" <?php echo ($query[0]->default_part=="N") ? "selected" : "";?>><?php echo lang("site.all");?></option>
								<option value="Y" <?php echo ($query[0]->default_part=="Y") ? "selected" : "";?>>부분</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">단지 사용여부</th>
						<td>
							<select class="form-control input-large" name="danzi">
								<option value="0" <?php echo ($query[0]->danzi=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->danzi=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">융자금 사용여부</th>
						<td>
							<select class="form-control input-large" name="lease_price">
								<option value="0" <?php echo ($query[0]->lease_price=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->lease_price=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">권리금 사용여부</th>
						<td>
							<select class="form-control input-large" name="premium_price">
								<option value="0" <?php echo ($query[0]->premium_price=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->premium_price=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle"><?php echo lang("product.mgr_price");?> 사용여부</th>
						<td>
							<select class="form-control input-large" name="mgr_price">
								<option value="0" <?php echo ($query[0]->mgr_price=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->mgr_price=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle"><?php echo lang("product.mgr_price");?>(전/월세)분리 사용여부</th>
						<td>
							<select class="form-control input-large" name="mgr_price_full_rent">
								<option value="0" <?php echo ($query[0]->mgr_price_full_rent=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->mgr_price_full_rent=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">최소 보증금 사용여부</th>
						<td>
							<select class="form-control input-large" name="monthly_rent_deposit_min">
								<option value="0" <?php echo ($query[0]->monthly_rent_deposit_min=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->monthly_rent_deposit_min=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">기대출금</th>
						<td>
							<input type="text" class="form-control" name="loan" placeholder="콤마(,)로 구분 입력" value="<?php echo $query[0]->loan?>"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">동,호수</th>
						<td>
							<select class="form-control input-large" name="dongho">
								<option value="0" <?php echo ($query[0]->dongho=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->dongho=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">실면적 사용여부</th>
						<td>
							<select class="form-control input-large" name="real_area">
								<option value="0" <?php echo ($query[0]->real_area=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->real_area=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">계약면적 사용여부</th>
						<td>
							<select class="form-control input-large" name="law_area">
								<option value="0" <?php echo ($query[0]->law_area=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->law_area=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">대지면적 사용여부(전체)</th>
						<td>
							<select class="form-control input-large" name="land_area">
								<option value="0" <?php echo ($query[0]->land_area=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->land_area=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">도로지분면적 사용여부(전체)</th>
						<td>
							<select class="form-control input-large" name="road_area">
								<option value="0" <?php echo ($query[0]->road_area=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->road_area=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle"><?php echo lang("product.enter_year");?></th>
						<td>
							<input type="text" class="form-control" name="enter_year" placeholder="콤마(,)로 구분 입력" value="<?php echo $query[0]->enter_year?>"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle"><?php echo lang("product.build_year");?> 사용여부</th>
						<td>
							<select class="form-control input-large" name="build_year">
								<option value="0" <?php echo ($query[0]->build_year=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->build_year=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">침실 사용여부</th>
						<td>
							<select class="form-control input-large" name="bedcnt">
								<option value="0" <?php echo ($query[0]->bedcnt=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->bedcnt=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">욕실 사용여부</th>
						<td>
							<select class="form-control input-large" name="bathcnt">
								<option value="0" <?php echo ($query[0]->bathcnt=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->bathcnt=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">현재 층</th>
						<td>
							<input type="text" class="form-control" name="current_floor" placeholder="콤마(,)로 구분 입력" value="<?php echo $query[0]->current_floor?>"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">전체 층</th>
						<td>
							<select class="form-control input-large" name="total_floor">
								<option value="0" <?php echo ($query[0]->total_floor=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->total_floor=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">현재 업종</th>
						<td>
							<input type="text" class="form-control" name="store_category" placeholder="콤마(,)로 구분 입력" value="<?php echo $query[0]->store_category?>"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">현재 상호</th>
						<td>
							<select class="form-control input-large" name="store_name">
								<option value="0" <?php echo ($query[0]->store_name=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->store_name=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle"><?php echo lang("site.revenue");?></th>
						<td>
							<select class="form-control input-large" name="profit">
								<option value="0" <?php echo ($query[0]->profit=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->profit=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">공실 방볼때</th>
						<td>
							<input type="text" class="form-control" name="gongsil_see" placeholder="콤마(,)로 구분 입력" value="<?php echo $query[0]->gongsil_see?>"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle"><?php echo lang("site.status");?></th>
						<td>
							<input type="text" class="form-control" name="gongsil_status" placeholder="콤마(,)로 구분 입력" value="<?php echo $query[0]->gongsil_status?>"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">공실 연락처</th>
						<td>
							<select class="form-control input-large" name="gongsil_contact">
								<option value="0" <?php echo ($query[0]->gongsil_contact=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->gongsil_contact=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">확장 여부(선택항목입력)</th>
						<td>
							<input type="text" class="form-control" name="extension" placeholder="콤마(,)로 구분 입력" value="<?php echo $query[0]->extension?>"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">난방(선택항목입력)</th>
						<td>
							<input type="text" class="form-control" name="heating" placeholder="콤마(,)로 구분 입력" value="<?php echo $query[0]->heating?>"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">주차</th>
						<td>
							<select class="form-control input-large" name="park">
								<option value="0" <?php echo ($query[0]->park=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->park=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">도로인접조건</th>
						<td>
							<input type="text" class="form-control" name="road_condition" placeholder="콤마(,)로 구분 입력" value="<?php echo $query[0]->road_condition?>"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">용도지역,지목입력항목</th>
						<td>
							<select class="form-control input-large" name="ground">
								<option value="0" <?php echo ($query[0]->ground=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->ground=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">공장정보(전기,호이스트,용도)</th>
						<td>
							<select class="form-control input-large" name="factory">
								<option value="0" <?php echo ($query[0]->factory=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->factory=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">VR주소</th>
						<td>
							<select class="form-control input-large" name="vr">
								<option value="0" <?php echo ($query[0]->vr=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->vr=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">동영상주소</th>
						<td>
							<select class="form-control input-large" name="video_url">
								<option value="0" <?php echo ($query[0]->video_url=="0") ? "selected" : "";?>>사용안함</option>
								<option value="1" <?php echo ($query[0]->video_url=="1") ? "selected" : "";?>>사용함</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<?php echo form_close();?>
		</div>
	</div>
</div>