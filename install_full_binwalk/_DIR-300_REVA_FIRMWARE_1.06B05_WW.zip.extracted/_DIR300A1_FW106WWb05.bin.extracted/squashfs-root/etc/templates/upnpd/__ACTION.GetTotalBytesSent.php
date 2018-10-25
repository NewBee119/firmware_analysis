<NewTotalBytesSent><?

if ($ROUTER_ON!=1) { echo "0"; }
else { map("/runtime/stats/wan/inf:1/tx/bytes","","0"); }

?></NewTotalBytesSent>
