<?
echo "<!--\n";
echo "$ACTION_POST=".$ACTION_POST."\n";
//echo "$sec_type=".$sec_type."\n";
//echo "$type=".$type."\n";
echo "$full_secret=".$full_secret."\n";
if($ACTION_POST=="1_ssid")
{
	set($G_WIZ_PREFIX_WLAN."/wireless/ssid",$ssid);
	anchor($G_WIZ_PREFIX_WLAN."/wireless/");
	/*authentication : 0:open system, 1:share key, 2: WPA, 3: WPA-PSK, 4: WPA2, 5: WPA2-PSK, 6: WPA-AUTO, 7: WPA-AUTO-PSK,
			   8:802.1X
	  wepmode: 0:disable, 1:wep, 2:tkip, 3: aes, 4: tkip+aes
	*/
	if( $type == 0 )
	{
		if( $sec_type == 7 )
		{
			set("authtype", "7");
			set("wepmode", "4");
			set("psk_type", $psk_type);
		}
		else if( $sec_type == 1 )
		{
			set("authtype", "0");
			set("wepmode", "1");
			set("wep_type", $wep_type);
			set("wep_length", $wep_length);
		}
		set("full_secret", $full_secret);
		$WIZ_NEXT="3_saving";
	}
	else
	{
		if( $sec_type == 7 )
		{
			set("authtype", "7");
			set("wepmode", "4");
			$WIZ_NEXT="2_wpa";
		}
		else if( $sec_type == 1 )
		{
			set("authtype", "0");
			set("wepmode", "1");
			$WIZ_NEXT="2_wep";
		}
	}
	set("wiz_type", $type);

}
else if($ACTION_POST=="2_wep")
{
	anchor($G_WIZ_PREFIX_WLAN."/wireless");
	set("full_secret",$secret);
	set("wep_length",$wep_length);
	set("wep_type",$wep_type);
}
else if($ACTION_POST=="2_wpa")
{
	anchor($G_WIZ_PREFIX_WLAN."/wireless");
	set("full_secret",$secret);
	set("psk_type",$psk_type);
}
else if($ACTION_POST=="3_saving")
{
	anchor($G_WIZ_PREFIX_WLAN."/wireless");
	$ssid=query("ssid");
	$auth=query("authtype");
	$full_secret=query("full_secret");
	$wep_length=query("wep_length");
	$psk_type=query("psk_type");
	$wepmode=query("wepmode");
	$wep_type=query("wep_type");
	//echo "$ssid=".$ssid."\n";
	//echo "$auth=".$auth."\n";
	//echo "$full_secret=".$full_secret."\n";
	//echo "$wep_length=".$wep_length."\n";
	//echo "$psk_type=".$psk_type."\n";
	//echo "$wepmode=".$wepmode."\n";
	//echo "$wep_type=".$wep_type."\n";

	anchor("/wireless");
	$wps=0;
	if (query("ssid") != $ssid)	{ set("ssid",$ssid); $wps++; }
	if ($auth=="0")
	{
		if($full_secret=="")	//open with none wep
		{
			set("authtype","0");		set("encrypttype","0");
		}
		else			//open with 128 bits wep
		{
			set("authtype", "0");			set("encrypttype", "1");
			set("wep/length", $wep_length);	set("wep/format", $wep_type);
			set("wep/defkey","1");			set("wep/key:1", $full_secret);
			$wps++;
		}
	}
	else if($auth=="7")
	{
		set("authtype",$auth);	set("encrypttype",$wepmode);	set("wpa/key",$full_secret);
		set("wpa/format",$psk_type);
		$wps++;
	}
	if ($wps > 0) { set("wps/configured", 1); set("wps/locksecurity", 1);}
}
echo "-->\n";
?>
