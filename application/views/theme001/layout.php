<!DOCTYPE html>
<html>
<head>
<title><?php echo $config->site_name;?> <?php echo (isset($page_title)) ? "-".$page_title : ""?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="description" content="<?php echo $config->description;?>" />
<meta name="keywords" content="부동산,매물,부동산솔루션,부동산홈페이지,<?php echo $config->keyword;?>" />
<meta name="author" content="둥지" />
<meta name="naver-site-verification" content="<?php echo $config->naverwebmasterkey;?>"/>
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<link href="/assets/plugin/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/assets/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="/assets/plugin/fancybox/source/jquery.fancybox.css" rel="stylesheet">
<link href="/assets/common/css/element.css" rel="stylesheet">
<link href="/assets/common/css/dialogs.css" rel="stylesheet"> <!-- 매물 상세 보기 화면에 대한 스타일 정의 -->
<link href="/assets/basic/css/style.css" rel="stylesheet">
<link href="/assets/common/css/map.css" rel="stylesheet">
<link href="/assets/basic/css/home.css" rel="stylesheet">
<link href="/assets/basic/css/section.css" rel="stylesheet">
<link href="/assets/basic/css/search.css" rel="stylesheet">
<link href="/style/index" rel="stylesheet">
<link href="/assets/plugin/icheck/skins/square/red.css" rel="stylesheet">
<link href="/assets/plugin/nouislider/jquery.nouislider.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/assets/plugin/util/util.carousel.css" media="screen" /> 
<link rel="stylesheet" type="text/css" href="/assets/plugin/util/util.carousel.skins.css" media="screen" /> 

<link href="/assets/theme001/css/theme1.css" rel="stylesheet">

<link href="/assets/common/css/all.css" rel="stylesheet">
<link href="/assets/common/css/hover-min.css" rel="stylesheet">


<!-- 리스팅 CSS -->
<link rel="stylesheet" type="text/css" href="/assets/common/css/listing_<?php echo $config->LISTING?>.css" media="screen" /> 
<link href="/assets/plugin/royalslider/royalslider.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugin/royalslider/skins/default/rs-default.css">

<style>
.modal-dialog{ width:98%;max-width: 1024px;}

<?php if(strstr($this->config->item('bg_image'),'pattern')){?>
.corporate-box{
	background: url(/assets/common/img/bg/skin/<?php echo $this->config->item('bg_image');?>.jpg) 50% 50% / auto repeat scroll;
}
<?php }else{?>
.corporate-box{
	background: url(/assets/common/img/bg/skin/<?php echo $this->config->item('bg_image');?>.jpg) 50% 50% / cover no-repeat fixed;
}
<?php }?>
</style>
<!--[if lt IE 9]>
	<script src="/assets/plugin/html5shiv.js"></script>
    <script src="/assets/plugin/respond.min.js"></script>
<![endif]-->

<script src="/assets/plugin/jquery.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery-migrate.min.js" type="text/javascript"></script>
<script src="/assets/plugin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/assets/plugin/ui-general.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="/assets/plugin/icheck/icheck.min.js" type="text/javascript"></script>

<script src="/assets/plugin/util/jquery.utilcarousel.min.js"></script>
<!-- carousel end -->

<script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=<?php echo $config->DAUM_MAP_KEY;?>&libraries=services"></script>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places&language=ko&region=KR"></script>
<script src="/assets/plugin/jquery.geocomplete.min.js" type="text/javascript" charset="UTF-8"></script>
<script src="/assets/plugin/jquery.form.js" type="text/javascript" charset="UTF-8"></script>
<script src="/assets/plugin/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/assets/common/js/init.js"></script>
<script src="/assets/common/js/util.js"></script>

<script src="/assets/plugin/nouislider/jquery.nouislider.all.min.js"></script>
<script src="/assets/plugin/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->
<script src="/assets/plugin/jquery-leanmodal/jquery.leanModal.min.js"></script>
<script language="javascript">
$(document).ready(function() {

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	$("#modal_login_form").validate({
		rules: {
			siEmail: {  
				required: true
			},
			siPw: {  
				required: true
			}
		},
		errorPlacement: function(error, element){
			element.addClass("error_input");
		}
	});

	$("#modal_login_form").ajaxForm({
		beforeSubmit:function(){
			if (!$("#modal_login_form").valid()) return false;
		},
		success:function(data){
			if(data=="1"){
				window.location.reload();
			} else if(data=="0"){
				msg($("#login_error_msg"), "danger" ,"비밀번호가 틀립니다. 다른 비밀번호를 입력하시거나 비밀번호 찾기를 해주세요.");
			} else if(data=="2"){
				msg($("#login_error_msg"), "danger" ,"관리자에 의해 로그인이 거부되었습니다.");
			} else if(data=="3"){
				msg($("#login_error_msg"), "danger" ,"접근이 허용된 IP에서 로그인 해주세요.");
			} else if(data=="4"){
				msg($("#login_error_msg"), "danger" ,"로그인 기간이 만료 되었습니다.");
			} else {
				msg($("#login_error_msg"), "danger" ,"해당 이메일로 가입된 회원이 없습니다.회원가입을 해주세요.");
			}
		}
	});

	init_category();
	link_init();
	login_leanModal();
	UIGeneral.init();
});

var popupStatus = 0;

function check(){
	$(".checkbox").each(function(){
			if(this.checked){
				$(this).parent().addClass("on");				
			} else {
				$(this).parent().removeClass("on");			
			}
	});
}

function init_category(){
		check();
		$(".checkbox").click(function(){
			check();
		});
}

function lazy(){
	$("div.lazy").lazyload({
	  failure_limit : 10,
      effect : "fadeIn",
	  /*effectspeed : 100,*/
	  skip_invisible : false
	});
}

function login_leanModal(){
	$('.leanModal').leanModal({
		top : 300,
		closeButton: ".modal_close"
	});
}

function facebook_link(url,image,title,summary){
	var facebook_url = "http://www.facebook.com/sharer.php?s=100&passerby=1&";
	facebook_url += "&p[url]="+encodeURIComponent(url);
	facebook_url += "&p[images][0]="+encodeURIComponent(image);
	facebook_url += "&p[title]="+encodeURIComponent(title);
	facebook_url += "&p[summary]="+encodeURIComponent(summary);

	facebook_url += "&u="+encodeURIComponent(url);
	facebook_url += "&imgname="+encodeURIComponent(image);
	facebook_url += "&t="+encodeURIComponent(title);
	facebook_url += "&msg="+encodeURIComponent(summary);
	window.open(facebook_url,"_blank");
}
</script>
<?php
$url = urlencode("http://". HOST . "/") ;
$icon = urlencode("http://". HOST . "/assets/common/img/app.png") ;
$title = urlencode($config->name);
?>
<?php require_once(HOME.'/uploads/script/logs.php');?>
</head>
<body class="corporate <?php if($this->config->item('wide_type')=='box') echo "corporate-box";?>" oncontextmenu="return false">
	<div class="wide-wrap <?php if($this->config->item('wide_type')=='box') echo "wide-wrap-box";?>">
		<!-- BEGIN MODAL-LOGIN -->
		<div id="signup">
			<a class="modal_close pull-right" href="javascript:void(0)"><img src="/assets/common/img/close.png"/></a>
			<div id="signup-ct">
				<div id="signup-header">
					<h2><?php echo lang("menu.login");?></h2>
				</div>
				<form name="modal_login_form" id="modal_login_form" action="/member/signin_action" method="post">
					<div id="login_error_msg"></div>
					<div class="txt-fld">
						<label for="siEmail"><?php echo lang("site.email");?></label>
						<input id="siEmail" name="siEmail" type="text"/>
					</div>
					<div class="txt-fld">
						<label for="siPw"><?php echo lang("site.pw");?></label>
						<input type="password" id="siPw" name="siPw" type="text"/>
					</div>
					<div class="btn-fld text-right">
						<a class="btn btn-link" href="/member/search"><?php echo lang("site.repw");?></a>
						<a class="btn btn-info" href="/member/signup"><strong><i class="fa fa-user-plus"></i> <?php echo lang("menu.signup");?></strong></a>
						<button type="submit" class="btn btn-primary"><strong><i class="fa fa-sign-in"></i> <?php echo lang("menu.login");?></strong></button>
					</div>
				</form>
			</div>
		</div>
		<!-- END MODAL-LOGIN -->

		<!-- BEGIN MODAL-PERMIT AREA -->
		<div id="permit-area">
			<a class="modal_close pull-right" href="javascript:void(0)" style="z-index:10"><img src="/assets/common/img/close.png"/></a>
			<div class="txt-fld">
				<label><i class="fa fa-eye-slash" style="color:red;font-size:22px;"></i> 허용된 지역만 볼 수 있습니다.</label>
			</div>
		</div>
		<!-- END MODAL-PERMIT AREA -->

		<?php echo $top_for_layout;?>

		<?php echo $content_for_layout;?>

		<!-- BEGIN PRE-FOOTER -->
		<div class="pre-footer">
			<div class="footer-menu">
				<div class="_container">
					<div class="row">
						<div class="col-md-3">
							<h4>소개</h4>
							<ul>
								<li><?php echo anchor("main/intro",lang("menu.aboutus"));?></li>
								<li><?php echo anchor("home/rule",lang("menu.uselaw"));?></li>
								<li><?php echo anchor("home/privacy",lang("menu.infolaw"));?></li>
							</ul>
						</div>
						<div class="col-md-3">
							<h4>매물검색</h4>
							<ul>
								<li><a href="/main/map"><?php echo lang("site.map");?> <?php echo lang("site.search");?></a></li>
								<li><a href="/main/grid"><?php echo lang("site.list");?> <?php echo lang("site.search");?></a></li>
							</ul>
						</div>
						<div class="col-md-3">
							<h4><?php echo $config->news_ktitle?></h4>
							<ul>
								<?php foreach($menu_news as $val){?>
								<li><a href="/news/index/<?php echo $val->id?>"><?php echo $val->name?></a></li>
								<?php }?>
							</ul>
						</div>
						<div class="col-md-3">
							<h4><?php echo lang("menu.customercenter");?></h4>
							<ul>
								<li><a href="/notice/index"><?php echo lang("menu.notice");?></a></li>
								<li><a href="/faq/index"><?php echo lang("menu.faq");?></a></li>
							</ul>
						</div>			
					</div>
				</div>	
			</div>			
			<div class="_container padding-top-20">
				<div class="row">
					<!-- BEGIN BOTTOM ABOUT BLOCK -->
					<div class="col-md-3 col-sm-3">
						<a href="/main/intro">
						<?php if($config->footer_logo!=""){?>
						<img class="footer_logo" src="/uploads/logo/<?php echo $config->footer_logo;?>" alt="<?php echo $config->name;?>"/><br/><br/>
						<?php } else {?>
						<?php if($config->logo==""){echo "<img class='footer_logo' src='/assets/common/img/dungzi.png'>";} else {?><img class="footer_logo" src="/uploads/logo/<?php echo $config->logo;?>" alt="<?php echo $config->name;?>"/><?php }?>
						<br/><br/>
						<?php } ?>
						</a>
					</div>
					<!-- BEGIN BOTTOM CONTACTS -->
					<div class="col-md-6 col-sm-6">
						<address class="margin-bottom-35">
						<span  class="help" data-toggle="tooltip" title="<?php echo toeng($config->address);?>"><i class="fa fa-map-marker"></i> <?php echo toeng($config->new_address);?></span> | 
						<?php echo lang("site.ceo");?>: <?php echo $config->ceo;?><br/>
						<?php echo lang("site.biznum");?>: <?php echo $config->biznum;?> 
						<?php if($config->renum!=""){?>| <?php echo lang("site.renum");?>: <?php echo $config->renum;?><?php } ?><br>
						<i class="fa fa-phone"></i> <?php echo lang("site.tel");?>: <?php echo $config->tel;?> | 
						<i class="fa fa-phone"></i> <?php echo lang("site.fax");?>: <?php echo $config->fax;?> <br>
						<?php if($config->email!=""){?><i class="fa fa-envelope"></i> <a href="mailto:<?php echo $config->email;?>"> <?php echo $config->email;?></a><?php }?>	
						<?php if($config->DUNGZI=="1"){?>.powered by <a href="http://www.dungzi.com/" target="_blank">dungzi.com</a><br><?php }?>
						</address>
					</div>
					<div class="col-md-3 col-sm-3 pre-footer-col">
						<a href="/main/intro">
						<img class="footer_map" src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $config->lat?>,<?php echo $config->lng?>&zoom=11&size=250x85&markers=color:red%7C<?php echo $config->lat;?>,<?php echo $config->lng;?>&sensor=false&language=ko&region=KR">
						</a>		  	  
					</div>
				</div><!--row-->
			</div><!--container-->
		</div>
		<!-- END PRE-FOOTER -->

		<!-- BEGIN FOOTER -->
		<div class="footer">
			<div class="_container">
				<div class="row">
					<!-- BEGIN COPYRIGHT -->
					<div class="col-md-12 col-xs-12">
						<ul class="list-unstyled list-inline text-center">
							<?php if(isset($social->naver_cafe) && $social->naver_cafe){?>
							<li><a href="http://<?php echo $social->naver_cafe;?>" target="_blank"><img src="/assets/theme001/img/icon_cafe.png"></a></li>
							<?php } if(isset($social->naver_blog) && $social->naver_blog){?>
							<li><a href="http://<?php echo $social->naver_blog;?>" target="_blank"><img src="/assets/theme001/img/icon_blog.png"></a></li>
							<?php } if(isset($social->facebook) && $social->facebook){?>
							<li><a href="http://<?php echo $social->facebook;?>" target="_blank"><img src="/assets/theme001/img/icon_facebook.png"></a></li>
							<?php } if(isset($social->twitter) && $social->twitter){?>
							<li><a href="http://<?php echo $social->twitter;?>" target="_blank"><img src="/assets/theme001/img/icon_twitter.png"></a></li>
							<?php } if(isset($social->google_plus) && $social->google_plus){?>
							<li><a href="http://<?php echo $social->google_plus;?>" target="_blank"><img src="/assets/theme001/img/icon_plus.png"></a></li>
							<?php } if(isset($social->youtube_channel) && $social->youtube_channel){?>
							<li><a href="http://<?php echo $social->youtube_channel;?>" target="_blank"><img src="/assets/theme001/img/icon_youtube.png"></a></li>
							<?php }?>
						</ul>
					</div>
					<div class="col-md-12 col-xs-12 text-center">
						© <?php echo $config->year?> <?php echo $config->name;?>. All Rights Reserved. <a href="/adminlogin/" target="_blank"><i class="fa fa-cogs"></i></a>
					</div>
					<!-- END COPYRIGHT -->
				</div>
			</div>
		</div>
		<!-- END FOOTER -->

		<!-- 하단에 TOP 버튼 -->
		<script src="/assets/plugin/back-to-top.js" type="text/javascript"></script>
		<script>
		function link_init(obj){
			obj = (obj==undefined) ? $('.view_product') : obj;
			obj.click(function(event){
				$("#modal-body").html("<div id='loading_now'><div class='row'><div class='col-md-12 col-xs-12 text-center' style='margin-top:265px;margin-bottom:265px;'><img src='/assets/common/img/loading_now.gif'/></div></div></div>");
				var id = $(this).attr("data-id");

				$("#modal_id").val(id);

				$.ajax({
					url: "/product/view_modal/",
					type: "POST",
					cache: false,
					data: {
						'id': id
					},
					dataType: "json",
					success: function(data) {
						var member_id = "";
						var lat;	/**  좌표 변수(lat) **/
						var lng;	/** 좌표 변수(lng) **/
						var panorama_url;
						$.each(data,function(key,val){

							if(key=="result"){
								$("#modal-body").html(val);
							
							} else if(key=="product"){
							
								var finished = "";
								var finished_type = (val["type"]=="installation") ? "분양" : "계약";

								if(val["is_finished"]=="1"){
									finished = "<span style=\"font-size:15px;border-radius:5px;color:white;margin-left:3px;padding:0px 5px 2px 3px;line-height:1em;background-color:red;\">"+finished_type+"완료</span>";
								}
								else{
									if(val["is_defer"]=="1"){
										finished = "<span style=\"font-size:15px;border-radius:5px;color:white;margin-left:3px;padding:0px 5px 2px 3px;line-height:1em;background-color:red;\">"+finished_type+"보류</span>";
									}
								}
								
								$("#modal-product-title").html("<span class='title_number'>"+val["id"]+"</span> "+strcut_utf8(val["title"],75)+finished);	
								 
								$("#modal_title").val(val["title"]);

								lat = val["lat"];
								lng = val["lng"];
								panorama_url = val["panorama_url"];

								set_radius(<?php echo $config->RADIUS;?>);  

								position_daum(lat, lng, 4, <?php echo $config->maxzoom;?>);

								member_id=val["member_id"];
								
								view_init('<?php echo $config->PRODUCT_THUMBNAIL_POS;?>');	
								
								$('#view_dialog').modal('handleUpdate');

								<?php if($config->DAUM!="") {?>local("MT1",lat,lng);<?php }?>
								refresh();
							}

							if(key=="first_gallery_id"){
								$("#modal_first_gallery_id").val(val)
							}

							var markers = [];
							if(key=="near_data"){
								$.each(val,function(near_key,neark_val){
									$.each(neark_val,function(near_key2,near_val2){
										markers[near_key2] = new daum.maps.Marker({
											position: new daum.maps.LatLng(near_val2['latitude'], near_val2['longitude']),
											map: map_detail,
											title: near_val2['title']
										});
									});
								});
							}
							var markers = [];
							if(key=="store_data"){
								$.each(val,function(store_key,store_val){
									markers[store_key] = new daum.maps.Marker({
										position: new daum.maps.LatLng(store_val['latitude'], store_val['longitude']),
										map: map_detail,
										title: store_val['title']
									});
								});
							}
						});

						<?php if($config->DAUM!="") {?>local("MT1",lat,lng);<?php }?>

						if(panorama_url){
							$(".rsArrow").css("height","92%");
							$(".rsNavItem:eq(0)").html('<img class="rsTmb" src="/assets/common/img/vr_thumb.png"/><span class="thumbIco"></span>');
						}

						$("#modal-body").slimScroll({
							height: '700px'
						});

						UIGeneral.init();
					}
				});		
			});
			lazy();
		}
		</script>

		<!-- 매물보기 모달 -->
		<div id="view_dialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<input type="hidden" id="modal_id"/>
			<input type="hidden" id="modal_title"/>
			<input type="hidden" id="modal_first_gallery_id"/>
			<div class="modal-dialog">
				<div class="portlet box blue">
					<div class="portlet-title">
						<div class="caption" id="modal-product-title"></div>
						<div class="tools">
							<!--a href="javascript:;" class="collapse" data-original-title="" title=""></a-->
							
							<!--a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""></a-->
							<!--a href="javascript:;" class="reload" data-original-title="" title=""></a-->
							<a href="javascript:;" class="remove close_lg" data-dismiss="modal" data-original-title="" title=""></a>
						</div>
						<div class="actions">
							<a href="#" class="btn btn-default btn-sm" onclick="hope($('#modal_id').val());"><i class="fa fa-heart"></i> <?php echo lang("site.save");?></a>
							<a href="#" class="btn btn-default btn-sm" onclick="print();"><i class="fa fa-file-text-o"></i> 프린트</a>
							<!--
							<div class="btn-group open">
								<a class="btn btn-default btn-sm" href="#" data-toggle="dropdown" aria-expanded="true">
								<i class="fa fa-print"></i> <?php echo lang("site.print");?> <i class="fa fa-angle-down"></i>
								</a>
								
								<ul class="dropdown-menu pull-right" style="z-index:100000000000;">
									<li>
										<a href="#">
										<i class="fa fa-file-image-o"></i> 프린트(사진포함) </a>
									</li>
									<li>
										<a href="#" onclick="print();">
										<i class="fa fa-file-text-o"></i> 프린트(텍스트만) </a>
									</li>
								</ul>
							</div>
							-->
							<div class="btn-group open">
								<a class="btn btn-default btn-sm" href="#" data-toggle="dropdown" aria-expanded="true">
								<i class="fa fa-share-alt"></i> 공유 <i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu pull-right">
									<li>
										<a href="#" onclick="facebook_link('http://<?php echo HOST;?>/product/view/'+$('#modal_id').val(),'http://<?php echo HOST;?>/photo/gallery_image/'+$('#modal_first_gallery_id').val(),$('#modal_title').val(),'');">
										<i class="fa fa-facebook-official"></i> 페이스북 </a>
									</li>
									<li>
										<a href="#" onclick="window.open('http://twitter.com/home?status='+encodeURIComponent($('#modal_title').val())+encodeURIComponent('http://<?php echo HOST;?>/product/view/'+$('#modal_id').val()),'_blank')">
										<i class="fa fa-twitter-square"></i> 트위터 </a>
									</li>
									<li>
										<a href="#" onclick="window.open('https://plus.google.com/share?url=http://<?php echo HOST;?>/product/view/'+$('#modal_id').val(),'_blank');">
										<i class="fa fa-google-plus-square"></i> 구글플러스 </a>
									</li>
								</ul>
							</div>
							<a href="#" class="btn btn-default btn-sm" onclick="window.open('/product/view/'+$('#modal_id').val(),'_blank');"><i class="fa fa-external-link"></i></a>
						</div>
					</div>
					<div class="portlet-body">
						<div id="modal-body"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><strong><?php echo lang("site.close");?></strong></button>
					</div>
				</div>
			</div>
		</div>

		<script src="/script/src/view"></script>
		<link href="/assets/plugin/easy-responsive-tabs.css" rel="stylesheet">
		<script src="/assets/plugin/easyResponsiveTabs.js"></script>
		<script src="/assets/plugin/royalslider/jquery.easing-1.3.js" type="text/javascript"></script>
		<link href="/assets/plugin/royalslider/royalslider.css" rel="stylesheet">
		<link rel="stylesheet" href="/assets/plugin/royalslider/skins/default/rs-default.css"> 
		<script src="/assets/plugin/royalslider/jquery.royalslider.min.js" type="text/javascript"></script>
		<script src="/assets/plugin/jquery.lazyload.js" type="text/javascript"></script>
		<!-- 공지사항 시작 -->
		<div class="modal" id="notice_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog" style="width:98%;max-width: 580px;">
			<div class="modal-content">
			  <div class="modal-header" style="padding:10px;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:5px;"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-circle"></i> 
				<span id="notice_modal_title"></span></h4>
			  </div>
			  <div class="modal-body" style="padding:10px;">
				<div id="notice_modal_content"></div>
			  </div>
			  <div class="modal-footer" style="padding:10px">
				<button type="button" class="btn btn-warning btn-xs" onclick="$('#notice_modal').modal('hide')"><?php echo lang("site.close");?></button>
			  </div>
			</div>
		  </div>
		</div>
		<!-- 공지사항 종료 -->
	</div>

<?php if($config->kakaochat!=""){?>
<!-- 카카오톡 그룹채팅 기능을 설정하면 사용할 수 있다. -->
<div class="kakaochat">
	<a href="http://open.kakao.com/o/<?php echo $config->kakaochat;?>" target="_blank">
	<img src="/assets/common/img/kakaoopenchat.png">
	</a>
</div>
<?php }?>

<!-- 
	자동 로그아웃 기능 : 자동로그아웃을 설정하면 일정 시간이 경과하면 자동으로 로그아웃처리(세션시간과는 다름)
 -->
<iframe  src="/common/autologout" style="display:none;"></iframe>	

<?php if($config->glogkey!=""){ ?>
<!-- 구글 어낼리틱스 코드가 있을 경우 표시되는 영역입니다. -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $config->glogkey;?>', 'auto');
  ga('send', 'pageview');
</script>
<?php } ?>

</body>
</html>