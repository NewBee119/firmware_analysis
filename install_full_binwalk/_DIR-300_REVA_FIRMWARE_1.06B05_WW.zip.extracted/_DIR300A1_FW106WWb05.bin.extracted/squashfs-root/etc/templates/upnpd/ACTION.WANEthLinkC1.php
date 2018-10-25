<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
require("/etc/templates/upnpmsg.php");
$MMM="SOAP ACTION: ".$ServiceType."#".$ActionName."\n";
fwrite($UPNPMSG, $MMM);

fwrite ($ShellPath, "#!/bin/sh\n");

$ACTION_NAME	= query("/runtime/upnp/action_name");
$SERVICE_TYPE	= query("/runtime/upnpdev/root:1/device:1/device:1/service:1/servicetype");

if	($ACTION_NAME=="GetEthernetLinkStatus")
{
	$SOAP_BODY = $template_root."/upnpd/__ACTION.".$ACTION_NAME.".php";
	require($template_root."/upnpd/__ACTION_200.php");
}
else
{
	$errorCode=401;
	$errorDescription="Invalid Action";
	require($template_root."/upnpd/__ACTION_500.php");
}
?>
