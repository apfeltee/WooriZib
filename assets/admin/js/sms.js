
var send_count = 0;
var minus_count = 0;

$(document).ready(function(){

	$.support.cors = true; /* ie9 등에서 한글도메인일 경우에 넣어줘야만 ajaxform이 동작한다. */		
	
	$('#check_all').change(function(){
		var check_state = $(this).prop('checked');
		$("input[name='check_id[]']").each(function(i){
			$(this).prop('checked',check_state);
		});
	});

    if (jQuery().datepicker) {
        $('.date-picker').datepicker({
            format: "yyyy-mm-dd",
            orientation: "left",
            language: "kr",
            autoclose: true
        });
    }

    if (jQuery().timepicker) {

        $('.timepicker-24').timepicker({
            autoclose: true,
            minuteStep: 1,
            showSeconds: false,
            showMeridian: false
        });
    }

	$("#sms_form").validate({
		rules: {
			sms_msg: { required: true },
			mms_file: { required: true },
			r_date: { required: true },
			r_time: { required: true }
		},
		messages: {
			sms_msg: { required: "전송 내용을 입력해 주세요" },
			mms_file: { required: "이미지를 업로드 해주세요" },
			r_date: { required: "예약 날짜를 선택하세요" },
			r_titme: { required: "예약 시간을 선택하세요" }
		}
	});

	$("input[name='sms_type']").change(function(){
		msg_reset();
		if($(this).val()=="A"){
			$("#minus_count").html(send_count);
			minus_count = send_count;
			$(".maxcount").html("80");	
			$(".lms").fadeOut();
			$(".mms").fadeOut();
		}
		if($(this).val()=="C"){
			$("#minus_count").html(send_count * 3);
			minus_count = send_count * 3;
			$(".maxcount").html("1000");
			$(".lms").fadeIn();
			$(".mms").fadeOut();
		}
		if($(this).val()=="D"){
			$("#minus_count").html(send_count * 10);
			minus_count = send_count * 10;
			$(".maxcount").html("2000");
			$(".lms").fadeIn();
			$(".mms").fadeIn();
		}
	});

	$("input[name='reserve']").change(function(){
		if($(this).val()=='yes'){
			$("#reserve_date").fadeIn();
		}
		else{
			$("#reserve_date").fadeOut();
		}
	});

    $('.remaining').each(function () {
		var $count = $('.count', this);
		var $input = $("#sms_form").find("textarea");

		var update = function () {
			var before = $count.text() * 1;
			var str_len = $input.val().length;
			var cbyte = 0;
			var li_len = 0;
			for (i = 0; i < str_len; i++) {
				var ls_one_char = $input.val().charAt(i);
				if (escape(ls_one_char).length > 4) {
					cbyte += 2;
				} else {
					cbyte++;
				}
				if (cbyte <= maximumByte) {
					li_len = i + 1;
				}
			}

			var sms_type_text = "";
			if($("input[name='sms_type']:checked").val()=="A"){
				maximumByte = 80;
				sms_type_text = "단문(SMS)은 ";
			}
			else if($("input[name='sms_type']:checked").val()=="C"){
				maximumByte = 1000;
				sms_type_text = "장문(LMS)은 ";
			}
			else if($("input[name='sms_type']:checked").val()=="D"){
				maximumByte = 2000;
				sms_type_text = "포토(MMS)는 ";
			}

			if (parseInt(cbyte) > parseInt(maximumByte)) {
				alert(sms_type_text + maximumByte+'byte 까지만 전송 가능합니다.');
				var str = $input.val();
				var str2 = $input.val().substr(0, li_len);
				$input.val(str2);
				var cbyte = 0;
				for (i = 0; i < $input.val().length; i++) {
					var ls_one_char = $input.val().charAt(i);
					if (escape(ls_one_char).length > 4) {
						cbyte += 2;
					} else {
						cbyte++;
					}
				}
			}
			$count.text(cbyte);
		};
		$input.bind('input keyup keydown paste change', function () {
			setTimeout(update, 0)
		});
		update();
    });

	$('#sms_form').ajaxForm({
		beforeSubmit:function(data,form,option){
			var html = "";
			$("input[name='check_id[]']:checked").each(function () {
				html += '<input type="hidden" name="check_id[]" value="'+$(this).val()+'"/>';
			});
			$("#check_id_clone").html(html);
			return true;
		},
		success:function(data){
			msg_reset();
			if(data=="발송성공"){
				msg($("#sms_result"),"success","문자가 발송 되었습니다.");
			}
			else{
				msg($("#sms_result"),"danger",data);
			}
		}
	});
});

function open_sms(all){

	msg_reset();

	if(all){
		$.getJSON("/adminsms/send_all_count/"+$("#sms_form").find("input[name='send_page']").val()+"/"+all+"/"+Math.round(new Date().getTime()),function(data){
			send_count = data;
			if(send_count==0){
				alert("문자 전송할 회원이 없습니다");
				return false;
			}
			$("#send_count,#minus_count").html(send_count);
		});
		$("#sms_form").find("input[name='send_all_type']").val(all);
	}
	else{
		$("#sms_form").find("input[name='send_all_type']").val("");
		send_count = $("input[name='check_id[]']:checked").length;
		if(send_count==0){
			alert("문자 전송할 회원을 선택해 주세요");
			return false;
		}
		$("#send_count,#minus_count").html(send_count);
	}
	
	if(minus_count) $("#minus_count").html(minus_count);
	
	$('#sms_dialog').modal("show");
}

function msg_reset(){
	$("#sms_result").hide();
	$(".count").html("0");
	$("#sms_form").find("input[name='sms_subject']").val("");
	$("#sms_form").find("textarea").val("");
	$("#sms_form").find("input[name='sms_msg']").val("");
	$("#sms_form").find("input[name='mms_file']").val("");
}

function self_send(){
	$("#sms_form").attr("action", "/adminsms/self_send");
	$("#sms_form").submit();
}