<?php
//error_reporting(0);
define('SERVER_IN_MAINTANANCE',false);

define('ACCOUNT_ID_MAXLEN',8);
define('ACCOUNT_KEY_LEN',16);
define('ACCOUNT_KEY_RAW_LEN',32);

define('TIME_FORMAT','Y/m/d,H:i:s');
define('TIME_LEN',19);
define('TIME_ZERO','0001/01/01,00:00:00');

define('SERVER_ID',1);
define('SERVER_ADMIN_NAME','');
define('SERVER_ADMIN_EMAIL','');
define('SERVER_OPERATOR_NAME','');
define('SERVER_OPERATOR_URL','');

define('REGISTRATION_NEED_INVITATION',true);
define('REGISTRATION_NEED_VERIFICATION',false);

define('WICHAT_WEB_ROOT',dirname(__FILE__));
define('WICHAT_WEB_WEB_ROOT','127.0.0.1/web');
define('WICHAT_WEB_WEB_TEMPLATE',WICHAT_WEB_WEB_ROOT.'/include/templates');

define('WICHAT_WEB_DB_DIR',WICHAT_WEB_ROOT.'/db');
define('REGISTER_LIST',WICHAT_WEB_DB_DIR.'/reg.dat');
define('INVITATION_LIST',WICHAT_WEB_DB_DIR.'/invitation.dat');
?>
