<script>
$(document).ready(function(){

	$("#add_form").validate({  
        errorElement: "span",
        wrapper: "span",  
		rules: {
			type: {  
				required: true
			},
			user_id: {  
				required: true
			},
			address: {  
				required: true
			},
			blog_id: {  
				required: true
			},
			blog_key: {  
				required: true
			}
		},  
		messages: {  
			type: {  
				required: "블로그를 선택해주세요"
			},
			user_id: {  
				required: "로그인아이디를 입력해주세요"
			},
			address: {  
				required: "블로그주소를 입력해주세요"
			},
			blog_id: {  
				required: "블로그아이디를 입력해주세요"
			},
			blog_key: {  
				required: "블로그키를 선택해주세요"
			}
		} 
	});  

	$("#edit_dialog").dialog({
			title: "정보 수정",
			bgiframe: true,
			resizable: false,
			autoOpen: false,
			width:650,
			height: 400,
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
});

function edit(id,form){
	$.getJSON("/adminblogapi/get_json/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			$("#"+form).find("#"+key).val(val);
		});

		$('#edit_dialog').dialog("open");	
	});
}

function data_delete(id){
	if(confirm("삭제하시겠습니까?")){
		location.href="/adminblogapi/delete_action/"+id;
	}
}

function change_form(value){
	if(value=='naver'){
		$('.tistory').hide();
	}
	if(value=='tistory'){
		$('.tistory').show();	
	}
}
</script>	

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
			블로그 <small>관리</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i> <a href="/adminhome/index"><?php echo lang("menu.home");?></a> <i class="fa fa-angle-right"></i> </li>
				<li>블로그 관리</li>
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<div class="help-block">* 제목을 클릭하면 수정을 할 수 있습니다.</div>
		<div class="help-block">* 네이버는 로그인ID, 블로그주소, 블로그ID 세 개가 모두 동일하게 입력해주세요.</div> 
		<div class="help-block"><a href="https://sites.google.com/site/dungzimanual/beullogeuyeondong" class="btn btn-info btn-xs" target="_blank">블로그 매뉴얼 다운로드</a></div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead>
				<tr>
					<th>블로그</th>
					<th>포스팅</th>
					<th>정보</th>
					<th>포스팅수</th>
					<th style="width:30px;">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if(count($query)<1){
					echo "<tr><td colspan='5' class='text-center'>".lang("msg.nodata")."</td></tr>";
				}
				foreach($query as $val){?>
				<tr>
					<td><?php 
							if($val->type=="naver"){
								echo "네이버";
							} else if($val->type=="tistory"){
								echo "티스토리";
							}
					
						?></td>
					<td><?php 
							if($val->valid=="Y"){
								echo "<i class='fa fa-toggle-on'></i>";
							} else {
								echo "<i class='fa fa-toggle-off'></i>";
							}
					
						?></td>
					<td>
						<b>로그인ID</b>: <a href="#" onclick="edit('<?php echo $val->id;?>','edit_form');"><?php echo $val->user_id;?></a><br>
						<b>블로그주소</b>: <?php echo $val->address;?><br/>
						<b>블로그ID</b>: <?php echo $val->blog_id;?><br/>
						<b>키 / 암호</b>: <?php echo $val->blog_key;?>
					</td>
					<td><?php echo $val->cnt;?></td>
					<td><button class="btn btn-xs btn-danger" onclick="data_delete('<?php echo $val->id;?>');"><i class="fa fa-trash-o"></i></button></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	<div class="col-lg-6">
		<div class="portlet">
			<?php echo form_open("adminblogapi/add_action",Array("id"=>"add_form"))?>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th class="text-center vertical-middle">블로그</th>
						<td>
							<select class="form-control select2me" name="type" onchange="change_form(this.value)">
								<option value="">선택해주세요.</option>
								<option value="naver">네이버</option>
								<option value="tistory">다음 티스토리</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">포스팅여부</th>
						<td>
							<select class="form-control select2me" name="valid">
								<option value="Y" selected>포스팅함</option>
								<option value="N">포스팅하지 않음</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">로그인 아이디</th>
						<td>
							<input type="text" class="form-control" name="user_id" placeholder="로그인 아이디"/>
						</td>
					</tr>
					<tr class="tistory">
						<th class="text-center vertical-middle">블로그 주소</th>
						<td>
							<input type="text" class="form-control" name="address" placeholder="블로그 주소"/>
						</td>
					</tr>
					<tr class="tistory">
						<th class="text-center vertical-middle">블로그 아이디</th>
						<td>
							<input type="text" class="form-control" name="blog_id" placeholder="블로그 아이디"/>
						</td>
					</tr>
					<tr>
						<th class="text-center vertical-middle">블로그 키 또는 암호</th>
						<td>
							<input type="text" class="form-control" name="blog_key" placeholder="블로그 키"/>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="text-center">
				<button type="submit" class="btn btn-primary">블로그 등록</button>
			</div>
			<?php echo form_close();?>
		</div>
	</div>
</div>

<div id="edit_dialog" title="블로그 api 정보 수정" style="display:none;">
<?php echo form_open("adminblogapi/edit_action",Array("id"=>"edit_form"))?>
	<input type="hidden" id="id" name="id"/>
	<table class="table table-bordered table-striped table-condensed flip-content">
		<tr>
			<th>블로그</th>
			<td>
				<select class="form-control select2me" id="type" name="type">
					<option value="naver">네이버 블로그</option>
					<option value="tistory">다음 티스토리</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>포스팅여부</th>
			<td>
				<select class="form-control select2me" id="valid" name="valid">
					<option value="Y">포스팅함</option>
					<option value="N">포스팅하지 않음</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>로그인 아이디</th>
			<td>
				<input type="text" class="form-control" id="user_id" name="user_id" placeholder="로그인 아이디"/>
			</td>
		</tr>
		<tr>
			<th>블로그 주소</th>
			<td>
				<input type="text" class="form-control" id="address" name="address" placeholder="블로그 주소"/>
			</td>
		</tr>
		<tr>
			<th>블로그 아이디</th>
			<td>
				<input type="text" class="form-control" id="blog_id" name="blog_id" placeholder="블로그 아이디"/>
			</td>
		</tr>
		<tr>
			<th>블로그 키 또는 암호</th>
			<td>
				<input type="text" class="form-control" id="blog_key" name="blog_key" placeholder="블로그 키"/>
			</td>
		</tr>
	</table>
<?php echo form_close();?>
</div>