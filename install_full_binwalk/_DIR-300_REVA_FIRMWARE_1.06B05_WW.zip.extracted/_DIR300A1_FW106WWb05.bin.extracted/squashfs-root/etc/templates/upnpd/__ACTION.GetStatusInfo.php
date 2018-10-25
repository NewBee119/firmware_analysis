<NewConnectionStatus><?

	$errorCode=200;

    if ($ROUTER_ON==1) { map("/runtime/wan/inf:".$WID."/connectstatus","connected","Connected","*","Disconnected"); }
    else { echo "Connected"; }

?></NewConnectionStatus>
<NewLastConnectionError>ERROR_NONE</NewLastConnectionError>
<NewUptime><?

	$v1 = query("/runtime/sys/uptime");
	$v2 = query("/runtime/wan/inf:".$WID."/uptime");
	$uptime = $v1 - $v2;
	echo $uptime;

?></NewUptime>
