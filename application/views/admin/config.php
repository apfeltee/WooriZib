<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				설정<small>보기</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index">홈</a> <i class="fa fa-angle-right"></i> </li>
				<li>
					설정 보기
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="location.href='/adminhome/config_edit'">수정</button>
			</div>
		</div>
	</div>
</div>

<div class="portlet">
		 <div class="row">
			<div class="col-lg-12">
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">사업자명 <i class="fa fa-question-circle help" data-toggle="tooltip" title="사업자 등록증상에 표기된 이름을 입력해 주세요."></i></div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->name;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">사이트명 <i class="fa fa-question-circle help" data-toggle="tooltip" title="사이트명이 없을 경우 사업자명으로 사이트명이 사용됩니다."></i></div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->site_name;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">접속 IP <i class="fa fa-question-circle help" data-toggle="tooltip" title="저장된 IP에 대해서는 통계시 제외합니다. (접속IP:<?php echo $this->input->ip_address();?>)"></i></div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->ip;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">지도 최대 줌 <i class="fa fa-question-circle help" data-toggle="tooltip" title="숫자가 작으면 확대가 되는 것이니 너무 지도가 자세하다고 생각하시면 3으로 저장해 보세요."></i></div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->maxzoom;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">설명</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->description;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">키워드</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->keyword;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">대표자명 <i class="fa fa-question-circle help" data-toggle="tooltip" title="중개사무소 등록증에 기재된 중개업자의 성명(법인은 대표자 성명, 분사무소는 분사무소 책임자 성명)을 표시."></i></div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->ceo;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">대표이메일</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->email;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">사업자번호</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->biznum;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">부동산등록번호</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->renum;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">대표전화번호</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->tel;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">대표휴대번호</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->mobile;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">카카오톡오픈채팅주소 <a href="https://sites.google.com/site/dungzimanual/5-hwal-yong/kakao-opeunchaeting-yeongyeol" target="_blank"><i class="fa fa-question-circle help" data-toggle="tooltip" title="오픈채팅 매뉴얼"></i></a> </div>
					<div class="col-sm-10 col-xs-8 value">
						<?php if($config->kakaochat!=""){?><a href="http://open.kakao.com/o/<?php echo $config->kakaochat;?>" target="_blank">http://open.kakao.com/o/<?php echo $config->kakaochat;?></a><?php } else {?>없음<?php }?>
					</div>
				</div>				
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">팩스번호</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->fax;?>
					</div>
				</div>				
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">주소(지번)</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->address;?> | 위도: <?php echo $config->lat;?>, 경도: <?php echo $config->lng;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">주소(도로)</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->new_address;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">카피라이트연도</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->year;?>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">설명</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php echo $config->content;?>
				</div>
			</div>
		</div>
	</div> 
</div> 

<h4>사이트 책임 관리자 지정<a href="#" target="_blank"><i class="fa fa-question-circle"></i></a></h4>
<div class="portlet">
	<div class="row">
		<div class="col-lg-12">
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name_plain">담당자</div>
				<div class="col-sm-10 col-xs-8 value">
					<?php echo $site_admin->name;?> (<?php echo $site_admin->email;?>, <?php echo $site_admin->phone;?>)
				</div>
			</div>			
		</div>
	</div>
</div> 

<h4>네이버 검색 연동 설정<a href="https://sites.google.com/site/dungzimanual/5-hwal-yong/neibeogeomsaeg-yeondong" target="_blank"><i class="fa fa-question-circle"></i></a></h4>
<div class="portlet">
	<div class="row">
		<div class="col-lg-12">

			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name_plain">네이버 웹마스터도구 키</div>
				<div class="col-sm-10 col-xs-8 value">
					<?php echo $config->naverwebmasterkey;?>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name_plain">연동키(token)</div>
				<div class="col-sm-10 col-xs-8 value">
					<?php echo $config->naverwebmastertoken;?>
				</div>
			</div>				
		</div>
	</div>
</div> 

<h4>네이버 카페 연동 설정<a href="https://sites.google.com/site/dungzimanual/5-kapeyeondong" target="_blank"><i class="fa fa-question-circle"></i></a></h4>
<div class="portlet">
	 <div class="row">
		<div class="col-lg-12">

			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name_plain">네이버컨슈머키</div>
				<div class="col-sm-10 col-xs-8 value">
					<?php echo $config->navercskey;?>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name_plain">네이버컨슈머시크릿</div>
				<div class="col-sm-10 col-xs-8 value">
					<?php echo $config->navercssecret;?>
				</div>
			</div>

			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name_plain">네이버 Client ID</div>
				<div class="col-sm-10 col-xs-8 value">
					<?php echo $config->naverclientkey;?>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name_plain">네이버 Client Secret</div>
				<div class="col-sm-10 col-xs-8 value">
					<?php echo $config->naverclientsecret;?>
				</div>
			</div>								
		</div>
	</div>
</div> 


<h4>구글 분석 연결<a href="https://sites.google.com/site/dungzimanual/9-logeubunseog/gugeul-eonaellitigseu" target="_blank"><i class="fa fa-question-circle"></i></a></h4>
<div class="portlet">
	<div class="row">
		<div class="col-lg-12">

			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name_plain">구글 분석 추적코드</div>
				<div class="col-sm-10 col-xs-8 value">
					<?php echo $config->glogkey;?>
				</div>
			</div>
		</div>
	</div>
</div> 

</div> 