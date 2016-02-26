<style>
.all_exe {
	padding:10px 15px;
}
</style>
<script src="/assets/plugin/organictabs.jquery.js"></script>
<script type="text/javascript" src="/script/src/search"></script>
<script>
	var sell_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("sell_unit");?></font>";
	var price_unit = "<font style='font-size:11px;font-family:\"돋움\",dotum'><?php echo lang("price_unit");?></font>";
	var address_type="admin"; /** 공개/비공개 매물 등록된 주소 가져오도록 **/
</script>
<script>
var total;

$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */

	init_search("<?php echo element('type',$search);?>","<?php echo element('category',$search);?>");
	
	$("#next_page").val('<?php echo element("now_page",$search);?>');

	$('#search_form').ajaxForm( {
		beforeSubmit: function()
		{
			/** map은 여기서 move_map을 했는데 grid는 그럴 필요가 없으니까 그냥 냅두자 **/
		},
		success: function(data)
		{
			$.ajax({
				type: 'GET',
				url: '/adminproduct/listing_json/'+$("#next_page").val()+'/',
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
								$("#now_page").val($("#next_page").val());
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

	$("#memo_form").validate({
		rules: {
			memo: {  
				required: true
			}
		},
		errorPlacement: function(error, element){
			
		}
	});

	$('#memo_form').ajaxForm({
		beforeSubmit: function(){
		},
		success: function(data){
			var product_id = $("#memo_form").find("input[name='product_id']").val();
			var product_title = $("#memo_product_title").text();
			get_memo_list(product_id,product_title);
		}
	});	

	$("#member").change(function(){
		$("#member_name").val("");
		$("#search_member_id").val("");		
	});

	$("#keyword").keypress(function(event){
		if (event.which==13) {
			$('#search_form').trigger('submit');
		}		
	});
	
	$("#go_keyword").click(function(){
		if($("#keyword_layer").css("display")!="none"){
			keyword_layer();
		}
		$('#search_form').trigger('submit');
	});

	$("#go_member_name").click(function(){
		$("#member option:selected").attr("selected", false);
		$('#search_form').trigger('submit');
	});

	$("#remove_keyword").click(function(){
		$("#keyword").val("");
		$(".keyword_checkbox").each(function(){
			$(this).prop('checked',false);
		});
		$("#keyword_all").prop('checked',true);
		$('input').iCheck('update');
		$('#search_form').trigger('submit');
	});

	$("#remove_member_name").click(function(){
		$("#member_name").val("");
		$("#search_member_id").val("");
		$('#search_form').trigger('submit');
	});

	$('#check_all').change(function(){
		var check_state = $(this).prop('checked');
		$("input[name='check_product[]']").each(function(i){
            if($(this).attr('self-data')){
                $(this).prop('checked',check_state);
            }
        });
	});

	$('#delete_product').click(function(){
		var check_length = $("input[name='check_product[]']:checked").length;
		if(!check_length){
			alert('삭제할 <?php echo lang("product");?>(를)을 선택 해주시기 바랍니다.');
			return;
		}
		else{
			if(confirm('선택한 <?php echo lang("product");?>(를)을 삭제 하시겠습니까?')){
				$('#list_form').submit();
			}
		}
	});


	$( "#member_name" ).autocomplete({
		selectFirst: true, 
		autoFill: true,
		autoFocus: true,
		focus: function(event,ui){
			return false;
		},
		scrollHeight:40,
		minlength:1,
		select: function(a,b){
			$("#member_name").val(b.item.member_name);
			$("#search_member_id").val(b.item.member_id);
			a.stopPropagation(); 
		},
		source: function(request, response){
			$.ajax({
				url: "/search/member_list",
				type: "POST",
				data: {
					search: $("#member_name").val()
				},
				dataType: "json",
				success: function(data) {
					response( $.map( data, function( item ) {
						return {
							member_id: item.id,
							member_name: item.name,
							member_email: item.email
						}; 
					}));
				}
			});						
		},
	}).data("ui-autocomplete")._renderItem = autoCompleteRenderAdmin;

	$("#search_tab").organicTabs();

	<?php if( element("search_type",$search) == "" || element("search_type",$search) == "google"  ){?>
		var only = "<?php echo element('only',$search)?>";
		var admin_member_id = "<?php echo element('search_admin_member_id',$search)?>";
		var auth_id = "<?php echo $this->session->userdata('auth_id')?>";
		if(only=="") only = "ongoing";
		$("#only").val(only);
		if(auth_id!="1"){
			if(admin_member_id=="") admin_member_id = "<?php echo $this->session->userdata('admin_id')?>";
			$("#search_admin_member_id").val(admin_member_id);
			$("#admin_member_id").val(admin_member_id);
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

	$("#num").keypress(function(event){
		if(event.which==13){
			go_num();
		}		
	});

	$("#go_num").click(function(){
		go_num();
	});

	$(".keyword_checkbox").iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
		increaseArea: '20%'
	});

	$(".keyword_checkbox").on('ifChanged', function(event){
		if($(this).val()=="all" && $(this).prop('checked')){
			$(".keyword_checkbox").each(function(){
				if($(this).val()!="all") $(this).prop('checked',false);
			});
		}
		else{
			$("#keyword_all").prop('checked',false);
		}
		$('input').iCheck('update');
	});
});
/*** <?php echo lang("product");?> 번호를 넣고 클릭을 하면 이동하는 기능 **/
function go_num(){
	$.get("/product/have_num/"+$("#num").val()+"/"+Math.round(new Date().getTime()),function(data){
		if(data=="1"){
			location.href="/adminproduct/view/"+$("#num").val();
		} else {
			alert("해당 <?php echo lang("product.no");?>의 <?php echo lang("product");?>이(가) 없습니다.");
		}
	});
}

function autoCompleteRenderAdmin(ul, item) {
	return $("<li class='search_rows'></li>").data("item.autocomplete", item).append("<i class='fa fa-user'></i> " + item.member_name+'('+item.member_email+')').appendTo(ul);
}


function change(type, id, status){
	if(confirm("상태를 변경하시겠습니까?")){
		$.get("/adminproduct/change/"+type+"/"+id+"/"+status+"/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				location.href="/adminproduct/index/"+$("#next_page").val();
		   } else {
				alert("변경 실패");
		   }
		})
	}
}

function blog(id){
	window.open("/adminblogapi/blog_popup/"+id,"blog_window","width=460, height=700, resizable=no, scrollbars=no, status=no;");
}

function daum_blog(id){
	window.open("/adminblogapi/daum_OAuth/"+id,"blog_window","width=460, height=700, resizable=no, scrollbars=no, status=no;");
}

function cafe(id){
	window.open("/admincafeapi/OAuth/"+id,"cafe_window","width=460, height=760, resizable=no, scrollbars=no, status=no;");
}

function delete_product(id){
	if(confirm("<?php echo lang("product");?>(를)을 삭제하시겠습니까?\n<?php echo lang("product");?>삭제는 관리자와 등록한 직원만 가능합니다.")){
		location.href="/adminproduct/delete_product/"+id;
	}
}

function get_check_list(id){
	$.ajax({
		url: "/adminproduct/get_check_list",
		type: "POST",
		dataType: "json",
		cash: false,
		async: false,
		data: {
			product_id : id
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

	var check_length = $("input[name='check_product[]']:checked").length;
	if(!check_length){
		alert('일괄실행할 <?php echo lang("product");?>(를)을 선택 해주시기 바랍니다.');
		return;
	}

	$("#exe_type").val(exe_type);
	$("#exe_value").val(exe_value);
	$('#list_form').attr('action', '/adminproduct/exe_all');
	$('#list_form').submit();
}

var cursor = false;

function thumb_display(obj,display){
	
	$(".gallery_thumb").hide();
	
	var src = $(obj).children().attr("src");

	if(!src){
		obj = $(obj).prev().find("div");
		src = $(obj).children().attr("src");
	}
	if(display=="show"){
		cursor = true;
		$(obj).children().attr("src",src.replace("plus","minus"));
		$(obj).parent().next().show();
	}
	else if(display=="hide"){
		setTimeout(function () {
			if(!cursor){
				$(obj).children().attr("src",src.replace("minus","plus"));
				$(obj).parent().next().hide();
			}
		}, 100);
		cursor = false;
	}
}

function memo_view(product_id,product_title){
	$("#memo_form").find("input[name='product_id']").val(product_id);
	get_memo_list(product_id,product_title);
}

function get_memo_list(product_id,product_title){

	$("#memo_form").find("input[name='memo']").val("");

	$.ajax({
		url: "/adminproduct/get_memo_list",
		type: "POST",
		dataType: "json",
		data: {
			product_id : product_id
		},
		success: function(data) {
			var str = "";
			$("#memo_product_id").text(product_id);
			$("#memo_product_title").text(product_title);
			if(data!=""){
				$.each(data, function(key, val) {
					str +=	'<li>';
					str +=		'<div class="message" style="padding:10px;">';										
					str +=			'<span class="body">'+val['memo']+'</span>';
					str +=			'<div class="margin-top-10 margin-bottom-10 pull-right" style="color:#5b9bd1">'+val['date']+' <a href="javascript:memo_delete('+val['id']+');"><i class="fa fa-times"></i></a></div>';
					str +=		'</div>';
					str +=	'</li>';
				});			
			}
			else{
				str +=	'<li>';
				str +=		'<div class="message" style="padding:10px;">';
				str +=			'<span class="body"><?php echo lang("msg.nodata");?></span>';
				str +=		'</div>';
				str +=	'</li>';
			}
			$("#memo_list").html(str);
		}
	});	
}

function memo_delete(id){
	if(confirm("메모를 삭제 하시겠습니까?")){
		$.get("/adminproduct/memo_delete/"+id+"/"+Math.round(new Date().getTime()),function(data){
			var product_id = $("#memo_form").find("input[name='product_id']").val();
			var product_title = $("#memo_product_title").text();
			get_memo_list(product_id,product_title);
		});	
	}
}

function keyword_layer(){
	$('#keyword_layer').toggle();
	$('#keyword_button').toggle();
}
</script>
<link href="/assets/plugin/nouislider/jquery.nouislider.css" rel="stylesheet">
<script src="/assets/plugin/nouislider/jquery.nouislider.all.min.js"></script>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				<?php echo lang("product");?><small>목록</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					<?php echo lang("product");?> 관리
				</li>
			</ul>
			<div>
				<div class="sorting pull-right">
					<div class="input-inline">
						<div class="input-inline"><i class="fa fa-user"></i> 직원검색 </div>
						<!-- <?php echo $val->cnt?>건 : 건수 보여지는 부분은 안 보이게 해달라는 요청이 있어서 뺌(직원관리에서 볼 수 있으므로 여기서는 빼도 될 것 같다.) -->
						<select id="admin_member_id" class="form-control input-inline" autocomplete="off">
							<option value="all" <?php if(element("search_admin_member_id",$search)=="" || element("search_admin_member_id",$search)=="all") {echo "selected";}?>><?php echo lang("site.all");?></option>
							<?php foreach($members as $val){?>
							<option value="<?php echo $val->id?>" <?php if(element("search_admin_member_id",$search)==$val->id) {echo "selected";}?>><?php echo $val->name?> (<?php echo $val->email?>)</option>
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
										<button class="btn btn-default btn-sm" onclick="all_exe('check_product');"><?php echo lang("product")?>확인하기</button>
									</span>									
								</li>
								<li class="divider"></li>
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
								<li class="all_exe">
									<span>
										<button class="btn btn-xs blue" onclick="all_exe('is_speed',1)">급매하기</button>
										<button class="btn btn-xs red" onclick="all_exe('is_speed',0)">급매취소</button>
									</span>
								</li>
								<li class="all_exe">
									<span>
										<button class="btn btn-xs blue" onclick="all_exe('is_defer',1)">보류하기</button>
										<button class="btn btn-xs red" onclick="all_exe('is_defer',0)">보류취소</button>
									</span>
								</li>
							</ul>
						</div>
						<button class="btn blue" onclick="location.href='/adminproduct/add'"><?php echo lang("site.submit");?></button>
						<button class="btn red" id="delete_product"><?php echo lang("site.delete");?></button>
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
			<a href="/adminproduct/clean" class="btn btn-default" style="width:100%"><i class="fa fa-toggle-on"></i> 검색 초기화</a>
			</div>
		    <div class="search-wrapper">
		    	<div class="row margin-bottom-10">
				     <div class="col-lg-12">
				          <label><?php echo lang("product.no");?></label>
				          <div class="input-group">
				               <input type="text" id="num"  name="num" placeholder="<?php echo lang("product.no");?>" class="form-control"/>
				               <div class="input-group-btn">
				                    <button id="go_num" class="btn btn-default" type="button">이동</button>
				               </div>
				          </div>
				     </div>
				</div>

				<form  action="/search/set_search/" id="search_form" method="post" role="form">
					<!-- 검색 공통 시작 -->
					<input type="hidden" id="search_type" name="search_type">
					<input type="hidden" id="search_value" name="search_value">
					<input type="hidden" id="lat" name="lat">
					<input type="hidden" id="lng" name="lng">
					<input type="hidden" id="sido_val" name="sido_val" value="<?php echo element("sido_val",$search);?>">
					<input type="hidden" id="gugun_val" name="gugun_val" value="<?php echo element("gugun_val",$search);?>">
					<input type="hidden" id="dong_val" name="dong_val" value="<?php echo element("dong_val",$search);?>">
					<input type="hidden" id="subway_local_val" name="subway_local_val" value="<?php echo element("subway_local_val",$search);?>">
					<input type="hidden" id="hosun_val" name="hosun_val" value="<?php echo element("hosun_val",$search);?>">
					<input type="hidden" id="station_val" name="station_val" value="<?php echo element("station_val",$search);?>">
					<input type="hidden" id="search_admin_member_id" name="search_admin_member_id" value="<?php echo element("search_admin_member_id",$search);?>"/>
					<input type="hidden" id="now_page" name="now_page" value="<?php echo element("now_page",$search);?>"/>
					<!-- 검색 공통 종료 -->

					<input type="hidden" name="search_member_id" id="search_member_id">
					<input type="hidden" id="next_page" value="<?php echo ($this->uri->segment(3)) ? $this->uri->segment(3) : "0";?>" autocomplete="off"/>

			    	<div class="row margin-bottom-10">
					     <div class="col-lg-12">
					          <label>키워드검색</label>
							   <div class="input-group dropdown">
								    <input type="text" id="keyword"  name="keyword" class="form-control help" value="<?php echo element("keyword",$search);?>"/>
								    <input type="submit" style="display:none;">
									<div class="dropdown-menu" id="keyword_layer" style="width:80%;padding:10px;">
										<div class="pull-right" style="cursor:pointer;" onclick="keyword_layer();">
											<img width="15" height="15" src="/assets/common/img/close.png"/>
										</div>
										<span class="help">검색조건</span>
										<?php $keyword_type = (element("keyword_type",$search)) ? element("keyword_type",$search) : array();?>
										<ul class="margin-top-10">
											<li>
												<input type="checkbox" id="keyword_all" name="keyword_type[]" class="keyword_checkbox" value="all" <?php echo (in_array("all",$keyword_type) || count($keyword_type)==0) ? "checked" : "";?>/>
												<label> <?php echo lang("site.all");?></label>
											</li>
											<li>
												<input type="checkbox" name="keyword_type[]" class="keyword_checkbox" value="title" <?php echo (in_array("title",$keyword_type)) ? "checked" : "";?>/>
												<label> <?php echo lang("site.title");?></label>
											</li>
											<li>
												<input type="checkbox" name="keyword_type[]" class="keyword_checkbox" value="secret_memo" <?php echo (in_array("secret_memo",$keyword_type)) ? "checked" : "";?>/>
												<label> <?php echo lang("site.secretmemo");?></label>
											</li>
											<li>
												<input type="checkbox" name="keyword_type[]" class="keyword_checkbox" value="memo" <?php echo (in_array("memo",$keyword_type)) ? "checked" : "";?>/>
												<label> 관리메모</label>
											</li>
											<li>
												<input type="checkbox" name="keyword_type[]" class="keyword_checkbox" value="owner_name" <?php echo (in_array("owner_name",$keyword_type)) ? "checked" : "";?>/>
												<label> <?php echo lang("site.proprietor");?></label>
											</li>
											<li>
												<input type="checkbox" name="keyword_type[]" class="keyword_checkbox" value="phone" <?php echo (in_array("phone",$keyword_type)) ? "checked" : "";?>/>
												<label> <?php echo lang("site.contact");?></label>
											</li>
											<li>
												<input type="checkbox" name="keyword_type[]" class="keyword_checkbox" value="address" <?php echo (in_array("address",$keyword_type)) ? "checked" : "";?>/>
												<label> 상세주소</label>
											</li>
											<li>
												<input type="checkbox" name="keyword_type[]" class="keyword_checkbox" value="option" <?php echo (in_array("option",$keyword_type)) ? "checked" : "";?>/>
												<label> <?php echo lang("site.option");?></label>
											</li>
											<li>
												<input type="checkbox" name="keyword_type[]" class="keyword_checkbox" value="etc" <?php echo (in_array("etc",$keyword_type)) ? "checked" : "";?>/>
												<label> 추가입력항목</label>
											</li>
											<li>
												<input type="checkbox" name="keyword_type[]" class="keyword_checkbox" value="keyword" <?php echo (in_array("keyword",$keyword_type)) ? "checked" : "";?>/>
												<label> 키워드</label>
											</li>
										</ul>
									</div>
									<div class="input-group-btn">
										<button class="btn btn-default" type="button" id="keyword_button" onclick="keyword_layer();"><i class="fa fa-eject fa-rotate-180"></i></button>
										<button id="go_keyword" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
										<button id="remove_keyword" class="btn btn-default" type="button"><i class="fa fa-times"></i></button>
									</div>
							   </div>
					     </div>
					</div>

					<div id="search_tab">
						<ul class="nav">
							<li class="nav-one">
								<a href="#local_section" <?php if(element("search_type",$search)=="" or element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {echo "class='current'";}?>>지역</a>
							</li>
							<li class="nav-two">
								<a href="#sybway_section" <?php if(element("search_type",$search)=="subway") {echo "class='current'";}?>>지하철</a>
							</li>
							<li class="nav-three last" style="display:none;">
								<a href="#google_section" <?php if(element("search_type",$search)=="google") {echo "class='current'";}?>><?php echo lang("search.total");?></a>
							</li>
						</ul>
						
						<div class="list-wrap">
						
							<ul id="local_section"  
								<?php if(element("search_type",$search)=="" or element("search_type",$search)=="address" or element("search_type",$search)=="parent_address") {} else {echo "style='display:none;'";}?>>
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
							 
							 <ul id="sybway_section" <?php if(element("search_type",$search)=="subway") {} else {echo "style='display:none;'";}?>>
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
							 
							 <ul id="google_section" <?php if(element("search_type",$search)=="google") {} else {echo "style='display:none;'";}?>>
								<li>
									<input type="text" id="search" class="ui-autocomplete-input search_item" placeholder="지역, <?php echo lang("site.subway");?>" autocomplete="off" value="<?php if(element("search_type",$search)=="google") echo element("search_value",$search);?>">
								</li>
							 </ul>

						 </div> <!-- END List Wrap -->
					</div> <!-- search_tab -->


					<ul>
						<?php if($config->INSTALLATION_FLAG!="2"){?>
						<li>
							<div class="btn-group btn-group-justified btn-group-sm" role="group" data-toggle="buttons">
								<!-- input radio에 search_item을 걸면 갯수만큼 call이 나가기 때문에 안된다. -->
								<label class="btn btn-default">
									<input type="radio" name="type" class="search_item" value=""><?php echo lang("site.all");?>
								</label>
								<?php if($config->INSTALLATION_FLAG=="1"){?>
								<label class="btn btn-default">
									<input type="radio" name="type" class="search_item" value="installation"><?php echo lang('installation');?>
								</label>
								<?php }?>
								<label class="btn btn-default">
									<input type="radio" name="type" class="search_item" value="sell"><?php echo lang('sell');?>
								</label>
								<label class="btn btn-default">
									<input type="radio" name="type" class="search_item" value="full_rent"><?php echo lang('full_rent');?>
								</label>
								<label class="btn btn-default">
									<input type="radio" name="type" class="search_item" value="monthly_rent"><?php echo lang('monthly_rent');?>
								</label>		
							</div>
						</li>
						<?php }?>
						<li class="price_range price_sell">
							<div class="price_label"><?php echo lang('sell');?>가</div>
							<div class="price_show">
									<input type="hidden" id="sell_start" name="sell_start">
									<input type="hidden" id="sell_end" name="sell_end">
									<div id="sell_label"></div>
							</div>
							<div style="clear:both;"></div>
							<div class="price_slider">
								<div id="sell_range" class="slider" 
									data-start="0" 
									data-end="<?php echo element('sell_end',$search) ? element('sell_end',$search) : $config->SELL_MAX ;?>" 
									data-min="0" 
									data-max="<?php echo $config->SELL_MAX;?>"
									data-step="1"
									data-type="sell" ></div>
							</div>
						</li>
						<li class="price_range price_full">
							<div class="price_label"><?php echo lang('full_rent');?>가</div>
							<div class="price_show">
								<input type="hidden" id="full_start" name="full_start">
								<input type="hidden" id="full_end" name="full_end">
								<div id="full_label"></div>
							</div>
							<div style="clear:both;"></div>
							<div class="price_slider">
								<div id="full_range" class="slider" 
									data-start="0" 
									data-end="<?php echo element('full_end',$search) ? element('full_end',$search) : $config->FULL_MAX ;?>" 
									data-min="0" 
									data-max="<?php echo $config->FULL_MAX;?>"
									data-step="500"
									data-type="full"></div>
							</div>
						</li>
						<li class="price_range price_rent">
							<div class="price_label"><?php echo lang("product.price.deposit");?></div>
							<div class="price_show">
								<input type="hidden" id="month_deposit_start" name="month_deposit_start">
								<input type="hidden" id="month_deposit_end" name="month_deposit_end">
								<div id="month_deposit_label"></div>
							</div>
							<div style="clear:both;"></div>
							<div class="price_slider">
								<div id="month_deposit_range" class="slider"
									data-start="0" 
									data-end="<?php echo element('month_deposit_end',$search) ? element('month_deposit_end',$search) : $config->MONTH_DEPOSIT_MAX ;?>" 
									data-min="0" 
									data-max="<?php echo $config->MONTH_DEPOSIT_MAX;?>"
									data-step="50"
									data-type="month_deposit"></div>
							</div>
							<div class="price_label"><?php echo lang('monthly_rent');?>가</div>
							<div class="price_show">
								<input type="hidden" id="month_start" name="month_start">
								<input type="hidden" id="month_end" name="month_end">
								<div id="month_label"></div>
							</div>
							<div style="clear:both;"></div>
							<div class="price_slider">
								<div id="month_range" class="slider"
									data-start="0" 
									data-end="<?php echo element('month_end',$search) ? element('month_deposit_end',$search) : $config->MONTH_DEPOSIT_MAX ;?>" 
									data-min="0" 
									data-max="<?php echo $config->MONTH_MAX;?>"
									data-step="5"
									data-type="month"></div>
							</div>
						</li>
						<li>
							<h3><?php echo lang("search.type");?></h3>
						</li>
						<?php foreach($category as $val){ ?>
						<li>
							<input type="checkbox" name="category[]" id="category_<?php echo $val->id;?>" value="<?php echo $val->id;?>" class='category_checkbox search_item' >
							<label> <?php echo $val->name;?></label>
						</li>
						<?php }?>
						<li>
							<h3><?php echo lang("product.theme")?></h3>
						</li>
						<?php foreach($theme as $val){ ?>
						<li>
							<input type="checkbox" name="theme[]" value="<?php echo $val->id;?>" class='category_checkbox search_item' <?php echo isset($val->checked)?$val->checked:"";?>/>
							<label> <?php echo $val->theme_name;?></label>
						</li>
						<?php }?>
					</ul>
					<?php if($config->USE_FACTORY) {?>
					<ul>
							<li>
								<h3 for="select-property-type">대지면적</h3>
							</li>
							<li>
								<select id="site_area" name="site_area" class="search_item form-control">
									<option value="">대지면적선택</option>
									<option value="500">500py이하</option>
									<option value="1000">500py~1000py이하</option>
									<option value="2000">1000py~2000py이하</option>
									<option value="3000">2000py~3000py이하</option>
									<option value="10000">3000py이상</option>
								</select>
							</li>						
							<li>
								<h3 for="select-property-type">연면적</h3>
							</li>
							<li>
								<select id="law_area" name="law_area" class="search_item form-control">
									<option value="">연면적 선택</option>
									<option value="60">60py이하</option>
									<option value="100">60py~100py이하</option>
									<option value="200">100py~200py이하</option>
									<option value="400">200py~400py이하</option>
									<option value="1000">400py이상</option>
								</select>
							</li>			
						</ul>
					<?php } ?>
			    	<div class="row margin-bottom-10">
					     <div class="col-lg-12">
					          <label>회원이름 검색</label>
					          <div class="input-group">
								<input type="text" id="member_name"  name="member_name" class="form-control help" value="" data-toggle='tooltip' placeholder="회원이름 검색" autocomplete="off" class="ui-autocomplete-input"/>
					               <div class="input-group-btn">
										<button id="go_member_name" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
										<button id="remove_member_name" class="btn btn-default" type="button"><i class="fa fa-times"></i></button>
					               </div>
					          </div>
					     </div>
					</div>
			</div>
	</div>
	<div class="col-lg-9">
			<div class="sorting" style="padding-right:10px;">
				<select name="sorting" class="search_item form-control" autocomplete="off">
					<option value="date_desc" <?php if(element("sorting",$search)=="" || element("sorting",$search)=="date_desc") {echo "selected";}?>><?php echo lang("sort.newest");?></option>
					<option value="date_asc" <?php if(element("sorting",$search)=="date_asc") {echo "selected";}?>><?php echo lang("sort.oldest");?></option>
					<option value="price_desc" <?php if(element("sorting",$search)=="price_desc") {echo "selected";}?>><?php echo lang("sort.high");?></option>
					<option value="price_asc" <?php if(element("sorting",$search)=="price_asc") {echo "selected";}?>><?php echo lang("sort.low");?></option>
					<option value="area_desc" <?php if(element("sorting",$search)=="area_desc") {echo "selected";}?>><?php echo lang("sort.big");?></option>
					<option value="area_asc" <?php if(element("sorting",$search)=="area_asc") {echo "selected";}?>><?php echo lang("sort.small");?></option>
				</select>
			</div>
			<div class="sorting" style="padding-right:10px;">
				<select id="only" name="only" class="search_item form-control" autocomplete="off">
					<option value="">[필터]<?php echo lang("site.all");?></option>
					<option value="ongoing" <?php if(element("only",$search)=="ongoing") {echo "selected";}?>>진행중 <?php echo lang("product");?>만</option>
					<option value="finished" <?php if(element("only",$search)=="finished") {echo "selected";}?>>완료 <?php echo lang("product");?>만</option>
					<option value="public" <?php if(element("only",$search)=="public") {echo "selected";}?>>공개 <?php echo lang("product");?>만</option>
					<option value="private" <?php if(element("only",$search)=="private") {echo "selected";}?>>비공개 <?php echo lang("product");?>만</option>
					<option value="recommand" <?php if(element("only",$search)=="recommand") {echo "selected";}?>>추천 <?php echo lang("product");?>만</option>
					<option value="speed" <?php if(element("only",$search)=="speed") {echo "selected";}?>>급 <?php echo lang("product");?>만</option>
				</select>
			</div>
			<div class="sorting" style="padding-right:10px;">
				<select name="valid" class="search_item form-control" autocomplete="off">
					<option value="">승인여부</option>
					<option value="1" <?php if(element("valid",$search)==="1" || element("valid",$search)==="") {echo "selected";}?>>승인<?php echo lang("product");?></option>
					<option value="0" <?php if(element("valid",$search)==="0") {echo "selected";}?>>비승인<?php echo lang("product");?></option>
				</select>
			</div>
			<div class="sorting">
				<select name="per_page" class="search_item form-control input-inline" autocomplete="off">
					<option value="10" <?php if(element("per_page",$search)=="" || element("per_page",$search)=="10") {echo "selected";}?>>10개씩 보기</option>
					<option value="20" <?php if(element("per_page",$search)=="20") {echo "selected";}?>>20개씩 보기</option>
					<option value="30" <?php if(element("per_page",$search)=="30") {echo "selected";}?>>30개씩 보기</option>
					<option value="40" <?php if(element("per_page",$search)=="40") {echo "selected";}?>>40개씩 보기</option>
					<option value="50" <?php if(element("per_page",$search)=="50") {echo "selected";}?>>50개씩 보기</option>
				</select>
				<div class="result_label input-inline"></div>
			</div>
			<div class="sorting hidden-xs" style="padding-left:10px;">
				<!--button class="btn yellow" onclick="if(confirm('다운로드받으시겠습니까?')){location.href='/adminproduct/excel'}">엑셀다운로드</button-->
			</div>
			<ul id="paging" class="pagination"></ul>
			<div style="clear:both;margin-bottom:10px;"></div>
			</form>
		<form  action="/adminproduct/delete_all_product" id="list_form" method="post" role="form">
		<input type="hidden" name="exe_type" id="exe_type">
		<input type="hidden" name="exe_value" id="exe_value">
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th style="width:25px;"><input type='checkbox' id='check_all'/></th>
					<th style="width:110px;">광고사진</th>
					<th style="width:110px;">관리자사진</th>
					<th style="width:100px;"><?php echo lang("site.information");?></th>
					<th><?php echo lang("site.title");?>/<?php echo lang("site.address");?></th>
					<th style="width:160px;" class="hidden-xs">정보</th>
					<th style="width:100px;" class="hidden-xs">포스팅</th>
					<th style="width:100px;" class="hidden-xs">설정/메모</th>
					<th style="width:100px;" class="hidden-xs"><?php echo lang("site.regdate");?>/담당</th>
				</tr>
			</thead>
			<tbody id="search-items">
				<div class="loading_content">
					<div class="loading_background"></div>
					<div class="loading_image"><img src="/assets/common/img/load_360.gif"></div>
				</div>
			</tbody>
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

<!-- MEMO FORM -->
<?php echo form_open_multipart("/adminproduct/memo_add",Array("id"=>"memo_form"))?>
<input type="hidden" name="product_id"/>
<div id="memo_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:98%;max-width:600px;">
		<div class="modal-content">
			<div class="modal-header">
				<div data-dismiss="modal" class="pull-right" style="cursor:pointer;">
					<img width="20" height="20" src="/assets/common/img/close.png"/>
				</div>
				<h4>[<span id="memo_product_id"></span>]<span id="memo_product_title"></span></h4>
			</div>
			<div class="modal-body">
				<div class="chat-form">
					<div class="input-cont">
						<input type="text" name="memo" class="form-control" placeholder="메모를 입력해주세요"/>
					</div>
					<div class="btn-cont">
						<span class="arrow"></span>
						<button type="submit" class="btn blue icn-only">
							<i class="fa fa-check icon-white"></i>
						</button>
					</div>
				</div>
				<div class="scroller" style="height: 500px;" data-always-visible="1" data-rail-visible="0">
					<ul class="feeds" id="memo_list">
						<li>
							<div class="message" style="padding:10px;">
								<span class="arrow"></span>											
								<span class="body"></span>
								<div class="margin-top-10 margin-bottom-10 pull-right" style="color:#5b9bd1"> <a href="javascript:memo_delete();"><i class="fa fa-times"></i></a></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>
<?php echo form_close();?>
<!-- MEMO FORM -->