HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/SetWLanSettings24/";
$Enabled=query($nodebase."Enabled");
$SSID=query($nodebase."SSID");
$SSIDBroadcast=query($nodebase."SSIDBroadcast");
$Channel=query($nodebase."Channel");

anchor("/wireless");

if($Enabled=="true")
{
	set("enable", 1);
}
else if($Enabled=="false")
{
	set("enable", 0);
}

if($SSID!="")
{
	$old_ssid = query("/wireless/ssid");
	if($old_ssid != $SSID) { set("/wireless/wps/configured", "1"); }
	set("ssid", $SSID);
}

if($SSIDBroadcast=="true")
{
	set("ssidHidden", 0);
}
else if($SSIDBroadcast=="false")
{
	set("ssidHidden", 1);
}

if($Channel!="")
{
	set("autochannel", 0);
	set("channel", $Channel);
}

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo \"[$0]-->WLan Change\" > /dev/console\n");
fwrite2($ShellPath, "/etc/scripts/misc/profile.sh put > /dev/console\n");
fwrite2($ShellPath, "/etc/templates/wlan.sh restart > /dev/console \n");
fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
set("/runtime/hnap/dev_status", "ERROR");
/* Kwest mark: 
   only to fix issue that D-Link HNAP Interface Verificator can't set wireless security to DIR-615D.
   NOTE: it is not a standard action. D-Link SHOULD fix his tool's bug.
 */
$Enabled=query($nodebase."Enabled");
$Type=query($nodebase."Type");
$Key=query($nodebase."Key");
$WEPKeyBits=query($nodebase."WEPKeyBits");
if($Enabled=="true")
{
	if($Type=="WPA")
	{
		set("/wireless/wps/configured", "1");
		set("/wireless/authtype", 7); // WPA-PSK/WPA2-PSK
		set("/wireless/encrypttype", 4); // TKIP/AES
		set("/wireless/wpa/key", $Key);
	}
	else if($Type=="WEP")
	{
		set("/wireless/wps/configured", "1");
		set("/wireless/authtype", 0); // Open
		set("/wireless/encrypttype", 1); // WEP
		set("/wireless/wep/format", 2); // HEX
		set("/wireless/wep/length", $WEPKeyBits);
		$defkey = query("/wireless/wep/defkey");
		set("/wireless/wep/key:".$defkey, $Key);
	}
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetWLanSettings24Response xmlns="http://purenetworks.com/HNAP1/">
      <SetWLanSettings24Result>OK</SetWLanSettings24Result>
    </SetWLanSettings24Response>
  </soap:Body>
</soap:Envelope>
