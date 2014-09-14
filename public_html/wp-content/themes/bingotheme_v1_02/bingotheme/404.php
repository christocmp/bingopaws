<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package bingotheme
 */

get_header(); ?>

<section id="content" class="main-content">
		
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">

			<h1 class="entry-title">Page Not Found</h1>

			<div class="entry-content">

			<p>Page not found or has been removed.  Please browse one of our other pages. Search our site below</p>
			<?php get_search_form(); ?>

			</div><!--.entry-content-->

		</article>
          	
</section> <!--#content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>