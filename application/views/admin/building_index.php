<?php echo form_open_multipart("adminbuilding/upload_action",Array("id"=>"building_form"))?>
<input type="file" name="excel_file"/>
<button type="submit" class="btn btn-primary">업로드</button>
<?php echo form_close();?>