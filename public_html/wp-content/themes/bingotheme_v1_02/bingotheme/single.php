<?php
/**
 * The template for displaying all single posts.
 *
 * @package bingotheme
 */

get_header(); ?>


<section id="content" class="main-content">

	<?php while (have_posts()) : the_post(); ?>	
		
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">

			<h1 class="entry-title"><?php the_title(); ?></h1>
		
			<div class="meta">
				
			<?php if (!get_theme_option('bylines-hide-all')) {

			if (!get_theme_option('bylines-hide-date')) { the_date(); }
	
   			if (!get_theme_option('bylines-hide-category')) { ?> <?php if (get_gambling_option('gen-posted')) { echo get_gambling_option('gen-posted'); } else { ?>Posted in<?php } ?> <?php the_category(', '); } ?> <?php  if (!get_theme_option('bylines-hide-author')) { ?> <?php if (get_gambling_option('gen-by')) { echo get_gambling_option('gen-by'); } else { ?>by<?php } ?> <?php the_author_posts_link(); } ?>   

			<?php if (!get_theme_option('bylines-hide-comment')) { ?> &bull; <a href="<?php the_permalink(); ?>#comments">   <?php comments_number(); ?></a> <?php } ?> <?php edit_post_link('  (Edit) ', '', '');

			} ?>

			</div><!--.meta-->

			<div class="entry-content">

      				<?php the_content();?>

				<p class="tagging"><?php the_tags('Tagged with: ',' &bull; ','<br />'); ?></p>  

				<?php if (get_theme_option('auth-bio')) { include('includes/article-author.php');} ?>
				
				<?php comments_template(); // Get comments template ?>

			</div><!--.entry-content-->

		</article>

        <?php endwhile; ?>
          	
</section> <!--#content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>