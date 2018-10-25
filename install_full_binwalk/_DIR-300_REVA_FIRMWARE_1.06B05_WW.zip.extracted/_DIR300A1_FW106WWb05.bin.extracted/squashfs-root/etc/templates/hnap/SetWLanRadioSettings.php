HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/SetWLanRadioSettings/";
$result = "REBOOT";
if( query($nodebase."RadioID") != "2.4GHZ" )
{
	$result = "ERROR_BAD_RADIOID";
}
else
{
	$mode = query($nodebase."Mode");
	$ssid = query($nodebase."SSID");
	if( query($nodebase."Enabled") == "true" )
	{ $wlanEn = "1"; }
	else
	{ $wlanEn = "0"; }
	if( $mode == "802.11b" )
	{ $wlanMode = "1"; }
	else if( $mode == "802.11g" )
	{ $wlanMode = "2"; }
	else if( $mode == "802.11n" )
	{ $wlanMode = "4"; }
	else if( $mode == "802.11bg" )
	{ $wlanMode = "3"; }
	else if( $mode == "802.11bn" )
	{ $wlanMode = "5"; }
	else if( $mode == "802.11gn" )
	{ $wlanMode = "6"; }
	else if( $mode == "802.11bgn" )
	{ $wlanMode = "7"; }
	else
	{ 
		if( $wlanEn == "1" ) { $result = "ERROR_BAD_MODE"; }
	}
	if( $wlanEn == "1" && $ssid == "" )
	{ $result = "ERROR"; }
	if( query($nodebase."SSIDBroadcast") == "false" )
	{ $ssidHidden = "1"; }
	else
	{ $ssidHidden = "0"; }
	$width = query($nodebase."ChannelWidth");
	if( $width == "20" )
	{ $bandWidth = "1"; }
	else if( $width == "40" )
	{ $bandWidth = "2"; }
	else
	{ $bandWidth = "3"; }
	$channel = query($nodebase."Channel");
	$countryCode = query("/sys/countrycode");
	if( $countryCode == "840" && $channel > "11" || $channel < "0" )
	{ $result = "ERROR"; }
	else if( $channel > "13" )
	{ $result = "ERROR"; }
	$secondaryChnl = query($nodebase."SecondaryChannel");
	$model = query("/sys/modelname");
	if( $width == "" ) 
	{ 
		if( $secondaryChnl!="0" )
		{ $result = "ERROR_BAD_SECONDARY_CHANNEL"; }
	}
	if(query($nodebase."QoS") == "false" )
	{ $qos = "0"; }
	else
	{ $qos = "1"; }
	if( $result == "REBOOT" )
	{
	  set("/wireless/enable",$wlanEn);
	  if( $wlanEn == "1" )
	  {
		$old_ssid = query("/wireless/ssid");
		if($old_ssid != $ssid) { set("/wireless/wps/configured", "1"); }
		set("/wireless/ssid",$ssid);
		set("/wireless/wlanmode",$wlanMode);
		set("/wireless/ssidhidden",$ssidHidden);
		set("/wireless/bandwidth",$bandWidth);
		if( $channel == "0" )
		{ set("/wireless/autochannel","1"); }
		else
		{
			set("/wireless/autochannel", "0");
			set("/wireless/channel",$channel);
		}
		set("/wireless/SecondaryChannel",$secondaryChnl);
		set("/wireless/wmm", $qos);
	  }
	}
}

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo \"[$0]-->WLan Change\" > /dev/console\n");
if( $result == "REBOOT" )
{
	fwrite2($ShellPath, "submit COMMIT > /dev/console\n");
	fwrite2($ShellPath, "submit WLAN > /dev/console \n");
	fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
	set("/runtime/hnap/dev_status", "ERROR");
}
else
{
	fwrite2($ShellPath, "echo \"We got a error, so we do nothing...\" > /dev/console");
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetWLanRadioSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <SetWLanRadioSettingsResult><?=$result?></SetWLanRadioSettingsResult>
    </SetWLanRadioSettingsResponse>
  </soap:Body>
</soap:Envelope>
