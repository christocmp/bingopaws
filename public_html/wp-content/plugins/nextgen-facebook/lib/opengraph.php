<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbOpengraph' ) && class_exists( 'SucomOpengraph' ) ) {

	class NgfbOpengraph extends SucomOpengraph {

		protected $size_name = '';

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->util->add_img_sizes_from_opts( array( 
				'og_img' => 'opengraph',
				'rp_img' => 'opengraph-rp',
			) );
			switch ( SucomUtil::crawler_name() ) {
				case 'pinterest':
					$this->size_name = $this->p->cf['lca'].'-opengraph-rp';
					break;
				default:
					$this->size_name = $this->p->cf['lca'].'-opengraph';
					break;
			}
			add_filter( 'language_attributes', array( &$this, 'add_doctype' ) );
		}

		public function add_doctype( $doctype ) {
			foreach ( array(
				'xmlns:og="http://ogp.me/ns#"',
				'xmlns:fb="http://ogp.me/ns/fb#"'
			) as $xmlns )
				if ( strpos( $doctype, ' '.$xmlns ) === false )
					$doctype .= ' '.$xmlns;
			return $doctype;
		}

		public function get_array( &$og = array(), $use_post = false ) {
			$obj = $this->p->util->get_post_object( $use_post );
			$post_id = empty( $obj->ID ) || empty( $obj->post_type ) ? 0 : $obj->ID;
			$post_type = '';
			$has_video_image = false;
			$og_max = $this->p->util->get_max_nums( $post_id );
			$og = apply_filters( $this->p->cf['lca'].'_og_seed', $og, $use_post );

			if ( ! isset( $og['fb:admins'] ) )
				$og['fb:admins'] = $this->p->options['fb_admins'];

			if ( ! isset( $og['fb:app_id'] ) )
				$og['fb:app_id'] = $this->p->options['fb_app_id'];

			if ( ! isset( $og['og:locale'] ) ) {
				// get the current or configured language for og:locale
				$lang = empty( $this->p->options['fb_lang'] ) ? 
					SucomUtil::get_locale( $post_id ) : $this->p->options['fb_lang'];

				$lang = apply_filters( $this->p->cf['lca'].'_lang', 
					$lang, SucomUtil::get_pub_lang( 'facebook' ), $post_id );

				$og['og:locale'] = $lang;
			}

			if ( ! isset( $og['og:site_name'] ) ) {
				// pass options array to allow fallback if locale option does not exist
				$key = SucomUtil::get_locale_key( 'og_site_name', $this->p->options, $post_id );
				if ( ! empty( $this->p->options[$key] ) )
					$og['og:site_name'] = $this->p->options[$key];
				else $og['og:site_name'] = get_bloginfo( 'name', 'display' );
			}

			if ( ! isset( $og['og:url'] ) )
				$og['og:url'] = $this->p->util->get_sharing_url( $use_post, true, 
					$this->p->util->get_source_id( 'opengraph' ) );

			if ( ! isset( $og['og:title'] ) )
				$og['og:title'] = $this->p->webpage->get_title( $this->p->options['og_title_len'], '...', $use_post );

			if ( ! isset( $og['og:description'] ) )
				$og['og:description'] = $this->p->webpage->get_description( $this->p->options['og_desc_len'], '...', $use_post );

			if ( ! isset( $og['og:type'] ) ) {

				// singular posts / pages are articles by default
				// check post_type for exceptions (like product pages)
				if ( is_singular() || $use_post !== false ) {
					if ( ! empty( $obj->post_type ) )
						$post_type = $obj->post_type;
					switch ( $post_type ) {
						case 'article':
						case 'book':
						case 'music.song':
						case 'music.album':
						case 'music.playlist':
						case 'music.radio_station':
						case 'product':
						case 'profile':
						case 'video.episode':
						case 'video.movie':
						case 'video.other':
						case 'video.tv_show':
						case 'website':
							$og['og:type'] = $post_type;
							break;
						default:
							$og['og:type'] = 'article';
							break;
					}

				// check for default author info on indexes and searches
				} elseif ( ( ! ( is_singular() || $use_post !== false ) && 
					! is_search() && ! empty( $this->p->options['og_def_author_on_index'] ) && ! empty( $this->p->options['og_def_author_id'] ) ) || 
					( is_search() && ! empty( $this->p->options['og_def_author_on_search'] ) && ! empty( $this->p->options['og_def_author_id'] ) ) ) {
	
					$og['og:type'] = 'article';
					if ( ! isset( $og['article:author'] ) )
						$og['article:author'] = $this->p->addons['util']['user']->get_article_author( $this->p->options['og_def_author_id'] );

				// default for everything else is 'website'
				} else $og['og:type'] = 'website';

				$og['og:type'] = apply_filters( $this->p->cf['lca'].'_og_type', $og['og:type'], $use_post );
			}

			// if the page is an article, then define the other article meta tags
			if ( isset( $og['og:type'] ) && $og['og:type'] == 'article' ) {

				if ( ! isset( $og['article:author'] ) ) {
					if ( is_singular() || $use_post !== false ) {
						if ( ! empty( $obj->post_author ) )
							$og['article:author'] = $this->p->addons['util']['user']->get_article_author( $obj->post_author );
						elseif ( ! empty( $this->p->options['og_def_author_id'] ) )
							$og['article:author'] = $this->p->addons['util']['user']->get_article_author( $this->p->options['og_def_author_id'] );
					}
				}

				if ( ! isset( $og['article:publisher'] ) )
					$og['article:publisher'] = $this->p->options['og_publisher_url'];

				if ( ! isset( $og['article:tag'] ) )
					$og['article:tag'] = $this->p->webpage->get_tags( $post_id );

				if ( ! isset( $og['article:section'] ) )
					$og['article:section'] = $this->p->webpage->get_section( $post_id );

				if ( ! isset( $og['article:published_time'] ) )
					$og['article:published_time'] = trim( get_the_date('c') );

				if ( ! isset( $og['article:modified_time'] ) )
					$og['article:modified_time'] = trim( get_the_modified_date('c') );
			}

			// get all videos
			// check first, to add video preview images
			if ( ! isset( $og['og:video'] ) ) {
				if ( empty( $og_max['og_vid_max'] ) )
					$this->p->debug->log( 'videos disabled: maximum videos = 0' );
				else {
					$og['og:video'] = $this->get_all_videos( $og_max['og_vid_max'], $post_id );
					if ( is_array( $og['og:video'] ) ) {
						foreach ( $og['og:video'] as $val ) {
							if ( is_array( $val ) && ! empty( $val['og:image'] ) ) {
								$this->p->debug->log( 'og:image found in og:video array (no default image required)' );
								$has_video_image = true;
							}
						}
					}
				} 
			}

			// get all images
			if ( ! isset( $og['og:image'] ) ) {
				if ( empty( $og_max['og_img_max'] ) ) 
					$this->p->debug->log( 'images disabled: maximum images = 0' );
				else {
					$og['og:image'] = $this->get_all_images( $og_max['og_img_max'], $this->size_name, $post_id );

					// if there's no image, and no video preview image, then add the default image for non-index webpages
					if ( empty( $og['og:image'] ) && $has_video_image === false &&
						( is_singular() || $use_post !== false ) )
							$og['og:image'] = $this->p->media->get_default_image( $og_max['og_img_max'], $this->size_name );
				} 
			}

			// only a few opengraph meta tags are allowed to be empty
			foreach ( $og as $key => $val ) {
				switch ( $key ) {
					case 'og:locale':
					case 'og:site_name':
					case 'og:description':
						break;
					default:
						if ( $val === '' || ( is_array( $val ) && empty( $val ) ) )
							unset( $og[$key] );
						break;
				}
			}

			// twitter cards are hooked into this filter to use existing open graph values
			return apply_filters( $this->p->cf['lca'].'_og', $og, $use_post );
		}

		public function get_all_videos( $num = 0, $post_id, $check_dupes = true ) {
			$this->p->debug->args( array( 'num' => $num, 'post_id' => $post_id, 'check_dupes' => $check_dupes ) );
			$og_ret = array();

			// check for index-type webpages with og_def_vid_on_index enabled to force a default video
			if ( ( ! empty( $this->p->options['og_def_vid_on_index'] ) && ( is_home() || is_archive() ) && ! is_author() ) ||
				( ! empty( $this->p->options['og_def_vid_on_author'] ) && is_author() ) ||
				( ! empty( $this->p->options['og_def_vid_on_search'] ) && is_search() ) ) {

				$num_remains = $this->p->media->num_remains( $og_ret, $num );
				$og_ret = array_merge( $og_ret, 
					$this->p->media->get_default_video( $num_remains, $check_dupes ) );
				return $og_ret;	// stop here and return the video array
			}

			if ( ! empty( $post_id ) ) {	// post id should be > 0 for post meta
				$num_remains = $this->p->media->num_remains( $og_ret, $num );
				$og_ret = array_merge( $og_ret, 
					$this->p->media->get_meta_video( $num_remains, $post_id, $check_dupes ) );
			}

			// if we haven't reached the limit of videos yet, keep going
			if ( ! $this->p->util->is_maxed( $og_ret, $num ) ) {
				$num_remains = $this->p->media->num_remains( $og_ret, $num );
				$og_ret = array_merge( $og_ret, 
					$this->p->media->get_content_videos( $num_remains, $post_id, $check_dupes ) );
			}
			$this->p->util->slice_max( $og_ret, $num );
			return $og_ret;
		}

		public function get_all_images( $num = 0, $size_name = 'thumbnail', $post_id, $check_dupes = true ) {
			$this->p->debug->args( array( 'num' => $num, 'size_name' => $size_name, 'post_id' => $post_id, 'check_dupes' => $check_dupes ) );
			$og_ret = array();

			// check for an attachment page
			if ( ! empty( $post_id ) && is_attachment( $post_id ) ) {	// post id should be > 0 for attachment pages
				$og_image = array();
				$num_remains = $this->p->media->num_remains( $og_ret, $num );
				$og_image = $this->p->media->get_attachment_image( $num_remains, $size_name, $post_id, $check_dupes );

				// if an attachment is not an image, then use the default image instead
				if ( empty( $og_ret ) ) {
					$num_remains = $this->p->media->num_remains( $og_ret, $num );
					$og_ret = array_merge( $og_ret, $this->p->media->get_default_image( $num_remains, $size_name, $check_dupes ) );
				} else $og_ret = array_merge( $og_ret, $og_image );

				return $og_ret;
			}

			// check for index webpages with og_def_img_on_index or og_def_img_on_search enabled to force a default image
			if ( ( ! empty( $this->p->options['og_def_img_on_index'] ) && ( is_home() || is_archive() ) && ! is_author() ) ||
				( ! empty( $this->p->options['og_def_img_on_author'] ) && is_author() ) ||
				( ! empty( $this->p->options['og_def_img_on_search'] ) && is_search() ) ) {

				$num_remains = $this->p->media->num_remains( $og_ret, $num );
				$og_ret = array_merge( $og_ret, $this->p->media->get_default_image( $num_remains, $size_name, $check_dupes ) );
				return $og_ret;	// stop here and return the image array
			}

			if ( is_author() || ( is_admin() && ( $screen = get_current_screen() ) && ( $screen->id === 'user-edit' || $screen->id === 'profile' ) ) ) {
				if ( is_admin() )
					$author_id = empty( $_GET['user_id'] ) ? get_current_user_id() : $_GET['user_id'];
				else {
					$author = get_query_var( 'author_name' ) ? 
						get_user_by( 'slug', get_query_var( 'author_name' ) ) :
						get_userdata( get_query_var( 'author' ) );
					$author_id = $author->ID;
				}
				$num_remains = $this->p->media->num_remains( $og_ret, $num );
				$og_ret = array_merge( $og_ret, $this->p->media->get_author_image( $num_remains, $size_name, $author_id, $check_dupes ) );
			}

			// check for custom meta, featured, or attached image(s)
			if ( ! empty( $post_id ) ) {	// post id should be > 0
				$num_remains = $this->p->media->num_remains( $og_ret, $num );
				$og_ret = array_merge( $og_ret, 
					$this->p->media->get_post_images( $num_remains, $size_name, $post_id, $check_dupes ) );

				// keep going to find more images
				// the featured / attached image(s) will be listed first in the open graph meta property tags
				// and duplicates will be filtered out
			}

			// check for ngg shortcodes and query vars
			if ( $this->p->is_avail['media']['ngg'] === true && 
				! empty( $this->p->addons['media']['ngg'] ) &&
				! $this->p->util->is_maxed( $og_ret, $num ) ) {

				// ngg pre-v2 used query arguments
				$ngg_query_og_ret = array();
				$num_remains = $this->p->media->num_remains( $og_ret, $num );
				if ( version_compare( $this->p->addons['media']['ngg']->ngg_version, '2.0.0', '<' ) )
					$ngg_query_og_ret = $this->p->addons['media']['ngg']->get_query_images( $num_remains, $size_name, $check_dupes );

				// if we found images in the query, skip content shortcodes
				if ( count( $ngg_query_og_ret ) > 0 ) {
					$this->p->debug->log( count( $ngg_query_og_ret ).' image(s) returned - skipping additional shortcode images' );
					$og_ret = array_merge( $og_ret, $ngg_query_og_ret );

				// if no query images were found, continue with ngg shortcodes in content
				} elseif ( ! $this->p->util->is_maxed( $og_ret, $num ) ) {
					$num_remains = $this->p->media->num_remains( $og_ret, $num );
					$og_ret = array_merge( $og_ret, 
						$this->p->addons['media']['ngg']->get_shortcode_images( $num_remains, $size_name, $check_dupes ) );
				}
			} // end of check for ngg shortcodes and query vars

			// if we haven't reached the limit of images yet, keep going and check the content text
			if ( ! $this->p->util->is_maxed( $og_ret, $num ) ) {
				$num_remains = $this->p->media->num_remains( $og_ret, $num );
				$og_ret = array_merge( $og_ret, 
					$this->p->media->get_content_images( $num_remains, $size_name, $post_id, $check_dupes ) );
			}

			$this->p->util->slice_max( $og_ret, $num );
			return $og_ret;
		}
	}
}

?>
