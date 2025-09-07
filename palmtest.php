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
define('PALM_VERSION', '1.0.0');
define('PALM_POST_LENGTH', 150);
// Load required files
require_once 'includes/meta.php';
require_once 'includes/summary_provider.php';
require_once 'includes/admin_functions.php';

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
    if (get_post_type() !== 'discussions') {
        return $content;
    }
    
    $summary = get_post_meta(get_the_ID(), 'palmtestSummary', true);
    if ($summary) {
        $content .= '<div class="palmtestSummary" style="background: #f0f8ff; border-left: 4px solid #0073aa; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <h4 style="margin-top: 0; color: #0073aa;">' . __('AI Summary', 'palmtest') . '</h4>
            <p style="margin-bottom: 0;">' . esc_html($summary) . '</p>
        </div>';
    }
    return $content;
}

