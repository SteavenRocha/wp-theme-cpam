<?php get_header(); ?>

<main class="contenedor seccion p-t pd-l-1 error-404-container">

    <section class="bg-crema error-404">
        <?php
        $titulo_404 = get_field('titulo_404', 'informacion-general');
        $descripcion_404 = get_field('descripcion_404', 'informacion-general');
        $boton_404 = get_field('boton_404', 'informacion-general');
        ?>

        <div class="encabezados text-primary">
            <h1 class="titulo-404"><?php echo nl2br(esc_html($titulo_404)); ?></h1>
            <p class="descripcion-404"><?php echo nl2br(esc_html($descripcion_404)); ?></p>
        </div>

        <a href="<?php echo esc_url($boton_404['enlace_boton_404']); ?>"
            class="btn big-btn <?php echo esc_attr($boton_404['estilo_boton_404']); ?>">
            <?php echo esc_html($boton_404['texto_boton_404']); ?>
        </a>
    </section>
</main>

<?php get_footer(); ?>