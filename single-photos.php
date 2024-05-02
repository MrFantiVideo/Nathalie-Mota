<?php
    get_header();

    while(have_posts()) :
        the_post();
        $current_post_id = get_the_ID();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<section class="photos-container">
    <div class="entry-content">
        <div class="picture-container">
            <div class="picture-left-side">
                <h2><?php the_title(); ?></h2>
                <h3 id="photoReference" data-reference="<?php echo esc_html(get_post_meta(get_the_ID(), 'reference', true)); ?>">Référence : <?php echo esc_html(get_post_meta(get_the_ID(), 'reference', true)); ?></h3>
                <h3>Catégorie : <?php echo esc_html(strip_tags(get_the_category_list(', '))); ?></h3>
                <h3>Format : <?php echo esc_html(strip_tags(get_the_term_list(get_the_ID(), 'format', '', ', '))); ?></h3>
                <h3>Type : <?php echo esc_html(strip_tags(get_the_term_list(get_the_ID(), 'type', '', ', '))); ?></h3>
                <h3>Année : <?php echo get_the_date('Y'); ?></h3>
            </div>
            <div class="picture-right-side">
                <?php
                    if (has_post_thumbnail())
                    {
                        the_post_thumbnail('full');
                    }
                ?>
            </div>
        </div>
        <div class="picture-bottom-side">
            <div class="picture-contact-info">
                <p>Cette photo vous intéresse ?</p>
                <button class="btn contact-form-btn">Contact</button>
            </div>
            <?php
                $current_post_date = get_the_date('Y-m-d H:i:s');
                $next_image_args = array(
                    'post_type' => 'photos',
                    'posts_per_page' => 1,
                    'orderby' => 'date',
                    'order' => 'ASC',
                    'date_query' => array(
                        array(
                            'after' => $current_post_date,
                            'inclusive' => false
                        ),
                    ),
                    'post__not_in' => array($current_post_id)
                );

                $prev_image_args = array(
                    'post_type' => 'photos',
                    'posts_per_page' => 1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'date_query' => array(
                        array(
                            'before' => $current_post_date,
                            'inclusive' => false
                        ),
                    ),
                    'post__not_in' => array($current_post_id)
                );

                $next_image = get_posts($next_image_args);
                $prev_image = get_posts($prev_image_args);
            ?>
            <div class="next-image-info">
                <?php if (!empty($next_image)): ?>
                    <a href="<?php echo get_permalink($next_image[0]->ID); ?>">
                        <img class="next-image-preview" href="<?php echo get_permalink($next_image[0]->ID); ?>" src="<?php echo get_the_post_thumbnail_url($next_image[0]->ID, 'thumbnail'); ?>" alt="Image suivante">
                    </a>
                <?php else: ?>
                    <a href="<?php echo get_permalink($current_post_id); ?>">
                        <img class="next-image-preview" href="<?php echo get_permalink($current_post_id); ?>" src="<?php echo get_the_post_thumbnail_url($current_post_id, 'thumbnail'); ?>" alt="Image actuelle">
                    </a>
                <?php endif; ?>
                <div class="next-image-buttons">
                <?php if (!empty($prev_image)): ?>
                    <a href="<?php echo get_permalink($prev_image[0]->ID); ?>">
                    <button class="arrow-button left"></button>
                    </a>
                <?php endif; ?>
                <?php if (!empty($next_image)): ?>
                    <a href="<?php echo get_permalink($next_image[0]->ID); ?>">
                    <button class="arrow-button right"></button>
                    </a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
        $current_post_id = get_the_ID();
        $categories = wp_get_post_categories($current_post_id, array('fields' => 'ids'));
        $args = array(
            'post_type' => 'photos', 
            'posts_per_page' => 2,
            'orderby' => 'rand',
            'post__not_in' => array($current_post_id),
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $categories,
                ),
            ),
        );
        $related_photos = get_posts($args);
        
        if (!empty($related_photos)) :
    ?>

    <div class="related-images">
        <h3>Vous aimerez aussi</h3>
        <div class="gallery">
            <?php foreach ($related_photos as $photo): ?>
                <?php if (has_post_thumbnail($photo->ID)): ?>
                    <?php
                        $image_url = get_the_post_thumbnail_url($photo->ID, 'large');
                        $image_alt = get_post_meta(get_post_thumbnail_id($photo->ID), '_wp_attachment_image_alt', true);
                        $image_ref = get_post_meta($photo->ID, 'reference', true);
                        $image_cats = get_the_terms($photo->ID, 'category');
                        $cat_names = array_map(function($cat) { return $cat->name; }, (array) $image_cats);
                        $cat_list = join(', ', $cat_names);
                    ?>
                    <div class="gallery-item"
                        data-post-id="<?php echo $photo->ID; ?>"
                        data-image-url="<?php echo esc_url($image_url); ?>"
                        data-image-alt="<?php echo esc_attr($image_alt); ?>"
                        data-image-ref="<?php echo esc_html($image_ref); ?>"
                        data-image-cat="<?php echo esc_html($cat_list); ?>">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                        <div class="hover-elements">
                            <div class="icon fullscreen">⛶</div>
                            <a href="<?php echo get_permalink($photo->ID); ?>" class="icon eye">👁</a>
                            <div class="text title"><?php echo esc_html($image_ref); ?></div>
                            <div class="text category"><?php echo esc_html($cat_list); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

</article>
<?php
    endif;
?>

<?php endwhile;
    get_footer();
?>