<script src="/script/product_common"></script>
<script src="/script/product_edit/admin/<?php echo $query->id?>"></script>
<script src="/assets/plugin/jquery.rotate.js"></script>
<script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=<?php echo $config->DAUM_MAP_KEY;?>&libraries=services"></script>
<script>

flag = 0;

$(document).ready(function(){

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
			$("#member_id_temp").val(b.item.member_id);
			$("#member_info_temp").val(b.item.member_name+" ("+b.item.member_email+", "+b.item.member_phone+")");
			a.stopPropagation();
			return false;
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
							member_email: item.email,
							member_phone: item.phone
						}; 
					}));
				}
			});						
		},
	}).data("ui-autocomplete")._renderItem = autoCompleteRenderAdmin;

	$("#go_member_name").click(function(){
		if($("#member_id_temp").val() && $("#member_info_temp").val()){
			$("#member_id").val($("#member_id_temp").val());
			$("#member_info").val($("#member_info_temp").val());		
		}
	});

	$("#add_owner").click(function(e){
		e.preventDefault();
		var random_id = Math.round(new Date().getTime());
		var add_html = '';
			add_html += '<div>';
			add_html += '	<select id="owner_type'+random_id+'" name="owner_type[]" class="form-control input-inline input-small">';
			add_html += '		<option value="">종류 선택</option>';
			add_html += '		<option value="seller">매도인</option>';
			add_html += '		<option value="buyer">매수인</option>';
			add_html += '		<option value="lessee">임차인</option>';
			add_html += '		<option value="lessor">임대인</option>';
			add_html += '		<option value="broker">중개인</option>';
			add_html += '		<option value="agent">대리인</option>';
			add_html += '		<option value="etc">기타</option>';
			add_html += '	</select>';
			add_html += '	<input type="hidden" id="contacts_id'+random_id+'"  name="contacts_id[]"/>';
			add_html += '	<input type="text" id="owner_name'+random_id+'"  class="form-control ui-autocomplete-input input-inline input-xlarge" value="" placeholder="회원이름 검색" autocomplete="off"/>';
			add_html += '	<button type="button" class="btn red btn-xs" onclick="owner_delete(this)"><i class="fa fa-minus"></i></button></br></br>';
			add_html += '</div>';

		$("#add_owner_section").append(add_html);

		apply_autoComplete($("#owner_type"+random_id), $("#contacts_id"+random_id), $("#owner_name"+random_id));
	});

	<?php if($this->session->userdata("auth_contact")=="Y"){?>
		apply_autoComplete($("#owner_type"), $("#contacts_id"), $("#owner_name"));
	<?php }?>
});
</script>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">
				<?php echo lang("product");?> 관리<small>수정</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i> 
					<a href="/adminhome/index"><?php echo lang("menu.home");?></a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					<a href="/adminproduct/index"><?php echo lang("product");?> 관리</a>
					<i class="fa fa-angle-right"></i> 
				</li>
				<li>
					수정
				</li>
			</ul>
			<div class="page-toolbar">
				<button class="btn blue" onclick="history.go(-1);"><?php echo lang("site.back");?></button>
			</div>
		</div>
	</div>
</div><!-- /.row -->

<?php echo form_open_multipart("adminproduct/edit_action","id='product_form' class='product form-horizontal'");?>
<input type="hidden" name="id" value="<?php echo $query->id?>"/>
<input type="hidden" id="refresh" name="refresh" value="0"/>
<?php
	echo $product_form;
?>

<div class="text-center margin-top-20 margin-bottom-20">
	<button type="submit" class="btn blue btn-lg" style="margin:20px;" onclick="$('#refresh').val(1);"><?php echo lang("site.refresh");?></button>
	<button type="submit" class="btn blue btn-lg" style="margin:20px;">수정</button>
</div>

<?php echo form_close();?>


<script>
	CKEDITOR.replace( 'content', {customConfig: '/ckeditor/dungzi_config.js'});
</script>

<div id="upload_dialog" title="<?php echo lang("site.imageupload");?>" style="display:none;padding:10px 0px 0px 0px;">
	<div style="padding:10px;">
		<?php echo form_open_multipart("adminproduct/upload_action","id='upload_form' autocomplete='off'");?>
		<input type="file" name="uploadfile" id="uploadfile" style="width:300px;border:0px;"/>
		<?php echo form_close();?>
	</div>
</div>
