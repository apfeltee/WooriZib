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
td{
	vertical-align:middle !important;
}
</style>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				고급설정<small>수정</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index">홈</a> <i class="fa fa-angle-right"></i> </li>
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
<h4>둥지</h4>
<table class="table table-bordered table-striped-left table-condensed flip-content">
	<tbody>
		<tr>
			<td  width="350">
				<div class="text-center">매물목록타입 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물목록 타입 1~3"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="LISTING">
					<option value="1" <?php echo ($config->LISTING=="1") ? "selected" : ""?>>1타입</option>
					<option value="2" <?php echo ($config->LISTING=="2") ? "selected" : ""?>>2타입</option>
					<option value="3" <?php echo ($config->LISTING=="3") ? "selected" : ""?>>3타입</option>
				</select>
			</td>
		</tr>
		<tr>
			<td  width="350">
				<div class="text-center">요금미납여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="요금미납여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="PAY">
					<option value="0" <?php echo ($config->PAY=="0") ? "selected" : ""?>>미납</option>
					<option value="1" <?php echo ($config->PAY=="1") ? "selected" : ""?>>완납</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">사용호스팅 <i class="fa fa-question-circle" data-toggle="tooltip" title="사용호스팅"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="HOSTING" value="<?php echo $config->HOSTING?>"/>
			</td>
		</tr>
		<tr>
			<td  width="350">
				<div class="text-center">카피라이트 둥지표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="카피라이트 둥지표시여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="DUNGZI">
					<option value="0" <?php echo ($config->DUNGZI=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->DUNGZI=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td  width="350">
				<div class="text-center">회원로그인 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원로그인 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="MEMBER_JOIN">
					<option value="0" <?php echo ($config->MEMBER_JOIN=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->MEMBER_JOIN=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td  width="350">
				<div class="text-center">관리자 간편 회원가입 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="관리자 간편 회원가입 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="ADMIN_JOIN">
					<option value="0" <?php echo ($config->ADMIN_JOIN=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->ADMIN_JOIN=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td  width="350">
				<div class="text-center">데모사이트 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="데모사이트 여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="IS_DEMO">
					<option value="0" <?php echo ($config->IS_DEMO=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->IS_DEMO=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">공장정보 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="공장정보 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="USE_FACTORY">
					<option value="0" <?php echo ($config->USE_FACTORY=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USE_FACTORY=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">유저페이지 매물관리 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="유저페이지 매물관리 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="USER_PRODUCT">
					<option value="0" <?php echo ($config->USER_PRODUCT=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USER_PRODUCT=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td  width="350">
				<div class="text-center">결제 시스템 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="결제 시스템 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="USE_PAY">
					<option value="0" <?php echo ($config->USE_PAY=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USE_PAY=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">유저매물 등록시 승인여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="유저매물 등록시 승인여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="USE_APPROVE">
					<option value="0" <?php echo ($config->USE_APPROVE=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USE_APPROVE=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">회원 형태 설정 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원 형태 설정"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="MEMBER_TYPE">
					<option value="general" <?php echo ($config->MEMBER_TYPE=="general") ? "selected" : ""?>>개인회원만 사용</option>
					<option value="biz" <?php echo ($config->MEMBER_TYPE=="biz") ? "selected" : ""?>>공인중개사회원만 사용</option>
					<option value="both" <?php echo ($config->MEMBER_TYPE=="both") ? "selected" : ""?>>개인회원/공인중개사회원 사용</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">매물목록 개방형/폐쇄형 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원 형태 설정"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="LIST_ENCLOSED">
					<option value="0" <?php echo ($config->LIST_ENCLOSED=="0") ? "selected" : ""?>>개방형</option>
					<option value="1" <?php echo ($config->LIST_ENCLOSED=="1") ? "selected" : ""?>>폐쇄형</option>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<?php }?>

<br/>
<h4><?php echo lang("product");?> 상세정보 설정</h4>
<table class="table table-bordered table-striped-left table-condensed flip-content">
	<tbody>
		<tr>
			<td width="350">
				<div class="text-center">상세 주소 보여주기</div>
			</td>
			<td>
				<select class="form-control input-large" name="SHOW_ADDRESS">
					<option value="0" <?php echo ($config->SHOW_ADDRESS=="0") ? "selected" : ""?>>보여주지 않음</option>
					<option value="1" <?php echo ($config->SHOW_ADDRESS=="1") ? "selected" : ""?>>보임</option>
				</select>				

				<div class="help-block">* 홈페이지 상세정보에서 동아래 번지까지 모두 보여주는 설정이며 기본은 보여지지 않습니다. 상세 주소를 보여주면 문의전화가 더 많이 온다는 통계가 있습니다. (미국)</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">지도 원 반경 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물상세 지도 원 반경"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="RADIUS" value="<?php echo $config->RADIUS?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">연락처정보보기 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="연락처정보보기 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="CALL_HIDDEN">
					<option value="0" <?php echo ($config->CALL_HIDDEN=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->CALL_HIDDEN=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>	
		<tr>
			<td width="350">
				<div class="text-center">매물 갤러리 썸네일 위치</div>
			</td>
			<td>
				<select class="form-control input-large" name="PRODUCT_THUMBNAIL_POS">
					<option value="0" <?php echo ($config->PRODUCT_THUMBNAIL_POS=="0") ? "selected" : ""?>>하단</option>
					<option value="1" <?php echo ($config->PRODUCT_THUMBNAIL_POS=="1") ? "selected" : ""?>>우측</option>
				</select>
				<div class="help-block">* 썸네일 하단은 사진이 더 크게 보입니다. 썸네일 우측은 갤러리 하단 정보가 좀 더 위쪽으로 이동합니다. (150px정도) </div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">사진퀄리티 비율 <i class="fa fa-question-circle" data-toggle="tooltip" title="사진퀄리티 비율"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="QUALITY" value="<?php echo $config->QUALITY?>"/>
			</td>
		</tr>
</table>
<br/>

<h4>기타 설정</h4>
<table class="table table-bordered table-striped-left table-condensed flip-content">
	<tbody>
		<tr>
			<td width="350">
				<div class="text-center">분양 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="분양 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="INSTALLATION_FLAG">
					<option value="0" <?php echo ($config->INSTALLATION_FLAG=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->INSTALLATION_FLAG=="1") ? "selected" : ""?>>사용함</option>
					<option value="2" <?php echo ($config->INSTALLATION_FLAG=="2") ? "selected" : ""?>>분양만 사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">융자금 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="분양 사용시에 융자금 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="INSTALLATION_LOAN_FLAG">
					<option value="0" <?php echo ($config->INSTALLATION_LOAN_FLAG=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->INSTALLATION_LOAN_FLAG=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">매물목록랜덤방식 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물목록랜덤방식"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="RANDOM">
					<option value="0" <?php echo ($config->RANDOM=="0") ? "selected" : ""?>>일반</option>
					<option value="1" <?php echo ($config->RANDOM=="1") ? "selected" : ""?>>랜덤</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">다음지도키 <i class="fa fa-question-circle" data-toggle="tooltip" title="다음지도키"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="DAUM_MAP_KEY" value="<?php echo $config->DAUM_MAP_KEY?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">최초 지도 확대레벨 <i class="fa fa-question-circle" data-toggle="tooltip" title="최초지도확대레벨"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="MAP_INIT_LEVEL">
				<?php for($i=1; $i<=15; $i++){?>
					<option value="<?php echo $i?>" <?php echo ($config->MAP_INIT_LEVEL==$i) ? "selected" : ""?>><?php echo $i?>레벨</option>
				<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">지도 최대 확대레벨 <i class="fa fa-question-circle" data-toggle="tooltip" title="최초지도확대레벨"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="MAP_MAX_LEVEL">
				<?php for($i=1; $i<=15; $i++){?>
					<option value="<?php echo $i?>" <?php echo ($config->MAP_MAX_LEVEL==$i) ? "selected" : ""?>><?php echo $i?>레벨</option>
				<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">최초 지도 확대영역 <i class="fa fa-question-circle" data-toggle="tooltip" title="최초지도확대레벨"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="STATS">
					<option value="gugun" <?php echo ($config->STATS=="gugun") ? "selected" : ""?>>구군</option>
					<option value="dong" <?php echo ($config->STATS=="dong") ? "selected" : ""?>>동(읍)</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">지도형/맵형 구분(웹) <i class="fa fa-question-circle" data-toggle="tooltip" title="0:목록형,1:지도형(웹)"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="MAP_BIG">
					<option value="0" <?php echo ($config->MAP_BIG=="0") ? "selected" : ""?>>목록형</option>
					<option value="1" <?php echo ($config->MAP_BIG=="1") ? "selected" : ""?>>지도형</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">지도형/맵형 구분(모바일) <i class="fa fa-question-circle" data-toggle="tooltip" title="0:목록형,1:지도형(웹)"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="M_MAP_BIG">
					<option value="0" <?php echo ($config->M_MAP_BIG=="0") ? "selected" : ""?>>목록형</option>
					<option value="1" <?php echo ($config->M_MAP_BIG=="1") ? "selected" : ""?>>지도형</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">매매가 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="매매가 최대값"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="SELL_MAX" value="<?php echo $config->SELL_MAX?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">전세가 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="전세가 최대값"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="FULL_MAX" value="<?php echo $config->FULL_MAX?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">월세보증금 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="월세보증금 최대값"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="MONTH_DEPOSIT_MAX" value="<?php echo $config->MONTH_DEPOSIT_MAX?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">월세임대료 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="월세임대료 최대값"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="MONTH_MAX" value="<?php echo $config->MONTH_MAX?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">지하철 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="지하철 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="SUBWAY">
					<option value="0" <?php echo ($config->SUBWAY=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->SUBWAY=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">매물옵션 비선택 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물옵션 비선택 표시여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="OPTION_FLAG">
					<option value="0" <?php echo ($config->OPTION_FLAG=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->OPTION_FLAG=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">다음인근정보키 <i class="fa fa-question-circle" data-toggle="tooltip" title="다음인근정보키"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="DAUM" value="<?php echo $config->DAUM?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">홈 지도 확대 오차 범위 <i class="fa fa-question-circle" data-toggle="tooltip" title="홈 지도 확대 오차 범위"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="HOME_MAP_ERROR">
					<option value="0" <?php echo ($config->HOME_MAP_ERROR=="0") ? "selected" : ""?>>0</option>
					<option value="1" <?php echo ($config->HOME_MAP_ERROR=="1") ? "selected" : ""?>>1</option>
					<option value="2" <?php echo ($config->HOME_MAP_ERROR=="2") ? "selected" : ""?>>2</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">매물정렬방식 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물정렬방식"></i></div>
			</td>
			<td>
				<select class="form-control input-large" name="DEFAULT_SORT">
					<option value="basic" <?php echo ($config->DEFAULT_SORT=="basic") ? "selected" : ""?>>추천 및 최신순</option>
					<option value="speed" <?php echo ($config->DEFAULT_SORT=="speed") ? "selected" : ""?>>급매 및 최신순</option>
					<option value="date_desc" <?php echo ($config->DEFAULT_SORT=="date_desc") ? "selected" : ""?>>최신 등록순</option>
					<option value="date_asc" <?php echo ($config->DEFAULT_SORT=="date_asc") ? "selected" : ""?>>최신 등록 역순</option>
					<option value="price_desc" <?php echo ($config->DEFAULT_SORT=="price_desc") ? "selected" : ""?>>높은 가격순</option>
					<option value="price_asc" <?php echo ($config->DEFAULT_SORT=="price_asc") ? "selected" : ""?>>낮은 가격순</option>
					<option value="area_desc" <?php echo ($config->DEFAULT_SORT=="area_desc") ? "selected" : ""?>>넓은 면적순</option>
					<option value="area_asc" <?php echo ($config->DEFAULT_SORT=="area_asc") ? "selected" : ""?>>좁은 면적순</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">토지정보 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="토지정보 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="USE_GROUND">
					<option value="0" <?php echo ($config->USE_GROUND=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USE_GROUND=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">매물입력시 전체,부분 선택여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물입력시 전체,부분 선택여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="PART_DEFAULT">
					<option value="N" <?php echo ($config->PART_DEFAULT=="N") ? "selected" : ""?>>전체</option>
					<option value="Y" <?php echo ($config->PART_DEFAULT=="Y") ? "selected" : ""?>>부분</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">테마검색 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="테마검색 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="USE_THEME">
					<option value="0" <?php echo ($config->USE_THEME=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USE_THEME=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">주차비 관리비 항목 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="주차비 관리비 항목 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="USE_ETC_FEE">
					<option value="0" <?php echo ($config->USE_ETC_FEE=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->USE_ETC_FEE=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">계약완료 상품 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="계약완료 상품 표시여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="COMPLETE_DISPLAY">
					<option value="0" <?php echo ($config->COMPLETE_DISPLAY=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->COMPLETE_DISPLAY=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">결제용 상점아이디 <i class="fa fa-question-circle" data-toggle="tooltip" title="결제용 상점아이디"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="CLIENTID" value="<?php echo $config->CLIENTID?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">매물등록시 기본지역(시도) <i class="fa fa-question-circle" data-toggle="tooltip" title="매물등록시 기본지역"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="INIT_SIDO" value="<?php echo $config->INIT_SIDO?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">매물등록시 기본지역(구군) <i class="fa fa-question-circle" data-toggle="tooltip" title="매물등록시 기본지역"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="INIT_GUGUN" value="<?php echo $config->INIT_GUGUN?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">매물등록시 기본지역(읍동) <i class="fa fa-question-circle" data-toggle="tooltip" title="매물등록시 기본지역"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="INIT_DONG" value="<?php echo $config->INIT_DONG?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">평당가 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="평당가 표시여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="UNIT_FLAG">
					<option value="0" <?php echo ($config->UNIT_FLAG=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->UNIT_FLAG=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">평당가격표시구분 <i class="fa fa-question-circle" data-toggle="tooltip" title="old : 평(평당 가격), new : 평방미터(㎡당 가격) ,only : 평방미터만"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="UNIT">
					<option value="old" <?php echo ($config->UNIT=="old") ? "selected" : ""?>>평(평당 가격)</option>
					<option value="new" <?php echo ($config->UNIT=="new") ? "selected" : ""?>>평방미터(㎡당 가격)</option>
					<option value="only" <?php echo ($config->UNIT=="only") ? "selected" : ""?>>평방미터만</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">건물총보증금(월세) 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="건물총보증금(월세) 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="ALL_RENT_PRICE">
					<option value="0" <?php echo ($config->ALL_RENT_PRICE=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->ALL_RENT_PRICE=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">의뢰하기 내놓기(매도) 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="의뢰하기 내놓기(매도) 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="ENQUIRE_SELL">
					<option value="0" <?php echo ($config->ENQUIRE_SELL=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->ENQUIRE_SELL=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">회원가입 후 이동 페이지 지정 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원가입 후 이동 페이지 지정"></i></div>
			</td>
			<td>
				<input class="form-control input-large" type="text" name="SIGNUP_REDIRECT" value="<?php echo $config->SIGNUP_REDIRECT?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">검색창 순서 타입 <i class="fa fa-question-circle" data-toggle="tooltip" title="1:(지역,지하철,통합) 2:(통합,지역,지하철)"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="SEARCH_ORDER">
					<option value="1" <?php echo ($config->SEARCH_ORDER=="1") ? "selected" : ""?>>지역,지하철,통합</option>
					<option value="2" <?php echo ($config->SEARCH_ORDER=="2") ? "selected" : ""?>>통합,지역,지하철</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">모바일 스플래시 윈도우 타입 <i class="fa fa-question-circle" data-toggle="tooltip" title="모바일 화면에서 스플래시 윈도우(1~11)"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="MOBILE_SPLASH">
				<?php for($i=1; $i<=11; $i++){?>
					<option value="<?php echo $i?>" <?php echo ($config->MOBILE_SPLASH==$i) ? "selected" : ""?>><?php echo $i?>타입</option>
				<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">블로그 포스팅시 타이틀에 매물유형 표시 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="블로그 포스팅시 타이틀에 매물유형 표시 여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="BLOG_TITLE_HEAD">
					<option value="0" <?php echo ($config->BLOG_TITLE_HEAD=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->BLOG_TITLE_HEAD=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">뉴스에 담당자와 날짜 표시 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="뉴스에 담당자와 날짜 표시 여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="NEWS_DATE_VIEW">
					<option value="0" <?php echo ($config->NEWS_DATE_VIEW=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->NEWS_DATE_VIEW=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">의뢰하기 형태 설정 <i class="fa fa-question-circle" data-toggle="tooltip" title="의뢰하기 형태 설정"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="ENQUIRE_TYPE">
					<option value="0" <?php echo ($config->ENQUIRE_TYPE=="0") ? "selected" : ""?>>일반형태</option>
					<option value="1" <?php echo ($config->ENQUIRE_TYPE=="1") ? "selected" : ""?>>목록형태</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">문의하기 형태 설정 <i class="fa fa-question-circle" data-toggle="tooltip" title="문의하기 형태 설정"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="ASK_TYPE">
					<option value="0" <?php echo ($config->ASK_TYPE=="0") ? "selected" : ""?>>일반형태</option>
					<option value="1" <?php echo ($config->ASK_TYPE=="1") ? "selected" : ""?>>목록형태</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div class="text-center">회원가입 승인 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원가입 승인 사용여부"></i></div>
			</td>
			<td class="text-center">
				<select class="form-control input-large" name="MEMBER_APPROVE">
					<option value="0" <?php echo ($config->MEMBER_APPROVE=="0") ? "selected" : ""?>>사용안함</option>
					<option value="1" <?php echo ($config->MEMBER_APPROVE=="1") ? "selected" : ""?>>사용함</option>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<h4>앱 설치경로</h4>
<table class="table table-bordered table-striped-left table-condensed flip-content">
		<tr>
			<td  width="350">
				<div class="text-center">구글앱경로 <i class="fa fa-question-circle" data-toggle="tooltip" title="구글앱경로"></i></div>
			</td>
			<td>
				<input class="form-control input-large input-inline" type="text" name="GPLAY" value="<?php echo $config->GPLAY?>"/> (예: com.dungzi.username )
			</td>
		</tr>
		<tr>
			<td  width="350">
				<div class="text-center">티스토어앱경로 <i class="fa fa-question-circle" data-toggle="tooltip" title="티스토어앱경로"></i></div>
			</td>
			<td>
				<input class="form-control input-large input-inline" type="text" name="TSTORE" value="<?php echo $config->TSTORE?>"/>
			</td>
		</tr>
		<tr>
			<td  width="350">
				<div class="text-center">네이버앱경로 <i class="fa fa-question-circle" data-toggle="tooltip" title="네이버앱경로"></i></div>
			</td>
			<td>
				<input class="form-control input-large input-inline" type="text" name="NAVER" value="<?php echo $config->NAVER?>"/>
			</td>
		</tr>
</table>
<?php echo form_close();?>