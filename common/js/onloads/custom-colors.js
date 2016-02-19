/**
 * Automatically sets color to elements based on gawain-custom-color-* class
 */


$('*[class^="gawain-custom-front-color-"]').each(function () {

    var str_FullClass = $(this).text();
    var str_ColorCode = str_FullClass.replace('gawain-custom-color-', '');

    $(this).css('color', str_ColorCode);

});


$('*[class^="gawain-custom-bg-color-"]').each(function () {

    var str_FullClass = $(this).text();
    var str_ColorCode = str_FullClass.replace('gawain-custom-color-', '');

    $(this).css('background-color', str_ColorCode);

});


$('*[class^="gawain-custom-bg-3d-gradient-color-"]').each(function () {

    var str_FullClass = $(this).text();
    var str_ColorCode = str_FullClass.replace('gawain-custom-color-', '');

    $(this).css('background-color', str_ColorCode);
    // TODO: fix 3D gradient definition

});