<?php
/**
* Header File for theme
*
* Displays all of the <head> section, header and top navigation areas
*
* @package Bingo Theme from Flytonic
*
*/
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">

	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php if (get_theme_option('branding-favicon') == "") { ?>
	<link rel="Shortcut Icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico" type="image/x-icon" />
	<?php } else { ?>
	<link rel="Shortcut Icon" href="<?php echo get_theme_option('branding-favicon'); ?>" type="image/x-icon" />
	<?php } ?>
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>"> 
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/includes/js/html5.js"></script>
	<![endif]-->

	<?php 

	//Show Theme Options Header Scripts here
	echo trim(stripslashes(get_theme_option('header-script'))); 
	?>

	<?php wp_head(); ?>
</head>

<?php if (lo_get_layout_type() == 'c-s'){ $class1='rightside'; } else { $class1='leftside'; } ?>

<body <?php body_class('custom '.$class1.''); ?>>

<div id="outerwrap">

	<header class="main-header" role="banner">

	<div class="wrap">
	
  		<div class="header-logo">
		
		<?php if (get_theme_option('header-logo') != ""): ?>
   		<a title="<?php bloginfo('name'); ?>" href="<?php echo get_option('home'); ?>">
   		<img alt="<?php bloginfo('name'); ?>" src="<?php echo get_theme_option('header-logo'); ?>" /></a>
  		<?php else: ?>
  		<h1><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></h1>

  		<?php endif;?>
  		</div><!--.header-logo-->


	<?php if ( is_active_sidebar( 'headertop-widgets' ) ) : ?>
		<div class="headerwidgets">
			
			<?php dynamic_sidebar('headertop-widgets'); ?>
			
		</div><!--.Widgets Heading-->
	<?php endif; ?>

	</div><!--.wrap-->
		 
	</header><!--.main-header-->


	<nav class="navbar" role="navigation" id="navigation">
		
		<div class="wrap">

		<?php wp_nav_menu( array( 'container' => 'false', 'theme_location' => 'primary', 'menu_class' => 'nav','menu_id'=> 'nav','fallback_cb' => 'fly_default_menu','link_before'     => '<span>','link_after'  => '</span>',) ); ?>

		</div><!--.wrap-->

	</nav><!--Nav--> 

	<?php 

	//Fixed navigation bar
	if (get_theme_option('theme-navfix')) { ?>
  		<script> 
  		$(function() {
    		moveScroller();
  		});
		</script> 
	
  		<div id="navigation-anchor"></div> 
	
	<?php } ?>

<div id="main" class="container" role="main">

	<div class="wrap">

	<?php theme_options_show_breadcrumbs(); ?>