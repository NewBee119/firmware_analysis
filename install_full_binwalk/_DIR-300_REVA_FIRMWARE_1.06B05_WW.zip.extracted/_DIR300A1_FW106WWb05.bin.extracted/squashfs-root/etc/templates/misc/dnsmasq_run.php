#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$dnsmasq_pid="/var/run/dnsmasq.pid";

if ($generate_start==1)
{
	if (query("/runtime/router/enable")!=1)
	{
		echo "echo Router function is off, DNS relay will not be enabled !!! > /dev/console\n";
	}
	else if (query("/dnsrelay/mode")==1)
	{
		echo "echo Dnsmasq disabled... > /dev/console\n";
	}
	else
	{
		$dns0  = query("/runtime/wan/inf:1/primarydns");
		$dns1  = query("/runtime/wan/inf:1/secondarydns");
		$dns2  = query("/runtime/wan/inf:2/primarydns");
		$dns3  = query("/runtime/wan/inf:2/secondarydns");
		$bdns  = query("/backupdns/enable");
		$bdns1 = query("/backupdns/dns1");
		$bdns2 = query("/backupdns/dns2");
		$wan2gw= query("/runtime/wan/inf:2/gateway");
		$wan2if= query("/runtime/wan/inf:2/interface");
		echo "#".$dns0.",".$dns1.",".$dns2.",".$dns3."\n";
		$param="";
		if ($dns0 != "") { $param=$param." -S ".$dns0; }
		if ($dns1 != "") { $param=$param." -S ".$dns1; }
		if ($dns2 != "") { $param=$param." -S ".$dns2; }
		if ($dns3 != "") { $param=$param." -S ".$dns3; }
		if ($bdns==1)
		{
			if ($bdns1!="") { $param=$param." -S ".$bdns1; }
			if ($bdns2!="") { $param=$param." -S ".$bdns2; }
		}

		/* For Dual Access mode, always force dnsmasq use all-servers mode */
		if ( query("/wan/rg/inf:1/mode")=="3" && query("/wan/rg/inf:2/mode")!="") /* Russia PPPoE(WAN1) plus WAN2(static or dynamic)*/
		{	$param=$param." --all-servers ";	}		
		else if ( query("/wan/rg/inf:1/mode")=="4" && query("/wan/rg/inf:1/pptp/physical")=="1")/* Russia PPTP(WAN1) plus WAN2(static or dynamic)*/
		{	$param=$param." --all-servers ";	}
		else if ( query("/wan/rg/inf:1/mode")=="5" && query("/wan/rg/inf:1/l2tp/physical")=="1")/* Russia L2TP(WAN1) plus WAN2(static or dynamic)*/
		{	$param=$param." --all-servers ";	}
	
/*		for now, dnsmasq still not support specific string, ex: "flets/"  */
/*
		$pro_id=query("/wan/rg/inf:2/profileid");
		for ("/routing/policyroute/entry")
		{
			if (query("profile") == $pro_id)
			{
				if (query("rule")==0)
				{
					$domain=query("domain");
					$param=$param." -K \"".$domain."\"";
				}
			}
		}
*/

		$urlhome = query("/sys/hostname");
		$lanip   = query("/lan/ethernet/ip");
		fwrite("/etc/hosts", "127.0.0.1 localhost\n");
		fwrite2("/etc/hosts", $lanip." ".$urlhome."\n");
		$adv_dns_param="";
		$adv_dns_enable = query("/advdns/enable");
		if ($adv_dns_enable == "1") 
		{ 				
			echo "echo nameserver 127.0.0.1 > /etc/resolv.conf\n";
			echo "mac=`rgdb -i -g /runtime/layout/wanmac`\n";
			$adv_dns_param="--adv-dns-enable 1 --adv-dns-id $mac";
			
			$advdns_primarydns = query("/advdns/server/primarydns");
			$advdns_secondarydns = query("/advdns/server/secondarydns");				
			
			if($advdns_primarydns=="") { $advdns_primarydns="204.194.232.200"; }
			if($advdns_secondarydns=="") { $advdns_secondarydns="204.194.234.200"; }
											
			$param="";			
			$param=" -S ".$advdns_primarydns." -S ".$advdns_secondarydns;
		}
		echo "echo Start Dnsmasq ... > /dev/console\n";
		echo "dnsmasq".$param." -c 0 -R -x ".$dnsmasq_pid." ".$adv_dns_param."\n";		
	}
}
else
{
	echo "echo Stop Dnsmasq ... > /dev/console\n";
	if (query("/runtime/router/enable")!=1)
	{
		echo "echo Dnsmasq is not enabled ! > /dev/console\n";
	}
	else
	{		
		echo "if [ -f ".$dnsmasq_pid." ]; then\n";
		echo "	PID=`cat ".$dnsmasq_pid."`\n";
		echo "	if [ \"$PID\" != \"0\" -a \"$PID\" != \"\" ]; then\n";
		echo "		kill $PID > /dev/console 2>&1\n";
		echo "	fi\n";
		echo "	rm -f ".$dnsmasq_pid." > /dev/console\n";
		echo "fi\n";
		echo "rm -f $0 > /dev/console\n";
		echo "killall -9 dnsmasq\n";
		
		
		$dns1=query("/runtime/wan/inf:1/primarydns");
		$dns2=query("/runtime/wan/inf:1/secondarydns");
		echo "DNSNOW=`cat /etc/resolv.conf`\n";
		echo "if [ \"$DNSNOW\" = \"nameserver 127.0.0.1\" ]; then\n";
		echo "echo -n > /etc/resolv.conf\n";
		echo "	echo nameserver ".$dns1." >> /etc/resolv.conf\n";
		echo "	echo nameserver ".$dns2." >> /etc/resolv.conf\n";
		echo "fi\n";			
	}
}
?>
# dnsmasq_run.php <<<
