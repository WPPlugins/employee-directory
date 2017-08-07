jQuery(document).ready(function() {
$=jQuery;
var $captcha_container = $('.captcha-container');
if ($captcha_container.length > 0) {
        var $image = $('img', $captcha_container),
        $anchor = $('a', $captcha_container);
        $anchor.bind('click', function(e) {
                e.preventDefault();
                $image.attr('src', $image.attr('src').replace(/nocache=[0-9]+/, 'nocache=' + +new Date()));
        });
}
$.validator.setDefaults({
    ignore: [],
});
$.extend($.validator.messages,search_employees_vars.validate_msg);
function createRadioCheckBox(ele, i)
{
  if(ele.attr('type') == 'checkbox')
  {          
     var newID = "cbx-"+ ele.attr('id') + i;
     var iconNameOn = 'ui-icon-check';
  }
  else if(ele.attr('type') == 'radio')
  {
     var newID = "rd-"+ ele.attr('id') +i;
     var iconNameOn = '';
  }
  ele.attr({ "id": newID  })
     .prop({ "type": ele.attr('type') })
     .after($("<label />").attr({ for: newID  }))
     .button({ text: false, icons: {
        primary: ele[0].checked ? iconNameOn: ""
        }
     })
     .change(function(e) {
        if(ele.attr('type') == 'radio')
        {
           $('label.radio span').removeClass( iconNameOn + ' ui-icon');
        }
        var toConsole = $(this).button("option", {
          icons: {
            primary: $(this)[0].checked ? iconNameOn : ""
          }
        });      
     });
     return ele;
}
$('label.checkbox label').removeClass('ui-corner-all');
$('#search_employees').validate({
onfocusout: false,
onkeyup: false,
onclick: false,
errorClass: 'text-danger',
rules: {
  emd_employee_number:{
alphanumeric : true,
},
blt_title:{
},
emd_employee_email:{
email  : true,
},
},
success: function(label) {
label.remove();
},
errorPlacement: function(error, element) {
if (typeof(element.parent().attr("class")) != "undefined" && element.parent().attr("class").search(/date|time/) != -1) {
error.insertAfter(element.parent().parent());
}
else if(element.attr("class").search("radio") != -1){
error.insertAfter(element.parent().parent());
}
else if(element.attr("class").search("select2-offscreen") != -1){
error.insertAfter(element.parent().parent());
}
else if(element.attr("class").search("selectpicker") != -1 && element.parent().parent().attr("class").search("form-group") == -1){
error.insertAfter(element.parent().find('.bootstrap-select').parent());
} 
else if(element.parent().parent().attr("class").search("pure-g") != -1){
error.insertAfter(element);
}
else {
error.insertAfter(element.parent());
}
},
});
$(document).on('click','#singlebutton_search_employees',function(event){
     var form_id = $(this).closest('form').attr('id');
     $.each(search_employees_vars.search_employees.req, function (ind, val){
         if(!$('input[name='+val+'],#'+ val).closest('.row').is(":hidden")){
             $('input[name='+val+'],#'+ val).rules("add","required"); 
         }
     });
     var valid = $('#' + form_id).valid();
     if(!valid) {
        event.preventDefault();
        return false;
     }
});
});
