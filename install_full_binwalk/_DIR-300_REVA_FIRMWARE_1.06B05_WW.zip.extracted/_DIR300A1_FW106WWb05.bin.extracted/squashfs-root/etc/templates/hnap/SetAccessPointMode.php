HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";

$IsAccessPoint	= query("/runtime/hnap/SetAccessPointMode/IsAccessPoint");
$Result		= "";
$NewIPAddress	= "";

if ($IsAccessPoint=="true")
{
	if (query("/bridge")!="1")	{ $Result="REBOOT"; set("/bridge", "1"); }
	else				{ $Result="OK"; /* we are already in bridge mode. */ }
}
else
{
	if (query("/bridge")=="1")	{ $Result="REBOOT"; set("/bridge", "0"); }
	else				{ $Result="OK"; /* we are already in router mode */ }
}

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo [$0] $1 ... > /dev/console\n");
fwrite2($ShellPath, "echo IsAccessPoint = ".$IsAccessPoint." > /dev/console\n");
fwrite2($ShellPath, "echo Result = ".$Result."\n");

if ($Result == "REBOOT")
{
	fwrite2($ShellPath, "/etc/scripts/system.sh stop\n");
	fwrite2($ShellPath, "/etc/scripts/misc/profile.sh put\n");
	fwrite2($ShellPath, "/etc/scripts/system.sh start\n");
	fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
	set("/runtime/hnap/dev_status", "ERROR");

	if ($IsAccessPoint!="true")			{ $NewIPAddress=query("/lan/ethernet/ip"); }
	else if (query("/wan/rg/inf:1/mode")=="1")	{ $NewIPAddress=query("/wan/rg/inf:1/static/ip"); }
}
else if ($Result == "OK")
{
	fwrite2($ShellPath, "echo \"We are already in bridge/router mode, so we do nothing ...\"");
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body>
<SetAccessPointModeResponse xmlns="http://purenetworks.com/HNAP1/">
<SetAccessPointModeResult><?=$Result?></SetAccessPointModeResult>
<NewIPAddress><?=$NewIPAddress?></NewIPAddress>
</SetAccessPointModeResponse>
</soap:Body>
</soap:Envelope>
