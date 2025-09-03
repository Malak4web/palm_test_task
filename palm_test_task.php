<?php 
/*
Plugin Name: Palm Test Task
Plugin URI: https://github.com/Malak4web/palm-test-task
Description: A simple WordPress plugin for the Palm test task.
Version: 1.0.0
Author: Malak Younan
Author URI: https://www.linkedin.com/in/malaak4web/
License: GPL2
*/

require_once 'meta.php';

function createCPT() {
    $labels = array(
        'name' => 'Community Discussions',
        'singular_name' => 'Community Discussion',
        'menu_name' => 'Community Discussions',
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

