<?php 

function generateSummary() {
    $nonce = isset($_POST['_wpnonce']) ? sanitize_text_field(wp_unslash($_POST['_wpnonce'])) : '';
    
    if (!isset($nonce) || !wp_verify_nonce($nonce, 'generate_summary_action')) {
        wp_send_json_error('Invalid or missing nonce');
    }
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
    if (isset($summary['error'])) {
        wp_send_json_error($summary['error']);
    }else {
        update_post_meta($post_id, 'palmtestSummary', $summary['summary']);
    }
    wp_send_json_success('Summary generated successfully');
}
add_action('wp_ajax_generate_summary', 'generateSummary');

function getAiSummary($content) {
    $api_key = summarySettingsGetApiKey();
    
    if (empty($api_key)) {
        return ['error' => 'API key not configured. Please set your API key in Summary Settings.'];
    }
    
    $summaryLength = summarySettingsGetSummaryLength();
    
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    $data = array(
        'contents' => array(
            array(
                'parts' => array(
                    array(
                        'text' => "Please generate a summary of the following content in approximately {$summaryLength} characters and reply only with the summary here's the content: " . $content
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
        return [ 'error' => 'Error: Unable to connect to AI service.' ];
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $content =$result['candidates'][0]['content']['parts'][0]['text'];
        $content = strip_tags($content);
        // Truncate summary to summaryLength characters if needed
        if (mb_strlen($content) > $summaryLength) {
            $content = mb_substr($content, 0, $summaryLength) . '...';
        }
        return [ 'summary' => esc_html($content) ];
    }

    if (isset($result['error']['message'])) {
        return [ 'error' => 'Error: ' . esc_html(strip_tags($result['error']['message'])) ];
    }
    if (empty($body) || !is_array($result)) {
        return [ 'error' => 'Error: Invalid response from AI service.' ];
    }

    return [ 'error' => 'Error: Invalid response from AI service.' ];
}
