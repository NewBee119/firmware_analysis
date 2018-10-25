# flush_dmz.php >>>
iptables -t nat -F PRE_DMZ
iptables -t mangle -F PRE_MARK
<?
/* vi: set sw=4 ts=4:
 * generating rules for DMZ.
 */
$dmzcount	= 0;
$dmzenable	= query("/nat/dmzsrv/enable");
$CMD_MAN = "iptables -t mangle -A PRE_MARK";
$TARGET_MAN = " -j MARK --set-mark 1\n";
$TARGET_MAN2 = " -j MARK --set-mark 2\n";
if ($dmzenable==1)
{
	$dmzaddr = query("/nat/dmzsrv/ip");

	if ($wanif!="" && $wanip!="" && $dmzaddr!="")
	{
		$UNIQUEID = query("/nat/dmzsrv/schedule/id");
		$TIMESTRING = "";
		if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
		echo $CMD_MAN." -d ".$wanip.$TARGET_MAN;
		echo "iptables -t nat -A PRE_DMZ -i ".$wanif." -d ".$lanip." -j DROP\n";
		echo "iptables -t nat -A PRE_DMZ -d ".$wanip.$TIMESTRING." -j DNAT --to-destination ".$dmzaddr."\n";
		$dmzcount+=2;
		if ($wan2if!="" && $wan2ip!="")
		{
			echo $CMD_MAN." -d ".$wan2ip.$TARGET_MAN2;
			echo "iptables -t nat -A PRE_DMZ -d ".$lanip." -j DROP\n";
			echo "iptables -t nat -A PRE_DMZ -d ".$wan2ip.$TIMESTRING." -j DNAT --to-destination ".$dmzaddr."\n";
			$dmzcount+=2;
		}
	}
	echo "logger -p 192.0 \"SYS:018[".$dmzaddr."]\"\n";
}
else
{
	echo "logger -p 192.0 \"SYS:019\"\n";
}

set("/runtime/rgfunc/dmz", $dmzcount);
?># flush_dmz.php <<<
