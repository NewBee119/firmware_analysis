<?
/*
 * anchor() should be at root device when require this file.
 * by David Hsieh <david_hsieh@alphanetworks.com>
 */
$MAXAGE		= query("maxage");
$DATE		= query("/runtime/time/rfc1123");
$LOCATION	= query("location");
$SERVER		= query("server");
$CMD		= "xmldbc -A ".$template_root."/upnpd/__M-SEARCH.resp.php".
		  " -V MAXAGE=\"".	$MAXAGE.	"\"".
		  " -V DATE=\"".	$DATE.		"\"".
		  " -V LOCATION=\"".	$LOCATION.	"\"".
		  " -V SERVER=\"".	$SERVER.	"\"";
$CMDTAIL	= " | upnpkits -H \"".$TARGET_HOST."\" -p UDP\n";
?>
