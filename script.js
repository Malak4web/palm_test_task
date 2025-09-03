jQuery('#generate-summary').click(function(e) {
    e.preventDefault();
    var post_id = jQuery(this).data('post-id');
    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {action: 'generate_summary', post_id: post_id},
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.data);
            }
        }
    });
});