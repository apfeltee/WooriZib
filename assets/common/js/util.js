/**
* cut string in utf-8
* @author gony (http://mygony.com)
* @param $str     source string
* @param $len     cut length
* @param $checkmb if this argument is true, the function treats multibyte character as two bytes. default value is false.
* @param $tail    abbreviation symbol
* @return string  processed string
*/
function strcut_utf8(str, len) {
    var s = 0;
         for (var i=0; i<str.length; i++) {
                 s += (str.charCodeAt(i) > 128) ? 2 : 1;
                 if (s > len) return str.substring(0,i) + "...";
         }        
    return str;
}

var fit_modal_body;

fit_modal_body = function(modal) {
  var body, bodypaddings, header, headerheight, height, modalheight;
  header = $(".modal-header", modal);
  footer = $(".modal-footer", modal);
  body = $(".modal-body", modal);
  modalheight = parseInt(modal.css("height"));
  headerheight = parseInt(header.css("height")) + parseInt(header.css("padding-top")) + parseInt(header.css("padding-bottom"));
  footerheight = parseInt(footer.css("height")) + parseInt(footer.css("padding-top")) + parseInt(footer.css("padding-bottom"));
  bodypaddings = parseInt(body.css("padding-top")) + parseInt(body.css("padding-bottom"));
  height = $(window).height() - headerheight - footerheight - bodypaddings - 150;
  return body.css({"max-height": "" + height + "px", 'height':'auto'});
};