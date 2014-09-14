<?php

/*
	Run the bonuscode_button function when WP initialization
*/

/*
	This actually adds the html for the dialog, all html and JS needed to control the behavior should go here
*/
add_action('in_admin_footer', 'bcb_add_editor');
function bcb_add_editor()
{
		
		$tags = get_terms('affiliate-tags');
		$networktag = get_terms('network-tags');
		$plattag = get_terms('platform-tags');
	
?>
<style>
  .flytonic_sc_editor { padding: 10px !important; margin:0 auto; color:#444; font-size:12px;  }
 .flytonic_sc_editor .fly_textinput {font-size:11px; border:1px solid #ddd; border-radius:4px; -moz-border-radius:4px; color:#444; padding:3px !important; margin:0 0 10px 0; height:27px;  }
 .flytonic_sc_editor label {margin-right:5px;}
.flytonic_sc_editor p {margin:0 0 10px 0; padding:0; font-size:12px; font-style:italic; color:#666;}	
</style>
	<div id="bcb_editor" class="flytonic_sc_editor" title="Bingo Bonuses Shortcode"  style="display:none">
        	
       
	
	<div>
    	<label>Number of sites</label>
    	<input class="fly_textinput" type="text" name="bcb_num" id="bcb_num" size="10">
    	<p>Enter number of sites to display</p>
   	</div>	 


	<div>
    	<label>Order By</label>
    	<select class="fly_textinput" name="bcb_orderby" id="bcb_orderby">
    	     	<option value="">[Default]</option>
                 <option value="_as_roomname">Site Name</option>
                 <option value="order">Menu Order</option>
                 <option value="date">Date</option>
                 <option value="_as_rating">Rating</option>
    	</select>
	</div>

	<div>
    	<label>Sort Order</label>
    	<select class="fly_textinput" name="bcb_sort" id="bcb_sort">
    	     	<option value="">[Default]</option>
		<option value="asc">Ascending</option>
		<option value="desc">Descending</option>
    	</select>
	</div>

	

	<div>
    	<label>Filter by Tag</label>
    	<select class="fly_textinput" name="bcb_tag" id="bcb_tag">
    	     	<option value="">[None]</option>
		   <?php foreach ($tags as $tag) { ?>
                       <option><?php echo $tag->name;?></option>
                  <?php } ?>
    	</select>
	</div>


	<div>
    	<label>Filter by Platform</label>
    	<select class="fly_textinput" name="casino_plat" id="casino_plat">
    	     	<option value="">[All]</option>
		   <?php foreach ($plattag as $tag) { ?>
                       <option><?php echo $tag->name;?></option>
                  <?php } ?>
    	</select>
	</div>

	<div>
    	<label>Filter by Software</label>
    	<select class="fly_textinput" name="casino_soft" id="casino_soft">
    	     	<option value="">[All]</option>
		   <?php foreach ($networktag as $tag) { ?>
                       <option><?php echo $tag->name;?></option>
                  <?php } ?>
    	</select>
	</div>

	<div>
    	<label>Display Style Variation</label>
    	<select class="fly_textinput" name="bcb_version" id="bcb_version">
		<option value="1">All Bonuses</option>
		<option value="2">No Deposit Bonus</option>
		<option value="3">Initial Deposit Bonus</option>
		<option value="4">Reload Bonus</option>
    	</select>
	<p>Choose the version of the shortcode to display.</p>
	</div>


     
    </div>

    <script>
	jQuery(document).ready( function() {
		jQuery('#bcb_editor').dialog({
			buttons: {
				Ok: function() {
					var str = '[bonustable ';
					
					
					if (jQuery('#bcb_num').val()!='') str += 'num=' + jQuery('#bcb_num').val() + ' '; //a normal input
					
					if (jQuery('#bcb_orderby :selected').val()!='') str += 'orderby=\'' + jQuery('#bcb_orderby :selected').val() + '\' '; //a select box
					if (jQuery('#bcb_sort :selected').val()!='') str += 'sort=\'' + jQuery('#bcb_sort :selected').val() + '\' '; //a select box
					if (jQuery('#bcb_tag :selected').val()!='') str += 'tag=\'' + jQuery('#bcb_tag :selected').val() + '\' '; //a select box
					
					if (jQuery('#casino_plat :selected').val()!='') str += 'platform=\'' + jQuery('#casino_plat :selected').val() + '\' '; //a select box

					if (jQuery('#casino_soft :selected').val()!='') str += 'software=\'' + jQuery('#casino_soft :selected').val() + '\' '; //a select box


					if (jQuery('#bcb_version :selected').val()!='') str += 'version=\'' + jQuery('#bcb_version :selected').val() + '\' '; //a select box
					str += ']';
					
					var Editor = tinyMCE.get('content');
					Editor.focus();
					Editor.selection.setContent(str);

					
					jQuery( this ).dialog( "close" );
				},
				Cancel: function() {
					jQuery( this ).dialog( "close" );
				}
			},
			autoOpen:false,
			draggable: false,
			modal: true,
			resizable: false
		});
	});
	</script>
<?php
}

?>