CKEDITOR.plugins.add( 'photo', {
    icons: 'photo',
    init: function( editor ) {
        editor.addCommand( 'insertPhoto', {
            exec: function( editor ) {
				if($("#"+editor.name).attr("target-dialog")){
					$("#"+$("#"+editor.name).attr("target-dialog")).dialog("open");
				}
				else{
					$("#upload_dialog").dialog("open");
				}				
            }
        });
        editor.ui.addButton( 'Photo', {
            label: '이미지업로드',
            command: 'insertPhoto',
            toolbar: 'insert'
        });
    }
});