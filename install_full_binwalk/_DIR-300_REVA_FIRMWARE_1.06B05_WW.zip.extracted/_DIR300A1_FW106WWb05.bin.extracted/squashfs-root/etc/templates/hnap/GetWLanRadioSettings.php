HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
if( get("s","/runtime/hnap/GetWLanRadioSettings/RadioID") != "2.4GHZ" )
{ $result = "ERROR_BAD_RADIO"; }
else
{
	anchor("/wireless");
	$result = "OK";
	$channel=query("/wireless/channel");
	if(query("enable")=="1" && query("autochannel")=="1")
	{
		//update channel value when autochannel setup for HNAP Spec.
		//$channel=query("/runtime/stats/wireless/channel");
		$channel="0";
	}
	$wlanMode = query("/wireless/wlanmode");
	if( $wlanMode == "1" )		{ $wlanStr = "802.11b" ;}
	else if( $wlanMode == "2" )	{ $wlanStr = "802.11g"; }
	else if( $wlanMode == "3" )	{ $wlanStr = "802.11bg"; }
	else if( $wlanMode == "4" )	{ $wlanStr = "802.11n"; }
	else if( $wlanMode == "5" )	{ $wlanStr = "802.11bn"; }
	else if( $wlanMode == "6" )	{ $wlanStr = "802.11gn"; }
	else if( $wlanMode == "7" )	{ $wlanStr = "802.11bgn"; }
	else						{ $result = "ERROR"; }
	$width = query("/wireless/bandwidth");
	if( $width == "1" )
	{ $bandWidth = "20"; }
	else if( $width == "2" )
	{ $bandWidth = "40"; }
	else
	{ $bandWidth = "0"; }
	$secondaryChnl = query("/wireless/SecondaryChannel");
	if($secondaryChnl == "")
	{
		$support11n = query("/runtime/func/ieee80211n");
		if($support11n == "1")
		{
			$ccode = query("/sys/countrycode");
			if( $ccode == "" )
			{
				$ccode = query("/runtime/nvram/countrycode");
			}		
			if( $ccode == "840" ) 
				{ $chnl_num = 11; }
			else if( $ccode == "826" || $ccode == "152" || $ccode == "392" )
				{ $chnl_num = 13; }
			else
				{ $chnl_num = 13; }
			if( $bandWidth == 40 && $channel <= 4 )
			{	 
				$secondaryChnl = $channel + 4; 
			}
			else if( $channel > 4 && $channel < 8 && $bandWidth == 40 )
			{ 
				$secondaryChnl = $channel - 4; 
			}
			else if( $channel >= 8 && $bandWidth == 40)
			{ 
				if( ($chnl_num - $channel) < 4 )
				{ $secondaryChnl = $channel - 4; }
				else
				{ $secondaryChnl = $channel - 4;}
			}
			else
			{
				$secondaryChnl = $channel;
			}	
		}
		else if($width == "")
		{
			$secondaryChnl = 0;
		}
	
	}
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetWLanRadioSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetWLanRadioSettingsResult><?=$result?></GetWLanRadioSettingsResult>
      <Mode><?=$wlanStr?></Mode>
	  <Enabled><?map("enable", "1", "true", "*", "false");?></Enabled>
      <MacAddress><?query("/runtime/sys/info/lanmac");?></MacAddress>
      <SSID><?query("ssid");?></SSID>
      <SSIDBroadcast><?map("ssidHidden", "1", "false", "*", "true");?></SSIDBroadcast>
      <ChannelWidth><?=$bandWidth?></ChannelWidth>
	  <Channel><?=$channel?></Channel>
	  <SecondaryChannel><?=$secondaryChnl?></SecondaryChannel>
	  <QoS><?map("wmm","0","false","*","true");?></QoS>
    </GetWLanRadioSettingsResponse>
  </soap:Body>
</soap:Envelope>
