<?php

/*
	Excerpt Shorcode Window
*/

/*
	This actually adds the html for the dialog, all html and JS needed to control the behavior should go here
*/
add_action('admin_footer', 'excerpt_button_add_editor');

// Add Excerpt Shortcode Dialog Window
function excerpt_button_add_editor()
{
	$terms = get_terms('category');
?>
<style>.shortcode_builder_table td { padding: 4px !important; }</style>
<div style="display:none">
    	<div id="excerpt_editor" title="[excerptlist] Builder" class="shortcode_editor">
			<table cellspacing=0 cellpadding=4 border=0 class="shortcode_builder_table">

                <tr>
                    <td class="shortcode_builder_label">Number of Posts</td>
                    <td class="shortcode_builder_value"><input id="excerpt_num" /></td>
                </tr>
                <tr>
                    <td class="shortcode_builder_label">Show only titles</td>
                    <td class="shortcode_builder_value"><input id="excerpt_titles" type="checkbox" value="Y" /></td>
                </tr>
                <tr>
                    <td class="shortcode_builder_label">Category</td>
                    <td class="shortcode_builder_value">
                        <select id="excerpt_cat">
                            <option value="">[None]</option>
                            <?php foreach ($terms as $tag) { ?>
                                <option value="<?php echo $tag->name;?>"><?php echo $tag->name;?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            </table>
        
            <?php /*<p class="submit">
                <input type="button" id="ftcta-shortcode-submit" class="button-primary" value="Insert Flytonic Box" name="submit" />
            </p>*/?>
        
        </div><!--/#excerpt_editor-->
    </div>



    <script>
	jQuery(document).ready( function() {
		jQuery('#excerpt_editor').dialog({
			autoOpen:false,
			draggable: false,
			modal: true,
			resizable: false,
			buttons: {
				Ok: function() {
					var str = '[excerptlist ';
					
					if (jQuery('#excerpt_num').val()!='') str += 'num=' + jQuery('#excerpt_num').val() + ' '; //a normal input
					if (jQuery('#excerpt_titles').is(':checked')) str += 'titleonly=\'y\' '; //a checkbox
					if (jQuery('#excerpt_cat :selected').val()!='') str += 'cat=\'' + jQuery('#excerpt_cat :selected').val() + '\' '; //a select box
										
					str += ']';
					
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