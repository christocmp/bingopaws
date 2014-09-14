<?php
// SETTINGS CONFIGURATION

$fonts=array("Arial, Helvetica, sans-serif"=>"Arial", "'Arial Black',Gadget, sans-serif"=>"Arial Black", "'Arial Narrow', sans-serif"=>"Arial Narrow", "Century Gothic,
sans-serif"=>"Century Gothic", "Copperplate Gothic Light, sans-serif"=>"Copperplate Gothic Light", "'Courier New', Courier, Monaco, monospace"=>"Courier New", "Georgia, Serif"=>"Georgia", "Gill Sans MT, sans-serif"=>"Gill Sans", "Impact, Charcoal, sans-serif"=>"Impact", 
"'Lucida Console', Monaco, monospace"=>"Lucida Console", "'Lucida Sans Unicode', 'Lucida Grande', sans-serif"=>"Lucida Sans Unicode", "'Palatino Linotype', 'Book Antiqua', Palatino, serif"=>"Palatino Linotype", "Tahoma, Geneva, sans-serif"=>"Tahoma", "'Times New Roman', Times, serif"=>"Times New Roman", "'Trebuchet MS', Helvetica, sans-serif"=>"Trebuchet MS", "Verdana, Geneva, sans-serif"=>"Verdana");

$fontsizes=array("8px"=>"8px", "10px"=>"10px", "11px"=>"11px", "12px"=>"12px", "14px"=>"14px", "16px"=>"16px", "18px"=>"18px", "20px"=>"20px","24px"=>"24px","28px"=>"28px","30px"=>"30px","36px"=>"36px", "40px"=>"40px");



$settings = array();

$settings[] = make_advanced_group("Basic Color Settings", array(

	make_setting("General Link Color", "gen-linkcolor","color", "", ""),

	make_setting("General Link Hover Color", "gen-linkhovercolor","color", "", ""),

	make_setting("Bonus Text Color", "bonus-color", "color", "Change the color of the bonus text, which is a red/orange color.", ".custom span.hilite { color: "),

	make_setting("Blue Heading Background Color", "blue-color1", "color", "Change the color of the blue headings and related color backgrounds.",""),

	make_setting("Featured Top Row Background", "featbg-color", "color", "Change the light blue background color of the first row of shortcodes and widgets.", ".bingosites.top, .depositwidget tr.top, .siteswidget  tr.top,.topbonuses tr.alt { background: "),

	
	make_setting("Featured Casino Border", "featborder-color", "color", "Change the light blue border color of the first row of shortcodes and widgets.", ".bingosites.top, .depositwidget tr.top, .siteswidget  tr.top{ border-color: "),

));

$settings[] = make_advanced_group("Button Settings", array(
	
	make_setting("Pink Button Background Color", "pink-button-bgcolor","color", "", ""),

	make_setting("Pink Button Text Color", "pink-button-txcolor", "color", "", ""),

	make_setting("Blue Background Color", "blue-button-bgcolor","color", "", ""),

	make_setting("Blue Button Text Color", "blue-button-txcolor", "color", "", ""),
	
));


$settings[] = make_advanced_group("Header Settings", array(
	
	make_setting("Header Background Color", "header-bgcolor","color", "", ""),

	make_setting("Navigation Background Color", "nav-bgcolor","color", "", ""),
		
));

$settings[] = make_advanced_group("Footer Settings", array(
	
	make_setting("Footer Background Color", "footer-bgcolor","color", "Footer Background Color", "footer.bottom-footer { background: "),


	make_setting("Top Footer Link Color", "footertop-color", "color", "Change the footer widgets link color.", ".main-footer a, .main-footer a:visited { color: "),

	make_setting("Top Footer Link Hover Color", "footertop-colorhover", "color", "Change the footer widgets link hover color.", ".main-footer a:hover { color: "),
		
));



$settings[] = make_advanced_group("Background Settings", array(
	
	make_setting("Background Image", "body-background-image", "image", "The background image of the body", ".custom { background-image: url('", "'); }"),

	make_setting("Background Image Repeat", "body-image-repeat", array("no-repeat"=>"None", "repeat"=>"Repeat","repeat-x"=>"Repeat X", "repeat-y"=>"Repeat Y"), "Should the background image repeat", ".custom { background-repeat: "),
	
));


$settings[] = make_advanced_group("Typography Settings", array(
	make_setting("General Font Family", "body-font-family",$fonts, "The overall font style", ".custom { font-family: "),

	make_setting("Content Area Font Size", "body-font-size", $fontsizes, "The size of the body text", ".custom .main-content { font-size:"),

	make_setting("sidebar Area Font Size", "sidebar-font-size", $fontsizes, "The size of the sidebar text", ".custom .sidebar { font-size:"),

	make_setting("Footer Area Font Size", "footer-font-size", $fontsizes, "The size of the footer text", ".custom footer.main-footer,  .custom footer.bottom-footer{ font-size:"),

	make_setting("Heading H1 Font Family", "bodyh1-font-family", $fonts, "The font type for the H1 headings", ".custom h1 { font-family:"),

	make_setting("Heading H1 Font Size", "bodyh1-font-size", $fontsizes, "The size of the H1 heading text", ".custom h1 { font-size:"),

	make_setting("Heading H2 Font Family", "bodyh2-font-family", $fonts, "The font type for the H1 headings", ".custom h2 { font-family:"),

	make_setting("Heading H2 Font Size", "bodyh2-font-size", $fontsizes, "The size of the H1 heading text", ".custom h2 { font-size:"),

	make_setting("Heading H3 Font Family", "bodyh3-font-family", $fonts, "The font type for the H3 headings", ".custom h3 { font-family:"),

	make_setting("Heading H3 Font Size", "bodyh3-font-size", $fontsizes, "The size of the H3 heading text", ".custom h3 { font-size:"),

	make_setting("Sidebar Widget Heading Font Family", "sideh3-font-family", $fonts, "The font type for the sidebar widget headings", ".custom .sidebar h3 { font-family:"),

	make_setting("Sidebar Widget Heading Font Size", "sideh3-font-size", $fontsizes, "The size of the sidebar widget heading text", ".custom .sidebar h3 { font-size:"),

	make_setting("Blog Title Font Family", "blog-title-fonttype", $fonts, "The font type for the blog title", ".custom .header-logo h1 { font-family: "),	

	make_setting("Blog Title Font Size", "blog-title-fontsize", $fontsizes, "The font size for the blog title", ".custom .header-logo h1 { font-size: "),

));



$settings[] = make_advanced_group("Custom", array(
	make_setting("Custom CSS", "custom", "textarea", "Custom CSS to be inserted into the site.  Proceed styles by .custom", "", "")
));


add_filter('flytonic_save_custom_css', 'ft_image_bordering', 0 , 2);
function ft_image_bordering($val, $field)
{
	if ($field['slug']=='header-bgcolor') {
	$middlecolor = $val;
	$darkcolor = ft_colorBrightness($val, -0.81);
	$topcolor = ft_colorBrightness($val, 0.98);
	$light = ft_colorBrightness($val, 0.90);
	
	return 'header.main-header { background:' . $val . '; border-bottom:1px solid ' . $darkcolor . ';}';
			
	}

else if ($field['slug']=='nav-bgcolor') {
	$middlecolor = $val;
	$darkcolor = ft_colorBrightness($val, -0.81);
	$topcolor = ft_colorBrightness($val, 0.98);
	$light = ft_colorBrightness($val, 0.90);
	$darkcolor2 = ft_colorBrightness($val, -0.6);
	
	return 'nav.navbar { background:' . $val . ';} header.main-header {border-top:4px solid ' . $val . ';} .nav li a:hover, .nav li a:active,.nav li:active,.nav li:hover {background:' . $darkcolor . ';}.nav li ul { background:' . $darkcolor . ' ;border-bottom:3px solid ' . $darkcolor2 . ';	border-left:3px solid ' . $darkcolor2 . ';border-right:3px solid ' . $darkcolor2 . ';}	.nav li.current-menu-item a, .nav li.current-menu-parent a  {
background:' . $darkcolor . '; } .nav li li.current-menu-item li a, .nav li li.current-menu-parent li a  {background:' . $darkcolor . ';}
.nav li li { border-bottom:1px solid '.$val.';}.topreview .topright span {color:' . $darkcolor . ';}.reviewarea .right .bonusinfo {background: '.$val.';}.bingosites.top .rank span {	background:'.$val.';}.siteswidget  tr.top .rank {color:'.$val.';}';
			
	}



	else if ($field['slug']=='pink-button-bgcolor') {
	$topcolor = ft_colorBrightness($val, 0.9);
	$border = ft_colorBrightness($val, -.9);

		return 'a.visbutton { background: ' . $val . '; background-image: -moz-linear-gradient(top, ' . $topcolor . ' 0%, ' . $val . ' 100%);background-image: -webkit-linear-gradient(top, ' . $topcolor . ' 0%, ' . $val . ' 100%);border-color:' . $border . ';}  ';
		


	} elseif($field['slug']=='blue-button-bgcolor') {
		$topcolor = ft_colorBrightness($val, 0.9);
	$border = ft_colorBrightness($val, -.9);

		return 'a.visbutton.blue { background: ' . $val . '; background-image: -moz-linear-gradient(top, ' . $topcolor . ' 0%, ' . $val . ' 100%);background-image: -webkit-linear-gradient(top, ' . $topcolor . ' 0%, ' . $val . ' 100%);border-color:' . $border . ';}.custom .searchsubmit { background: ' . $val . '; background-image: -moz-linear-gradient(top, ' . $topcolor . ' 0%, ' . $val . ' 100%);background-image: -webkit-linear-gradient(top, ' . $topcolor . ' 0%, ' . $val . ' 100%);border-color:' . $border . ';} .newsletterform .submitbutton { background: ' . $val . '; background-image: -moz-linear-gradient(top, ' . $topcolor . ' 0%, ' . $val . ' 100%);background-image: -webkit-linear-gradient(top, ' . $topcolor . ' 0%, ' . $val . ' 100%);border-color:' . $border . ';}';
		


} elseif($field['slug']=='blue-color1') {

	return 'h2 { background: ' . $val . '!important;} h2.underline {background:0!important;}.sidebar h3 {background:' . $val . '!important;} .reviewarea .left .freebonus {background:' . $val . '!important;}';



	} elseif($field['slug']=='blue-button-txcolor') {

	return 'a.visbutton.blue { color: ' . $val . '!important;} .newsletterform .submitbutton { color: ' . $val . '!important;}  .custom .searchsubmit{ color: ' . $val . '!important;}';


	} elseif($field['slug']=='pink-button-txcolor') {

	return 'a.visbutton { color: ' . $val . '!important;} ';

	} elseif($field['slug']=='gen-linkcolor') {

	return '.custom .main-content a, .custom .main-content a:visited { color: ' . $val . '} .custom .pagination span, .custom .pagination a { color:' . $val . '; background: #fff; } .custom .pagination a:hover {color:#fff;background: #' . $val . ';}.custom .pagination .current {background: ' . $val . '; color: #fff;}.custom .pagination a.last { background:#999; color:#FFF;}.custom .pagination a.last:hover { background:#333; color:#FFF;} .custom div.reply a.comment-reply-link, .custom div.reply a.comment-reply-link:visited { background:' . $val . '; }.custom div.reply a.comment-reply-link:hover {background:#999; }.custom #commentform #submit { background:' . $val . ' ; }.custom #commentform #submit:hover { background:#999; }';

	} elseif($field['slug']=='gen-linkhovercolor') {

	return '.custom .main-content a:hover { color: ' . $val . '}';

	}


	//return $val;
}




// END SETTINGS CONFIGURATION

//ADMINISTRATION SCREEN
add_action('admin_menu', 'theme_styles_add_menu');
function theme_styles_add_menu()
{
	add_menu_page('Flytonic Framework', 'Flytonic', 'update_themes', 'design-options', 'theme_styles_show_ui');
	add_submenu_page('design-options', 'Design Options', 'Design Options', 'update_themes', 'design-options', 'theme_styles_show_ui');
	
}

function theme_styles_show_ui()
{
	
	if ($_REQUEST["action"] == "Reset to Default") {
		delete_option('design-options-qsettings');
		delete_option('design-options-settings');
		design_generate_css();
	}
	
	if ($_REQUEST["action"] == "quick-settings") {
		$qs = $_REQUEST["qs"];
		delete_option('design-options-qsettings');
		add_option('design-options-qsettings', serialize($qs));
		
		$existing_values = @unserialize(get_option('design-options-settings'));	
		
		foreach ($qs as $key=>$quick) {
			if (trim($quick)!="") {
				$quickInfo = getQuickSetting($key);
				foreach ($quickInfo["affects"] as $f) {
					$existing_values[$f] = $quick;
				}
			}
		}
		
		delete_option('design-options-settings');
		add_option('design-options-settings', serialize($existing_values));
		design_generate_css();
	}
	
	if ($_REQUEST["action"] == "save-settings") {
		$css = $_REQUEST["css"];
		delete_option('design-options-settings');
		add_option('design-options-settings', serialize($css));
		design_generate_css();
	}	
	
	$existing_values = @unserialize(get_option('design-options-settings'));	
	$existing_quick = @unserialize(get_option('design-options-qsettings'));
	
	if (!is_array($existing_quick)) $existing_quick = array();
	if (!is_array($existing_values)) $existing_values = array();
	
	global $quick_settings;
	global $settings;
	
	$quick_settings = apply_filters('design_quicksettings', $quick_settings);
	$settings = apply_filters('design_settings', $settings,$fonts,$fontsizes);
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
    
        
        <div id="poststuff">
        
           
<h2>Edit Theme Style</h2>
<p>Choose to alter the design and look of your theme.  All css will be saved to your custom.css file in the includes folder.</p>
<form class="form-wrap" method="post" action="?page=design-options">  
<input type="hidden" name="action" value="save-settings" />    

<div class="inside-left">
<?php 
$toS = ceil(count($settings)/2);
if ($toS<1)$toS=1;

for($j=0;$j<$toS;$j++) : 
	$s = $settings[$j];
	$fields = $s["fields"]; ?>

         <div class="postbox halfpostbox" id="">
            <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span><?php echo $s["title"];?></span></h3>
            <div class="inside">
                    <?php
                        for ($i=0;$i<count($fields);$i++) {
                            $f = $fields[$i];
                        ?>
                            <div class="form-field form-required">
                                <?php design_do_field($f, $existing_values[$f["slug"]]); ?>
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
for($j=$toS;$j<count($settings);$j++) : 
	$s = $settings[$j];
	$fields = $s["fields"]; ?>

         <div class="postbox halfpostbox" id="quick-settings">
            <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span><?php echo $s["title"];?></span></h3>
            <div class="inside">
                    <?php
                        for ($i=0;$i<count($fields);$i++) {
                            $f = $fields[$i];
                        ?>
                            <div class="form-field form-required">
                                <?php design_do_field($f, $existing_values[$f["slug"]]); ?>
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


function design_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('design-upload', get_bloginfo('template_url').'/includes/design-options.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('design-upload');
	

}

function design_admin_styles() {
	wp_enqueue_style('thickbox');
	
}

if (isset($_GET['page']) && $_GET['page'] == 'design-options') {
	add_action('admin_print_scripts', 'design_admin_scripts');
	add_action('admin_print_styles', 'design_admin_styles');
}



//END ADMINISTRATION SCREEN

//HELPER FUNCTIONS
function getQuickSetting($key) {
	global $quick_settings;
$quick_settings = apply_filters('design_quicksettings', $quick_settings);
	foreach($quick_settings as $qs) {
		if ($qs["slug"]==$key) return $qs;
	}
	return false;
}
//END HELPER FUNCTIONS

//CSS GENERATION
function design_generate_css()
{
	global $settings;



$quick_settings = apply_filters('design_quicksettings', $quick_settings);
	$settings = apply_filters('design_settings', $settings);
	$existing_values = @unserialize(get_option('design-options-settings'));		
	if (!is_array($existing_values)) $existing_values = array();
	
	$template = "";
	
	foreach ($settings as $group) {
		$template .= "/* ".$group["title"]." */\n";
		foreach ($group["fields"] as $field) {
			if ($field['type']=='option_select') {
				$value = $existing_values[$field["slug"]];
				$c='';
				
				if ($value!='') {	
					$c= apply_filters('flytonic_save_custom_css', $field['options'][$value], $field);
					//$c= $field['options'][$value];
				}

				if ($c=='') {
						
					$c = $field['options'][$value];	
					}
				
				$template.=$c;
			} else {
				$value = $existing_values[$field["slug"]];
				
				$c='';
				if(trim($value)!="") {
					$c = apply_filters('flytonic_save_custom_css',stripslashes($value), $field);	
					if ($c=='') {
						//$c = apply_filters('flytonic_save_custom_css',$field["pre"].stripslashes($value).$field["post"]."\n", $field);	

$c = $field["pre"].stripslashes($value).$field["post"]."\n";	
					}
					//$c = $field["pre"].stripslashes($value).$field["post"]."\n";
				}
				$template.=$c;
			}
		}
		$template .= "\n";
	}
	
	if (function_exists('lo_append_to_custom_css')) {
		$template = lo_append_to_custom_css($template);
	}
	

	file_put_contents(dirname(__FILE__)."/custom.css", $template);

	return $template;
}
//END CSS GENERATION

function ft_colorBrightness($hex, $percent) {
	// Work out if hash given
	$hash = '';
	if (stristr($hex,'#')) {
		$hex = str_replace('#','',$hex);
		$hash = '#';
	}
	/// HEX TO RGB
	$rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
	//// CALCULATE 
	for ($i=0; $i<3; $i++) {
		// See if brighter or darker
		if ($percent > 0) {
			// Lighter
			$rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1-$percent));
		} else {
			// Darker
			$positivePercent = $percent - ($percent*2);
			$rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1-$positivePercent));
		}
		// In case rounding up causes us to go to 256
		if ($rgb[$i] > 255) {
			$rgb[$i] = 255;
		}
	}
	//// RBG to Hex
	$hex = '';
	for($i=0; $i < 3; $i++) {
		// Convert the decimal digit to hex
		$hexDigit = dechex($rgb[$i]);
		// Add a leading zero if necessary
		if(strlen($hexDigit) == 1) {
		$hexDigit = "0" . $hexDigit;
		}
		// Append to the hex string
		$hex .= $hexDigit;
	}
	return $hash.$hex;
}


?>