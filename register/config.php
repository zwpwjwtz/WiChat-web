<?php
define('WEB_AUTH_METHOD_INVITATION_CODE',0);

define('WEB_REGISTER_METHOD_NONE',0);
define('WEB_REGISTER_METHOD_NORMAL',1);

define('WEB_REGISTER_ACCOUNT_INACTIVE',0);
define('WEB_REGISTER_ACCOUNT_ACTIVE',1);

define('WEB_REGISTER_EMAIL_MAXLEN',48);
define('WEB_REGISTER_PHONE_MAXLEN',32);
define('WEB_REGISTER_AUTHQUESTION_MAXLEN',32);
define('WEB_REGISTER_AUTHANSWER_MAXLEN',32);

define('WEB_INVITATION_CODE_LEN',8);

define('WEB_INVITATION_TYPE_NORMAL',0);

define('WEB_INVITATION_STATE_NONE',0);
define('WEB_INVITATION_STATE_ACTIVE',1);
define('WEB_INVITATION_STATE_USED',2);
define('WEB_INVITATION_STATE_EXPIRED',3);
?>
