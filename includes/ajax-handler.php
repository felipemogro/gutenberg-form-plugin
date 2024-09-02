<?php

function gfp_handle_ajax_form_submit() {
    // Verificar el nonce de seguridad
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'gfp-form-nonce')) {
        wp_send_json_error(__('Nonce inválido', 'gfp-plugin-textdomain'));
        return;
    }

    // Sanitizar los campos del formulario
    $name = sanitize_text_field($_POST['name']);
    $lastname = sanitize_text_field($_POST['lastname']);
    $email = sanitize_email($_POST['email']);
    $country = sanitize_text_field($_POST['country']);
    $suggestions = sanitize_textarea_field($_POST['suggestions']);

    $suggestion_id = isset($_POST['suggestion_id']) ? intval($_POST['suggestion_id']) : 0;
    //var_dump($suggestion_id);

    //Valido si tengo un id de sugerencia
    if ($suggestion_id > 0) {
        // Actualizar el post existente
        $post_id = wp_update_post([
            'ID' => $suggestion_id,
            'post_title' => $name . ' ' . $lastname,
        ]);

        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, '_gfp_email', $email);
            update_post_meta($post_id, '_gfp_country', $country);
            update_post_meta($post_id, '_gfp_suggestions', $suggestions);
            wp_send_json_success(__('Sugerencia actualizada correctamente', 'gfp-plugin-textdomain'));
        } else {
            wp_send_json_error(__('Error al actualizar la sugerencia', 'gfp-plugin-textdomain'));
        }
    } else {
        // Crear un nuevo post en el CPT 'suggestion'
        $post_id = wp_insert_post([
            'post_type' => 'suggestion',
            'post_status' => 'publish',
            'post_title' => $name . ' ' . $lastname,
            'meta_input' => [
                '_gfp_email' => $email,
                '_gfp_country' => $country,
                '_gfp_suggestions' => $suggestions,
            ],
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            wp_send_json_success(__('Gracias por su sugerencia', 'gfp-plugin-textdomain'));
        } else {
            wp_send_json_error(__('Hubo un error al guardar su sugerencia', 'gfp-plugin-textdomain'));
        }
    }
}

add_action('wp_ajax_gfp_form_submit', 'gfp_handle_ajax_form_submit');
add_action('wp_ajax_nopriv_gfp_form_submit', 'gfp_handle_ajax_form_submit');
add_action('wp_enqueue_scripts', 'gfp_enqueue_scripts');

function gfp_enqueue_scripts() {
    wp_enqueue_script(
        'gfp-ajax-script',
        GFP_PLUGIN_URL . 'assets/form.js',
        ['jquery'],
        filemtime(GFP_PLUGIN_DIR . 'assets/form.js'),
        true
    );

    $suggestion_id = get_query_var('suggestion_id');

    $selected_country = $suggestion_id ? get_post_meta($suggestion_id, '_gfp_country', true) : '';

    wp_localize_script('gfp-ajax-script', 'gfp_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('gfp-form-nonce'),
        'is_user_logged_in' => is_user_logged_in(),
        'user_name' => is_user_logged_in() ? wp_get_current_user()->user_firstname : '',
        'user_lastname' => is_user_logged_in() ? wp_get_current_user()->user_lastname : '',
        'user_email' => is_user_logged_in() ? wp_get_current_user()->user_email : '',
        'selected_country' => $selected_country, 
    ]);
}

add_action('wp_enqueue_scripts', 'gfp_enqueue_scripts');
