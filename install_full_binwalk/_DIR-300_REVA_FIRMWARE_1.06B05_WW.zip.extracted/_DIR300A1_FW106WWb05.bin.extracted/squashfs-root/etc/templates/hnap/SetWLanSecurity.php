HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/SetWLanSecurity/";
$Type=query($nodebase."Type");
$Key=query($nodebase."Key");
$Enabled=query($nodebase."Enabled");
$WEPKeyBits=query($nodebase."WEPKeyBits");
$result = "REBOOT";
anchor("/wireless");
if($Enabled=="true")
{
	if($Type=="WPA")
	{
		set("/wireless/wps/configured", "1");
		set("authtype", 2);
		anchor("/wireless");
		//----- do not check empty value, because there maybe really empty value
		set("wpa/radius:1/host", query($nodebase."RadiusIP1"));
		set("wpa/radius:1/port", query($nodebase."RadiusPort1"));
		set("wpa/radius:2/host", query($nodebase."RadiusIP2"));
		set("wpa/radius:2/port", query($nodebase."RadiusPort2"));
		set("wpa/radius:1/secret", $Key);
	}
	else if($Type=="WEP")
	{
		set("/wireless/wps/configured", "1");
		set("authtype", 0);
		set("encrypttype", 1);
		if($WEPKeyBits!="")
		{
			set("wep/length", $WEPKeyBits);
		}
		$id=query("wep/defkey");
		set("wep/format", 1);
		set("wep/key:".$id, $Key);
	}
}
else if($Enabled=="false")
{
	set("authtype", 0);
	set("encrypttype", 0);
}
fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo \"[$0]-->WLan Change\" > /dev/console\n");
fwrite2($ShellPath, "/etc/scripts/misc/profile.sh put > /dev/console\n");
fwrite2($ShellPath, "/etc/templates/wlan.sh restart > /dev/console \n");
fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
set("/runtime/hnap/dev_status", "ERROR");
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetWLanSecurityResponse xmlns="http://purenetworks.com/HNAP1/">
      <SetWLanSecurityResult><?=$result?></SetWLanSecurityResult>
    </SetWLanSecurityResponse>
  </soap:Body>
</soap:Envelope>
