<?php

add_action('init', 'hrt_redirect_rooms'); 

function hrt_redirect_rooms () {
         global $wpdb;
	$redirectkey=fly_redirect_slug();
	$request = $_SERVER['REQUEST_URI'];
	if (!isset($_SERVER['REQUEST_URI'])) {
		$request = substr($_SERVER['PHP_SELF'], 1);
		if (isset($_SERVER['QUERY_STRING']) AND $_SERVER['QUERY_STRING'] != '') { $request.='?'.$_SERVER['QUERY_STRING']; }
	}
	if (isset($_GET['affkey'])) {
		$request = '/'. $redirectkey .'/'.$_GET['affkey'].'/';
	}
	

	if ( strpos('/'.$request, '/'. $redirectkey .'/') ) {
		$affkey_key = explode(''. $redirectkey .'/', $request);
		$affkey_key = $affkey_key[1];
		$affkey_key = str_replace('/', '', $affkey_key);
            
           $url=room($affkey_key);
           header("X-Robots-Tag: noindex, nofollow", true); 
           header('Location: ' . $url . "\n\n");
	   exit;

	} 
	
}

function room($affkey_key) {
	global $wpdb;
	global $post;
	
	query_posts('post_type=casino&meta_key=_as_redirectkey&meta_value='.$affkey_key .''); 
	
	if ( have_posts() ) {
		the_post();
		
		$url= get_post_meta($post->ID, '_as_roomurl', true);
		do_action('flytonic-room-redirect', $post);
	} else {
		$redirects = get_option('flytonic_custom_redirects');	
		if (array_key_exists($affkey_key, $redirects)) {
			$redirect = $redirects[$affkey_key];
			$redirect['hits'] += 1;
			
			$redirects[$affkey_key] = $redirect;
			update_option('flytonic_custom_redirects', $redirects);
			
			$url = $redirect['url'];
			
		}
	}
	
	return $url;
}

add_action('init', 'download_redirect'); 

function download_redirect () {
         global $wpdb;

	$request = $_SERVER['REQUEST_URI'];
	if (!isset($_SERVER['REQUEST_URI'])) {
		$request = substr($_SERVER['PHP_SELF'], 1);
		if (isset($_SERVER['QUERY_STRING']) AND $_SERVER['QUERY_STRING'] != '') { $request.='?'.$_SERVER['QUERY_STRING']; }
	}
	if (isset($_GET['dlkey'])) {
		$request = '/download/'.$_GET['dlkey'].'/';
	}
	

	if ( strpos('/'.$request, '/download/') ) {
		$dl_key = explode('download/', $request);
		$dl_key = $dl_key[1];
		$dl_key = str_replace('/', '', $dl_key);
  
                $url=room2($dl_key);
		header("X-Robots-Tag: noindex, nofollow", true); 
                header('Location: ' . $url . "\n\n");
			
			exit;

	} 
}

function room2($dl_key) {
global $wpdb;
global $post;

query_posts('post_type=casino&meta_key=_as_redirectkey&meta_value='.$dl_key .''); 
if ( have_posts() ) : the_post();

$url= get_post_meta($post->ID, '_as_downloadurl', true);

endif;

return $url;
}


add_action('admin_menu', 'redirect_add_menu',100);
function redirect_add_menu()
{
	add_submenu_page('design-options', 'Redirect Options', 'Redirect Options', 'update_themes', 'redirect-options', 'redirect_show_ui');
	
}

function redirect_show_ui()
{
	$redirectkey=fly_redirect_slug();
	$redirects = get_option('flytonic_custom_redirects');
	if (!$redirects) $redirects = array();

	if ($_POST['action'] == 'Create Redirect') {
		if (array_key_exists($_POST['key'], $redirects)) {
			$cr_error = 'Key already exists';
		} elseif ($_POST['key'] == '' || $_POST['url'] == '') {
			$cr_error = 'Key and URL are both required fields';
		} else {
			$redirect = array('key'=>$_POST['key'], 'url'=>$_POST['url'], 'hits'=>0);
			$redirects[$_POST['key']] = $redirect;
			
			update_option('flytonic_custom_redirects', $redirects);
		}
	} elseif ($_POST['action'] == 'Save Changes') {
		$rvalues = $_POST['redirects'];
		
		foreach ($rvalues as $key=>$info)
		{
			$redirects[$key]['url'] = $info['url'];
			
			if ($info['key'] != $key) {
				$redirects[$key]['key'] = $info['key'];
				
				$redirects[$info['key']] = $redirects[$key];
				unset($redirects[$key]);
			}
		}
		
		update_option('flytonic_custom_redirects', $redirects);
		
	} elseif ($_GET['action'] == 'delete-redirect') {
		unset($redirects[$_GET['key']]);
		update_option('flytonic_custom_redirects', $redirects);
	} elseif ($_GET['action'] == 'reset-redirect') {
		if (array_key_exists($_GET['key'], $redirects)) {
			$redirect = $redirects[$_GET['key']];
			$redirect['hits'] = 0;
			
			$redirects[$_GET['key']] = $redirect;
			
			update_option('flytonic_custom_redirects', $redirects);
		}
	}

	
?>
<style>
	.redirects_table th { text-align: left; }
	.redirects_table { width: 700px !important; }
</style>
	<div class="wrap meta-box-sortables">
    	<div class="icon32" id="icon-themes"><br></div>
        <h2>Custom Redirects</h2>
        
        <div id="poststuff">
        	<br /><br />
            <form method="post" action="?page=redirect-options">
	        <table class="redirects_table widefat" width=620>
            	<thead>
                	<tr>
                    	<th width=120>Redirect Key</th>
                        <th width=400>Url</th>
                        <th width=75>Hits</th>
                        <th width=160>Actions</th>
					</tr>
				</thead>
                <tbody>
                
                <?php if (count($redirects) == 0) : ?>
                	<tr>
                    	<td colspan="4"><em>No custom redirects</em></td>
					</tr>
                <?php else: ?>
                	<?php foreach ($redirects as $redirect) : ?>
                    <tr>
                    	<td><span><a href="<?php echo bloginfo('home');?>/<?php echo $redirectkey; ?>/<?php echo $redirect['key'];?>"><?php echo $redirect['key']; ?></a></span>
                        		<input type="text" style="width: 75px; display: none" name="redirects[<?php echo $redirect['key'];?>][key]" value="<?php echo $redirect['key']; ?>" /></td>
                        <td><span><?php echo  $redirect['url']; ?></span>
                        	<input type="text" style="width: 300px; display: none" name="redirects[<?php echo $redirect['key'];?>][url]" value="<?php echo $redirect['url']; ?>" />
                        </td>
                        <td><?php echo $redirect['hits']; ?></td>
                        <td>
                        	<span><a href="#" onclick="editRow(this)">Edit</a> | </span>
                        	<a href="?page=redirect-options&action=delete-redirect&key=<?php echo $redirect['key'];?>">Delete</a> | 
                        	<a href="?page=redirect-options&action=reset-redirect&key=<?php echo $redirect['key'];?>">Reset Hits</a></td>
                    </tr>
                    <?php endforeach; ?>
                
                <?php endif; ?>
                	
                </tbody>
            </table>
            <br />
            <input type="submit" name="action" value="Save Changes" />
		</form>
        <script>
		
			function editRow(o)
			{
				jQuery(o).parent().hide();
				jQuery(o).parents('tr').find('td span').hide();
				jQuery(o).parents('tr').find('td input').show();
			}
		
		</script>
        <br />
        <h3>Create a custom redirect</h3>
        <?php if ($cr_error != '') { ?>
        <strong style="color: #990000;"><?php echo $cr_error;?></strong>
        <?php } ?>
        <form method="post" action="?page=redirect-options">
            <label>Key:</label>
            <input type="text" name="key" value="<?php echo $_POST['key'];?>" />
            <label>URL:</label>
            <input style="width: 200px" name="url" value="<?php echo $_POST['url'];?>" type="text" />
            <input type="submit" name="action" value="Create Redirect" />
		</form>
		</div><!--poststuff-->
        
    </div><!--wrap-->
<?php
}

?>