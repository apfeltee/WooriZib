<?php 
	$position = array(
		"middle"	=> "중앙",
		"top"		=> "위",
		"bottom"	=> "아래",
		"center"	=> "중앙",
		"left"		=> "왼쪽",
		"right"		=> "오른쪽"
	);
?>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				로고<small>보기</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index">홈</a> <i class="fa fa-angle-right"></i> </li>
				<li>
					로고 보기
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="location.href='/adminhome/config_etc_edit'">수정</button>
			</div>
		</div>
	</div>
</div><!-- /.row -->

 <div class="row">
	 <div class="col-lg-12">

			<h4>이미지 정보</h4>
			<div class="portlet">
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">로고</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php if($config->logo==""){?>
							등록된 로고가 없습니다. 로고를 등록해 주세요. (최적: 200픽셀 * 60픽셀)
						<?php } else {?>
							<img src="/uploads/logo/<?php echo $config->logo;?>"/>
						<?php } ?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">푸터 로고</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php if($config->footer_logo==""){?>
							등록된 푸터 로고가 없습니다. 푸터 로고를 등록해 주세요. (최적: 200픽셀 * 60픽셀)
						<?php } else {?>
							<img src="/uploads/logo/<?php echo $config->footer_logo;?>"/>
						<?php } ?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">대표 이미지 없음</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php if($config->no==""){?>
							<?php echo lang("product");?> 등록 시 하단에 기본 "이미지없음" 이미지 대신 자신 만의 이미지를 사용하시려면 업로드해주세요.
						<?php } else {?>
							<img src="/uploads/logo/thumb/<?php echo $config->no;?>"/>
						<?php } ?>
						<br>
						<div class="help-block"><b>(기본 이미지)</b><br/><img src="/assets/common/img/no_thumb.png"></div>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">워터마크</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php if($config->watermark==""){?>
							등록된 워터마크가 없습니다. 워터마크를 등록해 주세요. (최적: 200픽셀 * 60픽셀)
						<?php } else {?>
							<img src="/uploads/logo/<?php echo $config->watermark;?>"/>
						<?php } ?>
					</div>
				</div>

				<div class="row static-info">
					<div class="col-sm-2 col-xs-4 name_plain">워터마크 위치</div>
					<div class="col-sm-10 col-xs-8 value">
						<?php if($config->watermark_position_vertical=="" || $config->watermark_position_horizontal==""){?>
							워터마크의 위치가 지정되지 않았습니다.
						<?php } else {?>
							세로 : <?php echo $position[$config->watermark_position_vertical];?> / 
							가로 : <?php echo $position[$config->watermark_position_horizontal];?>
						<?php } ?>
					</div>
				</div>
			</div>
			<h4>Cafe24 SMS 정보</h4>
			<div class="portlet">
				<div class="row static-info">
					<div class="col-sm-2 col-xs-3 name_plain">아이디</div>
					<div class="col-sm-10 col-xs-9 value">
						<?php echo $config->sms_id;?>
					</div>
				</div>
				<div class="row static-info">
					<div class="col-sm-2 col-xs-3 name_plain">키</div>
					<div class="col-sm-10 col-xs-9 value">
						<input class="form-control" type="text" placeholder="<?php echo $config->sms_key;?>" readonly>
					</div>
				</div>
			</div>

	</div><!-- row -->
</div> 
