# wan2_static >>>
<? /* vi: set sw=4 ts=4: */
$wanif = query("/runtime/layout/wanif");

if ($generate_start == 1)
{
	anchor("/wan/rg/inf:2/static");
	$ipaddr  = query("ip");
	$netmask = query("netmask");
	$gateway = query("gateway");
	$mtu     = query("mtu");		if ($mtu=="" || $mtu=="0") { $mtu="1500"; }
	$pri_dns = query("primarydns");
	$sec_dns = query("secondarydns");

	set("/runtime/wan/inf:2/connecttype", "1");
	anchor("/runtime/wan/inf:2");
	set("connectstatus", "connected");
	set("ip", $ipaddr);
	set("netmask", $netmask);
	set("gateway", $gateway);
	set("primarydns", $pri_dns);
	set("secondarydns", $sec_dns);
	set("interface", $wanif);
	set("mtu", $mtu);

	$param="";
	if ($netmask != "" && $netmask != "0.0.0.0")	{ $param=$param." netmask ".$netmask; }
	if ($mtu != "" && $mtu != "0")					{ $param=$param." mtu ".$mtu; }
	echo "ifconfig ".$wanif." ".$ipaddr.$param."\n";
	echo "echo \"Start WAN2(".$wanif."),".$ipaddr."/".$netmask." ...\" > /dev/console\n";

	if ($pri_dns != "" && $pri_dns != "0.0.0.0")
	{
		echo "if [ \"`chnet ".$pri_dns." ".$netmask."`\" != \"`chnet ".$ipaddr." ".$netmask."`\" ]; then\n";
		echo "	route add -host ".$pri_dns." gw ".$gateway." dev ".$wanif."\n";
		echo "fi\n";
	}
	if ($sec_dns != "" && $sec_dns != "0.0.0.0")
	{
		echo "if [ \"`chnet ".$sec_dns." ".$netmask."`\" != \"`chnet ".$ipaddr." ".$netmask."`\" ]; then\n";
		echo "	route add -host ".$sec_dns." gw ".$gateway." dev ".$wanif."\n";
		echo "fi\n";
	}

	echo $template_root."/wanup.sh > /dev/console\n";
}
else
{
	echo "echo \"Stop WAN 2...\" > /dev/console\n";
	echo "[ -f ".$template_root."/wandown.sh ] && ".$template_root."/wandown.sh > /dev/console\n";
	echo "ifconfig ".$wanif." 0.0.0.0 > /dev/console 2>&1\n";
	echo "route del default gw 0.0.0.0 dev ".$wanif." > /dev/console\n";
	echo "rgdb -i -s /runtime/wan/inf:2/connectstatus disconnected\n";
	echo "rgdb -i -s /runtime/wan/inf:2/ip \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:2/netmask \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:2/gateway \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:2/primarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:2/secondarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:2/interface \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:2/mtu \"\"\n";
}
?># wan2_static <<<
