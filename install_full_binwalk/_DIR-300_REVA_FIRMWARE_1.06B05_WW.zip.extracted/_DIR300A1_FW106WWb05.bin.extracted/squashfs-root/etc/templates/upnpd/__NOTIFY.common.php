<?
/*
 * anchor() should be at root device when require this file.
 * by David Hsieh <david_hsieh@alphanetworks.com>
 */
$MAXAGE		= query("maxage");
$LOCATION	= query("location");
$SERVER 	= query("server");
$CMD		= "xmldbc -A ".$template_root."/upnpd/__NOTIFY.req.ab.php".
		  " -V MAXAGE=\"".	$MAXAGE.	"\"".
		  " -V LOCATION=\"".	$LOCATION.	"\"".
		  " -V SERVER=\"".	$SERVER.	"\"".
		  " -V NTS=\"".		$NTS.		"\"";
$CMDTAIL	= " | upnpkits -H 239.255.255.250:1900 -p UDP\n";
?>
