<style>
.all_exe {
	padding:10px 15px;
}
</style>
<script src="/assets/plugin/organictabs.jquery.js"></script>
<script type="text/javascript" src="/assets/basic/js/search_installation.js"></script>
<script>
	var sell_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("sell_unit");?></font>";
	var price_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("price_unit");?></font>";
	var address_type="admin"; /** 공개/비공개 매물 등록된 주소 가져오도록 **/
</script>
<script>
var total;

$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	init_search("<?php echo element('type',$search_installation);?>","<?php echo element('category',$search_installation);?>");

	$('#search_form').ajaxForm( {
		beforeSubmit: function()
		{
			/** map은 여기서 move_map을 했는데 grid는 그럴 필요가 없으니까 그냥 냅두자 **/
		},
		success: function(data)
		{
			$.ajax({
				type: 'GET',
				url: '/admininstallation/listing_json/'+$("#next_page").val(),
				cache: false,
				dataType: 'json',	
				beforeSend: function(){
					loading_delay(true);
				},
				success: function(jsonData){
					var str = "";
					$.each(jsonData, function(rkey, rval) {
						if(rkey=="result") {
							str = rval;
						}
						if(rkey=="total"){
							total = rval;
							$(".result_label").html("<i class=\"fa fa-search\"></i> <?php echo lang("search.result");?> " + rval);
						}

						if(rkey=="paging"){

							$("#paging").html(rval);
							$("#paging").find("a").on("click", function() {
								$("#next_page").val( $(this).attr('href').replace("/","") );
								$('#search_form').trigger('submit');
								return false;
							});

						}
					});

					$("#search-items").html(str);			
					
					$('.help').tooltip();	

					loading_delay(false);

				}
			});
		}
	});
	
	$("#go_keyword").click(function(){
		$('#search_form').trigger('submit');
	});

	$("#remove_keyword").click(function(){
		$("#keyword").val("");
		$('#search_form').trigger('submit');
	});

	$('#check_all').change(function(){
		var check_state = $(this).prop('checked');
		$("input[name='check_installation[]']").each(function(i){
            if($(this).attr('self-data')){
                $(this).prop('checked',check_state);
            }
        });
	});

	$('#delete_installation').click(function(){
		var check_length = $("input[name='check_installation[]']:checked").length;
		if(!check_length){
			alert('삭제할 <?php echo lang("installation");?>(를)을 선택 해주시기 바랍니다.');
			return;
		}
		else{
			if(confirm('선택한 <?php echo lang("installation");?>(를)을 삭제 하시겠습니까?')){
				$('#list_form').submit();
			}
		}
	});

	$("#search_tab").organicTabs();

	<?php if( element("search_type",$search_installation) == ""){?>
		var only = "<?php echo element('only',$search_installation)?>";
		var admin_member_id = "<?php echo element('search_admin_member_id',$search_installation)?>";
		var auth_id = "<?php echo $this->session->userdata('auth_id')?>";
		if(only=="") only = "go";
		$("#only").val(only);
		if(auth_id!="1"){
			if(admin_member_id=="") admin_member_id = "<?php echo $this->session->userdata('admin_id')?>";
			$("#search_admin_member_id").val(admin_member_id);		
		}
		$("#search_form").trigger("submit");
	<?php }?>

	$(".search_item").change(function(){
		init_price();
		$('#search_form').trigger('submit');
	});

	$(".search_item").change(function(){
		$("#next_page").val("0");
		$('#search_form').trigger('submit');
	});

	$("#admin_member_id").change(function(){
		$("#search_admin_member_id").val($(this).val());
		$('#search_form').trigger('submit');
	});

	$('.category_checkbox').on('ifChanged', function(event){
		$("#search_form").trigger("submit");
	});

	/*** <?php echo lang("installation");?> 번호를 넣고 클릭을 하면 이동하는 기능 **/
	$("#go_num").click(function(data){
		$.get("/installation/have_num/"+$("#num").val()+"/"+Math.round(new Date().getTime()),function(data){
			if(data=="1"){
				location.href="/admininstallation/view/"+$("#num").val();
			} else {
				alert("해당 <?php echo lang("installation");?>번호의 <?php echo lang("installation");?>이(가) 없습니다.");
			}
		});
	});

});

function change(type, id, status){
	if(confirm("상태를 변경하시겠습니까?")){
		$.get("/admininstallation/change/"+type+"/"+id+"/"+status+"/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				location.href="/admininstallation/index/"+$("#next_page").val();
		   } else {
				alert("변경 실패");
		   }
		})
	}
}

function blog(id){
	window.open("/adminblogapi/blog_popup/"+id+"/installation","blog_window","width=460, height=700, resizable=no, scrollbars=no, status=no;");
}

function cafe(id){
	window.open("/admincafeapi/OAuth/"+id+"/"+"installation","cafe_window","width=460, height=760, resizable=no, scrollbars=no, status=no;");
}

function delete_installation(id){
	if(confirm("<?php echo lang("installation");?>(를)을 삭제하시겠습니까?\n<?php echo lang("installation");?>삭제는 관리자와 등록한 직원만 가능합니다.")){
		location.href="/admininstallation/delete_installation/"+id;
	}
}

function get_check_list(id){
	$.ajax({
		url: "/admininstallation/get_check_list",
		type: "POST",
		dataType: "json",
		cash: false,
		async: false,
		data: {
			installation_id : id
		},
		success: function(data) {
			var str = "";
			$.each(data, function(key, val) {
				if(key==0){
					str +=	'<tr><td>'+val['date']+' <i class="fa fa-check"></i> 최종확인</td></tr>';
				}
				else {
					str +=	'<tr><td>'+val['date']+'</td></tr>';
				}
			});
			$("#check_list").html(str);
		}
	});	
}

function all_exe(exe_type,exe_value){

	var check_length = $("input[name='check_installation[]']:checked").length;
	if(!check_length){
		alert('일괄실행할 <?php echo lang("installation");?>(를)을 선택 해주시기 바랍니다.');
		return;
	}

	$("#exe_type").val(exe_type);
	$("#exe_value").val(exe_value);
	$('#list_form').attr('action', '/admininstallation/exe_all');
	$('#list_form').submit();
}
</script>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				<?php echo lang("installation");?><small>목록</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<?php echo lang("installation");?> 관리
				</li>
			</ul>
			<div>
				<div class="sorting pull-right">
					<div class="input-inline">
						<div class="input-inline"><i class="fa fa-user"></i> 직원검색 </div>
						<select id="admin_member_id" class="form-control input-inline" autocomplete="off">
							<option value=""><?php echo lang("site.all");?></option>
							<?php foreach($members as $val){?>
							<option value="<?php echo $val->id?>" <?php if(element("search_admin_member_id",$search_installation)==$val->id) {echo "selected";}?>><?php echo $val->name?> (<?php echo $val->email?>) <?php echo $val->cnt?>건</option>
							<?php }?>
						</select>
					</div>
					<div class="input-inline" style="margin-left:10px;">						
						<div class="dropdown input-inline">
							<a href="#" class="btn green dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
								<i class="icon-settings"></i> 선택 일괄실행
							</a>
							<ul class="dropdown-menu dropdown-menu-default">
								<li class="all_exe">
									<span>
										<button class="btn btn-default btn-sm" onclick="all_exe('refresh')"><?php echo lang("site.refresh");?></button>
									</span>									
								</li>
								<li class="divider"></li>
								<li class="all_exe">
									<span>
										<button class="btn btn-xs blue" onclick="all_exe('is_valid',1)">승인하기</button>
										<button class="btn btn-xs red" onclick="all_exe('is_valid',0)">비승인하기</button>
									</span>
								</li>
								<li class="all_exe">
									<span>
										<button class="btn btn-xs blue" onclick="all_exe('is_activated',1)">공개하기</button>
										<button class="btn btn-xs red" onclick="all_exe('is_activated',0)">비공개하기</button>
									</span>
								</li>
								<li class="all_exe">
									<span>
										<button class="btn btn-xs blue" onclick="all_exe('recommand',1)">추천하기</button>
										<button class="btn btn-xs red" onclick="all_exe('recommand',0)">추천해제</button>
									</span>
								</li>
								<li class="all_exe">
									<span>
										<button class="btn btn-xs blue" onclick="all_exe('is_finished',1)">완료처리</button>
										<button class="btn btn-xs red" onclick="all_exe('is_finished',0)">완료취소</button>
									</span>
								</li>
							</ul>
						</div>
						<button class="btn blue" onclick="location.href='/admininstallation/add'">등록</button>
						<button class="btn red" id="delete_installation"><?php echo lang("site.delete");?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.row -->

<div class="row">
	<div class="col-lg-3">
		<!-- search start -->
			<div style="text-align:center;margin-bottom:10px;">
			<a href="/admininstallation/clean" class="btn btn-default" style="width:100%"><i class="fa fa-toggle-on"></i> 검색 초기화</a>
			</div>
		    <div class="search-wrapper">
		    	<div class="row margin-bottom-10">
				     <div class="col-lg-12">
				          <label><?php echo lang("installation");?>번호</label>
				          <div class="input-group">
				               <input type="text" id="num"  name="num" placeholder="<?php echo lang("installation");?>번호" class="form-control"/>
				               <div class="input-group-btn">
				                    <button id="go_num" class="btn btn-default" type="button">이동</button>
				               </div>
				          </div>
				     </div>
				</div>

				<form  action="/installation/set_search/" id="search_form" method="post" role="form">
					<!-- 검색 공통 시작 -->
					<input type="hidden" id="search_type" name="search_type">
					<input type="hidden" id="search_value" name="search_value">
					<input type="hidden" id="lat" name="lat">
					<input type="hidden" id="lng" name="lng">
					<input type="hidden" id="sido_val" name="sido_val" value="<?php echo element("sido_val",$search_installation);?>">
					<input type="hidden" id="gugun_val" name="gugun_val" value="<?php echo element("gugun_val",$search_installation);?>">
					<input type="hidden" id="dong_val" name="dong_val" value="<?php echo element("dong_val",$search_installation);?>">
					<input type="hidden" id="subway_local_val" name="subway_local_val" value="<?php echo element("subway_local_val",$search_installation);?>">
					<input type="hidden" id="hosun_val" name="hosun_val" value="<?php echo element("hosun_val",$search_installation);?>">
					<input type="hidden" id="station_val" name="station_val" value="<?php echo element("station_val",$search_installation);?>">
					<input type="hidden" id="search_admin_member_id" name="search_admin_member_id" value="<?php echo element("search_admin_member_id",$search_installation);?>"/>
					<!-- 검색 공통 종료 -->

					<input type="hidden" name="search_member_id" id="search_member_id">
					<input type="hidden" id="next_page" value="<?php echo ($this->uri->segment(3)) ? $this->uri->segment(3) : "0";?>" autocomplete="off"/>

			    	<div class="row margin-bottom-10">
					     <div class="col-lg-12">
					          <label>키워드검색</label>
					          <div class="input-group">
					               <input type="text" id="keyword"  name="keyword" class="form-control help" value="<?php echo element("keyword",$search_installation);?>" data-toggle='tooltip' title='제목, 관리메모, 전화번호, 키워드에 대한 키워드 검색입니다.'/>
					               <div class="input-group-btn">
										<button id="go_keyword" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
										<button id="remove_keyword" class="btn btn-default" type="button"><i class="fa fa-times"></i></button>
					               </div>
					          </div>
					     </div>
					</div>



					<div id="search_tab">
						
						<ul class="nav">
							<li class="nav-one">
								<a href="#local_section" <?php if(element("search_type",$search_installation)=="" or element("search_type",$search_installation)=="address" or element("search_type",$search_installation)=="parent_address") {echo "class='current'";}?>><?php echo lang("site.location");?></a>
							</li>
							<li class="nav-two">
								<a href="#sybway_section" <?php if(element("search_type",$search_installation)=="subway") {echo "class='current'";}?>>지하철</a>
							</li>
							<li class="nav-three last" style="display:none;">
								<a href="#google_section" <?php if(element("search_type",$search_installation)=="google") {echo "class='current'";}?>><?php echo lang("search.total");?></a>
							</li>
						</ul>
						
						<div class="list-wrap">
						
							<ul id="local_section"  
								<?php if(element("search_type",$search_installation)=="" or element("search_type",$search_installation)=="address" or element("search_type",$search_installation)=="parent_address") {} else {echo "style='display:none;'";}?>>
								<li>
								<div class="row" style="margin:0px;">
									<div class="col-xs-4" style="padding:0px;">
										<select id="sido" name="sido" onchange="$('#dong').html('<option value=\'\'>-</option>');"></select>
									</div>
									<div class="col-xs-4" style="padding:0px;">
										<select id="gugun" name="gugun"><option value="">-</option></select>
									</div>
									<div class="col-xs-4" style="padding:0px;">
										<select id="dong" name="dong"><option value="">-</option></select>
									</div>
								</div>
								</li>
							</ul>
							 
							 <ul id="sybway_section" <?php if(element("search_type",$search_installation)=="subway") {} else {echo "style='display:none;'";}?>>
								<li>
									<div class="row" style="margin:0px;">
										<div class="col-xs-4" style="padding:0px;">
											<select id="subway_local" name="subway_local" onchange="$('#station').html('<option value=\'\'>-</option>');"><option value="">-</option></select>
										</div>
										<div class="col-xs-4" style="padding:0px;">
											<select id="hosun" name="hosun"><option value="">-</option></select>
										</div>
										<div class="col-xs-4" style="padding:0px;">
											<select id="station" name="station"><option value="">-</option></select>
										</div>
									</div>							
								</li>
							 </ul>
							 
							 <ul id="google_section" <?php if(element("search_type",$search_installation)=="google") {} else {echo "style='display:none;'";}?>>
								<li>
									<input type="text" id="search" class="ui-autocomplete-input search_item" placeholder="<?php echo lang("site.location");?>, <?php echo lang("site.subway");?>" autocomplete="off" value="<?php if(element("search_type",$search_installation)=="google") echo element("search_value",$search_installation);?>">
								</li>
							 </ul>

						 </div> <!-- END List Wrap -->
					</div> <!-- search_tab -->


					<ul>
						<li>
							<h3>분양 형태</h3>
						</li>
						<li>
							<input type="checkbox" name="category[]" value="apt" class='category_checkbox search_item' >
							<label> 아파트</label>
						</li>
						<li>
							<input type="checkbox" name="category[]" value="villa" class='category_checkbox search_item' >
							<label> 빌라</label>
						</li>
						<li>
							<input type="checkbox" name="category[]" value="officetel" class='category_checkbox search_item' >
							<label> 오피스텔</label>
						</li>
						<li>
							<input type="checkbox" name="category[]" value="city" class='category_checkbox search_item' >
							<label> 도시형생활주택</label>
						</li>
						<li>
							<input type="checkbox" name="category[]" value="shop" class='category_checkbox search_item' >
							<label> 상가</label>
						</li>
					</ul>
			</div>
	</div>
	<div class="col-lg-9">
			<div class="sorting" style="padding-right:10px;">
				<select name="sorting" class="search_item form-control" autocomplete="off">
					<option value="date_desc" <?php if(element("sorting",$search_installation)=="" || element("sorting",$search_installation)=="date_desc") {echo "selected";}?>>최신 등록순</option>
					<option value="date_asc" <?php if(element("sorting",$search_installation)=="date_asc") {echo "selected";}?>>최신 등록 역순</option>
				</select>
			</div>
			<div class="sorting" style="padding-right:10px;">
				<select id="only" name="only" class="search_item form-control" autocomplete="off">
					<option value="">[필터]<?php echo lang("site.all");?></option>
					<option value="plan" <?php if(element("only",$search_installation)=="plan") {echo "selected";}?>><?php echo lang("installation");?> 계획중</option>
					<option value="go" <?php if(element("only",$search_installation)=="go") {echo "selected";}?>><?php echo lang("installation");?> 진행중</option>
					<option value="end" <?php if(element("only",$search_installation)=="end") {echo "selected";}?>><?php echo lang("installation");?> 종료</option>
					<option value="public" <?php if(element("only",$search_installation)=="public") {echo "selected";}?>>공개 <?php echo lang("installation");?>만</option>
					<option value="private" <?php if(element("only",$search_installation)=="private") {echo "selected";}?>>비공개 <?php echo lang("installation");?>만</option>
					<option value="recommand" <?php if(element("only",$search_installation)=="recommand") {echo "selected";}?>>추천 <?php echo lang("installation");?>만</option>
				</select>
			</div>
			<div class="sorting" style="padding-right:10px;">
				<select name="valid" class="search_item form-control" autocomplete="off">
					<option value="">승인여부</option>
					<option value="1" <?php if(element("valid",$search_installation)==="1" || element("valid",$search_installation)==="") {echo "selected";}?>>승인<?php echo lang("installation");?></option>
					<option value="0" <?php if(element("valid",$search_installation)==="0") {echo "selected";}?>>비승인<?php echo lang("installation");?></option>
				</select>
			</div>
			<div class="sorting">
				<select name="per_page" class="search_item form-control input-inline" autocomplete="off">
					<option value="10" <?php if(element("per_page",$search_installation)=="" || element("per_page",$search_installation)=="10") {echo "selected";}?>>10개씩 보기</option>
					<option value="20" <?php if(element("per_page",$search_installation)=="20") {echo "selected";}?>>20개씩 보기</option>
					<option value="30" <?php if(element("per_page",$search_installation)=="30") {echo "selected";}?>>30개씩 보기</option>
					<option value="40" <?php if(element("per_page",$search_installation)=="40") {echo "selected";}?>>40개씩 보기</option>
					<option value="50" <?php if(element("per_page",$search_installation)=="50") {echo "selected";}?>>50개씩 보기</option>
				</select>
				<div class="result_label input-inline"></div>
			</div>
			<div class="sorting hidden-xs" style="padding-left:10px;">
				<!--button class="btn yellow" onclick="if(confirm('다운로드받으시겠습니까?')){location.href='/admininstallation/excel'}">엑셀다운로드</button-->
			</div>
			<ul id="paging" class="pagination"></ul>
			<div style="clear:both;margin-bottom:10px;"></div>
			</form>
		<form  action="/admininstallation/delete_all_installation" id="list_form" method="post" role="form">
		<input type="hidden" name="exe_type" id="exe_type">
		<input type="hidden" name="exe_value" id="exe_value">
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th style="width:25px;"><input type='checkbox' id='check_all'/></th>
					<th style="width:110px;"><?php echo lang("site.photo");?></th>
					<th style="width:100px;"><?php echo lang("site.information");?></th>
					<th><?php echo lang("site.title");?>/<?php echo lang("site.address");?></th>
					<th style="width:150px;" class="hidden-xs">공고/입주 시기</th>
					<th style="width:100px;" class="hidden-xs">포스팅</th>
					<th style="width:100px;" class="hidden-xs">설정</th>
					<th style="width:100px;" class="hidden-xs"><?php echo lang("site.regdate");?>/<?php echo lang("product.owner");?></th>
				</tr>
			</thead>
			<tbody id="search-items"></tbody>
		</table>
		</form>
	</div>
</div>

<div id="check_log" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="max-width:270px;">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">매물확인 기록</h4>
		</div>
		<div class="modal-body" style="max-height:500px;overflow-y:auto;">
			<table class="table table-bordered table-striped-left table-condensed flip-content">
				<tbody id="check_list"></tbody>
			</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
			</div>
		</div>
	</div>
</div>