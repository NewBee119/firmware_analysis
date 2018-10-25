HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/SetRouterLanSettings/";
$DHCPServerEnabled=query($nodebase."DHCPServerEnabled");
$result = "REBOOT";
anchor("/lan");
set("ethernet/ip", query($nodebase."RouterIPAddress"));
set("ethernet/netmask", query($nodebase."RouterSubnetMask"));
if($DHCPServerEnabled=="true")
{
	set("dhcp/server/enable", 1);
}
else if($DHCPServerEnabled=="false")
{
	set("dhcp/server/enable", 0);
}

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo \"[$0]-->Lan Change\" > /dev/console\n");
fwrite2($ShellPath, "submit COMMIT > /dev/console\n");
fwrite2($ShellPath, "submit LAN_CHANGE > /dev/console\n");
fwrite2($ShellPath, "submit LAN > /dev/console\n");
fwrite2($ShellPath, "submit DNSR > /dev/console\n");
fwrite2($ShellPath, "submit DHCPD > /dev/console\n");
fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
set("/runtime/hnap/dev_status", "ERROR");
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetRouterLanSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <SetRouterLanSettingsResult><?=$result?></SetRouterLanSettingsResult>
    </SetRouterLanSettingsResponse>
  </soap:Body>
</soap:Envelope>
