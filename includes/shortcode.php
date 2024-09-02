<?php
// Verificar que WordPress ha sido cargado
if (!defined('ABSPATH')) {
    exit;
}

//Creacoion de shortcode para mostrar las sugerencias
function gfp_suggestions_shortcode() {
    if (!current_user_can('manage_options')) {
        return '<p>' . __('No tienes permiso para ver esta página.', 'gfp-plugin-textdomain') . '</p>';
    }

    $query = new WP_Query([
        'post_type' => 'suggestion',
        'posts_per_page' => -1,
    ]);

    if (!$query->have_posts()) {
        return '<p>' . __('No hay sugerencias disponibles.', 'gfp-plugin-textdomain') . '</br> <a class="style-button-stargo" href="' . esc_url(site_url()) . '">' . __('Volver al home', 'gfp-plugin-textdomain') . '</a></p>';
    }
    
    ob_start();
    ?>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th><?php _e('Nombre', 'gfp-plugin-textdomain'); ?></th>
                    <th><?php _e('Email', 'gfp-plugin-textdomain'); ?></th>
                    <th><?php _e('Sugerencia', 'gfp-plugin-textdomain'); ?></th>
                    <th><?php _e('Acciones', 'gfp-plugin-textdomain'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php
                $email = get_post_meta(get_the_ID(), '_gfp_email', true);
                $suggestion = get_post_meta(get_the_ID(), '_gfp_suggestions', true);
                ?>
                <tr>
                    <td><?php the_title(); ?></td>
                    <td><?php echo esc_html($email); ?></td>
                    <td><?php echo esc_html($suggestion); ?></td>
                    <td>
                        <a id="btn-editar" href="<?php echo esc_url(site_url(get_the_ID() . '/editar-ficha/')); ?>" class="btn btn-sm btn-primary">
                            <?php _e('Editar', 'gfp-plugin-textdomain'); ?>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
            <a class="style-button-stargo" href="<?php echo site_url(); ?>"> <?php esc_html_e('Volver al home', 'gfp-plugin-textdomain'); ?></a>
        </div>
        <script>
        let button = document.getElementById('btn-editar')
 
        button.addEventListener('click', e => { 
            localStorage.setItem("back-button", window.location.href
            )
        }) 
        </script>
    <?php
    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('gfp_suggestions', 'gfp_suggestions_shortcode');
