<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'pagebuilder';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// ADMINAREA laden
$route['admin'] = "admin/pagebuilder/index";

// ADMIN AREA - AJAX ROUTES
$route['admin/Load_contentmodule'] = "admin/pagebuilder/get_view_ajax";
$route['admin/Load_contentmodulelist'] = "admin/pagebuilder/get_modulelist_ajax";
$route['admin/Load_moduleeditform'] = "admin/pagebuilder/get_moduleform_ajax";
$route['admin/Load_moduleadminform'] = "admin/pagebuilder/get_adminmoduleform_ajax";
$route['admin/Load_editorfunctions'] = "admin/pagebuilder/get_admineditorfunctions_ajax";
$route['admin/Load_folder'] = "admin/pagebuilder/get_folderlist_ajax";
$route['admin/Load_inserticons'] = "admin/pagebuilder/get_icons";
$route['admin/Load_teaseredit'] = "admin/pagebuilder/get_teaseredit";
$route['admin/Load_imageupload'] = "admin/pagebuilder/get_imageupload_ajax";
$route['admin/Load_checkform'] = "admin/pagebuilder/checkform_ajax";
$route['admin/imageupload'] = "admin/pagebuilder/imageupload_ajax";
$route['admin/save_modulesettings'] = "admin/pagebuilder/save_module_settings";

// Frontend STARTSEITE laden
$route['(:any)/(:any)'] = "pagebuilder/index";

require_once( BASEPATH .'database/DB.php');
$db =& DB();
$query = $db->get( 'pages' );
$result = $query->result();
foreach( $result as $row )
{
    
    $expectedvar_route = '';
    $expectedvar_controller = '';

    if($row->expected_var>0) {
        // --- EXPECTED VARS FOR DETAIL VIEW
    	for($i=0; $i<$row->expected_var; $i++) {
    		$expectedvar_route = $expectedvar_route.'/(:any)';
    		$expectedvar_controller = $expectedvar_controller.'/$'.($i+3);
    	}

        // --- DEFAULT VIEW WITH EXPECTED VARS
        if($row->expected_var_default>0) {
            
            $expectedvar_route_default = '';
            $expectedvar_controller_default = '';
            
            for($i=0; $i<$row->expected_var_default; $i++) {
                $expectedvar_route_default = $expectedvar_route_default.'/(:any)';
                $expectedvar_controller_default = $expectedvar_controller_default.'/$'.($i+3);
            }

            $index_route_default = '(:any)/(:any)/'.str_replace(" ", "_", $row->path).$expectedvar_route_default;
            $index_controller_default = 'pagebuilder/index/'.str_replace(" ", "_", $row->page_name).$expectedvar_controller_default;
            $route[$index_route_default] = $index_controller_default;            
        }

        // --- DEFAULT PATH WITHOUT EXPECTED VARS
        $index_route = '(:any)/(:any)/'.str_replace(" ", "_", $row->path);
        $index_controller = 'pagebuilder/index/'.str_replace(" ", "_", $row->page_name);
        $route[$index_route] = $index_controller;
    }
    
    $index_route = '(:any)/(:any)/'.str_replace(" ", "_", $row->path).$expectedvar_route;
    $index_controller = 'pagebuilder/index/'.str_replace(" ", "_", $row->page_name).$expectedvar_controller;
    $index_route.'<br>'.$index_controller.'<br><br>';

    $route[$index_route] = $index_controller;
}



