<?php

/* Add our function to the widgets_init hook. */
add_action( 'widgets_init', 'fly_load_topsites2' );

/* Function that registers our widget. */
function fly_load_topsites2() {
	register_widget( 'Bingo_Widget' );
}

class Bingo_Widget extends WP_Widget {
function Bingo_Widget() {

$widget_ops = array( 'classname' => 'bingo-widget', 'description' => 'Display top bingo bonuses' );
/* Widget control settings. */
$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'bingo-widget' );
/* Create the widget. */
$this->WP_Widget( 'bingo-widget', 'Bingo Bonuses Widget', $widget_ops, $control_ops );
	}


function widget( $args, $instance ) {
		extract( $args );

		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
         	$detailslink = $instance["detailslink"];
                $version = $instance["version"];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;


		$key = $instance["ts_sort"];

                if ($key=='order'){	
	
		$loop = new WP_Query( array( 'post_type' => 'casino', 'posts_per_page' => -1, 'orderby'=>'menu_order', 'order' => 'desc' ) );

 		} elseif ($key=='date'){	
	
		$loop = new WP_Query( array( 'post_type' => 'casino', 'posts_per_page' => -1, 'orderby'=>'date','order' => 'DESC' ) );
 
                } elseif ($key=='name'){	
	
		$loop = new WP_Query( array( 'post_type' => 'casino', 'posts_per_page' => -1, 'orderby'=>'title', 'order' => 'ASC' ) );   
 
                } else {

               $loop = new WP_Query( array( 'post_type' => 'casino', 'posts_per_page' => -1, 'orderby'=>'meta_value_num', 'meta_key'=>$key, 'order' => 'desc' ) );

                 }
		
		$i=0;
		$posts = array();
		foreach ($loop->posts as $p) {
			if ($i>=$instance["numsites"]) continue;
			
			if ($instance['tag']!='' && !has_term($instance['tag'], 'affiliate-tags', $p)) continue;
			if ($instance['networktag']!='' && !has_term($instance['networktag'], 'network-tags', $p)) continue;
			if ($instance['plattag']!='' && !has_term($instance['plattag'], 'platform-tags', $p)) continue;
			
			$custom = get_post_custom($p->ID);	
			
			$posts[] = $p;
			$i++;
		}
		
		$content = apply_filters('topsites_content', $posts,$version);
		
		if (!is_array($content) && $content!="") {
			echo $content;
			
		} else {
?>

<table class="depositwidget">
	<tr>
		<th colspan="2" class="site"><?php if (get_gambling_option('widget2-site')) { echo get_gambling_option('widget2-site'); } else { ?>Site<?php } ?></th>
		<th  class="maxbonus"><span class="hilite"><?php if (get_gambling_option('widget2-bonus')) { echo get_gambling_option('widget2-bonus'); } else { ?>Bonus<?php } ?></span></th>
		<th  class="wager"><?php if (get_gambling_option('widget2-det')) { echo get_gambling_option('widget2-det'); } else { ?>Details<?php } ?></th>
	</tr>
			
<?php
$x=0;
global $post;
$opost = $post;
$redirectkey=fly_redirect_slug();
foreach ($posts as $post) :
	setup_postdata($post); 
$x=$x+1;
$width=get_post_meta($post->ID, "_as_rating", true)*20;    
 ?>



	<tr <?php if ($x==1) { echo 'class="top"';}?>>
		<td class="icon"><a title="<?php echo get_post_meta($post->ID,"_as_roomname",true);?>" href="<?php the_permalink() ?>"><?php the_post_thumbnail('casino-icon',array('class' => 'logo')); ?></a></td>
		<td class="sitename"><a title="<?php echo get_post_meta($post->ID,"_as_roomname",true);?>" href="<?php the_permalink() ?>"><?php echo get_post_meta($post->ID,"_as_roomname",true);?></a></td>
		<td class="bonus"><span class="hilite">

	<?php if ($version=='Version 1' || $version=='') {?>
	
		<?php echo get_post_meta($post->ID,"_as_bonusper",true);?>%

	<?php } else if ($version=='Version 2') { echo get_post_meta($post->ID,"_as_nodeposit",true); ?>

	<?php } else if ($version=='Version 3') { echo get_post_meta($post->ID,"_as_subonus",true); ?>

	<?php } else { echo get_post_meta($post->ID,"_as_reload",true); } ?>

</span></td>
		<td class="wager">

<?php if ($detailslink) { ?>

<a href="<?php the_permalink() ?>" class="visbutton sm"><?php if (get_gambling_option('widget2-det')) { echo get_gambling_option('widget2-det'); } else { ?>Details<?php } ?></a>

<?php } else { ?>

<a <?php if (get_theme_option('redirect-new-window')) { echo "target=\"_blank\""; }?> href="<?php bloginfo('url'); ?>/<?php echo $redirectkey; ?>/<?php echo get_post_meta($post->ID,"_as_redirectkey",true);?>/" class="visbutton sm"><?php if (get_gambling_option('widget2-detb')) { echo get_gambling_option('widget2-detb'); } else { ?>Details<?php } ?></a>

<?php } ?>

</td>
	</tr>
           

			
		
<?php endforeach;
$post = $opost;

 ?>
      </table>   <?php  
	  
		} // end default
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['ts_sort'] = strip_tags( $new_instance['ts_sort'] );
		$instance['numsites'] = strip_tags( $new_instance['numsites'] );
                $instance['version'] = strip_tags( $new_instance['version'] );
		
		$instance['tag'] = strip_tags( $new_instance['tag'] );
		$instance['networktag'] = strip_tags( $new_instance['networktag'] );
		$instance['plattag'] = strip_tags( $new_instance['plattag'] );
$instance['detailslink'] = strip_tags( $new_instance['detailslink'] );
		
		return $instance;
	}

function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Top Sites', 'ts_sort' => 'order','numsites' => '5');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
<p>
<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" type="text" style="width:100%;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('numsites'); ?>">Number of Casinos to Show:</label>
<input class="widefat" id="<?php echo $this->get_field_id('numsites'); ?>" name="<?php echo $this->get_field_name('numsites'); ?>" value="<?php echo $instance['numsites']; ?>" type="text" style="width:25px;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('ts_sort'); ?>">Sort By:</label>
<select style="width:60%;" class="widefat" name="<?php echo $this->get_field_name('ts_sort'); ?> ">
<option value="date" <?php if ($instance['ts_sort'] == "date") echo 'SELECTED'; ?> >Date (Newest)</option>
<option value="name" <?php if ($instance['ts_sort'] == "name") echo 'SELECTED'; ?> >Name</option>
<option value="order" <?php if ($instance['ts_sort'] == "order") echo 'SELECTED'; ?> >Menu Order</option>
 <option value="_as_rating" <?php if ($instance['ts_sort'] == "_as_rating") echo 'SELECTED'; ?> >Rating</option>
</select>
</p>


<p>
<label>Filter by Tag</label>
    <?php
		$terms = get_terms('affiliate-tags', array('hide_empty'=>0));
	?>
    <select class="widefat" style="width:60%;" name="<?php echo $this->get_field_name('tag');?>">
    	<option value=""></option>
    <?php	foreach ($terms as $term) { ?>
    	<option <?php echo ($instance['tag']==$term->term_id?'SELECTED':'');?> value="<?php echo $term->term_id;?>"><?php echo $term->name;?></option>
    <?php  } ?>
    </select>
</p>

<p>
<label>Filter by Platform</label>
    <?php
		$terms = get_terms('platform-tags', array('hide_empty'=>0));
	?>
    <select class="widefat" style="width:60%;" name="<?php echo $this->get_field_name('plattag');?>">
    	<option value=""></option>
    <?php	foreach ($terms as $term) { ?>
    	<option <?php echo ($instance['plattag']==$term->term_id?'SELECTED':'');?> value="<?php echo $term->term_id;?>"><?php echo $term->name;?></option>
    <?php  } ?>
    </select>
</p>

<p>
<label>Filter by Network</label>
    <?php
		$terms = get_terms('network-tags', array('hide_empty'=>0));
	?>
    <select class="widefat" style="width:60%;" name="<?php echo $this->get_field_name('networktag');?>">
    	<option value=""></option>
    <?php	foreach ($terms as $term) { ?>
    	<option <?php echo ($instance['networktag']==$term->term_id?'SELECTED':'');?> value="<?php echo $term->term_id;?>"><?php echo $term->name;?></option>
    <?php  } ?>
    </select>
</p>


<p>
<label for="<?php echo $this->get_field_id('version'); ?>">Widget Display Variation:</label>
<select style="width:60%;" class="widefat" name="<?php echo $this->get_field_name('version'); ?>">
 <option value="Version 1" <?php if ($instance['version'] == "Version 1") echo 'SELECTED'; ?> >Bonus Percent</option>
 <option value="Version 2" <?php if ($instance['version'] == "Version 2") echo 'SELECTED'; ?> >No Deposit Bonus</option>
 <option value="Version 3" <?php if ($instance['version'] == "Version 3") echo 'SELECTED'; ?> >Signup Bonus</option>
 <option value="Version 4" <?php if ($instance['version'] == "Version 4") echo 'SELECTED'; ?> >Reload Bonus</option>
</select>
</p>


<p>	
<label>Link Details Button to Review Page:</label>	
<input style="width:20px;" class="widefat" type="checkbox" <?php if ($instance['detailslink']) echo 'CHECKED'; ?> name="<?php echo $this->get_field_name('detailslink'); ?>" />Check to link details button to review page.
</p>




  <?php
    }
 }

?>