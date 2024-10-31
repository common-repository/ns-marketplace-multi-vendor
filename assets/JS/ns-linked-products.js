jQuery( document ).ready(function() {
   //saving into hidden input the choosen linked product
   /*LINKED PRODUCT*/
   jQuery(document).on('click','.ns-check-linked',function(event) {
      if(jQuery(this).is(':checked')){
         jQuery('#ns-linked-list').val(jQuery('#ns-linked-list').val()+jQuery(this).attr('id')+',');
      }
      else{
         var new_string = "";
          new_string = jQuery('#ns-linked-list').val();
         new_string = new_string.replace(jQuery(this).attr('id')+',', "");
         jQuery('#ns-linked-list').val(new_string);
      }
   });
});
