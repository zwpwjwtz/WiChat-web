<?php
/**
 * Project: NJUOPEN/Portal-BBS
 * Contributor:WTZ
 * Filename: function_UI.php
 */

function loadUI($view) // Load CSS/Javascript files
{
	global $cssList,$jsList;
	__add_to_list($cssList,'/css/general.css');
	switch($view)
	{
		case 'index': // Index page
			__add_to_list($cssList,'/css/index.css');
			break;
		case 'userinfo': // User information page
			__add_to_list($cssList,'/css/userinfo.css');
			break;
		case 'show_auth': // Authentication page
			__add_to_list($cssList,'/css/auth.css');
			break;
		case 'register_normal': // Registration page
		case 'register_ok': // Registration result page
			__add_to_list($cssList,'/css/register.css');
			break;
		default:
		
	}
}

function setSysMsg($item,$value) // Show system messages to user
{
	global $sysMsg;
	$sysMsg[$item]=$value;
}

// Resource lists used by frontend
$cssList=array();
$jsList=array();
$sysMsg=array();
?>
