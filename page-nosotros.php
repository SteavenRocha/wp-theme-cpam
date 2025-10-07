<?php get_header(); ?>

<?php
// Query CPT Noticias
$args = array(
    'post_type'      => 'noticias',
    'post_status'    => 'publish',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$query = new WP_Query($args);

?>

<main class="seccion pb-0 nosotros p-t">

    <section class="contenedor">
        <?php
        $imagen = get_field('imagen_hero');
        if ($imagen):
            echo wp_get_attachment_image($imagen, 'full', false, array('class' => 'imagen-hero'));
        endif;
        ?>
    </section>

    <section class="contenedor cantainer historia seccion-pg-t">
        <div class="contenido text-primary">
            <?php if (get_field('titulo_historia')) { ?>
                <h2 class="mg-b-4 tiny-lh"><?php the_field('titulo_historia'); ?></h2>
            <?php } ?>

            <?php if (get_field('descripcion_historia')) { ?>
                <p><?php the_field('descripcion_historia'); ?></p>
            <?php } ?>
        </div>

        <?php
        $historia_img = get_field('imagen_historia');
        if ($historia_img):
            echo wp_get_attachment_image($historia_img, 'full', false, array('class' => 'side-img'));
        endif;
        ?>
    </section>

    <section class="contenedor cantainer seccion-pg-t">
        <div class="contenido text-primary mision">
            <?php if (get_field('titulo_mision')) { ?>
                <h2 class="mg-b-4 tiny-lh"><?php the_field('titulo_mision'); ?></h2>
            <?php } ?>

            <?php if (get_field('descripcion_mision')) { ?>
                <p><?php the_field('descripcion_mision'); ?></p>
            <?php } ?>
        </div>

        <div class="contenido text-primary vision">
            <?php if (get_field('titulo_vision')) { ?>
                <h2 class="mg-b-4 tiny-lh"><?php the_field('titulo_vision'); ?></h2>
            <?php } ?>

            <?php if (get_field('descripcion_vision')) { ?>
                <p><?php the_field('descripcion_vision'); ?></p>
            <?php } ?>
        </div>

    </section>

    <section class="contenedor seccion-pg">
        <?php if (get_field('frase')) { ?>
            <h3 class="text-primary frase"><?php echo nl2br(esc_html(get_field('frase'))); ?></h3>
        <?php } ?>
    </section>

    <section class="bg-crema seccion-pg text-primary">
        <div class="noticias contenedor">
            <div class="w-7">
                <?php if (get_field('titulo_noticias')) { ?>
                    <h2 class="tiny-lh"><?php the_field('titulo_noticias'); ?></h2>
                <?php } ?>

                <?php if (get_field('descripcion_noticias')) { ?>
                    <p><?php the_field('descripcion_noticias'); ?></p>
                <?php } ?>
            </div>

            <?php
            if ($query->have_posts()): ?>
                <div class="swiper-container">
                    <div class="swiper noticias-swiper">
                        <div class="swiper-wrapper">
                            <?php while ($query->have_posts()): $query->the_post(); ?>
                                <a class="swiper-slide noticias-slide" href="<?php the_permalink(); ?>">

                                    <?php
                                    if (has_post_thumbnail()) {
                                        echo get_the_post_thumbnail(get_the_ID(), 'full', array('class' => 'imagen-slider'));
                                    } else {
                                        $fallback = get_template_directory_uri() . '/assets/img/img-fallback.jpg';
                                        echo '<img src="' . esc_url($fallback) . '" alt="Imagen por defecto" class="imagen-slider">';
                                    }
                                    ?>

                                    <div class="text-white">
                                        <div class="noticias-detalles">
                                            <h3 class="tiny-lh mg-b-05"><?php the_title(); ?></h3>
                                            <div class="meta-noticia">
                                                <span class="autor">por: <strong><?php the_author(); ?></strong></span> -
                                                <span class="fecha"><?php echo get_the_date('d M Y'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Controles Swiper -->
                    <?php
                    $icono_izquierda = get_field('icono_izquierda', 'informacion-general');
                    $icono_derecha = get_field('icono_derecha', 'informacion-general');
                    ?>

                    <div class="controles">
                        <div class="swiper-button-prev">
                            <?php
                            if ($icono_izquierda) : ?>
                                <img src="<?php echo esc_url($icono_izquierda); ?>" alt="" aria-hidden="true" class="icono">
                            <?php endif; ?>
                        </div>

                        <div class="swiper-button-next">
                            <?php
                            if ($icono_derecha) : ?>
                                <img src="<?php echo esc_url($icono_derecha); ?>" alt="" aria-hidden="true" class="icono">
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            <?php endif;

            wp_reset_postdata();
            ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>

<script>
    const swiper = new Swiper('.swiper', {
        loop: true,
        slidesPerView: 3,
        spaceBetween: 30,
        watchOverflow: false,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 25,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
        },
    });
</script>