<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
        wp_head();
    ?>
</head>

<body>

<header>
    <section class="header">
        <div class="header-container">
            <!-- Logo avec redirection vers l'accueil -->
            <a class="logo" href="<?php echo home_url(); ?>">
            <?php
                $logo = has_custom_logo() ? wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full')[0] : get_template_directory_uri() . '/assets/img/logo.svg';
                echo '<img src="' . esc_url($logo) . '" alt="' . get_bloginfo('name') . '">';
            ?>
            </a>
            <!-- Bouton pour menu de navigation mobile -->
            <button id="header-nav-btn">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </button>
            <!-- Barre de navigation (Entête) -->
            <?php
                wp_nav_menu(array
                (
                    'menu' => 'Entête',
                    'container' => 'nav',
                    'container_class' => 'nav-container',
                    'menu_class' => 'nav-container-list',
                ));
            ?>
        </div>
    </section>
</header>

<!-- Barre de navigation mobile (Entête) -->
<section id="header-nav-overlay">
    <?php
        wp_nav_menu(array
        (
            'menu' => 'Entête',
            'container' => 'nav',
            'container_class' => 'nav-container-mobile',
            'menu_class' => 'nav-container-mobile-list',
        ));
    ?>
</section>