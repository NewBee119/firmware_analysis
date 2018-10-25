<? /* vi: set sw=4 ts=4: */
if ($ROUTER_ON==1)
{
	if (query("/upnp/allow_disconnect_wan")!=0)
	{
		fwrite2($ShellPath, "echo UPNP disconnecting WAN".$WID." ... > /dev/console\n");
		fwrite2($ShellPath, "rgdb -s /runtime/wan/inf:".$WID."/disconnect 1\n");
	}
	$SOAP_BODY="";
	$errorCode=200;
}
else
{
	$errorCode=501;
}
?>
