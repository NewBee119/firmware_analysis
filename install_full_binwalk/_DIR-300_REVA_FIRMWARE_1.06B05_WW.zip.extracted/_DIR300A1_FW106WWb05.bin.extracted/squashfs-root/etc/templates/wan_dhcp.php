# wan_dhcp >>>
<? /* vi: set sw=4 ts=4: */
$router = query("/runtime/router/enable");
if ($router==1)	{ $wanif=query("/runtime/layout/wanif"); }
else			{ $wanif=query("/runtime/layout/lanif"); }

$dhcpc_pid="/var/run/udhcpc-".$wanif.".pid";

if ($generate_start == 1)
{
	echo "echo \"It is the DHCP mod start!!\" > /dev/console\n";
	anchor("/wan/rg/inf:1/dhcp");
	$hostname	= query("/sys/hostname");
	$clonemac	= query("clonemac");
	$autodns	= query("autodns");
	$mtu		= query("mtu");
	//add for use unicast
	$unicast	= query("unicast");
	if (query("/runtime/func/dhcp_unicast")==1)
	{
		if ($unicast == 1){$use_unicast="-u";}else{$use_unicast="";}
	}
	else
	{
		$use_unicast="";
	}
	if ($mtu == "" || $mtu == "0") { $mtu = "1500"; }

	if ($router == 1)
	{
		$orig_wanmac=query("/runtime/layout/wanmac");
		$curr_wanmac=query("/runtime/wan/inf:1/mac");
		require($template_root."/clone_wanmac.php");
	}

	set("/runtime/wan/inf:1/connecttype", $mode);
	anchor("/runtime/wan/inf:1");
	set("connectstatus", "connecting");
	set("ip", "");
	set("netmask", "");
	set("gateway", "");
	set("primarydns", "");
	set("secondarydns", "");
	set("mtu", $mtu);

	echo "echo \"DHCP client on WAN(".$wanif.") CloneMAC(".$clonemac.") ...\" > /dev/console\n";
	if ($mtu != "" && $mtu != "0") { echo "ifconfig ".$wanif." mtu ".$mtu."\n"; }
	if ($hostname != "") { $HOST=" -H \"".$hostname."\""; }
	echo "rgdb -A ".$template_root."/dhcp/udhcpc.php > /var/run/udhcpc.sh\n";
	echo "chmod +x /var/run/udhcpc.sh\n";
	//echo "sleep 1\n"; // peter
	//add $use_unicast "-u" for use unicast
	echo "udhcpc -i ".$wanif." -p ".$dhcpc_pid.$HOST." -s /var/run/udhcpc.sh -D 2 -R 5 -S 300 ".$use_unicast."&\n";
	
	/*----------8021x--------------------------------------------*/
	if (query("/runtime/func/w8021x")=="1" && query("/w8021x/dhenable")!="on")
	{
		echo "echo 802.1x authentication is disabled ! > /dev/console\n";
		echo "echo \"Stop 802.1x Authentication on ".$wanif."...\" > /dev/console\n";
		//tommy 05.11
		set("/runtime/w8021x/auth", "");
		///
		echo "killall xsupplicant\n"; 
	}
	else
	{
		if(query("/runtime/func/w8021x")=="1" && query("/w8021x/dhenable")=="on")
		{
  		anchor("/w8021x");
  		$type = query("dhtype");
  		$auth = query("dhauth");
  		$username = query("dhuser");
  		$password = query("dhpassword");
  		$1xConfigFilePath = "/var/1xConfig.conf";
  		$phase1 = "";
  		$phase2 = "";
  
  		if($type=="1") {$phase1="md5";} /* MD5 */
  		if($type=="2") {$phase1="peap"; $phase2="mschapv2";} /* PEAP: and it only support MSCHAPv2 */
  		if($type=="3") {$phase1="ttls";} /* TTLS */
  		
  
  		if($auth=="1") {$phase2="pap";} /* PAP */
  		if($auth=="2") {$phase2="chap";} /* CHAP */
  		if($auth=="3") {$phase2="mschap";} /* MSCHAP */
  		if($auth=="4") {$phase2="mschapv2";} /* MSCHAPv2 */
  				
  		fwrite($1xConfigFilePath, "network_list = all\n");
  		fwrite2($1xConfigFilePath, "destination = multicast\n");
  		fwrite2($1xConfigFilePath, "default_netname = default\n");
  		fwrite2($1xConfigFilePath, "logfile = /var/log/xsupplicant.log\n");  
  		fwrite2($1xConfigFilePath, "default{\n");
  		fwrite2($1xConfigFilePath, "\ttype = wired\n");
  		fwrite2($1xConfigFilePath, "\tallow_types=all\n");
  		fwrite2($1xConfigFilePath, "\tidentity = \"".$username."\"\n");
  		
  		fwrite2($1xConfigFilePath, "\teap-".$phase1."{\n");
  		if($type=="1") /* MD5 */
  		{
  			fwrite2($1xConfigFilePath, "\t\tusername=\"".$username."\"\n");
  			fwrite2($1xConfigFilePath, "\t\tpassword=\"".$password."\"\n");
  		}
  		if($type=="2" || $type=="3") /* PEAP and TTLS */
  		{
  			
  			fwrite2($1xConfigFilePath, "\t\troot_cert=NONE\n"); 
  
  			fwrite2($1xConfigFilePath, "\t\tchunk_size = 1398\n");
  			fwrite2($1xConfigFilePath, "\t\trandom_file = /dev/urandom\n");
  			fwrite2($1xConfigFilePath, "\t\tsession_resume = yes\n");
  			if($type=="2") /* PEAP */
  			{
  				
  				fwrite2($1xConfigFilePath, "\t\tallow_types = all\n");
  				fwrite2($1xConfigFilePath, "\t\teap-".$phase2."{\n");
  			}
  			if($type=="3") /* TTLS */
  			{
  				fwrite2($1xConfigFilePath, "\t\tphase2_type=".$phase2."\n");
  				fwrite2($1xConfigFilePath, "\t\t".$phase2."{\n");
  			}
  			fwrite2($1xConfigFilePath, "\t\t\tusername=\"".$username."\"\n");
  			fwrite2($1xConfigFilePath, "\t\t\tpassword=\"".$password."\"\n");
  			fwrite2($1xConfigFilePath, "\t\t}\n");
  		}
  
  		fwrite2($1xConfigFilePath, "\t}\n");
  		
  		fwrite2($1xConfigFilePath, "}\n");
  
  		echo "ifconfig ".$wanif." promisc\n";
  		echo "ifconfig ".$wanif." down\n";
  		echo "ifconfig ".$wanif." up\n";
  
  		echo "echo \"Start 802.1x Authentication on ".$wanif."...\" > /dev/console\n";
  		echo "xsupplicant -c ".$1xConfigFilePath." -i ".$wanif."\n";
  	}
	}	
}
else
{
	echo "echo \"Stop DHCP client on WAN(".$wanif.") ...\" > /dev/console\n";
	echo "if [ -f ".$dhcpc_pid." ]; then\n";
	echo "	PID=`cat ".$dhcpc_pid."`\n";
	echo "	if [ $PID != 0 ]; then\n";
	echo "		#prepare force deconfig of udhcpc\n";
	echo "		echo \"#!/bin/sh\"		> /var/run/wan_force_deconfig_udhcpc.sh\n";
	echo "		echo \"echo \\\"[\\$0] Waiting too long, forcing deconfig udhcpc ...\\\" > /dev/console \"	>> /var/run/wan_force_deconfig_udhcpc.sh\n";
	echo "		echo \"export interface=\\\"".$wanif."\\\"\""."	>> /var/run/wan_force_deconfig_udhcpc.sh\n";
	echo "		echo \"sh /var/run/udhcpc.sh deconfig\"		>> /var/run/wan_force_deconfig_udhcpc.sh\n";
	echo "		chmod +x /var/run/wan_force_deconfig_udhcpc.sh\n";
	echo "		#prepare timer to execute force script of udhcpc\n";
	echo "		xmldbc -t \"forceDefUDHCPC:15:/var/run/wan_force_deconfig_udhcpc.sh\"\n";
	echo "		kill -SIGUSR2 $PID > /dev/console 2>&1\n";
	echo "		while [ \"`xmldbc -i -g /runtime/wan/inf:1/connectstatus`\" = connected ]; do\n";
	echo "			echo \"[$0] waiting for WAN to be disconnected !!!\" > /dev/console\n";
	echo "			sleep 1\n";
	echo "		done\n";
	echo "		#remove timer of force script that for udhcpc\n";
	echo "		xmldbc -k forceDefUDHCPC\n";
	echo "		kill $PID > /dev/console 2>&1\n";
	echo "		rm -f /var/run/wan_force_deconfig_udhcpc.sh \n";
	echo "	fi\n";
	echo "	rm -f ".$dhcpc_pid."\n";
	echo "fi\n";
	
	if (query("/runtime/func/w8021x")=="1" && query("/w8021x/dhenable")!="on")
	{
		echo "echo 802.1x authentication is disabled ! > /dev/console\n";
	}
	else
	{	
		if(query("/runtime/func/w8021x")=="1")
		{
  		echo "echo \"Stop 802.1x Authentication on ".$wanif."...\" > /dev/console\n";
  		//tommy 05.11
			set("/runtime/w8021x/auth", "");
			///
  		echo "killall xsupplicant\n"; 
		}
	}

	/* force dhcpd restart */
	echo $template_root."/dhcpd.sh\n";

/*
	Remove this part, the cleaning up should be done in callback script.
	by David Hsieh.

	echo "ifconfig ".$wanif." 0.0.0.0 > /dev/console\n";
	echo "while route del default gw 0.0.0.0 dev ".$wanif." ; do\n";
	echo "	:\n";
	echo "done\n";
	echo "[ -f ".$template_root."/wandown.sh ] && ".$template_root."/wandown.sh\n";
	echo "rgdb -i -s /runtime/wan/inf:1/connectstatus disconnected\n";
	echo "rgdb -i -s /runtime/wan/inf:1/ip \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/netmask \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/gateway \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/primarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/secondarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/interface \"\"\n";
*/
	//kwest add to fix that when change WAN type from DHCP to PPTP manual, the connectstatus is still "connecting".
	echo "rgdb -i -s /runtime/wan/inf:1/connectstatus disconnected\n";
}
?># wan_dhcp <<<
