<?php

class BannerGroup {
	var $title="";
	var $banners=array();
	var $slug="";
	
	function addBanner($banner)
	{
		$this->banners[] = $banner;
	}
	
	function addHTMLBanner($title, $html,$active)
	{
		$banner = new Banner();
		$banner->makeHTMLBanner($title, $html,$active);
		$this->addBanner( $banner );
	}
	
	function addImageBanner($title, $image, $href,$active)
	{
		$banner = new Banner();
		$banner->makeImageBanner($title, $image, $href,$active);
		$this->addBanner( $banner );
	}
	
}

class Banner {
	var $title = "";
	var $html = "";
	var $image = "";
	var $href = "";
	var $alt = "";
	var $active = true;
	var $hits = 0;
	var $timestamp = 0;
	
	function makeHTMLBanner($title, $html,$active)
	{
		$this->title = $title;
		$this->html = $html;
		$this->active = $active;
	}
	
	function makeImageBanner($title, $image, $link,$active)
	{
		$this->title = $title;
		$this->image = $image;
		$this->href = $link;
		$this->active = $active;
	}
	
	function getHTML()
	{
		if ($this->html != "") {
			return stripslashes($this->html);
		} else {
			return "<a ". (get_theme_option('redirect-banner-window')!="" ? "target=\"_blank\"" : "") ." title=\"".$this->alt."\"  href=\"".$this->getLinkURL()."\"><img class=\"bannerAd\" alt=\"".$this->alt."\" src=\"".$this->image."\" /></a>";
		}
	}
	
	function getType()
	{
		if ($this->html=="") {
			return "Image";
		} else {
			return "HTML";
		}
	}
	
	function getHits()
	{
		return $this->hits;
	}
	
	function getKey()
	{		
		return substr(sanitize_title($this->title), 0, 50).($this->timestamp>0?substr($this->timestamp,-4, 4):'');
	}
	
	function getLinkURL()
	{
		return get_bloginfo('url') . '/banner/' . $this->getKey();
	}
}

add_action('init', 'flytonic_redirect_banners'); 

function flytonic_redirect_banners () {
    global $wpdb;

	$request = $_SERVER['REQUEST_URI'];
	if (!isset($_SERVER['REQUEST_URI'])) {
		$request = substr($_SERVER['PHP_SELF'], 1);
		if (isset($_SERVER['QUERY_STRING']) AND $_SERVER['QUERY_STRING'] != '') { $request.='?'.$_SERVER['QUERY_STRING']; }
	}
	if (isset($_GET['banerkey'])) {
		$request = '/banner/'.$_GET['banerkey'].'/';
	}


	if ( strpos('/'.$request, '/banner/') ) {
		$bannerkey = explode('banner/', $request);
		$bannerkey = $bannerkey[1];
		$bannerkey = str_replace('/', '', $bannerkey);
		

        
		$banner_groups = banner_manager_load_banners();	
		$url=false;
		foreach ($banner_groups as $group)
		{
			foreach (array_keys($group->banners) as $banner_key) {
				$banner = $group->banners[$banner_key];
				
				//echo $banner->getKey() . ' = ' . $bannerkey . '<br>';
				if ($banner->getKey()==$bannerkey) {
					//echo 'X!#$@#$#@$';
					$url = $banner->href;
					$banner->hits = $banner->hits+1;
					
					$group->banners[$banner_key]=$banner;
					banner_manager_save_banners($banner_groups);
				}
			}
		}
       
	   if ($url != '') {
	   header("X-Robots-Tag: noindex, nofollow", true); 
	   header('Location: ' . $url . "\n\n");
	   exit;
	   }
	   
	   

	} 
	
}

//ADMINISTRATION SCREEN
add_action('admin_menu', 'banner_manager_add_menu', 100);
function banner_manager_add_menu()
{
	add_submenu_page('design-options', 'Banner Manager', 'Banner Manager', 'update_themes', 'banner-manager', 'banner_manager_show_ui');
}

function banner_manager_save_banners($banner_groups)
{
	$header = $banner_groups["template-header"];
	unset($banner_groups["template-header"]);
	
	delete_option('banner-manager-header');
	delete_option('banner-manager-groups');
	
	add_option('banner-manager-header', serialize($header));
	add_option('banner-manager-groups', serialize($banner_groups));
}

function banner_manager_load_banners()
{
	$header = get_option('banner-manager-header');
	$groups = get_option('banner-manager-groups');
	
	if ($groups != "") {
		$groups = unserialize($groups);
	} else {
		$groups = array();	
	}
	
	if ($header != "") {
		$header = unserialize($header);
	} else {
		$header= new BannerGroup();
		$header->title = "Template Header";
		$header->slug = "template-header";
	}
		
	$groups2["template-header"] = $header;
	
	$groups2 = array_merge($groups2, $groups);
	
	return $groups2;
}

function banner_manager_show_new_banner($group, $banner_groups)
{	
	$group = $banner_groups[$group];
	
	if ($_REQUEST["action"]=="Create Banner")
	{
		$title = $_REQUEST["banner-title"];
		$type = $_REQUEST["banner-type"];
		
		$banner = new Banner();
		$banner->title = $title;
		if ($type=="simple")
		{
			$image = $_REQUEST["banner-image"];
			$link = $_REQUEST["banner-link"];
			
			$banner->makeImageBanner($title, $image, $link, true);
			$banner->alt = $_REQUEST["banner-alt"];
		} else {
			$html = $_REQUEST["banner-html"]." ";
			
			$banner->makeHTMLBanner($title, $html, true);
		}
			
		$group->addBanner($banner);
		
		$banner_groups[$group->slug] = $group;
		banner_manager_save_banners($banner_groups);
		
		unset($_GET["section"]);unset($_REQUEST["section"]);
		return banner_manager_show_ui();
	} elseif ($_REQUEST["action"] == "Cancel") {
		unset($_GET["section"]);unset($_REQUEST["section"]);
		return banner_manager_show_ui();
	}
	
	?>
    <style>
		input.check { width: auto !important; }
		.upload_image_button, .clear_field_button { width: auto !important; }
	</style>
	<div class="wrap meta-box-sortables">
    	<div class="icon32" id="icon-themes"><br></div>
        <h2>Create Banner in <?php echo $group->title; ?></h2>
        <form method="post" action="?page=banner-manager&section=new-banner&group=<?php echo $group->slug ?>" style="width: 400px;">
            <div class="form-field">
	            <label>Title</label>
    	        <input type="text" name="banner-title" />
        	    <p>A name for the banner so you can quickly identify banners later</p>
            </div>
            <div class="form-field">
            	<input class="check" name="banner-type" type="radio" value="simple" CHECKED /> Simple Banner<br />
			</div>
                <div id="simple-banner" style="padding-left: 50px; padding-bottom: 5px; margin-bottom: 5px; border-bottom: 1px solid #CCC">
       			    <div class="form-field">
            			<label>Image</label>
                        <input type="text" id="banner-image" name="banner-image" />
						<input class="upload_image_button button-primary" id="upload_image_button" type="button" value="Choose Image" />
					    <input class="clear_field_button button-secondary" type="button" value="Clear Field" />
                        <p>Upload an image to use as your banner</p>
					</div>
                    <div class="form-field">
                    	<label>Link</label>
                        <input type="text" name="banner-link" />
                        <p>Enter the URL the banner should link to</p>
                    </div> 
					<div class="form-field">
                    	<label>Alt Text</label>
                        <input type="text" name="banner-alt" />
                        <p>Alt text to be used on the link and image</p>
                    </div> 
                </div>
           <div class="form-field">
           		<input class="check" name="banner-type" type="radio" value="html" /> HTML Banner
           </div>
				<div id="html-banner" style="padding-left: 50px;">
       			    <div class="form-field">
            			<label>Banner HTML</label>
                        <textarea rows=10 name="banner-html"></textarea>
                        <p>Paste HTML code from a banner exchange or affiliate program</p>
					</div>
                </div>
                
			<input type="submit" name="action" class="button-primary" id="publish" value="Create Banner">
			<input type="submit" name="action" class="button-secondary" id="publish" value="Cancel">
            
        </form>
    <?php
	


}

function banner_manager_show_edit_banner($group, $bannerIndex, $banner_groups)
{
	$group = $banner_groups[$group];
	$banner = $group->banners[$bannerIndex];
	
	if ($_REQUEST["action"]=="Save Banner")
	{
		$title = $_REQUEST["banner-title"];
		$type = $_REQUEST["banner-type"];
		
		$banner = new Banner();
		$banner->title = $title;
		if ($type=="simple")
		{
			$image = $_REQUEST["banner-image"];
			$link = $_REQUEST["banner-link"];
			$alt = $_REQUEST["banner-alt"];
			
			$banner->alt = $alt;
			$banner->image = $image;
			$banner->href = $link;
			$banner->html = "";
		} else {
			$html = $_REQUEST["banner-html"];
			if ($html=="") $html=" ";
			$banner->html=$html;
			$banner->image="";
			$banner->href="";
			$banner->alt="";
		}
			
		$group->banners[$bannerIndex] = $banner;
		$banner_groups[$group->slug] = $group;
		
		banner_manager_save_banners($banner_groups);
		
		unset($_REQUEST["section"]);unset($_GET["section"]);
		return banner_manager_show_ui();
	} elseif ($_REQUEST["action"] == "Cancel") {
		unset($_REQUEST["section"]);unset($_GET["section"]);
		return banner_manager_show_ui();
	}
	


	?>
    <style>
		input.check { width: auto !important; }
		.upload_image_button, .clear_field_button { width: auto !important; }
	</style>
	<div class="wrap meta-box-sortables">
    	<div class="icon32" id="icon-themes"><br></div>
        <h2>Edit Banner</h2>
        <form method="post" action="?page=banner-manager&section=edit-banner&group=<?php echo $group->slug ?>&banner-index=<?php echo $bannerIndex;?>" style="width: 400px;">
            <div class="form-field">
	            <label>Title</label>
    	        <input type="text" name="banner-title" value="<?php echo $banner->title;?>" />
        	    <p>A name for the banner so you can quickly identify banners later</p>
            </div>
            <div class="form-field">
            	<input class="check" name="banner-type" type="radio" value="simple" <?php echo ($banner->html==""?"CHECKED":"");?> /> Simple Banner<br />
			</div>
                <div id="simple-banner" style="padding-left: 50px; padding-bottom: 5px; margin-bottom: 5px; border-bottom: 1px solid #CCC">
       			    <div class="form-field">
            			<label>Image</label>
                        <input type="text" id="banner-image" name="banner-image" value="<?php echo $banner->image;?>"/>
						<input class="upload_image_button button-primary" id="upload_image_button" type="button" value="Choose Image" />
					    <input class="clear_field_button button-secondary" type="button" value="Clear Field" />
                        <p>Upload an image to use as your banner</p>
					</div>
                    <div class="form-field">
                    	<label>Link</label>
                        <input type="text" name="banner-link" value="<?php echo $banner->href;?>" />
                        <p>Enter the URL the banner should link to</p>
                    </div> 
					<div class="form-field">
                    	<label>Alt Text</label>
                        <input type="text" name="banner-alt" value="<?php echo $banner->alt;?>" />
                        <p>Alt text to be used on the link and image</p>
                    </div> 
                </div>
           <div class="form-field">
           		<input class="check" name="banner-type" type="radio" value="html" <?php echo ($banner->html!=""?"CHECKED":"");?> /> HTML Banner
           </div>
				<div id="html-banner" style="padding-left: 50px;">
       			    <div class="form-field">
            			<label>Banner HTML</label>
                        <textarea rows=10 name="banner-html"><?php echo stripslashes( $banner->html );?></textarea>
                        <p>Paste HTML code from a banner exchange or affiliate program</p>
					</div>
                </div>
                
			<input type="submit" name="action" class="button-primary" id="publish" value="Save Banner">
			<input type="submit" name="action" class="button-secondary" id="publish" value="Cancel">
            
        </form>
    <?php
}

function banner_manager_show_ui()
{
	//Load Banner Groups
	$banner_groups = banner_manager_load_banners();	
	
	if ($_GET["section"]=="new-banner") {
		return banner_manager_show_new_banner($_REQUEST["group"], $banner_groups);
	} elseif ($_GET["section"]=="delete-banner") {
		$group = $_REQUEST["group"];
		$bannerIndex = $_GET["banner-index"];
		$group = $banner_groups[$group];
		unset( $group->banners[$bannerIndex] );
		$banner_groups[$group->slug] = $group;
		
		banner_manager_save_banners($banner_groups);
		$banner_groups = banner_manager_load_banners();	
	} elseif ($_GET["section"] == "edit-banner") {
		return banner_manager_show_edit_banner($_REQUEST["group"], $_REQUEST["banner-index"], $banner_groups);
	} elseif ($_REQUEST["action"] == "Create Group") {
		$title = $_REQUEST["group_title"];
		$group = new BannerGroup();
		$group->title = $title;
		$group->slug = sanitize_title_with_dashes($title);
		
		$banner_groups[$group->slug] = $group;
		banner_manager_save_banners($banner_groups);
		$banner_groups = banner_manager_load_banners();
	} elseif ($_REQUEST["action"] == "delete-group") {
		$group = $_REQUEST["group"];		
		unset($banner_groups[$group]);
		banner_manager_save_banners($banner_groups);
		$banner_groups = banner_manager_load_banners();
	} elseif ($_REQUEST["action"] == "activate") {
		$group = $banner_groups[$_REQUEST["group"]];
		$banner = $group->banners[$_REQUEST["banner-index"]];
		
		$banner->active=true;
		$group->banners[$_REQUEST["banner-index"]] = $banner;
		$banner_groups[$group->slug] = $group;
		
		banner_manager_save_banners($banner_groups);
		$banner_groups = banner_manager_load_banners();		
	} elseif ($_REQUEST["action"] == "deactivate") {
		$group = $banner_groups[$_REQUEST["group"]];
		$banner = $group->banners[$_REQUEST["banner-index"]];
		
		$banner->active=false;
		$group->banners[$_REQUEST["banner-index"]] = $banner;
		$banner_groups[$group->slug] = $group;
		
		banner_manager_save_banners($banner_groups);
		$banner_groups = banner_manager_load_banners();		
	} elseif ($_GET["action"] == "reset-hits") {
		$group = $banner_groups[$_REQUEST["group"]];
		$banner = $group->banners[$_REQUEST["banner-index"]];
		
		$banner->hits=0;
		$group->banners[$_REQUEST["banner-index"]] = $banner;
		$banner_groups[$group->slug] = $group;
		
		banner_manager_save_banners($banner_groups);
		$banner_groups = banner_manager_load_banners();		
	}
	
	//fix the blank entry problem
	$changed=false;
	
	foreach ($banner_groups as $group)
	{
		foreach (array_keys($group->banners) as $banner_key) {
			$banner = $group->banners[$banner_key];
			
			if (!property_exists($banner, 'title')) {
				unset($group->banners[$banner_key]);		
				$changed=true;
			}
			//$group = array_values($group);
		}
	}
	
	if ($changed) banner_manager_save_banners($banner_groups);
	

?>
<style>
#poststuff h2 { margin-bottom: 0px; }
</style>
<script>
jQuery(document).ready( function() {

});
</script>
	<div class="wrap meta-box-sortables">
    	<div class="icon32" id="icon-themes"><br></div>
        <h2>Banner Manager</h2>
        
        <div id="poststuff">
        
        <h3>Create a group</h3>
        <p>An ad group contains one or more advertisements to be displayed on your site in a particular location. To create a new group begin by typing a name below and pressing "create"
        <form method="post" action="?page=banner-manager">
        	<label>Group Title:</label>
            <input type="text" name="group_title" /> 
            <input type="submit" value="Create Group" id="publish" class="button-primary" name="action" />
        </form>
        <hr />
        <p>Each group can contain one or more advertisements. Advertisements will be selected randomly from the group, and you may selectively disable individual advertisements to stop them from appearing temporarily.</p>
        <?php foreach ($banner_groups as $group) : ?>
        	<h2><?php echo $group->title;?></h2>
            <p>
            <?php if ($group->slug!="template-header") { ?><a onclick="return confirm('Deleting groups cannot be undone. Are you sure?');" style="color:red" href="?page=banner-manager&action=delete-group&group=<?php echo $group->slug;?>">Delete</a><?php } ?><br />
            Shortcode: [banner-group name="<?php echo $group->slug;?>"]<br />
               Template Tag: &lt;?php if (function_exists('bannerAd')) bannerAd('<?php echo $group->slug;?>'); ?&gt;</p>
            
            <table cellspacing="0" class="widefat fixed" style="width: auto">
                <thead>
                    <tr>
                    <th class="manage-column">Active</th>
                    <th class="manage-column" style="width: 150px">Title</th>
                    <th class="manage-column" style="width: 75px">Type</th>
                    <th class="manage-column" style="width: 50px">Hits</th>
                    <th class="manage-column" style="width: 150px">Key</th>
                    <th class="manage-column" style="width: 200px">Actions</th>
                    </tr>
                </thead>
                
                <tfoot>
                    <tr>
                    <th class="manage-column">Active</th>
                    <th class="manage-column">Title</th>
                    <th class="manage-column">Type</th>
                    <th class="manage-column">Hits</th>
                    <th class="manage-column">Key</th>
                    <th class="manage-column">Actions</th>
                    </tr>
                </tfoot>
            
                <tbody>
                <?php 
					$y=0;
					foreach (array_keys($group->banners) as $banner_key) : 
						$banner = $group->banners[$banner_key]; ?>
                	<tr>
	                	<td><?php echo ($banner->active?"Yes":"No");?></td>
    	                <td> <?php echo $banner->title; ?></td>
                        <td><?php echo $banner->getType(); ?></td>
                        <td><?php echo $banner->getHits();?></td>
                        <td><?php echo $banner->getKey()?></td>
                        <td>
                        	<a href="?page=banner-manager&section=edit-banner&group=<?php echo $group->slug;?>&banner-index=<?php echo $banner_key;?>">Edit</a> 
                            <a href="?page=banner-manager&section=delete-banner&group=<?php echo $group->slug;?>&banner-index=<?php echo $banner_key;?>">Delete</a> 
                            <?php if ($banner->active) : ?>
                            <a href="?page=banner-manager&group=<?php echo $group->slug;?>&banner-index=<?php echo $banner_key;?>&action=deactivate">Deactivate</a>
                            <?php else: ?>
                            <a href="?page=banner-manager&group=<?php echo $group->slug;?>&banner-index=<?php echo $banner_key;?>&action=activate">Activate</a>                        
                            <?php endif; ?>
                            <a href="?page=banner-manager&group=<?php echo $group->slug;?>&banner-index=<?php echo $banner_key;?>&action=reset-hits">Reset Hits</a>                        
                        
                        </td>
                    </tr>
                <?php $y++; endforeach; ?>
					<tr>
                        <td colspan=4><a href="?page=banner-manager&section=new-banner&group=<?php echo $group->slug; ?>">Create new banner</a></td>
                        
					</tr>
                </tbody>
            
            </table>
            
            
        <?php endforeach; ?>
        
		</div><!--poststuff-->
        
    </div><!--wrap-->
    
<?php
}



function banner_manager_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('design-upload', get_bloginfo('template_url').'/includes/design-options.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('design-upload');
	

}

function banner_manager_admin_styles() {
	wp_enqueue_style('thickbox');
}

if (isset($_GET['page']) && $_GET['page'] == 'banner-manager') {
	add_action('admin_print_scripts', 'banner_manager_admin_scripts');
	add_action('admin_print_styles', 'banner_manager_admin_styles');
}



//END ADMINISTRATION SCREEN


function bannerAd($group="template-header", $bannerTitle="", $return=false)
{
	$banner_groups = banner_manager_load_banners();
	$group = $banner_groups[$group];
	$banners = $group->banners;
	$realbanners = array();
	foreach($banners as $banner)
	{
		if ($banner->active == true) {
			$realbanners[] = $banner;
			if ($bannerTitle!="" && $bannerTitle==$banner->title) $usablebanner = $banner;
		}
	}
	
	if ($bannerTitle=="") {
		$usablebanner = $realbanners[array_rand($realbanners)];
	} else {
	}
		
	if ($usablebanner) {
		if ($return) return $usablebanner->getHTML();
		echo $usablebanner->getHTML();
	}
}

function bannerAd_shortcode($atts) {
	extract(shortcode_atts(array(
		'name' => 'template-header',
		'title' => ''
	), $atts));

	return bannerAd($name, $title, true);
}
add_shortcode('banner-group', 'bannerAd_shortcode');



class BannerAd extends WP_Widget {
    /** constructor */
    function BannerAd() {
        parent::WP_Widget(false, $name = 'BannerAd');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $group = $instance['group'];
		$banner_title = $instance['banner_title'];
		$title = apply_filters('widget_title', $instance['title'] );
        ?>
 <?php echo $before_widget;         ?>   
<div class="bannerwidget">
                  <?php if ( $title ) { echo "<h4>$title</h4>"; } ?>
                  <?php echo bannerAd($group, $banner_title, true); ?>
</div>
 <?php echo $after_widget;         ?>             
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {	
				
	$instance = $old_instance;
	$instance['title'] = strip_tags( $new_instance['title'] );
	$instance['group'] = strip_tags($new_instance['group']);
	$instance['banner_title'] = strip_tags($new_instance['banner_title']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $groupName = esc_attr($instance['group']);
		$banner_title = esc_attr($instance['banner_title']);

		if($groupName=="")$groupName = "template-header";
		
		$banner_groups = banner_manager_load_banners();
				
        ?>

<p>
<label for="<?php echo $this->get_field_id('title'); ?>">Title (Optional):</label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" type="text" style="width:100%;" />
</p>

            <p><label for="<?php echo $this->get_field_id('group'); ?>"><?php _e('Group:'); ?> <select class="widefat" id="<?php echo $this->get_field_id('group'); ?>" name="<?php echo $this->get_field_name('group'); ?>">
            
            <?php foreach($banner_groups as $group) : ?>
            	<option value="<?php echo $group->slug;?>" <?php echo ($groupName==$group->slug?"SELECTED":"");?> ><?php echo $group->title;?></option>
            <?php endforeach; ?>
            </select></p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('banner_title'); ?>"><?php _e('Banner Title:'); ?><br />
                <select class="widefat" id="<?php echo $this->get_field_id('banner_title'); ?>" name="<?php echo $this->get_field_name('banner_title'); ?>">
                	<option value="">Random</option>
                    <?php foreach ($banner_groups as $group) :?>
                    	<?php foreach($group->banners as $banner) : ?>
		                    <option <?php echo ($group->slug==$groupName && $banner->title==$banner_title?"SELECTED":"");?> class="<?php echo $group->slug;?>" style="<?php echo ($group->slug==$groupName?"":"display:none");?>" value="<?php echo $banner->title;?>"><?php echo $banner->title;?></option>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </select>
            </p>
            <script>
				jQuery('#<?php echo $this->get_field_id('group'); ?>').change( function() {
					var group = jQuery(this).children(':selected').val();
					jQuery('#<?php echo $this->get_field_id('banner_title'); ?>').children('option').css('display','none');
					jQuery('#<?php echo $this->get_field_id('banner_title'); ?>').children('option.'+group).css('display','block');
					jQuery('#<?php echo $this->get_field_id('banner_title'); ?>').children(':first').css('display', 'block').attr('selected','1');
				});
			</script>
        <?php 
    }

} // class BannerAd
add_action('widgets_init', create_function('', 'return register_widget("BannerAd");'));


?>