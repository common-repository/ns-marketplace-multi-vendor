jQuery(document).ready(function($) {
   //ajax used to add cus category
   jQuery('#ns-cus-cat-btn').click(function($) {
      var custom_cat_val = jQuery('#ns-cus-cat-product').val();
      var custom_cat_parent = jQuery('#ns-cus-cat-parent-select').val();
         if(custom_cat_val != ''){
            var escaped_val = escape(custom_cat_val);
            // if(custom_cat_val != escaped_val){									//prevent user to insert special characters.
            //    window.alert('Special characters detected. Change your input');
            //    return;
            // }

         }
         else{
            //window.alert('Invalid input');
         }
      jQuery.ajax({
         
         url : ns_add_cat_product.ajax_url,
         type : 'post',
         data : {
            action : 'ns_add_cat_product_function',
            name : custom_cat_val,
            parent : custom_cat_parent
         },
         success : function( response ) {
            if(custom_cat_parent != '')
               custom_cat_parent = '(Parent category: '+custom_cat_parent+')';
            jQuery("#ns-cat-din-table").append('<tr><td><input type="checkbox" name="'+response+'" value="'+response+'"/> '+response+custom_cat_parent+' </td></tr>');
            jQuery('#ns-myModal-cat').css("display","none");
         },
         error: function(errorThrown){
            alert(errorThrown.responseText);
         }


      });
   });

});
