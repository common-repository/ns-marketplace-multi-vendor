jQuery( document ).ready(function() {
   //Initial switch for variable product
   jQuery('#ns-product-type').change(function(){
      var value = jQuery(this).val();
      if(value == 'variable'){
         //External Product
         jQuery('.ns-external-to-remove').removeClass('ns-hidden');
         jQuery('.ns-external-product-url-container').addClass('ns-hidden');
         /**************/

         jQuery('#ns-variation').removeClass('ns-hidden');

         jQuery('#ns-regular-price').parent().addClass('ns-hidden');	//if is a variable product i need to hide simple product prices
         jQuery('#ns-sale-price').parent().addClass('ns-hidden');
      }
      if(value == 'simple'){
         //External Product
         jQuery('.ns-external-to-remove').removeClass('ns-hidden');
         jQuery('.ns-external-product-url-container').addClass('ns-hidden');
         /**************/

         jQuery('#ns-variation').addClass('ns-hidden');

         jQuery('#ns-regular-price').parent().removeClass('ns-hidden'); //if is a simple product i can show the prices
         jQuery('#ns-sale-price').parent().removeClass('ns-hidden');
      }
      if(value == 'external-product'){
         jQuery('.ns-external-to-remove').addClass('ns-hidden');
         jQuery('.ns-external-product-url-container').removeClass('ns-hidden');

         jQuery('#ns-variation').addClass('ns-hidden');

         jQuery('#ns-regular-price').parent().removeClass('ns-hidden'); //if is a simple product i can show the prices
         jQuery('#ns-sale-price').parent().removeClass('ns-hidden');
      }
   });

});
