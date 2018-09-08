<?php
require_once('../config.php');
require_once('./config.php');

if (!defined('WICHAT_WEB_ROOT'))
{
    //Fatal Error
    exit(0);
}
require_once(WICHAT_WEB_ROOT.'/include/lib/function_base.php');


// Parse request parameters
$params = array();
if (array_key_exists('action',$_REQUEST))
    $action = $_REQUEST['action'];
else
    $action = '';
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST')
{
    foreach ($_POST as $key=>$val)
    {
        if ($key != 'action')
        {
            $params[$key] = $val;
        }
    }
}
else
{
    $action = 'invalid';
}

// Check if authentication is required
if (REGISTRATION_NEED_INVITATION)
{
    session_start();
    if(isset($_SESSION['authorized']) && $_SESSION['authorized']==true)
        $action='register_normal';
    else
        $action='show_auth';
}

// Dispatch request according to $action
$view='';
switch ($action)
{
    case 'register_normal':
        require_once('register_normal.php');
        if (register($params))
        {
            require_once('auth_code.php');
            auth_destroy($params);
            $view='register_ok';
        }
        break;
    case 'show_auth':
        require_once('auth_code.php');
        if (!authentication($params))
            $view='show_auth';
        else
            $view='register_normal';
        break;
    default:;
}

// Output view according to $view
// If $view is empty, use $action as $view
if ($view=='') $view=$action;
loadUI($view);
header("Content-type: text/html; charset=utf-8");
require_once(WICHAT_WEB_ROOT.'/include/templates/header.html');
switch($view)
{
    case 'register_normal':
        require_once(WICHAT_WEB_ROOT.'/include/templates/register.html');
        break;
    case 'register_ok':
        require_once(WICHAT_WEB_ROOT.'/include/templates/register_ok.html');
        break;
    case 'show_auth':
        require_once(WICHAT_WEB_ROOT.'/include/templates/auth_code.html');
        break;
    default:;
}
require_once(WICHAT_WEB_ROOT.'/include/templates/footer.html');

exit(0);
?>
