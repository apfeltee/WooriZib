<?php echo form_open("adminfront/edit_top","id='config_form'");?>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">탑<small>설정</small></h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a><i class="fa fa-angle-right"></i></li>
				<li>탑 설정</li>
			</ul>
		</div>
	</div>
</div><!-- /.row -->
<div class="row">
	<div class="col-lg-6">
	<div class="portlet">
			<div class="row static-info">
				<div class="col-sm-3 col-xs-4 name">탑 바 사용여부</div>
				<div class="col-md-9 col-xs-4">
					<select id="top_bar" name="top_bar" class="search_item form-control">
						<option value="0" <?php echo ($query->top_bar==0)? "selected" : "";?>>사용안함</option>
						<option value="1" <?php echo ($query->top_bar==1)? "selected" : "";?>>사용함</option>
					</select>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-3 col-xs-4 name">메뉴 타입</div>
				<div class="col-md-9 col-xs-4">
					<select id="menu" name="menu" class="search_item form-control">
						<option value="1" <?php echo ($query->menu==1)? "selected" : "";?>>좌측로고</option>
						<option value="2" <?php echo ($query->menu==2)? "selected" : "";?>>중앙로고</option>
					</select>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-3 col-xs-4 name">메뉴 좌측 설정</div>
				<div class="col-md-9 col-xs-4">
					<select id="menu_left" name="menu_left" class="search_item form-control">
						<option value="0" <?php echo ($query->menu_left==0)? "selected" : "";?>>미사용</option>
						<option value="1" <?php echo ($query->menu_left==1)? "selected" : "";?>>허위 매물이 단 1건이라도 있을 경우 즉시 폐업하겠습니다.</option>
					</select>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-3 col-xs-4 name">메뉴 우측 설정</div>
				<div class="col-md-9 col-xs-8">
					<input type="text" id="menu_right" name="menu_right" placeholder="메뉴 우측에 문구 입력" class="form-control" value="<?php echo $query->menu_right;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-3 col-xs-4 name">메뉴 우측 강조문구</div>
				<div class="col-md-9 col-xs-8">
					<input type="text" id="menu_right_bold" name="menu_right_bold" placeholder="메뉴 우측에 강조문구 입력" class="form-control" value="<?php echo $query->menu_right_bold;?>"/>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-sm-3 col-xs-4 name">네비게이션 메뉴</div>
				<div class="col-md-9 col-xs-4">
					<select id="navbar" name="navbar" class="search_item form-control">
						<option value="0" <?php echo ($query->navbar==0)? "selected" : "";?>>사용안함</option>
						<option value="1" <?php echo ($query->navbar==1)? "selected" : "";?>>사용함</option>
					</select>
				</div>
			</div>
		</div>
		<div class="page-toolbar text-right">
			<button type="submit" class="btn blue">저장하기</button>
		</div>	
	</div><!-- row -->
	<div class="col-lg-6">
		<div class="help-block"><i class="fa fa-quote-left"></i> 탑 배너를 추가하실 경우 맞춰서 제작을 해야하니 요청해 주세요.(유료)</div>
	</div>
</div>
<?php echo form_close();?>