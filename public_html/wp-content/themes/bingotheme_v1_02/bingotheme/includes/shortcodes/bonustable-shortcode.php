<?php 

// Adds Bonus Table Shortcode
function fly_bonustable_func($atts) {
	extract(shortcode_atts(array(
		'num' => 5,
		'orderby' => 'date',
		'sort' => 'DESC',
		'tag'=>'',
		'platform'=>'',
		'software'=>'',
		'version' => ''
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

if ($version ==1 || $version=='') {

$ret = '
<table class="topbonuses">
          <tr>
            <th >' . (get_gambling_option("replace-head-site")!="" ? get_gambling_option("replace-head-site") : "Bingo Site") .'</th>
            <th class="hideme">' . (get_gambling_option("replace-head-bonus-nd")!="" ? get_gambling_option("replace-head-bonus-nd") : "No Deposit Bonus") .'</th>
            <th ><span class="hilite">' . (get_gambling_option("replace-head-bonus-su")!="" ? get_gambling_option("replace-head-bonus-su") : "Signup Bonus") .'</span></th>
            <th class="hideme">' . (get_gambling_option("replace-head-bonus-rl")!="" ? get_gambling_option("replace-head-bonus-rl") : "Reload Bonus") .'</th>
            <th>' . (get_gambling_option("replace-head-visit")!="" ? get_gambling_option("replace-head-visit") : "Visit Site") .'</th>
          </tr>
 
';
$x=0;
global $post;
$opost = $post;
foreach ($posts as $post) :
	setup_postdata($post); 
$x=$x+1;

$ret .= '

	<tr ' . ($x==1 ? "class='alt'" : "") .'>

            <td class="logocol"><a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '">
		'. get_the_post_thumbnail($post->ID,'casino-icon',array('class' => 'logo')).'</a><br />
              <a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '">' . get_post_meta($post->ID,"_as_roomname",true) . '</a>
	    </td>
            <td class="bonuscol hideme">' . get_post_meta($post->ID,"_as_nodeposit",true) . '</td>
            <td class="bonuscol"><span class="hilite">' . get_post_meta($post->ID,"_as_bonustext",true) . '</span></td>
            <td class="bonuscol hideme">' . get_post_meta($post->ID,"_as_reload",true) . '</td>
            <td class="visitcol"><a '. (get_theme_option('redirect-new-window')!="" ? "target=\"_blank\"" : "") .' href="'. get_bloginfo('url') .'/'. $redirectkey .'/'. get_post_meta($post->ID,"_as_redirectkey",true)  .'/" class="visbutton">' . (get_gambling_option("replace-vis-button")!="" ? get_gambling_option("replace-vis-button") : "Play Now") .'</a></td>
          </tr>


';

endforeach;
$post = $opost;

} else if ($version ==2) {


$ret = '
<table class="midsites">
  	<tr>
            <th> </th>
	    <th class="hideme">' . (get_gambling_option("replace-head-site")!="" ? get_gambling_option("replace-head-site") : "Bingo Site") .'</th>
            <th class="hideme">' . (get_gambling_option("replace-head-btype")!="" ? get_gambling_option("replace-head-btype") : "Bonus Type") .'</th>
            <th ><span class="hilite">' . (get_gambling_option("replace-head-bamt")!="" ? get_gambling_option("replace-head-bamt") : "Bonus Amount") .'</span></th>
            <th>' . (get_gambling_option("replace-head-rev")!="" ? get_gambling_option("replace-head-rev") : "Review") .'</th>
          </tr>

 
';
$x=0;
global $post;
$opost = $post;
foreach ($posts as $post) :
	setup_postdata($post); 
$x=$x+1;        

$ret .= '

	<tr ' . ($x%2==0 ? "class='alt'" : "") .'>

 	<td class="logocol"><a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '">
		'. get_the_post_thumbnail($post->ID,'casino-icon',array('class' => 'logo')).'</a></td>
            <td class="casinoname hideme"><a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '">' . get_post_meta($post->ID,"_as_roomname",true) . '</a></td>
            <td class="bonuscol hideme">' . (get_gambling_option("replace-mid-btype2")!="" ? get_gambling_option("replace-mid-btype2") : "Signup Bonus") .'</td>
            <td class="bonuscol"><span class="hilite">' . get_post_meta($post->ID,"_as_subonus",true) . '</span></td>
            <td class="visitcol"><a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '" class="visbutton blue">' . (get_gambling_option("replace-mid-rev")!="" ? get_gambling_option("replace-mid-rev") : "Review") .'</a></td>

          </tr>


';

endforeach;
$post = $opost;
 

} else if ($version ==3) {


$ret = '
<table class="midsites">
  	<tr>
            <th> </th>
	    <th class="hideme">' . (get_gambling_option("replace-head-site")!="" ? get_gambling_option("replace-head-site") : "Bingo Site") .'</th>
            <th class="hideme">' . (get_gambling_option("replace-head-btype")!="" ? get_gambling_option("replace-head-btype") : "Bonus Type") .'</th>
            <th ><span class="hilite">' . (get_gambling_option("replace-head-bamt")!="" ? get_gambling_option("replace-head-bamt") : "Bonus Amount") .'</span></th>
            <th>' . (get_gambling_option("replace-head-rev")!="" ? get_gambling_option("replace-head-rev") : "Review") .'</th>
          </tr>

 
';
$x=0;
global $post;
$opost = $post;
foreach ($posts as $post) :
	setup_postdata($post); 
$x=$x+1;        

$ret .= '

	<tr ' . ($x%2==0 ? "class='alt'" : "") .'>

 	<td class="logocol"><a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '">
		'. get_the_post_thumbnail($post->ID,'casino-icon',array('class' => 'logo')).'</a></td>
            <td class="casinoname hideme"><a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '">' . get_post_meta($post->ID,"_as_roomname",true) . '</a></td>
            <td class="bonuscol hideme">' . (get_gambling_option("replace-mid-btype3")!="" ? get_gambling_option("replace-mid-btype3") : "No Deposit Bonus") .'</td>
            <td class="bonuscol"><span class="hilite">' . get_post_meta($post->ID,"_as_nodeposit",true) . '</span></td>
            <td class="visitcol"><a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '" class="visbutton blue">' . (get_gambling_option("replace-mid-rev")!="" ? get_gambling_option("replace-mid-rev") : "Review") .'</a></td>

          </tr>


';

endforeach;
$post = $opost;
 

} else if ($version == 4) {


$ret = '
<table class="midsites">
  	<tr>
            <th> </th>
	    <th class="hideme">' . (get_gambling_option("replace-head-site")!="" ? get_gambling_option("replace-head-site") : "Bingo Site") .'</th>
            <th class="hideme">' . (get_gambling_option("replace-head-btype")!="" ? get_gambling_option("replace-head-btype") : "Bonus Type") .'</th>
            <th ><span class="hilite">' . (get_gambling_option("replace-head-bamt")!="" ? get_gambling_option("replace-head-bamt") : "Bonus Amount") .'</span></th>
            <th>' . (get_gambling_option("replace-head-rev")!="" ? get_gambling_option("replace-head-rev") : "Review") .'</th>
          </tr>

 
';
$x=0;
global $post;
$opost = $post;
foreach ($posts as $post) :
	setup_postdata($post); 
$x=$x+1;        

$ret .= '

	<tr ' . ($x%2==0 ? "class='alt'" : "") .'>

 	<td class="logocol"><a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '">
		'. get_the_post_thumbnail($post->ID,'casino-icon',array('class' => 'logo')).'</a></td>
            <td class="casinoname hideme"><a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '">' . get_post_meta($post->ID,"_as_roomname",true) . '</a></td>
            <td class="bonuscol hideme">' . (get_gambling_option("replace-mid-btype4")!="" ? get_gambling_option("replace-mid-btype4") : "Reload Bonus") .'</td>
            <td class="bonuscol"><span class="hilite">' . get_post_meta($post->ID,"_as_reload",true) . '</span></td>
            <td class="visitcol"><a title="' . get_post_meta($post->ID,"_as_roomname",true) . '" href="' . get_permalink() . '" class="visbutton blue">' . (get_gambling_option("replace-mid-rev")!="" ? get_gambling_option("replace-mid-rev") : "Review") .'</a></td>

          </tr>

';

endforeach;
$post = $opost;
 

}

 $ret .='</table>';
 
 return $ret;
}

add_shortcode('bonustable', 'fly_bonustable_func');

?>