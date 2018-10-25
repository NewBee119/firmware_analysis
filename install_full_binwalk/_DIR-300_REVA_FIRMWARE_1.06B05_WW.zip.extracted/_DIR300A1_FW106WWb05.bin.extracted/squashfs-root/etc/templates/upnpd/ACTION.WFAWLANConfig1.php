<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
fwrite ($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo [$0] ... > /dev/console\n");
fwrite2($ShellPath, "echo \"SOAP ACTION: ".$ServiceType."#".$ActionName."\" > /dev/console\n");

$ACTION_NAME	= query("/runtime/upnp/action_name");
$SERVICE_TYPE	= query("/runtime/upnpdev/root:2/service:1/servicetype");

$SOAP_BODY="";
$errorCode=501;
$errorDescription="Action Failed";

/* ERROR */
require($template_root."/upnpd/__ACTION_500.php");
?>
