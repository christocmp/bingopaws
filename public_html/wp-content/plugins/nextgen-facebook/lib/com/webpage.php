<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'SucomWebpage' ) ) {

	class SucomWebpage {

		private $p;
		private $shortcode = array();

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->set_objects();
		}

		private function set_objects() {
			if ( ! empty( $this->p->options['plugin_shortcodes'] ) ) {
				foreach ( $this->p->cf['plugin'] as $lca => $info ) {
					if ( isset( $info['lib']['shortcode'] ) && is_array( $info['lib']['shortcode'] ) ) {
						foreach ( $info['lib']['shortcode'] as $id => $name ) {
							$classname = apply_filters( $lca.'_load_lib', false, 'shortcode/'.$id );
							if ( $classname !== false && class_exists( $classname ) )
								$this->shortcode[$id] = new $classname( $this->p );
						}
					}
				}
			}

			if ( ! empty( $this->p->options['plugin_page_excerpt'] ) )
				add_post_type_support( 'page', array( 'excerpt' ) );

			if ( ! empty( $this->p->options['plugin_page_tags'] ) )
				register_taxonomy_for_object_type( 'post_tag', 'page' );
		}

		// called from Tumblr class
		public function get_quote() {
			global $post;
			if ( empty( $post ) ) 
				return '';
			$quote = apply_filters( $this->p->cf['lca'].'_quote_seed', '' );
			if ( $quote != '' )
				$this->p->debug->log( 'quote seed = "'.$quote.'"' );
			else {
				if ( has_excerpt( $post->ID ) ) 
					$quote = get_the_excerpt( $post->ID );
				else $quote = $post->post_content;
			}
			// remove shortcodes, etc., but don't strip html tags
			$quote = $this->p->util->cleanup_html_tags( $quote, false );

			return apply_filters( $this->p->cf['lca'].'_quote', $quote );
		}

		// called from Tumblr, Pinterest, and Twitter classes
		public function get_caption( $type = 'title', $length = 200, $use_post = true, $use_cache = true, $add_hashtags = true, $encode = true ) {
			$this->p->debug->args( array( 
				'type' => $type, 
				'length' => $length, 
				'use_post' => $use_post, 
				'use_cache' => $use_cache, 
				'add_hashtags' => $add_hashtags,
				'encode' => $encode ) );
			$caption = '';
			$separator = html_entity_decode( $this->p->options['og_title_sep'], ENT_QUOTES, get_bloginfo( 'charset' ) );

			// request all values un-encoded, then encode once we have the complete caption text
			switch ( strtolower( $type ) ) {
				case 'title':
					$caption = $this->get_title( $length, '...', $use_post, $use_cache, $add_hashtags, false );
					break;
				case 'excerpt':
					$caption = $this->get_description( $length, '...', $use_post, $use_cache, $add_hashtags, false );
					break;
				case 'both':
					$prefix = $this->get_title( null, null, $use_post, $use_cache, false, false ).' '.$separator.' ';
					$caption = $prefix.$this->get_description( $length - strlen( $prefix ), '...', $use_post, $use_cache, $add_hashtags, false );
					break;
			}

			if ( $encode === true )
				$caption = htmlentities( $caption, ENT_QUOTES, get_bloginfo( 'charset' ), false );	// double_encode = false

			return apply_filters( $this->p->cf['lca'].'_caption', $caption, $type, $length, $use_post, $use_cache, $add_hashtags, $encode );
		}

		public function get_title( $textlen = 70, $trailing = '', $use_post = false, $use_cache = true, $add_hashtags = false, $encode = true, $custom = 'og_title' ) {
			$this->p->debug->args( array( 
				'textlen' => $textlen, 
				'trailing' => $trailing, 
				'use_post' => $use_post, 
				'use_cache' => $use_cache, 
				'add_hashtags' => $add_hashtags,
				'encode' => $encode,
				'custom' => $custom ) );
			$title = false;
			$parent_title = '';
			$paged_suffix = '';
			$hashtags = '';
			$post_id = 0;
			$separator = $encode === true ? 
				$this->p->options['og_title_sep'] : 	// option value is stored encoded
				html_entity_decode( $this->p->options['og_title_sep'], ENT_QUOTES, get_bloginfo( 'charset' ) );

			if ( is_singular() || $use_post !== false ) {
				if ( ( $obj = $this->p->util->get_post_object( $use_post ) ) === false ) {
					$this->p->debug->log( 'exiting early: invalid object type' );
					return $title;
				}
				$post_id = empty( $obj->ID ) || empty( $obj->post_type ) ? 0 : $obj->ID;
				if ( ! empty( $post_id ) && isset( $this->p->addons['util']['postmeta'] ) ) {
					$title = $this->p->addons['util']['postmeta']->get_options( $post_id, $custom );
					if ( ! empty( $title ) ) $this->p->debug->log( 'custom postmeta '.$custom.' = "'.$title.'"' );
				}
			} elseif ( is_author() || ( is_admin() && ( $screen = get_current_screen() ) && ( $screen->id === 'user-edit' || $screen->id === 'profile' ) ) ) {
				$author = $this->p->util->get_author_object();
				if ( ! empty( $author->ID ) ) {
					if ( isset( $this->p->addons['util']['user'] ) )
						$title = $this->p->addons['util']['user']->get_options( $author->ID, $custom );
					if ( ! empty( $title ) ) 
						$this->p->debug->log( 'custom user '.$custom.' = "'.$title.'"' );
					elseif ( is_admin() )	// re-create default wp title on admin side
						$title = $author->display_name.' '.$separator.' '.$title;
				}
			}

			// get seed if no custom meta title
			if ( empty( $title ) ) {
				$title = apply_filters( $this->p->cf['lca'].'_title_seed', '', $use_post, $use_cache, $add_hashtags, $encode, $custom );
				if ( ! empty( $title ) )
					$this->p->debug->log( 'title seed = "'.$title.'"' );
			}

			// check for hashtags in meta or seed title, remove and then add again after shorten
			if ( preg_match( '/(.*)(( #[a-z0-9\-]+)+)$/U', $title, $match ) ) {
				$title = $match[1];
				$hashtags = trim( $match[2] );
			} elseif ( is_singular() || $use_post !== false ) {
				if ( $add_hashtags && ! empty( $this->p->options['og_desc_hashtags'] ) )
					$hashtags = $this->get_hashtags( $post_id );
			}

			// construct a title of our own
			if ( empty( $title ) ) {
				// $obj and $post_id are defined above, with the same test, so we should be good
				if ( is_singular() || $use_post !== false ) {
					if ( is_singular() ) {
						$title = wp_title( $separator, false, 'right' );
						$this->p->debug->log( 'is_singular: wp_title() = "'.$title.'"' );
					} elseif ( ! empty( $post_id ) ) {
						$title = get_the_title( $post_id );
						$this->p->debug->log( 'have post_id: get_the_title() = "'.$title.'"' );
					}

					// get the parent's title if no seo package is installed
					if ( $this->p->is_avail['seo']['*'] == false && ! empty( $obj->post_parent ) )
						$parent_title = get_the_title( $obj->post_parent );

				// by default, use the wordpress title if an seo plugin is available
				} elseif ( $this->p->is_avail['seo']['*'] == true ) {

					// use separator on right for compatibility with aioseo
					$title = wp_title( $separator, false, 'right' );
					$this->p->debug->log( 'have seo: wp_title() = "'.$title.'"' );
	
				// category title, with category parents
				} elseif ( is_category() ) { 

					$term = get_queried_object();
					$title = $term->name;
					$cat_parents = get_category_parents( $term->term_id, false, ' '.$separator.' ', false );
					if ( is_wp_error( $cat_parents ) )
						$this->p->debug->log( 'get_category_parents() returned WP_Error object' );
					else {
						$this->p->debug->log( 'get_category_parents() = "'.$cat_parents.'"' );
						if ( ! empty( $cat_parents ) ) {
							$title = $cat_parents;
							$title = preg_replace( '/\.\.\. \\'.$separator.' /', '... ', $title );
						}
					}
	
				} else {
					/* The title text depends on the query:
					 *	single post = the title of the post 
					 *	date-based archive = the date (e.g., "2006", "2006 - January") 
					 *	category = the name of the category 
					 *	author page = the public name of the user 
					 */
					$title = wp_title( $separator, false, 'right' );
					$this->p->debug->log( 'default: wp_title() = "'.$title.'"' );
				}
	
				// just in case
				if ( empty( $title ) ) {
					$title = get_bloginfo( 'name', 'display' );
					$this->p->debug->log( 'last resort: get_bloginfo() = "'.$title.'"' );
				}
			}

			$title = $this->p->util->cleanup_html_tags( $title );		// strip html tags before removing separator
			$title = preg_replace( '/ \\'.$separator.' *$/', '', $title );	// trim excess separator

			// apply title filter before adjusting it's length
			$title = apply_filters( $this->p->cf['lca'].'_title_pre_limit', $title );

			// check title against string length limits
			if ( $textlen > 0 ) {
				// seo-like title modifications
				if ( $this->p->is_avail['seo']['*'] === false ) {
					$paged = get_query_var( 'paged' );
					if ( $paged > 1 ) {
						if ( ! empty( $separator ) )
							$paged_suffix .= $separator.' ';
						$paged_suffix .= sprintf( 'Page %s', $paged );
						$textlen = $textlen - strlen( $paged_suffix ) - 1;
					}
				}
				if ( ! empty( $parent_title ) ) 
					$textlen = $textlen - strlen( $parent_title ) - 3;
				if ( $add_hashtags === true && ! empty( $hashtags ) ) 
					$textlen = $textlen - strlen( $hashtags ) - 1;
				$title = $this->p->util->limit_text_length( $title, $textlen, $trailing, false );	// don't run cleanup_html_tags()
				$title = preg_replace( '/ \\'.$separator.' *('.$trailing.')?$/', $trailing, $title );	// just in case
			}

			if ( ! empty( $parent_title ) ) 
				$title .= ' ('.$parent_title.')';

			if ( ! empty( $paged_suffix ) ) 
				$title .= ' '.$paged_suffix;

			if ( $add_hashtags === true && ! empty( $hashtags ) ) 
				$title .= ' '.$hashtags;

			if ( $encode === true )
				$title = htmlentities( $title, ENT_QUOTES, get_bloginfo( 'charset' ), false );	// double_encode = false
			else $title = html_entity_decode( $title, ENT_QUOTES, get_bloginfo( 'charset' ) );	// just in case

			return apply_filters( $this->p->cf['lca'].'_title', $title );
		}

		public function get_description( $textlen = 156, $trailing = '...', $use_post = false, $use_cache = true, $add_hashtags = true, $encode = true, $custom = 'og_desc' ) {
			$this->p->debug->args( array( 
				'textlen' => $textlen, 
				'trailing' => $trailing, 
				'use_post' => $use_post, 
				'use_cache' => $use_cache, 
				'add_hashtags' => $add_hashtags, 
				'encode' => $encode,
				'custom' => $custom ) );
			$desc = false;
			$hashtags = '';
			$post_id = 0;
			$screen = '';
			$page = ''; 

			if ( is_singular() || $use_post !== false ) {
				if ( ( $obj = $this->p->util->get_post_object( $use_post ) ) === false ) {
					$this->p->debug->log( 'exiting early: invalid object type' );
					return $desc;
				}
				$post_id = empty( $obj->ID ) || empty( $obj->post_type ) ? 0 : $obj->ID;
				if ( ! empty( $post_id ) && isset( $this->p->addons['util']['postmeta'] ) ) {
					$desc = $this->p->addons['util']['postmeta']->get_options( $post_id, $custom );
					if ( ! empty( $desc ) ) $this->p->debug->log( 'custom postmeta '.$custom.' = "'.$desc.'"' );
				}
			} elseif ( is_author() || ( is_admin() && ( $screen = get_current_screen() ) && ( $screen->id === 'user-edit' || $screen->id === 'profile' ) ) ) {
				$author = $this->p->util->get_author_object();
				if ( ! empty( $author->ID ) ) {
					if ( isset( $this->p->addons['util']['user'] ) )
						$desc = $this->p->addons['util']['user']->get_options( $author->ID, $custom );
					if ( ! empty( $desc ) ) 
						$this->p->debug->log( 'custom user '.$custom.' = "'.$desc.'"' );
					elseif ( is_admin() )	// re-create default description on admin side
						$desc = empty( $author->description ) ? sprintf( 'Authored by %s', $author->display_name ) : $author->description;
				}
			}

			// get seed if no custom meta description
			if ( empty( $desc ) ) {
				$desc = apply_filters( $this->p->cf['lca'].'_description_seed', '', $use_post, $use_cache, $add_hashtags, $encode, $custom );
				if ( ! empty( $desc ) ) $this->p->debug->log( 'description seed = "'.$desc.'"' );
			}
		
			// check for hashtags in meta or seed description, remove and then add again after shorten
			if ( preg_match( '/(.*)(( #[a-z0-9\-]+)+)$/U', $desc, $match ) ) {
				$desc = $match[1];
				$hashtags = trim( $match[2] );
			} elseif ( is_singular() || $use_post !== false ) {
				if ( $add_hashtags && ! empty( $this->p->options['og_desc_hashtags'] ) )
					$hashtags = $this->get_hashtags( $post_id );
			}

			// if there's no custom description, and no pre-seed, 
			// then go ahead and generate the description value
			if ( empty( $desc ) ) {
				// $obj and $post_id are defined above, with the same test, so we should be good
				if ( is_singular() || $use_post !== false ) {
					// use the excerpt, if we have one
					if ( has_excerpt( $post_id ) ) {
						$desc = $obj->post_excerpt;
						if ( ! empty( $this->p->options['plugin_filter_excerpt'] ) ) {

							// remove the sharing buttons filter to avoid recursive loops
							if ( ! empty( $this->p->sharing ) && 
								is_object( $this->p->sharing ) )
									$filter_removed = $this->p->sharing->remove_buttons_filter( 'get_the_excerpt' );
							else $filter_removed = false;

							$this->p->debug->log( 'calling apply_filters(\'get_the_excerpt\')' );
							$desc = apply_filters( 'get_the_excerpt', $desc );

							if ( $filter_removed )
								$this->p->sharing->add_buttons_filter( 'get_the_excerpt' );
						}
					} else $this->p->debug->log( 'no post_excerpt for post_id '.$post_id );

					// if there's no excerpt, then fallback to the content
					if ( empty( $desc ) )
						$desc = $this->get_content( $post_id, $use_cache );
			
					// ignore everything until the first paragraph tag if $this->p->options['og_desc_strip'] is true
					if ( $this->p->options['og_desc_strip'] ) 
						$desc = preg_replace( '/^.*?<p>/i', '', $desc );	// question mark makes regex un-greedy
		
				} elseif ( is_author() ) { 
					$author = $this->p->util->get_author_object();
					$desc = empty( $author->description ) ? sprintf( 'Authored by %s', $author->display_name ) : $author->description;
			
				} elseif ( is_tag() ) {
					$desc = tag_description();
					if ( empty( $desc ) )
						$desc = sprintf( 'Tagged with %s', single_tag_title( '', false ) );
			
				} elseif ( is_category() ) { 
					$desc = category_description();
					if ( empty( $desc ) )
						$desc = sprintf( '%s Category', single_cat_title( '', false ) ); 
				
				} elseif ( is_tax() ) { 
					$term = get_queried_object();
					if ( ! empty( $term->description ) )
						$desc = $term->description;

				} elseif ( is_day() ) 
					$desc = sprintf( 'Daily Archives for %s', get_the_date() );
				elseif ( is_month() ) 
					$desc = sprintf( 'Monthly Archives for %s', get_the_date('F Y') );
				elseif ( is_year() ) 
					$desc = sprintf( 'Yearly Archives for %s', get_the_date('Y') );
			}

			// if there's still no description, then fallback to a generic version
			if ( empty( $desc ) ) {
				if ( is_admin() && ! empty( $obj->post_status ) && $obj->post_status == 'auto-draft' )
					$this->p->debug->log( 'post_status is auto-draft - using empty description' );
				else {
					// pass options array to allow fallback if locale option does not exist
					$key = SucomUtil::get_locale_key( 'og_site_description', $this->p->options, $post_id );
					if ( ! empty( $this->p->options[$key] ) ) {
						$this->p->debug->log( 'description is empty - custom site description ('.$key.')' );
						$desc = $this->p->options[$key];
					} else {
						$this->p->debug->log( 'description is empty - using blog description' );
						$desc = get_bloginfo( 'description', 'display' );
					}
				}
			}
			
			$desc = $this->p->util->cleanup_html_tags( $desc );

			// apply description filter before adjusting it's length
			$desc = apply_filters( $this->p->cf['lca'].'_description_pre_limit', $desc );

			if ( $textlen > 0 ) {
				if ( $add_hashtags === true && ! empty( $hashtags ) ) 
					$textlen = $textlen - strlen( $hashtags ) -1;
				$desc = $this->p->util->limit_text_length( $desc, $textlen, $trailing, false );	// don't run cleanup_html_tags()
			}

			if ( $add_hashtags === true && ! empty( $hashtags ) ) 
				$desc .= ' '.$hashtags;

			if ( $encode === true )
				$desc = htmlentities( $desc, ENT_QUOTES, get_bloginfo( 'charset' ), false );	// double_encode = false
			else $desc = html_entity_decode( $desc, ENT_QUOTES, get_bloginfo( 'charset' ) );	// just in case

			return apply_filters( $this->p->cf['lca'].'_description', $desc );
		}

		public function get_content( $use_post = true, $use_cache = true ) {
			$this->p->debug->args( array( 
				'use_post' => $use_post, 
				'use_cache' => $use_cache ) );
			$content = false;

			if ( ( $obj = $this->p->util->get_post_object( $use_post ) ) === false ) {
				$this->p->debug->log( 'exiting early: invalid object type' );
				return $content;
			}
			$post_id = empty( $obj->ID ) || empty( $obj->post_type ) ? 0 : $obj->ID;
			$this->p->debug->log( 'using content from object id '.$post_id );
			$filter_content = $this->p->options['plugin_filter_content'];
			$filter_name = $filter_content  ? 'filtered' : 'unfiltered';

			/*
			 * retrieve the content
			 */
			if ( $filter_content == true ) {
				if ( $this->p->is_avail['cache']['object'] ) {
					// if the post id is 0, then add the sharing url to ensure a unique salt string
					$cache_salt = __METHOD__.'(lang:'.SucomUtil::get_locale().'_post:'.$post_id.'_'.$filter_name.
						( empty( $post_id ) ? '_url:'.$this->p->util->get_sharing_url( $use_post ) : '' ).')';
					$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
					$cache_type = 'object cache';
					if ( $use_cache === true ) {
						$this->p->debug->log( $cache_type.': wp_cache salt '.$cache_salt );
						$content = wp_cache_get( $cache_id, __METHOD__ );
						if ( $content !== false ) {
							$this->p->debug->log( $cache_type.': '.$filter_name.' content retrieved from wp_cache '.$cache_id );
							return $content;
						}
					} else $this->p->debug->log( 'use cache = false' );
				}
			}

			$content = apply_filters( $this->p->cf['lca'].'_content_seed', '' );
			if ( ! empty( $content ) )
				$this->p->debug->log( 'content seed = "'.$content.'"' );
			elseif ( ! empty( $obj->post_content ) )
				$content = $obj->post_content;
			else $this->p->debug->log( 'exiting early: empty post content' );

			/*
			 * modify the content
			 */
			// save content length (for comparison) before making changes
			$content_strlen_before = strlen( $content );

			// remove singlepics, which we detect and use before-hand 
			$content = preg_replace( '/\[singlepic[^\]]+\]/', '', $content, -1, $count );
			if ( $count > 0 ) 
				$this->p->debug->log( $count.' [singlepic] shortcode(s) removed from content' );

			if ( $filter_content == true ) {

				// remove the sharing buttons filter to avoid recursive loops
				if ( ! empty( $this->p->sharing ) && 
					is_object( $this->p->sharing ) )
						$filter_removed = $this->p->sharing->remove_buttons_filter( 'the_content' );
				else $filter_removed = false;

				// remove all of our shortcodes
				if ( isset( $this->p->cf['*']['lib']['shortcode'] ) && 
					is_array( $this->p->cf['*']['lib']['shortcode'] ) )
						foreach ( $this->p->cf['*']['lib']['shortcode'] as $id => $name )
							if ( array_key_exists( $id, $this->shortcode ) && 
								is_object( $this->shortcode[$id] ) )
									$this->shortcode[$id]->remove();

				$this->p->debug->log( 'saving $post and calling apply_filters()' );
				global $post;
				$saved_post = $post;	// woocommerce can change the $post, so save and restore
				$content = apply_filters( 'the_content', $content );
				$post = $saved_post;

				// cleanup for NGG pre-v2 album shortcode
				unset ( $GLOBALS['subalbum'] );
				unset ( $GLOBALS['nggShowGallery'] );

				// add the sharing buttons filter back, if it was removed
				if ( $filter_removed )
					$this->p->sharing->add_buttons_filter( 'the_content' );

				// add our shortcodes back
				if ( isset( $this->p->cf['*']['lib']['shortcode'] ) && 
					is_array( $this->p->cf['*']['lib']['shortcode'] ) )
						foreach ( $this->p->cf['*']['lib']['shortcode'] as $id => $name )
							if ( array_key_exists( $id, $this->shortcode ) && 
								is_object( $this->shortcode[$id] ) )
									$this->shortcode[$id]->add();
			}

			$content = preg_replace( '/[\r\n\t ]+/s', ' ', $content );	// put everything on one line
			$content = preg_replace( '/^.*<!--'.$this->p->cf['lca'].'-content-->(.*)<!--\/'.
				$this->p->cf['lca'].'-content-->.*$/', '$1', $content );
			$content = preg_replace( '/<a +rel="author" +href="" +style="display:none;">Google\+<\/a>/', ' ', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );

			$content_strlen_after = strlen( $content );
			$this->p->debug->log( 'content strlen() before = '.$content_strlen_before.', after = '.$content_strlen_after );

			// apply filters before caching
			$content = apply_filters( $this->p->cf['lca'].'_content', $content );

			if ( $filter_content == true && ! empty( $cache_id ) ) {
				// only some caching plugins implement this function
				wp_cache_add_non_persistent_groups( array( __METHOD__ ) );

				wp_cache_set( $cache_id, $content, __METHOD__, $this->p->cache->object_expire );
				$this->p->debug->log( $cache_type.': '.$filter_name.' content saved to wp_cache '.
					$cache_id.' ('.$this->p->cache->object_expire.' seconds)');
			}
			return $content;
		}

		public function get_section( $post_id ) {
			$section = '';
			if ( ( is_singular() || ! empty( $post_id ) ) && isset( $this->p->addons['util']['postmeta'] ) )
				$section = $this->p->addons['util']['postmeta']->get_options( $post_id, 'og_art_section' );

			if ( ! empty( $section ) ) 
				$this->p->debug->log( 'found custom meta section = '.$section );
			else $section = $this->p->options['og_art_section'];

			if ( $section == 'none' )
				$section = '';

			return apply_filters( $this->p->cf['lca'].'_section', $section );
		}

		public function get_hashtags( $post_id ) {
			if ( empty( $this->p->options['og_desc_hashtags'] ) ) 
				return;

			$hashtags = apply_filters( $this->p->cf['lca'].'_hashtags_seed', '', $post_id );
			if ( ! empty( $hashtags ) )
				$this->p->debug->log( 'hashtags seed = "'.$hashtags.'"' );
			else {
				$tags = array_slice( $this->get_tags( $post_id ), 0, $this->p->options['og_desc_hashtags'] );
				if ( ! empty( $tags ) ) {
					$hashtags = '#'.trim( implode( ' #', preg_replace( '/[ \[\]#%!\$\?\\\\\/\.\*\+\^\-]/', '', $tags ) ) );
					$this->p->debug->log( 'hashtags = "'.$hashtags.'"' );
				}
			}
			return apply_filters( $this->p->cf['lca'].'_hashtags', $hashtags, $post_id );
		}

		public function get_tags( $post_id ) {
			$tags = apply_filters( $this->p->cf['lca'].'_tags_seed', array(), $post_id );
			if ( ! empty( $tags ) )
				$this->p->debug->log( 'tags seed = "'.implode( ',', $tags ).'"' );
			else {
				if ( is_singular() || ! empty( $post_id ) ) {
					$tags = $this->get_wp_tags( $post_id );
					if ( isset( $this->p->addons['media']['ngg'] ) && 
						$this->p->options['og_ngg_tags'] && 
						$this->p->is_avail['postthumb'] && 
						has_post_thumbnail( $post_id ) ) {

						$pid = get_post_thumbnail_id( $post_id );
						// featured images from ngg pre-v2 had 'ngg-' prefix
						if ( is_string( $pid ) && substr( $pid, 0, 4 ) == 'ngg-' )
							$tags = array_merge( $tags, $this->p->addons['media']['ngg']->get_tags( $pid ) );
					}
				} elseif ( is_search() )
					$tags = preg_split( '/ *, */', get_search_query( false ) );

				$tags = array_unique( array_map( 'strtolower', $tags ) );
				$this->p->debug->log( 'tags = "'.implode( ',', $tags ).'"' );
			}
			return apply_filters( $this->p->cf['lca'].'_tags', $tags, $post_id );
		}

		public function get_wp_tags( $post_id ) {
			$tags = apply_filters( $this->p->cf['lca'].'_wp_tags_seed', array(), $post_id );
			if ( ! empty( $tags ) )
				$this->p->debug->log( 'wp tags seed = "'.implode( ',', $tags ).'"' );
			else {
				$post_ids = array ( $post_id );	// array of one
				// add the parent tags if option is enabled
				if ( $this->p->options['og_page_parent_tags'] && is_page( $post_id ) )
					$post_ids = array_merge( $post_ids, get_post_ancestors( $post_id ) );
				foreach ( $post_ids as $id ) {
					if ( $this->p->options['og_page_title_tag'] && is_page( $id ) )
						$tags[] = get_the_title( $id );
					foreach ( wp_get_post_tags( $id, array( 'fields' => 'names') ) as $tag_name )
						$tags[] = $tag_name;
				}
				$tags = array_map( 'strtolower', $tags );
			}
			return apply_filters( $this->p->cf['lca'].'_wp_tags', $tags, $post_id );
		}
	}
}

?>
