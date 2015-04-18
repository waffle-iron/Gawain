/**
 * Bind button events using data-gawain-* attributes
 */


$(function() {
    $('button.gawain-controller-button').each(function() {
		$(this).click(function() {

			// Variable declaration
			var str_Controller = $(this).attr('data-gawain-controller');
			var str_ControllerMethod = $(this).attr('data-gawain-controller-method');
			var str_RequestMethod = $(this).attr('data-gawain-request-method');
			var str_Target = $(this).attr('data-gawain-target');

			// Retrieve values from target
			var obj_Target = $('#' + str_Target);
			var obj_RequestBody = {};



			// Start collecting elements details
			// Inputs
			var obj_Inputs = obj_Target.find('input');

			obj_Inputs.each(function() {
				var str_Name = $(this).attr('name');
				var str_Value;

				if ($(this).attr('data-gawain-hash') !== undefined) {
					str_Value = CryptoJS.SHA3($(this).val()).toString(CryptoJS.enc.Hex);
				} else {
					str_Value = $(this).val();
				}

				obj_RequestBody[str_Name] = str_Value;
			});


			// Selects
			var obj_Selects = obj_Target.find('select');

			obj_Selects.each(function() {
				var str_Name = $(this).attr('name');
				var str_Value = $(this).val();

				obj_RequestBody[str_Name] = str_Value;
			});


			// Checkboxes
			var obj_Checkboxes = obj_Target.find('checkbox');

			obj_Checkboxes.each(function() {
				var str_Name = $(this).attr('name');
				var str_Value = $(this).prop('checked');

				obj_RequestBody[str_Name] = str_Value;
			});


			// Compose the request
			// TODO: make this URL dynamic and taken from environment variables
			var str_RequestBaseURL = '/gawain/rest-api/';
			var str_ControllerURL = str_RequestBaseURL + str_Controller + '/' + str_ControllerMethod;


			// Send the request
			$.ajax({
				url :   str_ControllerURL,
				method: str_RequestMethod,
				data:   JSON.stringify(obj_RequestBody)
			       })
				.done(function(str_Data) {
				                     console.log(str_Data);
			                      });
		});
    });

});
