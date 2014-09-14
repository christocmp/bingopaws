<?php get_header(); ?>

<section id="content" class="main-content">
<?php if (have_posts()) : ?>

		<h1><?php if (get_gambling_option('search-results')) { echo get_gambling_option('search-results'); } else { ?>Search Result For<?php } ?> -  <?php /* Search Count */ $allsearch = &new WP_Query("s=$s&showposts=-1"); $key = wp_specialchars($s, 1); $count = $allsearch->post_count; _e(''); _e('<span class="search-terms">'); echo $key; _e('</span>'); _e(' &mdash; '); echo $count . ' '; 

if (get_gambling_option('search-art')) { echo get_gambling_option('search-art'); } else { echo 'Articles'; } 
 wp_reset_query(); ?></h1>

	
 <ul>

		<?php while (have_posts()) : the_post(); ?>

			
				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><strong><?php the_title(); ?></strong></a>, <?php the_time('l, F jS, Y') ?></small>

                                  </li>
	

		<?php endwhile; ?>

	</ul>	

<?php kriesi_pagination();?> 


	<?php else : ?>

		<h1 class="center">No posts found. Try a different search?</h1>
		<?php include (STYLESHEETPATH. '/searchform.php'); ?>

	<?php endif; ?>


</section> <!--#content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>