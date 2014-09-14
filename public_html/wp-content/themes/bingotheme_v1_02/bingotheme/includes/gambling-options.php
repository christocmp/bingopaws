<?php
// SETTINGS CONFIGURATION
$gambling_options = array();



$gambling_options[] = make_advanced_group("Bingo Bonuses Widget Word Replacements", array(

make_setting("Change 'Site' to:", "widget2-site", "text", "Enter the text to replace 'Site' in the heading of the bingo bonuses widget"),

make_setting("Change 'Bonus' to:", "widget2-bonus", "text", "Enter the text to replace 'Bonus' in the heading of the bingo bonuses widget"),

make_setting("Change 'Details' to:", "widget2-det", "text", "Enter the text to replace 'Details' in the heading of the bingo bonuses widget"),

make_setting("Change 'Details' to:", "widget2-detb", "text", "Enter the text to replace 'Details' button in the bingo bonuses widget"),
	
       
));


$gambling_options[] = make_advanced_group("Top Bingo Sites Widget Word Replacements", array(

make_setting("Change 'Bingo Site' to:", "widget1-bingosite", "text", "Enter the text to replace 'Bingo Site' in the heading of the top bingo sites widget"),

make_setting("Change 'Rating' to:", "widget1-rate", "text", "Enter the text to replace 'Rating' in the heading of the top bingo sites widget"),

make_setting("Change 'More Info' to:", "widget1-info", "text", "Enter the text to replace 'More Info' in the heading of the top bingo sites widget"),

make_setting("Change 'Visit Now' to:", "widget1-visit", "text", "Enter the text to replace 'Visit' button text in the top bingo sites widget"),

make_setting("Change 'Review' to:", "widget1-rreview", "text", "Enter the text to replace 'Review' link text in the top bingo sites widget"),
	
       
));



$gambling_options[] = make_advanced_group("Casino Bonuses Shortcode Word Replacement", array(

make_setting("Change 'Visit Site' to:", "replace-head-visit", "text", "Enter the word to replace 'visit Site' in the heading and row of the casino bonuses shortcode table"),

make_setting("Change 'Bingo Site' to:", "replace-head-site", "text", "Enter the word to replace 'casino' in the heading of the casino Bonuses shortcode table"),
	
make_setting("Change 'No Deposit Bonus' to:", "replace-head-bonus-nd", "text", "Enter the word to replace 'No Deposit Bonus' in the heading of the casino Bonuses shortcode table"),

make_setting("Change 'Signup Bonus' to:", "replace-head-bonus-su", "text", "Enter the word to replace 'Signup Bonus' in the heading of the casino Bonuses shortcode table"),

make_setting("Change 'Reload Bonus' to:", "replace-head-bonus-rl", "text", "Enter the word to replace 'Reload Bonus' in the heading of the casino Bonuses shortcode table"),

make_setting("Change 'Bonus Type' to:", "replace-head-btype", "text", "Enter the word to replace 'Bonus Type' in the heading of the casino Bonuses shortcode table"),

make_setting("Change 'Bonus Amount' to:", "replace-head-bamt", "text", "Enter the word to replace 'Bonus Amount' in the heading of the casino Bonuses shortcode table"),

make_setting("Change 'Play Now' to:", "replace-vis-button", "text", "Enter the word to replace 'Play Now' button in the casino Bonuses shortcode table"),
       
make_setting("Change 'Review' to:", "replace-head-rev", "text", "Enter the word to replace 'review' in the heading and row of the casino Bonuses shortcode table"),

make_setting("Change 'Review' to:", "replace-mid-rev", "text", "Enter the text to replace 'review' button in the casino Bonuses shortcode table"),


make_setting("Change 'Signup Bonus' to:", "replace-mid-btype2", "text", "Enter the text to replace 'Signup Bonus' in the casino Bonuses shortcode table"),

make_setting("Change 'No Deposit Bonus' to:", "replace-mid-btype3", "text", "Enter the text to replace 'No Deposit Bonus' in the casino Bonuses shortcode table"),

make_setting("Change 'Reload Bonus' to:", "replace-mid-btype4", "text", "Enter the text to replace 'Reload Bonus' in the casino Bonuses shortcode table")

));

$gambling_options[] = make_advanced_group("Top Bingo Sites Shortcode Word Replacement", array(
	
make_setting("Change 'Read Review' to:", "topsites-rev-button", "text", "Enter the text to replace 'Read Review' button text in the Bingo Sites Shortcode"),
	
make_setting("Change 'Play Now' to:", "topsites-pn-button", "text", "Enter the text to replace 'Play Now' button in the Bingo Sites Shortcode")
       
));



$gambling_options[] = make_advanced_group("Author Bio and Author Page", array(
	make_setting("Change 'More About' to:", "author-morea", "text", "Enter the word to replace 'More About' in the author bio on the bottom of the posts page"),
	
make_setting("Change 'View Posts' to:", "author-posts", "text", "Enter the word to replace 'View Posts' in the author bio on the bottom of the posts page"),

make_setting("Change 'Visit Website' to:", "author-vistweb", "text", "Enter the word to replace 'View Website' in the author bio on the bottom of the posts page"),

make_setting("Change 'About' to:", "authorpage-about", "text", "Enter the word to replace 'About' on the author page."),

make_setting("Change 'Full Name' to:", "authorpage-name", "text", "Enter the word to replace 'Full Name' on the author page."),

make_setting("Change 'Website' to:", "authorpage-web", "text", "Enter the word to replace 'Website' on the author page."),

make_setting("Change 'More Info' to:", "authorpage-info", "text", "Enter the word to replace 'More Info' on the author page."),

make_setting("Change 'Posts by' to:", "authorpage-postsby", "text", "Enter the word to replace 'Posts by' on the author page.")
       
));




$gambling_options[] = make_advanced_group("Review Page Word Replacement", array(

make_setting("Change 'Rating' to:", "review-rating", "text", "Enter the words to replace 'Rating' on the review page"),

make_setting("Change 'No Deposit Bonus' to:", "review-ndb", "text", "Enter the words to replace 'No Deposit Bonus' on the review page"),

make_setting("Change 'Play Now' to:", "review-pnow", "text", "Enter the words to replace 'Play Now' button on the review page"),

make_setting("Change 'Features' to:", "review-feat", "text", "Enter the words to replace 'Features' on the review page"),

make_setting("Change 'Bingo Software' to:", "review-software", "text", "Enter the words to replace 'Bingo Software' on the review page"),

make_setting("Change 'Deposit Options' to:", "review-deposit", "text", "Enter the words to replace 'Deposit Options' on the review page"),

make_setting("Change 'Withdrawal Options' to:", "review-with", "text", "Enter the words to replace 'Withdrawal Options' on the review page"),

make_setting("Change 'Platforms' to:", "review-plat", "text", "Enter the words to replace 'Platforms' on the review page"),

make_setting("Change 'Minimum Deposit' to:", "review-mindep", "text", "Enter the words to replace 'Minimum Deposit' on the review page"),

make_setting("Change 'Reload Bonus' to:", "review-reload", "text", "Enter the words to replace 'Reload Bonus' on the review page"),

make_setting("Change 'Signup Bonus Code' to:", "review-subonus", "text", "Enter the words to replace 'Signup Bonus Code' on the review page"),

make_setting("Change 'Support Options' to:", "review-support", "text", "Enter the words to replace 'Support Options' on the review page"),

make_setting("Change 'Play Now at' to:", "review-pnowat", "text", "Enter the words to replace 'Play Now' button on the review page"),


));


// END SETTINGS CONFIGURATION

//ADMINISTRATION SCREEN
add_action('admin_menu', 'gambling_options_add_menu', 100);
function gambling_options_add_menu()
{
	add_submenu_page('design-options', 'Word Replacement', 'Word Replacement', 'update_themes', 'gambling-options', 'gambling_options_show_ui');
}

function get_gambling_options()
{
	$opc = get_option('gambling-options-settings');
	if ($opc != "") return unserialize($opc);
	
	return array();
}

function get_gambling_option($key)
{
	$options = get_gambling_options();
	if (array_key_exists($key, $options)) {
		return $options[$key];
	}
	
	return false;
}


function gambling_options_show_ui()
{
	
	if ($_REQUEST["action"] == "Reset to Default") {
		//delete the option
		delete_option('gambling-options-settings');
	}
	
	if ($_REQUEST["action"] == "save-settings") {
		$tos = $_REQUEST["gambling_options"];
		delete_option('gambling-options-settings');
		add_option('gambling-options-settings', serialize($tos));
	}	
	
	$existing_values = @unserialize(get_option('gambling-options-settings'));	
	
	if (!is_array($existing_values)) $existing_values = array();
	
	global $gambling_options;
	$gambling_options = apply_filters('gambling_options', $gambling_options);
?>
<style>
	.inside-left, .inside-right {
		width: 80%;
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
        <h2>Word Replacement</h2>
        
        <div id="poststuff">
        
<p>Use this page to change the words throughout your theme.  You can replace words for translation for some of the widgets and shortcodes here.  Note that this will not change any images like buttons in which the text is part of the button image.</p>
<form class="form-wrap" method="post" action="?page=gambling-options">  
<input type="hidden" name="action" value="save-settings" />    

<div class="inside-left">
<?php 
$toS = count($gambling_options);

for($j=0;$j<$toS;$j++) : 
	$s = $gambling_options[$j];
	$fields = $s["fields"]; ?>

         <div class="postbox halfpostbox" id="">
            <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span><?php echo $s["title"];?></span></h3>
            <div class="inside">
                    <?php
                        for ($i=0;$i<count($fields);$i++) {
                            $f = $fields[$i];
                        ?>
                            <div class="form-field form-required">
                                <?php design_do_field($f, $existing_values[$f["slug"]], "gambling_options"); ?>
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




//END ADMINISTRATION SCREEN



?>