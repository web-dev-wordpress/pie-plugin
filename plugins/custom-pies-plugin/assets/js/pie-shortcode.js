(function($) {
    $(document).ready(function() {
        // Toggle the pie details when the title or arrow is clicked
        $('.pie-title').on('click', function() {
            $(this).next('.pie-details').slideToggle(); // Show/hide the details
            $(this).toggleClass('open'); // Toggle the open class to rotate the arrow
            console.log('hello');
        });
    });
})(jQuery);
