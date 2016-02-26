<script>
$(document).ready(function(){
	$("#add_file").click(function(e){
		e.preventDefault();
		$("#file_section").append('<div class="multi-form-control-wrapper"><input type="file" name="userfile[]" class="form-control input-inline input-xlarge" placeholder="첨부파일선택" autocomplete="off" style="height:auto"/> <button type="button" class="input_delete btn red btn-xs input-inline"><i class="fa fa-minus"></i></button></div>');

		$(".input_delete").on("click",function(e){
			e.preventDefault(); $(this).parent('div').remove();
		})
	});
	$("#construction_cost").text(setWon($("#construction_cost").text()));
	$("#design_supervision_cost").text(setWon($("#design_supervision_cost").text()));
	$("#probable_cost").text(setWon($("#probable_cost").text()));
});
</script>
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-title">건축물자가진단 의뢰<small>보기</small></h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="/adminhome/index"><?php echo lang("menu.home");?></a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="#">건축물자가진단 의뢰 보기</a>
                </li>
            </ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="javascript:history.back()"><?php echo lang("site.back");?></button>
			</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12" style="margin-bottom:100px;">
		<h4><strong>의뢰인</strong></h4>
		<table class="border-table">
			<tr>
				<th width="20%">의뢰인</th>
				<td width="30%"><?php echo $query->member_name;?>(<?php echo $query->member_email;?>)</td>
				<th width="20%">연락처</th>
				<td width="30%"><?php echo $query->member_phone;?></td>				
			</tr>
		</table>
		<h4 style="margin-top:30px"><strong>현 이용상태</strong></h4>
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
				<td width="80%" id="design_supervision_cost"><?php echo $query->design_supervision_cost;?>원</td>
			</tr>
			<tr>
				<th width="20%">예상건축비용</th>
				<td width="80%" id="probable_cost"><?php echo $query->probable_cost;?>원</td>
			</tr>
		</table>

		<h4 style="margin-top:30px"><strong>견적서 첨부</strong></h4>
		<?php echo form_open_multipart("adminbuilding/upload_estimate","id='estimate_form'");?>
		<input type="hidden" name="id" value="<?php echo $query->id;?>"/>
		<table class="border-table">
			<tr>
				<th width="20%">첨부파일</th>
				<td width="30%">
					<div class="form-group">
						<div class="help-block">* 업로드가능한 파일 : xlsx,xls,doc,docx,hwp,ppt,pptx,pdf,zip,txt,jpg,png</div>
						<div id="file_section" class="form-inline margin-top-10">
							<div class="multi-form-control-wrapper">
								<input type="file" name="files[]" class="form-control input-inline input-xlarge" placeholder="첨부파일선택" autocomplete="off" style="height:auto"/> <button type="button" id="add_file" class="btn blue btn-xs input-inline"><i class="fa fa-plus"></i></button>
							</div>
						</div>
					</div>
				</td>
				<th width="20%">첨부 되어있는 파일</th>
				<td width="30%">
						<?php foreach($attachment as $val){?>
						<button type="button" class="btn btn-default" onclick="location.href='/attachment/estimate_download/<?php echo $val->enquire_id;?>/<?php echo $val->id;?>'" style="margin:3px;"><?php echo $val->originname?> <i class="fa fa-download"></i></button><br/>					
						<?php }?>
						<?php if(count($attachment)==0){?>
						첨부파일이 없습니다.
						<?php }?>
				</td>
			</tr>
		</table>
		<div class="text-center" style="margin-top:30px;">
			<button type="submit" class="btn btn-primary btn-lg"><?php echo lang("site.submit");?> </button>
		</div>
		<?php echo form_close();?>
    </div>
</div>