# VPN passthrough >>>
iptables -F FOR_VPN
<? /* vi: set sw=4 ts=4: */
$pptp	= query("/nat/passthrough/pptp");
$ipsec	= query("/nat/passthrough/ipsec");
$l2tp	= query("/nat/passthrough/l2tp");
$rtsp	= query("/nat/passthrough/rtsp");

$count=0;
if ($pptp != 0)
{
	echo "logger -p 192.0 SYS:016[PPTP]\n";
}
else
{
	echo "iptables -A FOR_VPN -p tcp --dport 1723 -j DROP\n"; 
	echo "logger -p 192.0 SYS:017[PPTP]\n";
	$count++;
}

if ($ipsec != 0)
{
	echo "logger -p 192.0 SYS:016[IPSec]\n";
}
else
{
	echo "iptables -A FOR_VPN -p udp --dport 500 -j DROP\n"; 
	echo "iptables -A FOR_VPN -p udp --dport 4500 -j DROP\n";
	echo "iptables -A FOR_VPN -p ah -j DROP\n"; 
	echo "iptables -A FOR_VPN -p esp -j DROP\n"; 
	echo "logger -p 192.0 SYS:017[IPSec]\n";
	$count++;
}

if($l2tp!=0)
{
	echo "logger -p 192.0 SYS:016[L2TP]\n";
}
else
{
	echo "iptables -A FOR_VPN -p udp --dport 500 -j DROP\n";
	echo "iptables -A FOR_VPN -p udp --dport 1701 -j DROP\n";
	echo "logger -p 192.0 SYS:017[L2TP]\n";
	$count++;
}

if(query("/runtime/func/rtsp") == "1")
{
	if($rtsp==1)
	{
		echo "logger -p 192.0 SYS:016[RTSP]\n";
	}
	else
	{
		echo "iptables -A FOR_VPN -p tcp --dport 554 -j DROP\n";
		echo "iptables -A FOR_VPN -p tcp --dport 557 -j DROP\n";
		echo "logger -p 192.0 SYS:017[RTSP]\n";
		$count++;
	}
}

set("/runtime/rgfunc/vpn", $count);
?>
# VPN passthrough <<<
