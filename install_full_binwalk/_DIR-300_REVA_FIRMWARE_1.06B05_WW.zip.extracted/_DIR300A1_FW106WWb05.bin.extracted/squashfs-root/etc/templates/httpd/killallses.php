<?
$max_session = query("/proc/web/sessionum");
$index = 1;
while ($index <= $max_session)
{
	unlink("/var/proc/web/session:".$index."/user/ac_auth");
	unlink("/var/proc/web/session:".$index."/ip");
	unlink("/var/proc/web/session:".$index."/time");
	$index++;
}
?>
