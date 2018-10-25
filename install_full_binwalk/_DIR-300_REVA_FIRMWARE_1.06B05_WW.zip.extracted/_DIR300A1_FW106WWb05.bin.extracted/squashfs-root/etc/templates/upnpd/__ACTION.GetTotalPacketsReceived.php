<NewTotalPacketsReceived><?

if ($ROUTER_ON!=1) { echo "0"; }
else { map("/runtime/stats/wan/inf:1/rx/packets","","0"); }

?></NewTotalPacketsReceived>
