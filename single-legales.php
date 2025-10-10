<?php get_header(); ?>

<main class="single-legales contenedor seccion pd-l-1 p-t">

    <section class="contenido-principal">
        <?php
        while (have_posts()): the_post();
        ?>
            <div class="contenido_WYSIWYG">
                <?php
                the_content();
                ?>
            </div>
        <?php
        endwhile;
        ?>
    </section>
</main>

<?php get_footer(); ?>