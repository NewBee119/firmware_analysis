#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */

$lanip = query("/lan/ethernet/ip");
$mask  = query("/lan/ethernet/netmask");

echo "# DHCP server ...\n";
echo "IP=`chnet ".query("/lan/dhcp/server/pool:1/startip")." ".$lanip." ".$mask."`\n";
echo "rgdb -s /lan/dhcp/server/pool:1/startip \"$IP\"\n";
echo "IP=`chnet ".query("/lan/dhcp/server/pool:1/endip")." ".$lanip." ".$mask."`\n";
echo "rgdb -s /lan/dhcp/server/pool:1/endip \"$IP\"\n";

echo "# Virtual Server ...\n";
for ("/nat/vrtsrv/entry")
{
	$priv_ip = query("privateip");
	if ($priv_ip!="0.0.0.0" && $priv_ip!="")
	{
		echo "IP=`chnet ".$priv_ip." ".$lanip." ".$mask."`\n";
		echo "rgdb -s /nat/vrtsrv/entry:".$@."/privateip \"$IP\"\n";
	}
}

echo "# Static DHCP ...\n";
for ("/lan/dhcp/server/pool:1/staticdhcp/entry")
{
	$ipaddr = query("ip");
	if ($ipaddr!="0.0.0.0" && $ipaddr!="")
	{
		echo "IP=`chnet ".$ipaddr." ".$lanip." ".$mask."`\n";
		echo "rgdb -s /lan/dhcp/server/pool:1/staticdhcp/entry:".$@."/ip \"$IP\"\n";
	}
}

echo "# DMZ ...\n";
$ipaddr = query("/nat/dmzsrv/ipaddr");
if ($ipaddr!="0.0.0.0" && $ipaddr!="")
{
	echo "IP=`chnet ".query("/nat/dmzsrv/ipaddress")." ".$lanip." ".$mask."`\n";
	echo "rgdb -s /nat/dmzsrv/ipaddress \"$IP\"\n";
}

?>
