HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/SetDeviceSettings/";
$dev_name = query($nodebase."DeviceName");
$captcha  = query($nodebase."CAPTCHA");
set("/sys/devicename", $dev_name);
if($captcha=="true")
{
	set("/sys/captcha", 1);
}
else if($captcha=="false")
{
	set("/sys/captcha", 0);
}
$result = "REBOOT";
for("/sys/user")
{
	if(query("group")==0)
	{
		set("password", query($nodebase."AdminPassword"));
	}
}

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo \"[$0]-->Password Changed\" > /dev/console\n");
if($result == "REBOOT")
{
	fwrite2($ShellPath, "submit HTTPD_PASSWD > /dev/console\n");
	fwrite2($ShellPath, "/etc/scripts/misc/profile.sh put > /dev/console\n");
	fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console\n");
	set("/runtime/hnap/dev_status", "ERROR");
}
else
{
	fwrite2($ShellPath, "echo \"We got a error in setting, so we do nothing...\" > /dev/console\n");	
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetDeviceSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <SetDeviceSettingsResult><?=$result?></SetDeviceSettingsResult>
    </SetDeviceSettingsResponse>
  </soap:Body>
</soap:Envelope>
