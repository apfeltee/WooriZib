<?php echo form_open("adminnewsconfig/edit_action","id='config_form'");?>
<input type="hidden" name="id" value="<?php echo $config->id?>"/>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			뉴스<small>설정</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>뉴스 설정</li>
			</ul>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-6">
		<div class="help-block"><i class="fa fa-quote-left"></i> 방문자 체류시간, 페이지뷰 등이 증가되고 사이트에 대한 신뢰성이 향상될 수 있습니다. </div>
		<div class="help-block"><i class="fa fa-quote-left"></i> 지역전문가로서 지역에 대한 정보나, 고객 후기, 부동산 소식 등 다양한 콘텐트로 방문자들에게 도움이 되는 소식을 전해주세요. </div>
		<div class="help-block"><i class="fa fa-quote-left"></i> 블로그 발행 기능으로 블로그에도 노출시킬 수 있습니다. </div>
		<div class="help-block"><i class="fa fa-quote-left"></i> 메뉴명은 평범한 메뉴보다는 궁금증을 일으킬만한 센스있는 작명을 해보세요.</div>
		<div class="help-block"><i class="fa fa-quote-left"></i> 설정과 카테고리는 슈퍼관리자만, 글쓰기는 관리자는 누구나 가능합니다.</div>
		<div class="help-block"><i class="fa fa-quote-left"></i> 뉴스 카테고리에서 회원로그인이 필요한 카테고리로 회원가입을 유도할 수 있습니다.</div>
	</div>
	<div class="col-lg-6">
		<div class="portlet">
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th class="text-center vertical-middle">뉴스사용여부</th>
						<td>
							<select name="news_flag" class="form-control select2me">
								<option value="N" <?php if($config->news_flag=="N"){echo "selected";}?>>사용안함</option>
								<option value="Y" <?php if($config->news_flag=="Y"){echo "selected";}?>>사용함</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">메뉴한글명</th>
						<td>
							<input type="text" name="news_ktitle" class="form-control" value="<?php echo $config->news_ktitle;?>" maxlength="6"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">메뉴영문명</th>
						<td>
							<input type="text" name="news_etitle" class="form-control" value="<?php echo $config->news_etitle;?>" maxlength="15"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">댓글설정</th>
						<td>
							<select name="news_reply" class="form-control select2me">
								<option value="N" <?php if($config->news_reply=="N"){echo "selected";}?>>사용안함</option>
								<option value="Y" <?php if($config->news_reply=="Y"){echo "selected";}?>>사용함(누구나 글쓰기 가능)</option>
								<option value="M" <?php if($config->news_reply=="M"){echo "selected";}?>>사용함(회원만 글쓰기 가능)</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="text-center">
				<button type="submit" class="btn blue">저장하기</button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close();?>