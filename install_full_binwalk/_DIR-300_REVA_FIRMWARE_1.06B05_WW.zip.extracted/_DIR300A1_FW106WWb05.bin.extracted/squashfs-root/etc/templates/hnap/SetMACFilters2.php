HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/SetMACFilters2/";
$Enabled=query($nodebase."Enabled");
$DefaultAllow=query($nodebase."IsAllowList");
$rlt="OK";
$i=0;
for($nodebase."MACList/MACInfo")
{
	$i++;
}

if($i==0 && query($nodebase."MACList/MACInfo/MacAddress")!="") //-----Maybe only one node, set it to array
{
	set($nodebase."MACList/MACInfo:1/MacAddress", query($nodebase."MACList/MACInfo/MacAddress"));
	set($nodebase."MACList/MACInfo:1/DeviceName", query($nodebase."MACList/MACInfo/DeviceName"));
	$i++;
}

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo [$0] > /dev/console\n");
if($i>32)
{
	$rlt="TOOMANY";
	fwrite2($ShellPath, "echo \"We got a error in setting, so we do nothing...\" > /dev/console");
}
else
{
	anchor("/security/macfilter/");
	if($Enabled=="true")
	{
		set("enable", 1);
	}
	else if($Enabled=="false")
	{
		set("enable", 0);
	}
	if($DefaultAllow=="true")
	{
		set("action", 1);
	}
	else if($DefaultAllow=="false")
	{
		set("action", 0);
	}
	//-----Clear entry
	$j=0;
	for("entry")
	{
		$j++;
	}
	while($j>0)
	{
		del("entry:".$j);
		$j--;
	}

	$j=1;
	while($j<=$i)
	{
		set("/security/macfilter/entry:".$j."/sourcemac", query($nodebase."MACList/MACInfo:".$j."/MacAddress"));
		set("/security/macfilter/entry:".$j."/description", query($nodebase."MACList/MACInfo:".$j."/DeviceName"));
		$j++;
	}

	fwrite2($ShellPath, "/etc/scripts/misc/profile.sh put > /dev/console\n");
	fwrite2($ShellPath, "/etc/templates/rg.sh macfilter > /dev/console\n");
	fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
	set("/runtime/hnap/dev_status", "ERROR");
	$rlt="REBOOT";
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetMACFilters2Response xmlns="http://purenetworks.com/HNAP1/">
      <SetMACFilters2Result><?=$rlt?></SetMACFilters2Result>
    </SetMACFilters2Response>
  </soap:Body>
</soap:Envelope>
