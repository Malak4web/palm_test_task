<?php 



function addSummaryMetaBox() {
    add_meta_box(
        'summary_meta_box',
        'Summary',
        'summaryMetaCB',
        'discussions',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'addSummaryMetaBox');


function summaryMetaCB($post) {
    wp_nonce_field(basename(__FILE__), 'summary_meta_box_nonce');
    wp_enqueue_script( 'summary-meta-box-script', plugins_url('script.js', __FILE__), array('jquery'), '1.0.0', true );
    $summary = get_post_meta($post->ID, 'summary', true);
    $o = '<button class="button button-primary" id="generate-summary" data-post-id="' . $post->ID . '">Generate Summary</button>';
    if ($summary) {
        $o .= '<p>Summary:<br> <code>' . $summary . '</code></p>';
    }
    echo $o;

}

