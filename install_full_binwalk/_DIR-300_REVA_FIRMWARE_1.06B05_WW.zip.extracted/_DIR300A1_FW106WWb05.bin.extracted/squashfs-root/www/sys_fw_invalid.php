<?
/* vi: set sw=4 ts=4: */
$MY_NAME	="sys_fw_invalid";
$MY_MSG_FILE=$MY_NAME.".php";

$NO_NEED_AUTH="1";
require("/www/model/__html_head.php");
?>
<script>
function init()
{
	var f=get_obj("frm");
	f.bt.focus();
}
function click_bt()
{
	history.go(-1);
}
</script>
<?
$USE_BUTTON="1";
require("/www/model/__show_info.php");
?>
