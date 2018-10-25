<?
/* vi: set sw=4 ts=4: */
$MY_NAME			="sys_fw_valid";
$MY_MSG_FILE		=$MY_NAME.".php";

$NO_NEED_AUTH		="1";
$NO_SESSION_TIMEOUT	="1";
$NO_BUTTON			="1";
require("/www/model/__html_head.php");
require("/www/model/__burn_time.php");
?>
<script>
<?
$fw_size=query("/runtime/sys/fw_size");
if($fw_size!="")	{ echo "var countdown=get_burn_time(".$fw_size.");\n"; }
else				{ echo "self.location.href=\"sys_fw_invalid.php\";\n"; }
?>
function init()
{
	nev();
}
function nev(){ countdown--; document.frm.WaitInfo.value=countdown;
if(countdown < 1 ) top.location.href='<?=$G_HOME_PAGE?>.php';
else setTimeout('nev()',1000);}
</script>
<?
$REQUIRE_FILE="__upgrading_fw_msg.php";
require("/www/model/__show_info.php");
?>
