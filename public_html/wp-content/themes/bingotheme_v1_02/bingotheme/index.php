<?php
/**
 * The template index page.
 *
 * @package bingo theme
 */

get_header(); ?>

<section id="content" class="main-content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


		<div class="articleexcerpt">

			<?php include('includes/article-thumb.php'); ?>			

			<h3><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
			<?php if (!get_theme_option('bylines-hide-all')) {  include('includes/article-meta.php'); } ?>
			
		
			<?php the_excerpt();?>


		</div>


    	</article><!-- #post -->
		
	<?php endwhile; endif; ?><?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>

	<?php kriesi_pagination();?> 
	
</section> <!--#content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>



