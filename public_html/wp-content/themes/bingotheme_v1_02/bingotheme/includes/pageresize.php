<?php

add_action( 'add_meta_boxes', 'flytonic_add_page_resizer' );
add_action( 'save_post', 'flytonic_save_page_size' );


function flytonic_add_page_resizer() {
    add_meta_box(
        'flytonic_page_Resizer',
        __( 'Page Size', 'flytonic_language' ), 
        'flytonic_page_resizer_inner',
        'page'
    );

 add_meta_box(
        'flytonic_page_Resizer',
        __( 'Page Size', 'flytonic_language' ), 
        'flytonic_page_resizer_inner',
        'casino'
    );
}

add_action('wp_head', 'flytonic_insert_custom_page_sizing');
function flytonic_insert_custom_page_sizing()
{
	global $post;
	$options = array('use_custom_layout', 'total_width','layout_type','sidebar1_width','sidebar2_width','content_width');
	
	if ($post->post_type=='page' || $post->post_type=='casino') {
		foreach($options as $option) {
			$$option = get_post_meta($post->ID, '_'.$option, true);
		}
		
		if ($use_custom_layout != '') {
			$content = "<style>\n";
			$content .= ".custom #main { width: {$total_width}px; }\n";
			$content .= ".custom #sidebar1 { width: {$sidebar1_width}px; }\n";
			$content .= ".custom #sidebar2 { width: {$sidebar2_width}px; }\n";
			$content .= ".custom #middlecontent { width: {$content_width}px; }\n";
			$content .= ".custom #footer {width: ".($total_width)."px;}\n";
			//$content .= "#content { width: ".($total_width-40)."px;}\n";
			$content .= ".custom #header { width: {$total_width}px; }\n";
			$content .= '</style>';
			echo $content;
		}
	}
}


/* When the post is saved, saves our custom data */
function flytonic_save_page_size( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  if ( !wp_verify_nonce( $_POST['flytonic_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  
  $options = array('use_custom_layout', 'total_width','layout_type','sidebar1_width','sidebar2_width','content_width');
  foreach($options as $option) {
	  	delete_post_meta($post_id, '_'.$option);
		
		if (isset($_POST[$option]))
			add_post_meta($post_id, '_'.$option, $_POST[$option]);
	}
	
}


function flytonic_page_resizer_inner( $post ) {


  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'flytonic_noncename' );

	$options = array('use_custom_layout', 'total_width','layout_type','sidebar1_width','sidebar2_width','content_width');
	
		
	foreach($options as $option) {
		//$$option = get_option($option);
		$$option = get_post_meta($post->ID, '_'.$option, true);
	}


	//if no values are provided, use these values
	if ($layout_type == "") $layout_type = "c-s";
	//if ($total_width == "") $total_width = 1000;
	//if ($sidebar1_width == "") $sidebar1_width = 300;
	//if ($sidebar1_width == "") $sidebar2_width = 200;
	//if ($content_width == "") $content_width = 639;

?>
<style>
	.slider_container, .sidebar_sliders {
		margin: 10px 40px 10px 40px;
		font-size: 18px;
		width: 500px; }
	.slider_label {
		font-weight: bold; }
	.layout-editor {
		position: relative;
		width: 1000px;
		height: 200px;
		border:1px solid #ddd;
		padding: 4px;
		margin-left: 10px; }
		.layout-editor .panel {
			outline:1px dashed #999;
			height: 100%;
			display: inline-block;
			width: 200px;
			margin: 0px; }
			
		.layout-editor .content {
			background-color: #9dedff; }
		.layout-editors { margin-top: 30px; }
		
		.layout-errors li { color: #DD0000;  }
		
	.dynamic-layout-block, .total-width { margin-bottom: 20px; }
	.dynamic-layout-block label, .total-width label { display: block ;}
			
</style>
       
 <div id="poststuff">
        <form method="post" action="?page=layout-options">
            <div class="postbox " id="quick-settings">
                
                <div class="inside">
                	<input type="checkbox" name="use_custom_layout" <?php echo ($use_custom_layout!=''?'CHECKED':'');?> /> <label>Use custom layout</label><br /><br />
                	<label>Select a layout type:</label><br />
                    <select name="layout_type" id="layout_type">
         
                         <option <?php echo ($layout_type=="c-s"?"SELECTED":"");?> value="c-s">Content - Sidebar</option>
                        <option <?php echo ($layout_type=="s-c"?"SELECTED":"");?> value="s-c">Sidebar - Content</option>
                        
                 
                    </select><br />
                    <br />

                   
                </div>                
            </div>
			</form>
		</div><!--poststuff-->

<?php
}

?>