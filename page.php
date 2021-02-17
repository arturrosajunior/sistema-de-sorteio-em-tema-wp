<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package site
 */

get_header();
?>

<main id="primary" class="site-main container p-5">
    <object data="http://promo.rensz.com.br/wp-content/uploads/2020/12/Rensz-Calcados-vale-brinde_NC.pdf"
        type="application/pdf">
        <embed src="http://promo.rensz.com.br/wp-content/uploads/2020/12/Rensz-Calcados-vale-brinde_NC.pdf"
            type="application/pdf" />
    </object>
<style>

	object{width: 100%;min-height: 100vh;}
</style>
    <?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );



		endwhile; // End of the loop.
		?>

</main><!-- #main -->
<style>
header.entry-header {
    display: none;
}

.entry-content * {
    margin: 1.5em 0 0;
    color: #000 !important;
}
</style>
<?php
get_footer();