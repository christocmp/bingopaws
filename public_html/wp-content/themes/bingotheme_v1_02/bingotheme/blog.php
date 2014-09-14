<?php
/*
Template Name: Blog Template
*/
?>

<?php get_header(); ?>

<section id="content" class="main-content">


<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php

$numposts=get_post_meta($post->ID, '_numblogs', 'true');
if ($numposts == ''){
$numposts=5;}

$cat=get_post_meta($post->ID, '_blogcat', 'true');
$excerpts=get_post_meta($post->ID, '_blogexcerpts', 'true');


?>
	<div class="post" id="post-<?php the_ID(); ?>" style="margin-bottom:20px;">
	
        <?php the_content(); ?>

    	</div> <!--#.post-->
		
     	<?php endwhile; endif; ?> <?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>


	<?php if ($excerpts == 'Yes'){ ?>

			
	<?php if ( get_query_var('paged') ) {

    	$page = get_query_var('paged');
 	$paged = get_query_var('paged');

	} elseif ( get_query_var('page') ) {

    	$page = get_query_var('page');
    	$paged = get_query_var('page');

	} else {

    	$page = 1;
	$paged = 1;

	}

	if ($cat == ''){ 
	query_posts("showposts=$numposts&paged=$page"); } else {   query_posts("showposts=$numposts&paged=$page&cat=$cat");      }  while ( have_posts() ) : the_post() ?>


		<article <?php post_class(articleexcerpt) ?> id="post-<?php the_ID(); ?>">

			<?php include('includes/article-thumb.php'); ?>

			
			<h3><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>

			<?php if (!get_theme_option('bylines-hide-all')) {  include('includes/article-meta.php'); } ?>
		
			<?php the_excerpt();?>


		</article><!--.articleexcerpt-->


  	<?php endwhile; ?>
	
	<?php kriesi_pagination();?> 

	
	<?php } else { ?>


 	<?php if ( get_query_var('paged') ) {

    	$page = get_query_var('paged');
 	$paged = get_query_var('paged');

	} elseif ( get_query_var('page') ) {

    	$page = get_query_var('page');
    	$paged = get_query_var('page');

	} else {

    	$page = 1;
	$paged = 1;

	}

 	if ($cat == ''){ 
	query_posts("showposts=$numposts&paged=$page"); } else {   query_posts("showposts=$numposts&paged=$page&cat=$cat");      }  while ( have_posts() ) : the_post() ?>
	
       		<article <?php post_class(blogarticles) ?> id="post-<?php the_ID(); ?>">

			<h2 class="entry-title underline"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>    
        		
<div class="meta">
				
			<?php if (!get_theme_option('bylines-hide-all')) {

			if (!get_theme_option('bylines-hide-date')) { the_date(); }
	
   			if (!get_theme_option('bylines-hide-category')) { ?> <?php if (get_gambling_option('gen-posted')) { echo get_gambling_option('gen-posted'); } else { ?>Posted in<?php } ?> <?php the_category(', '); } ?> <?php  if (!get_theme_option('bylines-hide-author')) { ?> <?php if (get_gambling_option('gen-by')) { echo get_gambling_option('gen-by'); } else { ?>by<?php } ?> <?php the_author_posts_link(); } ?>   

			<?php if (!get_theme_option('bylines-hide-comment')) { ?> &bull; <a href="<?php the_permalink(); ?>#comments">   <?php comments_number(); ?></a> <?php } ?> <?php edit_post_link('  (Edit) ', '', '');

			} ?>

			</div><!--.meta-->

  			<?php the_content();?>

		</article><!--.articleexcerpt-->

  	<?php endwhile; ?>

	<?php kriesi_pagination();?> 

	<?php }  ?>		

</section> <!--#content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>