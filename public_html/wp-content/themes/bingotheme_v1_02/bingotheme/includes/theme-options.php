<?php
// SETTINGS CONFIGURATION
$theme_options = array();


$theme_options[] = make_advanced_group("Branding Preferences", array(
	make_setting("Logo", "header-logo", "image", "The logo for your site.  Recommended size is less than 300px wide and 100px in height."),
	make_setting("Favicon URL", "branding-favicon", "text", "Enter the full url for your custom favicon."),
	make_setting("Login Logo", "login-logo", "image", "The logo that is shown on the login page.  Recommended size less than 200px x 60px"),
	make_setting("Login Logo URL", "login-logourl", "text", "The link url for the logo image.  Where the user goes when they click on the login logo."),
	make_setting("Login Logo Title", "login-logoalt", "text", "The alternative text for the logo on login page.")
	
));

$theme_options[] = make_advanced_group("Header Preferences", array(
	make_setting("Header script", "header-script", "textarea", "Additional information to insert in the page header file like Google Analytics code")
      
));

$themecolors=array( "Purple"=>"Purple");


$theme_options[] = make_advanced_group("General Options", array(
	make_setting("Fixed Navigation Bar", "theme-navfix", "checkbox", "Check for the navigation bar to remain on the top when scrolling down."),
	make_setting("Theme Color", "theme-color", $themecolors, "Choose a preset theme design style other than default."),
       make_setting("Article Thumbnail", "art-thumb", "image", "The default article thumbnail if one is not set (100x100)"),
       make_setting("Author Image", "auth-imgd", "image", "The defualt author image is the user has not set one (80x80)"),
       make_setting("Show Author Bio", "auth-bio", "checkbox", "Check to show the author bio on the post page"),
       make_setting("Bingo Review Page Slug", "affiliate-slug","text", "The casino review site slug, default is review.  Example - http://www.yoursite.com/slug/casinoname/.  You will need to reset your permalinks by resaving them if you change this, otherwise you will see a 404 page."),

));

$theme_options[] = make_advanced_group("Bylines", array(
	make_setting("Hide Entire Bylines", "bylines-hide-all", "checkbox","Check to hide all bylines including post date, category, author and comments from all areas."),
	make_setting("Hide Author", "bylines-hide-author", "checkbox"),
	make_setting("Hide Date", "bylines-hide-date", "checkbox"),
	make_setting("Hide Category", "bylines-hide-category", "checkbox"),
	make_setting("Hide Comments Link", "bylines-hide-comment", "checkbox", "Check to hide comments link and comments number in bylines.")
));

$theme_options[] = make_advanced_group("Breadcrumbs", array(
	make_setting("Enable Breadcrumbs", "breadcrumbs-enable", "checkbox"),
	make_setting("Hide breadcrumbs from pages", "bread-crumbs-hide-pages", "checkbox"),
	make_setting("Hide breadcrumbs from posts", "bread-crumbs-hide-posts", "checkbox"),
	//make_setting("Hide breadcrumbs from home", "bread-crumbs-hide-home", "checkbox"),
	make_setting("Hide breadcrumbs from 404 pages", "bread-crumbs-hide-404", "checkbox"),
	make_setting("Hide breadcrumbs from archive pages", "bread-crumbs-hide-archive", "checkbox")
));

$theme_options[] = make_advanced_group("Footer Options", array(
 	make_setting("Footer script", "footer-script", "textarea"),
        make_setting("Hide Footer Widget Area (Where Applicable)", "footer-toparea", "checkbox"),
 	make_setting("Hide Bottom Footer Area", "footer-bottomhide", "checkbox"),
	make_setting("Hide theme credit", "footer-credit", "checkbox"),
        make_setting("Bottom Footer Text", "footer-text", "textarea", "Enter text to replace the copyright, theme credit, and site link in the footer.")
));

$theme_options[] = make_advanced_group("Redirect Options", array(
	make_setting("Link Redirect Options", "redirect-new-window", "checkbox", "Check to have affiliate redirect links open in new windows when clicked"),
make_setting("Banner Redirect Options", "redirect-banner-window", "checkbox", "Check to have banners open in new windows when clicked"),

 make_setting("Outbound Redirect Slug", "redirect-slug","text", "Enter the outbound affiliate slug to replace 'visit'.  Use one word")

));

$theme_options[] = make_advanced_group("Excerpts", array(
	make_setting("Excerpt length", "excerpt-length", "text")
));


// END SETTINGS CONFIGURATION

//ADMINISTRATION SCREEN
add_action('admin_menu', 'theme_options_add_menu', 100);
function theme_options_add_menu()
{
	add_submenu_page('design-options', 'Theme Options', 'Theme Options', 'update_themes', 'theme-options', 'theme_options_show_ui');
}

function get_theme_options()
{
	$opc = get_option('theme-options-settings');
	if ($opc != "") return unserialize($opc);
	
	return array();
}

function get_theme_option($key)
{
	$options = get_theme_options();
	if (array_key_exists($key, $options)) {
		return $options[$key];
	}
	
	return false;
}

function theme_options_show_ui()
{
	
	if ($_REQUEST["action"] == "Reset to Default") {
		//delete the option
		delete_option('theme-options-settings');
	}
	
	if ($_REQUEST["action"] == "save-settings") {
		$tos = $_REQUEST["theme_options"];
		delete_option('theme-options-settings');
		add_option('theme-options-settings', serialize($tos));
	}	
	
	$existing_values = @unserialize(get_option('theme-options-settings'));	
	
	if (!is_array($existing_values)) $existing_values = array();
	
	global $theme_options;
	$theme_options = apply_filters('theme_options', $theme_options);

?>
<style>
	.inside-left, .inside-right {
		width: 48%; float: left;
		margin: 0 5px 0 5px; }
	
	.halfpostbox { margin: 5px 0 5px 0; }
	
	.upload_image_button, .clear_field_button {
		width: auto !important; }
		
	input.farbtastic_color { width: 200px !important; }
	.farbtastic_container { display: none; }
	
	.postbox .inside { display: none; }
</style>
<script>
jQuery(document).ready( function() {
	jQuery('.handlediv').toggle( 
		function() {
			jQuery(this).siblings('.inside').slideDown();
		},
		function() {
			jQuery(this).siblings('.inside').slideUp();
		}
	);
});
</script>
	<div class="wrap meta-box-sortables">
    	<div class="icon32" id="icon-themes"><br></div>
        <h2>Theme Options</h2>
        
        <div id="poststuff">
        
<p>update the different options of the themes here.</p>
<form class="form-wrap" method="post" action="?page=theme-options">  
<input type="hidden" name="action" value="save-settings" />    

<div class="inside-left">
<?php 
$toS = ceil(count($theme_options)/2);
if ($toS<1)$toS=1;

for($j=0;$j<$toS;$j++) : 
	$s = $theme_options[$j];
	$fields = $s["fields"]; ?>

         <div class="postbox halfpostbox" id="">
            <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span><?php echo $s["title"];?></span></h3>
            <div class="inside">
                    <?php
                        for ($i=0;$i<count($fields);$i++) {
                            $f = $fields[$i];
                        ?>
                            <div class="form-field form-required">
                                <?php design_do_field($f, $existing_values[$f["slug"]], "theme_options"); ?>
                            </div>
                        <?php
                        }
                    ?>
            </div>                
        </div>
<?php endfor; ?>
</div>
<div class="inside-right">
<?php
for($j=$toS;$j<count($theme_options);$j++) : 
	$s = $theme_options[$j];
	$fields = $s["fields"]; ?>

         <div class="postbox halfpostbox" id="quick-settings">
            <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span><?php echo $s["title"];?></span></h3>
            <div class="inside">
                    <?php
                        for ($i=0;$i<count($fields);$i++) {
                            $f = $fields[$i];
                        ?>
                            <div class="form-field form-required">
                                <?php design_do_field($f, $existing_values[$f["slug"]], "theme_options"); ?>
                            </div>
                        <?php
                        }
                    ?>
            </div>                
        </div>
<?php endfor; ?>
</div>
<div class="clear"></div>
<input type="submit" value="Save Changes" accesskey="p" tabindex="5" id="publish" class="button-primary" name="publish">
<input type="submit" name="action" value="Reset to Default" class="button-secondary">
</form>
		</div><!--poststuff-->
        
    </div><!--wrap-->

<?php
}





function theme_options_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('design-upload', get_bloginfo('template_url').'/includes/design-options.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('design-upload');
	

}

function theme_options_admin_styles() {
	wp_enqueue_style('thickbox');
	
}

if (isset($_GET['page']) && $_GET['page'] == 'theme-options') {
	add_action('admin_print_scripts', 'theme_options_admin_scripts');
	add_action('admin_print_styles', 'theme_options_admin_styles');
}



//END ADMINISTRATION SCREEN


function theme_options_show_breadcrumbs()
{
	if (!function_exists('show_breadcrumbs')) return;
	if (get_theme_option('breadcrumbs-enable')=="") return;
	
	if ((is_page() || get_post_type() == 'casino') && get_theme_option("bread-crumbs-hide-pages")!="") return;
	if (is_single() && get_post_type() != 'casino'  && get_theme_option("bread-crumbs-hide-posts")!="") return;
	if (is_front_page() && get_theme_option("bread-crumbs-hide-home")!="") return;
	if (is_404() && get_theme_option("bread-crumbs-hide-404")!="") return;
	if (is_archive() && get_theme_option("bread-crumbs-hide-archive")!="") return;
	
	show_breadcrumbs();
}

?>