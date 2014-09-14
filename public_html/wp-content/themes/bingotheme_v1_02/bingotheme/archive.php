<?php
/**
 * The template for displaying archive pages.
 *
 * @package bingotheme
 */


get_header(); ?>

<section id="content" class="main-content">


<?php if (have_posts()) : ?>
        
                <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
                
                <?php if (is_category()) { ?>                                
                        <h1><?php echo single_cat_title(); ?></h1>
                        
                <?php } elseif( is_tag() ) { ?>
                        <h1>Posts Tagged: <?php single_tag_title(); ?></h1>
                        
                <?php } elseif (is_day()) { ?>
                        <h1>Archive for <?php echo get_the_date(); ?></h1>
                        
                <?php } elseif (is_month()) { ?>
                        <h1>Archive for <?php echo get_the_date( _x( 'F Y', 'monthly archives date format', 'pokernews' ) ) ?></h1>
                        
                <?php } elseif (is_year()) { ?>
                        <h1>Archive for <?php echo get_the_date( _x( 'Y', 'yearly archives date format', 'pokernews' ) ) ?></h1>
                        
                <?php } elseif (is_search()) { ?>
                        <h1>Search Results</h1>
                        
                <?php } elseif ( is_author() ) { ?>
                        <h1>Author Archive</h1>
                        
                <?php } elseif ( isset($_GET['paged'] ) && !empty( $_GET['paged']) ) { ?>
                        <h1>Blog Archives</h1>
                        
                <?php } ?>

	<?php while (have_posts()) : the_post(); ?>	
		
		<article <?php post_class(articleexcerpt) ?> id="post-<?php the_ID(); ?>">


			<?php include('includes/article-thumb.php'); ?>

			
			
			<h3><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>

			<?php if (!get_theme_option('bylines-hide-all')) {  include('includes/article-meta.php'); } ?>
					
			<?php the_excerpt();?>

		<p class="rmore"><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>">Read more</a></p>
		

		</article>

       <?php endwhile; ?> <?php else: ?>
	
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	
	<?php endif; ?>

<?php kriesi_pagination();?> 
          	
</section> <!--#content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>