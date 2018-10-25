var result =<?
$status		= query("/runtime/ddns/status");
$errormsg	= query("/runtime/ddns/errormsg");
$uptime		= query("/runtime/ddns/uptime");
$ipaddr		= query("/runtime/ddns/ipaddr");
$provider	= query("/runtime/ddns/provider");
?>new Array("<?=$status?>", "<?=$errormsg?>", "<?=$uptime?>", "<?=$ipaddr?>", "<?=$provider?>");
