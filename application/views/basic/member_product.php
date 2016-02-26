<script>
$(document).ready(function(){
	$('#check_all').change(function(){
		var check_state = $(this).prop('checked');
		$("input[name='check_product[]']").each(function(i){
			$(this).prop('checked',check_state);
		});
	});
});

function product_edit(id){
	location.href="/member/product_edit/"+id;
}

function product_delete(id){
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
}

function state_change(id,state){
	if(!state){
		if(!confirm("대기중으로 변경하시겠습니까?")) return false;
	}
	$.ajax({
		url: "/member/state_change/is_activated",
		type: "POST",
		data: {
			state : state,
			id: id
		},
		success: function(data) {
			if(data=='success'){
				var enabled_count = parseInt($("#enabled_count").html());
				if(state){
					$("#enabled_count").html(enabled_count-1);				
					$("#state_button"+id).html('<button onclick="state_change('+id+',0);" type="button" class="btn btn-info">게시중</button>');
				}
				else{
					$("#enabled_count").html(enabled_count+1);
					$("#state_button"+id).html('<button onclick="state_change('+id+',1);" type="button" class="btn btn-warning">대기중</button>');
				}				
			}
			else if(data=='fail'){
				alert("광고 횟수를 모두 사용하였습니다.");
			}
			else if(data=='no_pay'){
				alert("<?php echo lang("pay");?>을 구매 해주시기 바랍니다.");
				location.href="/member/product_pay";
			}
		}
	});	
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
              <li class="list-group-item clearfix active"><a href="/member/product"><i class="fa fa-angle-right"></i> <?php echo lang("product");?>관리</a></li>
              <li class="list-group-item clearfix"><a href="/member/product_add"><i class="fa fa-angle-right"></i> <?php echo lang("product");?>등록</a></li>
			  <?php if($config->USE_PAY){?>
              <li class="list-group-item clearfix"><a href="/member/product_pay"><i class="fa fa-angle-right"></i> <?php echo lang("pay");?></a></li>
			  <li class="list-group-item clearfix"><a href="/member/pay"><i class="fa fa-angle-right"></i> 결제내역</a></li>
			  <?php }?>
			  <?php if($this->session->userdata("type")=="admin" || $this->session->userdata("type")=="biz"){?>
			  <li class="list-group-item clearfix"><a href="/member/blog"><i class="fa fa-angle-right"></i> 나의블로그</a></li>
			  <?php }?>
            </ul>
          </div>
          <!-- END SIDEBAR -->

          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-9">
            <h1><?php echo lang("product");?>관리</h1>
            <div class="content-form-page" style="padding:0">
			  <?php if($pay_info){?>
			  <h4 class="text-right">광고공개 가능한 건 수 : <span id="enabled_count"><?php echo $pay_info->enabled_count;?></span>건</h4>
			  <?php }?>
			  <div class="row">
                <div class="col-md-12 col-sm-12">
					<form  action="/member/delete_all_product" id="list_form" method="post" role="form">
					<table class="table">
						<tr>
							<th class="text-center">
								<span class="radiobox-wrap"><input class="checkbox" type='checkbox' id='check_all'/></span>
							</th>
							<th class="text-center"><?php echo lang("site.photo");?></th>
							<th class="text-center"><?php echo lang("site.title");?></th>
							<th class="text-center"><?php echo lang("site.price");?></th>
							<?php if($this->session->userdata("type")=="admin" || $this->session->userdata("type")=="biz"){?>
							<th class="text-center">포스팅</th>
							<?php }?>
							<th class="text-center"><?php echo lang("site.status");?></th>
							<?php if($config->USE_APPROVE){?>
							<th class="text-center">승인처리</th>
							<?php }?>
							<th class="text-center"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
						</th>
						<?php foreach($query as $val){?>
						<tr>
							<td class="text-center">
								<span class="radiobox-wrap">
									<input type="checkbox" name="check_product[]" value="<?php echo $val->id;?>" class="checkbox">
								</span>
							</td>
							<td class="text-center">
								<?php	if($val->thumb_name==""){
									echo anchor("product/view/".$val->id,"<img src=\"/assets/common/img/no_thumb.png\" style=\"width:110px;\">");
								} else {
									echo anchor("product/view/".$val->id,"<img src=\"/photo/gallery_thumb/".$val->gallery_id."\" style=\"height:110px;width:110px;\">");
								}?>
							</td>
							<td>
								<?php echo anchor("product/view/".$val->id,$val->title);?>
							</td>
							<td>
								<?php echo price($val,$config);?>
							</td>
							<?php if($this->session->userdata("type")=="admin" || $this->session->userdata("type")=="biz"){?>
							<td>
								<div>
									<button type="button" type="button" class="btn btn-link btn-sm" onclick="blog('<?php echo $val->id?>');"><i class="fa fa-share-alt"></i> 블로그(<?php echo $val->is_blog?>)</button>
								</div>
								<?php if($config->navercskey && $config->navercssecret && $config->naverclientkey && $config->naverclientsecret ){?>
								<div>									
									<button type="button" type="button" class="btn btn-link btn-sm" onclick="cafe('<?php echo $val->id?>');"><i class="fa fa-share-alt"></i> N카페(<?php echo $val->is_cafe?>)</button>	
								</div>
								<?php }?>
								<?php if($config->daumclientkey && $config->daumclientsecret){?>
								<div>									
									<button type="button" type="button" class="btn btn-link btn-sm" onclick="daum_blog('<?php echo $val->id?>');"><i class="fa fa-share-alt"></i> 다음블로그(<?php echo $val->is_blog_daum?>)</button>	
								</div>
								<?php }?>
							</td>
							<?php }?>
							<td class="text-center" id="state_button<?php echo $val->id;?>">
								<?php if($val->is_activated){?>
								<button onclick="state_change(<?php echo $val->id;?>,0);" type="button" class="btn btn-info">게시중</button>
								<?php } else { ?>
								<button onclick="state_change(<?php echo $val->id;?>,1);" type="button" class="btn btn-warning">대기중</button>
								<?php } ?>
							</td>
							<?php if($config->USE_APPROVE){?>
							<td class="text-center">								
								<?php if($val->is_valid){?>
								<button type="button" class="btn btn-info" style="cursor:default">승인</button>
								<?php } else { ?>
								<button type="button" class="btn btn-warning" style="cursor:default">승인대기</button>
								<?php } ?>								
							</td>
							<?php }?>
							<td class="text-center">
								<button onclick="product_edit('<?php echo $val->id;?>');" type="button" class="btn btn-default"><?php echo lang("site.modify");?></button>
							</td>
						</tr>
						<?php }
						
						if(count($query) < 1){
							echo "<tr><td colspan='6' class='text-center'>".lang("msg.nodata")."</td></tr>";
						}
						?>
					</table>
					</form>
					<?php if(count($query) > 0){?>
					<div class="text-right">
						<button onclick="product_delete();" type="button" class="btn btn-danger"><?php echo lang("site.delete");?></button>
					</div>
					<?php }?>
					<div class="row text-center">
						<div class="col-sm-12">
							<ul class="pagination" style="float:none;">
								<?php echo $pagination;?>
							</ul>
						</div>
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