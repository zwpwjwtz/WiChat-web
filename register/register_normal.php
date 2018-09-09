<?php
function register($params)
{
    $ID=$params['id'];
    $password=$params['password'];
    $repassword=$params['repassword'];
    $email=$params['email'];
    $phone=$params['phone'];
    $authQuestion=$params['authQuestion'];
    $authAnswer=$params['authAnswer'];
    $agreement=$params['agreement'];
    
    initLocale();
    require_once(WICHAT_WEB_ROOT.'/include/lib/account.php');
    if (!checkID($ID))
    {
        setSysMsg('result',gettext('ID format is incorrect. Please try again.'));
        return false;
    }
    if (!checkPassword($password))
    {
        setSysMsg('result',gettext('Password format is incorrect. Please try again.'));
        return false;
    }
    if ($password != $repassword)
    {
        setSysMsg('result',gettext('Password do not match.'));
        return false;
    }
    if (!checkEmail($email) || strlen($email) > WEB_REGISTER_EMAIL_MAXLEN)
    {
        setSysMsg('result',gettext('Email address is invalid. Please try again.'));
        return false;
    }
    if (!checkPhone($phone) || strlen($phone) > WEB_REGISTER_PHONE_MAXLEN)
    {
        setSysMsg('result',gettext('Phone number is invalid. Please try again.'));
        return false;
    }
    if (strlen($authQuestion) > 0 || strlen($authAnswer) > 0)
    {
        if (strlen($authQuestion) <= 0 || strlen($authQuestion) > WEB_REGISTER_AUTHQUESTION_MAXLEN)
        {
            setSysMsg('result',gettext('The length of authentication question must be 1 ~ 30.'));
            return false;
        }
        if (strlen($authAnswer) <= 0 || strlen($authAnswer) > WEB_REGISTER_AUTHANSWER_MAXLEN)
        {
            setSysMsg('result',gettext('The length of answer of the authentication question must be 1 ~ 30.'));
            return false;
        }
    }
    if ($agreement != 'true')
    {
        setSysMsg('result',gettext('Please read the General Terms of Use and Disclaimer, then agree with them by clicking the checkbox.'));
        return false;
    }
    
    // Check passed.
    require_once(WICHAT_WEB_ROOT.'/include/lib/enc.php');
    $password=substr(sha256sum($password),0,ACCOUNT_KEY_RAW_LEN);
    
    require_once(WICHAT_WEB_ROOT.'/scomm/query.php');
    $content=queryAccountServer(ACCOUNT_SCOMM_ACTION_CREATE_ACCOUNT,formatID($ID,ACCOUNT_ID_MAXLEN).$password);
    if (strlen($content) < 2)
        setSysMsg('result',gettext('Server error. Please try again later.'));
    else
    switch (ord($content[0]))
    {
        case RESPONSE_SUCCESS:
            require_once(WICHAT_WEB_ROOT.'/include/lib/db.php');
            $account=new regRecord();
            $account->ID=$ID;
            $account->regIP=getClientIP();
            $account->regMethod=WEB_REGISTER_METHOD_NORMAL;
            $account->state=WEB_REGISTER_ACCOUNT_ACTIVE;
            $account->email=$email;
            $account->phone=$phone;
            $account->authQuestion=$authQuestion;
            $account->authAnswer=$authAnswer;
            $db=new regDB(REGISTER_LIST);
            $db->setRecord($account);
            return true;
        case RESPONSE_FAILED:
            if (ord($content[1]) == RESPONSE_ACCOUNT_ID_EXISTS)
                setSysMsg('result',gettext('This ID already exists. Please choose another one.'));
            else
            {
                setSysMsg('result',gettext('Registration failed.'));
                setSysMsg('help',gettext('Please contact website administrator for help.'));
            }
            break;
        default:
            setSysMsg('result',gettext('Server error. Please try again later.'));
    }
    return false;
}
?>
