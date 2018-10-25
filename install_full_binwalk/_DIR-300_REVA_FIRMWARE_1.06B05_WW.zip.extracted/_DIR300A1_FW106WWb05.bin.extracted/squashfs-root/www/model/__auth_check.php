<?
/* vi: set sw=4 ts=4: */
if ($NO_NEED_AUTH!="1")
{
	/* for POP up login. */
//	require("/www/auth/__authenticate_p.php");
//	if ($AUTH_RESULT=="401")	{exit;}

	/* for WEB based login  */
	require("/www/auth/__authenticate_s.php");
	if($AUTH_RESULT=="401")		{require("/www/login.php"); exit;}
	if($AUTH_RESULT=="full")	{require("/www/session_full.php"); exit;}
	if($AUTH_RESULT=="timeout")	{require("/www/session_timeout.php"); exit;}

	$AUTH_GROUP=fread("/var/proc/web/session:".$sid."/user/group");
}
else
{
	$AUTH_GROUP="";
	$AUTH_RESULT="";
}
require("/www/model/__lang_msg.php");
?>
