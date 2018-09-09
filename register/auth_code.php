<?php
function authentication($params)
{
    initLocale();
    if (isset($params['code']))
    {
        $code=$params['code'];
        if (strlen($code)!=WEB_INVITATION_CODE_LEN)
        {
            setSysMsg('result',gettext('Code format is incorrect. Please try again.'));
            return false;
        }

        require_once(WICHAT_WEB_ROOT.'/include/lib/db.php');        
        $db=new inviteDB(INVITATION_LIST);
        if (!$db->OK) return false;
        if (!$db->existRecord($code))
        {
            setSysMsg('result',gettext('This code does not exist.'));
            return false;
        }
        $record=$db->getRecord($code);
        
        switch ($record->state)
        {
            case WEB_INVITATION_STATE_NONE:
            case WEB_INVITATION_STATE_EXPIRED:
                setSysMsg('result',gettext('Sorry, this code has expired.'));
                return false;
            case WEB_INVITATION_STATE_ACTIVE:
                if (timeDiff(TIME_REG,$record->creationTime)/86400>$record->validity)
                    $record->state=WEB_INVITATION_STATE_EXPIRED;
                else
                {
                    session_start();
                    $_SESSION['authorized']=true;
                    $record->state=WEB_INVITATION_STATE_USED;
                }
                $db->setRecord($record);
                if ($record->state==WEB_INVITATION_STATE_EXPIRED)
                {
                    setSysMsg('result',gettext('Sorry, this code has expired.'));
                    return false;
                }
                else
                    return true;
            case WEB_INVITATION_STATE_USED:
                setSysMsg('result',gettext('Sorry, this code has already been used.'));
                return false;
            default:
                return false;
        }
    }
    else
        return false;
}
function auth_destroy($params)
{
    unset($_SESSION['authorized']);
    if (!isset($params['code'])) return false;
    $code=$params['code'];
    if (isset($params['id'])) $ID=$params['id']; else $ID='';

    initLocale();
    require_once(WICHAT_WEB_ROOT.'/include/lib/db.php');        
    $db=new inviteDB(INVITATION_LIST);
    if (!$db->OK) return false;
    if (!$db->existRecord($code))
    {
        setSysMsg('result',gettext('The invitation code is invalid.'));
        return false;
    }
    $record=$db->getRecord($code);
    
    if ($ID=='')
    {
        // Make this invitation code unable to be used again
        $record->state=WEB_INVITATION_STATE_NONE;
        $db->setRecord($record);
        return true;
    }
    else if ($record->state!=WEB_INVITATION_STATE_USED || $record->regID!='')
    {
        setSysMsg('result',gettext('The invitation code is invalid.'));
        return false;
    }
    else
    {
        $record->regID=$ID;
        $record->regTime=gmdate(TIME_FORMAT);
        $db->setRecord($record);
        return true;
    }
}
?>
