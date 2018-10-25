<?
if ($ROUTER_ON==1)	{ $ipaddr = query("/runtime/wan/inf:".$WID."/ip"); }
else				{ $ipaddr = query("/lan/ethernet/ip"); }
?><NewExternalIPAddress><?=$ipaddr?></NewExternalIPAddress>
