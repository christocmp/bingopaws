<?php

/* Add our function to the widgets_init hook. */
add_action( 'widgets_init', 'fly_smedia_widget' );

/* Function that registers our widget. */
function fly_smedia_widget() {
	register_widget( 'fly_smedia_wid' );
}

class fly_smedia_wid extends WP_Widget {
function fly_smedia_wid() {

$widget_ops = array( 'classname' => 'smediawidget', 'description' => 'Add social media icons to a widget area.' );
/* Widget control settings. */
$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'fly-smedia' );
/* Create the widget. */
$this->WP_Widget( 'fly-smedia', 'Social Media Icons', $widget_ops, $control_ops );
	}


function widget( $args, $instance ) {
		extract( $args );

		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		           

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title ) echo $before_title . $title . $after_title;

		$facebookurl = $instance['facebookurl'];
		$twitterurl = $instance['twitterurl'];
		$googleurl = $instance['googleurl'];
		$pinturl = $instance['pinturl'];
		$instaurl = $instance['instaurl'];
		$youtubeurl = $instance['youtubeurl'];
		$linkedinurl = $instance['linkedinurl'];
		$flickrurl = $instance['flickrurl'];
		$stumbleurl = $instance['stumbleurl'];
		$tumblrurl = $instance['tumblrurl'];
		$rssurl = $instance['rssurl'];
		
		$targetwindow = $instance["targetwindow"];
               	$iconsize = empty($instance['iconsize']) ? '54' : $instance['iconsize'];
		
		
?>


<ul class="smedia">

	 <?php if ($facebookurl!='') { ?> <li><a <?php if ($targetwindow) { echo 'target="_blank"'; } ?> title="Follow us on Facebook" href="<?php echo $facebookurl; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/facebook.png" alt="Facebook" height="<?php echo $iconsize; ?>" width="<?php echo $iconsize; ?>"></a></li>
	<?php } ?>

 	<?php if ($twitterurl!='') { ?> <li><a <?php if ($targetwindow) { echo 'target="_blank"'; } ?> title="Follow us on Twitter" href="<?php echo $twitterurl; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/twitter.png" alt="Twitter" height="<?php echo $iconsize; ?>" width="<?php echo $iconsize; ?>"></a></li>
	<?php } ?>

 <?php if ($googleurl!='') { ?> <li><a <?php if ($targetwindow) { echo 'target="_blank"'; } ?> title="Follow us on Google Plus" href="<?php echo $googleurl; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/google.png" alt="Google Plus" height="<?php echo $iconsize; ?>" width="<?php echo $iconsize; ?>"></a></li>
	<?php } ?>

 	<?php if ($pinturl!='') { ?> <li><a <?php if ($targetwindow) { echo 'target="_blank"'; } ?> title="Follow us on Pinterest" href="<?php echo $pinturl; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/pinterest.png" alt="Pinterest" height="<?php echo $iconsize; ?>" width="<?php echo $iconsize; ?>"></a></li>
	<?php } ?>

 	<?php if ($instaurl!='') { ?> <li><a <?php if ($targetwindow) { echo 'target="_blank"'; } ?> title="Follow us on Instagram" href="<?php echo $instaurl; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/instagram.png" alt="Instagram" height="<?php echo $iconsize; ?>" width="<?php echo $iconsize; ?>"></a></li>
	<?php } ?>

 	<?php if ($youtubeurl!='') { ?> <li><a <?php if ($targetwindow) { echo 'target="_blank"'; } ?> title="Follow us on Youtube" href="<?php echo $youtubeurl; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/youtube.png" alt="Youtube" height="<?php echo $iconsize; ?>" width="<?php echo $iconsize; ?>"></a></li>
	<?php } ?>

 	<?php if ($linkedinurl!='') { ?> <li><a <?php if ($targetwindow) { echo 'target="_blank"'; } ?> title="Follow us on Linkedin " href="<?php echo $linkedinurl; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/linkedin.png" alt="Linkedin" height="<?php echo $iconsize; ?>" width="<?php echo $iconsize; ?>"></a></li>
	<?php } ?>

 	<?php if ($flickrurl!='') { ?> <li><a <?php if ($targetwindow) { echo 'target="_blank"'; } ?> title="Follow us on Flickr" href="<?php echo $flickrurl; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/flickr.png" alt="Flickr" height="<?php echo $iconsize; ?>" width="<?php echo $iconsize; ?>"></a></li>
	<?php } ?>

	<?php if ($stumbleurl!='') { ?> <li><a <?php if ($targetwindow) { echo 'target="_blank"'; } ?> title="Follow us on Stumble Upon " href="<?php echo $stumbleurl; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/stumbleupon.png" alt="Stumble Upon" height="<?php echo $iconsize; ?>" width="<?php echo $iconsize; ?>"></a></li>
	<?php } ?>

	<?php if ($tumblrurl!='') { ?> <li><a <?php if ($targetwindow) { echo 'target="_blank"'; } ?> title="Follow us on Tumblr" href="<?php echo $tumblrurl; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/tumblr.png" alt="Tumblr" height="<?php echo $iconsize; ?>" width="<?php echo $iconsize; ?>"></a></li>
	<?php } ?>

	<?php if ($rssurl!='') { ?> <li><a <?php if ($targetwindow) { echo 'target="_blank"'; } ?> title="Subscribe to RSS Feed" href="<?php echo $rssurl; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/rss.png" alt="RSS Feed" height="<?php echo $iconsize; ?>" width="<?php echo $iconsize; ?>"></a></li>
	<?php } ?>	    


</ul><!--.smedia-->



      <?php  
	  
		
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );

$instance['facebookurl'] = strip_tags( $new_instance['facebookurl'] );
$instance['twitterurl'] = strip_tags( $new_instance['twitterurl'] );
$instance['googleurl'] = strip_tags( $new_instance['googleurl'] );
$instance['pinturl'] = strip_tags( $new_instance['pinturl'] );
$instance['instaurl'] = strip_tags( $new_instance['instaurl'] );
$instance['youtubeurl'] = strip_tags( $new_instance['youtubeurl'] );
$instance['linkedinurl'] = strip_tags( $new_instance['linkedinurl'] );
$instance['flickrurl'] = strip_tags( $new_instance['flickrurl'] );
$instance['stumbleurl'] = strip_tags( $new_instance['stumbleurl'] );
$instance['tumblrurl'] = strip_tags( $new_instance['tumblrurl'] );
$instance['rssurl'] = strip_tags( $new_instance['rssurl'] );
			
		$instance['iconsize'] = strip_tags( $new_instance['iconsize'] );	
		$instance['targetwindow'] = strip_tags( $new_instance['targetwindow'] );
		
		return $instance;
	}

function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Follow Us','iconsize' => '54');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
<p>
<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
</p>

<p><strong>Enter the url for your social social media pages below that you would like to display in the widget below.  Leave blank to hide icon.</strong></p>


<p>
<label for="<?php echo $this->get_field_id('facebookurl'); ?>">Facebook Page URL:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('facebookurl'); ?>" name="<?php echo $this->get_field_name( 'facebookurl' ); ?>" value="<?php echo $instance['facebookurl']; ?>" style="width:100%;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('twitterurl'); ?>">Twitter Page URL:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('twitterurl'); ?>" name="<?php echo $this->get_field_name( 'twitterurl' ); ?>" value="<?php echo $instance['twitterurl']; ?>" style="width:100%;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('googleurl'); ?>">Google Plus Page URL:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('googleurl'); ?>" name="<?php echo $this->get_field_name( 'googleurl' ); ?>" value="<?php echo $instance['googleurl']; ?>" style="width:100%;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('pinturl'); ?>">Pinterest Page URL:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('pinturl'); ?>" name="<?php echo $this->get_field_name( 'pinturl' ); ?>" value="<?php echo $instance['pinturl']; ?>" style="width:100%;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('instaurl'); ?>">Instagram Page URL:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('instaurl'); ?>" name="<?php echo $this->get_field_name( 'instaurl' ); ?>" value="<?php echo $instance['instaurl']; ?>" style="width:100%;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('youtubeurl'); ?>">Youtube Page URL:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('youtubeurl'); ?>" name="<?php echo $this->get_field_name( 'youtubeurl' ); ?>" value="<?php echo $instance['youtubeurl']; ?>" style="width:100%;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('linkedinurl'); ?>">Linkedin Page URL:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('linkedinurl'); ?>" name="<?php echo $this->get_field_name( 'linkedinurl' ); ?>" value="<?php echo $instance['linkedinurl']; ?>" style="width:100%;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('flickrurl'); ?>">Flickr Page URL:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('flickrurl'); ?>" name="<?php echo $this->get_field_name( 'flickrurl' ); ?>" value="<?php echo $instance['flickrurl']; ?>" style="width:100%;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('tumblrurl'); ?>">Tumblr Page URL:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('tumblrurl'); ?>" name="<?php echo $this->get_field_name( 'tumblrurl' ); ?>" value="<?php echo $instance['tumblrurl']; ?>" style="width:100%;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('stumbleurl'); ?>">Stumbleupon Page URL:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('stumbleurl'); ?>" name="<?php echo $this->get_field_name( 'stumbleurl' ); ?>" value="<?php echo $instance['stumbleurl']; ?>" style="width:100%;" />
</p>

<p>
<label for="<?php echo $this->get_field_id('rssurl'); ?>">RSS Page URL:</label>
<input type="text"  class="widefat" id="<?php echo $this->get_field_id('rssurl'); ?>" name="<?php echo $this->get_field_name( 'rssurl' ); ?>" value="<?php echo $instance['rssurl']; ?>" style="width:100%;" />
</p>


<p>
<label for="<?php echo $this->get_field_id('iconsize'); ?>">Widget Display Variation:</label>
<select style="width:60%;" class="widefat" name="<?php echo $this->get_field_name('iconsize'); ?>">
 <option value="64" <?php if ($instance['iconsize'] == "64") echo 'SELECTED'; ?> >64x64</option>
 <option value="54" <?php if ($instance['iconsize'] == "54") echo 'SELECTED'; ?> >54x54</option>
 <option value="48" <?php if ($instance['iconsize'] == "48") echo 'SELECTED'; ?> >48x48</option>
 <option value="40" <?php if ($instance['iconsize'] == "40") echo 'SELECTED'; ?> >40x40</option>
 <option value="32" <?php if ($instance['iconsize'] == "32") echo 'SELECTED'; ?> >32x32</option>
</select>
</p>

<p>		
<input style="width:20px;" class="widefat" type="checkbox" <?php if ($instance['targetwindow']) echo 'CHECKED'; ?> name="<?php echo $this->get_field_name('targetwindow'); ?>" />Check to open links in new window.
</p>



  <?php
    }
 }

?>