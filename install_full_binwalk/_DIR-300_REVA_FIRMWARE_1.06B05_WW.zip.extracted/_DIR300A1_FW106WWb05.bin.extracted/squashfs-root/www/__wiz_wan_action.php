<?
/* vi: set sw=4 ts=4: */
echo "<!--\n";
echo "ACTION_POST=".$ACTION_POST."\n";
if ($ACTION_POST=="1_password")
{
	echo "password=".$password."\n";
	if ($password != $G_DEF_PASSWORD)
	{
		set($G_WIZ_PREFIX_WAN."/password", $password);
	}
}
else if ($ACTION_POST=="2_timezone")
{
	echo "tzone=".$tzone."\n";
	echo "ntp_server=".$ntp_server."\n";
	set($G_WIZ_PREFIX_WAN."/timezone", $tzone);
	set($G_WIZ_PREFIX_WAN."/ntpserver", $ntp_server);

}
else if ($ACTION_POST=="3_sel_wan")
{
	echo "wan_type=".$wan_type."\n";
	set($G_WIZ_PREFIX_WAN."/wan_type", $wan_type);
	if      ($wan_type == 2)			{$WIZ_NEXT="4_dhcp";}
	else if ($wan_type == 3)			{$WIZ_NEXT="4_pppoe";}
	else if ($wan_type == 4)			{$WIZ_NEXT="4_pptp";}
	else if ($wan_type == 5)			{$WIZ_NEXT="4_l2tp";}
	else if ($wan_type == 1)			{$WIZ_NEXT="4_fixed";}
	else if ($wan_type == "r_pptp")		{$WIZ_NEXT="4_pptp";}
	else if ($wan_type == "r_l2tp")     {$WIZ_NEXT="4_l2tp";}
	else if ($wan_type == "r_pppoe")	{$WIZ_NEXT="4_pppoe";}
	else								{$WIZ_NEXT="4_dhcp";}
}
else if ($ACTION_POST=="4_fixed")
{
	echo "ipaddr=".$ipaddr."\n";
	echo "netmask=".$netmask."\n";
	echo "gateway=".$gateway."\n";
	echo "dns1=".$dns1."\n";
	echo "dns2=".$dns2."\n";
	set($G_WIZ_PREFIX_WAN."/static/ipaddr", $ipaddr);
	set($G_WIZ_PREFIX_WAN."/static/netmask", $netmask);
	set($G_WIZ_PREFIX_WAN."/static/gateway", $gateway);
	set($G_WIZ_PREFIX_WAN."/static/dns1", $dns1);
	set($G_WIZ_PREFIX_WAN."/static/dns2", $dns2);
}
else if ($ACTION_POST=="4_dhcp")
{
	echo "hostname=".$hostname."\n";
	echo "clonemac=".$clonemac."\n";
	set($G_WIZ_PREFIX_WAN."/dhcp/hostname", $hostname);
	set($G_WIZ_PREFIX_WAN."/dhcp/clonemac", $clonemac);
}
else if ($ACTION_POST=="4_pppoe")
{
	echo "mode=".$mode."\n";
	echo "ipaddr=".$ipaddr."\n";
	echo "username=".$username."\n";
	echo "password=".$password."\n";
	echo "svc_name=".$svc_name."\n";

	anchor($G_WIZ_PREFIX_WAN."/pppoe");
	set("mode", $mode);
	set("ipaddr", $ipaddr);
	set("username", $username);
	if ($G_DEF_PASSWORD != $password) {set("password", $password);}
	set("svc_name", $svc_name);
	
	if (query($G_WIZ_PREFIX_WAN."/wan_type")=="r_pppoe")
	{
		echo "phy_mode=".$phy_mode."\n";
		echo "phy_ip="	.$phy_ip."\n";
		echo "phy_mask=".$phy_mask."\n";
		echo "phy_gw="	.$phy_gw."\n";
		echo "phy_dns1=".$phy_dns1."\n";
		echo "phy_dns2=".$phy_dns2."\n";

		set($G_WIZ_PREFIX_WAN."/wan/rg/inf:2/mode", $phy_mode);
		if ($phy_mode=="1")
		{
			anchor($G_WIZ_PREFIX_WAN."/wan/rg/inf:2/static");
			set("ip", $phy_ip);
			set("netmask",	$phy_mask);
			set("gateway",	$phy_gw);
			set("primarydns",	$phy_dns1);
			set("secondarydns",	$phy_dns2);
		}
	}
}
else if ($ACTION_POST=="4_pptp")
{
	echo "mode=".$mode."\n";
	echo "ipaddr=".$ipaddr."\n";
	echo "netmask=".$netmask."\n";
	echo "gateway=".$gateway."\n";
	echo "server=".$server."\n";
	echo "username=".$username."\n";
	echo "password=".$password."\n";

	anchor($G_WIZ_PREFIX_WAN."/pptp");
	set("mode", $mode);
	set("ipaddr", $ipaddr);
	set("netmask", $netmask);
	set("gateway", $gateway);
	set("server", $server);
	set("username", $username);
	if ($G_DEF_PASSWORD != $password) {set("password", $password);}
}
else if ($ACTION_POST=="4_l2tp")
{
	echo "mode=".$mode."\n";
	echo "ipaddr=".$ipaddr."\n";
	echo "netmask=".$netmask."\n";
	echo "gateway=".$gateway."\n";
	echo "server=".$server."\n";
	echo "username=".$username."\n";
	echo "password=".$password."\n";

	anchor($G_WIZ_PREFIX_WAN."/l2tp");
	set("mode", $mode);
	set("ipaddr", $ipaddr);
	set("netmask", $netmask);
	set("gateway", $gateway);
	set("server", $server);
	set("username", $username);
	if ($G_DEF_PASSWORD != $password) {set("password", $password);}
}
else if ($ACTION_POST=="5_saving")
{
	/* password */
	anchor($G_WIZ_PREFIX_WAN);
	$password	= query("password");
	$timezone	= query("timezone");
	$ntpserver	= query("ntpserver");
	$wantype	= query("wan_type");
	if ($password != $G_DEF_PASSWORD)
	{
		set("/sys/user:1/password", $password);
		echo "set password=".$password."\n";
	}
	set("/time/timezone", $timezone);
	set("/time/syncwith", 2);
	set("/time/ntpserver/ip", $ntpserver);
	echo "set timezone=".$timezone."\n";
	echo "wantype=".$wantype."\n";
	if ($wantype == "1")
	{
		anchor($G_WIZ_PREFIX_WAN."/static");
		$ipaddr		= query("ipaddr");
		$netmask	= query("netmask");
		$gateway	= query("gateway");
		$dns1		= query("dns1");
		$dns2		= query("dns2");
		set("/wan/rg/inf:1/mode", "1");
		$entry = "/wan/rg/inf:1/static/";
		set($entry."ip", $ipaddr);
		set($entry."netmask", $netmask);
		set($entry."gateway", $gateway);
		$entry = "/dnsrelay/server/";
		set($entry."primarydns", $dns1);
		set($entry."secondarydns", $dns2);
	}
	else if ($wantype == "2")
	{
		anchor($G_WIZ_PREFIX_WAN."/dhcp");
		$hostname	= query("hostname");
		$clonemac	= query("clonemac");
		set("/wan/rg/inf:1/mode", "2");
		$entry="/wan/rg/inf:1/dhcp/";
		set("/sys/hostname", $hostname);
		set($entry."clonemac", $clonemac);
	}
	else if ($wantype == "3" || $wantype=="r_pppoe")
	{
		anchor($G_WIZ_PREFIX_WAN."/pppoe");
		$mode		= query("mode");
		$ipaddr		= query("ipaddr");
		$username	= query("username");
		$password	= query("password");
		$svc_name	= query("svc_name");
		set("/wan/rg/inf:1/mode", "3");
		$entry="/wan/rg/inf:1/pppoe/";
		set($entry."mode", $mode);
		if ($mode == "1")
		{
			set($entry."staticip", $ipaddr);
			set($entry."autodns", "0");
		}
		set($entry."user", $username);
		if ($password != $G_DEF_PASSWORD) {set($entry."password", $password);}
		set($entry."acservice", $svc_name);
		if ($wantype=="r_pppoe")
		{
			$phy_mode=query($G_WIZ_PREFIX_WAN."/wan/rg/inf:2/mode");
			set("/wan/rg/inf:2/mode", $phy_mode);
			echo "phy_mode="		.$phy_mode."\n";
			if ($phy_mode=="1")	//static
			{
				anchor($G_WIZ_PREFIX_WAN."/wan/rg/inf:2/static");
				$phy_ip		=query("ip");
				$phy_mask	=query("netmask");
				$phy_gw		=query("gateway");
				$phy_dns1	=query("primarydns");
				$phy_dns2	=query("secondarydns");

				echo "phy_ip="		.$phy_ip."\n";
				echo "phy_mask="	.$phy_mask."\n";
				echo "phy_gw="		.$phy_gw."\n";
				echo "phy_dns1="	.$phy_dns1."\n";
				echo "phy_dns2="	.$phy_dns2."\n";

				$entry="/wan/rg/inf:2/static/";
				set($entry."ip",			$phy_ip);
				set($entry."netmask",		$phy_mask);
				set($entry."gateway",		$phy_gw);
				set($entry."primarydns",	$phy_dns1);
				set($entry."secondarydns",	$phy_dns2);
			}
			else	//DHCP
			{
				del("/wan/rg/inf:2/static");
			}
		}
	}
	else if ($wantype == "4" || $wantype=="r_pptp")
	{
		anchor($G_WIZ_PREFIX_WAN."/pptp");
		$mode		= query("mode");
		$ipaddr		= query("ipaddr");
		$netmask	= query("netmask");
		$gateway	= query("gateway");
		$server		= query("server");
		$username	= query("username");
		$password	= query("password");
		set("/wan/rg/inf:1/mode", "4");
		$entry="/wan/rg/inf:1/pptp/";
		set($entry."mode", $mode);
		if ($mode == "1")
		{
			set($entry."ip", $ipaddr);
			set($entry."netmask", $netmask);
			set($entry."gateway", $gateway);
		}
		set($entry."serverip", $server);
		set($entry."user", $username);
		if ($password != $G_DEF_PASSWORD) {set($entry."password", $password);}
		if ($wantype=="r_pptp")	{set($entry."physical", "1");}
		else					{del($entry."physical");}
	}
	else if ($wantype == "5"|| $wantype=="r_l2tp")
	{
		anchor($G_WIZ_PREFIX_WAN."/l2tp");
		$mode		= query("mode");
		$ipaddr		= query("ipaddr");
		$netmask	= query("netmask");
		$gateway	= query("gateway");
		$server		= query("server");
		$username	= query("username");
		$password	= query("password");
		set("/wan/rg/inf:1/mode", "5");
		$entry="/wan/rg/inf:1/l2tp/";
		set($entry."mode", $mode);
		if ($mode == "1")
		{
			set($entry."ip", $ipaddr);
			set($entry."netmask", $netmask);
			set($entry."gateway", $gateway);
		}
		set($entry."serverip", $server);
		set($entry."user", $username);
		if ($password != $G_DEF_PASSWORD) {set($entry."password", $password);}
		if ($wantype=="r_l2tp")	{set($entry."physical", "1");}
		else					{del($entry."physical");}

	}
}
echo "-->\n";
?>
