# flush_macfilter.php >>>
<? /* vi: set sw=4 ts=4: */ ?>
iptables -t nat -F PRE_MACFILTER
iptables -F FOR_MACFILTER
iptables -F INP_MACFILTER
iptables -t nat -F PRE_GZ_MACFILTER
<?
$enable	= query("/security/macfilter/enable");
$action	= query("/security/macfilter/action");
$log	= query("/security/log/droppacketinfo");
$logstr	= "LOG --log-level info --log-prefix DRP:004:";
$count	= 0;

$entry = 0;
for("/security/macfilter/entry") { $entry++; }

$IPTCMD = "iptables -t nat -A PRE_MACFILTER -i ".$lanif;
$IPTCMD1 = "iptables -A FOR_MACFILTER -i ".$lanif;
$IPTCMD2 = "iptables -A INP_MACFILTER -i ".$lanif;

if ($entry == 0 || $enable == 0)
{	
	echo "logger -p 192.0 \"SYS:007\"\n";
}
else if ($action == 1)
{
	echo "logger -p 192.0 \"SYS:009\"\n";
	for("/security/macfilter/entry")
	{
		$enable	= query("enable");
		$mac	= query("mac");
		if ($mac!="" && $enable!=0)
		{
			$TIMESTRING = "";
			$UNIQUEID   = query("schedule/id");
			if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
			echo $IPTCMD." -m mac --mac-source ".$mac.$TIMESTRING." -j RETURN\n";
			echo $IPTCMD1." -m mac --mac-source ".$mac.$TIMESTRING." -j RETURN\n";
			$count++;
		}
	}
	if ($log == 1) 
	{ 
		echo $IPTCMD." -j ".$logstr."\n"; 
		echo $IPTCMD1." -j ".$logstr."\n"; 
	}
	echo $IPTCMD." -j DROP\n";
	echo $IPTCMD1." -j DROP\n";
}
else if ($action == 0)	
{
	echo "logger -p 192.0 \"SYS:008\"\n";
	for("/security/macfilter/entry")
	{
		$enable	= query("enable");
		$mac	= query("mac");
		if ($mac!="" && $enable!=0)
		{
			$TIMESTRING = "";
			$UNIQUEID   = query("schedule/id");
			if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
			if ($log == 1) 
			{ 
				echo $IPTCMD." -m mac --mac-source ".$mac.$TIMESTRING." -j ".$logstr."\n"; 
				echo $IPTCMD1." -m mac --mac-source ".$mac.$TIMESTRING." -j ".$logstr."\n"; 
			}
			echo $IPTCMD." -m mac --mac-source ".$mac.$TIMESTRING." -j DROP\n";
			echo $IPTCMD1." -m mac --mac-source ".$mac.$TIMESTRING." -j DROP\n";
			$count++;
		}
	}
}
else if ($action == 2)	
{
	echo "logger -p 192.0 \"SYS:008\"\n";
	for("/security/macfilter/entry")
	{
		$enable	= query("enable");
		$mac	= query("mac");
		if ($mac!="" && $enable!=0)
		{
			$TIMESTRING = "";
			$UNIQUEID   = query("schedule/id");
			if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
			if ($log == 1) 
			{ 
				echo $IPTCMD1." -m mac --mac-source ".$mac.$TIMESTRING." -j ".$logstr."\n"; 
				echo $IPTCMD2." -m mac --mac-source ".$mac.$TIMESTRING." -j ".$logstr."\n"; 
			}

			$tcp_reject = " -j REJECT --reject-with tcp-reset\n";
			$udp_reject = " -j REJECT --reject-with icmp-host-unreachable\n";
			echo $IPTCMD1." -p tcp "." -m mac --mac-source ".$mac.$TIMESTRING.$tcp_reject;
			echo $IPTCMD2." -p tcp "." -m mac --mac-source ".$mac.$TIMESTRING.$tcp_reject;
			echo $IPTCMD1." -p ! tcp "." -m mac --mac-source ".$mac.$TIMESTRING.$udp_reject;
			echo $IPTCMD2." -p ! tcp "." -m mac --mac-source ".$mac.$TIMESTRING.$udp_reject;
			$count++;
		}
	}
}
set("/runtime/rgfunc/macfilter", $count);

/* guest zone mac filter. */
$lock_client	= query("/gzone/lockclient/enable");
$IPTCMD			= "iptables -t nat -A PRE_GZ_MACFILTER -i ".$lan2if;
if($lock_client==1)
{
	/* allow the list, and deny others. */
	$gz_count = 0;
	for("/gzone/macfilter/entry")
	{
		$mac    = query("mac");
		if ($mac != "")
		{
			echo $IPTCMD." -m mac --mac-source ".$mac." -j RETURN\n";
			$gz_count++;
		}
	}
	echo $IPTCMD." -j DROP\n";
}
set("/runtime/rgfunc/gzone/macfilter", $gz_count);
?>
# flush_macfilter.php <<< 
