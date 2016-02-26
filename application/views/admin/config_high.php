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
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				고급설정<small>보기</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index">홈</a> <i class="fa fa-angle-right"></i> </li>
				<li>
					고급설정 보기
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="location.href='/adminhome/config_high_edit'">수정</button>
			</div>
		</div>
	</div>
</div>

<?php if($show_menu){?>
<h4>둥지 <small>- 둥지에서만 설정이 가능한 섹션입니다.</small></h4>
<table class="table table-bordered" style="border:2px solid red;color:red;">
	<tbody>
		<tr>
			<th width="25%">
				요금미납여부
			</th width="25%">
			<td><?php echo ($config->PAY) ? "완납" : "미납"?></td>
			<th width="25%">
				사용호스팅 <i class="fa fa-question-circle" data-toggle="tooltip" title="사용호스팅"></i>
			</th>
			<td width="25%"><?php echo $config->HOSTING?></td>
		</tr>
		<tr>
			<th width="350">
				매물목록타입 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물목록 타입 1~3"></i>
			</th>
			<td>
				<?php 
				if( $config->LISTING =="3") {
					echo "3*3 그리드 방식";
				} else if($config->LISTING == "1") {
					echo "테이블 방식";
				} else {
					echo "설정없음(오류)";
				}
				?>
			</td>
			<th width="350">
				카피라이트 둥지표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="카피라이트 둥지표시여부"></i>
			</th>
			<td><?php echo ($config->DUNGZI) ? "사용함" : "사용안함"?></td>
		</tr>
		<tr>
			<th width="350">
				회원로그인 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원로그인 사용여부"></i>
			</th>
			<td><?php echo ($config->MEMBER_JOIN) ? "사용함" : "사용안함"?></td>
			<th width="350">
				관리자 간편 회원가입 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="관리자 간편 회원가입 사용여부"></i>
			</th>
			<td><?php echo ($config->ADMIN_JOIN) ? "사용함" : "사용안함"?></td>
		</tr>
		<tr>
			<th width="350">
				결제 시스템 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="결제 시스템 사용여부"></i>
			</th>
			<td><?php echo ($config->USE_PAY) ? "사용함" : "사용안함"?></td>
			<th>
				회원 형태 설정 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원 형태 설정"></i>
			</th>
			<td>
				<?php 
				if($config->MEMBER_TYPE=="general"){
					echo "개인회원만 사용";
				}
				else if($config->MEMBER_TYPE=="biz"){
					echo "공인중개사회원만 사용";
				}
				else if($config->MEMBER_TYPE=="both"){
					echo "개인회원/공인중개사회원 사용";
				}
				?>
			</td>
		</tr>
		<tr>
			<th>
				매물목록 개방형/폐쇄형 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원 형태 설정"></i>
			</th>
			<td><?php echo ($config->LIST_ENCLOSED) ? "폐쇄형" : "개방형"?></td>
			<th>
				매물 검색 지도 사용여부
			</th>
			<td><?php echo ($config->MAP_USE) ? "사용" : "미사용"?> (* 미사용시 패키지가격이 낮아짐)</td>
		</tr>
		<tr>
			<th width="350">
				데모사이트 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="데모사이트 여부"></i>
			</th>
			<td><?php echo ($config->IS_DEMO) ? "데모사이트" : "일반사이트"?></td>
			<th width="350">
				공장정보 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="공장정보 사용여부"></i>
			</th>
			<td><?php echo ($config->USE_FACTORY) ? "사용함" : "사용안함"?></td>
		</tr>
		<tr>
			<th width="25%">
				분양 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="분양 사용여부"></i>
			</th>
			<td width="25%">
				<?php
				if($config->INSTALLATION_FLAG=="0"){
					echo "사용안함";
				}else if($config->INSTALLATION_FLAG=="1"){
					echo "사용함";
				}else if($config->INSTALLATION_FLAG=="2"){
					echo "분양만 사용함";
				}
				?>
			</td>
			<th width="25%">
				공실 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="사용할 경우 담당자 정보 대신 연락처 입력한 내용이 보이게 됩니다."></i>
			</th>
			<td width="25%"><?php echo ($config->GONGSIL_FLAG) ? "사용함" : "사용안함"?></td>
		</tr>
		<tr>
			<th width="25%">
				유저페이지 매물관리 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="유저페이지 매물관리 사용여부"></i>
			</th>
			<td><?php echo ($config->USER_PRODUCT) ? "사용함" : "사용안함"?></td>
			<th width="25%">
				유저매물 등록시 승인여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="유저매물 등록시 승인여부"></i>
			</th>
			<td><?php echo ($config->USE_APPROVE) ? "사용함" : "사용안함"?></td>
		</tr>
		<tr>
			<th width="25%">
				결제용 상점아이디 <i class="fa fa-question-circle" data-toggle="tooltip" title="결제용 상점아이디"></i>
			</th>
			<td width="25%"><?php echo $config->CLIENTID?></td>
			<th width="25%">
				입금 계좌 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물 목록 좌측 하단에 계좌번호를 보여줍니다."></i>
			</th>
			<td width="25%"><?php echo $config->BANKACCOUNT?></td>
		</tr>		
	</tbody>
</table>
<?php }?>
<br/>

<h4><?php echo lang("product");?> 설정</h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th width="25%">
				상세 주소 보여주기  <i class="fa fa-question-circle" data-toggle="tooltip" title="홈페이지 상세정보에서 동아래 번지까지 모두 보여주는 설정이며 기본은 보여지지 않습니다. 상세 주소를 보여주면 문의전화가 더 많이 온다는 통계가 있습니다. (미국)"></i>
			</th>
			<td width="25%">
				<?php
				if($config->SHOW_ADDRESS=="0"){
					echo "보여주지 않음";
				}else if($config->SHOW_ADDRESS=="1"){
					echo "보임";
				}
				?>
			</td>
			<th width="25%">
				지도 원 반경 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물상세 지도 원 반경"></i>
			</th>
			<td><?php echo $config->RADIUS?> 미터(m)</td>
		</tr>
		<tr>
			<th width="25%">
				연락처정보보기 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="연락처정보보기 사용여부"></i>
			</th>
			<td><?php echo ($config->CALL_HIDDEN) ? "사용함" : "사용안함"?></td>
			<th width="25%">
				매물 갤러리 썸네일 위치 <i class="fa fa-question-circle" data-toggle="tooltip" title="썸네일 하단은 사진이 더 크게 보입니다. 썸네일 우측은 갤러리 하단 정보가 좀 더 위쪽으로 이동합니다. (150px정도)"></i>
			</th>
			<td>
				<?php
				if($config->PRODUCT_THUMBNAIL_POS=="0"){
					echo "하단";
				}else if($config->PRODUCT_THUMBNAIL_POS=="1"){
					echo "우측";
				}
				?>
			</td>
		</tr>
		<tr>
			<th width="25%">
				사진퀄리티 비율 <i class="fa fa-question-circle" data-toggle="tooltip" title="사진퀄리티 비율"></i>
			</th>
			<td><?php echo $config->QUALITY?></td>
			<th>
				다음인근정보키 <i class="fa fa-question-circle" data-toggle="tooltip" title="다음인근정보키"></i>
			</th>
			<td><?php echo $config->DAUM?></td>
		</tr>
		<tr>
			<th>난방 선택 항목 <i class="fa fa-question-circle" data-toggle="tooltip" title="선택항목을 콤마로 구분해 주세요. 항목이 없으면 보여주지 않습니다."></i></th>
			<td><?php echo $config->USE_HEATING?></td>
			<th width="25%">
				융자금 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="분양 사용시에 융자금 사용여부"></i>
			</th>
			<td width="25%"><?php echo ($config->INSTALLATION_LOAN_FLAG) ? "사용함" : "사용안함"?></td>
		</tr>
		<tr>
			<th>
				평당가 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="평당가 표시여부"></i>
			</th>
			<td><?php echo ($config->UNIT_FLAG) ? "사용함" : "사용안함"?></td>
			<th>
				평당가격표시구분 <i class="fa fa-question-circle" data-toggle="tooltip" title="old : 평(평당 가격), new : 평방미터(㎡당 가격) ,only : 평방미터만"></i>
			</th>
			<td>
				<?php 
				if($config->UNIT=="old"){
					echo "평(평당 가격)";
				}
				else if($config->UNIT=="new"){
					echo "평방미터(㎡당 가격)";
				}
				else if($config->UNIT=="only"){
					echo "평방미터만";
				}
				?>
			</td>
		</tr>
		<tr>
			<th>
				건물총보증금(월세) 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="부분이 아닌 전체일 때 총 보증금과 월세를 보여줄 지 말지를 선택"></i>
			</th>
			<td><?php echo ($config->ALL_RENT_PRICE) ? "사용함" : "사용안함"?></td>
			<th>
				주차비 관리비 항목 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="주차비 관리비 항목 사용여부"></i>
			</th>
			<td><?php echo ($config->USE_ETC_FEE) ? "사용함" : "사용안함"?></td>
		</tr>
		<tr>
			<th>
				매물옵션 비선택 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물옵션 비선택 표시여부"></i>
			</th>
			<td><?php echo ($config->OPTION_FLAG) ? "사용함" : "사용안함"?></td>
			<th>
				토지정보 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="토지정보 사용여부"></i>
			</th>
			<td><?php echo ($config->USE_GROUND) ? "사용함" : "사용안함"?></td>
		</tr>
</table>
<br/>

<h4>매물 검색 지도 설정 <small>- 레벨 숫자가 작아질 수록 더 세밀하게 확대가 됩니다.</small></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th width="25%">
				다음지도키 <i class="fa fa-question-circle" data-toggle="tooltip" title="다음지도키"></i>
			</th>
			<td colspan="3"><?php echo $config->DAUM_MAP_KEY?></td>
		</tr>
		<tr>
			<th width="25%">
				최초 지도 확대레벨 <i class="fa fa-question-circle" data-toggle="tooltip" title="최초지도확대레벨"></i>
			</th>
			<td><?php echo $config->MAP_INIT_LEVEL?>레벨</td>
			<th width="25%">
				지도 최대 확대레벨 <i class="fa fa-question-circle" data-toggle="tooltip" title="최초지도확대레벨"></i>
			</th>
			<td><?php echo $config->MAP_MAX_LEVEL?>레벨</td>
		</tr>
		<tr>
			<th width="25%">
				지도형/맵형 구분(웹) <i class="fa fa-question-circle" data-toggle="tooltip" title="0:목록형,1:지도형(웹)"></i>
			</th>
			<td><?php echo ($config->MAP_BIG) ? "지도형" : "목록형"?></td>
			<th width="25%">
				지도형/맵형 구분(모바일) <i class="fa fa-question-circle" data-toggle="tooltip" title="0:목록형,1:지도형(웹)"></i>
			</td>
			<td><?php echo ($config->M_MAP_BIG) ? "지도형" : "목록형"?></td>
		</tr>
	</tbody>
</table>
<br/>

<h4>홈 지도 설정(홈에서 지도 사용시에 적용)</h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th width="25%">
				최초 지도 확대영역 <i class="fa fa-question-circle" data-toggle="tooltip" title="최초지도확대레벨"></i>
			</th>
			<td width="25%">
				<?php
				if($config->STATS=="gugun"){
					echo "구군";
				}else if($config->STATS=="dong"){
					echo "동(읍)";
				}
				?>
			</td>
			<th width="25%">
				홈 지도 확대 오차 범위<i class="fa fa-question-circle" data-toggle="tooltip" title="홈 지도 확대 오차 범위"></i>
			</th>
			<td width="25%"><?php echo $config->HOME_MAP_ERROR?></td>
		</tr>
	</tbody>
</table>
<br/>


<h4>가격 설정 <small>- 가격 검색시 최대 범위값(최대 값이 되면 무제한이 됨)</small></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th width="25%">
				매매가 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="매매가 최대값"></i>
			</th>
			<td width="25%"><?php echo number_format($config->SELL_MAX)?> 억원</td>
			<th width="25%">
				전세가 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="전세가 최대값"></i>
			</th>
			<td><?php echo number_format($config->FULL_MAX)?> 만원</td>
		</tr>
		<tr>
			<th width="25%">
				월세보증금 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="월세보증금 최대값"></i>
			</th>
			<td width="25%"><?php echo number_format($config->MONTH_DEPOSIT_MAX)?> 만원</td>
			<th width="25%">
				월세임대료 최대값 <i class="fa fa-question-circle" data-toggle="tooltip" title="월세임대료 최대값"></i>
			</th>
			<td width="25%"><?php echo number_format($config->MONTH_MAX)?> 만원</td>
		</tr>
	</tbody>
</table>
<br/>

<h4>검색 설정 <small>- 매물 검색과 관련된 설정</small></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th width="25%">
				유형/지역 검색 위치
			</th>
			<td colspan="3"><?php echo ($config->SEARCH_POSITION) ? "상단" : "좌측"?></td>
		</tr>
		<tr>
			<th width="25%">
				매물목록랜덤방식 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물목록랜덤방식"></i>
			</th>
			<td width="25%"><?php echo ($config->RANDOM) ? "랜덤" : "일반"?></td>
			<th width="25%">
				검색창 순서 타입 <i class="fa fa-question-circle" data-toggle="tooltip" title="1:(지역,지하철,통합) 2:(통합,지역,지하철)"></i>
			</th>
			<td width="25%"><?php echo $config->SEARCH_ORDER?>타입</td>
		</tr>
		<tr>
			<th>
				지하철 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="지하철 사용여부"></i>
			</th>
			<td><?php echo ($config->SUBWAY) ? "사용함" : "사용안함"?></td>
			<th>
				매물정렬방식 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물정렬방식"></i>
			</th>
			<td>
				<?php 
				if($config->DEFAULT_SORT=="basic"){
					echo "추천 및 최신순";
				}
				else if($config->DEFAULT_SORT=="speed"){
					echo "급매 및 최신순";
				}
				else if($config->DEFAULT_SORT=="date_desc"){
					echo "최신 등록순";
				}
				else if($config->DEFAULT_SORT=="date_asc"){
					echo "최신 등록 역순";
				}
				else if($config->DEFAULT_SORT=="price_desc"){
					echo "높은 가격순";
				}
				else if($config->DEFAULT_SORT=="price_asc"){
					echo "낮은 가격순";
				}
				else if($config->DEFAULT_SORT=="area_desc"){
					echo "넓은 면적순";
				}
				else if($config->DEFAULT_SORT=="area_asc"){
					echo "좁은 면적순";
				}
				?>
			</td>
		</tr>
		<tr>
			<th>
				테마검색 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="테마검색 사용여부"></i>
			</th>
			<td><?php echo ($config->USE_THEME) ? "사용함" : "사용안함"?></td>
			<th>
				계약완료 상품 표시여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="계약완료 상품 표시여부"></i>
			</th>
			<td><?php echo ($config->COMPLETE_DISPLAY) ? "사용함" : "사용안함"?></td>
		</tr>
	</tbody>
</table>
<br/>

<h4>기타 설정</h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th width="25%">
				매물입력시 전체,부분 선택여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="매물입력시 전체,부분 선택여부"></i>
			</th>
			<td><?php echo ($config->PART_DEFAULT=="Y") ? "부분" : "전체"?></td>
		</tr>
		<tr>
			<th>
				매물등록시 기본지역(시도) <i class="fa fa-question-circle" data-toggle="tooltip" title="매물등록시 기본지역"></i>
			</th>
			<td><?php echo $config->INIT_SIDO?></td>
		</tr>
		<tr>
			<th>
				매물등록시 기본지역(구군) <i class="fa fa-question-circle" data-toggle="tooltip" title="매물등록시 기본지역"></i>
			</th>
			<td><?php echo $config->INIT_GUGUN?></td>
		</tr>
		<tr>
			<th>
				매물등록시 기본지역(읍동) <i class="fa fa-question-circle" data-toggle="tooltip" title="매물등록시 기본지역"></i>
			</th>
			<td><?php echo $config->INIT_DONG?></td>
		</tr>
		<tr>
			<th>
				의뢰하기 내놓기(매도) 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="의뢰하기 내놓기(매도) 사용여부"></i>
			</th>
			<td><?php echo ($config->ENQUIRE_SELL) ? "사용함" : "사용안함"?></td>
		</tr>
		<tr>
			<th>
				회원가입 후 이동 페이지 지정 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원가입 후 이동 페이지 지정"></i>
			</th>
			<td><?php echo $config->SIGNUP_REDIRECT?></td>
		</tr>
		<tr>
			<th>
				모바일 스플래시 윈도우 타입 <i class="fa fa-question-circle" data-toggle="tooltip" title="모바일 화면에서 스플래시 윈도우(1~11)"></i>
			</th>
			<td><?php echo $config->MOBILE_SPLASH?>타입</td>
		</tr>
		<tr>
			<th>
				블로그 포스팅시 타이틀에 매물유형 표시 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="블로그 포스팅시 타이틀에 매물유형 표시 여부"></i>
			</th>
			<td><?php echo ($config->BLOG_TITLE_HEAD) ? "사용함" : "사용안함"?></td>
		</tr>
		<tr>
			<th>
				뉴스에 담당자와 날짜 표시 여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="뉴스에 담당자와 날짜 표시 여부"></i>
			</th>
			<td><?php echo ($config->NEWS_DATE_VIEW) ? "사용함" : "사용안함"?></td>
		</tr>
		<tr>
			<th>
				의뢰하기 형태 설정 <i class="fa fa-question-circle" data-toggle="tooltip" title="의뢰하기 형태 설정"></i>
			</th>
			<td><?php echo ($config->ENQUIRE_TYPE) ? "목록형태" : "일반형태"?></td>
		</tr>
		<tr>
			<th>
				문의하기 형태 설정 <i class="fa fa-question-circle" data-toggle="tooltip" title="문의하기 형태 설정"></i>
			</th>
			<td><?php echo ($config->ASK_TYPE) ? "목록형태" : "일반형태"?></td>
		</tr>
		<tr>
			<th>
				회원가입 승인 사용여부 <i class="fa fa-question-circle" data-toggle="tooltip" title="회원가입 승인 사용여부"></i>
			</th>
			<td><?php echo ($config->MEMBER_APPROVE) ? "사용함" : "사용안함"?></td>
		</tr>
	</tbody>
</table>

<h4>앱 설치경로</h4>
<table class="table table-bordered">
		<tr>
			<th width="11%">
				구글앱경로 <i class="fa fa-question-circle" data-toggle="tooltip" title="구글앱경로"></i>
			</td>
			<td width="22%"><?php if($config->GPLAY!="") { echo $config->GPLAY; } else {echo "없음";}?> (예: com.dungzi.username )</td>
			<th width="11%">
				티스토어앱경로 <i class="fa fa-question-circle" data-toggle="tooltip" title="티스토어앱경로"></i>
			</td>
			<td width="22%"><?php if($config->TSTORE!="") { echo $config->TSTORE; } else {echo "없음";}?></td>
			<th width="11%">
				네이버앱경로 <i class="fa fa-question-circle" data-toggle="tooltip" title="네이버앱경로"></i>
			</td>
			<td width="22%"><?php if($config->NAVER!="") { echo $config->NAVER; } else {echo "없음";}?></td>
		</tr>
</table>