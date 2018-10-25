Umask 026
<?
require("/etc/templates/troot.php");
$SESSION_TIMEOUT=query("/sys/sessiontimeout");
$SESSION_NUM	=query("/proc/web/sessionum");
$HTTP_ALLOW	=query("/security/firewall/httpallow");
$REMOTE_PORT	=query("/security/firewall/httpRemotePort");
$WAN_IF		=query("/runtime/wan/inf:1/interface");
$LAN_IF		=query("/runtime/layout/lanif");
$UPNP_PORT	= 49152;
/* get the WAN2 infterface of Russia PPPoE/PPTP */
if(query("/wan/rg/inf:2/mode")!="" || query("/wan/rg/inf:1/pptp/physical")=="1")
{
	$WAN2_IF	=query("/runtime/wan/inf:2/interface");
}

/* For UPNP */
$model	= query("/sys/modelname");
$ver	= query("/runtime/sys/info/firmwareversion");
set("/runtime/upnpdev/port",        $UPNP_PORT);
set("/runtime/upnpdev/server",      "Linux, UPnP/1.0, ".$model." Ver ".$ver);
set("/runtime/upnpdev/maxage",      1800);

/* generate this file for WEB server */
fwrite("/var/proc/web/sessiontimeout", $SESSION_TIMEOUT);

/* generate to support widget */
$supportWidget = query("/runtime/func/widget/yahoo");
if($supportWidget == "1")
{
	$INDEX_NAME = "index.php";
}
else
{
	$INDEX_NAME = "index.html";
}

/* if 8mb flash is used, uploadsize need to increase */
$MAXUPLOADSIZE=query("/runtime/httpd/maxuploadsize");
if( $MAXUPLOADSIZE == "" )
{
	$MAXUPLOADSIZE = 3735552; 
}
?>
Tuning {
	NumConnections 15
	BufSize 12288
	InputBufSize 4096
	ScriptBufSize 4096
	NumHeaders 100
	Timeout 60
	ScriptTimeout 60
	MaxUploadSize <?=$MAXUPLOADSIZE?>
}

PIDFile /var/run/httpd.pid
LogGMT On

Control {
	Types {
		text/html { html htm }
		text/xml { xml }
		text/plain { txt }
		image/gif { gif }
		image/jpeg { jpg }
		text/css { css }
		application/ocstream { * }
	}
	Specials {
		Dump { /dump }
		CGI { cgi }
		Imagemap { map }
		Redirect { url }
		Internal { _int torrent-add }
	}
	External {
		/sbin/atp { php txt }
		/sbin/xgi { xgi bin }
		/sbin/sgi { sgi }
		/sbin/btgi { torrents-get 
					torrent-add-url
					torrent-stop 
					torrent-start 
					torrent-remove 
					torrent-get-files 
					torrent-file-get
					torrent-file-set-priority
					app-settings-get 
					app-settings-set 
					torrent-set-props 
					}
	}
	IndexNames { <?=$INDEX_NAME?> }
}

Server {
	Interface <?=$LAN_IF?>
	Virtual {
		AnyHost
<?
		/* The config for HTTP server on LAN side. */
		require($template_root."/httpd/httpd_server.php");
?>
		Control {
			Alias /HNAP1
			Location /www/HNAP1
			External {
				/usr/sbin/hnap { hnap }
			}
			IndexNames { index.hnap }
		}
	}
}

<?

/* The config section for UPnP support in HTTPD. */
if (query("/function/httpd_upnp")==1) { require($template_root."/httpd/httpd_upnp.php"); }

/* The config for HTTP server on WAN side. */
if ($WAN_IF!="" && $HTTP_ALLOW==1)
{
	echo "Server {\n";
	echo "	Interface ".$WAN_IF."\n";
	echo "	Port ".$REMOTE_PORT."\n";
	echo "	Virtual {\n";
	echo "		AnyHost\n";

	require($template_root."/httpd/httpd_server.php");

	echo "	}\n";
	echo "}\n";
}

if ($WAN2_IF!="" && $HTTP_ALLOW==1)
{
	echo "Server {\n";
	echo "	Interface ".$WAN2_IF."\n";
	echo "	Port ".$REMOTE_PORT."\n";
	echo "	Virtual {\n";
	echo "		AnyHost\n";

	require($template_root."/httpd/httpd_server.php");

	echo "	}\n";
	echo "}\n";
}
?>
