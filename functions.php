<?php

function cpam_setup()
{

    // Imagenes Destacadas
    add_theme_support('post-thumbnails');

    // Titulos de las paginas -> SEO
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'cpam_setup');

function cpam_menus()
{

    register_nav_menus(array(
        'menu-principal' => __('Menu Principal', 'cpam')
    ));
}

add_action('init', 'cpam_menus');

function cpam_scripts_styles()
{

    wp_enqueue_style('style', get_template_directory_uri() . '/assets/css/style.css', array(), '1.0.0');
    // wp_enqueue_style('style', get_stylesheet_uri(), array(), '1.0.0'); // estilos ubicado en la carpeta raiz
}

add_action('wp_enqueue_scripts', 'cpam_scripts_styles');

// Ocultar "Entradas" del menú de administración
function quitar_menu_entradas() {
    remove_menu_page('edit.php'); 
}

add_action('admin_menu', 'quitar_menu_entradas');

/* SWIPER */
function enqueue_swiper_assets()
{
    wp_enqueue_style('swiper-css', get_template_directory_uri() . '/assets/css/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', [], null, true);
}

add_action('wp_enqueue_scripts', 'enqueue_swiper_assets');

// PAGINACIÓN ESPECIALIDADES
function cargar_especialidades_ajax()
{
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $args = array(
        'post_type' => 'especialidades',
        'post_status'    => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
        'posts_per_page' => 8,
        'paged' => $paged
    );

    $query = new WP_Query($args);

    $html = '';

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            /* LOCAL */
            /* $image_html = '';
            $image_id = get_field('imagen');
            if ($image_id) {
                $image_html = wp_get_attachment_image($image_id, 'full', false, ['class' => 'imagen']);
            } */

            /* SHARED */
            $image_html = '';
            $image_id = get_field('imagen');
            if ($image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'full'); // URL absoluta
                $image_html = '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '" class="imagen">';
            }

            $html .= '<li class="card">
                        <div class="contenido text-white">
                            ' . $image_html . '
                            <div class="bg-contenido">
                                <h3>' . get_the_title() . '</h3>
                                ' . get_field('descripcion') .  '
                            </div>
                        </div>
                    </li>';
        endwhile;
    endif;

    wp_reset_postdata();

    wp_send_json([
        'html' => $html,
        'maxPages' => $query->max_num_pages
    ]);
}

// Para usuarios logueados y no logueados
add_action('wp_ajax_nopriv_cargar_especialidades', 'cargar_especialidades_ajax');
add_action('wp_ajax_cargar_especialidades', 'cargar_especialidades_ajax');

// PAGINACIÓN DOCTORES
function cargar_doctores_ajax()
{
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $args = array(
        'post_type'      => 'staff_medico',
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
        'posts_per_page' => 8,
        'paged'          => $paged
    );

    $query = new WP_Query($args);

    $html = '';

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            /* LOCAL */
            /* $image_html = '';
            $image_id   = get_field('imagen');
            if ($image_id) {
                $image_html = wp_get_attachment_image($image_id, 'full', false, ['class' => 'imagen']);
            } */

            /* SHARED */
            $image_html = '';
            $image_id = get_field('imagen');
            if ($image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'full'); // URL absoluta
                $image_html = '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '" class="imagen">';
            }

            $staff_page = get_page_by_path('staff-medico');
            $titulo_boton = $staff_page ? get_field('titulo_boton', $staff_page->ID) : '';

            // Especialidades (relación con CPT)
            $especialidades_html = '';
            $especialidades = get_field('especialidad');

            if ($especialidades) {
                if (is_array($especialidades)) {
                    $spans = [];
                    foreach ($especialidades as $esp_id) {
                        $spans[] = '<span class="pill">' . esc_html(get_the_title($esp_id)) . '</span>';
                    }
                    $especialidades_html = implode(' ', $spans);
                } else {
                    $especialidades_html = '<span class="pill">' . esc_html(get_the_title($especialidades)) . '</span>';
                }
            }

            // Documentos de los doctores
            $docs_html = '';
            if (have_rows('documentos')) {
                $docs = [];

                while (have_rows('documentos')) : the_row();
                    $nombre = get_sub_field('nombre_documento');
                    $numero = get_sub_field('n_documento');

                    if ($nombre && $numero) {
                        $docs[] = '<strong>' . esc_html($nombre) . ':</strong> ' . esc_html($numero);
                    }
                endwhile;

                if (!empty($docs)) {
                    $docs_html .= '<p class="documentos">' . implode(' / ', $docs) . '</p>';
                }
            }

            $html .= '<li class="card doctores">
                        <div class="contenido text-white">
                            ' . $image_html . '
                            <div class="bg-contenido">

                                <div class="pill-contenedor">
                                    ' . $especialidades_html . '
                                </div>

                                <div class="cuerpo">
                                    <h4>' . get_the_title() . '</h4>
                                    ' . $docs_html . '
                                </div>

                                <span class="titulo-btn">' . $titulo_boton . '</span>
                            </div>
                        </div>
                    </li>';
        endwhile;
    endif;

    wp_reset_postdata();

    wp_send_json([
        'html'     => $html,
        'maxPages' => $query->max_num_pages
    ]);
}

// Para usuarios logueados y no logueados
add_action('wp_ajax_nopriv_cargar_doctores', 'cargar_doctores_ajax');
add_action('wp_ajax_cargar_doctores', 'cargar_doctores_ajax');
