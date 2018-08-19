<?php include_once('../config.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>General Terms of Use</title>
</head>

<body bgcolor="#F5F5F5">
<div style="background:#8AE6F9; height:50px; width:100%; margin:auto; padding:2px 0px;">
<p align="center"><font size="+1"><b>General Terms of Use for WiChat Service</b></font></p>
</div>
<div style="height:auto;border-style:solid; border-color:#000000; padding:0px 20px; border-width:thin;">
<p>1. For traffic statistics and account management, the software running at <a href="http://<?php echo WICHAT_WEB_WEB_ROOT;?>"><?php echo WICHAT_WEB_WEB_ROOT;?></a>(hereinafter referred to as "this site") will record any IP source from which an HTTP request is sent to get digital resources (image, audio, plain text, etc.). The user who initiate these HTTP requests is hereinafter referred to as "you". The person responsible for the installation and maintenance of this site (<a href="mailto:<?php echo SERVER_ADMIN_EMAIL;?>"><?php echo SERVER_ADMIN_NAME;?></a>) (hereinafter referred to as "the webmaster") is responsible for all legal affairs concerning this site. 
<p>The data collected and stored by this site includes:</p>
<ul>
<li>    IP address: HTTP_VIA. HTTP_X_FORWARDED_FOR in HTTP header(given by PHP system), or REMOTE_ADDR when both above are unavailable;</li>
<li>    Access time: year, month, day, hour, minute, second of GMT format (in server time)；</li>
<li>    Email address；</li>
<li>    Phone number；</li>
</ul>
<p>2. The data recorded by this site is used only for traffic statistics and account management; besides, it will not be used for any commercial or private purposes. This site will only make it available to public when it is considering public safety under the permission of local government;</p>
<p>3. The access data of visitors to this site will be kept strictly confidential; however, if this site encounters irresistible force (such as hacking, natural disasters, war, etc.), and that cause the loss or leakage of the data, the webmaster and the network operator (<a href="<?php echo SERVER_OPERATOR_URL;?>"><?php echo SERVER_OPERATOR_NAME;?></a>) do not assume corresponding responsibility;</p>
<p>4. By default, since you visit this site, you have read and are awared of the content of this General Terms of Use. If you still have any objection, please contact the webmaster (<a href="mailto:<?php echo SERVER_ADMIN_EMAIL;?>"><?php echo SERVER_ADMIN_EMAIL;?></a>) in order to ensure the security of your information.</p>
</div>
</body>
</html>