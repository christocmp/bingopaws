<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoHead' ) ) {

	class WpssoHead {

		private $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->p->util->add_plugin_filters( $this, array( 
				'head_cache_salt' => 2,		// modify the cache salt for certain crawlers
			) );
			add_filter( 'language_attributes', array( &$this, 'add_doctype' ) );
			add_action( 'wp_head', array( &$this, 'add_header' ), WPSSO_HEAD_PRIORITY );
		}

		public function add_doctype( $doctype ) {
			$obj = $this->p->util->get_post_object( false );
			$post_id = empty( $obj->ID ) || empty( $obj->post_type ) ? 0 : $obj->ID;
			$post_type = '';
			$item_type = 'Blog';	// default value for non-singular webpages
			if ( is_singular() ) {
				if ( ! empty( $obj->post_type ) )
					$post_type = $obj->post_type;
				switch ( $post_type ) {
					case 'article':
					case 'book':
					case 'blog':
					case 'event':
					case 'organization':
					case 'person':
					case 'product':
					case 'review':
					case 'other':
						$item_type = ucfirst( $post_type );
						break;
					case 'local.business':
						$item_type = 'LocalBusiness';
						break;
					default:
						$item_type = 'Article';
						break;
				}
			} elseif ( ( ! is_search() && ! empty( $this->p->options['og_def_author_on_index'] ) && ! empty( $this->p->options['og_def_author_id'] ) ) || 
				( is_search() && ! empty( $this->p->options['og_def_author_on_search'] ) && ! empty( $this->p->options['og_def_author_id'] ) ) )
					$item_type = 'Article';

			$item_type = apply_filters( $this->p->cf['lca'].'_item_type', $item_type );

			if ( strpos( $doctype, ' itemscope itemtype="http://schema.org/' ) === false )
				$doctype .= ' itemscope itemtype="http://schema.org/'.$item_type.'"';

			return $doctype;
		}

		public function filter_head_cache_salt( $salt, $use_post = false ) {
			switch ( SucomUtil::crawler_name() ) {
				case 'pinterest':
					$salt .= '_crawler:'.SucomUtil::crawler_name();
					break;
			}
			return $salt;
		}

		// called by WP wp_head action
		public function add_header() {
			// add various function test results top-most in the debug log
			// hook into wpsso_is_functions to extend the default array of function names
			if ( $this->p->debug->is_on() ) {
				$is_functions = array( 
					'is_author',
					'is_archive',
					'is_category',
					'is_tag',
					'is_tax',
					'is_home',
					'is_search',
					'is_singular',
					'is_attachment',
					'is_product',
					'is_product_category',
					'is_product_tag',
				);
				$is_functions = apply_filters( $this->p->cf['lca'].'_is_functions', $is_functions );
				foreach ( $is_functions as $function ) 
					if ( function_exists( $function ) && $function() )
						$this->p->debug->log( $function.'() = true' );
			}

			if ( $this->p->is_avail['metatags'] ) {
				echo $this->get_header_html();
			} else echo "\n<!-- ".$this->p->cf['lca']." meta tags are disabled -->\n";

			// include additional information when debug mode is on
			if ( $this->p->debug->is_on() ) {
				$defined_constants = get_defined_constants( true );
				$defined_constants['user']['WPSSO_NONCE'] = '********';
				$this->p->debug->show_html( SucomUtil::preg_grep_keys( '/^WPSSO_/', $defined_constants['user'] ), 'wpsso constants' );

				$opts = $this->p->options;
				foreach ( $opts as $key => $val ) {
					switch ( true ) {
						case ( strpos( $key, '_css_' ) !== false ):
						case ( strpos( $key, '_js_' ) !== false ):
						case ( preg_match( '/_(key|tid)$/', $key ) ):
							$opts[$key] = '********';
					}
				}
				$this->p->debug->show_html( print_r( $this->p->is_avail, true ), 'available features' );
				$this->p->debug->show_html( print_r( $this->p->check->get_active(), true ), 'active plugins' );
				$this->p->debug->show_html( null, 'debug log' );
				$this->p->debug->show_html( $opts, 'wpsso settings' );

				// on singular webpages, show the custom social settings
				if ( is_singular() && ( $obj = $this->p->util->get_post_object() ) !== false ) {
					$post_id = empty( $obj->ID ) || empty( $obj->post_type ) ? 0 : $obj->ID;
					if ( ! empty( $post_id ) && isset( $this->p->addons['util']['postmeta'] ) ) {
						$post_opts = $this->p->addons['util']['postmeta']->get_options( $post_id );
						$this->p->debug->show_html( $post_opts, 'wpsso post_id '.$post_id.' social settings' );
					}
				}
			}
		}

		public function get_header_html( $use_post = false, $read_cache = true, &$meta_og = array() ) {
			$html = "\n<!-- ".$this->p->cf['lca']." meta tags begin -->\n";
			foreach ( $this->get_header_array( $use_post, $read_cache, $meta_og ) as $val )
				if ( isset( $val[0] ) )
					$html .= $val[0];
			$html .= "<!-- ".$this->p->cf['lca']." meta tags end -->\n";
			return $html;
		}

		public function get_header_array( $use_post = false, $read_cache = true, &$meta_og = array() ) {
			$lca = $this->p->cf['lca'];
			$short_aop = $this->p->cf['plugin'][$lca]['short'].
				( $this->p->is_avail['aop'] ? ' Pro' : '' );
			$obj = $this->p->util->get_post_object( $use_post );
			$post_id = empty( $obj->ID ) || empty( $obj->post_type ) || 
				( ! is_singular() && $use_post === false ) ? 0 : $obj->ID;
			$sharing_url = $this->p->util->get_sharing_url( $use_post );
			$author_id = 0;

			$header_array = array();
			if ( $this->p->is_avail['cache']['transient'] ) {
				$cache_salt = __METHOD__.'('.apply_filters( $lca.'_head_cache_salt', 
					'lang:'.SucomUtil::get_locale().'_post:'.$post_id.'_url:'.$sharing_url, $use_post ).')';
				$cache_id = $lca.'_'.md5( $cache_salt );
				$cache_type = 'object cache';
				$this->p->debug->log( $cache_type.': transient salt '.$cache_salt );
				if ( apply_filters( $this->p->cf['lca'].'_header_read_cache', $read_cache ) ) {
					$header_array = get_transient( $cache_id );
					if ( $header_array !== false ) {
						$this->p->debug->log( $cache_type.': header array retrieved from transient '.$cache_id );
						return $header_array;
					}
				}
			}

			/**
			 * Define an author_id, if one is available
			 */
			if ( is_singular() || $use_post !== false ) {
				if ( ! empty( $obj->post_author ) )
					$author_id = $obj->post_author;
				elseif ( ! empty( $this->p->options['seo_def_author_id'] ) )
					$author_id = $this->p->options['seo_def_author_id'];

			} elseif ( is_author() || ( is_admin() && ( $screen = get_current_screen() ) && ( $screen->id === 'user-edit' || $screen->id === 'profile' ) ) ) {
				$author = $this->p->util->get_author_object();
				$author_id = $author->ID;

			} elseif ( ( ! ( is_singular() || $use_post !== false ) && 
				! is_search() && ! empty( $this->p->options['seo_def_author_on_index'] ) && ! empty( $this->p->options['seo_def_author_id'] ) ) || 
				( is_search() && ! empty( $this->p->options['seo_def_author_on_search'] ) && ! empty( $this->p->options['seo_def_author_id'] ) ) )
					$author_id = $this->p->options['seo_def_author_id'];

			/**
			 * Open Graph, Twitter Card
			 *
			 * The Twitter Card meta tags are added by the WpssoHeadTwittercard class using an 'wpsso_og' filter hook.
			 */
			if ( $this->p->is_avail['opengraph'] )
				$meta_og = $this->p->og->get_array( $meta_og, $use_post );

			/**
			 * Name / SEO meta tags
			 */
			$meta_name = array();
			if ( isset( $this->p->options['seo_author_name'] ) && 
				$this->p->options['seo_author_name'] !== 'none' )
					$meta_name['author'] = $this->p->addons['util']['user']->get_author_name( $author_id, $this->p->options['seo_author_name'] );

			$meta_name['description'] = $this->p->webpage->get_description( $this->p->options['seo_desc_len'], 
				'...', $use_post, true, false, true, 'seo_desc' );	// add_hashtags = false, custom meta = seo_desc

			$meta_name = apply_filters( $lca.'_meta_name', $meta_name );

			/**
			 * Link relation tags
			 */
			$link_rel = array();
			if ( ! empty( $author_id ) )
				$link_rel['author'] = $this->p->addons['util']['user']->get_author_website_url( $author_id, $this->p->options['link_author_field'] );

			if ( ! empty( $this->p->options['link_publisher_url'] ) )
				$link_rel['publisher'] = $this->p->options['link_publisher_url'];

			$link_rel = apply_filters( $lca.'_link_rel', $link_rel );

			/**
			 * Schema meta tags
			 */
			$meta_schema = array();

			$meta_schema['description'] = $this->p->webpage->get_description( $this->p->options['og_desc_len'], 
				'...', $use_post, true, true, true, 'schema_desc' );	// custom meta = schema_desc

			$meta_schema = apply_filters( $lca.'_meta_schema', $meta_schema );

			/**
			 * Combine and return all meta tags
			 */
			$header_array = array_merge(
				$this->get_single_tag( 'meta', 'name', 'generator',
					$short_aop.' '.$this->p->cf['plugin'][$lca]['version'].
						( $this->p->check->aop() ? 'L' : 
							( $this->p->is_avail['aop'] ? 'U' : 'G' ) ) ),
				$this->get_tag_array( 'link', 'rel', $link_rel ),
				$this->get_tag_array( 'meta', 'name', $meta_name ),
				$this->get_tag_array( 'meta', 'itemprop', $meta_schema ),
				$this->get_tag_array( 'meta', 'property', $meta_og )
			);
 
			if ( apply_filters( $this->p->cf['lca'].'_header_set_cache', $this->p->is_avail['cache']['transient'] ) ) {
				set_transient( $cache_id, $header_array, $this->p->cache->object_expire );
				$this->p->debug->log( $cache_type.': header array saved to transient '.$cache_id.' ('.$this->p->cache->object_expire.' seconds)');
			}
			return $header_array;
		}

		/**
		 * Loops through the arrays (1 to 3 dimensions) and calls get_single_tag() for each
		 */
		private function get_tag_array( $tag = 'meta', $type = 'property', $tag_array ) {
			$this->p->debug->log( count( $tag_array ).' '.$tag.' '.$type.' to process' );
			$this->p->debug->log( $tag_array );
			$ret = array();
			if ( empty( $tag_array ) )
				return $ret;
			foreach ( $tag_array as $f_name => $f_val ) {					// 1st-dimension array (associative)
				if ( is_array( $f_val ) ) {
					foreach ( $f_val as $s_num => $s_val ) {			// 2nd-dimension array
						if ( SucomUtil::is_assoc( $s_val ) ) {
							ksort( $s_val );
							foreach ( $s_val as $t_name => $t_val )		// 3rd-dimension array (associative)
								$ret = array_merge( $ret, $this->get_single_tag( $tag, $type, $t_name, $t_val, $f_name.':'.( $s_num + 1 ) ) );
						} else $ret = array_merge( $ret, $this->get_single_tag( $tag, $type, $f_name, $s_val, $f_name.':'.( $s_num + 1 ) ) );
					}
				} else $ret = array_merge( $ret, $this->get_single_tag( $tag, $type, $f_name, $f_val ) );
			}
			return $ret;
		}

		private function get_single_tag( $tag = 'meta', $type = 'property', $name, $value = '', $comment = '' ) {

			// known exceptions for the 'property' $type
			if ( $tag === 'meta' && $type === 'property' && 
				( strpos( $name, 'twitter:' ) === 0 || strpos( $name, ':' ) === false ) )
					$type = 'name';

			$ret = array();
			$attr = $tag === 'link' ? 'href' : 'content';
			$log_pre = $tag.' '.$type.' '.$name;

			if ( empty( $this->p->options['add_'.$tag.'_'.$type.'_'.$name] ) ) {
				$this->p->debug->log( $log_pre.' is disabled (skipped)' );
				return $ret;

			} elseif ( $value === -1 ) {	// -1 is reserved, meaning use the defaults - exclude, just in case
				$this->p->debug->log( $log_pre.' value is -1 (skipped)' );
				return $ret;

			} elseif ( $value === '' || $value === null ) {	// allow for 0
				$this->p->debug->log( $log_pre.' value is empty (skipped)' );
				return $ret;

			} elseif ( is_array( $value ) ) {
				$this->p->debug->log( $log_pre.' value is an array (skipped)' );
				return $ret;

			} elseif ( is_object( $value ) ) {
				$this->p->debug->log( $log_pre.' value is an object (skipped)' );
				return $ret;
			}

			$charset = get_bloginfo( 'charset' );
			$value = htmlentities( $value, ENT_QUOTES, $charset, false );	// double_encode = false
			$this->p->debug->log( $log_pre.' = "'.$value.'"' );
			$comment_html = empty( $comment ) ? '' : '<!-- '.$comment.' -->';

			// add an additional secure_url meta tag for open graph images and videos
			if ( $tag === 'meta' && $type === 'property' &&
				( $name === 'og:image' || $name === 'og:video' ) && 
				strpos( $value, 'https:' ) === 0 && 
				! empty( $this->p->options['add_'.$tag.'_'.$type.'_'.$name.':secure_url'] ) ) {

				$secure_url = $value;
				$value = preg_replace( '/^https:/', 'http:', $value );

				$ret[] = array( 
					$comment_html.'<'.$tag.' '.$type.'="'.$name.':secure_url" '.$attr.'="'.$secure_url.'" />'."\n",
					$tag, $type, $name.':secure_url', $attr, $secure_url, $comment
				);

			} 
			
			$ret[] = array( 
				$comment_html.'<'.$tag.' '.$type.'="'.$name.'" '.$attr.'="'.$value.'" />'."\n",
				$tag, $type, $name, $attr, $value, $comment
			);

			return $ret;
		}
	}
}

?>
