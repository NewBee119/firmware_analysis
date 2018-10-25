HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/SetWLanRadioSecurity/";
$result = "REBOOT";
if( query($nodebase."RadioID") != "2.4GHZ" )
{
	$result = "ERROR_BAD_RADIOID";
}
else
{
	if(query($nodebase."Enabled") == "false" )
	{
		set("/wireless/encrypttype","0");
		set("/wireless/authtype","0");
	}
	else
	{
		$type = query($nodebase."Type");
		$encrypt = query($nodebase."Encryption");
		$key = query($nodebase."Key");
		$keyRenewal = query($nodebase."KeyRenewal");
		$radiusIP1 = query($nodebase."RadiusIP1");
		$radiusPort1 = query($nodebase."RadiusPort1");
		$radiusSecret1 = query($nodebase."RadiusSecret1");
		$radiusIP2 = query($nodebase."RadiusIP2");
		$radiusPort2 = query($nodebase."RadiusPort2");
		$radiusSecret2 = query($nodebase."RadiusSecret2");
		if( $type == "WEP-OPEN" || $type == "WEP-SHARED" )
		{
			if( $encrypt == "WEP-64" )
			{
				$wepLen = 64;
			}
			else if( $encrypt == "WEP-128" )
			{
				$wepLen = 128;
			}
			else
			{
				$result = "ERROR_ENCRYPTION_NOT_SUPPORTED";
			}
			if( $type == "WEP-OPEN" )
			{
				$auth = 0;
			}
			else
			{
				$auth = 1;
			}
			if( $key == "" )
			{ $result = "ERROR_ILLEGAL_KEY_VALUE"; }
			if( $result == "REBOOT" )
			{
				set("/wireless/wps/configured", "1");
				set("/wireless/authtype", $auth);
				set("/wireless/encrypttype","1");
				set("/wireless/wep/length", $wepLen);
				set("/wireless/wep/format", "2");
				$defKey = query("/wireless/wep/defkey");
				set("/wireless/wep/key:".$defKey, $key);
			}
		}
		else if( $type == "WPA-PSK" || $type == "WPA2-PSK" || $type == "WPAORWPA2-PSK" )
		{
			if( $keyRenewal == "" )
			{
				$result = "ERROR_KEY_RENEWAL_BAD_VALUE";
			}
			//more strict
			if( $keyRenewal < 60 || $keyRenewal > 7200 )
			{
				$result = "ERROR_KEY_RENEWAL_BAD_VALUE";
			}
			if( $key == "" )
			{
				$result = "ERROR_ILLEGAL_KEY_VALUE";
			}
			if( $encrypt != "TKIP" && $encrypt != "AES" && $encrypt != "TKIPORAES" )
			{
				$result = "ERROR_ENCRYPTION_NOT_SUPPORTED";
			}
			if( $type == "WPA-PSK" )
			{ $auth = "3"; }
			else if( $type == "WPA2-PSK" )
			{ $auth = "5"; }
			else
			{ $auth = "7"; }
			if( $encrypt == "TKIP" )
			{ $encrypttype = "2"; }
			else if( $encrypt == "AES" )
			{ $encrypttype = "3"; }
			else
			{ $encrypttype = "4"; }
			if( $result == "REBOOT" )
			{
				set("/wireless/wps/configured", "1");
				set("/wireless/authtype",$auth);
				set("/wireless/encrypttype",$encrypttype);
				set("/wireless/wpa/format","1");
				set("/wireless/wpa/key",$key);
				set("/wireless/wpa/grp_rekey_interval",$keyRenewal);
			}
		}
		else if( $type == "WPA-RADIUS" || $type == "WPA2-RADIUS" || $type == "WPAORWPA2-RADIUS" )
		{
			if( $keyRenewal == "" )
			{
				$result = "ERROR_KEY_RENEWAL_BAD_VALUE";
			}
			//more strict
			if( $keyRenewal < 60 || $keyRenewal > 7200 )
			{
				$result = "ERROR_KEY_RENEWAL_BAD_VALUE";
			}
			if( $encrypt != "TKIP" && $encrypt != "AES" && $encrypt != "TKIPORAES" )
			{
				$result = "ERROR_ENCRYPTION_NOT_SUPPORTED";
			}
			if( $radiusIP1 == "" || $radiusPort1 == "" || $radiusSecret1 == "" )
			{
				$result = "ERROR_BAD_RADIUS_VALUES";
			}
			if( $type == "WPA-RADIUS" )
			{ $auth = "2"; }
			else if( $type == "WPA2-RADIUS" )
			{ $auth = "4"; }
			else
			{ $auth = "6"; }
			if( $encrypt == "TKIP" )
			{ $encrypttype = "2"; }
			else if( $encrypt == "AES" )
			{ $encrypttype = "3"; }
			else
			{ $encrypttype = "4"; }
			if( $result == "REBOOT" )
			{
				set("/wireless/wps/configured", "1");
				set("/wireless/authtype",$auth);
				set("/wireless/encrypttype",$encrypttype);
				set("/wireless/wpa/format","1");
				set("/wireless/wpa/radius:1/host",$radiusIP1);
				set("/wireless/wpa/radius:1/port",$radiusPort1);
				set("/wireless/wpa/radius:1/secret",$radiusSecret1);
				set("/wireless/wpa/radius:2/host",$radiusIP2);
				set("/wireless/wpa/radius:2/port",$radiusPort2);
				set("/wireless/wpa/radius:2/secret",$radiusSecret2);
				set("/wireless/wpa/grp_rekey_interval",$keyRenewal);
			}
		}
		else
		{
			$result = "ERROR_TYPE_NOT_SUPPORT";
		}
	}
}

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo \"[$0]-->WLan Change\" > /dev/console\n");
if($result=="REBOOT")
{
	fwrite2($ShellPath, "submit COMMIT > /dev/console\n");
	fwrite2($ShellPath, "submit WLAN > /dev/console \n");
	fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
	set("/runtime/hnap/dev_status", "ERROR");
}
else
{
	fwrite2($ShellPath, "echo \"We got a error in setting, so we do nothing...\" > /dev/console");
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetWLanRadioSecurityResponse xmlns="http://purenetworks.com/HNAP1/">
      <SetWLanRadioSecurityResult><?=$result?></SetWLanRadioSecurityResult>
    </SetWLanRadioSecurityResponse>
  </soap:Body>
</soap:Envelope>
