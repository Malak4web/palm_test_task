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
    $summary = get_post_meta($post->ID, 'summary', true);
    $o = '<button class="button button-primary" id="generate-summary">Generate Summary</button>';
    if ($summary) {
        $o .= '<p>Summary: ' . $summary . '</p>';
    }
    echo $o;

}

