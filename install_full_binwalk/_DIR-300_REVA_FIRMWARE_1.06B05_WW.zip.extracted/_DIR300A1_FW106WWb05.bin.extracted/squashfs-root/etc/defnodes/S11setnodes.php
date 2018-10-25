<? /* vi: set sw=4 ts=4: */
/* Get values */
$imgsig	= fread("/etc/config/image_sign");
$bver	= fread("/etc/config/buildver");
$bnum	= fread("/etc/config/buildno");
$bdate	= fread("/etc/config/builddate");
$lanmac	= query("/runtime/nvram/lanmac");
$wanmac	= query("/runtime/nvram/wanmac");
$hwrev	= query("/runtime/nvram/hwrev");
$pin	= query("/runtime/nvram/pin");
/* security */
/*
$wpakey        = query("/runtime/nvram/wpa/key");
if ($wpakey != "")		{ set("/wireless/wpa/key",      $wpakey); }
else					{ set("/wireless/wpa/key",      $pin); }
*/
/* Validate */
if ($lanmac == "") { $lanmac="00:de:fa:19:c0:01"; }
if ($wanmac == "") { $wanmac="00:de:fa:19:c0:02"; }
if ($hwrev  == "") { $hwrev="N/A"; }
/* Set */
/* layout */
set("/runtime/layout/image_sign", "");
anchor("/runtime/layout");
	set("image_sign",		$imgsig);
	set("wanmac",			$wanmac);
	set("lanmac",			$lanmac);
	set("wlanmac",			$lanmac);
	set("wanif",			"eth0.2");
	set("lanif",			"br0");
	set("wlanif",			"ath0");
/* sys info */
set("/runtime/sys/info/dummy", "");
anchor("/runtime/sys/info");
	set("hardwareversion",	"rev ".$hwrev);
	set("firmwareversion",	$bver);
	set("firmwarebuildno",	$bnum);
	set("firmwarebuildate",	$bdate);
/* WPS pin */
set("/runtime/wps/pin",		$pin);
/* others */
set("/sys/telnetd",			"true");
set("/sys/sessiontimeout",	"180");
set("/proc/web/sessionum",	"8");
set("/proc/web/authnum",	"6");
?>
