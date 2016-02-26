<script>
$(document).ready(function(){
	
	var map = new google.maps.Map( document.getElementById("gmap"),  {
		center: new google.maps.LatLng(0,0),
		zoom: 3,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		panControl: false,
		streetViewControl: false,
		mapTypeControl: false
	});


		var geocoder = new google.maps.Geocoder(); 
		geocoder.geocode({
				address : jQuery('input[name=address]').val(), 
				region: 'no' 
			},
		    function(results, status) {
		    	if (status.toLowerCase() == 'ok') {
					
					var coords = new google.maps.LatLng(
						results[0]['geometry']['location'].lat(),
						results[0]['geometry']['location'].lng()
					);

					$('#lat').val(coords.lat());
					$('#lng').val(coords.lng());
					$("#product_form").submit();
		    	}
			}
		);

});
</script>
<div id="gmap" style="width:0px; height:0px;"></div>
	   <div class="row">
          <div class="col-lg-12">
            <h1>시도구군 위치<small>수정</small></h1>
            <ol class="breadcrumb">
              <li><a href="/adminhome/index"><i class="icon-dashboard"></i> <?php echo lang("menu.home");?></a></li>
              <li><a href="/adminarea/index"><i class="icon-dashboard"></i> <?php echo lang("site.address");?> 관리</a></li>
              <li class="active"><i class="icon-file-alt"></i> 수정</li>
            </ol>
          </div>
        </div><!-- /.row -->
<?php echo form_open("adminarea/address_action","id='product_form'");?>
	<table class="table table-bordered table-striped table-condensed flip-content">
		<tr>
			<th width="15%">번호</th>
			<td>
				<input type="text" name="id" class="form-control" value="<?php echo $query->id;?>"/>
			</td>
		</tr>
		<tr>
			<th width="15%"><?php echo lang("site.address");?></th>
			<td>
				<input type="text" name="address" class="form-control" value="<?php echo $query->sido;?> <?php echo $query->gugun;?> <?php echo $query->dong;?>"/>
			</td>
		</tr>
		<tr>
			<th width="15%">lat</th>
			<td>
				<input type="text" id="lat" name="lat" class="form-control"/>
			</td>
		</tr>
		<tr>
			<th width="15%">lng</th>
			<td>
				<input type="text" id="lng" name="lng" class="form-control"/>
			</td>
		</tr>
	</table>
	<div style="text-align:right;">
		<button type="submit" class="btn btn-primary">위치 수정</button>
	</div>
<?php echo form_close();?>
		
	</div>
</div>