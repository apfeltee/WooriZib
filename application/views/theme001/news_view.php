<script>
$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */			

		$("#add_form").ajaxForm({
			beforeSubmit:function(){
				$("#add_form").validate({ 
					rules: {
						name: {  
							required: true,  
							minlength: 2
						},
						pw: {  
							required: true
						},
						content: {  
							required: true,  
							minlength: 5
						}
					},  
					messages: {  
						name: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.2");?>"
						},
						pw: {
							required: "<?php echo lang("form.required");?>"
						},
						content: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.5");?>"
						}
					} 
				});
				if (!$("#add_form").valid()) return false;
			},
			success:function(data){
				if(data=="success"){
					get_comment();
					$("#comment_add_name").val("");
					$("#comment_add_pw").val("");
					$("#comment_add_content").val("");
					$("#comment_alert").hide();
				} else {
					msg($("#comment_alert"), "danger" ,"<?php echo lang("form.fail");?>");
				}
			}
		});

		$("#edit_form").ajaxForm({
			beforeSubmit:function(){
				$("#edit_form").validate({ 
					rules: {
						name: {  
							required: true,  
							minlength: 2
						},
						pw: {  
							required: true
						},
						content: {  
							required: true,  
							minlength: 5
						}
					},  
					messages: {  
						name: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.2");?>"
						},
						pw: {
							required: "<?php echo lang("form.required");?>"
						},
						content: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.5");?>"
						}
					} 
				});
				if (!$("#edit_form").valid()) return false;
			},
			success:function(data){
				if(data=="success"){
					$('#editModal').modal('hide');
					get_comment();
					$("#comment_edit_name").val("");
					$("#comment_edit_pw").val("");
					$("#comment_edit_content").val("");
				} else {
					if(data=="fail"){
						msg($("#comment_edit_alert"), "danger" ,"<?php echo lang("form.pwerror");?>");				
					}
				}
			}
		});

		$("#reply_form").ajaxForm({
			beforeSubmit:function(){
				$("#reply_form").validate({ 
					rules: {
						name: {  
							required: true,  
							minlength: 2
						},
						pw: {  
							required: true
						},
						content: {  
							required: true,  
							minlength: 5
						}
					},  
					messages: {  
						name: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.2");?>"
						},
						pw: {  
							required: "<?php echo lang("form.required");?>"
						},
						content: {  
							required: "<?php echo lang("form.required");?>",  
							minlength: "<?php echo lang("form.5");?>"
						}
					} 
				});
				if (!$("#reply_form").valid()) return false;
			},
			success:function(data){
				if(data=="success"){
					get_comment();
					$('#replyModal').modal('hide');
					$("#reply_name").val("");
					$("#reply_pw").val("");
					$("#reply_content").val("");
				} else {
					msg($("#comment_reply_alert"), "danger" ,"<?php echo lang("form.fail");?>");	
				}
			}
		});		

		$("#delete_form").ajaxForm({
			success:function(data){
				if(data=="success"){
					get_comment();
					$('#deleteModal').modal('hide');
					$("#delete_pw").val("");
				} else {
					msg($("#comment_delete_alert"), "danger" ,"<?php echo lang("form.pwerror");?>");
				}
			}
		});		

		get_comment();
});

function get_comment(){
	$.getJSON("/newscomment/get_json/<?php echo $query->id;?>/"+Math.round(new Date().getTime()),function(data){
			var str = "";
			$.each(data, function(key, val) {
				if(val["step_id"]=="0") {
				str += "<div class='comment-reply-item'>";
				} else {
				str += "<div class='comment-reply-item comment-reply-item-reply'>";
				}
				str += "<b>"+val["name"]+"</b>  <small>"+val["date"]+"</small> ";
				if(val["step_id"]=="0") {
					str += " <a href='#replyModal' role=\"button\" data-toggle=\"modal\" class='btn btn-small btn-warning' style='color:white;padding:0px 5px 0px 2px;' onclick='open_reply(\""+val["id"]+"\")'><i class='fa fa-reply'></i> 답글</a>";
				}
				if(val["member_id"]=="0") {
					str+=" <a href='#deleteModal' role=\"button\" data-toggle=\"modal\" onclick=\"open_delete('"+val["id"]+"',"+val["step_id"]+")\"><i class='fa fa-trash-o'></i> <?php echo lang("site.delete");?></a> ";
					str += "<a href='#editModal' role=\"button\" data-toggle=\"modal\" onclick=\"open_edit('"+val["id"]+"',"+val["step_id"]+",'"+val["news_id"]+"')\"><i class='fa fa-pencil-square-o'></i> <?php echo lang("site.modify");?></a>";
				} else {
					if(val["member_id"]=="<?php echo $this->session->userdata("id");?>") {
						str+=" <button type='button' class='btn btn-small' style='padding:0px 5px 0px 2px;'><?php echo lang("site.delete");?></button> <button type='button' class='btn btn-small' style='padding:0px 5px 0px 2px;'><?php echo lang("site.modify");?></button>";
					}
				}
				if(val["delete"]=="Y"){
					str += "<br/><i class='fa fa-quote-left'></i> <span style='text-decoration:line-through;'>삭제된 댓글 입니다.</span>";					
				}
				else{
					str += "<br/><i class='fa fa-quote-left'></i> "+val["content"];
				}
				str += "</div>";
			});
			if(str=="") str ="<div><?php echo lang("msg.nodata");?></div>";
			$("#comment_list").html(str);
	});
}

/**
 * 댓글의 답글 달기
 */
function open_reply(id){
	$("#parent_id").val(id);	
}

function open_delete(id,step_id){
	$("#delete_id").val(id);
	$("#delete_step_id").val(step_id);
}

function open_edit(id,step_id,news_id){
	$("#edit_id").val(id);
	$("#edit_step_id").val(step_id);
	$.getJSON("/newscomment/get_one_json/"+id+"/"+step_id+"/"+news_id+"/"+Math.round(new Date().getTime()),function(data){
		$.each(data, function(key, val) {
			if(key=="name") $("#comment_edit_name").val(val);
			if(key=="content") $("#comment_edit_content").val(val);
		});
	});

}

</script>

	<div class="main">
      <div class="<?php echo (MobileCheck()) ? "container" : "_container"?>">
        <ul class="breadcrumb">
            <li><a href="/"><?php echo lang("menu.home");?></a></li>
            <li>
				<a href="#">
				<?php foreach($mainmenu as $val){
					if($val->type=="news") echo $val->title;
				}?>				
				</a>
			</li>
            <li class="active">보기</li>
        </ul>

        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN CONTENT -->
          <div class="col-md-12 col-sm-12">
            <h1>
			<?php foreach($mainmenu as $val){
				if($val->type=="news") echo $val->title;
			}?>	보기		
			</h1>
            <div class="content-page">
              <div class="row">
                <!-- BEGIN LEFT SIDEBAR -->            
                <div class="col-lg-9 news-item">
				  <h3><a href="#"><?php echo $query->title;?></a></h3>
				  <ul class="news-info">
				  	<?php if($config->NEWS_DATE_VIEW){?>
                    <!--li><i class="fa fa-user"></i> <?php echo $query->member_name;?></li-->
                    <li><i class="fa fa-calendar"></i> <?php echo $query->date;?></li>
                    <li><i class="fa fa-comments"></i> <?php echo $query->viewcnt;?></li>
                    <?php }?>
                    <li><i class="fa fa-tags"></i> <?php echo $query->tag;?></li>
                  </ul>

                  <div class="news-item-img">
					<?php if($query->thumb_name!=""){
						echo "<img src='/uploads/news/".$query->thumb_name."' class='img-responsive'>";
					}?>
                  </div>
                
                  <p><?php echo $query->content;?></p>
                  
				  <?php if($config->news_reply!="N"){?>
                  <h3>댓글</h3>
				  <div id="comment_list"></div>

                  <div class="post-comment padding-top-40">
					<div id="comment_alert"></div>
                    <h3>댓글 남기기</h3>
					  <?php echo form_open("newscomment/add_action","id='add_form'");?>
					  <!-- 회원일 경우에는 비밀번호를 입력하지 않는다 -->
					  <input type="hidden" name="news_id" value="<?php echo $query->id;?>">
					  <input type="hidden" name="member_id" value="<?php echo $this->session->userdata("id");?>">
                    <form role="form">
                      <div class="form-group">
                        <label><?php echo lang("site.name");?></label>
						<input type="text" id="comment_add_name" name="name" autocomplete="off" class="form-control" placeholder="<?php echo lang("site.name");?>">
                      </div>
                      <div class="form-group"  <?php if($this->session->userdata("id")!="") {echo "style='display:none;'";}?>>
                        <label><?php echo lang("site.pw");?></label>
						<input type="text" id="comment_add_pw" name="pw" autocomplete="off" class="form-control">
                      </div>
                      <!--<div class="form-group">
                        <label>Email <span class="color-red">*</span></label>
                        <input class="form-control" type="text">
                      </div>-->

                      <div class="form-group">
                        <label><?php echo lang("site.content");?></label>
                        <textarea class="form-control" id="comment_add_content" name="content" rows="8" autocomplete="off"></textarea>
                      </div>
                      <p><button class="btn btn-primary" type="submit">댓글 등록</button></p>
					<?php echo form_close();?>
                  </div>
				  <?php }?>
                </div>
                <!-- END LEFT SIDEBAR -->

                <!-- BEGIN RIGHT SIDEBAR -->            
                <div class="col-lg-3 news-sidebar">
                  <!-- CATEGORIES START -->
                  <h2 class="no-top-space"><?php echo lang("site.category");?></h2>
                  <ul class="nav sidebar-categories margin-bottom-40">
						<li> <a href="/news/index"><?php echo lang("site.all");?></a></li>
						<?php foreach($newscategory as $val){?>
						 <li <?php if($query->category==$val->id){?>class="active"<?php }?>><a href="/news/index/<?php echo $val->id;?>/" style="cursor:pointer;"><?php echo $val->name;?><?php if($val->opened=="N") {echo " <i class='fa fa-user'></i>";}?></a></li>
						<?php }?>
                  </ul>
                  <!-- CATEGORIES END -->
                  

                </div>
                <!-- END RIGHT SIDEBAR -->            
              </div>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
      </div>
    </div>


<!-- 댓글에 답글 달기 -->
<?php echo form_open("newscomment/add_reply_action","id='reply_form' class='form-horizontal'");?>
<div id="replyModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">답글 작성</h3>
	  </div>
	  <div class="modal-body">
		<div id="comment_reply_alert"></div>
		<input type="hidden" id="parent_id" name="id" >
		<input type="hidden" name="news_id" value="<?php echo $query->id;?>">
		<input type="hidden" name="member_id" value="<?php echo $this->session->userdata("id");?>">
		<div class="control-group">
			<label class="control-label"><?php echo lang("site.name");?></label>
			<div class="controls">
				<input type="text" name="name" id="reply_name" autocomplete="off" placeholder="<?php echo lang("site.name");?>">
			</div>
		</div>
		<div class="control-group" <?php if($this->session->userdata("id")!="") {echo "style='display:none;'";}?>>
			<label class="control-label"><?php echo lang("site.pw");?></label>
			<div class="controls">
				<input type="password" name="pw" id="reply_pw" autocomplete="off">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="enContent"><?php echo lang("site.content");?></label>
			<div class="controls">
				<textarea id="reply_content" name="content" rows="5" style="width:90%;" autocomplete="off"></textarea>
			</div>
		</div>
	  </div>
	  <div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo lang("site.close");?></button>
		<button class="btn btn-primary"><?php echo lang("site.submit");?></button>
	  </div>
    </div>
  </div>
</div>
<?php echo form_close();?>

<!-- 수정하기 -->
<?php echo form_open("newscomment/edit_action","id='edit_form' class='form-horizontal'");?>
<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">답글 작성</h3>
	  </div>
	  <div class="modal-body">
		<div id="comment_edit_alert"></div>
		<input type="hidden" id="edit_id" name="id" >
		<input type="hidden" id="edit_step_id" name="step_id" >
		<input type="hidden" name="news_id" value="<?php echo $query->id;?>">
		<input type="hidden" name="member_id" value="<?php echo $this->session->userdata("id");?>">
		<div class="control-group">
			<label class="control-label"><?php echo lang("site.name");?></label>
			<div class="controls">
				<input type="text" id="comment_edit_name" name="name" autocomplete="off" placeholder="<?php echo lang("site.name");?>">
			</div>
		</div>
		<div class="control-group" <?php if($this->session->userdata("id")!="") {echo "style='display:none;'";}?>>
			<label class="control-label"><?php echo lang("site.pw");?></label>
			<div class="controls">
				<input type="password" id="comment_edit_pw" name="pw" autocomplete="off">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="enContent"><?php echo lang("site.content");?></label>
			<div class="controls">
				<textarea id="comment_edit_content" name="content" rows="5" style="width:90%;" autocomplete="off"></textarea>
			</div>
		</div>
	  </div>
	  <div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo lang("site.close");?></button>
		<button class="btn btn-primary"><?php echo lang("site.modify");?></button>
	  </div>
    </div>
  </div>
</div>
<?php echo form_close();?>

<!-- 삭제하기 -->
<?php echo form_open("newscomment/delete_action","id='delete_form' class='form-horizontal'");?>
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  <div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	  <h3 id="myModalLabel">댓글 <?php echo lang("site.delete");?></h3>
	  </div>
		  <div class="modal-body">
			<div id="comment_delete_alert"></div>
			<input type="hidden" id="delete_id" name="id" >
			<input type="hidden" id="delete_step_id" name="step_id" >
			<input type="hidden" name="news_id" value="<?php echo $query->id;?>">
			<input type="hidden" name="member_id" value="<?php echo $this->session->userdata("id");?>">
			<div class="control-group">
				<label class="control-label"><?php echo lang("site.pw");?></label>
				<div class="controls">
					<input type="password" name="pw" id="delete_pw" autocomplete="off">
				</div>
			</div>
		  </div>
		  <div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo lang("site.close");?></button>
			<button class="btn btn-primary"><?php echo lang("site.delete");?></button>
		  </div>	  
	  </div>
	</div>
</div>

<?php echo form_close();?>
