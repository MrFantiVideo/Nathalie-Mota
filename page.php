<?php
    get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
            get_template_part( 'template-parts/content', 'page' );
        ?>
    </main>
</div>

<?php
    get_footer();
?>