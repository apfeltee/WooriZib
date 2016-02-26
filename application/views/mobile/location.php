  <section class="w-section mobile-wrapper">
    <div class="page-content" id="main-stack">
      <div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
        <div class="w-container">
		  <?php echo $menu?>
          <div class="wrapper-mask" data-ix="menu-mask"></div>
          <div class="navbar-title">위치정보 약관</div>
          <div class="w-nav-button navbar-button left" id="menu-button" data-ix="hide-navbar-icons">
            <div class="navbar-button-icon home-icon">
              <div class="bar-home-icon"></div>
              <div class="bar-home-icon"></div>
              <div class="bar-home-icon"></div>
            </div>
          </div>
		  <a href="#" class="w-inline-block navbar-button right" onclick="onBackKeyDown();">
			<div class="navbar-button-icon icon ion-ios-close-empty"></div>
		  </a>
        </div>
      </div>
      <div class="body">
        <div class="news-container item-new">
          <div>
            <div class="grey-header">
              <h2 class="grey-heading-title">위치정보 약관</h2>
            </div>
            <div class="text-new no-borders">
              <div>
                <div class="separator-fields"></div>
                <h2 class="title-new">1. 목적</h2>
                <div class="separator-fields"></div>
                <p class="description-new">
				이 약관은 “<?php echo $config->name?>” 위치기반서비스(이하 "서비스"라 한다)를 이용하는 “고객” 사이의 “서비스” 이용에 관한 제반 사항을 정함을 목적으로 합니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">2. 용어의 정의</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				1) 이 약관에서 사용하는 용어의 의미는 다음 각 호와 같습니다. 아래 각 호에서 정의되지 않은 이 약관상의 용어의 의미는 “<?php echo $config->name?>” 이용약관 및 일반적인 거래 관행에 의합니다.</br>
				   ① “<?php echo $config->name?>”이란 “회사”가 제공하는 위치기반서비스입니다.</br>
				   ② “서비스”라 함은 이용자에게 스마트 폰 등의 위치정보를 기준으로 부동산 매물 등록 정보를 제공하는 것을 말합니다.</br>
				   ③ “고객”이라 함은 “<?php echo $config->name?>”을 이용하는 이용자를 말합니다.</br>
				   ④ “회원”이라 함은 회사에 개인정보를 제공하고 회원등록을 한 자로서, “<?php echo $config->name?>”의 정보를 지속적으로 제공받으며, “회사”가 제공하는 “<?php echo $config->name?>”의 “서비스”를 계속적으로 이용할 수 있는 자를 말합니다. “회사”는 “서비스”의 원활한 제공을 위해 “회원”의 등급을 “회사” 내부의 규정에 따라 나눌 수 있습니다.</br>
				   ⑤ “비회원”이라 함은 “회원”으로 가입하지 않고 “회사”가 제공하는 “서비스”를 이용하는 자를 말합니다.</br>
				2) 이 약관은 「위치정보의 보호 및 이용 등에 관한 법률」 및 관계 법령 등에서 정하는 바에 따릅니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">3. 계약의 체결 및 해지</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				1) “고객”은 “회사”의 “서비스”를 이용하고자 하는 경우, 약관의 고지 내용에 따라 개인위치정보 서비스에 가입하게 됩니다. “회원”의 경우 회원가입 시 동의절차를 밟으며, “비회원”인 경우 “서비스”를 이용하는 동안 이 약관에 동의한 것으로 간주합니다.</br>
				2) “고객”은 계약을 해지하고자 할 때에는 “회사”의 개인정보보호 담당자에게 이메일을 통해 해지신청을 하여야 합니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">4. 서비스의 내용</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				1) “회사”는 “고객”이 등록한 매물의 위치정보 만을 “고객”에게 제공하며, 해당 위치정보를 다른 정보와 결합하여 개인위치정보로 이용하지 않습니다.</br>
				2) 제공되는 “고객”의 매물 위치정보는 해당 스마트폰 등에서 제공합니다.</br>
				3) “회사”는 위치정보사업자인 이동통신사로부터 위치정보를 전달받아 “<?php echo $config->name?>”의 모바일 단말기 전용 어플리케이션(이하 "어플리케이션")을 통해 아래와 같은 위치기반서비스를 제공합니다.</br>
				   ① 접속 위치 제공 “서비스”: 위치 정보 사용을 승인한 “고객”들의 “서비스” 최종 접속 위치를 기반으로 “서비스” 내의 정보를 지도 위에 혹은 리스트를 통해 제공합니다.</br>
				   ② 위치 정보: 모바일 단말기 등의 WPS(Wifi Positioning System), GPS 기반으로 추출된 좌표를 이용하여 “고객”이 생성하는 지점을 말합니다.</br>
				   ③ 최종 접속 위치를 활용한 검색 결과 제공 “서비스”: 정보 검색 요청 시 개인위치정보의 현 위치를 이용한 “서비스” 내의 기능에 따라 제공되는 정보에 대하여 검색 결과를 제시합니다.</br>
				   ④ ”고객”의 위치 정보의 갱신은 “<?php echo $config->name?>” 실행 시 또는 실행 후, 위치 관련 메뉴 이용 시 이루어지며, “고객”이 갱신한 사용자의 위치정보를 기준으로 최종 위치를 반영합니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">5. 이용시간</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				“<?php echo $config->name?>”의 이용은 24시간 가능하며, 다만, 시스템 장애, 프로그램 오류 보수, 외부요인 등 불가피한 경우에는 서비스 이용이 불가능 할 수 있습니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">6. 위치정보수집 방법</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				“회사”는 다음과 같은 방식으로 개인위치정보를 수집합니다.</br>
				  ① 모바일 단말기 등을 이용한 기지국 기반(Cell ID방식)의 실시간 위치정보 수집</br>
				  ② GPS칩이 내장된 전용 단말기 등을 통해 수집되는 GPS 정보를 통한 위치정보 수집</br>
				  ③ Wi-Fi칩이 내장된 전용 단말기 등을 통해 수집되는 Wi-Fi 정보를 통한 위치정보 수집
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">7. 접속자의 위치정보 이용</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				“회사”는 “회원”이 약관 등에 동의하는 경우 또는 “비회원”이 위치관련 메뉴 이용 시에 한해 단말기를 통해 수집된 위치정보를 활용하여 정보 및 “회원”의 게시물을 제공할 수 있습니다.</br>
				   ① 약관 등에 동의를 한 “회원” 또는 “비회원”이 위치관련 메뉴 사용 시 “서비스” 이용을 위해 본인의 위치를 자의적으로 노출하였다고 간주하며 “회사”는 “고객”의 실시간 위치정보를 바탕으로 컨텐츠를 제공합니다.</br>
				   ② 장소정보 및 컨텐츠 입력 등 “서비스” 이용 시 “회원”이 생성한 컨텐츠에 대해 “회사”는 “회원”의 위치에 대한 정보를 저장 및 보존 합니다. “회사”는 장소정보 또는 “회원”이 등록한 게시물을 “고객”의 현재위치를 기반으로 추천하기 위해 위치정보를 이용합니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">8. 개인위치정보의 이용 또는 제공에 관한 동의</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				1) “회사”는 개인위치정보의 동의 없이 당해 개인위치정보를 제3자에게 제공하지 아니합니다.</br>
				2) “회사”는 “고객”간의 거래와 관련 없는 목적을 위해 개인위치정보 이용, 제공사실 확인자료를 기록하거나 보존하지 아니합니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">9. 개인위치정보주체의 권리</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				1) 개인위치정보주체는 개인위치정보의 이용•제공에 대한 동의의 전부 또는 일부를 철회할 수 있습니다.</br>
				2) 개인위치정보주체는 “회사”에 대하여 아래 자료의 열람 또는 고지를 요구할 수 있고, 당해 자료에 오류가 있는 경우에는 그 정정을 요구할 수 있습니다. 이 경우 “회사”는 정당한 이유 없이 요구를 거절하지 아니합니다.</br>
				  ① 개인위치정보주체에 대한 위치정보 이용, 제공사실 확인자료</br>
				  ② 개인위치정보주체의 개인위치정보가 위치정보의 보호 및 이용 등에 관한 법률 또는 다른 법령의 규정에 의하여 제3자에게 제공된 이유 및 내용</br>
				3) 개인위치정보주체는 제1항 내지 제2항의 권리행사를 위하여 이 약관 제15조의 연락처를 이용하여 “회사”에 요구할 수 있습니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">10. "서비스"의 변경 및 중지</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				1) “회사”는 위치정보사업자의 정책변경 등과 같이 “회사”의 제반 사정 또는 법률상의 장애 등으로 “서비스”를 유지할 수 없는 경우, “서비스”의 전부 또는 일부를 제한, 변경하거나 중지할 수 있습니다.</br>
				2) 제1항에 의한 “서비스” 중단의 경우에는 “회사”는 사전에 인터넷 및 “서비스” 화면 등에 공지하거나 개인위치정보주체에게 통지합니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">11. 위치정보관리책임자의 지정</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				1) “회사”는 위치정보를 관리, 보호하고, 거래 시 “고객”의 개인위치정보로 인한 불만을 원활히 처리할 수 있는 위치정보관리책임자를 지정해 운영합니다.</br>
				2) 위치정보관리책임자는 위치기반서비스를 제공하는 부서의 부서장으로서 구체적인 사항은 본 약관의 부칙에 따릅니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">12. 손해배상 및 면책></h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				1) 개인위치정보주체는 “회사”의 다음 각 호에 해당하는 행위로 손해를 입은 경우에 “회사”에 대해 손해배상을 청구할 수 있습니다. 이 경우 개인위치정보주체는 “회사”의 고의 또는 과실이 있음을 직접 입증하여야 합니다.</br>
				  ① 법령에서 허용하는 경우를 제외하고 이용자 또는 개인위치정보주체의 동의 없이 위치정보를 수집, 이용하는 행위</br>
				  ② 개인위치정보의 누출, 변조, 훼손 등의 행위</br>
				2) “회사”는 천재지변 등 불가항력적인 사유나 이용자의 고의 또는 과실로 인하여 발생한 때에는 손해를 배상하지 아니합니다.</br>
				3) “회사”는 이용자가 망사업자의 통신환경에 따라 발생할 수 있는 오차 있는 위치정보를 이용함으로써 이용자 및 제3자가 입은 손해에 대하여는 배상하지 아니합니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">13. 약관의 변경</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				1) “회사”가 약관을 변경하고자 할 때는 사전에 공지사항을 통해 변경내용을 게시합니다.</br>
				2) “회원”은 제1항의 규정에 따른 약관의 변경내용이 게시되거나 통지된 후부터 변경되는 약관의 시행일 전 영업일까지 계약을 해지할 수 있습니다. 단 전단의 기간 안에 “회원”의 이의가 “회사”에 도달하지 않으면 “회원”이 이를 승인한 것으로 봅니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">14. 분쟁의 조정</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				“회사”는 위치정보와 관련된 분쟁의 당사자간 협의가 이루어지지 아니하거나 협의를 할 수 없는 경우에는 전기통신기본법의 규정에 따라 방송통신위원회에 재정을 신청하거나 정보통신망이용촉진및정보보호등에관한 법률의 규정에 의한 개인정보분쟁조정위원회에 조정을 신청할 수 있습니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new">15. 사업자 정보 및 위치정보관리책임자 지정</h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				1) “회사”의 상호, 주소, 전화번호 그 밖의 연락처는 다음과 같습니다.</br>
				   상호 : <?php echo $config->name?></br>
				   <i class="fa fa-map-marker"></i> : <?php echo toeng($config->new_address)?></br>
				   <i class="fa fa-map-marker"></i> : <?php echo toeng($config->address)?></br>
				   전화번호 : <?php echo $config->tel?></br>
				   FAX : <?php echo $config->fax?></br>
				 2) 위치정보관리책임자는 다음과 같이 지정합니다.
				</p>
                <div class="separator-button"></div>
              </div>

              <div>
                <h2 class="title-new"><small>- 개인정보 취급방침 및 위치정보관리책임자 -</small></h2>
                <div class="separator-fields"></div>
				<p class="description-new">
				성명 : <?php echo $config->ceo?></br>
				전자우편 : <?php echo $config->email?>
				</p>
                <div class="separator-button"></div>
              </div>

            </div>
		  </div>
        </div>
      </div>
    </div>
    <div class="page-content loading-mask" id="new-stack">
      <div class="loading-icon">
        <div class="navbar-button-icon icon ion-load-d"></div>
      </div>
    </div>
    <div class="shadow-layer"></div>
  </section>