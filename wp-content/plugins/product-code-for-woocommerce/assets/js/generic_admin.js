jQuery(document).ready( function( $ ) {
    $( "#product_code_notice" ).on( "click", ".notice-dismiss", function() {
        product_code_dismiss_notice(0)
    })

    $( "#product_code_notice" ).on( "click", "a", function() {
        product_code_dismiss_notice(1)
        $( "#product_code_notice .notice-dismiss" ).trigger( "click" )
    })
}) 

function product_code_dismiss_notice( is_final ) {

    jQuery.ajax({
        url : PRODUCT_CODE_ADMIN.ajax,
        data : { action : "product_code_dismiss_notice", dismissed_final : is_final },
        type : "POST",
        dataType : "json",
        success : function( response ) {
            console.log(response)
        }
    })
}

// Code to add field title for 18 characters
jQuery( document ).ready( function ($) {
   var first_max_chars = 12;
   var second_max_chars = 14;

   jQuery('#product_code_text').keydown( function(e){
       if (jQuery(this).val().length >= first_max_chars) { 
          jQuery(this).val(jQuery(this).val().substr(0, first_max_chars));
       }
   });

   jQuery('#product_code_text').keyup( function(e){
       if (jQuery(this).val().length >= first_max_chars) { 
           jQuery(this).val(jQuery(this).val().substr(0, first_max_chars));
       }
   });
   
   jQuery('#product_code_text_second').keydown( function(e){
       if (jQuery(this).val().length >= second_max_chars) { 
          jQuery(this).val(jQuery(this).val().substr(0, second_max_chars));
       }
   });

   jQuery('#product_code_text_second').keyup( function(e){
       if (jQuery(this).val().length >= second_max_chars) { 
           jQuery(this).val(jQuery(this).val().substr(0, second_max_chars));
       }
   });

   jQuery('#product_code_quik_edit_text').keydown( function(e){
       if (jQuery(this).val().length >= first_max_chars) { 
          jQuery(this).val(jQuery(this).val().substr(0, first_max_chars));
       }
   });

   jQuery('#product_code_quik_edit_text').keyup( function(e){
       if (jQuery(this).val().length >= first_max_chars) { 
           jQuery(this).val(jQuery(this).val().substr(0, first_max_chars));
       }
   });
   
   jQuery('#product_code_quik_edit_text_second').keydown( function(e){
       if (jQuery(this).val().length >= second_max_chars) { 
          jQuery(this).val(jQuery(this).val().substr(0, second_max_chars));
       }
   });

   jQuery('#product_code_quik_edit_text_second').keyup( function(e){
       if (jQuery(this).val().length >= second_max_chars) { 
           jQuery(this).val(jQuery(this).val().substr(0, second_max_chars));
       }
   });

});