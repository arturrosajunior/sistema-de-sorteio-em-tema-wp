<?php
/**
 * site functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package site
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'site_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function site_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on site, use a find and replace
		 * to change 'site' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'site', get_template_directory() . '/languages' );

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

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'site' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'site_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'site_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function site_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'site_content_width', 640 );
}
add_action( 'after_setup_theme', 'site_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function site_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'site' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'site' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'site_widgets_init' );

/**
 * Enqueue scripts and styles.
 */



function get_rede_social(){
	$cpt_rede_social = new WP_Query(array('post_type' => 'rede_social'));
	while($cpt_rede_social->have_posts()) : $cpt_rede_social->the_post();
		$_title_rede_social = get_the_title();
		$_icon_rede_social  = get_field('icone_rede_social');
		$_link_rede_social  = get_field('link_rede_sicial');
		$_perfis .= '<li><a href="'.$_link_rede_social.'" target="_blank"><i class="'.$_icon_rede_social.' fa-lg"></i></a></li>';
	endwhile; 
	wp_reset_query();
	return $_perfis;
}


function get_slide(){
	$i=0;
    $cpt_slides = new WP_Query(array('post_type' => 'slide', 'posts_per_page'=>-1));
    while($cpt_slides->have_posts()) : $cpt_slides->the_post();
        $active = ($i==0)?'active':'';
        $cpt_slider_img = get_field('imagem');
        $cpt_text_banner = get_field('texto_banner');
		$item .= '<div class="carousel-item '.$active.'"> 
		<img src="'.$cpt_slider_img.'" alt="'.get_the_title().'" class="img-fluid d-block w-100"> </div> ';
        $i++;
    endwhile;
    wp_reset_query();
    if($item!=''){
		
	$carousel = '<div id="carousel-slide_head" class="carousel slide" data-ride="carousel"> 
	<div class="carousel-inner" role="listbox">'.$item.'</div>
	</div> ';
    }
    return $carousel;
}   


function site_scripts() {
	wp_enqueue_style( 'site-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'site-style', 'rtl', 'replace' );

	wp_enqueue_script( 'site-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'site_scripts' );

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

