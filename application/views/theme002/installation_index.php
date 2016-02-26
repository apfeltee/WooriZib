<link rel="stylesheet" href="/assets/plugin/royalslider/skins/minimal-white/rs-minimal-white.css"> 
<link rel="stylesheet" href="/assets/common/css/installation.css"> 
<style>

.rsNav {
	border:0px;
}

#full-width-slider {
  width: 100%;
  color: #000;
}
.coloredBlock {
  padding: 12px;
  background: rgba(255,0,0,0.6);
  color: #FFF;
   width: 200px;
   left: 20%;
   top: 5%;
}
.infoBlock {
  position: absolute;
  top: 30px;
  right: 30px;
  left: auto;
  max-width: 40%;
  padding-bottom: 0;
  background: #FFF;
  background: rgba(255, 255, 255, 0.8);
  overflow: hidden;
  padding: 20px;
}
.infoBlockLeftBlack {
  color: #FFF;
  background: #000;
  background: rgba(0,0,0,0.75);
  left: 30px;
  right: auto;
}
.infoBlock h4 {
  font-size: 20px;
  line-height: 1.2;
  margin: 0;
  padding-bottom: 3px;
}
.infoBlock p {
  font-size: 14px;
  margin: 4px 0 0;
}
.infoBlock a {
  color: #FFF;
  text-decoration: underline;
}
.photosBy {
  position: absolute;
  line-height: 24px;
  font-size: 12px;
  background: #FFF;
  color: #000;
  padding: 0px 10px;
  position: absolute;
  left: 12px;
  bottom: 12px;
  top: auto;
  border-radius: 2px;
  z-index: 25; 
} 
.photosBy a {
  color: #000;
}
.fullWidth {
  max-width: 1400px;
  margin: 0 auto 24px;
}

@media screen and (min-width:960px) and (min-height:660px) {
  .heroSlider .rsOverflow,
  .royalSlider.heroSlider {
      height: 421px !important;
  }
}

@media screen and (min-width:960px) and (min-height:1000px) {
    .heroSlider .rsOverflow,
    .royalSlider.heroSlider {
        height: 660px !important;
    }
}
@media screen and (min-width: 0px) and (max-width: 800px) {
  .royalSlider.heroSlider,
  .royalSlider.heroSlider .rsOverflow {
    height: 300px !important;
  }
  .infoBlock {
    padding: 10px;
    height: auto;
    max-height: 100%;
    min-width: 40%;
    left: 5px;
    top: 5px;
    right: auto;
    font-size: 12px;
  }
  .infoBlock h3 {
     font-size: 14px;
     line-height: 17px;
  }
}

</style>
<script src="/assets/plugin/organictabs.jquery.js"></script>
<script>
	var address_type="front"; /** 공개/비공개 매물 등록된 주소 가져오도록 **/
</script>
<script type="text/javascript" src="/assets/basic/js/search_installation.js"></script>
 <script>
      jQuery(document).ready(function($) {

		$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

      	init_search("<?php echo element('type',$search_installation);?>","<?php echo element('category',$search_installation);?>");
		if($('#full-width-slider').children().size()){
			$('#full-width-slider').royalSlider({
				arrowsNav: true,
				loop: true,
				keyboardNavEnabled: true,
				controlsInside: false,
				imageScaleMode: 'fill',
				arrowsNavAutoHide: false,
				autoScaleSlider: true, 
				autoScaleSliderWidth: 960,     
				autoScaleSliderHeight: 250,
				controlNavigation: 'bullets',
				thumbsFitInViewport: false,
				navigateByClick: true,
				startSlideId: 0,
				autoPlay: {
					// autoplay options go gere
					enabled: true,
					delay: 3000,
					pauseOnHover: true
				},
				transitionType:'move',
				globalCaption: false,
				deeplinking: {
				  enabled: true,
				  change: false
				}
			});
		}

	  $('#search_form').ajaxForm( {
	    beforeSubmit: function()
	    {
	      /** map은 여기서 move_map을 했는데 grid는 그럴 필요가 없으니까 그냥 냅두자 **/
	    },
	    success: function(data)
	    {
	      $.ajax({
	        type: 'GET',
	        url: '/installation/listing_json/'+$("#next_page").val(),
	        cache: false,
	        dataType: 'json',
	        beforeSend: function(){
	          $("#next_page").val("0"); /*** 검색 조건이 바뀌면 다시 페이지가 0이 되면서 시작되어야 한다. more가 되면 submit이 아닌 success 된 이후에 ajax만 동작하면 된다. ***/
	          loading_delay(true);
	        },
	        success: function(jsonData){
	          var str = "";
	          $.each(jsonData, function(rkey, rval) {
	            if(rkey=="result") {
	              str = rval;
	            }
	            if(rkey=="total"){
	              total = rval;
	              $(".result_label").html("<i class=\"fa fa-search\"></i> <?php echo lang("search.result");?> " + rval);
	            }

	            if(rkey=="paging"){

	              if(total<=rval){
	                $("#pagination_more").hide();
	              } else if(rval=="0"){
	                $("#pagination_more").hide();
	              } else {
	                $("#pagination_more").show();
	              }
	              
	              next_page = rval;
	              $("#next_page").val(rval);
	            }
	          });
	          
	          if(total=="0"){
	            $("#pagination_more").hide();
	          }

	          if(next_page<10){
	            $("#search-items").html(str);     
	          } else {
	            $("#search-items").append(str);
	          }

	          $('.help').tooltip(); 

	          link_init($('.view_installation'));
	          loading_delay(false);
	          login_leanModal();
	        }
	      });
	    }
	  });

	  $(".search_item").change(function(){
	    $('#search_form').trigger('submit');
	  });

	  $(".search_item").change(function(){
	    $("#next_page").val("0");
	    $('#search_form').trigger('submit');
	  });

	  $('.category_checkbox').on('ifChanged', function(event){
	    $("#next_page").val("0");
	    $("#search_form").trigger("submit");
	  });

	$("#search_tab").organicTabs();

	<?php if( element("search_type",$search_installation) == ""){?>		
		$("#search_form").trigger("submit");
	<?php }?>
});

function more(){
  $('#search_form').trigger('submit');
}
</script>
<!-- file:///C:/developments/extSource/codecanyon-461126-royalslider-touchenabled-jquery-image-gallery/new-rs-9.5.4/templates/full-width/index.html -->
<!-- http://journal.digital-atelier.com/1/ -->

<form action="/installation/set_search/" id="search_form" method="post">
<input type="hidden" id="search_type" name="search_type">
<input type="hidden" id="search_value" name="search_value">
<input type="hidden" id="lat" name="lat">
<input type="hidden" id="lng" name="lng">
<input type="hidden" id="sido_val" name="sido_val" value="<?php echo element("sido_val",$search_installation);?>">
<input type="hidden" id="gugun_val" name="gugun_val" value="<?php echo element("gugun_val",$search_installation);?>">
<input type="hidden" id="dong_val" name="dong_val" value="<?php echo element("dong_val",$search_installation);?>">
<input type="hidden" id="subway_local_val" name="subway_local_val" value="<?php echo element("subway_local_val",$search_installation);?>">
<input type="hidden" id="hosun_val" name="hosun_val" value="<?php echo element("hosun_val",$search_installation);?>">
<input type="hidden" id="station_val" name="station_val" value="<?php echo element("station_val",$search_installation);?>">
<div style="background-image: url('//digital-cdn.net/1/image/data/journal2/pattern/dots.png');background-repeat: repeat;    background-position: center top;background-attachment: scroll;background-color: rgb(228, 228, 228);">
<div class="_container padding-top-20" style="padding-bottom:20px;">
	<div class="row">
		<div class="col-md-9" style="padding-right:5px;">
			<div class="royalSlider heroSlider rsMinW" id="full-width-slider">	
				<?php foreach($installation as $val){
				if(!$val["thumb_name"]) continue;?>
				<a href="/installation/view/<?php echo $val["id"];?>" title="<?php echo $val["title"];?>" target="_blank">
					<div class="rsContent">
						<img class="rsImg" src="/uploads/gallery_installation/<?php echo $val["id"];?>/<?php echo $val["thumb_name"];?>" alt="" />
						<div class="infoBlock infoBlockLeftBlack" data-fade-effect="" data-move-offset="10" data-move-effect="bottom" data-speed="200">						
							<h4><?php echo $val["title"]?></h4>
							<p><?php echo $val["scale"];?></p>
							<div class="address" style="font-size:12px;color:#cacaca;">
								<?php echo toeng(element("address_name",$val))?> 
								<?php echo element("address",$val)?>
							</div>
						</div>
					</div>
				</a>
				<?php }?>
			</div>
		</div>
		<div class="col-md-3" style="padding-left:0px;">
			<?php foreach($favorite as $key=>$val){?>
			<div class="installation_cell" style="<?php if($key>0) echo "margin-top:5px;";?>background-image:url(/photo/gallery_installation_thumb/<?php echo $val["gallery_id"];?>);cursor:pointer;" onclick="window.open('/installation/view/<?php echo $val["id"];?>','_blank');">
				<div class="meta">
					<span class="label label-sm label-danger">인기 <?php echo $key+1;?></span><br/><a href="#"><?php echo $val["title"]?></a>
					<div class="address">
						<?php echo toeng(element("address_name",$val))?> 
						<?php echo element("address",$val)?>
					</div>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
</div>
</div>


<div class="main" style="padding-top:20px;">
  <div class="_container margin-bottom-40">
  	<div class="row">
              <div class="col-md-3">
                  <div class="search-wrapper">

						<div id="search_tab">
							<ul class="nav">								
								<li class="nav-one">
									<a href="#local_section" <?php if(element("search_type",$search_installation)=="" or element("search_type",$search_installation)=="address" or element("search_type",$search_installation)=="parent_address") {echo "class='current'";}?>>지역</a>
								</li>
								<li class="nav-two">
									<a href="#sybway_section" <?php if(element("search_type",$search_installation)=="subway") {echo "class='current'";}?>><?php echo lang("site.subway");?></a>
								</li>
							</ul>							
							<div class="list-wrap">							
								<ul id="local_section"  
									<?php if(element("search_type",$search_installation)=="" or element("search_type",$search_installation)=="address" or element("search_type",$search_installation)=="parent_address") {} else {echo "style='display:none;'";}?>>
									<li>
									<div class="row" style="margin:0px;">
										<div class="col-xs-4" style="padding:0px;">
											<select id="sido" name="sido" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
										</div>
										<div class="col-xs-4" style="padding:0px;">
											<select id="gugun" name="gugun"><option value="">-</option></select>
										</div>
										<div class="col-xs-4" style="padding:0px;">
											<select id="dong" name="dong"><option value="">-</option></select>
										</div>
									</div>
									</li>
								</ul>								 
								<ul id="sybway_section" <?php if(element("search_type",$search_installation)=="subway") {} else {echo "style='display:none;'";}?>>
									<li>
										<div class="row" style="margin:0px;">
											<div class="col-xs-4" style="padding:0px;">
												<select id="subway_local" name="subway_local" onchange="$('#station').html('<option value=\'\'>-</option>');"><option value="">-</option></select>
											</div>
											<div class="col-xs-4" style="padding:0px;">
												<select id="hosun" name="hosun"><option value="">-</option></select>
											</div>
											<div class="col-xs-4" style="padding:0px;">
												<select id="station" name="station"><option value="">-</option></select>
											</div>
										</div>							
									</li>
								</ul>
							</div> <!-- END List Wrap -->
						</div> <!-- search_tab -->

                      <ul>
                        <li>
                          <h3><?php echo lang("installation");?> 종류</h3>
                        </li>
                        <li>
                          <input type="checkbox" name="category[]" id="category_apt" value="apt" class='category_checkbox search_item' >
                          <label> <?php echo lang("installation.category.apt");?></label>
                        </li>
                        <li>
                          <input type="checkbox" name="category[]" id="category_villa" value="villa" class='category_checkbox search_item' >
                          <label> <?php echo lang("installation.category.villa");?></label>
                        </li>   
                        <li>
                          <input type="checkbox" name="category[]" id="category_officetel" value="officetel" class='category_checkbox search_item' >
                          <label> <?php echo lang("installation.category.officetel");?></label>
                        </li> 
                        <li>
                          <input type="checkbox" name="category[]" id="category_city" value="city" class='category_checkbox search_item' >
                          <label> <?php echo lang("installation.category.city");?></label>
                        </li>                            
                        <li>
                          <input type="checkbox" name="category[]" id="category_shop" value="shop" class='category_checkbox search_item' >
                          <label> <?php echo lang("installation.category.shop");?></label>
                        </li>                        
                      </ul>

                  </div>
              </div>
  		<div class="col-md-9">

  			<!-- 매물 그리드 시작 -->
			<div class="margin-bottom-20">
				<div class="loading_content">
					<div class="loading_background"></div>
					<div class="loading_image"><img src="/assets/common/img/load_360.gif"></div>
				</div>
				<div  style="float:left;display:inline;">
					<?php 
						$sch = "";
						if(isset($search_installation["sorting"])){
							$sch = $search_installation["sorting"];
						}
					?>
					<select name="sorting" class="search_item" style="height:24px;border:1px solid #cacaca;">
						<option value="basic" <?php if($sch=="" && "basic"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="basic") {echo "selected";}?>><?php echo lang("sort.recommend");?></option>
						<option value="date_desc" <?php if($sch=="" && "date_desc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="date_desc") {echo "selected";}?>>최신 등록순</option>
						<option value="date_asc" <?php if($sch=="" && "date_asc"==$config->DEFAULT_SORT) {echo "selected";} else if($sch=="date_asc") {echo "selected";}?>>최신 등록 역순</option>
					</select>
				</div>
				<div class="result_label" style="float:right;display:inline;padding:0px;"></div>
	  			<div style="clear:both;"></div>
  				<div id="search-items"></div>
				<div style="clear:both;"></div>
				<div style="padding:10px;text-align:center;">
					<input type="hidden" id="next_page" value="0" autocomplete="off"/>
					<button type="button" id="pagination_more" class="btn btn-default" style="width:30%;" onclick="more();"><i class="fa fa-chevron-circle-down"></i> <?php echo lang("site.more");?></button>
				</div>
				<div style="clear:both;"></div> 
			</div>
			<!-- 매물 그리드 종료 -->
  		</div><!-- col-md-9 -->
  	</div >
  </div>
</div>