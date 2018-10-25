HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/SetRouterSettings/";
$result = "OK";
if( query($nodebase."WiredQoS") == "true" )
{
	$wireQos = 1;
}
else
{
	$wireQos = 0;
}
if( query($nodebase."RemoteSSL") == "true" )
{ $result = "ERROR_REMOTE_SSL_NOT_SUPPORTED"; }
if(query($nodebase."ManageRemote") == "true" )
{
	$remoteMng = "1";
}
else
{ $remoteMng = "0"; }

$hostName = query($nodebase."DomainName");

$remotePort = query($nodebase."RemotePort");
if( $remoteMng == "" || $remotePort == "" )
{
	$result = "ERROR";
}
$mngWlan = query($nodebase."ManageWireless");
set("/hnap/SetRouterSettings/ManageWireless",	$mngWlan);

//$wpsPin = query($nodebase."WPSPin");
//$wpsEn = query("/runtime/func/wps");
//if( $wpsPin == "" && $wpsEn == "1" )
//{
//	$result = "ERROR";
//}

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo \"[$0]-->RouterSettings Change\" > /dev/console\n");
if($result == "OK")
{
	set("/qos/mode",$wireQos);
	set("/ddns/hostname", $hostName);
	set("/security/firewall/httpallow", $remoteMng);
	set("/security/firewall/httpremoteport", $remotePort);
	set("/wireless/wps/pin",				$wpsPin);
	fwrite2($ShellPath, "submit COMMIT > /dev/console\n");
	if( query("/security/firewall/httpallow") == "1" )
	{ fwrite2($ShellPath, "submit REMOTE > /dev/console\n"); }
	if( query("/ddns/enable") == "1" )
	{ fwrite2($ShellPath, "submit DDNS > /dev/console\n"); }
	//if( $wpsEn == "1" )
	//{ fwrite2($ShellPath, "submit WLAN > /dev/console\n"); }
	fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
	set("/runtime/hnap/dev_status", "ERROR");
	$result = "REBOOT";
}
else
{
	fwrite2($ShellPath, "echo \"We got a error in setting, so we do nothing...\" > /dev/console");
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetRouterSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <SetRouterSettingsResult><?=$result?></SetRouterSettingsResult>
    </SetRouterSettingsResponse>
  </soap:Body>
</soap:Envelope>
