	<div class="clearboth"></div>

	</div><!--.wrap-->
</div><!--#Main-->

	<footer id="footer" class="main-footer">
		<div class="wrap">

		<?php 

		//Check to See if top footer is hidden or not
		if (!get_theme_option('footer-toparea')) { 

		?>

		<div class="widgetarea">


		<?php include(get_stylesheet_directory() . '/footerwidgets.php'); ?>
		
		</div><!--.widgetarea-->

	
		<?php } ?>
		</div><!--.wrap-->

	</footer>	


	<?php 

		//Check to See if bottom footer is hidden or not
		if (!get_theme_option('footer-bottomhide')) { 

		?>

	
	<footer id="footerbottom" class="bottom-footer">
		<div class="wrap">
	
				<span>
		<?php if (get_theme_option('footer-text')) {  echo stripslashes(get_theme_option('footer-text')); ?>

  		<?php } else { ?>

  		 Copyright &copy; <?php echo(date('Y')); ?> <a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a>, All Rights Reserved  

  		<?php if (!get_theme_option('footer-credit')) { ?>
| <a href="http://www.flytonic.com/">Bingo Theme</a> by Flytonic.
  		
  		<?php 
			} 

		} ?>
		
		</span>
		
		</div><!--.wrap-->
	</footer><!--#footer-->

	<?php } //bottom footer check ?>


</div><!--.outside -->


<?php wp_footer(); ?>

	<?php
 
	//Output any footer scripts from theme options panel
	echo trim(stripslashes(get_theme_option('footer-script'))); 

	?>


</body>
</html>