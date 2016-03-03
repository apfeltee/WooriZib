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
	"MT1"=>lang("daum.local.MT1"),
	"CS2"=>lang("daum.local.CS2"),
	"PS3"=>lang("daum.local.PS3"),
	"SC4"=>lang("daum.local.SC4"),
	"SW8"=>lang("daum.local.SW8"),
	"BK9"=>lang("daum.local.BK9"),
	"CT1"=>lang("daum.local.CT1"),
	"PO3"=>lang("daum.local.PO3"),
	"AT4"=>lang("daum.local.AT4"),
	"HP8"=>lang("daum.local.HP8"),
	"PM9"=>lang("daum.local.PM9")
	);

?>
<div>
    <div class="row">
        <div class="col-md-9 col-xs-12">
			<div class="property_content shadow-border">
					<?php if(count($gallery) > 0) {?>
					<div id="gallery-1" class="royalSlider rsDefault">
						<!-- 갤러리 시작 -->
							<?php if($query->panorama_url){?>
								<iframe src="http://<?php echo str_replace("http://","",$query->panorama_url)?>" frameBorder="0" style="width:100%;height:100%" allowfullscreen></iframe>
							<?php }?>
							<?php 
							foreach($gallery as $key=>$val){
							?>
							<a class="rsImg bugaga" data-rsBigImg="/photo/gallery_image/<?php echo $val->id;?>" href="/photo/gallery_image/<?php echo $val->id;?>" <?php if($key==0) {echo "data-rsVideo=\"".$query->video_url."\"";}?>>
							<img class="rsTmb" src="/photo/gallery_thumb/<?php echo $val->id;?>" /></a>
							<?php }?>
							<?php if(count($gallery)==0){?>
								<?php if($config->no==""){?>
									<a class="rsImg bugaga" data-rsBigImg="/assets/common/img/no.png" href="/assets/common/img/no.png"  data-rsVideo="<?php echo $query->video_url;?>">
									<img class="rsTmb" src="/assets/common/img/no_thumb.png" />
									</a>
								<?php } else { ?>
									<a class="rsImg bugaga" data-rsBigImg="/uploads/logo/<?php echo $config->no?>" href="/uploads/logo/<?php echo $config->no?>"  data-rsVideo="<?php echo $query->video_url;?>">
									<img class="rsTmb" src="/uploads/logo/thumb/<?php echo $config->no?>" />
									</a>
								<?php } ?>							
							<?php }?>
						<!-- 갤러리 종료 -->
					  </div>
					  <?php } ?>
					<!--상세정보-->
					<?php echo $product_view;?>

					<?php if($this->config->item('view_map_position')=='bottom'){?>
						<div class="property_content_inner">
						<?php echo $query->content;?>
						<?php echo $member->sign;?>
						</div>

						<?php if($config->MAP_ALERT!=""){?>
							<span class="help-block <?php if(!$this->config->item('view_map_use')) echo "display-none"?>" style="font-size:11px;">
								<img src="/assets/common/img/exclamation.png"> <?php echo $config->MAP_ALERT?>
							</span>
						<?php } ?>

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

						<?php if($config->MAP_ALERT!=""){?>
							<span class="help-block <?php if(!$this->config->item('view_map_use')) echo "display-none"?>" style="font-size:11px;">
								<img src="/assets/common/img/exclamation.png"> <?php echo $config->MAP_ALERT?>
							</span>
						<?php } ?>

						<!-- daum map START -->
						<div id="_container" class="view_map <?php if(!$this->config->item('view_map_use')) echo "display-none"?>">
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
					<div id="htab">
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
				<!--div class="prop_social">
					<button class="btn btn-info btn-xs" onclick="hope('<?php echo $query->id;?>');"><i class="fa fa-heart"></i> <?php echo lang("site.save");?></button>
					<button class="btn btn-warning btn-xs" onclick="print();"><i class="fa fa-print"></i> <?php echo lang("site.print");?></button>
					<?php $url = "http://".HOST."/product/view/".$query->id; ?>
					<?php $gallery_img = (isset($gallery[0]->id)) ? $gallery[0]->id : "";?>
					<link rel="image_src" href="<?php echo "http://".HOST."/photo/gallery_image/".$gallery_img;?>" />
					<a href="javascript:facebook_link('<?php echo $url;?>','<?php echo "http://".HOST."/photo/gallery_image/".$gallery_img;?>','<?php echo $query->title;?>','');"><img src="/assets/common/img/facebook.png"></a>
					<a href="http://twitter.com/home?status=<?php echo urlencode($query->title);?><?php echo urlencode($url);?>" target="_blank"><img src="/assets/common/img/twitter.png"></a>
					<a href="https://plus.google.com/share?url=<?php echo $url;?>" target="_blank"><img src="/assets/common/img/googleplus.png"></a> 
				</div-->
				<?php if($config->MEMBER_INFO_RIGHT){?>
				<div class="member shadow-border question">
					<div class="memberClose"></div>
					<div class="question_body">
						<?php if($config->GONGSIL_FLAG){?>						
							<h4 style="font-weight:bold;">공실 정보</h4>
							<table class="border-table">
								<tr>
									<th width="30%"><?php echo lang("site.status");?></th>
									<td width="70%">
										<?php 	echo $query->gongsil_status;?>
									</td>
								</tr>
								<tr>							
									<th width="30%">방볼때</th>
									<td width="70%">
										<?php 	echo $query->gongsil_see;?>
									</td>
								</tr>
								<tr>
									<th width="30%"><?php echo lang("site.contact");?></th>
									<td width="70%">
										<?php echo multi_view($query->gongsil_contact,"gongsil");?>
									</td>
								</tr>	
							</table>
						<?php } else { ?>
							<div class="row">
								<?php if($member->profile!=""){ echo "<div class='col-md-4 col-xs-4'><img class='profile img-responsive' src='/uploads/member/".$member->profile."'></div>";}?>
								<div class="col-md-8 col-xs-8">
									<div class="text-primary">
										<?php if($member->type=="general") echo "[".lang("site.trdirect")."]";?>
										<?php if($member->type=="biz") echo "[".lang("site.tragent")."]";?>
									</div>
									<h4>
										<?php if($member->type=="biz") echo $member->biz_name;?>
										<?php if($member->type=="admin") echo lang("product.owner");?>
									</h4>
									<i class="fa fa-user"></i> <b><?php echo $member->name;?></b><br/>
									<?php 
									if ($member->biz_auth=="1") {
										echo " 대표공인중개사";
									} else if ($member->biz_auth=="2"){
										echo " 소속공인중개사";
									} else if ($member->biz_auth=="3"){
										echo " 중개보조원";
									} else {
										echo "";
									}
									?><br/>
								</div> <!-- span8 -->
								<div class="col-md-12 margin-top-10 view_phone_area <?php if($config->CALL_HIDDEN){?>hidden<?php }?>">
									
									<div class="i_title"><?php echo $member->address?> <?php echo $member->address_detail?></div>
									<div class="i_title"><?php echo lang("site.tel");?> : <?php echo $member->tel?></div>
									<div class="i_title"><?php echo lang("site.ceo");?> : <?php echo $member->biz_ceo?></div>
									<?php 
									if($member->type!="admin"){
										if ($member->biz_auth=="0") {?>
										<div class="i_title"><?php echo lang("site.ceo");?> : <?php echo $member->biz_ceo?></div>
										<?php } else {?>
										<div class="i_title"><?php echo lang("site.renum");?> : <?php echo $member->re_num?></div>
									<?php 
										}
									}
									?>
									<i class="fa fa-phone-square"></i> 
									<b><?php echo $member->phone;?></b>
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
								<input type="hidden" name="module" value="product"/>
								<input type="hidden" name="id" value="<?php echo $id;?>"/>
								<input id="mobile" name="mobile" type="text" maxlength="13" placeholder="<?php echo lang("site.mobile");?> 010-000-0000">
								<button type="submit" class="btn btn-primary btn-block margin-top-15"><i class="fa fa-mobile"></i> <?php echo lang("site.savecontact");?></button>
								<?php echo form_close();?>
							<?php }?>
					<?php } ?><!-- GONGSIL_FLAG -->
					</div><!-- question_body -->
				</div><!-- member -->
				<?php }?> 


				<!-- 매매시 대출 정보 보여줌 시작 -->
				<?php if(isset($loan) && count($loan) > 0){?>
				<div class="portlet box blue-hoki margin-top-10">
					<div class="portlet-title">
						<div class="caption" style="font-size:14px;">
							<i class="fa fa-percent"></i> 대출정보
						</div>
						<div class="actions">
							<a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#loan_dialog">더보기</a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="loan table table-hover">
							<tr>
								<th>금융기관</th>
								<th>대출금리</th>
								<th>대출한도</th>
							</tr>
							<?php
							foreach($loan as $key=>$val){
								if($key==2) break;
							?>
							<tr>
								<td><?php echo $val->bank_name;?></td>
								<td><?php echo $val->rate_min;?>~<?php echo $val->rate_max;?>%</td>
								<td><b><?php echo number_format($val->loan_limit);?></b>만원</td>
							</tr>
							<?php }?>
						</table>
					</div>
				</div>
				<div id="loan_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:99999;">
					<div class="modal-dialog" style="max-width:500px;">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" onclick="$('#loan_dialog').modal('hide');"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel"><i class="fa fa-percent"></i> 대출정보</h4>
							</div>
							<div class="modal-body">
								<table class="table table-hover">
									<tr>
										<th>금융기관</th>
										<th>대출금리</th>
										<th>대출한도</th>
										<th>기타</th>
									</tr>
									<?php foreach($loan as $key=>$val){?>
									<tr>
										<td><?php echo $val->bank_name;?></td>
										<td><?php echo $val->rate_min;?>~<?php echo $val->rate_max;?>%</td>
										<td><b><?php echo number_format($val->loan_limit);?></b>만원</td>
										<td><?php echo$val->etc;?></td>
									</tr>
									<?php }?>
								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" onclick="$('#loan_dialog').modal('hide');"><?php echo lang("site.close");?></button>
							</div>
						</div>
					</div>
				</div>
				<?php }?>
				<!-- 매매시 대출 정보 보여줌 종료 -->


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
										echo "/photo/gallery_thumb/".$val->gallery_id;
									}?>);">
										<a href="/product/view/<?php echo $val->id?>" target="_blank"><img src="/assets/common/img/bg/0.png" class="holder"></a>
									
									</div>
								</div>
								<div class="col-md-8 col-xs-8">
									<div class="title margin-bottom-10" title="<?php echo $val->title;?>"><?php echo anchor("product/view/".$val->id,cut($val->title,65),"target='_blank'");?></div>
									<?php echo price($val,$config);?>
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
										echo "/photo/gallery_thumb/".$val->gallery_id;
									}?>);">
										<a href="/product/view/<?php echo $val->id?>" target="_blank"><img src="/assets/common/img/bg/0.png" class="holder"></a>
									
									</div>
								</div>
								<div class="col-md-8 col-xs-8">
									<div class="title margin-bottom-10" title="<?php echo $val->title;?>"><?php echo anchor("product/view/".$val->id,cut($val->title,65),"target='_blank'");?></div>
									<?php echo price($val,$config);?>
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
				
			  <?php
				$recent_title = ($query->danzi_id) ? lang("site.recentdanzi") : lang("site.recentproducts");
				echo "<h4>";
				echo $recent_title;
				echo "<i class=\"fa fa-question-circle help\" data-toggle=\"tooltip\" title=\"".lang("site.recentproducts_description")."\"></i>";
				echo "</h4>";
			  ?>
			  
			  <ul class="list_widget">
				<?php if(count($recent)<1){
					echo "<li> ".lang("msg.nodata")."</li>";
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
									<a href="/product/view/<?php echo $val["id"]?>" target="_blank"><img src="/assets/common/img/bg/0.png" class="holder"></a>
								<?php } else {?>
									<a href="#" class="leanModal" lean-id="#permit-area"><img src="/assets/common/img/bg/0.png" class="holder"></a>			
								<?php }?>
						<?php } else {?>
							<a href="/product/view/<?php echo $val["id"]?>" target="_blank"><img src="/assets/common/img/bg/0.png" class="holder"></a>
						<?php }?>
						</div>
					</div>
					<div class="col-md-8 col-xs-8 margin-bottom-10">
						<?php if($this->session->userdata("permit_area")){
								$permit_area = @explode(",",$this->session->userdata("permit_area"));
								if(in_array($val["address_id"],$permit_area)){?>
									<div class="title margin-bottom-10" title="<?php echo $val["title"];?>"><?php echo anchor("product/view/".$val["id"],cut($val["title"],65),"target='_blank'");?></div>
								<?php } else {?>
									<div class="title margin-bottom-10" title="<?php echo $val["title"];?>" class="leanModal" lean-id="#permit-area"><?php echo cut($val["title"],65);?></div>		
								<?php }?>
						<?php } else {?>
							<div class="title margin-bottom-10" title="<?php echo $val["title"];?>"><?php echo anchor("product/view/".$val["id"],cut($val["title"],65),"target='_blank'");?></div>
						<?php }?>
						<?php echo price($val,$config);?>
					</div>
				</li>
				<?php }?>
			  </ul>

			  <?php if($right_news){?>
			  <h4 class="margin-top-20">&nbsp;</h4>
			  <ul class="list_widget">
			    <?php foreach($right_news as $val){?>
				<li class="row">
					<?php if($val->thumb_name!=""){?>
					<div class="col-md-4 col-xs-4" style="padding-right:0px;">
						<div class="img_wrapper" style="background-image:url(/uploads/news/thumb/<?php echo $val->thumb_name;?>">
							<a href="/news/view/<?php echo $val->id?>" target="_blank"><img src="/assets/common/img/bg/0.png" class="holder"></a>				
						</div>
					</div>
					<?php }?>
					<?php $col = ($val->thumb_name!="") ? "8" : "12";?>
					<div class="col-md-<?php echo $col;?> col-xs-<?php echo $col;?>">
						<div class="title margin-bottom-10" title="<?php echo $val->title;?>"><?php echo anchor("news/view/".$val->id,cut($val->title,100),"target='_blank'");?></div>					
					</div>
					<?php foreach($val->attachment as $attachment){?>
					<div class="col-md-12 col-xs-12 margin-bottom-5">
						<button class="btn btn-default btn-sm" onclick="location.href='/attachment/news_download/<?php echo $attachment->news_id;?>/<?php echo $attachment->id;?>'"><strong><?php echo $attachment->originname;?> <i class="fa fa-download" style="font-size:10px;"></i></strong></button>
					</div>
					<?php }?>
				</li>
				<?php }?>
			  </ul>
			  <?php }?>

        </div> <!-- span3 -->
    </div>
</div>

<script>
    //http://help.dimsemenov.com/kb/royalslider-jquery-plugin-issues/slider-content-area-shrinks
    //http://help.dimsemenov.com/discussions/problems/1355-setting-width-and-height
    //https://www.google.co.kr/webhp?sourceid=chrome-instant&ion=1&espv=2&ie=UTF-8#q=rayalslider+bootstrap+modal+fullscreen
</script>