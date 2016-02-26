<!DOCTYPE html>
<html>
<head>
<title><?php echo $config->name;?> <?php echo lang('admin_administrator');?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="description" content="">
<meta name="keywords" content="" />
<meta name="author" content="Dungzi">
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<link href="/assets/plugin/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/assets/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="/assets/plugin/fancybox/source/jquery.fancybox.css" rel="stylesheet">

<link href="/assets/admin/css/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/admin/css/jquery-ui.theme.css">
<link href="/assets/plugin/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link rel="stylesheet" href="/assets/admin/css/morris-0.5.1.css">
<link href="/assets/plugin/select2/select2.css" rel="stylesheet" type="text/css"/>

<!-- BEGIN THEME STYLES -->
<link href="/assets/admin/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/css/themes/darkblue.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/css/custom.css" rel="stylesheet" type="text/css"/>
<link href="/assets/common/css/element.css" rel="stylesheet">
<link href="/assets/plugin/icheck/skins/square/red.css" rel="stylesheet">
<!-- END THEME STYLES -->

<link href="/assets/common/css/all.css" rel="stylesheet">

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/assets/plugin/respond.min.js"></script>
<script src="/assets/plugin/excanvas.min.js"></script> 
<![endif]-->
<script src="/assets/plugin/jquery.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery-migrate.min.js" type="text/javascript"></script>
<script src="/assets/plugin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery.cokie.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery-ui.min.js"></script>
<script src="/assets/plugin/icheck/icheck.min.js" type="text/javascript"></script>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places&language=ko&region=KR"></script>

<script src="/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="/ckeditor/bootstrap-ckeditor-fix.js"></script>		
<script src="/assets/plugin/jquery.form.js" type="text/javascript" charset="UTF-8"></script>
<script src="/assets/plugin/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/plugin/select2/select2.min.js"></script>
<!-- END CORE PLUGINS -->

<script src="/assets/common/js/init.js" type="text/javascript"></script>
<script src="/assets/admin/js/admin_custom.js" type="text/javascript"></script>

<?php if($this->session->userdata("is_mobile")!="1"){?>
<script src="/assets/plugin/plupload/plupload.full.min.js"></script>
<?php }?>

<script type="text/javascript" src="/assets/plugin/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="/assets/plugin/fancybox/source/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="/assets/plugin/jquery.print.js"></script>

<link rel="stylesheet" href="/assets/admin/css/style.css">
<script src="/assets/admin/js/metronic.js" type="text/javascript"></script>
<script src="/assets/admin/js/layout.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery.session.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery.geocomplete.min.js"></script>

<script>

$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	Metronic.init(); 
	Layout.init(); 
	
	$('input,textarea').attr('autocomplete', 'off');
    
    $(".fancy").fancybox({
          helpers: {
              title : {
                  type : 'float'
              }
          }
    });

	$('.help').tooltip();

	$("#upload_admin_dialog").dialog({
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:400,
			height: 230,
			modal: true,
			open: function (event, ui) {
				 $(".ui-dialog").css("z-index",9999);
			},
			buttons: {
				'이미지 등록': function() {
					$("#upload_admin_form").submit();
				}
			}
	});

	$('#upload_admin_form').ajaxForm({
		success:function(data){
			if(data == ""){
				alert("실패");
				alert(data);
			} 
			else {
	
				CKEDITOR.instances.sign_admin_edit.insertHtml( "<img src='"+data+"'>" );
				
			} 
			$('#upload_admin_dialog').dialog("close");

		}
	});

	$("#admin_edit_form").validate({  
        errorElement: "span",
        wrapper: "span", 
		rules: {
			name: {  
				required: true,  
				minlength: 2
			},
			email: {  
				required: true,
				email:true
			},
			phone: {  
				required: true,
				minlength:8
			}
		},  
		messages: {  
			name: {  
				required: "<?php echo lang("form.required");?>",  
				minlength: "최소 2자리 이상입니다"
			},
			email: {  
				required: "<?php echo lang("form.required");?>",
				email:"이메일 형식으로 입력해주세요"
			},
			phone: {  
				required: "<?php echo lang("form.required");?>",
				minlength:"최소 8자리 이상입니다"
			}
		} 
	});

	$("#admin_edit_form").ajaxForm({
		beforeSubmit: function(){
		},
		success:function(data){
			if(data=="true"){
				$("#edit_admin_dialog").modal('hide');
			}
			return;
		}
	});

	$('#admin_edit_form').find('#delete_profile').click(function(){

		var member_id = $("#admin_edit_form input[id=id]").val();
		var profile_img_name = $('#admin_edit_form').find('#profile_img_name').val();
		if(!profile_img_name || $('#admin_edit_form').find('#profile_img').hasClass("is-delete")){
			alert("현재 등록된 프로필사진이 없습니다.");
			return false;
		}
		if(confirm("프로필사진이 바로 삭제 됩니다. 삭제하시겠습니까?")){
			$.ajax({
				url: "/adminmember/delete_profile_image",
				type: "POST",
				data: {
					member_id: member_id,
					profile_img_name: profile_img_name
				},
				success: function(data) {
					$('#admin_edit_form').find('#profile_img').addClass("is-delete");
					$('#admin_edit_form').find('#profile_img').html("<img src='/assets/common/img/no_human.png' style='width:60px;height:60px;'>");
					msg($('#admin_edit_form').find("#profile_msg"), "success" ,"삭제 되었습니다.");
				}
			});		
		}
	});

	$('#admin_edit_form').find('input[name="profile"]').change(function(e){
		msg($('#admin_edit_form').find("#profile_msg"), "info" ,$(this).val());
	});

	$('#admin_edit_form').find('#delete_watermark').click(function(){

		var member_id = $("#admin_edit_form input[id=id]").val();
		var watermark_img_name = $('#admin_edit_form').find('#watermark_img_name').val();
		if(!watermark_img_name || $('#admin_edit_form').find('#watermark_img').hasClass("is-delete")){
			alert("현재 등록된 워터마크가 없습니다.");
			return false;
		}
		if(confirm("워터마크가 바로 삭제 됩니다. 삭제하시겠습니까?")){
			$.ajax({
				url: "/adminmember/delete_watermark_image",
				type: "POST",
				data: {
					member_id: member_id,
					watermark_img_name: watermark_img_name
				},
				success: function(data) {
					$('#admin_edit_form').find('#watermark_img').addClass("is-delete");
					$('#admin_edit_form').find('#watermark_img').html("등록된 워터마크가 없습니다.");
					msg($('#admin_edit_form').find("#watermark_msg"), "success" ,"삭제 되었습니다.");
				}
			});		
		}
	});

	$('#admin_edit_form').find('input[name="profile"]').change(function(e){
		msg($('#admin_edit_form').find("#profile_msg"), "info" ,$(this).val());
	});

	var default_menu = '<?php echo $this->session->flashdata("sub-menu");?>';
	if(default_menu) $.session.set('sub-menu',default_menu);

	$(".first-menu").click(function(){
		$.session.set('sub-menu',this.id); 
	});

	$(".sub-menu").find("li").click(function(){
		$.session.set('sub-menu',this.id); 
	});
	
	if($("#"+$.session.get('sub-menu')).hasClass("first-menu")){
		$("#"+$.session.get('sub-menu')+"-li").addClass("active open");
	}
	else{
		$("#"+$.session.get('sub-menu')).addClass("open");
		$("#"+$.session.get('sub-menu')).parent().parent().addClass("active open");	
	}
});

function member_edit(id,form){
	$("#"+form).find("#profile_msg").html('');
	$.getJSON("/adminmember/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			switch(key){
				case "type":
					if(val=='admin'){
						$(".admin_field").show();
					}
					else{
						$(".admin_field").hide();
					}
					if(val=='biz'){
						$(".biz_field").show();
					}
					else{
						$(".biz_field").hide();
					}

					if(val=='biz' || val=='admin'){
						$(".biz_admin_field").show();
					}
					else{
						$(".biz_admin_field").hide();
					}

					if(val=='general' || val=='biz'){
						$(".general_biz_field").show();
					}
					else{
						$(".general_biz_field").hide();
					}
					break;
				case "sign":
					if(form=="member_edit_form") CKEDITOR.instances.sign_member_edit.setData(val);
					if(form=="admin_edit_form") CKEDITOR.instances.sign_admin_edit.setData(val);
					break;
				case "profile":
					if(val!="" && val!=null){
						$("#"+form).find('#profile_img_name').val(val);
						$("#"+form).find('#profile_img').html("<img src='/uploads/member/"+val+"' style='width:60px;height:60px;'>");
					} else {
						$("#"+form).find('#profile_img').html("<img src='/assets/common/img/no_human.png' style='width:60px;height:60px;'>");
					}
					break;
				case "watermark":
					if(val!="" && val!=null){
						$("#"+form).find('#watermark_img_name').val(val);
						$("#"+form).find('#watermark_img').html("<img src='/uploads/member/"+val+"' style='width:200px;height:60px;'>");
					} else {
						$("#"+form).find('#watermark_img').html("등록된 워터마크가 없습니다.");
					}
					break;
				case "permit_area":
					var area_button = "";
					if(val!="" && val!=null){						
						$.each(val, function(index,item){
							area_button += '<button type="button" class="btn btn-default" style="margin:2px 2px;" onclick="$(this).remove()">'+item+' <i class="fa fa-minus-square" style="color:#d84a38"></i><input type="hidden" id="'+index+'" name="permit_area[]" value="'+index+'" /></button>';
						});											
					}
					$("#"+form).find("#"+key).html(area_button);
				default:
					$("#"+form).find("#"+key).val(val);
					if(key=="color"){
						$("#"+form).find("#"+key).css("color","#"+val);
					}
					break;
			}
		});
	});
}
function go(p,w,h){
	product = window.open(p,'product', "width="+w+", height="+h+"");
}

function pay_sms(){
	window.open("/adminpaysms/index","pay_sms","width=400, height=600");
}
</script>

</head>
<body class="page-header-fixed page-quick-sidebar-over-content page-style-square" oncontextmenu="return false"> 
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<a class="navbar-brand first-menu" id="menu-home" href="/adminhome/index"><i class="fa fa-copyright"></i> <?php echo $config->name;?></a>
			<div class="menu-toggler sidebar-toggler hide">
				<!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
			</div>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">
				<li class="dropdown dropdown-user">
					<a href="#" class="dropdown-toggle" style="cursor:default;line-height:19px;">
					<span class="username username-hide-on-mobile" style="color:#fff">
					<i class="icon-envelope"></i> SMS 잔여건수 : <strong><?php echo $config->sms_cnt?>건</strong><button class="btn btn-primary btn-xs vertical-top" style="padding:0px 10px;margin:0px 10px;" onclick="pay_sms()">
					충전</button></span>
					</a>
				</li>
				<li class="dropdown dropdown-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" style="line-height:19px;">
					<!--img alt="" class="img-circle" src="../../assets/admin/layout/img/avatar3_small.jpg"/-->
					<span class="username username-hide-on-mobile">
					<?php echo $this->session->userdata("admin_name");?> </span>
					<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-default">
						<li>
							<a href="#" onclick="member_edit('<?php echo $this->session->userdata("admin_id");?>','admin_edit_form');" data-toggle="modal" data-target="#edit_admin_dialog">
							<i class="icon-user"></i> 정보 수정 </a>
						</li>
						<li>
							<a href="/adminlogin/logout">
							<i class="icon-key"></i> 로그아웃 </a>
						</li>
					</ul>
				</li>
				<li class="dropdown dropdown-quick-sidebar-toggler">
					<a href="https://sites.google.com/site/dungzimanual/" class="dropdown-toggle" target="_blank">
					<i class="fa fa-question-circle"></i>
					</a>
				</li>				
				<!-- END USER LOGIN DROPDOWN -->
				<!-- BEGIN QUICK SIDEBAR TOGGLER -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				<!--li class="dropdown dropdown-quick-sidebar-toggler">
					<a href="javascript:;" class="dropdown-toggle">
					<i class="icon-logout"></i>
					</a>
				</li-->
				<!-- END QUICK SIDEBAR TOGGLER -->
			</ul>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>


<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
				<li class="sidebar-toggler-wrapper">
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler">
					</div>
					<!-- END SIDEBAR TOGGLER BUTTON -->
				</li>
				<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
				<li class="heading">
					<h3 class="uppercase">메뉴</h3>
				</li>
				<!--li class="start active open"-->
				<?php
				if($this->session->userdata("auth_home")=="Y"){?>
				<li id="menu-home-li">
					<a href="/adminhome/index">
					<i class="icon-home"></i>
					<span class="title first-menu" id="menu-home"><?php echo lang("menu.home");?></span>
					<!--span class="selected"></span-->
					</a>
				</li>
				<?php 
				}
				if($this->session->userdata("auth_product")=="Y"){?>
				<li>
					<a href="javascript:;">
					<i class="icon-wallet"></i>
					<span class="title"><?php echo lang("product");?></span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<li id="menu-product-1"><a href="/adminproduct/index"><?php echo lang("product");?> 관리</a></li>
						<?php if($this->session->userdata("auth_id")==1){?>
						<li id="menu-product-2"><a href="/admincategory/index"><?php echo lang("product.category");?> 관리</a></li>
						<li id="menu-product-3"><a href="/admintheme/index"><?php echo lang("product.theme")?> 관리</a></li>
							<?php if($config->INSTALLATION_MENU_FLAG){?>
							<li id="menu-product-4"><a href="/admininstallation/index"><?php echo lang("installation")?> 관리</a></li>
							<?php }?>
						<?php }?>
					</ul>
				</li>
				<?php 
				}
				if($this->session->userdata("auth_member")=="Y"){?>
				<li>
					<a href="javascript:;">
					<i class="icon-user"></i>
					<span class="title">사이트회원</span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<?php if($config->MEMBER_TYPE=="general" || $config->MEMBER_TYPE=="both"){?>
						<li id="menu-member-1"><a href="/adminmember/index/general">일반회원</a></li>
						<?php }?>
						<?php if($config->MEMBER_TYPE=="biz" || $config->MEMBER_TYPE=="both"){?>
						<li id="menu-member-2"><a href="/adminmember/index/biz">사업자회원</a></li>
						<?php }?>
						<?php if($this->session->userdata("auth_id")==1){?>
						<li id="menu-member-3"><a href="/adminmember/index/admin">직원</a></li>
						<li id="menu-member-4"><a href="/adminauth/index">직원 권한그룹 관리</a></li>
				        <?php }?>
					</ul>
				</li>
				<?php
				}
				if($this->session->userdata("auth_contact")=="Y"){
				?>
				<li>
					<a href="javascript:;">
					<i class="icon-user"></i>
					<span class="title">고객 관리</span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<li id="menu-contact-1"><a href="/admincontact/index">고객</a></li>
						<li id="menu-contact-2"><a href="/admincontact/history">변경이력</a></li>
					</ul>
				</li>
				<?php
				}
				if($this->session->userdata("auth_request")=="Y"){?>
				<li>
					<a href="javascript:;">
					<i class="icon-user"></i>
					<span class="title"><?php echo lang("enquire");?></span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<li id="menu-enquire-1"><a href="/adminenquire/index"><?php echo lang("enquire.title");?></a></li>
						<li id="menu-enquire-2"><a href="/adminask/index"><?php echo lang("qna_title");?></a></li>
						<li id="menu-enquire-3"><a href="/adminenquire/history">변경이력</a></li>
						<li id="menu-enquire-4"><a href="/adminenquire/calendar">업무달력</a></li>
						<li id="menu-enquire-5"><a href="/adminenquire/status">의뢰하기 상태설정</a></li>
						<?php if($config->BUILDING_ENQUIRE){?>
						<li id="menu-enquire-6"><a href="/adminbuilding/building_enquire">건축물자가진단 의뢰</a></li>
						<?php }?>
					</ul>
				</li>
				<?php 
				}
				if($this->session->userdata("auth_news")=="Y"){?>
				<li>
					<a href="javascript:;">
					<i class="fa fa-quote-left"></i>
					<span class="title">뉴스</span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
					  <li id="menu-news-2"><a href="/adminnewscategory/index">뉴스카테고리</a></li>
					  <li id="menu-news-3"><a href="/adminnews/index">뉴스관리</a></li>
					</ul>
				</li>
				<?php 
				}
				if($this->session->userdata("auth_portfolio")=="Y"){?>
				<li>
					<a href="javascript:;">
					<i class="fa fa-file-image-o"></i>
					<span class="title">갤러리</span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
					  <li id="menu-portfolio-1"><a href="/adminportfoliocategory/index">카테고리</a></li>
					  <li id="menu-portfolio-2"><a href="/adminportfolio/index">항목관리</a></li>
					</ul>
				</li>
				<?php 
				}
				if($this->session->userdata("auth_custom")=="Y"){?>
				<li>
					<a href="javascript:;">
					<i class="icon-pointer"></i>
					<span class="title">커스텀</span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<li id="menu-custom-1"><a href="/adminspot/index">지도위치 바로가기</a></li>
						<li id="menu-custom-2"><a href="/adminloan/index">매매 대출 관리</a></li>
					</ul>
				</li>
				<?php 
				}
				if($this->session->userdata("auth_popup")=="Y"){?>
				<li>
					<a href="javascript:;">
					<i class="fa fa-bullhorn"></i>
					<span class="title"><?php echo lang("menu.customercenter");?></span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<li id="menu-helpdesk-1"><a href="/adminnotice/index"><?php echo lang("menu.notice");?></a></li>
						<li id="menu-helpdesk-2"><a href="/adminfaq/index"><?php echo lang("menu.faq");?></a></li>
					</ul>
				</li>
				<!--<li id="menu-ad-li">
					<a href="javascript:;">
					<i class="fa fa-money"></i>
					<span class="title">광고</span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<li id="menu-ad-1"><a href="/adminad/keyword">광고 키워드</a></li>
						<li id="menu-ad-2"><a href="/adminad/app">앱 키워드</a></li>
					</ul>
				</li>-->
				<?php 
				}
				if($this->session->userdata("auth_layout")=="Y"){?>
				<li>
					<a href="javascript:;">
					<i class="icon-puzzle"></i>
					<span class="title">레이아웃 설정</span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<li id="menu-layout-1"><a href="/adminfront/index">메인페이지 설정</a></li>
						<!--<li id="menu-layout-2"><a href="/adminskin/index">스킨 관리</a></li>-->
						<li id="menu-layout-3"><a href="/adminmenu/index">메인메뉴 관리</a></li>
						<li id="menu-layout-4"><a href="/adminintro/index">회사소개메뉴 관리</a></li>
						<li id="menu-layout-5"><a href="/adminservice/index">서비스 소개</a></li>
						<li id="menu-layout-6"><a href="/adminskin/select" target="_blank">스킨 선택</a></li>
						<!--<li id="menu-layout-6"><a href="/admintemplate/index" target="_blank">템플릿 관리</a></li>-->
					</ul>
				</li>
				<?php 
				}
				if($this->session->userdata("auth_set")=="Y"){?>
				<li>
					<a href="javascript:;">
					<i class="icon-settings"></i>
					<span class="title">설정</span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<li id="menu-set-1"><a href="/adminhome/config_edit">공통설정</a></li>
						<li id="menu-set-2"><a href="/adminproductconfig/index"><?php echo lang("product");?> 폼 설정</a></li>
						<li id="menu-set-3"><a href="/adminnewsconfig/index">뉴스설정</a></li>
						<li id="menu-set-4"><a href="/adminblogapi/index">블로그설정</a></li>
						<li id="menu-set-5"><a href="/adminhome/config_etc_edit">로고 & 워터마크</a></li>
						<li id="menu-set-6"><a href="/adminhome/social_link">나의 소셜링크</a></li>
						<li id="menu-set-7"><a href="/admindanzi/index">아파트단지설정</a></li>
						<?php if($config->BUILDING_DISPLAY || $config->BUILDING_ENQUIRE){?>
						<li id="menu-set-8"><a href="/adminbuilding/building_upload">건축물정보 업로드</a></li>
						<?php }?>
						<?php if($config->REGION_USE){?>
						<li id="menu-set-9"><a href="/adminregion/index">지역 사전 설정</a></li>
						<?php }?>
						<li id="menu-set-10"><a href="/adminhome/config_high_edit">고급설정</a></li>
					</ul>
				</li>
				<?php 
				}
				if($this->session->userdata("auth_stats")=="Y"){?>
				<li>
					<a href="javascript:;">
					<i class="icon-bar-chart"></i>
					<span class="title">통계</span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<li id="menu-stats-1"><a href="/adminstats/site">사이트 방문 통계</a></li>
						<!--<li id="menu-stats-2"><a href="/adminstats/blog">블로그 방문 통계</a></li>-->
						<li id="menu-stats-3"><a href="/adminstats/call"><?php echo lang("site.contact");?> 조회 통계</a></li>
						<li id="menu-stats-4"><a href="/adminpaysms/pay_log">문자 충전내역</a></li>
                        <li id="menu-stats-5"><a href="/adminsms/history">문자 발송이력</a></li>
						<li id="menu-stats-6"><a href="/adminad/keyword">키워드광고 추출</a></li>
					</ul>
				</li>
				<?php 
				}
				if($config->USE_PAY && $this->session->userdata("auth_pay")=="Y"){?>
				<li>
					<a href="javascript:;">
					<i class="icon-basket"></i>
					<span class="title">결제 관리</span>
					<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<li id="menu-pay-1"><a href="/adminpay/index">결제 내역</a></li>
						<li id="menu-pay-2"><a href="/adminpay/setting"><?php echo lang("pay");?> 설정</a></li>
					</ul>
				</li>
				<?php }?>
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<?php echo $content_for_layout;?>
		</div>
	</div>
	<!-- END CONTENT -->
  
  
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<?php if($config->DUNGZI=="1"){?>
	<div class="page-footer-inner">
		 2014 &copy; Dungzi.com
	</div>
	<?php }?>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->

<!-- ADMIN UPDATE FORM -->
<?php echo form_open_multipart("adminmember/edit_action",Array("id"=>"admin_edit_form"))?>
<input type="hidden" id="id" name="id">
<input type="hidden" id="profile_img_name" name="profile_img_name">
<input type="hidden" id="watermark_img_name" name="watermark_img_name">
<div id="edit_admin_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">정보 수정</h4>
		</div>
		<div class="modal-body">
			<!--암호를 입력하지 않으면 암호는 변경되지 않습니다-->
			<table class="table table-bordered table-striped-left table-condensed flip-content">
				<tbody>
					<tr>
						<td class="text-center vertical-middle"><?php echo lang("site.email");?></td>
						<td>
							<input type="text" class="form-control input-medium input-inline" id="email" name="email" placeholder="<?php echo lang("site.email");?>" readonly/>
							<input type="text" class="form-control input-medium input-inline" name="pw" placeholder="암호"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">회원명</td>
						<td>
							<input type="text" class="form-control input-large" id="name" name="name" placeholder="이름"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">전화</td>
						<td>
							<input type="text" class="form-control input-medium input-inline" id="phone" name="phone" placeholder="휴대번호"/>
							<input type="text" class="form-control input-medium input-inline" id="tel" name="tel" placeholder="일반번호"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">카카오톡아이디</td>
						<td>
							<input type="text" class="form-control input-large" id="kakao" name="kakao" placeholder="카카오톡아이디"/>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">프로필 사진<br/>(60 * 60 픽셀)</td>
						<td>
							<div id="profile_img"></div>
							<div id="profile_msg"></div>
							<div class="btn btn-default btn-file margin-top-10">프로필 사진 업로드<input type="file" id="profile" name="profile"></div>
							<div id="delete_profile" class="btn btn-primary"><i class="fa fa-trash-o"></i> 프로필 사진 삭제</div>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">워터마크<br/>(200 * 60 픽셀)</td>
						<td>
							<div id="watermark_img"></div>
							<div id="watermark_msg"></div>
							<div class="btn btn-default btn-file help margin-top-10">워터마크 업로드<input type="file" id="watermark" name="watermark"></div>
							<div id="delete_watermark" class="btn btn-primary"><i class="fa fa-trash-o"></i> 워터마크 삭제</div>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">워터마크위치</td>
						<td>
							<select id="watermark_position_vertical" name="watermark_position_vertical" class="form-control input-inline input-small">
								<option value="middle" selected>중앙</option>
								<option value="top">위</option>
								<option value="bottom">아래</option>
							</select>
							<select id="watermark_position_horizontal" name="watermark_position_horizontal" class="form-control input-inline input-small">
								<option value="center" selected>중앙</option>
								<option value="left">왼쪽</option>
								<option value="right">오른쪽</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle">직원 소개글</td>
						<td>
							<textarea class="form-control" id="bio" name="bio" rows="5"></textarea>
						</td>
					</tr>
					<tr>
						<td class="text-center vertical-middle" width="120">시그니쳐</td>
						<td>
							<textarea id="sign_admin_edit" target-dialog="upload_admin_dialog" name="sign_edit" class="form-control" rows="5"></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
				<button type="submit" class="btn btn-primary">수정</button>
			</div>

		</div>
	</div>
</div>
<script>
	CKEDITOR.replace( 'sign_admin_edit', {customConfig: '/ckeditor/agent_config.js'});
</script>
<?php echo form_close();?>
<!-- ADMIN FORM -->

<div id="upload_admin_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;">
	<?php echo form_open_multipart("adminproduct/upload_action","id='upload_admin_form' autocomplete='off'");?>
	<div class="help-block">* 큰 이미지는 넓이(폭)이 890픽셀로 조정됩니다.</div>
	<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
	<?php echo form_close();?>
</div>
<style>
.modal-dialog{ width:98%;max-width: 780px;/* your width */ }
.modal-smsdialog{ width:88%;max-width: 600px;/* your width */ }
</style>