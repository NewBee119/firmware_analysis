# flush_main.php >>>
<? /* vi: set sw=4 ts=4: */ ?>
iptables -t mangle -F PREROUTING
iptables -t mangle -F POSTROUTING 
iptables -t nat -F PREROUTING
iptables -t nat -F POSTROUTING
iptables -F FORWARD
iptables -F INPUT
<?
anchor("/runtime/rgfunc");

/* Update main chains */

/* mangle PREROUTING */
if( $wanif != "" )
{ echo "iptables -t mangle -A PREROUTING -j TTL --ttl-inc 1\n"; }
if ($wanip!="")
{
	if(query("vrtsrv") > 0 || query("dmz") > 0)
	{
		echo "iptables -t mangle -A PREROUTING -i ".$lanif." -d ".$wanip." -j PRE_MARK\n";
		if($wan2if!="" && $wan2ip!="")
		{
			echo "iptables -t mangle -A PREROUTING -i ".$lanif." -d ".$wan2ip." -j PRE_MARK\n";
		}
	}
}

/* PREROUTINE */
if( query("/runtime/func/anti_spoof") == "1" && query("/security/arp/enable") == "1")
{
	echo "lan1=`echo ".$lanip." | cut -d. -f1`\n";
	echo "lan2=`echo ".$lanip." | cut -d. -f2`\n";
	echo "lan3=`echo ".$lanip." | cut -d. -f3`\n";
	echo "wan1=`echo ".$wanip." | cut -d. -f1`\n";
	echo "wan2=`echo ".$wanip." | cut -d. -f2`\n";
	echo "wan3=`echo ".$wanip." | cut -d. -f3`\n";
	echo "iptables -t nat -A PREROUTING -i ".$lanif." -s \$wan1.\$wan2.0.0/16 -j DROP\n";
	echo "iptables -t nat -A PREROUTING -i ".$wanif." -s \$lan1.\$lan2.\$lan3.0/24 -j DROP\n";
}
if (query("macfilter") > 0)
{
	echo "iptables -t nat -A PREROUTING -i ".$lanif." -j PRE_MACFILTER\n";
	echo "iptables -A FORWARD -j FOR_MACFILTER\n";
	echo "iptables -A INPUT -j INP_MACFILTER\n";
}
if(query("gzone/macfilter")>0)
{
	echo "iptables -t nat -A PREROUTING -i ".$lan2if." -j PRE_GZ_MACFILTER\n";
}
if ($wanip!="" && query("portt") > 0)
{
	echo "iptables -t nat -A PREROUTING -d ".$wanip." -j PRE_PORTT\n";
}
if($wan2if!="" && $wan2ip!="" && query("portt")>0)
{
	echo "iptables -t nat -A PREROUTING -d ".$wan2ip." -j PRE_PORTT\n";
}
if ($wanif!="" && query("/security/dos/enable")==1)
{
	echo "iptables -A FORWARD -i ".$wanif." -j DOS\n";
	echo "iptables -A FORWARD -i ".$wanif." -j SPI\n";
	echo "iptables -A INPUT -i ".$wanif." -j DOS\n";
	echo "iptables -A INPUT -i ".$wanif." -j SPI\n";

	if($wan2if!="" && $wan2ip!="")
	{
		echo "iptables -A FORWARD -i ".$wan2if." -j DOS\n";
		echo "iptables -A FORWARD -i ".$wan2if." -j SPI\n";
	}
}
/* Protecting form ARP Flood 
if (query("/security/attack/arp")==1)
{
	echo "echo 5 > /proc/sys/net/ipv4/arp_flood_burst\n";
	echo "echo 20 > /proc/sys/net/ipv4/arp_flood_cost\n";
}*/
if (query("vrtsrv") > 0)
{
	echo "iptables -t nat -A PREROUTING -j PRE_VRTSRV\n";
}

if (query("/upnp/enable") == 1)
{
	echo "iptables -t nat -A PREROUTING -j PRE_UPNP\n";
}

if (query("/nat/general/igmpproxy/enable") == 1)
{
	echo "iptables -t nat -A PREROUTING -j PRE_IGMP\n"; 
}

echo "iptables -t nat -A PREROUTING -j PRE_MISC\n"; 

if ($wanip!="" && query("dmz") > 0)
{
	echo "iptables -t nat -A PREROUTING -d ".$wanip." -j PRE_DMZ\n";
}
if($wan2ip!="" && query("dmz")>0)
{
	echo "iptables -t nat -A PREROUTING -d ".$wan2ip." -j PRE_DMZ\n";
}

/* echo "iptables -t nat -A PREROUTING -i ! ".$lanif." -j PRE_DEFAULT\n"; */
$CMDHEAD = "iptables -t nat -A PREROUTING -i "; $CMDTAIL = " -j PRE_DEFAULT\n";
if ($wanphy!="")					{ echo $CMDHEAD.$wanphy.$CMDTAIL;}
if ($wanphy!=$wanif && $wanif!="")	{ echo $CMDHEAD.$wanif.$CMDTAIL; }
if ($wanphy!=$wanif2 && $wanif2!=""){ echo $CMDHEAD.$wanif2.$CMDTAIL;}

/* FORWARD */
$wanif2=query("/runtime/wan/inf:2/interface");
if ($wanmode == 3)
{
	 if($wanif != "")
	 {
	 	$mss=query("/wan/rg/inf:1/pppoe/mtu");
		 if($mss=="")    { $mss=1400; }
	 	 else           { $mss=$mss-40; }
	 	echo "iptables -t mangle -A PREROUTING -i ".$wanif." -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
     	echo "iptables -t mangle -A PREROUTING -i br0 -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
	 }
	 if($wanif2 != "")
	 {
	 	$mss=query("/wan/rg/inf:2/pppoe/mtu");
		if($mss=="")    { $mss=1400; }
	 	else           { $mss=$mss-40; }
	 	echo "iptables -t mangle -A PREROUTING -i ".$wanif2." -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
     	echo "iptables -t mangle -A PREROUTING -i br1 -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
	 }
	 				
 }
if ($wanmode == 4 )
{
     if($wanif != "")
	 {
	 	$mss=query("/wan/rg/inf:1/pptp/mtu");
     	if($mss=="")    { $mss=1400; }
     	else           { $mss=$mss-40; }
     	echo "iptables -t mangle -A PREROUTING -i ".$wanif." -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
     	echo "iptables -t mangle -A PREROUTING -i br0 -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
	 }
     if($wanif2 != "")
	 {
	 	$mss=query("/wan/rg/inf:2/pptp/mtu");
     	if($mss=="")    { $mss=1400; }
     	else           { $mss=$mss-40; }
     	echo "iptables -t mangle -A PREROUTING -i ".$wanif2." -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
     	echo "iptables -t mangle -A PREROUTING -i br1 -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
	 }

 }
if ($wanmode == 5 )
{
     if($wanif != "")
	 {
	 	$mss=query("/wan/rg/inf:1/l2tp/mtu");
     	if($mss=="")    { $mss=1400; }
     	else           { $mss=$mss-40; }
     	echo "iptables -t mangle -A PREROUTING -i ".$wanif." -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
     	echo "iptables -t mangle -A PREROUTING -i br0 -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
	 }
}
if ($wanmode == 6)
{
     if($wanif != "")
	 {
	 	$mss=query("/wan/rg/inf:1/ppp3g/mtu");
     	if($mss=="")    { $mss=1400; }
     	else           { $mss=$mss-40; }
     	echo "iptables -t mangle -A PREROUTING -i ".$wanif." -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
     	echo "iptables -t mangle -A PREROUTING -i br0 -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
	 }
	 if($wanif2 != "")
	 {
	 	$mss=query("/wan/rg/inf:2/ppp3g/mtu");
		if($mss=="")    { $mss=1400; }
	 	else           { $mss=$mss-40; }
	 	echo "iptables -t mangle -A PREROUTING -i ".$wanif2." -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
     	echo "iptables -t mangle -A PREROUTING -i br1 -p tcp --tcp-flags SYN SYN -m tcpmss --mss ".$mss.":1500 -j TCPMSS --set-mss ".$mss."\n";
	 }
}	


if ($wanif!="" && query("portt") > 0)
{	echo "iptables -A FORWARD -o ".$wanif." -j FOR_PORTT\n"; }
if($wan2if!="" && query("portt")>0)
{	echo "iptables -A FORWARD -o ".$wan2if." -j FOR_PORTT\n"; }

if (query("firewall") > 0) 
{ echo "iptables -A FORWARD -j FOR_FIREWALL\n"; }

if (query("ipfilter") > 0)
{ echo "iptables -A FORWARD -j FOR_IPFILTER\n"; }

if (query("vrtsrv") > 0 || query("dmz") > 0 || query("upnp") > 0)
{ echo "iptables -A FORWARD -j FOR_DNAT\n"; }

if (query("urlfilter") > 0 && query("/runtime/func/policy") != "1")
{ echo "iptables -A FORWARD -i ".$lanif." -p tcp --dport 80 -j FOR_BLOCKING\n"; }

if(query("/runtime/rgfunc/domainfilter")>0)
{ echo "iptables -A FORWARD -i ".$lanif." -p udp --dport 53 -j FOR_BLOCKING\n";
  echo "iptables -A INPUT -i "  .$lanif." -p udp --dport 53 -j INP_BLOCKING\n"; }

if (query("vpn") > 0)
{ echo "iptables -A FORWARD -j FOR_VPN\n"; }

if(query("policy") > 0)
{ echo "iptables -A FORWARD -j FOR_POLICY\n"; }

/* POSTROUTING */
if (query("vrtsrv") > 0 || query("dmz") > 0)
{ 
	echo "iptables -t nat -A POSTROUTING -m mark --mark 1 -j PST_VRTSRV\n";
	if($wan2if!="" && $wan2ip!="")
	{
		echo "iptables -t nat -A POSTROUTING -m mark --mark 2 -j PST_VRTSRV2\n";
	}
}

/* POSTROUTING MASQUERADE */
$use_stun = query("/runtime/func/stun/enabled");
$stun_tcp_type = query("/stun/type/tcp");
$stun_udp_type = query("/stun/type/udp");
$stun_type= query("/runtime/func/stun/type");
if ($use_stun==1 && $stun_tcp_type>0 && $stun_tcp_type<5) { $MASQ_tcp_target = " -p tcp -j STUN --type ".$stun_tcp_type; }

if ($use_stun==1 && $stun_udp_type>0 && $stun_udp_type<5) { $MASQ_udp_target = " -p udp -j STUN --type ".$stun_udp_type; }

if ($use_stun==1 && $stun_type>0 && $stun_type<5) { $MASQ_target = "STUN --type ".$stun_type; }
else { $MASQ_target = "MASQUERADE"; }

if ($wanif!="" && $MASQ_tcp_target!="") { echo "iptables -t nat -A POSTROUTING -o ".$wanif.$MASQ_tcp_target."\n"; }
if ($wan2if!="" && $MASQ_tcp_target!=""){ echo "iptables -t nat -A POSTROUTING -o ".$wan2if.$MASQ_tcp_target."\n"; }

if ($wanif!="" && $MASQ_udp_target!="") { echo "iptables -t nat -A POSTROUTING -o ".$wanif.$MASQ_udp_target."\n"; }
if ($wan2if!="" && $MASQ_udp_target!=""){ echo "iptables -t nat -A POSTROUTING -o ".$wan2if.$MASQ_udp_target."\n"; }

if ($wanif!="")	{ echo "iptables -t nat -A POSTROUTING -o ".$wanif." -j ".$MASQ_target."\n"; }
/* Add the MASQUERADE rule for other wan interface. */
if ($wan2if!=""){ echo "iptables -t nat -A POSTROUTING -o ".$wan2if." -j ".$MASQ_target."\n"; }


/* Fast NAT */
echo "echo -1 > /proc/fastnat/forskipsupport\n";
if (query("urlfilter") > 0)		{ echo "echo 80 > /proc/fastnat/forskipsupport\n"; }
if (query("domainfilter") > 0)	{ echo "echo 53 > /proc/fastnat/forskipsupport\n"; }

/* netsniper */
require($template_root."/rg/flush_netsniper.php");

/* For pass https://www.grc.com/x/ne.dll?bh0bkyd2 test */
echo "iptables -I INPUT -p TCP --dport 1 -j DROP\n";
echo "iptables -I INPUT -p TCP --dport 0 -j DROP\n";
?>
# flush_main.php <<<
