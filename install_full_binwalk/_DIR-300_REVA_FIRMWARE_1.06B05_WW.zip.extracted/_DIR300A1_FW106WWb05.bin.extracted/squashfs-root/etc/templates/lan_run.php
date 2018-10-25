#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$lanif=query("/runtime/layout/lanif");
$igmpproxy_path="/usr/sbin/igmpproxy";
$arp_en = query("/security/arp/enable");

if ($generate_start==1)
{
	if (query("/runtime/router/enable")!=1)
	{
		echo "echo Bridge mode selected, LAN is disabled ! > /dev/console\n";
	}
	else
	{
		$lanmac=query("/runtime/layout/lanmac");
		set("/runtime/sys/info/lanmac", $lanmac);

		anchor("/lan/ethernet");
		$ipaddr = query("ip");
		$subnet = query("netmask");
		echo "echo \"Start LAN (".$lanif."/".$ipaddr."/".$subnet.")...\" > /dev/console\n";
		echo "ifconfig ".$lanif." ".$ipaddr;
		if ($subnet != "") { echo " netmask ".$subnet; }
		echo "\n";

		$dhcpd_if=$lanif;
		$dhcpd_clearleases=1;
		require($template_root."/dhcp/dhcpd.php");
		
		/* prepare /etc/hosts, lpd need it. */
		$hostname = query("/sys/hostname");
		echo "hostname ".$hostname."\n";
        fwrite("/etc/hosts", "127.0.0.1 localhost\n");
        fwrite2("/etc/hosts", $ipaddr." ".$hostname."\n");
		echo "if [ -f ".$igmpproxy_path." ]; then\n";
		echo "echo \"Start igmp ...\" > /dev/console\n";
		echo "\t".$template_root."/igmpproxy.sh start\n";
		echo "fi\n";
		if	(query("/runtime/func/router_netbios")==1)
		{
			echo "netbios &\n";
			echo "echo \"Start router netbios function ...\" > /dev/console\n";
		}

		$logenbale=query("/sys/log/logserverenable");
		$logsrv=query("/sys/log/logserver");
		if ($logenbale == "1" && $logsrv != "")
		{
			echo "echo \"restart syslog\" > /dev/console\n";
			echo $template_root."/logs.sh restart  > /dev/console\n";
		}
		if($arp_en == "1")
		{
			echo "echo \"restart arp-spoof\" > /dev/console\n";
			echo $template_root."/arp_spoof.sh restart > /dev/console\n";
		}
	}
}
else
{
	if (query("/runtime/router/enable")!=1)
	{
		echo "echo Bridge mode selected, LAN is disabled ! > /dev/console\n";
	}
	else
	{
		echo "echo \"Stop LAN (".$lanif.")...\" > /dev/console\n";
		if	(query("/runtime/func/router_netbios")==1)
		{
			echo "killall netbios\n";
			echo "echo \"Stop router netbios function ...\" > /dev/console\n";
		}
		echo "if [ -f ".$igmpproxy_path." ]; then\n";
		echo "\techo \"Stop igmp ...\" > /dev/console\n";
		echo "\t".$template_root."/igmpproxy.sh stop\n";
		echo "fi\n";
		$dhcpd_if=$lanif;
		$dhcpd_clearleases=0;
		require($template_root."/dhcp/dhcpd.php");
		echo "ifconfig ".$lanif." 0.0.0.0 > /dev/console 2>&1\n";
		if($arp_en == "1")
		{
			echo "echo \"stop arp-spoof\" > /dev/console\n";
			echo $template_root."/arp_spoof.sh stop > /dev/console\n";
		}
	}
}
?>
