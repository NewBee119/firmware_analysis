<?
/*
 * vi: set sw=4 ts=4:
 *
 * Need the following variables:
 * $clonemac	- target mac address
 * $curr_wanmac	- current active mac address
 * $orig_wanmac	- permanent mac address
 * $wanif		- interface name of the WAN port.
 */
if ($clonemac != "")
{
	if ($curr_wanmac != $clonemac)
	{
		echo "ifconfig ".$wanif." down\n";
		echo "ifconfig ".$wanif." hw ether ".$clonemac." up\n";
		echo "rgdb -i -s /runtime/wan/inf:1/mac ".$clonemac."\n";
		echo "echo \"Clone WAN MAC : ".$clonemac."\" > /dev/console\n";
	}
}
else
{
	if ($curr_wanmac != $orig_wanmac && $orig_wanmac != "")
	{
		echo "ifconfig ".$wanif." down\n";
		echo "ifconfig ".$wanif." hw ether ".$orig_wanmac." up\n";
		echo "rgdb -i -s /runtime/wan/inf:1/mac ".$orig_wanmac."\n";
		echo "echo \"Restore WAN MAC : ".$orig_wanmac."\" > /dev/console\n";
	}
}
?>
