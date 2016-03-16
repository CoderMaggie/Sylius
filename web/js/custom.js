$(function () {
    capBgm.initFilterToggles();
});

capBgm = {
    initFilterToggles: function() {
        $('.filter-toggle').on('click','a', function(e) {
            e.preventDefault();
            var $panelToggle = $(this).closest('.filter-toggle');
            var $panelBody = $panelToggle.closest('.panel').find('.panel-body');
            $panelBody.removeClass('hidden');
            $panelToggle.hide();
        });
    }
};