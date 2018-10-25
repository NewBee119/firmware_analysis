<?
/* vi: set sw=4 ts=4: */
$MY_NAME="index";
$MSG_FILE="";

$TITLE="";
$NO_SESSION_TIMEOUT=1;
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");

/* ------------------------------ */
$group=fread("/var/proc/web/session:".$sid."/user/group");
if ($group==0 && query("/time/syncwith")==1)
{
	$NEXT_LINK=$G_HOME_PAGE;
	require("/www/comm/__msync.php");
}
else
{
	echo "<script>self.location.href=\"".$G_HOME_PAGE.".php\"</script>";
}
?>
<body></body>
</html>
