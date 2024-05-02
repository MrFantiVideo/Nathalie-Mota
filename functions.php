<?php

/**
 * Import CSS
 */
function theme_enqueue_styles()
{
    wp_enqueue_style( 'styles', get_template_directory_uri() . '/assets/css/styles.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

/**
 * Import JS
 */
function theme_enqueue_scripts()
{
    wp_enqueue_script( 'scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), '1.0', true );
    wp_enqueue_script('theme-ajax-request', get_template_directory_uri() . '/assets/js/ajax-request.js', array('jquery'), null, true);
    wp_localize_script('theme-ajax-request', 'ajax', array('ajax-url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

/**
 * Personnalisation du logo.
 */
function theme_custom_logo()
{
    add_theme_support('custom-logo', array(
        'height'      => 28,
        'width'       => 216,
    ));
}
add_action('after_setup_theme', 'theme_custom_logo');

/**
 * Support des posts à images.
 */
add_theme_support('post-thumbnails');

/**
 * Création des photos (Menu Photos)
 */
function create_pictures_list()
{
    $labels = array(
        'name' => 'Photos',
        'singular_name' => 'Photo',
        'menu_name' => 'Photos',
        'name_admin_bar' => 'Photo',
        'add_new' => 'Ajouter nouvelle',
        'add_new_item' => 'Ajouter nouvelle Photo',
        'new_item' => 'Nouvelle Photo',
        'edit_item' => 'Éditer Photo',
        'view_item' => 'Voir Photo',
        'all_items' => 'Toutes les Photos',
        'search_items' => 'Rechercher Photos',
        'not_found' => 'Aucune photo trouvée.',
        'not_found_in_trash' => 'Aucune photo dans la corbeille.',
    );
    $args = array(
        'menu_icon' => 'dashicons-format-image',
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'photos'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 6,
        'supports' => array('title', 'thumbnail'),
        'taxonomies' => array('category', 'format', 'type')
    );
    register_post_type('photos', $args);
}
add_action('init', 'create_pictures_list');

/**
 * Paramètres des photos (Menu Photos)
 */
function create_pictures_taxonomies()
{
    // Formats
    $labels_format = array
    (
        'name' => 'Formats',
        'singular_name' => 'Format',
        'search_items' => 'Rechercher des Formats',
        'all_items' => 'Tous les Formats',
        'edit_item' => 'Éditer le Format',
        'update_item' => 'Mettre à jour le Format',
        'add_new_item' => 'Ajouter un nouveau Format',
        'new_item_name' => 'Nom du nouveau Format',
        'menu_name' => 'Formats',
    );
    $args_format = array
    (
        'hierarchical' => true,
        'labels' => $labels_format,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'format'),
    );
    register_taxonomy('format', array('photos'), $args_format);

    // Types
    $labels_type = array(
        'name' => 'Types',
        'singular_name' => 'Type',
        'search_items' => 'Rechercher des Types',
        'all_items' => 'Tous les Types',
        'edit_item' => 'Éditer le Type',
        'update_item' => 'Mettre à jour le Type',
        'add_new_item' => 'Ajouter un nouveau Type',
        'new_item_name' => 'Nom du nouveau Type',
        'menu_name' => 'Types',
    );
    $args_type = array(
        'hierarchical' => true,
        'labels' => $labels_type,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'type'),
    );
    register_taxonomy('type', array('photos'), $args_type);
}
add_action('init', 'create_pictures_taxonomies');

/**
 * Photos - Création / Mise en page des colonnes.
 */
function add_columns_names($columns)
{
    // Placement au début de la colonne "Date".
    $date_column = $columns['date'];
    unset($columns['date']);

    // Ajout des autres colonnes.
    $columns['image'] = 'Image';
    $columns['reference'] = 'Référence';
    $columns['date'] = $date_column;
    return $columns;
}
add_filter('manage_photos_posts_columns', 'add_columns_names');

/**
 * Photos - Ajout du contenue de chaque post dans les colonnes.
 */
function add_content_in_columns($column, $post_id)
{
    switch ($column)
    {
        case 'image':
            $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'thumbnail', true);
            if ($thumbnail_url)
            {
                echo '<img src="' . esc_url($thumbnail_url[0]) . '" alt="" style="max-width: 100px; height: auto;" />';
            }
            else
            {
                echo 'Aucune image';
            }
            break;
        case 'reference':
            $reference = get_post_meta($post_id, 'reference', true);
            echo esc_html($reference);
            break;
        case 'format':
            $format_terms = get_the_terms($post_id, 'format');
            if (!empty($format_terms) && !is_wp_error($format_terms))
            {
                foreach ($format_terms as $term)
                {
                    echo esc_html($term->name) . ', ';
                }
            }
            else
            {
                echo 'Aucun format';
            }
            break;
        case 'type':
            $type_terms = get_the_terms($post_id, 'type');
            if (!empty($type_terms) && !is_wp_error($type_terms))
            {
                foreach ($type_terms as $term)
                {
                    echo esc_html($term->name) . ', ';
                }
            }
            else
            {
                echo 'Aucun type';
            }
            break;
    }
}
add_action('manage_photos_posts_custom_column', 'add_content_in_columns', 10, 2);

/**
 * Photos - Page de modification - Ajout du thumbnail
 */

function custom_quick_edit_fields($column_name, $post_type)
{
    if ('thumbnail' == $column_name) {
        ?>
        <fieldset class="inline-edit-col-right">
            <div class="inline-edit-col">
                <label>
                    <span class="title">Image à la une</span>
                    <span class="input-text-wrap"><input type="text" name="_thumbnail_id" value=""></span>
                </label>
            </div>
        </fieldset>
        <?php
    }
}
add_action('quick_edit_custom_box', 'custom_quick_edit_fields', 10, 2);

function save_custom_quick_edit_data($post_id, $post)
{
    if (isset($_REQUEST['_thumbnail_id']))
    {
        $thumbnail_id = intval($_REQUEST['_thumbnail_id']);
        if ($thumbnail_id > 0)
        {
            set_post_thumbnail($post_id, $thumbnail_id);
        }
    }
}
add_action('save_post', 'save_custom_quick_edit_data', 10, 2);


function show_meta_box_photos($post)
{
    wp_nonce_field(basename(__FILE__), 'photos_nonce');
    $reference = get_post_meta($post->ID, 'reference', true);
    ?>
    <p>
        <label for="reference">ID:</label>
        <input type="text" id="reference" name="reference" value="<?php echo esc_attr($reference); ?>" />
    </p>
    <?php
}

function save_meta_box_photos($post_id)
{
    if (!isset($_POST['photos_nonce']) || !wp_verify_nonce($_POST['photos_nonce'], basename(__FILE__)))
    {
        return $post_id;
    }
    if ('photos' == $_POST['post_type'])
    {
        if (!current_user_can('edit_post', $post_id))
        {
            return $post_id;
        }
    }
    if (isset($_POST['reference']))
    {
        update_post_meta($post_id, 'reference', sanitize_text_field($_POST['reference']));
    }
    if (isset($_POST['format']))
    {
        update_post_meta($post_id, 'format', sanitize_text_field($_POST['format']));
    }
    if (isset($_POST['type']))
    {
        update_post_meta($post_id, 'type', sanitize_text_field($_POST['type']));
    }
}
add_action('save_post', 'save_meta_box_photos');

function create_meta_box_photos()
{
    add_meta_box(
        'photos_reference',
        'Référence',
        'show_meta_box_photos',
        'photos',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'create_meta_box_photos');

/*
 *  Menu Personnalisation
 */
function theme_custom_menu($wp_customize)
{
    $wp_customize->add_panel('custom_panel', array
    (
        'title' => 'Personnalisation',
        'priority' => 120,
    ));

    // Section pour la bannière et le titre
    $wp_customize->add_section('home_hero_section', array
    (
        'title' => 'Accueil',
        'panel' => 'custom_panel',
    ));

    // Titre
    $wp_customize->add_setting('home_hero_title', array
    (
        'default' => 'PHOTOGRAPHE EVENT',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('home_hero_title_control', array
    (
        'label' => 'Titre',
        'section' => 'home_hero_section',
        'settings' => 'home_hero_title',
        'type' => 'text',
    ));

    // Bannière
    $wp_customize->add_setting('home_hero_image', array
    (
        'default' => get_template_directory_uri() . '/assets/img/nathalie-11.jpeg',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'home_hero_image_control', array
    (
        'label' => 'Bannière',
        'section' => 'home_hero_section',
        'settings' => 'home_hero_image',
    )));
}
add_action('customize_register', 'theme_custom_menu');

/*
 *  Galerie Photos
 */
function filter_photos()
{
    $category = $_POST['category'];
    $format = $_POST['format'];
    $order = $_POST['order'];
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $per_page = 8;
    $args =
    [
        'post_type' => 'photos',
        'posts_per_page' => $per_page,
        'offset' => $offset,
        'order' => $order == 'recent' ? 'DESC' : 'ASC',
        'orderby' => 'date',
        'tax_query' => []
    ];
    if (!empty($category))
    {
        $args['tax_query'][] =
        [
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => explode(',', $category)
        ];
    }
    if (!empty($format))
    {
        $args['tax_query'][] =
        [
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => explode(',', $format)
        ];
    }
    $query = new WP_Query($args);
    if($query->have_posts())
    {
        while($query->have_posts()) : $query->the_post();
        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        $image_alt = get_post_meta(get_post_thumbnail_id(get_the_ID()), '_wp_attachment_image_alt', true);
        $image_ref = get_post_meta(get_the_ID(), 'reference', true);
        $image_cats = get_the_terms(get_the_ID(), 'category');
        $cat_names = array_map(function($cat) { return $cat->name; }, (array) $image_cats);
        $cat_list = join(', ', $cat_names);
        echo '<div class="gallery-item"';
        echo ' data-post-id="' . get_the_ID() . '"';
        echo ' data-image-url="' . esc_url($image_url) . '"';
        echo ' data-image-alt="' . esc_attr($image_alt) . '"';
        echo ' data-image-ref="' . esc_html($image_ref) . '"';
        echo ' data-image-cat="' . esc_html($cat_list) . '">';
        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '">';
        echo '<div class="hover-elements">';
        echo '<div class="icon fullscreen">⛶</div>';
        echo '<a href="' . get_permalink(get_the_ID()) . '" class="icon eye">👁</a>';
        echo '<div class="text title">' . esc_html($image_ref) . '</div>';
        echo '<div class="text category">' . esc_html($cat_list) . '</div>';
        echo '</div>';
        echo '</div>';
        endwhile;
        wp_reset_postdata();
    }
    else
    {
        echo '<p>Aucun résultat.</p>';
    }
    if($query->found_posts > ($offset + $per_page))
    {
        echo '<button class="btn more">Charger plus</button>';
    }
    die();
}
add_action('wp_ajax_filter_photos', 'filter_photos');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos');

/*
 *  Lightbox
 */
function handle_load_image_request()
{
    $post_id = intval($_POST['post_id']);
    $direction = $_POST['direction'];
    $new_post_id = ($direction == 'next') ? $post_id - 1 : $post_id + 1;
    $image_details = get_post($new_post_id);
    $response = array
    (
        'image_url' => get_the_post_thumbnail_url($new_post_id),
        'image_alt' => get_post_meta($new_post_id, '_wp_attachment_image_alt', true),
        'post_id'   => $new_post_id,
        'image_ref' => get_post_meta($new_post_id, 'reference', true),
        'image_cat' => get_post_meta($new_post_id, 'category', true) 
    );
    wp_send_json($response);
}
add_action('wp_ajax_load_image', 'handle_load_image_request');
add_action('wp_ajax_nopriv_load_image', 'handle_load_image_request');