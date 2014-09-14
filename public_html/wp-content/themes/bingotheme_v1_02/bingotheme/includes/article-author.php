<?php if (get_theme_option('auth-bio')) { ?>

 		<div class="authorbio">

  		 <?php if ( get_the_author_meta( 'authimg' ) != '') { ?>
  		<img src="<?php echo the_author_meta( 'authimg' ); ?>" alt="<?php echo the_author_meta( 'display_name' ); ?>" width="80" height="80" />
  		<?php }  else {?>
  		<img src="<?php echo get_theme_option('auth-imgd'); ?>" alt="<?php echo the_author_meta( 'display_name' ); ?>" width="80" height="80" />
  		<?php } ?>


     		<h3><?php if (get_gambling_option('author-morea')) { echo get_gambling_option('author-morea'); } else { ?>More About<?php } ?> <?php the_author_posts_link(); ?></h3>
       		<p><?php the_author_meta( 'description' ); ?></p>
  <span> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php if (get_gambling_option('author-posts')) { echo get_gambling_option('author-posts'); } else { ?>View Posts<?php } ?></a> - <a href="<?php the_author_meta( 'user_url'); ?>"><?php if (get_gambling_option('author-vistweb')) { echo get_gambling_option('author-vistweb'); } else { ?>Visit Website<?php } ?></a></span>
  		</div><!-- End of Author Bio  -->

<?php } ?>
