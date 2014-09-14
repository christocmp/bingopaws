<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbGplSocialBuddypress' ) ) {

	class NgfbGplSocialBuddypress {

		private $p;
		private $sharing;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();

			if ( is_admin() || bp_current_component() ) {
				$this->p->util->add_plugin_filters( $this, array( 
					'post_types' => 3,
				) );
				// load sharing buttons code if sharing features exist and are enabled
				if ( array_key_exists( 'ssb', $this->p->is_avail ) &&
					$this->p->is_avail['ssb'] === true ) {
					$classname = __CLASS__.'Sharing';
					$this->sharing = new $classname( $this->p );
				}
			}
		}

		/* Purpose: Provide custom post types for ngfb, without having to register them with WordPress */
		public function filter_post_types( $pt, $prefix, $output = 'objects' ) {
			if ( $prefix == 'buttons' ) {
				if ( $output == 'objects' ) {
					foreach ( array( 
						'activity' => 'Activity',
						'group' => 'Group',
						'members' => 'Members',
					) as $name => $desc ) {
						$pt['bp_'.$name] = new stdClass();
						$pt['bp_'.$name]->public = true;
						$pt['bp_'.$name]->name = 'bp_'.$name;
						$pt['bp_'.$name]->label = $desc;
						$pt['bp_'.$name]->description = 'BuddyPress '.$desc;
					}
				}
			}
			return $pt;
		}
	}
}

if ( ! class_exists( 'NgfbGplSocialBuddypressSharing' ) && class_exists( 'NgfbSharing' ) ) {

	class NgfbGplSocialBuddypressSharing {

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
					'style_bp_activity_rows' => 2,
					'sharing_show_on' => 2,
				) );
			}
		}

		/* Purpose: Create default options for the sanitation process, so it doesn't strip-out non-existing options */
		public function filter_get_defaults( $opts_def ) {
			$opts_def['buttons_css_bp_activity'] = '/* Save an empty style text box to reload the default example styles.
 * These styles are provided as examples only - modifications may be 
 * necessary to customize the layout for your website. Social sharing
 * buttons can be aligned vertically, horizontally, floated, etc.
 */

.ngfb-bp_activity-buttons { 
	display:block;
	margin:10px auto;
	text-align:center;
}';
			// the default 'Show Button in' for 'BP Activity' is unchecked
			foreach ( $this->p->cf['opt']['pre'] as $name => $prefix )
				$opts_def[$prefix.'_on_bp_activity'] = 0;
			return $opts_def;
		}


		/* Purpose: Include the 'BP Activity' checkbox in the 'Show Button in' options */
		public function filter_sharing_show_on( $show_on = array(), $prefix ) {
			switch ( $prefix ) {
				case 'pin':
					break;
				default:
					$show_on['bp_activity'] = 'BP Activity';
					$this->p->options[$prefix.'_on_bp_activity:is'] = 'disabled';
					break;
			}
			return $show_on;
		}
		/* Purpose: Add a 'BuddyPress Activity' tab to the Style settings */
		public function filter_style_tabs( $tabs ) {
			$tabs['bp_activity'] = 'BuddyPress Activity';
			return $tabs;
		}

		/* Purpose: Add css input textarea for the 'BuddyPress Activity' style tab */
		public function filter_style_bp_activity_rows( $rows, $form ) {
			$rows[] = '<td class="textinfo">
			<p>Social sharing buttons added to BuddyPress Activities are assigned the 
			\'ngfb-bp_activity-buttons\' class, which itself contains the 
			\'ngfb-buttons\' class -- a common class for all the sharing buttons 
			(see the All Buttons tab).</p> 
			<p>Example:</p><pre>
.ngfb-bp_activity-buttons 
    .ngfb-buttons
        .facebook-button { }</pre></td>'.
			'<td class="blank tall code">'.$form->get_hidden( 'buttons_css_bp_activity' ).
				$this->p->options['buttons_css_bp_activity'].'</td>';
			return $rows;
		}
	}
}

?>
