<?php
/**
 * HWY 559 Bridge Theme Functions
 */

defined('ABSPATH') || exit;

function hwy559bridge_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'hwy559bridge_theme_setup');

function hwy559bridge_enqueue_scripts() {
    wp_enqueue_style('hwy559bridge-style', get_stylesheet_uri(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'hwy559bridge_enqueue_scripts');
