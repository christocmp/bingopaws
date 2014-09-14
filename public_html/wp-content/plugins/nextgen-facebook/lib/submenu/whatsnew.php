<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuWhatsnew' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbSubmenuWhatsnew extends NgfbAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function add_meta_boxes() {
			$lca = $this->p->cf['lca'];
			$short_aop = $this->p->cf['plugin'][$lca]['short'].
				( $this->p->is_avail['aop'] ? ' Pro' : '' );

			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( 
				$this->pagehook.'_whatsnew', 
				$short_aop.' version '.$this->p->cf['plugin'][$lca]['version'], 
				array( &$this, 'show_metabox_whatsnew' ), 
				$this->pagehook, 
				'normal'
			);
		}

		public function show_metabox_whatsnew() {
			echo '<table class="sucom-setting whatsnew-metabox"><tr><td>';
			echo $this->p->msgs->get( 'info-review' );
			echo $this->p->util->get_remote_content( 
				'', 
				constant( $this->p->cf['uca'].'_PLUGINDIR' ).'whatsnew.html',
				$this->p->cf['*']['version']
			);
			echo '</td></tr></table>';
		}
	}
}

?>
