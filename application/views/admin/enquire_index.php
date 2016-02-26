<link href="/assets/admin/css/todo.css" rel="stylesheet" type="text/css"/>
<style>
.modal-dialog{ width:98%;max-width: 780px;/* your width */ }
</style>
<script>
function row_delete(id){
	if(confirm("삭제하시겠습니까?")){
		location.href="/adminenquire/row_delete/"+id;
	}
}
function status_change(label,status){
	if($("input[name='check_id[]']:checked").length==0){
		alert("상태변경할 의뢰를 선택해주시기 바랍니다.");
		return;
	}
	if(confirm("선택된 의뢰를 "+label+"처리 하시겠습니까?")){
		$("input[name='status']").val(status);
		$("#list_form").submit();
	}
}
</script>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			<?php echo lang("enquire.title");?> <small>관리</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="/adminhome/index"><?php echo lang("menu.home");?></a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="#"><?php echo lang("enquire.title");?> 관리</a>
				</li>
			</ul>
			<div class="page-toolbar">
                <div class="dropdown input-inline">
					<?php if($config->IS_DEMO){?>
					<a href="#" class="btn btn-default" onclick="javascript:alert('데모사이트는 사용할 수 없습니다.');">
					<?php } else {?>
					<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">				
					<?php }?>
                        <i class="icon-envelope"></i> 문자 보내기
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
						<li>
							<a href="#" onclick="open_sms();">선택 전송</a>
						</li>
                        <li class="divider"></li>
                        <li>
                            <a href="#" onclick="open_sms('<?php echo ($status) ? $status : "all"?>');">전체 전송</a>
                        </li>
                    </ul>
                </div>
                <div class="dropdown input-inline">
                    <a href="#" class="btn green dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-settings"></i> <?php echo lang("site.status");?> <?php echo lang("site.modify");?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <?php foreach($status_category as $val){?>
						<li class="divider"></li>
						<li>
                            <a href="javascript:status_change('<?php echo $val->label?>','<?php echo $val->name?>');" data-toggle="modal"><?php echo $val->label?> 처리</a>
                        </li>
						<?php }?>
                    </ul>
                </div>
				<?php echo anchor("adminenquire/add",lang("enquire.title") . " 등록","class='btn blue'");?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading"><?php echo lang("site.search");?></div>
		  <div class="panel-body">
			<!-- BEGIN FORM-->
			<?php echo form_open("adminenquire/search/".$status,Array("id"=>"search_form","class"=>"form-inline"))?>
				<div class="form-group">
					<select class="form-control input-small select2me" name="gubun" autocomplete="off">
						 <option value="">전체 구분</option>
						 <option value="buy" <?php if($search["gubun"]=="buy") echo "selected";?>>매수</option>
						 <option value="sell" <?php if($search["gubun"]=="sell") echo "selected";?>>매도</option>
					</select>
				</div>
				<?php if($config->INSTALLATION_FLAG!="2"){?>
				<div class="form-group">
					<select class="form-control input-small select2me" name="type" autocomplete="off">
						<option value="">전체 종류</option>
						<?php if($config->INSTALLATION_FLAG=="1"){?>
						<option value="installation" <?php if($search["type"]=="installation") echo "selected";?>><?php echo lang('installation');?></option>
						<?php }?>
						<option value="sell" <?php if($search["type"]=="sell") echo "selected";?>><?php echo lang('sell');?></option>
						<option value="full_rent" <?php if($search["type"]=="full_rent") echo "selected";?>><?php echo lang('full_rent');?></option>
						<option value="monthly_rent" <?php if($search["type"]=="monthly_rent") echo "selected";?>><?php echo lang('monthly_rent');?></option>
					</select>
				</div>
				<?php }?>

				<div class="form-group">
					<select class="form-control input-medium select2me" name="category" autocomplete="off">
						<option value=""><?php echo lang("search.type");?></option>
						<?php foreach($category as $val){?>
						<option value="<?php echo $val->id;?>" <?php if($search["category"]==$val->id) echo "selected";?>><?php echo $val->name;?></option>
						<?php }?>
					</select>
				</div>

				<div class="form-group">
					<select class="form-control input-large select2me" name="member_id" autocomplete="off">
						<option value="">담당자 전체</option>
						<?php foreach($members as $val) {?>
							<option value="<?php echo $val->id?>" <?php if($val->id==$search["member_id"]){echo "selected";}?>><?php echo $val->name?> (<?php echo $val->email?>, <?php echo $val->phone?>)</option>
						<?php } ?>
					</select>
				</div>
				<div class="input-group">
					<input type="text" class="form-control" name="keyword" placeholder="키워드" autocomplete="off"  value="<?php echo $search["keyword"];?>">
				</div>
				<button type="submit" class="btn btn-warning"><?php echo lang("site.search");?></button>
				<a href="/adminenquire/clean" class="btn btn-default"><?php echo lang("site.initfilter");?></a>
			<?php echo form_close();?>
			<!-- END FORM-->			
		  </div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-xs-12">
		<div role="tabpanel">
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="<?php echo ($status=='' || $status=='all')? "active" : "";?>"><a onclick="location.href='/adminenquire/index'" href="#" aria-controls="#" role="tab" data-toggle="tab">전체(<?php echo $count_all?>)</a></li>
				<?php foreach($status_category as $val){?>
					<li role="presentation" class="<?php echo ($status==$val->name)? "active" : "";?>"><a onclick="location.href='/adminenquire/index/<?php echo $val->name?>'" href="#" aria-controls="#" role="tab" data-toggle="tab"><?php echo $val->label?>(<?php echo ${"count_$val->name"}?>)</a></li>
				<?php }?>
			</ul>
		</div>
		<?php echo form_open("adminenquire/status_change",Array("id"=>"list_form","class"=>"form-horizontal"))?>
		<input type="hidden" name="status"/>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr class="text-center">
                    <th class="text-center" <?php echo lang("site.status");?>="width:25px;"><input type='checkbox' id='check_all'/></th>
					<th class="text-center"><?php echo lang("site.status");?></th>
					<th class="text-center" style="width:70px;">구분</th>
					<th class="text-center">고객명</th>
					<th class="text-center">고객전화번호</th>
					<th class="text-center"><?php echo lang("product.type");?></th>
					<th class="text-center">형태</th>
					<th class="text-center"><?php echo lang("product.owner");?></th>
					<th class="text-center">업무</th>
					<th class="text-center hidden-xs">등록/수정날짜</th>
					<?php if($this->session->userdata("auth_contact")=="Y"){?>
					<th class="text-center hidden-xs">고객추가</th>
					<?php }?>
					<th class="text-center hidden-xs"><?php echo lang("site.delete");?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if(count($query)<1){
					echo "<tr><td colspan='12' class='text-center'>".lang("msg.nodata")."</td></tr>";
				}
				foreach($query as $val){?>
				<tr>
                    <td class='text-center'>
                        <input type='checkbox' class='checkbox' name='check_id[]' value='<?php echo $val['id']?>' self-data='on' data-id='<?php echo $val['id']?>'/>
                    </td>
					<td class='text-center'>
						<?php 
						foreach($status_category as $category){
							if(element("status",$val)=="N") $label_color = "btn-warning";
							if(element("status",$val)=="G") $label_color = "btn-success";
							if(element("status",$val)=="H") $label_color = "btn-info";
							if(element("status",$val)=="F") $label_color = "btn-danger";
							if(element("status",$val)=="D") $label_color = "purple";
							if(element("status",$val)=="Y") $label_color = "btn-primary";
							if(element("status",$val)=="R") $label_color = "green";
							if(element("status",$val)=="X") $label_color = "red";
							if(element("status",$val)=="Y") $label_color = "yellow";
							if(element("status",$val)=="Z") $label_color = "blue";
							if(element("status",$val)==$category->name) echo "<span class='btn btn-xs ".$label_color."'>".$category->label."</span>";
						}
						?>
					</td>
					<td class='text-center'>
						<?php if($val["gubun"]=="sell"){echo "매도";} else {echo "매수";}?>
					</td>
					<td class='text-center'>
						<?php echo anchor("adminenquire/view/".$val["id"],$val["name"]);?>
					</td>
					<td class='text-center'>
						<?php if(MobileCheck()){?>
							<a href="tel:<?php echo $val["phone"];?>"><?php echo $val["phone"];?></a>
							<?php if($val["phone_etc1"]){?>
							<br/><a href="tel:<?php echo $val["phone_etc1"];?>"><?php echo $val["phone_etc1"];?></a>
							<?php }?>
							<?php if($val["phone_etc2"]){?>
							<br/><a href="tel:<?php echo $val["phone_etc2"];?>"><?php echo $val["phone_etc2"];?></a>
							<?php }?>
						<?php }else{?>
						<?php echo $val["phone"];?>
							<?php if($val["phone_etc1"]) echo "<br/>".$val["phone_etc1"];?>
							<?php if($val["phone_etc2"]) echo "<br/>".$val["phone_etc2"];?>
						<?php }?>
					</td>
					<td class='text-center'>
						<?php echo lang($val["type"]);?>
					</td>
					<td class='text-center'>
						<?php foreach( $val["category_list"] as $val2){echo $val2->name . " " ;}?>
					</td>
					<td class='text-center'>
						<?php echo ($val["member_name"]) ? $val["member_name"] : "담당자없음";?>
					</td>
					<td class='text-center'>
						접촉(<?php echo $val["count_contact"];?>)
						메모(<?php echo $val["count_memo"];?>)
						계약(<?php echo $val["count_contract"];?>)
					</td>
					<td class="text-center hidden-xs">
						<?php echo $val["date"];?><br/>
						<?php echo $val["moddate"];?>
					</td>
					<?php if($this->session->userdata("auth_contact")=="Y"){?>
					<td class="text-center hidden-xs">
						<a class="btn btn-success btn-sm" href="/admincontact/add_flashdata/enquire/<?php echo $val["id"];?>">고객전환</a>
					</td>
					<?php }?>
					<td class="text-center hidden-xs">
						<button type="button" class="btn btn-danger btn-sm" onclick="row_delete('<?php echo $val["id"];?>')"><?php echo lang("site.delete");?></button>
					</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		<?php echo form_close();?>
		<div class="row text-center">
			<div class="col-sm-12">
				<ul class="pagination">
					<?php echo $pagination?>
				</ul>
			</div>
		</div>
	</div>
</div>

<!-- SMS FORM-->
<?php echo form_open_multipart("/adminsms/select_send",Array("id"=>"sms_form","class"=>"form-horizontal"))?>
<input type="hidden" name="send_page" value="enquire"/>
<input type="hidden" name="send_all"/>
<div id="check_id_clone"></div>
<div id="sms_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-smsdialog modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">문자 보내기</h4>
			</div>
			<div class="modal-body">
				<div class="form">
					<div class="form-body">
						<div class="form-group alert alert-info" role="alert">
							<div style="font-size:16px;">
								전송 건 : <span id="send_count">0</span>건 (차감 건 : <span id="minus_count">0</span>건)
							</div>
							<div class="margin-top-10">* 단문 : 건당1, 장문 : 건당3, 포토 : 건당10 차감</div>
							<div class="margin-top-10">* 휴대전화가 아니거나 중복된 연락처는 건수에서 제외되어 발송 됩니다.</div>
						</div>
						<div class="form-group" id="sms_result"></div>
						<div class="form-group">
							<div data-toggle="buttons">
								<label class="btn btn-default active">
									<input type="radio" name="sms_type" value="A" checked/><strong>단문(SMS)</strong>
								</label>
								<label class="btn btn-default">
									<input type="radio" name="sms_type" value="C"/><strong>장문(LMS)</strong>
								</label>
								<label class="btn btn-default">
									<input type="radio" name="sms_type" value="D"/><strong>포토(MMS)</strong>
								</label>
							</div>
						</div>
						<div class="form-group display-none lms">
							<input class="form-control" type="text" name="sms_subject" placeholder="제목을 입력하세요." maxlength="30">
						</div>
						<div class="form-group display-none mms">
							<input type="file" class="form-control input-xlarge" name="mms_file" placeholder="이미지파일" style="height:auto" accept="image/jpg, image/jpeg"/>
							</br>* 1MB 이하 파일, JPG형식의 파일만 전송 가능합니다.
						</div>
						<div class="form-group">
							<textarea class="form-control" rows="8" name="sms_msg"></textarea>
						</div>
						<div class="form-group text-left">
							<span class="remaining">
								<span class="count">0</span>/<span class="maxcount">80</span>byte
							</span>
						</div>
						<div class="form-group">
							<div class="inline" data-toggle="buttons">
								<label class="btn btn-default active">
									<input type="radio" name="reserve" value="no" checked> 즉시 발송
								</label>
								<label class="btn btn-default">
									<input type="radio" name="reserve" value="yes"> 예약 발송
								</label>
							</div>
							<div id="reserve_date" class="inline" style="display:none">
								<input type="text" name="r_date" class="form-control input-inline input-small date-picker" placeholder="날짜" autocomplete="off"/>
								<input type="text" name="r_time" class="form-control input-inline input-small timepicker-24" placeholder="시간" autocomplete="off"/>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary pull-left" onclick="javascript:self_send();">나에게 보내기</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
				<button type="submit" class="btn btn-primary">전송</button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close();?>
<link rel="stylesheet" type="text/css" href="/assets/plugin/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/assets/plugin/bootstrap-datepicker/js/locales/bootstrap-datepicker.kr.js"></script>
<script type="text/javascript" src="/assets/plugin/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="/assets/admin/js/sms.js"></script>
<!-- SMS FORM-->