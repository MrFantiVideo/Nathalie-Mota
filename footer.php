
<footer>

<!-- Barre de navigation (Bas de page) -->
<?php
    wp_nav_menu(array
    (
        'menu' => 'Bas de page',
        'container' => 'nav',
        'container_class' => 'footer-container',
        'menu_class' => 'footer-container-list',
    ));
?>

<!-- Formulaire de contact (Modale) -->
<div id="content-contact" style="display: none;">
    <?php
        get_template_part('template-parts/content', 'contact');
    ?>
</div>

<!-- Lightbox -->
<div id="content-lightbox" style="display: none;">
    <?php
        get_template_part('template-parts/content', 'lightbox');
    ?>
</div>

</footer>

<?php
    wp_footer();
?>

</body>
</html>