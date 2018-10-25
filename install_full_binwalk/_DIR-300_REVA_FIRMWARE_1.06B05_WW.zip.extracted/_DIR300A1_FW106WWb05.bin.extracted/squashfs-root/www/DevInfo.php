<?
/* vi: set sw=4 ts=4: */
$MY_NAME	= "DevInfo";
$MY_MSG_FILE= $MY_NAME.".php";
/* ------------------------------------------------------------------------- */
$NO_NEED_AUTH="1";
$NO_SESSION_TIMEOUT="1";

anchor("/tmp/dr");
//kwest modified to follow "D-Link WiFI Frequency table_20090702.xls"
set("domain/36/name", "AU");			set("domain/AU/name","AU");
set("domain/124/name","CA");			set("domain/CA/name","CA");
set("domain/156/name","CN");			set("domain/CN/name","CN");
set("domain/702/name","SG");			set("domain/SG/name","SG");
set("domain/158/name","TW");			set("domain/TW/name","TW");
set("domain/840/name","US/NA");			set("domain/US/name","US/NA");
set("domain/LA/name","LA"); //can't find country number for Latin America
//for EU country
set("domain/208/name","EU"); 			set("domain/DK/name","EU");
set("domain/276/name","EU"); 			set("domain/DE/name","EU");
set("domain/352/name","EU"); 			set("domain/IS/name","EU");
set("domain/246/name","EU"); 			set("domain/FI/name","EU");
set("domain/528/name","EU"); 			set("domain/NL/name","EU");
set("domain/578/name","EU"); 			set("domain/NO/name","EU");
set("domain/752/name","EU"); 			set("domain/SE/name","EU");
set("domain/616/name","EU"); 			set("domain/PL/name","EU");
set("domain/705/name","EU"); 			set("domain/SI/name","EU");
set("domain/442/name","EU"); 			set("domain/LU/name","EU");
set("domain/710/name","EU"); 			set("domain/ZA/name","EU");
set("domain/826/name","EU");            set("domain/GB/name","EU");     set("domain/UK/name","EU");
set("domain/372/name","EU");            set("domain/IE/name","EU");

set("domain/376/name","IL");            set("domain/IL/name","IL");
set("domain/410/name","KR");            set("domain/KR/name","KR");
set("domain/392/name","JP");            set("domain/JP/name","JP");
set("domain/818/name","EG");			set("domain/EG/name","EG");
set("domain/76/name", "BR");			set("domain/BR/name","BR");
set("domain/643/name","RU");			set("domain/RU/name","RU");

$country_code=query("/runtime/nvram/countrycode");
$domain_region=query("/tmp/dr/domain/".$country_code."/name");

$version_no=query("/runtime/sys/info/firmwareversion");
$build_no=query("/runtime/sys/info/firmwarebuildno");
$model=query("/sys/modelname");
$hw_ver=query("/sys/hwversion");
$kernel=query("/sys/kernel_version");

$LANGCODE = fread("/www/locale/alt/langcode");
if($LANGCODE == "")
{
	$language = "en";
}
else
{
	$language = $LANGCODE;
}

$captcha = query("/sys/captcha");
if($captcha=="1")
{
	$m_captcha = "Enable";
}
else
{
	$m_captcha = "Disable";
}

$lan_mac=query("/runtime/layout/lanmac");
$wan_mac=query("/runtime/layout/wanmac");
$wlan_mac=query("/runtime/layout/wlanmac");

echo "Firmware External Version: V".$version_no."\n";
echo "Firmware Internal Version: ".$build_no."\n";
echo "Model Name: ".$model."\n";
echo "Hardware Version: ".$hw_ver."\n";
echo "WLAN Domain: ".$domain_region."\n";
echo "Kernel: ".$kernel."\n";
echo "Language: ".$language."\n";
echo "Graphcal Authentication: ".$m_captcha."\n";
echo "LAN MAC: ".$lan_mac."\n";
echo "WAN MAC: ".$wan_mac."\n";
echo "WLAN MAC: ".$wlan_mac."\n";
?>
