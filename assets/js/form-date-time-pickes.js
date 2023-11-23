$(function() {
    "use strict";

    $('.timepicker').pickatime();

    $('.date-time').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });

    $('#date').bootstrapMaterialDatePicker({
        time: false
    });

    $('#time').bootstrapMaterialDatePicker({
        date: false,
        format: 'HH:mm'
    });

});