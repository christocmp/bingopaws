<?php

$custom_type_templates = array();

/**
  *	Allows a theme to add a custom type template
 **/	
function add_custom_type_template($custom_type, $template_name, $template_title=false)
{
	global $custom_type_templates;
	
	$templates = array();
	if (array_key_exists($custom_type, $custom_type_templates)) {
		$templates = $custom_type_templates[$custom_type];
	}
	
	if (!array_key_exists($template_name, $templates)) {
		$templates[$template_name] = ($template_title!=false?$template_title:$template_name);
	}
	
	$custom_type_templates[$custom_type] = $templates;
}


add_action( 'add_meta_boxes', 'custom_templates_add_metabox' );
add_action( 'save_post', 'custom_templates_save_postdata' );

/**
  * Add custom metabox to registered custom types
  */
function custom_templates_add_metabox() {
	global $custom_type_templates;
	$custom_type_templates = array();
	do_action('setup_custom_post_templates');
	
	foreach ($custom_type_templates as $post_type=>$templates) {
		add_meta_box(
			'custom_type_templates_meta',
			'Custom Type Page Template', 
			'custom_type_templates_inner',
			$post_type,
			'side'
    	);
	}
    
}


function custom_type_templates_inner( $post )
{
	global $custom_type_templates;
	
	// Use nonce for verification
	wp_nonce_field( 'custom-type-templates.php', 'custom_type_templates_noncename' );
	
	// The actual fields for data entry
	echo '<label for="custom_type_templates_select">Custom Template</label><br />';
	
	$templates = $custom_type_templates[$post->post_type];
	
	$template = get_post_meta($post->ID, 'custom_type_templates_select', true);	
	echo '<select id="custom_type_templates_select" name="custom_type_templates_select">';
	echo '<option value="">[Default]</option>';

	foreach ($templates as $file=>$title) {
		echo '<option value="' . $file . '" ' . ($file==$template?'SELECTED':'') . '>'. $title . '</option>';
	}
	
	echo '</select>';
}

/**
  * Save custom template if the data exists in $_POST
  */
function custom_templates_save_postdata( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  return;
  if ( !wp_verify_nonce( $_POST['custom_type_templates_noncename'], 'custom-type-templates.php' ) ) return;
  if ( !current_user_can( 'edit_post', $post_id ) ) return;

  $template = $_POST['custom_type_templates_select'];  
  update_post_meta($post_id, 'custom_type_templates_select', $template);
}


/**
  * Check if post has a custom template and if so, use it
  */
function custom_templates_redirector() {
	global $post;
	global $custom_type_templates;
	$custom_type_templates = array();
	do_action('setup_custom_post_templates');



	$template = get_post_meta($post->ID, 'custom_type_templates_select', true);

	if ($template != '')
	{
		//make sure this is still a registered type
		if (array_key_exists($post->post_type, $custom_type_templates)) {
			$templates = $custom_type_templates[$post->post_type];
			if (array_key_exists($template, $templates)) {
				$t = locate_template(array($template));
				if ($t !== false) {
					load_template($t);
					exit;
				}
			}
		}
	}
}
add_action('template_redirect', 'custom_templates_redirector');