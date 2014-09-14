<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbGplAdminStyle' ) ) {

	class NgfbGplAdminStyle {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->util->add_plugin_filters( $this, array( 
				'style_sharing_rows' => 2,
				'style_content_rows' => 2,
				'style_excerpt_rows' => 2,
				'style_sidebar_rows' => 2,
				'style_shortcode_rows' => 2,
				'style_widget_rows' => 2,
				'style_admin_edit_rows' => 2,
			) );
		}

		public function filter_style_common_rows( &$rows, &$form, $idx ) {
			$text = $this->p->msgs->get( 'info-style-'.$idx );
			if ( isset( $this->p->options['buttons_preset_'.$idx] ) ) {
				$text .= '<p><strong>The social sharing button options for the '.$idx.
				' style are subject to preset values, selected on the '.
				$this->p->util->get_admin_url( 'sharing#sucom-tab_sharing_preset', 'Sharing Buttons settings page' ).
				', to modify their action (share vs like), size, and counter orientation.</strong> '.
				'The width and height values in your CSS should reflect these presets (if any).</p>';
				$text .= '<p><strong>Selected preset:</strong> '.
					( empty( $this->p->options['buttons_preset_'.$idx] ) ? '[none]' :
						$this->p->options['buttons_preset_'.$idx] ).'</p>';
			}
			$rows[] = '<td class="textinfo">'.$text.'</td>'.
			'<td class="blank tall code">'.$form->get_hidden( 'buttons_css_'.$idx ).
				$this->p->options['buttons_css_'.$idx].'</td>';
			return $rows;
		}

		public function filter_style_sharing_rows( $rows, $form ) {
			return $this->filter_style_common_rows( $rows, $form, 'sharing' );
		}

		public function filter_style_content_rows( $rows, $form ) {
			return $this->filter_style_common_rows( $rows, $form, 'content' );
		}

		public function filter_style_excerpt_rows( $rows, $form ) {
			return $this->filter_style_common_rows( $rows, $form, 'excerpt' );
		}

		public function filter_style_sidebar_rows( $rows, $form ) {
			$rows = array_merge( $rows, $this->filter_style_common_rows( $rows, $form, 'sidebar' ) );
			$rows[] = $this->p->util->th( 'Sidebar Javascript', null, 'buttons_js_sidebar' ).
			'<td class="blank average code">'.$form->get_hidden( 'buttons_js_sidebar' ).
				$this->p->options['buttons_js_sidebar'].'</td>';
			return $rows;
		}

		public function filter_style_shortcode_rows( $rows, $form ) {
			return $this->filter_style_common_rows( $rows, $form, 'shortcode' );
		}

		public function filter_style_widget_rows( $rows, $form ) {
			return $this->filter_style_common_rows( $rows, $form, 'widget' );
		}

		public function filter_style_admin_edit_rows( $rows, $form ) {
			return $this->filter_style_common_rows( $rows, $form, 'admin_edit' );
		}
	}
}

?>
