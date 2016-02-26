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
			alert("현재 등록된 등록된 워터마크가 없습니다.");
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
				<li><i class="fa fa-home"></i> <a href="/adminhome/index">홈</a> <i class="fa fa-angle-right"></i> </li>
				<li>
					로고 수정
				</li>
			</ul>
			<div class="page-toolbar">
				<button type="submit" class="btn blue">저장</button>
			</div>
		</div>
	</div>
</div><!-- /.row -->

 <div class="row">
	 <div class="col-lg-12">
			<?php if($code=="error"){?><div class="alert alert-danger" role="alert">업로드하는 로고 이미지의 높이는 60픽셀을 초과할 수 없습니다.</div><?php }?>
			<?php if($code=="error_watermark"){?><div class="alert alert-danger" role="alert">업로드하는 워터마크의 이미지의 높이는 가로 200픽셀 * 세로 200픽셀을 초과할 수 없습니다.</div><?php }?>
			<h4>이미지 정보</h4>
			<div class="portlet">
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name">로고</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php if($config->logo==""){?>
							등록된 로고가 없습니다. 로고를 등록해 주세요.<br/>
						<?php } else {?>
							<img src="/uploads/logo/<?php echo $config->logo;?>"/><br/>
						<?php } ?>
						<input type="file" name="logo" class="form-control"/> 
						<div class="help-block">*최적 200*60 픽셀</div>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name">푸터로고</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php if($config->footer_logo==""){?>
							등록된 푸터 로고가 없습니다. 푸터 로고를 등록해 주세요. 없을 경우 로고를 사용합니다.<br/>
						<?php } else {?>
							<img src="/uploads/logo/<?php echo $config->footer_logo;?>"/><br/>
						<?php } ?>
						<input type="file" name="footer_logo" class="form-control"/> 
						<div class="help-block">*최적 200*60 픽셀</div>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name">대표 이미지 없음</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php if($config->no==""){?>
							<?php echo lang("product");?> 등록시 대표이미지가 없을 경우 보여주는 이미지가 없습니다.  (사이즈제한없음)<br/>
						<?php } else {?>
							<img src="/uploads/logo/thumb/<?php echo $config->no;?>"/><br/>
						<?php } ?>
						<input type="file" name="no" class="form-control"/> 
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name">워터마크</div>
					<div class="col-sm-10 col-xs-8 value">
					<?php if($config->watermark==""){?>
						<div id="watermark_image">등록된 워터마크가 없습니다. 워터마크를 등록해 주세요.</div>
					<?php } else {?>
						<div id="watermark_image"><img src="/uploads/logo/<?php echo $config->watermark;?>"/></div>
					<?php }?>
						<div id="watermark_msg"></div>
						<span class="btn btn-default btn-file margin-top-10">워터마크 업로드<input type="file" name="watermark"/>
						</span>
						<span id="delete_watermark" class="btn btn-primary"><i class="fa fa-trash-o"></i> 워터마크 삭제</span><span> (*최적 200*60 픽셀)</span>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name">워터마크 위치</div>
					<div class="col-sm-10 col-xs-8 value">
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
					</div>
				</div>

			</div>

			<h4>Cafe24 SMS 정보</h4>
			<div class="portlet">
				<div class="row static-info">
					<div class="col-sm-2 col-xs-3 name">아이디</div>
					<div class="col-sm-10 col-xs-9 value">
						<input type="text" name="sms_id" class="form-control" value="<?php echo $config->sms_id;?>"/>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-3 name">키</div>
					<div class="col-sm-10 col-xs-9 value">
						<input type="text" name="sms_key" class="form-control" value="<?php echo $config->sms_key;?>"/>
					</div>
				</div>
			</div>

	</div><!-- row -->
</div> <!-- container-fluid -->
<?php echo form_close();?>

<div id="upload_dialog" title="이미지업로드" style="display:none;padding:10px 0px 0px 0px;">
<div style="padding:10px;">
<?php echo form_open_multipart("adminhome/upload_action","id='upload_form' autocomplete='off'");?>
<div class="help-block">* 넓이(폭)이 700픽셀 이하로 조정됩니다.</div>
<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
<?php echo form_close();?>
</div>
</div>