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

// Dispatch request according to $action
$view='';
switch ($action)
{
    case 'register_normal':
        require_once('./register_normal.php');
        register($params);
        require_once('./index.php');
        break;
    default:;
}

// Output view according to $view
// If $view is empty, use $action as $view
header("Content-type: text/html; charset=utf-8");
require_once(WICHAT_WEB_ROOT.'/include/templates/header.html');
if ($view=='') $view=$action;
switch($view)
{
    case 'register_normal':
    default:
        require_once(WICHAT_WEB_ROOT.'/include/templates/register.html');
}
require_once(WICHAT_WEB_ROOT.'/include/templates/footer.html');

exit(0);
?>
