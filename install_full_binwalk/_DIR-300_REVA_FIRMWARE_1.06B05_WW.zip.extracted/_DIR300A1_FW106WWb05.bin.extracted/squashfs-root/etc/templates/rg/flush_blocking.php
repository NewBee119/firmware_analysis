# flush_blocking >>>
<? /* vi: set sw=4 ts=4: */ ?>
iptables -F INP_BLOCKING
iptables -F FOR_BLOCKING
<?
/* URL Blocking */
$log		= query("/security/log/droppacketinfo");
$logtgt		= " -j LOG --log-level info --log-prefix";
$urlcount	= 0;
$domaincount= 0;
$IPTURLCMD	= "iptables -A FOR_BLOCKING -p tcp --dport 80 ";

if (query("/security/urlblocking/enable")==1)
{
	$log_prefix=" 'DRP:007:'\n";
	if (query("/security/urlblocking/action")=="1")
	{
		for ("/security/urlblocking/entry")
		{
			$enable = query("enable");
			$target_url=query("url");
			if ($target_url!="" && $enable!=0)
			{
				$UNIQUEID = query("schedule/id");
				$TIMESTRING = "";
				if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
				echo "noHttpUrl=`echo '".$target_url."'|sed 's/^http:\\/\\/\/\/g'|sed 's/\\/\*\$\/\/g'`\n";
				echo $IPTURLCMD.$TIMESTRING." -m string --url \$noHttpUrl -j ACCEPT\n";
				$urlcount++;
			}
		}

		if ($log == 1) {echo $IPTURLCMD." -m string --http_req ".$logtgt.$log_prefix;}
		echo $IPTURLCMD." -m string --http_req -j DROP\n";
		$urlcount++;
	}
	else if (query("/security/urlblocking/action")=="2")
	{
		for ("/security/urlblocking/entry")
		{
			$enable = query("enable");
			$target_url=query("url");
			if ($target_url!="" && $enable!=0)
			{
				$UNIQUEID = query("schedule/id");
				$TIMESTRING = "";
				if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
				echo "noHttpUrl=`echo '".$target_url."'|sed 's/^http:\\/\\/\/\/g'|sed 's/\\/\*\$\/\/g'`\n";
				if ($log == 1) {echo $IPTURLCMD.$TIMESTRING." -m string --url \$noHttpUrl ".$logtgt.$log_prefix;}
				echo $IPTURLCMD.$TIMESTRING." -m string --url \$noHttpUrl -j REJECT --reject-with tcp-reset\n";
				$urlcount++;
			}
		}
		echo "logger -p 192.0 \"SYS:011\"\n";
	}
	else
	{
		for ("/security/urlblocking/entry")
		{
			$enable = query("enable");
			$target_url=query("url");
			if ($target_url!="" && $enable!=0)
			{
				$UNIQUEID = query("schedule/id");
				$TIMESTRING = "";
				if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
				echo "noHttpUrl=`echo '".$target_url."'|sed 's/^http:\\/\\/\/\/g'|sed 's/\\/\*\$\/\/g'`\n";
				if ($log == 1) {echo $IPTURLCMD.$TIMESTRING." -m string --url \$noHttpUrl ".$logtgt.$log_prefix;}
				echo $IPTURLCMD.$TIMESTRING." -m string --url \$noHttpUrl -j DROP\n";
				$urlcount++;
			}
		}
		echo "logger -p 192.0 \"SYS:011\"\n";
	}
}
else
{
	echo "logger -p 192.0 \"SYS:010\"\n";
}
set("/runtime/rgfunc/urlfilter",$urlcount);

/* Domain Blocking */
if (query("/security/domainblocking/enable")==1)
{
	$log_prefix	= " 'DRP:007:'\n";
	if (query("/security/domainblocking/action")==1)
	{
		for ("/security/domainblocking/entry")
		{
			$domain = query("domain");
			if($domain!="")
			{
				$UNIQUEID = query("schedule/id");
				$TIMESTRING = "";
				if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
				echo "nohttpprefix=`echo '".$domain."'|sed 's/^.*:\\/\\/\/\/g'|sed 's/\\/\*\$\/\/g'`\n";

				echo "iptables -A INP_BLOCKING -p udp --dport 53 ".$TIMESTRING." -m string --dns \$nohttpprefix -j ACCEPT\n";
				echo "iptables -A FOR_BLOCKING -p udp --dport 53 ".$TIMESTRING." -m string --dns \$nohttpprefix -j ACCEPT\n";
				$domaincount++;
			}
		}
		if ($log == 1)
		{
			echo "iptables -A INP_BLOCKING -p udp --dport 53 ".$logtgt.$log_prefix;
			echo "iptables -A FOR_BLOCKING -p udp --dport 53 ".$logtgt.$log_prefix;
		}
		echo "iptables -A INP_BLOCKING -p udp --dport 53 -j DROP\n";
		echo "iptables -A FOR_BLOCKING -p udp --dport 53 -j DROP\n";
		echo "logger -p 192.0 \" SYS:015 \"\n";
		$domaincount++;
	}
	else
	{
		for ("/security/domainblocking/entry")
		{
			$domain = query("domain");
			if($domain!="")
			{
				$UNIQUEID = query("schedule/id");
				$TIMESTRING = "";
				if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
				echo "nohttpprefix=`echo '".$domain."'|sed 's/^.*:\\/\\/\/\/g'|sed 's/\\/\*\$\/\/g'`\n";
				if ($log == 1)
				{
					echo "iptables -A INP_BLOCKING -p udp --dport 53 ".$TIMESTRING." -m string --dns \$nohttpprefix ".$logtgt.$log_prefix;
					echo "iptables -A FOR_BLOCKING -p udp --dport 53 ".$TIMESTRING." -m string --dns \$nohttpprefix ".$logtgt.$log_prefix;
				}
				echo "iptables -A INP_BLOCKING -p udp --dport 53 ".$TIMESTRING." -m string --dns \$nohttpprefix -j DROP\n";
				echo "iptables -A FOR_BLOCKING -p udp --dport 53 ".$TIMESTRING." -m string --dns \$nohttpprefix -j DROP\n";
				$domaincount++;
			}
		}
		echo "logger -p 192.0 \" SYS:014 \"\n";
	}
}
else
{
	echo "logger -p 192.0 \"SYS:013\"\n";
}
set("/runtime/rgfunc/domainfilter",$domaincount);
?>
# flush_blocking <<< 
