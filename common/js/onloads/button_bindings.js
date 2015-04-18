/**
 * Bind button events using data-gawain-* attributes
 */


$(function() {
    $('button.gawain-method-button').each(function() {
		$(this).click(function() {

			// Variable declaration
			var str_Controller = $(this).attr('data-gawain-controller');
			var str_Method = $(this).attr('data-gawain-method');
			var str_Target = $(this).attr('data-gawain-target');
			var str_Hashing = $(this).attr('data-gawain-hash');

			// Retrieve values from target
			var obj_Target = $('#' + str_Target);
			var obj_FormContent = obj_Target.serializeArray();
		});
    });

});
