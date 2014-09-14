<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbGplForumBbpress' ) ) {

	class NgfbGplForumBbpress {

		private $p;
		private $sharing;
		private $has_setup = false;
		private $post_id;
		private $post_type;
		private $topic_type = 'topic';
		private $forum_type = 'forum';
		private $reply_type = 'reply';

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();

			if ( is_admin() || is_bbpress() ) {
				// load sharing buttons code if sharing features exist and are enabled
				if ( array_key_exists( 'ssb', $this->p->is_avail ) &&
					$this->p->is_avail['ssb'] === true ) {
					$classname = __CLASS__.'Sharing';
					$this->sharing = new $classname( $this->p );
				}
			}
		}
	}
}

if ( ! class_exists( 'NgfbGplForumBbpressSharing' ) && class_exists( 'NgfbSharing' ) ) {

	class NgfbGplForumBbpressSharing {

		private $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();

			$this->p->util->add_plugin_filters( $this, array( 
				'get_defaults' => 1,
			) );

			if ( is_admin() ) {
				$this->p->util->add_plugin_filters( $this, array( 
					'style_tabs' => 1,
					'style_bbp_single_rows' => 2,
					'sharing_show_on' => 2,
				) );
			}
		}

		/* Purpose: Create default options for the sanitation process, so it doesn't strip-out non-existing options */
		public function filter_get_defaults( $opts_def ) {
			$opts_def['buttons_css_bbp_single'] = '/* Save an empty style text box to reload the default example styles.
 * These styles are provided as examples only - modifications may be 
 * necessary to customize the layout for your website. Social sharing
 * buttons can be aligned vertically, horizontally, floated, etc.
 */

.ngfb-bbp_single-buttons { 
	display:block;
	margin:10px auto;
	text-align:center;
}';
			// the default 'Show Button in' for 'bbPress Single' is unchecked
			foreach ( $this->p->cf['opt']['pre'] as $name => $prefix )
				$opts_def[$prefix.'_on_bbp_single'] = 0;
			return $opts_def;
		}

		/* Purpose: Add a 'bbPress Single' tab to the Style settings */
		public function filter_style_tabs( $tabs ) {
			$tabs['bbp_single'] = 'bbPress Single';
			return $tabs;
		}

		/* Purpose: Add css input textarea for the 'bbPress Single' style tab */
		public function filter_style_bbp_single_rows( $rows, $form ) {
			$rows[] = '<td class="textinfo">
			<p>Social sharing buttons added at the top of bbPress Single Templates are assigned the 
			\'ngfb-bbp_single-buttons\' class, which itself contains the 
			\'ngfb-buttons\' class -- a common class for all the sharing buttons 
			(see the All Buttons tab).</p> 
			<p>Example:</p><pre>
.ngfb-bbp_single-buttons 
    .ngfb-buttons
        .facebook-button { }</pre></td>'.
			'<td class="blank tall code">'.$form->get_hidden( 'buttons_css_bbp_single' ).
				$this->p->options['buttons_css_bbp_single'].'</td>';
			return $rows;
		}

		/* Purpose: Include the 'bbPress Single' checkbox in the 'Show Button in' options */
		public function filter_sharing_show_on( $show_on = array(), $prefix ) {
			switch ( $prefix ) {
				case 'pin':
					break;
				default:
					$show_on['bbp_single'] = 'bbPress Single';
					$this->p->options[$prefix.'_on_bbp_single:is'] = 'disabled';
					break;
			}
			return $show_on;
		}
	}
}

?>
