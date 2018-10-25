<? /* vi: set sw=4 ts=4: */
anchor("/runtime/upnp/GetGenericPortMappingEntry");
$NewPortMappingIndex = query("NewPortMappingIndex");

if ($ROUTER_ON==0)
{
	$errorCode=501;
}
else
{
	$count = 0;
	for ("/runtime/upnp/wan:".$WID."/entry") { $count++; }

	if ($NewPortMappingIndex >= $count)
	{
		$errorCode=713;
	}
	else
	{
		$errorCode=200;
		$SOAP_BODY=$template_root."/upnpd/__ACTION.GetGenericPortMappingEntry.php";
	}
}
?>
