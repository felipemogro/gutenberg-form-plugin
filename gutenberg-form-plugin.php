<?php
/**
 * Plugin Name: Gutenberg Form Plugin
 * Description: Crear bloque personalizado para formulario de Sugerencias en Gutenberg
 * Version: 1.0
 * Author: Felipe Mogro
 * Text Domain: gfp-plugin-textdomain
 * Author URI: https://github.com/felipemogro
 */

if (!defined('ABSPATH')) {
    exit;
}

define('GFP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('GFP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Incluir archivos necesarios
require_once GFP_PLUGIN_DIR . 'includes/custom-post-type.php';
require_once GFP_PLUGIN_DIR . 'includes/register-block.php';
require_once GFP_PLUGIN_DIR . 'includes/ajax-handler.php';
require_once GFP_PLUGIN_DIR . 'includes/shortcode.php';

// Hooks de activación
register_activation_hook(__FILE__, 'gfp_register_suggestion_post_type');
register_activation_hook(__FILE__, 'gfp_register_custom_meta');

// Reglas de reescritura personalizadas
function gfp_add_custom_rewrite_rules() {
    add_rewrite_rule('^([0-9]+)/editar-ficha/?', 'index.php?suggestion_id=$matches[1]&pagename=editar-ficha', 'top');
    add_rewrite_tag('%suggestion_id%', '([0-9]+)');
}
add_action('init', 'gfp_add_custom_rewrite_rules');

function gfp_template_redirect() {
    if (get_query_var('pagename') == 'editar-ficha') {
        require_once GFP_PLUGIN_DIR . 'includes/edit-suggestion.php';
    }
}
add_action('template_redirect', 'gfp_template_redirect');

//Inclusiond e bootstrap y sweetalert
function gfp_enqueue_basic_scripts() {
    wp_enqueue_style('basics',  plugin_dir_url( __FILE__ )  . 'assets/basics.css');
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true);
    wp_enqueue_script('sweetalert-js', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', [], null, true);
}
add_action('wp_enqueue_scripts', 'gfp_enqueue_basic_scripts');


function gfp_load_textdomain() {
    load_plugin_textdomain('gfp-plugin-textdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', 'gfp_load_textdomain');