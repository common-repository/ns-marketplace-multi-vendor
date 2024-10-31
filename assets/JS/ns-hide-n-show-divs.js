jQuery( document ).ready(function() {
   /*HIDE SHOW DIVS*/
   //product data
   jQuery('#ns-post-prod-data-hide-show').on('click', function(event) {
          if(jQuery( '#ns-product-data-inner-container' ).is( ':hidden' )){
            jQuery('#ns-post-prod-data-hide-show').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
          }
          else
            jQuery('#ns-post-prod-data-hide-show').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
          jQuery('#ns-product-data-inner-container').slideToggle('show');
   });

   //short description
   jQuery('#ns-short-desc-hide-show').on('click', function(event) {
          if(jQuery( '#ns-wp-editor-div' ).is( ':hidden' )){
            jQuery('#ns-short-desc-hide-show').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
          }
          else
            jQuery('#ns-short-desc-hide-show').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
          jQuery('#ns-wp-editor-div').slideToggle('show');
   });

   //post content
   jQuery('#ns-post-content-hide-show').on('click', function(event) {
             if(jQuery( '#ns-wp-post-content-div' ).is( ':hidden' )){
            jQuery('#ns-post-content-hide-show').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
          }
          else
            jQuery('#ns-post-content-hide-show').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
         jQuery('#ns-wp-post-content-div').slideToggle('show');
   });

   //tags
   jQuery('#ns-prod-tags-hide-show').on('click', function(event) {
          if(jQuery( '#ns-prod-tags-div' ).is( ':visible' )){
             jQuery('#ns-product-tags').css('height', '100%');
             jQuery('#ns-prod-tags-hide-show').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
          }
          if(jQuery( '#ns-prod-tags-div' ).is( ':hidden' )){
            jQuery('#ns-product-tags').css('height', 'auto');
            jQuery('#ns-prod-tags-hide-show').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
          }
          jQuery('#ns-prod-tags-div').slideToggle('show');
   });

   //add image
   jQuery('#ns-prod-image-hide-show').on('click', function(event) {
          if(jQuery( '#ns-image-container-0' ).is( ':visible' )){
             jQuery('#ns-image-container').css('height', '100%');
             jQuery('#ns-prod-image-hide-show').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
          }
          if(jQuery( '#ns-image-container-0' ).is( ':hidden' )){
            jQuery('#ns-image-container').css('height', 'auto');
            jQuery('#ns-prod-image-hide-show').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
          }
          jQuery('#ns-image-container-0').slideToggle('show');
   });

   //categories
   jQuery('#ns-prod-categories-hide-show').on('click', function(event) {
          if(jQuery( '#ns-prod-cat-inner' ).is( ':visible' )){
             jQuery('#ns-product-categories').css('height', '100%');
             jQuery('#ns-prod-categories-hide-show').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');

          }
          if(jQuery( '#ns-prod-cat-inner' ).is( ':hidden' )){
            jQuery('#ns-product-categories').css('height', 'auto');
            jQuery('#ns-prod-categories-hide-show').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
          }
          jQuery('#ns-prod-cat-inner').slideToggle('show');
   });

   //gallery
   jQuery('#ns-prod-gallery-hide-show').on('click', function(event) {
          if(jQuery( '#ns-prod-gallery-inner' ).is( ':visible' )){
             jQuery('#ns-product-gallery').css('height', '100%');
            jQuery('#ns-prod-gallery-hide-show').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');

          }
          if(jQuery( '#ns-prod-gallery-inner' ).is( ':hidden' )){
            jQuery('#ns-product-gallery').css('height', 'auto');
            jQuery('#ns-prod-gallery-hide-show').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
          }
          jQuery('#ns-prod-gallery-inner').slideToggle('show');
   });


});
