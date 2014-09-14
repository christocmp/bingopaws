<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuSharingLinkedin' ) && class_exists( 'NgfbSubmenuSharing' ) ) {

	class NgfbSubmenuSharingLinkedin extends NgfbSubmenuSharing {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function filter_get_defaults( $opts_def ) {
			return array_merge( $opts_def, self::$cf['opt']['defaults'] );
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();

			$rows[] = $this->p->util->th( 'Show Button in', 'short' ).'<td>'.
			( $this->show_on_checkboxes( 'linkedin' ) ).'</td>';

			$rows[] = $this->p->util->th( 'Preferred Order', 'short' ).'<td>'.
			$this->form->get_select( 'linkedin_order', 
				range( 1, count( $this->p->admin->submenu['sharing']->website ) ), 
					'short' ).'</td>';

			if ( $this->p->options['plugin_display'] == 'all' ) {
				$rows[] = $this->p->util->th( 'JavaScript in', 'short' ).'<td>'.
				$this->form->get_select( 'linkedin_js_loc', $this->p->cf['form']['js_locations'] ).'</td>';
			}

			$rows[] = $this->p->util->th( 'Counter Mode', 'short' ).'<td>'.
			$this->form->get_select( 'linkedin_counter', 
				array( 
					'none' => '',
					'right' => 'Horizontal',
					'top' => 'Vertical',
				)
			).'</td>';

			if ( $this->p->options['plugin_display'] == 'all' ) {
				$rows[] = $this->p->util->th( 'Zero in Counter', 'short' ).'<td>'.
				$this->form->get_checkbox( 'linkedin_showzero' ).'</td>';
			}

			return $rows;
		}
	}
}

if ( ! class_exists( 'NgfbSharingLinkedin' ) ) {

	class NgfbSharingLinkedin {

		private static $cf = array(
			'opt' => array(				// options
				'defaults' => array(
					'linkedin_on_content' => 0,
					'linkedin_on_excerpt' => 0,
					'linkedin_on_admin_edit' => 1,
					'linkedin_on_sidebar' => 0,
					'linkedin_order' => 5,
					'linkedin_js_loc' => 'header',
					'linkedin_counter' => 'right',
					'linkedin_showzero' => 1,
				),
			),
		);

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->util->add_plugin_filters( $this, array( 'get_defaults' => 1 ) );
		}

		public function filter_get_defaults( $opts_def ) {
			return array_merge( $opts_def, self::$cf['opt']['defaults'] );
		}

		public function get_html( $atts = array(), &$opts = array() ) {
			if ( empty( $opts ) ) 
				$opts =& $this->p->options;
			$use_post = array_key_exists( 'use_post', $atts ) ? $atts['use_post'] : true;
			$source_id = $this->p->util->get_source_id( 'linkedin', $atts );
			$atts['add_page'] = array_key_exists( 'add_page', $atts ) ? $atts['add_page'] : true;	// get_sharing_url() argument
			$atts['url'] = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $source_id ) : 
				apply_filters( $this->p->cf['lca'].'_sharing_url', $atts['url'],
					$use_post, $atts['add_page'], $source_id );

			$html = '<!-- LinkedIn Button --><div '.$this->p->sharing->get_css( 'linkedin', $atts ).'><script type="IN/Share" data-url="'.$atts['url'].'"';
			$html .= empty( $opts['linkedin_counter'] ) ? '' : ' data-counter="'.$opts['linkedin_counter'].'"';
			$html .= empty( $opts['linkedin_showzero'] ) ? '' : ' data-showzero="true"';
			$html .= '></script></div>';

			$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );
			return $html."\n";
		}
		
		public function get_js( $pos = 'id' ) {
			$this->p->debug->mark();
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$js_url = $this->p->util->get_cache_url( $prot.'//platform.linkedin.com/in.js' );

			return  '<script type="text/javascript" id="linkedin-script-'.$pos.'">'.$this->p->cf['lca'].'_insert_js( "linkedin-script-'.$pos.'", "'.$js_url.'" );</script>'."\n";
		}
	}
}

?>
