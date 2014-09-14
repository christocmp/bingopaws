<?php


/*--------------------------------------------------------------*/
/*                   Theme Setup Here                           */                      
/*--------------------------------------------------------------*/


//----------Include General Functions-------------------------

include_once(TEMPLATEPATH .'/includes/options.lib.php');


//----------Include Option Panels-----------------------------

include_once(TEMPLATEPATH .'/includes/theme-options.php');
include_once(TEMPLATEPATH .'/includes/design-options.php');

include_once(TEMPLATEPATH .'/includes/gambling-options.php');  

include_once(TEMPLATEPATH .'/includes/pageresize.php');

include_once(TEMPLATEPATH .'/includes/layout-options.php');

//---Redirect URLs and Hide Affiliate Links
include_once(TEMPLATEPATH .'/includes/redirects.php');

// Hit counter on affiliate links
include_once(TEMPLATEPATH .'/includes/hitcounter.php');               

//---Banner Panel and Widget
include_once(TEMPLATEPATH .'/includes/banner-manager.php');

//---Theme Breadcrumbs
include_once(TEMPLATEPATH .'/includes/breadcrumbs.php');

//---Custom Pages
include_once(TEMPLATEPATH .'/includes/custom-type-templates.php');

//---Add Affiliate Post Type and Meta Boxes
include_once(TEMPLATEPATH .'/includes/custom_meta_boxes.php');




// Add Color picker to all admin pages
add_action( 'admin_enqueue_scripts', 'cdf_enqueue_scripts' );
	function cdf_enqueue_scripts( $hook_suffix ) {
  		wp_enqueue_style( 'wp-color-picker' );
         	wp_enqueue_script( 'flytonic-colors', get_bloginfo('template_url').'/includes/js/flytonic_color_picker.js', array( 'wp-color-picker' ), false, true );
 		wp_enqueue_script('jquery-ui-dialog');        
    		wp_enqueue_style('wp-jquery-ui-dialog');

	}


// Set outbound affiliate redirect slug, default is "visit"

function fly_redirect_slug() {
	if (get_theme_option('redirect-slug')){
	$redirectkey=get_theme_option('redirect-slug');
 	  
	} else { $redirectkey = 'visit'; 	 
     	}

    	 return $redirectkey;
}


//Add Columns to Casino Post Type
function casino_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Title",
    "description" => "Description",
    "bonustext" => "Bonus Info",
    "rating" => "Rating",
  "rank" => "Rank",
  "roomurl" => "Affiliate URL",
  'date' => "Post Date"
  );
 
  return $columns;
}

//Add Columns to Casino Post Type
function casino_custom_columns($column){
  global $post;
 
  switch ($column) {
    case 'description':
        the_excerpt();
      break;
 
    case 'bonustext':
         echo get_post_meta( $post->ID , '_as_bonustext' , true ); 
      break;
    case 'rating':
       echo get_post_meta( $post->ID , '_as_rating' , true ); 
      break;

 case 'roomurl':
       echo get_post_meta( $post->ID , '_as_roomurl' , true ); 
      break;

  }
}
add_action("manage_posts_custom_column",  "casino_custom_columns");
add_filter("manage_edit-casino_columns", "casino_edit_columns");


//-------------------------Widget Setup----------------------------

function flytonic_widgets() {
	// Sidebar 1
	register_sidebar( array(
		'name' => 'Sidebar 1',
		'id' => 'sidebar1-widgets',
		'description' =>  'Main Sidebar Area',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );


	// Footer Widget 1
	register_sidebar( array(
		'name' => 'Footer Widget 1',
		'id' => 'footer1-widgets',
		'description' => 'Footer Widget Area 1',
                'before_widget' => '<section class="footerwidget">',
		'after_widget' => '</section>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );

	// Footer Widget 2
	register_sidebar( array(
		'name' => 'Footer Widget 2',
		'id' => 'footer2-widgets',
		'description' => 'Footer Widget Area 2',
                'before_widget' => '<section class="footerwidget">',
		'after_widget' => '</section>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );

	// Footer Widget 3
	register_sidebar( array(
		'name' => 'Footer Widget 3',
		'id' => 'footer3-widgets',
		'description' => 'Footer Widget Area 3',
                'before_widget' => '<section class="footerwidget">',
		'after_widget' => '</section>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );

        // Footer Widget 4
	register_sidebar( array(
		'name' => 'Footer Widget 4',
		'id' => 'footer4-widgets',
		'description' => 'Footer Widget Area 4',
                'before_widget' => '<section class="footerwidget">',
		'after_widget' => '</section>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );


	// Header Widgets
	register_sidebar( array(
		'name' => 'Header Widgets',
		'id' => 'headertop-widgets',
		'description' =>  'Top Right Header Widget Area',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div><!--.widget-->',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );
	
}

add_action('widgets_init', 'flytonic_widgets');

//Theme Support for Thumbnails
if ( function_exists( 'add_theme_support' ) ) { // Added in 2.9
	    add_theme_support( 'post-thumbnails' );
	    set_post_thumbnail_size( 150, 150, true ); // Normal post thumbnails
	}

//  Add image sizes for casino logos
if ( function_exists( 'add_image_size' ) ) { 
	   add_image_size( 'casino-icon', 80, 40, false );  
	   add_image_size( 'casino-logo', 160, 80, false ); 
	}


//Add menus
add_theme_support( 'menus' );// Added in 3.0

//Add gallery theme support
add_theme_support( 'html5', array( 'gallery') );

register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'bingotheme' ),
		'featured' => __( 'Featured Pages Menu', 'bingotheme' )

	) );

// Menu fallback
function fly_default_menu() { ?>
 	<ul class="nav" id="nav">                
 	<li class="current-menu-item"><a href="<?php bloginfo('url'); ?>/wp-admin/nav-menus.php"><span>Please Set Up Your Menu</span></a></li>
	</ul>

<?php }

// Add Comments HTML5 Support
function fly_comments_setup() {
    add_theme_support( 'html5', array( 'comment-list', 'search-form', 'comment-form', ) );
}
add_action( 'after_setup_theme', 'fly_comments_setup' );


// -----------------------Excerpt Length-------------------------

function custom_excerpt_length( $length ) {

$exc=30;

if (get_theme_option ('excerpt-length') != ""):
$exc= get_theme_option ('excerpt-length');
endif;
return $exc;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function new_excerpt_more($more) {
       global $post;
	return ' <a href="' . get_permalink() . '">[...]</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');


//Show Home Page on Menu
function home_page_menu_args( $args ) {
$args['show_home'] = true;
return $args;
}
add_filter( 'wp_page_menu_args', 'home_page_menu_args' );

//---------------------Add Scripts----------------------

function flytonic_add_scripts() {
     wp_enqueue_script('selectnav', get_bloginfo('template_directory').'/includes/js/selectnav.js', false, false, true);
    wp_enqueue_script('selectnav_settings', get_bloginfo('template_directory').'/includes/js/selectnav_settings.js', false, false, true);

if (get_theme_option('theme-navfix')) {
  wp_enqueue_script('scroller', get_bloginfo('stylesheet_directory').'/includes/js/scroller.js',array('jquerylib'));
    wp_enqueue_script('jquerylib', 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');   

}


    
}
add_action('wp_enqueue_scripts', 'flytonic_add_scripts');


//---------------------Add Stylesheets----------------------

function flytonic_my_stylesheets() {

	
        wp_enqueue_style('shortcode-css', get_template_directory_uri() . '/includes/css/shortcode.css');
	
    	// Main Stylsheet
	wp_enqueue_style('flytonic_style', get_stylesheet_uri() );
	// Custom stylesheet - saved when user saves design options
    	wp_enqueue_style('custom_style', get_bloginfo('stylesheet_directory').'/includes/custom.css');

}
add_action('wp_enqueue_scripts', 'flytonic_my_stylesheets');

// Add Alternative Stylsheets
function flytonic_stylesheetsalt() {
	
	if (get_theme_option('theme-color') == 'Purple') {
	// Green Stylesheet
    	wp_enqueue_style('black_style', get_bloginfo('template_directory').'/styles/purple.css');
	} 
	
}

add_action('wp_enqueue_scripts', 'flytonic_stylesheetsalt','12');


//---------------------Check Custom.css----------------------

/* Installation Check */
function flytonict_showMessage($message, $errormsg = false)
{
	if ($errormsg) {
		echo '<div id="message" class="error">';
	}
	else {
		echo '<div id="message" class="updated fade">';
	}

	echo "<p><strong>$message</strong></p></div>";
}    

function flytonict_showAdminMessages()
{
    // Shows as an error message. You could add a link to the right page if you wanted.
	if (!file_exists(get_stylesheet_directory() . '/includes/custom.css')) {
		flytonict_showMessage("WARNING - Bingo Theme - A file with the name custom.css must be created and writeable in the includes directory for custom design options to be saved", true);
	} elseif (!is_writeable(get_stylesheet_directory() . '/includes/custom.css')) {
		flytonict_showMessage("WARNING - Bingo Theme - The file custom.css in the bingotheme/includes directory must be made writeable for custom design options to be saved.", true);
	}   
}

add_action('admin_notices', 'flytonict_showAdminMessages'); 


//---------------------- Pagination ---------------

function kriesi_pagination($pages = '', $range = 4)
{  
     $showitems = ($range)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<div class='pagination'>";
         //if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'> &laquo;</a>";
         if($paged > 1 ) echo "<a class='last' href='".get_pagenum_link($paged - 1)."'>&laquo; previous</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages ) echo "<a class='last' href='".get_pagenum_link($paged + 1)."'>next &raquo;</a>";  
         //if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</div>\n";
     }
}


//---------------------- Add Profile Fields ---------------

add_action( 'show_user_profile', 'flytonic_profile_fields' );
add_action( 'edit_user_profile', 'flytonic_profile_fields' );

function flytonic_profile_fields( $user ) { ?>

	<h3>Additional profile information</h3>

	<table class="form-table">

                <tr>
			<th><label for="authimg">Author Image Link</label></th>

			<td>
				<input type="text" name="authimg" id="authimg" value="<?php echo esc_attr( get_the_author_meta( 'authimg', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Enter the url for you author avatar image shown in bio (80x80 pixels)</span>
			</td>
		</tr>

		<tr>
			<th><label for="twitter">Twitter Username</label></th>

			<td>
				<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Enter your Twitter username.</span>
			</td>
		</tr>

	</table>
<?php }


add_action( 'personal_options_update', 'save_flytonic_profile_fields' );
add_action( 'edit_user_profile_update', 'save_flytonic_profile_fields' );

function save_flytonic_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'twitter', $_POST['twitter'] );
        update_usermeta( $user_id, 'authimg', $_POST['authimg'] );
}

//---------------------- Adjust Login Logo and URL ---------------


function ft_login_logo() {
   $logo = get_theme_option('login-logo');


        echo '<style type="text/css">
            body.login div#login h1 a {
                background-image: url('. $logo .');
                padding-bottom: 20px;
                background-size: auto !important;
		width: auto !important;
		
            }
        </style>';

    
}
add_action( 'login_enqueue_scripts', 'ft_login_logo' );



function ft_login_logo_url() {
    $logourl = get_theme_option('login-logourl');
    
    if(!empty($logourl)) { 
        return $logourl;
    } else {
        return get_bloginfo('url');
    }
    
}
add_filter( 'login_headerurl', 'ft_login_logo_url' );

function ft_login_logo_url_title() {
    $logoalt = get_theme_option('login-logoalt');
    
    if(!empty($logoalt)) { 
        return $logoalt;
    } else {
        return get_bloginfo()." Login";
    }
}
add_filter( 'login_headertitle', 'ft_login_logo_url_title' );

// Get casino posts
function getAllPostsByType($type='casino') {
	$posts = get_posts(array('numberposts'=>-1, 'post_type' => $type, 'orderby'=>'title', 'order'=>'ASC'));
	return $posts;
}

// Get unlinked terms
function fly_unlinkterm($id,$myterm) {
	$terms = wp_get_post_terms($id,$myterm);
	$count = count($terms);
 	$i=0;
 		if($count > 0){
		foreach ($terms as $term) {
		$i++;
      				$ret .=''.$term->name.'';
		
			if ($count != $i) {
        			$ret .= ', ';
       		 	}
		}
}
	
	return $ret;
}


function ft_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
add_filter( 'mce_buttons_2', 'ft_mce_buttons_2' );

function ft_tiny_mce_before_init( $settings ) {
	$style_formats = array(
		array( 'title' => 'H2 Heading - No Margin', 'block' => 'h2', 'classes' => 'marz' ),
		array( 'title' => 'H2 Heading - Bottom Border', 'block' => 'h2', 'classes' => 'underline' ),
	);
	$settings['style_formats'] = json_encode( $style_formats );
	return $settings;
}
add_filter( 'tiny_mce_before_init', 'ft_tiny_mce_before_init' );


// Function that will return our Wordpress menu
function ft_feat_menu() {


    $menu_name = 'featured';

    $menu_list = '<div class="contentmenu">';

    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
	$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );

	$menu_items = wp_get_nav_menu_items($menu->term_id);

	foreach ( (array) $menu_items as $key => $menu_item ) {
	    $title = $menu_item->title;

	$postid=$menu_item->id;
	    $url = $menu_item->url;
		$postid=$menu_item->object_id;
	    
		$menu_list .= '<div class="item">
 				<div class="top">'. get_the_post_thumbnail($postid,'thumbnail', array('class' => 'menuimage')) .'<a href="' . $url . '" class="visbutton blue cent">Read More</a> </div>
 				<h4><a title="' . $title . '" href="' . $url . '">' . $title . '</a></h4>
 				</div>   ';
	}
	
    } else {
	$menu_list = 'Menu "' . $menu_name . '" not defined.';
    }
    // $menu_list now ready to output

$menu_list .= '</div>';

return $menu_list;

}


//Create the shortcode
add_shortcode("featuredpages", "ft_feat_menu");


?>