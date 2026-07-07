(function($) {
    $.fn.enable_disableline = function(enable_element) {
        // 'this' refers to the jQuery object selection
        return this.each(function() {
            // 'this' refers to the individual DOM element inside the loop
            if(enable_element) {
                $(this).closest('tr').find('span').removeClass("disabled");
                return $(this).prop("disabled", false);
            } else {
                $(this).closest('tr').find('span').addClass("disabled");
                return $(this).prop("disabled", true);
            }
        });
    };
}(jQuery));
