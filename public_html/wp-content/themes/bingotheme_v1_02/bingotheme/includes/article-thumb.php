<div class="thumb">
<?php if ( has_post_thumbnail() ) { ?>
		<a href="<?php the_permalink(); ?>">      
        	<?php the_post_thumbnail(array(125,125), array('class' => 'articleimg')); ?>
        	</a>
		
 	<?php } else if (get_theme_option('art-thumb')) { ?>
       	
		<a href="<?php the_permalink(); ?>">      
        	<img class="articleimg" src="<?php echo get_theme_option('art-thumb'); ?>" alt="<?php the_title(); ?>" width="125" height="125" />
        	</a>
		
<?php } ?>
</div>