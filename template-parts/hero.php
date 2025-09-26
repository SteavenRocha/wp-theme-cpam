<?php while (have_posts()): the_post(); ?>
    <section class="hero">
        <?php if (get_field('titulo')): ?>
            <h1><?php the_field('titulo'); ?></h1>
        <?php endif; ?>

        <?php if (get_field('descripcion')): ?>
            <p><?php the_field('descripcion'); ?></p>
        <?php endif; ?>

        <?php 
        $imagen = get_field('imagen');
        if ($imagen) {
            echo wp_get_attachment_image($imagen, 'full', false, array('class' => 'imagen-destacada'));
        }
        ?>
    </section>
<?php endwhile; ?>