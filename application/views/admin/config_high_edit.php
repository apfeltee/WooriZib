<?php 
if($this->session->userdata("admin_id")==1){
	$show_menu = TRUE;
}
else{
	$show_menu = FALSE;
}
?>
<style>
tr{
	height:50px;
}
th, td{
	vertical-align:middle !important;
}
th {
	text-align:right;
	padding:10px;
}
</style>
<script>
$(document).ready(function(){
	$.validator.addMethod('minStrict', function (value, el, param) {
		return value > param;
	});

	$("#config_high_form").validate({
		rules: {
			SELL_MAX: {  
				required: true,
				number: true,
				minStrict: 0
			},
			FULL_MAX: {  
				required: true,
				number: true,
				minStrict: 0
			},
			MONTH_DEPOSIT_MAX: {  
				required: true,
				number: true,
				minStrict: 0
			},
			MONTH_MAX: {  
				required: true,
				number: true,
				minStrict: 0
			}
		},  
		messages: {  
			SELL_MAX: {  
				required: "매매가 최대값을 입력해주세요",  
				number: "매매가 최대값은 숫자만 입력가능합니다",
				minStrict: "매매가 최대값은 0이상이어야 합니다"
			},
			FULL_MAX: {  
				required: "전세가 최대값을 입력해주세요",  
				number: "전세가 최대값은 숫자만 입력가능합니다",
				minStrict: "전세가 최대값은 0이상이어야 합니다"
			},
			MONTH_DEPOSIT_MAX: {  
				required: "월세보증금 최대값을 입력해주세요",  
				number: "월세보증금 최대값은 숫자만 입력가능합니다",
				minStrict: "월세보증금 최대값은 0이상이어야 합니다"
			},
			MONTH_MAX: {  
				required: "월세임대료 최대값을 입력해주세요",  
				number: "월세임대료 최대값은 숫자만 입력가능합니다",
				minStrict: "월세임대료 최대값은 0이상이어야 합니다"
			}
		} 
	});

	get_sido();
});

var sido = "<?php echo $config->INIT_SIDO?>";
var gugun = "<?php echo $config->INIT_GUGUN?>";
var dong = "<?php echo $config->INIT_DONG?>";

var sido_selected = false;
var gugun_selected = false;
var dong_selected = false;

function get_sido(){
	$("select[name='INIT_GUGUN']").html("<option value=''>구군 선택</option>");
	$("select[name='INIT_DONG']").html("<option value=''>읍면동 선택</option>");
	$.getJSON("/address/get_sido/full/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>시도 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["sido"]+"'>"+val["sido"]+"</option>";
		});
		$("select[name='INIT_SIDO']").html(str);
		$("select[name='INIT_SIDO']").change(function(){
			get_gugun(this.value);
		});
		if(sido!="" && !sido_selected){
			$("select[name='INIT_SIDO']").val(sido);
			get_gugun(sido);
			sido_selected = true;
		}
	});
}

function get_gugun(sido){
	$("select[name='INIT_DONG']").html("<option value=''>읍면동 선택</option>");
	$.getJSON("/address/get_gugun/full/"+encodeURI(sido)+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>구군 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["parent_id"]+"'>"+val["gugun"]+"</option>";
		});
		$("select[name='INIT_GUGUN']").html(str);
		$("select[name='INIT_GUGUN']").change(function(){
			get_dong(this.value);
		});
		if(gugun!="" && !gugun_selected){
			$("select[name='INIT_GUGUN']").val(gugun);
			get_dong(gugun);
			gugun_selected = true;
		}
	});
}

function get_dong(parent_id){
	$.getJSON("/address/get_dong/full/"+parent_id+"/"+Math.round(new Date().getTime()),function(data){
		var str = "<option value=''>읍면동 선택</option>";
		$.each(data, function(key, val) {
			str = str + "<option value='"+val["id"]+"'>"+val["dong"]+"</option>";
		});
		$("select[name='INIT_DONG']").html(str);
		if(dong!="" && !dong_selected){
			$("select[name='INIT_DONG']").val(dong);
			dong_selected = true;
		}
	});
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			고급설정<small>수정</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					고급설정 수정
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="javascript:$('#config_high_form').submit();">수정하기</button>
			</div>
		</div>
	</div>
</div>
<?php echo form_open("adminhome/config_high_action","id='config_high_form'");?>
<input type="hidden" name="id" value="<?php echo $config->id?>"/>

<?php if($show_menu){?>
<h4><strong>둥지</strong></h4>
<table class="table table-bordered table-striped-left table-condensed flip-content" style="border:2px solid red;color:red;">
	<tbody>
		<tr>
			<th width="20%">
				요금미납여부
			</th width="30%">
			<td>
				<select class="form-control input-large" name="PAY">
					<option value="0" <?php echo ($config->PAY=="0") ? "selected" : ""?>>미납</option>
					<option value="1" <?php echo ($config->PAY=="1") ? "selected" : ""?>>완납</option>
				</select>
			</td>
			<th width="20%">
				사용호스팅 <i class="fa fa-question-circle" data-toggle="tooltip" title="사용호스팅"></i>
			</th>
			<td width="30%">
				<input class="form-control input-large" type="text" name="HOSTING" value="<?php echo $config->HOSTING?>"/>
			</td>
		</tr>
		<tr>
			<th>
				매물목록타입 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물목록 타입 1~3"></i>
			</th>
			<td>
				<select class="form-control input-large" name="LISTING">
					<option value="1" <?php echo ($config->LISTING=="1") ? "selected" : ""?>>테이블 방식</option>
					<option value="3" <?php echo ($config->LISTING=="3") ? "selected" : ""?>>3*3 그리드 방식</option>
				</select>
			</td>
			<th>
				카피라이트 둥지표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="카피라이트 둥지표시여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="DUNGZI">
					<option value="0" <?php echo ($config->DUNGZI=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->DUNGZI=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				회원로그인 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원로그인 사용여부"></i></div>
			</th>
			<td>
				<select class="form-control input-large" name="MEMBER_JOIN">
					<option value="0" <?php echo ($config->MEMBER_JOIN=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->MEMBER_JOIN=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				관리자 간편 회원가입 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="관리자 간편 회원가입 사용여부"></i></div>
			</th>
			<td>
				<select class="form-control input-large" name="ADMIN_JOIN">
					<option value="0" <?php echo ($config->ADMIN_JOIN=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->ADMIN_JOIN=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				결제 시스템 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="결제 시스템 사용여부"></i></div>
			</th>
			<td>
				<select class="form-control input-large" name="USE_PAY">
					<option value="0" <?php echo ($config->USE_PAY=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USE_PAY=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				회원 형태 설정 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원 형태 설정"></i></div>
			</th>
			<td>
				<select class="form-control input-large" name="MEMBER_TYPE">
					<option value="general" <?php echo ($config->MEMBER_TYPE=="general") ? "selected" : ""?>>개인회원만 사용</option>
					<option value="biz" <?php echo ($config->MEMBER_TYPE=="biz") ? "selected" : ""?>>공인중개사회원만 사용</option>
					<option value="both" <?php echo ($config->MEMBER_TYPE=="both") ? "selected" : ""?>>개인회원/공인중개사회원 사용</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				매물목록 개방형/폐쇄형 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원 형태 설정"></i></div>
			</th>
			<td>
				<select class="form-control input-large" name="LIST_ENCLOSED">
					<option value="0" <?php echo ($config->LIST_ENCLOSED=="0") ? "selected" : ""?>>개방형</option>
					<option value="1" <?php echo ($config->LIST_ENCLOSED=="1") ? "selected" : ""?>>폐쇄형</option>
				</select>
			</td>
			<th>
				매물 검색 지도 사용여부</div>
			</th>
			<td>
				<select class="form-control input-large" name="MAP_USE">
					<option value="0" <?php echo ($config->MAP_USE=="0") ? "selected" : ""?>>미사용</option>
					<option value="1" <?php echo ($config->MAP_USE=="1") ? "selected" : ""?>>사용</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				데모사이트 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="데모사이트 여부"></i></div>
			</th>
			<td>
				<select class="form-control input-large" name="IS_DEMO">
					<option value="0" <?php echo ($config->IS_DEMO=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->IS_DEMO=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				공장정보 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="공장정보 사용여부"></i></div>
			</th>
			<td>
				<select class="form-control input-large" name="USE_FACTORY">
					<option value="0" <?php echo ($config->USE_FACTORY=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USE_FACTORY=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				분양 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="분양 사용여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="INSTALLATION_FLAG">
					<option value="0" <?php echo ($config->INSTALLATION_FLAG=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->INSTALLATION_FLAG=="1") ? "selected" : ""?>>사용함</option>
					<option value="2" <?php echo ($config->INSTALLATION_FLAG=="2") ? "selected" : ""?>>분양만 사용함</option>
				</select>
			</td>
			<th>
				공실 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="사용할 경우 담당자 정보 대신 연락처 입력한 내용이 보이게 됩니다."></i>
			</th>
			<td>
				<select class="form-control input-large" name="GONGSIL_FLAG">
					<option value="0" <?php echo ($config->GONGSIL_FLAG=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->GONGSIL_FLAG=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				분양메뉴 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="사용할 경우 분양메뉴가 표시됩니다."></i>
			</th>
			<td>
				<select class="form-control input-large" name="INSTALLATION_MENU_FLAG">
					<option value="0" <?php echo ($config->INSTALLATION_MENU_FLAG=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->INSTALLATION_MENU_FLAG=="1") ? "selected" : ""?>>사용함</option>
				</select>				
			</td>
			<th>
				결제용 상점아이디 <i class="fa fa-question-circle" data-toggle="tooltip" title="결제용 상점아이디"></i>
			</th>
			<td>
				<input class="form-control input-small inline" type="text" name="CLIENTID" value="<?php echo $config->CLIENTID?>"/>
				<select class="form-control input-medium inline" type="text" name="PG_ACCOUNT">
					<option value="" selected>계좌이체PG사 선택</option>
					<option value="allthegate" <?php echo ($config->PG_ACCOUNT=="allthegate") ? "selected" : ""?>>올더게이트</option>
					<option value="inicis" <?php echo ($config->PG_ACCOUNT=="inicis") ? "selected" : ""?>>이니시스</option>
				</select>
			</td>
		</tr>		
		<tr>
			<th>
				유저페이지 매물관리 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="유저페이지 매물관리 사용여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="USER_PRODUCT">
					<option value="0" <?php echo ($config->USER_PRODUCT=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USER_PRODUCT=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				유저매물 등록시 승인여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="유저매물 등록시 승인여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="USE_APPROVE">
					<option value="0" <?php echo ($config->USE_APPROVE=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USE_APPROVE=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				매물상세에 건축물정보 정보 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물상세에 건축물정보 표시여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="BUILDING_DISPLAY">
					<option value="0" <?php echo ($config->BUILDING_DISPLAY=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->BUILDING_DISPLAY=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				건축물자가진단 의뢰 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="건축물자가진단 의뢰 사용여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="BUILDING_ENQUIRE">
					<option value="0" <?php echo ($config->BUILDING_ENQUIRE=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->BUILDING_ENQUIRE=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				NICE인증 CP정보 <i class="fa fa-question-circle" data-toggle="tooltip" title="NICE인증 CP정보"></i>
			</th>
			<td>
				<input class="form-control input-inline" type="text" name="CP_CODE" value="<?php echo $config->CP_CODE?>" placeholder="사이트 코드"/>
				<input class="form-control input-inline" type="text" name="CP_PASSWORD" value="<?php echo $config->CP_PASSWORD?>" placeholder="사이트 패스워드"/>
			</td>
			<th>
				NICE인증 IPIN정보 <i class="fa fa-question-circle" data-toggle="tooltip" title="NICE인증 IPIN정보"></i>
			</th>
			<td>
				<input class="form-control input-inline" type="text" name="IPIN_CODE" value="<?php echo $config->IPIN_CODE?>" placeholder="사이트 코드"/>
				<input class="form-control input-inline" type="text" name="IPIN_PASSWORD" value="<?php echo $config->IPIN_PASSWORD?>" placeholder="사이트 패스워드"/>
			</td>
		</tr>
		<tr>
			<th>
				오류문의 레이어 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="오류문의 레이어 사용여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="BUG_LAYER">
					<option value="0" <?php echo ($config->BUG_LAYER=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->BUG_LAYER=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				실거래가 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="오류문의 레이어 사용여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="REALPRICE">
					<option value="0" <?php echo ($config->REALPRICE=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->REALPRICE=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<?php }?>
<br/>

<h4><strong><?php echo lang("product");?> 설정</strong></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th width="20%">
				상세 주소 보여주기  <i class="fa fa-question-circle" data-toggle="tooltip" title="홈페이지 상세정보에서 동아래 번지까지 모두 보여주는 설정이며 기본은 보여지지 않습니다. 상세 주소를 보여주면 문의전화가 더 많이 온다는 통계가 있습니다. (미국)"></i>
			</th>
			<td width="30%">
				<select class="form-control input-large" name="SHOW_ADDRESS">
					<option value="0" <?php echo ($config->SHOW_ADDRESS=="0") ? "selected" : ""?>>보여주지 않음</option>
					<option value="1" <?php echo ($config->SHOW_ADDRESS=="1") ? "selected" : ""?>>보임</option>
				</select>
			</td>
			<th width="20%">
				지도 원 반경 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물상세 지도 원 반경"></i>
			</th>
			<td width="30%">
				<div class="help-block"><small>지도에서 원 반경을 보여주지 않으시려면 0으로 값을 저장해 주세요.</small></div>
				<input class="form-control input-inline" type="text" name="RADIUS" value="<?php echo $config->RADIUS?>"/> 미터(m)</td>
		</tr>
		<tr>
			<th>
				연락처정보보기 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="연락처정보보기 사용여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="CALL_HIDDEN">
					<option value="0" <?php echo ($config->CALL_HIDDEN=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->CALL_HIDDEN=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				매물 갤러리 썸네일 위치 <i class="fa fa-question-circle" data-toggle="tooltip" title="썸네일 하단은 사진이 더 크게 보입니다. 썸네일 우측은 갤러리 하단 정보가 좀 더 위쪽으로 이동합니다. (150px정도)"></i>
			</th>
			<td>
				<select class="form-control input-large" name="PRODUCT_THUMBNAIL_POS">
					<option value="0" <?php echo ($config->PRODUCT_THUMBNAIL_POS=="0") ? "selected" : ""?>>하단</option>
					<option value="1" <?php echo ($config->PRODUCT_THUMBNAIL_POS=="1") ? "selected" : ""?>>우측</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				연락받기 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="고객이 전화번호를 남기는 기능"></i>
			</th>
			<td>
				<select class="form-control input-large" name="USE_CALL_REMAIN">
					<option value="0" <?php echo ($config->USE_CALL_REMAIN=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USE_CALL_REMAIN=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>	
			<th>
				우측 담당자정보 표시 <i class="fa fa-question-circle" data-toggle="tooltip" title="우측 담당자정보 표시"></i>
			</th>
			<td>
				<select class="form-control input-large" name="MEMBER_INFO_RIGHT">
					<option value="0" <?php echo ($config->MEMBER_INFO_RIGHT=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->MEMBER_INFO_RIGHT=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				평당가 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="평당가 표시여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="UNIT_FLAG">
					<option value="0" <?php echo ($config->UNIT_FLAG=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->UNIT_FLAG=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				평당가격표시구분 <i class="fa fa-question-circle" data-toggle="tooltip" title="old : 평(평당 가격), new : 평방미터(㎡당 가격) ,only : 평방미터만"></i>
			</th>
			<td>
				<select class="form-control input-large" name="UNIT">
					<option value="old" <?php echo ($config->UNIT=="old") ? "selected" : ""?>>평(평당 가격)</option>
					<option value="new" <?php echo ($config->UNIT=="new") ? "selected" : ""?>>평방미터(㎡당 가격)</option>
					<option value="only" <?php echo ($config->UNIT=="only") ? "selected" : ""?>>평방미터만</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				매물옵션 비선택 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물옵션 비선택 표시여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="OPTION_FLAG">
					<option value="0" <?php echo ($config->OPTION_FLAG=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->OPTION_FLAG=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				다음인근정보키 <i class="fa fa-question-circle" data-toggle="tooltip" title="다음인근정보키"></i>
			</th>
			<td>
				<input class="form-control input-large" type="text" name="DAUM" value="<?php echo $config->DAUM?>"/>
			</td>
		</tr>
		<tr>
			<th>
				등록일/수정일 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="등록일/수정일 표시여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="DATE_DISPLAY">
					<option value="0" <?php echo ($config->DATE_DISPLAY=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->DATE_DISPLAY=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				목록에서 보여주는 면적 우선순위 <i class="fa fa-question-circle" data-toggle="tooltip" title="목록에서 보여주는 면적 우선순위"></i>
			</th>
			<td>
				<select class="form-control input-large" name="AREA_SORTING">
					<option value="0" <?php echo ($config->AREA_SORTING=="0") ? "selected" : ""?>>계약면적</option>
					<option value="1" <?php echo ($config->AREA_SORTING=="1") ? "selected" : ""?>>실면적</option>
				</select>
			</td>
		</tr>
</table>		

<br/>
<h4><strong><?php echo lang("product");?> 관리 설정</strong></h4>
<table class="table table-bordered table-striped-left table-condensed flip-content">
	<tbody>
		<tr>
			<th width="20%">
				사진퀄리티 비율 <i class="fa fa-question-circle" data-toggle="tooltip" title="사진퀄리티 비율"></i>
			</th>
			<td width="30%">
				<input class="form-control input-large" type="text" name="QUALITY" value="<?php echo $config->QUALITY?>"/>
			</td>			
			<th width="20%">
				매물등록시 기본지역(시도) <i class="fa fa-question-circle" data-toggle="tooltip" title="매물등록시 기본지역"></i>
			</th>
			<td width="30%">
				<select class="form-control input-inline input-small" name="INIT_SIDO"></select>
				<select class="form-control input-inline input-small" name="INIT_GUGUN"></select>
				<select class="form-control input-inline input-small" name="INIT_DONG"></select>
			</td>
		</tr>
	</tbody>
</table>		
<br/>

<h4><strong>매물 검색 지도 설정 <small>- 레벨 숫자가 작아질 수록 더 세밀하게 확대가 됩니다.</small></strong></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th width="20%">
				다음지도키 <i class="fa fa-question-circle" data-toggle="tooltip" title="다음지도키"></i>
			</th>
			<td colspan="3"><input class="form-control input-large" type="text" name="DAUM_MAP_KEY" value="<?php echo $config->DAUM_MAP_KEY?>"/></td>
		</tr>
		<tr>
			<th width="20%">
				지도스타일 <i class="fa fa-question-circle" data-toggle="tooltip" title="지도스타일"></i>
			</th>
			<td width="30%">
				<select class="form-control input-large" name="MAP_STYLE">
					<option value="1" <?php echo ($config->MAP_STYLE=="1") ? "selected" : ""?>>A타입</option>
					<option value="2" <?php echo ($config->MAP_STYLE=="2") ? "selected" : ""?>>B타입</option>
					<option value="3" <?php echo ($config->MAP_STYLE=="3") ? "selected" : ""?>>C타입</option>
					<option value="4" <?php echo ($config->MAP_STYLE=="4") ? "selected" : ""?>>D타입</option>
				</select>
			</td>
			<th width="20%">
				제목표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="제목표시여부"></i>
			</th>
			<td width="30%">
				<select class="form-control input-large" name="MAP_TITLE">
					<option value="0" <?php echo ($config->MAP_TITLE=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->MAP_TITLE=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th width="20%">
				최초 지도 확대레벨 <i class="fa fa-question-circle" data-toggle="tooltip" title="최초지도확대레벨"></i>
			</th>
			<td width="30%">
				<select class="form-control input-large" name="MAP_INIT_LEVEL">
				<?php for($i=1; $i<=15; $i++){?>
					<option value="<?php echo $i?>" <?php echo ($config->MAP_INIT_LEVEL==$i) ? "selected" : ""?>><?php echo $i?>레벨</option>
				<?php }?>
				</select>
			</td>
			<th width="20%">
				지도 최대 확대레벨 <i class="fa fa-question-circle" data-toggle="tooltip" title="최초지도확대레벨"></i>
			</th>
			<td width="30%">
				<select class="form-control input-large" name="MAP_MAX_LEVEL">
				<?php for($i=1; $i<=15; $i++){?>
					<option value="<?php echo $i?>" <?php echo ($config->MAP_MAX_LEVEL==$i) ? "selected" : ""?>><?php echo $i?>레벨</option>
				<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				지도형/맵형 구분(웹) <i class="fa fa-question-circle" data-toggle="tooltip" title="0:목록형,1:지도형(웹)"></i>
			</th>
			<td>
				<select class="form-control input-large" name="MAP_BIG">
					<option value="0" <?php echo ($config->MAP_BIG=="0") ? "selected" : ""?>>목록형</option>
					<option value="1" <?php echo ($config->MAP_BIG=="1") ? "selected" : ""?>>지도형</option>
				</select>
			</td>
			<th>
				지도형/맵형 구분(모바일) <i class="fa fa-question-circle" data-toggle="tooltip" title="0:목록형,1:지도형(웹)"></i>
			</td>
			<td>
				<select class="form-control input-large" name="M_MAP_BIG">
					<option value="0" <?php echo ($config->M_MAP_BIG=="0") ? "selected" : ""?>>목록형</option>
					<option value="1" <?php echo ($config->M_MAP_BIG=="1") ? "selected" : ""?>>지도형</option>
				</select>
			</td>
		</tr>

		<tr>
			<th>
				맵 클러스터 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="0:원묶음표시,1:각각표시"></i>
			</th>
			<td>
				<select class="form-control input-large" name="MAP_CLUSTER">
					<option value="0" <?php echo ($config->MAP_CLUSTER=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->MAP_CLUSTER=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				맵 좌표 가격/아이콘 표시구분 <i class="fa fa-question-circle" data-toggle="tooltip" title="0:가격표시,1:아이콘표시"></i>
			</td>
			<td>
				<select class="form-control input-large" name="MAP_ICON_ONLY">
					<option value="0" <?php echo ($config->MAP_ICON_ONLY=="0") ? "selected" : ""?>>가격표시</option>
					<option value="1" <?php echo ($config->MAP_ICON_ONLY=="1") ? "selected" : ""?>>아이콘표시</option>
				</select>
			</td>
		</tr>

		<tr>
			<th>
				지도 위 알림 문구
			</th>
			<td colspan="3">
				<div class="help-block">* 매물 상세보기 화면에서 지도 위의 알림 문구입니다.</div>
				<input class="form-control" type="text" name="MAP_ALERT" value="<?php echo $config->MAP_ALERT?>">
			</td>
		</tr>		
	</tbody>
</table>
<br/>


<h4><strong>홈 지도 설정(홈에서 지도 사용시에 적용)</strong></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th width="20%">
				최초 지도 확대영역 <i class="fa fa-question-circle" data-toggle="tooltip" title="최초지도확대레벨"></i>
			</th>
			<td width="30%">
				<select class="form-control input-large" name="STATS">
					<option value="gugun" <?php echo ($config->STATS=="gugun") ? "selected" : ""?>>구군</option>
					<option value="dong" <?php echo ($config->STATS=="dong") ? "selected" : ""?>>동(읍)</option>
				</select>
			</td>
			<th width="20%">
				홈 지도 확대 오차 범위<i class="fa fa-question-circle" data-toggle="tooltip" title="홈 지도 확대 오차 범위"></i>
			</th>
			<td width="30%">
				<select class="form-control input-large" name="HOME_MAP_ERROR">
					<option value="0" <?php echo ($config->HOME_MAP_ERROR=="0") ? "selected" : ""?>>0</option>
					<option value="1" <?php echo ($config->HOME_MAP_ERROR=="1") ? "selected" : ""?>>1</option>
					<option value="2" <?php echo ($config->HOME_MAP_ERROR=="2") ? "selected" : ""?>>2</option>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<br/>

<h4><strong>가격 설정 <small>- 가격 검색시 최대 범위값(최대 값이 되면 무제한이 됨)</small></strong></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th width="20%">
				매매가 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="매매가 최대값"></i>
			</th>
			<td width="30%"><input class="form-control input-inline" type="text" name="SELL_MAX" value="<?php echo $config->SELL_MAX?>"/> 억원</td>
			<th width="20%">
				전세가 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="전세가 최대값"></i>
			</th>
			<td width="30%"><input class="form-control input-inline" type="text" name="FULL_MAX" value="<?php echo $config->FULL_MAX?>"/> 만원</td>
		</tr>
		<tr>
			<th>
				월세보증금 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="월세보증금 최대값"></i>
			</th>
			<td><input class="form-control input-inline" type="text" name="MONTH_DEPOSIT_MAX" value="<?php echo $config->MONTH_DEPOSIT_MAX?>"/> 만원</td>
			<th>
				월세임대료 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="월세임대료 최대값"></i>
			</th>
			<td><input class="form-control input-inline" type="text" name="MONTH_MAX" value="<?php echo $config->MONTH_MAX?>"/> 만원</td>
		</tr>
	</tbody>
</table>

<br/>

<h4><strong>검색 설정 <small>- 매물 검색과 관련된 설정</small></strong></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th width="20%">
				거래/지역 검색 위치
			</th>
			<td width="30%">
				<select class="form-control input-large" name="SEARCH_POSITION">
					<option value="0" <?php echo ($config->SEARCH_POSITION=="0") ? "selected" : ""?>>좌측</option>
					<option value="1" <?php echo ($config->SEARCH_POSITION=="1") ? "selected" : ""?>>상단</option>
					<option value="2" <?php echo ($config->SEARCH_POSITION=="2") ? "selected" : ""?>>하단</option>
				</select>
			</td>
			<th width="20%">
				지역 사전 설정
			</th>
			<td width="30%">
				<select class="form-control input-large" name="REGION_USE">
					<option value="0" <?php echo ($config->REGION_USE=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->REGION_USE=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th width="20%">
				매물목록랜덤방식 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물목록랜덤방식"></i>
			</th>
			<td width="30%">
				<select class="form-control input-large" name="RANDOM">
					<option value="0" <?php echo ($config->RANDOM=="0") ? "selected" : ""?>>일반</option>
					<option value="1" <?php echo ($config->RANDOM=="1") ? "selected" : ""?>>랜덤</option>
				</select>
			</td>
			<th width="20%">
				검색창 순서 타입 <i class="fa fa-question-circle" data-toggle="tooltip" title="1:(지역,지하철,통합) 2:(통합,지역,지하철)"></i>
			</th>
			<td width="30%">
				<select class="form-control input-large" name="SEARCH_ORDER">
					<option value="1" <?php echo ($config->SEARCH_ORDER=="1") ? "selected" : ""?>>지역,지하철,통합</option>
					<option value="2" <?php echo ($config->SEARCH_ORDER=="2") ? "selected" : ""?>>통합,지역,지하철</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				지하철 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="지하철 사용여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="SUBWAY">
					<option value="0" <?php echo ($config->SUBWAY=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->SUBWAY=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				매물정렬방식 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물정렬방식"></i>
			</th>
			<td>
				<select class="form-control input-large" name="DEFAULT_SORT">
					<option value="basic" <?php echo ($config->DEFAULT_SORT=="basic") ? "selected" : ""?>><?php echo lang("sort.recommend");?></option>
					<option value="speed" <?php echo ($config->DEFAULT_SORT=="speed") ? "selected" : ""?>><?php echo lang("sort.speed");?></option>
					<option value="date_desc" <?php echo ($config->DEFAULT_SORT=="date_desc") ? "selected" : ""?>><?php echo lang("sort.newest");?></option>
					<option value="date_asc" <?php echo ($config->DEFAULT_SORT=="date_asc") ? "selected" : ""?>><?php echo lang("sort.oldest");?></option>
					<option value="price_desc" <?php echo ($config->DEFAULT_SORT=="price_desc") ? "selected" : ""?>><?php echo lang("sort.high");?></option>
					<option value="price_asc" <?php echo ($config->DEFAULT_SORT=="price_asc") ? "selected" : ""?>><?php echo lang("sort.low");?></option>
					<option value="area_desc" <?php echo ($config->DEFAULT_SORT=="area_desc") ? "selected" : ""?>><?php echo lang("sort.big");?></option>
					<option value="area_asc" <?php echo ($config->DEFAULT_SORT=="area_asc") ? "selected" : ""?>><?php echo lang("sort.small");?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo lang("product.theme");?>검색 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo lang("product.theme");?>검색 사용여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="USE_THEME">
					<option value="0" <?php echo ($config->USE_THEME=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USE_THEME=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
			<th>
				계약완료 상품 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="계약완료 상품 표시여부"></i>
			</th>
			<td>
				<select class="form-control input-large" name="COMPLETE_DISPLAY">
					<option value="0" <?php echo ($config->COMPLETE_DISPLAY=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->COMPLETE_DISPLAY=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<br/>

<h4><strong>기타 설정</strong></h4>
<table class="table table-bordered table-striped-left table-condensed flip-content">
	<tbody>
		<tr>
			<th width="20%">
				의뢰하기 내놓기(매도) 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="의뢰하기 내놓기(매도) 사용여부"></i>
			</th>
			<td width="80%">
				<select class="form-control input-large" name="ENQUIRE_SELL">
					<option value="0" <?php echo ($config->ENQUIRE_SELL=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->ENQUIRE_SELL=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				회원가입 후 이동 페이지 지정 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원가입 후 이동 페이지 지정"></i>
			</th>
			<td>
				<input class="form-control input-large" type="text" name="SIGNUP_REDIRECT" value="<?php echo $config->SIGNUP_REDIRECT?>"/>
			</td>
		</tr>
		<tr>
			<th>
				모바일 스플래시 윈도우 타입 <i class="fa fa-question-circle" data-toggle="tooltip" title="모바일 화면에서 스플래시 윈도우(1~11)"></i>
			</th>
			<td class="text-center">
				<select class="form-control input-large" name="MOBILE_SPLASH">
				<?php for($i=1; $i<=11; $i++){?>
					<option value="<?php echo $i?>" <?php echo ($config->MOBILE_SPLASH==$i) ? "selected" : ""?>><?php echo $i?>타입</option>
				<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				블로그 포스팅시 타이틀에 거래유형 표시 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="블로그 포스팅시 타이틀에 매물유형 표시 여부"></i>
			</th>
			<td class="text-center">
				<select class="form-control input-large" name="BLOG_TITLE_HEAD">
					<option value="0" <?php echo ($config->BLOG_TITLE_HEAD=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->BLOG_TITLE_HEAD=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				뉴스에 담당자와 날짜 표시 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="뉴스에 담당자와 날짜 표시 여부"></i>
			</th>
			<td class="text-center">
				<select class="form-control input-large" name="NEWS_DATE_VIEW">
					<option value="0" <?php echo ($config->NEWS_DATE_VIEW=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->NEWS_DATE_VIEW=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				의뢰하기 형태 설정 <i class="fa fa-question-circle" data-toggle="tooltip" title="의뢰하기 형태 설정"></i>
			</th>
			<td class="text-center">
				<select class="form-control input-large" name="ENQUIRE_TYPE">
					<option value="0" <?php echo ($config->ENQUIRE_TYPE=="0") ? "selected" : ""?>>일반형태</option>
					<option value="1" <?php echo ($config->ENQUIRE_TYPE=="1") ? "selected" : ""?>>목록형태</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				문의하기 형태 설정 <i class="fa fa-question-circle" data-toggle="tooltip" title="문의하기 형태 설정"></i>
			</th>
			<td class="text-center">
				<select class="form-control input-large" name="ASK_TYPE">
					<option value="0" <?php echo ($config->ASK_TYPE=="0") ? "selected" : ""?>>일반형태</option>
					<option value="1" <?php echo ($config->ASK_TYPE=="1") ? "selected" : ""?>>목록형태</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				회원가입 휴대폰인증 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원가입 휴대폰인증 사용여부"></i>
			</th>
			<td class="text-center">
				<select class="form-control input-large" name="MEMBER_PHONE_CONFIRM">
					<option value="0" <?php echo ($config->MEMBER_PHONE_CONFIRM=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->MEMBER_PHONE_CONFIRM=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				회원가입 승인 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원가입 승인 사용여부"></i>
			</th>
			<td class="text-center">
				<select class="form-control input-large" name="MEMBER_APPROVE">
					<option value="0" <?php echo ($config->MEMBER_APPROVE=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->MEMBER_APPROVE=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				자동로그아웃 (단위:분) <i class="fa fa-question-circle" data-toggle="tooltip" title="0이면 사용을 안하는 것입니다."></i>
			</th>
			<td class="text-center">
				<input type="number" name="AUTO_LOGOUT" class="form-control input-large" value="<?php echo $config->AUTO_LOGOUT;?>">
			</td>
		</tr>		
	</tbody>
</table>


<h4><strong>앱 설치경로</strong></h4>
<table class="table table-bordered">
	<tr>
		<th width="11%">
			구글앱경로 <i class="fa fa-question-circle" data-toggle="tooltip" title="구글앱경로"></i>
		</td>
		<td width="22%"><input class="form-control input-inline" type="text" name="GPLAY" value="<?php echo $config->GPLAY?>" placeholder="예:com.dungzi.username"/> </td>
		<th width="11%">
			티스토어앱경로 <i class="fa fa-question-circle" data-toggle="tooltip" title="티스토어앱경로"></i>
		</td>
		<td width="22%"><input class="form-control input-inline" type="text" name="TSTORE" value="<?php echo $config->TSTORE?>"/></td>
		<th width="11%">
			네이버앱경로 <i class="fa fa-question-circle" data-toggle="tooltip" title="네이버앱경로"></i>
		</td>
		<td width="22%"><input class="form-control input-inline" type="text" name="NAVER" value="<?php echo $config->NAVER?>"/></td>
	</tr>
</table>

<h4><strong>디자인 요소 설정</strong></h4>
<table class="table table-bordered">
	<tr>
		<th width="20%">
			로고 우측 멘트
		</td>
		<td width="40%">
			<textarea class="form-control" name="DESIGN_LOGO_RIGHT" style="height:100px;"><?php echo $config->DESIGN_LOGO_RIGHT?></TEXTAREA>
		</td>
		<td width="40%">
			로고 우측에 전화번호나 멘트를 작성하는 부분입니다.
		</td>			
	</tr>
	<tr>
		<th width="20%">
			최신매물우측에 정보창 사용여부
		</td>
		<td width="40%">
			<select class="form-control input-inline" name="DESIGN_RECENT_RIGHT">
				<option value="0" <?php echo ($config->DESIGN_RECENT_RIGHT=="0") ? "selected" : ""?>>사용안함</option>
				<option value="1" <?php echo ($config->DESIGN_RECENT_RIGHT=="1") ? "selected" : ""?>>사용함</option>
			</select> 
		</td>
		<td width="40%">
			홈에서 최신 매물 우측에 뉴스, 공지사항 등 내용들을 보여주는 정보창을 보여주는 기능입니다.
		</td>
	</tr>
	<tr>
		<th width="20%">
			기본폰트 설정
		</td>
		<td width="40%">
			<select class="form-control input-inline" name="FONT_BASIC">
				<option value="Nanum Gothic" <?php echo ($config->FONT_BASIC=="Nanum Gothic") ? "selected" : ""?>>나눔고딕</option>
				<option value="Nanum Gothic Coding" <?php echo ($config->FONT_BASIC=="Nanum Gothic Coding") ? "selected" : ""?>>나눔고딕코딩</option>
				<option value="Nanum Myeongjo" <?php echo ($config->FONT_BASIC=="Nanum Myeongjo") ? "selected" : ""?>>나눔명조</option>
				<option value="Hanna" <?php echo ($config->FONT_BASIC=="Hanna") ? "selected" : ""?>>한나</option>
				<option value="Jeju Gothic" <?php echo ($config->FONT_BASIC=="Jeju Gothic") ? "selected" : ""?>>제주고딕</option>
				<option value="KoPub Batang" <?php echo ($config->FONT_BASIC=="KoPub Batang") ? "selected" : ""?>>코펍바탕</option>
				<option value="Jeju Myeongjo" <?php echo ($config->FONT_BASIC=="Jeju Myeongjo") ? "selected" : ""?>>제주명조</option>
			</select> 
		</td>
		<td width="40%">
			홈에서 최신 매물 우측에 뉴스, 공지사항 등 내용들을 보여주는 정보창을 보여주는 기능입니다.
		</td>
	</tr>	
</table>

<?php echo form_close();?>