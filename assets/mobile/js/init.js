/**
 * http://getbootstrap.com/components/#alerts
 * 
 * @type : success, info, warning, dander, error(bootstrap 3.x 에서는 사라짐)
 */
function msg(obj,type,msg){
	obj.hide();
	obj.html("<div class=\"alert alert-"+type+"\" style='font-size:12px;margin-bottom:0px;'><button type=\"button\" class=\"close\" data-dismiss=\"alert\" onclick=\"$(this).parent().hide();\">&times;</button>"+msg+"</div>");
	obj.fadeIn("slow")
}