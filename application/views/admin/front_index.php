<?php
	$this->config->load('layouts');

	$module_type = array(
		'html'		=> 'HTML',
		'link'		=> '링크',
		'news'		=> '뉴스',
		'portfolio'	=> '계약후기',
		'map'		=> '맵',
		'team'		=> '직원소개',
		'spot'		=> '지도위치 바로가기',
		'recent'	=> '최신'.lang("product"),
		'recommand'	=> lang("product.category").'별 추천'.lang("product"),
		'recommand_type'=> lang("product.type") . '별 추천'.lang("product"),
		'recommand_all'=> lang("product.category").'별 및 '.lang("product.type").'별 추천'.lang("product"),
		'recommand_theme'=> lang("product.theme") . '별 추천'.lang("product"),
		'theme'		=> lang("product.theme"),
		'service'	=> '서비스안내',
		'sitemap'	=> '사이트맵',
		'app'		=> '앱 소개'
	);
?>
<style>
#edit_form_table tr th{ width:105px; }
</style>
<script>
var module_type = {
	html:		'<?php echo element("html",$module_type);?>',
	link:		'<?php echo element("link",$module_type);?>',
	news:		'<?php echo element("news",$module_type);?>',
	portfolio:	'<?php echo element("portfolio",$module_type);?>',
	map:		'<?php echo element("map",$module_type);?>',
	team:		'<?php echo element("team",$module_type);?>',
	spot:		'<?php echo element("spot",$module_type);?>',
	recent:		'<?php echo element("recent",$module_type);?>',
	recommand:	'<?php echo element("recommand",$module_type);?>',
	recommand_type:	'<?php echo element("recommand_type",$module_type);?>',
	recommand_all:	'<?php echo element("recommand_all",$module_type);?>',
	recommand_theme:	'<?php echo element("recommand_theme",$module_type);?>',
	theme:		'<?php echo element("theme",$module_type);?>',
	service:	'<?php echo element("service",$module_type);?>',
	sitemap:	'<?php echo element("sitemap",$module_type);?>',
	app:		'<?php echo element("app",$module_type);?>'
};

var news_option = "<?php echo $news_option;?>";
var portfolio_option = "<?php echo $portfolio_option;?>";
var is_mobile = "<?php echo MobileCheck();?>";
is_mobile = (is_mobile==0) ? false : true;

$(document).ready(function(){

	if(!is_mobile){

		var uploader = new plupload.Uploader({
		  browse_button: 'browse', 
		  url: "/adminfront/slide_upload_action/"
		});

		uploader.bind('Error', function(up, err) {
		  $("#console").html("\에러 #" + err.code + ": " + err.message);
		});

		uploader.bind('UploadComplete', function(up, files) {
		  get_list();
		});	

		uploader.bind('FilesAdded', function(up, files) {
			uploader.start();
			$("#list").html("<div style='text-align:center;vertical-align:middle;'><img src='/assets/common/img/ajax-loader.gif'></div>");
		});

		uploader.init();
		

		var uploader_landing = new plupload.Uploader({
		  browse_button: 'browse_landing', 
		  url: "/adminfront/landing_upload_action/"
		});

		uploader_landing.bind('Error', function(up, err) {
		  $("#console").html("\에러 #" + err.code + ": " + err.message);
		});

		uploader_landing.bind('UploadComplete', function(up, files) {
		  get_list_landing();
		});	

		uploader_landing.bind('FilesAdded', function(up, files) {
			uploader_landing.start();
			$("#list_landing").html("<div style='text-align:center;vertical-align:middle;'><img src='/assets/common/img/ajax-loader.gif'></div>");
		});

		uploader_landing.init();
	}

	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};

	$("#sort_list").find("tr").each(function(index){
		if(index!=0) $(this).css('cursor','s-resize');
	});

	$("#sort_list").sortable({
		items: ".is_sortable",
		helper: fixHelper,
		update: function (event, ui) {
			var i=1;
			$("#sort_list").find("tr").each(function(){
				$.get("/adminfront/sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
					
				});
				i++;
			});
		}
	}).disableSelection();

	$("#edit_dialog").dialog({
		title: "정보 수정",
		bgiframe: true,
		resizable: false,
		autoOpen: false,
		width:650,
		height: 600,
		modal: true,
		buttons: {
			'취소': function() {
				$(this).dialog("close");
			},
			'수정': function(){
				$("#edit_form").submit();
			}
		}
	});

	get_list();
	get_list_landing();
});

function edit(id,form){

	var code = "";
	var top_type = "";

	$(".code_type").hide();
	$(".top_type").hide();
	$(".valid_type").show();

	$.getJSON("/adminfront/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			if(key=='code') code = val;
			switch(key){
				case "module":
					$(".code_type").hide();
					$("#slide_code").prop("disabled",true);
					$("#map_code").prop("disabled",true);
					$("#landing_code").prop("disabled",true);
					$("#html_code").prop("disabled",true);
					$("#category_code").prop("disabled",true);
					$("#line_code").prop("disabled",true);
					$("#top_type").prop("disabled",true);
					if(!is_mobile) $("#edit_form").find("#uploadfile").prop("disabled",true);
					if(!is_mobile) $("#edit_form").find("#uploadfile_landing").prop("disabled",true);

					$("#"+form).find("#"+key).val(val);
					$("#module_text").html(module_type[val]);
					switch(val){ 
						case "map":
							top_type = val;
							$("#"+form).find("#top_type").val(val);
							$("#map_code").prop("disabled",false);
							$("#top_type").prop("disabled",false);
							$(".code_type.map").show();
							$(".top_type").show();
							$(".valid_type").hide();
							var use_spot = "<?php echo $this->config->item('use_spot')?>";
							use_spot = (use_spot) ? "Y" : "N";
							$("#"+form).find("#use_spot").val(use_spot);
							break;
						case "slide":
							top_type = val;
							$("#"+form).find("#top_type").val(val);
							$("#slide_code").prop("disabled",false);
							$("#top_type").prop("disabled",false);
							$(".code_type.slide").show();
							$(".top_type").show();
							$(".valid_type").hide();							
							if(!is_mobile){
								$("#edit_form").find("#uploadfile").prop("disabled",false);
							}
							break;
						case "landing":
							top_type = val;
							$("#"+form).find("#top_type").val(val);
							$("#landing_code").prop("disabled",false);
							$("#top_type").prop("disabled",false);
							$(".code_type.landing").show();
							$(".top_type").show();
							$(".valid_type").hide();							

							if(!is_mobile){
								$("#edit_form").find("#uploadfile_landing").prop("disabled",false);
							}
							break;
						case "html":
						case "link":
							$("#html_code").prop("disabled",false);
							$(".code_type.html").show();
							break;
						case "news":
							$("#category_code").prop("disabled",false);
							$(".code_type.category").show();
							$("#category_code").html(news_option);
							break;
						case "portfolio":
							$("#category_code").prop("disabled",false);
							$(".code_type.category").show();
							$("#category_code").html(portfolio_option);
							break;
						case "recent":
						case "recommand":
						case "recommand_type":
							$("#line_code").prop("disabled",false);
							$(".code_type.line").show();
							break;
						default:
							break;
					}
					break;
				default:
					$("#"+form).find("#"+key).val(val);
					break;
			}
		});
		if(top_type=="slide") $("#slide_code").val(code);
		if(top_type=="map") $("#map_code").val(code);
		if(top_type=="landing") $("#landing_code").val(code);
		if($("#category_code").css("display")!="none") $("#category_code").val(code);
		if($("#html_code").css("display")!="none") $("#html_code").val(code);
		if($("#line_code").css("display")!="none") $("#line_code").val(code);
		$('#edit_dialog').dialog("open");
	});
}

function get_list(){
	$.getJSON("/adminfront/slide_json/"+Math.round(new Date().getTime()),function(data){
		if(data == ""){
			$("#list").html("<div style='padding-left:10px;'><?php echo lang("msg.nodata");?></div>");
		} 
		else {
			$("#list").html("");
		}
		var str = "";
		$.each(data, function(key, val) {
			if(val["filename"]!=""){
				var photo = val["filename"].split('.');
				var link = "";
				if(val["link"]!=null) link = val["link"];	
				str += "<li><div class=\"thumbnail\">";
				str += "<a href='/uploads/slide/"+val["filename"]+"' class='fancy'><img data-id='"+val["id"]+"' src='/uploads/slide/"+photo[0]+"."+photo[1]+"' style='width:160px;height:70px'></a>";
				str += "	<div style='padding:5px;'>";
				str += "		<textarea id='slide-"+val["id"]+"' class='form-control' rows='3' style='width:150px;' maxlength='180' placeholder='이미지링크주소'>"+link+"</textarea>";
				str += "	</div>";
				str += "	<div class=\"caption\" style='text-align:center;padding:0px;padding:5px 0px;'>";
				str += "		<a style='cursor:pointer;' onclick=\"image_link('"+val["id"]+"','slide');\"><span class=\"glyphicon glyphicon glyphicon-ok\"></span>링크저장</a>";
				str += "		<a style='cursor:pointer;' onclick=\"slide_delete('"+val["id"]+"');\"><span class=\"glyphicon glyphicon-trash\"></span><?php echo lang("site.delete");?></a>";
				str += "	</div>";
				str += "</div></li>";
			}
		});
		if(str!=""){
			$("#list").html(str);
			$("#list" ).sortable({
				update: function (event, ui) {
					var i=1;
					$("#list").find("img").each(function(){
						$.get("/adminfront/slide_sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
							
						});
						i++;
					});
				}
			});
		}
	});
}

function get_list_landing(){
	$.getJSON("/adminfront/landing_json/"+Math.round(new Date().getTime()),function(data){
		if(data == ""){
			$("#list_landing").html("<div style='padding-left:10px;'><?php echo lang("msg.nodata");?></div>");
		} 
		else {
			$("#list_landing").html("");
		}
		var str = "";
		$.each(data, function(key, val) {
			if(val["filename"]!=""){
				var photo = val["filename"].split('.');
				var link = "";
				if(val["link"]!=null) link = val["link"];
				str += "<li><div class=\"thumbnail\">";
				str += "<a href='/uploads/landing/"+val["filename"]+"' class='fancy'><img data-id='"+val["id"]+"' src='/uploads/landing/"+photo[0]+"."+photo[1]+"' style='width:160px;height:70px'></a>";
				/*
				str += "	<div style='padding:5px;'>";
				str += "		<textarea id='landing-"+val["id"]+"' class='form-control' rows='3' style='width:150px;' maxlength='180' placeholder='이미지링크주소'>"+link+"</textarea>";
				str += "	</div>";
				*/
				str += "	<div class=\"caption\" style='text-align:center;padding:0px;padding:5px 0px;'>";
				/*
				str += "		<a style='cursor:pointer;' onclick=\"image_link('"+val["id"]+"','landing');\"><span class=\"glyphicon glyphicon glyphicon-ok\"></span>링크저장</a>";
				*/
				str += "		<a style='cursor:pointer;' onclick=\"landing_delete('"+val["id"]+"');\"><span class=\"glyphicon glyphicon-trash\"></span><?php echo lang("site.delete");?></a>";
				str += "	</div>";
				str += "</div></li>";
			}
		});
		if(str!=""){
			$("#list_landing").html(str);
			$("#list_landing" ).sortable({
				update: function (event, ui) {
					var i=1;
					$("#list_landing").find("img").each(function(){
						$.get("/adminfront/landing_sorting/"+$(this).attr("data-id")+"/"+i+"/"+Math.round(new Date().getTime()),function(){
							
						});
						i++;
					});
				}
			});
		}
	});
}

function slide_delete(id){
	if(confirm("정말로 삭제하시겠습니까?")){
		$.get("/adminfront/slide_delete/"+id+"/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				get_list();
		   } else {
				alert("삭제 실패");
		   }
		});
	}
}

function landing_delete(id){
	if(confirm("정말로 삭제하시겠습니까?")){
		$.get("/adminfront/landing_delete/"+id+"/"+Math.round(new Date().getTime()),function(data){
		   if(data=="1"){
				get_list_landing();
		   } else {
				alert("삭제 실패");
		   }
		});
	}
}

function change_type(value){
	$(".code_type").hide();
	if(value=="slide"){
		$("#slide_code").prop("disabled",false);
		$("#edit_form").find("#slide_code").val("1");
		if(!is_mobile){
			$("#edit_form").find("#uploadfile").prop("disabled",false);
			$(".code_type.slide").show();
		}
	}
	else if(value=="map"){
		$("#map_code").prop("disabled",false);
		$(".code_type.map").show();
		$("#edit_form").find("#map_code").val("1");
	}
	else if(value=="landing"){
		$("#landing_code").prop("disabled",false);
		$("#edit_form").find("#landing_code").val("1");
		if(!is_mobile){
			$("#edit_form").find("#uploadfile_landing").prop("disabled",false);
			$(".code_type.landing").show();
		}	
	}
}

function image_link(id,type){

	var link = $("#"+type+"-"+id).val();

	$.ajax({
		type: "post",
		url: "/adminfront/top_layout_link/"+id+"/"+type,
		data: {
			link : link
		},
		cache: false,
		success: function(data){
			if(type=="slide"){
				get_list();
			}
			else if(type=="landing"){
				get_list_landing();
			}
		},
		error:function(e){
		}
	});
}
</script>	

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				메인페이지 설정
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>
					메인페이지 설정
				</li>
			</ul>
			<div class="page-toolbar">
				
			</div>
		</div>
	</div>
</div><!-- /.row -->

		 <div class="row">
			<div class="col-lg-6">
				<div class="help-block">* 제목을 클릭하면 수정을 할 수 있습니다.</div>
				<div class="help-block">* 마우스를 드래그하여 위치를 변경 하실 수 있습니다.</div>
				<table class="table table-bordered table-striped table-condensed flip-content">
					<thead>
						<tr>
							<th class="text-center" style="width:25px;"><i class="fa fa-arrows"></i></th>
							<th class="text-center" style="width:150px;">구성</th>
							<th class="text-center">타이틀명</th>
							<th class="text-center" style="width:80px;">사용여부</th>
						</tr>
					</thead>
					<tbody id="sort_list">
						<?php
						foreach($query as $key=>$val){
							if($key==0){ ?>
						<tr>
							<td class="text-center"><i class="fa fa-times"></i></td>
							<td class="text-center">상단형태</td>
							<td><a href="#" onclick="edit('<?php echo $val->id;?>','edit_form');"><?php if(strip_tags($val->title)==""){echo "[제목없음]";} else { echo strip_tags($val->title);}?></a></td>
							<td class="text-center">필수</td>
						</tr>
							<?php } else { ?>						
						<tr class="is_sortable" data-id="<?php echo $val->id;?>">
							<td class="text-center"><i class="fa fa-sort"></i></td>
							<td class="text-center"><?php echo $module_type[$val->module];?></td>
							<td><a href="#" onclick="edit('<?php echo $val->id;?>','edit_form');"><?php if(strip_tags($val->title)==""){echo "[제목없음]";} else { echo strip_tags($val->title);}?></a></td>
							<td class="text-center"><?php if($val->valid=="Y") {echo "사용";} else {echo "미사용";}?></td>
						</tr>
							<?php }
						}?>
					</tbody>
				</table>
			</div>
		</div>
</div>

<div id="edit_dialog" title="정보 수정" style="display:none;">
<?php echo form_open_multipart("adminfront/edit_action",Array("id"=>"edit_form"))?>
	<input type="hidden" id="id" name="id">
	<table id="edit_form_table" class="table table-bordered table-striped table-condensed flip-content">
		<tr class="top_type">
			<th>형태</th>
			<td>
				<select id="top_type" name="top_type" class="form-control input-small select2me" onchange="change_type(this.value)" style="width:120px;">
					<option value="slide">슬라이드형</option>
					<option value="map">지도형</option>
					<option value="landing">랜딩형</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>타이틀명</th>
			<td><input type="text" class="form-control" id="title" name="title" placeholder="타이틀명"></td>
		</tr>
		<tr class="valid_type">
			<th>사용여부</th>
			<td>
				<select id="valid" name="valid" class="form-control input-small select2me" style="width:80px;">
					<option value="Y">사용</option>
					<option value="N">미사용</option>
				</select>
			</td>
		</tr>
		<tr class="code_type slide">
			<th>검색창 형태</th>
			<td>
				<!-- 중앙 탭이 없음 추가 : 강대중 2016년 2월 17일 -->
				<select id="slide_code" name="slide_code" class="form-control input-medium select2me" style="width:100px;">
					<option value="1">중앙(탭있음) 형태</option>
					<option value="3">중앙(탭없음) 형태</option>
					<option value="2">하단(탭있음) 형태</option>
				</select>	
			</td>
		</tr>
		<tr class="code_type map">
			<th>검색창 형태</th>
			<td>
				<select id="map_code" name="map_code" class="form-control input-small select2me" style="width:100px;">
					<option value="1">우측 형태</option>
					<option value="2">바 형태</option>
				</select>	
			</td>
		</tr>
		<tr class="code_type landing">
			<th>검색창 형태</th>
			<td>
				<select id="landing_code" name="landing_code" class="form-control input-medium select2me" style="width:100px;">
					<option value="1">중앙(탭있음) 형태</option>
					<option value="2">중앙(탭없음) 형태</option>
				</select>	
			</td>
		</tr>
		<tr class="code_type map">
			<th>지도위치 바로가기</th>
			<td>
				<select id="use_spot" name="use_spot" class="form-control input-small select2me" style="width:100px;">
					<option value="Y">사용</option>
					<option value="N">미사용</option>
				</select>	
			</td>
		</tr>
		<tr class="code_type html">
			<th>내용편집</th>
			<td><textarea class="form-control" rows="16" id="html_code" name="html_code" placeholder="HTML 코드"></textarea></td>
		</tr>
		<tr class="code_type category">
			<th><?php echo lang("product.category");?></th>
			<td>
				<select id="category_code" name="category_code" class="form-control input-large select2me" style="width:130px"></select>			
			</td>
		</tr>
		<tr class="code_type line">
			<th>라인수 설정</th>
			<td>
				<select id="line_code" name="line_code" class="form-control input-small select2me">
					<option value="1">1줄</option>
					<option value="2">2줄</option>
					<option value="3">3줄</option>
					<option value="4">4줄</option>
					<option value="5">5줄</option>
				</select>			
			</td>
		</tr>
		<?php
			if(!MobileCheck()){
		?>
		<tr class="code_type slide">
			<th style="width:100px;">배경 사진</th>
			<td>
				<div class="form-group">
					<div>
						<button type="button" id="browse" class="btn btn-primary" style="margin-bottom:15px;"><i class="fa fa-file-image-o"></i> 멀티 파일 선택</button></br>
						<span><strong>2000 * 450 픽셀(와이드) / 1200 * 450 픽셀(박스)</strong></span>
						<pre id="console" style="display:none;"></pre>
						<ul class="row" id="list"></ul>
					</div>
				</div>
				<div id="upload_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;">
					<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;" autocomplete='off'/>
				</div>
			</td>	
		</tr>
		<tr class="code_type landing">
			<th style="width:100px;">배경 사진</th>
			<td>
				<div class="form-group">
					<div>
						<button type="button" id="browse_landing" class="btn btn-primary" style="margin-bottom:15px;"><i class="fa fa-file-image-o"></i> 멀티 파일 선택</button><br/>
						<span><strong>1600 * 850 이상</strong></span>
						<pre id="console" style="display:none;"></pre>
						<ul class="row" id="list_landing"></ul>
					</div>
				</div>
				<div id="upload_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;">
					<input type="file" name="uploadfile_landing" id="uploadfile_landing" style="width:300px;border:0px;" autocomplete='off'/>
				</div>
			</td>	
		</tr>
		<?php } ?>
	</table>
<?php echo form_close();?>
</div>