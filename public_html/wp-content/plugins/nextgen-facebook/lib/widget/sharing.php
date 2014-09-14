<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbWidgetSharing' ) && class_exists( 'WP_Widget' ) ) {

	class NgfbWidgetSharing extends WP_Widget {

		protected $p;

		public function __construct() {
			global $ngfb;
			if ( ! is_object( $ngfb ) )
				return;
			$this->p =& $ngfb;
			$lca = $this->p->cf['lca'];
			$short = $this->p->cf['plugin'][$lca]['short'];
			$widget_name = 'Sharing Buttons';
			$widget_class = $this->p->cf['lca'].'-widget-buttons';
			$widget_ops = array( 
				'classname' => $widget_class,
				'description' => 'The '.$short.' social sharing buttons widget.'
			);
			$this->WP_Widget( $widget_class, $widget_name, $widget_ops );
		}
	
		public function widget( $args, $instance ) {
			if ( is_feed() )
				return;	// nothing to do in the feeds

			if ( ! empty( $_SERVER['NGFB_DISABLE'] ) )
				return;

			if ( ! is_object( $this->p ) )
				return;

			if ( is_object( $this->p->sharing ) && $this->p->sharing->is_post_buttons_disabled() ) {
				$this->p->debug->log( 'widget buttons skipped: sharing buttons disabled' );
				return;
			}
			extract( $args );

			if ( $this->p->is_avail['cache']['transient'] ) {
				$sharing_url = $this->p->util->get_sharing_url();
				$cache_salt = __METHOD__.'(lang:'.SucomUtil::get_locale().'_widget:'.$this->id.'_url:'.$sharing_url.')';
				$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
				$cache_type = 'object cache';
				$this->p->debug->log( $cache_type.': transient salt '.$cache_salt );
				$html = get_transient( $cache_id );
				if ( $html !== false ) {
					$this->p->debug->log( $cache_type.': html retrieved from transient '.$cache_id );
					echo $html;
					$this->p->debug->show_html();
					return;
				}
			}

			// sort enabled sharing buttons by their preferred order
			$sorted_ids = array();
			foreach ( $this->p->cf['opt']['pre'] as $id => $pre )
				if ( array_key_exists( $id, $instance ) && (int) $instance[$id] )
					$sorted_ids[$this->p->options[$pre.'_order'].'-'.$id] = $id;
			ksort( $sorted_ids );

			$atts = array( 
				'css_id' => $args['widget_id'],
				'filter_id' => 'widget',	// used by get_html() to filter atts and opts
				'use_post' => false,		// don't use the post ID on indexes
				'preset_id' => $this->p->options['buttons_preset_widget'],
			);
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			$html = '<!-- '.$this->p->cf['lca'].' '.$args['widget_id'].' begin -->'.
				$before_widget.( empty( $title ) ? '' : $before_title.$title.$after_title ).
				$this->p->sharing->get_html( $sorted_ids, $atts ).$after_widget.
				'<!-- '.$this->p->cf['lca'].' '.$args['widget_id'].' end -->'."\n";

			if ( $this->p->is_avail['cache']['transient'] ) {
				set_transient( $cache_id, $html, $this->p->cache->object_expire );
				$this->p->debug->log( $cache_type.': html saved to transient '.$cache_id.' ('.$this->p->cache->object_expire.' seconds)');
			}
			echo $html;
			$this->p->debug->show_html();
		}
	
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			foreach ( $this->p->sharing->get_website_ids() as $id => $name )
				$instance[$id] = empty( $new_instance[$id] ) ? 0 : 1;
			return $instance;
		}
	
		public function form( $instance ) {
			$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Share It';
			echo "\n", '<p><label for="', $this->get_field_id( 'title' ), '">Title (Leave Blank for No Title):</label>',
				'<input class="widefat" id="', $this->get_field_id( 'title' ), 
					'" name="', $this->get_field_name( 'title' ), 
					'" type="text" value="', $title, '" /></p>', "\n";
	
			foreach ( $this->p->sharing->get_website_ids() as $id => $name ) {
				$name = $name == 'GooglePlus' ? 'Google+' : $name;
				echo '<p><label for="', $this->get_field_id( $id ), '">', 
					'<input id="', $this->get_field_id( $id ), 
					'" name="', $this->get_field_name( $id ), 
					'" value="1" type="checkbox" ';
				if ( ! empty( $instance[$id] ) )
					echo checked( 1 , $instance[$id] );
				echo ' /> ', $name;
				switch ( $id ) {
					case 'pinterest' : echo ' (not added on indexes)'; break;
					case 'tumblr' : echo ' (shares link on indexes)'; break;
				}
				echo '</label></p>', "\n";
			}
		}

	}
}

?>
