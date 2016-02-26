<script>
/** 찜하기 **/
function hope_add(id){
  $.get("/mobile/hope_add/"+id+"/"+Math.round(new Date().getTime()),function(data){
    $("#hope_add").hide();
    $("#hope_remove").show();
  })
}

/** 찜하기 취소 **/
function hope_remove(id){
  $.get("/mobile/hope_remove/"+id+"/"+Math.round(new Date().getTime()),function(data){
    $("#hope_remove").hide(); 
    $("#hope_add").show();
  })
}

</script>
<div class="page-content" id="main-stack">
	<div class="w-nav navbar" data-collapse="all" data-animation="over-left" data-duration="400" data-contain="1" data-easing="ease-out-quint" data-no-scroll="1">
		<div class="w-container">
			<div class="wrapper-mask" data-ix="menu-mask"></div>
				<div class="navbar-title"><?php echo lang("product.no");?> <?php echo $query->id?> </div>
					<a id="hope_add" class="w-inline-block navbar-button left" href="#" onclick="hope_add('<?php echo $query->id?>');" <?php if($hope>0) {echo "style='display:none;'";}?> >
					<div class="navbar-button-icon smaller icon ion-android-favorite-outline"></div>
				</a>      
				<a id="hope_remove" class="w-inline-block navbar-button left" href="#" onclick="hope_remove('<?php echo $query->id?>');" <?php if($hope<1) {echo "style='display:none;'";}?> >
					<div class="navbar-button-icon smaller icon ion-android-favorite"></div>
				</a>            
				<a href="#" class="w-inline-block navbar-button right" onclick="onBackKeyDown();">
					<div class="navbar-button-icon icon ion-ios-close-empty"></div>
				</a>
				</div>
			</div>
			<div class="body">
				<iframe id="target_url" style="width:0px;height:0px;display:none;"></iframe>
				<?php 
				/** 공실 사용시 연락처 정보가 제공되기 때문에 담당자에게 연락할 일이 없으니 전화, SMS아이콘을 삭제한다 ***/
				if(!$config->GONGSIL_FLAG){?>
					<a class="view_phone" href="sms:<?php echo $member->phone;?>" data-id="<?php echo $query->id?>"><div class="sms-button icon ion-android-mail"></div></a>
					<a class="view_phone" href="tel:<?php echo $member->phone;?>" data-id="<?php echo $query->id?>"><div class="call-button icon ion-ios-telephone"></div></a>
				<?php } ?>

				<?php if($query->panorama_url){?>
					<iframe src="http://<?php echo str_replace("http://","",$query->panorama_url)?>" frameBorder="0" style="width:100%;height:250px;" allowfullscreen></iframe>
				<?php }?>

				<?php if(count($gallery)>0){?>
				<div class="w-slider hero-slider" data-animation="slide" data-duration="1000" data-infinite="1" data-nav-spacing="5" data-delay="4000" data-autoplay="1">
					<div class="w-slider-mask">
					<?php foreach($gallery as $key=>$val){?>
						<div class="w-slide slide" style="background-image:url(/photo/gallery_image/<?php echo $val->id;?>);"></div>
					<?php }?>
					</div>
					<div class="w-slider-arrow-left arrow-icon">
						<div class="w-icon-slider-left"></div>
					</div>
					<div class="w-slider-arrow-right arrow-icon">
						<div class="w-icon-slider-right"></div>
					</div>
					<div class="w-slider-nav w-round slider-bullets"></div>
				</div>
				<?php } ?>

				<div class="text-new">

					<div class="separator-fields"></div>
					<h2 class="title-new">
						<?php echo $query->title?>
						<?php if($query->is_speed=="1")   echo "<i class=\"label label-warning\">급매</i>"; ?>
						<?php if($query->recommand=="1")  echo "<i class=\"label label-info\">".lang("site.recommand")."</i>"; ?>
						<?php 
						if($query->is_finished=="1"){
							if($query->type=="installation") {
								echo "<i class=\"label label-danger\">분양완료</i>";
								} else {
								echo "<i class=\"label label-danger\">거래완료</i>";
							}
						}
						?>
					</h2>

					<div class="separator-fields"></div>
					<?php
						if($query->video_url != ""){
							preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/',$query->video_url,$matches);
							$id  = $matches[2];
							echo "<iframe src=\"https://www.youtube.com/embed/".$id."\" frameborder=\"0\" allowfullscreen style='width:100%;height:200px;'></iframe>";
						}
					?>

					<?php echo $product_view;?>

					<div class="separator-fields"></div>
					<div class="separator-fields"></div>

					<div class="property_content_inner">
						<?php echo $query->content;?>
						<?php echo $member->sign;?>
					</div>

					<?php if(isset($loan) && count($loan) > 0){?>
							<div class="separator-fields"></div>
							<h4 style="font-weight:bold;margin-top:20px;padding-left:5px;"> 대출정보</h4>					
							<table class="loan table table-hover">
								<tr>
									<th>금융기관</th>
									<th>대출금리</th>
									<th>대출한도</th>
								</tr>
								<?php
								foreach($loan as $key=>$val){
									
								?>
								<tr>
									<td><?php echo $val->bank_name;?></td>
									<td><?php echo $val->rate_min;?>~<?php echo $val->rate_max;?>%</td>
									<td><b><?php echo number_format($val->loan_limit);?></b>만원</td>
								</tr>
								<?php }?>
							</table>
					<?php }?>

					<div class="separator-fields"></div>
					<div class="separator-fields"></div>
					<div>
						<a href="/mobile/mapdetail/<?php echo $query->id;?>" class="action-button" style="width:100%">지도 및 로드뷰 보기</a>
						<div id="map" style="width:100%;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
var lat = "<?php echo $query->lat?>";
var lng = "<?php echo $query->lng?>";
var mapContainer = document.getElementById('map'),
    mapOption = { 
        center: new daum.maps.LatLng(lat,lng),
        level: 4
    };
var map = new daum.maps.Map(mapContainer, mapOption);

var circle = new daum.maps.Circle({
    center : new daum.maps.LatLng(lat, lng),
    radius: <?php echo $config->RADIUS;?>,
    strokeWeight: 5,
    strokeColor: '#75B8FA',
    strokeOpacity: 1,
    strokeStyle: 'dashed',
    fillColor: '#CFE7FF',
    fillOpacity: 0.7
});
circle.setMap(map); 
</script>