jQuery( document ).ready(function() {
   /*VARIABLE PRODUCT*/

	//hide the variable notification message if an attribute with variation exists
	//a deeper control is used to disable users to click only 'is variation' checkbox, if without corresponding attribute
	jQuery('#ns-variation').on('click', function(event) {
		var hide_message = false;
		jQuery('#ns-message').removeClass('ns-hidden');
		jQuery('#ns-var-button').addClass('ns-hidden');

		var aux = '';
		if(jQuery('#ns-attr-from-list-variation').val() != ''){
			 aux = jQuery('#ns-attr-from-list-variation').val().split(',');
		}

		var list_variation_num = aux.length;
		if(list_variation_num > 0){
			hide_message = true;
		}

		var list_variation_custom_num = jQuery('#ns-attr-from-list-variation-custom').val();
		if(list_variation_custom_num > 0){
			hide_message = true;
		}

		if(hide_message){
			jQuery('#ns-message').addClass('ns-hidden');
			jQuery('#ns-var-button').removeClass('ns-hidden');
		}
	});

   //add variations (n)
   var j = 0;
   jQuery('#ns-var-button').on('click', function(event) {
      j++;

      var html_to_append = '<div>'+
               '<div>'+
               '<label>Color Attributes</label><br>'+
                  '<select  name="ns-variation-attributes'+j+'" class="ns-input-width ns-variation-attributes">'+
                     //here need to dinamycally add all the colors to this option set

                  '</select>' +
               '</div>'+
               '<div>'+
               '<label>Custom Attributes</label><br>'+
                  '<select  name="ns-variation-custom-attributes'+j+'" class="ns-input-width ns-variation-custom-attributes">'+
                     //here need to dinamycally add all the custom attributes to this option set

                  '</select>' +
               '</div>'+
               '<div>'+
                  '<label>Product Variation Image</label><br>'+
                  '<p><input type="file" name="ns-thumbnail-var'+j+'" id="ns-thumbnail-var'+j+'" /></p>'+
               '</div>'+
               '<div><label>Sku</label><br><input class="ns-input-width" size="6" id="ns-variation-sku'+j+'" name="ns-variation-sku'+j+'"  type="text"></div>'+
               '<div>'+
                  '<table>'+
                     '<tr>'+
                        '<th>Downloadable</th><th><input name="ns-variation-downloadable'+j+'" id="ns-variation-downloadable'+j+'" type="checkbox"></th>'+
                        '<th>Virtual</th><th><input name="ns-variation-virtual'+j+'" id="ns-variation-virtual'+j+'" type="checkbox"></th>'+
                     '</tr>'+
                  '</table>'+
               '</div>'+
               '<div>'+
                  '<p><label class="ns-input-width">Regular Price</label><br><input id="ns-variation-regular-price'+j+'" name="ns-variation-regular-price'+j+'" class="ns-input-width" placeholder="Variation price (required)" type="text"></p>'+
                  '<p><label class="ns-input-width">Sale Price</label><br><input id="ns-variation-sale-price'+j+'" name="ns-variation-sale-price'+j+'" class="ns-input-width" type="text"></p>'+
                  '<p>'+
                     '<label class="ns-input-width">Stock Status</label><br>'+
                     '<select id="ns-variation-stock-status'+j+'" name="ns-variation-stock-status'+j+'" class="ns-input-width" >'+
                        '<option value="instock">In stock</option>'+
                        '<option value="outofstock">Out of stock</option>'+
                     '</select>'+
                  '</p>'+
                  '<p>'+
                     '<label class="ns-input-width">Weight(kg)</label><br><input id="ns-variation-weight'+j+'" name="ns-variation-weight'+j+'" class="ns-input-width" type="text">'+
                     '<br><label class="ns-input-width">Length</label><br>'+
                     '<input id="ns-variation-length'+j+'" name="ns-variation-length'+j+'" class="ns-input-width" type="text"><br>'+
                     '<label class="ns-input-width">Width</label><br>'+
                     '<input id="ns-variation-width'+j+'" name="ns-variation-width'+j+'" class="ns-input-width" type="text"><br>'+
                     '<label class="ns-input-width">Height</label><br>'+
                     '<input id="ns-variation-height'+j+'" name="ns-variation-height'+j+'" class="ns-input-width" type="text"><br>'+
                  '</p>'+
                  '<p>'+
                     '<label class="ns-input-width">Variation description</label>'+
                     '<textarea id="ns-variation-descritpion'+j+'" name="ns-variation-descritpion'+j+'"></textarea>'+
                  '</p>'+
               '</div>'+
               '<button type="button" class="button ns-variation-remove" style="float:left">Remove</button>'+
            '</div>';

      jQuery('#ns-inner-variation').after(html_to_append);
      jQuery('#ns-variation-list').val(j);
      add_options_attribute_variation();

   });

   //removing variation
	jQuery(document).on('click', '.ns-variation-remove', function(event){
		jQuery(this).parent().remove();
		j--;
		jQuery('#ns-variation-list').val(j);
	});


	//used to populate options attribute variation
	function add_options_attribute_variation(){
		var to_split = jQuery('#ns-attr-from-list-variation').val();
		var arr_of_attr_var = to_split.split(',');

		jQuery.each(arr_of_attr_var, function(index, item){
			jQuery('.ns-variation-attributes').append('<option id="opt-'+item+'" value="'+item+'">'+item+'</option>');
		});
	}

   //used to populate custom options attribute variation
   jQuery('#ns-var-button').on('click', function(event) {

      jQuery('.ns-variation-custom-attributes').empty();	//clean input

      var to_split = jQuery('#ns-attr-custom-names').val();
      var arr_of_attr_var = to_split.split(',');

      jQuery.each(arr_of_attr_var, function(index, item){
         jQuery('.ns-variation-custom-attributes').append('<option id="opt-'+item+'" value="'+item+'">'+item+'</option>');
      });
   });
});
