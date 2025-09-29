<footer class="footer">
    <div class="bg-footer">
        <div class="contenedor contenido-footer text-white">

            <div class="column">
                <!-- Logo -->
                <?php
                $logo_footer = get_field('logo', 'informacion-general');
                if ($logo_footer):
                    echo wp_get_attachment_image($logo_footer, 'full', false, array('class' => 'logo-footer'));
                endif;
                ?>
            </div>

            <!-- TITULOS DE LOS ENLACES -->
            <?php $titulos = get_field('titulos__detalles', 'informacion-general'); ?>

            <!-- Enlaces -->
            <div class="column big-lh">
                <h4><?php echo esc_html($titulos['titulo_enlaces']); ?></h4>

                <!-- Navegacion -->
                <?php

                $args = array(
                    'theme_location' => 'menu-principal',
                    'container' => 'nav',
                    'container_class' => 'menu-principal'
                );

                wp_nav_menu($args);

                ?>
            </div>

            <!-- Legal -->
            <div class="column">
                <h4><?php echo esc_html($titulos['titulo_legal']); ?></h4>
            </div>

            <!-- Contactanos -->
            <div class="column">
                <h4>
                    <?php echo esc_html($titulos['contactanos']['titulo_contactanos']); ?>
                </h4>

                <div class="row mg-b-4">
                    <p class="small-lh">
                        <?php echo nl2br(esc_html($titulos['contactanos']['whatsapp_detalles']['descripcion'])); ?>
                    </p>

                    <a class="info"
                        href="<?php echo esc_url(get_field('enlace_whatsApp', 'informacion-general')); ?>"
                        target="_blank"
                        rel="noopener noreferrer">

                        <img class="icono"
                            src="<?php echo esc_url($titulos['contactanos']['whatsapp_detalles']['icono']['value']['url']); ?>"
                            alt="<?php echo esc_attr($titulos['contactanos']['whatsapp_detalles']['icono']['value']['alt']); ?>">

                        <p class="text-bold-big"><?php echo esc_html(get_field('telefono', 'informacion-general')); ?></p>
                    </a>
                </div>

                <div class="row">
                    <p class="small-lh">
                        <?php echo nl2br(esc_html($titulos['contactanos']['direccion_detalles']['descripcion'])); ?>
                    </p>

                    <a class="info"
                        href="<?php echo esc_url(get_field('enlace_google_maps', 'informacion-general')); ?>"
                        target="_blank"
                        rel="noopener noreferrer">

                        <img class="icono"
                            src="<?php echo esc_url($titulos['contactanos']['direccion_detalles']['icono']['value']['url']); ?>"
                            alt="<?php echo esc_attr($titulos['contactanos']['direccion_detalles']['icono']['value']['alt']); ?>">

                        <p class="text-bold-big"><?php echo esc_html(get_field('direccion', 'informacion-general')); ?></p>
                    </a>
                </div>
            </div>

            <!-- Redes Sociales -->
            <div class="column">
                <h4><?php echo esc_html($titulos['titulo_redes_sociales']); ?></h4>

                <div class="redes-sociales">
                    <?php
                    // Definimos las redes que tienes en ACF
                    $redes = ['facebook', 'linkedin', 'instagram', 'youtube', 'x'];

                    foreach ($redes as $red) {
                        $social = get_field($red, 'informacion-general');

                        if (!empty($social['url']) && !empty($social['icono']['value']['url'])): ?>
                            <a href="<?php echo esc_url($social['url']); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="red-social <?php echo esc_attr($red); ?>">
                                <img class="icono"
                                    src="<?php echo esc_url($social['icono']['value']['url']); ?>"
                                    alt="<?php echo esc_attr($social['icono']['value']['alt']); ?>">
                            </a>
                    <?php endif;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-sub-footer">
        <div class="contenedor contenido-sub-footer text-primary">
            <p><?php echo esc_html(get_field('Copyright', 'informacion-general')); ?></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>

</html>