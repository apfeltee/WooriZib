<script>
function delete_product(id){
	if(confirm("<?php echo lang("product");?>(를)을 삭제하시겠습니까?\n<?php echo lang("product");?>삭제는 관리자와 등록한 직원만 가능합니다.")){
		location.href="/adminproduct/delete_product/"+id;
	}
}

function change(type, id, status){
	if(confirm("상태를 변경하시겠습니까?")){
		$.get("/adminproduct/change/"+type+"/"+id+"/"+status+"/"+Math.round(new Date().getTime()),function(data){
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
			<?php echo lang("product");?> 관리<small>보기</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="/adminproduct/index"><?php echo lang("product");?> 관리</a>
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
							<a href="/adminproduct/index/">목록으로 가기</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="/adminproduct/edit/<?php echo $query->id;?>">수정</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="/adminproduct/check_product/<?php echo $query->id;?>"><?php echo lang("product")?>확인하기</a>
						</li>
						<li>
							<a href="/adminproduct/copy/<?php echo $query->id;?>">복사</a>
						</li>
						<li>
							<a href="/adminproduct/refresh/<?php echo $query->id;?>"><?php echo lang("site.refresh");?></a>
						</li>									
						<li class="divider"></li>
						<li>
							<a href="#" onclick="print();"><?php echo lang("site.print");?></a>
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
						<li>
						<?php if($query->is_finished=="1"){?>
							<a href="#" onclick="change('is_finished','<?php echo $query->id;?>','0');"><?php echo ($query->type=="installation")?"분양":"계약"?>완료처리 취소하기</a>
						<?php } else {	?>
							<a href="#" onclick="change('is_finished','<?php echo $query->id;?>','1');"><?php echo ($query->type=="installation")?"분양":"계약"?>완료 처리하기</a>
						<?php }?>
						</li>
						<li>
						<?php if($query->is_speed=="1"){?>
							<a href="#" onclick="change('is_speed','<?php echo $query->id;?>','0');">급매로 설정 취소하기</a>
						<?php } else {	?>
							<a href="#" onclick="change('is_speed','<?php echo $query->id;?>','1');">급매로 설정하기</a>
						<?php }?>
						</li>
						<li>
						<?php if($query->is_defer=="1"){?>
							<a href="#" onclick="change('is_defer','<?php echo $query->id;?>','0');"><?php echo ($query->type=="installation")?"분양":"계약"?>보류로 설정 취소하기</a>
						<?php } else {	?>
							<a href="#" onclick="change('is_defer','<?php echo $query->id;?>','1');"><?php echo ($query->type=="installation")?"분양":"계약"?>보류로 설정하기</a>
						<?php }?>
						</li>
						<li class="divider"></li>
						<li>
							<a href="#" onclick="delete_product('<?php echo $query->id;?>');"><?php echo lang("site.delete");?></a>
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
			<?php if($query->recommand=="1") {echo "<span class='label label-success'>추천</span>";}?>
			<?php if($query->is_activated=="1") {echo "<span class='label label-primary'>공개</span>";}?>
			<?php if($query->is_activated=="0") {echo "<span class='label label-danger'>비공개</span>";}?>
			<?php if($query->is_finished=="1") {echo "<span class='label label-default'>완료</span>";}?>
			<?php if($query->is_speed=="1") {echo "<span class='label label-warning '>급매</span>";}?>
			<?php if($query->is_defer=="1"){
				if($query->type=="installation") echo "<span class='label label-info '>분양보류</span>";
				else echo "<span class='label label-info '>계약보류</span>";
			}
			?>
		</h4>
		<hr/>

		<?php echo $product_view;?>

		<table class="border-table">
			<tr class="not_print">
				<th width="20%">지도</th>
				<td width="80%" colspan="3">
					<div id="map" style="width:100%;height:350px;"></div> 
				</td>
			</tr>	
		</table>

		<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 미디어</h4>					
		<table class="border-table">
			<tr>
				<th width="20%"><?php echo lang("site.photo");?> <br><small style="color:gray;font-weight:100;"> * 첫번째 사진은 대표사진으로 사용됩니다.</small></th>
				<td width="80%" colspan="3">
					<ul class="row" id="list">
					<?php foreach($gallery as $key=>$val){
						$temp = explode(".",$val->filename); ?>
						<li>
							<div class="thumbnail <?php echo ($key==0) ? 'first_thumb' : 'thumb';?>" style="margin:0;padding:0">
								<a href="/uploads/gallery/<?php echo $val->product_id?>/<?php echo $val->filename;?>" class='fancy'><img src="/uploads/gallery/<?php echo $val->product_id?>/<?php echo $temp[0]."_thumb.".$temp[1];?>" style='width:180px;height:180px;'/></a>
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
				<th width="20%">VR 파노라마 주소</th>
				<td width="80%">
					<a href="<?php echo $query->panorama_url;?>" target="_blank"><?php echo $query->panorama_url;?></a>
				</td>
			</tr>
			<tr>
				<th width="20%">첨부파일</th>
				<td width="80%">
					<span class="help-inline">
						<?php foreach($attachment as $val){?>
						<a href="/attachment/download/<?php echo $query->id;?>/<?php echo $val->id?>/<?php echo $val->filename?>"><?php echo $val->originname?></a><br/>
						<?php }?>
						<?php if(count($attachment)==0){?>
						등록된 첨부파일이 없습니다.
						<?php }?>
					</span>
				</td>
			</tr>
		</table>

		<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 관리자 전용 이미지 <small style="color:gray;font-weight:100;"> * 관리자만 볼 수 있는 관리용 이미지 입니다.</small></h4>					
		<table class="border-table">
			<tr>
				<th width="20%"><font color='red' style='padding:3px;background-color:#efefef;border-radius:5px;'><?php echo lang("product");?>사진 <i class="fa fa-user-secret" title="관리자로그인시에만 보입니다."></i></font></th>
				<td width="80%" colspan="3">
					<ul class="row" id="list">
					<?php foreach($gallery_admin as $key=>$val){
						$temp = explode(".",$val->filename); ?>
						<li>
							<div class="thumbnail" style="margin:0;padding:0">
								<a href="/uploads/gallery_admin/<?php echo $val->product_id?>/<?php echo $val->filename;?>" class='fancy'><img src="/uploads/gallery_admin/<?php echo $val->product_id?>/<?php echo $temp[0]."_thumb.".$temp[1];?>" style='width:180px;height:180px;'/></a>
							</div>
						</li>
					<?php }?>
					<?php if(count($gallery_admin)==0){?>
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
		</table>

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
