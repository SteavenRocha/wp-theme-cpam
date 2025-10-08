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

// Ocultar "Entradas" del menú de administración
function quitar_menu_entradas()
{
    remove_menu_page('edit.php');
}

add_action('admin_menu', 'quitar_menu_entradas');

function cpam_scripts_styles()
{
    wp_enqueue_style('style', get_template_directory_uri() . '/assets/css/style.css', array(), '1.0.0');
    // wp_enqueue_style('style', get_stylesheet_uri(), array(), '1.0.0'); // estilos ubicado en la carpeta raiz
}

add_action('wp_enqueue_scripts', 'cpam_scripts_styles');

/* SWIPER */
function enqueue_swiper_assets()
{
    wp_enqueue_style('swiper-css', get_template_directory_uri() . '/assets/css/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', [], null, true);
}

add_action('wp_enqueue_scripts', 'enqueue_swiper_assets');

/* MICROMODAL */
function enqueue_modal_assets()
{
    wp_enqueue_script('micromodal', get_template_directory_uri() . '/assets/js/micromodal.min.js', [], null, true);
    wp_add_inline_script('micromodal', 'MicroModal.init();');
}
add_action('wp_enqueue_scripts', 'enqueue_modal_assets');

// PAGINACIÓN ESPECIALIDADES
function cargar_especialidades_ajax()
{
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $especialidad_id = isset($_POST['especialidad_id']) ? intval($_POST['especialidad_id']) : 0;

    $args = array(
        'post_type'      => 'especialidades',
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
        'posts_per_page' => 8,
        'paged'          => $paged
    );

    // Si se seleccionó una especialidad específica
    if ($especialidad_id) {
        $args['p'] = $especialidad_id;
    }

    $query = new WP_Query($args);
    $html = '';

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            /* LOCAL */
            $image_html = '';
            $image_id = get_field('imagen');
            if ($image_id) {
                $image_html = wp_get_attachment_image($image_id, 'full', false, ['class' => 'imagen']);
            }

            /* SHARED */
            /* $image_html = '';
            $image_id = get_field('imagen');
            if ($image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'full'); // URL absoluta
                $image_html = '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '" class="imagen">';
            } */

            $staff_page = get_page_by_path('staff-medico');
            $staff_url = $staff_page ? get_permalink($staff_page->ID) : '#';

            $especialidades_page = get_page_by_path('especialidades');
            $boton = $especialidades_page ? get_field('boton_especialidades', $especialidades_page->ID) : '';
            $boton_html = '';

            if ($boton) {
                $boton_html = '<a href="' . esc_url($staff_url . '?especialidad=' . get_the_ID()) . '" 
                           class="btn ' . esc_attr($boton['estilo']) . '" 
                           data-especialidad-id="' . get_the_ID() . '">
                            ' . esc_html($boton['texto']) . '
                        </a>';
            }

            $html .= '<li class="card">
                        <div class="contenido text-white">
                            ' . $image_html . '
                            <div class="bg-contenido">
                                <div>
                                    <h3>' . get_the_title() . '</h3>
                                    ' . get_field('descripcion') . '
                                </div>
                                ' . $boton_html . '
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
    $nombre = isset($_POST['nombre']) ? sanitize_text_field($_POST['nombre']) : '';
    $especialidad = isset($_POST['especialidad']) ? sanitize_text_field($_POST['especialidad']) : '';

    $meta_query = array();
    $tax_query = array();

    // Filtro por nombre (búsqueda en título)
    $search = '';
    if (!empty($nombre)) {
        $search = $nombre;
    }

    // Filtro por especialidad (relación con CPT)
    if (!empty($especialidad)) {
        $tax_query[] = array(
            'relation' => 'OR',
            array(
                'key' => 'especialidad', // campo ACF (relación)
                'value' => '"' . $especialidad . '"',
                'compare' => 'LIKE'
            ),
        );
    }

    $args = array(
        'post_type'      => 'staff_medico',
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
        's'              => $search,
        'posts_per_page' => 8,
        'paged'          => $paged,
        'meta_query'     => $meta_query,
    );

    if (!empty($tax_query)) {
        $args['meta_query'] = $tax_query;
    }

    $query = new WP_Query($args);
    $html = '';

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            $image_html = '';
            $image_url = '';
            $image_alt  = '';
            $image_id   = get_field('imagen');
            if ($image_id) {
                $image_html = wp_get_attachment_image($image_id, 'full', false, ['class' => 'imagen']);
                $image_url  = wp_get_attachment_image_url($image_id, 'full');
                $image_alt  = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            }

            $staff_page = get_page_by_path('staff-medico');
            $titulo_boton = $staff_page ? get_field('titulo_boton', $staff_page->ID) : '';

            // Especialidades (relación con CPT)
            $especialidades_html = '';
            $especialidades = get_field('especialidad');
            if ($especialidades) {
                $spans = [];
                foreach ((array)$especialidades as $esp_id) {
                    $spans[] = '<span class="pill">' . esc_html(get_the_title($esp_id)) . '</span>';
                }
                $especialidades_html = implode(' ', $spans);
            }

            // Documentos
            $docs_html = '';
            if (have_rows('documentos')) {
                $docs = [];
                while (have_rows('documentos')) : the_row();
                    $nombre_doc = get_sub_field('nombre_documento');
                    $numero = get_sub_field('n_documento');
                    if ($nombre_doc && $numero) {
                        $docs[] = '<strong>' . esc_html($nombre_doc) . ':</strong> ' . esc_html($numero);
                    }
                endwhile;
                if (!empty($docs)) {
                    $docs_html .= '<p class="documentos">' . implode(' / ', $docs) . '</p>';
                }
            }

            $detalles = get_field('detalles');
            $data_detalles = !empty($detalles) ? esc_attr(json_encode($detalles)) : '[]';

            $html .= '<li class="card doctores">
                        <div class="contenido text-white">
                            ' . $image_html . '
                            <div class="bg-contenido">
                                <div class="pill-contenedor">' . $especialidades_html . '</div>
                                <div class="cuerpo">
                                    <h4>' . get_the_title() . '</h4>
                                    ' . $docs_html . '
                                </div>
                                <span class="titulo-btn"
                                    data-nombre="' . esc_attr(get_the_title()) . '"
                                    data-especialidades="' . esc_attr($especialidades_html) . '"
                                    data-documentos="' . esc_attr($docs_html) . '"
                                    data-imagen="' . esc_url($image_url) . '"
                                    data-alt="' . esc_attr($image_alt) . '"
                                    data-detalles="' . $data_detalles . '"
                                >' . esc_html($titulo_boton) . '</span>
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