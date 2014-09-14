<?php
/*
Template Name: Full Page Template
*/
?>
<?php get_header(); ?>

<section id="content" class="main-content full">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry">		
			<?php the_content(); ?>

			

		</div>


    	</article><!-- #post -->
		
	<?php endwhile; endif; ?><?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>

	<?php kriesi_pagination();?> 	
	
</section> <!--#content-->

<?php get_footer(); ?>