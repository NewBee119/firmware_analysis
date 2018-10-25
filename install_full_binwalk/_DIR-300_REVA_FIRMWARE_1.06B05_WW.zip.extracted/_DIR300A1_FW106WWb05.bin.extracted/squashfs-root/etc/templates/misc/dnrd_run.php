#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$dnrd_pid="/var/run/dnrd.pid";
$del_dns_route="/var/run/del_dns_route.sh";

if ($generate_start==1)
{
	if (query("/runtime/router/enable")!=1)
	{
		echo "echo Router function is off, DNS relay will not be enabled !!! > /dev/console\n";
	}
	else if (query("/dnsrelay/mode")==1)
	{
		echo "echo DNRD disabled... > /dev/console\n";
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
		if ($dns0 != "") { $param=$param." -s ".$dns0; }
		if ($dns1 != "") { $param=$param." -s ".$dns1; }
		if (query("/wan/rg/inf:1/pptp/physical") == "1" || query("/wan/rg/inf:1/l2tp/physical") == "1")
		{
			if ($dns2 != "") { $param=$param." -s ".$dns2; }
			if ($dns3 != "") { $param=$param." -s ".$dns3; }
		}
		else
		{
			if ($dns2 != "") { $param=$param." -S ".$dns2; }
			if ($dns3 != "") { $param=$param." -S ".$dns3; }
		}
		if ($bdns==1)
		{
			if ($bdns1!="") { $param=$param." -s ".$bdns1; }
			if ($bdns2!="") { $param=$param." -s ".$bdns2; }
		}
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

		$urlhome = query("/sys/hostname");
		$lanip   = query("/lan/ethernet/ip");
		fwrite("/etc/hosts", "127.0.0.1 localhost\n");
		fwrite2("/etc/hosts", $lanip." ".$urlhome."\n");

		echo "echo Start DNRD ... > /dev/console\n";
		echo "dnrd".$param." -c off &\n";
/*
		echo "if [ -f ".$dnrd_pid." ]; then\n";
		echo "	PID=`cat ".$dnrd_pid."`\n";
		echo "	if [ \"$PID\" = \"0\" -o \"$PID\" = \"\" ]; then\n";
		echo "		echo	dnrd fail, try again...\n";
		echo "		sleep 1\n";
		echo "		dnrd".$param." -c off &\n";
		echo "	fi\n";
		echo "fi\n";
*/
/*
		fwrite( $del_dns_route, "#!/bins/sh\n");
		fwrite2($del_dns_route, "echo [$0] ... > /dev/console\n");
		if ($wan2gw != "" && $wan2if != "")
		{
			if ($dns2 != "")
			{
				echo "route add -host ".$dns2." gw ".$wan2gw." dev ".$wan2if."\n";
				fwrite2($del_dns_route, "route del -host ".$dns2." gw ".$wan2gw." dev ".$wan2if."\n");
			}
			if ($dns3 != "")
			{
				echo "route add -host ".$dns3." gw ".$wan2gw." dev ".$wan2if."\n";
				fwrite2($del_dns_route, "route del -host ".$dns3." gw ".$wan2gw." dev ".$wan2if."\n");
			}
		}
		fwrite2($del_dns_route, "rm -f ".$del_dns_route." > /dev/console\n");
 */
	}
}
else
{
	echo "echo Stop DNRD ... > /dev/console\n";
	if (query("/runtime/router/enable")!=1)
	{
		echo "echo DNRD is not enabled ! > /dev/console\n";
	}
	else
	{
		/*
		echo "[ -f ".$del_dns_route." ] && sh ".$del_dns_route." > /dev/console\n";
		 */
		echo "if [ -f ".$dnrd_pid." ]; then\n";
		echo "	PID=`cat ".$dnrd_pid."`\n";
		echo "	if [ \"$PID\" != \"0\" -a \"$PID\" != \"\" ]; then\n";
		echo "		kill -9 $PID > /dev/console 2>&1\n";
		echo "	fi\n";
		echo "	rm -f ".$dnrd_pid." > /dev/console\n";
		echo "fi\n";
		echo "rm -f $0 > /dev/console\n";
	}
}
?>
# dnrd_run.php <<<
