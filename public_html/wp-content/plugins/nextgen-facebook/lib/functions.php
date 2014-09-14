<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! function_exists( 'ngfb_get_social_buttons' ) ) {
	function ngfb_get_social_buttons( $ids = array(), $atts = array() ) {
		return ngfb_get_sharing_buttons( $ids, $atts );
	}
}

if ( ! function_exists( 'ngfb_get_sharing_buttons' ) ) {
	function ngfb_get_sharing_buttons( $ids = array(), $atts = array() ) {
		global $ngfb;
		if ( $ngfb->is_avail['ssb'] ) {
			if ( $ngfb->is_avail['cache']['transient'] ) {
				$cache_salt = __METHOD__.'(lang:'.SucomUtil::get_locale().'_url:'.$ngfb->util->get_sharing_url().
					'_ids:'.( implode( '_', $ids ) ).'_atts:'.( implode( '_', $atts ) ).')';
				$cache_id = $ngfb->cf['lca'].'_'.md5( $cache_salt );
				$cache_type = 'object cache';
				$ngfb->debug->log( $cache_type.': transient salt '.$cache_salt );
				$html = get_transient( $cache_id );
				if ( $html !== false ) {
					$ngfb->debug->log( $cache_type.': html retrieved from transient '.$cache_id );
					return $ngfb->debug->get_html().$html;
				}
			}
			$html = '<!-- '.$ngfb->cf['lca'].' sharing buttons begin -->' .
				$ngfb->sharing->get_js( 'sharing-buttons-header', $ids ) .
				$ngfb->sharing->get_html( $ids, $atts ) .
				$ngfb->sharing->get_js( 'sharing-buttons-footer', $ids ) .
				'<!-- '.$ngfb->cf['lca'].' sharing buttons end -->';
	
			if ( $ngfb->is_avail['cache']['transient'] ) {
				set_transient( $cache_id, $html, $ngfb->cache->object_expire );
				$ngfb->debug->log( $cache_type.': html saved to transient '.$cache_id.' ('.$ngfb->cache->object_expire.' seconds)');
			}
		} else $html = '<!-- '.$ngfb->cf['lca'].' sharing sharing buttons disabled -->';
		return $ngfb->debug->get_html().$html;
	}
}

?>
