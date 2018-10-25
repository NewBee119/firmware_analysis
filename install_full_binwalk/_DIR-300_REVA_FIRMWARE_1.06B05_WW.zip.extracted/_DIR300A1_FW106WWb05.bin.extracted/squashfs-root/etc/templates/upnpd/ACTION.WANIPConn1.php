<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
require("/etc/templates/upnpmsg.php");
$MMM="SOAP ACTION: ".$ServiceType."#".$ActionName."\n";
fwrite($UPNPMSG, $MMM);

fwrite ($ShellPath, "#!/bin/sh\n");

$ACTION_NAME    = query("/runtime/upnp/action_name");
$SERVICE_TYPE   = query("/runtime/upnpdev/root:1/device:1/device:1/service:2/servicetype");
$ROUTER_ON		= query("/runtime/router/enable");

$WID=1;	/* WAN port ID */
$SOAP_BODY="";
$errorCode=401;

if	(	$ACTION_NAME=="AddPortMapping"
	||	$ACTION_NAME=="DeletePortMapping"
	||	$ACTION_NAME=="GetConnectionTypeInfo"
	||	$ACTION_NAME=="GetExternalIPAddress"
	||	$ACTION_NAME=="GetGenericPortMappingEntry"
	||	$ACTION_NAME=="GetNATRSIPStatus"
	||	$ACTION_NAME=="GetSpecificPortMappingEntry"
	||	$ACTION_NAME=="GetStatusInfo"
	||	$ACTION_NAME=="RequestConnection"
	||	$ACTION_NAME=="ForceTermination"
	||	$ACTION_NAME=="SetConnectionType"
	)
{
	require($template_root."/upnpd/__ACTION.DO.".$ACTION_NAME.".php");
}

/* 200 OK */
if ($errorCode==200) { require($template_root."/upnpd/__ACTION_200.php"); exit; }

/* ERROR */
if		($errorCode==401) { $errorDescription="Invalid Action"; }
else if	($errorCode==402) { $errorDescription="Invalid Args"; }
else if	($errorCode==501) { $errorDescription="Action Failed"; }
else if	($errorCode==704) { $errorDescription="ConnectionSetupFailed"; }
else if	($errorCode==705) { $errorDescription="ConnectionSetupInProgress"; }
else if	($errorCode==706) { $errorDescription="ConnectionNotConfigured"; }
else if	($errorCode==707) { $errorDescription="DisconnectInProgress"; }
else if	($errorCode==708) { $errorDescription="InvalidLayer2Address"; }
else if	($errorCode==709) { $errorDescription="InternetAccessDisabled"; }
else if	($errorCode==710) { $errorDescription="InvalidConnectionType"; }
else if	($errorCode==711) { $errorDescription="ConnectionAlreadyTerminated"; }
else if	($errorCode==713) { $errorDescription="SpecifiedArrayIndexInvalid"; }
else if	($errorCode==714) { $errorDescription="NoSuchEntryInArray"; }
else if	($errorCode==715) { $errorDescription="WildCardNotPermittedInSrcIP"; }
else if	($errorCode==716) { $errorDescription="WildCardNotPermittedInExtPort"; }
else if	($errorCode==718) { $errorDescription="ConflictInMappingEntry"; }
else if	($errorCode==724) { $errorDescription="SamePortValuesRequired"; }
else if	($errorCode==725) { $errorDescription="OnlyPermanentLeasesSupported"; }
else if	($errorCode==726) { $errorDescription="RemoteHostOnlySupportsWildcard"; }
else if	($errorCode==727) { $errorDescription="ExternalPortOnlySupportsWildcard"; }
require($template_root."/upnpd/__ACTION_500.php");
?>
