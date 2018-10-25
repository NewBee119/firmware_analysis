<?
/* vi: set sw=4 ts=4:
 *
 * Get configuration
 */
$wanmode	= query("/wan/rg/inf:1/mode");
$lanif  	= query("/runtime/layout/lanif");
$lan2if		= query("/runtime/layout/lanif2");
$lan2ip  	= query("/gzone/ethernet/ip");
$lan2mask	= query("/gzone/ethernet/netmask");
$lanip  	= query("/lan/ethernet/ip");
$lanmask	= query("/lan/ethernet/netmask");
$wanif		= query("/runtime/wan/inf:1/interface");
$wanip		= query("/runtime/wan/inf:1/ip");
$wanstatus	= query("/runtime/wan/inf:1/connectstatus");

$wan2mode	= query("/wan/rg/inf:2/mode");
$wan2if		= query("/runtime/wan/inf:2/interface");
$wan2ip		= query("/runtime/wan/inf:2/ip");
$wan2status	= query("/runtime/wan/inf:2/connectstatus");

$wanphy		= query("/runtime/layout/wanif");

echo "# WAN1 mode:[".$wanmode. "], status:[".$wanstatus. "] ".$wanif. "/".$wanip."\n";
echo "# WAN2 mode:[".$wan2mode."], status:[".$wan2status."] ".$wan2if."/".$wan2ip."\n";
echo "# LAN: ".$lanif."/".$lanip."/".$lanmask."\n";
?>
