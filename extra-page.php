<?php
/*
Plugin Name: Extra Page
Plugin URI: 
Description:
Author: Clement Yuan
Version: 1.0.0
Author URI:
*/

/*
	License

    Extra Page
    Copyright (C) 2010 Clement Yuan <info@iamclement.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

add_action('admin_menu', 'admin_extra_page_plugin');
add_action('admin_menu', 'admin_extra_page_output');

//Call Function

function admin_extra_page_output() {
	$admin_page_content = get_site_option('admin_page_content');
	$admin_page_name = get_site_option('admin_page_name');
	if (  !empty($admin_page_content) &&  !empty($admin_page_name)  ){
		add_submenu_page('index.php', $admin_page_name, $admin_page_name, 8, 'extra-page', 'admin_ads_output');
	}
}

function admin_ads_output() {
	$admin_page_content = get_site_option('admin_page_content');
	if ( !empty($admin_page_content) && $admin_page_content != 'empty' ){
		echo stripslashes($admin_page_content);
	}
}

function admin_extra_page_plugin() {
	global $wpdb, $wp_roles, $current_user;
	if ( is_site_admin() ) {
		add_submenu_page('wpmu-admin.php', 'Extra Page', 'Extra Page', 10, 'extra-page', 'extra_page_setting');
	}
}

//Page Function

function extra_page_setting() {
	global $wpdb, $wp_roles, $current_user;

	if (isset($_GET['set'])) {
		?><div id="message" class="updated fade"><p><?php _e('' . urldecode($_GET['message']) . '') ?></p></div><?php
	}
	
	echo '<div class="wrap">';
	
	switch($_GET[ 'action'] ) {

		default:
			
			$admin_page_name = get_site_option('admin_page_name');
			$admin_page_content = get_site_option('admin_page_content');
			
			?>
			<h2>Extra Page</h2>
            <form method="post" action="wpmu-admin.php?page=extra-page&action=update">
            <table class="form-table">
            <tr>
            <th>Page Name</th>
            <td>
            <input name="admin_page_name" type="text" id="admin_page_name" style="width: 20%" value="<?php echo $admin_page_name; ?>" />
            </td>
            </tr>
            <tr><th>Page Content</th>
			<td><textarea name="admin_page_content" type="text" rows="20" wrap="soft" id="admin_page_content" style="width: 100%"/><?php echo $admin_page_content; ?></textarea>
            <br />HTML Supported.</td>
            </tr>
            </table>
            
            <p class="submit">
            <input type="submit" name="Submit" value="Save Changes" />
			<input type="submit" name="Reset" value="Reset" />
            </p>
            </form>
			<?php
		break;

		case "update":
			if ( isset( $_POST['Reset'] ) ) {
				
				update_site_option( "admin_page_content", "" );
				update_site_option( "admin_page_name", "" );
				notice_message('Settings cleared.');
				
			}elseif( strlen( $_POST['admin_page_name'] ) > 15 ){
				notice_message('Error! Maximun 15 Characters.');
			}else{
				
				$admin_page_name = $_POST['admin_page_name'];
				$admin_page_content = $_POST['admin_page_content'];

				if( $admin_page_content == '' && $admin_page_name == ''  ){
					
					update_site_option( "admin_page_name", $admin_page_name );
					update_site_option( "admin_page_content", stripslashes($admin_page_content ) );
					notice_message('Page name and content is empty. Plugin Disabled.');

				}elseif( $admin_page_name == '' ){
					
					update_site_option( "admin_page_name", $admin_page_name );
					update_site_option( "admin_page_content", stripslashes($admin_page_content) );
					notice_message('Page name is empty. Plugin Disabled.');
				
				}elseif( $admin_page_content == '' ){
					
					update_site_option( "admin_page_name", $admin_page_name );
					update_site_option( "admin_page_content", stripslashes($admin_page_content) );
					notice_message('Page content is empty. Plugin Disabled.');
								
				}else{
				
					update_site_option( "admin_page_name", $admin_page_name );
					update_site_option( "admin_page_content", stripslashes($admin_page_content) );
					notice_message('Settings saved.');

				}
			}
		break;

		case "temp":
		break;

	}
	echo '</div>';
}

function notice_message( $message ){
	echo "<script language='javascript'>
	window.location='wpmu-admin.php?page=extra-page&set=true&message=" . urlencode(__($message)) . "';
	</script>
	";
}

?>
