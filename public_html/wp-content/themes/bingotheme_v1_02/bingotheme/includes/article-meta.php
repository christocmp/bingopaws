<div class="bylines">

<?php //meta 

	if (!get_theme_option('bylines-hide-date')) { the_date(); }
	
   	if (!get_theme_option('bylines-hide-category')) { ?> <?php if (get_gambling_option('gen-posted')) { echo get_gambling_option('gen-posted'); } else { ?>Posted in<?php } ?> <?php the_category(', '); } ?> <?php  if (!get_theme_option('bylines-hide-author')) { ?> <?php if (get_gambling_option('gen-by')) { echo get_gambling_option('gen-by'); } else { ?>by<?php } ?> <?php the_author_posts_link(); } ?>   

	<?php if (!get_theme_option('bylines-hide-comment')) { ?> &bull; <a href="<?php the_permalink(); ?>#comments">   <?php comments_number(); ?></a> <?php } ?> <?php edit_post_link('  (Edit) ', '', '');

?>
</div><!--.bylines-->