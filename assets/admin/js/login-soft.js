var Login = function () {

	var handleLogin = function() {
		$('.login-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            rules: {
	                email: {
	                    required: true,
						email: true
	                },
	                pw: {
	                    required: true
	                }
	            },

	            messages: {
	                email: {
	                    required: "이메일을 입력하여 주시기 바랍니다.",
						email: "올바른 이메일 주소를 입력하여 주시기 바랍니다."
	                },
	                pw: {
	                    required: "패스워드를 입력하여 주시기 바랍니다."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   
	                $('.alert-danger', $('.login-form')).show();
	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
	                //form.submit();
	            }
	        });

	        $('.login-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.login-form').validate().form()) {
	                    //$('.login-form').submit();
	                }
	                return false;
	            }
	        });
	}

	var handleForgetPassword = function () {
		$('.forget-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "",
	            rules: {
	                email: {
	                    required: true,
	                    email: true
	                }
	            },

	            messages: {
	                email: {
	                    required: "이메일을 입력하여 주시기 바랍니다.",
						email: "올바른 이메일 주소를 입력하여 주시기 바랍니다."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   

	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
	                //form.submit();
	            }
	        });

	        $('.forget-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.forget-form').validate().form()) {
	                    //$('.forget-form').submit();
	                }
	                return false;
	            }
	        });

	        jQuery('#forget-password').click(function () {
	            jQuery('.login-form').hide();
	            jQuery('.forget-form').show();
	        });

	        jQuery('#back-btn').click(function () {
	            jQuery('.login-form').show();
	            jQuery('.forget-form').hide();
	        });

	}

	var handleRegister = function () {

		function format(state) {
            if (!state.id) return state.text; // optgroup
            return "<img class='flag' src='../../assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
        }


		$("#select2_sample4").select2({
		  	placeholder: '<i class="fa fa-map-marker"></i>&nbsp;Select a Country',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function (m) {
                return m;
            }
        });


			$('#select2_sample4').change(function () {
                $('.register-form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });

         $('.register-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "",
	            rules: {
	                email: {
	                    required: true,
	                    email: true,
						remote : {
							type : "POST",
							async: false,
							url  : "/adminlogin/check_email"
						}
	                },
	                password: {
	                    required: true,
						minlength: 4,
						maxlength: 12
	                },
	                rpassword: {
						required: true,
	                    equalTo: "#register_password"
	                },							
	                name: {
	                    required: true,
						minlength: 2
	                },
	                phone: {
	                    required: true
	                },
	                accept: {
	                    required: true
	                }
	            },

	            messages: { // custom messages for radio buttons and checkboxes
	                email: {
	                    required: "이메일을 입력하여 주시기 바랍니다.",
						email: "올바른 이메일 주소를 입력하여 주시기 바랍니다.",
						remote: "이미 가입된 이메일 입니다."
	                },
	                password: {
	                    required: "패스워드를 입력하여 주시기 바랍니다.",
						minlength: "패스워드는 4자~12자 사이입니다",
						maxlength: "패스워드는 4자~12자 사이입니다"
	                },
	                rpassword: {
						required: "패스워드를 입력하여 주시기 바랍니다.",
						equalTo: "동일한 패스워드를 입력하여 주시기 바랍니다."
	                },
	                name: {
	                    required: "직원명을 입력하여 주시기 바랍니다.",
						minlength: "직원명은 4자 이상입니다"
	                },
	                phone: {
	                    required: "휴대전화를 입력하여 주시기 바랍니다."
	                },
					accept: {
	                    required: "개인정보 수집 및 이용에 동의해주시기 바랍니다."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   

	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                if (element.attr("name") == "accept") { // insert checkbox errors after the container
						error.insertAfter($('#register_accept_error'));
	                } else if (element.closest('.input-icon').size() === 1) {
	                    error.insertAfter(element.closest('.input-icon'));
	                } else {
	                	error.insertAfter(element);
	                }
	            },

	            submitHandler: function (form) {
					//form.submit();
	            }
	        });

			$('.register-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.register-form').validate().form()) {
	                    //$('.register-form').submit();
	                }
	                return false;
	            }
	        });

	        jQuery('#register-btn').click(function () {
	            jQuery('.login-form').hide();
	            jQuery('.register-form').show();
	        });

	        jQuery('#register-back-btn').click(function () {
	            jQuery('.login-form').show();
	            jQuery('.register-form').hide();
	        });
	}
    
    return {
        //main function to initiate the module
        init: function () {
        	
            handleLogin();
            handleForgetPassword();
            handleRegister();    
        }

    };

}();