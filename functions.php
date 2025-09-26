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

            $image_html = '';
            $image_id = get_field('imagen');
            if ($image_id) {
                $image_html = wp_get_attachment_image($image_id, 'full', false, ['class' => 'imagen']);
            }

            $html .= '<li class="card">
                        <div class="contenido text-white">
                            ' . $image_html . '
                            <div class="bg-contenido">
                                <h3>' . get_the_title() . '</h3>
                                <p>' . get_field('descripcion') . '</p>
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
