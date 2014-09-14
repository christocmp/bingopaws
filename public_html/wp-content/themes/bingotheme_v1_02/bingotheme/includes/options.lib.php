<?php

//ADMIN UI
function design_do_field($f, $value, $pre="css")
{
	$value = stripslashes($value);
	
	if ($f['type']=='option_select') { ?>
	<label for="tag-name"><?php echo $f["title"];?></label>
	<select class="design-options-select" name="<?php echo $pre?>[<?php echo $f["slug"];?>]">
    		<option value="">None </option>
		<?php foreach ($f["options"] as $title=>$opt) { ?>
        	<option value="<?php echo $title;?>" <?php echo ($title==$value?"SELECTED":"");?>><?php echo $title;?>&nbsp;</option>
        <?php } ?>
	</select>
	<?php } elseif (is_array($f["type"])) { ?>

	<label for="tag-name"><?php echo $f["title"];?></label>
	<select class="design-options-select" name="<?php echo $pre?>[<?php echo $f["slug"];?>]">
    		<option value="">DEFAULT </option>
		<?php foreach ($f["type"] as $val=>$opt) { ?>
        	<option value="<?php echo stripslashes($val);?>" <?php echo (stripslashes($val)==$value?"SELECTED":"");?>><?php echo $opt;?>&nbsp;</option>
        <?php } ?>
	</select>
    <p><?php echo $f["description"];?></p>

<?php } elseif ($f["type"]=="image") { ?>

	<label for="tag-name"><?php echo $f["title"];?></label>
	<input class="upload_image_field" id="upload_image_<?php echo $f["slug"];?>" type="text" size="36" name="<?php echo $pre;?>[<?php echo $f["slug"];?>]" value="<?php echo $value;?>" />
	<input class="upload_image_button button-primary" id="upload_image_button" type="button" value="Choose Image" />
    <input class="clear_field_button button-secondary" type="button" value="Clear Field" />
    <p><?php echo $f["description"];?></p>
   <img style="max-width: 300px; display: block;" src="<?php echo $value;?>" class="preview-upload" />
    
<?php } elseif ($f["type"] == "color") { ?>

	<label for="tag-name"><?php echo $f["title"];?></label>
	<input type="text" name="<?php echo $pre;?>[<?php echo $f["slug"];?>]" style="background-color: <?php echo $value;?>" class="flytonic_color_picker" id="<?php echo $pre?><?php echo $f["slug"];?>" value="<?php echo ($value!=""?$value:" ");?>" size="40" aria-required="true" />
    <div class="farbtastic_container" id="farbtastic_container_<?php echo $f["slug"];?>"></div>
	<p><?php echo $f["description"];?></p>
    
<?php } elseif ($f["type"] == "textarea") { ?>

	<label for="tag-name"><?php echo $f["title"];?></label>
    <p><?php echo $f["description"];?></p>
	<textarea rows=6 name="<?php echo $pre;?>[<?php echo $f["slug"];?>]" id="<?php echo $pre;?><?php echo $f["slug"];?>"><?php echo  stripslashes($value);?></textarea>
    
<?php } elseif ($f["type"] == "checkbox") { ?>

		<input type="checkbox" style="width: auto;display:inline-block;" name="<?php echo $pre;?>[<?php echo $f["slug"];?>]" <?php echo ($value!=""?"CHECKED":"");?> id="<?php echo $pre?><?php echo $f["slug"];?>" /><label style="display: inline" for="tag-name"><?php echo $f["title"];?></label>
    <p><?php echo $f["description"];?></p>
    
<?php } else { ?>

	<label for="tag-name"><?php echo $f["title"];?></label>
	<input type="text" name="<?php echo $pre;?>[<?php echo $f["slug"];?>]" id="<?php echo $f["slug"];?>" value="<?php echo stripslashes($value);?>" size="40" aria-required="true">
	<p><?php echo $f["description"];?></p>

<?php
	}
}
//END ADMIN UI

//HELPER FUNCTIONS
function make_quick_setting($title, $slug, $affects, $type, $description)
{
	return array("title" => $title,
				"slug" => $slug,
				"affects" => $affects,
				"type" => $type,
				"description" => $description);	
}

function make_advanced_group($title, $fields)
{
	return array("title"=>$title,"fields"=>$fields);	
}

function make_setting($title, $slug, $type, $description="", $pre="", $post=";}")
{
	return array("title" => $title,
				"slug" => $slug,
				"type" => $type,
				"description" => $description,
				"pre" => $pre,
				"post" => $post);	
}

function make_options_setting($title, $slug, $description, $options)
{
	return array(
				'title'=>$title,
				'slug'=>$slug,
				'type'=>'option_select',
				'description'=>$description,
				'options'=>$options
			);
				
}

//END HELPER FUNCTIONS
?>