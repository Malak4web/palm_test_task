jQuery('#generate-summary').click(function(e) {
    e.preventDefault();
    var post_id = jQuery(this).data('post-id');
    var nonce = jQuery(this).data('nonce');
    jQuery(this).prop('disabled', true);
    jQuery(this).text('Generating...');
    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {action: 'generate_summary', post_id: post_id, _wpnonce: nonce},
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.data);
            }
        },
        error: function(xhr, status, error) {
            alert(error);
        },
        complete: function() {
            jQuery(this).prop('disabled', false);
            jQuery(this).text('Generate Summary');
        }
    });
});