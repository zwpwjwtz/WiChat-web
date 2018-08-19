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
    if (!checkEmail($email) || strlen($email) > WEB_REGISTER_EMAIL_MAXLEN)
    {
        setSysMsg('result','Email address is invalid. Please try again.');
        return false;
    }
    if (!checkPhone($phone) || strlen($phone) > WEB_REGISTER_PHONE_MAXLEN)
    {
        setSysMsg('result','Phone number is invalid. Please try again.');
        return false;
    }
    if (strlen($authQuestion) > 0 || strlen($authAnswer) > 0)
    {
        if (strlen($authQuestion) <= 0 || strlen($authQuestion) > WEB_REGISTER_AUTHQUESTION_MAXLEN)
        {
            setSysMsg('result','The length of authentication question must be 1 ~ 30.');
            return false;
        }
        if (strlen($authAnswer) <= 0 || strlen($authAnswer) > WEB_REGISTER_AUTHANSWER_MAXLEN)
        {
            setSysMsg('result','The length of answer of the authentication question must be 1 ~ 30.');
            return false;
        }
    }
    if ($agreement != 'true')
    {
        setSysMsg('result','Please read the General Terms of Use and Disclaimer, then agree with them by clicking the checkbox.');
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
            require_once(WICHAT_WEB_ROOT.'/include/lib/db.php');
            $record=new regRecord();
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
