<? /* vi: set sw=4 ts=4: */
anchor("/runtime/upnp/GetSpecificPortMappingEntry");
$NewRemoteHost		= query("NewRemoteHost");
$NewExternalPort	= query("NewExternalPort");
$NewProtocol		= query("NewProtocol");

if ($ROUTER_ON==0)
{
	$errorCode=501;
}
else
{
	$target = 0;
	for ("/runtime/upnp/wan:".$WID."/entry")
	{
		if ($NewProtocol == "TCP") { $proto=1; } else { $proto=2; }
		if (query("remoteip") == $NewRemoteHost &&
			query("port2") == $NewExternalPort &&
			query("protocol") == $proto)
		{
			$target = $@;
		}
	}
	if ($target == 0)
	{
		$errorCode=714;
	}
	else
	{
		$errorCode=200;
		$SOAP_BODY=$template_root."/upnpd/__ACTION.GetSpecificPortMappingEntry.php";
	}
}
?>
