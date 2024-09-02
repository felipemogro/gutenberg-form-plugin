<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}

function gfp_suggestions_shortcode_edit() {
    if (!current_user_can('manage_options')) {
        return '<p>' . esc_html__('No tienes permiso para ver esta página.', 'gfp-plugin-textdomain') . '</p>';
    }

    ob_start();
    
    $suggestion_id = get_query_var('suggestion_id');
    $suggestion = get_post($suggestion_id);

    if (!$suggestion || $suggestion->post_type !== 'suggestion') {
        wp_redirect(home_url());
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = sanitize_email($_POST['email']);
        $country = sanitize_text_field($_POST['country']);
        $suggestions = sanitize_textarea_field($_POST['suggestions']);

        update_post_meta($suggestion_id, '_gfp_email', $email);
        update_post_meta($suggestion_id, '_gfp_country', $country);
        update_post_meta($suggestion_id, '_gfp_suggestions', $suggestions);

        echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "' . esc_js(__('¡Cambios guardados!', 'gfp-plugin-textdomain')) . '",
                    text: "' . esc_js(__('Tu sugerencia ha sido actualizada.', 'gfp-plugin-textdomain')) . '",
                    icon: "success",
                    confirmButtonText: "' . esc_js(__('Volver al listado', 'gfp-plugin-textdomain')) . '"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = localStorage.getItem(\'back-button\');
                    }
                });
            });
        </script>';
    }

    $selected_country = esc_attr(get_post_meta($suggestion_id, '_gfp_country', true));
?>

<div class="container">
    <form method="POST">
        <div class="mb-3">
            <div class="col-md-6 input-group">
                <span class="input-group-text"><?php esc_html_e('Email:', 'gfp-plugin-textdomain'); ?></span>
                <input type="email" class="form-control" id="gfp-email" name="email" value="<?php echo esc_attr(get_post_meta($suggestion_id, '_gfp_email', true)); ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <div class="col-md-6 input-group">
                <span class="input-group-text"><?php esc_html_e('País:', 'gfp-plugin-textdomain'); ?></span>
                <select class="form-control" id="gfp-country" name="country" required>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <div class="col-md-6 input-group">
                <span class="input-group-text"><?php esc_html_e('Sugerencias:', 'gfp-plugin-textdomain'); ?></span>
                <textarea class="form-control" id="suggestions" name="suggestions" rows="3" required><?php echo esc_textarea(get_post_meta($suggestion_id, '_gfp_suggestions', true)); ?></textarea>
            </div>       
        </div>
        <div class="buttons-css">
            <div>
                <a id="editar-back" class="style-button-stargo" href="#"><?php esc_html_e('Volver al listado', 'gfp-plugin-textdomain'); ?></a>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><?php esc_html_e('Guardar Cambios', 'gfp-plugin-textdomain'); ?></button>
            </div>
        </div>
    </form>
</div>


<script type="text/javascript">
    jQuery(document).ready(function($) {
        $.ajax({
            url: 'https://restcountries.com/v3.1/all',
            method: 'GET',
            success: function(countries) {
                countries.sort((a, b) => a.name.common.localeCompare(b.name.common));
                const selectedCountry = '<?php echo esc_js($selected_country); ?>';

                countries.forEach(function(country) {
                    const isSelected = country.name.common === selectedCountry ? 'selected' : '';
                    $('#gfp-country').append(
                        `<option value="${country.name.common}" ${isSelected}>${country.name.common}</option>`
                    );
                });
            }
        });
    });
</script>
<script>
    let editarBack = document.getElementById('editar-back')
    let back = localStorage.getItem("back-button")
    if (back) {
        document.getElementById("editar-back").href=back; 
    }else{
        document.getElementById("editar-back").href="<?php echo esc_url(site_url() ); ?>"
    }
</script>
<?php
    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('gfp_edit_suggestions_shortcode', 'gfp_suggestions_shortcode_edit');
?>
