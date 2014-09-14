<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSitesubmenuSitelicenses' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbSitesubmenuSitelicenses extends NgfbAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function set_form() {
			$def_site_opts = $this->p->opt->get_site_defaults();
			$this->form = new SucomForm( $this->p, NGFB_SITE_OPTIONS_NAME, $this->p->site_options, $def_site_opts );
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_licenses', 'License Network Settings', array( &$this, 'show_metabox_licenses' ), $this->pagehook, 'normal' );

			// add a class to set a minimum width for the network postboxes
			add_filter( 'postbox_classes_'.$this->pagehook.'_'.$this->pagehook.'_licenses', array( &$this, 'add_class_postbox_network' ) );
		}

		public function add_class_postbox_network( $classes ) {
			array_push( $classes, 'admin_postbox_network' );
			return $classes;
		}

		public function show_metabox_licenses() {
			echo '<table class="sucom-setting licenses-metabox" style="padding-bottom:10px">'."\n";
			echo '<tr><td colspan="4">'.$this->p->msgs->get( 'info-plugin-tid' ).'</td></tr>'."\n";

			foreach ( $this->p->cf['plugin'] as $lca => $info ) {
				$url = '';
				$links = '';
				$qty_used = class_exists( 'SucomUpdate' ) ?
					SucomUpdate::get_option( $lca, 'qty_used' ) : false;

				if ( ! empty( $info['url']['download'] ) ) {
					$url = $info['url']['download'];
					$links .= ' | <a href="'.$info['url']['download'].'" target="_blank">Download the Free Version</a>';
				}

				if ( ! empty( $info['url']['purchase'] ) ) {
					$url = $info['url']['purchase'];
					if ( $this->p->cf['lca'] === $lca || $this->p->check->aop() )
						$links .= ' | <a href="'.$info['url']['purchase'].'" target="_blank">Purchase a Pro License</a>';
					else $links .= ' | <em>Purchase a Pro License</em>';
				}

				if ( ! empty( $info['img']['icon-small'] ) )
					$img = $info['img']['icon-small'];
				else $img = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';

				// logo image
				echo '<tr><td style="width:140px;padding:10px 0;" rowspan="3" valign="top" align="left">';
				if ( ! empty( $url ) ) echo '<a href="'.$url.'" target="_blank">';
				echo '<img src="'.$img.'" width="125" height="125" class="highlight">';
				if ( ! empty( $url ) ) echo '</a>';
				echo '</td>';

				// plugin name
				echo '<td colspan="3" style="padding:10px 0 0 0;">
					<p><strong>'.$info['name'].'</strong></p>';

				if ( ! empty( $info['desc'] ) )
					echo '<p>'.$info['desc'].'</p>';

				if ( ! empty( $links ) )
					echo '<p>'.trim( $links, ' |' ).'</p>';

				echo '</td></tr>'."\n";

				if ( ! empty( $info['url']['purchase'] ) || 
					! empty( $this->p->options['plugin_'.$lca.'_tid'] ) ) {
					if ( $this->p->cf['lca'] === $lca || $this->p->check->aop() ) {
						echo '<tr>'.$this->p->util->th( 'Authentication ID', 'medium' );
						echo '<td class="tid">'.$this->form->get_input( 'plugin_'.$lca.'_tid', 'tid mono' ).'</td>';
						echo '<td><p>'.( empty( $qty_used ) ? '' : $qty_used.' Licenses Assigned' ).'</p></td></tr>'."\n";
					} else {
						echo '<tr>'.$this->p->util->th( 'Authentication ID', 'medium' );
						echo '<td class="blank">'.$this->form->get_no_input( 'plugin_'.$lca.'_tid', 'tid mono' ).'</td>';
						echo '<td>'.$this->p->msgs->get( 'pro-option-msg' ).'</td></tr>'."\n";
					}
					echo '<tr>'.$this->p->util->th( 'Site Use', 'medium' );
					echo '<td>'.$this->form->get_select( 'plugin_'.$lca.'_tid:use', $this->p->cf['form']['site_option_use'], 'site_use' );
					echo '</td><td style="padding-bottom:10px;"><p>&nbsp;</p></td></tr>'."\n";
				} else {
					echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
					echo '<tr><td style="padding-bottom:10px;" colspan="3"><p>&nbsp;</p></td></tr>'."\n";
				}
			}
			echo '</table>'."\n";
		}
	}
}

?>
