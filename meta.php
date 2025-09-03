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
    wp_enqueue_script( 'summary-meta-box-script', plugins_url('script.js', __FILE__), array('jquery'), PALM_VERSION, true );

    $summary = get_post_meta($post->ID, 'summary', true);
    $o = '<button class="button button-primary" id="generate-summary" data-post-id="' . $post->ID . '">Generate Summary</button>';
    if ($summary) {
        $o .= '<p>Summary:<br> <code>' . esc_html( $summary ). '</code></p>';
    }
    echo $o;

}

