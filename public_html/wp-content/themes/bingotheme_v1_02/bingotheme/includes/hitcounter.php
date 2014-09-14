<?php

add_action('flytonic-room-redirect', 'flytonic_hitcounter');
function flytonic_hitcounter($room)
{
	$hits = get_post_meta($room->ID, '_flytonic_hitcounter', true);
	$hits = intval($hits)+1;
	update_post_meta($room->ID, '_flytonic_hitcounter', $hits);
	
	return $hits;
}


add_action('admin_head', 'hitcounter_header');

add_action('init', 'setup_hitcounter');
function setup_hitcounter() {
	add_filter('manage_edit-casino_columns', 'hitcounter_posts_column');
	add_action('manage_casino_posts_custom_column', 'hitcounter_custom_column', 10, 2);
}


// Add CSS for new column
function hitcounter_header(){
?>
	<style type="text/css">
		table.widefat th.column-hitcounter_posts_column,{
			width: 20px;
		}
	</style>
<?php
}

function hitcounter_posts_column($defaults){
    $defaults['flytonic_hits'] = __('Hits');
    return $defaults;
}

// Populate the new column with the Post ID
function hitcounter_custom_column($column_name, $id){
	if($column_name === 'flytonic_hits'){
        $hits = get_post_meta($id, '_flytonic_hitcounter', true);
		echo (int)$hits;
    }
}



add_action('admin_menu', 'hitcounter_options_add_menu', 100);
function hitcounter_options_add_menu()
{
	add_submenu_page('design-options', 'Hit Statistics', 'Hit Statistics', 'update_themes', 'hits-options', 'hits_show_ui');
}

function hits_show_ui()
{
?>
	<div class="wrap meta-box-sortables">
    	<div class="icon32" id="icon-themes"><br></div>
        <h2>Outbound Link Hits</h2>
        
        <?php
			if ($_GET['action']=='clear') {
				update_post_meta($_GET['id'], '_flytonic_hitcounter', 0);
			} elseif ($_GET['action'] == 'clearall') {
				$posts = get_posts(array('post_type'=>'casino', 'numberposts'=>-1));
				foreach ($posts as $p) {
					update_post_meta($p->ID, '_flytonic_hitcounter', 0);
				}
			}
		
			$posts = get_posts(array('post_type'=>'casino', 'numberposts'=>-1));
			function csort($a, $b) {
				$ahits = get_post_meta($a->ID, '_flytonic_hitcounter', true);
				$bhits = get_post_meta($b->ID, '_flytonic_hitcounter', true);
				
				if ($ahits > $bhits) return -1;
				if ($bhits > $ahits) return 1;
				return 0;
			}
			usort($posts, 'csort');
			
		?>

        <table cellspacing="0" class="wp-list-table widefat fixed posts" style="width:500px !important">
            <thead>
            	<tr>
                	<th style="" class="manage-column column-title" id="title" scope="col">Title</th>
                	<th style="width:50px" class="manage-column column-description" id="description" scope="col">Hits</th>
                    <th style="width:50px">Reset?</th>
                </tr>
            </thead>
        
            <tfoot>
				<tr>
                	<th style="" class="manage-column column-title" id="title" scope="col">Title</th>
                	<th style="" class="manage-column column-description" id="description" scope="col">Hits</th>
                    <th>Reset?</th>
                </tr>
            </tfoot>
        
            <tbody id="the-list">
			<?php foreach ($posts as $p) : ?>
            	<tr valign="top" class="post-<?php echo $p->ID;?> casino type-casino status-publish hentry  iedit author-other" id="post-<?php echo $p->ID;?>">
					<td class="post-title page-title column-title">
                    	<strong><a title="Edit <?php echo $p->post_title;?>" href="<?php echo get_bloginfo( 'url' );?>/wp-admin/post.php?post=<?php echo $p->ID;?>&amp;action=edit" class="row-title"><?php echo $p->post_title;?></a></strong>
						<div class="row-actions">
                        	<span class="edit"><a title="Edit this item" href="<?php echo get_bloginfo( 'url' );?>/wp-admin/post.php?post=<?php echo $p->ID;?>&amp;action=edit">Edit</a> | </span>
                            <span class="view"><a rel="permalink" title="View <?php echo $p->post_title;?>" href="<?php echo get_permalink($p->ID);?>">View</a></span>
                        </div>
                    </td>
					<td class="flytonic_hits column-flytonic_hits"><?php echo intval(get_post_meta($p->ID, '_flytonic_hitcounter', true));?></td>
                    <td><a href="?page=hits-options&action=clear&id=<?php echo $p->ID;?>">[Clear]</a></td>
				</tr>
			<?php endforeach; ?>    
		</tbody>
</table>
<br />
<form class="form-wrap" method="post" action="?page=hits-options&action=clearall">
                    
                    <input type="submit" value="Clear ALL Hit Counts" accesskey="p" tabindex="5" id="publish" class="button-primary" name="clearall">
                    </form>

<?php
} ?>