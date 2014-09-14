<?php
/**
 * The template for displaying all single affilaite site/casino reviews
 *
 * @package bingotheme
 */

get_header(); ?>

  <section id="content" class="main-content">
	  

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

 	<?php $redirectkey=fly_redirect_slug(); ?>

 	<div class="topreview">
	   	<div class="topleft"><h1><?php the_title(); ?></h1></div>
	 	 <div class="topright"><?php if (get_gambling_option('review-rating')) { echo get_gambling_option('review-rating'); } else { ?>Rating<?php } ?>: <span class="rating"><?php echo get_post_meta($post->ID,"_as_rating",true); ?>/5</span></div>
	  </div><!--.topreview-->

	<div class="reviewarea">
	  	<div class="left">
			<div class="top">
			<a <?php if (get_theme_option('redirect-new-window')) { echo "target=\"_blank\""; }?> href="<?php bloginfo('url'); ?>/<?php echo $redirectkey; ?>/<?php echo get_post_meta($post->ID,"_as_redirectkey",true);?>/">

			<?php the_post_thumbnail('casino-logo',array('class' => 'logo')); ?>
	
			</a>
			<span class="rate cen"><span class="ratetotal" style="width:<?php echo get_post_meta($post->ID,"_as_rating",true)*2*10;?>%"></span></span>
			<div class="freebonus"><span class="amt"><?php echo get_post_meta($post->ID,"_as_nodeposit",true);?></span> <span class="infoamt"><?php if (get_gambling_option('review-ndb')) { echo get_gambling_option('review-ndb'); } else { ?>No Deposit <br /> Bonus<?php } ?></span></div>
			</div>
			<a <?php if (get_theme_option('redirect-new-window')) { echo "target=\"_blank\""; }?> href="<?php bloginfo('url'); ?>/<?php echo $redirectkey; ?>/<?php echo get_post_meta($post->ID,"_as_redirectkey",true);?>/" class="visbutton lg cent"><?php if (get_gambling_option('review-pnow')) { echo get_gambling_option('review-pnow'); } else { ?>Play Now<?php } ?></a>
		</div><!--.left-->

		<div class="right">
			<div class="bonusinfo">
				<?php echo get_post_meta($post->ID,"_as_bonustext",true);?>
			</div>
			<div class="basicinfo">
			<?php echo get_post_meta($post->ID, '_as_roomname', true);?> <?php if (get_gambling_option('review-feat')) { echo get_gambling_option('review-feat'); } else { ?>Features<?php } ?>
			</div>
			
			<table class="basictable">
		
			<?php if( has_term('','network-tags') ) { ?>
			<tr>
				<th><?php if (get_gambling_option('review-software')) { echo get_gambling_option('review-software'); } else { ?>Bingo Software<?php } ?></th>
				<td><?php echo fly_unlinkterm($post->ID,"network-tags"); ?></td>
			</tr>
			<?php } ?>


			<?php if( has_term('','deposit-tags') ) { ?>
			<tr>
				<th><?php if (get_gambling_option('review-deposit')) { echo get_gambling_option('review-deposit'); } else { ?>Deposit Options<?php } ?></th>
				<td><?php echo fly_unlinkterm($post->ID,"deposit-tags"); ?></td>
			</tr>
			<?php } ?>


			<?php if( has_term('','withdrawal-tags') ) { ?>
			<tr>
				<th><?php if (get_gambling_option('review-with')) { echo get_gambling_option('review-with'); } else { ?>Withdrawal Options<?php } ?></th>
				<td><?php echo fly_unlinkterm($post->ID,"withdrawal-tags"); ?></td>
			</tr>
			
			<?php } ?>

			<?php if( has_term('','support-tags') ) { ?>
			<tr>
				<th><?php if (get_gambling_option('review-support')) { echo get_gambling_option('review-support'); } else { ?>Support Options<?php } ?></th>
				<td><?php echo fly_unlinkterm($post->ID,"support-tags"); ?></td>
			</tr>
			
			<?php } ?>

			<?php if( has_term('','platform-tags') ) { ?>
			<tr>
				<th><?php if (get_gambling_option('review-plat')) { echo get_gambling_option('review-plat'); } else { ?>Platforms<?php } ?></th>
				<td><?php echo fly_unlinkterm($post->ID,"platform-tags"); ?></td>
			</tr>
			<?php } ?>

			
			<?php if (get_post_meta($post->ID, '_as_mindeposit', true) != '') { ?>
			<tr>
				<th><?php if (get_gambling_option('review-mindep')) { echo get_gambling_option('review-mindep'); } else { ?>Minimum Deposit<?php } ?></th>
				<td><?php echo get_post_meta($post->ID, '_as_mindeposit', true);?></td>
			</tr>
			<?php } ?>


			<?php if (get_post_meta($post->ID, '_as_reload', true) != '') { ?>
			<tr>
				<th><?php if (get_gambling_option('review-reload')) { echo get_gambling_option('review-reload'); } else { ?>Reload Bonus<?php } ?></th>
				<td><?php echo get_post_meta($post->ID, '_as_reload', true);?></td>
			</tr>
			<?php } ?>

			<?php if (get_post_meta($post->ID, '_as_bonuscode', true) != '') { ?>
			<tr>
				<th><?php if (get_gambling_option('review-subonus')) { echo get_gambling_option('review-subonus'); } else { ?>Signup Bonus Code<?php } ?></th>
				<td><span class="hilite"><strong><?php echo get_post_meta($post->ID, '_as_bonuscode', true);?></strong></span></td>
			</tr>
			<?php } ?>

			
			</table>
				
		</div><!--.right-->
	  
	  </div><!--.reviewarea-->

	<div class="entry-content">

   		<?php the_content();?>

		<p style="text-align:center;">
		<a <?php if (get_theme_option('redirect-new-window')) { echo "target=\"_blank\""; }?> href="<?php bloginfo('url'); ?>/<?php echo $redirectkey; ?>/<?php echo get_post_meta($post->ID,"_as_redirectkey",true);?>/" class="visbutton lg"><?php if (get_gambling_option('review-pnowat')) { echo get_gambling_option('review-pnowat'); } else { ?>Play Now at<?php } ?> <?php echo get_post_meta($post->ID, '_as_roomname', true);?></a>
		</p>

		<?php comments_template(); // Get comments template ?>

	</div><!--.entry-content-->

<?php endwhile; endif; ?> 

</section> <!--.content-->

<?php get_sidebar(); ?>
  		
<?php get_footer(); ?>