var result = <?

anchor("/runtime/wan/inf:1");

$status		= query("connectstatus");
$ipaddr		= query("ip");
$subnet		= query("netmask");
$gateway	= query("gateway");
$dns		= query("primarydns")."&nbsp;".query("secondarydns");

anchor("/runtime/w8021x");

$w8021x_status          = query("auth");

if($status == "connecting" || $status == "disconnecting")
{
	$rtcode = "OK";
}
else
{
	$rtcode	= "OK";
}

?>new Array("<?=$rtcode?>", "<?=$status?>", "<?=$ipaddr?>", "<?=$subnet?>", "<?=$gateway?>", "<?=$dns?>", "<?=$w8021x_status?>");
