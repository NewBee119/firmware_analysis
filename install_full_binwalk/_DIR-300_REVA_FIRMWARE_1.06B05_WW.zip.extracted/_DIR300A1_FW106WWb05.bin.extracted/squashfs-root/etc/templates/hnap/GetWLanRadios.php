HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<? echo "<"."?";?>xml version="1.0" encoding="utf-8"<? echo "?".">";?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetWLanRadiosResponse xmlns="http://purenetworks.com/HNAP1/">
		<GetWLanRadiosResult>OK</GetWLanRadiosResult>
		<RadioInfos>
			<RadioInfo>
			<RadioID>2.4GHZ</RadioID>
			<Frequency>2</Frequency>
			<SupportedModes>
			<?
			$model = query("/sys/modelname");
			$support11n = query("/runtime/func/ieee80211n");
			if($model == "DIR-320" || $model == "DIR-300" || $model == "DIR-600")
			{
				if($support11n == "1")
				{
				echo "<string>802.11n</string>\n";
				}
				else
				{
				echo "<string>802.11g</string>\n";
				}
				echo "<string>802.11bg</string>\n";
				if($support11n == "1")
				{
				echo "<string>802.11bgn</string>\n";
				}
			}
			else if($model == "DIR-605" || $model == "DIR-615")
			{
				echo "<string>802.11n</string>\n";
				echo "<string>802.11bg</string>\n";
				echo "<string>802.11bgn</string>\n";
			}
			?>
			</SupportedModes>
			<Channels>
			<?
			$countryCode = get("s","/sys/countrycode");
			if( $countryCode == "")
			{
				$countryCode = query("/runtime/nvram/countrycode");
			}
			if( $countryCode == "840" )
			{
				$channelNumber = 11;
			}
			else { $channelNumber = 13; }
			$inx = 1;
			while( $inx <= $channelNumber )
			{	echo "<int>".$inx."</int>\n"; $inx++; }
			?>
			</Channels>
			<WideChannels>
			<?
			if($support11n == "1")
			{
			$width = query("/wireless/bandwidth");
			if( $width == "1" )
			{ $bandWidth = "20"; }
			else if( $width == "2" )
			{ $bandWidth = "40"; }
			else
			{ $bandWidth = "0"; }
			
			$startChannel = 1;
			while( $startChannel <= $channelNumber )
			{
				echo "<WideChannel>\n";
				echo "	<Channel>".$startChannel."</Channel>\n";
				echo "	<SecondaryChannels>\n";
				// fix bug
				if( $bandWidth == 40 && $startChannel <= 4 )
				{ 
					$secondaryChnl = $startChannel + 4; 
				}
				else if( $startChannel > 4 && $startChannel < 8 && $bandWidth == 40 )
				{ 
					$secondaryChnl = $startChannel - 4; 
				}
				else if( $startChannel >= 8 && $bandWidth == 40)
				{ 
					if( ($channelNumber - $startChannel) < 4 )
					{ $secondaryChnl = $startChannel - 4; }
					else
					{ $secondaryChnl = $startChannel - 4; }
				}
				else
				{
					$secondaryChnl = $startChannel;
				}
				echo "		<int>".$secondaryChnl."</int>\n";
				echo "	</SecondaryChannels>\n";
				echo "</WideChannel>\n";
				$startChannel++;

			}
			}
			?>
			</WideChannels>
			<SupportedSecurity>
				<SecurityInfo>
					<SecurityType>WEP-OPEN</SecurityType>
					<Encryptions>
						<string>WEP-64</string>
						<string>WEP-128</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WEP-SHARED</SecurityType>
					<Encryptions>
						<string>WEP-64</string>
						<string>WEP-128</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA-PSK</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA-RADIUS</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA2-PSK</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA2-RADIUS</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPAORWPA2-PSK</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPAORWPA2-RADIUS</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
			</SupportedSecurity>
			</RadioInfo>
		</RadioInfos>
    </GetWLanRadiosResponse>
  </soap:Body>
</soap:Envelope>
