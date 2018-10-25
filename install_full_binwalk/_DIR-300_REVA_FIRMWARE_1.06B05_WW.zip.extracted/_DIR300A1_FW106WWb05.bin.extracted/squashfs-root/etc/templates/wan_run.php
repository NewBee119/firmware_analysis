#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$mode = query("/wan/rg/inf:1/mode");
if (query("/runtime/router/enable")!=1)
{
	echo "echo Bridge mode selected ! > /dev/console\n";
	
	//if in the ap mode, restore to original wan MAC
	$wanif=query("/runtime/layout/wanif");
	$orig_wanmac=query("/runtime/layout/wanmac");
	echo "ifconfig ".$wanif." down\n";
	echo "ifconfig ".$wanif." hw ether ".$orig_wanmac." up\n";
	echo "rgdb -i -s /runtime/wan/inf:1/mac ".$orig_wanmac."\n";
	echo "echo Restore to original wan MAC! > /dev/console\n";
	
	if ($mode==1) {	require($template_root."/wan_static.php"); }
	else { $mode=2;	require($template_root."/wan_dhcp.php"); }
}
else
{
	$turbonat="1";

	// Appex
	$appex_func=query("/runtime/func/appex");
	$appex_en=query("/apx/enable");
	if($appex_func == 1 && $appex_en == 1)
	{
		echo "sh ".$template_root."/appex.sh\n";
	}

	/* netsniper */
	$netsniper = query("/runtime/func/netsniper");
	$netsniper_enable = query("/wan/rg/inf:1/netsniper_enable");
	if($netsniper==1)
	{
		/* enable netsniper, must remove sw_tcpip module. Or else web access from lan will fail! */
		if($netsniper_enable == 1)
		{
			$turbonat="0";
			echo "echo 1024 > /proc/sys/net/ipv4/ip_personality_sport\n";
			echo "echo 1 > /proc/sys/net/ipv4/ip_personality_enable\n";
		}
		else
		{
			echo "echo 0 > /proc/sys/net/ipv4/ip_personality_enable\n";
			echo "echo 0 > /proc/sys/net/ipv4/ip_personality_sport\n";
		}
	}

	if($turbonat == 1)
	{ echo "insmod /lib/modules/sw_tcpip.o\n"; }
	else
	{ echo "rmmod sw_tcpip\n"; }

	if		($mode==1)	{ require($template_root."/wan_static.php"); }
	else if	($mode==2)	{ require($template_root."/wan_dhcp.php"); }
	else if	($mode==3)
	{
		require($template_root."/wan_pppoe.php");
		$wan2_mode = query("/wan/rg/inf:2/mode");
		if		($wan2_mode=="1") { require($template_root."/wan2_static.php");}
		else if ($wan2_mode=="2") { require($template_root."/wan2_dhcp.php");}
	}
	else if ($mode==4)	{ require($template_root."/wan_ip_setup.php"); }
	else if ($mode==5)	{ require($template_root."/wan_ip_setup.php"); }
	else if ($mode==6)	{ require($template_root."/wan_ppp3g.php"); }
	else if ($mode==7)	{ require($template_root."/wan_dhcp.php"); }
	else if ($mode==8)	{ require($template_root."/wan_dhcpplus.php"); }
	else
	{
		echo "echo \"Uknown WAN mode : ".$mode."\" > /dev/console\n";
	}
}
?>
