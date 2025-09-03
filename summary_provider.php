<?php 

function generateSummary() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Insufficient permissions');
    }

    if (empty($post_id)) {
        wp_send_json_error('Post ID is required');
    }

    $post = get_post($post_id);
    if (empty($post)) {
        wp_send_json_error('Post not found');
    }

    if ($post->post_type !== 'discussions') {
        wp_send_json_error('Invalid post type');
    }

    $content = $post->post_content;
    if (empty($content)) {
        wp_send_json_error('Content not found');
    }
    $summary = getAiSummary($content);
    if (empty($summary)) {
        wp_send_json_error('Summary not generated');
    }
    update_post_meta($post_id, 'summary', $summary);
    wp_send_json_success('Summary generated successfully');
}
add_action('wp_ajax_generate_summary', 'generateSummary');

function getAiSummary($content) {

    $api_key = GEMINI_API_KEY;
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    $data = array(
        'contents' => array(
            array(
                'parts' => array(
                    array(
                        'text' => "Please generate a summary of the following content: " . $content
                    )
                )
            )
        )
    );

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $api_key
        ),
        'body' => wp_json_encode($data),
        'timeout' => 15
    );

    $response = wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        return 'Error: Unable to connect to AI service.';
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        return  esc_html($result['candidates'][0]['content']['parts'][0]['text']);
    }

    return  esc_html(substr($content, 0, 100) .'....');
}
