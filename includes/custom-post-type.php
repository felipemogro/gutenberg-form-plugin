<?php

function gfp_register_suggestion_post_type() {
    register_post_type('suggestion', [
        'labels' => [
            'name' => __('Sugerencias'),
            'singular_name' => __('Sugerencia')
        ],
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'custom-fields'],
    ]);
}

add_action('init', 'gfp_register_suggestion_post_type');

function gfp_register_custom_meta(){
    register_post_meta('suggestion', '_gfp_email', [
        'show_in_rest' => true,
        'type' => 'string',
        'single' => true,
    ]);
    register_post_meta('suggestion', '_gfp_country', [
        'show_in_rest' => true,
        'type' => 'string',
        'single' => true,
    ]);
    register_post_meta('suggestion', '_gfp_suggestions', [
        'show_in_rest' => true,
        'type' => 'string',
        'single' => true,
    ]);
}
add_action('init', 'gfp_register_custom_meta');
