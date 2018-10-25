<? /* vi: set sw=4 ts=4: */
require("/etc/templates/upnpmsg.php");

$found = 0;
for ("subscription") { if (query("uuid")==$SID) { $found = $@; } }
if ($found != 0)
{
	/* debug message */
	$servicetype = query("servicetype");
	fwrite($UPNPMSG, ">> RENEWSUB @ ".$found.": ".$servicetype.",".$SID."\n");

	/* Update timeout. */
	if ($TIMEOUT==0) { $timeout=0; }
	else { $timeout = query("/runtime/sys/uptime") + $TIMEOUT; }
	set("subscription:".$found."/timeout", $timeout);

	/* Out header */
	echo "HTTP/1.1 200 OK\r\n";
	echo "SID: ".$SID."\r\n";
	echo "TIMEOUT: ";
	if ($TIMEOUT==0) { echo "Second-infinite"; } else { echo "Second-".$TIMEOUT; }
	echo "\r\n\r\n";
}
else
{
	fwrite($UPNPMSG, ">> RENEWSUB: NOT FOUND ! (".$SID.")\n");
	echo "HTTP 412 Precondition Failed\r\n";
	echo "\r\n";
}
?>
