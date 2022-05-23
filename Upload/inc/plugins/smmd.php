<?php
/**
 * Plugin Title: Simple Mode Must Die!
 *
 * Plugin Description: Disables or re-enables simple mode.
 *
 * MyBB Version: 1.8
 *
 * Plugin Version: 1.1
 *
 */
 
// No direct initialization

if( !defined('IN_MYBB') )
{
    die('Direct initialization of this file is not allowed.');
}
 
/**
 * Plugin information
 *
 * @return void
 */

function smmd_info()
{

    return array(
        'name'          => 'Simple Mode Must Die!',
        'description'   => 'Disables or re-enables simple mode',
        'website'       => 'https://github.com/vintagedaddyo',
        'author'        => 'Wildcard & Vintagedaddyo',
        'authorsite'    => 'https://github.com/vintagedaddyo',
        'version'       => '1.1',
        'compatibility' => '18*',
        'codename'      => 'simplemustdie'
    );    
    
}

if (defined('IN_ADMINCP')) {

    $plugins->add_hook('admin_style_themes_begin', 'smmd_admin_themes_begin');

}

/**
 * Plugin installation
 */
 
function smmd_install()
{

    global $db, $mybb;

    $smmd_settinggroup = array(
        'name'           => 'smmd_settinggroup',
        'title'          => 'Simple Mode Must Die! Plugin Settings', 
        'description'    => 'This plugin disables or re-enables simple mode theme editor',
        'disporder'      => '1',
        'isdefault'      => '0'
        );
        
    $group['gid'] = $db->insert_query('settinggroups', $smmd_settinggroup);

    $gid = $db->insert_id();
    
    $enable_smmd = array(
        'name'           => 'enable_smmd',
        'title'          => 'Enable smmd?', 
        'description'    => 'Do you want the Simple Mode Must Die! feature of the active plugin turned on or off?',
        'optionscode'    => 'yesno',
        'value'          => '1',
        'disporder'      => '2',
        'gid'            => intval($gid)
        );
        
    $db->insert_query('settings', $enable_smmd);

    rebuild_settings();

}

function smmd_is_installed() { 

  global $mybb; 

  if(isset($mybb->settings['enable_smmd'])) 
   { 
   
    return true; 

   } 
   
    return false;
}

/**
 * Plugin activation
 * Activate via plugin listing page
 */
 
function smmd_activate()
{
    
    global $db, $mybb;
            
    if ($mybb->input['action'] == "edit_stylesheet" && (!isset($mybb->input['mode']) || $mybb->input['mode'] == "simple"))
     {
        
      $mybb->input['mode'] = 'advanced';
      
     }
 
}

/**
 * Plugin de-activation
 * Deactivate via plugin listing page
 */
 
function smmd_deactivate()
{
        
    global $db, $mybb;
        
    if ($mybb->input['action'] == "edit_stylesheet" && (!isset($mybb->input['mode']) || $mybb->input['mode'] == "advanced"))
     {
        
      $mybb->input['mode'] = 'simple';
      
     }
 
}

/**
 * Plugin uninstall
 */ 
 
function smmd_uninstall()
{

     global $db;

     $db->delete_query('settings', "name IN ('enable_smmd')");
     $db->delete_query('settinggroups', "name = 'smmd_settinggroup'");

     rebuild_settings();

}

/**
 * Disable / re-enable simple mode style sheet editing
 * Disable / re-enable via configuration listing
 * @return void
 */
 
function smmd_admin_themes_begin()
{

    global $db, $mybb;

    // 167 / 179 sort warning
    $enable_smmd = isset($enable_smmd) ? $enable_smmd : '';    
    
    if ($mybb->settings['enable_smmd'] == 1)
    {
        
      if ($mybb->input['action'] == "edit_stylesheet" && (!isset($mybb->input['mode']) || $mybb->input['mode'] == "simple"))
      {
        
        $mybb->input['mode'] = 'advanced';
      
      }
      
    }

    if ($mybb->settings['enable_smmd'] == 0)
    {
        
      if ($mybb->input['action'] == "edit_stylesheet" && (!isset($mybb->input['mode']) || $mybb->input['mode'] == "advanced"))
      {
        
        $mybb->input['mode'] = 'simple';
      
      }
      
    }

}

?>
