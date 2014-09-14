<?php

/*
	Alert Boxes Shorcode Window
*/

/*
	This actually adds the html for the dialog, all html and JS needed to control the behavior should go here
*/
add_action('admin_footer', 'fly_boxes_window');

// Add Excerpt Shortcode Dialog Window
function fly_boxes_window()
{
	
?>
<style>  
	.flytonic_sc_editor { padding: 10px !important; margin:0 auto; color:#444; font-size:12px;  }
 .flytonic_sc_editor .fly_textinput {font-size:11px; border:1px solid #ddd; border-radius:4px; -moz-border-radius:4px; color:#444; padding:3px !important; margin:0 0 10px 0; height:27px;  }
 .flytonic_sc_editor label {margin-right:5px;}
.flytonic_sc_editor p {margin:0 0 10px 0; padding:0; font-size:12px; font-style:italic; color:#666;}	


</style>


 <div id="fly_boxes" class="flytonic_sc_editor" title="Alert Boxes Shortcode"  style="display:none">
    	
	<div>
    	<label>Icon</label>
    	<select class="fly_textinput" name="box_style" id="box_style">
    	     	<option value="info">Info Box</option>
		<option value="check">Check Box</option>
               	 <option value="download">Download Box</option>
		<option value="error">Error Box</option>
   		<option value="alert">Alert Box</option>
    	</select>

	</div>

	<div>
    	<label>Alert Box Text</label>
	<textarea class="fly_textinput" id="box_body" style="width: 100%; height: 80px;"></textarea>
	<p>Enter text here or within the post itself</p>
   	</div> 
	

    </div>


    <script>
	jQuery(document).ready( function() {
		jQuery('#fly_boxes').dialog({
			autoOpen:false,
			draggable: false,
			modal: true,
			resizable: false,
			buttons: {
				Ok: function() {
					var str = '[ft_boxes ';
					
					if (jQuery('#box_style').val()!='') str += 'box_style=\'' + jQuery('#box_style').val() + '\' '; //a normal input
										
					str += ']';

					str += jQuery('#box_body').val() + '[/ft_boxes]';
					
					var Editor = tinyMCE.get('content');
					Editor.focus();
					Editor.selection.setContent(str);

					
					jQuery( this ).dialog( "close" );
				},
				Cancel: function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	});
	</script>
<?php
}

?>