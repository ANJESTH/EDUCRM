$ = jQuery.noConflict();

$(document).ready(function(){

    // Post extra ajax req. param on application add/edit page,
    // query courses interms of institutions.
    if (typeof acf !== 'undefined') {
    	acf.add_filter('select2_ajax_data', (data) => {
            // 'field_58a1b11070e0f', //course fieldkey
            if (data.field_key === 'field_58a1b11070e0f') {
            //'field_58a1b0f970e0e', // Institution field key
    		data.institution_id = document.getElementById('acf-field_58a1b0f970e0e').value;
			console.log( typeof data.institution_id );
			if( data.institution_id === '' ) {
				data = null;
				alert( 'Choose Institution first!');
			}
    	  }
    	  return data;
    	});
    }

});
