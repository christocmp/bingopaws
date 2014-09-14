<?php
function flytonic_button_func($atts) {
	extract(shortcode_atts(array(
		'text' => 'Button',
		'linkurl' => '#',
		'text_color' => 'white',
		'text_color_custom' => '',
		'icon' => 'none',
		'size' => 'reg', //Small(sm), Regular(reg), Medium(med), Large(lg)
		'bg_color' => '',
		'bg_color_custom' => '',
		'border_color' => 'none',
		'border_color_custom' => '',
	), $atts));
    
    $style_addition = "";
    $style_addition_open = " style='";
    $style_addition_close = "'";
    $icon_class = "";
    
    $cdf_class .= "flybutton";
    
    if($size != "reg") {
        $cdf_class .= " ".$size;
    }

    if($bg_color != "normal") {
        if($bg_color != "custom") {
            $cdf_class .= " ".$bg_color;
        } else {
            if($bg_color_custom != "") {
                $style_addition .= "background:".$bg_color_custom." !important;";
            }
        }
    }
    
    if($border_color == "custom") {
        if($border_color_custom != "") {
            $style_addition .= "border: 1px solid ".$border_color_custom.";";
        }
        
    }

    $cdf_class .= " ".$text_color;
    
    if(!empty($style_addition)) {
        $style_addition = $style_addition_open.$style_addition.$style_addition_close;
    }
    
    if($icon != "none") {
        $icon_class = " class='flyspan ".$icon."'";
    }
    
    $fly_button .= "<a href='".$linkurl."' class='".$cdf_class."'".$style_addition."><span".$icon_class.">".$text."</span></a>\n";

    return $fly_button;
}


add_shortcode('flytonic-button', 'flytonic_button_func');


?>