#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$cfile	= "/var/run/igmpproxy.conf";
$sfile	= "/etc/templates/igmpproxy_helper.sh";
$wanif	= query("/runtime/wan/inf:1/interface");
$wan2if	= query("/runtime/wan/inf:2/interface");
$lanif	= query("/runtime/layout/lanif");
$lan2if	= query("/runtime/layout/lanif2");
$wlanif	= query("/runtime/layout/wlanif");
$wlan2if = query("/runtime/layout/wlanif2");
if ($wlan2if=="")
{ if (query("/gzone/members/wlan:2/enable") == 1){$wlan2if="wl0.1";} else{$wlan2if="";} }
$enable	= query("/nat/general/igmpproxy/enable");
$enhance = query("/nat/general/igmpproxy/enhancement");

if (query("/runtime/debug_igmp")==1) { $debug = " -v"; }

if ($generate_start==1)
{
	if ($enable==1)
	{
		echo "echo Start IGMP proxy ... > /dev/console\n";
		if (query("/runtime/router/enable")==1)
		{
			if($wanif != "")	{fwrite2($cfile,  $wanif." upstream 1 0\n");}
			if($wan2if!= "")	{fwrite2($cfile,  $wan2if." upstream 1 0\n");}
		}
		else
		{
        	echo "echo Bridge mode selected, there are no upstreams for IGMP proxy! > /dev/console\n";
		}
		if($lanif != "")	{fwrite2($cfile,  $lanif." downstream 1 0\n");}
		if($lan2if!= "")	{fwrite2($cfile, $lan2if." downstream 1 0\n");}
		echo "igmpproxy -c ".$cfile." -s ".$sfile.$debug." &\n";
		if($enhance == 1)
		{
			if($lanif != "")	
			{
				echo "echo enable > /proc/net/br_igmpp_".$lanif."\n";
				echo "echo enable > /proc/net/br_mac_".$lanif."\n";
			}
			if($lan2if != "")	
			{
				echo "echo enable > /proc/net/br_igmpp_".$lan2if."\n";
				echo "echo enable > /proc/net/br_mac_".$lan2if."\n";
			}
			if($wlanif != "") 	{echo "echo \"setwl ".$wlanif."\" > /proc/net/br_igmpp_".$lanif."\n";}
			if($wlan2if != "") 	{echo "echo \"setwl ".$wlan2if."\" > /proc/net/br_igmpp_".$lan2if."\n";}
		}
		echo "echo 1 > /proc/igmp_snoop \n";
	}
	else
	{
		echo "echo 0 > /proc/igmp_snoop \n";
		echo "echo IGMP proxy is not enabled!\n";
	}
}
else
{
	if ($enable == 1)
	{
		echo "echo Stop IGMP proxy ... > /dev/console\n";
		echo "killall igmpproxy\n";
		echo "rm -f ".$cfile."\n";
		if($enhance == 1)
		{
			if($lanif != "")
			{
				echo "echo disable > /proc/net/br_igmpp_".$lanif."\n";
				echo "echo disable > /proc/net/br_mac_".$lanif."\n";
			}
			if($lan2if != "")	
			{
				echo "echo disable > /proc/net/br_igmpp_".$lan2if."\n";
				echo "echo disable > /proc/net/br_mac_".$lan2if."\n";
			}
		}
		echo "sleep 2\n";
	}
	else
	{
		echo "echo IGMP proxy is not started ! > /dev/console\n";
	}
}
?>
