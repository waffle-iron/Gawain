/*
 * Gawain
 * Copyright (C) 2016  Stefano Romanò (rumix87 (at) gmail (dot) com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Bind button events using data-gawain-* attributes
 */


$(function () {
    $('body').on('click', 'button.gawain-controller-button', function () {

        // Variable declaration
        var str_Controller = $(this).attr('data-gawain-controller');
        var str_ControllerMethod = $(this).attr('data-gawain-controller-method');
        var str_RequestMethod = $(this).attr('data-gawain-request-method');
        var str_RequestTarget = $(this).attr('data-gawain-request-target');
        var str_ResponseTarget = $(this).attr('data-gawain-response-target');

        var str_WaitingSpinner = '<i class="fa fa-refresh fa-spin fa-3x"></i>';

        // Adds the waiting spinner to Response target, if exists
        if (str_ResponseTarget !== undefined) {
            $('#' + str_ResponseTarget)
                .empty()
                .html(str_WaitingSpinner)
                .show();
        }


        // Retrieve values from target
        var obj_Target = $('#' + str_RequestTarget);
        var obj_RequestBody = {};


        // Start collecting elements details
        // Inputs
        var obj_Inputs = obj_Target.find('input');

        obj_Inputs.each(function () {
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

        obj_Selects.each(function () {
            var str_Name = $(this).attr('name');
            var str_Value = $(this).val();

            obj_RequestBody[str_Name] = str_Value;
        });


        // Checkboxes
        var obj_Checkboxes = obj_Target.find('checkbox');

        obj_Checkboxes.each(function () {
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
                url: str_ControllerURL,
                method: str_RequestMethod,
                data: JSON.stringify(obj_RequestBody)
            })
            .done(function (str_Data) {
                if (str_ResponseTarget !== undefined) {
                    $('#' + str_ResponseTarget)
                        .hide()
                        .empty()
                        .html(str_Data)
                        .fadeIn(250);
                }
            });
    });

});
