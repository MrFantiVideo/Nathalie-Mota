<?php
    get_header();
?>

<section class="home-hero">
    <img src="<?php echo get_theme_mod('home_hero_image', get_template_directory_uri() . '/assets/img/nathalie-11.jpeg'); ?>" alt="Bannière">
    <div class="title-container">
        <h1><?php echo get_theme_mod('home_hero_title', 'PHOTOGRAPHE EVENT'); ?></h1>
    </div>
</section>

<section class="home-photos">
    <div class="filters">
        <div class="taxonomies">
            <div class="dropdown category">
                <button class="dropdown-btn">Catégories<i></i></button>
                <?php
                    function display_categories($parent_id = 0, $level = 0)
                    {
                        $args = array(
                            'parent' => $parent_id,
                            'hide_empty' => false,
                            'exclude' => array(1)
                        );
                        $categories = get_categories($args);
                        if (count($categories) > 0)
                        {
                            echo '<div class="dropdown-container' . ($level > 0 ? '' : ' main') . '">';
                            foreach ($categories as $category)
                            {
                                $child_categories = get_categories(array('parent' => $category->term_id, 'hide_empty' => false));
                                if(count($child_categories) > 0)
                                {
                                    echo '<button class="dropdown-btn">' . $category->name . '<i></i></button>';
                                }
                                else
                                {
                                    echo '<a data-category="' . esc_attr($category->slug) . '">' . $category->name . '</a>';
                                }
                                display_categories($category->term_id, $level + 1);
                            }
                            echo '</div>';
                        }
                    }
                    display_categories();
                ?>
            </div>
            <div class="dropdown format">
                <button class="dropdown-btn">Formats<i></i></button>
                <?php
                    function display_formats($parent_id = 0, $level = 0)
                    {
                        $args = array(
                            'taxonomy' => 'format',
                            'parent' => $parent_id,
                            'hide_empty' => false,
                        );
                        $formats = get_terms($args);
                        if (count($formats) > 0)
                        {
                            echo '<div class="dropdown-container' . ($level > 0 ? '' : ' main') . '">';
                            foreach ($formats as $format)
                            {
                                $child_formats = get_terms(array('taxonomy' => 'format', 'parent' => $format->term_id, 'hide_empty' => false));
                                if(count($child_formats) > 0)
                                {
                                    echo '<button class="dropdown-btn">' . $format->name . '<i></i></button>';
                                }
                                else
                                {
                                    echo '<a data-format="' . esc_attr($format->slug) . '">' . $format->name . '</a>';
                                }
                                display_formats($format->term_id, $level + 1);
                            }
                            echo '</div>';
                        }
                    }
                    display_formats();
                ?>
            </div>
        </div>
        <div class="dropdown date">
            <button class="dropdown-btn">Trier par<i></i></button>
            <div class="dropdown-container main">
                <a data-sort="recent">À partir des plus récentes</a>
                <a data-sort="old">À partir des plus anciennes</a>
            </div>
        </div>
    </div>

    <?php
        $all_photos_query = new WP_Query(
        [
            'post_type' => 'photos',
            'posts_per_page' => -1,
            'order' => 'DESC',
            'orderby' => 'date'
        ]);
        $all_photos = $all_photos_query->posts;
    ?>

    <div class="gallery"></div>

</section>

<?php
    get_footer();
?>