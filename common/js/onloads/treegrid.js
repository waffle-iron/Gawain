/**
 * Generates the treegrids
 */

$(function () {
    $('.gawain-treegrid').treegrid({
        initialState: 'collapsed',
        expanderTemplate: '<i class="treegrid-expander"></i> ',
        expanderExpandedClass: 'fa fa-minus-square',
        expanderCollapsedClass: 'fa fa-plus-square'
    });
});