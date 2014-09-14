<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSharing' ) ) {

	class NgfbSharing {

		protected $p;
		protected $website = array();
		protected $plugin_filepath;

		public $sharing_css_min_file = '';
		public $sharing_css_min_url = '';

		public static $cf = array(
			'opt' => array(				// options
				'defaults' => array(
					'plugin_min_shorten' => 22,
					'plugin_bitly_login' => '',
					'plugin_bitly_api_key' => '',
					'plugin_google_api_key' => '',
					'plugin_google_shorten' => 0,
					'buttons_on_index' => 0,
					'buttons_on_front' => 0,
					'buttons_add_to_post' => 1,
					'buttons_add_to_page' => 1,
					'buttons_add_to_attachment' => 1,
					'buttons_pos_content' => 'bottom',
					'buttons_pos_excerpt' => 'bottom',
					'buttons_use_social_css' => 1,
					'buttons_enqueue_social_css' => 0,
					'buttons_css_admin_edit' => '',
					'buttons_css_sharing' => '',
					'buttons_css_content' => '',
					'buttons_css_excerpt' => '',
					'buttons_css_sidebar' => '',
					'buttons_css_shortcode' => '',
					'buttons_css_widget' => '',
					'buttons_js_sidebar' => '/* Save an empty style text box to reload the default javascript */

jQuery("#ngfb-sidebar").mouseenter( function(){ 
	jQuery("#ngfb-sidebar-buttons").css({
		display:"block",
		width:"auto",
		height:"auto",
		overflow:"visible",
		"border-style":"solid",
	}); } );
jQuery("#ngfb-sidebar").click( function(){ 
	jQuery("#ngfb-sidebar-buttons").toggle(); } );',
					'buttons_preset_content' => '',
					'buttons_preset_excerpt' => '',
					'buttons_preset_widget' => '',
					'buttons_preset_sidebar' => 'large_share_vertical',
					'buttons_preset_admin_edit' => 'small_share_count',
					'buttons_preset_shortcode' => '',
				),
				'preset' => array(
					'small_share_count' => array(
						'fb_button' => 'share',
						'fb_send' => 0,
						'fb_show_faces' => 0,
						'fb_action' => 'like',
						'fb_type' => 'button_count',
						'gp_action' => 'share',
						'gp_size' => 'medium',
						'gp_annotation' => 'bubble',
						'gp_expandto' => '',
						'twitter_size' => 'medium',
						'twitter_count' => 'horizontal',
						'linkedin_counter' => 'right',
						'linkedin_showzero' => 1,
						'pin_button_shape' => 'rect',
						'pin_button_height' => 'small',
						'pin_count_layout' => 'beside',
						'buffer_count' => 'horizontal',
						'reddit_type' => 'static-wide',
						'managewp_type' => 'small',
						'tumblr_button_style' => 'share_1',
						'stumble_badge' => 1,
					),
					'large_share_vertical' => array(
						'fb_button' => 'share',
						'fb_send' => 0,
						'fb_show_faces' => 0,
						'fb_action' => 'like',
						'fb_type' => 'box_count',
						'fb_layout' => 'box_count',
						'gp_action' => 'share',
						'gp_size' => 'tall',
						'gp_annotation' => 'vertical-bubble',
						'gp_expandto' => '',
						'twitter_size' => 'medium',
						'twitter_count' => 'vertical',
						'linkedin_counter' => 'top',
						'linkedin_showzero' => '1',
						'pin_button_shape' => 'rect',
						'pin_button_height' => 'large',
						'pin_count_layout' => 'above',
						'buffer_count' => 'vertical',
						'reddit_type' => 'static-tall-text',
						'managewp_type' => 'big',
						'tumblr_button_style' => 'share_2',
						'stumble_badge' => 5,
					),
				),
			),
			'sharing' => array(
				'show_on' => array( 
					'content' => 'Content', 
					'excerpt' => 'Excerpt', 
					'sidebar' => 'CSS Sidebar', 
					'admin_edit' => 'Admin Edit',
				),
				'style' => array(
					'sharing' => 'All Buttons',
					'content' => 'Content',
					'excerpt' => 'Excerpt',
					'sidebar' => 'CSS Sidebar',
					'shortcode' => 'Shortcode',
					'widget' => 'Widget',
					'admin_edit' => 'Admin Edit',
				),
			),
		);

		public function __construct( &$plugin, $plugin_filepath = NGFB_FILEPATH ) {
			$this->p =& $plugin;
			$this->plugin_filepath = $plugin_filepath;
			$this->sharing_css_min_file = NGFB_CACHEDIR.'sharing-styles.min.css';
			$this->sharing_css_min_url = NGFB_CACHEURL.'sharing-styles.min.css';
			$this->set_objects();

			add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_styles' ) );
			add_action( 'wp_head', array( &$this, 'show_header' ), NGFB_HEAD_PRIORITY );
			add_action( 'wp_footer', array( &$this, 'show_footer' ), NGFB_FOOTER_PRIORITY );

			$this->add_buttons_filter( 'get_the_excerpt' );
			$this->add_buttons_filter( 'the_excerpt' );
			$this->add_buttons_filter( 'the_content' );

			$this->p->util->add_plugin_filters( $this, array( 
				'get_defaults' => 1,	// add sharing options and css file contents to defaults
			) );

			if ( is_admin() ) {
				add_action( 'add_meta_boxes', array( &$this, 'add_post_metaboxes' ) );
				$this->p->util->add_plugin_filters( $this, array( 
					'save_options' => 2,		// update the sharing css file
					'option_type' => 2,		// identify option type for sanitation
					'post_cache_transients' => 4,	// flush transients on post save
					'status_gpl_features' => 1,	// include sharing, shortcode, and widget status
					'tooltip_side' => 2,		// tooltip messages for side boxes
					'tooltip_plugin' => 2,		// tooltip messages for advanced settings
					'tooltip_postmeta' => 3,	// tooltip messages for post social settings
				) );
				$this->p->util->add_plugin_filters( $this, array( 
					'status_pro_features' => 3,	// include social file cache status
				), 10, 'ngfb' );			// hook into the extension name instead
			}
		}

		private function set_objects() {
			foreach ( $this->p->cf['plugin']['ngfb']['lib']['website'] as $id => $name ) {
				$classname = NgfbConfig::load_lib( false, 'website/'.$id, 'ngfbsharing'.$id );
				if ( $classname !== false && class_exists( $classname ) )
					$this->website[$id] = new $classname( $this->p );
			}
		}

		public function filter_get_defaults( $opts_def ) {
			$opts_def = array_merge( $opts_def, self::$cf['opt']['defaults'] );
			$opts_def = $this->p->util->push_add_to_options( $opts_def, array( 'buttons' => 'frontend' ) );
			$plugin_dir = trailingslashit( plugin_dir_path( $this->plugin_filepath ) );
			$url_path = parse_url( trailingslashit( plugins_url( '', $this->plugin_filepath ) ), PHP_URL_PATH );	// relative URL

			foreach ( self::$cf['sharing']['style'] as $id => $name ) {
				$css_file = $plugin_dir.'css/'.$id.'-buttons.css';

				// css files are only loaded once (when variable is empty) into defaults to minimize disk i/o
				if ( empty( $opts_def['buttons_css_'.$id] ) ) {
					if ( ! $fh = @fopen( $css_file, 'rb' ) )
						$this->p->notice->err( 'Failed to open '.$css_file.' for reading.' );
					else {
						$css_data = fread( $fh, filesize( $css_file ) );
						fclose( $fh );
						$this->p->debug->log( 'read css from file '.$css_file );
						foreach ( array( 'URLPATH' => $url_path ) as $macro => $value )
							$css_data = preg_replace( '/{{'.$macro.'}}/', $value, $css_data );
						$opts_def['buttons_css_'.$id] = $css_data;
					}
				}
			}
			return $opts_def;
		}

		public function filter_save_options( $opts, $options_name ) {
			if ( $options_name === NGFB_OPTIONS_NAME ) {
				// update the combined and minimized social stylesheet
				$this->update_sharing_css( $opts );
			}
			return $opts;
		}

		public function filter_option_type( $type, $key ) {
			if ( ! empty( $type ) )
				return $type;

			// remove localization for more generic match
			if ( strpos( $key, '#' ) !== false )
				$key = preg_replace( '/#.*$/', '', $key );

			switch ( $key ) {
				// integer options that must be 1 or more (not zero)
				case 'stumble_badge':
				case 'plugin_min_shorten':
				case ( preg_match( '/_order$/', $key ) ? true : false ):
					return 'posnum';
					break;
				// text strings that can be blank
				case 'gp_expandto':
				case 'pin_desc':
				case 'tumblr_img_desc':
				case 'tumblr_vid_desc':
				case 'twitter_desc':
				case 'plugin_bitly_login':
				case 'plugin_bitly_api_key':
				case 'plugin_google_api_key':
					return 'okblank';
					break;
				// options that cannot be blank
				case 'fb_markup': 
				case 'gp_lang': 
				case 'gp_action': 
				case 'gp_size': 
				case 'gp_annotation': 
				case 'twitter_count': 
				case 'twitter_size': 
				case 'linkedin_counter':
				case 'managewp_type':
				case 'pin_button_lang':
				case 'pin_button_shape':
				case 'pin_button_color':
				case 'pin_button_height':
				case 'pin_count_layout':
				case 'pin_caption':
				case 'tumblr_button_style':
				case 'tumblr_caption':
				case ( strpos( $key, 'buttons_pos_' ) === 0 ? true : false ):
				case ( preg_match( '/^[a-z]+_js_loc$/', $key ) ? true : false ):
					return 'notblank';
					break;
			}
			return $type;
		}

		public function filter_post_cache_transients( $transients, $post_id, $lang = 'en_US', $sharing_url ) {
			if ( ! empty( self::$cf['sharing']['show_on'] ) &&
				is_array( self::$cf['sharing']['show_on'] ) ) {

				$transients['NgfbSharing::get_buttons'] = array();
				foreach( self::$cf['sharing']['show_on'] as $type_id => $type_name )
					$transients['NgfbSharing::get_buttons'][$type_id] = 'lang:'.$lang.'_obj:'.$post_id.'_type:'.$type_id;
			}
			return $transients;
		}

		public function filter_status_gpl_features( $features ) {
			if ( ! empty( $this->p->cf['*']['lib']['submenu']['sharing'] ) )
				$features['Sharing Buttons'] = array( 'class' => $this->p->cf['lca'].'Sharing' );

			if ( ! empty( $this->p->cf['*']['lib']['shortcode']['sharing'] ) )
				$features['Sharing Shortcode'] = array( 'class' => $this->p->cf['lca'].'ShortcodeSharing' );

			if ( ! empty( $this->p->cf['*']['lib']['submenu']['style'] ) )
				$features['Sharing Stylesheet'] = array( 'status' => $this->p->options['buttons_use_social_css'] ? 'on' : 'off' );

			if ( ! empty( $this->p->cf['*']['lib']['widget']['sharing'] ) )
				$features['Sharing Widget'] = array( 'class' => $this->p->cf['lca'].'WidgetSharing' );

			return $features;
		}

		public function filter_status_pro_features( $features = array(), $lca = '', $info = array() ) {
			if ( ! empty( $lca ) && ! empty( $info['lib']['submenu']['sharing'] ) ) {
				$features['Social File Cache'] = array( 
					'status' => $this->p->is_avail['cache']['file'] ? 'on' : 'off',
					'td_class' => $this->p->check->aop( $lca ) ? '' : 'blank',
				);
			}
			return $features;
		}

		public function filter_tooltip_side( $text, $idx ) {
			$lca = $this->p->cf['lca'];
			$short = $this->p->cf['plugin'][$lca]['short'];
			$short_pro = $short.' Pro';
			switch ( $idx ) {
				case 'tooltip-side-sharing-buttons':
					$text = 'Social sharing features include the '.$this->p->cf['menu'].' '.$this->p->util->get_admin_url( 'sharing', 'Buttons' ).
					' and '.$this->p->util->get_admin_url( 'style', 'Styles' ).' settings pages, the Social Settings -&gt; Sharing Buttons tab on Post 
					or Page editing pages, along with the social sharing shortcode and widget. All social sharing features can be disabled using one of 
					the available PHP <a href="http://surniaulula.com/codex/plugins/nextgen-facebook/notes/constants/" target="_blank">constants</a>.';
					break;
				case 'tooltip-side-sharing-shortcode':
					$text = 'Support for shortcode(s) can be enabled / disabled on the '.
					$this->p->util->get_admin_url( 'advanced', 'Advanced' ).' settings page. Shortcodes are disabled by default
					to optimize WordPress performance and content processing.';
					break;
				case 'tooltip-side-sharing-stylesheet':
					$text = 'A stylesheet can be included on all webpages for the social sharing buttons. Enable or disable the
					addition of the stylesheet from the '.$this->p->util->get_admin_url( 'style', 'Styles' ).' settings page.';
					break;
				case 'tooltip-side-sharing-widget':
					$text = 'The social sharing widget feature adds a \'Sharing Buttons\' widget in the WordPress Appearance - Widgets page.
					The widget can be used in any number of widget areas, to share the current webpage. The widget, along with all social
					sharing featured, can be disabled using an available 
					<a href="http://surniaulula.com/codex/plugins/nextgen-facebook/notes/constants/" target="_blank">constant</a>.';
					break;
				case 'tooltip-side-social-file-cache':
					$text = $short_pro.' can save social sharing images and JavaScript to a cache folder, 
					and provide URLs to these cached files instead of the originals. The current \'Social File Cache Expiry\'
					value, as defined on the '.$this->p->util->get_admin_url( 'advanced#sucom-tab_plugin_cache', 'Advanced' ).' settings page, is '.
					$this->p->options['plugin_file_cache_hrs'].' hours (the default value of 0 hours disables the 
					file caching feature).';
					break;
				case 'tooltip-side-url-shortener':
					$text = '<strong>When using the Twitter social sharing button provided by this plugin</strong>, 
					the webpage URL (aka the <em>canonical</em> or <em>permalink</em> URL) within the Tweet, 
					can be shortened by one of the available URL shortening services. 
					Enable URL shortening for Twitter from the '.$this->p->util->get_admin_url( 'sharing', 'Buttons' ).' settings page.';
					break;
			}
			return $text;
		}

		public function filter_tooltip_plugin( $text, $idx ) {
			switch ( $idx ) {
				/*
				 * 'API Keys' (URL Shortening) settings
				 */
				case 'tooltip-plugin_min_shorten':
					$text = 'URLs shorter than this length will not be shortened (the default is '.
					$this->p->opt->get_defaults( 'plugin_min_shorten' ).' characters).';
					break;
				case 'tooltip-plugin_bitly_login':
					$text = 'The Bit.ly username for your API key. If you don\'t already have one, see 
					<a href="https://bitly.com/a/your_api_key" target="_blank">Your Bit.ly API Key</a>.
					After setting your username and API key, you may select the Bit.ly shortener in the '.
					$this->p->util->get_admin_url( 'sharing', 'Twitter settings' ).'.';
					break;
				case 'tooltip-plugin_bitly_api_key':
					$text = 'The Bit.ly API key for this website. If you don\'t already have one, see 
					<a href="https://bitly.com/a/your_api_key" target="_blank">Your Bit.ly API Key</a>.
					After setting your username and API key, you may select the Bit.ly shortener in the '.
					$this->p->util->get_admin_url( 'sharing', 'Twitter settings' ).'.';
					break;
				case 'tooltip-plugin_google_api_key':
					$text = 'The Google BrowserKey for this website / project. If you don\'t already have one, visit
					<a href="https://cloud.google.com/console#/project" target="_blank">Google\'s Cloud Console</a>,
					create a new project for your website, and under the API &amp; auth - Registered apps, 
					register a new \'Web Application\' (name it \'NGFB\' for example), and enter it\'s BrowserKey here.';
					break;
				case 'tooltip-plugin_google_shorten':
					$text = 'In order to use Google\'s URL Shortener for URLs in Tweets, you must turn on the 
					URL Shortener API from <a href="https://cloud.google.com/console#/project" 
					target="_blank">Google\'s Cloud Console</a>, under the API &amp; auth - APIs 
					menu options. Confirm that you have enabled Google\'s URL Shortener by checking 
					the \'Yes\' option here. You can then select the Goo.gl shortener in the '.
					$this->p->util->get_admin_url( 'sharing', 'Twitter settings' ).'.';
					break;
			}
			return $text;
		}

		public function filter_tooltip_postmeta( $text, $idx, $atts ) {
			$ptn = empty( $atts['ptn'] ) ? 'Post' : $atts['ptn'];
			switch ( $idx ) {
				 case 'tooltip-postmeta-pin_desc':
					$text = 'A custom caption text, used by the Pinterest social sharing button, 
					for the custom Image ID, attached or featured image.';
				 	break;
				 case 'tooltip-postmeta-tumblr_img_desc':
				 	$text = 'A custom caption, used by the Tumblr social sharing button, 
					for the custom Image ID, attached or featured image.';
				 	break;
				 case 'tooltip-postmeta-tumblr_vid_desc':
					$text = 'A custom caption, used by the Tumblr social sharing button, 
					for the custom Video URL or embedded video.';
				 	break;
				 case 'tooltip-postmeta-twitter_desc':
				 	$text = 'A custom Tweet text for the Twitter social sharing button. 
					This text is in addition to any Twitter Card description.';
				 	break;
				 case 'tooltip-postmeta-buttons_disabled':
					$text = 'Disable all social sharing buttons (content, excerpt, widget, shortcode) for this '.$ptn.'.';
				 	break;
			}
			return $text;
		}

		public function wp_enqueue_styles() {
			// only include sharing styles if option is checked
			if ( ! empty( $this->p->options['buttons_use_social_css'] ) ) {

				// create the css file if it does not exist
				if ( ! file_exists( $this->sharing_css_min_file ) ) {
					$this->p->debug->log( 'updating '.$this->sharing_css_min_file );
					$this->update_sharing_css( $this->p->options );
				}

				if ( ! empty( $this->p->options['buttons_enqueue_social_css'] ) ) {
					$this->p->debug->log( 'wp_enqueue_style = '.$this->p->cf['lca'].'_sharing_buttons' );
					wp_register_style( 
						$this->p->cf['lca'].'_sharing_buttons', 
						$this->sharing_css_min_url, 
						false, 
						$this->p->cf['plugin'][$this->p->cf['lca']]['version']
					);
					wp_enqueue_style( $this->p->cf['lca'].'_sharing_buttons' );
				} else {
					if ( ! is_readable( $this->sharing_css_min_file ) ) {
						if ( is_admin() )
							$this->p->notice->err( $this->sharing_css_min_file.' is not readable.', true );
						$this->p->debug->log( $this->sharing_css_min_file.' is not readable' );
					} else {
						echo '<style type="text/css">';
						if ( ( $fsize = @filesize( $this->sharing_css_min_file ) ) > 0 &&
							$fh = @fopen( $this->sharing_css_min_file, 'rb' ) ) {
							echo fread( $fh, $fsize );
							fclose( $fh );
						}
						echo '</style>',"\n";
					}
				}
			} else $this->p->debug->log( 'social css option is disabled' );
		}

		public function update_sharing_css( &$opts ) {
			if ( ! empty( $opts['buttons_use_social_css'] ) ) {
				if ( ! $fh = @fopen( $this->sharing_css_min_file, 'wb' ) ) {
					if ( ! is_writable( NGFB_CACHEDIR ) ) {
						if ( is_admin() )
							$this->p->notice->err( NGFB_CACHEDIR.' is not writable.', true );
						$this->p->debug->log( NGFB_CACHEDIR.' is not writable', true );
					}
					if ( is_admin() )
						$this->p->notice->err( 'Failed to open file '.$this->sharing_css_min_file.' for writing.', true );
					$this->p->debug->log( 'failed opening '.$this->sharing_css_min_file.' for writing' );
				} else {
					$css_data = '';
					$style_tabs = apply_filters( $this->p->cf['lca'].'_style_tabs', self::$cf['sharing']['style'] );
					foreach ( $style_tabs as $id => $name )
						if ( array_key_exists( 'buttons_css_'.$id, $opts ) )
							$css_data .= $opts['buttons_css_'.$id];
					$classname = apply_filters( $this->p->cf['lca'].'_load_lib', false, 'ext/compressor', 'SuextMinifyCssCompressor' );
					if ( $classname !== false && class_exists( $classname ) ) {
						$css_data = call_user_func( array( $classname, 'process' ), $css_data );
						if ( fwrite( $fh, $css_data ) === false ) {
							if ( is_admin() )
								$this->p->notice->err( 'Failed writing to file '.$this->sharing_css_min_file.'.', true );
							$this->p->debug->log( 'failed writing to '.$this->sharing_css_min_file );
						} else $this->p->debug->log( 'updated css file '.$this->sharing_css_min_file );
						fclose( $fh );
					}
				}
			} else $this->unlink_sharing_css();
		}

		public function unlink_sharing_css() {
			if ( file_exists( $this->sharing_css_min_file ) ) {
				if ( ! @unlink( $this->sharing_css_min_file ) && is_admin() )
					$this->p->notice->err( 'Error removing minimized stylesheet file. Does the web server have sufficient privileges?', true );
			}
		}

		public function add_post_metaboxes() {
			if ( ! is_admin() )
				return;

			if ( ! $this->have_buttons( 'admin_edit' ) )
				return;

			// get the current object / post type
			if ( ( $obj = $this->p->util->get_post_object() ) === false ) {
				$this->p->debug->log( 'exiting early: invalid object type' );
				return;
			}
			$post_type = get_post_type_object( $obj->post_type );

			if ( ! empty( $this->p->options[ 'buttons_add_to_'.$post_type->name ] ) ) {
				// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
				add_meta_box( '_'.$this->p->cf['lca'].'_share', 'Sharing Buttons', 
					array( &$this, 'show_admin_sharing' ), $post_type->name, 'side', 'high' );
			}
		}

		public function add_buttons_filter( $type = 'the_content' ) {
			add_filter( $type, array( &$this, 'get_buttons_'.$type ), NGFB_SOCIAL_PRIORITY );
			$this->p->debug->log( 'buttons filter for '.$type.' added' );
		}

		public function remove_buttons_filter( $type = 'the_content' ) {
			$rc = remove_filter( $type, array( &$this, 'get_buttons_'.$type ), NGFB_SOCIAL_PRIORITY );
			$this->p->debug->log( 'buttons filter for '.$type.' removed ('.( $rc  ? 'true' : 'false' ).')' );
			return $rc;
		}

		public function show_header() {
			echo $this->get_js_loader();
			echo $this->get_js( 'header' );
			$this->p->debug->show_html( null, 'Debug Log' );
		}

		public function show_footer() {
			echo $this->show_sidebar();
			echo $this->get_js( 'footer' );
			$this->p->debug->show_html( null, 'Debug Log' );
		}

		public function show_sidebar() {
			if ( ! $this->have_buttons( 'sidebar' ) ) {
				$this->p->debug->log( 'exiting early: no buttons enabled for sidebar' );
				return;
			}
			$js = trim( preg_replace( '/\/\*.*\*\//', '', $this->p->options['buttons_js_sidebar'] ) );
			$text = '';	// varabled passed by reference
			$text = $this->get_buttons( $text, 'sidebar', false );	// use_post = false
			if ( ! empty( $text ) ) {
				echo '<div id="'.$this->p->cf['lca'].'-sidebar">';
				echo '<div id="'.$this->p->cf['lca'].'-sidebar-header"></div>';
				echo $text;
				echo '</div>', "\n";
				echo '<script type="text/javascript">'.$js.'</script>', "\n";
			}
			$this->p->debug->show_html( null, 'Debug Log' );
		}

		public function show_admin_sharing( $post ) {
			$post_type = get_post_type_object( $post->post_type );	// since 3.0
			$post_type_name = ucfirst( $post_type->name );
			$css_data = $this->p->options['buttons_css_admin_edit'];
			$classname = apply_filters( $this->p->cf['lca'].'_load_lib', false, 'ext/compressor', 'SuextMinifyCssCompressor' );
			if ( $classname !== false && class_exists( $classname ) )
				$css_data = call_user_func( array( $classname, 'process' ), $css_data );

			echo '<style type="text/css">'.$css_data.'</style>', "\n";
			echo '<table class="sucom-setting side"><tr><td>';
			if ( get_post_status( $post->ID ) == 'publish' ) {
				$content = '';
				echo $this->get_js_loader();
				echo $this->get_js( 'header' );
				echo $this->get_buttons( $content, 'admin_edit' );
				echo $this->get_js( 'footer' );
				$this->p->debug->show_html( null, 'Debug Log' );
			} else echo '<p class="centered">The '.$post_type_name.' must be published<br/>before it can be shared.</p>';
			echo '</td></tr></table>';
		}

		public function get_buttons_the_excerpt( $text ) {
			$id = $this->p->cf['lca'].' excerpt-buttons';
			$text = preg_replace_callback( '/(<!-- '.$id.' begin -->.*<!-- '.$id.' end -->)(<\/p>)?/Usi', 
				array( __CLASS__, 'remove_paragraph_tags' ), $text );
			return $text;
		}

		public function get_buttons_get_the_excerpt( $text ) {
			return $this->get_buttons( $text, 'excerpt' );
		}

		public function get_buttons_the_content( $text ) {
			return $this->get_buttons( $text, 'content' );
		}

		public function get_buttons( &$text, $type = 'content', $use_post = true ) {

			// should we skip the sharing buttons for this content type or webpage?
			if ( is_admin() ) {
				if ( strpos( $type, 'admin_' ) !== 0 ) {
					$this->p->debug->log( $type.' filter skipped: '.$type.' ignored with is_admin()'  );
					return $text;
				}
			} else {
				if ( ! is_singular() && empty( $this->p->options['buttons_on_index'] ) ) {
					$this->p->debug->log( $type.' filter skipped: index page without buttons_on_index enabled' );
					return $text;
				} elseif ( is_front_page() && empty( $this->p->options['buttons_on_front'] ) ) {
					$this->p->debug->log( $type.' filter skipped: front page without buttons_on_front enabled' );
					return $text;
				}
				if ( $this->is_post_buttons_disabled() ) {
					$this->p->debug->log( $type.' filter skipped: sharing buttons disabled' );
					return $text;
				}
			}

			if ( ! $this->have_buttons( $type ) ) {
				$this->p->debug->log( $type.' filter exiting early: no sharing buttons enabled' );
				return $text;
			}

			// get the post id for the transient cache salt
			if ( ( $obj = $this->p->util->get_post_object( $use_post ) ) === false ) {
				$this->p->debug->log( 'exiting early: invalid object type' );
				return $text;
			}

			$html = false;
			if ( $this->p->is_avail['cache']['transient'] ) {
				// if the post id is 0, then add the sharing url to ensure a unique salt string
				$cache_salt = __METHOD__.'(lang:'.SucomUtil::get_locale().'_obj:'.$obj->ID.'_type:'.$type.
					( empty( $obj->ID ) ? '_url:'.$this->p->util->get_sharing_url( true ) : '' ).')';
				$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
				$cache_type = 'object cache';
				$this->p->debug->log( $cache_type.': transient salt '.$cache_salt );
				$html = get_transient( $cache_id );
			}

			if ( $html !== false ) {
				$this->p->debug->log( $cache_type.': '.$type.' html retrieved from transient '.$cache_id );
			} else {
				// sort enabled sharing buttons by their preferred order
				$sorted_ids = array();
				foreach ( $this->p->cf['opt']['pre'] as $id => $pre )
					if ( ! empty( $this->p->options[$pre.'_on_'.$type] ) )
						$sorted_ids[ zeroise( $this->p->options[$pre.'_order'], 3 ).'-'.$id ] = $id;
				ksort( $sorted_ids );

				$atts['use_post'] = $use_post;
				$css_type = $atts['css_id'] = $type.'-buttons';
				if ( ! empty( $this->p->options['buttons_preset_'.$type] ) )
					$atts['preset_id'] = $this->p->options['buttons_preset_'.$type];

				$buttons_html = $this->get_html( $sorted_ids, $atts, $this->p->options );
				if ( ! empty( $buttons_html ) ) {
					$html = '<!-- '.$this->p->cf['lca'].' '.$css_type.' begin --><div '.
						( $use_post ? 'class' : 'id' ).'="'.$this->p->cf['lca'].'-'.$css_type.'">'.
						$buttons_html.'</div><!-- '.$this->p->cf['lca'].' '.$css_type.' end -->';

					if ( ! empty( $cache_id ) ) {
						set_transient( $cache_id, $html, $this->p->cache->object_expire );
						$this->p->debug->log( $cache_type.': '.$type.' html saved to transient '.
							$cache_id.' ('.$this->p->cache->object_expire.' seconds)' );
					}
				}
			}

			// just in case
			$buttons_pos = empty( $this->p->options['buttons_pos_'.$type] ) ? 
				'bottom' : $this->p->options['buttons_pos_'.$type];

			switch ( $buttons_pos ) {
				case 'top': 
					$text = $html.$text; 
					break;
				case 'bottom': 
					$text = $text.$html; 
					break;
				case 'both': 
					$text = $html.$text.$html; 
					break;
			}
			return $text.$this->p->debug->get_html();
		}

		// get_html() is called by the widget, shortcode, function, and perhaps some filter hooks
		public function get_html( &$ids = array(), &$atts = array() ) {

			$preset_id = empty( $atts['preset_id'] ) ? '' : 
				preg_replace( '/[^a-z0-9\-_]/', '', $atts['preset_id'] );

			$filter_id = empty( $atts['filter_id'] ) ? '' : 
				preg_replace( '/[^a-z0-9\-_]/', '', $atts['filter_id'] );

			// important: possibly dereference the opts variable to prevent passing on changes
			if ( empty( $preset_id ) && empty( $filter_id ) )
				$custom_opts =& $this->p->options;
			else $custom_opts = $this->p->options;

			// apply the presets to $custom_opts
			if ( ! empty( $preset_id ) && ! empty( self::$cf['opt']['preset'] ) ) {
				if ( array_key_exists( $preset_id, self::$cf['opt']['preset'] ) &&
					is_array( self::$cf['opt']['preset'][$preset_id] ) )
						$custom_opts = array_merge( $custom_opts, self::$cf['opt']['preset'][$preset_id] );
				else $this->p->debug->log( $preset_id.' preset missing or not array'  );
			} 

			$filter_name = $this->p->cf['lca'].'_sharing_html_'.$filter_id.'_options';
			if ( ! empty( $filter_id ) && has_filter( $filter_name ) )
				$custom_opts = apply_filters( $filter_name, $custom_opts );

			$html = '';
			foreach ( $ids as $id ) {
				$id = preg_replace( '/[^a-z]/', '', $id );	// sanitize the website object name
				if ( method_exists( $this->website[$id], 'get_html' ) )
					$html .= $this->website[$id]->get_html( $atts, $custom_opts );
			}
			if ( ! empty( $html ) ) 
				$html = '<div class="'.$this->p->cf['lca'].'-buttons">'."\n".$html.'</div>';
			return $html;
		}

		// add javascript for enabled buttons in content, widget, shortcode, etc.
		public function get_js( $pos = 'header', $ids = array() ) {

			// determine which (if any) sharing buttons are enabled
			// loop through the sharing button option prefixes (fb, gp, etc.)
			if ( empty( $ids ) ) {
				if ( is_admin() ) {
					if ( ( $obj = $this->p->util->get_post_object() ) === false  ||
						 empty( $obj->filter ) || $obj->filter !== 'edit' )
							return;
				} elseif ( is_singular() && $this->is_post_buttons_disabled() ) {
					$this->p->debug->log( 'exiting early: buttons disabled' );
					return;
				}

				if ( class_exists( 'NgfbWidgetSharing' ) ) {
					$widget = new NgfbWidgetSharing();
		 			$widget_settings = $widget->get_settings();
				} else $widget_settings = array();

				if ( is_admin() ) {
					foreach ( $this->p->cf['opt']['pre'] as $id => $pre ) {
						foreach ( SucomUtil::preg_grep_keys( '/^'.$pre.'_on_admin_/', $this->p->options ) as $key => $val )
							if ( ! empty( $val ) )
								$ids[] = $id;
					}
				} else {
					if ( is_singular() || 
						( ! is_singular() && ! empty( $this->p->options['buttons_on_index'] ) ) || 
						( is_front_page() && ! empty( $this->p->options['buttons_on_front'] ) ) ) {
	
						// exclude buttons enabled for admin editing pages
						foreach ( $this->p->cf['opt']['pre'] as $id => $pre ) {
							foreach ( SucomUtil::preg_grep_keys( '/^'.$pre.'_on_/', $this->p->options ) as $key => $val )
								if ( strpos( $key, $pre.'_on_admin_' ) === false && ! empty( $val ) )
									$ids[] = $id;
						}
					}
					// check for enabled buttons in ACTIVE widget(s)
					foreach ( $widget_settings as $num => $instance ) {
						if ( is_object( $widget ) && is_active_widget( false, $widget->id_base.'-'.$num, $widget->id_base ) ) {
							foreach ( $this->p->cf['opt']['pre'] as $id => $pre ) {
								if ( array_key_exists( $id, $instance ) && 
									! empty( $instance[$id] ) )
										$ids[] = $id;
							}
						}
					}
				}
				if ( empty( $ids ) ) {
					$this->p->debug->log( 'exiting early: no buttons enabled' );
					return;
				}
			}

			natsort( $ids );
			$ids = array_unique( $ids );
			$js = '<!-- '.$this->p->cf['lca'].' '.$pos.' javascript begin -->'."\n";

			if ( strpos( $pos, '-header' ) ) 
				$js_loc = 'header';
			elseif ( strpos( $pos, '-footer' ) ) 
				$js_loc = 'footer';
			else $js_loc = $pos;

			if ( ! empty( $ids ) ) {
				foreach ( $ids as $id ) {
					$id = preg_replace( '/[^a-z]/', '', $id );
					$opt_name = $this->p->cf['opt']['pre'][$id].'_js_loc';
					if ( method_exists( $this->website[$id], 'get_js' ) && 
						! empty( $this->p->options[$opt_name] ) && 
						$this->p->options[$opt_name] == $js_loc )
							$js .= $this->website[$id]->get_js( $pos );
				}
			}
			$js .= '<!-- '.$this->p->cf['lca'].' '.$pos.' javascript end -->'."\n";
			return $js;
		}

		public function get_js_loader( $pos = 'id' ) {
			$lang = empty( $this->p->options['gp_lang'] ) ? 'en-US' : $this->p->options['gp_lang'];
			$lang = apply_filters( $this->p->cf['lca'].'_lang', $lang, SucomUtil::get_pub_lang( 'gplus' ) );
			return '<script type="text/javascript" id="ngfb-header-script">
				window.___gcfg = { lang: "'.$lang.'" };
				function '.$this->p->cf['lca'].'_insert_js( script_id, url, async ) {
					if ( document.getElementById( script_id + "-js" ) ) return;
					var async = typeof async !== "undefined" ? async : true;
					var script_pos = document.getElementById( script_id );
					var js = document.createElement( "script" );
					js.id = script_id + "-js";
					js.async = async;
					js.type = "text/javascript";
					js.language = "JavaScript";
					js.src = url;
					script_pos.parentNode.insertBefore( js, script_pos );
				};</script>'."\n";
		}

		public function get_css( $css_name, &$atts = array(), $css_class_extra = '' ) {
			global $post;

			$css_class = $css_name.'-'.( empty( $atts['css_class'] ) ? 
				'button' : $atts['css_class'] );

			$css_id = $css_name.'-'.( empty( $atts['css_id'] ) ? 
				'button' : $atts['css_id'] );

			if ( is_singular() && ! empty( $post->ID ) ) 
				$css_id .= '-post-'.$post->ID;

			if ( ! empty( $css_class_extra ) ) 
				$css_class = $css_class_extra.' '.$css_class;

			return 'class="'.$css_class.'" id="'.$css_id.'"';
		}

		public function is_post_buttons_disabled() {
			global $post;
			$ret = false;
			if ( ! empty( $post ) ) {
				$post_type = $post->post_type;
				if ( $this->p->addons['util']['postmeta']->get_options( $post->ID, 'buttons_disabled' ) ) {
					$this->p->debug->log( 'post '.$post->ID.': sharing buttons disabled by custom meta option' );
					$ret = true;
				} elseif ( ! empty( $post_type ) && empty( $this->p->options['buttons_add_to_'.$post_type] ) ) {
					$this->p->debug->log( 'post '.$post->ID.': sharing buttons not enabled for post type '.$post_type );
					$ret = true;
				}
			}
			return apply_filters( $this->p->cf['lca'].'_post_buttons_disabled', $ret );
		}

		public function remove_paragraph_tags( $match = array() ) {
			if ( empty( $match ) || ! is_array( $match ) ) return;
			$text = empty( $match[1] ) ? '' : $match[1];
			$suff = empty( $match[2] ) ? '' : $match[2];
			$ret = preg_replace( '/(<\/*[pP]>|\n)/', '', $text );
			return $suff.$ret; 
		}

		public function have_buttons( $type ) {
			foreach ( $this->p->cf['opt']['pre'] as $id => $pre )
				if ( ! empty( $this->p->options[$pre.'_on_'.$type] ) )
					return true;
			return false;
		}

		public function get_website_ids() {
			$ids = array();
			foreach ( array_keys( $this->website ) as $id )
				$ids[$id] = $this->p->cf['plugin']['ngfb']['lib']['website'][$id];
			return $ids;
		}
	}
}

?>
