<style>
tr{
	height:50px;
}
th, td{
	vertical-align:middle !important;
}
th {
	text-align:center;
	padding:10px;
	width:300px;
}
</style>
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

	$("#get_coord").click(function(){
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
		    	}
			}
		);
	});

	$("#upload_dialog").dialog({
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:400,
			height: 230,
			modal: true,
			buttons: {
				'이미지 등록': function() {
					$("#upload_form").submit();
				}
			}
	});

	$('#delete_watermark').click(function(){
		var watermark = '<?php echo $config->watermark?>';
		if(!watermark || $('#watermark_image').hasClass("is-delete")){
			alert("<?php echo lang("msg.nodata");?>");
			return false;
		}
		if(confirm("워터마크가 바로 삭제 됩니다. 삭제하시겠습니까?")){
			$.ajax({
				url: "/adminhome/delete_watermark_image",
				type: "POST",
				data: {
					watermark: watermark
				},
				success: function(data) {
					$('#watermark_image').addClass("is-delete");
					$('#watermark_image').html("등록된 워터마크가 없습니다. 워터마크를 등록해 주세요.");
					msg($("#watermark_msg"), "success" ,"삭제 되었습니다.");
				}
			});		
		}
	});
	$('input[name="watermark"]').change(function(e){
		msg($("#watermark_msg"), "info" ,$(this).val());
	});

});

</script>
<div id="gmap" style="width:0px; height:0px;"></div>
<?php echo form_open_multipart("adminhome/config_etc_action","id='config_form'");?>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			로고<small>수정</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					로고 수정
				</li>
			</ul>
			<div class="page-toolbar">
				<button type="submit" class="btn blue">저장하기</button>
			</div>
		</div>
	</div>
</div>

<?php if($code=="error"){?>
<div class="alert alert-danger" role="alert">업로드하는 로고 이미지의 높이는 60픽셀을 초과할 수 없습니다.</div>
<?php }?>
<?php if($code=="error_watermark"){?>
<div class="alert alert-danger" role="alert">업로드하는 워터마크의 이미지의 높이는 가로 200픽셀 * 세로 200픽셀을 초과할 수 없습니다.</div>
<?php }?>
<h4><strong>이미지 정보</strong></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th>로고</th>
			<td>
			<?php if($config->logo==""){?>
				<?php echo lang("msg.nodata");?><br/>
			<?php } else {?>
				<img src="/uploads/logo/<?php echo $config->logo;?>"/><br/>
			<?php } ?>
				<input type="file" name="logo" class="form-control input-xlarge" style="height:auto;"/> 
				<div class="help-block">*최적 200*60 픽셀</div>
			</td>
		</tr>
		<tr>
			<th>하단 로고</th>
			<td>
			<?php if($config->footer_logo==""){?>
				<?php echo lang("msg.nodata");?><br/>
			<?php } else {?>
				<img src="/uploads/logo/<?php echo $config->footer_logo;?>"/><br/>
			<?php } ?>
				<input type="file" name="footer_logo" class="form-control input-xlarge" style="height:auto;"/> 
				<div class="help-block">*최적 200*60 픽셀</div>
			</td>
		</tr>
		<tr>
			<th>대표 이미지 없음</th>
			<td>
			<?php if($config->no==""){?>
				<?php echo lang("product");?> <?php echo lang("msg.nodata");?><br/>
				<div class="help-block"><b>(기본 이미지)</b><br/><img src="/assets/common/img/no_thumb.png"></div>
			<?php } else {?>
				<img src="/uploads/logo/thumb/<?php echo $config->no;?>"/><br/>
			<?php } ?>
				<input type="file" name="no" class="form-control input-xlarge" style="height:auto;"/> 
			</td>
		</tr>
		<tr>
			<th>워터마크</th>
			<td>
			<?php if($config->watermark==""){?>
				<div id="watermark_image"><?php echo lang("msg.nodata");?></div>
			<?php } else {?>
				<div id="watermark_image"><img src="/uploads/logo/<?php echo $config->watermark;?>"/></div>
			<?php }?>
				<div id="watermark_msg"></div>
				<span class="btn btn-default btn-file margin-top-10">워터마크 업로드<input type="file" name="watermark"/>
				</span>
				<span id="delete_watermark" class="btn btn-primary"><i class="fa fa-trash-o"></i> 워터마크 삭제</span><span> (*최적 200*60 픽셀)</span>
			</td>
		</tr>
		<tr>
			<th>워터마크 위치</th>
			<td>
				상하
				<select name="watermark_position_vertical" class="form-control input-inline input-small">
					<option value="middle" <?php echo ($config->watermark_position_vertical=='middle')?"selected":"";?>>중앙</center>
					<option value="top" <?php echo ($config->watermark_position_vertical=='top')?"selected":"";?>>위</center>
					<option value="bottom" <?php echo ($config->watermark_position_vertical=='bottom')?"selected":"";?>>아래</center>
				</select>
				좌우
				<select name="watermark_position_horizontal" class="form-control input-inline input-small">
					<option value="center" <?php echo ($config->watermark_position_horizontal=='center')?"selected":"";?>>중앙</center>
					<option value="left" <?php echo ($config->watermark_position_horizontal=='left')?"selected":"";?>>왼쪽</center>
					<option value="right" <?php echo ($config->watermark_position_horizontal=='right')?"selected":"";?>>오른쪽</center>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<h4><strong>Cafe24 SMS 정보</strong></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th>아이디</th>
			<td>
				<input type="text" name="sms_id" class="form-control" value="<?php echo $config->sms_id;?>"/>
			</td>
		</tr>
		<tr>
			<th>키</th>
			<td>
				<input type="text" name="sms_key" class="form-control" value="<?php echo $config->sms_key;?>"/>
			</td>
		</tr>
	</tbody>
</table>
<?php echo form_close();?>