<script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=<?php echo $config->DAUM_MAP_KEY;?>&libraries=services"></script>
<style>
.border-table tr{
	height:40px;
}
.border-table tr th{
	text-align:center;
}
</style>
<script>
$(document).ready(function(){

	$("#building_enquire_form").validate({ 
		rules: {
			expense_kind: {  
				required: true
			},
			expense_grade: {  
				required: true
			},
			expense_elevator: {  
				required: true
			}
		},  
		messages: {
			expense_kind: {  
				required: "용도를 선택해 주세요"
			},
			expense_grade: {  
				required: "등급을 선택해 주세요"
			},
			expense_elevator: {  
				required: "엘리베이터 유무를 선택해 주세요"
			}
		} 
	});

	get_gugun("서울");
});

function get_sido(type){
	$.getJSON("/address/get_sido_building/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>시도 선택</option>";

		$.each(data, function(key, val) {
			str = str + "<option value='"+val["sido"]+"'>"+val["sido"]+"</option>";
		});

		$("#sido").html(str);

		$("#sido").change(function(){
			$("#dong").html("<option value=''>- 읍면동 선택 -</option>");
			get_gugun(this.value);
		});

	});
}

function get_gugun(sido){
	$.getJSON("/address/get_gugun_building/"+encodeURI(sido)+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>- 구군 선택 -</option>";

		$.each(data, function(key, val) {
			str = str + "<option value='"+val["parent_id"]+"'>"+val["gugun"]+"</option>";
		});

		$("#gugun").html(str);

		$("#gugun").change(function(){
			get_dong(this.value);
		});
	});
}

function get_dong(parent_id){
	$.getJSON("/address/get_dong_building/"+parent_id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>- 읍면동 선택 -</option>";

		$.each(data, function(key, val) {
			str = str + "<option value='"+val["id"]+"'>"+val["dong"]+"</option>";
		});

		$("#dong").html(str);
	});
}

function address_validate(){
	if($("#sido").val()==""){
		msg($("#msg"), "danger" ,"시도를 선택해주세요");
		$("#sido").focus();
		return false;
	}
	if($("#gugun").val()==""){
		msg($("#msg"), "danger" ,"구군을 선택해주세요");
		$("#gugun").focus();
		return false;
	}
	if($("#dong").val()==""){
		msg($("#msg"), "danger" ,"읍면동 선택해주세요");
		$("#dong").focus();
		return false;
	}
	if($("#bunzi").val()==""){
		msg($("#msg"), "danger" ,"번지를 입력해주세요");
		$("#bunzi").focus();
		return false;
	}
	return true;
}

function building_search(obj){

	if(!address_validate()) return false;

	$(obj).prop('onclick',null).off('click');

	$("select[name='expense_kind']").val("");
	$("select[name='expense_grade']").val("");
	$("select[name='expense_elevator']").val("");

	expense_reset();

	var address = $("#sido option:selected").text()+" "+$("#gugun option:selected").text()+" "+$("#dong option:selected").text()+" "+$("#bunzi").val();

	$.getJSON("/member/building_search/"+encodeURIComponent(address)+"/"+Math.round(new Date().getTime()),function(data){
		
		loading(true);
		
		if(data!=""){
			$("input[name='id']").val(data["id"]);
			$("#address").text(data["address"]);
			$("#road_name").text(data["road_name"]);
			$("#plottage").text(data["plottage"]+"㎡");
			$("#building_area").text(data["building_area"]+"㎡");
			$("#total_floor_area").text(data["total_floor_area"]+"㎡");
			$("#floor_area_cal").text(data["floor_area_cal"]+"㎡");
			$("#building_coverage").text(data["building_coverage"]+"%");
			$("#floor_area_ratio").text(data["floor_area_ratio"]+"%");
			$("#ground_floors").text("지하층수 : "+data["underground_floors"]+"<?php echo lang("product.f");?> / "+"지상층수 : "+data["ground_floors"]+"<?php echo lang("product.f");?>");			
			$("#structure_name").text(data["structure_name"]);
			$("#main_use").text(data["main_use"]);
			$("#etc_use").text(data["etc_use"]);
			$("#use_approval_day").text(data["use_approval_day"]);
			if(data["energy_efficiency"]!="0") $("#energy_efficiency").text(data["energy_efficiency"]+"등급");

			building_use_info(address);

			$("#msg2").html("");
		}
		else{

			$("input[name='id']").val("");
			$("#address").text("");
			$("#road_name").text("");
			$("#plottage").text("");
			$("#building_area").text("");
			$("#total_floor_area").text("");
			$("#floor_area_cal").text("");
			$("#building_coverage").text("");
			$("#floor_area_ratio").text("");
			$("#ground_floors").text("");			
			$("#structure_name").text("");
			$("#main_use").text("");
			$("#etc_use").text("");
			$("#use_approval_day").text("");
			$("#energy_efficiency").text("");

			$("input[name='city_planning']").val("");
			$("input[name='coverage_upper']").val("");
			$("input[name='ratio_upper']").val("");
			$("input[name='building_area_uppper']").val("");
			$("input[name='ground_total_floor_area']").val("");

			$("#city_planning").text("");
			$("#coverage_upper").text("");
			$("#ratio_upper").text("");
			$("#building_area_uppper").text("");
			$("#ground_total_floor_area").text("");

			$("#building_limit").html("");

			loading(false);

			msg($("#msg"), "danger" ,"검색 결과가 없습니다");
		}

		$(obj).attr('onclick','building_search(this)');
	
	});
}

/* 용도정보 가져오기 */
function building_use_info(address){
	var geocoder = new daum.maps.services.Geocoder();

	geocoder.addr2coord(address, function(status, result){
		 if(status === daum.maps.services.Status.OK){
			$.getJSON("/member/building_use_info/"+$("input[name='id']").val()+"/"+result.addr[0].lat+"/"+result.addr[0].lng+"/"+Math.round(new Date().getTime()),function(data){
				$("input[name='city_planning']").val(data["city_planning"]);
				$("input[name='coverage_upper']").val(data["coverage_upper"]);
				$("input[name='ratio_upper']").val(data["ratio_upper"]);
				$("input[name='building_area_uppper']").val(data["building_area_uppper"]);
				$("input[name='ground_total_floor_area']").val(data["ground_total_floor_area"]);

				$("#city_planning").text(data["city_planning"]);
				$("#coverage_upper").text(data["coverage_upper"]+"%");
				$("#ratio_upper").text(data["ratio_upper"]+"%");
				$("#building_area_uppper").text(data["building_area_uppper"]+"㎡");
				$("#ground_total_floor_area").text(data["ground_total_floor_area"]+"㎡");
				$("#building_limit").html(data["building_limit"]);

				loading(false);
			});
		}
		else{
			loading(false);
		}
	});
}

/* 비용산정 */
function expense(){
	var kind = $("select[name='expense_kind']");
	var grade = $("select[name='expense_grade']");
	var elevator = $("select[name='expense_elevator']");

	if($("input[name='ground_total_floor_area']").val()==""){
		kind.val("");
		grade.val("");
		elevator.val("");
		msg($("#msg2"), "danger" ,"건축물 정보가 검색되지 않았습니다. 정보를 검색 후에 선택해주세요");
		return false;
	}

	if(kind.val()!="" && grade.val()!="" && elevator.val()!=""){
		$.getJSON("/member/building_expense/"+kind.val()+"/"+grade.val()+"/"+$("input[name='ground_total_floor_area']").val()+"/"+elevator.val()+"/"+Math.round(new Date().getTime()),function(data){
			$("input[name='construction_cost']").val(data["construction_cost"]);
			$("input[name='design_supervision_cost']").val(data["design_supervision_cost"]);
			$("input[name='probable_cost']").val(data["probable_cost"]);

			$("#construction_cost").text(setWon(data["construction_cost"]));
			$("#design_supervision_cost").text(setWon(data["design_supervision_cost"]));
			$("#probable_cost").text(setWon(data["probable_cost"]));
		});		
	}
	else{
		expense_reset();
	}
}

function expense_reset(){
	$("input[name='construction_cost']").val("");
	$("input[name='design_supervision_cost']").val("");
	$("input[name='probable_cost']").val("");
	
	$("#construction_cost").text("");
	$("#design_supervision_cost").text("");
	$("#probable_cost").text("");
}

function form_submit(type){
	$("input[name='type']").val(type);
	$("#building_enquire_form").submit();
}
</script>
<div class="main">
	<div class="_container">
		<ul class="breadcrumb">
			<li><a href="/"><?php echo lang("menu.home");?></a></li>
			<li>
			<?php foreach($mainmenu as $val){
				if($val->type=="enquire") echo $val->title;
			}?>
			</li>
			<li class="active">건축물자가진단 의뢰하기</li>
		</ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
			<div class="col-md-12 col-sm-12">
				<h1>건축물자가진단 의뢰</h1>
			</div>
			<!-- BEGIN CONTENT -->
			<div class="col-md-12 col-sm-12">
				<div class="loading_content">
					<div class="loading_background"></div>
					<div class="loading_image"><img src="/assets/common/img/load_360.gif"></div>
				</div>
				<?php echo form_open("member/building_enquire_action","id='building_enquire_form' style='margin-top:20px;' role='form'");?>
				<input type="hidden" name="id"/>
				<input type="hidden" name="member_id" value="<?php echo $this->session->userdata("id");?>"/>
				<input type="hidden" name="type"/>
				<input type="hidden" name="city_planning"/>
				<input type="hidden" name="coverage_upper"/>
				<input type="hidden" name="ratio_upper"/>
				<input type="hidden" name="building_area_uppper"/>
				<input type="hidden" name="ground_total_floor_area"/>				
				<input type="hidden" name="construction_cost"/>
				<input type="hidden" name="design_supervision_cost"/>
				<input type="hidden" name="probable_cost"/>
				<div id="msg" class="margin-bottom-20"></div>
				<div class="margin-bottom-20">
					<select id="sido" name="sido" class="form-control" style="display:inline;width:180px;">
						<option value='서울'>서울</option>
					</select>
					<select id="gugun" name="gugun" class="form-control" style="display:inline;width:180px;"><option value="">- 구군 선택 -</option></select>
					<select id="dong" name="dong" class="form-control" style="display:inline;width:180px;"><option value="">- 읍면동 선택 -</option></select>
					<input type="text" id="bunzi" name="bunzi" class="form-control" style="display:inline;width:180px;" placeholder="번지"/>
					<button type="button" class="btn btn-default" style="display:inline;margin-bottom:5px;" onclick="building_search(this)"><i class="glyphicon glyphicon-search"></i> <?php echo lang("site.search");?></button>
				</div>

				<h4><strong>현 이용상태</strong></h4>
				<table class="border-table">
					<tr>
						<th width="20%">지번주소</th>
						<td width="30%" id="address"></td>
						<th width="20%">도로명주소</th>
						<td width="30%" id="road_name"></td>
					</tr>
					<tr>
						<th width="20%">대지면적</th>
						<td width="30%" id="plottage"></td>
						<th width="20%">건축면적</th>
						<td width="30%" id="building_area"></td>
					</tr>
					<tr>
						<th width="20%">연면적</th>
						<td width="30%" id="total_floor_area"></td>
						<th width="20%">용적율산정연면적</th>
						<td width="30%" id="floor_area_cal"></td>
					</tr>
					<tr>
						<th width="20%">건폐율</th>
						<td width="30%" id="building_coverage"></td>
						<th width="20%">용적율</th>
						<td width="30%" id="floor_area_ratio"></td>
					</tr>
					<tr>
						<th width="20%">규모</th>
						<td width="30%" id="ground_floors"></td>
						<th width="20%">구조</th>
						<td width="30%" id="structure_name"></td>
					</tr>
					<tr>
						<th width="20%">주용도</th>
						<td width="30%" id="main_use"></td>
						<th width="20%">기타용도</th>
						<td width="30%" id="etc_use"></td>
					</tr>
					<tr>
						<th width="20%">사용승인</th>
						<td width="30%" id="use_approval_day"></td>
						<th width="20%">에너지효율등급</th>
						<td width="30%" id="energy_efficiency"></td>
					</tr>
				</table>

				<h4 style="margin-top:30px"><strong>개발가능진단</strong></h4>
				<table class="border-table">
					<tr>
						<th width="20%">도시계획</th>
						<td width="80%" colspan="3" id="city_planning"></td>
					</tr>
					<tr>
						<th width="20%">건폐율상한</th>
						<td width="30%" id="coverage_upper"></td>
						<th width="20%">용적율상한</th>
						<td width="30%" id="ratio_upper"></td>
					</tr>
					<tr>
						<th width="20%">건축면적상한</th>
						<td width="30%" id="building_area_uppper"></td>
						<th width="20%">지상연면적상한</th>
						<td width="30%" id="ground_total_floor_area"></td>
					</tr>
				</table>
				<h4>
					<small class="text-danger"> * 상기 정보는 관련 공부 및 법령을 기반으로 한 개략적 내용이므로 정확한 내용은 반드시 허가관청 확인 요망</small>
				</h4>
				<div id="building_limit"></div>

				<h4 style="margin-top:30px"><strong>건축비용산정</strong></h4>
				<div id="msg2"></div>
				<table class="border-table">
					<tr>
						<th width="20%">용도선택</th>
						<td width="80%" colspan="3">
							<select class="form-control input-small" name="expense_kind" style="display:inline;width:30%" onchange="expense();">
								<option value="">- 선택 -</option>
								<option value="1">단독주택</option>
								<option value="2">상가주택</option>
								<option value="3">상가건물</option>
							</select>
						</td>
					</tr>
					<tr>
						<th width="20%">등급선택</th>
						<td width="80%" colspan="3">
							<select class="form-control" name="expense_grade" style="display:inline;width:30%" onchange="expense();">
								<option value="">- 선택 -</option>
								<option value="normal">일반</option>
								<option value="medium">중급</option>
								<option value="high">고급</option>
							</select>
						</td>
					</tr>
					<tr>
						<th width="20%">엘리베이터선택</th>
						<td width="80%" colspan="3">
							<select class="form-control" name="expense_elevator" style="display:inline;width:30%" onchange="expense();">
								<option value="">- 선택 -</option>
								<option value="1">유</option>
								<option value="0">무</option>
							</select>
						</td>
					</tr>
					<tr>
						<th width="20%">공사비</th>
						<td width="80%" colspan="3" id="construction_cost"></td>
					</tr>
					<tr>
						<th width="20%">설계감리비</th>
						<td width="80%" colspan="3" id="design_supervision_cost"></td>
					</tr>
					<tr>
						<th width="20%">예상건축비용</th>
						<td width="80%" colspan="3" id="probable_cost"></td>
					</tr>
				</table>
				<h4>
					<small class="text-danger"> * 상기 정보는 지상연면적 기준 개략적 예상 건축비용으로서, 실제 소요비용과 차이가 발생할 수 있음</small><br/>
					<small class="text-danger"> * 철거비용, 지하층,확장공사, 취득관련 세금 및 기타 비용 불포함 내역임</small>
				</h4>
				<div class="text-center" style="margin-top:40px;">
					<button type="button" class="btn btn-success btn-lg" onclick="form_submit('e​stimate');"><i class="glyphicon glyphicon-list-alt"></i> 견적의뢰 (세부견적요청합니다)</button>
					<button type="button" class="btn btn-danger btn-lg" onclick="form_submit('sell');"><i class="glyphicon glyphicon-bullhorn"></i> 중개의뢰 (팔아주세요)</button>
				</div>
				<?php echo form_close();?>
			</div>
			<!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
	</div>
</div>