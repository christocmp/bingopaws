<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'SucomScript' ) ) {

	class SucomScript {

		private $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();

			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
		}

		public function admin_enqueue_scripts( $hook ) {
			$url_path = constant( $this->p->cf['uca'].'_URLPATH' );
			wp_register_script( 
				'jquery-qtip', 
				$url_path.'js/ext/jquery-qtip.min.js', 
				array( 'jquery' ), 
				'1.0.0-RC3',
				true
			);
			wp_register_script( 
				'sucom_tooltips', 
				$url_path.'js/com/jquery-tooltips.min.js', 
				array( 'jquery' ), 
				$this->p->cf['plugin'][$this->p->cf['lca']]['version'], 
				true
			);
			wp_register_script( 
				'sucom_postmeta', 
				$url_path.'js/com/jquery-postmeta.min.js',
				array( 'jquery' ),
				$this->p->cf['plugin'][$this->p->cf['lca']]['version'],
				true
			);
			// don't load our javascript where we don't need it
			switch ( $hook ) {
				case 'user-edit.php' :
				case 'profile.php' :
				case 'post.php' :
				case 'post-new.php' :
				case ( preg_match( '/_page_'.$this->p->cf['lca'].'-/', $hook ) ? true : false ) :
					wp_enqueue_script( 'jquery' );
					wp_enqueue_script( 'jquery-qtip' );
					wp_enqueue_script( 'sucom_tooltips' );
					wp_enqueue_script( 'sucom_postmeta' );
					break;
			}
		}
	}
}

?>
