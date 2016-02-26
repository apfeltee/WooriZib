<script>
$(document).ready(function(){
	$("#construction_cost").text(setWon($("#construction_cost").text()));
	$("#design_supervision_cost").text(setWon($("#design_supervision_cost").text()));
	$("#probable_cost").text(setWon($("#probable_cost").text()));
});
</script>
<div class="main">
	<div class="_container">
		<ul class="breadcrumb">
			<li><a href="/"><?php echo lang("menu.home");?></a></li>
			<li><a href="#"><?php echo lang("menu.mypage");?></a></li>
			<li class="active">건축물자가진단 의뢰</li>
		</ul>
		<!-- BEGIN SIDEBAR & CONTENT -->
		<div class="row margin-bottom-40">          
			<!-- BEGIN SIDEBAR -->
			<div class="sidebar col-md-3 col-sm-3">
				<ul class="list-group margin-bottom-25 sidebar-menu">
					<?php if($this->session->userdata("id")==""){?>
					<li class="list-group-item clearfix"><a href="/member/signin"><i class="fa fa-angle-right"></i> <?php echo lang("menu.login");?></a></li>
					<li class="list-group-item clearfix"><a href="/member/signup"><i class="fa fa-angle-right"></i> <?php echo lang("menu.signup");?></a></li>
					<li class="list-group-item clearfix"><a href="/member/search"><i class="fa fa-angle-right"></i> <?php echo lang("menu.lostpw");?></a></li>
					<?php }?>
					<?php if($this->session->userdata("id")){?>
					<li class="list-group-item clearfix"><a href="/member/profile"><i class="fa fa-angle-right"></i> <?php echo lang("menu.modifyprofile");?></a></li>
					<li class="list-group-item clearfix"><a href="/member/delete"><i class="fa fa-angle-right"></i> <?php echo lang("menu.withdrawal");?></a></li>
					<?php }?>
					<li class="list-group-item clearfix"><a href="/member/history"><i class="fa fa-angle-right"></i> <?php echo lang("site.seen");?></a></li>
					<li class="list-group-item clearfix"><a href="/member/hope"><i class="fa fa-angle-right"></i> <?php echo lang("site.saved");?></a></li>
					<?php if($config->BUILDING_ENQUIRE){?>
					<li class="list-group-item clearfix active"><a href="/member/building_enquire_list"><i class="fa fa-angle-right"></i> 건축물자가진단 의뢰</a></li>
					<?php }?>
				</ul>
			</div>
			<!-- END SIDEBAR -->

			<!-- BEGIN CONTENT -->
			<div class="col-md-9 col-sm-9">
				<h1>건축물자가진단 의뢰 보기</h1>
				<table class="border-table">
					<tr>
						<th width="20%">지번주소</th>
						<td width="30%"><?php echo $building->address;?></td>
						<th width="20%">도로명주소</th>
						<td width="30%"><?php echo $building->road_name;?></td>
					</tr>
					<tr>
						<th width="20%">대지면적</th>
						<td width="30%"><?php echo $building->plottage;?>㎡</td>
						<th width="20%">건축면적</th>
						<td width="30%"><?php echo $building->building_area;?>㎡</td>
					</tr>
					<tr>
						<th width="20%">연면적</th>
						<td width="30%"><?php echo $building->total_floor_area;?>㎡</td>
						<th width="20%">용적율산정연면적</th>
						<td width="30%"><?php echo $building->floor_area_cal;?>㎡</td>
					</tr>
					<tr>
						<th width="20%">건폐율</th>
						<td width="30%"><?php echo $building->building_coverage;?>%</td>
						<th width="20%">용적율</th>
						<td width="30%"><?php echo $building->floor_area_ratio;?>%</td>
					</tr>
					<tr>
						<th width="20%"><?php echo lang("product.floor");?></th>
						<td width="30%">지하층수 : <?php echo $building->underground_floors;?><?php echo lang("product.f");?> / 지상층수 : <?php echo $building->ground_floors;?><?php echo lang("product.f");?></td>
						<th width="20%">구조</th>
						<td width="30%"><?php echo $building->structure_name;?></td>
					</tr>
					<tr>
						<th width="20%">주용도</th>
						<td width="30%"><?php echo $building->main_use;?></td>
						<th width="20%">기타용도</th>
						<td width="30%"><?php echo $building->etc_use;?></td>
					</tr>
					<tr>
						<th width="20%">사용승인</th>
						<td width="30%"><?php echo $building->use_approval_day;?></td>
						<th width="20%">에너지효율등급</th>
						<td width="30%"><?php echo ($building->energy_efficiency) ? $building->energy_efficiency."등급" : "";?></td>
					</tr>
				</table>

				<h4 style="margin-top:30px"><strong>개발가능진단</strong></h4>
				<table class="border-table">
					<tr>
						<th width="20%">도시계획</th>
						<td width="80%" colspan="3"><?php echo $query->city_planning;?></td>
					</tr>
					<tr>
						<th width="20%">건폐율상한</th>
						<td width="30%"><?php echo $query->coverage_upper;?>%</td>
						<th width="20%">용적율상한</th>
						<td width="30%"><?php echo $query->ratio_upper;?>%</td>
					</tr>
					<tr>
						<th width="20%">건축면적상한</th>
						<td width="30%"><?php echo $query->building_area_uppper;?>㎡</td>
						<th width="20%">지상연면적상한</th>
						<td width="30%"><?php echo $query->ground_total_floor_area;?>㎡</td>
					</tr>
				</table>
				<h4>
					<small class="text-danger"> * 상기 정보는 관련 공부 및 법령을 기반으로 한 개략적 내용이므로 정확한 내용은 반드시 허가관청 확인 요망</small>
				</h4>
				<h4 style="margin-top:30px"><strong>건축비용산정</strong></h4>
				<table class="border-table">
					<tr>
						<th width="20%">용도</th>
						<td width="80%">
							<?php if($query->expense_kind=="1") echo "단독주택";?>
							<?php if($query->expense_kind=="2") echo "상가주택";?>
							<?php if($query->expense_kind=="3") echo "상가건물";?>
						</td>
					</tr>
					<tr>
						<th width="20%">등급</th>
						<td width="80%">
							<?php if($query->expense_grade=="normal") echo "일반";?>
							<?php if($query->expense_grade=="medium") echo "중급";?>
							<?php if($query->expense_grade=="high") echo "고급";?>
						</td>
					</tr>
					<tr>
						<th width="20%">엘리베이터</th>
						<td width="80%">
							<?php if($query->expense_elevator=="1") echo "유";?>
							<?php if($query->expense_elevator=="0") echo "무";?>
						</td>
					</tr>
					<tr>
						<th width="20%">공사비</th>
						<td width="80%" id="construction_cost"><?php echo $query->construction_cost;?></td>
					</tr>
					<tr>
						<th width="20%">설계감리비</th>
						<td width="80%" id="design_supervision_cost"><?php echo $query->design_supervision_cost;?></td>
					</tr>
					<tr>
						<th width="20%">예상건축비용</th>
						<td width="80%" id="probable_cost"><?php echo $query->probable_cost;?></td>
					</tr>
				</table>
				<h4>
					<small class="text-danger"> * 상기 정보는 지상연면적 기준 개략적 예상 건축비용으로서, 실제 소요비용과 차이가 발생할 수 있음</small><br/>
					<small class="text-danger"> * 철거비용, 지하층,확장공사, 취득관련 세금 및 기타 비용 불포함 내역임</small>
				</h4>
				<h4 style="margin-top:30px"><strong>첨부파일</strong></h4>
				<div class="well">
					<?php foreach($attachment as $val){?>
					<button type="button" class="btn btn-default" onclick="location.href='/attachment/estimate_download/<?php echo $val->enquire_id;?>/<?php echo $val->id;?>'" style="margin:3px;"><?php echo $val->originname?> <i class="fa fa-download"></i></button>					
					<?php }?>
					<?php if(count($attachment)==0){?>
					첨부파일이 없습니다.
					<?php }?>
				</div>
				<div class="text-center">
					<button type="button" class="btn btn-primary btn-lg margin-top-20" onclick="javascript:history.back();">목록으로</button>
				</div>
			</div>
		</div>
		<!-- END CONTENT -->
		</div>
	<!-- END SIDEBAR & CONTENT -->
	</div>
</div>
