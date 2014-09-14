<?php 

add_action('init', 'fly_casino_posts');

function fly_casino_posts() {

// Check it Slug has been set
if (get_theme_option('affiliate-slug')){
	$slug=get_theme_option('affiliate-slug');
   } else { $slug= 'review'; 

}

$args = array(
  'labels' => array(   
         'name' => __( 'Online Casinos' ),
         'singular_name' => __( 'Online Casino' ),
        'add_new' => __( 'Add New Online Casino' ),
	'add_new_item' => __( 'Add New Online Casino' ),
	'edit' => __( 'Edit Online Casino' ),
	'edit_item' => __( 'Edit Online Casino' ),
	'new_item' => __( 'New Online Casino' ),
	'view' => __( 'View Online Casino' ),
	'view_item' => __( 'View Online Casino' ),
	'search_items' => __( 'Search Online Casinos' ),
	'not_found' => __( 'No Online Casinos found' ),
	'not_found_in_trash' => __( 'No Online Casinos found in Trash' ),
	'parent' => __( 'Parent Online Casino' ),

                ),

  'public' => true,
  'show_ui' => true,
  'capability_type' => 'post',
  'hierarchical' => false,
  'rewrite' => array( 'slug' => $slug, 'with_front' => false ),
  'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes','comments','author')
);

register_post_type('casino',$args);

  $labels = array(
    'name' => _x( 'Affiliate Tags', 'affiliate tag' ),
    'singular_name' => _x( 'affiliate tag', 'affiliate tag' ),
    'search_items' =>  __( 'Search Affiliate Tags' ),
    'all_items' => __( 'All Affiliate Tags' ),
    'parent_item' => __( 'Parent Affiliate Tag' ),
    'parent_item_colon' => __( 'Parent Affiliate Tag:' ),
    'edit_item' => __( 'Edit Affiliate Tag' ), 
    'update_item' => __( 'Update Affiliate Tag' ),
    'add_new_item' => __( 'Add New Affiliate Tag' ),
    'new_item_name' => __( 'New Affiliate Tag' ),
    'menu_name' => __( 'Affiliate Tags' ),
  ); 	

register_taxonomy('affiliate-tags',array('casino'), array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'tag-affiliates' ),
  ));


  $labels2 = array(
    'name' => _x( 'Networks', 'Network tag' ),
    'singular_name' => _x( 'Network tag', 'Network tag' ),
    'search_items' =>  __( 'Search Network Tags' ),
    'all_items' => __( 'All Network Tags' ),
    'parent_item' => __( 'Parent Network Tag' ),
    'parent_item_colon' => __( 'Parent Network Tag:' ),
    'edit_item' => __( 'Edit Network Tag' ), 
    'update_item' => __( 'Update Network Tag' ),
    'add_new_item' => __( 'Add New Network Tag' ),
    'new_item_name' => __( 'New Network Tag' ),
    'menu_name' => __( 'Networks' ),
  ); 	

register_taxonomy('network-tags',array('casino'), array(
    'hierarchical' => false,
    'labels' => $labels2,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'tag-networks' ),
  ));


  $labels3 = array(
    'name' => _x( 'Deposit Options', 'Deposit Options' ),
    'singular_name' => _x( 'Deposit Option', 'Deposit Option' ),
    'search_items' =>  __( 'Search Deposit Options' ),
    'all_items' => __( 'All Deposit Options' ),
    'parent_item' => __( 'Parent Deposit Option' ),
    'parent_item_colon' => __( 'Parent Deposit Options:' ),
    'edit_item' => __( 'Edit Deposit Option' ), 
    'update_item' => __( 'Update Deposit Option' ),
    'add_new_item' => __( 'Add New Deposit Option' ),
    'new_item_name' => __( 'New Deposit Options' ),
    'menu_name' => __( 'Deposit Options' ),
  ); 	

register_taxonomy('deposit-tags',array('casino'), array(
    'hierarchical' => false,
    'labels' => $labels3,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'tag-doptions' ),
  ));

$labels4 = array(
    'name' => _x( 'Withdrawal Options', 'Withdrawal Options' ),
    'singular_name' => _x( 'Withdrawal Option', 'Withdrawal Option' ),
    'search_items' =>  __( 'Search Withdrawal Options' ),
    'all_items' => __( 'All Withdrawal Options' ),
    'parent_item' => __( 'Parent Withdrawal Option' ),
    'parent_item_colon' => __( 'Parent Withdrawal Options:' ),
    'edit_item' => __( 'Edit Withdrawal Option' ), 
    'update_item' => __( 'Update Withdrawal Option' ),
    'add_new_item' => __( 'Add New Withdrawal Option' ),
    'new_item_name' => __( 'New Withdrawal Options' ),
    'menu_name' => __( 'Withdrawal Options' ),
  ); 	

register_taxonomy('withdrawal-tags',array('casino'), array(
    'hierarchical' => false,
    'labels' => $labels4,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'tag-wdptions' ),
  ));


$labels5 = array(
    'name' => _x( 'Platforms', 'Platforms' ),
    'singular_name' => _x( 'Platform', 'Platform' ),
    'search_items' =>  __( 'Search Platforms' ),
    'all_items' => __( 'All Platforms' ),
    'parent_item' => __( 'Parent Platform' ),
    'parent_item_colon' => __( 'Parent Platforms:' ),
    'edit_item' => __( 'Edit Platform' ), 
    'update_item' => __( 'Update Platform' ),
    'add_new_item' => __( 'Add New Platform' ),
    'new_item_name' => __( 'New Platforms' ),
    'menu_name' => __( 'Platforms' ),
  ); 	

register_taxonomy('platform-tags',array('casino'), array(
    'hierarchical' => false,
    'labels' => $labels5,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'tag-platform' ),
  ));


$labels6 = array(
    'name' => _x( 'Support Type', 'Support Types' ),
    'singular_name' => _x( 'Support Type', 'Support Type' ),
    'search_items' =>  __( 'Search Support Types' ),
    'all_items' => __( 'All Currencies' ),
    'parent_item' => __( 'Parent Support Type' ),
    'parent_item_colon' => __( 'Parent Support Types:' ),
    'edit_item' => __( 'Edit Support Type' ), 
    'update_item' => __( 'Update Support Type' ),
    'add_new_item' => __( 'Add New Support Type' ),
    'new_item_name' => __( 'New Support Types' ),
    'menu_name' => __( 'Support Types' ),
  ); 	

register_taxonomy('support-tags',array('casino'), array(
    'hierarchical' => false,
    'labels' => $labels6,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'tag-support' ),
  ));	

}

add_action('admin_init', 'fly_create_metaboxes');


add_action('save_post','save_blogmetaboxes');
add_action('save_post','save_casinometaboxes');



function fly_create_metaboxes(){  
  add_meta_box("room-meta", "Casino Properties", "fly_casino_metabox", "casino", "normal", "low");
  add_meta_box("blog-meta", "Blog Page Options", "blog_type_metabox", "page", "advanced", "low");
   }  

function get_distinct_values($key, $excludeArray)
{
	global $wpdb;
	$x = $wpdb->get_col("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key='$key'");
	$types = array();
	foreach($x as $y)
	{
		if (!in_array($y, $excludeArray)) {
			$types[] = $y;
		}
	}
	return $types;
}

function fly_casino_metabox($post) {
         $custom = get_post_custom($post->ID);  
         $roomname = $custom["_as_roomname"][0];  
         $roomurl = $custom["_as_roomurl"][0];  
         $redirectkey = $custom["_as_redirectkey"][0];  
         
         $usonly = $custom["_as_usonly"][0]; 
         $bonusamount = $custom["_as_bonusamount"][0]; 
         $bonuscode = $custom["_as_bonuscode"][0]; 
      
         $rating = $custom["_as_rating"][0]; 
         
 $bonustext = $custom["_as_bonustext"][0]; 
 $subonus = $custom["_as_subonus"][0];

 $bonusper = $custom["_as_bonusper"][0]; 

$nodeposit = $custom["_as_nodeposit"][0];
$nodeposithl = $custom["_as_nodeposithl"][0];
$reloadhl = $custom["_as_reloadhl"][0];
$reload = $custom["_as_reload"][0];

 $mindeposit = $custom["_as_mindeposit"][0]; 	
	
?>


<style>
	.smallmeta {font-size:10px; width:275px; }
        .minimeta {font-size:10px; width:50px; }
	.smallmetatwo {font-size:10px;  }
	
</style>

<input type="hidden" name="casino_type_noncename" id="casino_type_noncename" value="<?php echo wp_create_nonce( 'casino_type'.$post->ID );?>" />

<table cellpadding="5" cellspacing="10" width="100%" style="font-size:10px;">


<tr>
  <th width="20%" align="left"><label>Site Name:</label></th>
  <td><input class="smallmeta" type="text" name="_as_roomname" value="<?php echo $roomname; ?>" /></td>
</tr>
<tr>
  <th align="left"><label>Site Affiliate URL:</label></th>
  <td><input class="smallmeta" type="text" name="_as_roomurl" value="<?php echo $roomurl; ?>" /></td>
</tr>

<tr>
  <th align="left"><label>Redirect Key:</label></th>
  <td><span style="font-size:10px;"><?php bloginfo('url'); ?>/visit/</span><input style="width:150px; border:0px; color:#006699; font-size:10px; border-bottom:1px solid #dddddd;" type="text" name="_as_redirectkey" value="<?php echo $redirectkey; ?>" /><span style="font-style: italic;">/</span></td>
</tr>


<tr>
  <th align="left"><label>Main Bonus Code:</label></th>
  <td><input class="smallmeta" type="text" name="_as_bonuscode" value="<?php echo $bonuscode; ?>" /></td>
</tr>


<tr>
  <th align="left"><label>Initial Signup Bonus Amount (with currency):</label></th>
  <td><input class="minimeta" type="text" name="_as_subonus" value="<?php echo $subonus; ?>" /></td>
</tr>

<tr>
  <th align="left"><label>Signup Bonus Headline: (Include currency, text, and/or numbers)</label></th>
  <td><input class="smallmeta" type="text" name="_as_bonustext" value="<?php echo $bonustext; ?>" /></td>
</tr>

<tr>
  <th align="left"><label>Max Signup Bonus Percent:</label></th>
  <td><input class="minimeta" type="text" name="_as_bonusper" value="<?php echo $bonusper; ?>" />%</td>
</tr>

<tr>
  <th align="left"><label>No Deposit Bonus Amount (with currency):</label></th>
  <td><input class="minimeta" type="text" name="_as_nodeposit" value="<?php echo $nodeposit; ?>" /></td>
</tr>

<tr>
  <th align="left"><label>No Deposit Bonus Amount Headline: (Include currency, text, and/or numbers)</label></th>
  <td><input class="smallmeta" type="text" name="_as_nodeposithl" value="<?php echo $nodeposithl; ?>" /></td>
</tr>

<tr>
  <th align="left"><label>Reload Bonus Amount (with currency):</label></th>
  <td><input class="minimeta" type="text" name="_as_reload" value="<?php echo $reload; ?>" /></td>
</tr>

<tr>
  <th align="left"><label>Reload Bonus Amount Headline: (Include currency, text, and/or numbers)</label></th>
  <td><input class="smallmeta" type="text" name="_as_reloadhl" value="<?php echo $reloadhl; ?>" /></td>
</tr>


<tr>
  <th align="left"><label>Minimum Deposit:</label></th>
  <td><input class="minimeta" type="text" name="_as_mindeposit" value="<?php echo $mindeposit; ?>" /></td>
</tr>


<tr>
  <th align="left"><label>Us Players Allowed:</label></th>
  <td><input type="checkbox" name= "_as_usonly" <?php if ($usonly) echo 'CHECKED'; ?> /></td>
</tr>

<tr>
  <th width="20%" align="left"><label>Overall Rating:</label></th>

   <td>
  	<select name="_as_rating" class="smallmetatwo">
        <option value="">Select</option>
    	<option <?php if ($rating == "1") echo 'SELECTED'; ?>>1</option>


<?php $x=0; while ($x<=5){ ?>

<option <?php if ($rating == "$x") echo 'SELECTED'; ?>><?php echo $x; ?></option>
<?php $x=$x+0.1; } ?>
       </select>
       
  </td>

</tr>


</table>


<script>
var destObj = false;
var oldSendTo;

jQuery(document).ready(function() {

	jQuery('.upload_image_button').click(function() {
	 formfield = jQuery(this).prev().attr('name');
	 destObj = jQuery(this).prev();
	 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	 return false;
	});
	
	oldSendTo = window.send_to_editor;
	window.send_to_editor = function(html) {
		if (destObj != false) {
			 imgurl = jQuery('img',html).attr('src');
			 jQuery(destObj).val(imgurl);
			 jQuery(destObj).parent().find('img').attr('src', imgurl);
			 tb_remove();
			 destObj = false;
		} else {
			oldSendTo(html);
		}
	}
	
	jQuery('.clear_field_button').click( function() {
		jQuery(this).prev().prev().val('');
	});
});
</script>

<?php
      }


function blog_type_metabox() {
         global $post;  
         $custom = get_post_custom($post->ID);  
         $numblogs = $custom["_numblogs"][0];  
         $blogexcerpts = $custom["_blogexcerpts"][0];  
         $blogcat = $custom["_blogcat"][0];  
      
?>

<input type="hidden" name="blog_type_noncename" id="blog_type_noncename" value="<?php echo wp_create_nonce( 'blog_type'.$post->ID );?>" />
<table cellpadding="5" cellspacing="10" width="100%" style="font-size:10px;">

<tr>
  <th width="40%" align="left"><label>Show Excerpts Instead of Full Posts:</label></th>
   <td>
  	<select name="_blogexcerpts" class="smallmetatwo">
        <option value="">Select</option>
    	<option <?php if ($blogexcerpts == "Yes") echo 'SELECTED'; ?>>Yes</option>
        <option <?php if ($blogexcerpts == "No") echo 'SELECTED'; ?>>No</option>
       </select> 
  </td>
</tr>

<tr>
  <th align="left"><label>Show Posts From This Cat ID Only (Leave blank for all):</label></th>
  <td><input class="minimeta" type="text" name="_blogcat" value="<?php echo $blogcat; ?>" /></td>
</tr>

<tr>
  <th align="left"><label>Number of Posts To Show:</label></th>
  <td><input class="minimeta" type="text" name="_numblogs" value="<?php echo $numblogs; ?>" /></td>
</tr>
</table>

<?php  } 

function save_blogmetaboxes($post_id) {	
	if ( !wp_verify_nonce( $_POST['blog_type_noncename'], 'blog_type'.$post_id )) {
		return $post_id;
	}

$fields = array('_numblogs', '_blogexcerpts', '_blogcat');
	foreach ($fields as $field) {
		modify_post_meta($post_id, $field, $_POST[$field]);
	}

}


function save_casinometaboxes($post_id) {
	global $post;

	if ( !wp_verify_nonce( $_POST['casino_type_noncename'], 'casino_type'.$post_id )) {
		return $post_id;
	}
	
	//special handling
	if ($_POST["_as_network"] == "Other") $_POST["_as_network"] = $_POST["as_network_other"];
	if ($_POST["_as_currency"] == "Other") $_POST["_as_currency"] = $_POST["as_currency_other"];

	$fields = array('_as_roomname', '_as_roomurl','_as_redirectkey','_as_usonly' ,'_as_bonusamount','_as_bonuscode','_as_rating','_as_founded','_as_bonustext','_as_mindeposit','_as_reload','_as_reloadhl','_as_nodeposithl','_as_nodeposit','_as_subonus','_as_bonusper');
	foreach ($fields as $field) {
		modify_post_meta($post_id, $field, $_POST[$field]);
	}

}	


function modify_post_meta($id, $key, $value)
{
	delete_post_meta($id, $key);
	if ($value != "") {
		add_post_meta($id, $key, $value);
	}

}



?>