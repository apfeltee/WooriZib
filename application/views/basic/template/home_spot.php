<?php if(count($spot)>0){?>
<div class="main_color">
	<div class="_container">

		
		<div class="spot">
			<?php foreach($spot as $val){?>
				  <div><a href="#" onclick="set_spot('<?php echo $val->id;?>','<?php echo $val->lat;?>','<?php echo $val->lng;?>');" class="spot_obj" data-lat="<?php echo $val->lat;?>" data-lng="<?php echo $val->lng;?>" data-name="<?php echo $val->name;?>" data-content="<?php echo $val->content;?>"><i class="fa fa-map-marker"></i> <?php echo $val->name;?> </a></div>
			<?php }?>
		</div>
		<div style="clear:both;"></div>
		

	</div> <!-- container -->
</div>
<?php }?>

<script>
$(document).ready(function(){

	$(".spot_obj").hover(function(){
		show_marker($(this).attr("data-lat"),$(this).attr("data-lng"),$(this).attr("data-content"));
	});
});

function show_marker(lat,lng,content){

		if(marker){
			marker.setMap(null);
		}
		
		var markerPosition  = new daum.maps.LatLng(lat, lng); 

		
		marker = new daum.maps.Marker({
			position: markerPosition
		});

		
		marker.setMap(map);

		if(content!=""){
			var iwContent = '<div style="padding:5px;max-width:150px;">'+content+'</div>';

			iwPosition = new daum.maps.LatLng(lat, lng);

			if(daum_infowindow){
				daum_infowindow.close();
			}

			daum_infowindow = new daum.maps.InfoWindow({
				position : iwPosition, 
				content : iwContent 
			});

			daum_infowindow.open(map, marker);		
		}

}
</script>