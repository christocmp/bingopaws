<?php
/**
 * The author template for displaying author info and posts.
 *
 * @package bingotheme
 */


get_header(); ?>

<section id="content" class="main-content">

<?php
    $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
    ?>

	<h1><?php if (get_gambling_option('authorpage-about')) { echo get_gambling_option('authorpage-about'); } else { ?>About<?php } ?> <?php echo $curauth->display_name; ?></h1>

	<div class="authorbio">
 	<?php if ($curauth->authimg != '') { ?>
	<img src="<?php echo $curauth->authimg; ?>" alt="<?php echo $curauth->display_name; ?>" width="80" height="80" />
	<?php }  else {?>
	<img src="<?php echo get_theme_option('auth-imgd'); ?>" alt="<?php echo $curauth->display_name; ?>" width="80" height="80" />
	<?php } ?>
 	<strong><?php if (get_gambling_option('authorpage-name')) { echo get_gambling_option('authorpage-name'); } else { ?>Full Name<?php } ?>: </strong> <?php echo $curauth->first_name; ?> <?php echo $curauth->last_name; ?><br />
 	<strong><?php if (get_gambling_option('authorpage-web')) { echo get_gambling_option('authorpage-web'); } else { ?>Website<?php } ?>: </strong> <a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a><br />
 	<strong><?php if (get_gambling_option('authorpage-info')) { echo get_gambling_option('authorpage-info'); } else { ?>More Info<?php } ?>: </strong> <?php echo $curauth->user_description; ?>
	</div><!--.authorbio-->

	<h2><?php if (get_gambling_option('authorpage-postsby')) { echo get_gambling_option('authorpage-postsby'); } else { ?>Post by<?php } ?> <?php echo $curauth->display_name; ?></h2>	
			
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<article <?php post_class(articleexcerpt) ?> id="post-<?php the_ID(); ?>">

			<?php include('includes/article-thumb.php'); ?>

				
			<h3><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>

			<?php if (!get_theme_option('bylines-hide-all')) {  include('includes/article-meta.php'); } ?>
		
			<?php the_excerpt();?>	
			<p class="rmore"><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>">Read more</a></p>	
			

		</article><!--.articleexcerpt-->


     	<?php endwhile; ?><?php else: ?>
		
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php endif; ?>
	<?php kriesi_pagination();?> 				
		
</section> <!--#content-->


<?php get_sidebar(); ?>

<?php get_footer(); ?>