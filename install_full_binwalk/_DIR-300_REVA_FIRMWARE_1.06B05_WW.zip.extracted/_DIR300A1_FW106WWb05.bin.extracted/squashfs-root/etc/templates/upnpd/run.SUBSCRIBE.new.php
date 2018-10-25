<? /* vi: set sw=4 ts=4: */
require("/etc/templates/upnpmsg.php");

$count = 0; $found = 0;
for ("subscription")
{
	$count++;
	if (query("host")==$HOST && query("uri")==$URI) { $found=$@; }
}
if ($found==0)
{
	$index = $count + 1;
	$new_uuid = "uuid:".query("/runtime/genuuid");
}
else
{
	$index = $found;
	$new_uuid = query("subscription:".$index."/uuid");
}

if ($TIMEOUT == 0) { $timeout = 0; }
else { $timeout = query("/runtime/sys/uptime") + $TIMEOUT; }

/* display debug message */
$servicetype = query("servicetype");
fwrite($UPNPMSG, ">> NEWSSUB @ ".$index.": ".$servicetype.", ".$new_uuid."\n");

set("subscription:".$index."/remote",	$REMOTE);
set("subscription:".$index."/uuid",		$new_uuid);
set("subscription:".$index."/host",		$HOST);
set("subscription:".$index."/uri",		$URI);
set("subscription:".$index."/timeout",	$timeout);
set("subscription:".$index."/seq",		"0");

/* Generate HTTP header */
echo "HTTP/1.1 200 OK\r\n";
echo "SID: ".$new_uuid."\r\n";
echo "TIMEOUT: ";
if ($TIMEOUT == 0) { echo "Second-infinite"; } else { echo "Second-".$TIMEOUT; }
echo "\r\n\r\n";
?>
