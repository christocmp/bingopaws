<?php 

// Adds Bonus Table Shortcode
function fly_topbinog_func($atts) {
	extract(shortcode_atts(array(
		'num' => 5,
		'orderby' => 'date',
		'sort' => 'DESC',
		'tag'=>'',
		'platform'=>'',
		'software'=>'',
	), $atts));


if ($orderby == 'date'){
	
$loop = new WP_Query( array( 'post_type' => 'casino', 'posts_per_page' => -1, 'orderby'=>'date','order' => 'DESC'  )); 

} else if ($orderby == '_as_roomname') {

$loop = new WP_Query( array( 'post_type' => 'casino', 'posts_per_page' => -1, 'order'=>$sort, 'orderby'=>'meta_value', 'meta_key'=>$orderby )); 

} else if ($orderby == 'order') {

$loop = new WP_Query( array( 'post_type' => 'casino', 'posts_per_page' => -1, 'order'=>$sort, 'orderby'=>'menu_order' )); 

} else {

$loop = new WP_Query( array( 'post_type' => 'casino', 'posts_per_page' => -1, 'order'=>$sort, 'orderby'=>'meta_value_num', 'meta_key'=>$orderby ) );

}

	$i=0;
	$posts = array();
	foreach ($loop->posts as $p) {
		if ($i>=$num) continue;
		
		if ($tag!='' && !has_term($tag, 'affiliate-tags', $p)) continue;
		if ($platform!='' && !has_term($platform, 'platform-tags', $p)) continue;
		if ($software!='' && !has_term($software, 'network-tags', $p)) continue;
		$custom = get_post_custom($p->ID);	
		
		$posts[] = $p;
		$i++;
	}


$redirectkey=fly_redirect_slug();


$ret = '
 <div class="bingositesout">       

';
$x=0;
global $post;
$opost = $post;
foreach ($posts as $post) :
	setup_postdata($post); 
$x=$x+1;
$width=get_post_meta($post->ID, "_as_rating", true)*20;        

$ret .= '

	<div ' . ($x==1 ? "class='bingosites top'" : "class='bingosites'") .'>
        	<div class="rank"><span>'.$x .'</span> </div>
           	<div class="logoarea"><a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '"> '. get_the_post_thumbnail($post->ID,'casino-logo',array('class' => 'logo')).'</a>
		</div>
            <div class="info"> <h3>' . get_post_meta($post->ID,"_as_roomname",true) . '</h3> <span class="rate fl"> <span class="ratetotal" style="width: '. $width.'%;"></span></span> <div class="summary">'. get_the_excerpt() .'</div></div>
            <div class="buttons"><a href="' . get_permalink() . '" class="visbutton size1 blue mb">' . (get_gambling_option("topsites-rev-button")!="" ? get_gambling_option("topsites-rev-button") : "Read Review") .'</a> <br /><a '. (get_theme_option('topsites-rev-button')!="" ? "target=\"_blank\"" : "") .' href="'. get_bloginfo('url') .'/'. $redirectkey .'/'. get_post_meta($post->ID,"_as_redirectkey",true)  .'/" class="visbutton size1 marz">' . (get_gambling_option("topsites-pn-button")!="" ? get_gambling_option("topsites-pn-button") : "Play Now") .'</a> </div>
          </div>

';

endforeach;
$post = $opost;
 

 $ret .='</div> <!--.bingositesout-->';
 
 return $ret;
}

add_shortcode('fly_topsites', 'fly_topbinog_func');

?>