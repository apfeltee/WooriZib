var UIGeneral = function () {

    var handlePulsate = function () {
        if (!jQuery().pulsate) {
            return;
        }

        if (jQuery().pulsate) {
            jQuery('#pulsate-regular').pulsate({
                color: "#bf1c56"
            });

            //jQuery('#pulsate-once').click(function () {
			if($('#pulsate-once-target').length > 0){
				setTimeout(function(){
					$('#pulsate-once-target').pulsate({
						color: "#D22129",
						repeat: false
					});
				}, 2000)
			}
            //});

            jQuery('#pulsate-crazy').click(function () {
                $('#pulsate-crazy-target').pulsate({
                    color: "#fdbe41",
                    reach: 50,
                    repeat: 10,
                    speed: 100,
                    glow: true
                });
            });
        }
    }

    return {
        //main function to initiate the module
        init: function () {
            handlePulsate();
        }
    };

}();