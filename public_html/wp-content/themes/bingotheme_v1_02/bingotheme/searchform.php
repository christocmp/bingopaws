<?php
/**
 * Search form template
 *
 * @package afftheme
 */
?>

<form method="get" class="searchform" id="searchform" action="<?php bloginfo('url'); ?>/">
	<input class="searchinput" value="Search" onfocus="if (this.value == 'Search') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search';}" id="searchinput" type="text" name="s"  />
	<input name="submit" type="submit" class="searchsubmit" value="Search" />
</form>
