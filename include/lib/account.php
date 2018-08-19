<?php
define('REGEX_ACCOUNT_PHONE','/^(?:(?:\+?(\d+)-?)|(?:\(\d+\)))(\d+)$/');

function checkID($str)
{
    if (!$str) return false;
    $l=strlen($str);
    if ($l<5 || $l>7) return false;
    if ($str[0]=='0') return false;
    for($i=0;$i<$l;$i++)
    {
        $ch=$str[$i];
        if ($ch<'0' || $ch>'9') return false;
    }
    if ($str=='10000') return false;
    return true;
}

function checkPassword($str)
{
    if (!$str) return false;
    $l=strlen($str);
    if ($l<6 || $l>16) return false;
    return true;
}

function checkEmail($str)
{
    return filter_var($str, FILTER_VALIDATE_EMAIL);
}

function checkPhone($str)
{
    return preg_match(REGEX_ACCOUNT_PHONE,preg_replace('/\s+/', '', $str));
}

function formatID($str,$len)
{
    return $str.str_repeat("\0", $len - strlen($str));
}
?>
