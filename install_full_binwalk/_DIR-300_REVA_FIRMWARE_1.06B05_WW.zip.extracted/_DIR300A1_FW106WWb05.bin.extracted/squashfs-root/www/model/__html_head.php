<?
/* vi: set sw=4 ts=4: */
require("/www/model/__auth_check.php");
?><html>
<head>
<link rel="stylesheet" href="/model/router.css" type="text/css">
<meta http-equiv=Content-Type content="no-cache">
<meta http-equiv=Content-Type content="text/html; charset=<?=$CHARSET?>">
<?=$OTHER_META?>
<title><?=$TITLE?></title>
<?
$supportWidget = query("/runtime/func/widget/yahoo");
if($supportWidget=="1")
{
	require("/www/widget/__js_widget.php");
}	
require("/www/comm/__js_comm.php"); 
if ($NO_SESSION_TIMEOUT!="1")	{ require("/www/auth/__session_timeout.php"); }
if ($NO_BUTTON!="1")			{ require("/www/model/__button.php"); }
?>
</head>

