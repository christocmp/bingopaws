<?php

// Excerpts Shortcode
function fly_excerptlist_func($atts) {
	extract(shortcode_atts(array(
		'titleonly' => 'n',
		'cat' => '',
		'num' => 5,
	), $atts));
	
	$args = array('posts_per_page' => $num);
	$id = get_cat_id($cat);
	$args['cat'] = $id;
	
	$loop = new WP_Query( $args ); 
        $posts = array();
        foreach($loop->posts as $p) { $posts[] = $p; }
	global $post;
	$opost = $post;
	ob_start(); 



ob_start();
$ret = apply_filters('excerptlist', $posts);
if (is_array($ret) || $ret =="" || $ret == " ") {
	$ret = ob_get_contents();
}
ob_end_clean();

if (!is_array($ret) && $ret !="") {
} else {

if (strtolower($titleonly)!="y") {

$ret = '
   <div class="excerptlist"> ';
    foreach ($posts as $post):
		setup_postdata($post);

      $ret .=' <div class="articleexcerpt"> 


       <div class="thumb">';

       if ( has_post_thumbnail() ) { 
       $ret .=' <a href="' . get_permalink($post->ID) .'">'. get_the_post_thumbnail($post->ID,array(125,125), array('class' => 'articleimg')) .'</a>';

       } else if (get_theme_option('art-thumb')) { 
       $ret .='<a href="' . get_permalink($post->ID) . '"><img class="articleimg" src="'. get_theme_option('art-thumb') .'" alt="'. get_the_title($post->ID) .'" width="125" height="125" /></a>';
        } 


    $ret .='</div>
		<h3 class="title"><a href="' . get_permalink($post->ID) . '" rel="bookmark">'. get_the_title($post->ID) .'</a></h3>';



 	if (!get_theme_option('bylines-hide-all')) {  

		$ret .= ' <div class="bylines"> 

               ' . (!get_theme_option("bylines-hide-date") ? get_the_time('F j, Y') : "") .' 
               ' . (!get_theme_option("bylines-hide-author") ? "by" : "") .' 
               ' . (!get_theme_option("bylines-hide-author") ? "<a href=\"".get_author_posts_url(get_the_author_meta( 'ID' ))."\">".get_the_modified_author()." </a>"  : "") .' 

 ' . (!get_theme_option("bylines-hide-category") ? "posted in " : "") .' 
            ' . (!get_theme_option("bylines-hide-category") ? get_the_category_list(', ','',$post->ID)  : "") .'

' . (!get_theme_option("bylines-hide-comment") ? '&bull; <a href="' . get_permalink($post->ID) . '#comments">  '. get_comments_number() .' comments </a>' : "") .'
 
			
		</div>';

	}	

              $ret .= '  <p>'. get_the_excerpt() .'</p>

		

            </div><!-- End of articleexcerpt  --> ';
 


endforeach;
$post = $opost;


 $ret .= '</div><!-- End of excerptlist  --> ';


} else { 

$ret = '<ul> ';
    foreach ($posts as $post):
		setup_postdata($post);

   $ret .='<li class="exelist"><a href="' . get_permalink($post->ID) . '" rel="bookmark">'. get_the_title($post->ID) .'</a></li>';

  endforeach;
  $post = $opost;

  $ret .= '</ul>';

}
 

   }

return $ret;
}

add_shortcode('excerptlist', 'fly_excerptlist_func');

?>