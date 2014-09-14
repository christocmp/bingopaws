<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuSharingPinterest' ) && class_exists( 'NgfbSubmenuSharing' ) ) {

	class NgfbSubmenuSharingPinterest extends NgfbSubmenuSharing {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();

			$rows[] = $this->p->util->th( 'Show Button in', 'short highlight', null,
			'The Pinterest "Pin It" button will only appear on Posts and Pages with a <em>custom image ID</em>, 
			a <em>featured</em> image, or an <em>attached</em> image that is equal to or larger than the 
			\'Image Dimensions\' you have chosen.' ).'<td>'.
			( $this->show_on_checkboxes( 'pin' ) ).'</td>';

			$rows[] = $this->p->util->th( 'Preferred Order', 'short' ).'<td>'.
			$this->form->get_select( 'pin_order', 
				range( 1, count( $this->p->admin->submenu['sharing']->website ) ), 
					'short' ).'</td>';

			if ( $this->p->options['plugin_display'] == 'all' ) {
				$rows[] = $this->p->util->th( 'JavaScript in', 'short' ).'<td>'.
				$this->form->get_select( 'pin_js_loc', $this->p->cf['form']['js_locations'] ).'</td>';
			}

			$rows[] = $this->p->util->th( 'Button Height', 'short' ).'<td>'.
			$this->form->get_select( 'pin_button_height', array( 'small' => 'Small', 'large' => 'Large' ) );

			$rows[] = $this->p->util->th( 'Button Shape', 'short' ).'<td>'.
			$this->form->get_select( 'pin_button_shape', array( 'rect' => 'Rectangular', 'round' => 'Circular' ) );

			$rows[] = $this->p->util->th( 'Button Color', 'short' ).'<td>'.
			$this->form->get_select( 'pin_button_color', array( 'gray' => 'Gray', 'red' => 'Red', 'white' => 'White' ) );

			$rows[] = $this->p->util->th( 'Button Language', 'short' ).'<td>'.
			$this->form->get_select( 'pin_button_lang', array( 'en' => 'English', 'ja' => 'Japanese' ) );

			$rows[] = $this->p->util->th( 'Show Pin Count', 'short' ).'<td>'.
			$this->form->get_select( 'pin_count_layout', 
				array( 
					'none' => 'Not Shown',
					'beside' => 'Beside the Button',
					'above' => 'Above the Button',
				)
			).'</td>';

			$rows[] = $this->p->util->th( 'Image Dimensions', 'short' ).
			'<td>Width '.$this->form->get_input( 'pin_img_width', 'short' ).' x '.
			'Height '.$this->form->get_input( 'pin_img_height', 'short' ).' &nbsp; '.
			'Crop '.$this->form->get_checkbox( 'pin_img_crop' ).'</td>';

			$rows[] = $this->p->util->th( 'Image Caption Text', 'short' ).'<td>'.
			$this->form->get_select( 'pin_caption', $this->p->cf['form']['caption_types'] ).'</td>';

			if ( $this->p->options['plugin_display'] == 'all' ) {
				$rows[] = $this->p->util->th( 'Caption Length', 'short' ).'<td>'.
				$this->form->get_input( 'pin_cap_len', 'short' ).' characters or less</td>';
			}

			return $rows;
		}
	}
}

if ( ! class_exists( 'NgfbSharingPinterest' ) ) {

	class NgfbSharingPinterest {

		private static $cf = array(
			'opt' => array(				// options
				'defaults' => array(
					'pin_on_content' => 0,
					'pin_on_excerpt' => 0,
					'pin_on_admin_edit' => 1,
					'pin_on_sidebar' => 0,
					'pin_order' => 4,
					'pin_js_loc' => 'footer',
					'pin_button_lang' => 'en',
					'pin_button_shape' => 'rect',
					'pin_button_color' => 'gray',
					'pin_button_height' => 'small',
					'pin_count_layout' => 'beside',
					'pin_img_width' => 800,
					'pin_img_height' => 800,
					'pin_img_crop' => 0,
					'pin_caption' => 'both',
					'pin_cap_len' => 400,
				),
			),
		);

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->util->add_plugin_filters( $this, array( 'get_defaults' => 1 ) );
			$this->p->util->add_img_sizes_from_opts( array( 'pin_img' => 'pinterest' ) );
		}

		public function filter_get_defaults( $opts_def ) {
			return array_merge( $opts_def, self::$cf['opt']['defaults'] );
		}

		public function get_html( $atts = array(), &$opts = array() ) {
			if ( empty( $opts ) ) 
				$opts =& $this->p->options;
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$use_post = array_key_exists( 'use_post', $atts ) ? $atts['use_post'] : true;
			$source_id = $this->p->util->get_source_id( 'pinterest', $atts );
			$atts['add_page'] = array_key_exists( 'add_page', $atts ) ? $atts['add_page'] : true;	// get_sharing_url argument

			$atts['url'] = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $source_id ) : 
				apply_filters( $this->p->cf['lca'].'_sharing_url', $atts['url'], 
					$use_post, $atts['add_page'], $source_id );

			$post_id = 0;
			if ( is_singular() || $use_post !== false ) {
				if ( ( $obj = $this->p->util->get_post_object( $use_post ) ) === false ) {
					$this->p->debug->log( 'exiting early: invalid object type' );
					return false;
				}
				$post_id = empty( $obj->ID ) || empty( $obj->post_type ) ? 0 : $obj->ID;
			}

			if ( empty( $atts['size'] ) ) 
				$atts['size'] = $this->p->cf['lca'].'-pinterest';

			if ( empty( $atts['photo'] ) ) {
				if ( empty( $atts['pid'] ) && $post_id > 0 ) {
					// check for meta, featured, and attached images
					$pid = $this->p->addons['util']['postmeta']->get_options( $post_id, 'og_img_id' );
					$pre = $this->p->addons['util']['postmeta']->get_options( $post_id, 'og_img_id_pre' );
					if ( ! empty( $pid ) ) 
						$atts['pid'] = $pre == 'ngg' ? 'ngg-'.$pid : $pid;
					elseif ( $this->p->is_avail['postthumb'] == true && has_post_thumbnail( $post_id ) )
						$atts['pid'] = get_post_thumbnail_id( $post_id );
					else $atts['pid'] = $this->p->media->get_first_attached_image_id( $post_id );
				}
				if ( ! empty( $atts['pid'] ) )
					list( $atts['photo'], $atts['width'], $atts['height'],
						$atts['cropped'] ) = $this->p->media->get_attachment_image_src( $atts['pid'], $atts['size'], false );
			}

			// the pinterest button always shares an image - return now if there's no image
			if ( empty( $atts['photo'] ) ) {
				$this->p->debug->log( 'exiting early: no photo defined for post_id '.$post_id );
				return false;
			}

			if ( empty( $atts['caption'] ) && $post_id > 0 ) 
				$atts['caption'] = $this->p->addons['util']['postmeta']->get_options( $post_id, 'pin_desc' );

			// html encode param is false to use url encoding instead
			if ( empty( $atts['caption'] ) ) 
				$atts['caption'] = $this->p->webpage->get_caption( $opts['pin_caption'], $opts['pin_cap_len'], $use_post, true, true, false );

			$query = 'url='.urlencode( $atts['url'] );
			$query .= '&amp;media='.urlencode( $atts['photo'] );

			// use rawurlencode() for for mobile devices (encodes a space as '%20' instead of '+')
			$query .= '&amp;description='.rawurlencode( $atts['caption'] );

			switch ( $opts['pin_button_shape'] ) {
				case 'rect':
					$pin_img_width = $opts['pin_button_height'] == 'small' ? 40 : 56;
					$pin_img_height = $opts['pin_button_height'] == 'small' ? 20 : 28;
					$pin_img_url = $prot.'//assets.pinterest.com/images/pidgets/pinit_fg_'.
						$opts['pin_button_lang'].'_'.$opts['pin_button_shape'].'_'.
						$opts['pin_button_color'].'_'.$pin_img_height.'.png';
					break;
				case 'round':
					$pin_img_width = $pin_img_height = $opts['pin_button_height'] == 'small' ? 16 : 32;
					$pin_img_url = $prot.'//assets.pinterest.com/images/pidgets/pinit_fg_'.
						'en_'.$opts['pin_button_shape'].'_'.
						'red_'.$pin_img_height.'.png';
					break;
				default:
					$this->p->debug->log( 'exiting early: unknown pinterest button shape' );
					return $html;
					break;
			}
			$pin_img_url = $this->p->util->get_cache_url( $pin_img_url );

			$html = '<!-- Pinterest Button --><div '.$this->p->sharing->get_css( 'pinterest', $atts ).'>'.
			'<a href="'.$prot.'//pinterest.com/pin/create/button/?'.$query.'" '.
			'data-pin-do="buttonPin" '.
			'data-pin-lang="'.$opts['pin_button_lang'].'" '.
			'data-pin-shape="'.$opts['pin_button_shape'].'" '.
			'data-pin-color="'.$opts['pin_button_color'].'" '.
			'data-pin-height="'.$pin_img_height.'" '.
			'data-pin-config="'.$opts['pin_count_layout'].'">'.
			'<img border="0" alt="Pin It" src="'.$pin_img_url.'" width="'.$pin_img_width.'" height="'.$pin_img_height.'" /></a></div>';

			$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );
			return $html."\n";
		}

		public function get_js( $pos = 'id' ) {
			$this->p->debug->mark();
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$js_url = $this->p->util->get_cache_url( $prot.'//assets.pinterest.com/js/pinit.js' );

			return '<script type="text/javascript" id="pinterest-script-'.$pos.'">'.$this->p->cf['lca'].'_insert_js( "pinterest-script-'.$pos.'", "'.$js_url.'" );</script>'."\n";
		}
	}
}

?>
