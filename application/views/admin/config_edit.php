<style>
tr{
	height:50px;
}
th, td{
	vertical-align:middle !important;
}
th {
	text-align:right;
	padding:10px;
	width:300px;
}
</style>
<script>
$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */
	
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
			title: "<?php echo lang("site.imageupload");?>",
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
	
	$('#upload_form').ajaxForm({
		success:function(data){
			if(data == ""){
				alert("실패");
				alert(data);
			} 
			else {
				
				 CKEDITOR.instances.content.insertHtml( "<img src='"+data+"'>" );
			} 
			$('#upload_dialog').dialog("close");

		}
	});
});

</script>
<div id="gmap" style="width:0px; height:0px;"></div>
<?php echo form_open("adminhome/config_action","id='config_form'");?>
<input type="hidden" name="id" value="<?php echo $config->id?>"/>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				설정<small>수정</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					설정 수정
				</li>
			</ul>
			<div class="page-toolbar">
				<button type="submit" class="btn blue">저장하기</button>
			</div>
		</div>
	</div>
</div>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th>
				사업자명 <i class="fa fa-question-circle help" data-toggle="tooltip" title="사업자 등록증상에 표기된 이름을 입력해 주세요."></i>
			</th>
			<td>
				<input type="text" name="name" class="form-control" value="<?php echo $config->name;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				사이트명 <i class="fa fa-question-circle help" data-toggle="tooltip" title="사이트명이 없을 경우 사업자명으로 사이트명이 사용됩니다."></i>
			</th>
			<td>
				<input type="text" name="site_name" class="form-control" value="<?php echo $config->site_name;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				접속 IP <i class="fa fa-question-circle help" data-toggle="tooltip" title="저장된 IP에 대해서는 통계시 제외합니다. (접속IP:<?php echo $this->input->ip_address();?>)"></i>
			</th>
			<td>
				<input type="text" name="ip" class="form-control" value="<?php echo $config->ip;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				지도 최대 줌 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="숫자가 작으면 확대가 되는 것이니 너무 지도가 자세하다고 생각하시면 3으로 저장해 보세요."></i>
			</th>
			<td>
				<select name="maxzoom" class="form-control select2me input-small">
					<?php $i = 1;
					for($i=1;$i<8;$i++){?>
					<option value="<?php echo $i;?>" <?php if($config->maxzoom==$i){echo "selected";}?>><?php echo $i;?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				사이트 설명 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="검색엔진에 제공되는 사이트 설명입니다."></i>
			</th>
			<td>
				<input type="text" name="description" class="form-control" value="<?php echo $config->description;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				사이트 키워드 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="검색엔진에 제공되는 키워드입니다."></i>
			</th>
			<td>
				<input type="text" name="keyword" class="form-control" value="<?php echo $config->keyword;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo lang("site.ceo");?> <i  class="fa fa-question-circle help" data-toggle="tooltip" title="원칙적으로 중개사무소 등록증에 기재된 중개업자의 성명(법인은 대표자 성명, 분사무소는 분사무소 책임자 성명)을 표시"></i>
			</th>
			<td>
				<input type="text" name="ceo" class="form-control" value="<?php echo $config->ceo;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo lang("site.email");?>
			</th>
			<td>
				<input type="text" name="email" class="form-control" value="<?php echo $config->email;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo lang("site.biznum");?> <i  class="fa fa-question-circle help" data-toggle="tooltip" title="검색 광고를 하기 위해서는 사업자 번호를 기재해야 합니다."></i>
			</th>
			<td>
				<input type="text" name="biznum" class="form-control" value="<?php echo $config->biznum;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo lang("site.renum");?> <i  class="fa fa-question-circle help" data-toggle="tooltip" title="검색 광고시 잘못된 번호로 입력되어 있으면 광고법에 의거하여 승인거절됩니다."></i>
			</th>
			<td>
				<input type="text" name="renum" class="form-control" value="<?php echo $config->renum;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				대표전화번호 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="KLIS시스템에 신고된 번호를 원칙으로 하며 등록관청에 신고된 사무실 유선 전화번호를 입력해 주세요."></i>
			</th>
			<td>
				<input type="text" name="tel" class="form-control" value="<?php echo $config->tel;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				대표휴대번호 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="방문자가 의뢰하기를 하면 이 번호로 SMS알림이 옵니다."></i>
			</th>
			<td>
				<input type="text" name="mobile" class="form-control" value="<?php echo $config->mobile;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				카카오톡오픈채팅주소 <a href="https://sites.google.com/site/dungzimanual/5-hwal-yong/kakao-opeunchaeting-yeongyeol" target="_blank"><i class="fa fa-question-circle help" data-toggle="tooltip" title="오픈채팅 매뉴얼"></i></a>
			</th>
			<td>
				http://open.kakao.com/o/<input type="text" name="kakaochat" class="form-control input-inline input-small" value="<?php echo $config->kakaochat;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				FAX
			</th>
			<td>
				<input type="text" name="fax" class="form-control" value="<?php echo $config->fax;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				주소(지번) <i  class="fa fa-question-circle help" data-toggle="tooltip" title="회사 소개 메뉴에서 회사 위치로 표시됩니다."></i>
			</th>
			<td>
				<input type="text" name="address" class="form-control" placeholder="주소" style="display:inline;" value="<?php echo toeng($config->address);?>"/> <button type="button" id="get_coord" class="btn btn-primary" style="margin-bottom:5px;">위치 검색</button>
				<input type="text" id="lat" name="lat" class="form-control help" data-toggle="tooltip" title="위치 검색 버튼을 클릭해 주세요." placeholder="위도" style="width:150px;display:inline;" value="<?php echo $config->lat;?>"/> 
				<input type="text" id="lng" name="lng" class="form-control help" data-toggle="tooltip" title="위치 검색 버튼을 클릭해 주세요." placeholder="경도" style="width:150px;display:inline;" value="<?php echo $config->lng;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				주소(도로)
			</th>
			<td>
				<input type="text" name="new_address" class="form-control" placeholder="주소" value="<?php echo $config->new_address;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				카피라이트연도
			</th>
			<td>
				<input type="text" name="year" class="form-control" placeholder="카피라이트연도" value="<?php echo $config->year;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				설명 <i class="fa fa-question-circle help" data-toggle="tooltip" title="회사 소개 메뉴에서 설명 내용으로 표시됩니다."></i>
			</th>
			<td>
				<textarea name="content" rows="5" class="ckeditor form-control"><?php echo $config->content;?></textarea>
			</td>
		</tr>
	</tbody>
</table>
<h4>사이트 책임 관리자 지정</h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th>
				<?php echo lang("product.owner");?>
			</th>
			<td>
				<select name="site_admin" class="form-control input-large">
					<?php foreach($members as $val) {?>
						<option value="<?php echo $val->id?>" <?php if($config->site_admin==$val->id) echo "selected"?>><?php echo $val->name?> (<?php echo $val->email?>, <?php echo $val->phone?>)</option>
					<?php } ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<h4>네이버 검색 연동 설정<a href="https://sites.google.com/site/dungzimanual/5-hwal-yong/neibeogeomsaeg-yeondong" target="_blank"> <i class="fa fa-question-circle"></i></a></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th>
				네이버 웹마스터도구 키
			</th>
			<td>
				<input type="text" name="naverwebmasterkey" class="form-control" value="<?php echo $config->naverwebmasterkey;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				연동키(token)
			</th>
			<td>
				<input type="text" name="naverwebmastertoken" class="form-control" value="<?php echo $config->naverwebmastertoken;?>"/>
			</td>
		</tr>
	</tbody>
</table>
<h4>네이버 카페 연동 설정<a href="https://sites.google.com/site/dungzimanual/5-kapeyeondong" target="_blank"> <i class="fa fa-question-circle"></i></a></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th>
				네이버컨슈머키
			</th>
			<td>
				<input type="text" name="navercskey" class="form-control" value="<?php echo $config->navercskey;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				네이버컨슈머시크릿
			</th>
			<td>
				<input type="text" name="navercssecret" class="form-control" value="<?php echo $config->navercssecret;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				네이버 Client ID
			</th>
			<td>
				<input type="text" name="naverclientkey" class="form-control" value="<?php echo $config->naverclientkey;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				네이버 Client Secret
			</th>
			<td>
				<input type="text" name="naverclientsecret" class="form-control" value="<?php echo $config->naverclientsecret;?>"/>
			</td>
		</tr>
	</tbody>
</table>

<h4>다음 블로그 연동 설정<a href="#" target="_blank"> <i class="fa fa-question-circle"></i></a></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th>
				다음 Client ID
			</th>
			<td>
				<input type="text" name="daumclientkey" class="form-control" value="<?php echo $config->daumclientkey;?>"/>
			</td>
		</tr>
		<tr>
			<th>
				다음 Client Secret
			</th>
			<td>
				<input type="text" name="daumclientsecret" class="form-control" value="<?php echo $config->daumclientsecret;?>"/>
			</td>
		</tr>
	</tbody>
</table>

<h4>구글 분석 연결<a href="https://sites.google.com/site/dungzimanual/9-logeubunseog/gugeul-eonaellitigseu" target="_blank"> <i class="fa fa-question-circle"></i></a></h4>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th>
				구글 분석 추적코드
			</th>
			<td>
				<input type="text" name="glogkey" class="form-control" value="<?php echo $config->glogkey;?>"/>
			</td>
		</tr>
	</tbody>
</table>
<?php echo form_close();?>

<script>
	CKEDITOR.replace( 'content', {customConfig: '/ckeditor/dungzi_config.js'});
</script>

<div id="upload_dialog" title="이미지업로드">
<?php echo form_open_multipart("adminhome/upload_action","id='upload_form' autocomplete='off'");?>
<div class="help-block">* 넓이(폭)이 700픽셀 이하로 조정됩니다.</div>
<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
<?php echo form_close();?>
</div>