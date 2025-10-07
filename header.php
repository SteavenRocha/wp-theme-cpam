<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--  <title>Document</title> -->
    <?php wp_head(); ?>
</head>

<body>
    <header class="header">
        <div class="contenedor barra-navegacion">
            <div class="logo">
                <!-- Logo -->
                <?php
                $logo_header = get_field('logo_header', 'informacion-general');
                if ($logo_header):
                    echo wp_get_attachment_image($logo_header, 'full', false, array('class' => 'logo-header'));
                endif;
                ?>
            </div>

            <!-- Botón hamburguesa -->
            <button class="menu-toggle" aria-label="Abrir menú">
                <span></span>
                <span></span>
                <span></span>
            </button>

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
    </header>

    <div class="menu-overlay"></div>