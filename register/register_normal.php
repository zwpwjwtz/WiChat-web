<?php
function register($params)
{
    $ID=$params['id'];
    $password=$params['password'];
    $repassword=$params['repassword'];
    
    require_once(WICHAT_WEB_ROOT.'/include/lib/account.php');
    if (!checkID($ID))
    {
        setSysMsg('result','ID format is incorrect. Please try again.');
        return false;
    }
    if (!checkPassword($password))
    {
        setSysMsg('result','Password format is incorrect. Please try again.');
        return false;
    }
    if ($password != $repassword)
    {
        setSysMsg('result','Password do not match.');
        return false;
    }
    
    // Check passed.
    require_once(WICHAT_WEB_ROOT.'/include/lib/enc.php');
    $password=substr(sha256sum($password),0,ACCOUNT_KEY_RAW_LEN);
    
    require_once(WICHAT_WEB_ROOT.'/scomm/query.php');
    $content=queryAccountServer(ACCOUNT_SCOMM_ACTION_CREATE_ACCOUNT,formatID($ID,ACCOUNT_ID_MAXLEN).$password);
    if (strlen($content) < 2)
        setSysMsg('result','Server error. Please try again later.');
    else
    switch (ord($content[0]))
    {
        case RESPONSE_SUCCESS:
            setSysMsg('result','User Registered.');
            setSysMsg('help','Please log in with your account ID <b>'.$ID.'</b> and password.');
            break;
        case RESPONSE_FAILED:
            if (ord($content[1]) == RESPONSE_ACCOUNT_ID_EXISTS)
                setSysMsg('result','This ID already exists. Please choose another one.');
            else
            {
                setSysMsg('result','Registration failed.');
                setSysMsg('help','Please contact website administrator for help.');
            }
            break;
        default:
            setSysMsg('result','Server error. Please try again later.');
    }
    return true;
}
?>
