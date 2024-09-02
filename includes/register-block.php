<?php
function gfp_register_gutenberg_form_block() {
    wp_register_script(
        'gfp-block-script',
        GFP_PLUGIN_URL . 'dist/block.bundle.js',
        ['wp-blocks', 'wp-element', 'wp-editor'],
        filemtime(GFP_PLUGIN_DIR . 'dist/block.bundle.js')
    );

    register_block_type('gfp/form-block', [
        'editor_script'   => 'gfp-block-script',
        'render_callback' => 'gfp_render_form_block',
        'attributes'      => [
            'bgColor' => [
                'type'    => 'string',
                'default' => 'lightcoral'
            ],
        ]
    ]);
}
add_action('init', 'gfp_register_gutenberg_form_block');

function gfp_render_form_block($attributes) {
    ob_start();
    $bgColor = isset($attributes['bgColor']) ? esc_attr($attributes['bgColor']) : 'lightcoral';
    ?>
    <div class="container p-4" id="gfp-form-block" style="background-color: <?php echo $bgColor; ?>; border-radius: 10px;">
        <form id="gfp-form" class="form-inline">
            <div class="row mb-3">
                <div class="col-md-6 input-group">
                    <span class="input-group-text"><?php esc_html_e('Nombre:', 'gfp-plugin-textdomain'); ?></span>
                    <input type="text" class="form-control" id="gfp-name" name="name" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 input-group">
                    <span class="input-group-text"><?php esc_html_e('Apellido:', 'gfp-plugin-textdomain'); ?></span>
                    <input type="text" class="form-control" id="gfp-lastname" name="lastname" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 input-group">
                    <span class="input-group-text"><?php esc_html_e('Email:', 'gfp-plugin-textdomain'); ?></span>
                    <input type="email" class="form-control" id="gfp-email" name="email" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 input-group">
                    <span class="input-group-text"><?php esc_html_e('País:', 'gfp-plugin-textdomain'); ?></span>
                    <select id="gfp-country" class="form-control" name="country" required></select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 input-group">
                    <span class="input-group-text"><?php esc_html_e('Sugerencias:', 'gfp-plugin-textdomain'); ?></span>
                    <textarea id="gfp-suggestions" class="form-control" name="suggestions" rows="3"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="buttons-css">
                    <div>
                        <a class="style-button-stargo" href="<?php echo esc_url(site_url()); ?>">
                            <?php esc_html_e('Volver al home', 'gfp-plugin-textdomain'); ?>
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" style="border-radius: 32px 32px 32px 32px;">
                            <?php esc_html_e('Guardar Cambios', 'gfp-plugin-textdomain'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div id="gfp-form-response" class="mt-3"></div>
    </div>
    <?php
    return ob_get_clean();
}
?>
