# Auto generated config file<?

/* WPS state */
if (query("/wireless/wps/enable")=="1")
{
	if (query("/wireless/wps/configured")==1)	{ $WPS_STATE = 2; }
	else										{ $WPS_STATE = 1; }
}
else
{
	$WPS_STATE = 0;
}

$SSID = query("/wireless/ssid");
$auth = query("/wireless/authtype");
$wepmode = query("/wireless/encrypttype");

if ($PARAM=="enrollee")
{
	/* use our own PIN */
	$DEVPWDID = "0x0000";
	/* Get configured PIN. */
	$DEVPWD = query("/wireless/wps/pin");
	/* Factory default PIN. (label) */
	if ($DEVPWD == "") { $DEVPWD = query("/runtime/wps/pin"); }
}
else
{
	$PIN = query("/runtime/wps/enrollee/pin");
	/* Get all the config setting for WPS */
	if ($PIN=="00000000")	{ $DEVPWDID = "0x0004"; }
	else					{ $DEVPWDID = "0x0000"; }

	$DEVPWD=$PIN;

	set("/runtime/wps/enrollee/pin", "");

	/* Config ourself, if we are unconfigured. */
	if ($WPS_STATE == 1)
	{
		$SSID = query("/runtime/wps/registrar/default_ssid");
		if ($SSID=="") {$SSID=query("/wireless/ssid");}
		$auth = 0;
		$wepmode = 0;
	}
}
$USE_UPNP = query("/upnp/enable");

if		($auth == 0)	{ $AUTH_TYPE="0x0001"; }	/* Open */
else if	($auth == 1)	{ $AUTH_TYPE="0x0004"); }	/* Shared-Key */
else if	($auth == 2)	{ $AUTH_TYPE="0x0008"); }	/* WPA */
else if	($auth == 3)	{ $AUTH_TYPE="0x0002"); }	/* WPA PSK */
else if	($auth == 4)	{ $AUTH_TYPE="0x0010"); }	/* WPA2 */
else if	($auth == 5)	{ $AUTH_TYPE="0x0020"); }	/* WPA2 PSK */
else if	($auth == 6)	{ $AUTH_TYPE="0x0008"); }	/* WPA/WPA2 */
else if	($auth == 7)	{ $AUTH_TYPE="0x0002"); }	/* WPA/WPA2 PSK */
else if	($auth == 8)	{ $AUTH_TYPE="0x0001"); }	/* 802.1x */

if		($wepmode == 0)	{ $ENCR_TYPE="0x0001"); }	/* None */
else if	($wepmode == 1)	{ $ENCR_TYPE="0x0002"); }	/* WEP */
else if	($wepmode == 2)	{ $ENCR_TYPE="0x0004"); }	/* TKIP */
else if	($wepmode == 3)	{ $ENCR_TYPE="0x0008"); }	/* AES */
else if	($wepmode == 4)								/* TKIP+AES */
{
	if ($AUTH_TYPE=="0x0008" || $AUTH_TYPE=="0x0002") { $ENCR_TYPE="0x0004"; }
	else { $ENCR_TYPE="0x0008"; }
}

if ($wepmode == 1)
{
	$keyid = query("/wireless/wep/defkey");
	$NWKEY = query("/wireless/wep/key:".$keyid);
}
else if ($wepmode == 0)
{
	$NWKEY = "";
}
else
{
	$NWKEY = query("/wireless/wpa/key");
}

$AUTH_TYPE_FLAGS="0x003f";
$ENCR_TYPE_FLAGS="0x000f";

?>
WPS_STATE=<?=$WPS_STATE?>
CONFIG_METHODS=0x0086
DEV_PASSWORD_ID=<?=$DEVPWDID?>
DEV_PASSWORD=<?=$DEVPWD?>
PRI_DEV_CATEGORY=6
PRI_DEV_OUI=0x0050F204
PRI_DEV_SUB_CATEGORY=1
CONN_TYPE_FLAGS=0x01
UUID=<?			query("/runtime/upnpdev/root:2/uuid"); ?>
VERSION=0x10
DEVICE_NAME=<?	query("/sys/modelname"); ?>
MAC_ADDRESS=<?	query("/runtime/layout/lanmac");?>
MANUFACTURER=<?	query("/sys/vendor"); ?>
MODEL_NAME=<?	query("/sys/modelname"); ?>
MODEL_NUMBER=<?	query("/sys/modelname"); ?>
SERIAL_NUMBER=00000000
RF_BAND=1
OS_VER=0x80000000
SSID=<?=$SSID?>
AUTH_TYPE_FLAGS=<?=$AUTH_TYPE_FLAGS?>
ENCR_TYPE_FLAGS=<?=$ENCR_TYPE_FLAGS?>
AUTH_TYPE=<?=$AUTH_TYPE?>
ENCR_TYPE=<?=$ENCR_TYPE?>
NW_KEY=<?=$NWKEY?>
USE_UPNP=<?=$USE_UPNP?>
RESTART_AP_CMD=/etc/templates/restart_ap.sh
