<script>
function delete_installation(id){
	if(confirm("<?php echo lang("installation");?>(를)을 삭제하시겠습니까?\n<?php echo lang("installation");?>삭제는 관리자와 등록한 직원만 가능합니다.")){
		location.href="/admininstallation/delete_installation/"+id;
	}
}

function change(type, id, status){
	if(confirm("상태를 변경하시겠습니까?")){
		$.get("/admininstallation/change/"+type+"/"+id+"/"+status+"/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				location.reload();
		   } else {
				alert("변경 실패");
		   }
		})
	}
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				<?php echo lang("installation");?> 관리<small>보기</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="/admininstallation/index"><?php echo lang("installation");?> 관리</a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="#">보기</a>
				</li>
			</ul>
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<?php if($this->session->userdata("auth_id")=="1" || $this->session->userdata("admin_id")==$query->member_id){?>
					<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
					실행 <i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="/admininstallation/index/">목록으로 가기</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="/admininstallation/edit/<?php echo $query->id;?>">수정</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="/admininstallation/copy/<?php echo $query->id;?>">복사</a>
						</li>
						<li>
							<a href="/admininstallation/refresh/<?php echo $query->id;?>"><?php echo lang("site.refresh");?></a>
						</li>									
						<li class="divider"></li>
						<li>
							<a href="#" onclick="javascript:print('#print_area');"><?php echo lang("site.print");?></a>
						</li>
						<li class="divider"></li>
						<li>
						<?php if($query->is_valid=="1"){?>
							<a href="#" onclick="change('is_valid','<?php echo $query->id;?>','0');">비승인하기</a>
						<?php } else {	?>
							<a href="#" onclick="change('is_valid','<?php echo $query->id;?>','1');">승인하기</a>
						<?php }?>
						</li>
						<li>
						<?php if($query->is_activated=="1"){?>
							<a href="#" onclick="change('is_activated','<?php echo $query->id;?>','0');">비공개하기</a>
						<?php } else {	?>
							<a href="#" onclick="change('is_activated','<?php echo $query->id;?>','1');">공개하기</a>
						<?php }?>
						</li>
						<li>
						<?php if($query->recommand=="1"){?>
							<a href="#" onclick="change('recommand','<?php echo $query->id;?>','0');">추천해제하기</a>
						<?php } else {	?>
							<a href="#" onclick="change('recommand','<?php echo $query->id;?>','1');">추천하기</a>
						<?php }?>
						</li>
						<li class="divider"></li>
						<li>
							<a href="#" onclick="delete_installation('<?php echo $query->id;?>');"><?php echo lang("site.delete");?></a>
						</li>
					</ul>
					<?php } else {?>
					<button type="button" class="btn btn-danger" onclick="history.back(-1)"><?php echo lang("site.back");?></button>					
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.row -->

		 <div class="row">
			 <div class="col-lg-12">

				<!-- order meta start-->
				<h4>
					<?php echo $query->id;?>. <?php echo $query->title;?><span style="font-size:12px;padding-left:10px;">
					<?php if($query->recommand=="1") {echo "<span class='label label-success'>".lang("site.recommand")."</span>";}?>
					<?php if($query->is_activated=="1") {echo "<span class='label label-primary'>공개</span>";}?>
					<?php if($query->is_activated=="0") {echo "<span class='label label-danger'>비공개</span>";}?>
				</h4>
				<hr/>

			<div class="tabbable tabbable-custom boxless tabbable-reversed">
				<div class="installation tab-pane" id="detail" >
					<input type="hidden" name="id" value="<?php echo $query->id?>"/>
					<span id="print_area">
					<h3 class="display-none" style="font-weight:bold;margin-top:20px;padding-left:5px;"><?php echo "[".$query->id."]".$query->title;?></h3>
					<table class="border-table margin-top-10">
						<tr>
							<th width="20%"><?php echo lang("product.category");?></th>
							<td width="80%" colspan="3">
							<?php	echo lang("installation.category.".$query->category);?>

							<?php 
								if($query->status=="plan"){
									echo "<span class=\"label label-sm label-primary\">계획중</span>";
								} else if($query->status=="go"){
									echo "<span class=\"label label-sm label-danger\">진행중</span>";
								} else if($query->status=="end"){
									echo "<span class=\"label label-sm label-success\">종료</span>";
								}
							?> 
							
							</td>
						</tr>
						<tr>
							<th width="20%"><?php echo lang("site.address");?></th>
							<td width="80%" colspan="3">
								<?php echo $address->sido;?> 
								<?php echo $address->gugun;?> 
								<?php echo $address->dong;?> 
								<?php echo $query->address;?>
							</td>
						</tr>
						<tr <?php if($config->SUBWAY=="0") echo "style='display:none;'";?>>
							<th width="20%"><?php echo lang("site.subway");?></th>
							<td width="80%" colspan="3">
								<?php 
									if(count($installation_subway)<1){
										echo lang("msg.nodata").;
									}

									foreach($installation_subway as $val){
										echo "<b>[".$val->hosun." 호선]</b> ".$val->name."역 ".$val->distance." km ";
									}
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
					</table>

					<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> <?php echo lang("installation");?> 정보</h4>
					<table class="border-table">
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

						<tr class="not_print">
							<th width="20%">지도</th>
							<td width="80%" colspan="3">
								<div id="map" style="width:100%;height:350px;"></div> 
							</td>
						</tr>
					</table>

					<?php if(count($pyeong)>0){//print 용도?>
					<div class="is_print" style="display:none;">
						<h4 style="font-weight:bold;margin-top:20px;padding-left:5px"> 평형정보</h4>
						<?php foreach($pyeong as $key=>$val){?>
						<div style="margin-bottom:20px;">
							<div style="display:inline;float:left;width:50%;text-align:center;">
							<?php 
							if($val->filename!=""){
								$temp = explode(".",$val->filename);
							?>
								<img src="/uploads/pyeong/<?php echo $val->installation_id?>/<?php echo $temp[0]."_thumb.".$temp[1];?>" style="max-width:100%;max-height:230px;">
							<?php } else {?>
								<img src="/assets/common/img/no_thumb.png" style="max-width:100%;max-height:230px;">
							<?php }?>
							</div>
							<div style="display:inline;float:left;width:50%">
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
										<td style="text-align:right"><?php echo $val->real_area;?> ㎡/<?php echo $val->law_area;?> ㎡</td>
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
										<td style="text-align:right"><?php echo $val->bedcnt;?><?php echo $val->bathcnt;?></td>
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

					<h4 class="not_print" style="font-weight:bold;margin-top:20px;padding-left:5px;"> 평형</h4>					
					<table class="border-table not_print">
						<tr>
							<th width="20%">평형정보</th>
							<td width="80%" colspan="3">
								<div class="col-md-7">
									<?php if( count($pyeong) > 0 ) {?>
									<!-- Nav tabs -->
									<ul class="nav nav-tabs" role="tablist">
									<?php foreach($pyeong as $key=>$val){?>
										<li role="presentation" <?php if($key=="0"){ ?>class="active"<?php }?>><a href="#pyeong_<?php echo $val->id;?>" aria-controls="pyeong_<?php echo $val->id;?>" role="tab" data-toggle="tab"><?php echo $val->name;?> ㎡</a></li>
									<?php  	}  ?>
									</ul>

									<!-- Tab panes -->
									<div class="tab-content">
									<?php foreach($pyeong as $key=>$val){?>
											<div role="tabpanel" class="tab-pane <?php if($key=="0"){ ?>active<?php }?>" id="pyeong_<?php echo $val->id;?>" style="padding:10px;">

												<div class="row">
													<div class="col-md-6">
													<?php 
													if($val->filename!=""){
														$temp = explode(".",$val->filename);
													?>
														<a href="/uploads/pyeong/<?php echo $val->installation_id?>/<?php echo $val->filename;?>" class="fancy"><img src="/uploads/pyeong/<?php echo $val->installation_id?>/<?php echo $temp[0]."_thumb.".$temp[1];?>" style="max-width:100%;max-height:230px;" class="img-responsive"></a>
													<?php } else {?>
														<a href="/assets/common/img/no.png" class="fancy"><img src="/assets/common/img/no_thumb.png" style="max-width:100%;max-height:230px;" class="img-responsive"></a>
													<?php }?>
													</div>
													<div class="col-md-6">
													<table width="100%" class="table table-striped-left table-hover table-condensed">
														<col width="30%"/>
														<col width="70%"/>														
														<tr>
															<th>분양세대수</th>
															<td class="text-right"><?php echo $val->cnt;?> 세대</td>
														</tr>
														<tr>
															<th>분양가</th>
															<td class="text-right"><?php echo $val->price_min;?> ~ <?php echo $val->price_max;?>만원</td>
														</tr>
														<tr>
															<th>취득세</th>
															<td class="text-right"><?php echo $val->tax;?> 만원</td>
														</tr>
														<tr>
															<th>전용/공급</th>
															<td class="text-right"><?php echo $val->real_area;?> ㎡/<?php echo $val->law_area;?> ㎡</td>
														</tr>
														<tr>
															<th>대지지분</th>
															<td class="text-right"><?php echo $val->road_area;?> ㎡</td>
														</tr>
														<tr>
															<th>현관</th>
															<td class="text-right"><?php echo $val->gate;?></td>
														</tr>
														<tr>
															<th>방/욕실</th>
															<td class="text-right"><?php echo $val->bedcnt;?><?php echo $val->bathcnt;?></td>
														</tr>
														<tr>														
															<th>전매기간</th>
															<td class="text-right"><?php echo $val->presale_date;?></td>
														</tr>
														<?php if($val->description){?>
														<tr>
															<td colspan="2"><?php echo $val->description;?></td>
														</tr>
														<?php }?>
													</table>
													</div>
												</div>

											</div> <!-- tabpanel -->
										<?php 	}?>
									</div><!-- tab-content -->
									<?php  	}  ?> <!-- 평형 정보가 있을 경우에만 보여준다. -->
								</div><!-- col-md-7 -->
							</td>
						</tr>
					</table>

					<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 일정</h4>
					<table class="border-table">
						<tr>
							<th width="20%" rowspan="<?php echo count($schedule)?>">일정정보</th>
						<?php foreach($schedule as $val){?>							
							<td width="20%"><?php echo $val->name?></td>
							<td width="20%"><?php echo $val->date?></td>
							<td width="*"><?php echo $val->description?></td>
						</tr>
						<?php }?>
						<?php if(count($schedule)==0){?>
							<td width="80%" colspan="3">
								<?php echo lang("msg.nodata");?>
							</td>
						</tr>
						<?php }?>
					</table>

					<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 설명</h4>
					<table class="border-table">
						<tr>
							<th width="20%">관리메모 <i class="fa fa-lock"></i></th>
							<td width="80%" colspan="3">
								<span class="help-inline"><font color="red"><?php echo $query->secret;?></font></span>
							</td>
						</tr>							
						<tr class="not_print">
							<th width="20%">설명</th>
							<td width="80%" colspan="3">
								<span class="help-inline"><?php echo $query->content;?></span>
							</td>
						</tr>
						<tr class="is_print display-none"><!--프린트용 설명-->
							<th width="20%">설명</th>
							<td width="80%" colspan="3">
								<span class="help-inline"><?php echo strip_tags(str_replace("&nbsp;","",$query->content),"<p><br>");?></font></span>
							</td>
						</tr>
					</table>		
					
					<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> <?php echo lang("product.owner");?></h4>
					<table class="border-table">
						<tr>
							<th width="20%"><?php echo lang("product.owner");?></th>
							<td width="30%">
								<span class="help-inline"><?php echo $query->member_name;?></span>
							</td>
							<th width="20%"><?php echo lang("site.email");?></th>
							<td width="30%">
								<span class="help-inline"><?php echo $query->member_email;?></span>
							</td>
						</tr>
						<tr>
							<th width="20%"><?php echo lang("site.mobile");?></th>
							<td width="30%">
								<span class="help-inline"><?php echo $query->member_phone;?></span>
							</td>
							<th width="20%"><?php echo lang("site.tel");?></th>
							<td width="30%">
								<span class="help-inline"><?php echo $query->member_tel;?></span>
							</td>
						</tr>
					</table>

					</span>

					<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 미디어</h4>					
					<table class="border-table">
						<tr>
							<th width="20%"><?php echo lang("installation");?><?php echo lang("site.photo");?> <br><small style="color:gray;font-weight:100;"> * 첫번째 사진은 대표사진으로 사용됩니다.</small></th>
							<td width="80%" colspan="3">
								<ul class="row" id="list">
								<?php foreach($gallery as $key=>$val){
									$temp = explode(".",$val->filename); ?>
									<li>
										<div class="thumbnail <?php echo ($key==0) ? 'first_thumb' : 'thumb';?>" style="margin:0;padding:0">
											<a href="/uploads/gallery_installation/<?php echo $val->installation_id?>/<?php echo $val->filename;?>" class='fancy'><img src="/uploads/gallery_installation/<?php echo $val->installation_id?>/<?php echo $temp[0]."_thumb.".$temp[1];?>" style='width:180px;height:180px;'/></a>
										</div>
									</li>
								<?php }?>
								<?php if(count($gallery)==0){?>
									<?php if($config->no==""){?>
									<li>
										<div class="thumbnail" style="margin:0;padding:0">
											<a href="/assets/common/img/no.png" class='fancy'>
											<img src="/assets/common/img/no_thumb.png" style='width:100px;height:100px'/>
											</a>
										</div>
									</li>
									<?php } else { ?>
										<div class="thumbnail" style="margin:0;padding:0">
											<img src="/uploads/logo/thumb/<?php echo $config->no?>" style='width:100px;height:100px'/>
										</div>
									<?php } ?>
								<?php }?>
								</ul>
							</td>
						</tr>
						<tr>
							<th width="20%">유튜브 주소 <a href="http://youtu.be/Fa1te_bbb8w" target="_blank"><i class="fa fa-question-circle"></i></a></th>
							<td width="80%">
								<a href="<?php echo $query->video_url;?>" target="_blank"><?php echo $query->video_url;?></a>
							</td>
						</tr>
						<tr>
							<th width="20%">입주자모집요강</th>
							<td width="80%">
								<span class="help-inline">
									<?php foreach($attachment as $val){?>
									<a href="/attachment/installation_download/<?php echo $query->id;?>/<?php echo $val->id?>/<?php echo $val->filename?>"><?php echo $val->originname?></a><br/>
									<?php }?>
									<?php if(count($attachment)==0){?>
									등록된 입주자모집요강 파일이 없습니다.
									<?php }?>
								</span>
							</td>
						</tr>
					</table>
				</div> <!-- tab1 -->
			</div>
	</div><!-- row -->
</div>
<script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=<?php echo $config->DAUM_MAP_KEY;?>&libraries=services"></script>
<script>
var mapContainer = document.getElementById('map'),
    mapOption = { 
        center: new daum.maps.LatLng(<?php echo $query->lat;?>, <?php echo $query->lng;?>),
        level: 3
    };
var map = new daum.maps.Map(mapContainer, mapOption);

var mapTypeControl = new daum.maps.MapTypeControl();
map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);

var zoomControl = new daum.maps.ZoomControl();
map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);

var circle = new daum.maps.Circle({
    center : new daum.maps.LatLng(<?php echo $query->lat;?>, <?php echo $query->lng;?>),
    radius: 50,
    strokeWeight: 5,
    strokeColor: '#75B8FA',
    strokeOpacity: 1,
    strokeStyle: 'dashed',
    fillColor: '#CFE7FF',
    fillOpacity: 0.7
});

circle.setMap(map);
</script>