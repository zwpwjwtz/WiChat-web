<?php
if (!defined('WICHAT_WEB_ROOT')) exit(0);
define('TIME_REG','/^([0-9]{4})\/([0-1][0-9])\/([0-3][0-9]),([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/');

/**
 * Project: NJUOPEN/Portal-BBS
 * Contributor:WTZ
 * Filename: function_base.php
 */

function __add_to_list(&$list,$item)
{
	if (!in_array($item,$list)) array_push($list,$item);
}

/*
	功能：
		生成分页页码
	参数：
		$current:当前页码
		$total:总页码
		$limit:允许显示的页码数
		$ellipsis:(可选)用于显示省略页的记号
	返回值：
		包含所有页码的数组（string型）
*/
function pagination($current,$total,$limit,$ellipsis='...')
{
	$left=$current-floor($limit/2);
	if ($left<1) $left=1;
	$right=$current+ceil($limit/2);
	if ($right>$total) $right=$total;
	
	$list=array();
	if ($left>1) array_push($list,$ellipsis);
	for($i=$left;$i<=$right;$i++)
	{
		array_push($list,(string)$i);
	}
	if ($right<$total) array_push($list,$ellipsis);
	return $list;	
}

/*
	功能：
		判断数值是否为自然数，并返回有效值（用于解析传入的$params）
	参数：
		$var:待分析值
		$default:当$var无效时采用的默认值
	返回值：
		有效的自然数（int型）
*/
function getNatureNumber(&$var,$default)
{
	if (isset($var) && $var!=NULL)
		if ((int)$var < 1)	return $default; else return (int)$var;
	else
		return $default;	
}

/**
 * Returns XSS-safe equivalent of string
 * @param mixed $data
 * From phpsec project, by the OWASP foundation
 */
function xss_safe($data)
{
    if (func_num_args()>1)
    {
        $args=func_get_args();
        $out=array();
        foreach ($args as $arg)
            $out[]=xss_safe ($arg);
        return implode("",$out);
    }
    if (defined("ENT_HTML401"))
        $t=htmlspecialchars($data,ENT_QUOTES | ENT_HTML401,"UTF-8");
    else
        $t=htmlspecialchars($data,ENT_QUOTES,"UTF-8");

    return $t;
}

/**   
 * PHP去掉特定的html标签
 * @param string $str
 * @param array $tagsArr
 */  
function strip_tag_array($str,$tagsArr) {   
    foreach ($tagsArr as $tag)
    {
        $reg[]='/(<(?:\/'.$tag.'|'.$tag.')[^>]*>)/i';  
    }
    return preg_replace($reg,'',$str);
}

/**
 * Various library functions
 * From Wichat-server project, by zwpwjwtz
 */
function intToBytes($value,$length)
{
	$temp='';
	for ($i=0;$i<$length;$i++)
	{
		$temp.=chr($value & 0xFF);
		$value>>=8;
	}
	return $temp;
}
function bytesToInt($value,$length)
{
	$temp=0;
	if (strlen($value)<$length) $length=strlen($value);
	for ($i=0;$i<$length;$i++)
		$temp+=ord($value[$i])<<($i*8);
	return $temp;
}
function stringsToInts($value)  // Return: Array of Integers
{
	if ($value==NULL) return NULL;
	else if (count($value)==1) return (int)$value;
	else
	{
		$temp=array();
		foreach ($value as $item) array_push($temp,(int)$item);
		return $temp;
	}
}
/**
 * Return the difference (in second) between two time string
 * The time string is parsed according to the format string
 * @param string $format
 * @param string $time1
 * @param string $time2
 */
function timeDiff($format,$time1,$time2='') // Return: Long
{
	date_default_timezone_set('UTC');
	if (!(preg_match($format,$time1,$timeArray1)==1 && ($timeArray1=stringsToInts($timeArray1))!=NULL && checkdate($timeArray1[2],$timeArray1[3],$timeArray1[1]))) return 0;
	if ($time2=='')	return time()-mktime($timeArray1[4],$timeArray1[5],$timeArray1[6],$timeArray1[2],$timeArray1[3],$timeArray1[1]);
	else
	{
		if (!(preg_match($format,$time2,$timeArray2)==1 && ($timeArray2=stringsToInts($timeArray2))!=NULL && checkdate($timeArray2[2],$timeArray2[3],$timeArray2[1]))) return 0;
		else return mktime($timeArray1[4],$timeArray1[5],$timeArray1[6],$timeArray1[2],$timeArray1[3],$timeArray1[1])-mktime($timeArray2[4],$timeArray2[5],$timeArray2[6],$timeArray2[2],$timeArray2[3],$timeArray2[1]);
	}
}

/**
 * Fix the length of a string, padding it when necessary
 * @param string $string
 * @param int $length
 */
function str_fix(&$string,$length)
{
	if (strlen($string) > $length)
		$string=substr($string,0,$length);
	else
		$string=str_pad($string,$length,"\0");
}

function getClientIP()
{
	$addr='';
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
	if (empty($addr))
		$addr = $_SERVER['REMOTE_ADDR'];
	return $addr;
}

require_once(WICHAT_WEB_ROOT.'/include/lib/function_UI.php');
?>
