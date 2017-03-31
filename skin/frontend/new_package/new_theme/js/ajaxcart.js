/**
 * Created by aber on 11.08.16.
 */

function setLocationAjax(id, form_key)
{
    $j.ajax({
        url: '/ajaxcart/cart/add',
        dataType: 'json',
        method: 'post',
        data: 'product='+id+'&form_key='+form_key,
        success: function (data) {
            if(data.status == 'success')
            {
                $j('#header-account').html(data.toplink);
                $j('.header-minicart').html(data.sidebar);
            }
            $j('#messages_product_view').remove();
            $msgHtml = '<div id="messages_product_view"><ul class="messages">' +
                '<li class="'+data.status+'-msg">' +
                '<ul>' +
                '<li><span>'+data.msg+'</span></li>' +
                '</ul>' +
                '</li>' +
                '</ul></div>';
            $j('#map-popup').after($msgHtml);
        }

    });
}

var skipContents = $j('.skip-content');
var skipLinks = $j('.skip-link');

$j(document).on('click', '.header-minicart > a', function (e) {
    e.preventDefault();

    var self = $j(this);
    // Use the data-target-element attribute, if it exists. Fall back to href.
    var target = self.attr('data-target-element') ? self.attr('data-target-element') : self.attr('href');

    // Get target element
    var elem = $j(target);

    // Check if stub is open
    var isSkipContentOpen = elem.hasClass('skip-active') ? 1 : 0;

    // Hide all stubs
    skipLinks.removeClass('skip-active');
    skipContents.removeClass('skip-active');

    // Toggle stubs
    if (isSkipContentOpen) {
        self.removeClass('skip-active');
    } else {
        self.addClass('skip-active');
        elem.addClass('skip-active');
    }

});

$j(document).on('click', '#header-cart .skip-link-close', function(e) {
    var parent = $j(this).parents('.skip-content');
    var link = parent.siblings('.skip-link');

    parent.removeClass('skip-active');
    link.removeClass('skip-active');

    e.preventDefault();
});

