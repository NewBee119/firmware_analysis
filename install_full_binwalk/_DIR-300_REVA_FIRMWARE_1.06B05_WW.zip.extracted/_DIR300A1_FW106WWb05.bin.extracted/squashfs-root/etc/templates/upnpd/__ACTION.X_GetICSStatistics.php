<TotalBytesSent><?

	if ($ROUTER_ON!=1) { echo "0"; }
	else { map("/runtime/stats/wan/inf:1/tx/bytes","","0"); }

?></TotalBytesSent>
<TotalBytesReceived><?

	if ($ROUTER_ON!=1) { echo "0"; }
	else { map("/runtime/stats/wan/inf:1/rx/bytes","","0"); }

?></TotalBytesReceived>
<TotalPacketsSent><?

	if ($ROUTER_ON!=1) { echo "0"; }
	else { map("/runtime/stats/wan/inf:1/tx/packets","","0"); }

?></TotalPacketsSent>
<TotalPacketsReceived><?

	if ($ROUTER_ON!=1) { echo "0"; }
	else { map("/runtime/stats/wan/inf:1/rx/packets","","0"); }

?></TotalPacketsReceived>
<Layer1DownstreamMaxBitRate>100000000</Layer1DownstreamMaxBitRate>
<Uptime><?

	if ($ROUTER_ON==1)
	{
		$wanuptime=query("/runtime/wan/inf:1/uptime");
		$sysuptime=query("/runtime/sys/uptime");
		$uptime=$sysuptime - $wanuptime;
	}
	else
	{
		$uptime = 0;
	}
	echo $uptime;

?></Uptime>
