# wan2_dhcp >>>
<? /* vi: set sw=4 ts=4: */
$wanif=query("/runtime/layout/wanif");
$dhcpc_pid="/var/run/udhcpc-".$wanif."-wan2.pid";

if ($generate_start == 1)
{
	anchor("/wan/rg/inf:2/dhcp");
	$hostname	= query("/sys/hostname");
	$autodns	= query("autodns");
	$mtu		= query("mtu");
	if ($mtu == "" || $mtu == "0") { $mtu = "1500"; }

	set("/runtime/wan/inf:2/connecttype", "2");
	anchor("/runtime/wan/inf:2");
	set("connectstatus", "connecting");
	set("ip", "");
	set("netmask", "");
	set("gateway", "");
	set("primarydns", "");
	set("secondarydns", "");
	set("mtu", $mtu);

	echo "echo \"DHCP client on WAN2(".$wanif.") ...\" > /dev/console\n";
	if ($mtu != "" && $mtu != "0") { echo "ifconfig ".$wanif." mtu ".$mtu."\n"; }
	if ($hostname != "") { $HOST=" -H \"".$hostname."\""; }
	echo "rgdb -A ".$template_root."/dhcp/udhcpc_wan2.php > /var/run/udhcpc_wan2.sh\n";
	echo "chmod +x /var/run/udhcpc_wan2.sh\n";
	echo "udhcpc -i ".$wanif." -p ".$dhcpc_pid.$HOST." -s /var/run/udhcpc_wan2.sh -D 2 -R 5 -S 300 &\n";
}
else
{
	echo "echo \"Stop DHCP client on WAN2(".$wanif.") ...\" > /dev/console\n";
	echo "if [ -f ".$dhcpc_pid." ]; then\n";
	echo "	PID=`cat ".$dhcpc_pid."`\n";
	echo "	if [ $PID != 0 ]; then\n";
	echo "		kill -SIGUSR2 $PID > /dev/console 2>&1\n";
	echo "		kill $PID > /dev/console 2>&1\n";
	echo "	fi\n";
	echo "	rm -f ".$dhcpc_pid."\n";
	echo "fi\n";
	echo "ifconfig ".$wanif." 0.0.0.0 > /dev/console\n";
	echo "while route del default gw 0.0.0.0 dev ".$wanif." ; do\n";
	echo "	:\n";
	echo "done\n";
	echo "[ -f ".$template_root."/wandown.sh ] && ".$template_root."/wandown.sh\n";
	echo "rgdb -i -s /runtime/wan/inf:2/connectstatus disconnected\n";
	echo "rgdb -i -s /runtime/wan/inf:2/ip \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:2/netmask \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:2/gateway \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:2/primarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:2/secondarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:2/interface \"\"\n";

    /* force dhcpd restart */
    echo $template_root."/dhcpd.sh\n";

}
?># wan2_dhcp <<<
