var __result=<?
if(query("/runtime/wan/inf:1/connectstatus")=="connected" && query("/runtime/switch/wan_port/linktype")!="0")
{
	$conn_status="connected";
}
else if(query("/runtime/wan/inf:1/connecttype")=="3" 
		&& query("/runtime/wan/inf:1/connectstatus")=="connected" 
		&& query("/runtime/stats/usb/devices/driver")!="" )
{
	//this is 3g mode. set status to connected
	$conn_status="connected";
}
else
{
	$conn_status="disconnected";
}
?>new Array("OK", "<?=$conn_status?>");
