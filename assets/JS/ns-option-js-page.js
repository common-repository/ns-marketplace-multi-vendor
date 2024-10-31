jQuery( document ).ready(function() {
	//jQuery('#ns-wp-editor-div').append(jQuery('#wp-ns-editor-add-prod-short-desc-wrap'));

	/*PRODUCT DATA*/
	jQuery("li").on('click', function () {
		jQuery(".ns-prod-data-tab").addClass("ns-hidden");
		jQuery("li").removeClass("ns-active");
		jQuery("." + jQuery(this).attr("id")).removeClass("ns-hidden");
		jQuery(this).addClass("ns-active");
	});

	jQuery("#ns-manage-stock").on('click', function () {
		if(jQuery("#ns-manage-stock").val() == "no"){
			jQuery('#ns-manage-stock-div').css('display','block');
			jQuery("#ns-manage-stock").val("yes");
		}
		else{
			jQuery('#ns-manage-stock-div').css('display','none');
			jQuery("#ns-manage-stock").val("no");
		}
	});

	//attributes
	var i = 0;
	jQuery('#ns-add-attribute-btn').on('click', function(event) {
		if(jQuery('#ns-attribute-taxonomy').val() == 'ns-color-att'){
			jQuery('#ns-inner-attributes').after('<div class="ns-color-attr-class"><h3><label>Color</label></h3><div><label>Add new color:</label><input id="ns-color-attr" name="ns-color-attr" class="ns-input-width" type="text"></div><div><label id="ns-existing-colors">Select a color: </label></div><div><label>Visible on product page </label><input class="checkbox" name="ns-attr-visibility-status" id="ns-attr-visibility-status" checked="checked" type="checkbox"></div><button id="ns-attribute-btn-remove-col" type="button" class="button" style="float:left">Remove</button></div>');
			jQuery('#ns-color-id').prop('disabled', true);
			jQuery('#ns-attribute-taxonomy').val(jQuery('#ns-attribute-taxonomy option:first').val());
			//get the color attributes already saved to create the checkboxes and permits the user to choosing them
			var col_attr = jQuery('#ns-color-att-list').val();
			col_attr = col_attr.split(',');

			//create checkboxes for each color already inserted
			jQuery('#ns-existing-colors').after('<table id="color-table">');
			jQuery.each(col_attr, function(index, value){
				if(value != "")
					jQuery('#color-table').append('<tr><th>'+value+'</th><th><input class="checkbox checkbox-attr-selectable-color ns-margin-right" name="'+value+'" type="checkbox"><label>Is Variation</label><input class="checkbox check-is-variation-color-name" name="'+value+'" id="ns-attr-is-variable-color'+value+'" type="checkbox" style="margin-left: 15px;"></th></tr>');

			});
			jQuery('#ns-existing-colors').append('</table>');
		}
		else{
			var atname = '';
			if(jQuery('#ns-attribute-taxonomy').val() != 'ns-cus-prod-att'){
				atname = jQuery('#ns-attribute-taxonomy option:selected').text();
			}
			jQuery('#ns-inner-attributes').after('<div><h3><label>Custom product attribute</label></h3><div class="ns-input-name-and-variation"><label>Name:</label><br><input class="ns-input-width attr-names-class" value="'+atname+'" name="ns-attr-names'+i+'" id="ns-attr-names'+i+'" type="text"/><br><br><label>Is Variation</label><input class="checkbox check-is-variation-custom-name" name="'+i+'" id="ns-attr-is-variable-custom'+i+'" type="checkbox" style="margin-left: 15px;"></div><div><label>Value(s)</label><textarea name="ns-attribute-values'+i+'"placeholder="Enter some text, or some attributes by &quot;|&quot; separating values."></textarea></div><div><label>Visible on product page </label><input class="checkbox" name="ns-attr-visibility-status'+i+'" id="ns-attr-visibility-status'+i+'" checked="checked" type="checkbox" style="margin-left: 15px;"/></div><button id="ns-attribute-btn-remove" type="button" class="button" style="float:left">Remove</button></div>');
			i++;
		}
		jQuery('#ns-attribute-list').val(i);

	});

	//removing attribute
	jQuery(document).on('click', '#ns-attribute-btn-remove, #ns-attribute-btn-remove-col', function(event){
		if(jQuery(this).parent().hasClass('ns-color-attr-class')){
			jQuery('#ns-color-id').prop('disabled', false);
		}

		if(jQuery(this).attr('id') == 'ns-attribute-btn-remove'){	// check if theres a need to decrement the counter -- only in case im removing a custom attributes --
			i--;
			//removing from attribute custom variations hidden input the attribute
			var new_string = "";
		    new_string = jQuery('#ns-attr-custom-names').val();
			new_string = new_string.replace(jQuery('.ns-input-name-and-variation :nth-child(2)').val()+',', "");
			jQuery('#ns-attr-custom-names').val(new_string);
			console.log(jQuery('.ns-input-name-and-variation :nth-child(2)').val());
		}
		jQuery(this).parent().remove();
		jQuery('#ns-attribute-list').val(i);
	});

	//saving into hidden input selectable color
	jQuery(document).on('click', '.checkbox-attr-selectable-color', function(event){
		if(jQuery(this).is(':checked')){
			jQuery('#ns-attr-from-list').val(jQuery('#ns-attr-from-list').val()+jQuery(this).attr('name')+',');
		}
		else{
			var new_string = "";
		    new_string = jQuery('#ns-attr-from-list').val();
			new_string = new_string.replace(jQuery(this).attr('name')+',', "");
			jQuery('#ns-attr-from-list').val(new_string);
		}

	});


	//saving into hidden input selected color for variation
	jQuery(document).on('click', '.check-is-variation-color-name', function(event){
		if(jQuery(this).is(':checked')){
			jQuery('#ns-attr-from-list-variation').val(jQuery('#ns-attr-from-list-variation').val()+jQuery(this).attr('name')+',');
		}
		else{
			var new_string = "";
		    new_string = jQuery('#ns-attr-from-list-variation').val();
			new_string = new_string.replace(jQuery(this).attr('name')+',', "");
			jQuery('#ns-attr-from-list-variation').val(new_string);
		}

	});

	var x = 0;
	//saving into hidden input selected custom attribute for variation and adding the field to variations custom attr list
	jQuery(document).on('click', '.check-is-variation-custom-name', function(event){
		if(jQuery(this).is(':checked')){
			jQuery('#ns-attr-from-list-variation-custom').val(++x);

			//save the name of the custom attribute to hidden input to use it in variations
			jQuery('#ns-attr-custom-names').val(jQuery('#ns-attr-custom-names').val()+jQuery('.ns-input-name-and-variation :nth-child(3)').val()+',');
		}
		else{
			jQuery('#ns-attr-from-list-variation-custom').val(--x);
			var new_string = "";
		    new_string = jQuery('#ns-attr-custom-names').val();
			new_string = new_string.replace(jQuery('.ns-input-name-and-variation :nth-child(2)').val()+',', "");
			jQuery('#ns-attr-custom-names').val(new_string);
		}

	});


	/*PRODUCT IMAGE*/
	/*This is used to create a temporary url (objectURL) to update the thumbnail image after user insert one*/
	jQuery('#ns-thumbnail').change( function(event) {
		jQuery("#ns-img-thumbnail").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
	});

	/*GALLERY AND MODAL*/
	/* When the user clicks on the button, open the gallery modal*/
	jQuery("#ns-myBtn").on('click', function() {
		jQuery('#ns-myModal').css("display","block");
	});

	/* When the user clicks on (x), close the gallery modal*/
	jQuery(".ns-close").on('click', function() {
		jQuery('#ns-myModal').css("display","none");
	});

	/*Categories modal*/
	jQuery("#ns-myBtn-cat").on('click', function() {
		jQuery('#ns-myModal-cat').css("display","block");
	});

	jQuery(".ns-close").on('click', function() {
		jQuery('#ns-myModal-cat').css("display","none");
	});



	/*Used to get the selected image from gallery list*/
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

	/*This one is used to upload into the gallery the image from local path
	jQuery('#ns-image-from-file').change( function(event) {
		jQuery('#ns-image-from-file').attr('src',URL.createObjectURL(event.target.files[0]));
	});*/


	/*EDIT PAGE*/
	jQuery(".ns-button-table-section").hover(function() {
		jQuery(".ns-to-hide").css('display', 'block');
	}, function() {
		jQuery(".ns-to-hide").css('display', 'none');
	});


	//used to update gallery with user selected image from input type file in gallery
	jQuery("#ns-add-prod-frontend-add-img-gallery").change(function() {
        jQuery('#ns-add-prod-frontend-save-img-gallery').show();

    });


	//this function provides the post id to php after the user clicks on edit button
	jQuery(document).on('click', '.ns-button-table-edit', function(event){
			var id = jQuery(this).attr('data-id').split('#');
			id = id[1];
			jQuery('#ns-page-params').val(id);
			jQuery('#ns-is-edit-input').val('yes');
	});


	//Image - CASE EDIT: need to change image on click 'choose' (sfoglia)
	jQuery('#ns-thumbnail').change( function(event) {
		jQuery("#ns-image-container1 .attachment-thumbnail").attr('srcset', '');
		jQuery("#ns-image-container1 .attachment-thumbnail").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
	});
});
