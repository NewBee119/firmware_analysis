<? /* vi: set sw=4 ts=4: */
require("/etc/templates/upnpmsg.php");

$found = 0;
for ("subscription") { if (query("uuid")==$SID) { $found = $@; } }
if ($found != 0)
{
	/* debug message */
	$servicetype = query("servicetype");
	fwrite($UPNPMSG, ">> UNSUB @ ".$found.": ".$servicetype.", ".$SID."\n");

	del("subscription:".$found);
	echo "HTTP/1.1 200 OK\r\n";
}
else
{
	fwrite($UPNPMSG, ">> UNSUB: NOT FOUND ! (".$SID.")\n");
	echo "HTTP/1.1 412 Precondition Failed\r\n";
}
echo "\r\n";
?>
