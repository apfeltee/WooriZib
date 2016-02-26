<link rel="stylesheet" type="text/css" href="/assets/plugin/megafolio/css/settings.css" media="screen" /> 
<script type="text/javascript" src="/assets/plugin/megafolio/js/jquery.themepunch.tools.min.js"></script> 
<script type="text/javascript" src="/assets/plugin/megafolio/js/jquery.themepunch.megafoliopro.js"></script>
<script>
	var address_type="front"; /** 공개매물 등록된 주소 가져오도록 **/
</script>
<script type="text/javascript" src="/script/src/search"></script>
<script>

var map;
var markers = [];
var spot;
var marker;
var daum_infowindow;

$(document).ready(function(){

	$('.color-mode >ul > li.color').click(function(){
		loadCSS($(this).attr('data-style'),'');
	});

	$('.color-mode > div > .btn').click(function(){
		$('.color-mode > div > .btn').removeClass("active");
		$(this).addClass("active");
		loadWIDE($(this).attr('data-style'));
	});

	$('.color-mode >ul > li.bg').click(function(){
		loadBG($(this).attr('data-style'));
	});
	setTimeout(function() {
		$('.category-carousel').utilCarousel({
			responsiveMode : 'itemWidthRange',
			indexChanged : function() {
				var height = $(document).scrollTop();
				window.scrollTo(0, height + 1);				
			},
			itemWidthRange : [270, 270]}
		);
		$('.link').utilCarousel({
			responsiveMode : 'itemWidthRange',
			autoPlay: true,
			itemWidthRange : [195, 200]}
		);
	}, 100);

	<?php 
		$this->load->helper('cookie');
		$pop = 0;
		foreach($notice as $val){?>
			<?php if (get_cookie("pop".$val->id)!="done"&&$pop==0 && $val->is_popup=="1") {
				$pop = 1;
			?>
			$('#notice_<?php echo $val->id?>').modal('show');
		<?php }?>
	<?php }?>

	init_mega();

	if($('#colorSelector').length > 0){
		$('#colorSelector').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$('.user_select_li').css('background-color','#'+hex);
				loadCSS(hex,hex);
				$(el).hide('slow');
			},
			flat: true,
			color: "<?php echo ($this->config->item('skin_color'));?>"
		});
	}	

	if($('.user_select_li').length > 0){
		$('.user_select_li').click(function(){
			if($('#colorSelector').css('display')=='none'){
				$('#colorSelector').show('slow');
			}
			else{
				$('#colorSelector').hide('slow');
			}
		});
	}
	var home_section = $(".home_section");
	if(home_section.length > 0){
		home_section.each(function(index){
			if(index%2==0) $(this).addClass("main_color");
			else $(this).addClass("alternative_color");
		});
	}

<?php if($this->session->userdata("skin_control") || $config->IS_DEMO){?>

	var panel = $('.color-panel');

	var setColor = function (color) {
		$('#style-color').attr("href", "/assets/theme/seven/layout/css/themes/" + color + ".css");
	}

	$('.icon-color', panel).click(function () {
		$('.color-mode').show();
		$('.icon-color-close').show();
	});

	$('.icon-color-close', panel).click(function () {
		$('.color-mode').hide();
		$('.icon-color-close').hide();
	});

	$('li.color', panel).click(function () {
		var color = $(this).attr("data-style");
		setColor(color);
		$('.inline li.color', panel).removeClass("current");
		$(this).addClass("current");
	});

	$('li.bg', panel).click(function () {
		var color = $(this).attr("data-style");
		setColor(color);
		$('.inline li.bg', panel).removeClass("current");
		$(this).addClass("current");
	});

<?php }?>

});

function set_search(id,title, lat, lng){
	<?php if($config->STATS=="dong"){?>
		$("#search_type").val("address");
	<?php } else { ?>
		$("#search_type").val("parent_address");
	<?php } ?>
	$("#search_value").val(id);
	$("#search").val(title);
	$("#lat").val(lat);
	$("#lng").val(lng);
	$("#search_form").trigger("submit");
}


function init_mega(){
  var api=jQuery('.megafolio-container').megafoliopro(
  {
    filterChangeAnimation:"rotate",  
    filterChangeSpeed:400,          
    filterChangeRotate:99,          
    filterChangeScale:0.4,          
    delay:10,              
    paddingHorizontal:10,  
    paddingVertical:10,
    layoutarray:[17]

  });  

  jQuery(".fancybox").fancybox();
}


function set_theme(id){
	$("#theme").val(id);
	$("#front_search_form").trigger("submit");	
}

function loadCSS(color,user_color){
	$('#skin_css').remove;
	var cssLink = $("<link rel='stylesheet' type='text/css' media='screen' href='/style/index/"+color+"' id='skin_css'>");
	$("head").append(cssLink);
	$('#skin_color').val(color);
	if(user_color) $('#user_color').val(color);
	else $('#user_color').val('');
}

function loadWIDE(type){
	if(type=='box'){
		$('.bg_image').slideDown();
		$('.corporate').addClass("corporate-box");
		$('.wide-wrap').addClass("wide-wrap-box");
	}
	else{
		$('.bg_image').slideUp();
		$('.corporate').removeClass("corporate-box");
		$('.wide-wrap').removeClass("wide-wrap-box");
	}
	if($("#home_map").length > 0) map.relayout();

	$('#wide_type').val(type);
}

function loadBG(bg){
	$('body').css("background-image","url(/assets/common/img/bg/skin/"+bg+".jpg)");
	if(bg.indexOf('pattern')==0){
		$('body').css("background-repeat","repeat");
		$('body').css("background-size","auto");
		$('body').css("background-attachment","scroll");
	}
	else{
		$('body').css("background-repeat","no-repeat");
		$('body').css("background-size","cover");
		$('body').css("background-attachment","fixed");
	}
	$('#bg_image').val(bg);
}

function set_spot(id,lat,lng){
	$("#search_type").val("google");
	$("#zoom").val("18");
	$("#lat").val(lat);
	$("#lng").val(lng);
	$("#search_form").trigger("submit");
}
</script>

<?php 
	echo $home_layout;
?>

<div class="_container padding-top-20 margin-bottom-20">
		<div class="row">
			<!-- BEGIN BOTTOM ABOUT BLOCK -->
			<div class="col-md-6 col-sm-6 text-center">
				<img src="/assets/theme001/img/mockup.png">
			</div>
			<div class="col-md-6 col-sm-6 padding-top-50">
				<h3>오투오빌의 앱을 다운로드받으세요!</h3>
				<p class="help-block">스마트폰에서 언제 어디서라도 편리하게 검색하세요.</p>
				<br/>
				<?php if($config->GPLAY!=""){?>
				<a href="https://play.google.com/store/apps/details?id=<?php echo $config->GPLAY;?>" target="_blank"><img src="/assets/theme001/img/market_google.png"></a>
				<?php }?>
			</div>
	</div><!--row-->
</div><!--container-->

<!-- BEGIN STYLE CUSTOMIZER -->
<?php
if($this->session->userdata("skin_control") || $config->IS_DEMO){

	$skin_color_kind = array( 
		"c3512f","719430","55606E","7C6853","a81010",
		"2d5c88","222222","333333","734854","2997ab",
		"000000","f0591a","435960",
		"0286B7","2980b9","2c3e50"
	);

	$bg_image_kind = array( 
		"pattern1","pattern2","pattern3","pattern4","pattern5",
		"pattern6","pattern7","pattern8","pattern9","pattern10",
		"back1","back2","back3","back4","back5",
		"back6","back7","back8"
	);
	?>
	<link rel="stylesheet" href="/assets/plugin/colorpicker/css/colorpicker.css" type="text/css" />
	<script type="text/javascript" src="/assets/plugin/colorpicker/js/colorpicker.js"></script>
	<script type="text/javascript" src="/assets/plugin/colorpicker/js/eye.js"></script>
	<script type="text/javascript" src="/assets/plugin/colorpicker/js/utils.js"></script>
	<script type="text/javascript" src="/assets/plugin/colorpicker/js/layout.js?ver=1.0.2"></script>
	<?php echo form_open("adminskin/edit_action","id='skin_form' class='hidden-xs'");?>
	<input type="hidden" id="skin_color" name="skin_color"/>
	<input type="hidden" id="user_color" name="user_color"/>
	<input type="hidden" id="wide_type" name="wide_type"/>
	<input type="hidden" id="bg_image" name="bg_image"/>
	<div class="color-panel hidden-sm">
		<div class="color-mode-icons icon-color"></div>
		<div class="color-mode-icons icon-color-close"></div>
		<div class="color-mode">
			<p>홈페이지 색상 선택</p>
			<ul class="inline">
				<?php
				foreach($skin_color_kind as $skin_color){ ?>
				<li class="color <?php echo ($this->config->item('skin_color')==$skin_color) ? "current" : "";?>" data-style="<?php echo $skin_color;?>" style="background-color:#<?php echo $skin_color;?>;"></li>
				<?php }?>
			</ul>
			<div>
				<div class="inline">
					<ul class="inline" style="display:inline;">
						<li class="color user_select_li <?php echo ($this->config->item('user_color')) ? "current" : "";?>" style="background-color:#<?php echo ($this->config->item('skin_color'));?>;"></li>
					</ul>
					<div class="inline user_select_text">직접선택</div>
				</div>
			</div>
			<div class="colorSelector_wrap">
				<div id="colorSelector"></div>
			</div>			
			<p>홈페이지 형태 선택</p>			
			<div class="text-center" style="margin-bottom:20px;margin-top:10px;">
				<button type="button" class="btn btn-default btn-lg <?php echo ($this->config->item('wide_type')=='wide') ? "active" : "";?>" data-style="wide"><strong>와이드</strong></button>	
				<button type="button" class="btn btn-default btn-lg <?php echo ($this->config->item('wide_type')=='box') ? "active" : "";?>" data-style="box"><strong>박스</strong></button>		
			</div>
			<p class="bg_image">홈페이지 배경 선택</p>
			<ul class="bg_image">
				<?php
				foreach($bg_image_kind as $bg_image){ ?>
				<li class="bg <?php echo ($this->config->item('bg_image')==$bg_image) ? "current" : "";?>" data-style="<?php echo $bg_image;?>" style="background: url(/assets/common/img/bg/skin/<?php echo $bg_image;?>_t.jpg)"></li>
				<?php }?>
			</ul>
				<?php if($config->IS_DEMO){?>
				<button type="button" class="btn btn-default btn-sm btn-block" onclick="alert('데모에서는 적용 하실 수 없습니다.');"><strong><?php echo lang("site.apply");?></strong></button>
				<?php }else{?>
				<button type="submit" class="btn btn-default btn-sm btn-block" onclick="$('#skin_form').submit();"><strong><?php echo lang("site.apply");?></strong></button>
				<?php }?>
		</div>
	</div>
	<script>
	var wide_type = "<?php echo $this->config->item('wide_type');?>";
	if(wide_type=='wide') $('.bg_image').hide();
	$('.color-mode').show();
	$('.icon-color-close').show();
	</script>
	<?php echo form_close();?>
<?php }?>
<!-- END BEGIN STYLE CUSTOMIZER -->

<?php foreach($notice as $val){?>
<div class="modal" id="notice_<?php echo $val->id?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:98%;max-width: 580px;">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:5px;"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-circle"></i> <?php echo $val->title;?></h4>
      </div>
      <div class="modal-body" style="padding:10px;">
        <?php echo $val->content;?>
      </div>
      <div class="modal-footer" style="padding:10px">
		<a href="#" onclick="closeWin('<?php echo $val->id?>');" style="margin-right:10px;"><?php echo lang("site.todaystop");?></a>
		<button type="button" class="btn btn-warning btn-xs" onclick="$('#notice_<?php echo $val->id?>').modal('hide')"><?php echo lang("site.close");?></button>
      </div>
    </div>
  </div>
</div>
<?php }?>