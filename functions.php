<?php
/**
 * handicraft functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Handicraft
 */

if ( ! function_exists( 'handicraft_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function handicraft_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on handicraft, use a find and replace
		 * to change 'handicraft' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'handicraft', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'handicraft-post-thumbnail', 850, 800 );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Header', 'handicraft' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'handicraft_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => get_template_directory_uri() . '/assets/images/papertexture.jpg',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		add_theme_support( 'gutenberg', array(
			'wide-images' => true,
			'colors'      => array(
				'#eaa553',
				'#4cb0c1',
				'#e06e53',
				'#9dbc71',
				'#ffffff',
			),
		) );
	}
endif;
add_action( 'after_setup_theme', 'handicraft_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function handicraft_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'handicraft_content_width', 1100 );
}
add_action( 'after_setup_theme', 'handicraft_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function handicraft_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widgets', 'handicraft' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'handicraft_widgets_init' );


/**
 * Register Google Fonts
 */
function handicraft_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Zilla Slab, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$zilla = esc_html_x( 'on', 'Zilla Slab font: on or off', 'handicraft' );
	
	if ( 'off' !== $zilla ) {
		$font_families = array();
		$font_families[] = 'Zilla Slab:400,700';
		
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
	
		$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );
	}
	
	return $fonts_url;

}

/** 
 * Gutenberg Editor Styles 
 */

function handicraft_editor_styles() {
	wp_enqueue_style( 'handicraft-editor-style', get_template_directory_uri() . '/assets/stylesheets/editor-style.css');
	wp_enqueue_style( 'handicraft-zilla-slab', handicraft_fonts_url(), array(), null );
}
add_action( 'enqueue_block_editor_assets', 'handicraft_editor_styles' );

/**
 * Enqueue scripts and styles.
 */
function handicraft_scripts() {
	wp_enqueue_style( 'handicraft-style', get_stylesheet_uri() );

	wp_enqueue_style( 'handicraft-block-style', get_template_directory_uri() . '/assets/stylesheets/blocks.css' );

	wp_enqueue_script( 'handicraft-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'handicraft-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_style( 'handicraft-zilla-slab', handicraft_fonts_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'handicraft_scripts' );

/**
 * Return early if Jetpack Author Bio is not available.
 */
function handicraft_author_bio() {
	if ( ! function_exists( 'jetpack_author_bio' ) ) {
		get_template_part( 'template-parts/content', 'author' );
	} else {
		jetpack_author_bio();
	}
}

/**
 * Return early if Social Menu is not available.
 */
function handicraft_social_menu() {
	if ( ! function_exists( 'jetpack_social_menu' ) ) {
		return;
	} else {
		jetpack_social_menu();
	}
}

/**
* Custom function to get the URL of a post thumbnail;
* If Jetpack is not available, fall back to wp_get_attachment_image_src()
*/
function handicraft_get_attachment_image_src( $post_id, $post_thumbnail_id, $size ) {
	if ( function_exists( 'jetpack_featured_images_fallback_get_image_src' ) ) {
		return jetpack_featured_images_fallback_get_image_src( $post_id, $post_thumbnail_id, $size );
	} else {
		$attachment = wp_get_attachment_image_src( $post_thumbnail_id, $size ); // Attachment array
		$url = $attachment[0]; // Attachment URL
		return $url;
	}
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
