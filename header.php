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
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/LOGO_Y_VARIABLES-0001.png" alt="">
            </div>

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