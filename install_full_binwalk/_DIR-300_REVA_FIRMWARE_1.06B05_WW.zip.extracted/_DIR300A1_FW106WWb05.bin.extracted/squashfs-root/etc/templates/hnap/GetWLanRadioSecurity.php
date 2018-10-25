HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$result = "OK";
$radioID = get("h","/runtime/hnap/GetWLanRadioSecurity/RadioID");
if( $radioID != "2.4GHZ" )
{
	$result = "ERROR_BAD_RADIOID";
	$enabled = "";
	$keyRenewal = "";
	$type = "";
	$encrypt = "";
	$key = "";
	$radiusIP1 = "";
	$radiusPort1 = "";
	$radiusSecret1 = "";
	$radiusIP2 = "";
	$radiusPort2 = "";
	$radiusSecret2 = "";
}
else
{
	$enable = get("x","/wireless/encrypttype");
	$authType	= get("x","/wireless/authtype");
	if( $enable != "0" )
	{
		$enabled = "true";
		if( $enable == "1" )
		{
			if( $authType != "0" && $authType != "1" )
			{
				$result = "ERROR";
			}
			else
			{
				$defKey = get("x","/wireless/wep/defkey");
				$key = get("x","/wireless/wep/key:".$defKey);
				if( $authType == "0" )
				{
					$type = "WEP-OPEN";
					$keyLen = get("x","/wireless/wep/length");
					$encrypt = "WEP-".$keyLen;
				}
				else if( $authType == "1" )
				{
					$type = "WEP-SHARED";
					$keyLen = get("x","/wireless/wep/length");
					$encrypt = "WEP-".$keyLen;
				}
				else
				{
					$result = "ERROR";
				}
			}
		}
		else if( $authType == "2" || $authType == "4" || $authType == "6")
		{
			if( $authType == "2" ) { $type = "WPA-RADIUS"; }
			if( $authType == "4" ) { $type = "WPA2-RADIUS"; }
			if( $authType == "6" ) { $type = "WPAORWPA2-RADIUS"; } /* ALPHA add, not follow HNAP Spec 1.1 */
			if( $enable == "2" ) {	$encrypt = "TKIP"; } 
			else if( $enable == "3" ) { $encrypt = "AES"; }
			else if( $enable == "4" ) { $encrypt = "TKIPORAES"; }
			else { $result = "ERROR"; }
			$keyRenewal = get("x","/wireless/wpa/grp_rekey_interval");
			$radiusIP1 = get("x","/wireless/wpa/radius:1/host");
			$radiusPort1 = get("x","/wireless/wpa/radius:1/port");
			$radiusSecret1 = get("x","/wireless/wpa/radius:1/secret");
			$radiusIP2 = get("x","/wireless/wpa/radius:2/host");
			$radiusPort2 = get("x","/wireless/wpa/radius:2/port");
			$radiusSecret2 = get("x","/wireless/wpa/radius:2/secret");
		}
		else if( $authType == "3" || $authType == "5" || $authType == "7")
		{
			if( $authType == "3" ) { $type = "WPA-PSK"; }
			if( $authType == "5" ) { $type = "WPA2-PSK"; }
			if( $authType == "7" ) { $type = "WPAORWPA2-PSK"; } /* ALPHA add, not follow HNAP Spec 1.1 */
			if( $enable == "2" ) {	$encrypt = "TKIP"; } 
			else if( $enable == "3" ) { $encrypt = "AES"; }
			else if( $enable == "4" ) { $encrypt = "TKIPORAES"; }
			else { $result = "ERROR"; }
			$keyRenewal = get("x","/wireless/wpa/grp_rekey_interval");
			$key = get("x","/wireless/wpa/key");
		}
	}
	else
	{
		$enabled = "false";
		$keyRenewal = "0";
		$radiusPort1 = "0";
		$radiusPort2 = "0";
	}
	//fix for TestDevice
	if($keyRenewal == "") { $keyRenewal = "0"; }
	if($radiusPort1 == "") { $radiusPort1 = "0"; }
	if($radiusPort2 == "") { $radiusPort2 = "0"; }
}

?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetWLanRadioSecurityResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetWLanRadioSecurityResult><?=$result?></GetWLanRadioSecurityResult>
      <Enabled><?=$enabled?></Enabled>
      <Type><?=$type?></Type>
      <Encryption><?=$encrypt?></Encryption>
      <KeyRenewal><?=$keyRenewal?></KeyRenewal>
      <Key><?=$key?></Key>
      <RadiusIP1><?=$radiusIP1?></RadiusIP1>
      <RadiusPort1><?=$radiusPort1?></RadiusPort1>
	  <RadiusSecret1><?=$radiusSecret1?></RadiusSecret1>
      <RadiusIP2><?=$radiusIP2?></RadiusIP2>
      <RadiusPort2><?=$radiusPort2?></RadiusPort2>
	  <RadiusSecret2><?=$radiusSecret2?></RadiusSecret2>
    </GetWLanRadioSecurityResponse>
  </soap:Body>
</soap:Envelope>
