<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuSharingTwitter' ) && class_exists( 'NgfbSubmenuSharing' ) ) {

	class NgfbSubmenuSharingTwitter extends NgfbSubmenuSharing {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();
			
			$rows[] = $this->p->util->th( 'Show Button in', 'short' ).'<td>'.
			( $this->show_on_checkboxes( 'twitter' ) ).'</td>';

			$rows[] = $this->p->util->th( 'Preferred Order', 'short' ).'<td>'.
			$this->form->get_select( 'twitter_order', 
				range( 1, count( $this->p->admin->submenu['sharing']->website ) ), 
					'short' ).'</td>';

			if ( $this->p->options['plugin_display'] == 'all' ) {
				$rows[] = $this->p->util->th( 'JavaScript in', 'short' ).'<td>'.
				$this->form->get_select( 'twitter_js_loc', $this->p->cf['form']['js_locations'] ).'</td>';
			}

			$rows[] = $this->p->util->th( 'Default Language', 'short' ).'<td>'.
			$this->form->get_select( 'twitter_lang', SucomUtil::get_pub_lang( 'twitter' ) ).'</td>';

			$rows[] = $this->p->util->th( 'Count Position', 'short' ).'<td>'.
			$this->form->get_select( 'twitter_count', array( 'none' => '', 
			'horizontal' => 'Horizontal', 'vertical' => 'Vertical' ) ).'</td>';

			$rows[] = $this->p->util->th( 'Button Size', 'short' ).'<td>'.
			$this->form->get_select( 'twitter_size', array( 'medium' => 'Medium', 'large' => 'Large' ) ).'</td>';

			$rows[] = $this->p->util->th( 'Tweet Text Source', 'short' ).'<td>'.
			$this->form->get_select( 'twitter_caption', $this->p->cf['form']['caption_types'] ).'</td>';

			if ( $this->p->options['plugin_display'] == 'all' ) {
				$rows[] = $this->p->util->th( 'Tweet Text Length', 'short' ).'<td>'.
				$this->form->get_input( 'twitter_cap_len', 'short' ).' characters or less</td>';

				$rows[] = $this->p->util->th( 'Do Not Track', 'short', null,
				'Disable tracking for Twitter\'s tailored suggestions and tailored ads.' ).
				'<td>'.$this->form->get_checkbox( 'twitter_dnt' ).'</td>';
			}

			$rows[] = $this->p->util->th( 'Add via @username', 'short', null,
			'Append the website\'s @username to the tweet (see the '.
			$this->p->util->get_admin_url( 'general#sucom-tab_pub_twitter', 'Twitter' ).
			' options tab on the General settings page). '.
			'The website\'s @username will also be displayed and recommended after the Post / Page is shared.' ).
			( $this->p->check->aop( 'ngfb' ) ? '<td>'.$this->form->get_checkbox( 'twitter_via' ).'</td>' :
				'<td class="blank">'.$this->form->get_no_checkbox( 'twitter_via' ).'</td>' );

			$rows[] = $this->p->util->th( 'Recommend Author', 'short', null, 
			'Recommend following the Author\'s Twitter @username (from their profile) after sharing. 
			If the \'<em>Add via @username</em>\' option (above) is also checked, the Website\'s @username will be suggested first.' ).
			( $this->p->check->aop( 'ngfb' ) ? '<td>'.$this->form->get_checkbox( 'twitter_rel_author' ).'</td>' :
				'<td class="blank">'.$this->form->get_no_checkbox( 'twitter_rel_author' ).'</td>' );

			if ( isset( $this->p->addons['admin']['apikeys'] ) ) {
				$rows[] = $this->p->util->th( 'Shorten URLs with', 'short', null, 
				'If you select a URL shortening service here, <strong>you must also enter its API credentials</strong>
				on the '.$this->p->util->get_admin_url( 'advanced#sucom-tab_plugin_apikeys', 'Advanced settings page' ).'.' ).
				( $this->p->check->aop( 'ngfb' ) ?  '<td>'.$this->form->get_select( 'twitter_shortener', $this->p->cf['form']['shorteners'], 'medium' ).'&nbsp;' :
				'<td class="blank">'.$this->form->get_hidden( 'twitter_shortener' ).$this->p->cf['form']['shorteners'][$this->p->options['twitter_shortener']].' &mdash; ' ).
				' using these '.$this->p->util->get_admin_url( 'advanced#sucom-tab_plugin_apikeys', 'API Keys' ).'</td>';
			}

			return $rows;
		}
	}
}

if ( ! class_exists( 'NgfbSharingTwitter' ) ) {

	class NgfbSharingTwitter {

		private static $cf = array(
			'opt' => array(				// options
				'defaults' => array(
					'twitter_on_content' => 0,
					'twitter_on_excerpt' => 0,
					'twitter_on_admin_edit' => 1,
					'twitter_on_sidebar' => 0,
					'twitter_order' => 3,
					'twitter_js_loc' => 'header',
					'twitter_lang' => 'en',
					'twitter_count' => 'horizontal',
					'twitter_caption' => 'title',
					'twitter_cap_len' => 140,
					'twitter_size' => 'medium',
					'twitter_via' => 1,
					'twitter_rel_author' => 1,
					'twitter_dnt' => 1,
					'twitter_shortener' => 'none',
				),
			),
		);

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->util->add_plugin_filters( $this, array( 'get_defaults' => 1 ) );
		}

		public function filter_get_defaults( $opts_def ) {
			return array_merge( $opts_def, self::$cf['opt']['defaults'] );
		}

		public function get_html( $atts = array(), &$opts = array() ) {
			if ( empty( $opts ) ) 
				$opts =& $this->p->options;
			global $post; 
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$use_post = array_key_exists( 'use_post', $atts ) ? $atts['use_post'] : true;
			$source_id = $this->p->util->get_source_id( 'twitter', $atts );
			$atts['add_page'] = array_key_exists( 'add_page', $atts ) ? $atts['add_page'] : true;	// get_sharing_url argument

			$long_url = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $source_id ) : 
				apply_filters( $this->p->cf['lca'].'_sharing_url', $atts['url'], 
					$use_post, $atts['add_page'], $source_id );

			$short_url = apply_filters( $this->p->cf['lca'].'_shorten_url', 
				$long_url, $opts['twitter_shortener'] );

			if ( ! array_key_exists( 'lang', $atts ) )
				$atts['lang'] = empty( $opts['twitter_lang'] ) ? 'en' : $opts['twitter_lang'];
			$atts['lang'] = apply_filters( $this->p->cf['lca'].'_lang', $atts['lang'], SucomUtil::get_pub_lang( 'twitter' ) );

			if ( array_key_exists( 'tweet', $atts ) )
				$atts['caption'] = $atts['tweet'];

			if ( ! array_key_exists( 'caption', $atts ) ) {
				if ( ! empty( $post->ID ) && $use_post == true ) 
					$atts['caption'] = $this->p->addons['util']['postmeta']->get_options( $post->ID, 'twitter_desc' );

				if ( empty( $atts['caption'] ) ) {
					// get_tweet_max_len() needs the long URL as input
					$cap_len = $this->p->util->get_tweet_max_len( $long_url );
					$atts['caption'] = $this->p->webpage->get_caption( $opts['twitter_caption'], $cap_len, $use_post );
				}
			}

			if ( ! array_key_exists( 'via', $atts ) ) {
				if ( ! empty( $opts['twitter_via'] ) && 
					$this->p->check->aop( 'ngfb' ) )
						$atts['via'] = preg_replace( '/^@/', '', $opts['tc_site'] );
				else $atts['via'] = '';
			}

			if ( ! array_key_exists( 'related', $atts ) ) {
				if ( ! empty( $opts['twitter_rel_author'] ) && 
					$this->p->check->aop( 'ngfb' ) && ! empty( $post ) && $use_post == true )
						$atts['related'] = preg_replace( '/^@/', '', 
							get_the_author_meta( $opts['plugin_cm_twitter_name'], $post->author ) );
				else $atts['related'] = '';
			}

			// hashtags are included in the caption instead
			if ( ! array_key_exists( 'hashtags', $atts ) )
				$atts['hashtags'] = '';

			if ( ! array_key_exists( 'dnt', $atts ) ) 
				$atts['dnt'] = $opts['twitter_dnt'] ? 'true' : 'false';

			$html = '<!-- Twitter Button --><div '.$this->p->sharing->get_css( 'twitter', $atts ).'>';
			$html .= '<a href="'.$prot.'//twitter.com/share" class="twitter-share-button" data-lang="'. $atts['lang'].'" ';
			$html .= 'data-url="'.$short_url.'" data-counturl="'.$long_url.'" data-text="'.$atts['caption'].'" ';
			$html .= 'data-via="'.$atts['via'].'" data-related="'.$atts['related'].'" data-hashtags="'.$atts['hashtags'].'" ';
			$html .= 'data-count="'.$opts['twitter_count'].'" data-size="'.$opts['twitter_size'].'" data-dnt="'.$atts['dnt'].'"></a></div>';

			$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );
			return $html."\n";
		}
		
		public function get_js( $pos = 'id' ) {
			$this->p->debug->mark();
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$js_url = $this->p->util->get_cache_url( $prot.'//platform.twitter.com/widgets.js' );

			return '<script type="text/javascript" id="twitter-script-'.$pos.'">'.$this->p->cf['lca'].'_insert_js( "twitter-script-'.$pos.'", "'.$js_url.'" );</script>'."\n";
		}
	}
}

?>
