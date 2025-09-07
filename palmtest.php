<?php 
/*
Plugin Name: Palm Test Task
Plugin URI: https://github.com/Malak4web/palm-test-task
Description: A simple WordPress plugin for the Palm test task.
Version: 1.0.0
Author: Malak Younan
Author URI: https://www.linkedin.com/in/malaak4web/
License: GPL2
Text Domain: palmtest
*/

if (!defined('ABSPATH')) {
    exit;
}
define('GEMINI_API_KEY', 'AIzaSyB8RB6QHZlpnrpUpoO1uM8IMiaMO9_MjwI');
define('PALM_VERSION', '1.0.0');

// Load required files
require_once 'includes/meta.php';
require_once 'includes/summary_provider.php';

function createCPT() {
    $labels = array(
        'name' => __('Community Discussions' , 'palmtest'),
        'singular_name' => __('Community Discussion' , 'palmtest'),
        'menu_name' => __('Community Discussions' , 'palmtest'),
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'discussions'),
    );
    register_post_type('discussions', $args);
}
add_action('init', 'createCPT');



add_filter('the_content', 'addSummaryToContent');
function addSummaryToContent($content) {
    $summary = get_post_meta(get_the_ID(), 'summary', true);
    if ($summary) {
        $content .= '<div class="summary">' . esc_html($summary) . '</div>';
    }
    return $content;
}

