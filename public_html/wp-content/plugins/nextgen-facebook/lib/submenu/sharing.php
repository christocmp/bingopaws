<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuSharing' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbSubmenuSharing extends NgfbAdmin {

		public $website = array();

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->menu_id = $id;
			$this->menu_name = $name;
			$this->set_objects();
			$this->p->util->add_plugin_filters( $this, array( 
				'messages' => 2,		// default messages filter
			) );
		}

		private function set_objects() {
			foreach ( $this->p->cf['plugin']['ngfb']['lib']['website'] as $id => $name ) {
				$classname = NgfbConfig::load_lib( false, 'website/'.$id, 'ngfbsubmenusharing'.$id );
				if ( $classname !== false && class_exists( $classname ) )
					$this->website[$id] = new $classname( $this->p );
			}
		}

		public function filter_messages( $text, $idx ) {
			switch ( $idx ) {
				/*
				 * 'Social Buttons' settings
				 */
				case ( strpos( $idx, 'tooltip-buttons_' ) !== false ? true : false ):
					switch ( $idx ) {
						case ( strpos( $idx, 'tooltip-buttons_pos_' ) === false ? false : true ):
							$text = 'Individual social sharing button(s) must also be enabled below.';
							break;
						case 'tooltip-buttons_pos_excerpt':
							$text = 'Individual social sharing button(s) must also be enabled below.';
							break;
						case 'tooltip-buttons_on_index':
							$text = 'Add the following social sharing buttons to each entry of an index webpage (<strong>non-static</strong> 
							homepage, category, archive, etc.). By Default, social sharing buttons are <em>not</em> included on index webpages 
							(default is unchecked). You must also enable the buttons you want to display by choosing to show the buttons on 
							the content or excerpt.';
							break;
						case 'tooltip-buttons_on_front':
							$text = 'If a static Post or Page has been chosen for the homepage, add the following
							social sharing buttons to the static homepage as well (default is unchecked).
							You must also enable the buttons you want to display by choosing to show the buttons on the content or excerpt.';
							break;
						case 'tooltip-buttons_add_to':
							$text = 'Enabled social sharing buttons are added to the Post, Page, Media and Product custom post types by default.
							If your theme (or another plugin) supports additional custom post types, and you would like to include
							social sharing buttons on these webpages, check the appropriate option(s) here.';
							break;
						/*
						 * Other settings
						 */
						default:
							$text = apply_filters( $this->p->cf['lca'].'_tooltip_buttons', $text, $idx );
							break;
					}
					break;
			}
			return $text;
		}

		// called by each website's settings class to display a list of checkboxes
		// Show Button in: Content, Excerpt, Admin Edit, etc.
		protected function show_on_checkboxes( $prefix ) {
			$cols = 0;
			$html = '<table>';
			$show_on = apply_filters( $this->p->cf['lca'].'_sharing_show_on', 
				NgfbSharing::$cf['sharing']['show_on'], $prefix );
			foreach ( $show_on as $suffix => $desc ) {
				$cols++;
				$class = array_key_exists( $prefix.'_on_'.$suffix.':is', $this->p->options ) &&
					$this->p->options[$prefix.'_on_'.$suffix.':is'] === 'disabled' &&
					! $this->p->check->aop() ? 'show_on blank' : 'show_on';
				if ( $cols == 1 )
					$html .= '<tr><td class="'.$class.'">';
				else $html .= '<td class="'.$class.'">';
				$html .= $this->form->get_checkbox( $prefix.'_on_'.$suffix ).$desc.'&nbsp; ';
				if ( $cols == 3 ) {
					$html .= '</td></tr>';
					$cols = 0;
				} else $html .= '</td>';
			}
			$html .= $cols < 3 ? '</tr>' : '';
			$html .= '</table>';
			return $html;
		}

		protected function add_meta_boxes() {
			$col = 0;
			$row = 0;

			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_sharing', 'Social Sharing Buttons', array( &$this, 'show_metabox_sharing' ), $this->pagehook, 'normal' );

			foreach ( $this->p->cf['plugin']['ngfb']['lib']['website'] as $id => $name ) {
				$classname = __CLASS__.$id;
				if ( class_exists( $classname ) ) {
					$col = $col == 1 ? 2 : 1;
					$row = $col == 1 ? $row + 1 : $row;
					$pos_id = 'website-row-'.$row.'-col-'.$col;
					$name = $name == 'GooglePlus' ? 'Google+' : $name;
					add_meta_box( $this->pagehook.'_'.$id, $name, array( &$this->website[$id], 'show_metabox_website' ), $this->pagehook, $pos_id );
					add_filter( 'postbox_classes_'.$this->pagehook.'_'.$this->pagehook.'_'.$id, array( &$this, 'add_class_postbox_website' ) );
					$this->website[$id]->form = &$this->get_form_ref();
				}
			}

			// these metabox ids should be closed by default (array_diff() selects everything except those listed)
			$ids = array_diff( array_keys( $this->p->cf['plugin']['ngfb']['lib']['website'] ), array( 'facebook', 'gplus', 'twitter' ) );
			$this->p->addons['util']['user']->reset_metabox_prefs( $this->pagehook, $ids, 'closed' );
		}

		public function add_class_postbox_website( $classes ) {
			array_push( $classes, 'display_'.$this->p->options['plugin_display'] );
			array_push( $classes, 'admin_postbox_website' );
			return $classes;
		}

		public function show_metabox_sharing() {
			$metabox = 'sharing';
			$tabs = array(
				'include' => 'Include Buttons',
				'position' => 'Buttons Position' 
			);

			if ( $this->p->options['plugin_display'] == 'all' )
				$tabs['preset'] = 'Preset Options';

			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs', $tabs );
			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', array(), $this->form ) );
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		public function show_metabox_website() {
			echo '<table class="sucom-setting">'."\n";
			foreach ( $this->get_rows( null, null ) as $row ) 
				echo '<tr>'.$row.'</tr>';
			echo '</table>'."\n";
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();
			switch ( $metabox.'-'.$key ) {
				case 'sharing-position':
					$rows[] = $this->p->util->th( 'Position in Content Text', null, 'buttons_pos_content' ).
					'<td>'.$this->form->get_select( 'buttons_pos_content',
						array( 'top' => 'Top', 'bottom' => 'Bottom', 'both' => 'Both Top and Bottom' ) ).'</td>';

					$rows[] = $this->p->util->th( 'Position in Excerpt Text', null, 'buttons_pos_excerpt' ).
					'<td>'.$this->form->get_select( 'buttons_pos_excerpt', 
						array( 'top' => 'Top', 'bottom' => 'Bottom', 'both' => 'Both Top and Bottom' ) ).'</td>';
					break;

				case 'sharing-include':
					$rows[] = '<tr><td colspan="2">'.$this->p->msgs->get( 'info-'.$metabox.'-'.$key ).'</td></tr>';

					$rows[] = $this->p->util->th( 'Include on Index Webpages', null, 'buttons_on_index' ).
					'<td>'.$this->form->get_checkbox( 'buttons_on_index' ).'</td>';

					$rows[] = $this->p->util->th( 'Include on Static Homepage', null, 'buttons_on_front' ).
					'<td>'.$this->form->get_checkbox( 'buttons_on_front' ).'</td>';
					break;
			}
			return $rows;
		}
	}
}

?>
