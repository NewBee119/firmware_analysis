<?
if ($ROUTER_ON)
{
	if (query("")!="0")
	{
		fwrite2($ShellPath, "echo UPNP connecting WAN".$WID." ... > /dev/console\n");
		fwrite2($ShellPath, "rgdb -s /runtime/wan/inf:".$WID."/connect 1\n");
	}
	$SOAP_BODY = "";
	$errorCode=200;
}
else
{
	$errorCode=501;
}
?>
