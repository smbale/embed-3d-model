<?php
/*
Plugin Name: Lao3D 3D Model Online
Plugin URI: http://wordpress.org/#
Description: This plugin helps you easily upload 3D Model to Lao3d.com and insert 3D Model in your posts or pages by text editor.
Author: wxstorm	
Licence:GPL 3
Version: 2.0
Author URI: http://www.lao3d.com
 */

require_once 'lao3d-common.php';

//----------------------------------------------------//
//ADD BUTTON TO TINY MCE                                                                                    
//----------------------------------------------------//

class LP_Personal_Buttons {
    function LP_Personal_Buttons(){
        if(is_admin()){
            if ( current_user_can('edit_posts') && current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
            {
                add_filter('tiny_mce_version', array(&$this, 'tiny_mce_version') );
                add_filter("mce_external_plugins", array(&$this, "mce_external_plugins"));
                add_filter('mce_buttons', array(&$this, 'mce_buttons'));
            }
        }
    }
    function mce_buttons($buttons) {
        array_push($buttons, "|", "lao3d_personal_edition" );
        return $buttons;
    }
    function mce_external_plugins($plugin_array) {
        $plugin_array['lao3d_personal_edition']  =  plugins_url('tiny-mce/editor-plugin.js', __FILE__);
        return $plugin_array;
    }
    function tiny_mce_version($version) {
        return ++$version;
    }
}

class Lao3d_Personal{
    function Lao3d_Personal(){
        add_shortcode('lao3dp', array(&$this, 'build_lao3d'));
        add_action('admin_menu', array(&$this,'lao3d_add_pages'));
        add_action('init', array(&$this,'LP_Buttons'));
        add_filter('plugin_action_links', array(&$this,'lao3d_plugin_action_links'), 10, 2);
    }
    
    //builds the 3d model.
    function build_lao3d($atts, $content = null) {
	    $opts = $this->lao3d_get_options();	
	    extract(shortcode_atts(array(
		    'width' 		=> $opts[LAO3D_PERSONAL_OPTION_WIDTH],
		    'height' 		=> $opts[LAO3D_PERSONAL_OPTION_HEIGHT],
            'src'    		=> '',
	    ), $atts));
        
        $output = '<iframe src="' . $src . '" width="' . $width . '" height="' . $height . '"  border="0" frameborder="no" marginwidth="0" marginheight="0"></iframe>';
        
	    return $output;
    }
    
    //----------------------------------------------------//
    //OPTIONS
    //----------------------------------------------------//
    function lao3d_get_options() {
        $lao3d_width = get_option(LAO3D_PERSONAL_OPTION_WIDTH, LAO3D_PERSONAL_DEFAULT_WIDTH);
        $lao3d_height = get_option(LAO3D_PERSONAL_OPTION_HEIGHT, LAO3D_PERSONAL_DEFAULT_HEIGHT);
        $lao3d_api = get_option(LAO3D_ENTERPRISE_OPTION_API, LAO3D_ENTERPRISE_DEFAULT_API);
        $lao3d_libname = get_option(LAO3D_ENTERPRISE_OPTION_LIBNAME, LAO3D_ENTERPRISE_DEFAULT_LIBNAME);
        $lao3d_priviledge = get_option(LAO3D_ENTERPRISE_OPTION_PRIVILEDGE, LAO3D_ENTERPRISE_DEFAULT_PRIVILEDGE);
              
        return array(
        LAO3D_PERSONAL_OPTION_WIDTH => $lao3d_width,
        LAO3D_PERSONAL_OPTION_HEIGHT => $lao3d_height,  
        LAO3D_ENTERPRISE_OPTION_API => $lao3d_api,  
        LAO3D_ENTERPRISE_OPTION_LIBNAME => $lao3d_libname,  
        LAO3D_ENTERPRISE_OPTION_PRIVILEDGE => $lao3d_priviledge,  
        );
    }


    // Options update page:
    function lao3d_add_pages() {
        // Add a new menu:
        add_menu_page('Lao3d设置', 'Lao3d设置', 8, 'Lao3dPersonalEdition', array(&$this, 'lao3d_options_page'), plugins_url('img/lao3d-personal-edition-icon.png', __FILE__));
    }
    
    // lao3d_options_page() displays the page content for the Options menu
    function lao3d_options_page() {
        if($_POST['action'] == 'update'){
            update_option(LAO3D_PERSONAL_OPTION_WIDTH, $_POST[LAO3D_PERSONAL_OPTION_WIDTH]);
            update_option(LAO3D_PERSONAL_OPTION_HEIGHT, $_POST[LAO3D_PERSONAL_OPTION_HEIGHT]);            		
            update_option(LAO3D_ENTERPRISE_OPTION_API, $_POST[LAO3D_ENTERPRISE_OPTION_API]);            		
            update_option(LAO3D_ENTERPRISE_OPTION_LIBNAME, $_POST[LAO3D_ENTERPRISE_OPTION_LIBNAME]);
            update_option(LAO3D_ENTERPRISE_OPTION_PRIVILEDGE, $_POST[LAO3D_ENTERPRISE_OPTION_PRIVILEDGE]);
		?><div class="updated"><p><strong><?php _e('保存成功.'); ?></strong></p></div><?php
        };

    ?>
	<div class='wrap'>	
		<h2>Lao3d设置</h2>
		<form method='post'>
			<?php wp_nonce_field('lao3dpersonal_options', 'lao3dpersonalsettings'); ?>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="<?php echo LAO3D_PERSONAL_OPTION_WIDTH , LAO3D_PERSONAL_OPTION_HEIGHT, LAO3D_ENTERPRISE_OPTION_API, LAO3D_ENTERPRISE_OPTION_LIBNAME, LAO3D_ENTERPRISE_OPTION_PRIVILEDGE;?>" />
			<table class="form-table">
				<tbody>
					<tr valign="top">
					<th scope="row"><?php _e("默认宽度（像素）"); ?></th>
						<td>
						    <input type="text" name="<?php echo LAO3D_PERSONAL_OPTION_WIDTH; ?>" value="<?php echo get_option(LAO3D_PERSONAL_OPTION_WIDTH, LAO3D_PERSONAL_DEFAULT_WIDTH);?>" />
						</td>
					</tr>
                    
                    <tr valign="top">
					<th scope="row"><?php _e("默认高度（像素）"); ?></th>
						<td>
						    <input type="text" name="<?php echo LAO3D_PERSONAL_OPTION_HEIGHT;?>" value="<?php echo get_option(LAO3D_PERSONAL_OPTION_HEIGHT, LAO3D_PERSONAL_DEFAULT_HEIGHT);?>" />
						</td>
					</tr>
                    
                    <tr valign="top">
					<th scope="row" colspan="2"><?php _e("以下为Lao3d企业版选项，可选填；如果不填，则不能使用模型上传功能。"); ?></th>
						<td>						    
						</td>
					</tr>
                    
                     <tr valign="top">
					<th scope="row"><?php _e("API Key"); ?></th>
						<td>
						    <input type="text" name="<?php echo LAO3D_ENTERPRISE_OPTION_API;?>" value="<?php echo get_option(LAO3D_ENTERPRISE_OPTION_API, LAO3D_ENTERPRISE_DEFAULT_API); ?>" />
						</td>
					</tr>
                    
                    <tr valign="top">
					<th scope="row"><?php _e("默认模型库名称"); ?></th>
						<td>
						    <input type="text" name="<?php echo LAO3D_ENTERPRISE_OPTION_LIBNAME;?>" value="<?php echo get_option(LAO3D_ENTERPRISE_OPTION_LIBNAME, LAO3D_ENTERPRISE_DEFAULT_LIBNAME);?>" />
						</td>
					</tr>
                    
                    <tr valign="top">
					<th scope="row"><?php _e("默认隐私"); ?></th>
						<td>
						    <select name="<?php echo LAO3D_ENTERPRISE_OPTION_PRIVILEDGE;?>" id="<?php echo LAO3D_ENTERPRISE_OPTION_PRIVILEDGE;?>">
                                <option value ="0">对所有人公开</option>
                                <option value ="1">仅对我自己公开</option>
                                <option value="2">仅对我关注的人公开</option>
                            </select>
                            <script type="text/javascript">
                                document.getElementById("<?php echo LAO3D_ENTERPRISE_OPTION_PRIVILEDGE;?>").value = <?php echo get_option(LAO3D_ENTERPRISE_OPTION_PRIVILEDGE, LAO3D_ENTERPRISE_DEFAULT_PRIVILEDGE);?>
                            </script>
						</td>
					</tr>
				</tbody>
			</table>
			
			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('保存') ?>" />
			</p>
		</form>
	</div>
<?php
    }
    
    //settings
    function lao3d_plugin_action_links($links, $file) {
        static $this_plugin;

        if (!$this_plugin) {
            $this_plugin = plugin_basename(__FILE__);
        }

        if ($file == $this_plugin) {            
            $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=Lao3dPersonalEdition">Settings</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }
    
    function LP_Buttons(){
        global $LP_Personal_Buttons;
        $LP_Personal_Buttons = new LP_Personal_Buttons();
    }
}

new Lao3d_Personal();

?>