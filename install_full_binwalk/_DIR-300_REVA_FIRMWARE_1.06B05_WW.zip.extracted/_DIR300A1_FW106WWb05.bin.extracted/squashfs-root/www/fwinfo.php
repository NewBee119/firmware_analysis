var result = <?

$SIGNATURE		= get("j", "/runtime/sys/fwinfo/SIGNATURE");
$MAJVERSION		= get("j", "/runtime/sys/fwinfo/MAJVERSION");
$MINVERSION		= get("j", "/runtime/sys/fwinfo/MINVERSION");
$BUILDNUMBER	= get("j", "/runtime/sys/fwinfo/BUILDNUMBER");
$FIRMWAREURL	= get("j", "/runtime/sys/fwinfo/FIRMWAREURL");

?>new Array("OK", "<?=$SIGNATURE?>", "<?=$MAJVERSION?>", "<?=$MINVERSION?>", "<?=$BUILDNUMBER?>", "<?=$FIRMWAREURL?>");
