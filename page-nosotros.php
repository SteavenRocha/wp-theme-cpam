<?php get_header(); ?>

<main class="seccion pb-0 nosotros">

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

    <section class="contenedor cantainer mision seccion-pg-t">
        <div class="contenido text-primary">
            <?php if (get_field('titulo_mision')) { ?>
                <h2 class="mg-b-4 tiny-lh"><?php the_field('titulo_mision'); ?></h2>
            <?php } ?>

            <?php if (get_field('descripcion_mision')) { ?>
                <p><?php the_field('descripcion_mision'); ?></p>
            <?php } ?>
        </div>

        <?php
        $historia_img = get_field('imagen_mision');
        if ($historia_img):
            echo wp_get_attachment_image($historia_img, 'full', false, array('class' => 'side-img'));
        endif;
        ?>
    </section>

    <section class="contenedor cantainer vision seccion-pg-t">
        <div class="contenido text-primary">
            <?php if (get_field('titulo_vision')) { ?>
                <h2 class="mg-b-4 tiny-lh"><?php the_field('titulo_vision'); ?></h2>
            <?php } ?>

            <?php if (get_field('descripcion_vision')) { ?>
                <p><?php the_field('descripcion_vision'); ?></p>
            <?php } ?>
        </div>

        <?php
        $historia_img = get_field('imagen_vision');
        if ($historia_img):
            echo wp_get_attachment_image($historia_img, 'full', false, array('class' => 'side-img'));
        endif;
        ?>
    </section>

    <section class="contenedor seccion-pg">
        <?php if (get_field('frase')) { ?>
            <h3 class="text-primary frase"><?php echo nl2br(esc_html(get_field('frase'))); ?></h3>
        <?php } ?>
    </section>

    <section class="bg-crema seccion-pg text-primary">
        <div class="contenedor">
            <?php if (get_field('titulo_noticias')) { ?>
                <h2 class="tiny-lh"><?php the_field('titulo_noticias'); ?></h2>
            <?php } ?>

            <?php if (get_field('descripcion_noticias')) { ?>
                <p><?php the_field('descripcion_noticias'); ?></p>
            <?php } ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>