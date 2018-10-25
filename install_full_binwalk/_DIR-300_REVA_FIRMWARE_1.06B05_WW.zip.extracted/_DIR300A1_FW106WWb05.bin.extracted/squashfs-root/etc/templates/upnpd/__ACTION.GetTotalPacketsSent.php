<NewTotalPacketsSent><?

if ($ROUTER_ON!=1) { echo "0"; }
else { map("/runtime/stats/wan/inf:1/tx/packets","","0"); }

?></NewTotalPacketsSent>
