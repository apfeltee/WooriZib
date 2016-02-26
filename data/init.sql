CREATE TABLE `address` (
	`id` INT(11) NOT NULL,
	`parent_id` INT(11) NOT NULL,
	`sido` VARCHAR(4) NOT NULL DEFAULT '',
	`gugun` VARCHAR(20) NOT NULL DEFAULT '',
	`dong` VARCHAR(50) NOT NULL DEFAULT '',
	`lat` double NOT NULL DEFAULT '0',
	`lng` double NOT NULL DEFAULT '0',
	`zoom` INT(11) NOT NULL DEFAULT '16',
	PRIMARY KEY (`id`),
	KEY `sido` (`sido`),
	KEY `gugun` (`gugun`),
	KEY `dong` (`dong`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `members` (
	`id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NOT NULL,
	`bio` TEXT NULL,  
	`biz_name` VARCHAR(100) NULL,
	`biz_auth` VARCHAR(10) NOT NULL DEFAULT '',
	`biz_num` VARCHAR(100) NOT NULL DEFAULT '',
	`biz_ceo` VARCHAR(100) NOT NULL DEFAULT '',
	`re_num` VARCHAR(100) NOT NULL DEFAULT '',
	`address` VARCHAR(200) NULL,
	`address_detail` VARCHAR(200) NULL,
	`email` VARCHAR(30) NOT NULL,
	`kakao` VARCHAR(30) NULL,
	`profile` VARCHAR(100) NULL DEFAULT '',
	`type` ENUM('admin','biz','general') NOT NULL DEFAULT 'general',
	`pw` VARCHAR(100) NOT NULL,
	`phone` VARCHAR(100) NOT NULL,
	`tel` VARCHAR(100) NULL COMMENT '유선번호가 있으면 우선 표시됨',
	`sign` TEXT NULL,
	`auth_id` INT(11) NOT NULL DEFAULT '0', 
	`valid` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	`secession` ENUM('Y','N') NOT NULL DEFAULT 'N',
	`secession_reason` TEXT NULL,
	`secession_date` DATETIME DEFAULT NULL,
	`moddate` DATETIME DEFAULT NULL,
	`end_date` DATETIME DEFAULT NULL,
	`date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`sorting` INT(11) NULL DEFAULT '0',
	`watermark` VARCHAR(50) NULL,
	`watermark_position_vertical` ENUM('middle','top','bottom') NOT NULL DEFAULT 'middle',
	`watermark_position_horizontal` ENUM('left','center','right') NOT NULL DEFAULT 'center',
	`permit_ip` TEXT NULL,
	`permit_area` TEXT NULL,
	`uuid` VARCHAR(100) NULL DEFAULT '',
	`color` VARCHAR(10) NULL DEFAULT '',
	`expire_date` DATE NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='관리회원';

CREATE TABLE `news_category` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL,
	`abstract` TEXT NULL,
	`opened` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	`sorting` INT(11) NOT NULL DEFAULT '0',
	`valid` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `news` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`date` DATETIME DEFAULT NULL,
	`title` VARCHAR(200) DEFAULT NULL,
	`thumb_name` VARCHAR(200) DEFAULT NULL,
	`category` INT(11) DEFAULT '1',
	`content` TEXT ,
	`is_activated` TINYINT(1) DEFAULT '0',
	`is_blog` INT(11) DEFAULT '0',
	`is_cafe` INT(11) DEFAULT '0',
	`product_print` ENUM('Y','N') NOT NULL DEFAULT 'N',
	`member_id` INT(11) DEFAULT NULL COMMENT '담당자',
	`viewcnt` INT(11) DEFAULT '0' COMMENT '조회수',
	`result` VARCHAR(50) DEFAULT '0',
	`tag` VARCHAR(200) DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `date` (`date`),
	KEY `category` (`category`),
	KEY `is_activated` (`is_activated`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='뉴스';

CREATE TABLE `news_comment` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`step_id` INT(11) NOT NULL DEFAULT '0',
	`news_id` INT(11) NOT NULL,
	`member_id` INT(11) NOT NULL DEFAULT '0',
	`type` ENUM('front','admin') NOT NULL DEFAULT 'front',
	`name` VARCHAR(50) NOT NULL,
	`pw` VARCHAR(50) NOT NULL,
	`content` TEXT,
	`delete` ENUM('Y','N') NOT NULL DEFAULT 'N',
	`date` DATETIME NOT NULL,
	PRIMARY KEY (`id`,`step_id`,`news_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `blogapi` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`member_type` ENUM('member','admin') NOT NULL DEFAULT 'member',
	`member_id` INT(11) NULL DEFAULT NULL,
	`type` ENUM('naver','tistory') NOT NULL DEFAULT 'naver',
	`user_id` VARCHAR(50) NOT NULL COMMENT '로그인아이디',
	`address` VARCHAR(50) NOT NULL,
	`blog_id` VARCHAR(50) NOT NULL COMMENT '블로그아이디',
	`blog_key` VARCHAR(50) NOT NULL COMMENT '블로그 암호 키',
	`cnt` INT(10) NOT NULL DEFAULT '0' COMMENT '포스팅건수',
	`valid` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `bloghistory` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`blog_id` INT(11) NOT NULL,
	`type` ENUM('product','blog','installation') NOT NULL DEFAULT 'product',
	`data_id` INT(11) NOT NULL,
	`title` VARCHAR(200) NOT NULL,
	`return` VARCHAR(200) NOT NULL COMMENT '응답내용',
	`viewcnt` INT(10) NOT NULL DEFAULT '0' COMMENT '조회수',
	`date` DATETIME NOT NULL COMMENT '등록일시',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `bloghistory_daum` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`type` ENUM('product','news','installation') NOT NULL DEFAULT 'product',
	`blog_name` VARCHAR(20) NOT NULL,
	`blog_category` INT(11) NOT NULL,
	`data_id` INT(11) NOT NULL,
	`title` VARCHAR(200) NOT NULL,
	`viewcnt` INT(10) NOT NULL DEFAULT '0' COMMENT '조회수',
	`date` DATETIME NOT NULL COMMENT '등록일시',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `cafehistory` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`type` ENUM('product','news','installation') NOT NULL DEFAULT 'product',
	`cafe_id` INT(11) NOT NULL,
	`menu_id` INT(11) NOT NULL,
	`data_id` INT(11) NOT NULL,
	`title` VARCHAR(200) NOT NULL,
	`viewcnt` INT(10) NOT NULL DEFAULT '0' COMMENT '조회수',
	`date` DATETIME NOT NULL COMMENT '등록일시',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;

CREATE TABLE `category` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL,
	`main` INT(11) NOT NULL DEFAULT '1',
	`opened` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	`sorting` INT(11) NOT NULL DEFAULT '0',
	`template` TEXT NOT NULL,
	`valid` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	`meta` TEXT ,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

CREATE TABLE `config` (
	`id` INT(11) NOT NULL DEFAULT '1',
	`name` VARCHAR(100) NOT NULL COMMENT '상호명',
	`site_name` VARCHAR(100) NOT NULL,    
	`ip` VARCHAR(100) NOT NULL COMMENT '부동산접속아이피',
	`description` TEXT NOT NULL,
	`keyword` TEXT NOT NULL,
	`logo` VARCHAR(100) NOT NULL COMMENT '로고이미지파일명',
	`footer_logo` VARCHAR(100) NOT NULL,
	`no` VARCHAR(100) NOT NULL,
	`ceo` VARCHAR(100) NOT NULL COMMENT '대표자명',
	`email` VARCHAR(100) NOT NULL COMMENT '대표이메일',
	`biznum` VARCHAR(100) NOT NULL COMMENT '사업자등록번호',
	`renum` VARCHAR(100) NOT NULL COMMENT '부동산등록번호',
	`tel` VARCHAR(100) NOT NULL COMMENT '대표번호',
	`mobile` VARCHAR(100) NOT NULL,
	`kakaochat` VARCHAR(100) NOT NULL DEFAULT '' ,
	`fax` VARCHAR(100) NOT NULL COMMENT '팩스번호',
	`address` VARCHAR(100) NOT NULL COMMENT '주소',
	`new_address` VARCHAR(100) NOT NULL COMMENT '도로명주소',
	`lat` double NOT NULL COMMENT '위도',
	`lng` double NOT NULL COMMENT '경도',
	`year` VARCHAR(10) NOT NULL COMMENT '카피라이트연도',
	`content` TEXT NOT NULL COMMENT '설명',
	`sms_id` VARCHAR(50) NOT NULL COMMENT 'Cafe24의 sms 호스팅 아이디',
	`sms_key` VARCHAR(50) NOT NULL COMMENT 'Cafe24의 sms 호스팅 암호 키',
	`glogkey` VARCHAR(50) NOT NULL,
	`naverwebmasterkey` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '네이버웹마스터도구키',
	`naverwebmastertoken` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '네이버신디케이션토큰',
	`navercskey` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '네이버컨슈머키',
	`navercssecret` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '네이버컨슈머시크릿',
	`naverclientkey` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '네이버 Client ID',
	`naverclientsecret` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '네이버 Client Secret',
	`daumclientkey` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '다음 Client ID',
	`daumclientsecret` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '다음 Client Secret',
	`news_flag` ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT '뉴스메뉴사용여부',
	`news_ktitle` VARCHAR(100) NOT NULL COMMENT '뉴스한글타이틀',
	`news_etitle` VARCHAR(100) NOT NULL COMMENT '뉴스영문타이틀',
	`news_reply` ENUM('Y','N','M') NOT NULL DEFAULT 'Y' COMMENT 'Y는 누구나 쓰고 M은 회원만 쓴다',
	`maxzoom` INT(11) NOT NULL DEFAULT '18' COMMENT '최대 줌',
	`watermark` VARCHAR(50) NULL,
	`watermark_position_vertical` ENUM('middle','top','bottom') NOT NULL DEFAULT 'middle',
	`watermark_position_horizontal` ENUM('left','center','right') NOT NULL DEFAULT 'center',
	`site_admin` INT(11) NOT NULL DEFAULT 2 COMMENT '책임관리자아이디',
	`sms_cnt` INT(11) NOT NULL DEFAULT 500 COMMENT 'SMS잔여건수',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='기본설정테이블';

CREATE TABLE `config_form` (
	`id` INT(11) NOT NULL DEFAULT '1',
	`name` VARCHAR(100) NOT NULL COMMENT '메인 유형 아이디',
	`default_type` VARCHAR(100) NOT NULL COMMENT '매물 종류 기본값',
	`default_part` ENUM('Y','N') NOT NULL DEFAULT 'Y' COMMENT '건물 부분여부 기본 값',
	`danzi` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '단지 사용여부',
	`lease_price` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '융자금 사용여부',
	`premium_price` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '권리금 사용여부',
	`mgr_price` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '관리비 사용여부',
	`mgr_price_full_rent` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '관리비(전세) 사용여부',
	`monthly_rent_deposit_min` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '최소 보증금 사용여부',
	`loan` VARCHAR(50) NULL DEFAULT '없음,30%이하,50%이하,70%이하,기타' COMMENT '기대출금',
	`dongho` TINYINT(1) NULL DEFAULT '0' COMMENT '동,호수',
	`real_area` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '실면적 사용여부',
	`law_area` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '계약면적 사용여부',
	`land_area` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '대지면적 사용여부(전체)',
	`road_area` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '도로지분면적 사용여부(전체)',
	`enter_year` VARCHAR(200) NOT NULL DEFAULT '1' COMMENT '입주일 사용여부',
	`build_year` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '준공일 사용여부',
	`bedcnt` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '침실 사용여부',
	`bathcnt` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '욕실 사용여부',
	`current_floor` VARCHAR(200) NOT NULL DEFAULT '저층,중층,고층' COMMENT '현재 층',
	`total_floor` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '전체층',
	`store_category` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '현재업종',
	`store_name` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '현재 상호',
	`profit` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '수익구조',
	`gongsil_see` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '공실 방볼때',
	`gongsil_status` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '공실 현재상태',
	`gongsil_contact` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '공실 연락처',
	`extension` VARCHAR(200) NULL DEFAULT NULL COMMENT '확장 여부(선택항목입력)',
	`heating` VARCHAR(200) NULL DEFAULT NULL COMMENT '난방(선택항목입력)',
	`park` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '주차 사용여부',
	`road_condition` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '도로인접조건',
	`ground` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '용도지역,지목입력항목',
	`ground_aim` VARCHAR(250) NOT NULL DEFAULT '' COMMENT '지목',
	`ground_use` VARCHAR(250) NOT NULL DEFAULT '' COMMENT '지역',
	`factory` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '공장정보(전기,호이스트,용도)',
	`factory_hoist` VARCHAR(250) NOT NULL DEFAULT '' COMMENT '호이스트',
	`factory_use` VARCHAR(250) NOT NULL DEFAULT '',
	`vr` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'VR주소',
	`video_url` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '동영상주소',
	PRIMARY KEY (`id`)
)
COMMENT='필드정의'
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `config_high` (
	`id` INT(11) NOT NULL DEFAULT '1',
	`FONT_BASIC` VARCHAR(50) NOT NULL DEFAULT 'Nanum Gothic' COMMENT '기본폰트',
	`INSTALLATION_FLAG` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '분양사용여부',
	`INSTALLATION_MENU_FLAG` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '분양 메뉴 사용여부',
	`GONGSIL_FLAG` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '공실사용여부',
	`GONGSIL_STATUS` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '공실 방 상태',
	`GONGSIL_SEE` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '공실 방볼 때',
	`AUTO_LOGOUT` INT(10) NOT NULL DEFAULT '0' COMMENT '자동로그아웃 0:사용안함,가타:분',
	`SHOW_ADDRESS` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '상세 주소 보여주기',
	`PRODUCT_THUMBNAIL_POS` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '매물정보에서 썸네일 위치(0은 하단, 1은 우측)',
	`PRODUCT_REALAREA` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '매물 실면적 사용여부',
	`PRODUCT_LAWAREA` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '매물 계약면적 사용여부',
	`SEARCH_POSITION` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '주요검색영역위치(0:좌측,1:상단)',
	`RANDOM` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '매물목록랜덤방식',
	`LISTING` TINYINT(1) NOT NULL DEFAULT '3' COMMENT '매물목록타입',
	`PAY` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '요금미납여부',
	`BANKACCOUNT` VARCHAR(100) NULL DEFAULT '' COMMENT '은행계좌번호',
	`DAUM_MAP_KEY` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '다음지도키',
	`MAP_STYLE` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '지도스타일',
	`MAP_TITLE` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '제목표시여부',
	`MAP_ALERT` VARCHAR(200) NOT NULL DEFAULT '매물 보호를 위하여 정확한 위치 대신 범위로 표시하니 정확한 위치는 문의해 주세요.' COMMENT '매물지도 위 알림 문구',
	`MAP_INIT_LEVEL` TINYINT(1) NOT NULL DEFAULT '8' COMMENT '최초지도확대레벨',
	`MAP_MAX_LEVEL` TINYINT(1) NOT NULL DEFAULT '3' COMMENT '지도최대확대레벨',
	`MAP_USE` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '지도사용여부(0이면 사용안함)',
	`MAP_CLUSTER` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '맵 클러스터 사용여부',
	`MAP_ICON_ONLY` VARCHAR(1) NOT NULL DEFAULT '0' COMMENT '맵 좌표 아이콘으로만 표시',	
	`STATS` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '최초지도확대영역',
	`MAP_BIG` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '0:목록형,1:지도형(웹)',
	`M_MAP_BIG` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0:목록형,1:지도형(모바일)',
	`RADIUS` INT(3) NOT NULL DEFAULT '50' COMMENT '매물상세 지도 원 반경',
	`SELL_MAX` INT(11) NOT NULL DEFAULT '10' COMMENT '매매가 최대값',
	`FULL_MAX` INT(11) NOT NULL DEFAULT '10000' COMMENT '전세가 최대값',
	`MONTH_DEPOSIT_MAX` INT(11) NOT NULL DEFAULT '5000' COMMENT '월세보증금 최대값',
	`MONTH_MAX` INT(11) NOT NULL DEFAULT '100' COMMENT '월세임대료 최대값',
	`SUBWAY` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '지하철 사용여부',
	`OPTION_FLAG` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '매물옵션 비선택 표시여부',
	`QUALITY` VARCHAR(20) NOT NULL DEFAULT '90%' COMMENT '사진퀄리티 비율',
	`DAUM` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '다음인근정보키',
	`DATE_DISPLAY` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '등록일/수정일 표시여부',
	`HOME_MAP_ERROR` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '홈지도에확대레벨',
	`DEFAULT_SORT` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '매물정렬방식',
	`HOSTING` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '사용호스팅',
	`GPLAY` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '구글앱경로',
	`TSTORE` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '티스토어앱경로',
	`NAVER` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '네이버앱경로',
	`DUNGZI` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '카피라이트 둥지표시여부',
	`MEMBER_JOIN` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '회원로그인 사용여부',
	`ADMIN_JOIN` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '관리자 간편 회원가입 사용여부',
	`IS_DEMO` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '데모사이트여부',
	`CALL_HIDDEN` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '연락처정보보기 사용여부',
	`USE_FACTORY` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '공장정보 사용여부',
	`TYPE_DEFAULT` VARCHAR(50) NOT NULL DEFAULT 'sell' COMMENT '매물 거래 유형 기본 선택 값',
	`USER_PRODUCT` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '유저페이지 매물관리 사용여부',
	`USE_PAY` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '결제 시스템 사용여부',
	`USE_APPROVE` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '유저매물 등록시 승인여부',
	`USE_THEME` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '테마검색 사용여부',
	`USE_HEATING` VARCHAR(200) NOT NULL DEFAULT '지역난방,개별가스난방,중앙난방,도시가스,LPG,심야전기' COMMENT '(분양)난방종류',
	`COMPLETE_DISPLAY` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '계약완료 상품 표시여부',
	`CLIENTID` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '결제용 상점아이디',
	`PG_ACCOUNT` ENUM('allthegate','inicis') NOT NULL DEFAULT 'inicis' COMMENT '계좌이체PG사',
	`INIT_SIDO` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '매물등록시 기본지역',
	`INIT_GUGUN` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '매물등록시 기본지역',
	`INIT_DONG` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '매물등록시 기본지역',
	`UNIT_FLAG` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '평당가 표시여부',
	`UNIT` VARCHAR(20) NOT NULL DEFAULT 'new' COMMENT '평당가격표시구분',
	`ALL_RENT_PRICE` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '건물총보증금(월세)',
	`ENQUIRE_SELL` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '의뢰하기 내놓기(매도) 사용여부',
	`SIGNUP_REDIRECT` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '회원가입 후 이동 페이지 지정',
	`SEARCH_ORDER` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '검색창 순서 지정',
	`MOBILE_SPLASH` TINYINT(2) NOT NULL DEFAULT '1' COMMENT '모바일 스플래시 윈도우',
	`BLOG_TITLE_HEAD` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '블로그 포스팅 제목에 매물유형 표시 여부',
	`NEWS_DATE_VIEW` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '뉴스에 담당자와 날짜 표시 여부',
	`ENQUIRE_TYPE` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '의뢰하기 형태 설정',
	`ASK_TYPE` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '문의하기 형태 설정',
	`MEMBER_TYPE` VARCHAR(20) NOT NULL DEFAULT 'general' COMMENT '회원 형태 설정',
	`MEMBER_PHONE_CONFIRM` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '회원가입 휴대폰인증 사용여부',
	`MEMBER_APPROVE` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '회원가입 승인 사용여부',
	`LIST_ENCLOSED` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '매물목록 개방형 폐쇄형 여부',
	`DESIGN_LOGO_RIGHT` TEXT NULL COMMENT '로고우측멘트항목',
	`DESIGN_RECENT_RIGHT` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '최신매물우측에 정보창 사용여부',
	`MEMBER_INFO_RIGHT` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '매물상세 우측 담당자정보 표시여부',
	`BUILDING_DISPLAY` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '매물상세에 건물정보 표시여부',
	`BUILDING_ENQUIRE` INT(1) NOT NULL DEFAULT '0' COMMENT '건물의뢰 사용여부',
	`USE_CALL_REMAIN` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '연락받기 사용여부',
	`CP_CODE` VARCHAR(20) NOT NULL DEFAULT '' COMMENT 'CP코드',
	`CP_PASSWORD` VARCHAR(20) NOT NULL DEFAULT '' COMMENT 'CP패스워드',
	`IPIN_CODE` VARCHAR(20) NOT NULL DEFAULT '' COMMENT 'IPIN코드',
	`IPIN_PASSWORD` VARCHAR(20) NOT NULL DEFAULT '' COMMENT 'IPIN패스워드',
	`AREA_SORTING` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '목록에서 보여주는 면적 우선순위',
	`BUG_LAYER` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '오류문의 레이어 사용여부',
	`REALPRICE` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '실거래가 표시여부',
	`REGION_USE` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '지역사전설정 사용여부',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='고급설정테이블';

CREATE TABLE `enquire` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) DEFAULT NULL,
	`feature` TEXT NULL COMMENT '고객의 신상에 대하여 저장',
	`member_id` INT(10) DEFAULT NULL,
	`status` ENUM('N','G','H','F','D','R','X','Y','Z') DEFAULT 'N',
	`gubun` ENUM('buy','sell') DEFAULT 'buy',
	`phone` VARCHAR(100) DEFAULT NULL,
	`phone_etc1` VARCHAR(100) DEFAULT NULL,
	`phone_etc2` VARCHAR(100) DEFAULT NULL,
	`location` VARCHAR(100) DEFAULT NULL,
	`visitdate` VARCHAR(200) NULL DEFAULT NULL,
	`movedate` VARCHAR(200) NULL DEFAULT NULL,  
	`price` VARCHAR(100) DEFAULT NULL,
	`area` VARCHAR(100) DEFAULT NULL,
	`type` VARCHAR(100) DEFAULT NULL,
	`category` VARCHAR(100) DEFAULT NULL,
	`content` TEXT,
	`work` TEXT,
	`secret` TEXT NULL COMMENT '비밀메모',
	`open` ENUM('Y','N') DEFAULT 'Y',
	`pw` VARCHAR(100) DEFAULT NULL,
	`date` DATETIME DEFAULT NULL,
	`moddate` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='문의';

CREATE TABLE `enquire_status` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` CHAR(1) NOT NULL,
	`label` VARCHAR(50) NULL DEFAULT NULL,
	`valid` ENUM('Y','N') NULL DEFAULT 'N',
	`sorting` INT(11) DEFAULT '0',
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `gallery` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`product_id` INT(11) DEFAULT NULL,
	`content` TEXT,
	`filename` VARCHAR(200) DEFAULT NULL,
	`sorting` INT(11) DEFAULT '0',
	`regdate` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `sorting` (`sorting`),
	INDEX `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `gallery_temp` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`member_id` INT(11) NULL DEFAULT NULL,
	`content` TEXT,
	`filename` VARCHAR(200) NULL DEFAULT NULL,
	`sorting` INT(10) NULL DEFAULT '0',
	`regdate` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `gallery_admin` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`product_id` INT(11) DEFAULT NULL,
	`content` TEXT,
	`filename` VARCHAR(200) DEFAULT NULL,
	`sorting` INT(11) DEFAULT '0',
	`regdate` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `sorting` (`sorting`),
	INDEX `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `gallery_temp_admin` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`member_id` INT(11) NULL DEFAULT NULL,
	`content` TEXT,
	`filename` VARCHAR(200) NULL DEFAULT NULL,
	`sorting` INT(10) NULL DEFAULT '0',
	`regdate` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `hope` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`session_id` VARCHAR(100) NOT NULL DEFAULT '0',
	`product_id` INT(11) DEFAULT NULL COMMENT '매물번호',
	`member_id` INT(11) DEFAULT NULL COMMENT '회원번호',
	`date` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='관심';

CREATE TABLE `hope_installations` (
`id` INT (11) NOT NULL AUTO_INCREMENT,
`session_id` VARCHAR (100) NOT NULL DEFAULT '0' COLLATE 'utf8_unicode_ci',
`installation_id` INT (11) NULL DEFAULT NULL COMMENT '분양번호' ,
`member_id` INT (11) NULL DEFAULT NULL COMMENT '회원번호' ,
`date` DATETIME NULL DEFAULT NULL,
PRIMARY KEY ( `id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='관심';


CREATE TABLE `log_blog` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`user_agent` VARCHAR(100) NOT NULL,
	`blog_id` INT(11) DEFAULT '0',
	`mobile` TINYINT(1) NOT NULL DEFAULT '0',
	`ip` VARCHAR(40) NOT NULL,
	`date` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `log_site` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`session_id` VARCHAR(40) NOT NULL,
	`session_cnt` INT(11) NOT NULL DEFAULT '0',
	`user_agent` VARCHAR(120) NOT NULL,
	`user_referrer` VARCHAR(200) NOT NULL,
	`mobile` TINYINT(1) NOT NULL DEFAULT '0',
	`ip` VARCHAR(40) NOT NULL,
	`type` ENUM('home' ,'category', 'product','installation' ) NOT NULL DEFAULT 'home',
	`data_id` INT(11) DEFAULT '0',
	`date` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `notices` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`date` DATETIME DEFAULT NULL,
	`title` VARCHAR(200) DEFAULT NULL,
	`content` TEXT ,
	`is_popup` TINYINT(1) DEFAULT '0',
	`viewcnt` INT(11) DEFAULT '0' COMMENT '조회수',
	PRIMARY KEY (`id`),
	KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='공지사항';

CREATE TABLE `faq` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(200) DEFAULT NULL,
	`content` TEXT ,
	`sorting` INT(11) NOT NULL,
	`date` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='FAQ';

CREATE TABLE `ask` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(50) DEFAULT NULL,
	`name` VARCHAR(50) DEFAULT NULL,
	`email` VARCHAR(100) DEFAULT NULL,
	`phone` VARCHAR(50) DEFAULT NULL,
	`content` TEXT,
	`pw` VARCHAR(100) DEFAULT NULL,
	`answer` TEXT,
	`open` ENUM('Y','N') DEFAULT 'Y',
	`date` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='문의하기';


CREATE TABLE `parent_address` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`sido` VARCHAR(4) NOT NULL DEFAULT '',
	`gugun` VARCHAR(20) NOT NULL DEFAULT '',
	`lat` double NOT NULL DEFAULT '0',
	`lng` double NOT NULL DEFAULT '0',
	`area` TEXT NOT NULL,
	`zoom` INT(11) NOT NULL DEFAULT '12',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `product_subway` (
	`product_id` INT(11) NOT NULL COMMENT '매물번호',
	`subway_id` INT(11) NOT NULL COMMENT '지하철번호'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='매물과 지하철의 상관 테이블';

CREATE TABLE `products` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`date` DATETIME DEFAULT NULL,
	`moddate` DATETIME DEFAULT NULL,
	`title` VARCHAR(200) DEFAULT '',
	`secret` VARCHAR(200) DEFAULT '',
	`gongsil_contact` TEXT NULL COMMENT '공실용연락처',
	`gongsil_status` VARCHAR(100) NULL DEFAULT '' COMMENT '공실용 방상태',
	`gongsil_see` VARCHAR(100) NULL DEFAULT '' COMMENT '공실용 방볼때',
	`address_id` INT(11) DEFAULT NULL COMMENT '지역정보',
	`danzi_id` VARCHAR(20) DEFAULT '' COMMENT '단지정보',
	`apt_dong` VARCHAR(20) NULL DEFAULT '' COMMENT '아파트동(공개)',
	`apt_ho` VARCHAR(20) NULL DEFAULT '' COMMENT '아파트호수(비공개)',	
	`loan` VARCHAR(200) NULL DEFAULT '' COMMENT '기대출금',	
	`theme` VARCHAR(100) DEFAULT '',
	`type` ENUM('installation','sell','full_rent','monthly_rent','rent') DEFAULT 'sell',
	`part` ENUM('Y','N') DEFAULT 'Y' COMMENT '부분,전체',
	`category` INT(11) DEFAULT '1',
	`category_sub` INT(11) NOT NULL DEFAULT '0',
	`sell_price` INT(11) NOT NULL DEFAULT '0' COMMENT '매매가',
	`lease_price` INT(11) NOT NULL DEFAULT '0' COMMENT '융자금',
	`full_rent_price` INT(11) NOT NULL DEFAULT '0' COMMENT '전세가',
	`monthly_rent_price` INT(11) NOT NULL DEFAULT '0' COMMENT '월세보증금',
	`monthly_rent_deposit` INT(11) NOT NULL DEFAULT '0' COMMENT '월세',
	`monthly_rent_deposit_min` INT(11) NOT NULL DEFAULT '0' COMMENT '최소보증금',
	`premium_price` INT(11) NOT NULL DEFAULT '0' COMMENT '권리금 및 프리미엄',
	`price_adjustment` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '가격조정가능',
	`mgr_price` VARCHAR(200) NULL DEFAULT '' COMMENT '관리비',
	`mgr_price_full_rent` VARCHAR(200) NULL DEFAULT '' COMMENT '관리비(전세)',
	`mgr_include` VARCHAR(200) NULL DEFAULT '' COMMENT '관리비포함내역',  
	`park_price` INT(11) NULL DEFAULT NULL COMMENT '주차비',
	`park` VARCHAR(100) NULL DEFAULT '' COMMENT '주차정보(총 몇대 / 세대당 몇대)',
	`total_floor` INT(11) NOT NULL DEFAULT '0' COMMENT '전체 층',
	`current_floor` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '해당 층',
	`bedcnt` INT(11) NOT NULL DEFAULT '0' COMMENT '방수',
	`bathcnt` INT(11) NOT NULL DEFAULT '0' COMMENT '욕실수',
	`extension` VARCHAR(200) NULL DEFAULT '' COMMENT '확장여부',
	`option` TEXT COMMENT '옵션사항들',
	`heating` VARCHAR(100) NULL DEFAULT '' COMMENT '난방정보',
	`abstract` VARCHAR(100) DEFAULT '',
	`content` TEXT ,
	`address` VARCHAR(200) DEFAULT NULL,
	`address_unit` VARCHAR(200) NULL DEFAULT NULL COMMENT '호수/건물/층수 등 세부정보',  
	`lat` double DEFAULT NULL,
	`lng` double DEFAULT NULL,
	`real_area` double DEFAULT NULL COMMENT '실평수/건축면적',
	`law_area` double DEFAULT NULL COMMENT '공급평수/대지면적',
	`land_area` DOUBLE NULL DEFAULT NULL COMMENT '연면적',
	`road_area` DOUBLE NULL DEFAULT NULL COMMENT '도로지분',
	`road_conditions` VARCHAR(50) DEFAULT NULL COMMENT '도로조건',
	`enter_year` VARCHAR(50) NOT NULL COMMENT '입주년월',
	`build_year` VARCHAR(50) NOT NULL COMMENT '준공년월',
	`ground_use` VARCHAR(50) DEFAULT NULL COMMENT '용도지역',
	`ground_aim` VARCHAR(50) DEFAULT NULL COMMENT '용도지목',
	`factory_power` VARCHAR(50) DEFAULT NULL COMMENT '전기(단위는 kw)',
	`factory_hoist` VARCHAR(50) DEFAULT NULL COMMENT '호이스트(단위는 ton)',
	`factory_use` VARCHAR(50) DEFAULT NULL COMMENT '공장용도',
	`store_category` VARCHAR(100) NULL DEFAULT '' COMMENT '상가 현재업종',
	`store_name` VARCHAR(100) NULL DEFAULT '' COMMENT '상가 현재업소명',  
	`profit_income` INT(10) NOT NULL DEFAULT '0' COMMENT '상가 수입',
	`profit_outcome` INT(10) NOT NULL DEFAULT '0' COMMENT '상가 지출',
	`outcome_matcost` INT(10) NOT NULL DEFAULT '0' COMMENT '지출 재료비',
	`outcome_salary` INT(10) NOT NULL DEFAULT '0' COMMENT '지출 인건비',
	`outcome_etc` INT(10) NOT NULL DEFAULT '0' COMMENT '지출 기타',
	`status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '매물상태(0:계약완료, 1:계약가능)',
	`recommand` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '추천여부',
	`is_activated` TINYINT(1) DEFAULT '0',
	`is_valid` TINYINT(1) DEFAULT '1',
	`is_finished` TINYINT(1) DEFAULT '0',
	`is_speed` TINYINT(1) DEFAULT '0',
	`is_defer` TINYINT(1) DEFAULT '0', 
	`is_blog` INT(11) DEFAULT '0',
	`is_blog_daum` INT(11) DEFAULT '0',
	`is_cafe` INT(11) DEFAULT '0',
	`member_id` INT(11) DEFAULT NULL COMMENT '담당자',
	`viewcnt` INT(11) DEFAULT '0' COMMENT '조회수',
	`result` VARCHAR(50) DEFAULT '0',
	`tag` VARCHAR(200) DEFAULT '0',
	`video_url` VARCHAR(100) NULL,
	`panorama_url` VARCHAR(100) NULL,
	`owner_name` VARCHAR(100) NULL,
	`owner_phone` VARCHAR(100) NULL,
	`etc` TEXT ,
	PRIMARY KEY (`id`),
	KEY `date` (`date`),
	KEY `address_id` (`address_id`),
	KEY `category` (`category`),
	KEY `lat` (`lat`),
	KEY `lng` (`lng`),
	KEY `is_activated` (`is_activated`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='매물';

CREATE TABLE `product_check` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`product_id` INT(11) NOT NULL,
	`date` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `product_attachment` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`product_id` INT(11) NULL DEFAULT NULL,
	`originname` VARCHAR(200) NULL DEFAULT NULL,
	`filename` VARCHAR(200) NULL DEFAULT NULL,
	`file_ext` VARCHAR(50) NULL DEFAULT NULL,
	`file_size` FLOAT NULL DEFAULT NULL,
	`regdate` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
) COLLATE='utf8_unicode_ci' ENGINE=MyISAM AUTO_INCREMENT=1
;

CREATE TABLE `concern_log` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`source` VARCHAR(40) NOT NULL,
	`module` VARCHAR(40) NOT NULL DEFAULT 'product',
	`member` INT(10) NOT NULL,
	`result` VARCHAR(100) NOT NULL,
	`data_id` INT(11) NULL DEFAULT NULL,
	`date` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
) COLLATE='utf8_unicode_ci' ENGINE=MyISAM;

CREATE TABLE `sms_history` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`sms_from` VARCHAR(40) NULL DEFAULT NULL COMMENT '발신자',
	`sms_to` TEXT NULL COMMENT '수신자',
	`msg` TEXT NULL COMMENT '문자내용',
	`type` CHAR(1) NULL DEFAULT NULL COMMENT 'A:단문, C:장문, D:포토',
	`minus_count` INT(11) NOT NULL DEFAULT '0' COMMENT '차감건수',
	`result` VARCHAR(100) NULL DEFAULT NULL COMMENT '결과값',
	`page` VARCHAR(20) NULL DEFAULT NULL COMMENT '발송페이지',
	`member_id` INT(11) NOT NULL DEFAULT '0',
	`date` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
;

CREATE TABLE `spot` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`date` DATETIME DEFAULT NULL,
	`name` VARCHAR(200) DEFAULT NULL,
	`lat` double DEFAULT NULL,
	`lng` double DEFAULT NULL,
	`content` TEXT ,
	`address` VARCHAR(200) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='핫스팟';

CREATE TABLE `subway` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`local` TINYINT(2) NOT NULL DEFAULT 1,
	`hosun` VARCHAR(50) NOT NULL,
	`hosun_id` INT(11) NOT NULL,
	`name` VARCHAR(100) NOT NULL,
	`lat` double NOT NULL,
	`lng` double NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='지하철역';

CREATE TABLE `theme` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`theme_name` VARCHAR(50) NOT NULL,
		`description` VARCHAR(100) NOT NULL,
		`col` TINYINT(2) NOT NULL DEFAULT '1',
		`image` VARCHAR(200) DEFAULT NULL,
		`sorting` INT(11) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
;

CREATE TABLE `service` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`service_name` VARCHAR(50) NOT NULL,
	`description` VARCHAR(200) NOT NULL,
	`link` VARCHAR(200) NOT NULL,
	`target` ENUM('Y','N') NOT NULL DEFAULT 'N',  
	`col` TINYINT(2) NOT NULL DEFAULT '1',
	`flag` ENUM('Y','N') NOT NULL DEFAULT 'Y',  
	`image` VARCHAR(200) NULL DEFAULT NULL,
	`sorting` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
;

CREATE TABLE `widgets` (
	`id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
	`type` INT(10) NOT NULL DEFAULT '1',
	`title` VARCHAR(100) NOT NULL,
	`content` TEXT NOT NULL,
	`border` TINYINT(2) NOT NULL DEFAULT '0',
	`valid` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	`viewcnt` INT(11) NOT NULL DEFAULT '0',
	`date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='위젯';

CREATE TABLE `mainmenu` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(100) NOT NULL,
	`type` VARCHAR(100) NOT NULL,
	`flag` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	`sorting` INT(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `intro` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(100) NOT NULL,
	`content` TEXT,
	`flag` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	`sorting` INT(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `contacts_group` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`group_name` VARCHAR(100) NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `contacts` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NOT NULL,
	`group_id` INT(10) NOT NULL DEFAULT '0',
	`role` VARCHAR(100) NULL DEFAULT NULL,
	`organization` VARCHAR(100) NULL DEFAULT NULL,
	`email` TEXT NULL,
	`address` TEXT NULL,
	`phone` TEXT NULL,
	`homepage` TEXT NULL,
	`background` TEXT NULL,
	`sex` ENUM('F','M') NULL DEFAULT NULL,
	`regdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`moddate` DATETIME NULL DEFAULT NULL,
	`member_id` INT(11) NULL DEFAULT NULL,
	`is_opened` TINYINT(1) NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `contacts_memo` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`contacts_id` INT(11) NULL DEFAULT NULL,
	`content` TEXT,
	`regdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`member_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `contacts_action` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`contacts_id` INT(11) NULL DEFAULT NULL,
	`type` ENUM('call','meeting','etc') NOT NULL DEFAULT 'call',
	`content` TEXT NULL,
	`actiondate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`regdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`member_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
;

CREATE TABLE `contacts_task` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`contacts_id` INT(11) NULL DEFAULT NULL,
	`title` VARCHAR(200) NOT NULL COMMENT '작업제목',
	`content` TEXT NULL COMMENT '작업내용',
	`important` ENUM('1','2','3') NULL DEFAULT '2',
	`finished` ENUM('Y','N') NULL DEFAULT 'N' COMMENT '종료여부',
	`regdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록일',
	`deaddate` DATE NOT NULL DEFAULT '0000-00-00' COMMENT '데드라인',
	`enddate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '실제종료시간',
	`member_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
;

CREATE TABLE `contacts_history` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`contacts_id` INT(11) NULL DEFAULT NULL,
	`data_id` INT(11) NULL DEFAULT NULL,
	`member_id` INT(11) NOT NULL,
	`type` ENUM('C','M','A','T') NOT NULL COMMENT 'C:Contact,M:Memo,A:접촉,T:Task',
	`action` ENUM('A','M','S','E') NOT NULL COMMENT 'A등록,M수정,S시작,E종료',
	`title` TEXT NOT NULL COMMENT '입력한 내용',
	`regdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `contacts_product` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`type` ENUM('seller','buyer','broker','etc') NOT NULL,
	`contacts_id` INT(11) NULL DEFAULT NULL,
	`product_id` INT(11) NULL DEFAULT NULL,
	`date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
;


CREATE TABLE `admin_auth` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`auth_name` VARCHAR(100) NOT NULL,
	`auth_home` ENUM('N','Y') NOT NULL DEFAULT 'N',
	`auth_product` ENUM('N','Y') NOT NULL DEFAULT 'N',
	`auth_member` ENUM('N','Y') NOT NULL DEFAULT 'N',
	`auth_contact` ENUM('N','Y') NOT NULL DEFAULT 'N',
	`auth_request` ENUM('N','Y') NOT NULL DEFAULT 'N',
	`auth_news` ENUM('N','Y') NOT NULL DEFAULT 'N',
	`auth_portfolio` ENUM('Y','N') NOT NULL DEFAULT 'N',
	`auth_set` ENUM('N','Y') NOT NULL DEFAULT 'N',
	`auth_custom` ENUM('N','Y') NOT NULL DEFAULT 'N',
	`auth_popup` ENUM('N','Y') NOT NULL DEFAULT 'N',
	`auth_layout` ENUM('N','Y') NOT NULL DEFAULT 'N',
	`auth_stats` ENUM('N','Y') NOT NULL DEFAULT 'N',
	`auth_pay` ENUM('N','Y') NOT NULL DEFAULT 'N',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
;

CREATE TABLE `component_portfolio_category` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL,
	`opened` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	`sorting` INT(11) NOT NULL DEFAULT '0',
	`valid` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
;

CREATE TABLE `component_portfolio` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`date` DATETIME NULL DEFAULT NULL,
	`title` VARCHAR(200) NULL DEFAULT NULL,
	`thumb_name` VARCHAR(200) NULL DEFAULT NULL,
	`category` INT(11) NULL DEFAULT '1',
	`content` TEXT NULL,
	`is_activated` TINYINT(1) NULL DEFAULT '0',
	`is_blog` INT(11) NULL DEFAULT '0',
	`viewcnt` INT(11) NULL DEFAULT '0' COMMENT '조회수',
	`result` VARCHAR(50) NULL DEFAULT '0',
	`tag` VARCHAR(200) NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `date` (`date`),
	INDEX `category` (`category`),
	INDEX `is_activated` (`is_activated`)
)
COMMENT='포트폴리오'
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
;

CREATE TABLE `component_portfolio_comment` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`step_id` INT(11) NOT NULL DEFAULT '0',
	`portfolio_id` INT(11) NOT NULL,
	`member_id` INT(11) NOT NULL DEFAULT '0',
	`type` ENUM('front','admin') NOT NULL DEFAULT 'front',
	`name` VARCHAR(50) NOT NULL,
	`pw` VARCHAR(50) NOT NULL,
	`content` TEXT,
	`delete` ENUM('Y','N') NOT NULL DEFAULT 'N',
	`date` DATETIME NOT NULL,
	PRIMARY KEY (`id`,`step_id`,`portfolio_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `home_layout` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(100) NOT NULL,
	`sorting` INT(10) NOT NULL,
	`module` VARCHAR(100) NOT NULL DEFAULT 'html' COMMENT '컴포넌트',
	`code` TEXT,
	`valid` ENUM('Y','N') DEFAULT 'Y',
	`date` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `top_layout` (
	`id` INT(2) NOT NULL DEFAULT '1',
	`top_bar` INT(2) DEFAULT '0',
	`menu` INT(2) DEFAULT '1',
	`menu_left` INT(2) DEFAULT '1',
	`menu_right` VARCHAR(50) DEFAULT '',
	`menu_right_bold` VARCHAR(20) DEFAULT '',
	`navbar` INT(2) DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `call_log` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`member` INT(11) NOT NULL,
	`product_id` INT(11) NULL DEFAULT NULL,
	`user_agent` VARCHAR(120) NOT NULL,
	`date` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
;

CREATE TABLE `slide` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`filename` VARCHAR(200) NULL DEFAULT NULL,
	`link` VARCHAR(200) NULL DEFAULT NULL,
	`sorting` INT(10) NULL DEFAULT '0',
	`regdate` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `landing` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`filename` VARCHAR(200) NULL DEFAULT NULL,
	`link` VARCHAR(200) NULL DEFAULT NULL,
	`sorting` INT(10) NULL DEFAULT '0',
	`regdate` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `product_near_meta` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(50) NOT NULL,
	`query` VARCHAR(50) NOT NULL,
	`valid` ENUM('Y','N') NOT NULL DEFAULT 'N',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `pay_setting` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL,  
	`count` INT(11) NOT NULL DEFAULT '0',
	`day` INT(11) NOT NULL DEFAULT '0',
	`price` INT(11) NOT NULL DEFAULT '0',
	`sorting` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `pay` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`member_id` INT(11) NOT NULL COMMENT '회원아이디',
	`pay_setting_id` INT(11) NOT NULL COMMENT '결제상품아이디',
	`order_name` VARCHAR(50) NOT NULL ,
	`pay_type` TINYINT(2) NOT NULL,
	`tid` VARCHAR(100) NULL DEFAULT NULL COMMENT 'PG거래번호' ,
	`pltid` VARCHAR(100) NULL DEFAULT NULL COMMENT '페이원큐 거래번호' ,
	`cid` VARCHAR(100) NULL DEFAULT NULL COMMENT 'PG거래번호' ,
	`pgname` VARCHAR(100) NULL DEFAULT NULL COMMENT '결제PG' ,
	`cardname` VARCHAR(100) NULL DEFAULT NULL COMMENT '결제수단명' ,
	`dealno` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증' ,
	`receiptcid` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증(cid)' ,
	`paysid` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증(신분확인번호)' ,
	`gubun` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증(구분)' ,
	`receiptissuegubun` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증 구매자발행, 자체발행 구분' ,
	`receipterrcode` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증 에러코드' ,
	`receipterrmsg` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증 에러메시지' ,
	`cashreceiptkind` ENUM('0','1') NULL DEFAULT NULL COMMENT '\'0\':소득공제, \'1\':지출증빙' ,
	`accountno` VARCHAR(100) NULL DEFAULT NULL COMMENT '계좌번호' ,
	`accountname` VARCHAR(100) NULL DEFAULT NULL COMMENT '계좌주명' ,
	`bankname` VARCHAR(100) NULL DEFAULT NULL COMMENT '입금은행명' ,
	`transfer_email` VARCHAR(100) NULL DEFAULT NULL COMMENT '이전받을 이메일주소' ,
	`errcode` VARCHAR(100) NULL DEFAULT NULL COMMENT '에러코드' ,
	`errmsg` VARCHAR(100) NULL DEFAULT NULL COMMENT '에러메세지' ,
	`use_day` INT(11) NOT NULL,
	`use_count` INT(11) NOT NULL COMMENT '등록가능횟수',
	`price` INT(11) NOT NULL COMMENT '결제금액',
	`state` ENUM('N','Y','W') NOT NULL DEFAULT 'N' COMMENT '결제상태' ,
	`start_date` DATETIME NULL DEFAULT NULL COMMENT '결제시작일',
	`end_date` DATETIME NULL DEFAULT NULL COMMENT '결제종료일',
	`payed_date` DATETIME NULL DEFAULT NULL,
	`is_admin` ENUM('N','Y') NOT NULL DEFAULT 'N' COMMENT '관라지지급', 
	`date` DATETIME NOT NULL COMMENT '등록일',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `social` (
	`id` INT(11) NOT NULL DEFAULT '1',
	`naver_cafe` VARCHAR(100) NULL DEFAULT NULL COMMENT '네이버카페',
	`naver_blog` VARCHAR(100) NULL DEFAULT NULL COMMENT '네이버블로그',
	`facebook` VARCHAR(100) NULL DEFAULT NULL COMMENT '페이스북',
	`twitter` VARCHAR(100) NULL DEFAULT NULL COMMENT '트위터',
	`google_plus` VARCHAR(100) NULL DEFAULT NULL COMMENT '구글플러스',
	`youtube_channel` VARCHAR(100) NULL DEFAULT NULL COMMENT '유투브채널',
	PRIMARY KEY (`id`)
) 
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;
;

CREATE TABLE IF NOT EXISTS `viral_proverb` (
	`id` INT(6) NOT NULL AUTO_INCREMENT,
	`code` TEXT NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `viral_statement` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`code` VARCHAR(200) NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `viral_youtube` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(200) NOT NULL COMMENT '제목',
	`code` VARCHAR(100) NOT NULL COMMENT '유튜브코드',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;


CREATE TABLE `styles` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`code` TEXT NULL,
	`valid` ENUM('Y','N') NULL DEFAULT 'N',
	`regdate` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `enquire_memo` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`enquire_id` INT(11) NULL DEFAULT NULL,
	`content` TEXT NULL,
	`regdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`member_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;

CREATE TABLE `enquire_action` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`enquire_id` INT(11) NULL DEFAULT NULL,
	`type` ENUM('call','meeting','etc') NOT NULL DEFAULT 'call',
	`content` TEXT NULL,
	`actiondate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`regdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`member_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;

CREATE TABLE `sms_statements` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`content` TEXT NULL,
	`regdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;

CREATE TABLE `sms_charging` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`price` INT(11) NOT NULL,
	`cnt` INT(11) NOT NULL,
	`regdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;

CREATE TABLE `enquire_history` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`enquire_id` INT(11) NULL DEFAULT NULL,
	`data_id` INT(11) NULL DEFAULT NULL,
	`member_id` INT(11) NOT NULL,
	`type` ENUM('C','M','L') NOT NULL COMMENT 'C:접촉,M:메모,L:계약(law)',
	`action` ENUM('A','M') NOT NULL COMMENT 'A등록,M수정',
	`title` TEXT NOT NULL COMMENT '입력한 내용',
	`regdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;

CREATE TABLE `enquire_contract` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`enquire_id` INT(11) NULL DEFAULT NULL,
	`title` VARCHAR(200) NULL DEFAULT NULL COMMENT '계약명',
	`contract_date` DATE NULL DEFAULT NULL COMMENT '계약일',
	`type` VARCHAR(100) NULL DEFAULT NULL COMMENT '거래유형',
	`category` VARCHAR(100) NULL DEFAULT NULL COMMENT '매물유형',
	`contract_price` VARCHAR(100) NULL DEFAULT NULL COMMENT '계약금',
	`part_price` VARCHAR(100) NULL DEFAULT NULL COMMENT '중도금',
	`balance_price` VARCHAR(100) NULL DEFAULT NULL COMMENT '잔금',
	`commission_price` VARCHAR(100) NULL DEFAULT NULL COMMENT '중개수수료',
	`contract_pay_date` DATE NULL DEFAULT NULL COMMENT '계약금 지급날짜',
	`part_pay_date` DATE NULL DEFAULT NULL COMMENT '중도금 지급날짜',
	`balance_pay_date` DATE NULL DEFAULT NULL COMMENT '잔금 지급날짜',
	`tax_type` ENUM('cash','tax') NOT NULL DEFAULT 'cash' COMMENT 'cash:현금영수증,tax:세금계산서',
	`tax_use` ENUM('N','Y') NOT NULL DEFAULT 'N' COMMENT '발행여부',
	`originname` VARCHAR(200) NULL DEFAULT NULL COMMENT '계약서파일명',
	`filename` VARCHAR(200) NULL DEFAULT NULL COMMENT '계약서파일',
	`file_size` FLOAT NULL DEFAULT NULL COMMENT '계약서파일크기',
	`contacts_id` INT(11) NULL DEFAULT NULL,
	`status` ENUM('N','Y') NOT NULL DEFAULT 'N' COMMENT '계약상태',
	`date` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `pay_sms` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`order_name` VARCHAR(50) NOT NULL ,
	`pay_type` TINYINT(2) NOT NULL,
	`tid` VARCHAR(100) NULL DEFAULT NULL COMMENT 'PG거래번호' ,
	`pltid` VARCHAR(100) NULL DEFAULT NULL COMMENT '페이원큐 거래번호' ,
	`cid` VARCHAR(100) NULL DEFAULT NULL COMMENT 'PG거래번호' ,
	`pgname` VARCHAR(100) NULL DEFAULT NULL COMMENT '결제PG' ,
	`cardname` VARCHAR(100) NULL DEFAULT NULL COMMENT '결제수단명' ,
	`dealno` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증' ,
	`receiptcid` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증(cid)' ,
	`paysid` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증(신분확인번호)' ,
	`gubun` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증(구분)' ,
	`receiptissuegubun` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증 구매자발행, 자체발행 구분' ,
	`receipterrcode` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증 에러코드' ,
	`receipterrmsg` VARCHAR(100) NULL DEFAULT NULL COMMENT '현금영수증 에러메시지' ,
	`cashreceiptkind` ENUM('0','1') NULL DEFAULT NULL COMMENT '\'0\':소득공제, \'1\':지출증빙' ,
	`accountno` VARCHAR(100) NULL DEFAULT NULL COMMENT '계좌번호' ,
	`accountname` VARCHAR(100) NULL DEFAULT NULL COMMENT '계좌주명' ,
	`bankname` VARCHAR(100) NULL DEFAULT NULL COMMENT '입금은행명' ,
	`transfer_email` VARCHAR(100) NULL DEFAULT NULL COMMENT '이전받을 이메일주소' ,
	`errcode` VARCHAR(100) NULL DEFAULT NULL COMMENT '에러코드' ,
	`errmsg` VARCHAR(100) NULL DEFAULT NULL COMMENT '에러메세지' ,
	`sms_count` INT(11) NOT NULL COMMENT 'sms구매건수',
	`price` INT(11) NOT NULL COMMENT '결제금액',
	`state` ENUM('N','Y','W') NOT NULL DEFAULT 'N' COMMENT '결제상태' ,
	`payed_date` DATETIME NULL DEFAULT NULL,
	`date` DATETIME NOT NULL COMMENT '등록일',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `installation_subway` (
	`installation_id` INT(11) NOT NULL COMMENT '분양번호',
	`subway_id` INT(11) NOT NULL COMMENT '지하철번호'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='분양과 지하철의 상관 테이블';

CREATE TABLE `installations` (
	 `id` INT(11) NOT NULL AUTO_INCREMENT,
	 `category` ENUM('apt','villa','officetel','city','shop') DEFAULT 'apt' COMMENT 'apt:아파트,villa:빌라,officetel:오피스텔,city:도시형생활주택,shop:상가',
	 `title` VARCHAR(200) DEFAULT '',
	 `tel` VARCHAR(200) DEFAULT ''  COMMENT '문의전화',
	 `secret` VARCHAR(200) DEFAULT '',
	 `address_id` INT(11) DEFAULT NULL COMMENT '지역정보',
	 `address` VARCHAR(200) DEFAULT NULL,
	 `scale` VARCHAR(200) NULL DEFAULT '' COMMENT '규모',
	 `heating` VARCHAR(100) NULL DEFAULT '' COMMENT '난방정보',
	 `park` VARCHAR(100) NULL DEFAULT '' COMMENT '주차정보(총 몇대 / 세대당 몇대)',
	 `builder` VARCHAR(100) NULL DEFAULT '' COMMENT '건설사',
	 `builder_url` VARCHAR(100) NULL DEFAULT '' COMMENT '건설사 홈페이지',
	 `bank` VARCHAR(100) NULL DEFAULT '' COMMENT '청약가능통장',
	 `is_presale` TINYINT(1) DEFAULT '1' COMMENT '전매가능여부',
	 `enter_year` VARCHAR(50) NOT NULL COMMENT '입주날짜',
	`notice_year` VARCHAR(50) NOT NULL COMMENT '공고날짜',   
	 `abstract` VARCHAR(100) DEFAULT '',
	 `content` TEXT,
	 `lat` double DEFAULT NULL,
	 `lng` double DEFAULT NULL,
	 `recommand` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '추천여부',
	 `is_activated` TINYINT(1) DEFAULT '0',
	 `is_valid` TINYINT(1) DEFAULT '1',
	 `status` ENUM('plan','go','end') DEFAULT 'plan' COMMENT 'plan:계획중,go:진행중,end:종료',
	 `is_blog` INT(11) DEFAULT '0',
	 `is_cafe` INT(11) DEFAULT '0',
	 `member_id` INT(11) DEFAULT NULL COMMENT '담당자',
	 `viewcnt` INT(11) DEFAULT '0' COMMENT '조회수',
	 `tag` VARCHAR(200) DEFAULT '0',
	 `video_url` VARCHAR(100) NULL,
	 `application_file` VARCHAR(100) NULL COMMENT '입주자모집요강(파일첨부)',
	 `moddate` DATETIME DEFAULT NULL,
	 `date` DATETIME DEFAULT NULL,
	 PRIMARY KEY (`id`),
	 KEY `date` (`date`),
	 KEY `address_id` (`address_id`),
	 KEY `category` (`category`),
	 KEY `lat` (`lat`),
	 KEY `lng` (`lng`),
	 KEY `is_activated` (`is_activated`)
 ) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='분양';

CREATE TABLE `installation_attachment` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`installation_id` INT(11) NULL DEFAULT NULL,
	`originname` VARCHAR(200) NULL DEFAULT NULL,
	`filename` VARCHAR(200) NULL DEFAULT NULL,
	`file_ext` VARCHAR(50) NULL DEFAULT NULL,
	`file_size` FLOAT NULL DEFAULT NULL,
	`regdate` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `installation_schedule` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`installation_id` INT(11) NOT NULL,
		`name` VARCHAR(50) NOT NULL,
		`description` VARCHAR(200) NOT NULL,
		`date` date DEFAULT NULL,
		PRIMARY KEY (`id`)
)
 COLLATE='utf8_general_ci'
 ENGINE=MyISAM
 AUTO_INCREMENT=1;

CREATE TABLE `gallery_installation` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`installation_id` INT(11) DEFAULT NULL,
	`content` TEXT,
	`filename` VARCHAR(200) DEFAULT NULL,
	`sorting` INT(11) DEFAULT '0',
	`regdate` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `sorting` (`sorting`),
	INDEX `installation_id` (`installation_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `gallery_installation_temp` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`member_id` INT(11) NULL DEFAULT NULL,
	`content` TEXT,
	`filename` VARCHAR(200) NULL DEFAULT NULL,
	`sorting` INT(10) NULL DEFAULT '0',
	`regdate` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `pyeong` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`installation_id` INT(11) DEFAULT NULL,
	`filename` VARCHAR(200) DEFAULT NULL,
	`name` VARCHAR(50) NOT NULL,
	`description` VARCHAR(200) NOT NULL,
	`price_min` INT(11) NOT NULL DEFAULT '0' COMMENT '분양최소가',
	`price_max` INT(11) NOT NULL DEFAULT '0' COMMENT '분양최대가',
	`tax` INT(11) NOT NULL DEFAULT '0' COMMENT '취득세',
	`real_area` double DEFAULT NULL COMMENT '전용면적',
	`law_area` double DEFAULT NULL COMMENT '공급면적',
	`road_area` DOUBLE NULL DEFAULT NULL COMMENT '대지지분',
	`gate` VARCHAR(200) DEFAULT '' COMMENT '현관',
	`cnt` INT(11) NOT NULL DEFAULT '0' COMMENT '분양세대수',
	`image` VARCHAR(200) DEFAULT NULL,
	`bedcnt` INT(11) NOT NULL DEFAULT '0' COMMENT '방수',
	`bathcnt` INT(11) NOT NULL DEFAULT '0' COMMENT '욕실수',
	`presale_date` VARCHAR(200) DEFAULT '' COMMENT '전매기간',
	`sorting` INT(11) DEFAULT '0',
	`regdate` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `sorting` (`sorting`),
	INDEX `installation_id` (`installation_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pyeong_temp` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`member_id` INT(11) NULL DEFAULT NULL,
	`filename` VARCHAR(200) NULL DEFAULT NULL,
	`name` VARCHAR(50) NOT NULL,
	`description` VARCHAR(200) NOT NULL,
	`price_min` INT(11) NOT NULL DEFAULT '0' COMMENT '분양최소가',
	`price_max` INT(11) NOT NULL DEFAULT '0' COMMENT '분양최대가',
	`tax` INT(11) NOT NULL DEFAULT '0' COMMENT '취득세',
	`real_area` double DEFAULT NULL COMMENT '전용면적',
	`law_area` double DEFAULT NULL COMMENT '공급면적',
	`road_area` DOUBLE NULL DEFAULT NULL COMMENT '대지지분',
	`gate` VARCHAR(200) DEFAULT '' COMMENT '현관',
	`cnt` INT(11) NOT NULL DEFAULT '0' COMMENT '분양세대수',
	`image` VARCHAR(200) DEFAULT NULL,
	`bedcnt` INT(11) NOT NULL DEFAULT '0' COMMENT '방수',
	`bathcnt` INT(11) NOT NULL DEFAULT '0' COMMENT '욕실수',
	`presale_date` VARCHAR(200) DEFAULT '' COMMENT '전매기간',
	`sorting` INT(10) NULL DEFAULT '0',
	`regdate` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `members_delete_log` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(100) NULL DEFAULT NULL,
	`name` VARCHAR(100) NULL DEFAULT NULL,
	`reason` VARCHAR(200) NULL DEFAULT NULL,
	`date` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `building` (
	`id` INT(11) NOT NULL,
	`address` VARCHAR(100) NOT NULL COMMENT '주소',
	`address_id` INT(11) NOT NULL,
	`road_name` VARCHAR(100) NULL DEFAULT NULL COMMENT '도로명',
	`plottage` DOUBLE NULL DEFAULT NULL COMMENT '대지면적',
	`building_area` DOUBLE NULL DEFAULT NULL COMMENT '건축면적',
	`building_coverage` DOUBLE NULL DEFAULT NULL COMMENT '건폐율',
	`total_floor_area` DOUBLE NULL DEFAULT NULL COMMENT '연면적',
	`floor_area_cal` DOUBLE NULL DEFAULT NULL COMMENT '용적률산정연면적',
	`floor_area_ratio` DOUBLE NULL DEFAULT NULL COMMENT '용적율',
	`structure_name` VARCHAR(50) NULL DEFAULT NULL COMMENT '구조코드명',
	`main_use` VARCHAR(50) NULL DEFAULT NULL COMMENT '주용도코드명',
	`etc_use` VARCHAR(50) NULL DEFAULT NULL COMMENT '기타용도',
	`ground_floors` INT(11) NULL DEFAULT NULL COMMENT '지상층수',
	`underground_floors` INT(11) NULL DEFAULT NULL COMMENT '지하층수',
	`elevator_count` INT(11) NULL DEFAULT NULL COMMENT '엘리베이터수',
	`parking_count` INT(11) NULL DEFAULT NULL COMMENT '총주차대수',
	`use_approval_day` DATE NULL DEFAULT NULL COMMENT '사용승인일',
	`energy_efficiency` INT(11) NULL DEFAULT NULL COMMENT '에너지효율등급',
	PRIMARY KEY (`id`),
	INDEX `address` (`address`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `building_enquire` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`building_id` INT(11) NOT NULL,
	`member_id` INT(11) NOT NULL,
	`type` ENUM('e​stimate','sell') NOT NULL DEFAULT 'e​stimate' COMMENT 'e​stimate:견적의뢰,sell:중개의뢰',
	`city_planning` VARCHAR(100) NULL DEFAULT NULL COMMENT '도시계획',
	`coverage_upper` INT(11) NULL DEFAULT NULL COMMENT '건폐율상한',
	`ratio_upper` INT(11) NULL DEFAULT NULL COMMENT '용적율상한',
	`building_area_uppper` INT(11) NULL DEFAULT NULL COMMENT '건축면적상한',
	`ground_total_floor_area` INT(11) NULL DEFAULT NULL COMMENT '지상연면적상한',
	`expense_kind` TINYINT(1) NULL DEFAULT NULL COMMENT '1:단독주택, 2:상가주택, 3:상가건물',
	`expense_grade` ENUM('normal','medium','high') NULL DEFAULT 'normal' COMMENT '등급',
	`expense_elevator` TINYINT(1) NULL DEFAULT '0' COMMENT '엘리베이터유무',
	`construction_cost` BIGINT(20) NULL DEFAULT NULL COMMENT '공사비',
	`design_supervision_cost` BIGINT(20) NULL DEFAULT NULL COMMENT '설계감리비',
	`probable_cost` BIGINT(20) NULL DEFAULT NULL COMMENT '예상건축비용',
	`date` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `building_estimate` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`enquire_id` INT(11) NULL DEFAULT NULL,
	`originname` VARCHAR(200) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`filename` VARCHAR(200) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`file_ext` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`file_size` FLOAT NULL DEFAULT NULL,
	`regdate` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `building_expense` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`kind` TINYINT(1) NOT NULL COMMENT '1:단독주택, 2:상가주택, 3:상가건물, 4:설계감리비/평, 5:엘리베이터',
	`grade` ENUM('normal','medium','high') NOT NULL DEFAULT 'normal' COMMENT '등급',
	`price` INT(11) NOT NULL COMMENT '가격',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `building_limit` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`code_name` VARCHAR(50) NOT NULL,
	`category` VARCHAR(20) NOT NULL,
	`category_sub` VARCHAR(20) NULL DEFAULT NULL,
	`limit_title` VARCHAR(200) NOT NULL,
	`limit_num` TINYINT(1) NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `building_supremum` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`code_name` VARCHAR(50) NULL DEFAULT NULL,
	`coverage_upper` INT(11) NULL DEFAULT NULL,
	`ratio_upper` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `news_attachment` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`news_id` int(11) DEFAULT NULL,
	`originname` varchar(200) DEFAULT NULL,
	`filename` varchar(200) DEFAULT NULL,
	`file_ext` varchar(50) DEFAULT NULL,
	`file_size` float DEFAULT NULL,
	`regdate` datetime DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `product_memo` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`product_id` INT(11) NOT NULL,
	`memo` TEXT NULL,
	`date` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `category_sub` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`main_id` INT(11) NOT NULL,
	`name` VARCHAR(20) NOT NULL,
	`sorting` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `region` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(200) DEFAULT NULL,
	`address_id` INT(11) DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `monthly_add_price` (
	`product_id` INT(11) NOT NULL,
	`monthly_rent_deposit` INT(11) NULL DEFAULT '0',
	`monthly_rent_price` INT(11) NULL DEFAULT '0'
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;