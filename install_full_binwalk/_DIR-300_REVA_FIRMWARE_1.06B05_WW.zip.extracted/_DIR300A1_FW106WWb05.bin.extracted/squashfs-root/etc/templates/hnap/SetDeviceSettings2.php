HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/SetDeviceSettings2/";
$result = "REBOOT";
$userName = query($nodebase."Username");
if($userName != "admin")
{ $result = "ERROR_USERNAME_NOT_SUPPORTED"; }
$tz = query($nodebase."TimeZone");
if( $tz == "UTC-12:00" || $tz == "GMT-12:00" )
{ $tzInx = "1"; }
else if( $tz == "UTC-11:00" || $tz == "GMT-11:00" )
{ $tzInx = "2"; }
else if( $tz == "UTC-10:00" || $tz == "GMT-10:00" )
{ $tzInx = "3"; }
else if( $tz == "UTC-09:00" || $tz == "GMT-09:00" )
{ $tzInx = "4"; }
else if( $tz == "UTC-08:00" || $tz == "GMT-08:00" )
{ $tzInx = "5"; }
else if( $tz == "UTC-07:00" || $tz == "GMT-07:00" )
{ $tzInx = "6"; }
else if( $tz == "UTC-06:00" || $tz == "GMT-06:00" )
{ $tzInx = "9"; }
else if( $tz == "UTC-05:00" || $tz == "GMT-05:00" )
{ $tzInx = "13"; }
else if( $tz == "UTC-04:00" || $tz == "GMT-04:00" )
{ $tzInx = "16"; }
else if( $tz == "UTC-03:30" || $tz == "GMT-03:30" )
{ $tzInx = "19"; }
else if( $tz == "UTC-03:00" || $tz == "GMT-03:00" )
{ $tzInx = "20"; }
else if( $tz == "UTC-02:00" || $tz == "GMT-02:00" )
{ $tzInx = "23"; }
else if( $tz == "UTC-01:00" || $tz == "GMT-01:00" )
{ $tzInx = "24"; }
else if( $tz == "UTC+00:00" || $tz == "UTC" || $tz == "GMT+00:00" || $tz == "GMT" )
{ $tzInx = "27"; }
else if( $tz == "UTC+01:00" || $tz == "GMT+01:00" )
{ $tzInx = "28"; }
else if( $tz == "UTC+02:00" || $tz == "GMT+02:00" )
{ $tzInx = "33"; }
else if( $tz == "UTC+03:00" || $tz == "GMT+03:00" )
{ $tzInx = "39"; }
else if( $tz == "UTC+03:30" || $tz == "GMT+03:30" )
{ $tzInx = "43"; }
else if( $tz == "UTC+04:00" || $tz == "GMT+04:00" )
{ $tzInx = "44"; }
else if( $tz == "UTC+04:30" || $tz == "GMT+04:30" )
{ $tzInx = "46"; }
else if( $tz == "UTC+05:00" || $tz == "GMT+05:00" )
{ $tzInx = "47"; }
else if( $tz == "UTC+05:30" || $tz == "GMT+05:30" )
{ $tzInx = "49"; }
else if( $tz == "UTC+05:45" || $tz == "GMT+05:45" )
{ $tzInx = "50"; }
else if( $tz == "UTC+06:00" || $tz == "GMT+06:00" )
{ $tzInx = "51"; }
else if( $tz == "UTC+06:30" || $tz == "GMT+06:30" )
{ $tzInx = "54"; }
else if( $tz == "UTC+07:00" || $tz == "GMT+07:00" )
{ $tzInx = "55"; }
else if( $tz == "UTC+08:00" || $tz == "GMT+08:00" )
{ $tzInx = "57"; }
else if( $tz == "UTC+09:00" || $tz == "GMT+09:00" )
{ $tzInx = "62"; }
else if( $tz == "UTC+09:30" || $tz == "GMT+09:30" )
{ $tzInx = "65"; }
else if( $tz == "UTC+10:00" || $tz == "GMT+10:00" )
{ $tzInx = "57"; }
else if( $tz == "UTC+11:00" || $tz == "GMT+11:00" )
{ $tzInx = "72"; }
else if( $tz == "UTC+12:00" || $tz == "GMT+12:00" )
{ $tzInx = "73"; }
else if( $tz == "UTC+13:00" || $tz == "GMT+13:00" )
{ $tzInx = "75"; }
else
{ $result = "ERROR_TIMEZONE_NOT_SUPPORTED"; }

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo \"[$0]--> Time Changed\" > /dev/console\n");
if( $result == "REBOOT" )
{
	set("/time/timezone",$tzInx);
	set("/hnap/timezone:".$tzInx."/name", $tz);
	$autoAdj = query($nodebase."AutoAdjustDST");
	if($autoAdj == "true")
	{ set("/time/daylightsaving", "1"); }
	else
	{ set("/time/daylightsaving", "0"); }	
	$locale = query($nodebase."Locale");
	set("/hnap/Locale",$locale);

	fwrite2($ShellPath, "submit COMMIT > /dev/console\n");
	fwrite2($ShellPath, "submit TIME > /dev/console\n");
	fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console\n");
	set("/runtime/hnap/dev_status", "ERROR");
}
else
{
	fwrite2($ShellPath, "echo \"[$0] --> Failed\n");
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetDeviceSettings2Response xmlns="http://purenetworks.com/HNAP1/">
      <SetDeviceSettings2Result><?=$result?></SetDeviceSettings2Result>
    </SetDeviceSettings2Response>
  </soap:Body>
</soap:Envelope>
