<?
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

$external_no=query("/runtime/sys/info/externalversion");
$internal_no=query("/runtime/sys/info/internalversion");
$kernel=query("/sys/kernel_version");//jana added 

$wan_mac=query("/runtime/layout/wanmac");
$lan_mac=query("/runtime/layout/lanmac");
$wlan_mac=query("/runtime/layout/wlanmac");

$fwinfo_srv=query("/sys/fwinfosrv");
$fwinfo_path=query("/sys/fwinfopath");
$model_name=query("/sys/modelname");
$ssid=get(h,"/wireless/ssid");
$build_date=query("/runtime/sys/info/firmwarebuildate");
$build_datedd      = query("/runtime/time/dateddyymm");
$build_datetime      = fread("/etc/config/builddaytime");
$build_digest      = fread("/etc/config/digest");
$wlan_driver	= query("/sys/wlandriverver");

//+++jana
if(query("/sys/restore_default") == "")
	{$restore_default=0;}
else
	{$restore_default = query("/sys/restore_default");}
//---jana

$m_context_title	="Version";

$m_context_title_mandatory="Mandatory";
$m_context_title_optional="Optional";
$m_firmware_external_version = "Firmware External Version: ";
$m_firmware_internal_version = "Firmware Internal Version: ";
$m_language_pack = "Language Package:";
if($LANGCODE == "")
{
	$m_language_name = "en";
}
else
{
	$m_language_name = $LANGCODE;
}
$m_date = "Date:";
$m_checksum = "CheckSum:";//jana modified
$m_wlan_domain = "WLAN Domain (2.4GHz):";
$m_firmare_query = "Firmware Query:";//jana modified
$m_kernel = "Kernel:";//jana added
$m_system_uptime = "System Uptime:";
$m_wan_mac = "WAN MAC: ";
$m_lan_mac = "LAN MAC: ";
$m_wlan_mac = "WLAN MAC: ";

$m_kernel = "Kernel: ";
$m_ssid = "SSID: ";
$m_default_setting = "Default Setting: ";
$m_ssid = "SSID: ";
$m_svn = "SVN: ";
$m_debug_mode = "Debug Mode: ";
$m_apps = "Apps: ";
$m_wlan_driver = "WLAN Driver: ";
$m_restore_default = "Restore Default:";//jana added

$m_c_mandatory = "<table>";
//$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb width=200>".$m_firmware_external_version."</td><td>".$version_no."</td></tr>";//jana removed
$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb width=200>".$m_firmware_external_version."</td><td>"."V".$external_no."</td></tr>";
$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_firmware_internal_version."</td><td>"."V".$internal_no."</td></tr>";
$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_language_pack."</td><td>".$m_language_name."</td></tr>";
$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_date."</td><td>".$build_datedd."</td></tr>";
//$m_c_mandatory = $m_c_mandatory."<tr><td>".$m_system_uptime."</td><td><script>document.write(shortTime());</script></td></tr>";
$m_c_mandatory = $m_c_mandatory."<tr><td>".$m_checksum."</td><td>"."<script>document.write(EncodeHex());</script></td></tr>";
$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_wlan_domain."</td><td>".$domain_region."</td></tr>";
//$m_c_mandatory = $m_c_mandatory."<tr><td>".$m_firmare_query."</td><td>http:\/\/".$fwinfo_srv.$fwinfo_path."?model=".$model_name."</td></tr>";
$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_kernel."</td><td>".$kernel."</td></tr>";//jana added
$m_c_mandatory2 = "<tr><td class=l_tb>".$m_firmare_query."</td><td><script>document.write(getQueryUrl());</script></td></tr>";

//$m_c_optional = "<tr><td class=l_tb>".$m_wan_mac."</td><td>".$wan_mac."</td></tr>";//jana removed
$m_c_optional = "<tr><td class=l_tb>".$m_apps."</td><td>".$build_date."</td></tr>";//jana added
$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_wlan_driver."</td><td>".$wlan_driver."</td></tr>";//jana added

$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_lan_mac."</td><td>".$lan_mac."</td></tr>";
$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_wan_mac."</td><td>".$wan_mac."</td></tr>";//jana added
$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_wlan_mac."</td><td>".$wlan_mac."</td></tr>";

//$m_c_optional = $m_c_optional."<tr><td >".$m_kernel."</td><td>".$."</td></tr>";
//$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_apps."</td><td>".$build_date."</td></tr>";//jana removed
//$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_wlan_driver."</td><td>".$wlan_driver."</td></tr>";//jana removed
$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_ssid."</td><td>".$ssid."</td></tr>";
$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_restore_default."</td><td>".$restore_default."</td></tr>";//jana added

$m_c_optional = $m_c_optional."</table>";

$m_context = $m_c_mandatory;
$m_context2 = $m_c_mandatory2;//jana added
//length limit of $m_context, so add $m_context_next.
$m_context_next = $m_c_optional;

//$m_context = "?ˆæœ¬ : ".$version_no."<br><br>Build Number : ".$build_no."<br><br>";
//$m_context = $m_context."System Uptime : <script>document.write(shortTime());</script>";

$m_days		= "Days";
$m_button_dsc	=$m_continue;
?>
