<?php 
	$this->lang->load('site');
	$this->lang->load('admin');
	$this->load->helper('language');
?>
<script>
$(document).ready(function(){

	$('#check_all').change(function(){
		var check_state = $(this).prop('checked');
		$("input[name='check_blog[]']").each(function(i){
			$(this).prop('checked',check_state);
		});
	});

	$("#blog_add_form").validate({  
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

	$("#blog_edit_form").validate({  
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

});

function change_form(value){
	if(value=='naver'){
		$('.tistory').hide('fast');
	}
	if(value=='tistory'){
		$('.tistory').show('fast');	
	}
}

function blog_delete(){
	var check_length = $("input[name='check_blog[]']:checked").length;
	if(!check_length){
		alert('삭제할 블로그를 선택 해주시기 바랍니다.');
		return;
	}
	else{
		if(confirm('선택한 블로그를 삭제 하시겠습니까?')){
			$('#list_form').submit();
		}
	}
}

function blog_edit(id){
	$.getJSON("/member/get_json_blog/"+id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			if(key=="type"){
				if(val=="naver") $(".tistory").hide();
				else $(".tistory").show();
			}
			if(key=="id"){
				$("#blog_edit_form").find("#id").val(val);
			}
			else{
				$("#blog_edit_form").find("#"+key).val(val);
			}
		});
	});
}

</script>
	<div class="main">
		<div class="_container">

		<ul class="breadcrumb">
            <li><a href="/"><?php echo lang("menu.home");?></a></li>
            <li class="active"><?php echo lang("product");?>관리</li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-3">
            <ul class="list-group margin-bottom-25 sidebar-menu">
              <li class="list-group-item clearfix"><a href="/member/product"><i class="fa fa-angle-right"></i> <?php echo lang("product");?>관리</a></li>
              <li class="list-group-item clearfix"><a href="/member/product_add"><i class="fa fa-angle-right"></i> <?php echo lang("product");?>등록</a></li>
			  <?php if($config->USE_PAY){?>
              <li class="list-group-item clearfix"><a href="/member/product_pay"><i class="fa fa-angle-right"></i> <?php echo lang("pay");?></a></li>
			  <li class="list-group-item clearfix"><a href="/member/pay"><i class="fa fa-angle-right"></i> 결제내역</a></li>
			  <?php }?>
			  <li class="list-group-item clearfix active"><a href="/member/blog"><i class="fa fa-angle-right"></i> 나의블로그</a></li>
            </ul>
          </div>
          <!-- END SIDEBAR -->

          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-9">
            <h1>나의 블로그</h1>
            <div class="content-form-page" style="padding:0">
			  <div class="row">
                <div class="col-md-12 col-sm-12">
					<form  action="/member/delete_all_blog" id="list_form" method="post" role="form">
					<table class="table">
						<tr>
							<th class="text-center">
								<span class="radiobox-wrap"><input class="checkbox" type='checkbox' id='check_all'/></span>
							</th>
							<th class="text-center">블로그</th>
							<th class="text-center">포스팅</th>
							<th class="text-center">정보</th>
							<th class="text-center"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
						</th>
						<?php 
						foreach($query as $val){?>
						<tr>
							<td class="text-center">
								<span class="radiobox-wrap">
									<input type="checkbox" name="check_blog[]" value="<?php echo $val->id;?>" class="checkbox">
								</span>
							</td>
							<td class="text-center"><?php echo ($val->type=="naver") ? "네이버" : "다음 티스토리";?></td>
							<td class="text-center">
							<?php 
								if($val->valid=="Y"){
									echo "<i class='fa fa-toggle-on'></i>";
								} else {
									echo "<i class='fa fa-toggle-off'></i>";
								}							
							?>
							</td>
							<td class="text-center">
								<b>로그인ID</b>: <?php echo $val->user_id;?><br>
								<b>블로그주소</b>: <?php echo $val->address;?><br/>
								<b>블로그ID</b>: <?php echo $val->blog_id;?><br/>
								<b>키 / 암호</b>: <?php echo $val->blog_key;?>							
							</td>
							<td class="text-center">
								<button  type="button" class="btn btn-default" data-toggle="modal" data-target="#blog_edit_dialog" onclick="blog_edit('<?php echo $val->id;?>');"><?php echo lang("site.modify");?></button>
							</td>
						</tr>
						<?php }
						
						if(count($query)<1){
							echo "<tr><td class='text-center' colspan='5'>".lang("msg.nodata")."</td></tr>";
						}
						?>
					</table>
					</form>
					<div class="row text-center">
						<div class="col-sm-12">
							<ul class="pagination" style="float:none;">
								<?php echo $pagination;?>
							</ul>
						</div>
					</div>
					<div class="text-right">
						<button onclick="blog_delete();" type="button" class="btn btn-danger" <?php if(count($query)<1) echo "disabled";?>><?php echo lang("site.delete");?></button>
						<button onclick="" type="button" class="btn btn-primary" data-toggle="modal" data-target="#blog_add_dialog">등록</button>
					</div>
				</div>
              </div>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
	</div>
</div>

<!-- BLOG ADD FORM -->
<?php echo form_open_multipart("member/blog_add_action",Array("id"=>"blog_add_form"))?>
<div id="blog_add_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="max-width:450px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><strong>블로그 등록</strong></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="recipient-name" class="control-label"><strong>블로그</strong></label>
					<select name="type" class="form-control" onchange="change_form(this.value)">
						<option value="">선택해주세요</option>
						<option value="naver">네이버</option>
						<option value="tistory">다음 티스토리</option>
					</select>
				</div>
				<div class="form-group">
					<label for="recipient-name" class="control-label"><strong>포스팅여부</strong></label>
					<select name="valid" class="form-control">
						<option value="Y" selected>포스팅함</option>
						<option value="N">포스팅하지 않음</option>
					</select>
				</div>
				<div class="form-group">
					<label for="recipient-name" class="control-label"><strong>로그인 아이디</strong></label>
					<input type="text" class="form-control" name="user_id"/>
				</div>
				<div class="form-group tistory">
					<label for="recipient-name" class="control-label"><strong>블로그 주소</strong></label>
					<input type="text" class="form-control" name="address"/>
				</div>
				<div class="form-group tistory">
					<label for="recipient-name" class="control-label"><strong>블로그 아이디</strong></label>
					<input type="text" class="form-control" name="blog_id"/>
				</div>
				<div class="form-group">
					<label for="recipient-name" class="control-label"><strong>블로그 키 또는 암호</strong></label>
					<input type="text" class="form-control" name="blog_key"/>
				</div>				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
				<button type="submit" class="btn btn-primary"><?php echo lang("site.submit");?></button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close();?>
<!-- BLOG ADD FORM -->

<!-- BLOG EDIT FORM -->
<?php echo form_open_multipart("member/blog_edit_action",Array("id"=>"blog_edit_form"))?>
<input type="hidden" id="id" name="id"/>
<div id="blog_edit_dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="max-width:450px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><strong>블로그 <?php echo lang("site.modify");?></strong></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="recipient-name" class="control-label"><strong>블로그</strong></label>
					<select id="type" name="type" class="form-control" onchange="change_form(this.value)">
						<option value="">선택해주세요</option>
						<option value="naver">네이버</option>
						<option value="tistory">다음 티스토리</option>
					</select>
				</div>
				<div class="form-group">
					<label for="recipient-name" class="control-label"><strong>포스팅여부</strong></label>
					<select id="valid" name="valid" class="form-control">
						<option value="Y" selected>포스팅함</option>
						<option value="N">포스팅하지 않음</option>
					</select>
				</div>
				<div class="form-group">
					<label for="recipient-name" class="control-label"><strong>로그인 아이디</strong></label>
					<input type="text" class="form-control" id="user_id" name="user_id"/>
				</div>
				<div class="form-group tistory">
					<label for="recipient-name" class="control-label"><strong>블로그 주소</strong></label>
					<input type="text" class="form-control" id="address" name="address"/>
				</div>
				<div class="form-group tistory">
					<label for="recipient-name" class="control-label"><strong>블로그 아이디</strong></label>
					<input type="text" class="form-control" id="blog_id" name="blog_id"/>
				</div>
				<div class="form-group">
					<label for="recipient-name" class="control-label"><strong>블로그 키 또는 암호</strong></label>
					<input type="text" class="form-control" id="blog_key" name="blog_key"/>
				</div>				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("site.close");?></button>
				<button type="submit" class="btn btn-primary"><?php echo lang("site.modify");?></button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close();?>
<!-- BLOG EDIT FORM -->