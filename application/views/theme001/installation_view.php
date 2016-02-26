<link href="/assets/plugin/easy-responsive-tabs.css" rel="stylesheet">
<script src="/assets/plugin/easyResponsiveTabs.js"></script>
<link rel="stylesheet" href="/assets/common/css/installation.css"> 

<?php 

/**
MT1 대형마트
CS2 편의점
PS3 어린이집, 유치원
SC4 학교
AC5 학원
SW8 지하철역
BK9 은행
CT1 문화시설
PO3 공공기관
AT4 관광명소
FD6 음식점
CE7 카페
HP8 병원
PM9 약국
**/
$code = Array(
	"MT1"=>"대형마트",
	"CS2"=>"편의점",
	"PS3"=>"어린이집, 유치원",
	"SC4"=>"학교",
	"SW8"=>"지하철역",
	"BK9"=>"은행",
	"CT1"=>"문화시설",
	"PO3"=>"공공기관",
	"AT4"=>"관광명소",
	"HP8"=>"병원",
	"PM9"=>"약국"
	);

	
?>
<script>
$(document).ready(function(){

    
    	/*** 평면도 클릭했을 때 크게 보이기 위해서 추가하였다. ***/
	$(".fancy").fancybox({
	      helpers: {
	          title : {
	              type : 'float'
	          }
	      }
	});

	<?php if($config->DAUM!="") {?>local("MT1", "<?php echo $query->lat;?>", "<?php echo $query->lng;?>");<?php }?>

	set_radius(<?php echo $config->RADIUS;?>); 
	position_daum("<?php echo $query->lat;?>", "<?php echo $query->lng;?>", 4, <?php echo $config->maxzoom;?>);

	view_init('<?php echo $config->PRODUCT_THUMBNAIL_POS;?>'); 

	var markers = [];

	<?php 
	if(isset($near_data)){
		foreach($near_data as $key=>$val){
			foreach($val as $near_key=>$near){
	?>
		var index = <?php echo $near_key?>;
		var title = '<?php echo $near->title;?>';
		var lat = <?php echo $near->latitude?>;
		var lng = <?php echo $near->longitude?>;
		markers[index] = new daum.maps.Marker({
			position: new daum.maps.LatLng(lat, lng),
			map: map_detail,
			title: title
		});

	<?php
			}
		}
	}?>
});
</script>
<div class="<?php echo (MobileCheck()) ? "container" : "_container"?> main">
    <div class="row">
        <div class="col-md-9 col-xs-12">
			<div class="property_content shadow-border">

				<h3 style="font-weight:bold;margin-top:10px;padding-left:5px;"><?php echo $query->title?></h3>
					
				<?php if(count($gallery)>0){?>
				<div style="padding:10px;border:1px solid #cacaca;margin:10px 5px 10px 5px;">

				<!-- 갤러리 시작 : http://dimsemenov.com/plugins/royal-slider/documentation/#basic-usage-->
				<div id="gallery-1" class="royalSlider rsDefault">
					<?php foreach($gallery as $key=>$val){?>
						<a class="rsImg bugaga" data-rsBigImg="/photo/gallery_installation_image/<?php echo $val->id;?>" href="/photo/gallery_installation_image/<?php echo $val->id;?>" <?php if($key==0) {echo "data-rsVideo=\"".$query->video_url."\"";}?>>
							<img class="rsTmb" src="/photo/gallery_installation_thumb/<?php echo $val->id;?>" />
							<p><?php echo $val->content;?></p>
						</a>
					<?php }?>
				  </div>
				  <!-- 갤러리 종료 -->
				  <?php }?>
				 </div>
				<span id="print_area">
					
					<table class="border-table">
						<tr>
							<th width="20%"><?php echo lang("site.address");?></th>
							<td width="80%" colspan="3">
								<?php 
									echo toeng($query->address_name) . " ";
									echo $query->address;
								?>
							</td>
						</tr>
						<?php if(isset($near_data)){?>
							<?php foreach($near_data as $key=>$val){?>
							<tr>
								<th width="20%"><?php echo $key;?></th>
								<td width="80%" colspan="3">
									<?php foreach($val as $near){?>
									<span class="near" title="<?php echo $near->title?>"><?php echo $near->title?></span> <?php echo round($near->distance,1)?> ㎞
									<?php }?>
								</td>
							</tr>							
							<?php }?>
						<?php }?>							
						<tr>
							<th width="20%">종류</th>
							<td width="80%" colspan="3">
								<?php	echo lang("installation.category.".$query->category);	?>
							</td>
						</tr>
					<?php if($query->scale!="") {?>
					<tr>
						<th width="20%">규모</th>
						<td width="80%" colspan="3"><?php echo $query->scale;?></td>
					</tr>
					<?php }?>
					<?php if($query->notice_year!="" || $query->enter_year!="") {?>
					<tr>
						<th width="20%">공고/입주 시기</th>
						<td width="80%" colspan="3">
							<?php echo $query->notice_year;?> / <?php echo $query->enter_year;?>
						</td>
					</tr>
					<?php }?>
					<?php if($query->tel!="") {?>
					<tr>
						<th width="20%"><?php echo lang("site.contact");?></th>
						<td width="80%" colspan="3">
							<?php echo $query->tel;?>
						</td>
					</tr>
					<?php }?>						
					<?php if($query->builder!="") {?>
					<tr>
						<th width="20%">건설사</th>
						<td width="80%" colspan="3">
						<?php echo $query->builder;?>
						<?php if($query->builder_url!=""){?><a href="http://<?php echo $query->builder_url;?>" target="_blank"><?php echo $query->builder_url;?></a><?php }?>
						</td>
					</tr>
					<?php }?>						
					<?php if($query->heating!="") {?>
					<tr>
						<th width="20%">난방</th>
						<td width="80%" colspan="3"><?php echo $query->heating;?></td>
					</tr>
					<?php }?>
					<?php if($query->park!="") {?>
					<tr>
						<th width="20%">주차</th>
						<td width="80%" colspan="3"><?php echo $query->park;?></td>
					</tr>
					<?php }?>
					<?php if($query->bank!="") {?>
					<tr>
						<th width="20%">청약가능통장</th>
						<td width="80%" colspan="3"><?php echo $query->bank;?></td>
					</tr>
					<?php }?>
					<?php if($query->is_presale!="") {?>
					<tr>
						<th width="20%">전매가능여부</th>
						<td width="80%" colspan="3">
							<?php echo ($query->is_presale=="0") ? "불가능" : "가능";?>
						</td>
					</tr>
					<?php }?>
					</table>

					<?php if(count($pyeong)>0){//print 용도?>
					<div class="is_print" style="display:none">
						<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 평형정보</h4>
						<?php foreach($pyeong as $key=>$val){?>
						<div style="margin-bottom:20px;">
							<div style="display:inline;float:left;width:50%">
							<?php 
							if($val->filename!=""){
								$temp = explode(".",$val->filename);
							?>
								<img src="/uploads/pyeong/<?php echo $val->installation_id?>/<?php echo $temp[0]."_thumb.".$temp[1];?>" style="max-width:100%;max-height:230px;">
							<?php } else {?>
								<img src="/assets/common/img/no_thumb.png" style="max-width:100%;max-height:230px;">
							<?php }?>
							</div>
							<div style="display:inline;float:left;width:50%;text-align:center;">
								<table class="border-table" style="margin:0px 0px 0px 10px;">
									<colgroup>
										<col width="30%"/>
										<col width="70%"/>
									</colgroup>
									<tr>
										<td colspan="2" style="text-align:center;font-weight:bold;font-size:16px;"><?php echo $val->name;?>㎡</td>
									</tr>
									<tr>
										<th width="30%">분양세대수</th>
										<td width="70%" style="text-align:right"><?php echo $val->cnt;?> 세대</td>
									</tr>
									<tr>
										<th>분양가</th>
										<td style="text-align:right"><?php echo $val->price_min;?> ~ <?php echo $val->price_max;?>만원</td>
									</tr>
									<tr>
										<th>취득세</th>
										<td style="text-align:right"><?php echo $val->tax;?> 만원</td>
									</tr>
									<tr>
										<th>전용/공급</th>
										<td style="text-align:right"><?php echo $val->real_area;?> ㎡ / <?php echo $val->law_area;?> ㎡</td>
									</tr>
									<tr>
										<th>대지지분</th>
										<td style="text-align:right"><?php echo $val->road_area;?> ㎡</td>
									</tr>
									<tr>
										<th>현관</th>
										<td style="text-align:right"><?php echo $val->gate;?></td>
									</tr>
									<tr>
										<th>방/욕실</th>
										<td style="text-align:right"><?php echo $val->bedcnt;?> / <?php echo $val->bathcnt;?></td>
									</tr>
									<tr>
										<th>전매기간</th>
										<td style="text-align:right"><?php echo $val->presale_date;?></td>
									</tr>
									<?php if($val->description){?>
									<tr>
										<td colspan="2"><?php echo $val->description;?></td>
									</tr>
									<?php }?>
								</table>
							</div>
							<div style="clear:both;"></div>
						</div>
						<?php }?>
					</div>
					<?php }?>
					
					<?php if(count($schedule)>0){//print 용도?>
					<div class="is_print" style="display:none">
						<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 분양일정</h4>
						<table class="border-table" style="margin:0px 0px 0px 10px;">
							<colgroup>
								<col width="30%"/>
								<col width="15%"/>
								<col width="*"/>
							</colgroup>
							<?php foreach($schedule as $val){?>
							<tr>
								<th><?php echo $val->name?></th>
								<td><?php echo $val->date?></td>
								<td><?php echo $val->description?></td>
							</tr>
							<?php }?>
						</table>
					</div>
					<?php }?>

					<div>
						<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 설명</h4>
						<table class="border-table">
							<tr>
								<th width="20%">설명</th>
								<td width="80%" colspan="3">
									<span class="help-inline"><?php echo strip_tags(str_replace("&nbsp;","",$query->content),"<p><br>");?></font></span>
								</td>
							</tr>									
							<tr>
								<th width="20%">키워드</th>
								<td width="80%">
									<span class="help-inline"><?php echo $query->tag;?></span>
								</td>								
							</tr>
							<tr>
								<th width="20%">날짜</th>
								<td width="80%" colspan="3">
									<span class="help-inline"><?php echo $query->date;?></span>
								</td>								
							</tr>
						</table>
													
						<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> <?php echo lang("product.owner");?></h4>
						<table class="border-table">
							<tr>
								<th width="20%"><?php echo lang("site.name");?></th>
								<td width="30%">
									<?php 
									if( $member->biz_name!="" ) {
										echo "[".$member->biz_name."] ";
									}
									?>
									<?php echo $member->name;?>
								</td>
								<th width="20%"><?php echo lang("site.contact");?></th>
								<td width="30%">
									<?php echo $member->phone;?>
								</td>								
							</tr>
						</table>
					</div>

				</span><!-- print_area -->
				
				<br/>
				
					<div class="row">
						<div class="col-md-7 installation_pyeong">
							<?php if( count($pyeong) > 0 ) {?>
							<!-- Nav tabs -->
							<ul class="nav nav-tabs" role="tablist">
							<?php foreach($pyeong as $key=>$val){?>
							    	<li role="presentation" <?php if($key=="0"){ ?>class="active"<?php }?>><a href="#pyeong_<?php echo $val->id;?>" aria-controls="pyeong_<?php echo $val->id;?>" role="tab" data-toggle="tab"><?php echo $val->name;?> ㎡</a></li>
							<?php  	}  ?>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
							<?php foreach($pyeong as $key=>$val){ $temp = explode(".",$val->filename); ?>
							    	<div role="tabpanel" class="tab-pane <?php if($key=="0"){ ?>active<?php }?>" id="pyeong_<?php echo $val->id;?>" style="padding:10px;">

						    			<div class="row">
						    				<div class="col-md-6">
											<?php 
											if($val->filename!=""){
												$temp = explode(".",$val->filename);
											?>
												<a href="/uploads/pyeong/<?php echo $val->installation_id?>/<?php echo $val->filename;?>" class="fancy"><img src="/uploads/pyeong/<?php echo $val->installation_id?>/<?php echo $temp[0]."_thumb.".$temp[1];?>" style="max-width:100%;max-height:230px;"></a>
											<?php } else {?>
												<a href="/assets/common/img/no.png" class="fancy"><img src="/assets/common/img/no_thumb.png" style="max-width:100%;max-height:230px;"></a>
											<?php }?>
						    				</div>
						    				<div class="col-md-6">
											<table width="100%" class="table table-striped table-hover table-condensed">
												<tr>
													<td>분양세대수</td><td class="text-right"><?php echo $val->cnt;?> 세대</td>
												</tr>
												<tr>
													<td>분양가</td><td class="text-right"><?php echo $val->price_min;?> ~ <?php echo $val->price_max;?>만원</td>
												</tr>
												<tr>
													<td>취득세</td>	<td class="text-right"><?php echo $val->tax;?> 만원</td>
												</tr>
												<tr>
													<td>전용/공급</td><td class="text-right"><?php echo $val->real_area;?> ㎡ / <?php echo $val->law_area;?> ㎡</td>
												</tr>
												<tr>
													<td>대지지분</td><td class="text-right"><?php echo $val->road_area;?> ㎡</td>
												</tr>
												<tr>
													<td>현관</td><td class="text-right"><?php echo $val->gate;?></td>
												</tr>
												<tr>
													<td>방/욕실</td><td class="text-right"><?php echo $val->bedcnt;?> / <?php echo $val->bathcnt;?></td>
												</tr>
												<tr>														
													<td>전매기간</td><td class="text-right"><?php echo $val->presale_date;?></td>
												</tr>													
												<tr>
													<td colspan="2"><?php echo $val->description;?></td>
												</tr>
											</table>
						    				</div>
						    			</div>

							    	</div> <!-- tabpanel -->
								<?php 	}?>
							</div><!-- tab-content -->
							<?php  	}  ?> <!-- 평형 정보가 있을 경우에만 보여준다. -->
						</div><!-- col-md-7 -->
						<div class="col-md-5">
							<?php if(count($schedule)>0){?>
							<h4><i class="fa fa-clock-o"></i> 분양일정</h4>
							<table class="table table-condensed">
								<?php foreach($schedule as $val){?>							
								<tr>									
									<th width="30%"><?php echo $val->name?></th>
									<td width="30%"><?php echo $val->date?></td>
									<td width="*"><?php echo $val->description?></td>
								</tr>
								<?php } ?>
							</table>
							<?php } ?>
						</div>
					</div>

					<br/>

						<?php if($this->config->item('view_map_position')=='bottom'){?>
							<div class="property_content_inner">
							<?php echo $query->content;?>
							<?php echo $member->sign;?>
							</div>

							<!-- daum map START -->
							<div id="container" class="view_map margin-bottom-20 <?php if(!$this->config->item('view_map_use')) echo "display-none"?>">
								<div id="mapWrapper" style="width:100%;height:500px;position:relative;">
									<div id="point_map" style="width:100%;height:100%"></div> <!-- 지도를 표시할 div 입니다 -->
									<button type="button" id="btnRoadview" class="btn btn-lg" onclick="toggleMap(false)" style="color:#fff;background-color:#<?php echo $this->config->item('skin_color')?>"><i class="fa fa-street-view"></i> <?php echo lang("site.roadview");?></button>
								</div>
								<div id="rvWrapper" style="width:100%;height:500px;position:absolute;top:0;left:0;">
									<div id="roadview" style="height:100%"></div> <!-- 로드뷰를 표시할 div 입니다 -->
									<button type="button" id="btnMap" class="btn btn-lg" onclick="toggleMap(true)" style="color:#fff;background-color:#<?php echo $this->config->item('skin_color')?>"><i class="fa fa-map-marker"></i> <?php echo lang("site.map");?></button>
								</div>
							</div>
							<!-- daum map END -->

						<?php } else {?>

							<!-- daum map START -->
							<div id="container" class="view_map margin-bottom-20 <?php if(!$this->config->item('view_map_use')) echo "display-none"?>">
								<div id="mapWrapper" style="width:100%;height:500px;position:relative;">
									<div id="point_map" style="width:100%;height:100%"></div> <!-- 지도를 표시할 div 입니다 -->
									<button type="button" id="btnRoadview" class="btn btn-lg" onclick="toggleMap(false)" style="color:#fff;background-color:#<?php echo $this->config->item('skin_color')?>"><i class="fa fa-street-view"></i> <?php echo lang("site.roadview");?></button>
								</div>
								<div id="rvWrapper" style="width:100%;height:500px;position:absolute;top:0;left:0;">
									<div id="roadview" style="height:100%"></div> <!-- 로드뷰를 표시할 div 입니다 -->
									<button type="button" id="btnMap" class="btn btn-lg" onclick="toggleMap(true)" style="color:#fff;background-color:#<?php echo $this->config->item('skin_color')?>"><i class="fa fa-map-marker"></i> <?php echo lang("site.map");?></button>
								</div>
							</div>
							<!-- daum map END -->

							<div class="property_content_inner">
							<?php echo $query->content;?>
							<?php echo $member->sign;?>
							</div>
						<?php }?>
						
						<?php if($config->DAUM!=""){?>
						<div id="htab" class="margin-top-20 margin-bottom-20">
							<ul class="resp-tabs-list hor_1">
								  <?php foreach($code as $key=>$val){?>
								  <li data-key="<?php echo $key;?>"><a href="#<?php echo $key;?>" data-key="<?php echo $key;?>" data-toggle="tab" data-lat='<?php echo $query->lat;?>' data-lng='<?php echo $query->lng;?>' style="padding:15px 5px;"><?php echo $val;?></a></li>
								  <?php }?>
							</ul>
							<div class="resp-tabs-container hor_1">
								<?php foreach($code as $key=>$val){?>
								<div>
									<div id="<?php echo $key;?>"></div>
								</div>
								<?php }?>
							</div>
						</div>
						<?php }?>
						
			</div> <!-- property_content -->

        </div> <!-- col-md-9 col-xs-12 -->

		<div class="col-md-3 col-xs-12">
				<div class="prop_social">
					<button class="btn btn-info btn-xs" onclick="hope_installation('<?php echo $query->id;?>');"><i class="fa fa-heart"></i> <?php echo lang("site.save");?></button>
					<button class="btn btn-warning btn-xs" onclick="print();"><i class="fa fa-print"></i> <?php echo lang("site.print");?></button>
					<?php $url = "http://".HOST."/installation/view/".$query->id; ?>
					<a href="http://www.facebook.com/sharer.php?u=<?php echo $url;?>&amp;t=<?php echo urlencode($query->title);?>" target="_blank"><img src="/assets/common/img/facebook.png"></a>
					<a href="http://twitter.com/home?status=<?php echo urlencode($query->title);?><?php echo urlencode($url);?>" target="_blank"><img src="/assets/common/img/twitter.png"></a>
					<a href="https://plus.google.com/share?url=<?php echo $url;?>" target="_blank"><img src="/assets/common/img/googleplus.png"></a> 
				</div>
				<div class="member shadow-border question">
					<div class="memberClose"></div>
					<div class="question_body">
						<div class="row">
							<?php if($member->profile!=""){ echo "<div class='col-md-4 col-xs-4'><img class='profile img-responsive' src='/uploads/member/".$member->profile."'></div>";}?>
							<div class="col-md-8 col-xs-8">
								<h4>
									<?php 
										if( $member->biz_name!="" ) {
											echo $member->biz_name;
										} else {
											echo lang("product.owner");
										}
									?>
								</h4>
								<i class="fa fa-user"></i> <b><?php echo $member->name;?></b><br/>
							</div> <!-- span8 -->
							<div class="col-md-12 margin-top-10 view_phone_area <?php if($config->CALL_HIDDEN){?>hidden<?php }?>">
								
								<?php 
								if($member->type!="admin"){
									if ($member->biz_auth=="0") {?>
									<div class="i_title"><?php echo $member->address?> <?php echo $member->address_detail?></div>
									<div class="i_title"><?php echo lang("site.biznum");?> : <?php echo $member->biz_num?></div>
									<div class="i_title">대표전화 : <?php echo $member->tel?></div>
									<div class="i_title">대표자 : <?php echo $member->biz_ceo?></div>
									<?php } else {?>
									<div class="i_title"><?php echo $member->address?> <?php echo $member->address_detail?></div>
									<div class="i_title"><?php echo lang("site.renum");?> : <?php echo $member->re_num?></div>
									<div class="i_title">대표전화 : <?php echo $member->tel?></div>
									<div class="i_title">대표자 : <?php echo $member->biz_ceo?></div>
								<?php 
									}
								}
								?>
								<i class="fa fa-phone-square"></i> 
								<a href="tel:<?php echo $member->phone;?>"><b><?php echo $member->phone;?></b></a>
							</div>

						</div><!-- row-fluid -->
						<iframe id="target_url" style="width:0px;height:0px;display:none;"></iframe>
						<button id="view_phone" data-id="<?php echo $query->id?>" data-member-id="<?php echo $query->member_id?>" type="button" class="btn btn-info btn-block margin-top-15 <?php if(!$config->CALL_HIDDEN){?>hidden<?php }?>"><i class="fa fa-phone-square"></i> <?php echo lang("site.showcontact");?></button>
						<div class="view_phone_area <?php if($config->CALL_HIDDEN){?>hidden<?php }?>">
						<?php if($member->kakao!="") {?>
						<div class="row">
							<div class="col-md-12">
									<div class="kakao">
										<?php echo lang("site.kakaoadd");?><br/>
										<div class='kakao_in'>
										<?php if(substr($member->kakao, 0, 1)=="@"){?><a href="http://goto.kakao.com/<?php echo $member->kakao?>" target="_blank"><img src="/assets/common/img/add_kakao.png"> <?php echo $member->kakao?></a><?php } else {?><?php echo $member->kakao?><?php }?></div>
									</div>
							</div>
						</div>
						<?php }?>
						</div>

							<?php if($config->USE_CALL_REMAIN){?>
								<div class="i_title margin-top-10"><?php echo lang("msg.contact");?></div>
								<div id="err" style="margin-top:10px;"></div>
								<?php echo form_open("concern/action","id='concern_form'");?>
								<input type="hidden" name="module" value="installation"/>
								<input type="hidden" name="id" value="<?php echo $id;?>"/>
								<input id="mobile" name="mobile" type="text" maxlength="13" placeholder="<?php echo lang("site.mobile");?> 010-000-0000">
								<button type="submit" class="btn btn-primary btn-block margin-top-15"><i class="fa fa-mobile"></i> <?php echo lang("site.savecontact");?></button>
								<?php echo form_close();?>
							<?php }?>

					</div><!-- question_body -->
              </div><!-- member -->

			<div id="history_alert" style="margin-top:10px;"></div>
				<ul class="nav nav-tabs" style="margin-top:20px;margin-bottom:10px;">
			  <li class="active"><a href="#tab_history" data-toggle="tab"><?php echo lang("site.seen");?></a></li>
			  <li><a href="#tab_hope" data-toggle="tab"><?php echo lang("site.saved");?></a></li>
			</ul>
			<div class="tab-content">
					<div class="tab-pane active" id="tab_history">
						<ul class="list_widget">
						<?php foreach($panel_history as $val){?>
							<li class="row">
								<div class="col-md-4 col-xs-4" style="padding-right:0px;">
									<div class="img_wrapper" style="background-image:url(<?php	if($val->thumb_name==""){
										echo "/assets/common/img/no_thumb.png";
									} else {
										echo "/photo/gallery_installation_thumb/".$val->gallery_id;
									}?>);">
										<a href="/installation/view/<?php echo $val->id?>" target="_blank"><img src="/assets/common/img/bg/0.png" class="holder"></a>
									
									</div>
								</div>
								<div class="col-md-8 col-xs-8">
									<div class="title margin-bottom-10" title="<?php echo $val->title;?>"><?php echo anchor("installation/view/".$val->id,cut($val->title,65),"target='_blank'");?></div>
								</div>									
							</li>
						<?php }
						
						if(count($panel_history)<1){
							echo "<li><i class='fa fa-linux fa-lg'></i> ".lang("msg.nodata")."</li>";
						}
						?>
						</ul>
					</div>
					<div class="tab-pane" id="tab_hope">
						<ul class="list_widget">
						<?php foreach($panel_hope as $val){?>
							<li class="row">
								<div class="col-md-4 col-xs-4" style="padding-right:0px;">
									<div class="img_wrapper" style="background-image:url(<?php	if($val->thumb_name==""){
										echo "/assets/common/img/no_thumb.png";
									} else {
										echo "/photo/gallery_installation_thumb/".$val->gallery_id;
									}?>);">
										<a href="/installation/view/<?php echo $val->id?>" target="_blank"><img src="/assets/common/img/bg/0.png" class="holder"></a>
									
									</div>
								</div>
								<div class="col-md-8 col-xs-8">
									<div class="title margin-bottom-10" title="<?php echo $val->title;?>"><?php echo anchor("installation/view/".$val->id,cut($val->title,65),"target='_blank'");?></div>
								</div>									
							</li>
						<?php }
						
						if(count($panel_hope)<1){
							echo "<li><i class='fa fa-linux fa-lg'></i> ".lang("msg.nodata")."</li>";
						}
						?>
						</ul>
					</div>
			</div>
				
			  <h4>인근 분양<?php echo lang("installation");?> <i class="fa fa-question-circle help" data-toggle="tooltip" title="가까운 순으로 보여집니다."></i></h4>
			  <ul class="list_widget">
				<?php if(count($recent)<1){
					echo "<li>".lang("msg.nodata")."</li>";
				}?>
				<?php foreach($recent as $val){?>
				<li class="row">
					<div class="col-md-4 col-xs-4 margin-bottom-10" style="padding-right:0px;">
						<div class="img_wrapper" style="background-image:url(<?php	if($val["thumb_name"]==""){
							echo "/assets/common/img/no_thumb.png";
						} else {
							echo "/photo/gallery_thumb/".$val["gallery_id"];
						}?>);">
						<?php if($this->session->userdata("permit_area")){
								$permit_area = @explode(",",$this->session->userdata("permit_area"));
								if(in_array($val["address_id"],$permit_area)){?>
									<a href="/installation/view/<?php echo $val["id"]?>" target="_blank"><img src="/assets/common/img/bg/0.png" class="holder"></a>
								<?php } else {?>
									<a href="#" class="leanModal" lean-id="#permit-area"><img src="/assets/common/img/bg/0.png" class="holder"></a>			
								<?php }?>
						<?php } else {?>
							<a href="/installation/view/<?php echo $val["id"]?>" target="_blank"><img src="/assets/common/img/bg/0.png" class="holder"></a>
						<?php }?>						
						</div>
					</div>
					<div class="col-md-8 col-xs-8 margin-bottom-10">
						<?php if($this->session->userdata("permit_area")){
								$permit_area = @explode(",",$this->session->userdata("permit_area"));
								if(in_array($val["address_id"],$permit_area)){?>
									<div class="title margin-bottom-10" title="<?php echo $val["title"];?>"><?php echo anchor("installation/view/".$val["id"],cut($val["title"],65),"target='_blank'");?></div>
								<?php } else {?>
									<div class="title margin-bottom-10" title="<?php echo $val["title"];?>" class="leanModal" lean-id="#permit-area"><?php echo cut($val["title"],65);?></div>		
								<?php }?>
						<?php } else {?>
							<div class="title margin-bottom-10" title="<?php echo $val["title"];?>"><?php echo anchor("installation/view/".$val["id"],cut($val["title"],65),"target='_blank'");?></div>
						<?php }?>
						<?php echo price($val,$config);?>
					</div>
				</li>
				<?php }?>
			  </ul>

        </div> <!-- span3 -->
    </div>
</div>