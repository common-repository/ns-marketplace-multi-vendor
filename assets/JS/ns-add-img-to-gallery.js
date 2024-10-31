jQuery(document).ready(function($) {
    /*PREVENT DEFAULT SUBMISSION POST TO CALL AJAX SERVICE*/
    jQuery("#ns-add-img-from-gallery-form").submit(function(ev){
        jQuery('#ns-add-prod-frontend-save-img-gallery').hide();
        jQuery('#ns-loader-open-gallery').show();
        ev.preventDefault();
        var form = jQuery("#ns-add-img-from-gallery-form")[0];
        var formData = new FormData(form);
        formData.append('action', 'ns_add_to_gallery_image');
        jQuery.ajax({
            url : ns_add_to_gallery_image.ajax_url,
            type : 'post',
            data : formData,
            processData: false,
            contentType: false,
            success : function( response ) {
            //alert(response);
            jQuery('#ns-loader-open-gallery').hide();
            // jQuery('#ns-add-prod-frontend-save-img-gallery').show();
            jQuery('#ns-add-prod-frontend-add-img-gallery').after('<p>Select product images gallery and close (autosave)</p>');
            jQuery('.ns-image-container').prepend(response);
    
                /*THIS SCRIPT IS THE SAME AS THE ONE IN ns-option-js-page.js WE NEED IT TO REACTIVATE THE CLICKING EVENT*/
                var img_array = [];		//this array will contains all the SELECTED images
                jQuery('.ns-image-container img').on('click', function(){
                    //Image clicked for the first time
                    if(img_array.indexOf(jQuery(this).attr("id")) < 0){
                        img_array.push(jQuery(this).attr("id"));
                        //setting the value of the input with the urls of images separated by comma
                        jQuery('#ns-image-from-list').val(img_array.toString());
                        //jQuery('#ns-image-from-list').val( jQuery(this).attr("src") );
                        jQuery(this).css('border','5px solid #bdcfed');
                    }
                    else{
                        //Image already being clicked. Removing border and delete element from img_array
                        jQuery(this).css('border', '1px solid gray');
                        var elementToRemove = jQuery(this).attr("id");
                        img_array = jQuery.grep(img_array, function(value) {
                        return value != elementToRemove;
                        });
                        jQuery('#ns-image-from-list').val(img_array.toString());
                    }

                });
    
            },
            error: function(errorThrown){
                alert('Error');
            }
        });
        
        
    });
});
    