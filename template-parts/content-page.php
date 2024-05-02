<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="page-content">
        <?php
            the_title('<h1 class="entry-title">', '</h1>');
            the_content();
        ?>
    </div>
</article>