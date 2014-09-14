<?php
function flytonic_box_func($atts,$boxcontent) {
	extract(shortcode_atts(array(
		'box_style' => 'info',
		
	), $atts));
    
 
    $fly_box = '<div class="fly_box '.$box_style.'">'.$boxcontent.'</div>';

    return $fly_box;
}


add_shortcode('ft_boxes', 'flytonic_box_func');


?>