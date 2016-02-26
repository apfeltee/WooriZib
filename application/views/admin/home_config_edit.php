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
			title: "이미지업로드",
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
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				설정<small>수정</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index">홈</a> <i class="fa fa-angle-right"></i> </li>
				<li>
					설정 수정
				</li>
			</ul>
			<div class="page-toolbar">
				<button type="submit" class="btn blue">저장하기</button>
			</div>
		</div>
	</div>
</div><!-- /.row -->

<input type="hidden" name="id" value="<?php echo $config->id?>"/>
<div class="portlet">
	 <div class="row">
		<div class="col-lg-12">

			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">사업자명 <i class="fa fa-question-circle help" data-toggle="tooltip" title="사업자 등록증상에 표기된 이름을 입력해 주세요."></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="name" class="form-control" value="<?php echo $config->name;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name_plain">사이트명 <i class="fa fa-question-circle help" data-toggle="tooltip" title="사이트명이 없을 경우 사업자명으로 사이트명이 사용됩니다."></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="site_name" class="form-control" value="<?php echo $config->site_name;?>"/>
				</div>
			</div>			
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">접속 IP <i class="fa fa-question-circle help" data-toggle="tooltip" title="저장된 IP에 대해서는 통계시 제외합니다. (접속IP:<?php echo $this->input->ip_address();?>)"></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="ip" class="form-control" value="<?php echo $config->ip;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">지도 최대 줌 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="숫자가 작으면 확대가 되는 것이니 너무 지도가 자세하다고 생각하시면 3으로 저장해 보세요."></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<select name="maxzoom" class="form-control select2me input-small">
						<?php $i = 1;
						for($i=1;$i<8;$i++){?>
						<option value="<?php echo $i;?>" <?php if($config->maxzoom==$i){echo "selected";}?>><?php echo $i;?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">사이트 설명 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="검색엔진에 제공되는 사이트 설명입니다."></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="description" class="form-control" value="<?php echo $config->description;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">사이트 키워드 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="검색엔진에 제공되는 키워드입니다."></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="keyword" class="form-control" value="<?php echo $config->keyword;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">대표자명 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="원칙적으로 중개사무소 등록증에 기재된 중개업자의 성명(법인은 대표자 성명, 분사무소는 분사무소 책임자 성명)을 표시"></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="ceo" class="form-control" value="<?php echo $config->ceo;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">대표이메일</div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="email" class="form-control" value="<?php echo $config->email;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">사업자번호 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="검색 광고를 하기 위해서는 사업자 번호를 기재해야 합니다."></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="biznum" class="form-control" value="<?php echo $config->biznum;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">부동산등록번호 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="검색 광고시 잘못된 번호로 입력되어 있으면 광고법에 의거하여 승인거절됩니다."></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="renum" class="form-control" value="<?php echo $config->renum;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">대표전화번호 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="KLIS시스템에 신고된 번호를 원칙으로 하며 등록관청에 신고된 사무실 유선 전화번호를 입력해 주세요."></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="tel" class="form-control" value="<?php echo $config->tel;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">대표휴대번호 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="방문자가 의뢰하기를 하면 이 번호로 SMS알림이 옵니다."></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="mobile" class="form-control" value="<?php echo $config->mobile;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">FAX</div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="fax" class="form-control" value="<?php echo $config->fax;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">주소(지번) <i  class="fa fa-question-circle help" data-toggle="tooltip" title="회사 소개 메뉴에서 회사 위치로 표시됩니다."></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="address" class="form-control" placeholder="주소" style="display:inline;" value="<?php echo $config->address;?>"/> <button type="button" id="get_coord" class="btn btn-primary" style="margin-bottom:5px;">좌표가져오기</button>
					<input type="text" id="lat" name="lat" class="form-control help" data-toggle="tooltip" title="좌표가져오기 버튼을 클릭해 주세요." placeholder="위도" style="width:150px;display:inline;" value="<?php echo $config->lat;?>"/> 
					<input type="text" id="lng" name="lng" class="form-control help" data-toggle="tooltip" title="좌표가져오기 버튼을 클릭해 주세요." placeholder="경도" style="width:150px;display:inline;" value="<?php echo $config->lng;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">주소(도로)</div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="new_address" class="form-control" placeholder="주소" value="<?php echo $config->new_address;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">카피라이트연도</div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="year" class="form-control" placeholder="카피라이트연도" value="<?php echo $config->year;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">설명 <i  class="fa fa-question-circle help" data-toggle="tooltip" title="회사 소개 메뉴에서 설명 내용으로 표시됩니다."></i></div>
				<div class="col-sm-10 col-xs-8 value">
					<textarea name="content" rows="5" class="ckeditor form-control"><?php echo $config->content;?></textarea>
				</div>
			</div>
		</div>
	</div>
</div>

<h4>사이트 책임 관리자 지정<a href="#" target="_blank"><i class="fa fa-question-circle"></i></a></h4>
<div class="portlet">
	 <div class="row">
		<div class="col-lg-12">
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">담당자</div>
				<div class="col-sm-10 col-xs-8 value">
					<select name="site_admin" class="form-control input-large">
						<?php foreach($members as $val) {?>
							<option value="<?php echo $val->id?>" <?php if($config->site_admin==$val->id) echo "selected"?>><?php echo $val->name?> (<?php echo $val->email?>, <?php echo $val->phone?>)</option>
						<?php } ?>
					</select>
				</div>
			</div>		
		</div>
	</div>
</div>

<h4>네이버 검색 연동 설정<a href="https://sites.google.com/site/dungzimanual/5-hwal-yong/neibeogeomsaeg-yeondong" target="_blank"><i class="fa fa-question-circle"></i></a></h4>
<div class="portlet">
	 <div class="row">
		<div class="col-lg-12">
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">네이버 웹마스터도구 키</div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="naverwebmasterkey" class="form-control" value="<?php echo $config->naverwebmasterkey;?>">
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">연동키(token)</div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="naverwebmastertoken" class="form-control" value="<?php echo $config->naverwebmastertoken;?>">
				</div>
			</div>			
		</div>
	</div>
</div>

<h4>네이버 카페 연동 설정<a href="https://sites.google.com/site/dungzimanual/5-kapeyeondong" target="_blank"><i class="fa fa-question-circle"></i></a></h4>
<div class="portlet">
	 <div class="row">
		<div class="col-lg-12">
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">네이버컨슈머키</div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="navercskey" class="form-control" value="<?php echo $config->navercskey;?>">
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">네이버컨슈머시크릿</div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="navercssecret" class="form-control" value="<?php echo $config->navercssecret;?>">
				</div>
			</div>	
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">네이버 Client ID</div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="naverclientkey" class="form-control" value="<?php echo $config->naverclientkey;?>">
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">네이버 Client Secret</div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="naverclientsecret" class="form-control" value="<?php echo $config->naverclientsecret;?>">
				</div>
			</div>					
		</div>
	</div>
</div>

<h4>구글 분석 연결<a href="https://sites.google.com/site/dungzimanual/9-logeubunseog/gugeul-eonaellitigseu" target="_blank"><i class="fa fa-question-circle"></i></a></h4>
<div class="portlet">
	 <div class="row">
		<div class="col-lg-12">
			<div class="row static-info">
				<div class="col-sm-2 col-xs-4 name">구글 분석 추적코드</div>
				<div class="col-sm-10 col-xs-8 value">
					<input type="text" name="glogkey" class="form-control" value="<?php echo $config->glogkey;?>">
				</div>
			</div>
		</div>
	</div>
</div>
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